<?php


class Bank
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('transactions', $fields)) {
            throw new Exception("There was a problem inserting the transaction");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('transactions',$where, $fields)) {
            throw new Exception("There was a problem updating the transaction");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('transactions', $fields)) {
            throw new Exception("There was a problem deleting the transaction");
        }
    }

    public function toUser($user)
    {
        $toTransactions = array();
        $transactionsTo = $this->_db->get("transactions", array(
            array('T_from', '=', $user)
        ), 'id', 'desc limit 5');
        foreach($transactionsTo->results() as $to){
            $toUser = Model::get('User');
            $toUser->find($to->T_to);

            $toTransactions[] = array(
                "amount" => $to->T_amount,
                "date"   => $to->T_date,
                "user"   => $toUser
            );
        }
        return $toTransactions;
    }

    public function fromUser($user)
    {
        $fromTransactions = array();
        $transactionsFrom = $this->_db->get("transactions", array(
            array('T_to', '=', $user)
        ), 'id', 'desc limit 5');
        foreach($transactionsFrom->results() as $from){
            $fromUser = Model::get('User');
            $fromUser->find($from->T_from);

            $fromTransactions[] = array(
                "amount" => $from->T_amount,
                "date"   => $from->T_date,
                "user"   => $fromUser
            );
        }
        return $fromTransactions;
    }
}