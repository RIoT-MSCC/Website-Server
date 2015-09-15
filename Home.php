<?php
	require_once ("/Includes/header.php");

    if (isset($_GET['Message'])){
        echo "<script>popup('".$_GET['Message']."')</script>";
        header ("Refresh:2; url=/Home.php");
    }
?>

<link rel="stylesheet" type="text/css" href="/Styles/Index.css">
<div id="Heading">
	<p id="para1">
		A step towards a unified environment.<br>
		Empowering you to regulate settings of your day to day devices on the go!
	</p>

    <?php
        if(!logged_on())
        {
    ?>
	        <p id="signup">
		        Not started yet?
            </p>
	        <p>
		        <a id="button" href="/register.php">Sign up</a>
	        </p>
    <?php
        }
    ?>

</div>
<div id="Desc1">
	<p id="Desc_Head">
		Hardware
	</p>
	<p id="Desc_content">
		Powered by the open source Arduino chipsets, we help you in connecting your devices to the internet.<br>
		Sync in your devices with the master-chip once and let it do the rest for you.
	</p>
</div>
<div id="Desc2">
	<p id="Desc_Head">
		Settings Portal
	</p>
	<p id="Desc_content">
		Connect and control the device directly using your phone/Desktop.<br>
		Running on the Microsoft Azure cloud platform, reliable quality service is assured.
	</p>
</div>

<?php
	require_once ("/Includes/footer.php");
?>