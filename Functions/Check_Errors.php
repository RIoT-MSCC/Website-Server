<?php
	function check_errors()
    {
        if(($errors = sqlsrv_error())!=null)
        {
            foreach( $errors as $error)
            {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."\n";
                echo "code: ".$error[ 'code']."\n";
                echo "message: ".$error[ 'message']."\n";
            }
        }
    }
?>