-- -------------- DEMODATA FOR 'pelan_api'

-- ---- TABLE 'user' ADMINS
INSERT INTO `user` (`ID`, `Firstname`, `Lastname`, `Nickname`, `Email`, `Lang`, `Auth_Key`) VALUES
(1, 'Patrick', 'Helpdeskadmin', 'HelpPat', 'xx0001@demo.com', 'de', '$2y$10$T/P6GS7zKBQKP70m1Cf4zO/jKEKbuS.vP8ZyJw2GCkid9z4dwpsia'),
(2, 'Andreas', 'Verkaufadmin', 'VerkAnd', 'yy0001@demo.com', 'de', '$2y$10$Kk/LvBk9Y1Ku93ZpX48JIOfZG3fCAFAfjd08A.mg3D0eaLQlSJkP.');

-- ---- TABLE 'team'
INSERT INTO `team` (`ID`, `Title`, `Description`, `Public`, `Owner_ID`) VALUES
(1, 'Helpdesk', 'Das Helpdesk-Team', NULL, '1'),
(2, 'Verkauf', 'Das Verkauf-Team', NULL, '2');

-- ---- TABLE 'role'
INSERT INTO `role` (`ID`, `Title`, `Description`, `Admin`, `Team_ID`) VALUES
(1, 'Teamleiter', 'Der Leiter des Helpdesk-Teams', '1', '1'),
(2, 'Mitglied', 'Ein Helpdesk-Teammitglied', NULL, '1'),
(3, 'Leitung', 'Die Verkauf-Teamleitung', '1', '2'),
(4, 'Normal', 'Ein Verkauf-Teammitglied', NULL, '2');

-- -- TABLE 'user' ADJUST
UPDATE `user` SET `Team_ID` = '1' WHERE `user`.`ID` = 1;
UPDATE `user` SET `Role_ID` = '1' WHERE `user`.`ID` = 1;
UPDATE `user` SET `Team_ID` = '2' WHERE `user`.`ID` = 2;
UPDATE `user` SET `Role_ID` = '3' WHERE `user`.`ID` = 2;

-- -- TABLE 'daytime'
INSERT INTO `daytime` (`ID`, `Title`, `Description`, `Abbreviation`, `Position`, `Active`, `Team_ID`) VALUES
(1, 'Superfrüh', 'Morning-Hours has Gold in the mouth.', 'Super', '1', '1', '1'),
(2, 'Früh', 'Mittach.', 'Früh', '2', '1', '1'),
(3, 'Spät', 'After-Hour', 'Spät', '3', '1', '1'),
(4, 'Ganztägig', 'Der Verkauf hat nur eine Schicht pro Tag', 'GT', '1', '1', '2');

-- -- TABLE 'shift'
INSERT INTO `shift` (`ID`, `Title`, `Description`, `Color`, `Active`, `Team_ID`) VALUES
(1, 'Telefon', 'Telefon-Support leisten.', '3366FF', '1', '1'),
(2, 'IBS', 'Vor-Ort-Support leisten.', 'FF00FF', '1', '1'),
(3, 'Krank', 'Mitarbeiter fällt aus weil krank.', 'FF0000', '1', '1'),
(4, 'Ferien', 'Mitarbeiter fällt aus weil Ferien.', 'FF6600', '1', '1'),
(5, 'Anwesend', 'Verkauf-Mitarbeiter ist anwesend', '00E500', '1', '2'),
(6, 'Abwesend', 'Verkauf-Mitarbeiter ist abwesend', 'FF0000', '1', '2'),
(7, 'Task', 'Muss hier etwas spezifisches erledigen.', 'FFFF00', '1', '1'),
(8, 'Bez. Absenz', 'Ist abwesend.', '808000', '1', '1'),
(9, 'HomeOffice', 'Telefon, aber zu Hause.', 'D3D3D3', '1', '1');

