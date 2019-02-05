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

    public function __construct($db){
        $this->conn = $db;
    }

    function read(){

        $query = "
        SELECT ID as id, Title as title, Abbreviation as abbreviation, Description as description, Position as position
        FROM ". $this->db_table . "
        WHERE Teams_ID = :team
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        $stmt->execute();

        return $stmt;

    }

    function edit(){

        $query = "
        UPDATE ".$this->db_table." SET
        `Title` = :title, `Abbreviation` = :abbreviation, `Position` = :position, `Description` = :description
        WHERE `times`.`ID` = :id AND `times`.`Teams_ID` = :team;
        ";

        $stmt = $this->conn->prepare($query);

        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->abbreviation=htmlspecialchars(strip_tags($this->abbreviation));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->position=htmlspecialchars(strip_tags($this->position));
        $this->team=htmlspecialchars(strip_tags($this->team));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':team', $this->team);

        if($stmt->execute()){
            return true;
        }

        print_r($stmt);
        die();

        return false;

    }

    function create(){

        $query = "
            INSERT INTO ".$this->db_table."
            (`Title`, `Abbreviation`, `Position`, `Description`, `Teams_ID`) VALUES
            (:title, :abbreviation, :position, :description, :team);
        ";

        $stmt = $this->conn->prepare($query);

        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->abbreviation=htmlspecialchars(strip_tags($this->abbreviation));
        $this->position=htmlspecialchars(strip_tags($this->position));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->team=htmlspecialchars(strip_tags($this->team));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':team', $this->team);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;

    }


    function delete(){

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
?>
