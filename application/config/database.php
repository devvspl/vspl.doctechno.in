<?php
defined('BASEPATH') or exit('No direct script access allowed');
$active_group = 'default';
$query_builder = true;
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    // Local Development Configuration
    $db['default'] = [
        'dsn' => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'vspl_scanocr',
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
	$db['default'] = array(
		'dsn'	=> '',
		'hostname' => 'localhost',
		'username' => 'vspl_user',
		'password' => '}^p=*JHB6gW!',
		'database' => 'vspl_scanocr',
		'dbdriver' => 'mysqli',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => (ENVIRONMENT !== 'production'),
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);
}
$db['secondary'] = [
    'dsn' => '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'scan_ocr_agrisoft',
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
