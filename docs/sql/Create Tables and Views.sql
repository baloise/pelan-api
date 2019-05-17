-- -------------- INITIALISIERUNG 'pelan'
CREATE DATABASE IF NOT EXISTS pelan DEFAULT CHARACTER SET utf8;
USE pelan;


-- -------------- CREATE TABLES

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
    Main                BOOLEAN,

    PRIMARY KEY (ID),
    FOREIGN KEY (Team_ID) REFERENCES team(ID)
);

-- ---- TABLE 'user_has_team'
CREATE TABLE IF NOT EXISTS user_has_team (
    User_ID             INT NOT NULL,
    Team_ID             INT NOT NULL,
    Role_ID             INT NOT NULL,
    Current             BOOLEAN,

    UNIQUE INDEX UNIQUE_Current_per_User (User_ID, Current),

    PRIMARY KEY (User_ID, Team_ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID),
    FOREIGN KEY (Team_ID) REFERENCES team(ID),
    FOREIGN KEY (Role_ID) REFERENCES role(ID)
);

-- ---- TABLE 'invitation'
CREATE TABLE IF NOT EXISTS invitation (
    ID                  INT NOT NULL AUTO_INCREMENT,
    Creator_ID          INT NOT NULL,
    Created             TIMESTAMP NOT NULL,
    Code                VARCHAR(10) NOT NULL,
    Email               VARCHAR(89) NOT NULL,
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
    User_ID             INT NOT NULL,
    Date                DATE NOT NULL,
    Daytime_ID          INT NOT NULL,
    Shift_ID            INT,
    Note                MEDIUMTEXT,
    Team_ID             INT NOT NULL,
    Creator_ID          INT NOT NULL,

    PRIMARY KEY (User_ID, Date, Daytime_ID),
    FOREIGN KEY (Daytime_ID) REFERENCES daytime(ID),
    FOREIGN KEY (User_ID) REFERENCES user(ID),
    FOREIGN KEY (Creator_ID) REFERENCES user(ID),
    FOREIGN KEY (Team_ID) REFERENCES team(ID),
    FOREIGN KEY (Shift_ID) REFERENCES shift(ID)
);


-- -------------- CREATE VIEWS

-- -- VIEW 'view_user_detail'
CREATE VIEW view_user_detail AS

    SELECT

        us.ID AS 'id',
        us.Firstname AS 'firstname',
        us.Lastname AS 'lastname',
        us.Lang AS 'language',
        us.Nickname AS 'nickname',
        us.Email AS 'email'

    FROM user AS us;


-- -- VIEW 'view_user_team'
CREATE VIEW view_user_team AS

    SELECT

        uht.User_ID AS 'id',
        uht.Current AS 'current',
        te.ID AS 'team_id',
        te.Title AS 'team_title',
        te.Description AS 'team_description',
        CONCAT(us.Firstname, ' ', us.Lastname) AS 'team_owner',
        us.ID AS 'team_owner_id',
        ro.ID AS 'role_id',
        ro.Title AS 'role_title',
        ro.Admin AS 'role_admin'

    FROM user_has_team AS uht
    INNER JOIN role AS ro ON uht.Role_ID = ro.ID
    INNER JOIN team AS te ON uht.Team_ID = te.ID
    LEFT JOIN user AS us ON te.Owner_ID = us.ID;


-- -- VIEW 'view_assigns_team'
CREATE VIEW view_assigns_team AS

    SELECT

        ass.User_ID as 'user',
        ass.Date as 'date',
        ass.Daytime_ID as 'time',
        ass.Shift_ID as 'shift',
        ass.Note as 'note',
        ass.Team_ID as 'team',
        ass.Creator_ID as 'creator'

    FROM assignment AS ass;


-- -- VIEW 'view_team_users'
CREATE VIEW view_team_users AS

    SELECT

        us.ID as 'id',
        us.Firstname as 'firstname',
        us.Lastname as 'lastname',
        us.Nickname as 'nickname',
        us.Lang as 'language',
        uht.Team_ID as 'team_id',
        uht.Role_ID as 'role_id',
        te.Owner_ID as 'owner_id'

    FROM user_has_team AS uht
    LEFT JOIN team AS te ON te.Owner_ID = uht.User_ID
    INNER JOIN user AS us ON uht.User_ID = us.ID;


-- -- VIEW 'view_assign_notes'
CREATE VIEW view_assign_notes AS

    SELECT

        us.ID as 'user_id',
        ass.Team_ID as 'team_id',
        ass.Date as 'date',
        ass.Note as 'note',
        day.ID as 'time_id',
        day.Title as 'time_title',
        CONCAT(
            us.Firstname, ' ', us.Lastname
        ) as 'user_fullname'

    FROM assignment AS ass
    INNER JOIN daytime AS day ON ass.Daytime_ID = day.ID
    INNER JOIN user AS us ON ass.User_ID = us.ID;

-- -- VIEW 'view_invite_detail'
CREATE VIEW view_invite_detail AS

    SELECT

        inv.ID as 'id',
        inv.Code as 'code',
        inv.Email as 'email',
        inv.Team_ID as 'team',
        CONCAT(
            us.Firstname, ' ', us.Lastname
        ) as 'creator',
        ro.Title as 'role'

    FROM invitation AS inv
    INNER JOIN user AS us ON us.ID = inv.Creator_ID
    INNER JOIN role AS ro ON ro.ID = inv.Role_ID;
