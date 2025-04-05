<?php 
function multi_db_query($sql,$secondary_db = 'secondary')
{
    // Get primary database object
    $CI =& get_instance();
    // $primary_db_obj = $CI->load->database($primary_db, TRUE);

    // // Execute query on primary database
    // $primary_db_obj->query($sql);

    // Get secondary database object
    $secondary_db_obj = $CI->load->database($secondary_db, TRUE);

    // Execute query on secondary database
    $secondary_db_obj->query($sql);
}




?>