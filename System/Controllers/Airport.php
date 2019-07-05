<?php


class Airport extends Controller
{

    public function index()
    {
        $user = $this->model('User');
        if(!$user->isLoggedIn()){
            Redirect::to('/');
        }elseif($user->getTimer('prison') > time()){
            Redirect::to('/prison');
        }
        $locations = Database::getInstance()->get("locations", array('id', '<>', $user->stats()->GS_location));
        $locations = $locations->results();
        $this->view('airport', array(
            "locations" => $locations
        ));
    }

    public function fly()
    {
        $user = $this->model('User');
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $location = Database::getInstance()->get("locations", array('id', '=', Input::get('location')));
                if($location->count()){
                    $location = $location->first();
                    if($user->stats()->GS_location !== $location->id){
                        if($user->stats()->GS_cash >= $location->L_cost){
                            if($user->getTimer("travel") <= time()){
                                $user->set(array(
                                    "GS_cash"       => $user->stats()->GS_cash - $location->L_cost,
                                    "GS_location"   => $location->id
                                ));
                                $user->setTimer('travel', $location->L_time);
                                Session::flash("success", 'You travelled to '.$location->L_name.' at a cost of $'.number_format($location->L_cost).'!');
                            }else{
                                //Session::flash("error", 'you have to wait');
                            }
                        }else{
                            Session::flash("error", 'You do not have enough money to travel to '.$location->L_name.', you need $'.number_format($location->L_cost).'!');
                        }
                    }else{
                        Session::flash("error", 'You are already in '.$location->L_name.'!');
                    }
                }else{
                    Session::flash("error", 'The city you are trying to fly is not exist!');
                }
            }
        }
        Redirect::to('/airport');
    }

}