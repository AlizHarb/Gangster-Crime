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
        $theft      = Model::get('Theft');
        $location   = Model::get('Location');
        $locations = $location->all(array('id', '<>', $user->location()->id));

        $this->view('cars/theft', array(
            "thefts"    => $theft->all(),
            "garage"    => $theft->userCars(),
            "skill"     => $theft->skill(),
            "locations" => $locations
        ));
    }

    public function steal()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = Model::get('User');
                $theft = Model::get('Theft');
                $item = Model::get('Item');

                if($theft->find(Input::get('theft'))){
                    $theftauto = $theft->find(Input::get('theft'));
                    if($user->timer('autotheft') <= time()){

                        $jailChance = mt_rand(1, 3);
                        $chance = mt_rand(1, 100);
                        $carDamage = mt_rand(1, $theftauto->AT_maxDamage);
                        $userChance = $theftauto->AT_chance + ($user->stats()->GS_rank * 2);
                        if ($userChance > 100) {
                            $userChance = 100;
                        }

                        $cars = $theft->cars(array(
                            array("id", '>=', $theftauto->AT_worstCar),
                            array("id", '<=', $theftauto->AT_bestCar),
                        ));
                        $total = 0;
                        foreach ($cars as $row) {
                            $total += $row['chance'];
                        }
                        $car = mt_rand(1, $total);

                        $total2 = 0;

                        foreach ($cars as $row) {
                            $total2 += $row['chance'];
                            if ($total2 >= $car) {
                                $car = $row['id'];
                                $carName = $row['name'];
                                break;
                            }

                        }

                        $user->timer('autotheft', 3*60);
                        if ($chance > $userChance && $jailChance == 1){
                            $user->timer('prison', 2*60);
                            $user->set(array(
                                "GS_prisonReason" => "Auto theft"
                            ));
                            Session::flash('error', 'Security catching you on camera breaking into a vehicle. Police arrived and sent you to Prison.');
                            Redirect::to('prison');
                            $add = 0;
                        }elseif($chance > $userChance){
                            Session::flash("error", 'You failed to steal a car from '.$theftauto->AT_name.'!');
                            $add = 1;
                        }else{
                            Session::flash("success", 'You successfully stole '.$carName.' with '.$carDamage.'% damage!');
                            $theft->insert(array(
                                "GA_user"               => $user->data()->id,
                                "GA_car"                => $car,
                                "GA_damage"             => $carDamage,
                                "GA_currentLocation"    => $user->stats()->GS_location,
                                "GA_nowLocation"        => $user->stats()->GS_location
                            ));
                            $user->set(array(
                                "GS_exp"    => $user->stats()->GS_exp + 1,
                                "GS_autostolen" => $user->stats()->GS_autostolen + 1
                            ));
                            $items = explode('-', $theftauto->AT_items);
                            $randomItem = $items[array_rand($items)];
                            $itemChance = ($randomItem == 1 ? 10 : $randomItem*3);
                            $chanceItem = mt_rand($randomItem, $itemChance);
                            if($randomItem == $chanceItem && $randomItem > 0){
                                $item->find($randomItem);
                                $userItems = explode('-', $user->stats()->GS_items);
                                $userItems[($item->data()->id-1)] = $userItems[($item->data()->id-1)] + 1;
                                $newItem = implode('-', $userItems);
                                $user->set(array(
                                    "GS_items" => $newItem
                                ));
                                Session::flash("info", 'You have found '.$item->data()->I_name.' while attempting the crime. You have '.number_format($userItems[($item->data()->id-1)]).' '.$item->data()->I_name);
                            }
                            $add = 2;
                        }
                        $user->set(array(
                            "GS_theftExp"    => $user->stats()->GS_theftExp + $add
                        ));
                    }else{
                        //Session::flash("error", 'You have to wait!');
                    }
                }else{
                    Session::flash("error", 'The auto theft crime you are trying to do is not exist!');
                }
            }
        }
        Redirect::to('autotheft');
    }

    public function action()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user       = Model::get('User');
                $location   = Model::get('Location');
                $mail       = Model::get('Mail');
                $car        = Model::get('Theft');

                $garage = $car->garage(Input::get('cars') ,$user->data()->id);

                if($garage){
                    if($garage->GA_nowLocation == $user->stats()->GS_location && $garage->GA_shipTime <= time()){
                        if(Input::get('sell')){
                            $multi = (100 - $garage->GA_damage) / 100;
                            $value = round(($car->car($garage->GA_car)->C_price * $multi));
                            $user->set(array(
                                "GS_cash"   => $user->stats()->GS_cash + $value
                            ));
                            $car->remove(array(
                                array('id', '=', Input::get('cars'))
                            ));
                            Session::flash("success", 'You sold '.$car->car($garage->GA_car)->C_name.', making $'.number_format($value).'.');
                        }elseif(Input::get('repair')){
                            if($garage->GA_damage > 0){
                                $multi = (100 + $garage->GA_damage) / 100;
                                $value = round(($car->car($garage->GA_car)->C_price * $multi));
                                $user->set(array(
                                    "GS_cash"   => $user->stats()->GS_cash - $value
                                ));
                                $car->edit("id = ".Input::get('cars'), array(
                                    "GA_damage" => 0
                                ));
                                Session::flash("success", 'You repaired '.$car->car($garage->GA_car)->C_name.' for $'.number_format($value).'.');
                            }else{
                                Session::flash('error', 'This car is not damaged.');
                            }
                        }elseif(Input::get('ship')){
                            if($location->find(Input::get('location'))){
                                $location->find(Input::get('location'));
                                if($garage->GA_shipTime <= time()){
                                    if($garage->GA_nowLocation !== $location->data()->id){
                                        $car->edit("id = ".Input::get('cars'), array(
                                            "GA_shipTo"     => $location->data()->id,
                                            "GA_shipTime"   => (time()+1*60*60)
                                        ));
                                        $user->set(array(
                                            "GS_cash"   => $user->stats()->GS_cash - ($location->data()->L_cost / 7)
                                        ));
                                        Session::flash("success", 'You exported '.$car->car($garage->GA_car)->C_name.' at a cost of $'.number_format(($location->data()->L_cost / 7)).'.');
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
                    Session::flash("error", "You do not own this car.".$garage->id);
                }
            }
        }
        Redirect::to('autotheft');
    }
}