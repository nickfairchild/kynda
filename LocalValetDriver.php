<?php

class LocalValetDriver extends BasicValetDriver
{
    public function serves($sitePath, $siteName, $uri)
    {
        return (is_dir($sitePath.'/public/wp/') &&
            file_exists($sitePath.'/public/wp-config.php'));
    }

    public function isStaticFile($sitePath, $siteName, $uri)
    {
        $staticFilePath = $sitePath.'/public'.$uri;

        if ($this->isActualFile($staticFilePath)) {
            return $staticFilePath;
        }

        return false;
    }

    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        $_SERVER['PHP_SELF']    = $uri;
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        if (strpos($uri, '/wp/') === 0) {
            return is_dir($sitePath . '/public' . $uri)
                ? $sitePath . '/public' . $this->forceTrailingSlash($uri) . '/index.php'
                : $sitePath . '/public' . $uri;
        }

        return $sitePath . '/public/index.php';
    }

    private function forceTrailingSlash($uri)
    {
        if (substr($uri, -1 * strlen('/wp/wp-admin')) == '/wp/wp-admin') {
            header('Location: ' . $uri . '/');
            die;
        }

        return $uri;
    }
}
