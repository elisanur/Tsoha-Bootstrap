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

        $params = $_POST;

        $user = new User(array(
            'firstName' => $params['firstName'],
            'lastName' => $params['lastName'],
            'address' => $params['address'],
            'postalCode' => $params['postalCode'],
            'city' => $params['city'],
            'name' => $params['name'],
            'password' => $params['password']
        ));

        Kint::dump($params);
        $user->save();

        Redirect::to('/account/' . $poster->publisher, array('message' => 'Poster wash added!'));


        View::make('register.html');
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
