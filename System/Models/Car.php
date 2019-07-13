<?php


class car
{

    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('cars', $fields)) {
            throw new Exception("There was a problem inserting the car");
        }
    }

    public function insert($fields = array())
    {
        if (!$this->_db->insert('garage', $fields)) {
            throw new Exception("There was a problem inserting in garage");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('cars', $where, $fields)) {
            throw new Exception("There was a problem updating the car");
        }
    }

    public function edit($where, $fields = array())
    {
        if (!$this->_db->update('garage', $where, $fields)) {
            throw new Exception("There was a problem editing garage");
        }
    }

    public function remove($fields = array())
    {
        if (!$this->_db->delete('garage', $fields)) {
            throw new Exception("There was a problem deleting from garage");
        }
    }

    public function getCar($car)
    {
        (is_numeric($car) ? $field = "id" : $field = "C_name");
        $car = $this->_db->get("cars", array(
            array($field, '=', $car)
        ));
        if($car->count()){
            return $car->first();
        }else{
            return false;
        }
    }

    public function checkUserCars($user)
    {
        $mail       = Model::get('Mail');
        $location   = Model::get('Location');
        $garage = $this->_db->get('garage', array(
            array("GA_user", '=', $user),
            array('GA_shipTime', '<=', time())
        ));
        foreach($garage->results() as $car){
            if($car->GA_nowLocation !== $car->GA_shipTo){
                $this->_db->update('garage', 'id ='.$car->id, array(
                    "GA_nowLocation" => $car->GA_shipTo
                ));
                $mail->create(array(
                    "M_toUser"      => $user,
                    "M_fromUser"    => 0,
                    "M_text"        => "Your car had successfully been shipped to {$location->getLocation($car->GA_shipTo)->L_name}!",
                    "M_date"        => date('Y-m-d H:i:s')
                ));
            }
        }
    }
}