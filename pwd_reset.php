<?php
    require_once ("Includes/session.php");
    require_once ("Includes/simplecms-config.php"); 
    require_once ("Includes/connectDB.php");
    require_once ("Includes/header.php");

    if(logged_on())
    {
        header ("Location: /index.php");
    }
?>

<?php
    /* pwd_recovery.php SUBMIT button function
    -------------------------------------------------*/
    if (isset($_POST['password_reset']))
    {
        $OTP = $_POST['OTP'];
        $pwd = $_POST['password'];
        $cpwd = $_POST['confirm_password'];
        $valid = FALSE;

        if($pwd != $cpwd || $OTP != $_SESSION['random_key'])
        {
            $valid = TRUE;
        }
        else
        {    
            //Updating the password in the database
            $query = "UPDATE riot4.users SET password = HASHBYTES('SHA',?) WHERE username = ?";
            $params = array($pwd, $_SESSION['temp_user']);
            $statement  = sqlsrv_query($conn, $query, $params);
               
            //Setting the current user as logged in    
            $query = "SELECT * FROM riot4.users WHERE username = ?";
            $params = array($_SESSION['temp_user']);
            $statement = sqlsrv_query($conn, $query, $params);

            $row = sqlsrv_fetch_array($statement);
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $_SESSION['temp_user'];

            $_SESSION['temp_user'] = NULL;
            $_SESSION['random_key'] = NULL;

            header ("Location: index.php");
        }
    }
?>
<link rel="stylesheet" type="text/css" href="/Styles/Logon.css">
    <div id="main">
        <h2>Password Reset</h2>
        <form action="pwd_reset.php" method="post">
            <fieldset>
                <legend>Password Reset</legend>
                <ol>
                    <li>
                        <label for="OTP">OTP:</label>
                        <input type="password" name="OTP" value="" id="OTP" />
                    </li>
                    <li>
                        <label for="password">Password:</label>
                        <input type="password" name="password" value="" id="password" />
                    </li>
                    <li>
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" name="confirm_password" value="" id="confirm_password" />
                    </li>
                </ol>
                <input type="submit" name="password_reset" value="Reset" />

<?php
    if(isset($_POST['password_reset']))
    {
        if ($valid)
        {
            echo "<br>Sorry, No such user-email combination exists. Please Try Again!";
        }
    }
?>

            </fieldset>
        </form>
    </div>

<?php 
    require_once ("Includes/footer.php");
 ?>