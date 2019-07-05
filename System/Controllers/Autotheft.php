<?php


class Autotheft extends Controller
{

    public function index()
    {
        $user = $this->model('User');
        if(!$user->isLoggedIn()){
            Redirect::to('/');
        }elseif($user->getTimer('prison') > time()){
            Redirect::to('/prison');
        }
        $theftauto = Database::getInstance()->get("autotheft", array('id', '>', 0));
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
                $theftauto = Database::getInstance()->get("autotheft", array('id', '=', Input::get('theft')));
                if($theftauto->count()){
                    $theftauto = $theftauto->first();
                    if($user->getTimer('autotheft') <= time()){

                        $jailChance = mt_rand(1, 3);
                        $chance = mt_rand(1, 100);
                        $carDamage = mt_rand(1, $theftauto->AT_maxDamage);
                        $userChance = $theftauto->AT_chance; // rank * 2 should be here
                        if ($userChance > 100) {
                            $userChance = 100;
                        }

                        $user->setTimer('autotheft', 3*60);
                        if ($chance > $userChance && $jailChance == 1){
                            $user->set(array(
                                'GS_prisonCrime' => 'Auto Theft'
                            ));
                            $user->setTimer('prison', 2*60);
                            Session::flash("error", 'You failed to steal a car from '.$theftauto->AT_name.', you were caught and sent to jail!');
                            Redirect::to('/prison');
                        }elseif($chance > $userChance){
                            Session::flash("error", 'You failed to steal a car from '.$theftauto->AT_name.'!');
                        }else{
                            Session::flash("success", 'You successfully stole a car with '.$carDamage.'% damage!');
                            $user->set(array(
                                "GS_autostolen"       => $user->stats()->GS_autostolen + 1
                            ));
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