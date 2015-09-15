<?php
	$relative_path = $_SERVER['PHP_SELF'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>RIoT - Error</title>
    </head>
    <body>
		<center>
        <h1>
			<img src="/Images/error.png" alt="Error" height="150px" width="150px"/>
			<br>
			<?php
				echo "The Page you requested for is not available at our domain RIoT.\n<h3>Please check the path again.</h3>";
			?>
		</h1>
		</center>
    </body>
</html>
