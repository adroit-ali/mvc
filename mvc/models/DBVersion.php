<?php
namespace MVC\Models;

abstract class DBVersion {
    abstract public function up();
    abstract public function down();
}

class CreateMyCustomTable extends DBVersion {
    public function up() {
        $table = new CUSTOM_TABLE();
        $table->createTable();
    }

    public function down() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'my_custom_table';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}

class AddPhoneNumberToCustomTable extends DBVersion {
    public function up() {
        $table = new CUSTOM_TABLE();
        $table->addColumn('phone_number', 'varchar(15) NOT NULL');
    }

    public function down() {
        $table = new CUSTOM_TABLE();
        $table->removeColumn('phone_number');
    }
}

class RemoveAgeFromCustomTable extends DBVersion {
    public function up() {
        $table = new CUSTOM_TABLE();
        $table->removeColumn('age');
    }

    public function down() {
        $table = new CUSTOM_TABLE();
        $table->addColumn('age', 'int(11) NOT NULL');
    }
}
