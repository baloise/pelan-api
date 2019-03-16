<?php

    $fullArray = array();
    $namesPool = array(
        'Lucas', 'Berbett', 'Lionel', 'Kropf', 'Bianca', 'Civale', 'Olivier',
        'Fischer', 'Patrick', 'Schmitt', 'Friedrich', 'Yusuf', 'Ba', 'Grandjanin',
        'Stammherr', 'Giganto', 'Koch', 'Marinelli', 'Peters', 'Christian', 'Luca');

    $fixUsers = array(
        array("id"=>1, "firstname"=>"Patrick", "lastname"=>"Schmitt", "admin"=>true),
        array("id"=>2, "firstname"=>"Patrick", "lastname"=>"Friedrich", "admin"=>true),
        array("id"=>3, "firstname"=>"Elia", "lastname"=>"Reutlinger", "admin"=>false),
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
            "role" => $tmpRole,
            "team" => $teamID
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

        if($tmpUserID > 10){
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
            "role" => $tmpRole,
            "team" => $teamID
        );

        array_push($fullArray, $tmpUser);

    }

    echo "INSERT INTO `users` (`ID`, `Firstname`, `Lastname`, `Language`, `Identifier`, `Nickname`, `Email`, `Roles_ID`, `Teams_ID`) VALUES <br />";

    $fa = $fullArray;

    for($i=0;$i<count($fa);$i++){

        $tmpZ = ",";
        if($i === count($fa)-1){
            $tmpZ = ";";
        }

        echo "(
            ".$fa[$i]['id'].",
            '".$fa[$i]['fName']."',
            '".$fa[$i]['lName']."',
            '".$fa[$i]['lang']."',
            '".$fa[$i]['hash']."',
            '".$fa[$i]['nick']."',
            '".$fa[$i]['email']."',
            ".$fa[$i]['role'].",
            ".$fa[$i]['team']."
        )".$tmpZ." -- ".$fa[$i]['key']."<br />";

    }

    //print_r($fullArray);




?>
