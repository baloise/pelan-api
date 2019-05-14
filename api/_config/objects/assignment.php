<?php

class Assignment {

    private $conn;
    private $db_table = "assignment";
    private $db_team_assigns = "view_assigns_team";

    public $user;
    public $date;
    public $time;
    public $shift;
    public $note;
    public $team;
    public $creator;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readTeam($from = false, $to = false) {

        $query = "
        SELECT * FROM ". $this->db_team_assigns . "
        WHERE team = :team
        ";

        if ($from && $to) {
            $query .= " AND date BETWEEN :from AND :to";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        if ($from && $to) {
            $stmt->bindParam(':from', $from);
            $stmt->bindParam(':to', $to);
        }

        $stmt->execute();
        return $stmt;

    }

    public function read($from = false, $to = false) {

        $query = "
        SELECT * FROM ". $this->db_team_assigns . "
        WHERE team = :team AND user = :user
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
            SELECT * FROM ". $this->db_team_assigns . "
            WHERE team = :team AND date BETWEEN :from AND :to
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
        (`User_ID`, `Date`, `Daytime_ID`, `Shift_ID`, `Note`, `Team_ID`, `Creator_ID`) VALUES
        (:user, :date, :time, :shift, :note, :team, :creator);
        ";

        if ($this->shift <= 0 && strlen($this->note) <= 0) {
            throw new InvalidArgumentException("Missing Shift or Note");
        } else if ($this->shift <= 0) {
            $this->shift = NULL;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':time', $this->time);
        $stmt->bindParam(':shift', $this->shift);
        $stmt->bindParam(':note', $this->note);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':creator', $this->creator);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function delete() {

        $sql1 = "
        SELECT * FROM " . $this->db_table . "
        WHERE User_ID = :user AND Date = :date AND Daytime_ID = :time AND Team_ID = :team
        ";

        $sql2 = "
        DELETE FROM " . $this->db_table . "
        WHERE User_ID = :user AND Date = :date AND Daytime_ID = :time AND Team_ID = :team
        ";

        $stmt = $this->conn->prepare($sql1);
        $stmt->bindParam(":user", $this->user);
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":time", $this->time);
        $stmt->bindParam(":team", $this->team);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {

            $stmt = $this->conn->prepare($sql2);
            $stmt->bindParam(":user", $this->user);
            $stmt->bindParam(":date", $this->date);
            $stmt->bindParam(":time", $this->time);
            $stmt->bindParam(":team", $this->team);

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
