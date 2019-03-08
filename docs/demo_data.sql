INSERT INTO `teams` (`ID`, `InsertDate`, `UpdateDate`, `Title`, `Abbreviation`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Helpdesk', 'HD');

INSERT INTO `roles` (`ID`, `InsertDate`, `UpdateDate`, `Title`, `Abbreviation`, `Admin`, `Teams_ID`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Mitglied', 'M', '0', '1'),
(2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Teamleiter', 'TM', '1', '1');

INSERT INTO `users` (`ID`, `InsertDate`, `UpdateDate`, `Firstname`, `Lastname`, `Language`, `Identifier`, `Nickname`, `Email`, `Roles_ID`, `Teams_ID`) VALUES
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Ernst', 'Fall', 'de', '$2y$10$upVWkPNDMG62y56uHaFSE.M0tx6VpGhzhEur.adGpHt1KYmXZOmfK', 'E.Fall', 'ernst.fall@demo.com', '2', '1'), -- xx005
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Ernst', 'Haft', 'en', '$2y$10$ERZLaIIgaHXhr4F0OsJjye8geSXqkNTAs0UnHDDz18DpsjNcTlF52', 'E.Haft', 'ernst.haft@demo.com', '2', '1'), -- xx006
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Donald', 'Duck', 'de', '$2y$10$QhCsY8d0MrYjzQrUOG/ISuuCq9dNQ4E7YkgGpcY.Dt0S7rxYSzNIe', 'D.Duck', 'donald.duck@demo.com', '1', '1'), -- xx001
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Filet', 'Minyon', 'en', '$2y$10$V9gkxOO07JXVcUQPT78Oquv/SaIwU6WzvQ.ZjfnLMtyTcle6ebkJ.', 'F.Miny', 'filet.minyon@demo.com', '1', '1'), -- xx002
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Alexander', 'Platz', 'de', '$2y$10$MrB7eiP/JZtqfKvEXF0LluGHhCdYm2j00ky0ahIDbtgVZTociLnDy', 'Alex', 'alexander.platz@demo.com', '1', '1'), -- xx003
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Bill', 'Yard', 'de', '$2y$10$ihVaFvHirlE94hOaWJA0LeEdjej94i8jQAki7z7tjHDHnhh66xscu', 'Bill', 'bill.yard@demo.com', '1', '1'), -- xx004
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Gerd', 'Nehr', 'de', '$2y$10$CsmTm5HDtDfLxA5xMl89gu5EL.6kXKVDhmGvwnS/Tb2rKJHaottEu', 'Gerd', 'gerd.nehr@demo.com', '1', '1'), -- xx007
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Johannes', 'Kraut', 'de', '$2y$10$tz3kWKI0ajIL1a4.h6hAEuohy1emCtk/rj6qfoYoDn/hvcsFHPvZC', 'Jok', 'johannes.kraut@demo.com', '1', '1'), -- xx008
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Klaus', 'thaler', 'de', '$2y$10$5daNDQn.9/axfx0AmWX9yuQOxegFhVy0NFo4vxjlvW4jGHRzgnqzK', 'Klaus', 'klaus.thaler@demo.com', '1', '1'), -- xx009
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Peter', 'Silie', 'de', '$2y$10$UlBo14YZ4tt1adLdVE0EcuIxZAm.selNEmxektR/YRMyGqSS2Pd1.', 'Peter', 'peter.silie@demo.com', '1', '1'); -- xx010

INSERT INTO `shifts` (`ID`, `InsertDate`, `UpdateDate`, `Title`, `Abbreviation`, `Color`, `Description`, `Teams_ID`) VALUES
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Telefon', 'Tele', '#42f465', 'Anrufe entgegennehmen', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Telefon FrÃ¼h', 'TeFu', '#01b023', 'Anrufe frÃ¼h entgegennehmen', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Vor-Ort FrÃ¼h', 'IBFu', '#4150f4', 'Vor-Ort Tickets frÃ¼h bearbeiten', '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Vor-Ort', 'IBS', '#a8b0ff', 'Vor-Ort Tickets bearbeiten', '1');

INSERT INTO `times` (`ID`, `InsertDate`, `UpdateDate`, `Description`, `Title`, `Abbreviation`, `Position`, `Teams_ID`) VALUES
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Vormittags', 'Morgen', 'Mo', 1, '1'),
(NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Nachmittags', 'Abend', 'Ab', 2, '1');
