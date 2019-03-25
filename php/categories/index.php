<?php

include "../models/CategoryRepository.php";

$config = include("../db/config.php");
$db = new PDO($config["db"], $config["username"], $config["password"]);
$db -> exec("set names utf8");
$category = new CategoryRepository($db);


switch($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $category->getAll();
        break;
}


header("Content-Type: application/json");
echo json_encode($result);

?>
