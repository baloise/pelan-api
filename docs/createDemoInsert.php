<?php

    $fullArray = array();
    $namesPool = array(
        'Lucas', 'Berbett', 'Lionel', 'Kropf', 'Bianca', 'Civale', 'Olivier',
        'Fischer', 'Patrick', 'Schmitt', 'Friedrich', 'Yusuf', 'Ba', 'Grandjanin',
        'Stammherr', 'Giganto', 'Koch', 'Marinelli', 'Peters', 'Christian', 'Luca');

    $fixUsers = array(
        array("id"=>1, "firstname"=>"Patrick", "lastname"=>"Schmitt", "admin"=>true)
    );

    $keyTmp = "xx00";
    $mailTmp = "@demo.com";
    $teamID = 1;
    $adminRoleID = 2;
    $defaultRoleID = 1;
    $userAmount = 30;

    $tmpUserID = 0;
    for($i=0;$i<count($fixUsers);$i++){

        $tmpUserID = $fixUsers[$i]["id"];

        $tmpKey = $keyTmp;
        $tmpNick = substr($fixUsers[$i]["firstname"], 0, 1).".".substr($fixUsers[$i]["lastname"], 0, 3).$tmpUserID;
        $tmpRole = $defaultRoleID;

        if($fixUsers[$i]["admin"]){
            $tmpRole = $adminRoleID;
        }

        if($tmpUserID < 10){
            $tmpKey.= "0".$tmpUserID;
        } else {
            $tmpKey.= $tmpUserID;
        }

        $hashedKey = password_hash($tmpKey, PASSWORD_DEFAULT);
        $tmpUser = array(
            "id" => $tmpUserID,
            "fName" => $fixUsers[$i]["firstname"],
            "lName" => $fixUsers[$i]["lastname"],
            "lang" => "de",
            "key" => $tmpKey,
            "hash" => $hashedKey,
            "nick" => $tmpNick,
            "email" => $tmpKey.$mailTmp,
            "role" => "2",
            "team" => "1"
        );

        array_push($fullArray, $tmpUser);

    }

    while($tmpUserID<$userAmount){

        $tmpUserID++;

        $tmpKey = $keyTmp;
        $tmpFName = $namesPool[rand ( 0 , count($namesPool) -1)];
        $tmpLName = $namesPool[rand ( 0 , count($namesPool) -1)];

        $tmpNick = substr($tmpFName, 0, 1).".".substr($tmpLName, 0, 3).$tmpUserID;
        $tmpRole = $defaultRoleID;

        if($tmpUserID < 10){
            $tmpKey.= "0".$tmpUserID;
        } else {
            $tmpKey.= $tmpUserID;
        }

        $hashedKey = password_hash($tmpKey, PASSWORD_DEFAULT);
        $tmpUser = array(
            "id" => $tmpUserID,
            "fName" => $tmpFName,
            "lName" => $tmpLName,
            "lang" => "de",
            "key" => $tmpKey,
            "hash" => $hashedKey,
            "nick" => $tmpNick,
            "email" => $tmpKey.$mailTmp,
            "role" => "2",
            "team" => "1"
        );

        array_push($fullArray, $tmpUser);

    }

    //    INSERT INTO `user` (`ID`, `Firstname`, `Lastname`, `Nickname`, `Email`, `Lang`, `Team_ID`, `Role_ID`, `Auth_Key`)
    echo "INSERT INTO `user` (`ID`, `Firstname`, `Lastname`, `Nickname`, `Email`, `Lang`, `Team_ID`, `Role_ID`, `Auth_Key`) VALUES <br />";

    $fa = $fullArray;

    for($i=0;$i<count($fa);$i++){

        $tmpZ = ",";
        if($i === count($fa)-1){
            $tmpZ = ";";
        }

        echo "(
            ".($fa[$i]['id']+1).",
            '".$fa[$i]['fName']."',
            '".$fa[$i]['lName']."',
            '".$fa[$i]['nick']."',
            '".$fa[$i]['email']."',
            '".$fa[$i]['lang']."',
            ".$fa[$i]['team'].",
            ".$fa[$i]['role'].",
            '".$fa[$i]['hash']."'
        )".$tmpZ."<br />";

    }

    //print_r($fullArray);




?>
