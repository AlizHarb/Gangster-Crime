<?php


class Prison extends Controller
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();

        parent::__construct(true, false, true);
    }

    public function index()
    {
        $user = Model::get('User');

        $prisoners = array();
        $prison = $this->_db->get("gangstersTimer", array(
            array('GT_name', '=', "prison"),
            array('GT_time', '>=', time())
        ));
        foreach($prison->results() as $prison){
            $userPrison = Model::get('User');
            $userPrison->find($prison->id);

            if($userPrison->stats()->GS_location == $user->stats()->GS_location){
                $prisoners[] = array(
                    "time"      => $prison->GT_time * 1000,
                    "userInfo"  => $userPrison
                );
            }
        }

        $buster = array();
        $busters = $this->_db->get("gangstersStats", array(
            array("GS_prisonSuccess", ">", 0)
        ), 'GS_prisonSuccess', 'desc limit 5');
        foreach($busters->results() as $bust){
            $userPrison = Model::get('User');
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
                $time = $this->_db->get("gangstersTimer", array(
                    array('id', '=', Input::get('prisoner')),
                    array('GT_time', '>=', time())
                ));
                $time = $time->first();
                $user = Model::get('User');
                $mail = Model::get('Mail');
                $userInfo = Model::get('User');
                $userInfo->find(Input::get('prisoner'));
                if($time->GT_time >= time()){
                    if($user->getTimer('prison') <= time()){
                        if($userInfo->stats()->GS_prisonReward <= $userInfo->stats()->GS_cash){
                            if($userInfo->data()->id !== $user->data()->id){
                                if($userInfo->stats()->GS_location == $user->stats()->GS_location){
                                    $chance = mt_rand(1, 3);
                                    if($chance == 1 || $chance == 3){
                                        Session::flash('error', 'You were caught trying to bust '.$userInfo->data()->profile.' out of Prison and have been sent to Prison.');
                                        $user->set(array(
                                            'GS_prisonCrime' => 'Failed bust '.$userInfo->data()->profile,
                                            'GS_prisonFailed' => $user->stats()->GS_prisonFailed + 1
                                        ));
                                        $user->setTimer('prison', 2*60);
                                    }else{
                                        $user->set(array(
                                            'GS_prisonSuccess' => $user->stats()->GS_prisonSuccess + 1,
                                            'GS_cash' => $user->stats()->GS_cash + $userInfo->stats()->GS_prisonReward,
                                            "GS_exp"              => $user->stats()->GS_exp + 1
                                        ));
                                        $userInfo->set(array(
                                            "GS_cash" => $userInfo->stats()->GS_cash - $userInfo->stats()->GS_prisonReward
                                        ));
                                        $userInfo->setTimer('prison', 0);
                                        Session::flash('success', 'You have successfully busted '.$userInfo->data()->profile.'.');
                                        $mail->create(array(
                                            "M_toUser"      => $userInfo->data()->id,
                                            "M_fromUser"    => $user->data()->id,
                                            "M_text"        => $userInfo->data()->profile." has busted you out of prison.",
                                            "M_date"        => date('Y-m-d H:i:s')
                                        ));
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
        $user = Model::get('User');
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
        $user = Model::get('User');
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