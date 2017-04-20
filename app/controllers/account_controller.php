<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AccountController extends BaseController {

    public static function myPosters() {
        $user = self::get_user_logged_in();
        $posters = Poster::allFromUser($user->id);
        View::make('account.html', array('posters' => $posters));
    }

    public static function allUsers() {
        $users = User::all();
        View::make('users.html', array('users' => $users));
    }

    public static function topSellers() {
        $users = User::topSellers();
        View::make('home.html', array('users' => $users));
    }

    public static function account() {
        View::make('account.html');
    }

    public static function editAccount() {
        $user = self::get_user_logged_in();
        View::make('edit_account.html', array('attributes' => $user));
    }
    
    public static function shoppingBag() {
        $user = self::get_user_logged_in_id();
        View::make('shopping_bag.html');
    }

    public static function update() {
        $id = self::get_user_logged_in()->id;

        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'firstname' => $params['firstname'],
            'lastname' => $params['lastname'],
            'address' => $params['address'],
            'postalcode' => $params['postalcode'],
            'city' => $params['city'],
            'name' => $params['name'],
            'password' => $params['password']
        );

        $user = new User($attributes);
        $errors = $user->errors();

        if (count($errors) > 0) {
            View::make('edit_account.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $user->update();

            Redirect::to('/account', array('message' => 'Account information has been edited!'));
        }
    }

    public static function destroy() {
        $id = self::get_user_logged_in()->id;
        $user = new User(array('id' => $id));
        $_SESSION['user'] = null;
        $user->destroy();
        Redirect::to('/', array('message' => 'User was deleted successfully!'));
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
            $_SESSION['user'] = $user->id;
            Redirect::to('/account/' . $user->id, array('message' => 'User created successfully!'));
        } else {
            View::make('register.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function handle_login() {
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->id;

            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

}
