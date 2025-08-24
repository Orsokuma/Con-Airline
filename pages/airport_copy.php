<script>
    function calcDistFromLatLong(lat1, lng1, lat2, lng2) {
        return Math.sqrt(((lat2 - lat1) * (lat2 - lat1)) + ((lng2 - lng1) * (lng2 - lng1))) * 111.139;
    }
    
    function vectorFromLatLong(startlat, startlng, endlat, endlng) {
        return [(endlat - startlat) / 180, (endlng - startlng) / 180];
    }
</script>

<?php
include "../pages/dbconnect.php";

$user = isset($_GET['u']) ? $_GET['u'] : 0;
$lat = isset($_GET['lat']) ? $_GET['lat'] : 0;
$lng = isset($_GET['lng']) ? $_GET['lng'] : 0;

$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'setdeparture': setdeparture(); break;
case 'setarrival': setarrival(); break;
case 'setarrival': setarrival(); break;
default:index(); break;
}


function index() {
    
global $db;
?><table border="1" width="100%">

<tr>
    <td><b>Plane</b></td>
    <td><b>Information</b></td>
    <td><b>Options</b></td>
</tr>
<tr>
    <td colspan="5"><hr /></td>
</tr>

<?php
$lat = isset($_GET['lat']) ? $_GET['lat'] : 0;
$lng = isset($_GET['lng']) ? $_GET['lng'] : 0;

$user = isset($_GET['u']) ? $_GET['u'] : 0;
$getinfo = $db->query("SELECT * FROM userairplanes WHERE planeACTIVE=0 AND planeOWNER=$user");
while($data=$db->fetch_row($getinfo)) {
            $planeID = $data['planeID'];
            $gr = $db->query("SELECT * FROM airplanes WHERE planeID=$planeID");
            $datas = $db->fetch_row($gr);
            $userid = $data['planeOWNER'];
            
            
        ?>
        
            <tr valign="middle" width="100%" id="<?php echo $data['id']; ?>">
                <td width="30%"><img src="<?php echo $datas['planeIMAGE']; ?>" width="100px"><br />
                <?php
                    if ($data['planeUname'] == 'Not Set') { ?>
                    <b><?php echo $datas['planeMAKE'].' - '.$datas['planeMODEL']; ?></b> <br /><small>(ID: <?php echo $data['id']; ?>)</small><br />
                    <?php } else { ?>
                    <b><?php echo $data['planeUname']; ?></b> <br /><small>(ID: <?php echo $data['id']; ?>)</small>
                    <?php } ?>
                
                
                </td>
                <td width="20%"><?php echo number_format($data['planePASSENGERCURRENT']); ?> / <?php echo number_format($data['planePASSENGER']); ?> PAX<br />
                    <?php echo number_format($data['planeFUELCURRENT']); ?>l / <?php echo number_format($data['planeMAXFUEL']); ?>l<br />
                    <?php echo number_format($data['planeCURRENTWEIGHT']); ?>kg / <?php echo number_format($data['planeMAXWEIGHT']); ?>kg</td>
                <td width="40%">
                
                 <table width="100%">
                    <tr>
                        <td width="70%">
                            <form action="?a=setdeparture" target="airplane" method="post">
                            <input type="hidden" name="startLat" value="<?php echo $lat ?>">
                            <input type="hidden" name="startLng" value="<?php echo $lng ?>">
                            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                            <input type="hidden" name="planeID" class="planeID" value="<?php echo $data['id']; ?>">
                            <input type="hidden" name="dataplaneID" class="dataplaneID" value="<?php echo $planeID; ?>">
                            <input type="hidden" name="planePosLat" class="planePosLat" value="<?php echo $data['planeLOCATIONLAT']; ?>">
                            <input type="hidden" name="planePosLng" class="planePosLng" value="<?php echo $data['planeLOCATIONLON']; ?>">
                            <input type="hidden" name="planeMaxDistance" class="planeMaxDistance" value="<?php echo $data['planeMAXDISTANCE']; ?>">
                            <input type="hidden" name="planeCONSUMPTIONRATE" class="planeCONSUMPTIONRATE" value="<?php echo $datas['planeCONSUMPTIONRATE']; ?>">
                            <input type="hidden" name="departAirport" class="departAirport" value="Error">
                            <input type="hidden" name="arriveAirport" class="arriveAirport" value="Error">
                            <input type="hidden" name="flightDistance" value="0" class="flightDistance">
                            <b>Depart from:</b> <span class="departAirportShow"></span> <br />
                            <b>Distance:</b> <span class="flightDistanceShow"></span>/<?php echo number_format($data['planeMAXDISTANCE']) ?>km
                        </td>
                        <td width="30%"><input type="submit" name="" value="Depart" class="btn btn-info depart-button"></form></td>
                    </tr>
                </table>
                
                
                
                </td>

            </tr>
            
    <?php  }  ?> 
    </table>
    <?php
}




