<?php
namespace MVC\Routes;

class AjaxRoute {
    private $ajax = [];
    // public function addRouteo($name ,$function, $middlewares, $nopriv = false) {
    //     $this->ajax[$name] = ['function' => $function, 'middlewares' => $middlewares ,'nopriv' => $nopriv];
    // }
    public function addRoute($routeDetails) {
        if (empty($routeDetails['handle']) || empty($routeDetails['function'])) {
            return; // Do not add the route if 'action' is missing or null
        }
        $handle = $routeDetails['handle'];
        $function = $routeDetails['function'];
        $middlewares = $routeDetails['middlewares'] ?? [];
        $nopriv = $routeDetails['nopriv'] ?? false;
        $this->ajax[$handle] = ['function' => $function, 'middlewares' => $middlewares ,'nopriv' => $nopriv];
    }
    private function handleMiddleware($middlewares, $function) {
        return function() use ($middlewares, $function) {
            foreach ($middlewares as $middleware) {
                $middleware->handle();
                $result = $middleware->status();
                if ($result['success'] !== true) {
                    wp_send_json($result);
                    exit;
                }
            }
            call_user_func($function);
        };
    }

    public function run() {
        foreach ($this->ajax as $name => $ajax) {
            $function = $this->handleMiddleware($ajax['middlewares'], $ajax['function']);
            $nopriv = $ajax['nopriv'];
            add_action('wp_ajax_'.$name, $function);
            if ($nopriv) {
                add_action('wp_ajax_nopriv_'.$name, $function);
            }
        }
    }
}
