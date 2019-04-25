<?php

class User {

    private $conn;
    private $db_table = "user";
    private $db_table_role = "role";
    private $db_view_token = "view_usertoken";

    public $id;
    public $firstname;
    public $lastname;
    public $language;
    public $authkey;
    public $nickname;
    public $email;
    public $team;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function userExists() {

        $query = "
        SELECT ID, Auth_Key, Role_ID, Team_ID FROM " . $this->db_table . "
        WHERE Email = ? LIMIT 0,1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['ID'];
            $this->authkey = $row['Auth_Key'];
            return true;
        }

    }

    public function readToken() {

        $query = "SELECT * FROM " . $this->db_view_token . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {

            $this->role = new stdClass();
            $this->team = new stdClass();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->firstname = $row['Firstname'];
            $this->lastname = $row['Lastname'];
            $this->language = $row['Language'];
            $this->nickname = $row['Nickname'];
            $this->email = $row['Email'];
            $this->role->id = $row['Role_ID'];
            $this->role->title = $row['Role_Title'];
            $this->role->description = $row['Role_Description'];
            $this->role->admin = $row['Role_Admin'];
            $this->team->id = $row['Team_ID'];
            $this->team->title = $row['Team_Title'];

            return true;

        }

        return false;

    }

    public function editLanguage() {

        $query = "UPDATE " . $this->db_table . " SET Lang = :language WHERE ID = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function editDetails() {

        $checkQuery = "SELECT * FROM " . $this->db_table_role . " WHERE ID = :id AND Team_ID = :team";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(':team', $this->team);
        $stmt->bindParam(':id', $this->role);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {

            $query = "
                UPDATE ".$this->db_table . " SET
                Firstname = :firstname,
                Lastname = :lastname,
                Nickname = :nickname,
                Role_ID = :role
                WHERE ID = :id AND Team_ID = :team
            ";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':nickname', $this->nickname);
            //$stmt->bindParam(':language', $this->language);
            $stmt->bindParam(':role', $this->role);
            $stmt->bindParam(':team', $this->team);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new InvalidArgumentException($stmt->errorInfo()[1]);
            }

        } else {
            throw new InvalidArgumentException("Role doesn't match team");
        }

    }

    public function read($userid = false) {

        $query = "
        SELECT ID as id, Firstname as firstname, Lastname as lastname, Nickname as nickname, Lang as language, Role_ID as role
        FROM ". $this->db_table . "
        WHERE Team_ID = :team
        ";

        if ($userid) {
            $query .= " AND ID = :userid";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':team', $this->team);
        if ($userid) {
            $stmt->bindParam(':userid', $userid);
        }
        $stmt->execute();

        return $stmt;

    }

}
