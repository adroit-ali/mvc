<?php
namespace MVC\Helpers;
use MVC\Models\BaseModel;
class BaseForm {
    public $model;
    public $columns;
    public $purpose;
    public $action;
    public $values = [];
    public $states = [];
    public $styleHandle;
    public $scriptHandle;

    public function __construct(BaseModel $model, $columns,$purpose,$action=false) {
        $this->model = $model;
        $this->columns = $columns;
        $this->purpose = $purpose;
        $this->action = $action;
    }
    public function getFormHTML(){
        $model = $this->model;
        $columns = $this->columns;
        $purpose = $this->purpose;
        $action = $this->action;
        $values = $this->values;
        $states = $this->states;
        ob_start();
        if($this->styleHandle) wp_enqueue_style( $this->styleHandle );
        if($this->scriptHandle) wp_enqueue_script( $this->scriptHandle );
        $form_id = $purpose."_".$model::tableName()."_form_id";
        echo "<form action='' method='post' id='$form_id'>";
        $rnum = rand(1,10000);
        if(!empty($values)&&!is_array($values)){
            $values = (array) $values->data;
        }
        $inputs = "";
        foreach ($columns as $column => $type) {
            $inputs .= "<label for='$column$rnum'>$column: </label>";
            $inputs .= "<input type='$type' id='$column$rnum' class='$column' name='$column' value='".(isset($values[$column])?$values[$column]:"")."' ".(isset($states[$column])?$states[$column]:"")."><br>";
        }
        echo apply_filters($purpose."_".$model::tableName().'_form_inputs', $inputs, $columns, $values, $states);
        if($action){
            echo "<div>";
            do_action($purpose."_".$model::tableName().'_form_actions', $values);
            echo "</div>";
        }
        echo"</form>";
        return ob_get_clean();

    }
    public function insert($columns){
        $this->values = $columns;
    }
    public function reset(){
        $this->values = [];
        $this->states = [];
    }
    public function setState($states){
        $this->states = $states;
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