-- ---- TABLE 'user' Team: Helpdesk
INSERT INTO `user` (`ID`, `Firstname`, `Lastname`, `Nickname`, `Email`, `Lang`, `Team_ID`, `Role_ID`, `Auth_Key`) VALUES
( 3, 'Kropf', 'Christian', 'K.Chr2', 'xx0002@demo.com', 'de', 1, 2, '$2y$10$OdC995tG0DkxkVA1KfNTG.ykIgQaomf1rWn9nphaYlVTzVSQUbo3S' ),
( 4, 'Schmitt', 'Peters', 'S.Pet3', 'xx0003@demo.com', 'de', 1, 2, '$2y$10$0TBN/pxMghIHllNBTw8Z1.X6/fWaT020G7U6kM.Qnp/2wuefEklvK' ),
( 5, 'Berbett', 'Olivier', 'B.Oli4', 'xx0004@demo.com', 'de', 1, 2, '$2y$10$rj7G54M14jXcx8jIt1ya.uklU3KvQwn5PfCccq7JcUlOu7pEFbW7W' ),
( 6, 'Berbett', 'Luca', 'B.Luc5', 'xx0005@demo.com', 'de', 1, 2, '$2y$10$iHMvusaXcD21cvN8U32.5ecUZluhexMe3sHRMOk3oQ0G3ylEx.UuC' ),
( 7, 'Luca', 'Giganto', 'L.Gig6', 'xx0006@demo.com', 'de', 1, 2, '$2y$10$qFx.XUCzQ/XcyvprYuZQuOY/4MefvlgM94rAsk1O0XW22pJXEwfTm' ),
( 8, 'Yusuf', 'Fischer', 'Y.Fis7', 'xx0007@demo.com', 'de', 1, 2, '$2y$10$FIDrbyPYnpnDXd.NNPvpW.bq27PcztYjumzsY.BOP2DOb9PTzWykm' ),
( 9, 'Grandjanin', 'Schmitt', 'G.Sch8', 'xx0008@demo.com', 'de', 1, 2, '$2y$10$gqeX7xcUfI/u7mNjNnAgo.4Eq8Q9k/aS70gzR1CM6xSHVPjztbmh2' ),
( 10, 'Kropf', 'Koch', 'K.Koc9', 'xx0009@demo.com', 'de', 1, 2, '$2y$10$xXPkB.RyW5lXTqoJH1t9F.icArMDC34RzDT02u/HKXEvzCXxNGgGa' ),
( 11, 'Luca', 'Fischer', 'L.Fis10', 'xx0010@demo.com', 'de', 1, 2, '$2y$10$pI3/dRgDwPWB3lj8fcgSu.gHE6SA6VNyhNvsJHbCQSjU1NHqR9GBi' ),
( 12, 'Koch', 'Kropf', 'K.Kro11', 'xx0011@demo.com', 'de', 1, 2, '$2y$10$eyS9uJbNA7aCIPXSxiiLee0C1gZsDefFS4USz4VudK2R67A2yDMgi' ),
( 13, 'Civale', 'Stammherr', 'C.Sta12', 'xx0012@demo.com', 'de', 1, 2, '$2y$10$AwZ4zN6Ef06ZlKS6FPuWd.a6RszbYx.7nkpgQnYLBcEST1KtltJGK' ),
( 14, 'Luca', 'Ba', 'L.Ba13', 'xx0013@demo.com', 'de', 1, 2, '$2y$10$YLUQjS.wwBIZ2iraR4MS6OCEXCBN7xlSaZR8KvcKlNcBn7YXith7G' ),
( 15, 'Lucas', 'Peters', 'L.Pet14', 'xx0014@demo.com', 'de', 1, 2, '$2y$10$L8kv8mE5fagoTgJWmEw7vu9ytk8i5XrWYCEv5563mV6tHmFUeIXHu' ),
( 16, 'Schmitt', 'Berbett', 'S.Ber15', 'xx0015@demo.com', 'de', 1, 2, '$2y$10$NCp2cwRcwzLIawxuRVR1JuVCbWIsqz.NrDCH/W8V46WoAqzSn3Ciu' ),
( 17, 'Olivier', 'Marinelli', 'O.Mar16', 'xx0016@demo.com', 'de', 1, 2, '$2y$10$Sn52g1zgZ0GmqwpuPHLSveAhySWar12neFKBwrsd/XEsVjBAZid5O' ),
( 18, 'Berbett', 'Lucas', 'B.Luc17', 'xx0017@demo.com', 'de', 1, 2, '$2y$10$ZBwMrmaYSlLM3LDcVZv9V.aJpN9.hYJxH/dxK3yk7pqgAvdSlvkS.' ),
( 19, 'Friedrich', 'Luca', 'F.Luc18', 'xx0018@demo.com', 'de', 1, 2, '$2y$10$E5CQWZhsmFxMp7PBgP6pVOVwo/x28ggc/JlgRnY7M64GGBbGuu4vS' ),
( 20, 'Schmitt', 'Patrick', 'S.Pat19', 'xx0019@demo.com', 'de', 1, 2, '$2y$10$h9fSALrltnqFiGXiFsPgyOsTOrwv1sgslBXvrXbBloTMUjbApzELq' ),
( 21, 'Peters', 'Christian', 'P.Chr20', 'xx0020@demo.com', 'de', 1, 2, '$2y$10$XQCYda7EegLr4HFkQbtpY.uqZd9wkSNZMWk1axzTe8yUBYrl3WzNO' ),
( 22, 'Olivier', 'Christian', 'O.Chr21', 'xx0021@demo.com', 'de', 1, 2, '$2y$10$3gdFyB8L4BrXKqp/G706PuupaPVbXijKiyyg60Gy9nIaexGqEM5HC' ),
( 23, 'Fischer', 'Luca', 'F.Luc22', 'xx0022@demo.com', 'de', 1, 2, '$2y$10$r.1KjVBxrlZCRyDzvBl/c.j0Yut3mV3vIP30LF9GCxwPlpuQWjgQ.' ),
( 24, 'Koch', 'Koch', 'K.Koc23', 'xx0023@demo.com', 'de', 1, 2, '$2y$10$DDq938KUWMecZa3BHcTZQOLUNCgBXpJsGE0REb5FHYQ14jGkFeJjO' ),
( 25, 'Patrick', 'Kropf', 'P.Kro24', 'xx0024@demo.com', 'de', 1, 2, '$2y$10$VUhX5XpsX.Mjx4NbnOyAweumcDfMRX7yT09NVnpB8hLOpRzDW7gt2' ),
( 26, 'Lucas', 'Stammherr', 'L.Sta25', 'xx0025@demo.com', 'de', 1, 2, '$2y$10$cOHp2p2jpPpzbAIhQMyuu.Kdm1rpvNQ09kGmxAVWB2cksrhxXFbEa' ),
( 27, 'Ba', 'Luca', 'B.Luc26', 'xx0026@demo.com', 'de', 1, 2, '$2y$10$PUSuOVXTKKXwjlJnxJYtEOdwfhnsXP5EFluvMaCWCfwg8SapRDAyO' ),
( 28, 'Ba', 'Bianca', 'B.Bia27', 'xx0027@demo.com', 'de', 1, 2, '$2y$10$vD4D.x8ehgX2Gw6nwTCaquTojRh.QpZ9PCyNxE7owrDqpaNK.Fmmi' ),
( 29, 'Kropf', 'Yusuf', 'K.Yus28', 'xx0028@demo.com', 'de', 1, 2, '$2y$10$6Z/QdQVAzwNwpjKfOF/bZutrJYFCwiXZM2FbortBbC8HPuppLza1u' ),
( 30, 'Koch', 'Kropf', 'K.Kro29', 'xx0029@demo.com', 'de', 1, 2, '$2y$10$CqlHf0um.Qa.SCRk1HYqv.B8ao.icQpeOEYXjBCFFr.ywlGXhnydS' ),
( 31, 'Civale', 'Bianca', 'C.Bia30', 'xx0030@demo.com', 'de', 1, 2, '$2y$10$iSOg30EbzYkcajmwt8d0MO/HVbuHo7bKlbD1gq177TF0QZpHqndwi' );
