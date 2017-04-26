<?php

// account related

$routes->get('/', function() {
    AccountController::topSellers();
});
$routes->get('/orders', function() {
    AccountController::orders();
});

$routes->get('/sales', function() {
    AccountController::sales();
});

$routes->post('/shopping_bag/remove', function() {
    AccountController::removeFromShoppingBag();
});

$routes->post('/shopping_bag/add', function() {
    AccountController::addToShoppingBag();
});

$routes->get('/shopping_bag', function() {
    AccountController::shoppingBag();
});

$routes->get('/users', function() {
    AccountController::allUsers();
});

$routes->post('/edit_account/destroy', function() {
    AccountController::destroy();
});

$routes->get('/edit_account', function() {
    AccountController::editAccount();
});

$routes->post('/edit_account', function() {
    AccountController::update();
});

$routes->get('/login', function() {
    AccountController::login();
});

$routes->post('/login', function() {
    AccountController::handle_login();
});

$routes->get('/logout', function() {
    AccountController::logout();
});

$routes->get('/register', function() {
    AccountController::register();
});

$routes->post('/register', function() {
    AccountController::new_user();
});


// poster related

$routes->get('/users_posters/:id', function($id) {
    PosterController::usersPosters($id);
});

$routes->get('/account', function() {
    PosterController::userLoggedInPosters();
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

$routes->get('/posters/:id', function($id) {
    PosterController::posterShow($id);
});

$routes->get('/posters', function() {
    PosterController::posters();
});

$routes->get('/account/new_poster', function() {
    PosterController::create();
});

$routes->post('/account/new_poster', function() {
    PosterController::store();
});


// Category related:

$routes->get('/categories', function(){
    CategoryController::listAll();
});
$routes->post('/category', function(){
    CategoryController::store();
});
$routes->get('/category/new', function() {
    CategoryController::create();
});
$routes->get('/category/:id', function($id){
    CategoryController::show($id);
});