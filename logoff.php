<?php
    require_once ("Includes/session.php");
    require_once ("Includes/connectDB.php");

    $query_user_update = "UPDATE riot4.users SET logged_MID = ? WHERE id = ?";

    $params = array(NULL,$_SESSION['userid']);
    sqlsrv_query($conn,$query_user_update,$params);

	$query = "SELECT * from riot4.ENV_settings WHERE id = ?";
	$params = array($_SESSION['MID']);
	$statement = sqlsrv_query($conn, $query, $params);

	if(sqlsrv_has_rows($statement))
	{
		if($_SESSION['userid']!=$_SESSION['ROOT'] && $row['LOCK']==0)
		{
			while($row = sqlsrv_fetch_array($statement))
			{
				$query_env_update = "UPDATE riot4.settings SET Value  = ? WHERE User_ID = ? AND Device = ?";
				$params = array($row['Value'],$_SESSION['userid'],$row['Device']);
				$statement_env_update = sqlsrv_query($conn, $query_env_update,$params);
			}
		}
		elseif($_SESSION['userid']==$_SESSION['ROOT'])
		{
			while($row = sqlsrv_fetch_array($statement))
			{
				$query_env_update = "UPDATE riot4.settings SET Value  = ? WHERE User_ID = ? AND Device = ?";
				$params = array($row['Value'],$_SESSION['userid'],$row['Device']);
				$statement_env_update = sqlsrv_query($conn, $query_env_update,$params);
			}
		}
	}

	session_start();
	$_SESSION = array();
	if(isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-300, '/');
	}
	session_destroy();
    $_SESSION['MID']=NULL;
    $_SESSION['ENV_OTP']=NULL;
    $_SESSION['ROOT']=NULL;

	header ("Location: index.php");
?>