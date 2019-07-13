<?php


class Crimes extends Controller
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

        $crimes = array();
        $crime = $this->_db->get("crimes", array(
            array('id', '>', 0)
        ));
        foreach($crime->results() as $crime){
            $crimePerc = explode('-', $user->stats()->GS_crimes);

            if($crime->id > 1){
                $previous = $this->_db->get("crimes", array(
                    array('id', '=',$crime->id-1)
                ));
                $previous = $previous->first();

                $previousPerc = $crimePerc[($previous->id - 1)];
            }else{
                $previousPerc = 100;
            }

            $crimes[] = array(
                "crimes"    => $crime,
                "perc"      => $crimePerc[($crime->id - 1)],
                "previous"  => $previousPerc
            );
        }

        $this->view('crimes', array(
            "crimes" => $crimes
        ));
    }

    public function attempt()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = Model::get('User');
                $crime = $this->_db->get('crimes', array(
                    array('id', '=', Input::get('crime'))
                ));
                $crime = $crime->first();
                $userCrimeChance = explode('-', $user->stats()->GS_crimes);
                if($crime->id > 1){
                    $previous = $this->_db->get("crimes", array(
                        array('id', '=',$crime->id-1)
                    ));
                    $previous = $previous->first();

                    $previous = $userCrimeChance[($previous->id - 1)];
                }else{
                    $previous = 100;
                }

                $chance = mt_rand(1, 100);
                $jailChance = mt_rand(1, 3);
                $userChance = $userCrimeChance[($crime->id - 1)];
                $cashReward = mt_rand($crime->C_minMoney, $crime->C_maxMoney);

                if($previous == 100){
                    if($user->getTimer('crime') <= time()){
                        $user->setTimer('crime', $crime->C_time);
                        if ($chance > $userChance && $jailChance == 1) {
                            Session::flash('error', 'You failed to commit the crime, you were caught and sent to jail!');
                            $user->setTimer('prison', 1*60);
                            $user->set(array(
                                "GS_prisonCrime" => "Attempted Crime"
                            ));
                            Redirect::to('/prison');
                            $add = 0;
                        }elseif($chance > $userChance){
                            Session::flash('error', 'You have failed commit the crime.');
                            $add = 1;
                        }else{
                            Session::flash('success', 'You have successfully commit the crime, you have got $'.number_format($cashReward));
                            $user->set(array(
                                "GS_cash"   => $user->stats()->GS_cash + $cashReward,
                                "GS_exp"    => $user->stats()->GS_exp + $crime->C_exp
                            ));
                            $items = $this->_db->get("items", array(
                                array("id", '>=', 1),
                                array("id", '<=', 18)
                            ));
                            foreach($items->results() as $item){
                                $chanceItem = mt_rand(1, 36);
                                if($chanceItem == $item->id){
                                    Session::flash("info", 'You have found '.$item->I_name.' while attempting the crime.');
                                    $userItems = explode('-', $user->stats()->GS_items);
                                    $userItems[($item->id-1)] = $userItems[($item->id-1)] + 1;
                                    $newItem = implode('-', $userItems);
                                    $user->set(array(
                                        "GS_items" => $newItem
                                    ));
                                    break;
                                }
                            }
                            $add = 2;
                        }
                        if($userCrimeChance[($crime->id-1)] == 99 && $add > 0){
                            $mail = Model::get('Mail');
                            $next = $this->_db->get("crimes", array(
                                array('id', '=',$crime->id+1)
                            ));
                            $next = $next->first();
                            $mail->create(array(
                                "M_toUser"      => $user->data()->id,
                                "M_fromUser"    => 0,
                                "M_text"        => "You have unlocked a new crime. <b>".$next->C_name."</b>.",
                                "M_date"        => date('Y-m-d H:i:s')
                            ));
                        }
                        $userCrimeChance[($crime->id-1)] = $userCrimeChance[($crime->id-1)] + $add;

                        if ($userCrimeChance[($crime->id-1)] > 100) {
                            $userCrimeChance[($crime->id-1)] = 100;
                        }

                        $newCrimePercentages = implode('-', $userCrimeChance);
                        $user->set(array(
                            "GS_crimes" => $newCrimePercentages
                        ));
                    }else{
                        //Session::flash('error', "You have to wait.");
                    }
                }else{
                    Session::flash('error', 'You can not commit this crime yet.');
                }
                Redirect::to('/crimes');
            }
        }
    }
}