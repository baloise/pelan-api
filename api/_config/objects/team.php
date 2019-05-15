<?php

class Team {

    private $conn;
    private $db_table = "team";
    private $db_view_user = "view_user_team";

    public $id;
    public $title;
    public $abbreviation;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $sql = "SELECT * FROM " . $this->db_view_user . " WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        if ($stmt->execute()) {
            return $stmt;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

}
