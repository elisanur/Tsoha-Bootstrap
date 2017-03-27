<?php

require 'app/models/poster.php';

class HelloWorldController extends BaseController {

    
    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }
    
    public static function account() {
        View::make('account.html');
    }
    
    public static function editAccount() {
        View::make('edit_account.html');
    }
    
    public static function editPoster() {
        View::make('edit_poster.html');
    }

    public static function login() {
        View::make('login.html');
    }
    
    public static function posterShow() {
        View::make('poster_show.html');
    }

    public static function posters() {
        View::make('posters.html');
    }

    public static function register() {
        View::make('register.html');
    }

}
