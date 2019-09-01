<?php

$twig->addGlobal('css', $settings::get('website_url').'Public/Views/'.$theme.'/assets/css/');
$twig->addGlobal('bootstrap', $settings::get('website_url').'Public/Views/'.$theme.'/assets/bootstrap/');
$twig->addGlobal('js', $settings::get('website_url').'Public/Views/'.$theme.'/assets/js/');
$twig->addGlobal('img', $settings::get('website_url').'Public/Views/'.$theme.'/assets/img/');

$user       = Model::get('User');
if($user->isLoggedIn()){
    $twig->addGlobal('user', $user);

    $mail       = Model::get('Mail');
    $twig->addGlobal('mail', $mail);
}

$twig->addGlobal('settings', $settings);
$twig->addGlobal('token', Token::generate());
$twig->addGlobal('time', time());
$twig->addGlobal('menus', $menus);

if(Session::exists('error')){
    $twig->addGlobal('error', Session::flash('error'));
}
if(Session::exists('success')){
    $twig->addGlobal('success', Session::flash('success'));
}
if(Session::exists('info')){
    $twig->addGlobal('info', Session::flash('info'));
}