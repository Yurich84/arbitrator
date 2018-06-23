<?php

$environmentPath = __DIR__.'/../.env';

if (file_exists($environmentPath)){
    $setEnv = trim(file_get_contents($environmentPath));
    putenv("APP_ENV=$setEnv");

    $dotenv = new Dotenv\Dotenv(__DIR__.'/../', '.'.getenv('APP_ENV').'.env');
    $dotenv->overload();
}
