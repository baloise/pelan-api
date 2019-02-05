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

    function userExists(){

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

    function create(){

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

    /*
    public function update(){

        $query = "
        UPDATE " . $this->db_table . " SET
        Firstname = :firstname,
        Lastname = :lastname,
        Language = :language,
        IsFemale = :isFemale,
        Birthdate = :birthdate,
        Height = :height,
        Aim_Weight = :aim_weight,
        Aim_Date = :aim_date
        WHERE ID = :id
        ";

        $stmt = $this->conn->prepare($query);
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->language=htmlspecialchars(strip_tags($this->language));
        $this->isFemale=htmlspecialchars(strip_tags($this->isFemale));
        $this->birthdate=htmlspecialchars(strip_tags($this->birthdate));
        $this->height=htmlspecialchars(strip_tags($this->height));
        $this->aims->weight=htmlspecialchars(strip_tags($this->aims->weight));
        $this->aims->date=htmlspecialchars(strip_tags($this->aims->date));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':language', $this->language);
        $stmt->bindParam(':isFemale', $this->isFemale);
        $stmt->bindParam(':height', $this->height);
        $stmt->bindParam(':birthdate', $this->birthdate);
        $stmt->bindParam(':aim_weight', $this->aims->weight);
        $stmt->bindParam(':aim_date', $this->aims->date);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){
            return true;
        }

        return false;

    }
    */

}
?>
