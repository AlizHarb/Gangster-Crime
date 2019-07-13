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
        $user = Model::get('User');
        $locations = $this->_db->get("locations", array(
            array('id', '<>', $user->stats()->GS_location)
        ));
        $locations = $locations->results();
        $this->view('airport', array(
            "locations" => $locations
        ));
    }

    public function fly()
    {
        $user       = Model::get('User');
        $location   = Model::get('Location');

        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                if($location->getLocation(Input::get('location'))){
                    if($user->stats()->GS_location !== $location->getLocation(Input::get('location'))->id){
                        if($user->stats()->GS_cash >= $location->getLocation(Input::get('location'))->L_cost){
                            if($user->getTimer("travel") <= time()){
                                $user->set(array(
                                    "GS_cash"       => $user->stats()->GS_cash - $location->getLocation(Input::get('location'))->L_cost,
                                    "GS_location"   => $location->getLocation(Input::get('location'))->id
                                ));
                                $user->setTimer('travel', $location->getLocation(Input::get('location'))->L_time);
                                Session::flash("success", 'You travelled to '.$location->getLocation(Input::get('location'))->L_name.' at a cost of $'.number_format($location->getLocation(Input::get('location'))->L_cost).'!');
                                $items = $this->_db->get("items", array(
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
                            Session::flash("error", 'You do not have enough money to travel to '.$location->getLocation(Input::get('location'))->L_name.', you need $'.number_format($location->getLocation(Input::get('location'))->L_cost).'!');
                        }
                    }else{
                        Session::flash("error", 'You are already in '.$location->getLocation(Input::get('location'))->L_name.'!');
                    }
                }else{
                    Session::flash("error", 'The city you are trying to fly is not exist!');
                }
            }
        }
        Redirect::to('/airport');
    }

}