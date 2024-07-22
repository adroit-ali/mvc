<?php
namespace MVC\Middlewares;
use MVC\Middlewares\BaseMiddleware;

class AddViewMiddleware implements BaseMiddleware
{
    public $status;
    public $data;
    public function handle()
    {
        print_r('Add Form View Middleware Run! <br> Middleware Verified <br>');
        print_r($_GET);
        print_r('<br>');
        print_r($_POST);
        print_r('<br>');
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
class MaintainanceMiddleware implements BaseMiddleware
{
    public $status;
    public $data;
    public function handle()
    {
        print_r('Maintainance Middleware Run! <br> Middleware Verified <br>');
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