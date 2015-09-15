<?php require_once ("Includes/session.php"); ?>
<html lang="en">
	<head>
	<meta charset="utf-8" />
        <?php
			$relative_path = $_SERVER['PHP_SELF'];
            if($relative_path=='/userPref_settings.php'||$relative_path=='/env_settings.php')
            {
                echo "<meta http-equiv=\"refresh\" content=\"15\" />";
            }
        ?>
        <title>
            <?php
			    echo "RIoT - ".substr($relative_path, 1, -4);
            ?>
        </title>
        <link rel="stylesheet" type="text/css" href="/Styles/Head.css">
        <script type="text/javascript" src="/Scripts/Site.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1"/> 
		<meta name="keywords" content="AntarikshYashSarveshAdityaRIoT4"/>
	</head>
	<body>
		<header id="ux-header" class="IE IE9 ltr">
			<div class="upperBand">
				<div class="upperBandContent">
					<div class="left">
						<a href="/index.php">
							<div class="site-title">
								<b>RIoT</b><span id="small"> User Profiles</span>
							</div>
						</a>
					</div>
					<div class="right">
						<div id="signIn">
                            <?php
							    if(logged_on()&&!ENV_logged_on())
                                {
                            ?>
                                    <a href="/dev_logon.php" title="Sign in">Env Sign In</a>
                                    <a href="/logoff.php" title="Sign in"><img src="/Images/logout.png" height="30px" width="30px"></a>
                            <?php
                                }
							    elseif(ENV_logged_on())
                                {
                            ?>
                                    <a href="/dev_logoff.php" title="Sign in">Env Sign Out</a>
                                    <a href="/logoff.php" title="Sign in"><img src="/Images/logout.png" height="30px" width="30px"></a>
                            <?php
                                }
                                elseif($relative_path=='/logon.php')
                                {
                            ?>
                                    <a href="/register.php" title="Sign in">Sign Up</a>
                            <?php
                                }
                                else
                                {
                            ?>
                                    <a href="/logon.php" title="Sign in">Sign In</a>
                            <?php
                                }
                            ?>
						</div>
					</div>
				</div>
			</div>
			<div style="height: 34px;" class="lowerBand">
				<div class="lowerBandContent">
					<nav>
						<ul class="dev-navigation">
							<li class="nav-expander">
								<a href="/index.php#Heading">Home</a>
							</li>
							<li class="nav-expander">
								<a href="/index.php#Desc1">Device</a>
							</li>
							<li class="nav-expander">
								<a href="/index.php#Desc2">Preference</a>
							</li>
						</ul>
					</nav>
					<div id="Lowerband_right">
						<ul class="dev-navigation">
							<li class="nav-expander">
								<?php
									if(logged_on()&&!ENV_logged_on())
                                    {
                                ?>
										<a href="/userPref_settings.php"><div id="Settings">My Settings</div></a>
                                <?php
                                    }
									if(ENV_logged_on())
                                    {
                                ?>
                                        <a href="/userPref_settings.php"><div id="Settings">My Settings</div></a>
										<a href="/env_settings.php"><div id="Settings">Env Settings</div></a>
                                <?php
                                    }
                                ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		<center>