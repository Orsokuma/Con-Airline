<?php include "../pages/dbconnect.php";





$userid = isset($_POST['userid']) ? $_POST['userid'] : 0;
$userinfo = $db->query("SELECT * FROM users WHERE userid=$userid");
$user = $db->fetch_row($userinfo);
$planeID = $_POST['planeID'];

$getinfo = $db->query("SELECT * FROM `airplanes` WHERE planeID='$planeID'");
$data=$db->fetch_row($getinfo);
    $cost = $data['planeCOST'];
    $planeID = $data['planeID'];
    $planePASSENGER = $data['planePASSENGER'];
    $planeFUEL = $data['planeFUEL'];
    $planeSPEED = $data['planeSPEED'];
    $planeDISTANCE = $data['planeDISTANCE'];
    $planeWEIGHT = $data['planeWEIGHT'];
    $planeCONSUMPTIONRATE = $data['planeCONSUMPTIONRATE'];
    $planeLAT = $user['latitude'];
    $planeLON = $user['longitude'];
    $premiumcost = $data['premiumcost'];
    $premium = $_POST['premium'];
if ($premium == '1') {
if ($user['airbucks'] < $premiumcost) {
    echo 'You cannot afford to buy this plane<br />
        <br />
        <a href="../pages/fleets.php?u='.$userid.'" target="fleets">Continue</a>';
} else {
$db->query("INSERT INTO `userairplanes` (`id`,`planeOWNER`, `planeID`, `planePASSENGER`, `planePASSENGERCURRENT`, `planeMAXFUEL`, `planeFUELCURRENT`, `planeSPEED`, `planeMAXDISTANCE`, `planeDISTANCETRAVELLED`, `planeMAXWEIGHT`, `planeCURRENTWEIGHT`, `planeCONSUMPTIONRATE`, `planeLOCATIONLAT`, `planeLOCATIONLON`, `planeACTIVE`) VALUES 
('','$userid','$planeID','$planePASSENGER','0','$planeFUEL','0','$planeSPEED','$planeDISTANCE','0','$planeWEIGHT','0','$planeCONSUMPTIONRATE','$planeLAT','$planeLON','0')");
$i = $db->insert_id(); 
$db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$premiumcost','Purchase: Plane: <b>$i</b>',unix_timestamp(),'airbucks')");
$db->query("UPDATE users SET airbucks=airbucks-'$premiumcost' WHERE userid=$userid");    
echo '<p>Success</p><br />
        You have just paid <span class="airbucks">'.money_formatter($premiumcost).'</span> bucks for this plane.<br />
        <br />
        <a href="../pages/fleets.php?u='.$userid.'" target="fleets">Continue</a>';    
}       
} else {
if ($user['bucks'] < $cost) {
    echo 'You cannot afford to buy this plane<br />
        <br />
        <a href="../pages/fleets.php?u='.$userid.'" target="fleets">Continue</a>';
} else {
$db->query("INSERT INTO `userairplanes` (`id`,`planeOWNER`, `planeID`, `planePASSENGER`, `planePASSENGERCURRENT`, `planeMAXFUEL`, `planeFUELCURRENT`, `planeSPEED`, `planeMAXDISTANCE`, `planeDISTANCETRAVELLED`, `planeMAXWEIGHT`, `planeCURRENTWEIGHT`, `planeCONSUMPTIONRATE`, `planeLOCATIONLAT`, `planeLOCATIONLON`, `planeACTIVE`) VALUES 
('','$userid','$planeID','$planePASSENGER','0','$planeFUEL','0','$planeSPEED','$planeDISTANCE','0','$planeWEIGHT','0','$planeCONSUMPTIONRATE','$planeLAT','$planeLON','0')");
$i = $db->insert_id(); 
$db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$cost','Purchase: Plane: <b>$i</b>',unix_timestamp(),'bucks')");
$db->query("UPDATE users SET bucks=bucks-'$cost' WHERE userid=$userid");    
echo '<p>Success</p><br />
        You have just paid <span class="bucks">'.money_formatter($cost).'</span> bucks for this plane.<br />
        <br />
        <a href="../pages/fleets.php?u='.$userid.'" target="fleets">Continue</a>';    
}   
}





?>    

        </table>

      </div>
      