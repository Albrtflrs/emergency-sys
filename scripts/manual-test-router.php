<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

if (PHP_SAPI !== 'cli-server') {
    http_response_code(404);
    exit;
}

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$publicFile = dirname(__DIR__) . '/public' . $path;
if ($path !== '/' && is_file($publicFile)) {
    return false;
}

define('LARAVEL_START', microtime(true));
require dirname(__DIR__) . '/vendor/autoload.php';
Dotenv\Dotenv::createMutable(dirname(__DIR__), '.env.testing')->safeLoad();
putenv('SESSION_DRIVER=file');
$_ENV['SESSION_DRIVER'] = 'file';
$_SERVER['SESSION_DRIVER'] = 'file';
putenv('APP_URL=http://127.0.0.1:8765');
$_ENV['APP_URL'] = 'http://127.0.0.1:8765';
$_SERVER['APP_URL'] = 'http://127.0.0.1:8765';

/** @var Application $app */
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->loadEnvironmentFrom('.env.testing');
$app->make(Illuminate\Contracts\Http\Kernel::class)->bootstrap();
Illuminate\Support\Facades\Facade::clearResolvedInstances();
Illuminate\Support\Facades\Facade::setFacadeApplication($app);
$app->make('db')->purge();

if (! app()->environment('testing') || config('database.connections.mysql.database') !== 'emergency_sys_test') {
    http_response_code(500);
    echo 'Manual test server refused to start because it is not connected to the isolated test database.';
    exit;
}

$app->handleRequest(Request::capture());
