<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User extends BaseModel {

    public $id, $firstName, $lastName, $address, $postalCode, $city, $name, $password;

    public static function authenticate($username, $pass) {
        $query = DB::connection()->prepare('SELECT * FROM Username WHERE name = :name AND password = :password LIMIT 1');
        $query->execute(array('name' => $username, 'password' => $pass));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'firstName' => $row['firstName'],
                'lastName' => $row['lastName'],
                'address' => $row['address'],
                'postalCode' => $row['postalCode'],
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
                'firstName' => $row['firstName'],
                'lastName' => $row['lastName'],
                'address' => $row['address'],
                'postalCode' => $row['postalCode'],
                'city' => $row['city'],
                'name' => $row['name'],
                'password' => $row['password']
            ));

            return $user;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Username(firstName, lastName, address, postalCode, city, name, password) VALUES (:firstName, :lastName, :address, :postalCode, :city, :name, :password) RETURNING id');
        $query->execute(array('firstName' => $this->firstName, 'lastName' => $this->lastName, 'address' => $this->address, 'postalCode' => $this->postalCode, 'city' => $this->city, 'name' => $this->name, 'password' => $this->password));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
}
