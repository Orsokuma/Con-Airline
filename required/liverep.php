<?php include "../pages/dbconnect.php";


$userid = $_GET['u'];
$rs = $db->query("SELECT reputation FROM users WHERE userid=$userid");
while($ir = $db->fetch_row($rs)) {
    echo number_format($ir['reputation'],5); 
}
?>