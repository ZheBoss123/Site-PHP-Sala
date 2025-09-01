<?php
require_once "config/routes.php";
require_once "config/pdo.php"; 

session_start();

$router = new Router();
$router->direct();
