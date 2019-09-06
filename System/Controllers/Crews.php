<?php


class Crews extends Controller
{
    protected $_db;

    public function __construct()
    {
        parent::__construct(true, true, true);
    }

    public function index()
    {
        $user = Model::get('User');
        $crew = Model::get('Crew');

        if($user->stats()->GS_crew == 0){
            $this->view('crews/overview', array(
                'crew'  => $crew,
                'crews' => $crew->allCrews()
            ));
        }else{
            $crew->find($user->stats()->GS_crew);
            $this->view('crews/main', array(
                'crew'  => $crew->data()
            ));
        }
    }

    public function create()
    {
        $user   = Model::get('User');
        $crew   = Model::get('Crew');
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                if(Input::get('C_size') == 10){
                    $cash = 10000000;
                    $size = 10;
                }else{
                    $cash = 30000000;
                    $size = 30;
                }

                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'C_name' => array(
                        'fieldName' => 'Crew Name',
                        'required' => true,
                        'min' => 2,
                        'max' => 20,
                        'unique' => 'crews'
                    ),
                    'C_size' => array(
                        'fieldName' => 'Crew Size',
                        'required' => true
                    )
                ));
                if($validate->passed()){
                    if($user->stats()->GS_rank >= 11){
                        if($cash <= $user->stats()->GS_cash){
                            if($crew->crewNum() < 7){
                                if($user->stats()->GS_crew == 0){
                                    $crew->create(array(
                                        "C_name"        => Input::get('C_name'),
                                        "C_boss"        => $user->data()->id,
                                        "C_underboss"   => 0,
                                        "C_size"        => $size
                                    ));
                                    $user->set(array(
                                        "GS_cash"   => $user->stats()->GS_cash - $cash,
                                        "GS_crew"   => $crew->myOwnCrew()->id
                                    ));
                                    Session::flash('success', 'You have successfully create the crew <b>'.Input::get('C_name').'</b>');
                                }else{
                                    Session::flash('error', 'You can not create a crew because you already have one.');
                                }
                            }else{
                                Session::flash('error', 'There are already 7 crews, you can not create crew anymore.');
                            }
                        }else{
                            Session::flash('error', 'You do not have $'.number_format($cash).' to create the crew.');
                        }
                    }else{
                        Session::flash('error', 'You have to be <b>Cell Boss</b> or higher.');
                    }
                }else{
                    $err = array();
                    foreach ($validation->errors() as $error) {
                        $err = $error;
                    }
                    Session::flash('error', $err);
                }
            }
        }
        Redirect::to('crews');
    }

}