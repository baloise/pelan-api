<?php
//error_reporting(0);
class Shift {

    private $conn;
    private $db_table = "shifts";

    public $id;
    public $title;
    public $abbreviation;
    public $color;
    public $description;
    public $team;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){

        $query = "
        SELECT ID as id, Title as title, Abbreviation as abbreviation, Color as color, Description as description
        FROM ". $this->db_table . "
        WHERE Teams_ID = :team
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        $stmt->execute();

        return $stmt;

    }

    public function edit(){

        $query = "
        UPDATE ".$this->db_table." SET
        `Title` = :title, `Abbreviation` = :abbreviation, `Color` = :color, `Description` = :description
        WHERE `shifts`.`ID` = :id AND `shifts`.`Teams_ID` = :team;
        ";

        $stmt = $this->conn->prepare($query);

        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->abbreviation=htmlspecialchars(strip_tags($this->abbreviation));
        $this->color=htmlspecialchars(strip_tags($this->color));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->team=htmlspecialchars(strip_tags($this->team));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':team', $this->team);

        if($stmt->execute()){
            return true;
        }

        return false;

    }

    public function create(){

        $query = "
            INSERT INTO ".$this->db_table."
            (`Title`, `Abbreviation`, `Color`, `Description`, `Teams_ID`) VALUES
            (:title, :abbreviation, :color, :description, :team);
        ";

        $stmt = $this->conn->prepare($query);

        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->abbreviation=htmlspecialchars(strip_tags($this->abbreviation));
        $this->color=htmlspecialchars(strip_tags($this->color));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->team=htmlspecialchars(strip_tags($this->team));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':team', $this->team);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;

    }


    public function delete(){

        $query = "
        DELETE FROM " . $this->db_table . "
        WHERE ID = :id AND Teams_ID = :team
        ";

        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->team=htmlspecialchars(strip_tags($this->team));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":team", $this->team);

        if($stmt->execute()){

            return true;

        }

        return false;

    }


}
