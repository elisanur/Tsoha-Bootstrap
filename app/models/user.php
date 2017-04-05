<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User extends BaseModel {

    public $id, $firstname, $lastname, $address, $postalcode, $city, $name, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_address', 'validate_city', 'validate_firstName', 'validate_lastName', 'validate_password', 'validate_postalCode', 'validate_username');
    }

    public static function authenticate($username, $pass) {
        $query = DB::connection()->prepare('SELECT * FROM Username WHERE name = :name AND password = :password LIMIT 1');
        $query->execute(array('name' => $username, 'password' => $pass));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'address' => $row['address'],
                'postalcode' => $row['postalcode'],
                'city' => $row['city'],
                'name' => $row['name'],
                'password' => $row['password']
            ));
            return $user;
        } else {
            return null;
        }
    }

    public static function find($user_id) {
        $query = DB::connection()->prepare('SELECT * FROM Username WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $user_id));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'address' => $row['address'],
                'postalcode' => $row['postalcode'],
                'city' => $row['city'],
                'name' => $row['name'],
                'password' => $row['password']
            ));

            return $user;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Username(firstname, lastname, address, postalcode, city, name, password) VALUES (:firstname, :lastname, :address, :postalcode, :city, :name, :password) RETURNING id');
        $query->execute(array('firstname' => $this->firstname, 'lastname' => $this->lastname, 'address' => $this->address, 'postalcode' => $this->postalcode, 'city' => $this->city, 'name' => $this->name, 'password' => $this->password));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function update(){
        $query = DB::connection()->prepare('UPDATE Username SET firstname = :firstname, '
                . 'lastname = :lastname, address = :address, postalcode = :postalcode, '
                . 'city = :city, name = :name, password = :password WHERE id = :id');
        $query->execute(array('id' => $this->id, 'firstname' => $this->firstname, 'lastname' => $this->lastname, 
            'address' => $this->address, 'postalcode' => $this->postalcode, 
            'city' => $this->city, 'name' => $this->name, 'password' => $this->password));
        
    }
    
    public function destroy(){
        $query = DB::connection()->prepare('DELETE FROM PosterCategory pc USING Poster p, Username u WHERE u.id=p.publisher AND p.id=pc.poster AND u.id = :id');
        $query->execute(array('id' => $this->id ));
        print 'eka';
        
        $query = DB::connection()->prepare('DELETE FROM Purchase pc WHERE pc.username=:id');
        $query->execute(array('id' => $this->id ));
        print 'toka';
      
        $query = DB::connection()->prepare('DELETE FROM Poster p USING Username u WHERE u.id=p.publisher AND u.id = :id');
        $query->execute(array('id' => $this->id ));
        print 'kolmas';
       
        $query = DB::connection()->prepare('DELETE FROM Username u WHERE u.id = :id');
        $query->execute(array('id' => $this->id ));
        print 'neljäs';
        
    }

    public function validate_username() {
        return parent::validate_string_length('Username', $this->name, 5);
    }
    
    public function validate_firstName() {
        return parent::validate_string_length('First name', $this->firstname, 2);
    }
    
    public function validate_lastName() {
        return parent::validate_string_length('Last name', $this->lastname, 2);
    }
    
    public function validate_address() {
        return parent::validate_string_length('Address', $this->address, 5);
    }
    
    public function validate_postalCode() {
        return parent::validate_string_length_exact('Postalcode', $this->postalcode, 5);
    }
    
    public function validate_password() {
        return parent::validate_string_length('Password', $this->password, 8);
    }
    
    public function validate_city() {
        return parent::validate_string_length('City', $this->city, 2);
    }

}
