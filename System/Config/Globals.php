<?php
$user       = Model::get('User');
$location   = Model::get('Location');
$mail       = Model::get('Mail');
if($user->isLoggedIn()){
    $twig->addGlobal('user', $user);
    $twig->addGlobal('location', $location);
    $twig->addGlobal('mail', $mail);
}

$twig->addGlobal('settings', $settings);
$twig->addGlobal('token', Token::generate());
$twig->addGlobal('time', time());

if(Session::exists('error')){
    $twig->addGlobal('error', Session::flash('error'));
}
if(Session::exists('success')){
    $twig->addGlobal('success', Session::flash('success'));
}
if(Session::exists('info')){
    $twig->addGlobal('info', Session::flash('info'));
}