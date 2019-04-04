<?php

class Role {

    private $conn;
    private $db_table = "role";

    public $id;
    public $title;
    public $admin;
    public $team;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $query = "
        SELECT ID as id, Title as title, Admin as admin, Description as description
        FROM ". $this->db_table . "
        WHERE Team_ID = :team
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        $stmt->execute();

        return $stmt;

    }

}
