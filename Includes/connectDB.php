<?php
	/* Prepare connection to database and set all tables and values required to process further */

    require_once ("/Includes/simplecms-config.php");
    require_once ("/Functions/database.php");
	require_once ("/Functions/Check_Errors.php");

    $connectionoptions = array("Database" => $database, 
                               "UID" => $user, 
                               "PWD" => $pass);
                               
    $conn = sqlsrv_connect($server, $connectionoptions);
    
    if($conn === false)
    {
        die(print_r(sqlsrv_errors(), true));
    }

    /* Create tables if needed. */
    prep_DB_content();
?>