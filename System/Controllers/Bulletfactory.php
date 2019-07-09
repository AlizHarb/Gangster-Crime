<?php


class Bulletfactory extends Controller
{

    public function __construct()
    {
        parent::__construct(true, true, true);
    }

    public function index()
    {
        $user = $this->model('User');
        $property = $this->model('Properties');
        $property->find($user->stats()->GS_location);

        $location = Database::getInstance()->get("locations", array(
            array('id', '=', $user->stats()->GS_location)
        ));
        $location = $location->first();
        $this->view('bulletfactory/bulletfactory', array(
            "location" => $location,
            "nextRelease" => $property->data()->P_time * 1000
        ));
    }

    public function steel()
    {
        $this->view('bulletfactory/steelfactory');
    }

    public function purchase()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $property = $this->model('Properties');
                $user = $this->model('User');
                $property->find($user->data()->GS_location);
                $maxBullet = $user->stats()->GS_rank * 10;
                $bulletsCost = Input::get('bullets') * $user->getLocation()->L_bulletsCost;
                if($user->getLocation()->L_bullets >= Input::get('bullets')){
                    if($user->stats()->GS_cash >= $bulletsCost){
                        if($user->getTimer('bulletsfactory') <= time()){
                            $validate = new Validate();
                            $validation = $validate->check($_POST, array(
                                'bullets'	=> array(
                                    'fieldName'	=> 'Bullets Amount',
                                    'required' 	=> true,
                                    'number'    => true,
                                    'minNumber' => 1,
                                    'maxNumber' => $maxBullet
                                )
                            ));
                            if($validation->passed()){
                                $user->set(array(
                                    "GS_cash"    => $user->stats()->GS_cash - $bulletsCost,
                                    "GS_bullets" => $user->stats()->GS_bullets + Input::get('bullets')
                                ));
                                $user->setTimer('bulletsfactory', 1*60);
                                Database::getInstance()->update("locations", "id = ".$user->stats()->GS_location, array("L_bullets" => $user->getLocation()->L_bullets - Input::get('bullets')));
                                Session::put('success', 'You have bought '.number_format(Input::get('bullets')).' for $'.number_format($bulletsCost));
                            }else{
                                $err = array();
                                foreach ($validation->errors() as $error) {
                                    $err = $error;
                                }
                                Session::flash('error', $err);
                            }
                        }else{
                            //Session::flash('error', 'You have to wait.');
                        }
                    }else{
                        Session::flash('error', 'You do not have enough money to buy the bullets.');
                    }
                }else{
                    Session::flash('error', 'The bullet factory does not have that many bullets.');
                }
            }
        }
        Redirect::to('/bulletfactory');
    }
}