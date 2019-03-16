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

    public function read($from = false, $to = false) {

        $query = "
        SELECT ID as id, Date as date, Note as note, Times_ID as time, Shifts_ID as shift, Users_ID as user
        FROM ". $this->db_table . "
        WHERE Users_ID = :user
        ";

        if($from && $to){
            $query .= "AND Date BETWEEN :from AND :to";
        }


        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user', $this->user);
        if($from && $to){
            $stmt->bindParam(':from', $from);
            $stmt->bindParam(':to', $to);
        }

        $stmt->execute();
        return $stmt;

    }

    public function readNotes($from = false, $to = false) {

        if($from && $to){

            $query = "
            SELECT ID as id, Date as date, Note as note, Users_ID as user
            FROM ". $this->db_table . "
            WHERE Date BETWEEN :from AND :to
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':from', $from);
            $stmt->bindParam(':to', $to);


            $stmt->execute();
            return $stmt;

        }

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
        $this->user = htmlspecialchars(strip_tags($this->user));

        if(isset($this->shift)){
            $this->shift = htmlspecialchars(strip_tags($this->shift));
        } else if(strlen($this->note) > 1){
            $this->shift = NULL;
        } else {
            throw new InvalidArgumentException("Missing Shift or Note");
        }

        $stmt->bindParam(':note', $this->note);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':shift', $this->shift);
        $stmt->bindParam(':user', $this->user);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }
        
        return false;

    }

    public function delete() {

        $query = "
        DELETE FROM " . $this->db_table . " WHERE
        Date = :date AND Times_ID = :time AND Users_ID = :user
        ";

        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->time = htmlspecialchars(strip_tags($this->time));
        $this->user = htmlspecialchars(strip_tags($this->user));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":time", $this->time);
        $stmt->bindParam(":user", $this->user);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

        return false;

    }

}
