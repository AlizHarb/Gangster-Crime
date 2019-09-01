<?php


class Mail
{

    private $_db,
            $_count = 0,
            $_data;

    public function __construct()
    {
        $this->_db 	= Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('mail', $fields)) {
            throw new Exception("There was a problem sending the message");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('mail', $where, $fields)) {
            throw new Exception("There was a problem updating the message");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('mail', $fields)) {
            throw new Exception("There was a problem deleting the message");
        }
    }

    public function userMail()
    {
        $user = Model::get('User');

        $messages = array();
        $messaging = $this->_db->get("mail", array(
            array("M_toUser", "=", $user->data()->id),
            array('M_saved', "<>", 1)
        ), "M_date", "desc limit 200");
        $this->_count = $messaging->count();
        foreach($messaging->results() as $message){
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
        return $messages;
    }

    public function savedMail()
    {
        $user = Model::get('User');

        $messages = array();
        $messaging = $this->_db->get("mail", array(
            array("M_toUser", "=", $user->data()->id),
            array('M_saved', "=", 1)
        ), "M_date", "desc limit 200");
        $this->_count = $messaging->count();
        foreach($messaging->results() as $message){
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
        return $messages;
    }

    public function checkMail()
    {
        $user = Model::get('User');

        $mail = $this->_db->get("mail", array(
            array('M_toUser', '=', $user->data()->id),
            array('M_read', '=', 0)
        ));
        if($mail->count()){
            return true;
        }
        return false;
    }

    public function getMail($id, $user)
    {
        $message = $this->_db->get("mail", array(
            array('id', '=', $id),
            array('M_toUser', '=', $user),
        ));
        if($message->count()){
            $this->_data = $message->first();
            return true;
        }
        return false;
    }

    public function count()
    {
        return $this->_count;
    }

    public function data()
    {
        return $this->_data;
    }
}