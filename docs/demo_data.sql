INSERT INTO `teams` (`ID`, `Title`, `Abbreviation`) VALUES
(1, 'Helpdesk', 'HD');

INSERT INTO `roles` (`ID`, `Title`, `Abbreviation`, `Admin`, `Teams_ID`) VALUES
(1, 'Mitglied', 'M', '0', 1),
(2, 'Teamleiter', 'TL', '1', 1);

INSERT INTO `shifts` (`ID`, `Title`, `Abbreviation`, `Color`, `Description`, `Teams_ID`) VALUES
(1, 'Standart', 'Def', '#534afe', 'Anrufe entgegennehmen', 1),
(2, 'IBS', 'IBS', '#de782f', 'Vor-Ort Support', 1),
(3, 'Task', 'Task', '#f1ef28', 'Hat spezielle Aufgabe', 1),
(4, 'Ferien', 'Free', '#ff9d9d', 'Nicht anwesend', 1),
(5, 'Krank', 'Sick', '#ff3939', 'Nicht anwesend', 1),
(6, 'Home Office', 'HoOf', '#b9b9b9', 'Nur Telefon', 1);

INSERT INTO `times` (`ID`, `Description`, `Title`, `Abbreviation`, `Position`, `Teams_ID`) VALUES
(1, '', 'SuperfrÃ¼h', NULL, 1, 1),
(2, '', 'FrÃ¼h', NULL, 2, 1),
(3, '', 'SpÃ¤t', NULL, 2, 1);

-- For Demousers see "createDemoInsert.php"
