<?php
//This option should be provided based on the devices present in the current environment only
    require_once ("Includes/header.php");

    if(!ENV_logged_on() || $_SESSION['ROOT']!=$_SESSION['userid'])
    {
        header ("Location: /index.php");
    }

    $dev = array("light_bulb","fan_ceil","fan_table","light_cfl","light_table");
    $mid = $_SESSION['MID'];

    $query = "SELECT * FROM riot4.ENV_settings WHERE id=?";
    $params = array($mid);
    $statement_count = sqlsrv_query($conn,$query,$params);
    
    if(isset($_POST['Update']))
    {
        while($row = sqlsrv_fetch_array($statement_count))
        {
            $d = $row['Device'];
            $dev = array_diff($dev, array($d));
            $ad_rm = isset($_POST[$d]);
            $ar = 0;
            if($ad_rm)
            {
                $ar = 1;
            }

            if($ar)
            {
                $query = "DELETE FROM riot4.ENV_settings WHERE id=? AND Device=?";
                $params = array($mid,$d);
                sqlsrv_query($conn,$query,$params);
            }
        }
        foreach($dev as $d)
        {
            $ad_rm = isset($_POST[$d]);
            $ar = 1;
            if($ad_rm)
            {
                $ar = 0;
            }

            if($ar)
            {
                $query = "INSERT INTO riot4.ENV_settings (id,Device,Value) VALUES (?,?,0)";
                $params = array($mid,$d);
                sqlsrv_query($conn,$query,$params);
            }
        }
        header ("Location: /env_settings.php");
    }
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/Styles/AddDevice.css\">";
    
    echo "<div id=\"main\">";
    echo "<h2>Add\Remove Devices</h2>";
    echo "<p><form action=\"env_addDevice.php\" method=\"post\"></p>";

    $udev = array();
        while($row = sqlsrv_fetch_array($statement_count))
        {
            array_push($udev,$row['Device']);
            $d = $row['Device'];
            $dev = array_diff($dev, array($d));
            echo "<div class=\"settings\">";
    		echo "<img src=\"/Images/$d.png\" alt=\"$d\" height=\"50em\" width=\"50em\">";
			echo "<input type=\"checkbox\" name=\"$d\" id=\"$d\"/><label for=\"$d\"></label>";
            echo "</div>";
        }
        
        foreach($dev as $d)
        {
            echo "<div class=\"settings\">";
    		echo "<img src=\"/Images/$d.png\" alt=\"$d\" height=\"50em\" width=\"50em\">";
			echo "<input type=\"checkbox\" name=\"$d\" id=\"$d\" checked/><label for=\"$d\"></label>";
            echo "</div>";
        }
        echo "<div class=\"settingsUpdate\">";
            echo "<input id=\"Update\" name=\"Update\" type=\"submit\" value=\"Update\"/>";
        echo "</div>";
    echo "</form>";
    echo "</div>";

    require_once ("Includes/footer.php"); 
?>