<?php


class Crimes extends Controller
{

    public function __construct()
    {
        parent::__construct(true, true, true);
    }

    public function index()
    {
        $user = $this->model('User');

        $crimes = array();
        $crime = Database::getInstance()->get("crimes", array(
            array('id', '>', 0)
        ));
        foreach($crime->results() as $crime){
            $crimePerc = explode('-', $user->stats()->GS_crimes);

            $crimes[] = array(
                "crimes"    => $crime,
                "perc"      => $crimePerc[($crime->id - 1)]
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
                $user = $this->model('User');
                $crime = Database::getInstance()->get('crimes', array(
                    array('id', '=', Input::get('crime'))
                ));
                $crime = $crime->first();
                $nextRank = Database::getInstance()->get("ranks", array(
                    array("id", '=', $user->stats()->GS_rank+1)
                ));
                $nextRank = $nextRank->first();
                $chance = mt_rand(1, 100);
                $jailChance = mt_rand(1, 3);
                $userCrimeChance = explode('-', $user->stats()->GS_crimes);
                $userChance = $userCrimeChance[($crime->id - 1)];
                $cashReward = mt_rand($crime->C_minMoney, $crime->C_maxMoney);

                if($crime->C_rank <= $user->stats()->GS_rank){
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
                            $items = Database::getInstance()->get("items", array(
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
                    Session::flash('error', 'You have to be '.$nextRank->R_name.' to do the crime.');
                }
                Redirect::to('/crimes');
            }
        }
    }
}