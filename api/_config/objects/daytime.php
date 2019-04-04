<?php

class Daytime {

    private $conn;
    private $db_table = "daytime";

    public $id;
    public $title;
    public $description;
    public $abbreviation;
    public $position;
    public $team;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $query = "
        SELECT ID as id, Title as title, Abbreviation as abbreviation, Description as description, Position as position
        FROM ". $this->db_table . "
        WHERE Team_ID = :team AND Active=1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        $stmt->execute();

        return $stmt;

    }

    public function edit() {

        $query = "
        UPDATE ".$this->db_table . " SET
        Title = :title, Abbreviation = :abbreviation, Description = :description, Position = :position
        WHERE ID = :id AND Team_ID = :team;
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function create() {

        $query = "
            INSERT INTO ".$this->db_table . "
            (Title, Abbreviation, Description, Position, Team_ID, Active) VALUES
            (:title, :abbreviation, :description, :position, :team, '1');
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':team', $this->team);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function delete() {

        $query = "
            UPDATE ".$this->db_table . " SET
            Active = '0', Position = NULL, Title = :newTitle
            WHERE ID = :id AND Team_ID = :team
        ";

        $newTitle = ('_deletetAt_' . date_timestamp_get(/** @scrutinizer ignore-type */date_create()));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":newTitle", $newTitle);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":team", $this->team);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }


}
