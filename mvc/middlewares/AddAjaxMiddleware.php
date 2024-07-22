<?php
namespace MVC\Middlewares;
use MVC\Middlewares\BaseMiddleware;

class AddAjaxMiddleware implements BaseMiddleware
{
    public $status;
    public $data;
    public function handle()
    {
        echo "Insert Ajax Middleware Run!\n";
        $this->setStatus(true,["message"=>"done"]);
    }
    public function setStatus($status,$data){
        $this->status=$status;
        $this->data=$data;
    }
    public function status(){
        return ["success"=>$this->status,"data"=>$this->data];
    }
}