<?php
    require_once ("/Includes/simplecms-config.php");

	/* Prepares all the required content of the database to start querying */
    function prep_DB_content ()
    {
        global $conn;

        create_tables($conn);
        create_user($conn);
        create_user_devices($conn);
        create_env($conn);
        create_env_devices($conn);
    }

	/* Create all tables required by the Project when not available */
    function create_tables($conn)
    {
        $query_users = "IF object_id('riot4.users') IS NULL CREATE TABLE riot4.users (id INT IDENTITY(1,1) PRIMARY KEY, username VARCHAR(20) NOT NULL UNIQUE, password VARCHAR(40) NOT NULL, emailid VARCHAR(40) NOT NULL, logged_MID INT)";
        sqlsrv_query($conn,$query_users);
      
        $query_settings = "IF object_id('riot4.settings') IS NULL CREATE TABLE riot4.settings (User_ID INT NOT NULL, Device VARCHAR(20) NOT NULL, Value INT NOT NULL, PrimKey INT IDENTITY(1,1) PRIMARY KEY)";
        sqlsrv_query($conn,$query_settings);

        $query_env = "IF object_id('riot4.ENV') IS NULL CREATE TABLE riot4.ENV (id INT NOT NULL PRIMARY KEY, OTP VARCHAR(10) NOT NULL, root INT)";
        sqlsrv_query($conn,$query_env);

        $query_envsettings = "IF object_id('riot4.ENV_settings') IS NULL CREATE TABLE riot4.ENV_settings (id INT NOT NULL, Device VARCHAR(20) NOT NULL, Value INT NOT NULL, PrimKey INT IDENTITY(1,1) PRIMARY KEY, LOCK INT NOT NULL DEFAULT 0)";
        sqlsrv_query($conn,$query_envsettings);
    }

	/* Set all user values as defined by the configuration file */
    function create_user($conn)
    {
        // HACK: Storing config values in variables so that they aren't passed by reference later.
        $default_admin_username = DEFAULT_USERNAME;
        $default_admin_password = DEFAULT_PASSWORD;
        $default_admin_email_id = DEFAULT_EMAIL_ID;

        $query_check_admin_exists = "SELECT id FROM riot4.users WHERE username = ? LIMIT 1";
        $params = array($default_admin_username);
        $statement_check_admin_exist = sqlsrv_query($conn,$query_check_admin_exists,$params);

        if (!sqlsrv_has_rows($statement_check_admin_exist))
        {
            $query_insert_admin = "INSERT INTO riot4.users (username, password, emailid) VALUES (?, HASHBYTES('SHA',?),?)";
            $params = array($default_admin_username,$default_admin_password,$default_admin_email_id);
            sqlsrv_query($conn,$query_insert_admin,$params);
        }
    }
    
	/* Set the user preference setting for the default user created above */
    function create_user_devices($conn)
    {
        $query_check_data_exists = "SELECT User_ID FROM riot4.settings";
        $statement_check_data_exist = sqlsrv_query($conn,$query_check_data_exists);

        if (!sqlsrv_has_rows($statement_check_data_exist))
        {
            $query = "SELECT id FROM riot4.users";
            $statement = sqlsrv_query($conn,$query);

            $row =  sqlsrv_fetch_array($statement);
            $query_insert_devices = "INSERT INTO riot4.settings (User_ID,Device,Value) VALUES (?,'light_bulb',0),(?,'fan_ceil',0),(?,'light_table',0),(?,'fan_table',0),(?,'light_cfl',0)";
            $params = array($row['id'],$row['id'],$row['id'],$row['id'],$row['id']);
            sqlsrv_query($conn,$query_insert_devices,$params);
        }
    }

	/* Set all environment values as defined by the configuration file */
    function create_env($conn)
    {
        // HACK: Storing config values in variables so that they aren't passed by reference later.
        $default_env_id = DEFAULT_ENV_ID;
        $default_env_otp = DEFAULT_ENV_OTP;

        $query_check_env_exists = "SELECT * FROM riot4.ENV";
        $statement_check_env_exist = sqlsrv_query($conn,$query_check_env_exists);

        if (!sqlsrv_has_rows($statement_check_env_exist))
        {
            $query_insert_env = "INSERT INTO riot4.ENV (id,OTP) VALUES (?,?)";
            $params = array($default_env_id,$default_env_otp);
            sqlsrv_query($conn,$query_insert_env,$params);
        }
    }

	/* Set the environment device(s) setting for the default environment created above */
    function create_env_devices($conn)
    {
        $query_check_data_exists = "SELECT * FROM riot4.ENV_settings";
        $statement_check_data_exist = sqlsrv_query($conn,$query_check_data_exists);

        if (!sqlsrv_has_rows($statement_check_data_exist))
        {
            $MID = DEFAULT_ENV_ID;
            $query_insert_devices = "INSERT INTO riot4.ENV_settings (id,Device,Value) VALUES (?,'light_bulb',4),(?,'fan_table',8),(?,'light_table',2)";
            $params = array($MID,$MID,$MID);
            sqlsrv_query($conn,$query_insert_devices,$params);
        }
    }
?>