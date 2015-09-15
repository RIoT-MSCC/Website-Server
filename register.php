<?php 
    require_once ("Includes/simplecms-config.php"); 
    require_once ("Includes/connectDB.php");
    require_once ("Includes/header.php");
    require_once ("send_email/sendgrid-php/vendor/autoload.php");

    if(logged_on())
    {
        header ("Location: /Home.php");
    }

    if (isset($_POST['Register']))
    {
        if($_POST['username'] and $_POST['password'] and $_POST['confirmpassword'])
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $cpasswrd = $_POST['confirmpassword'];
            $email    = $_POST['email'];
            $valid = FALSE;

            if($cpasswrd!=$password)
            {
                $msg = "Passwords Mismatch.<br><br>";
                $valid = TRUE;
            }
            elseif (strlen($username)<4 or strlen($username)>31)
            {
                $msg = "Username should be of length 5 to 30.<br><br>";
                $valid = TRUE;
            }
            elseif(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
	        {
		        $msg = "Your username contains invalid characters!<br><br>";
                $valid = TRUE;
	        }
            else
            {
                $query = "INSERT INTO riot4.users (username, password, emailid) VALUES (?, HASHBYTES('SHA',?), ?)";
                $params = array($username, $password, $email);
                $statement = sqlsrv_query($conn, $query, $params);
        
                $creationWasSuccessful = sqlsrv_rows_affected($statement) == 1? true: false;

                if ($creationWasSuccessful)
                {
                    $query = "SELECT id,username FROM riot4.users WHERE username = ? AND password = HASHBYTES('SHA',?)";
                    $params = array($username,$password);
                    $statement = sqlsrv_query($conn,$query,$params);

                    if(sqlsrv_has_rows($statement))
                    {
                        $row = sqlsrv_fetch_array($statement);
                        $_SESSION['userid'] = $row['id'];
                        $_SESSION['username'] = $row['username'];
                        
                        $query_insert = "INSERT INTO riot4.settings (User_ID,Device,Value) VALUES (?,'light_bulb',0),(?,'fan_ceil',0),(?,'light_table',0),(?,'fan_table',0),(?,'light_cfl',0)";
            
                        $params = array($_SESSION['userid'],$_SESSION['userid'],$_SESSION['userid'],$_SESSION['userid'],$_SESSION['userid']);
                        sqlsrv_query($conn,$query_insert,$params);

						$user = 'azure_430238948e4922913c2e268be74c2d44@azure.com';
					    $pass = 'p39RnC8dNWYXW0z';

						$sendgrid = new SendGrid($user, $pass);
			            $emailto    = new SendGrid\Email();
						$msg = "<html>
									<body>
					                    <h1> Hi	".$_SESSION['username'].",</h1><h3>
				                        <br><br>We have received your request to join RIoT, we have successfully created your account.
										<br>Your Username: ".$username."
										<br>Password:".$password."
				                        <br><br>Please ignore this message if you have not created this account.
										<br><br><br><br>
								        <b>Warm Regards,</b><br>
				                        Team A<br>
								        RIoT
										</h3>
			                        </body>
						        </html>";

						$emailto->addTo($email)
							  ->setFrom("sarveshwaran.dk@outlook.com")
							  ->addHeader('Sent-From-riot4.azurewebsites.net', 'SendGrid-API')
							  ->setSubject("RIoT one time key")
							  ->setHtml($msg);

						$response = $sendgrid->send($emailto);           
            
                        $msg = "You have been successfully registered. Have a nice day.";
                        header ("Location: /Home.php?Message=".urlencode($msg));
                    }
                }
                else
                {
                    $msg = "Username/EmailID already exists. Try Again!<br><br>";
                    $valid = TRUE;
                }
            }
        }
        else
        {
             $msg = "Please fill up all the fields!<br><br>";
             $valid = TRUE;
        }
    }
?>
<link rel="stylesheet" type="text/css" href="/Styles/Logon.css">
    <div id="main">
        <h2>Register an account</h2>
        <form action="register.php" method="post">
            <fieldset>
                <legend>Register an account</legend>
                <ol>
                    <li>
                        <label for="username">Username:</label> 
                        <input type="text" name="username" value="" id="username" />
                    </li>
                    <li>
                        <label for="password">Password:</label>
                        <input type="password" name="password" value="" id="password" />
                    </li>
                    <li>
                        <label for="password">Confirm Password:</label>
                        <input type="password" name="confirmpassword" value="" id="confirmpassword" />
                    </li>
                    <li>
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="" id="email" />
                    </li>
                </ol>

<?php
    if(isset($_POST['Register']) && $valid)
    {
        echo $msg;
    }
?>

                <input type="submit" name="Register" value="Register" />
            </fieldset>
        </form>
    </div>
<?php
    include ("Includes/footer.php");
?>