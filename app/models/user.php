<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User extends BaseModel {

    public $id, $firstname, $lastname, $address, $postalcode, $city, $name, $password;

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
}
