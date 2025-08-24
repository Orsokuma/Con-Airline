<?php include "../pages/dbconnect.php";


$userid = $_GET['u'];
$cnt = $db->query("SELECT * FROM userairplanes WHERE planeACTIVE=0 AND planeOWNER=$userid");
$ready = $db->num_rows($cnt);

    echo number_format($ready); 


?>