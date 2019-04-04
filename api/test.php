<?php

include_once '_config/core.php';
include_once '_config/validate.php';


$value = -20;

try {

    $value = val_number($value, -20, 10);
    echo $value;

} catch(Exception $e){
    echo $e;
}
