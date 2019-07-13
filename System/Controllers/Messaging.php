<?php


class Messaging extends Controller
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();

        parent::__construct(true, false, false);
    }

    public function index()
    {
        $user = Model::get('User');
        $mail = Model::get('Mail');

        $mail->update("M_read = 0 and M_toUser = ".$user->data()->id, array(
            "M_read" => 1
        ));

        $count = 0;
        $messages = array();
        $messaging = $this->_db->get("mail", array(
            array("M_toUser", "=", $user->data()->id),
            array('M_saved', "<>", 1)
        ), "M_date", "desc limit 200");
        if($messaging->count()){
            $count = $messaging->count();
            $messaging = $messaging->results();
            foreach($messaging as $message){
                $userInfo = Model::get('User');
                if($message->M_fromUser == 0){
                    $userInfo->find(0);
                    $userInfo->data()->profile = "System";
                    $userInfo->data()->name = "System";
                    $userInfo->data()->avatar = "public/assets/img/avatar_small.jpg";
                }else{
                    $userInfo->find($message->M_fromUser);
                }

                $messages[] = array(
                    "from"      => $userInfo,
                    "message"   => $message
                );
            }
        }

        $this->view('messaging/main' ,array(
            'messages'  => $messages,
            'count'     => $count
        ));
    }

    public function saved()
    {
        $user = Model::get('User');

        $count = 0;
        $messages = array();
        $messaging = $this->_db->get("mail", array(
            array("M_toUser", "=", $user->data()->id),
            array('M_saved', "=", 1)
        ), "M_date", "desc limit 200");
        if($messaging->count()){
            $count = $messaging->count();
            $messaging = $messaging->results();
            foreach($messaging as $message){
                $userInfo = Model::get('User');
                if($message->M_fromUser == 0){
                    $userInfo->find(0);
                    $userInfo->data()->profile = "System";
                    $userInfo->data()->name = "System";
                    $userInfo->data()->avatar = "public/assets/img/avatar_small.jpg";
                }else{
                    $userInfo->find($message->M_fromUser);
                }

                $messages[] = array(
                    "from"      => $userInfo,
                    "message"   => $message
                );
            }
            if(Input::get('get')){
                die('hi');
            }
        }

        $this->view('messaging/main' ,array(
            'messages'  => $messages,
            'count'     => $count
        ));
    }

    public function newMessage($message = null)
    {
        if($message) {
            $user = Model::get('User');
            $user->find($message->M_fromUser);
            $this->view('messaging/send', array(
                "message" => $message,
                'userInfo' => $user
            ));
        }else{
            $this->view('messaging/send');
        }
    }

    public function send()
    {
        if(Input::exists()) {
            if (Token::check(Input::get('token'))) {
                $user = Model::get('User');
                $userTo = Model::get('User');
                $userTo->find(Input::get('recipient'));
                $mail = Model::get('Mail');
                if($userTo && $user->data()->id !== $userTo->data()->id){
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'recipient'	=> array(
                            'fieldName'	=> 'Recipient',
                            'required' 	=> true
                        ),
                        'text'	=> array(
                            'fieldName'	=> 'Message Text',
                            'required' 	=> true,
                        )
                    ));
                    if($validation->passed()){
                        $mail->create(array(
                            "M_toUser"      => $userTo->data()->id,
                            "M_fromUser"    => $user->data()->id,
                            "M_text"        => Input::get('text'),
                            "M_date"        => date('Y-m-d H:i:s')
                        ));
                        Session::flash('success', 'You have successfully sent a message to '.$userTo->data()->name);
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }else{
                    Session::flash('error', 'The user you are trying to send message to is not exist');
                }
            }
        }
        Redirect::to('/messaging/newMessage');
    }

    public function action()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = Model::get('User');
                $mail = Model::get('Mail');
                $message = $this->_db->get("mail", array(
                    array('id', '=', Input::get('message')),
                    array('M_toUser', '=', $user->data()->id),
                ));
                if($message->count()){
                    $message = $message->first();
                    if(Input::get('delete')){
                        $mail->delete(array(
                            array('id', '=', Input::get('message'))
                        ));
                        Session::flash('success', "You have successfully deleted the message");
                    }elseif(Input::get('save')){
                        if($message->M_saved !== 1){
                            $mail->update("id = ".Input::get('message') ,array(
                                "M_saved" => 1
                            ));
                        }else{
                            Session::flash('error', 'You have already saved this message.');
                        }
                        Session::flash('success', "You have successfully saved the message");
                    }elseif(Input::get('forward')){
                        $this->newMessage($message);
                    }
                }else{
                    Session::flash('error', 'The message you are trying to access is not exist.');
                }
            }
        }
        Redirect::to('/messaging');
    }
}