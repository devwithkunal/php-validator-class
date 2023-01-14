<?php

class Validator{

    /**
     * @var array $data - Data to validate.
     */
    private $data;

    /**
     * @var string $current_field - Current selected key/field to validae data.
     */
    private $current_field;

    /**
     * @var array $response_messages - Error messages to show user.
     * 
     * You can change messages from here. User "{field}" to refer the field name.
     */
    private $response_messages = [
        "required" => "{field} is required.",
        "alpha" => "{field} must contains alphabatic charectors only.",
        "alpha_num" => "{field} must contains alphabatic charectors & numbers only.",
        "numeric" => "{field} must contains numbers only.",
        "email" => "Invalid email address",
        "max_len" => "{field} is too long.",
        "min_len" => "{field} is too short.",
        "max_val" => "{field} is too high.",
        "min_val" => "{field} is too low.",
        "enum" => "{field} is invalid.",
        "equals" => "{field} does not match.",
        "must_contain" => "{field} must contains {chars}.",
        "match" => "{field} is invalid.",
        "date" => "Date is invalid.",
        "date_after" => "{field} date is not valid.",
        "date_before" => "{field} date is not valid.",
    ];

    /**
     * @var array $error_messages - Error message generated after validation of each field.
     */
    public $error_messages = [];

    /**
     * @var boolean $next - Check if next validation on the field shoud run or not.
     */
    private $next = true;

    /**
     * Validator - Create new instance of Validator class.
     * 
     * @param array $data - Data to validate.
     * @return object Validator
     */
    function __construct($data){
        $this->data = $data;
    }

    /**
     * add_error_message - Create and add error message after each validation faild.
     * 
     * @param string $type - Key of $response_messages array.
     * @return void
     */
    private function add_error_message($type, $others = []){
        $msg = str_replace('{field}', ucfirst($this->current_field), $this->response_messages[$type]);
        foreach($others as $key => $val){
            $msg = str_replace('{'.$key.'}', $val, $msg);
        }
        array_push($this->error_messages, $msg);
    }
    
    /**
     * exists - Check if the current field or field value exists or not.
     * 
     * @return boolean
     */
    private function exists(){
        if(!isset($this->data[$this->current_field]) || !$this->data[$this->current_field]){
            return false;
        }
        return true;
    }

    /**
     * set_response_messages - Function to set/extend custom error response messages.
     * 
     * @param array $mesages
     * @return void
     */
    function set_response_messages($messages){
        foreach($messages as $key => $val){
            $this->response_messages[$key] = $val;
        }
    }

    /**
     * field - Set the field name to start validation.
     * 
     * @param string $name - Name of the field/key as on data to validate.
     * @return this
     */
    function field($name){
        $this->current_field = $name;
        $this->next = true;
        return $this;
    }
    
    /**
     * required - Check if the value exists.
     * 
     * @return this
     */
    function required(){
        if(!$this->exists()){
            $this->add_error_message('required');
            $this->next = false;
        }
        return $this;
    }

    /**
     * alpha - Check if the value is alpha only.
     * 
     * @param array $ignore - (Optional) add charectors to allow.
     * @return this
     */
    function alpha($ignore = []){
        if($this->next && $this->exists() && !ctype_alpha(str_replace($ignore, '', $this->data[$this->current_field]))){
            $this->add_error_message('alpha');
            $this->next = false;
        }
        return $this;
    }

    /**
     * alpha_num - Check if the value is alpha numeric only.
     * 
     * @param array $ignore - (Optional) add charectors to allow.
     * @return this
     */
    function alpha_num($ignore = []){
        if($this->next && $this->exists() && !ctype_alnum(str_replace($ignore, '', $this->data[$this->current_field]))){
            $this->add_error_message('alpha_num');
            $this->next = false;
        }
        return $this;
    }

    /**
     * numeric - Check if the value is numeric only.
     * 
     * @return this
     */
    function numeric(){
        if($this->next && $this->exists() && !is_numeric($this->data[$this->current_field])){
            $this->add_error_message('numeric');
            $this->next = false;
        }
        return $this;
    }

    /**
     * email - Check if the value is a valid email.
     * 
     * @return this
     */
    function email(){
        if($this->next && $this->exists() && !filter_var($this->data[$this->current_field], FILTER_VALIDATE_EMAIL)){
            $this->add_error_message('email');
            $this->next = false;
        }
        return $this;
    }

