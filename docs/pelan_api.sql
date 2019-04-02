-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 02. Apr 2019 um 13:40
-- Server-Version: 10.1.38-MariaDB
-- PHP-Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `pelan_api`
--
CREATE DATABASE IF NOT EXISTS `pelan_api` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pelan_api`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `assignment`
--

CREATE TABLE `assignment` (
  `ID` int(11) NOT NULL,
  `Daytime_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Creator_ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Shift_ID` int(11) DEFAULT NULL,
  `Note` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `daytime`
--

CREATE TABLE `daytime` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Abbreviation` varchar(5) NOT NULL,
  `Position` int(11) DEFAULT NULL,
  `Active` tinyint(1) DEFAULT NULL,
  `Team_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `invitation`
--

CREATE TABLE `invitation` (
  `ID` int(11) NOT NULL,
  `Creator_ID` int(11) NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Code` varchar(255) NOT NULL,
  `Email` int(11) NOT NULL,
  `Team_ID` int(11) NOT NULL,
  `Role_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role`
--

CREATE TABLE `role` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Admin` tinyint(1) DEFAULT NULL,
  `Team_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shift`
--

CREATE TABLE `shift` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Color` varchar(6) NOT NULL,
  `Active` tinyint(1) DEFAULT NULL,
  `Team_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `team`
--

CREATE TABLE `team` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` mediumtext NOT NULL,
  `Public` tinyint(1) DEFAULT NULL,
  `Owner_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `Firstname` varchar(255) NOT NULL,
  `Lastname` varchar(255) NOT NULL,
  `Nickname` varchar(10) NOT NULL,
  `Email` varchar(89) NOT NULL,
  `Auth_Key` varchar(255) NOT NULL,
  `Lang` enum('de','en') NOT NULL,
  `Last_Login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Team_ID` int(11) DEFAULT NULL,
  `Role_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_is_dev`
--

CREATE TABLE `user_is_dev` (
  `User_ID` int(11) NOT NULL,
  `Developer` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `view_teamassigns`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `view_teamassigns` (
`id` int(11)
,`date` date
,`note` mediumtext
,`time` int(11)
,`shift` int(11)
,`user` int(11)
,`user_team` int(11)
);

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `view_usertoken`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `view_usertoken` (
`ID` int(11)
,`Firstname` varchar(255)
,`Lastname` varchar(255)
,`Language` enum('de','en')
,`Nickname` varchar(10)
,`Email` varchar(89)
,`Role_ID` int(11)
,`Role_Title` varchar(255)
,`Role_Description` mediumtext
,`Role_Admin` tinyint(1)
,`Team_ID` int(11)
,`Team_Title` varchar(255)
);

-- --------------------------------------------------------

--
-- Struktur des Views `view_teamassigns`
--
DROP TABLE IF EXISTS `view_teamassigns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_teamassigns`  AS  select `ass`.`ID` AS `id`,`ass`.`Date` AS `date`,`ass`.`Note` AS `note`,`ass`.`Daytime_ID` AS `time`,`ass`.`Shift_ID` AS `shift`,`ass`.`User_ID` AS `user`,`usr`.`Team_ID` AS `user_team` from (`assignment` `ass` join `user` `usr` on((`usr`.`ID` = `ass`.`User_ID`))) ;

-- --------------------------------------------------------

--
-- Struktur des Views `view_usertoken`
--
DROP TABLE IF EXISTS `view_usertoken`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_usertoken`  AS  select `us`.`ID` AS `ID`,`us`.`Firstname` AS `Firstname`,`us`.`Lastname` AS `Lastname`,`us`.`Lang` AS `Language`,`us`.`Nickname` AS `Nickname`,`us`.`Email` AS `Email`,`ro`.`ID` AS `Role_ID`,`ro`.`Title` AS `Role_Title`,`ro`.`Description` AS `Role_Description`,`ro`.`Admin` AS `Role_Admin`,`te`.`ID` AS `Team_ID`,`te`.`Title` AS `Team_Title` from ((`user` `us` join `role` `ro` on((`us`.`Role_ID` = `ro`.`ID`))) join `team` `te` on((`us`.`Team_ID` = `te`.`ID`))) ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE_Date_per_User_per_Daytime` (`Date`,`User_ID`,`Daytime_ID`),
  ADD KEY `Daytime_ID` (`Daytime_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Creator_ID` (`Creator_ID`),
  ADD KEY `Shift_ID` (`Shift_ID`);

--
-- Indizes für die Tabelle `daytime`
--
ALTER TABLE `daytime`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Team_ID` (`Team_ID`);

--
-- Indizes für die Tabelle `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE_Code` (`Code`),
  ADD KEY `Creator_ID` (`Creator_ID`),
  ADD KEY `Team_ID` (`Team_ID`),
  ADD KEY `Role_ID` (`Role_ID`);

--
-- Indizes für die Tabelle `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Team_ID` (`Team_ID`);

--
-- Indizes für die Tabelle `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Team_ID` (`Team_ID`);

--
-- Indizes für die Tabelle `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE_Title` (`Title`),
  ADD KEY `Owner_ID` (`Owner_ID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNIQUE_Email` (`Email`),
  ADD KEY `Team_ID` (`Team_ID`),
  ADD KEY `Role_ID` (`Role_ID`);

--
-- Indizes für die Tabelle `user_is_dev`
--
ALTER TABLE `user_is_dev`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `assignment`
--
ALTER TABLE `assignment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `daytime`
--
ALTER TABLE `daytime`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `invitation`
--
ALTER TABLE `invitation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `shift`
--
ALTER TABLE `shift`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `team`
--
ALTER TABLE `team`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_1` FOREIGN KEY (`Daytime_ID`) REFERENCES `daytime` (`ID`),
  ADD CONSTRAINT `assignment_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `assignment_ibfk_3` FOREIGN KEY (`Creator_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `assignment_ibfk_4` FOREIGN KEY (`Shift_ID`) REFERENCES `shift` (`ID`);

--
-- Constraints der Tabelle `daytime`
--
ALTER TABLE `daytime`
  ADD CONSTRAINT `daytime_ibfk_1` FOREIGN KEY (`Team_ID`) REFERENCES `team` (`ID`);

--
-- Constraints der Tabelle `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `invitation_ibfk_1` FOREIGN KEY (`Creator_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `invitation_ibfk_2` FOREIGN KEY (`Team_ID`) REFERENCES `team` (`ID`),
  ADD CONSTRAINT `invitation_ibfk_3` FOREIGN KEY (`Role_ID`) REFERENCES `role` (`ID`);

--
-- Constraints der Tabelle `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_ibfk_1` FOREIGN KEY (`Team_ID`) REFERENCES `team` (`ID`);

--
-- Constraints der Tabelle `shift`
--
ALTER TABLE `shift`
  ADD CONSTRAINT `shift_ibfk_1` FOREIGN KEY (`Team_ID`) REFERENCES `team` (`ID`);

--
-- Constraints der Tabelle `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`Owner_ID`) REFERENCES `user` (`ID`);

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`Team_ID`) REFERENCES `team` (`ID`),
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`Role_ID`) REFERENCES `role` (`ID`);

--
-- Constraints der Tabelle `user_is_dev`
--
ALTER TABLE `user_is_dev`
  ADD CONSTRAINT `user_is_dev_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
