<?php


class Autotheft extends Controller
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
        $carModel   = Model::get('Car');
        $location   = Model::get('Location');

        $theftauto = $this->_db->get("autotheft", array(
            array('id', '>', 0))
        );
        $theftauto = $theftauto->results();

        $garages = array();
        $garage = $this->_db->get('garage', array(
            array("GA_user", '=', $user->data()->id)
        ));
        $loop = 0;
        $allPrice = 0;
        foreach($garage->results() as $car){

            $cars = $carModel->getCar($car->GA_car);
            $multi = (100 - $car->GA_damage) /100;
            $value = round(($cars->C_price * $multi));
            $allPrice = $allPrice + $value;
            $garages[] = array(
                "car"               => $car,
                "cars"              => $cars,
                "price"             => $value,
                "currentLocation"   => $location->getLocation($car->GA_currentLocation)->L_name,
                "nowLocation"       => $location->getLocation($car->GA_nowLocation)->L_name,
                "shipLocation"      => ($car->GA_shipTo && $car->GA_shipTime > time() ? $location->getLocation($car->GA_shipTo)->L_name : ""),
                "timeConvert"       => $car->GA_shipTime * 1000
            );
            $loop++;
        }

        $locations = $this->_db->get("locations", array(
            array('id', '<>', $user->stats()->GS_location)
        ));
        $locations = $locations->results();

        $this->view('autotheft', array(
            "autotheft"     => $theftauto,
            'garage'        => $garages,
            'loop'          => $loop,
            'price'         => $allPrice,
            'locations'     => $locations
        ));
    }

    public function steal()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = Model::get('User');
                $carModel = Model::get('Car');
                $theftauto = $this->_db->get("autotheft", array(
                    array('id', '=', Input::get('theft'))
                ));
                if($theftauto->count()){
                    $theftauto = $theftauto->first();
                    if($user->getTimer('autotheft') <= time()){

                        $jailChance = mt_rand(1, 3);
                        $chance = mt_rand(1, 100);
                        $carDamage = mt_rand(1, $theftauto->AT_maxDamage);
                        $userChance = $theftauto->AT_chance + ($user->stats()->GS_rank * 2);
                        if ($userChance > 100) {
                            $userChance = 100;
                        }

                        $cars = $this->_db->get("cars", array(
                            array("id", '>=', $theftauto->AT_worstCar),
                            array("id", '<=', $theftauto->AT_bestCar),
                        ));
                        $total = 0;
                        foreach ($cars->results() as $row) {
                            $total += $row->C_theftChance;
                        }
                        $car = mt_rand(1, $total);

                        $total2 = 0;

                        foreach ($cars->results() as $row) {
                            $total2 += $row->C_theftChance;
                            if ($total2 >= $car) {
                                $car = $row->id;
                                $carName = $row->C_name;
                                break;
                            }

                        }

                        $user->setTimer('autotheft', 3*60);
                        if ($chance > $userChance && $jailChance == 1){
                            $user->set(array(
                                'GS_prisonCrime' => 'Auto Theft'
                            ));
                            $user->setTimer('prison', 2*60);
                            Session::flash('error', 'Security catching you on camera breaking into a vehicle. Police arrived and sent you to Prison.');
                            Redirect::to('/prison');
                        }elseif($chance > $userChance){
                            Session::flash("error", 'You failed to steal a car from '.$theftauto->AT_name.'!');
                        }else{
                            Session::flash("success", 'You successfully stole '.$carName.' with '.$carDamage.'% damage!');
                            $carModel->insert(array(
                                "GA_user"               => $user->data()->id,
                                "GA_car"                => $car,
                                "GA_damage"             => $carDamage,
                                "GA_currentLocation"    => $user->stats()->GS_location,
                                "GA_nowLocation"        => $user->stats()->GS_location
                            ));
                            $user->set(array(
                                "GS_autostolen"       => $user->stats()->GS_autostolen + 1,
                                "GS_exp"              => $user->stats()->GS_exp + ($theftauto->id * $user->stats()->GS_rank)
                            ));
                            $items = $this->_db->get("items", array(
                                array("id", '>=', 1),
                                array("id", '<=', 11)
                            ));
                            foreach($items->results() as $item){
                                $chanceItem = mt_rand(1, 21);
                                if($chanceItem == $item->id){
                                    Session::flash("info", 'You have found '.$item->I_name.' in the car.');
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
                        //Session::flash("error", 'You have to wait!');
                    }
                }else{
                    Session::flash("error", 'The auto theft crime you are trying to do is not exist!');
                }
            }
        }
        Redirect::to('/autotheft');
    }

    public function action()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user       = Model::get('User');
                $location   = Model::get('Location');
                $mail       = Model::get('Mail');
                $car        = Model::get('Car');
                $garage = $this->_db->get('garage', array(
                    array('id', '=', Input::get('cars')),
                    array('GA_user', '=', $user->data()->id)
                ));
                if($garage->count()){
                    $garage = $garage->first();
                    if($garage->GA_nowLocation == $user->stats()->GS_location && $garage->GA_shipTime <= time()){
                        if(Input::get('sell')){
                            $multi = (100 - $garage->GA_damage) / 100;
                            $value = round(($car->getCar($garage->GA_car)->C_price * $multi));
                            $user->set(array(
                                "GS_cash"   => $user->stats()->GS_cash + $value
                            ));
                            $car->remove(array(
                                array('id', '=', Input::get('cars'))
                            ));
                            Session::flash("success", 'You sold '.$car->getCar($garage->GA_car)->C_name.', making $'.number_format($value).'.');
                        }elseif(Input::get('repair')){
                            if($garage->GA_damage > 0){
                                $multi = (100 + $garage->GA_damage) / 100;
                                $value = round(($car->getCar($garage->GA_car)->C_price * $multi));
                                $user->set(array(
                                    "GS_cash"   => $user->stats()->GS_cash - $value
                                ));
                                $car->edit("id = ".Input::get('cars'), array(
                                    "GA_damage" => 0
                                ));
                                Session::flash("success", 'You repaired '.$car->getCar($garage->GA_car)->C_name.' for $'.number_format($value).'.');
                            }else{
                                Session::flash('error', 'This car is not damaged.');
                            }
                        }elseif(Input::get('ship')){
                            if($location->getLocation(Input::get('location'))){
                                if($garage->GA_shipTime <= time()){
                                    if($garage->GA_nowLocation !== $location->getLocation(Input::get('location'))->id){
                                        $car->edit("id = ".Input::get('cars'), array(
                                            "GA_shipTo"     => $location->getLocation(Input::get('location'))->id,
                                            "GA_shipTime"   => (time()+1*60*60)
                                        ));
                                        $user->set(array(
                                            "GS_cash"   => $user->stats()->GS_cash - ($location->getLocation(Input::get('location'))->L_cost / 7)
                                        ));
                                        Session::flash("success", 'You exported '.$car->getCar($garage->GA_car)->C_name.' at a cost of $'.number_format(($location->getLocation(Input::get('location'))->L_cost / 7)).'.');
                                    }else{
                                        Session::flash('error', 'You ca not ship the car to the same city.');
                                    }
                                }else{
                                    Session::flash('error', "You can not ship a car while its moving.");
                                }
                            }else{
                                Session::flash('error', 'The location you are trying to ship to is not exist.');
                            }
                        }elseif(Input::get('send')){
                            $userInfo = Model::get('User');
                            if($userInfo->find(Input::get('user'))){
                                $userInfo->find(Input::get('user'));
                                if($garage->GA_shipTime <= time()){
                                    $car->edit("id = ".Input::get('cars'), array(
                                        "GA_user"   => $userInfo->data()->id
                                    ));
                                    $mail->create(array(
                                        "M_toUser"      => $userInfo->data()->id,
                                        "M_fromUser"    => 0,
                                        "M_text"        => $user->data()->profile." sent you ".$car->getCar($garage->GA_car)->C_name."!",
                                        "M_date"        => date('Y-m-d H:i:s')
                                    ));
                                    Session::flash('success', 'You have successfully sent the car to '.$userInfo->data()->profile.'');
                                }else{
                                    Session::flash('error', 'You have to wait till the car arrive to be able to send it.');
                                }
                            }else{
                                Session::flash('error', 'The gangster you are trying to send to is not exist.');
                            }
                        }
                    }else{
                        Session::flash("error", "You have to be at same location.");
                    }
                }else{
                    Session::flash("error", "You do not own this car.");
                }
            }
        }
        Redirect::to('/autotheft');
    }
}