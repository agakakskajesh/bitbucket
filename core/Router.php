<?php

require_once('app/controllers/UserController.php');

class Router {

    private static $list = [];

    public static function page($uri, $page_name) {
        self::$list[] = [
            "uri" => $uri,
            "page" => $page_name,
            "is_page" => true
        ];
    }

    public static function post($uri, $class, $method) {
        self::$list[] = [
            "uri" => $uri,
            "class" => $class,
            "method" => $method,
            "post" => true
        ];
    }

    public static function get($uri, $class, $method) {
        self::$list[] = [
            "uri" => $uri,
            "class" => $class,
            "method" => $method,
            "get" => true
        ];
    }


    public static function enable() {
        
        $query = htmlspecialchars($_GET['url']);

        foreach (self::$list as $route) {

            if ($route['uri'] === '/' . $query) {
                if ($route['post'] === true && $_SERVER["REQUEST_METHOD"] === "POST") {
                    $action = new $route['class'];
                    $method = $route['method'];
                    $action->$method($_POST);
                    
                    die();
                } elseif ($route['get'] === true && $_SERVER["REQUEST_METHOD"] === "GET") {
                    $action = new $route['class'];
                    $method = $route['method'];
                    $action->$method();
                    die();
                }
                elseif ($route['is_page'] === true) {
                    
                    require_once('app/views/' . $route['page']);
                    die();
                }
            }
        }

        self::not_found_page();

    }


    private static function not_found_page() {
        require_once 'app/views/errors/404.php';
    }

    public static function redirectToNotAjaxRequest() {
        require_once 'app/views/errors/wrong_request.php';
    }

    public static function redirect($uri) {
        header('Location: ' . $uri);
    }

}