<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'kynda.nickfairchild.website');
set('local_url', 'kynda.test');
set('site', 'website');
set('db_name', '{{site}}.sql');

// Project repository
set('repository', 'git@github.com:nickfairchild/kynda.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server
set('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts
host('143.110.165.159')
    ->user('forge')
    ->stage('staging')
    ->set('deploy_path', '~/{{application}}');

// Tasks
desc('Setup Theme');
task('deploy:setup_theme', function () {
    cd("{{deploy_path}}/public/wp-content/themes/{{site}}");
    run("composer install");
});

desc('Backup Remote DB');
task('deploy:backup_db', function () {
    cd("{{deploy_path}}");
    run("wp db export backup.sql");
});

desc('Transfer Local DB to remote');
task('deploy:transfer_db', function () {
    runLocally("wp db export {{db_name}}");
    upload("{{db_name}}", "{{deploy_path}}");
});

desc('Import DB');
task('deploy:import_db', function () {
    cd("{{deploy_path}}");
    run("wp db import {{db_name}}");
    run("wp search-replace '{{local_url}}' '{{application}}'");
});

desc('Transfer Assets');
task('deploy:transfer_assets', function () {
    upload("public/wp-content/uploads", "{{deploy_path}}/public/wp-content/");
});

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:setup_theme',
    'deploy:backup_db',
    'deploy:transfer_db',
    'deploy:import_db',
    'deploy:transfer_assets',
    'success'
]);

task('pull:info', function () {
    writeln("Pulling <fg=magenta;options=bold>{{application}}</>");
});

desc('Backup Local DB');
task('pull:backup_db', function () {
    runLocally("wp db export backup.sql");
});

desc('Transfer remote DB to local');
task('pull:transfer_db', function () {
    cd("{{deploy_path}}");
    run("wp db export {{db_name}}");
    download("{{deploy_path}}/{{db_name}}", ".");
});

desc('Import DB');
task('pull:import_db', function () {
    runLocally("wp db import {{db_name}}");
    runLocally("wp search-replace '{{application}}' '{{local_url}}'");
});

desc('Transfer Assets');
task('pull:transfer_assets', function () {
    download("{{deploy_path}}/public/wp-content/uploads", "public/wp-content/");
});

desc('Pull your project');
task('pull', [
    'pull:info',
    'pull:backup_db',
    'pull:transfer_db',
    'pull:import_db',
    'pull:transfer_assets',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
