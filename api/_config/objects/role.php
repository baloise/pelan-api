<?php

class Role {

    private $conn;
    private $db_table = "role";

    public $id;
    public $title;
    public $description;
    public $admin;
    public $team;
    public $main;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $query = "SELECT * FROM ". $this->db_table . " WHERE Team_ID = :team";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        $stmt->execute();

        return $stmt;

    }

    public function create() {

        $sql = "
            INSERT INTO ".$this->db_table . "
            (`Title`, `Description`, `Admin`, `Team_ID`, `Main`) VALUES
            (:title, :description, :admin, :team, :main);
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':admin', $this->admin);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':main', $this->main);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function delete() {

        $sql1 = "SELECT ID, Main FROM ".$this->db_table . " WHERE ID = :id AND Team_ID = :team";

        $sql2 = "
        DELETE FROM " . $this->db_table . "
        WHERE ID = :id AND Team_ID = :team
        ";

        $stmt = $this->conn->prepare($sql1);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':team', $this->team);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(intval($row['Main']) === 1){
                throw new InvalidArgumentException('Unable to delete main role');
            }
        } else {
            throw new InvalidArgumentException('Role not found');
        }

        $stmt = $this->conn->prepare($sql2);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":team", $this->team);

        if ($stmt->execute()) {
            return true;
        }

        if($stmt->errorInfo()[1] == 1451){
            throw new InvalidArgumentException('role_has_user_or_invitation');
        }

        throw new InvalidArgumentException($stmt->errorInfo()[1]);

    }

    public function edit() {

        $sql1 = "SELECT ID, Main FROM ".$this->db_table . " WHERE ID = :id AND Team_ID = :team";

        $sql2 = "
        UPDATE ".$this->db_table . " SET
        Title = :title, Description = :description, Admin = :admin
        WHERE ID = :id AND Team_ID = :team
        ";

        $stmt = $this->conn->prepare($sql1);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':team', $this->team);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(intval($row['Main']) === 1){
                $this->admin = 1;
            }

            $stmt = $this->conn->prepare($sql2);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':team', $this->team);
            $stmt->bindParam(':admin', $this->admin);

            if (!$stmt->execute()) {
                throw new InvalidArgumentException($stmt->errorInfo()[1]);
            }

            return true;

        }

        throw new InvalidArgumentException('Role not found');

    }

}