    /**
     * max_len - Check if length of the value is larger than the limit.
     * 
     * @param int $size - Max length of charectors of the value.
     * @return this
     */
    function max_len($size){
        if($this->next && $this->exists() && strlen($this->data[$this->current_field]) > $size){
            $this->add_error_message('max_len');
            $this->next = false;
        }
        return $this;
    }

     /**
     * min_len - Check if length of the value is smaller than the limit.
     * 
     * @param int $size - Min length of charectors of the value.
     * @return this
     */
    function min_len($size){
        if($this->next && $this->exists() && strlen($this->data[$this->current_field]) < $size){
            $this->add_error_message('min_len');
            $this->next = false;
        }
        return $this;
    }

    /**
     * max_val - Check if the value of intiger/number is not larger than limit.
     * 
     * @param int $val - Max value of the number.
     * @return this
     */
    function max_val($val){
        if($this->next && $this->exists() && $this->data[$this->current_field] > $val){
            $this->add_error_message('max_val');
            $this->next = false;
        }
        return $this;
    }

    /**
     * min_val - Check if the value of intiger/number is not smaller than limit.
     * 
     * @param int $val - Min value of the number.
     * @return this
     */
    function min_val($val){
        if($this->next && $this->exists() && $this->data[$this->current_field] < $val){
            $this->add_error_message('min_val');
            $this->next = false;
        }
        return $this;
    }

    /**
     * enum - Check if the value is in the list.
     * 
     * @param array $list - List of valid values.
     * @return this
     */
    function enum($list){
        if($this->next && $this->exists() && !in_array($this->data[$this->current_field], $list)){
            $this->add_error_message('enum');
            $this->next = false;
        }
        return $this;
    }

    /**
     * equals - Check if the value is equal.
     * 
     * @param mixed $value - Value to match equal.
     * @return this
     */
    function equals($value){
        if($this->next && $this->exists() && !$this->data[$this->current_field] == $value){
            $this->add_error_message('equals');
            $this->next = false;
        }
        return $this;
    }

    /**
     * date - Check if the value is a valid date.
     * 
     * @param mixed $format - format of the date. (ex. Y-m-d) Check out https://www.php.net/manual/en/datetime.format.php for more.
     * @return this
     */
    function date($format = 'Y-m-d'){
        if($this->next && $this->exists()){
            $dateTime = DateTime::createFromFormat($format, $this->data[$this->current_field]);
            if(!($dateTime && $dateTime->format($format) == $this->data[$this->current_field])){
                $this->add_error_message('date');
                $this->next = false;
            }
        }
        return $this;
    }

    /**
     * date_after - Check if the date appeared after the specified date.
     * 
     * @param mixed $date - Use format Y-m-d (ex. 2023-01-15).
     * @return this
     */
    function date_after($date){
        if($this->next && $this->exists() && strtotime($date) >= strtotime($this->data[$this->current_field])){
            $this->add_error_message('date_after');
            $this->next = false;
        }
        return $this;
    }

    /**
     * date_before - Check if the date appeared before the specified date.
     * 
     * @param mixed $date - Use format Y-m-d (ex. 2023-01-15).
     * @return this
     */
    function date_before($date){
        if($this->next && $this->exists() && strtotime($date) <= strtotime($this->data[$this->current_field])){
            $this->add_error_message('date_before');
            $this->next = false;
        }
        return $this;
    }

    /**
     * must_contain - Check if the value must contains some charectors.
     * 
     * @param string $chars - Set of chars in one string ex. "@#$&abc123".
     * @return this
     */
    function must_contain($chars){
        if($this->next && $this->exists() && !preg_match("/[".$chars."]/i", $this->data[$this->current_field])){
            $this->add_error_message('must_contain', ['chars' => $chars]);
            $this->next = false;
        }
        return $this;
    }

    /**
     * match - Check if the value matchs a pattern.
     * 
     * @param string $patarn - Rejex pattern to match.
     * @return this
     */
    function match($patarn){
        if($this->next && $this->exists() && !preg_match($patarn, $this->data[$this->current_field])){
            $this->add_error_message('match');
            $this->next = false;
        }
        return $this;
    }

    /**
     * is_valid - Check if all validations is successfull.
     * 
     * @return boolean
     */
    function is_valid(){
        return count($this->error_messages) == 0;
    }

    
}