<?php
namespace MVC\Models;

$all = [
    'MVC\Models\CreateMyCustomTable',
    'MVC\Models\AddPhoneNumberToCustomTable',
    'MVC\Models\RemoveAgeFromCustomTable',
];
$required = [
    'MVC\Models\CreateMyCustomTable',
    // 'MVC\Models\AddPhoneNumberToCustomTable',
];
$upgrades = array_intersect($all, $required);
$downgrades = array_diff($all, $required);

function my_plugin_activate() {
    global $upgrades, $downgrades;
    BaseModel::upgrade($upgrades);
    BaseModel::downgrade($downgrades);
}

register_activation_hook(__FILE__, 'my_plugin_activate');

//Manual Upgrades or Downgrades
BaseModel::upgrade($upgrades);
BaseModel::downgrade($downgrades);