<?php
use MVC\models\CUSTOM_TABLE;
use MVC\Helpers\BaseForm;

$edit_form = new BaseForm(new CUSTOM_TABLE(), ['id'=>'number','name'=>'text','email'=>'email','age'=>'number'],"edit",true);
$edit_form->addStyle("mvc/assets/css/style.css","my_table_hstyle");
$edit_form->addScript("mvc/assets/js/script.js","my_table_hscript",['jquery'],['ajax_url' => admin_url( 'admin-ajax.php' )]);

function add_form_actionsa($values) {
    echo "<input type='submit' value='Edit ID ".$values['id']."'>";
}
function add_form_inputss($inputs, $columns, $values, $states) {
    echo $inputs;
}
add_filter( 'edit_'.CUSTOM_TABLE::tableName().'_form_inputs', 'add_form_inputss', 10, 4);
add_action( 'edit_'.CUSTOM_TABLE::tableName().'_form_actions', 'add_form_actionsa', 10, 1);

$edit_form->insert(CUSTOM_TABLE::get($_POST['id']));
echo $edit_form->getFormHTML();