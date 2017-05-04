<?php

require 'app/models/poster.php';

class DefaultController extends BaseController {

    public static function index() {
        $users = User::topSellers();
        View::make('home.html', array('topSellers' => $users));
    }

}
