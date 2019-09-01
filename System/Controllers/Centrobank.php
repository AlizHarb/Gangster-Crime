<?php


class Centrobank extends Controller
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();

        parent::__construct(true, true, true);
    }

    public function index()
    {
        $user = Model::get('User');
        $bank = Model::get('Bank');

        $outGoing = $this->_db->countAll("transactions", array(
            'T_amount' => 'money',
        ), "where T_from = ".$user->data()->id);

        $inComing = $this->_db->countAll("transactions", array(
            'T_amount' => 'money',
        ), "where T_to = ".$user->data()->id);

        $this->view('bank/main',array(
            "toTransaction"     => $bank->toUser($user->data()->id),
            'fromTransaction'   => $bank->fromUser($user->data()->id),
            'outGoing'          => $outGoing,
            'inComing'          => $inComing
        ));
    }

    public function calculator()
    {
        if(Input::exists()){
            if(Token::check(Input::get('token'))){
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'cash'	=> array(
                        'fieldName'	=> 'Cash',
                        'required' 	=> true,
                        'number'    => true,
                        'minNumber' => 1
                    ),
                    'days'	=> array(
                        'fieldName'	=> 'Days',
                        'required' 	=> true,
                        'number'    => true,
                        'minNumber' => 1
                    )
                ));
                if($validation->passed()){
                    $interest = (Input::get("cash") < 1000000000 ? $interest = 0.30 : $interest = 0.01);
                    $result = (Input::get("cash") * $interest) * Input::get('days');
                    Session::flash("info", 'After depositing $'.number_format(Input::get('cash')).' for '.number_format(Input::get('days')).' days you will gain a total of: $'.number_format($result));
                }else{
                    $err = array();
                    foreach ($validation->errors() as $error) {
                        $err = $error;
                    }
                    Session::flash('error', $err);
                }
            }
        }
        Redirect::to('centrobank');
    }

    public function deposit()
    {
        if(Input::exists()) {
            if (Token::check(Input::get('token'))) {
                $user = Model::get('User');
                if($user->timer('bank') <= time()){
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'cash'	=> array(
                            'fieldName'	=> 'Cash',
                            'required' 	=> true,
                            'number'    => true,
                            'minNumber' => 100,
                            'maxNumber' => $user->stats()->GS_cash
                        )
                    ));
                    if($validation->passed()){
                        Session::flash('success', 'You deposited $'.number_format(Input::get('cash')).' into your Bank Account.');
                        $user->set(array(
                            "GS_bank" => Input::get('cash'),
                            "GS_cash" => $user->stats()->GS_cash - Input::get('cash'),
                        ));
                        $user->timer('bank', 24*60*60);
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }else{
                    Session::flash('error', 'You have already put some money in the bank!');
                }
            }
        }
        Redirect::to('centrobank');
    }

    public function withdraw()
    {
        if(Input::exists()) {
            if (Token::check(Input::get('token'))) {
                $user = Model::get('User');
                if($user->timer('bank') > time()){
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'cash'	=> array(
                            'fieldName'	=> 'Cash',
                            'required' 	=> true,
                            'number'    => true,
                            'minNumber' => 1,
                            'maxNumber' => $user->stats()->GS_bank
                        )
                    ));
                    if($validation->passed()){
                        Session::flash('success', 'You withdrew $'.number_format(Input::get('cash')).' from your Bank Account.');
                        if(Input::get('cash') >= $user->stats()->GS_bank){
                            $user->timer('bank', 0);
                        }
                        $user->set(array(
                            "GS_bank" => $user->stats()->GS_bank - Input::get('cash'),
                            "GS_cash" => $user->stats()->GS_cash + Input::get('cash')
                        ));
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }else{
                    Session::flash('error', 'You do not have money in the bank!');
                }
            }
        }
        Redirect::to('centrobank');
    }

    public function transfer()
    {
        if(Input::exists()) {
            if (Token::check(Input::get('token'))) {
                $user = Model::get('User');
                $recipient = Model::get('User');
                $recipient->find(Input::get('recipient'));
                $bank = Model::get('Bank');
                if($recipient && $user->data()->id !== $recipient->data()->id){
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'amount'	=> array(
                            'fieldName'	=> 'Amount',
                            'required' 	=> true,
                            'number'    => true,
                            'minNumber' => 1,
                            'maxNumber' => $user->stats()->GS_cash
                        ),
                        'recipient' => array(
                            'fieldName' => 'Recipient',
                            'required'  => true
                        )
                    ));
                    if($validation->passed()){
                        Session::flash('success', 'You have sent $'.number_format(Input::get('amount')).' to '.$recipient->data()->name.'.');
                        $user->set(array(
                            "GS_cash" => $user->stats()->GS_cash - Input::get('amount')
                        ));
                        $amount = Input::get('amount') - ($user->stats()->GS_cash + Input::get('amount') * (12/100));
                        $recipient->set(array(
                            "GS_cash" => $user->stats()->GS_cash + $amount
                        ));
                        $bank->create(array(
                            "T_from"    => $user->data()->id,
                            "T_to"      => $recipient->data()->id,
                            "T_amount"  => Input::get('amount'),
                            "T_date"    => date('Y-m-d H:i:s')
                        ));
                    }else{
                        $err = array();
                        foreach ($validation->errors() as $error) {
                            $err = $error;
                        }
                        Session::flash('error', $err);
                    }
                }else{
                    Session::flash('error', 'The user you are trying to transfer money to is not exist.');
                }
            }
        }
        Redirect::to('centrobank');
    }
}