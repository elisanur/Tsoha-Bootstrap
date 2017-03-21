<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/account', function() {
    HelloWorldController::account();
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