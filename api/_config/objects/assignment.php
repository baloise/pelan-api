<?php

class Assignment {

    private $conn;
    private $db_table = "assignment";
    private $db_teamassigns = "view_teamassigns";

    public $id;
    public $time;
    public $user;
    public $creator;
    public $date;
    public $shift;
    public $note;
    public $team;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($from = false, $to = false) {

        $query = "
        SELECT * FROM ". $this->db_teamassigns . "
        WHERE user_team = :team AND user = :user
        ";

        if ($from && $to) {
            $query .= " AND date BETWEEN :from AND :to";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':user', $this->user);
        if ($from && $to) {
            $stmt->bindParam(':from', $from);
            $stmt->bindParam(':to', $to);
        }

        $stmt->execute();
        return $stmt;

    }

    public function readNotes($from = false, $to = false) {

        if ($from && $to) {

            $query = "
            SELECT * FROM ". $this->db_teamassigns . "
            WHERE user_team = :team AND date BETWEEN :from AND :to
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':team', $this->team);
            $stmt->bindParam(':from', $from);
            $stmt->bindParam(':to', $to);
            $stmt->execute();

            return $stmt;

        }

    }

    public function set() {

        $query = "
        REPLACE INTO ". $this->db_table . "
        (`Note`, `Date`, `Daytime_ID`, `Shift_ID`, `User_ID`, `Creator_ID`) VALUES
        (:note, :date, :time, :shift, :user, :creator);
        ";

        if ($this->shift <= 0 && strlen($this->note) <= 0) {
            throw new InvalidArgumentException("Missing Shift or Note");
        } else if($this->shift <= 0) {
            $this->shift = NULL;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':note', $this->note);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':shift', $this->shift);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':creator', $this->creator);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function delete() {

        $getid = "
        SELECT id FROM ". $this->db_teamassigns . "
        WHERE date = :date AND time = :time AND user = :user AND user_team = :team
        ";

        $query = "
        DELETE FROM " . $this->db_table . "
        WHERE ID = :id
        ";

        $stmt = $this->conn->prepare($getid);
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":time", $this->time);
        $stmt->bindParam(":user", $this->user);
        $stmt->bindParam(":team", $this->team);

        if ($stmt->execute() && $stmt->rowCount() === 1) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new InvalidArgumentException($stmt->errorInfo()[1]);
            }

        } else {
            throw new InvalidArgumentException("No matching assign");
        }

    }

}
