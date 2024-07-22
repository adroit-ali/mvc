<?php
namespace MVC\Controllers;
use MVC\Middlewares\EditMiddleware;
use MVC\Models\CUSTOM_TABLE;

class AjaxController{
    public static function delete_button_ajax() {
        try {
            CUSTOM_TABLE::delete($_POST['id']);
            echo "Student Deleted Successfully";
        } catch (\Exception $e) {
            echo 'Exception: ' . $e->getMessage();
        }
        wp_die();
    }
    public static function insert_button_ajax_cb() {
        parse_str($_POST['data'], $form_data);
        try {
            print_r(CUSTOM_TABLE::add($form_data));
            echo "Student Inserted Successfully";
        } catch (\Exception $e) {
            echo 'Exception: ' . $e->getMessage();
        }
        wp_die();
    }
    public static function edit_button_ajax_cb() {
        parse_str($_POST['data'], $form_data);
        try {
            print_r(CUSTOM_TABLE::update($form_data));
            echo "Student Edited Successfully";
        } catch (\Exception $e) {
            echo 'Exception: ' . $e->getMessage();
        }
        wp_die();
    }
}