CREATE DATABASE IF NOT EXISTS Pelan;

CREATE TABLE IF NOT EXISTS roles (
    ID INT NOT NULL AUTO_INCREMENT,
    UserID INT NOT NULL,
    Weight DOUBLE,
    MeasureDate DATE NOT NULL,
    CreationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ID),
    FOREIGN KEY (UserID) REFERENCES users(ID)
);

CREATE TABLE IF NOT EXISTS teams (
    ID INT NOT NULL AUTO_INCREMENT,
    Title VARCHAR(45) NOT NULL,
    UNIQUE INDEX `Title_UNIQUE` (`Title`),
    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS users (
    ID INT NOT NULL AUTO_INCREMENT,
	Roles_ID INT NOT NULL,
	Teams_ID INT NOT NULL,
	Identifier VARCHAR(45) NOT NULL,
	Firstname VARCHAR(45) NOT NULL,
	Lastname VARCHAR(45) NOT NULL,
	Email VARCHAR(45) NOT NULL,
    CreationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ChangeDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE INDEX `Email_UNIQUE` (`Email`),
	UNIQUE INDEX `Key_UNIQUE` (`Key`),
    PRIMARY KEY (ID),
	FOREIGN KEY (Roles_ID) REFERENCES roles(ID),
	FOREIGN KEY (Teams_ID) REFERENCES teams(ID)
);

CREATE TABLE IF NOT EXISTS shifts (
    ID INT NOT NULL AUTO_INCREMENT,
    Title VARCHAR(45) NOT NULL,
	Color VARCHAR(45) NOT NULL,
	Teams_ID INT NOT NULL,
	CreationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ChangeDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (ID),
	FOREIGN KEY (Teams_ID) REFERENCES teams(ID)
);

CREATE TABLE IF NOT EXISTS times (
    ID INT NOT NULL AUTO_INCREMENT,
    Title VARCHAR(45) NOT NULL,
	Start DATETIME() NOT NULL,
	End DATETIME() NOT NULL,
	Position INT NOT NULL
	Teams_ID INT NOT NULL,
	CreationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ChangeDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (ID),
	FOREIGN KEY (Teams_ID) REFERENCES teams(ID)
);

CREATE TABLE IF NOT EXISTS assignments (
    ID INT NOT NULL AUTO_INCREMENT,
    Date DATE NOT NULL,
	Notes VARCHAR(45),
	Times_ID INT NOT NULL,
	Shifts_ID INT NOT NULL,
	Users_ID INT NOT NULL,
	CreationDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ChangeDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (ID),
	FOREIGN KEY (Times_ID) REFERENCES times(ID),
	FOREIGN KEY (Shifts_ID) REFERENCES shifts(ID),
	FOREIGN KEY (Users_ID) REFERENCES users(ID)
);
