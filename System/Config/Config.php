<?php

$GLOBALS['config'] = array(
    'mysql'		=> array(
        'host'		=> 'localhost',
        'username'	=> 'newuser',
        'password'	=> 'password',
        'db'		=> 'test',
    ),
    'remember'	=> array(
        'cookieName'	=> 'hash',
        'cookieExpiry'	=> 604800,
    ),
    'session'	=> array(
        'sessionName'	=> 'user',
        'tokenName'		=> 'token'
    ),
    'template' => array(
        'template_dir' => 'System/Views',
        'assets_dir' => 'public/assets/',
        'cache_dir' => 'public/cache'
    ),
    'website' => array(
        'base_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/crime/"
    )
);