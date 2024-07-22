<?php
namespace MVC\Controllers;

class ViewController{
    public static function add_view_controller() {
        require DIR_PATH . "mvc/views/addform.php";
    }
    public static function edit_view_controller() {
        require DIR_PATH . "mvc/views/editform.php";
    }
    public static function pedit_view_controller() {
        require DIR_PATH . "mvc/views/peditform.php";
    }
    public static function homeapicontroller() {
        echo "Home API Controller";
        exit();
    }
    public static function maintainancecontrol() {
        echo "Maintanance Controller Message";
        wp_die();
    }
        
    public static function default_view_controller() {
        require DIR_PATH . "mvc/views/deftable.php";
    }
}