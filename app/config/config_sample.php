<?php
/*
 * $config is a global var defined in System::init()
 */
$config['database'] = array(
    'SERVER' => 'localhost',
    'DATABASE' => 'dummy',
    'DB_LOGIN' => 'root',
    'DB_PASS' => '',
);

$config['system_debug_mode'] = true;//display error all
$config['db_debug_mode'] = true;//log query
$config['db_safe_mode'] = false;//Query are not executed, prevent accidental delete when testing

$config['timezone'] = 'Asia/Tokyo';//time setting
