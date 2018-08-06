<?php

return [
    'debug' => true,
    'users' => ['admin' => 'password'],
    'per_page' => 10,
    'db' => [
        'dsn' => 'sqlite:database/database.sqlite',
        'username' => 'root',
        'password' => 'secret',
    ],
    'mailer' => [
        'username' => 'root',
        'password' => 'secret',
    ]
];
