<?php


class Admin extends Controller
{
    protected $_db;

    public function __construct()
    {
        parent::__construct(true, false, false);
    }

    public function index()
    {
        $this->view('admin/home/main');
    }

    public function settings()
    {
        if(Input::get('update'))
        {
            if(Input::exists()){
                if(Token::check(Input::get('token'))){
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'website_name'	=> array(
                            'fieldName'	=> 'Game Name',
                            'required' 	=> true,
                            'min'		=> 2,
                            'max'		=> 20
                        ),
                        'website_url'	=> array(
                            'fieldName'	=> 'Game Url',
                            'required' 	=> true,
                            'url'       => true
                        ),
                        'website_email' => array(
                            'fieldName'	=> 'Game Email',
                            'required' 	=> true,
                            'email'     => true
                        ),
                        'website_theme' => array(
                            'fieldName'	=> 'Game Theme',
                            'required' 	=> true,
                            'file'     => 'Public/Views/'
                        )
                    ));
                    if($validate->passed()){
                        $settings = Model::get('Settings');
                        $settings->update("setting_name = 'website_name'", array("setting_value" => Input::get('website_name')));
                        $settings->update("setting_name = 'website_url'", array("setting_value" => Input::get('website_url')));
                        $settings->update("setting_name = 'website_email'", array("setting_value" => Input::get('website_email')));
                        $settings->update("setting_name = 'website_theme'", array("setting_value" => basename(Input::get('website_theme'))));
                        Session::flash('success', "The game settings were successfully updated.");
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }
            }
            Redirect::to('admin/settings');
        }else{
            $themes = glob("Public/Views" . '/*' , GLOB_ONLYDIR);
            $this->view('admin/settings/main', array(
                "themes"    => $themes
            ));
        }
    }

    public function locations()
    {
        $location   = Model::get('Location');

        if(Input::get('new') == "show"){
            $this->view('admin/locations/new');
        }elseif(Input::get('new') == true){
            if(Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'location_name' => array(
                            'fieldName' => 'Location Name',
                            'required' => true,
                            'min' => 2,
                            'max' => 20
                        ),
                        'location_cost' => array(
                            'fieldName' => 'Location Cost',
                            'required' => true,
                            'number' => true
                        ),
                        'location_bullets' => array(
                            'fieldName' => 'Game Email',
                            'required' => true,
                            'number' => true
                        ),
                        'location_bullets_cost' => array(
                            'fieldName' => 'Location Bullets Cost',
                            'required' => true,
                            'number'    => true
                        ),
                        'location_time' => array(
                            'fieldName' => 'Location Time',
                            'required' => true,
                            'number'    => true
                        )
                    ));
                    if($validate->passed()){
                        $location->create(array(
                            "L_name"            => Input::get('location_name'),
                            "L_cost"            => Input::get('location_cost'),
                            "L_time"            => Input::get('location_time'),
                            "L_bullets"         => Input::get('location_bullets'),
                            "L_bulletsCost"     => Input::get('location_bullets_cost'),
                        ));
                        Session::flash('success', 'You have successfully added the location.');
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }
            }
            Redirect::to('admin/locations');
        }elseif(Input::get('edit') && $location->find(Input::get('edit'))){
            $location->find(Input::get('edit'));
            $this->view('admin/locations/edit', array(
                "location" => $location
            ));
        }elseif(Input::get('update') && $location->find(Input::get('update'))){
            $location->find(Input::get('update'));
            if(Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'location_name' => array(
                            'fieldName' => 'Location Name',
                            'required' => true,
                            'min' => 2,
                            'max' => 20
                        ),
                        'location_cost' => array(
                            'fieldName' => 'Location Cost',
                            'required' => true,
                            'number' => true
                        ),
                        'location_bullets' => array(
                            'fieldName' => 'Game Email',
                            'required' => true,
                            'number' => true
                        ),
                        'location_bullets_cost' => array(
                            'fieldName' => 'Location Bullets Cost',
                            'required' => true,
                            'number'    => true
                        ),
                        'location_time' => array(
                            'fieldName' => 'Location Time',
                            'required' => true,
                            'number'    => true
                        )
                    ));
                    if($validate->passed()){
                        $location->update("id = ".$location->data()->id, array(
                            "L_name"            => Input::get('location_name'),
                            "L_cost"            => Input::get('location_cost'),
                            "L_time"            => Input::get('location_time'),
                            "L_bullets"         => Input::get('location_bullets'),
                            "L_bulletsCost"     => Input::get('location_bullets_cost'),
                        ));
                        Session::flash('success', 'You have successfully updated the location.');
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }
            }
            Redirect::to('admin/locations');
        }elseif(Input::get('delete') && $location->find(Input::get('delete'))){
            $location->find(Input::get('delete'));
            $location->delete(array(
                array('id', '=', Input::get('delete'))
            ));
            Session::flash('success', 'You have successfully deleted the location.');
            Redirect::to('admin/locations');
        }else{
            $locations = $location->all(array('id', '<>', 0));
            $this->view('admin/locations/main', array(
                "locations" => $locations
            ));
        }
    }

    public function thefts()
    {
        $theft      = Model::get('Theft');
        $item       = Model::get('Item');
        if(Input::get('new') == 'show'){
            $this->view('admin/thefts/new', array(
                'cars'  => $theft->allCars(),
                'items' => $item->all()
            ));
        }elseif(Input::get('new') == true){
            if(Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'theft_name' => array(
                            'fieldName' => 'Theft Name',
                            'required' => true,
                            'min' => 2,
                            'max' => 40
                        ),
                        'theft_chance' => array(
                            'fieldName' => 'Theft Chance',
                            'required' => true,
                            'number' => true,
                            'minNumber'   => 1,
                            'maxNumber'   => 100
                        ),
                        'theft_max_damage' => array(
                            'fieldName' => 'Theft Max Damage',
                            'required' => true,
                            'number' => true,
                            'minNumber'   => 50,
                            'maxNumber'   => 100
                        ),
                        'theft_worst_car' => array(
                            'fieldName' => 'Theft Worst Car',
                            'required' => true
                        ),
                        'theft_best_car' => array(
                            'fieldName' => 'Theft Best Car',
                            'required' => true
                        )
                    ));
                    if($validate->passed()){
                        $items = '';
                        foreach (Input::get('theft_items') as $item){
                            $items = $items.'-'.$item;
                        }
                        $theft->create(array(
                            "AT_name"                   => Input::get('theft_name'),
                            "AT_chance"                 => Input::get('theft_chance'),
                            "AT_maxDamage"              => Input::get('theft_max_damage'),
                            "AT_worstCar"               => Input::get('theft_worst_car'),
                            "AT_bestCar"                => Input::get('theft_best_car'),
                            "AT_items"                  => $items,
                        ));
                        Session::flash('success', 'You have successfully added the theft.');
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }
            }
            Redirect::to('admin/thefts');
        }elseif(Input::get('edit') && $theft->find(Input::get('edit'))){
            $info = $theft->find(Input::get('edit'));
            $this->view('admin/thefts/edit', array(
                'cars'  => $theft->allCars(),
                'items' => $item->all(),
                'theft'     => $info
            ));
        }elseif(Input::get('delete') && $theft->find(Input::get('delete'))){
            $theft->find(Input::get('delete'));
            $theft->delete(array(
                array('id', '=', Input::get('delete'))
            ));
            Session::flash('success', 'You have successfully deleted the theft.');
            Redirect::to('admin/thefts');
        }else{
            $this->view('admin/thefts/main', array(
                "thefts"    => $theft->all(),
            ));
        }
    }
}