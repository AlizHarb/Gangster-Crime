<?php


class Prison extends Controller
{

    public function index()
    {
        $user = $this->model('User');
        if(!$user->isLoggedIn()){
            Redirect::to('/');
        }

        $prisoners = array();
        $prison = Database::getInstance()->get("gangstersTimer", array('GT_name', '=', "prison"));
        foreach($prison->results() as $prison){
            $userPrison = $this->model('User');
            $userPrison->find($prison->id);

            if($prison->GT_time >= time() && $userPrison->stats()->GS_location == $user->stats()->GS_location){
                $prisoners[] = array(
                    "time"      => $prison->GT_time * 1000,
                    "userInfo"  => $userPrison
                );
            }
        }

        $buster = array();
        $busters = Database::getInstance()->get("gangstersStats", array("GS_prisonSuccess", ">", 0));
        foreach($busters->results() as $bust){
            $userPrison = $this->model('User');
            $userPrison->find($bust->id);

            $buster[] = array(
                "bust" => $bust->GS_prisonSuccess,
                "userInfo"  => $userPrison
            );
        }
        $this->view('prison', array(
            "prisoners"        => $prisoners,
            "busters"          => $buster
        ));
    }

    public function bust()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $time = Database::getInstance()->get('gangstersTimer', array('id', '=', Input::get('prisoner')));
                $time = $time->first();
                $user = $this->model('User');
                $userInfo = $this->model('User');
                $userInfo->find(Input::get('prisoner'));
                if($time->GT_name == "prison" && $time->GT_time >= time()){
                    if($user->getTimer('prison') <= time()){
                        if($userInfo->stats()->GS_prisonReward <= $userInfo->stats()->GS_cash){
                            if($userInfo->data()->id !== $user->data()->id){
                                if($userInfo->stats()->GS_location == $user->stats()->GS_location){
                                    $chance = mt_rand(1, 3);
                                    if($chance == 1 || $chance == 3){
                                        Session::flash('error', 'You have failed busting '.$userInfo->data()->name.'.');
                                        $user->set(array(
                                            'GS_prisonCrime' => 'Failed bust '.$userInfo->data()->name,
                                            'GS_prisonFailed' => $user->stats()->GS_prisonFailed + 1
                                        ));
                                        $user->setTimer('prison', 2*60);
                                    }else{
                                        $user->set(array(
                                            'GS_prisonSuccess' => $user->stats()->GS_prisonSuccess + 1,
                                            'GS_cash' => $user->stats()->GS_cash + $userInfo->stats()->GS_prisonReward
                                        ));
                                        $userInfo->set(array(
                                            "GS_cash" => $userInfo->stats()->GS_cash - $userInfo->stats()->GS_prisonReward
                                        ));
                                        $userInfo->setTimer('prison', 0);
                                        Session::flash('success', 'You have successfully busted '.$userInfo->data()->name.'.');
                                    }
                                }else{
                                    Session::flash('error', 'You can not bust someone in another city.');
                                }
                            }else{
                                Session::flash('error', 'You can not bust yourself.');
                            }
                        }else{
                            Session::flash('error', 'This user has no money to pay for reward.');
                        }
                    }else{
                        Session::flash('error', 'You can not bust someone while you are in prison.');
                    }
                }else{
                    Session::flash('error', 'This user is not in prison.');
                }
            }
        }
        Redirect::to('/prison');
    }

    public function bribe()
    {
        $user = $this->model('User');
        if(Input::exists()){
            if(Token::check(Input::get('token'))) {
                if($user->stats()->GS_bribe > 0){
                    if($user->getTimer('prison') >= time()){
                        $user->set(array(
                            'GS_bribe' => $user->stats()->GS_bribe - 1
                        ));
                        $user->setTimer('prison', 0);
                        Session::flash('success', 'You are out of prison.');
                    }else{
                        Session::flash('error', 'You are not in the prison.');
                    }
                }else{
                    Session::flash('error', 'You do not have any bribe.');
                }
            }
        }
        Redirect::to('/prison');
    }

    public function reward()
    {
        $user = $this->model('User');
        if(Input::exists()) {
            if (Token::check(Input::get('token'))) {
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'reward'	=> array(
                        'fieldName'	=> 'Reward',
                        'required' 	=> true,
                        'number'    => true,
                        'minNumber' => 1,
                        'maxNumber' => $user->stats()->GS_cash
                    )
                ));
                if($validation->passed()){
                    $user->set(array(
                        'GS_prisonReward' => Input::get('reward')
                    ));
                    Session::flash('success', 'Your bust reward was set to $'.number_format(Input::get('reward')));
                }else{
                    $err = array();
                    foreach ($validation->errors() as $error) {
                        $err = $error;
                    }
                    Session::flash('error', $err);
                }
            }
        }
        Redirect::to('/prison');
    }
}