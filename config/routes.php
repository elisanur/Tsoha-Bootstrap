<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->post('/edit_account/destroy', function(){
    AccountController::destroy();
});

$routes->get('/edit_account', function() {
    AccountController::editAccount();
});

$routes->post('/edit_account', function() {
    AccountController::update();
});


$routes->post('/edit_poster/:id/destroy', function($id) {
    PosterController::destroy($id);
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
    AccountController::handle_login();
});

$routes->get('/logout', function(){
    AccountController::logout();
});

$routes->get('/posters/:id', function($id) {
    PosterController::posterShow($id);
});

$routes->get('/posters', function() {
    PosterController::posters();
});

$routes->get('/register', function() {
    AccountController::register();
});

$routes->post('/register', function() {
    AccountController::new_user();
});

$routes->get('/account/new_poster', function() {
    PosterController::create();
});

$routes->post('/account/new_poster', function() {
    PosterController::store();
});

$routes->get('/account/:id', function($id) {
    PosterController::usersPosters($id);
});

$routes->get('/account', function() {
    AccountController::myPosters();
});
