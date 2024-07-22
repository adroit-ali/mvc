<?php
namespace MVC\Helpers;
use MVC\Models\BaseModel;

class BaseTable {
    public $model;
    public $columns;
    public $action;
    public $styleHandle;
    public $scriptHandle;
    public function __construct(BaseModel $model, $columns,$action=false) {
        $this->columns = $columns;
        $this->model = $model;
        $this->action = $action;
        
    }
    public function getTableHTML(){
        $columns = $this->columns;
        $model = $this->model;
        $action = $this->action;
        ob_start();
        if($this->styleHandle) wp_enqueue_style( $this->styleHandle );
        if($this->scriptHandle) wp_enqueue_script( $this->scriptHandle );
        $cols = count($columns);
        echo "<table id='".$model::tableName()."_tbl_id"."'>
        <thead>";
        for ($counter = 0; $counter < $cols; $counter++) {
            echo "<th>$columns[$counter]</th>";
        }
        if($action) echo "<th>Actions</th>";
        echo"</thead>
        <tbody>";
        foreach ($model->getAll() as $rows) {
            echo "<tr>";
            foreach ($columns as $column) {
                if(isset($rows->data[$column])){
                    echo "<td>".$rows->data[$column]."</td>";
                }
                else{
                    echo "<td></td>";
                }
            }
            if($action){
                echo "<th>";
                do_action($model::tableName().'_table_actions', $rows->data);
                echo "</th>";
            }

            echo "</tr>";
        }
        echo"</tbody>
        </table>";
        return ob_get_clean();

    }
    public function addStyle($src, $handle){
        if(!empty($src)) wp_register_style( $handle, DIR_URL.$src );
        $this->styleHandle = $handle;
    }
    public function addScript($src, $handle, $deps, $localize=false){
        if(!empty($src)) wp_register_script( $handle, DIR_URL.$src, $deps);
        if(!empty($localize)) wp_localize_script( $handle, 'ajax_object', $localize);
        $this->scriptHandle = $handle;
    }
}
