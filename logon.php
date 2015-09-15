<?php
    require_once ("Includes/header.php");
    
    if(logged_on())
    {
        header ("Location: /index.php");
    }

    if (isset($_POST['Login']))
    {
        if($_POST['username'] and $_POST['password'])
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $valid = FALSE;

            $query = "SELECT id,username FROM riot4.users WHERE username = ? AND password = HASHBYTES('SHA',?)";
            
            $params = array($username,$password);
            $statement = sqlsrv_query($conn,$query,$params);

            if(sqlsrv_has_rows($statement))
            {
                $row = sqlsrv_fetch_array($statement);
                $_SESSION['userid'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                header ("Location: index.php");
            }
            else
            {
                $msg = "Username/password combination is incorrect.<br><br>";
                $valid = TRUE;
            }
        }
        else
        {
            $msg = "Please provide both username and password.<br><br>";
            $valid = TRUE;
        }
    }
?>

<link rel="stylesheet" type="text/css" href="/Styles/Logon.css">
<div id="main">
    <h2>Log on</h2>
        <form action="logon.php" method="post">
            <fieldset>
            <legend>Log on</legend>
            <ol>
                <li>
                    <label for="username">Username:</label>
                    <input type="text" name="username" value="" id="username" />
                </li>
                <li>
                    <label for="password">Password:</label>
                    <input type="password" name="password" value="" id="password" />
                </li>
            </ol>

<?php
    if (isset($_POST['Login']) && $valid)
    {
        echo $msg;
    }
?>

            <input type="submit" id="Login" name="Login" value="Login" /><br><br>
            <a href="/pwd_recovery.php">Forgot Password?</a>
        </fieldset>
    </form>
</div>


<?php
    require_once ("Includes/footer.php");
?>