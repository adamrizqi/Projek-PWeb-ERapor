<?php

// Load Composer's autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load The Application
$app = require_once __DIR__ . '/../bootstrap/app.php';

$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
}
$app->useStoragePath($storagePath);

// Handle The Request
$request = Illuminate\Http\Request::capture();
$response = $app->handle($request);
$response->send();
$app->terminate();
