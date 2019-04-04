
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