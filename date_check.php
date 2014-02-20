<?php

function _date_is_valid($str) {
    if (substr_count($str, '-') == 2) {
        list($y, $m, $d) = explode('-', $str);
        return checkdate($m, $d, $y);
    }

    return false;
}

if(!_date_is_valid($start_date)) {
     header( 'Location: index.php?error=date&start_date='.$start_date.'&end_date='.$end_date) ;
     exit();
}
if(!_date_is_valid($end_date)) {
    header( 'Location: index.php?error=date&start_date='.$start_date.'&end_date='.$end_date) ;
    exit();
}


?>