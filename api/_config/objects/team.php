<?php

class Team {

    private $conn;
    private $db_table = "teams";

    public $id;
    public $title;
    public $abbreviation;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $query = "
        SELECT ID, Title, Abbreviation
        FROM " . $this->db_table . "
        WHERE ID = ?
        LIMIT 0,1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['ID'];
            $this->title = $row['Title'];
            $this->abbreviation = $row['Abbreviation'];

            return true;

        }

    }

}
