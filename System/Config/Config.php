<?php

$GLOBALS['config'] = array(
    'mysql'		=> array(
        'host'		=> 'localhost',
        'username'	=> 'newuser',
        'password'	=> 'password',
        'db'		=> 'test',
    ),
    'session'	=> array(
        'sessionName'	=> 'user',
        'tokenName'		=> 'token',
        'sessionTime'   => 3600
    ),
    'template' => array(
        'template_dir' => 'Public/Views',
        'cache' => false
    )
);