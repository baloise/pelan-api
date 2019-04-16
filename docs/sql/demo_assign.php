<?php

echo 'INSERT INTO `assignment`(`User_ID`, `Daytime_ID`, `Shift_ID`, `Date`,  `Note`, `Creator_ID`) VALUES <br/>';

$first_user = 3;
$last_user = 31;

$shifts = [1,2,3,4,7,8,9];
$daytimes = [1,2,3];

$year = '2020';
$month = '01';
$day_from = 1;
$day_to = 31;

for($current_user=$first_user; $current_user<=$last_user; $current_user++){
    for($current_day=$day_from; $current_day<=$day_to; $current_day++){

        $date = $year.'-'.$month.'-'.$current_day;
        if($current_day<10){
            $date = $year.'-'.$month.'-0'.$current_day;
        }

        foreach ($daytimes as &$time) {

            $shift = $shifts[array_rand($shifts)];
            echo "('".$current_user."', '".$time."', '".$shift."', '".$date."', NULL, '1'), <br/>";

        }

    }
}

?>
