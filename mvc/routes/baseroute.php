<?php
namespace MVC\Routes;

class BaseRoute {
    public $routes = [];

    public function addRouteo($path, $gparams, $pparams, $function, $middleware) {
        $path =  $this->slish(parse_url($path, PHP_URL_PATH));
        $this->routes[] = [$path, $gparams, $pparams, $function, $middleware];
    }
    public function addRoute($routeDetails) {
        if (empty($routeDetails['function'])) {
            return; // Do not add the route if 'action' is missing or null
        }
        $path = $this->slish(parse_url($routeDetails['path'] ?? null, PHP_URL_PATH));
        $gparams = $routeDetails['get'] ?? [];
        $pparams = $routeDetails['post'] ?? [];
        $function = $routeDetails['function'];
        $middleware = $routeDetails['middlewares'] ?? [];
        $this->routes[] = [$path, $gparams, $pparams, $function, $middleware];
    }
     public function match($gparams,$pparams){
        foreach ($gparams as $param) {
            if (is_array($param)) {
                // For associative arrays like ["action" => "insert_in_db"]
                $key = key($param);
                $value = $param[$key];
                if (!isset($_GET[$key]) || $_GET[$key] !== $value) {
                    return false;
                }
            } else {
                // For simple parameters like "id" or "name"
                if (!isset($_GET[$param])) {
                    return false;
                }
            }
        }
        foreach ($pparams as $param) {
            if (is_array($param)) {
                // For associative arrays like ["action" => "insert_in_db"]
                $key = key($param);
                $value = $param[$key];
                if (!isset($_POST[$key]) || $_POST[$key] !== $value) {
                    return false;
                }
            } else {
                // For simple parameters like "id" or "name"
                if (!isset($_POST[$param])) {
                    return false;
                }
            }
        }
        return true;
    }
    public function slish($string) {
        if($string==null) return null;
        if (substr($string, 0, 1) !== '/') {
            $string = '/' . $string;
        }
        if (substr($string, -1) !== '/') {
            $string .= '/';
        }
    
        return $string;
    }
    public function run() {
        usort($this->routes, function($a, $b) {
            return (count($b[1]) + count($b[2])) - (count($a[1]) + count($a[2]));
        });
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $this->slish(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        foreach ($this->routes as $route) {
            if ($this->match($route[1],$route[2])) {
                if(($route[0] == null) || $path == $route[0]){
                    foreach ($route[4] as $middleware) {
                        $middleware->handle();
                    }
                    call_user_func($route[3]);
                }
                return;
            }
        }
    }
}
