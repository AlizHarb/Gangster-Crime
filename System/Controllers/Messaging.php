<?php


class Messaging extends Controller
{

    public function __construct($login = true, $jail = false, $hospital = false)
    {
        parent::__construct(true, false, false);
    }

    public function index()
    {
        $user = $this->model('User');
        $mail = $this->model('Mail');

        $mail->update("M_read = 0 and M_toUser = ".$user->data()->id, array(
            "M_read" => 1
        ));

        $count = 0;
        $messages = array();
        $messaging = Database::getInstance()->get("mail", array(
            array("M_toUser", "=", $user->data()->id),
            array('M_saved', "<>", 1)
        ), "M_date", "desc limit 200");
        if($messaging->count()){
            $count = $messaging->count();
            $messaging = $messaging->results();
            foreach($messaging as $message){
                $userInfo = $this->model('User');
                if($message->M_fromUser == 0){
                    $userInfo->find(0);
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

    public function newMessage($message = null)
    {
        if($message) {
            $user = $this->model('User');
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
                $user = $this->model('User');
                $userTo = $this->model('User');
                $userTo->find(Input::get('recipient'));
                $mail = $this->model('Mail');
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
                $user = $this->model('User');
                $mail = $this->model('Mail');
                $message = Database::getInstance()->get("mail", array(
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