function setdeparture() {
    global $db;
    
    
    $userid = $_POST['userid'];
    $plane = $_POST['planeID'];
    $dataplane = $_POST['dataplaneID'];
    $airport = explode("; ", $_POST['airportSelect']);
    $lat = $_POST['startLat'];
    $lng = $_POST['startLng'];
    $distance = $_POST['flightDistance'];
    $maxDistance = $_POST['planeMaxDistance'];
    $departAirport = $_POST['departAirport'];
    $arriveAirport = $_POST['arriveAirport'];
    $planeLat = $_POST['planePosLat'];
    $planeLng = $_POST['planePosLng'];
    $consumptionr = $_POST['planeCONSUMPTIONRATE'];
    
    $peep = $db->query("SELECT * FROM userairplanes WHERE id=$plane");
    $get = $db->fetch_row($peep);
    
    $r = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($r);
    $flight_time = round($distance / $get['planeSPEED'] * 3600);
    
    $airplaneDataQuery = $db->query("SELECT * FROM airplanes WHERE planeID=".$dataplane);
    $airplaneData = $db->fetch_row($airplaneDataQuery);
    $currentfuel = $get['planeFUELCURRENT'];
    
    $fuel = $consumptionr * $flight_time / 3600; // calculate fuel usage
    
    if($currentfuel < $fuel) {
        die("You don't have enough fuel to make this trip!");        
    }
    
    if ($get['planeHEALTH'] < 25) {
        die("Your plane needs maintenance before it can fly!");
    }

    if ($distance > 0 && $distance <= $maxDistance) {
        $maxpassenger = $get['planePASSENGER'];
        $random = rand(0.3 * $ir['reputation'],$ir['reputation']);
        $emptyWeight = $get['planeCURRENTWEIGHT'];
        $ticket = $ir['tickets1'];
        $passenger = $maxpassenger*($random/100); //Calc the amount of passengers
        $totalWeight = $emptyWeight + ($passenger * 75); // calc the total weight
        $leftoverWeight = max($get['planeMAXWEIGHT'] - $totalWeight, 0);
        $freightWeight = round(rand(0.4 * $leftoverWeight, $leftoverWeight));
        $freightPayout = $freightWeight * 40;
        $totalWeight = $totalWeight + $freightWeight;
        
       // $payout = $passenger * $ticket * max(round(floor($distance / 100) * 1.05), 1) + $freightPayout; // payout
        
        if ($ir['premiumdays'] >= 1) {
            $payout = $passenger * $ticket * 0.005 * max(round(floor($distance / 100) * 1.05), 1) + $freightPayout; // payout
        } else {
            $payout = $passenger * $ticket * max(round(floor($distance / 100) * 1.05), 1) + $freightPayout; // payout
        }
        
        $passengers = round($passenger);
        $reputation = ($passengers / 4321) * (100 / $ticket);
        $alliancePayoutReductionPerc = $ir['allianceperc'];
        $alliancePayoutReduction = ($alliancePayoutReductionPerc / 100) * $payout;
        $payout = $payout - $alliancePayoutReduction;
        if ($ir['reputation'] + $reputation > 100) {
            $reputation = 100 - $ir['reputation'];
        }

        // Once the Table field in users is filled up, no more flights can be added. So somehow we need to remove the first instant placed.
        $newCost = $ir['reputation'];
        $currentCostPoints = $ir['reputationa'];
        $currentCostPointsArray = explode(",", $currentCostPoints);
        if (count($currentCostPointsArray) >= 50) {
            $currentCostPoints = $currentCostPointsArray[count($currentCostPointsArray) - 50];
            for ($i = count($currentCostPointsArray) - 49; $i < count($currentCostPointsArray) - 1; $i++) {
                $currentCostPoints = $newCurrentCostPoints.",".$currentCostPointsArray[$i];
            }
            $currentCostPoints = $currentCostPoints.",".$newCost;
        } else {
                $currentCostPoints = $currentCostPoints.",".$newCost;
        }
            

        $airlinecolor = $ir['airlinecolour'];
        $planeImage = $airplaneData['planeIMAGE'];
        $speed = $airplaneData['planeSPEED'];
        $newPlaneHealth = $get['planeHEALTH'] - (0.1 * max($distance / 100, 1));
        $newPlaneDistancetravelled = $get['planeDISTANCETRAVELLED'] + $distance;
        $newMaxPlaneHealth = $get['planeMAXHEALTH'] - floor(($newPlaneDistanceTravelled / 100000) * 0.5);
        
        if ($airplane['planeUname'] == 'Not Set') {
            $flightname = $airplaneData['planeMAKE'];
        } else {
            $flightname = $airplane['planeUname'];
        }
        $flightendt = $activeRow['flightEndTime']+3600;
        $flightend = date("h:i A",$flightendt);
        ?><script>
            Math.radians = function(degrees) {
            	return degrees * Math.PI / 180;
            }
            
            Math.degrees = function(radians) {
                return radians * 180 / Math.PI;
            }
        
            myAnimationIcon = parent.L.icon({
                iconUrl: '<?php echo $planeImage; ?>',
                iconRetinaUrl: '<?php echo $planeImage; ?>',
                iconSize: [50, 50],
                iconAnchor: [20, 20],
            });
        
            speed = <?php echo $speed; ?>/3.6;
            flighttime = <?php echo $flight_time; ?>;
            endtime = (new Date().getTime() / 1000) + flighttime;
            
            startLat = <?php echo $planeLat; ?>;
            startLng = <?php echo $planeLng; ?>;
            endLat = <?php echo $lat; ?>;
            endLng = <?php echo $lng; ?>;
            
            
            
            
            aircraftVector = vectorFromLatLong(startLat, startLng, endLat, endLng);
            
            aircraftPoints = [];
            aircraftPoints.push([startLat, startLng]);
            currentDLatLng = [startLat, startLng];
            
            let R = 6378137;
            
            // m/(111111 * cos(radians(origin_latitude)))
            
            for (let i = 0; i < flighttime; i += 5) {
                dLat = aircraftVector[0] * (speed / 11111.111);
                dLng = aircraftVector[1] * (speed / (111111.111 * Math.cos(Math.radians(currentDLatLng[1]))));
                
                currentDLatLng[0] += dLat;
                currentDLatLng[1] += dLng;
                aircraftPoints.push(Object.assign([], currentDLatLng));
                
            }
            
            console.log(aircraftPoints);
            
            
            
            
            line = parent.L.polyline(aircraftPoints, {color: '<?php echo $ir['airlinecolour']; ?>'}).addTo(parent.map);//parent.L.polyline([[startLat, startLng], [endLat, endLng]], {color: '<?php echo $ir['airlinecolour']; ?>'}).addTo(parent.map);
            line.setText('           \u27A4 <?php echo $flightname; ?> (ETA: <?php echo $flightend; ?>) \u27A4', {
                repeat: true,
                offset: 12,
                attributes: {fill: 'black'}});
            
            parent.aircraftMarkers.push([parent.L.marker([startLat, startLng], {icon: myAnimationIcon,}).addTo(parent.map), parent.vectorFromLatLong(startLat, startLng, endLat, endLng), speed, line, endtime, <?php echo $plane; ?>]);
            
        </script><?php
    } else {
        $payout = 0;
        $passengers = 0;
    }
    $fuels = $consumptionr * $flight_time / 3600; // calculate fuel usage

    ?>
    <br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <center>
        <table width="75%">
            <tr>
                <td><b>PAX:</b></td>
                <td><?php echo $passengers; ?> <br /></td>
            </tr>
            <tr>
                <td><b>Cargo:</b></td>
                <td><?php echo number_format($freightWeight); ?>kg</td>
            </tr>
            <tr>
                <td><b>Profit:</b></td>
                <td><?php echo money_formatter($payout); ?></td>
            </tr>
            <tr>
                <td><b>Alliance Payout:</b></td>
                <td><?php echo money_formatter($alliancePayoutReduction)?></td>
            </tr>
            <tr>
                <td><b>Reputation</b></td>
                <td>+<?php echo number_format($reputation,5); ?></td>
            </tr>
            <tr>
                <td><b>Departure Airport:</b></td>
                <td><?php echo $departAirport; ?></td>
            </tr>
            <tr>
                <td><b>Arrival Airport:</b></td>
                <td><?php echo $arriveAirport; ?></td>
            </tr>
            <tr>
                <td><b>Fuel Usage:</b></td>
                <td><?php echo number_format($fuels); ?>l</td>
            </tr>
            <tr>
                <td><b>Distance:</b></td>
                <td><?php echo number_format($distance); ?>km</td>
            </tr>
            <tr>
                <td><b>Flight time:</b></td>
                <td><?php echo date("H:i:s", $flight_time); ?></td>
            </tr>
        </table>
    </center>
    <?php    
    
    
    
    
    $allianceID = $ir['alliance'];
    $alliancePayoutReductionFormatted = money_formatter($alliancePayoutReduction);

    if ($distance > 0 && $distance <= $maxDistance) {
        $newDepartAirportName = $db->escape($departAirport);
        $newArriveAirportName = $db->escape($arriveAirport);
        $db->query("UPDATE userairplanes SET planeACTIVE=1, planeLOCATIONLAT=$lat, planeLOCATIONLON=$lng,planeCURRENTWEIGHT=$totalWeight,planePASSENGERCURRENT=$passengers,planeFUELCURRENT=planeFUELCURRENT-$fuel,planeDISTANCETRAVELLED=$newPlaneDistancetravelled, planeMONEYMADE=planeMONEYMADE+$payout, planeHEALTH=$newPlaneHealth, planeMAXHEALTH=$newMaxPlaneHealth WHERE id=$plane");
        $db->query("UPDATE users SET bucks=bucks+$payout, totalmoney=totalmoney+$payout, totaldistance=totaldistance+$newPlaneDistancetravelled, reputation=reputation+$reputation, reputationa='$currentCostPoints' WHERE userid=$userid"); // pay the cash and Rep Gain
        $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$payout','<b>Plane:</b> $plane, <b>PAX:</b> $passengers, <b>Departure:</b> $newDepartAirportName, <b>Arrival:</b> $newArriveAirportName , <b>Alliance Cut:</b> $alliancePayoutReductionFormatted ',unix_timestamp(),'bucks')");
        $db->query("INSERT INTO `alliancemoney`(`userid`, `outin`, `amount`,`item`,`date`,`type`, `allianceID`) VALUES ('$userid','in','$alliancePayoutReduction','Flight Payment',unix_timestamp(),'bucks', '$allianceID')");
        $db->query("INSERT INTO `activeflights`(`id`, `planeOWNER`, `startLat`, `startLon`, `endLat`, `endLon`, `flightStartTime`, `flightEndTime`, `planeID`) VALUES ('','$userid','$planeLat','$planeLng','$lat','$lng',unix_timestamp(),unix_timestamp()+$flight_time,'$plane')");
        $db->query("UPDATE alliance SET allianceMONEY=allianceMONEY+$alliancePayoutReduction WHERE allianceID=$allianceID");
    }

}











