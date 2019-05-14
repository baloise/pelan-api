<?php

echo 'INSERT INTO `assignment`(`User_ID`, `Date`, `Daytime_ID`, `Shift_ID`, `Note`, `Team_ID`, `Creator_ID`) VALUES <br/>';

$creator_id = 2;
$team_id = 1;
$first_user = 2;
$last_user = 31;

$shifts = ['1', '2', '3', '4', '5', '6', '7', 'NULL', 'NULL'];
$notes = ["'Dies ist ein Kommentar.'", 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL'];
$daytimes = [1, 2, 3];

$year = '2019';
$month = '05';
$day_from = 1;
$day_to = 31;

for ($current_user = $first_user; $current_user <= $last_user; $current_user++) {
    for ($current_day = $day_from; $current_day <= $day_to; $current_day++) {

        $date = $year . '-' . $month . '-' . $current_day;
        if ($current_day < 10) {
            $date = $year . '-' . $month . '-0' . $current_day;
        }

        foreach ($daytimes as &$time) {
            $shift = $shifts[array_rand($shifts)];
            $note = $notes[array_rand($notes)];
            echo "
            ('".$current_user . "', '" . $date . "', '" . $time . "', " . $shift . ", " . $note . ", '" . $team_id . "', '" . $creator_id . "'), <br/>
            ";
        }
    }
}
