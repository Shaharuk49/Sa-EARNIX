<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Illuminate\Support\Facades\Auth::loginUsingId(1);
$request = Illuminate\Http\Request::create('/bonus', 'GET');
$response = $app->handle($request);
echo $response->getStatusCode();
