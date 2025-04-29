<?php 
function formatSafeDate($date, $format = 'Y-m-d') {
    if (!empty($date) && $date !== '0000-00-00' && strtotime($date)) {
        return date($format, strtotime($date));
    }
    return '';
}


?>