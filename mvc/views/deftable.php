<?php
use MVC\Models\CUSTOM_TABLE;
use MVC\Helpers\BaseTable;

$view_table = new BaseTable(new CUSTOM_TABLE(), ['id','name','email','age'], true);
$view_table->addStyle("mvc/assets/css/style.css","my_table_style");
$view_table->addScript("mvc/assets/js/script.js","my_table_script",['jquery'],['ajax_url' => admin_url( 'admin-ajax.php' )]);

function add_custom_action($row) {
    echo '<a id="edit_btn" data-id="' . $row['id'] . '">POST Edit</a> | ';
    $fullUrl = relative_get_url(['id'=>$row['id'],'action'=>'edit']);
    echo '<a href="' . $fullUrl . '">GET Edit</a>';
    echo ' | ';
    echo '<a id="delete_btn" data-id="' . $row['id'] . '" onclick="return confirm(\'Are you sure?\')">AJAX Delete</a>';
    
    
}
add_action( CUSTOM_TABLE::tableName().'_table_actions', 'add_custom_action', 10, 1);


echo "<div class='main-wraper'>";
echo '<h1 align="center">All Students Table</h1>';
$fullUrl = relative_get_url(['action'=>'add']);
echo '<a href="' . $fullUrl . '">New Student</a>';
echo $view_table->getTableHTML();
echo "</div>";