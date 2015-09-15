<?php
    session_start();
    require_once  ("Includes/connectDB.php");

    function logged_on()
    {
        return isset($_SESSION['userid']);
    }

    function ENV_logged_on()
    {
        $query = "SELECT * FROM riot4.ENV WHERE id = ?";
        
        $params = array($_SESSION['MID']);
        $statement = sqlsrv_query($conn,$query,$params);

        if(sqlsrv_has_rows($statement))
        {
            $row = sqlsrv_fetch_array($statement);

            if($row['OTP']==$_SESSION['ENV_OTP'])
            {
                $_SESSION['ENV_OTP']=$row['OTP'];
            }
            else
            {
                $_SESSION['ENV_OTP']=NULL;
                $_SESSION['MID']=NULL;
                $_SESSION['ROOT']=NULL;
            }
        }
        return isset($_SESSION['MID']);
    }

    function get_count($MID)
    {
        $query = "SELECT * FROM riot4.users WHERE logged_MID = ?";
        
        $params = array($MID);
        $statement = sqlsrv_query($conn,$query,$params);

        return sqlsrv_num_rows($statement);
    }
?>