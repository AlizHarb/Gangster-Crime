<?php


class Smuggling extends Controller
{

    public function __construct()
    {
        parent::__construct(true, true, true);
    }

    public function index()
    {
        $items = array(
            0 => array(
                "name"  => "Fake Diamond",
                "img"   => "diamond.svg"
            ),
            1 => array(
                "name"  => "Crate of Booze",
                "img"   => "crateofbooze.png"
            ),
            2 => array(
                "name"  => "Case of Cigarettes",
                "img"   => "cigarette.svg"
            ),
            3 => array(
                "name"  => "Gold Bullion",
                "img"   => "gold.svg"
            ),
            4 => array(
                "name"  => "Counterfeit Goods",
                "img"   => "counterfeit.svg"
            ),
            5 => array(
                "name"  => "Jewellery",
                "img"   => "jewellery.svg"
            ),
            6 => array(
                "name"  => "Electronics",
                "img"   => "electronics.svg"
            ),
            7 => array(
                "name"  => "Stolen Artwork",
                "img"   => "artwork.svg"
            )
        );
        $this->view('smuggling', array(
            "items" => $items
        ));
    }

    public function action()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $user = $this->model('User');
                //$smuggling = explode('-', $user->stats()->GS_smuggling);

                if(Input::get('buy')){
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'amount_0'	=> array(
                            'fieldName'	=> 'Fake Diamond',
                            'number'    => true,
                            'minNumber' => 0
                        ),
                        'amount_1'	=> array(
                            'fieldName'	=> 'Crate of Booze',
                            'number'    => true,
                            'minNumber' => 0
                        ),
                        'amount_2'	=> array(
                            'fieldName'	=> 'Case of Cigarettes',
                            'number'    => true,
                            'minNumber' => 0
                        ),
                        'amount_3'	=> array(
                            'fieldName'	=> 'Gold Bullion',
                            'number'    => true,
                            'minNumber' => 0
                        ),
                        'amount_4'	=> array(
                            'fieldName'	=> 'Counterfeit Goods',
                            'number'    => true,
                            'minNumber' => 0
                        ),
                        'amount_5'	=> array(
                            'fieldName'	=> 'Jewellery',
                            'number'    => true,
                            'minNumber' => 0
                        ),
                        'amount_6'	=> array(
                            'fieldName'	=> 'Electronics',
                            'number'    => true,
                            'minNumber' => 0
                        ),
                        'amount_7'	=> array(
                            'fieldName'	=> 'Stolen Artwork',
                            'number'    => true,
                            'minNumber' => 0
                        ),
                    ));
                    if($validation->passed()){
                        Session::flash('success', 'You have successfully bought.');
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }

                if(Input::get('sell')){
                    die('sell');
                }
                Redirect::to('/smuggling');
            }
        }
    }
}