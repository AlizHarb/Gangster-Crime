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
        $crime = Model::get('Crime');
        $item = Model::get('Item');

        $crimes = array();
        foreach($crime->all() as $crime){
            $crimePerc = explode('-', $user->stats()->GS_crimes);

            $items = array();
            foreach(explode('-', $crime['items']) as $row){
                $item->find($row);
                $items[] = array(
                    "icon"  => $item->data()->I_icon
                );
            }

            if($crime['id'] > 1){
                $previous = $this->_db->get("crimes", array(
                    array('id', '=',$crime['id']-1)
                ));
                $previous = $previous->first();

                $previousPerc = $crimePerc[($previous->id - 1)];
            }else{
                $previousPerc = 100;
            }

            $crimes[] = array(
                "crimes"    => $crime,
                "perc"      => $crimePerc[($crime['id'] - 1)],
                "previous"  => $previousPerc,
                "items"      => $items
            );
        }

        $this->view('crimes/main', array(
            "crimes" => $crimes
        ));
    }

    public function attempt()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = Model::get('User');
                $crime = Model::get('Crime');
                $item = Model::get('Item');
                $crime = $crime->find(Input::get('crime'));
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
                    if($user->timer('crime-'.$crime->id) <= time()){
                        $user->timer('crime-'.$crime->id, $crime->C_time);
                        if ($chance > $userChance && $jailChance == 1) {
                            Session::flash('error', 'You failed to commit the crime, you were caught and sent to jail!');
                            $user->timer('prison', 1*60);
                            $user->set(array(
                                "GS_prisonReason" => "Attempted Crime"
                            ));
                            Redirect::to('prison');
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
                            $items = explode('-', $crime->C_items);
                            $randomItem = $items[array_rand($items)];
                            $itemChance = ($randomItem == 1 ? 10 : $randomItem*3);
                            $chanceItem = mt_rand($randomItem, $itemChance);
                            if($randomItem == $chanceItem){
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
                        $userCrimeChance[($crime->id-1)] = $userCrimeChance[($crime->id-1)] + $add;

                        if ($userCrimeChance[($crime->id-1)] > 100) {
                            $userCrimeChance[($crime->id-1)] = 100;
                        }

                        $newCrimePercentages = implode('-', $userCrimeChance);
                        $user->set(array(
                            "GS_crimes" => $newCrimePercentages
                        ));
                    }else{
                        Session::flash('error', "You have to wait.");
                    }
                }else{
                    Session::flash('error', 'You can not commit this crime yet.');
                }
            }
        }
        Redirect::to('crimes');
    }
}