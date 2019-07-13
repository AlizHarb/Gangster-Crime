<?php


class Crews extends Controller
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
        if ($user->stats()->GS_crew > 0) {
            $crew = Model::get('Crew');
            $crew->find($user->stats()->GS_crew);

            $gangstersList = array();
            $gangsters = $this->_db->get("gangstersStats", array(
                array("GS_crew", '=', $crew->data()->id)
            ));
            $x = 0;
            foreach ($gangsters->results() as $gangster) {
                $userInfo = Model::get('User');
                $userInfo->find($gangster->id);
                if ($gangster->GS_crewLevel == 6) {
                    $level = "Crew Boss";
                } elseif ($gangster->GS_crewLevel == 5) {
                    $level = "UnderBoss";
                } elseif ($gangster->GS_crewLevel == 4) {
                    $level = "Right Hand Man";
                } elseif ($gangster->GS_crewLevel == 3) {
                    $level = "Left Hand Man";
                } elseif ($gangster->GS_crewLevel == 2) {
                    $level = "Recruiter";
                } else {
                    $level = "Member";
                }

                $gangstersList[] = array(
                    'user' => $userInfo,
                    'level' => $level
                );
                $x++;
            }

            $this->view('crews/base', array(
                'crew' => $crew,
                'gangsters' => $gangstersList,
                'gangstersCount' => $x
            ));
        } else {
            $crewsList = array();
            $crews = $this->_db->get("crews", array(
                array('id', '>', '0')
            ));
            foreach ($crews->results() as $crew) {
                $boss = Model::get('User');
                $boss->find($crew->C_boss);
                $underboss = Model::get('User');
                $underboss->find($crew->C_underboss);

                $gangsters = $this->_db->get('gangstersStats', array(
                    array('GS_crew', '=', $crew->id)
                ));
                $gangsters = $gangsters->count();

                $crewsList[] = array(
                    "crew" => $crew,
                    "boss" => $boss,
                    "underboss" => $underboss,
                    'gangsters' => $gangsters
                );
            }
            $this->view('crews/overview', array(
                'crews' => $crewsList
            ));
        }
    }

    public function create()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = Model::get('User');
                $crew = Model::get('Crew');
                (Input::get('size') == 10 ? $cash = 10000000 : $cash = 25000000);
                if(!$crew->find(Input::get('name'))){
                    if(Input::get('size') == 10 && $user->stats()->GS_cash >= $cash || Input::get('size') == 30 && $user->stats()->GS_cash >= $cash){
                        if($user->stats()->GS_rank >= 11){
                            if($user->stats()->GS_crew == 0){
                                $crew->create(array(
                                    "C_name"    => Input::get('name'),
                                    "C_boss"    => $user->data()->id,
                                    "C_quote"   => "No Crew Quote.",
                                    "C_size"    => Input::get('size'),
                                    "C_joined"  => date('Y-m-d H:i:s')
                                ));
                                $myCrew = Model::get('Crew');
                                $user->set(array(
                                    "GS_cash"       => $user->stats()->GS_cash - $cash,
                                    "GS_crew"       => $myCrew->isBoss()->id,
                                    "GS_crewLevel"  => 6
                                ));
                                Session::flash('success', 'You have successfully created the crew '.Input::get('name'));
                            }else{
                                Session::flash('error', 'You can not create a crew while you are in another crew.');
                            }
                        }else{
                            Session::flash('error', 'You have to be Cell Boss to be able to create a crew.');
                        }
                    }else{
                        Session::flash('error', 'You do not have enough money to create the crew.');
                    }
                }else{
                    Session::flash('error', "There is already a crew with the same name.");
                }
            }
        }
        Redirect::to('/crews');
    }

    public function bank()
    {
        $user = Model::get('User');
        $crew = Model::get('Crew');
        $crew->find($user->stats()->GS_crew);

        $this->view('crews/bank', array(
            'crew' => $crew
        ));
    }

    public function donate()
    {
        $user = Model::get('User');
        $crew = Model::get('Crew');
        $crew->find($user->stats()->GS_crew);
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                if(Input::get('money')){
                    if($user->stats()->GS_crew > 0){
                        $validate = new Validate();
                        $validation = $validate->check($_POST, array(
                            'cash'	=> array(
                                'fieldName'	=> 'Cash',
                                'required' 	=> true,
                                'number'    => true,
                                'minNumber' => 1,
                                'maxNumber' => $user->stats()->GS_cash
                            )
                        ));
                        if($validation->passed()){
                            $user->set(array(
                                "GS_cash"    => $user->stats()->GS_cash - Input::get('cash')
                            ));
                            $crew->update(array(
                                "C_bank"    => $crew->data()->C_bank + Input::get('cash')
                            ));
                            Session::put('success', "You donated your crew by $".number_format(Input::get('cash')));
                        }else{
                            $err = array();
                            foreach ($validation->errors() as $error) {
                                $err = $error;
                            }
                            Session::flash('error', $err);
                        }
                    }else{
                        Session::flash('error', 'The crew you are trying to donate for is not exist.');
                    }
                }elseif(Input::get('bullets')){
                    if($user->stats()->GS_crew > 0){
                        if($user->getTimer('donate') <= time()){
                            $validate = new Validate();
                            $validation = $validate->check($_POST, array(
                                'bullet'	=> array(
                                    'fieldName'	=> 'Bullets',
                                    'required' 	=> true,
                                    'number'    => true,
                                    'minNumber' => 1,
                                    'maxNumber' => $user->stats()->GS_cash
                                )
                            ));
                            if($validation->passed()){
                                $user->set(array(
                                    "GS_bullets"    => $user->stats()->GS_bullets - Input::get('bullet')
                                ));
                                $user->setTimer('donate', 30*60);
                                $crew->update(array(
                                    "C_bullets"    => $crew->data()->C_bullets + Input::get('bullet')
                                ));
                                Session::put('success', "You donated your crew by ".number_format(Input::get('bullet'))." Bullets.");
                            }else{
                                $err = array();
                                foreach ($validation->errors() as $error) {
                                    $err = $error;
                                }
                                Session::flash('error', $err);
                            }
                        }else{
                            Session::flash('info', 'You have to wait <span id="donate"></span>.');
                        }
                    }else{
                        Session::flash('error', 'The crew you are trying to donate for is not exist.');
                    }
                }
            }
        }
        Redirect::to("/crews/bank");
    }

    public function manage()
    {
        $user = Model::get('User');
        if($user->stats()->GS_crewLevel == 6){
            $crew = Model::get('Crew');
            $crew->find($user->stats()->GS_crew);

            $this->view('crews/manage', array(
                'crew' => $crew
            ));
        }else{
            Session::flash("error", 'You have no permission to enter this page.');
            Redirect::to("/crews");
        }
    }
}