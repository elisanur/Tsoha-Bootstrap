<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AccountController extends BaseController {

    public static function myPosters($publisher) {
// Haetaan kaikki pelit tietokannasta
        $posters = Poster::allFromUser($publisher);
// Renderöidään views/game kansiossa sijaitseva tiedosto index.html muuttujan $games datalla
        View::make('account.html', array('posters' => $posters));
    }

    public static function account() {
        View::make('account.html');
    }

    public static function editAccount() {
        View::make('edit_account.html');
    }

    public static function login() {
        View::make('login.html');
    }

    public static function register() {
        View::make('register.html');
    }

    public static function new_user() {

        $params = $_POST;

        $attributes = array(
            'firstname' => $params['firstname'],
            'lastname' => $params['lastname'],
            'address' => $params['address'],
            'postalcode' => $params['postalcode'],
            'city' => $params['city'],
            'name' => $params['name'],
            'password' => $params['password']
        );

        Kint::dump($params);

        $user = new User($attributes);
        $errors = $user->errors();

        if (count($errors) == 0) {
            $user->save();
            Redirect::to('/account/' . $user->id, array('message' => 'User created successfully!'));
        } else {
            View::make('register.html', array('errors' => $errors, 'attributes' => $attributes));
        }

        
    }

    public static function handle_login() {
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->id;

            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
        }
    }

}
