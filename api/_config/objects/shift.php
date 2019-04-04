<?php
//error_reporting(0);
class Shift {

    private $conn;
    private $db_table = "shift";

    public $id;
    public $title;
    public $color;
    public $description;
    public $team;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $query = "
        SELECT ID as id, Title as title, Color as color, Description as description
        FROM ". $this->db_table . " WHERE Team_ID = :team AND Active = 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);

        if ($stmt->execute()) {
            return $stmt;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function edit() {

        $query = "
        UPDATE ".$this->db_table." SET
        Title = :title, Color = :color, Description = :description
        WHERE ID = :id AND Team_ID = :team;
        ";

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->team = htmlspecialchars(strip_tags($this->team));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':team', $this->team);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function create() {

        $query = "
            INSERT INTO ".$this->db_table . "
            (`Title`, `Color`, `Description`, `Team_ID`, `Active`) VALUES
            (:title, :color, :description, :team, '1');
        ";

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->team = htmlspecialchars(strip_tags($this->team));

        if(strlen($this->title) < 1){throw new InvalidArgumentException('Title is required');}
        if(strlen($this->color) !== 6){throw new InvalidArgumentException('Hex-Color is required');}
        if(strlen($this->description) < 1){throw new InvalidArgumentException('Description is required');}

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':description', $this->description);
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
        UPDATE ".$this->db_table." SET
        Active = '0', Title = CONCAT( IFNULL(Title,' '), :newTitle )
        WHERE ID = :id AND Team_ID = :team
        ";

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->team = htmlspecialchars(strip_tags($this->team));
        $newTitle = ('_deletetAt_'.date_timestamp_get(/** @scrutinizer ignore-type */date_create()));

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
