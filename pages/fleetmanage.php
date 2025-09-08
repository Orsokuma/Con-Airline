<?php
include "../pages/dbconnect.php";

$skipcost = 1;

$conf = [
        'id' => 'PlaneID',
        'planeUname' => 'Nickname',
        'planeACTIVE' => 'Plane Active',
        'planeSPEED' => 'Speed',
        'planeMONEYMADE' => 'Money Made',
        'planeDISTANCETRAVELLED' => 'Distance Travelled',
];
$_GET['u'] = array_key_exists('u', $_GET) && is_numeric($_GET['u']) && (int)$_GET['u'] > 0 ? (int)$_GET['u'] : 0;
$_GET['ord'] = array_key_exists('ord', $_GET) && is_string($_GET['ord']) && array_key_exists(strtolower($_GET['ord']), $conf) ? strtoupper($_GET['ord']) : 'id';
$_GET['ads'] = array_key_exists('ads', $_GET) && is_string($_GET['ads']) && in_array(strtolower($_GET['ads']), ['asc', 'desc']) ? strtoupper($_GET['ads']) : 'ASC';

$userid = $_GET['u'];
$orderby = $_GET['ord'];
$ads = $_GET['ads'];
?>
<script src="https://unpkg.com/jquery@3.6.0/dist/jquery.min.js" integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous" ></script>
<script>
  function getAirportNameICAOByLatLng(lat, lng) {
    for (let item of parent.markers) {
      if (Math.round(1000000000 * item.lat) / 1000000000 == Math.round(1000000000 * lat) / 1000000000 && Math.round(1000000000 * item.lng) / 1000000000 == Math.round(1000000000 * lng) / 1000000000) {
        return [item.name, item.icao];
      }
    }
    return ["HQ", ""];
  }
</script>


<style>
  .no-drop {
    cursor: no-drop;
  }
  .green {
    color: #379e00;
  }
</style>
<?php
$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
    case 'renameaircraft': renameaircraft(); break;
    case 'renameaircraftdo': renameaircraftdo(); break;
    case 'skipflight': skipflight(); break;
    case 'skipflightdo': skipflightdo(); break;
    case 'sellplane': sellplane(); break;
    case 'sellplanedo': sellplanedo(); break;
    case 'fillplane': fillplane(); break;
    case 'fillplanedo': fillplanedo(); break;
    case 'fillallplanes' : fillallplanes(); break;
    case 'fillallplanesdo' : fillallplanesdo(); break;
    case 'servicelog' : servicelog(); break;
    // TODO
    default:index(); break;
}




