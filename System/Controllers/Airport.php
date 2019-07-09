<?php


class Airport extends Controller
{

    public function __construct()
    {
        parent::__construct(true, true, true);
    }

    public function index()
    {
        $user = $this->model('User');
        $locations = Database::getInstance()->get("locations", array(
            array('id', '<>', $user->stats()->GS_location)
        ));
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
                $location = Database::getInstance()->get("locations", array(
                    array('id', '=', Input::get('location'))
                ));
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
                                $items = Database::getInstance()->get("items", array(
                                    array("id", '>', 0),
                                    array("id", '<', 9)
                                ));
                                foreach($items->results() as $item){
                                    if($item->id == 1 || $item->id == 8){
                                        $chanceItem = mt_rand(1, 9);
                                        if($chanceItem == $item->id){
                                            Session::flash("info", 'You have found '.$item->I_name.' in the plane.');
                                            $userItems = explode('-', $user->stats()->GS_items);
                                            $userItems[($item->id-1)] = $userItems[($item->id-1)] + 1;
                                            $newItem = implode('-', $userItems);
                                            $user->set(array(
                                                "GS_items" => $newItem
                                            ));
                                            break;
                                        }
                                    }
                                }
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