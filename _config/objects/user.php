<?php

//error_reporting(0);

class User {

    private $conn;
    private $db_table = "users";

    public $id;
    public $firstname;
    public $lastname;
    public $language;
    public $identifier;
    public $nickname;
    public $email;
    public $team;
    public $role;

    public function __construct($db){
        $this->conn = $db;
    }

    public function userExists(){

        $query = "
        SELECT ID, Firstname, Lastname, Language, Identifier, Nickname, Email, Roles_ID, Teams_ID
        FROM " . $this->db_table . "
        WHERE Email = ?
        LIMIT 0,1
        ";


        if(filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $this->email=htmlspecialchars(strip_tags($this->email));
        } else {
            throw new InvalidArgumentException('Invalid E-Mail Adress');
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount()>0){

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['ID'];
            $this->firstname = $row['Firstname'];
            $this->lastname = $row['Lastname'];
            $this->language = $row['Language'];
            $this->identifier = $row['Identifier'];
            $this->nickname = $row['Nickname'];
            $this->email = $row['Email'];
            $this->role = $row['Roles_ID'];
            $this->team = $row['Teams_ID'];

            return true;

        }

        return false;

    }

    public function create(){

        $query =  "
        INSERT INTO " . $this->db_table . "
        (`Firstname`, `Lastname`, `Language`, `Identifier`, `Nickname`, `Email`) VALUES
        (:firstname, :lastname, :language, :identifier, :nickname, :email);
        ";

        $stmt = $this->conn->prepare($query);

        if(
            mb_strlen($this->firstname) > 0 &&
            mb_strlen($this->lastname) > 0 &&
            mb_strlen($this->language) > 0 &&
            mb_strlen($this->language) <= 2 &&
            mb_strlen($this->identifier) > 0 &&
            mb_strlen($this->nickname) > 0 &&
            mb_strlen($this->nickname) <= 6
        ){

            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->lastname=htmlspecialchars(strip_tags($this->lastname));
            $this->language=htmlspecialchars(strip_tags($this->language));
            $this->identifier=htmlspecialchars(strip_tags($this->identifier));
            $this->nickname=htmlspecialchars(strip_tags($this->nickname));

        } else {
            throw new InvalidArgumentException('Missing Values');
        }

        if($this->userExists()){
            throw new InvalidArgumentException('User already exists');
        }

        if(filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $this->email=htmlspecialchars(strip_tags($this->email));
        } else {
            throw new InvalidArgumentException('Invalid E-Mail Adress');
        }

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':identifier', $this->identifier);
        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->bindParam(':email', $this->email);

        $identifier_hash = password_hash($this->identifier, PASSWORD_BCRYPT);
        $stmt->bindParam(':identifier', $identifier_hash);

        if($stmt->execute()){
            return true;
        }

        return false;

    }

    public function edit(){

        $query = "
        UPDATE " . $this->db_table . " SET
        Firstname = :firstname,
        Lastname = :lastname,
        Language = :language,
        Nickname = :nickname,
        Roles_ID = :role
        WHERE ID = :id AND Teams_ID = :team
        ";

        $stmt = $this->conn->prepare($query);
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->language=htmlspecialchars(strip_tags($this->language));
        $this->nickname=htmlspecialchars(strip_tags($this->nickname));
        $this->role=htmlspecialchars(strip_tags($this->role));
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->team=htmlspecialchars(strip_tags($this->team));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':team', $this->team);

        if($stmt->execute()){
            return true;
        }

        return false;

    }


}