function index() {
    global $db,$userid,$orderby,$ads,$skipcost,$conf;

    $fet = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($fet);


    $getinfo = $db->query("SELECT * FROM `userairplanes` WHERE planeOWNER='$userid' ORDER BY $orderby $ads");
    $userinfoquery = $db->query("SELECT * FROM `users` WHERE userid='$userid'");
    $userinfo = $db->fetch_row($userinfoquery);
    ?>

    <ul class="nav bg-<?php echo $ir['theme']; ?> fixed-top">
        <body style="width: 100%; margin-top: 45px">
        <li>
            <form action="?a=fillallplanes" target="fleetmanage" method="post">
                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                <input type="submit" name="" value="Refuel All" class="btn btn-success btn-sm">
            </form>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $ir['theme']; ?>"><b>ORDER BY:</b></a>
        </li>
        <?php
        foreach ($conf as $order => $display) {
            $bold = $_GET['ord'] === $order ? ' text-bold' : '';?>
            <li class="nav-item">
                <a class="btn btn-<?php echo $ir['theme'].$bold; ?>" href="?a=index&ord=<?php echo $order; ?>&u=<?php echo $userid; ?>" target="fleetmanage">
                    <?php echo $display; ?>
                </a>
            </li>
            <?php
        } ?>
        <li class="nav-item">
            <a class="btn btn-<?php echo $ir['theme']; ?>">|</a>
        </li>
        <?php
        foreach (['ASC', 'DESC'] as $direction) {
            $bold = $_GET['ads'] === $direction ? ' text-bold' : '';?>
            <li class="nav-item">
                <a class="btn btn-<?php echo $ir['theme'].$bold; ?>" href="?a=index&ord=<?php echo $_GET['ord']; ?>&ads=<?php echo $direction; ?>&u=<?php echo $userid; ?>" target="fleetmanage"><?php echo ucfirst(strtolower($direction)); ?></a>
            </li>
            <?php
        } ?>
        <li class="nav-item">
            <a class="btn btn-<?php echo $ir['theme']; ?>">|</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $ir['theme']; ?>">Current: <b><?php echo $orderby; ?></b> ordered by <b><?php echo $ads; ?></b></a>
        </li>

    </ul>



    <?php
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
            $style="danger";
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
                                <td width="30%"><font color="white"><img src="<?php echo $datas['planeIMAGE']; ?>" width="72px" class="rounded">
                                        <?php if ($data['planeUname'] == 'Not Set') { ?><b><?php echo $datas['planeMAKE'].' - '.$datas['planeMODEL']; ?></b> <small>(ID: <?php echo $id; ?>)</small>
                                        <?php } else { ?>
                                        <b><?php echo $data['planeUname']; ?></b> <small>(ID: <?php echo $id; ?>)</small></font>
                                    <?php } ?>
                                </td>
                                <td width="30%"><font color="white"><?php echo $arrivalnav; ?></font></td>
                                <td width="35%"><font color="white"><b><?php echo $head; ?>: </b><span id="airportName<?php echo $id; ?>"></span></font></td>
                                <td width="5%" valign="middle">
                                    <form onsubmit="return false;">
                                        <input type="hidden" id="planelocationlat<?php echo $id; ?>" value="<?php echo $data['planeLOCATIONLAT']; ?>">
                                        <input type="hidden" id="planelocationlng<?php echo $id; ?>" value="<?php echo $data['planeLOCATIONLON']; ?>">
                                        <input type="submit" name="locateButton" id="locateButton<?php echo $id; ?>" value="Locate" class="btn btn-info">
                                    </form>
                                </td>
                            </tr>
                        </table>

                    </div></a>

                <div id="collapse<?php echo $id; ?>" class="collapse hide" data-bs-parent="#accordion">
                    <div class="card-body">
                        <table width="100%">
                            <tr>
                                <td width="25%"></td>
                                <td width="45%"></td>
                                <td width="10%"></td>
                                <td Width="20%"></td>
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
                                <td><b>Passengers: </b><?php echo $data['planePASSENGERCURRENT']; ?> / <?php echo $data['planePASSENGER']; ?><br />
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-<?php echo $pstyle; ?> progress-bar-striped progress-bar-animated" style="width:<?php echo $pperc; ?>%">
                                        </div>
                                        <div class="progress-bar bg-danger" style="width:<?php echo $passdis; ?>%">
                                        </div></div>
                                    <b>Fuel: </b><?php echo number_format($data['planeFUELCURRENT']); ?>l / <?php echo number_format($data['planeMAXFUEL']); ?>l
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-<?php echo $fstyle; ?> progress-bar-striped progress-bar-animated" style="width:<?php echo $perc; ?>%">
                                        </div>
                                        <div class="progress-bar bg-danger" style="width:<?php echo $fueldis; ?>%">
                                        </div></div>
                                    <b>Weight: </b><?php echo number_format($data['planeCURRENTWEIGHT']); ?>kg / <?php echo number_format($data['planeMAXWEIGHT']); ?>kg<br />
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-<?php echo $wstyle; ?> progress-bar-striped progress-bar-animated" style="width:<?php echo $wperc; ?>%">
                                        </div>
                                        <div class="progress-bar bg-danger" style="width:<?php echo $weightdis; ?>%">
                                        </div></div>


                                    <b>Health: </b><?php if ($data['planeHEALTH'] == $data['planeMAXHEALTH']) {echo round($data['planeHEALTH']);} else {echo $data['planeHEALTH'];}; ?>%<br />
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-<?php echo $hstyle; ?> progress-bar-striped progress-bar-animated" style="width:<?php echo $hperc; ?>%">
                                        </div>
                                        <div class="progress-bar bg-danger" style="width:<?php echo $health; ?>%">
                                        </div></div>
                                </td>
                                <td></td>

                                <td><?php if($data['planeACTIVE'] == 0) { ?>
                                        <div class="btn-group">
                                            <form action="?a=fillplane" target="fleetmanage" method="post">
                                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                                <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                                                <input type="submit" name="" value="Refuel" class="btn btn-info btn-sm">
                                            </form>
                                            <form action="?a=sellplane" target="fleetmanage" method="post">
                                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                                <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                                                <input type="submit" name="" value="Sell" class="btn btn-warning btn-sm">
                                            </form>
                                            <form action="?a=renameaircraft" target="fleetmanage" method="post">
                                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                                <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                                                <input type="submit" name="" value="Set Nickname" class="btn btn-info btn-sm">
                                            </form>
                                        </div>

                                        <div class="btn-group">
                                            <form action="?a=servicelog" target="fleetmanage" method="post">
                                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                                <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                                                <input type="submit" name="" value="Service Log" class="btn btn-info btn-sm">
                                            </form>
                                        </div>
                                    <?php } else { ?><div class="btn-group">
                                        <form><input type="submit" name="" value="Refuel" class="btn btn-danger btn-sm" disabled></form>
                                        <form><input type="submit" name="" value="Sell" class="btn btn-danger btn-sm" disabled></form>
                                        <form action="?a=renameaircraft" target="fleetmanage" method="post">
                                            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                            <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                                            <input type="submit" name="" value="Set Nickname" class="btn btn-info btn-sm">
                                        </form>
                                        </div><div class="btn-group">
                                            <form action="?a=skipflight" target="fleetmanage" method="post">
                                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                                <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                                                <input type="submit" name="" value="Skip - <?php echo $skipcost; ?> Airbucks" class="btn btn-success btn-sm">
                                            </form>
                                            <form action="?a=servicelog" target="fleetmanage" method="post">
                                                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                                                <input type="hidden" name="planeID" value="<?php echo $data['id']; ?>">
                                                <input type="submit" name="" value="Service Log" class="btn btn-info btn-sm">
                                            </form>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>



                    </div>
                </div>
            </div>
        </div>




        <script>
          temp = getAirportNameICAOByLatLng(<?php echo $planeLat; ?>, <?php echo $planeLng; ?>);
          if (temp[0] === "HQ") {
            document.getElementById("airportName<?php echo $id; ?>").innerHTML = "<a onclick='parent.map.flyTo([<?php echo $userinfo['latitude']; ?>, <?php echo $userinfo['latitude']; ?>], 10)'>" + temp[0] + "</a>";
          } else {
            document.getElementById("airportName<?php echo $id; ?>").innerHTML = temp[0] + ", <a onclick='parent.document.getElementById(\"airportSearchbox\").value = \"" + temp[1] + "\"'>" + temp[1] + "</a>";
          }

          if (document.getElementById("countdown<?php echo $id; ?>") !== null) {
            var x<?php echo $id; ?> = setInterval(() => {
              distance<?php echo $id; ?> = (<?php echo ($currenttime) ? $currenttime: 0; ?> * 1000) - new Date().getTime();

              hours<?php echo $id; ?> = Math.floor((distance<?php echo $id; ?> % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
              minutes<?php echo $id; ?> = Math.floor((distance<?php echo $id; ?> % (1000 * 60 * 60)) / (1000 * 60));
              seconds<?php echo $id; ?> = Math.floor((distance<?php echo $id; ?> % (1000 * 60)) / (1000));
              document.getElementById("countdown<?php echo $id; ?>").innerHTML = hours<?php echo $id; ?> + " hours " + minutes<?php echo $id; ?> + " minutes " + seconds<?php echo $id; ?> + " seconds ";

              if (distance<?php echo $id; ?> < 0) {
                clearInterval(x<?php echo $id; ?>);
                document.getElementById("countdown<?php echo $id; ?>").innerHTML = "Coming in to Land";
              }
            }, 1000);
          }

          document.getElementById("locateButton<?php echo $id; ?>").onclick = (e) => {
            flyToLat = document.getElementById("planelocationlat<?php echo $id; ?>").value;
            flyToLng = document.getElementById("planelocationlng<?php echo $id; ?>").value;

            parent.map.flyTo([flyToLat, flyToLng], 10, {
              animate: true,
              duration: 1.5
            });
          };
        </script>
    <?php } ?>

    </div>
    <?php
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
    <a href='?a=index&u=<?php echo $userid; ?>' target='fleetmanage'  class='btn btn-info'>Back to Fleet List</a>
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



                // Working Input. Just needs to go where it has too. Mayde Edited to make the repair options better..
                // $servicelog1 = time();
                // $servicelog2 = 'General Plane Maintenance.';
                // $db->query("UPDATE userairplanes SET servicelog = CONCAT(servicelog, '<br>', '$servicelog1: $servicelog2') WHERE id = $planeID");

                ?>
            </td>
        </tr>
    </table>



    <?php
}






function renameaircraft() {
    global $db;
    $userid = $_POST['userid'];
    $planeID = $_POST['planeID'];

    $query = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
    $plane = $db->fetch_row($query);


    echo '<br /><br />What do with to call this aircraft?
    <form action="?a=renameaircraftdo" target="fleetmanage" method="post">
        <input type="hidden" name="userid" value="'.$userid.'">
        <input type="hidden" name="planeID" value="'.$planeID.'">
        <input type="text" name="name" placeholder="Enter Plane Nickname">
        <input type="submit" name="" value="Rename Aircraft" class="btn btn-success">
    </form>
    <form action="?a=renameaircraftdo" target="fleetmanage" method="post">
        <input type="hidden" name="userid" value="'.$userid.'">
        <input type="hidden" name="planeID" value="'.$planeID.'">
        <input type="hidden" name="name" value="Not Set">
        <input type="submit" name="" value="Remove Nickname" class="btn btn-success">
    </form><br />
            <a href="?a=index&u='.$userid.'" target="fleetmanage" class="btn btn-info">Back</a>
    
    ';
}


function renameaircraftdo() {
    global $db;
    $userid = $_POST['userid'];
    $planeID = $_POST['planeID'];
    $name = $_POST['name'];
    $newname = $db->escape($name);

    if ($name == 'Not Set') {
        echo 'You have Reset your aircraft name back to default.<br />
            <a href="?a=index&u='.$userid.'" target="fleetmanage" class="btn btn-info">Continue</a>';
    } else {
        echo 'You have Renamed your Aircraft to: '.$newname.'<br />
            <a href="?a=index&u='.$userid.'" target="fleetmanage" class="btn btn-info">Continue</a>';
    }



    $db->query("UPDATE userairplanes SET planeUname='$newname' WHERE id=$planeID");
}




function skipflight() {
    global $db,$skipcost;

    $userid = $_POST['userid'];
    $planeID = $_POST['planeID'];
    $skipcostd = $skipcost-1;
    $query2 = $db->query("SELECT * FROM users WHERE userid=$userid");
    $users = $db->fetch_row($query2);
    $airbuc = $users['airbucks'];

    if($airbuc <= $skipcostd) {
        echo 'You do not have enough to skip this flight.<br />
            <a href="?a=index&u='.$userid.'" target="fleetmanage" class="btn btn-info">Continue</a>';
    } else {

        echo 'Click Confirm to Skip flight.<br />
    <form action="?a=skipflightdo" target="fleetmanage" method="post">
        <input type="hidden" name="userid" value="'.$userid.'">
        <input type="hidden" name="planeID" value="'.$planeID.'">
        <input type="submit" name="" value="Confirm - '.$skipcost.' Airbucks" class="btn btn-success">
    </form>
    <br /><br /><a href="?a=index&u='.$userid.'" target="fleetmanage" class="btn btn-info">Cancel</a>';
    }

}





function skipflightdo() {
    global $db,$skipcost;

    $userid = $_POST['userid'];
    $planeID = $_POST['planeID'];

    $query = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
    $userplane = $db->fetch_row($query);
    $selectplane = $userplane['planeID'];

    $query1 = $db->query("SELECT * FROM airplanes WHERE planeID=$selectplane");
    $planes = $db->fetch_row($query1);

    $query2 = $db->query("SELECT * FROM users WHERE userid=$userid");
    $users = $db->fetch_row($query2);
    $airbuc = $users['airbucks'];
    $skipcostd = $skipcost-1;
    if($airbuc <= $skipcostd) {
        echo 'You do not have enough to skip this flight.<br />
            <a href="?a=index&u='.$userid.'" target="fleetmanage">Continue</a>';
    } else {
        $weightout = $db->fetch_row($db->query("SELECT * FROM userairplanes WHERE id=".$planeID))['planeFUELCURRENT']*0.819;

        $db->query("DELETE FROM activeflights WHERE planeID=$planeID");
        $db->query("UPDATE userairplanes SET planeACTIVE=0,planePASSENGERCURRENT=0, planeCURRENTWEIGHT=$weightout WHERE id=$planeID");
        $db->query("UPDATE users SET airbucks=airbucks-$skipcost WHERE userid=$userid");

        $db->query("INSERT INTO money(userid, outin, amount,item,date,type) VALUES ('$userid','out','$skipcost','Skipped Flight ID: <b>$planeID</b>',unix_timestamp(),'airbucks')");

        echo 'Skipped flight for '.$skipcost.' airbucks.<br />
            <a href="?a=index&u='.$userid.'" target="fleetmanage" class="btn btn-info">Continue</a>';
    }

}











function sellplane() {
    global $db;
    $userid = $_POST['userid'];
    $planeID = $_POST['planeID'];
    $query = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
    $userplane = $db->fetch_row($query);
    $selectplane = $userplane['planeID'];
    $query1 = $db->query("SELECT * FROM airplanes WHERE planeID=$selectplane");
    $planes = $db->fetch_row($query1);
    $cost = $planes['planeCOST'];
    $payout = $cost/100*75;
    ?>
    <table width="100%" border="1" align="center">
        <tr>
            <td colspan="3"><h4>Selling plane</h4></td>
        </tr>

        <tr>
            <td colspan="3">You are now selling <b><?php echo $planes['planeMAKE']; ?> - <?php echo $planes['planeMODEL']; ?> <small>(ID <?php echo $planeID; ?>)</small></b> for <b><?php echo money_formatter($payout); ?></b></td>
        </tr>
        <tr>
            <td colspan="3">Click Sell to Confirm and Sell.<br />
                            You will only get 75% back on the value of the aircraft.<Br /></td>
        </tr>
        <tr>
            <td colspan="3">
                <form action="?a=sellplanedo" target="fleetmanage" method="post">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <input type="hidden" name="planeID" value="<?php echo $planeID; ?>">
                    <input type="submit" name="" value="Sell" class="btn btn-info">
                </form>

            </td>
        </tr>
    </table>
    <?php
}


function sellplanedo() {
    global $db;
    $userid = $_POST['userid'];
    $planeID = $_POST['planeID'];

    $query = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
    $userplane = $db->fetch_row($query);
    $selectplane = $userplane['planeID'];

    $query1 = $db->query("SELECT * FROM airplanes WHERE planeID=$selectplane");
    $planes = $db->fetch_row($query1);
    $cost = $planes['planeCOST'];

    $payout = $cost/100*75;

    $db->query("DELETE FROM `userairplanes` WHERE id=$planeID");
    $db->query("DELETE FROM `activeflights` WHERE planeID=$planeID");
    $db->query("UPDATE users SET bucks=bucks+$payout WHERE userid=$userid");
    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$payout','Sold Plane: ID: $planeID',unix_timestamp(),'bucks')"); ?>
    You have sold the plane for <?php echo money_formatter($payout); ?>.<br /><br />
    <a href="?a=index&u=<?php echo $userid; ?>" target="fleetmanage" class="btn btn-info">Continue</a>
    <?php
}






function fillplane()
{
    global $db;
    $userid = isset($_POST['userid']) ? $_POST['userid'] : 0;
    $planeID = isset($_POST['planeID']) ? $_POST['planeID'] : 0;
    $pla = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
    $planeinfo = $db->fetch_row($pla);
    $use = $db->query("SELECT * FROM users WHERE userid=$userid");
    $userinfo = $db->fetch_row($use);
    $fuel = $userinfo['fuelstorage'];
    if($fuel == 0) {
        die("You don't have any fuel yet. Go buy some.
<a href='?a=index&u=".$userid."' target='fleetmanage' class='btn btn-info'>Continue</a>
");
    }
    $planefuelmax = $planeinfo['planeMAXFUEL'];
    $planefuelcurrent = $planeinfo['planeFUELCURRENT'];

    if ($planefuelcurrent >= $planefuelmax) {
        die("
This Aircraft already has fuel in it.<br />
<a href='?a=index&u=".$userid."' target='fleetmanage' class='btn btn-info'>Continue</a>
");
    }
    $planefuelrefill = $planefuelmax-$planefuelcurrent; // the difference
    ?>
    <a href="?a=index&u=<?php echo $userid; ?>" target="fleetmanage" class="btn btn-danger">Back</a><br />
    This Aircraft currently has <b><?php echo number_format($planefuelcurrent); ?></b> litres of Fuel onboard.<br />
    The Maximum load this Aircraft can hold is <b><?php echo number_format($planefuelmax); ?></b> litres.<br />
    <Br />
    You currently have <b><?php echo number_format($fuel); ?></b> litres of fuel available in your storage.
    <br />
    How much fuel do you want too put into this plane?<br />



    <form action="?a=fillplanedo" target="fleetmanage" method="post">
        <table width="100%">
            <tr>
                <td width="100%">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <input type="hidden" name="planeID" value="<?php echo $planeID; ?>">
                    <input type="range" value="1" min="1" max="<?php echo $planefuelrefill; ?>" class="form-range" id="fuel" name="fuel" oninput="this.nextElementSibling.value = this.value"><output>1</output> litres</td>

            </tr>
        </table>
        <input type="submit" name="" value="Fill up Plane" class="btn btn-info">
    </form>
    <?php
}



function fillplanedo()
{
    global $db;
    $userid = isset($_POST['userid']) ? $_POST['userid'] : 0;
    $planeID = isset($_POST['planeID']) ? $_POST['planeID'] : 0;
    $fuel = isset($_POST['fuel']) ? $_POST['fuel'] :0;
    $pla = $db->query("SELECT * FROM userairplanes WHERE id=$planeID");
    $planeinfo = $db->fetch_row($pla);
    $use = $db->query("SELECT * FROM users WHERE userid=$userid");
    $userinfo = $db->fetch_row($use);
    $fuelstorage = $userinfo['fuelstorage'];
    $planeFUEL = $planeinfo['planeMAXFUEL'];
    $planeFUELCURRENT = $planeinfo['planeFUELCURRENT'];
    if ($fuel > $fuelstorage) {
        die ("You don't have enough fuel to refill this aircraft.<br />
<a href='?a=index&u=".$userid."' target='fleetmanage' class='btn btn-info'>Continue</a>");
    }
    if($planeFUELCURRENT >= $planeFUEL) {
        die("
This Aircraft already has fuel in it.<br />
<a href='?a=index&u=".$userid."' target='fleetmanage' class='btn btn-info'>Continue</a>
");
    }
    else { ?>
        You filled the plane with <?php echo number_format($fuel); ?> litres of fuel.<br />
        <a href='?a=index&u=<?php echo $userid; ?>' target='fleetmanage' class='btn btn-info'>Continue</a>

        <br />

        <?php

//                             weight (kg) = volume (l) * 0.8
//                       1,466*0.8 = 1,172
//                       1,466*0.819 = 1,200
        $weightout = $planeFUEL*0.8;
        $db->query("UPDATE users SET fuelstorage=fuelstorage-$fuel WHERE userid=$userid");
        $db->query("UPDATE userairplanes SET planeFUELCURRENT=planeFUELCURRENT+$fuel, planeCURRENTWEIGHT=planeCURRENTWEIGHT+'$weightout' WHERE id=$planeID");
    }
}

function fillallplanes() {
    global $db;
    $userid = isset($_POST['userid']) ? $_POST['userid'] : 0;
    // Temp die the page
    /*die("
    This Page is currently work in progress.<br />
    <a href='?a=index&u=".$userid."' target='fleetmanage'>Continue</a>");*/

    $u = $db->query("SELECT * FROM users WHERE userid=$userid");
    $user = $db->fetch_row($u);
    $userPlanes = $db->query("SELECT * FROM userairplanes WHERE planeOWNER=$userid");

    // Check if user has enough fuel to fill all planes
    $userFuelAvail = $user['fuelstorage'];
    $totalToFuel = 0;

    while($plane = $db->fetch_row($userPlanes)) {
        if ($plane['planeACTIVE'] == '0') {
            if ($plane['planeFUELCURRENT'] < $plane['planeMAXFUEL']) {
                $totalToFuel += ($plane['planeMAXFUEL'] - $plane['planeFUELCURRENT']);
            }
        }
    }

    if ($totalToFuel > $userFuelAvail) {
        die ("You don't have enough fuel to refill all aircraft.<br />
        <a href='?a=index&u=".$userid."' target='fleetmanage' class='btn btn-info'>Continue</a>");
    }

    if ($totalToFuel == '0') {
        die ("All aircraft are already refueled.<br />
        <a href='?a=index&u=".$userid."' target='fleetmanage' class='btn btn-info'>Continue</a>");
    }

    // Make sure that user really wants to refuel all aircraft
    ?>
    <a href="?a=index&u=<?php echo $userid; ?>" target="fleetmanage" class="btn btn-danger">Back</a><br />
    Are you sure that you want to refuel all aircraft?
    This will remove <?php echo number_format($totalToFuel);?> litres of fuel from your fuel storage.

    <form action="?a=fillallplanesdo" target="fleetmanage" method="post">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <input type="submit" name="" value="Confirm" class="btn btn-info">
    </form>
    <?php
}



function fillallplanesdo() {
    global $db;
    $userid = isset($_POST['userid']) ? $_POST['userid'] : 0;

    // Temp die the page
    /*die("
    This Page is currently work in progress.<br />
    <a href='?a=index&u=".$userid."' target='fleetmanage'>Continue</a>");*/

    $u = $db->query("SELECT * FROM users WHERE userid=$userid");
    $user = $db->fetch_row($u);
    $userPlanes = $db->query("SELECT * FROM userairplanes WHERE planeOWNER=$userid");

    // Double-check if user has enough fuel to fill all planes
    $userFuelAvail = $user['fuelstorage'];
    $totalToFuel = 0;

    while($plane = $db->fetch_row($userPlanes)) {
        if ($plane['planeACTIVE'] == '0') {
            if ($plane['planeFUELCURRENT'] < $plane['planeMAXFUEL']) {
                $planeID = $plane['id'];
                $planeFuel = $plane['planeMAXFUEL'] - $plane['planeFUELCURRENT'];
                $weightout = $planeFuel*0.8;

                // Update plane
                $db->query("UPDATE userairplanes SET planeFUELCURRENT=planeFUELCURRENT+$planeFuel, planeCURRENTWEIGHT=planeCURRENTWEIGHT+$weightout WHERE planeOWNER=$userid AND id=$planeID");

                // Increase total to remove from user
                $totalToFuel += ($plane['planeMAXFUEL'] - $plane['planeFUELCURRENT']);
            }
        }
    }

    $db->query("UPDATE users SET fuelstorage=fuelstorage-$totalToFuel WHERE userid=$userid");

    ?>You refueled all aircraft. <?php echo number_format($totalToFuel); ?> litres of fuel were removed from your storage.<br />
<a href='?a=index&u=<?php echo $userid; ?>' target='fleetmanage'  class='btn btn-info'>Continue</a><?php
}



?>
