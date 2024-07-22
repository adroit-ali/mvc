<?php
use MVC\models\CUSTOM_TABLE;
use MVC\Helpers\BaseForm;

$add_form = new BaseForm(new CUSTOM_TABLE(), ['name'=>'text','email'=>'email','age'=>'number'],"add",true);
$add_form->addStyle("mvc/assets/css/style.css","my_table_hstyle");
$add_form->addScript("mvc/assets/js/script.js","my_table_hscript",['jquery'],['ajax_url' => admin_url( 'admin-ajax.php' )]);

function add_form_actionsa($values) {
    echo "<input type='submit' value='Add Student'>";
}
function add_form_inputss($inputs, $columns, $values, $states) {
    echo $inputs;
}
add_filter( 'add_'.CUSTOM_TABLE::tableName().'_form_inputs', 'add_form_inputss', 10, 4);
add_action( 'add_'.CUSTOM_TABLE::tableName().'_form_actions', 'add_form_actionsa', 10, 1);

echo $add_form->getFormHTML();