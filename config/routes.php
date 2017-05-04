<?php

function check_logged_in(){
    BaseController::check_logged_in();
}


$routes->get('/', function() {
    DefaultController::index();
});

// account_controller related:

$routes->post('/order', 'check_logged_in', function() {
    AccountController::makeOrder();
});

$routes->get('/shopping_bag', 'check_logged_in', function() {
    AccountController::shoppingBag();
});

$routes->post('/shopping_bag/remove', 'check_logged_in', function() {
    AccountController::removeFromShoppingBag();
});

$routes->post('/shopping_bag/add', 'check_logged_in', function() {
    AccountController::addToShoppingBag();
});

$routes->get('/users', function() {
    AccountController::allUsers();
});

$routes->post('/edit_account/destroy', 'check_logged_in', function() {
    AccountController::destroy();
});

$routes->get('/edit_account', 'check_logged_in', function() {
    AccountController::editAccount();
});

$routes->post('/edit_account', 'check_logged_in', function() {
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


// poster_contoller related:

$routes->get('/orders', 'check_logged_in', function() {
    PosterController::usersOrders();
});

$routes->get('/sales', 'check_logged_in', function() {
    PosterController::usersSales();
});

$routes->get('/users_posters/:id', function($id) {
    PosterController::usersUnsoldPosters($id);
});

$routes->get('/account', 'check_logged_in', function() {
    AccountController::account();
});

$routes->post('/edit_poster/:id/destroy', 'check_logged_in', function($id) {
    PosterController::destroy($id);
});

$routes->get('/edit_poster/:id', 'check_logged_in', function($id) {
    PosterController::editPoster($id);
});

$routes->post('/edit_poster/:id', 'check_logged_in', function($id) {
    PosterController::update($id);
});

$routes->get('/posters/:id', function($id) {
    PosterController::posterShow($id);
});

$routes->get('/posters', function() {
    PosterController::unsoldPosters();
});

$routes->get('/account/new_poster', 'check_logged_in', function() {
    PosterController::create();
});

$routes->post('/account/new_poster', 'check_logged_in', function() {
    PosterController::store();
});


// Category_controller related:

$routes->get('/categories', function(){
    CategoryController::listAll();
});
$routes->post('/category', 'check_logged_in', function(){
    CategoryController::store();
});
$routes->get('/category/new', 'check_logged_in', function() {
    CategoryController::create();
});
$routes->get('/category/:name', function($name){
    CategoryController::show($name);
});

//Image_controller related:

$routes->get('/image/:id', function($id) {
    ImageController::showImage($id);
});