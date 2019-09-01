<?php


class Airport extends Controller
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();

        parent::__construct(true, true, true);
    }

    public function index()
    {
        $user       = Model::get('User');
        $location   = Model::get('Location');
        $locations = $location->all(array('id', '<>', $user->location()->id));
        $this->view('airport/main', array(
            "locations" => $locations
        ));
    }

    public function fly()
    {
        $user       = Model::get('User');
        $location   = Model::get('Location');

        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                if($location->find(Input::get('location'))){
                    $location->find(Input::get('location'));
                    if($user->stats()->GS_location !== $location->data()->id){
                        if($user->stats()->GS_cash >= $location->data()->L_cost){
                            if($user->timer("travel") <= time()){
                                $user->set(array(
                                    "GS_cash"       => $user->stats()->GS_cash - $location->data()->L_cost,
                                    "GS_location"   => $location->data()->id
                                ));
                                $user->timer('travel', $location->data()->L_time);
                                Session::flash("success", 'You travelled to '.$location->data()->L_name.' at a cost of $'.number_format($location->data()->L_cost).'!');
                            }else{
                                //Session::flash("error", 'you have to wait');
                            }
                        }else{
                            Session::flash("error", 'You do not have enough money to travel to '.$location->data()->L_name.', you need $'.number_format($location->data()->L_cost).'!');
                        }
                    }else{
                        Session::flash("error", 'You are already in '.$location->data()->L_name.'!');
                    }
                }else{
                    Session::flash("error", 'The city you are trying to fly is not exist!');
                }
            }
        }
        Redirect::to('airport');
    }

}