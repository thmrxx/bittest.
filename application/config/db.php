<?php

$config = [
    'host' => 'localhost',
    'dbname' => 'bit',
    'username' => 'root',
    'password' => '',
];

if (file_exists(__DIR__ . '/db-local.php')) {
    $config = array_merge($config, require __DIR__ . '/db-local.php');
}

return $config;