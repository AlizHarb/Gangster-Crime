<?php


class Theft
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('autotheft', $fields)) {
            throw new Exception("There was a problem inserting the auto theft");
        }
    }

    public function insert($fields = array())
    {
        if (!$this->_db->insert('garage', $fields)) {
            throw new Exception("There was a problem inserting the car in garage");
        }
    }

    public function update($where, $fields = array())
    {
        if (!$this->_db->update('autotheft',$where, $fields)) {
            throw new Exception("There was a problem updating the auto theft");
        }
    }

    public function delete($fields = array())
    {
        if (!$this->_db->delete('autotheft', $fields)) {
            throw new Exception("There was a problem deleting your auto theft");
        }
    }

    public function find($theft)
    {
        if ($theft) {
            $fields = (is_numeric($theft)) ? 'id' : 'AT_name';
            $data 	= $this->_db->get('autotheft', array(
                array($fields, '=', $theft)
            ));
            if ($data->count()) {
                return $data->first();
            }
        }
        return false;
    }

    public function car($theft)
    {
        if ($theft) {
            $fields = (is_numeric($theft)) ? 'id' : 'C_name';
            $data 	= $this->_db->get('cars', array(
                array($fields, '=', $theft)
            ));
            if ($data->count()) {
                return $data->first();
            }
        }
        return false;
    }

    public function all()
    {
        $all = array();
        $data = $this->_db->get("autotheft", array(
            array("id", '>', 0)
        ));
        if($data->count()){
            foreach($data->results() as $row){
                $all[] = array(
                    "id"         => $row->id,
                    "name"       => $row->AT_name,
                    "chance"     => $row->AT_chance,
                    "damage"     => $row->AT_maxDamage,
                    "worst"      => $row->AT_worstCar,
                    "best"       => $row->AT_bestCar
                );
            }
            return $all;
        }
        return false;
    }

    public function cars($where)
    {
        $all = array();
        $data = $this->_db->get("cars", $where);
        if($data->count()){
            foreach($data->results() as $row){
                $all[] = array(
                    "id"       => $row->id,
                    "name"     => $row->C_name,
                    "price"    => $row->C_price,
                    "chance"   => $row->C_theftChance,
                    "img"      => $row->C_img
                );
            }
            return $all;
        }
        return false;
    }

    public function userCars()
    {
        $user       = Model::get('User');
        $location   = Model::get('Location');

        $garage = array();
        $data = $this->_db->get('garage', array(
            array('GA_user', '=', $user->data()->id)
        ));
        if($data->count()){
            foreach($data->results() as $cars){
                $car = $this->car($cars->GA_car);
                $currentLocation = Model::get('Location');
                $currentLocation->find($cars->GA_currentLocation);
                $now = Model::get('Location');
                $now->find($cars->GA_nowLocation);
                if($cars->GA_shipTo && $cars->GA_shipTime > time()){
                    $ship = Model::get('Location');
                    $ship->find($cars->GA_shipTo);
                    $shipLocation = $ship->data()->L_name;
                }else{
                    $shipLocation = "";
                }

                $multi = (100 - $cars->GA_damage) /100;
                $value = round(($car->C_price * $multi));

                $garage[] = array(
                    "id"        => $car->id,
                    "name"      => $car->C_name,
                    "damage"    => $cars->GA_damage,
                    "price"     => $value,
                    "location"  => $currentLocation->data()->L_name,
                    "now"       => $now->data()->L_name,
                    "ship"      => $shipLocation,
                    "time"      => $cars->GA_shipTime
                );
            }
            return $garage;
        }
        return false;
    }

    public function skill()
    {
        $user = Model::get('User');
        $expIntoNextSkill =  $user->stats()->GS_theftExp - 6000;
        $expNeededForNextSkill = ($user->stats()->GS_theftExp / 6) - $user->stats()->GS_theftExp;
        $skillExp = round($expIntoNextSkill / $expNeededForNextSkill * 100, 2);

        if($expIntoNextSkill < 1000){
            $skill = "Apprentice";
        }elseif($expIntoNextSkill < 2000){
            $skill = "Amateur";
        }elseif($expIntoNextSkill < 3000){
            $skill = "Experienced";
        }elseif($expIntoNextSkill < 4000){
            $skill = "Proficient";
        }elseif($expIntoNextSkill < 5000){
            $skill = "Professional";
        }else{
            $skill = "Master";
        }
        $skills = array(
            "exp"   => $skillExp,
            "name" => $skill
        );
        return $skills;
    }
}