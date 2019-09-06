<?php


class Validate
{

    private $_passed = false,
            $_errors = array(),
            $_db = null;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value 	= trim($source[$item]);
                $item 	= escape($item);

                if($rule === 'fieldName'){
                    $input = $rule_value;
                }

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$input} is required");
                }elseif ($rule === 'number' && !is_numeric($value)) {
                    $this->addError("{$input} must be a number");
                }elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$input} must be a minimum of {$rule_value} characters");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$input} must be no longer than {$rule_value} characters");
                            }
                            break;
                        case 'minNumber':
                            if ($value < $rule_value) {
                                $this->addError("{$input} must be higher than ".number_format($rule_value));
                            }
                            break;
                        case 'maxNumber':
                            if ($value > $rule_value) {
                                $this->addError("{$input} must be less than ".number_format($rule_value));
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                            $this->addError("   {$rule_value} must match {$input}");
                            }
                            break;
                        case 'file':
                            if(!file_exists($value)){
                                $this->addError($input." is not exist in ".$rule_value);
                            }
                            break;
                        case 'url':
                            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                                $this->addError("   Please enter a valid url");
                            }
                            break;
                        case 'email':
                            $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
                            if (preg_match($pattern, $value) !== 1) {
                                $this->addError("Please enter a valid email");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array(
                                array($item, '=' , $value))
                            );
                            if ($check->count()) {
                                $this->addError("{$input} already exists");
                            }
                            break;
                    }
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }

    private function addError($error)
    {
        $this->_errors[] = $error;
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return $this->_passed;
    }
}