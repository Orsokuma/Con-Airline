<?php include "../pages/dbconnect.php";


$userid = $_GET['u'];
$rs = $db->query("SELECT bucks FROM users WHERE userid=$userid");
while($ir = $db->fetch_row($rs)) {
    echo '💲'.number_format($ir['bucks']); 
}

?>
