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
    public $teamid;

    public function __construct($db){
        $this->conn = $db;
    }

    function read(){

        $query = "
        SELECT ID as id, Title as title, Abbreviation as abbreviation, Color as color, Description as description
        FROM ". $this->db_table . "
        WHERE Teams_ID = :teamid
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':teamid', $this->teamid);
        $stmt->execute();

        return $stmt;

    }

    function edit(){

        $query = "
        UPDATE ".$this->db_table." SET
        `Title` = :title, `Abbreviation` = :abbreviation, `Color` = :color, `Description` = :description
        WHERE `shifts`.`ID` = :id AND `shifts`.`Teams_ID` = :teamid;
        ";

        $stmt = $this->conn->prepare($query);

        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->abbreviation=htmlspecialchars(strip_tags($this->abbreviation));
        $this->color=htmlspecialchars(strip_tags($this->color));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->teamid=htmlspecialchars(strip_tags($this->teamid));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':teamid', $this->teamid);

        return $stmt->execute();
        if($stmt->execute()){
            return $stmt->execute();
        }

    }

    function create(){

        $query = "
            INSERT INTO ".$this->db_table."
            (`Title`, `Abbreviation`, `Color`, `Description`, `Teams_ID`)
            VALUES
            (:title, :abbreviation, :color, :description, :teamid);
        ";

        $stmt = $this->conn->prepare($query);

        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->abbreviation=htmlspecialchars(strip_tags($this->abbreviation));
        $this->color=htmlspecialchars(strip_tags($this->color));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->teamid=htmlspecialchars(strip_tags($this->teamid));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':teamid', $this->teamid);

        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;

    }

    /*
    function delete(){

        $query = "
        DELETE FROM " . $this->db_table . "
        WHERE ID = :id AND UserID = :userid
        ";

        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->userid=htmlspecialchars(strip_tags($this->userid));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":userid", $this->userid);

        if($stmt->execute()){

            return true;

        }

        return false;

    }
    */

}
?>
