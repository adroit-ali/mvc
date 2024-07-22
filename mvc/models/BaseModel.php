<?php
namespace MVC\Models;

abstract class BaseModel {
    public static $table_name;
    protected $columns;
    public $data = [];

    public function __construct($columns) {
        global $wpdb;
        static::$table_name = $wpdb->prefix . static::tableName();
        $this->columns = array_merge(["id" => "mediumint(9) NOT NULL AUTO_INCREMENT"], $columns);
    }

    abstract protected static function tableName();

    public function createTable() {
        global $wpdb;
        $table_name = static::$table_name;
        $columns_sqls = [];
        foreach ($this->columns as $column_name => $column_type) {
            $columns_sqls[] = "$column_name $column_type";
        }
        $columns_sql = implode(", ", $columns_sqls);
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            $columns_sql,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        if ($wpdb->last_error) {
            throw new \Exception('Error creating table: ' . $wpdb->last_error);
        }
    }

    public function addColumn($column_name, $column_type) {
        global $wpdb;
        $table_name = static::$table_name;
        $sql = "ALTER TABLE $table_name ADD COLUMN $column_name $column_type";
        $wpdb->query($sql);
        if ($wpdb->last_error) {
            throw new \Exception('Error adding column: ' . $wpdb->last_error);
        }
        $this->columns[$column_name] = $column_type;
    }

    public function removeColumn($column_name) {
        global $wpdb;
        $table_name = static::$table_name;
        $sql = "ALTER TABLE $table_name DROP COLUMN $column_name";
        $wpdb->query($sql);
        if ($wpdb->last_error) {
            throw new \Exception('Error removing column: ' . $wpdb->last_error);
        }
        unset($this->columns[$column_name]);
    }

    public function save() {
        global $wpdb;
        $data = $this->data;
        $table_name = static::$table_name;
        if (isset($data['id']) && !empty($data['id'])) {
            $wpdb->update($table_name, $data, ['id' => $data['id']]);
            if ($wpdb->last_error) {
                throw new \Exception('Error updating record: ' . $wpdb->last_error);
            }
        } else {
            $wpdb->insert($table_name, $data);
            if ($wpdb->last_error) {
                throw new \Exception('Error inserting record: ' . $wpdb->last_error);
            }
            $this->data['id'] = $wpdb->insert_id;
        }
    }

    public function __set($key, $value) {
        if (array_key_exists($key, $this->columns)) {
            $this->data[$key] = $value;
        }
    }

    public function load($data) {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->columns)) {
                $this->data[$key] = $value;
            }
        }
    }

    public static function add($atts) {
        $instance = new static([]);
        $instance->load($atts);
        try {
            $instance->save();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
        return $instance;
    }

    public static function update($atts) {
        if (!isset($atts['id'])) return ['error' => 'Please Provide ID to be Updated'];
        $instance = static::get($atts['id']);
        if (!$instance) return ['error' => 'Record not found'];
        $instance->load($atts);
        try {
            $instance->save();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
        return $instance;
    }

    public static function get($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . static::tableName();
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE `id` = %d", $id));
        if ($wpdb->last_error) {
            throw new \Exception('Error fetching record: ' . $wpdb->last_error);
        }
        if (!$row) return null;
        $instance = new static([]);
        $instance->load((array)$row);
        return $instance;
    }

    public static function getAll() {
        global $wpdb;
        $table_name = $wpdb->prefix . static::tableName();
        $rows = $wpdb->get_results("SELECT * FROM {$table_name}");
        if ($wpdb->last_error) {
            throw new \Exception('Error fetching records: ' . $wpdb->last_error);
        }
        $instances = [];
        foreach ($rows as $row) {
            $instance = new static([]);
            $instance->load((array)$row);
            $instances[] = $instance;
        }
        return $instances;
    }

    public static function delete($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . static::tableName();
        $wpdb->delete($table_name, ['id' => $id]);
        if ($wpdb->last_error) {
            throw new \Exception('Error deleting record: ' . $wpdb->last_error);
        }
    }

    public static function upgrade($migrations) {
        foreach ($migrations as $migration) {
            if (!self::isMigrationExecuted($migration)) {
                $migrationInstance = new $migration();
                $migrationInstance->up();
                self::markMigrationExecuted($migration);
            }
        }
    }

    public static function downgrade($migrations) {
        foreach (array_reverse($migrations) as $migration) {
            if (self::isMigrationExecuted($migration)) {
                $migrationInstance = new $migration();
                $migrationInstance->down();
                self::unmarkMigrationExecuted($migration);
            }
        }
    }

    protected static function isMigrationExecuted($migration) {
        $executed_migrations = get_option('executed_migrations', []);
        return in_array($migration, $executed_migrations);
    }

    protected static function markMigrationExecuted($migration) {
        $executed_migrations = get_option('executed_migrations', []);
        if (!in_array($migration, $executed_migrations)) {
            $executed_migrations[] = $migration;
            update_option('executed_migrations', $executed_migrations);
        }
    }

    protected static function unmarkMigrationExecuted($migration) {
        $executed_migrations = get_option('executed_migrations', []);
        if (($key = array_search($migration, $executed_migrations)) !== false) {
            unset($executed_migrations[$key]);
            update_option('executed_migrations', $executed_migrations);
        }
    }
}

class CUSTOM_TABLE extends BaseModel {
    public static function tableName() {
        return 'my_custom_table';
    }

    public function __construct() {
        $columns = [
            'name' => 'varchar(255) NOT NULL',
            'email' => 'varchar(255) NOT NULL',
            'age' => 'int(11) NOT NULL',
        ];
        parent::__construct($columns);
    }
}
