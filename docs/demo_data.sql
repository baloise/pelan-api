INSERT INTO `teams` (`ID`, `InsertDate`, `UpdateDate`, `Name`, `Abbreviation`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Helpdesk', 'HD');

INSERT INTO `roles` (`ID`, `InsertDate`, `UpdateDate`, `Name`, `Abbreviation`, `Admin`, `Teams_ID`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Mitglied', 'M', '0', '1'),
(2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Teamleiter', 'TM', '1', '1');

INSERT INTO `users` (`ID`, `InsertDate`, `UpdateDate`, `Firstname`, `Lastname`, `Language`, `Identifier`, `Nickname`, `Email`, `Roles_ID`, `Teams_ID`) VALUES
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Ernst', 'Fall', 'de', 'xx005', 'E.Fall', 'ernst.fall@demo.com', '2', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Ernst', 'Haft', 'en', 'xx006', 'E.Haft', 'ernst.haft@demo.com', '2', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Donald', 'Duck', 'de', 'xx001', 'D.Duck', 'donald.duck@demo.com', '1', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Filet', 'Minyon', 'en', 'xx002', 'F.Miny', 'filet.minyon@demo.com', '1', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Alexander', 'Platz', 'de', 'xx003', 'Alex', 'alexander.platz@demo.com', '1', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Bill', 'Yard', 'de', 'xx004', 'Bill', 'bill.yard@demo.com', '1', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Gerd', 'Nehr', 'de', 'xx007', 'Gerd', 'gerd.nehr@demo.com', '1', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Johannes', 'Kraut', 'de', 'xx008', 'Jok', 'johannes.kraut@demo.com', '1', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Klaus', 'thaler', 'de', 'xx009', 'Klaus', 'klaus.thaler@demo.com', '1', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Peter', 'Silie', 'de', 'xx010', 'Peter', 'peter.silie@demo.com', '1', '1');

INSERT INTO `shifts` (`ID`, `InsertDate`, `UpdateDate`, `Title`, `Abbreviation`, `Color`, `Description`, `Teams_ID`) VALUES 
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Telefon', 'Tele', '#42f465', 'Anrufe entgegennehmen', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Vor-Ort', 'IBS', '#4150f4', 'Vor-Ort Tickets bearbeiten', '1');
