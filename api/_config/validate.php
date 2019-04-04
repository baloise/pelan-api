<?php

function val_string ($value, $min=false, $max=false) {

    $value = trim($value);
    $value = htmlspecialchars($value);

    $value = filter_var($value, FILTER_SANITIZE_STRING);
    if ( ($min && $max) && (($min && strlen($value) < $min) || ($max && strlen($value) > $max)) ) {
        returnBadRequest("Value-Check (String) failed");
    } else {
        return $value;
    }

}

function val_number ($value, $min=false, $max=true) {

    if($value == 0 && !$min){
        return $value;
    } else {
        $value = trim($value);
        $value = htmlspecialchars($value);
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
        $state = filter_var($value, FILTER_VALIDATE_FLOAT);
        if($state && $min <= $value && $max >= $value){
            return $value;
        }
    }

    returnBadRequest("Value-Check (Number) failed");

}

function val_email ($value, $min=false, $max=false) {

    $value = trim($value);
    $value = htmlspecialchars($value);

    $value = filter_var($value, FILTER_SANITIZE_EMAIL);
    $state = filter_var($value, FILTER_VALIDATE_EMAIL);
    if (($min && $value < $min) || ($max && $value > $max) || !$state && $min && $max) {
        returnBadRequest("Value-Check (E-Mail) failed");
    } else {
        return $value;
    }

}
