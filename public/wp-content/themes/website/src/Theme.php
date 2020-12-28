<?php

namespace App;

use App\Config\Repository;
use App\Container\Container;
use App\Contracts\Application;
use App\Contracts\Config;
use App\Foundation\ProviderRepository;
use App\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Theme extends Container implements Application
{
    protected string $basePath;

    protected bool $hasBeenBootstrapped = false;

    protected bool $booted = false;

    protected array $serviceProviders = [];

    protected array $loadedProviders = [];

    public function __construct(?string $basePath = null)
    {
        if (! is_null($basePath)) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();
        $this->registerCoreContainerAliases();
    }

    protected function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
    }

    public function bootstrapWith(array $bootstrappers): void
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    public function hasBeenBootstrapped(): bool
    {
        return $this->hasBeenBootstrapped;
    }

    public function setBasePath(string $basePath): self
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }

    protected function bindPathsInContainer(): void
    {
        $this->instance('path.base', $this->basePath());
        $this->instance('path.config', $this->configPath());
    }

    public function basePath(string $path = ''): string
    {
        return $this->basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function configPath(string $path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'config'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function registerConfiguredProviders()
    {
        $providers = Collection::make($this->config['app.providers'])
            ->partition(function ($provider) {
                return strpos($provider, 'App\\') === 0;
            });

        (new ProviderRepository($this))
            ->load($providers->collapse()->toArray());
    }

    public function register($provider, bool $force = false)
    {
        if (($registered = $this->getProvider($provider)) && ! $force) {
            return $registered;
        }

        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        $provider->register();

        if (property_exists($provider, 'bindings')) {
            foreach ($provider->bindings as $key => $value) {
                $this->bind($key, $value);
            }
        }

        if (property_exists($provider, 'singletons')) {
            foreach ($provider->singletons as $key => $value) {
                $this->singleton($key, $value);
            }
        }

        $this->markAsRegistered($provider);

        if ($this->isBooted()) {
            $this->bootProvider($provider);
        }

        return $provider;
    }

    public function getProvider($provider)
    {
        return array_values($this->getProviders($provider))[0] ?? null;
    }

    public function getProviders($provider): array
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        return Arr::where($this->serviceProviders, function ($value) use ($name) {
            return $value instanceof $name;
        });
    }

    public function resolveProvider($provider)
    {
        return new $provider($this);
    }

    protected function markAsRegistered($provider): void
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }

    public function boot()
    {
        if ($this->isBooted()) {
            return;
        }

        array_walk($this->serviceProviders, function ($p) {
            $this->bootProvider($p);
        });

        $this->booted = true;
    }

    protected function bootProvider(ServiceProvider $provider)
    {
        $provider->callBootingCallbacks();

        if (method_exists($provider, 'boot')) {
            $this->call([$provider, 'boot']);
        }

        $provider->callBootedCallbacks();
    }

    public function registerCoreContainerAliases(): void
    {
        foreach ([
                     'app' => [
                         self::class, \App\Contracts\Container::class, \App\Contracts\Application::class,
                         \Psr\Container\ContainerInterface::class
                     ],
                     'config' => [Repository::class, Config::class]
                 ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }

    public function autoloadDirectory(string $directory): void
    {
        collect(glob($directory.'/*.php'))
            ->each(fn($file) => include_once $file);
    }
}
