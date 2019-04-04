<?php

include_once '_config/core.php';
include_once '_config/validate.php';


try {

    $value = val_number($value,9, 200);
    echo $value;

} catch(Exception $e){
    echo $e;
}
