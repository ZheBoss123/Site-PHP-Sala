<?php
$routes = [
    "" => ["AuthController", "landing_page"], // cand intri pe http://localhost/proiect_php/sala/

    "Abonamente/index" => ["AbonamentController", "index"],
    "Abonamente/show" => ["AbonamentController", "show"],
    "Abonamente/edit" => ["AbonamentController", "edit"],

    "Abonamente/buy" => ["AbonamentController", "buy"],
    "users/index" => ["UserController", "index"],
    "users/show" => ["UserController", "show"],
    "users/edit" => ["UserController", "edit"],
    "users/delete" => ["UserController", "delete"],
    "users/create" => ["UserController", "create"],

    "auth/login" => ["AuthController", "login"],
    "auth/logout" => ["AuthController", "logout"],
];


class Router {
    private $uri;

    public function __construct() {
       
        $this->uri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");

       
        $this->uri = preg_replace("#^proiect_php/sala/?#", "", $this->uri);
    }

    public function direct() {
        

        global $routes;

        if (array_key_exists($this->uri, $routes)) {
            [$controller, $method] = $routes[$this->uri];

            require_once "app/controllers/{$controller}.php";

            return $controller::$method();
        }

        require_once "app/views/404.php";
    }
}


?>