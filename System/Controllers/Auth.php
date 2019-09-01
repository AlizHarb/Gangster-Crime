<?php


class Auth extends Controller
{

    public function __construct()
    {
        parent::__construct(false, false, false);
    }

    public function index(){
        if(Session::exists('loginError')) {
            $this->view("auth/main", array(
                "loginError" => Session::flash('loginError')
            ));
        }elseif(Session::exists('registerError')) {
            $this->view("auth/main", array(
                "registerError" => Session::flash('registerError')
            ));
        }else{
            $this->view("auth/main");
        }
    }

    public function register()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'G_name'	=> array(
                        'fieldName'	=> 'Gangster Name',
                        'required' 	=> true,
                        'min'		=> 2,
                        'max'		=> 20,
                        'unique'	=> 'gangsters'
                    ),
                    'password'	=> array(
                        'fieldName'	=> 'Gangster Password',
                        'required' 	=> true,
                        'min'		=> 5
                    ),
                    'confirm_password' => array(
                        'fieldName'	=> 'Confirm Gangster Password',
                        'required' 	=> true,
                        'min'		=> 5,
                        'matches'	=> 'password'
                    ),
                    'G_email'	=> array(
                        'fieldName'	=> 'Gangster Email',
                        'required' 	=> true,
                        'min'		=> 2,
                        'max'		=> 50,
                        'unique'	=> 'gangsters'
                    )
                ));
                if($validation->passed()){
                    $user = Model::get('User');
                    $salt = Hash::salt(12);
                    try {
                        $user->create(array(
                            'G_name' 	    => ucfirst(Input::get('G_name')),
                            'G_password' 	=> Hash::make(Input::get('password'), $salt),
                            'G_email' 		=> Input::get('G_email'),
                            'G_salt' 		=> $salt,
                            'G_group'	    => 1,
                            'G_joined' 	    => date('Y-m-d H:i:s')
                        ));
                        //Session::flash('registerSuccess','You have been registered and can now log in');
                        $user->login(Input::get('G_name'), Hash::make(Input::get('password'), $salt));
                        Redirect::to('home');
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }else{
                    $err = array();
                    foreach ($validation->errors() as $error) {
                        $err = $error;
                    }
                    Session::flash('registerError', $err);
                    Redirect::to('auth');
                }
            }
        }
        Redirect::to('auth');
    }

    public function login()
    {
        if (Input::exists()) {
            if (Token::check(Input::get('token'))) {
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'gangster_username'	=> array(
                        'fieldName'	=> 'Gangster Username',
                        'required' 	=> true
                    ),
                    'gangster_password'	=> array(
                        'fieldName'	=> 'Gangster Password',
                        'required' 	=> true
                    )
                ));
                if ($validation->passed()) {
                    $user 		= Model::get('User');
                    $login 		= $user->login(Input::get('gangster_username'), Input::get('gangster_password'));
                    if ($login) {
                        Redirect::to('home');
                    } else {
                        Session::flash('loginError','Sorry we could not log you in.');
                        Redirect::to('auth');
                    }
                } else {
                    $err = array();
                    foreach ($validation->errors() as $error) {
                        $err = $error;
                    }
                    Session::flash('loginError', $err);
                    Redirect::to('auth');
                }
            }
        }
        Redirect::to('auth');
    }
}