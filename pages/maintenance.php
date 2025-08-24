<?php include "../pages/dbconnect.php";



$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'maintenance': maintenance(); break;
case 'maintenancedo': maintenancedo(); break;
case 'servicelog' : servicelog(); break;
default:index(); break;
}




function index()
{
global $db;
$userid = $_GET['u'];
$dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($dbbd);
?>
This is where you will Maintain your aircrafts.<br />
<?php

$fet = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($fet);


    $getinfo = $db->query("SELECT * FROM `userairplanes` WHERE planeOWNER='$userid' ORDER BY planeHEALTH ASC");
    $userinfoquery = $db->query("SELECT * FROM `users` WHERE userid='$userid'");
    $userinfo = $db->fetch_row($userinfoquery);
    
    $cnt = $db->query("SELECT * FROM userairplanes WHERE planeACTIVE=0 AND planeOWNER=$userid");
    $ready = $db->num_rows($cnt);
    
        while($data=$db->fetch_row($getinfo)) {
            $planeID = $data['planeID'];
            $gr = $db->query("SELECT * FROM airplanes WHERE planeID=$planeID");
            $datas = $db->fetch_row($gr);
            $id = $data['id'];
            $gh = $db->query("SELECT * FROM activeflights WHERE planeID=$id");
            $active = $db->fetch_row($gh);
            $currenttime = $active['flightEndTime'];
            $planeLat = $data['planeLOCATIONLAT'];
            $planeLng = $data['planeLOCATIONLON'];
        
        if($data['planeACTIVE'] == '0') {
            $style="success";
            $arrival = ' <b>Location:</b> Parked at Terminal.';
            $arrivalnav = ' <b>Location:</b> Parked at Terminal.';
        } else {
            $style="secondary";
            $arrival = ' <b>Arrival:</b> '.date("jS F, Y, H:i",$currenttime+3660);
            $arrivalnav = " <b>Flight Time left:</b> <span id='countdown$id'></span>";
        } 
        $perc=($data['planeFUELCURRENT']/$data['planeMAXFUEL']*100);
        $wperc=($data['planeCURRENTWEIGHT']/$data['planeMAXWEIGHT']*100);
        $pperc=($data['planePASSENGERCURRENT']/$data['planePASSENGER']*100);
        $hperc=($data['planeHEALTH']/$data['planeMAXHEALTH']*100);
        $fueldis = 100 - $perc;
        $weightdis = 100 - $wperc;
        $passdis = 100 - $pperc;
        $health = 100 - $hperc;
        if($perc < '20') { $fstyle = 'warning'; } else { $fstyle = 'success'; }
        if($wperc > '90') { $wstyle = 'warning'; } else { $wstyle = 'success'; }
        if($pperc < '10') { $pstyle = 'warning'; } else { $pstyle = 'success'; }
        if($hperc < '30') { $hstyle = 'warning'; } else { $hstyle = 'success'; }
        
        
        if($data['planeACTIVE'] == 0) { $head = 'Landed At'; } else { $head = 'Flying To'; } 
        
        ?>
            
  <div id="accordion">
    <div class="card"><a class="btn btn-<?php echo $style; ?>" data-bs-toggle="collapse" href="#collapse<?php echo $id; ?>">
      <div class="card-header">
        <table width="100%" border="1">
            <tr>
                <td width="45%"><font color="white"><img src="<?php echo $datas['planeIMAGE']; ?>" width="75px" class="rounded"> <?php if ($data['planeUname'] == 'Not Set') { ?><b><?php echo $datas['planeMAKE'].' - '.$datas['planeMODEL']; ?></b> <small>(ID: <?php echo $id; ?>)</small>
                                    <?php } else { ?>
                                    <b><?php echo $data['planeUname']; ?></b> <small>(ID: <?php echo $id; ?>)</small><?php } ?></font></td>
                <td width="45%" colspan="2"><b>Health: </b><?php if ($data['planeHEALTH'] == $data['planeMAXHEALTH']) {echo round($data['planeHEALTH']);} else {echo $data['planeHEALTH'];}; ?>%<br />
                      <div class="progress">
                        <div class="progress-bar bg-<?php echo $hstyle; ?>" style="width:<?php echo $hperc; ?>%">
                        </div>
                        <div class="progress-bar bg-danger" style="width:<?php echo $health; ?>%">
                      </div></div></td>
                <td width="10%"></td>
            </tr>
        </table>

      </div></a>
      
      <div id="collapse<?php echo $id; ?>" class="collapse hide" data-bs-parent="#accordion">
        <div class="card-body">
            <table width="100%">
              <tr>
                <td width="25%"></td>
                <td width="30%"></td>
                <td width="30%"></td>
                <td Width="15%"></td>
            </tr>  
            <tr valign="middle" class="alert alert-<?php echo $style; ?>">
                <td align="center"><img src="<?php echo $datas['planeIMAGE']; ?>" width="200px" class="rounded"><br />
                    <?php
                    if ($data['planeUname'] == 'Not Set') { ?>
                    <b><?php echo $datas['planeMAKE'].' - '.$datas['planeMODEL']; ?></b> <small>(ID: <?php echo $id; ?>)</small><br />
                    <?php } else { ?>
                    <b><?php echo $data['planeUname']; ?></b> <small>(ID: <?php echo $id; ?>)</small>
                    <?php } ?>
                   </td>
                <td colspan="3">
                    
                    <?php if($data['planeACTIVE'] == 0) { ?>
                        <form action="?a=maintenance" target="maintenance" method="post">
                            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                            <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                            <input type="submit" name="" value="Maintain Aircraft Window" class="btn btn-info">
                        </form>
                        
                        <form action="?a=servicelog" target="maintenance" method="post">
                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                                <input type="submit" name="" value="Service Log" class="btn btn-info btn-sm">
                        </form>
                        
                    <?php } else { ?>
                     You cannot maintain this Aircraft While it is still in flight
                    <?php } ?>
                                    </td>
            </tr>
         </table>
         
         
         
        </div> 
      </div>
    </div>
  </div>   
        <?php } ?>    

      </div>
     <?php
}



