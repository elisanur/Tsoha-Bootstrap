<?php

class BaseModel {

    protected $validators;

    public function __construct($attributes = null) {
        
        foreach ($attributes as $attribute => $value) {
            if (property_exists($this, $attribute)) {
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {
        $errors = array();
        foreach ($this->validators as $validator) {
            $errors = array_merge($errors, $this->{$validator}());
        }

        return array_filter($errors);
    }

    public function validate_string_length($name, $string, $length) {

        $string=trim($string);
        
        $errors = array();
        if ($string == '' || $string == null) {
            $errors[] = $name . ' cannot be empty';
        }
        if (strlen($string) < $length) {
            $errors[] = $name . ' cannot contain white spaces and should be atleast ' . $length . ' characters long!';
        }

        return $errors;
    }

    public function validate_string_length_exact($name, $string, $length) {

        $errors = array();

        if (strlen($string) != $length) {
            $errors[] = $name . ' should be ' . $length . ' characters long!';
        }

        return $errors;
    }

    public function validate_whole_number($name, $val) {
        $errors = array();

        if (is_numeric($val) && floor($val) == $val) {
            if ((string) $val === (string) 0){
                
            }

            elseif (ltrim((string) $val, '0') === (string) $val){
                
            }
          
        } else {
            $errors[] = $name . ' should be a whole number';
        }

        return $errors;
    }
    
    public function validate_numeric($name, $val){
        $errors = array();
        
        if (preg_match("/[^0-9]/", $val)){
            $errors[] =  $name." contained invalid characters, only digits 0-9 allowed";
        }
        
        return $errors;
    }
    
    public function validate_characters($name, $string) {
        $errors = array();
        
        if (preg_match("/[^A-Za-z\å\ä\ö\Å\Ä\Ö\' ']/", $string)){
            $errors[] =  $name." contained invalid characters, only characters A-Z, Å, Ä, Ö and a-z, å, ä, ö allowed";
        }
        return $errors;
    }
}
