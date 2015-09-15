<?php
    require_once ("Includes/header.php");

    if(!logged_on() || !ENV_logged_on())
    {
        header ("Location: /Home.php");
    }

    if (isset($_GET['Message'])){
        echo "<script>popup('".$_GET['Message']."')</script>";
        header ("Refresh:2; url=/env_settings.php");
    }
    
    if(isset($_POST['AddDevice']))
    {
        header ("Location: /env_addDevice.php");
    }

    if (isset($_POST['ENV_Update']))
    {
        $mid = $_SESSION['MID'];
        $query = "SELECT * FROM riot4.ENV_settings WHERE id = ? ORDER BY PrimKey DESC";
		$params = array($mid);
		$statement_user = sqlsrv_query($conn,$query,$params);

		if(sqlsrv_has_rows($statement_user))
		{
			while($row = sqlsrv_fetch_array($statement_user))
			{
				$d = $row['PrimKey'];
                $d1 = $row['PrimKey'].$row['PrimKey'];
				$val = $_POST[$d];
                $lock = isset($_POST[$d1]);
                $l = 0;
                if($lock)
                {
                    $l = 1;
                }
                
				$query_update = "UPDATE riot4.ENV_settings SET Value = ?,LOCK = ? WHERE PrimKey = ?";
				$params = array($val, $l, $row['PrimKey']);
				$statement_update = sqlsrv_query($conn,$query_update,$params);
			}
		}
    }
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/Styles/Settings.css\">";

    global $conn;
    $mid = $_SESSION['MID'];

	/**/
    $query = "SELECT * FROM riot4.ENV_settings WHERE id = ? ORDER BY PrimKey DESC";
    $params = array($mid);
    $statement_user = sqlsrv_query($conn,$query,$params);
    
    echo "<div id=\"main\">";
    echo "<h2>Settings</h2>";
    echo "<p><form action=\"env_settings.php\" method=\"post\"></p>";
                        
        while($row = sqlsrv_fetch_array($statement_user))
        {
            $d = $row['PrimKey'];
            $d1 = $row['PrimKey'].$row['PrimKey'];
            $dev = $row['Device'];
            $val = $row['Value'];

            if($val!=NULL||$val==0)
            {
				if($_SESSION['ROOT']==$_SESSION['userid']||$row['LOCK']==0)
				{
					echo "<div class=\"settings\">";
						echo "<img src=\"/Images/$dev.png\" alt=\"$dev\" height=\"50em\" width=\"50em\">";
						echo "<input name=\"$d\" type=\"range\" min=\"0\" max=\"10\" value=\"$val\" oninput=\"showValue(this.name,this.value)\" />";
						if($_SESSION['ROOT']==$_SESSION['userid'])
						{
							if($row['LOCK']==0)
								echo "<input type=\"checkbox\" name=\"$d1\" id=\"$d1\"/><label for=\"$d1\"></label>";
							else
								echo "<input type=\"checkbox\" name=\"$d1\" id=\"$d1\"/ checked><label for=\"$d1\"></label>";
						}
						echo "<span id=\"$d\">$val</span>";
					echo "</div>";
				}
            }
        }
        if(isset($_POST['ENV_Update']))
        {
            echo "Updated<br><br>";
        }
        echo "<div class=\"settingsUpdate\">";
            echo "<input id=\"ENV_Update\" name=\"ENV_Update\" type=\"submit\" value=\"Update Env\"/>&nbsp;&nbsp;&nbsp;";
            echo "<input id=\"AddDevice\" name=\"AddDevice\" type=\"submit\" value=\"Add/Remove Device\"/>";
        echo "</div>";
    echo "</form>";
    echo "</div>";

    require_once ("Includes/footer.php"); 
?>