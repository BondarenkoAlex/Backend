<?php

include "../models/ProductRepository.php";

$config = include("../db/config.php");
$db = new PDO($config["db"], $config["username"], $config["password"]);
$db -> exec("set names utf8");
$products = new ProductRepository($db);

switch($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $products->getAll(/*array(
            id => intval($_GET["id"]),
            name => $_GET["name"],
            category => intval($_GET["category"]),
            weight => $_GET["weight"]
        )*/);
        break;

    case "POST":
        $result = $products->insert(array(
            id => intval($_POST["id"]),
            name => $_POST["name"],
            category => intval($_POST["category"]),
            weight => $_POST["weight"]
        ));
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);

        $result = $products->update(array(
            id => intval($_PUT["id"]),
            name => $_PUT["name"],
            category => intval($_PUT["category"]),
            weight => $_PUT["weight"]
        ));
        break;

    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);

        $result = $products->remove(array(
            id => intval($_DELETE["id"]),
            name => $_DELETE["name"],
            category => intval($_DELETE["category"]),
            weight => $_DELETE["weight"]
        ));
        break;
}

header("Content-Type: application/json");
echo json_encode($result);

?>