-- -------------- INITIALISIERUNG 'pelan'
CREATE DATABASE IF NOT EXISTS pelan DEFAULT CHARACTER SET utf8;
USE pelan;

-- ---- TABLE 'user'
CREATE TABLE IF NOT EXISTS user (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Firstname           VARCHAR(255) NOT NULL,
    Lastname            VARCHAR(255) NOT NULL,
    Nickname            VARCHAR(10) NOT NULL,
    Email               VARCHAR(89) NOT NULL,
    Auth_Key            VARCHAR(255) NOT NULL,
    Lang                ENUM('de', 'en') NOT NULL,
    Last_Login          TIMESTAMP,
    Team_ID             INT,
    Role_ID             INT,

    UNIQUE INDEX UNIQUE_Email (Email),

    PRIMARY KEY (ID)
);

-- ---- TABLE 'user_is_dev'
CREATE TABLE IF NOT EXISTS user_is_dev (
    User_ID             INT NOT NULL,
    Developer           BOOLEAN NOT NULL,

    PRIMARY KEY (User_ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID)
);

-- ---- TABLE 'team'
CREATE TABLE IF NOT EXISTS team (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Title               VARCHAR(255) NOT NULL,
    Description         MEDIUMTEXT NOT NULL,
    Public              BOOLEAN,
    Owner_ID            INT NOT NULL,

    UNIQUE INDEX UNIQUE_Title (Title),

    PRIMARY KEY (ID),
    FOREIGN KEY (Owner_ID) REFERENCES user(ID)
);

-- ---- TABLE 'role'
CREATE TABLE IF NOT EXISTS role (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Title               VARCHAR(255) NOT NULL,
    Description         MEDIUMTEXT NOT NULL,
    Admin               BOOLEAN,
    Team_ID             INT NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (Team_ID) REFERENCES team(ID)
);

-- ---- ALTER TABLE 'user'
ALTER TABLE user ADD FOREIGN KEY (Team_ID) REFERENCES team(ID);
ALTER TABLE user ADD FOREIGN KEY (Role_ID) REFERENCES role(ID);

-- ---- TABLE 'invitation'
CREATE TABLE IF NOT EXISTS invitation (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Creator_ID          INT NOT NULL,
    Created             TIMESTAMP NOT NULL,
    Code                VARCHAR(255) NOT NULL,
    Email               INT NOT NULL,
    Team_ID             INT NOT NULL,
    Role_ID             INT NOT NULL,

    UNIQUE INDEX UNIQUE_Code (Code),

    PRIMARY KEY (ID),
    FOREIGN KEY (Creator_ID) REFERENCES user(ID),
    FOREIGN KEY (Team_ID) REFERENCES team(ID),
    FOREIGN KEY (Role_ID) REFERENCES role(ID)
);

-- ---- TABLE 'daytime'
CREATE TABLE IF NOT EXISTS daytime (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Title               VARCHAR(255) NOT NULL,
    Description         MEDIUMTEXT NOT NULL,
    Abbreviation        VARCHAR(5) NOT NULL,
    Position            INT,
    Active              BOOLEAN,
    Team_ID             INT NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (Team_ID) REFERENCES team(ID)
);

-- ---- TABLE 'shift'
CREATE TABLE IF NOT EXISTS shift (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Title               VARCHAR(255) NOT NULL,
    Description         MEDIUMTEXT NOT NULL,
    Color               VARCHAR(6) NOT NULL,
    Active              BOOLEAN,
    Team_ID             INT NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (Team_ID) REFERENCES team(ID)
);

-- ---- TABLE 'assignment'
CREATE TABLE IF NOT EXISTS assignment (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Daytime_ID          INT NOT NULL,
    User_ID             INT NOT NULL,
    Creator_ID          INT NOT NULL,
    Date                DATE NOT NULL,
    Shift_ID            INT,
    Note                MEDIUMTEXT,

    UNIQUE INDEX UNIQUE_Date_per_User_per_Daytime (Date, User_ID, Daytime_ID),

    PRIMARY KEY (ID),
    FOREIGN KEY (Daytime_ID) REFERENCES daytime(ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID),
    FOREIGN KEY (Creator_ID) REFERENCES user(ID),
    FOREIGN KEY (Shift_ID) REFERENCES shift(ID)
);


-- -- VIEW 'view_usertoken'
CREATE VIEW view_usertoken AS

    SELECT

        us.ID AS 'ID',
        us.Firstname AS 'Firstname',
        us.Lastname AS 'Lastname',
        us.Lang AS 'Language',
        us.Nickname AS 'Nickname',
        us.Email AS 'Email',

        ro.ID AS 'Role_ID',
        ro.Title AS 'Role_Title',
        ro.Description AS 'Role_Description',
        ro.Admin AS 'Role_Admin',

        te.ID AS 'Team_ID',
        te.Title AS 'Team_Title'

    FROM user AS us
    INNER JOIN role AS ro ON us.Role_ID = ro.ID
    INNER JOIN team AS te ON us.Team_ID = te.ID;


-- -- VIEW 'view_teamassigns'
CREATE VIEW view_teamassigns AS

    SELECT

        ass.ID as 'id',
        ass.Date as 'date',
        ass.Note as 'note',
        ass.Daytime_ID as 'time',
        ass.Shift_ID as 'shift',
        ass.User_ID as 'user',
        usr.Team_ID as 'user_team'

    FROM assignment AS ass
    INNER JOIN user AS usr ON usr.ID = ass.User_ID;
