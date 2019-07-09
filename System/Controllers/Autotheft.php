<?php


class Autotheft extends Controller
{

    public function __construct()
    {
        parent::__construct(true, true, true);
    }

    public function index()
    {
        $theftauto = Database::getInstance()->get("autotheft", array(
            array('id', '>', 0))
        );
        $theftauto = $theftauto->results();
        $this->view('autotheft', array(
            "autotheft" => $theftauto
        ));
    }

    public function steal()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = $this->model('User');
                $theftauto = Database::getInstance()->get("autotheft", array(
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
                            Session::flash("success", 'You successfully stole a car with '.$carDamage.'% damage!');
                            $user->set(array(
                                "GS_autostolen"       => $user->stats()->GS_autostolen + 1,
                                "GS_exp"              => $user->stats()->GS_exp + ($theftauto->id * $user->stats()->GS_rank)
                            ));
                            $items = Database::getInstance()->get("items", array(
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
                        Session::flash("error", 'You have to wait!'.$user->getTimer('autotheft'));
                    }
                }else{
                    Session::flash("error", 'The auto theft crime you are trying to do is not exist!');
                }
            }
        }
        Redirect::to('/autotheft');
    }
}