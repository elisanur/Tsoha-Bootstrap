<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
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
        if ($string == '' || $this->name == null) {
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

}
