<?php

include "Category.php";

class CategoryRepository {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function read($row) {
        $result = new Category();
        $result->id = $row["c_id"];
        $result->product_id = $row["c_product_id"];
        $result->category = $row["c_category"];
        return $result;
    }

    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY c_id ASC";
        $q = $this->db->prepare($sql);
        $q->execute();
        $rows = $q->fetchAll();

        $result = array();
        foreach($rows as $row) {
            array_push($result, $this->read($row));
        }
        return $result;
    }

}

?>