function maintenance() {
    global $db;
    $userid = $_POST['userid'];
    $planeID = $_POST['planeID'];

$query = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
$data = $db->fetch_row($query);
$wages = '84.91';


$health = $data['planeHEALTH'];
$maxhealth = $data['planeMAXHEALTH'];
$left = $maxhealth - $health;
?>

<h2>Maintenance for Plane ID: <?php echo $planeID; ?></h2>
Your Current Plane Health is at: <b><?php echo $data['planeHEALTH']; ?></b>%<br /><br />
<br />
What do you want to do today?<br /><Br />
    <script>
                function myFunction() {
                  var x = document.getElementById("health").value;
                  document.getElementById("result1").innerHTML = + x;
                  num1 = document.getElementById("cost").value;
                  num2 = document.getElementById("health").value;
                  document.getElementById("result2").innerHTML = (Math.round(num1 * num2 * 100) / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            </script>
    <form action="?a=maintenancedo" target="maintenance" method="post">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
        <input type="hidden" name="cost" id="cost" value="<?php echo $wages; ?>" />
        <input type="range" value="0.1" min="0.1" max="<?php echo number_format($left,1); ?>" class="form-range" id="health" name="health" step="0.1" oninput="myFunction()"><br />
        <input type="submit" name="" value="Fix up your Plane" class="btn btn-info">
    </form>
    <b>Repair: </b> <span id="result1">0</span> %<br />
    <b>Cost: </b> $<span id="result2">0</span>

    <hr /><br /><br />
    <form action="?a=index&u=<?php echo $userid; ?>" target="maintenance" method="post">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
        <input type="submit" name="" value="Nothing" class="btn btn-info">
    </form>
<?php
}











function maintenancedo() {
    global $db;
    $userid = $_POST['userid'];
    $planeID = $_POST['planeID'];
    $amount = $_POST['health'];
    $cost = $_POST['cost'];

$query = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
$data = $db->fetch_row($query);
$total = $amount * $cost;
$health = $data['planeHEALTH'];
$maxhealth = $data['planeMAXHEALTH'];
$healthnow = $health+$amount;
$randMessageCount = rand(1, 4);

if ($randMessageCount == 1) {
    echo 'You repaired <b>'.$amount.'</b>% of your aircraft.<br />
            Checked the wings, tail, and cockpit.<br />
            Lubricated the engines.<br />
            Your aircraft is now at <b>'.$healthnow.'</b>% health.<br /><br />
            The maintenance team charged you <b>'.money_formatter($total).'</b>.';
} else if ($randMessageCount == 2) {
    echo 'You repaired <b>'.$amount.'</b>% of your aircraft.<br />
            Your aircraft underwent maintenance.<br />
            Checked the engines, fuselage, and fuel systems.<br />
            Inspected the avionics and electronics.<br />
            Your aircraft is now at <b>'.$healthnow.'</b>% health.<br /><br />
            The maintenance team charged you <b>'.money_formatter($total).'</b>.';
} else if ($randMessageCount == 3) {
    echo 'You repaired <b>'.$amount.'</b>% of your aircraft.<br />
            You performed maintenance on your aircraft.<br />
            Checked the fuel and oil levels.<br />
            Tested the instruments and controls.<br />
            Your aircraft is now at <b>'.$healthnow.'</b>% health.<br /><br />
            The maintenance team charged you <b>'.money_formatter($total).'</b>.';
} else if ($randMessageCount == 4) {
    echo 'You repaired <b>'.$amount.'</b>% of your aircraft.<br />
            Your aircraft received a thorough checkup.<br />
            Inspected the brakes, tires, and landing gear.<br />
            Tested the flight controls and systems.<br />
            Your aircraft is now at <b>'.$healthnow.'</b>% health.<br /><br />
            The maintenance team charged you <b>'.money_formatter($total).'</b>.';
}

?>

<br /><Br /><br />
<div class="btn-group">
    <form action="?a=index&u=<?php echo $userid; ?>" target="maintenance" method="post">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
        <input type="submit" name="" value="Back to Maintenance" class="btn btn-info">
    </form>
</div>

<?php

    $servicelog1 = time()+3600;
    $servicelog2 = 'General Plane Maintenance. Repairs of '.$amount.'%. To Health '.$healthnow;
    $db->query("UPDATE userairplanes SET servicelog = CONCAT(servicelog, '<br>', '$servicelog1: $servicelog2') WHERE id = $planeID");

$db->query("UPDATE users SET bucks=bucks-$total WHERE userid=$userid");
$db->query("UPDATE userairplanes SET planeHEALTH=planeHEALTH+$amount WHERE id=$planeID");
$db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$total','Repairs: Repaired Plane ID: $planeID by $amount%',unix_timestamp(),'bucks')");
}






function servicelog() {
    global $db; 
        $userid = $_POST['userid'];
        $planeID = $_POST['planeID'];
        $query = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
        $plane = $db->fetch_row($query);
        $planem = $plane['planeID'];
        $gr = $db->query("SELECT * FROM airplanes WHERE planeID=$planem");
        $datas = $db->fetch_row($gr);
        $hperc=($plane['planeHEALTH']/$plane['planeMAXHEALTH']*100);
        $health = 100 - $hperc;
        if($hperc < '30') { $hstyle = 'warning'; } else { $hstyle = 'success'; }
        $totalc+=$plane['flighttime'];
        $totalcoutput = floor($totalc/60/60);
        $loclat = $plane['planeLOCATIONLAT'];
        $loclng = $plane['planeLOCATIONLON']; 
        $getpf = $db->query("SELECT * FROM airports WHERE lat=$loclat");
            while($get=$db->fetch_row($getpf)) {  $currentloc = '<b>Airport</b>: '.$get['name'].', <b>City</b>: '.$get['city']; }
        
    ?>
    <a href='?a=index&u=<?php echo $userid; ?>' target='maintenance'  class='btn btn-info'>Back to Maintenance List</a>
    <table width="100%" border="1">
        <tr>
            <td colspan="2" align="center"><b>Plane ID</b>: <?php echo $planeID; ?></td>
        </tr>
        <tr>
            <td><b><u>Plane Make</u></b>:</td>
            <td><?php echo $datas['planeMAKE']; ?></td>
        </tr>
        <tr>
            <td><b><u>Plane Model</u></b>:</td>
            <td><?php echo $datas['planeMODEL']; ?></td>
        </tr>
        <tr>
            <td><b><u>Total Flights</u></b>:</td>
            <td><?php echo $plane['totalflights']; ?></td>
        </tr>
        <tr>
            <td><b><u>Distance Traveled</u></b>:</td>
            <td><?php echo number_format($plane['planeDISTANCETRAVELLED']); ?> km</td>
        </tr>
        <tr>
            <td><b><u>Total Made</u></b>:</td>
            <td><?php echo money_formatter($plane['planeMONEYMADE']); ?></td>
        </tr>
        <tr>
            <td><b><u>Plane Location</u></b>:</td>
            <td><?php echo $currentloc; ?></td>
        </tr>
        <tr>
            <td><b><u>Plane Health</u></b>:</td>
            <td><b>Health: </b><?php if ($plane['planeHEALTH'] == $plane['planeMAXHEALTH']) {echo round($plane['planeHEALTH']);} else {echo $plane['planeHEALTH'];}; ?>%<br />
                      <div class="progress">
                        <div class="progress-bar bg-<?php echo $hstyle; ?>" style="width:<?php echo $hperc; ?>%">
                        </div>
                        <div class="progress-bar bg-danger" style="width:<?php echo $health; ?>%">
                      </div></div></td>
        </tr>
        <tr>
            <td><b><u>Flight Time</u></b></td>
            <td><?php
                $totalcoutput = $totalc / 3600; // convert total seconds to hours
                $totalcoutput_hours = number_format($totalcoutput, 0); // format hours with no decimal places
                $totalcoutput_minutes = ltrim(gmdate("i", $totalc), '0'); // get minutes and remove leading zeros
                echo $totalcoutput_hours . ' Hours, ' . $totalcoutput_minutes . ' Minutes and ' . gmdate("s \\S\\e\\c\\o\\n\\d\\s", $totalc) . ' Seconds';
                ?>
                </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><b><u>Maintenance Log</u></b>:</td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                    <?php 

                        $servicelog = $plane['servicelog'];
                        $servicelog_arr = array();
                        if (!empty($servicelog)) {
                            $logs = explode('<br>', $servicelog);
                            foreach ($logs as $log) {
                                // Trim each log and split by colon
                                $log_parts = explode(':', trim($log));
                                if (count($log_parts) > 1) {
                                    $timestamp = intval($log_parts[0]);
                                    $description = trim($log_parts[1]);
                                    $date = date('jS \o\f F Y, g:i a', $timestamp);
                                    $servicelog_arr[] = array('DATE' => $date, 'DESCRIPTION' => $description);
                                }
                            }
                        }
                        if (empty($servicelog_arr)) {
                            $servicelog_arr[] = array('No Maintenance to Report');
                        }
                        echo "<table width='80%' border=''1>";
                        foreach ($servicelog_arr as $log) {
                        echo "  <tr>
                                    <td><b><u>DATE</u></b>: {$log['DATE']}</td>
                                    <td><b><u>DESCRIPTION</u></b>: {$log['DESCRIPTION']}</td>
                                </tr>";
                        }
                        echo "</table>";
                        ?>
            </td>
        </tr>
    </table>
    
    
    
    <?php
}












?>
