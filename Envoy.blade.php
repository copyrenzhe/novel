@include('envoy.config.php')
@setup
    if( !isset($app_name) ) {
        throw new Exception('App Name is not set');
    }

    if( !isset($server_connections) ) {
        throw new Exception('Server connection is not set');
    }

    if ( ! isset($deploy_basepath) ) {
        throw new Exception('Base Path is not set');
    }

    $envoy_alias = [];
    $envoy_connections = [];
    foreach($server_connections as $alias => $connection) {
        $envoy_alias[] = $alias;
        $envoy_connections[] = $connection;
    }
    $envoy_servers = array_merge(['local'=>'localhost'], $server_connections);

    $app_dir = $deploy_basepath .'/'. $app_name;
@endsetup

@servers($envoy_servers)

@macro('deploy')
    git
    composer
    gulp
    clear_cache
    restart_queue
    compile_cache
@endmacro

@macro('help')
    show_cmd_list
@endmacro

@task('show_cmd_list', ['on' => 'local'])
    echo '================';
    echo '---- [macro command] ----';
    echo 'deploy';
    echo 'help';
    echo '---- [common list] ----';
    echo 'git';
    echo 'composer';
    echo '---- [cache list] ----';
    echo 'clear_cache';
    echo 'compile_cache';
    echo '---- [queue list] ----';
    echo 'restart_queue';
    echo '---- [snatch list] ----';
    echo 'update_hot';
    echo 'update';
    echo 'sum_chapter'
    echo '================';
@endtask

@task('git', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    git pull
@endtask

@task('composer', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    composer install
@endtask

@task('gulp', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    npm install
    gulp
@endtask

@task('clear_cache', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    php artisan config:clear
    php artisan route:clear
    php artisan clear-compiled
@endtask

@task('compile_cache', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    php artisan config:cache
    php artisan route:cache
    php artisan optimize --force
@endtask

@task('restart_queue', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    php artisan queue:restart
@endtask

@task('update_hot', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    php artisan snatch:updateHot 50 --queue
@endtask

@task('update', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    php artisan snatch:update --queue
@endtask

@task('sum_chapter', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    php artisan sum:chapter --queue
@endtask

@task('gulp', ['on' => $envoy_alias])
    cd {{ $app_dir }}
    gulp --production
@endtask