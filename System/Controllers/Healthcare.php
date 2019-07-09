<?php


class Healthcare extends Controller
{

    public function __construct()
    {
        parent::__construct(true, true, false);
    }

    public function index()
    {
        $healthCarePlans = array(
            0 => array(
                "name"      => "No Plan",
                "price"     => 525000,
                "increase"  => 8
            ),
            1 => array(
                "name"      => "Basic Plan",
                "price"     => 150000,
                "increase"  => 5
            ),
            2 => array(
                "name"      => "Premium Plan",
                "price"     => 240000,
                "increase"  => 10
            ),
            3 => array(
                "name"      => "Instant Health",
                "price"     => 10,
                "increase"  => 10
            )
        );

        $this->view('healthcare', array(
            "healthPlans" => $healthCarePlans
        ));
    }

    public function plan()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = $this->model('User');
                $healthCarePlans = array(
                    0 => array(
                        "name"      => "No Plan",
                        "price"     => 525000,
                        "increase"  => 8
                    ),
                    1 => array(
                        "name"      => "Basic Plan",
                        "price"     => 150000,
                        "increase"  => 5
                    ),
                    2 => array(
                        "name"      => "Premium Plan",
                        "price"     => 240000,
                        "increase"  => 10
                    ),
                    3 => array(
                        "name"      => "Instant Health",
                        "price"     => 10,
                        "increase"  => 10
                    )
                );

                if(array_key_exists(Input::get('plan'), $healthCarePlans)){
                    if($user->getHospital()->H_planTime <= time()){
                        if(Input::get('plan') < 3 && $user->stats()->GS_cash >= $healthCarePlans[Input::get('plan')]['price'] || Input::get('plan') == 3 && $user->stats()->GS_credits >= $healthCarePlans[Input::get('plan')]['price']){
                            $user->setHospital(array(
                                "H_plan" => Input::get('plan'),
                                "H_planTime" => (time()+(7*24*60*60))
                            ));
                            if(Input::get('plan') < 3){
                                $user->set(array(
                                    "GS_cash" => $user->stats()->GS_cash - $healthCarePlans[Input::get('plan')]['price']
                                ));
                            }else{
                                $user->set(array(
                                    "GS_credits" => $user->stats()->GS_credits - $healthCarePlans[Input::get('plan')]['price']
                                ));
                            }
                            Session::flash('success', 'You have bought '.$healthCarePlans[Input::get('plan')]['name']);
                        }else{
                            Session::flash('error', 'You do not have enough money/credits to buy the plan');
                        }
                    }else{
                        Session::flash('info', 'You already have health insurance coverage.');
                    }
                }else{
                    Session::flash('error', 'The plan you are trying to buy is not exist.');
                }
                Redirect::to('/healthcare');
            }
        }
    }

    public function checkin()
    {
        if(Input::exists()) {
            if (Token::check(Input::get('token'))) {
                $user = $this->model('User');
                $healthCarePlans = array(
                    0 => array(
                        "name"      => "No Plan",
                        "price"     => 525000,
                        "increase"  => 8
                    ),
                    1 => array(
                        "name"      => "Basic Plan",
                        "price"     => 150000,
                        "increase"  => 5
                    ),
                    2 => array(
                        "name"      => "Premium Plan",
                        "price"     => 240000,
                        "increase"  => 10
                    ),
                    3 => array(
                        "name"      => "Instant Health",
                        "price"     => 10,
                        "increase"  => 10
                    )
                );
                $price = $healthCarePlans[$user->getHospital()->H_plan]["price"] * Input::get('hours');
                $increaseHealth = ($user->getRank()->R_health * ($user->stats()->GS_hospitalHours * ($healthCarePlans[$user->getHospital()->H_plan]["increase"] / 100)));
                $user = $this->model('User');
                if($user->getHospital()->H_plan < 3){
                    if($price <= $user->stats()->GS_cash){
                        if(($user->stats()->GS_health - $increaseHealth) > 0){
                            if($user->getTimer('hospital') <= time()){
                                $validate = new Validate();
                                $validation = $validate->check($_POST, array(
                                    'hours'	=> array(
                                        'fieldName'	=> 'Hours',
                                        'required' 	=> true,
                                        'number'    => true,
                                        'minNumber' => 1,
                                        'maxNumber' => 20
                                    )
                                ));
                                if($validation->passed()){
                                    $user->set(array(
                                        "GS_cash"           => $user->stats()->GS_cash - $price,
                                        "GS_hospitalHours"   => Input::get('hours')
                                    ));
                                    $user->setTimer('hospital', (Input::get('hours')*60*60));
                                    Session::flash('success', 'You have successfully checked in for '.Input::get('hours').' hours!');
                                }else{
                                    $err = array();
                                    foreach ($validation->errors() as $error) {
                                        $err = $error;
                                    }
                                    Session::flash('error', $err);
                                }
                            }else{
                                Session::flash('error', 'You have already checked in!');
                            }
                        }else{
                            Session::flash('error', 'You do not have to check in for that much of the time!');
                        }
                    }else{
                        Session::flash('error', 'You do not have enough money to check in!');
                    }
                }else{
                    Session::flash('error', 'You have a Instant Health, you do not need to check in!');
                }
                Redirect::to('/healthcare');
            }
        }
    }
}