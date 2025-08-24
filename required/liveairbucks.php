<?php include "../pages/dbconnect.php";


$userid = $_GET['u'];
$rs = $db->query("SELECT airbucks FROM users WHERE userid=$userid");
while($ir = $db->fetch_row($rs)) {
    echo '✈'.number_format($ir['airbucks']); 
}

?>