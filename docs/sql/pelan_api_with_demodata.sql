-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 02. Apr 2019 um 13:41
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

--
-- Daten für Tabelle `daytime`
--

INSERT INTO `daytime` (`ID`, `Title`, `Description`, `Abbreviation`, `Position`, `Active`, `Team_ID`) VALUES
(1, 'Morgens', 'Morning-Hours has Gold in the mouth.', 'Morg', 1, 1, 1),
(2, 'Mittags', 'Mittach.', 'Mitt', 2, 1, 1),
(3, 'Abends', 'After-Hour', 'Aben', 3, 1, 1),
(4, 'Ganztägig', 'Der Verkauf hat nur eine Schicht pro Tag', 'GT', 1, 1, 2);

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

--
-- Daten für Tabelle `role`
--

INSERT INTO `role` (`ID`, `Title`, `Description`, `Admin`, `Team_ID`) VALUES
(1, 'Teamleiter', 'Der Leiter des Helpdesk-Teams', 1, 1),
(2, 'Mitglied', 'Ein Helpdesk-Teammitglied', NULL, 1),
(3, 'Leitung', 'Die Verkauf-Teamleitung', 1, 2),
(4, 'Normal', 'Ein Verkauf-Teammitglied', NULL, 2);

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

--
-- Daten für Tabelle `shift`
--

INSERT INTO `shift` (`ID`, `Title`, `Description`, `Color`, `Active`, `Team_ID`) VALUES
(1, 'Telefon', 'Telefon-Support leisten.', 'A1F600', 1, 1),
(2, 'Vor-Ort', 'Vor-Ort-Support leisten.', 'E7007E', 1, 1),
(3, 'Krank', 'Mitarbeiter fällt aus weil krank.', 'FF9F00', 1, 1),
(4, 'Ferien', 'Mitarbeiter fällt aus weil Ferien.', '0059D3', 1, 1),
(5, 'Anwesend', 'Verkauf-Mitarbeiter ist anwesend', '00E500', 1, 2),
(6, 'Abwesend', 'Verkauf-Mitarbeiter ist abwesend', 'FF0000', 1, 2),
(7, 'Task', 'Muss hier etwas spezifisches erledigen.', 'ffe867', 1, 1),
(8, 'Bez. Absenz', 'Ist abwesend.', '7c671d', 1, 1),
(9, 'HomeOffice', 'Telefon, aber zu Hause.', 'bfbfbf', 1, 1);

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

--
-- Daten für Tabelle `team`
--

