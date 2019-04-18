<?php

function val_string ($value, $min=false, $max=true) {

    $value = trim($value);
    if(strlen($value) === 0 && !$min){
        return $value;
    } else {
        $value = addslashes($value);
        $value = htmlspecialchars($value, ENT_NOQUOTES);
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        if($min <= strlen($value) && $max >= strlen($value)){
            return $value;
        }
    }

    return 'Incorrect string';

}

$sentence = 'HAHA """" @';
$funct = val_string($sentence, 1, 255);

echo $sentence . '<br /><br />';
print_r($funct);

?>
