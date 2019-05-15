<?php

class Role {

    private $conn;
    private $db_table = "role";

    public $id;
    public $title;
    public $description;
    public $admin;
    public $team;
    public $main;

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

    public function create() {

        $sql = "
            INSERT INTO ".$this->db_table . "
            (`Title`, `Description`, `Admin`, `Team_ID`, `Main`) VALUES
            (:title, :description, :admin, :team, :main);
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':admin', $this->admin);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':main', $this->main);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

}
