<?php

include "Product.php";

class ProductRepository {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new Product();
        $result->id = $row["p_id"];
        $result->name = $row["p_name"];
        $result->category = $row["p_category"];
        $result->weight = $row["p_weight"];
        return $result;
    }

    public function getById($id) {
        $sql = "SELECT * FROM products WHERE p_id = :id ORDER BY p_id ASC";
        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $id, PDO::PARAM_INT);
        $q->execute();
        $rows = $q->fetchAll();
        return $this->read($rows[0]);
    }

//public $id;
//public $name;
//public $category;
//public $weight;
    public function getAll($filter=null) {
        //$name = "%" . $filter["name"] . "%";
        //$address = "%" . $filter["address"] . "%";
        //$country_id = $filter["country_id"];

        //$sql = "SELECT * FROM products WHERE name LIKE :name AND address LIKE :address AND (:country_id = 0 OR country_id = :country_id)";
        $sql = "SELECT * FROM products ORDER BY p_id ASC";
        $q = $this->db->prepare($sql);
        //$q->bindParam(":name", $name);
        //$q->bindParam(":address", $address);
        //$q->bindParam(":country_id", $country_id);
        $q->execute();
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

    public function insert($data) {
        //$sql = "INSERT INTO clients (name, age, address, married, country_id) VALUES (:name, :age, :address, :married, :country_id)";
        $sql = "INSERT INTO products (p_name, p_category, p_weight) VALUES ( :name, :category, :weight )";
        $q = $this->db->prepare($sql);
        $q->bindParam(":name", $data["name"]);
        $q->bindParam(":category", $data["category"]);
        $q->bindParam(":weight", $data["weight"]);
        $q->execute();
        return $this->getById($this->db->lastInsertId());
    }

    public function update($data) {
        //$sql = "UPDATE clients SET name = :name, age = :age, address = :address, married = :married, country_id = :country_id WHERE id = :id";
        $sql = "UPDATE  products SET  p_name = :name, p_category = :category, p_weight = :weight WHERE  p_id = :id";
        $q = $this->db->prepare($sql);
        $q->bindParam(":name", $data["name"]);
        $q->bindParam(":category", $data["category"]);
        $q->bindParam(":weight", $data["weight"]);
        $q->bindParam(":id", $data["id"], PDO::PARAM_INT);
        $q->execute();
        return $this->getById($data["id"]);
    }

    public function remove($data) {
        $sql = "DELETE FROM products WHERE p_id = :id";
        $q = $this->db->prepare($sql);
        $q->bindParam(":id", $data["id"], PDO::PARAM_INT);
        $exec = $q->execute();
        if ($exec == true){
            $result = new Product();
            $result->id = $data["id"];
            $result->name = $data["name"];
            $result->category = $data["category"];
            $result->weight = $data["weight"];
            return $result;
        }
        else{
            return false;
        }
    }

}

?>