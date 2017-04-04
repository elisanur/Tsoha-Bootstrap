<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/edit_account', function() {
    AccountController::editAccount();
});

$routes->get('/edit_poster/:id', function($id) {
    PosterController::editPoster($id);
});

$routes->post('/edit_poster/:id', function($id) {
    PosterController::update($id);
});

$routes->get('/login', function() {
    AccountController::login();
});

$routes->post('/login', function() {
    // Kirjautumisen käsittely
    AccountController::handle_login();
});

$routes->get('/posters/:id', function($id) {
    PosterController::posterShow($id);
});

//$routes->get('/posters/:id', function($id) {
//    PosterController::show($id);
//});

$routes->get('/posters', function() {
    PosterController::posters();
});

$routes->get('/register', function() {
    AccountController::register();
});

$routes->post('/register', function() {
    AccountController::new_user();
});

$routes->get('/account', function() {
    AccountController::account();
});

$routes->get('/account/new_poster', function() {
    PosterController::create();
});

$routes->get('/account/:id', function($id) {
    AccountController::myPosters($id);
});

$routes->post('/account/new_poster', function() {
    PosterController::store();
});



