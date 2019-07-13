<?php


class Mail
{

    private $_db;

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
}