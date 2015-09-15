<?php 
    require_once ("Includes/header.php");

    if(!logged_on())
    {
        header ("Location: /index.php");
    }

    if (isset($_GET['Message'])){
        echo "<script>popup('".$_GET['Message']."')</script>";
        header ("Refresh:2; url=/userPref_settings.php");
    }

    if(isset($_POST['AddDevice']))
    {
        header ("Location: /user_addDevice.php");
    }

    if(isset($_POST['Update']))
    {
        $Uid = $_SESSION['userid'];
        $query = "SELECT * FROM riot4.settings WHERE User_ID = ? ORDER BY PrimKey DESC";
        $params = array($Uid);
        $statement_user = sqlsrv_query($conn,$query,$params);

        while($row = sqlsrv_fetch_array($statement_user))
        {
            $d1 = $row['PrimKey'];
            $val = $_POST[$d1];
                
            $query_update = "UPDATE riot4.settings SET Value = ? WHERE PrimKey = ?";
            $params = array($val, $row['PrimKey']);
            $statement_update = sqlsrv_query($conn,$query_update,$params);
        }
    }
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/Styles/Settings.css\">";

    global $conn;
    $Uid = $_SESSION['userid'];

    $query = "SELECT * FROM riot4.settings WHERE User_ID = ? ORDER BY PrimKey DESC";
    $params = array($Uid);
    $statement_user = sqlsrv_query($conn,$query,$params);

    echo "<div id=\"main\">";
    echo "<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Preferences</h2>";
    echo "<p><form action=\"userPref_settings.php\" method=\"post\"></p>";
                        
        while($row = sqlsrv_fetch_array($statement_user))
        {
            $d = $row['PrimKey'];
            $dev = $row['Device'];
            $val = $row['Value'];

            if($val!=NULL||$val==0)
            {
                echo "<div class=\"settings\">";
                    echo "<img src=\"/Images/$dev.png\" alt=\"$dev\" height=\"50em\" width=\"50em\">";
                    echo "<input name=\"$d\" type=\"range\" min=\"0\" max=\"10\" value=\"$val\" oninput=\"showValue(this.name,this.value)\" />";
                    echo "<span id=\"$d\">$val</span>";
                echo "</div>";
            }
        }
        if(isset($_POST['Update']))
        {
            echo "Updated<br><br>";
        }
        echo "<div class=\"settingsUpdate\">";
            echo "<input id=\"Update\" name=\"Update\" type=\"submit\" value=\"Update\"/>&nbsp;&nbsp;&nbsp;&nbsp;";
            echo "<input id=\"AddDevice\" name=\"AddDevice\" type=\"submit\" value=\"Add/Remove Device\"/>";
        echo "</div>";
    echo "</form>";
    echo "</div>";

    require_once ("Includes/footer.php"); 
?>