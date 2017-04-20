<?php

require 'app/models/poster.php';

class DefaultController extends BaseController {

    public static function index() {
        View::make('home.html');
    }

}
