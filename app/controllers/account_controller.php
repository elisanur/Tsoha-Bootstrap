<?php

/*
 */

class AccountController extends BaseController {

    public static function account() {
        $user = self::get_user_logged_in();
        $posters = Poster::allUnsoldPostersFromUser($user->id);
        View::make('user/account.html', array('posters' => $posters));
    }

    public static function allUsers() {
        $users = User::all();
        View::make('user/users.html', array('users' => $users));
    }

    public static function editAccount() {
        $user = self::get_user_logged_in();
        View::make('user/edit_account.html', array('attributes' => $user));
    }

    public static function update() {
        $id = self::get_user_logged_in()->id;
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'firstName' => trim($params['firstName']),
            'lastName' => trim($params['lastName']),
            'address' => trim($params['address']),
            'postalCode' => $params['postalCode'],
            'city' => trim($params['city']),
            'name' => trim($params['name']),
            'password' => $params['password'],
            'email' => trim($params['email'])
        );



        $user = new User($attributes);
        $errors = $user->errors();

        $name = $params['name'];
        $old = BaseController::get_user_logged_in();
        $new = User::findByUsername($name);

        
        if (isset($new) && $old->name != $name) {
            $errors[] = 'Username is already in use';
        }

        if (count($errors) == 0) {
            $user->update();
            Redirect::to('/account', array('message' => 'Account information has been edited!'));
        } else {
            View::make('user/edit_account.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function destroy() {
        $user = self::get_user_logged_in();
        $_SESSION['user'] = null;
        $user->destroy();
        Redirect::to('/', array('message' => 'User was deleted successfully!'));
    }

    public static function login() {
        View::make('user/login.html');
    }

    public static function register() {
        View::make('user/register.html');
    }

    public static function new_user() {
        $params = $_POST;

        $attributes = array(
            'firstName' => trim($params['firstName']),
            'lastName' => trim($params['lastName']),
            'address' => trim($params['address']),
            'postalCode' => $params['postalCode'],
            'city' => trim($params['city']),
            'name' => $params['name'],
            'password' => $params['password'],
            'email' => trim($params['email'])
        );

        $user = new User($attributes);
        $errors = $user->errors();

        $old = User::findByUsername($params['name']);

        if ($old) {
            $errors[] = 'Username is already in use';
        }

        if (count($errors) == 0) {
            $user->save();
            $_SESSION['user'] = $user->id;
            Redirect::to('/account', array('message' => 'User created successfully!'));
        } else {
            View::make('user/register.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function handle_login() {
        $params = $_POST;
        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('user/login.html', array('message' => 'Invalid username or password!', 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->id;
            $shoppingBag = array();
            $_SESSION['shoppingBag'] = $shoppingBag;

            Redirect::to('/', array('message' => 'Welcome back ' . $user->name . '!'));
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function shoppingBag() {
        $posters = array();

        if (isset($_SESSION['shoppingBag'])) {
            foreach ($_SESSION['shoppingBag'] as $posterId) {
                if (Poster::find($posterId)) {
                    $posters[] = Poster::find($posterId);
                }
            }
            View::make('user/shopping_bag.html', array('shoppingBag' => $posters));
        }
    }

    public static function removeFromShoppingBag() {
        $params = $_POST;
        unset($_SESSION['shoppingBag'][$params['posterId']]);
        Redirect::to('/shopping_bag');
    }

    public static function addToShoppingBag() {
        $params = $_POST;
        $_SESSION['shoppingBag'][$params['posterId']] = $params['posterId'];
        Redirect::to('/shopping_bag');
    }

    public static function makeOrder() {
        if (isset($_SESSION['shoppingBag'])) {
            foreach ($_SESSION['shoppingBag'] as $posterId) {
                if (Poster::find($posterId)) {
                    Poster::markAsSold($posterId);
                    Purchase::save($posterId, self::get_user_logged_in_id());
                }
            }

            unset($_SESSION['shoppingBag']);
            $_SESSION['shoppingBag'] = array();

            Redirect::to('/orders', array('message' => 'Order succesful! Contact seller(s) via email, and agree with terms of delivery.'));
        } else {
            View::make('user/orders.html', array('error' => 'Something weird happened, please try again.'));
        }
    }

}