INSERT INTO `team` (`ID`, `Title`, `Description`, `Public`, `Owner_ID`) VALUES
(1, 'Helpdesk', 'Das Helpdesk-Team', NULL, 1),
(2, 'Verkauf', 'Das Verkauf-Team', NULL, 2);

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

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`ID`, `Firstname`, `Lastname`, `Nickname`, `Email`, `Auth_Key`, `Lang`, `Last_Login`, `Team_ID`, `Role_ID`) VALUES
(1, 'Patrick', 'Helpdeskadmin', 'HelpPat', 'xx0001@demo.com', '$2y$10$T/P6GS7zKBQKP70m1Cf4zO/jKEKbuS.vP8ZyJw2GCkid9z4dwpsia', 'de', '2019-04-02 11:41:07', 1, 1),
(2, 'Andreas', 'Verkaufadmin', 'VerkAnd', 'yy0001@demo.com', '$2y$10$Kk/LvBk9Y1Ku93ZpX48JIOfZG3fCAFAfjd08A.mg3D0eaLQlSJkP.', 'de', '2019-04-02 11:41:07', 2, 3),
(3, 'Kropf', 'Christian', 'K.Chr2', 'xx0002@demo.com', '$2y$10$OdC995tG0DkxkVA1KfNTG.ykIgQaomf1rWn9nphaYlVTzVSQUbo3S', 'de', '2019-04-02 11:41:07', 1, 2),
(4, 'Schmitt', 'Peters', 'S.Pet3', 'xx0003@demo.com', '$2y$10$0TBN/pxMghIHllNBTw8Z1.X6/fWaT020G7U6kM.Qnp/2wuefEklvK', 'de', '2019-04-02 11:41:07', 1, 2),
(5, 'Berbett', 'Olivier', 'B.Oli4', 'xx0004@demo.com', '$2y$10$rj7G54M14jXcx8jIt1ya.uklU3KvQwn5PfCccq7JcUlOu7pEFbW7W', 'de', '2019-04-02 11:41:07', 1, 2),
(6, 'Berbett', 'Luca', 'B.Luc5', 'xx0005@demo.com', '$2y$10$iHMvusaXcD21cvN8U32.5ecUZluhexMe3sHRMOk3oQ0G3ylEx.UuC', 'de', '2019-04-02 11:41:07', 1, 2),
(7, 'Luca', 'Giganto', 'L.Gig6', 'xx0006@demo.com', '$2y$10$qFx.XUCzQ/XcyvprYuZQuOY/4MefvlgM94rAsk1O0XW22pJXEwfTm', 'de', '2019-04-02 11:41:07', 1, 2),
(8, 'Yusuf', 'Fischer', 'Y.Fis7', 'xx0007@demo.com', '$2y$10$FIDrbyPYnpnDXd.NNPvpW.bq27PcztYjumzsY.BOP2DOb9PTzWykm', 'de', '2019-04-02 11:41:07', 1, 2),
(9, 'Grandjanin', 'Schmitt', 'G.Sch8', 'xx0008@demo.com', '$2y$10$gqeX7xcUfI/u7mNjNnAgo.4Eq8Q9k/aS70gzR1CM6xSHVPjztbmh2', 'de', '2019-04-02 11:41:07', 1, 2),
(10, 'Kropf', 'Koch', 'K.Koc9', 'xx0009@demo.com', '$2y$10$xXPkB.RyW5lXTqoJH1t9F.icArMDC34RzDT02u/HKXEvzCXxNGgGa', 'de', '2019-04-02 11:41:07', 1, 2),
(11, 'Luca', 'Fischer', 'L.Fis10', 'xx0010@demo.com', '$2y$10$pI3/dRgDwPWB3lj8fcgSu.gHE6SA6VNyhNvsJHbCQSjU1NHqR9GBi', 'de', '2019-04-02 11:41:07', 1, 2),
(12, 'Koch', 'Kropf', 'K.Kro11', 'xx0011@demo.com', '$2y$10$eyS9uJbNA7aCIPXSxiiLee0C1gZsDefFS4USz4VudK2R67A2yDMgi', 'de', '2019-04-02 11:41:07', 1, 2),
(13, 'Civale', 'Stammherr', 'C.Sta12', 'xx0012@demo.com', '$2y$10$AwZ4zN6Ef06ZlKS6FPuWd.a6RszbYx.7nkpgQnYLBcEST1KtltJGK', 'de', '2019-04-02 11:41:07', 1, 2),
(14, 'Luca', 'Ba', 'L.Ba13', 'xx0013@demo.com', '$2y$10$YLUQjS.wwBIZ2iraR4MS6OCEXCBN7xlSaZR8KvcKlNcBn7YXith7G', 'de', '2019-04-02 11:41:07', 1, 2),
(15, 'Lucas', 'Peters', 'L.Pet14', 'xx0014@demo.com', '$2y$10$L8kv8mE5fagoTgJWmEw7vu9ytk8i5XrWYCEv5563mV6tHmFUeIXHu', 'de', '2019-04-02 11:41:07', 1, 2),
(16, 'Schmitt', 'Berbett', 'S.Ber15', 'xx0015@demo.com', '$2y$10$NCp2cwRcwzLIawxuRVR1JuVCbWIsqz.NrDCH/W8V46WoAqzSn3Ciu', 'de', '2019-04-02 11:41:07', 1, 2),
(17, 'Olivier', 'Marinelli', 'O.Mar16', 'xx0016@demo.com', '$2y$10$Sn52g1zgZ0GmqwpuPHLSveAhySWar12neFKBwrsd/XEsVjBAZid5O', 'de', '2019-04-02 11:41:07', 1, 2),
(18, 'Berbett', 'Lucas', 'B.Luc17', 'xx0017@demo.com', '$2y$10$ZBwMrmaYSlLM3LDcVZv9V.aJpN9.hYJxH/dxK3yk7pqgAvdSlvkS.', 'de', '2019-04-02 11:41:07', 1, 2),
(19, 'Friedrich', 'Luca', 'F.Luc18', 'xx0018@demo.com', '$2y$10$E5CQWZhsmFxMp7PBgP6pVOVwo/x28ggc/JlgRnY7M64GGBbGuu4vS', 'de', '2019-04-02 11:41:07', 1, 2),
(20, 'Schmitt', 'Patrick', 'S.Pat19', 'xx0019@demo.com', '$2y$10$h9fSALrltnqFiGXiFsPgyOsTOrwv1sgslBXvrXbBloTMUjbApzELq', 'de', '2019-04-02 11:41:07', 1, 2),
(21, 'Peters', 'Christian', 'P.Chr20', 'xx0020@demo.com', '$2y$10$XQCYda7EegLr4HFkQbtpY.uqZd9wkSNZMWk1axzTe8yUBYrl3WzNO', 'de', '2019-04-02 11:41:07', 1, 2),
(22, 'Olivier', 'Christian', 'O.Chr21', 'xx0021@demo.com', '$2y$10$3gdFyB8L4BrXKqp/G706PuupaPVbXijKiyyg60Gy9nIaexGqEM5HC', 'de', '2019-04-02 11:41:07', 1, 2),
(23, 'Fischer', 'Luca', 'F.Luc22', 'xx0022@demo.com', '$2y$10$r.1KjVBxrlZCRyDzvBl/c.j0Yut3mV3vIP30LF9GCxwPlpuQWjgQ.', 'de', '2019-04-02 11:41:07', 1, 2),
(24, 'Koch', 'Koch', 'K.Koc23', 'xx0023@demo.com', '$2y$10$DDq938KUWMecZa3BHcTZQOLUNCgBXpJsGE0REb5FHYQ14jGkFeJjO', 'de', '2019-04-02 11:41:07', 1, 2),
(25, 'Patrick', 'Kropf', 'P.Kro24', 'xx0024@demo.com', '$2y$10$VUhX5XpsX.Mjx4NbnOyAweumcDfMRX7yT09NVnpB8hLOpRzDW7gt2', 'de', '2019-04-02 11:41:07', 1, 2),
(26, 'Lucas', 'Stammherr', 'L.Sta25', 'xx0025@demo.com', '$2y$10$cOHp2p2jpPpzbAIhQMyuu.Kdm1rpvNQ09kGmxAVWB2cksrhxXFbEa', 'de', '2019-04-02 11:41:07', 1, 2),
(27, 'Ba', 'Luca', 'B.Luc26', 'xx0026@demo.com', '$2y$10$PUSuOVXTKKXwjlJnxJYtEOdwfhnsXP5EFluvMaCWCfwg8SapRDAyO', 'de', '2019-04-02 11:41:07', 1, 2),
(28, 'Ba', 'Bianca', 'B.Bia27', 'xx0027@demo.com', '$2y$10$vD4D.x8ehgX2Gw6nwTCaquTojRh.QpZ9PCyNxE7owrDqpaNK.Fmmi', 'de', '2019-04-02 11:41:07', 1, 2),
(29, 'Kropf', 'Yusuf', 'K.Yus28', 'xx0028@demo.com', '$2y$10$6Z/QdQVAzwNwpjKfOF/bZutrJYFCwiXZM2FbortBbC8HPuppLza1u', 'de', '2019-04-02 11:41:07', 1, 2),
(30, 'Koch', 'Kropf', 'K.Kro29', 'xx0029@demo.com', '$2y$10$CqlHf0um.Qa.SCRk1HYqv.B8ao.icQpeOEYXjBCFFr.ywlGXhnydS', 'de', '2019-04-02 11:41:07', 1, 2),
(31, 'Civale', 'Bianca', 'C.Bia30', 'xx0030@demo.com', '$2y$10$iSOg30EbzYkcajmwt8d0MO/HVbuHo7bKlbD1gq177TF0QZpHqndwi', 'de', '2019-04-02 11:41:07', 1, 2);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `invitation`
--
ALTER TABLE `invitation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `role`
--
ALTER TABLE `role`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `shift`
--
ALTER TABLE `shift`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `team`
--
ALTER TABLE `team`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
