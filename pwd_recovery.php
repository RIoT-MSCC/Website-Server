<?php
    require_once ("Includes/session.php");
    require_once ("Includes/simplecms-config.php"); 
    require_once ("Includes/connectDB.php");
    require_once ("Includes/header.php");
    require_once ("send_email/sendgrid-php/vendor/autoload.php");

    if(logged_on())
    {
        header ("Location: /index.php");
    }
?>

<?php
    /* logon.php SUBMIT button function
    -------------------------------------------------*/
    if (isset($_POST['forgot_password']))
    {
        $username  = $_POST['username'];
        $emailto = $_POST['email'];
        $valid = FALSE;

        $query = " SELECT * FROM riot4.users WHERE username = ? and emailid = ?";
        $params = array($username, $emailto);
        $statement = sqlsrv_query($conn, $query, $params);

        if(sqlsrv_has_rows($statement))
        {
            $_SESSION['random_key'] = rand(10000,99999);
            $_SESSION['temp_user'] = $username;

            $user = 'azure_430238948e4922913c2e268be74c2d44@azure.com';
            $pass = 'p39RnC8dNWYXW0z';

            $sendgrid = new SendGrid($user, $pass);
            $email    = new SendGrid\Email();
            $msg = "<html>
                        <body>
                        <h1> Hi ".$username.",</h1><h3>
                        <br><br>Please use this key to change your password: ".$_SESSION['random_key'].
                        "<br>Please ignore this mail if you haven't requested for a change of password.
                        <br><br><br><br>
                        <b>Warm Regards,</b><br>
                        Team A<br>
                        RIoT
                        </h3>
                        </body>
                    </html>";

            $email->addTo($emailto)
                  ->setFrom("sarveshwaran.dk@outlook.com")
                  ->addHeader('Sent-From-riot4.azurewebsites.net', 'SendGrid-API')
                  ->setSubject("RIoT one time key")
                  ->setHtml($msg);

            $response = $sendgrid->send($email);           
            
            header("Location: pwd_reset.php");
        }
        else
        {
            $valid = TRUE;
        }
    }
?>
<link rel="stylesheet" type="text/css" href="/Styles/Logon.css">
    <div id="main">
        <h2>Password Recovery</h2>
        <form action="pwd_recovery.php" method="post">
            <fieldset>
                <legend>Password Recovery</legend>
                <ol>
                    <li>
                        <label for="username">Username:</label> 
                        <input type="text" name="username" value="" id="username" />
                    </li>
                    <li>
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="" id="email" />
                    </li>
                </ol>
                <input type="submit" name="forgot_password" id="forgot_password" value="Recover" />

<?php
    if(isset($_POST['forgot_password']))
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