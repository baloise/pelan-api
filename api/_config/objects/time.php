<?php
//error_reporting(0);
class Time {

    private $conn;
    private $db_table = "times";

    public $id;
    public $title;
    public $abbreviation;
    public $description;
    public $position;
    public $team;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $query = "
        SELECT ID as id, Title as title, Abbreviation as abbreviation, Description as description, Position as position
        FROM ". $this->db_table . "
        WHERE Teams_ID = :team AND Deleted=0
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        $stmt->execute();

        return $stmt;

    }

    public function edit() {

        $query = "
        UPDATE ".$this->db_table . " SET
        `Title` = :title, `Position` = :position
        WHERE `times`.`ID` = :id AND `times`.`Teams_ID` = :team;
        ";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->position = htmlspecialchars(strip_tags($this->position));
        $this->team = htmlspecialchars(strip_tags($this->team));
        //$this->abbreviation = htmlspecialchars(strip_tags($this->abbreviation));
        //$this->description = htmlspecialchars(strip_tags($this->description));

        if(strlen($this->title) < 1){throw new InvalidArgumentException('Title is required');}
        if($this->position < 1){throw new InvalidArgumentException('Min. position is 1');}

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':team', $this->team);
        //$stmt->bindParam(':abbreviation', $this->abbreviation);
        //$stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function create() {

        $query = "
            INSERT INTO ".$this->db_table . "
            (Title, Position, Teams_ID) VALUES
            (:title, :position, :team);
        ";

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->position = htmlspecialchars(strip_tags($this->position));
        $this->team = htmlspecialchars(strip_tags($this->team));
        //$this->abbreviation = htmlspecialchars(strip_tags($this->abbreviation));
        //$this->description = htmlspecialchars(strip_tags($this->description));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':team', $this->team);
        //$stmt->bindParam(':abbreviation', $this->abbreviation);
        //$stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }


    public function delete() {


        $query = "
            UPDATE " . $this->db_table . "
            SET `Deleted` = '1', Position = NULL, Title = CONCAT( IFNULL(Title,' '), :newTitle )
            WHERE ID = :id AND Teams_ID = :team
        ";

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->team = htmlspecialchars(strip_tags($this->team));
        $newTitle = ('_deletetAt_'.date_timestamp_get(date_create()));

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
