<?php
defined('BASEPATH') or exit('No direct script access allowed');
$active_group = 'default';
$query_builder = true;
if (
    $_SERVER['SERVER_NAME'] == 'localhost' ||
    $_SERVER['SERVER_NAME'] == '192.168.34.59'
) {
    // Local Development Configuration
    $db['default'] = [
        'dsn' => '',
        'hostname' => 'localhost:2025',
        'username' => 'root',
        'password' => 'root',
        'database' => 'vspl_nova',
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => false,
        'db_debug' => true,
        'cache_on' => false,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt' => false,
        'compress' => false,
        'stricton' => false,
        'failover' => [],
        'save_queries' => true,
    ];
} else {
    // Live/Production Configuration
    $db['default'] = [
        'dsn' => '',
        'hostname' => '184.168.114.42',
        'username' => 'test_scan_user',
        'password' => 'testscan@192',
        'database' => 'test_scan',
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => false,
        'db_debug' => ENVIRONMENT !== 'production',
        'cache_on' => false,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt' => false,
        'compress' => false,
        'stricton' => false,
        'failover' => [],
        'save_queries' => true,
    ];
}
