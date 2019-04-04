<?php
//error_reporting(0);
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

        $this->note = htmlspecialchars(strip_tags($this->note));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->time = htmlspecialchars(strip_tags($this->time));
        $this->user = htmlspecialchars(strip_tags($this->user));
        $this->creator = htmlspecialchars(strip_tags($this->creator));

        if (isset($this->shift)) {
            $this->shift = htmlspecialchars(strip_tags($this->shift));
        } else if (strlen($this->note) > 1) {
            $this->shift = NULL;
        } else {
            throw new InvalidArgumentException("Missing Shift or Note");
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

        $query = "
        DELETE FROM " . $this->db_table . " WHERE
        Date = :date AND Daytime_ID = :time AND User_ID = :user
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

    }

}
