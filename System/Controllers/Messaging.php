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

        $this->view('messaging/main' ,array(
            'messages'  => $mail->userMail(),
            'count'     => $mail->count()
        ));
    }

    public function saved()
    {
        $mail = Model::get('Mail');

        $this->view('messaging/main' ,array(
            'messages'  => $mail->savedMail(),
            'count'     => $mail->count()
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
        Redirect::to('messaging/newMessage');
    }

    public function actions()
    {
        $user = Model::get('User');
        $mail = Model::get('Mail');
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $message = $mail->getMail(
                    Input::get('message'),
                    $user->data()->id
                );
                if($message){
                    if(Input::get('delete')){
                        $mail->delete(array(
                            array('id', '=', Input::get('message'))
                        ));
                        Session::flash('success', "You have successfully deleted the message");
                    }
                    if(Input::get('save')){
                        if($message->M_saved !== 1){
                            $mail->update("id = ".Input::get('message') ,array(
                                "M_saved" => 1
                            ));
                        }else{
                            Session::flash('error', 'You have already saved this message.');
                        }
                        Session::flash('success', "You have successfully saved the message");
                    }
                    if(Input::get('forward')){
                        $this->newMessage($message);
                    }
                }else{
                    Session::flash('error', 'The message you are trying to access is not exist.');
                }
            }
        }
        Redirect::to('messaging');
    }
}