$lat = isset($_GET['lat']) ? $_GET['lat'] : 0;
$lng = isset($_GET['lng']) ? $_GET['lng'] : 0;


?>

<script>
    function getAirportByLatLong(lat, lng) {
        for (let airport of parent.markers) {
            if (Math.round(1000000000 * airport.lat) / 1000000000 == Math.round(1000000000 * lat) / 1000000000 && Math.round(1000000000 * airport.lng) / 1000000000 == Math.round(1000000000 * lng) / 1000000000) return airport;
        }
        return {"name": "HQ"};
    }

    let flightDistances = document.getElementsByClassName("flightDistance");
    for (let selector in flightDistances) {
        flightDistance = flightDistances[selector];
        flightDistance.value = Math.round(10 * calcDistFromLatLong(<?php echo $lat; ?>, <?php echo $lng; ?>, document.getElementsByClassName("planePosLat")[selector].value, document.getElementsByClassName("planePosLng")[selector].value)) / 10;
        
        document.getElementsByClassName("departAirport")[selector].value = getAirportByLatLong(document.getElementsByClassName("planePosLat")[selector].value, document.getElementsByClassName("planePosLng")[selector].value).name;
        document.getElementsByClassName("arriveAirport")[selector].value = getAirportByLatLong(<?php echo $lat; ?>, <?php echo $lng; ?>).name;
        
        document.getElementsByClassName("departAirportShow")[selector].innerHTML = document.getElementsByClassName("departAirport")[selector].value;
        document.getElementsByClassName("flightDistanceShow")[selector].innerHTML = new Intl.NumberFormat('en-UK').format(flightDistance.value) + "km";
        
        if (flightDistance.value <= 0.5 || flightDistance.value > parseInt(document.getElementsByClassName("planeMaxDistance")[selector].value)) {
            document.getElementsByClassName("depart-button")[selector].classList.remove("btn-info");
            document.getElementsByClassName("depart-button")[selector].classList.add("btn-danger");
            document.getElementsByClassName("depart-button")[selector].disabled = true;
        }
        
        if (flightDistance.value > parseInt(document.getElementsByClassName("planeMaxDistance")[selector].value)) {
            document.getElementsByClassName("flightDistanceShow")[selector].style = "color: red; font-weight: bold";
        }
    }
</script>










