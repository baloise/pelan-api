<?php

class User {

    private $conn;
    private $db_table = "user";
    private $db_table_role = "role";
    private $db_user_team = "user_has_team";
    private $db_view_detail = "view_user_detail";
    private $db_view_team_users = "view_team_users";
    private $db_view_team = "view_user_team";

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

    public function create() {

        $query = "
            INSERT INTO ".$this->db_table . "
            (`Firstname`, `Lastname`, `Nickname`, `Email`, `Auth_Key`, `Lang`) VALUES
            (:firstname, :lastname, :nickname, :email, :authkey, :language);
        ";

        $this->authkey = password_hash($this->authkey, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':authkey', $this->authkey);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

    }

    public function userExists() {

        $query = "
        SELECT ID, Auth_Key FROM " . $this->db_table . "
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

    public function readToken($teamid = false) {

        $sql1 = "SELECT * FROM ".$this->db_view_detail." WHERE id = ?";
        $sql2 = "SELECT * FROM ".$this->db_view_team." WHERE id = ?";
        $sql3 = "UPDATE ".$this->db_user_team." SET Current = NULL WHERE Current = 1";
        $sql4 = "UPDATE ".$this->db_user_team." SET Current = 1 WHERE Team_ID = ?";
        if ($teamid) { $sql2 .= " AND team_id = ?"; }
        $sql2 .= " ORDER BY current DESC LIMIT 1";
        $sql3 .= " AND User_ID = ?";
        $sql4 .= " AND User_ID = ?";

        $stmt = $this->conn->prepare($sql1);
        $stmt->bindParam(1, $this->id);

        if(!$stmt->execute()){
            throw new InvalidArgumentException($stmt1->errorInfo()[1]);
        }

        if ($stmt->rowCount() === 1) {

            $dRow = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->firstname = $dRow['firstname'];
            $this->lastname = $dRow['lastname'];
            $this->language = $dRow['language'];
            $this->nickname = $dRow['nickname'];
            $this->email = $dRow['email'];
            $this->role = new stdClass();
            $this->team = new stdClass();

            $stmt = $this->conn->prepare($sql2);
            $stmt->bindParam(1, $this->id);
            if ($teamid) { $stmt->bindParam(2, $teamid); }

            $stmt->execute();

            if ($stmt->rowCount() === 1) {

                $tRow = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->role = array(
                    "id" => (int) $tRow['role_id'],
                    "title" => $tRow['role_title'],
                    "admin" => (int) $tRow['role_admin']
                );

                $this->team = array(
                    "id" => (int) $tRow['team_id'],
                    "title" => $tRow['team_title']
                );

                $stmt = $this->conn->prepare($sql3);
                $stmt->bindParam(1, $this->id);
                $stmt->execute();
                $stmt = $this->conn->prepare($sql4);
                $stmt->bindParam(1, $this->id);
                $stmt->bindParam(2, $tRow['team_id']);
                $stmt->execute();

            } else {
                $this->role = 0;
                $this->team = 0;
            }

            return true;

        }

        return false;

    }

    public function edit() {

        $sql = "
            UPDATE ".$this->db_table." SET
            Firstname = :firstname,
            Lastname = :lastname,
            Nickname = :nickname,
            Lang = :language
            WHERE ID = :id
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':id', $this->id);

        if (!$stmt->execute()) {
            throw new InvalidArgumentException($stmt->errorInfo()[1]);
        }

        return true;

    }

    public function teamEdit($force = false) {

        $checkRole = "SELECT * FROM " . $this->db_table_role . " WHERE ID = :role AND Team_ID = :team";
        $checkUser = "SELECT * FROM " . $this->db_user_team . " WHERE User_ID = :id AND Team_ID = :team";

        $stmt1 = $this->conn->prepare($checkRole);
        $stmt2 = $this->conn->prepare($checkUser);
        $stmt1->bindParam(':role', $this->role);
        $stmt1->bindParam(':team', $this->team);
        $stmt2->bindParam(':id', $this->id);
        $stmt2->bindParam(':team', $this->team);

        $stmt1->execute();
        $stmt2->execute();

        if ($stmt1->rowCount() === 1 && $stmt2->rowCount() === 1 || $force) {

            $sql = "
                UPDATE ".$this->db_user_team." SET
                Role_ID = :role
                WHERE User_ID = :id AND Team_ID = :team
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':role', $this->role);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':team', $this->team);

            if (!$stmt->execute()) {
                throw new InvalidArgumentException($stmt->errorInfo()[1]);
            }

            return true;

        } else {
            throw new InvalidArgumentException("No permissions to edit this user or use this role");
        }

    }

    public function read($userid = false) {

        $sql1 = "
        SELECT * FROM ".$this->db_view_team_users."
        WHERE team_id = :team
        ";

        if ($userid) {
            $sql1 .= " AND id = :userid";
        }

        $stmt = $this->conn->prepare($sql1);
        $stmt->bindParam(':team', $this->team);
        if ($userid) {
            $stmt->bindParam(':userid', $userid);
        }
        $stmt->execute();

        return $stmt;

    }

}
