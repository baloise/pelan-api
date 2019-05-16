<?php

class Team {

    private $conn;
    private $db_table = "team";
    private $db_users = "user_has_team";
    private $db_invite = "invitation";
    private $db_view_user = "view_user_team";
    private $db_view_invite = "view_invite_detail";

    public $id;
    public $title;
    public $abbreviation;
    public $public;
    public $user;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {

        $sql = "SELECT * FROM " . $this->db_view_user . " WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();

        if ($stmt->execute()) {
            return $stmt;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function create() {

        $sql = "
            INSERT INTO ".$this->db_table . "
            (`Title`, `Description`, `Owner_ID`) VALUES
            (:title, :description, :owner);
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':owner', $this->user);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function join($role = false) {

        if(!$role){
            return false;
        }

        $sql1 = "
            INSERT INTO ".$this->db_users." (`User_ID`, `Team_ID`, `Role_ID`) VALUES
            (:user, :team, :role);
        ";

        $sql2 = "SELECT Title, Description FROM ".$this->db_table." WHERE ID = :team";

        $stmt = $this->conn->prepare($sql1);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':team', $this->id);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {

            $stmt = $this->conn->prepare($sql2);
            $stmt->bindParam(':team', $this->id);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return array(
                    "id"=>$this->id,
                    "title"=>$row['Title'],
                    "description"=>$row['Description']
                );
            }

        } else {
            if($stmt->errorInfo()[1] == "1062"){
                return false;
            }
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function leave($userid) {

        if(!$userid){
            throw new InvalidArgumentException("No valid Invite ID found");
        }

        $sql1 = "
        SELECT * FROM " . $this->db_users . "
        WHERE User_ID = :user AND Team_ID = :team
        ";

        $sql2 = "
        DELETE FROM " . $this->db_users . "
        WHERE User_ID = :user AND Team_ID = :team
        ";

        $stmt = $this->conn->prepare($sql1);
        $stmt->bindParam(":user", $userid);
        $stmt->bindParam(":team", $this->id);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {

            $stmt = $this->conn->prepare($sql2);
            $stmt->bindParam(":user", $userid);
            $stmt->bindParam(":team", $this->id);
            if ($stmt->execute()) {
                return true;
            } else {
                throw new InvalidArgumentException($stmt->errorInfo()[1]);
            }

        } else {
            throw new InvalidArgumentException("No matching user");
        }

    }

    public function invite($invitor = false, $role = false, $email = false) {

        if(!$role || !$invitor || !$email){
            return false;
        }

        $sql = "
            INSERT INTO ".$this->db_invite."
            (`Creator_ID`, `Code`, `Email`, `Team_ID`, `Role_ID`) VALUES
            (:creator, :code, :email, :team, :role);
        ";

        $code = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < 10; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $code .= $characters[$index];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':creator', $invitor);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':team', $this->id);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return $code;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function hasInvite($code = false, $email = false) {

        if(!$email || !$code){
            return false;
        }

        $sql = "
            SELECT * FROM " . $this->db_invite . "
            WHERE Code = :code AND Email = :email
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return array(
                "role"=>$row['Role_ID'],
                "team"=>$row['Team_ID']
            );
        } else {
            return false;
        }

        throw new InvalidArgumentException($stmt->errorInfo()[1]);

    }

    public function readInvites() {

        $sql = "SELECT * FROM " . $this->db_view_invite . " WHERE team = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->execute()) {
            return $stmt;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function deleteInvite($invite_id = false) {

        if(!$invite_id){
            throw new InvalidArgumentException("No valid Invite ID found");
        }

        $sql1 = "
        SELECT * FROM " . $this->db_invite . "
        WHERE ID = :id AND Team_ID = :team
        ";

        $sql2 = "
        DELETE FROM " . $this->db_invite . "
        WHERE ID = :id AND Team_ID = :team
        ";

        $stmt = $this->conn->prepare($sql1);
        $stmt->bindParam(":id", $invite_id);
        $stmt->bindParam(":team", $this->id);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {

            $stmt = $this->conn->prepare($sql2);
            $stmt->bindParam(":id", $invite_id);
            $stmt->bindParam(":team", $this->id);

            if ($stmt->execute()) {
                return true;
            } else {
                throw new InvalidArgumentException($stmt->errorInfo()[1]);
            }

        } else {
            throw new InvalidArgumentException("No matching invite");
        }

    }

}
