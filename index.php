<?php
/*
Plugin Name: a tetsing student crud
*/
include "mvc/init.php";

use MVC\Routes\AjaxRoute;
use MVC\Routes\BaseRoute;
use MVC\Middlewares\EditViewMiddleware;
use MVC\Middlewares\AddViewMiddleware;
use MVC\Middlewares\DefViewMiddleware;
use MVC\Middlewares\DelAjaxMiddleware;
use MVC\Middlewares\AddAjaxMiddleware;
use MVC\Middlewares\EditAjaxMiddleware;
// use MVC\Middlewares\MaintainanceMiddleware;
use MVC\Controllers\ViewController;
use MVC\Controllers\AjaxController;

add_action('admin_menu', 'shortcode_admin_page_menu');
function shortcode_admin_page_menu() {
    add_menu_page(
        'Shortcode Output',
        'Shortcode Output',
        'manage_options',
        'shortcode-output',
        'shortcode_admin_page_display',
        'dashicons-admin-generic',
        20
    );
}
function shortcode_admin_page_display() {
    echo do_shortcode('[test01]');
}
add_shortcode( 'test01','mytest' );

$ajax_route = new AjaxRoute();
$ajax_route->addRoute([
    'handle' => 'delete_button_ajax',
    'function' => [AjaxController::class,'delete_button_ajax'],
    'middlewares' => [new DelAjaxMiddleware()]
]);
$ajax_route->addRoute([
    'handle' => 'insert_button_ajax',
    'function' => [AjaxController::class,'insert_button_ajax_cb'],
    'middlewares' => [new AddAjaxMiddleware()]
]);
$ajax_route->addRoute([
    'handle' => 'edit_button_ajax',
    'function' => [AjaxController::class,'edit_button_ajax_cb'],
    'middlewares' => [new EditAjaxMiddleware()],
    'nopriv' => false
]);
$ajax_route->run();


function mytest() {
    $route = new BaseRoute();
    $route->addRoute([
        'path' => '',
        'get' => [],
        'post' => [],
        'function' => [ViewController::class, 'default_view_controller'],
        'middlewares' => [new DefViewMiddleware()]
    ]);
    $route->addRoute([
        'path' => '',
        'get' => [["action"=>"add"]],
        'post' => [],
        'function' => [ViewController::class, 'add_view_controller'],
        'middlewares' => [new AddViewMiddleware()]
    ]);
    $route->addRoute([
        'path' => '',
        'get' => ['id',["action"=>"edit"]],
        'post' => [],
        'function' => [ViewController::class, 'edit_view_controller'],
        'middlewares' => [new EditViewMiddleware()]
    ]);
    $route->addRoute([
        'path' => '',
        'get' => [["action"=>"edit"]],
        'post' => ['id'],
        'function' => [ViewController::class, 'pedit_view_controller'],
        'middlewares' => [new EditViewMiddleware()]
    ]);
    $route->run();
}