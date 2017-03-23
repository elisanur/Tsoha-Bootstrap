<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/account', function() {
    HelloWorldController::account();
});

$routes->get('/edit_account', function() {
    HelloWorldController::editAccount();
});

$routes->get('/edit_poster', function() {
    HelloWorldController::editPoster();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/poster_show', function() {
    HelloWorldController::posterShow();
});

$routes->get('/posters', function() {
    HelloWorldController::posters();
});

$routes->get('/register', function() {
    HelloWorldController::register();
});