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
        WHERE Identifier = ?
        LIMIT 0,1
        ";

        $this->identifier=htmlspecialchars(strip_tags($this->identifier));

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->identifier);
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

    /*
    function create(){

        $query = "
        INSERT INTO " . $this->db_table . " SET
        Firstname = :firstname,
        Lastname = :lastname,
        Email = :email,
        Password = :password";

        $stmt = $this->conn->prepare($query);

        if(strlen($this->firstname) > 0 && strlen($this->lastname) > 0){
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        } else {
            throw new InvalidArgumentException('Invalid Firstname or Lastname');
        }

        if($this->emailExists()){
            throw new InvalidArgumentException('E-Mail already in use');
        }

        if(filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $this->email=htmlspecialchars(strip_tags($this->email));
        } else {
            throw new InvalidArgumentException('Invalid E-Mail Adress');
        }

        if (strlen($this->password) < 8 && !preg_match("#[0-9]+#", $this->password) && !preg_match("#[a-zA-Z]+#", $this->password)) {
            throw new InvalidArgumentException('Invalid Password');
        } else {
            $this->password=htmlspecialchars(strip_tags($this->password));
        }

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        if($stmt->execute()){
            return true;
        }

        return false;

    }

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
