<?php

class Assignment {

    private $conn;
    private $db_table = "assignment";
    private $db_view_team = "view_assigns_team";
    private $db_view_notes = "view_assign_notes";

    public $date;
    public $time;
    public $shift;
    public $note;
    public $team;
    public $creator;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read($from = false, $to = false, $user = false) {

        if (!$from || !$to) {
            throw new InvalidArgumentException("start or end date not found");
        }

        $sql = "
            SELECT * FROM ". $this->db_view_team . "
            WHERE team = :team AND date BETWEEN :from AND :to
        ";

        if($user){ $sql .= " AND user = :user"; }

        $sql .= " ORDER BY user, date, time";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':to', $to);

        if ($user) {
            $stmt->bindParam(':user', $user);
        }

        $stmt->execute();
        return $stmt;

    }

    public function readNotes($from = false, $to = false) {

        if ($from && $to) {

            $query = "
            SELECT * FROM ". $this->db_view_notes . "
            WHERE team_id = :team AND date BETWEEN :from AND :to
            ORDER BY date, time_id
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
