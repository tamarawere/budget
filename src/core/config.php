<?php

declare(strict_types=1);

namespace Budget\Core;

return [
    'env' => 'dev',
    'host' => 'http://localhost/',
    'base_path' => '/apps/budget/be/php/v2/',
    'app_headers' => [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Headers' => '*',
        'Content-Type' => 'application/json; charset=UTF-8',
        'Access-Control-Allow-Methods' => 'OPTIONS,GET,POST,PUT,DELETE',
        'Access-Control-Allow-Headers' => 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With',
        'X-App-Name' => 'Budgeting Application'
    ],
    'db' => [
        'dbname' => 'bug',
        'user' => 'bug',
        'password' => '123',
        'host' => '9.9.9.4',
        'port' => '5432',
        'driver' => 'pdo_pgsql',
    ],
    'modules' => [
        'home', 
        'core',
        'categories', 
        'auth', 
        'groups',
        'items',
        'lots',
        'modes',
        'places',
        'transactions',
        'uom',
        'users',
    ],
    // App Timezone
    'tz' => 'Asia/Jerusalem',
    'twig' => [
        'template' => 'web/assets/template/files/',
        'css' => 'web/assets/css/',
        'js' => '',
        'img' => '',
    ]
];