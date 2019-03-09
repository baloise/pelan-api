<?php
//error_reporting(0);
class Assignment {

    private $conn;
    private $db_table = "assignments";

    public $id;
    public $user;
    public $time;
    public $shift;
    public $date;
    public $note;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $query = "
        SELECT ID as id,
        Date as date,
        Note as note,
        Times_ID as time,
        Shifts_ID as shift,
        Users_ID as user
        FROM ". $this->db_table . "
        WHERE Users_ID = :user
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user', $this->user);
        $stmt->execute();

        return $stmt;

    }

    public function set() {

        $query = "
        REPLACE INTO ". $this->db_table . "
        (`Note`, `Date`, `Times_ID`, `Shifts_ID`, `Users_ID`) VALUES
        (:note, :date, :time, :shift, :user);
        ";

        $stmt = $this->conn->prepare($query);

        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->time = htmlspecialchars(strip_tags($this->time));
        $this->shift = htmlspecialchars(strip_tags($this->shift));
        $this->user = htmlspecialchars(strip_tags($this->user));

        $stmt->bindParam(':note', $this->note);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':shift', $this->shift);
        $stmt->bindParam(':user', $this->user);

        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

    /*
    public function create() {

        $query = "
            INSERT INTO ".$this->db_table . "
            (`Title`, `Abbreviation`, `Color`, `Description`, `Teams_ID`) VALUES
            (:title, :abbreviation, :color, :description, :team);
        ";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->abbreviation = htmlspecialchars(strip_tags($this->abbreviation));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->team = htmlspecialchars(strip_tags($this->team));

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':abbreviation', $this->abbreviation);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':team', $this->team);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;

    }


    public function delete() {

        $query = "
        DELETE FROM " . $this->db_table . "
        WHERE ID = :id AND Teams_ID = :team
        ";

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->team = htmlspecialchars(strip_tags($this->team));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":team", $this->team);

        if ($stmt->execute()) {

            return true;

        }

        return false;

    }
    */

}
