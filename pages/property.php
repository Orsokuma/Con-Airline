<?php include "../pages/dbconnect.php";




$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {

case 'place': place(); break;
case 'placedo': placedo(); break;
case 'upgrade': upgrade(); break;
case 'upgradedo': upgradedo(); break;
default:index(); break;
}


function index() {
global $db;
$userid = isset($_GET['u']) ? $_GET['u'] : 0;
$get = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($get);
    if($ir['airlinehq'] == '0') { ?>
          <table width="100%" border="1">
              <tr>
                  <td>Property</td>
                  <td>Cost</td>
                  <td>Options</td>
              </tr>
              <tr>
                  <td colspan="3"><hr /></td>
              </tr>
              <tr>
                  <td><b>Headquarters</b></td>
                  <td><span class="bucks">$1,000,000</span></td>
                  <td><form action="?a=place" method="post" target="properties">
                        <input type="hidden" name="u" value="<?php echo $ir['userid']; ?>">
                        <input type="hidden" name="cost" value="1000000">
                        <input type="submit" name="submit" value="Build" class="btn btn-info">
                    </form>
                      
                      
                </td>
                
              </tr>
          </table>
        <?php } else { 
        $rep = number_format($ir['reputation'],5);
        $level = $ir['level'];
        $reps = [
            1 => '35.00000',
            2 => '40.00000',
            3 => '45.00000',
            4 => '50.00000',
            5 => '55.00000',
            6 => '60.00000',
            7 => '65.00000',
            8 => '70.00000',
            9 => '75.00000',
            10 => '80.00000'
        ];
        $upgrade = '';
        foreach ($reps as $repLevel => $repValue) {
            if ($rep >= $repValue && $level == $repLevel && $ir['reputation'] >= $reps[$repLevel + 1]) {
                $upgrade = " - <a href='?a=upgrade&up={$repLevel}&u={$ir['userid']}'><small><b>UPGRADE</b></small></a>";
                break;
            }
        }

        ?>
            <form action="?a=index&u=<?php echo $ir['userid']; ?>" method="post" target="properties">
                        <input type="hidden" name="u" value="<?php echo $ir['userid']; ?>">
                        <input type="submit" name="submit" value="Reload" class="btn btn-info">
                    </form>
            <table width="100%" border="1">
                <tr>
                    <td><b><u>HQ Level</u></b>:</td>
                    <td><?php echo $level.$upgrade; ?></td>
                </tr>
                <tr>
                    <td><b><u>Company Rep</u></b>:</td>
                    <td><?php echo $rep; ?></td>
                </tr>
                <tr>
                    <td><b><u>Ticket Price</u></b>:</td>
                    <td><?php echo money_formatter($ir['tickets1']); ?></td>
                </tr>
            </table>
            
            <hr />
            
            <table width="100%" border="1">
              <tr>
                  <td>Property</td>
                  <td>Cost</td>
                  <td>Options</td>
              </tr>
              <tr>
                  <td colspan="3"><hr /></td>
              </tr>
              <tr>
                  <td><b>Headquarters</b></td>
                  <td><span class="bucks">$5,000,000</span></td>
                  <td><form action="?a=place" method="post" target="properties">
                        <input type="hidden" name="u" value="<?php echo $ir['userid']; ?>">
                        <input type="hidden" name="cost" value="5000000">
                        <input type="submit" name="submit" value="Re Locate" class="btn btn-info">
                    </form>
                </td>
                
              </tr>
          </table>
        <?php }
}
        


function upgrade() {
    global $db;
    $level = $_GET['up'];
    $userid = $_GET['u'];
    $newlevel = $level + 1;
    $cost = 10000000;
    $costs = $level * $cost;
    echo "Upgrade to Level $newlevel?<br />
          This will cost you ".money_formatter($costs)."<br /><br />
          <a href='?a=upgradedo&do=$newlevel&u=$userid' class='btn btn-info'>Upgrade</a>";
}

      
function upgradedo() {
    global $db;
    $level = $_GET['do'];
    $userid = $_GET['u'];
    $userid = isset($_GET['u']) ? $_GET['u'] : 0;
    $get = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($get);
    $reps = [
        1 => '35.00000',
        2 => '40.00000',
        3 => '45.00000',
        4 => '50.00000',
        5 => '55.00000',
        6 => '60.00000',
        7 => '65.00000',
        8 => '70.00000',
        9 => '75.00000',
        10 => '80.00000'
    ];
    $costfee = $level-1;
    $cost = $costfee * 10000000;
    if ($level > 10) {
        die("Invalid upgrade level.<br />
        <a href='?a=index&u=$userid' class='btn btn-info'>Continue</a>");
    }
    if ($ir['bucks'] <= $cost) {
        die("You don't have enough money to upgrade.<br />
        <a href='?a=index&u=$userid' class='btn btn-info'>Continue</a>");
        return;
    }
    if ($ir['reputation'] < $reps[$level]) {
        die("You don't have enough reputation to upgrade to level $level<br />
        <a href='?a=index&u=$userid' class='btn btn-info'>Continue</a>");
        return;
    }
    $db->query("UPDATE users SET level=$level, bucks=bucks-$cost WHERE userid=$userid");
    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$cost','Upgraded HQ. Level: <b>$level</b>',unix_timestamp(),'bucks')");
    echo "Successfully upgraded to level $level!<br />
    You were charged: ".money_formatter($cost)."
    <a href='?a=index&u=$userid' class='btn btn-info'>Continue</a>";
}

        
function place() {
    global $db;
$userid = isset($_POST['u']) ? $_POST['u'] : 0;
$get = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($get);
$cost = $_POST['cost'];
echo "<a href='property.php?u=".$ir['userid']."' target='properties' class='btn btn-info'>Back</a>";

?>
        <table>
            <tr>
                <td>Click on the Map on where you wish to place your HQ.<br />
                Once you see the Marker where you wish to place the HQ then you can click "Build".<br />
                      
                    <form action="?a=placedo" method="post" target="properties">
                        <input type="hidden" name="latitude" id="hqLatInput" placeholder="latitude" value="0">
                        <input type="hidden" name="longitude" id="hqLngInput" placeholder="longitude" value="0">
                        <input type="hidden" name="user" value="<?php echo $ir['userid']; ?>">
                        <input type="hidden" name="cost" value="<?php echo $cost; ?>">
                        <input type="submit" name="submit" value="Build on Marker" class="btn btn-success">
                    </form>
                      
                      
                </td>
            </tr>
        </table>
          
        <script>
            parent.allowMarkerMovement = true;
            parent.document.getElementById("hqTempLatInput").onchange = (e) => {
                console.log(e.target.value);
                document.getElementById("hqLatInput").value = e.target.value;
            };
            parent.document.getElementById("hqTempLngInput").onchange = (e) => {
                console.log(e.target.value);
                document.getElementById("hqLngInput").value = e.target.value;
            };
        </script><?php
}



function placedo() {
    global $db;
$userid = isset($_POST['user']) ? $_POST['user'] : 0;
$get = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($get);

    if(isset($_POST['submit'])){
        $cost = $_POST['cost'];
        if($ir['bucks'] < $cost) {
            echo 'You dont have enough money for this.';
        } else {
        ?>
            <script>
                parent.allowMarkerMovement = false;
                //parent.document.hqMarker = null;
                var myHQIcon = parent.L.icon({
                    iconUrl: '<?php echo $ir['airlineimage'] ;?>',
                    iconRetinaUrl: '<?php echo $ir['airlineimage'] ;?>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -14],
                });
                var hqPopup = "<?php echo $ir['airlinename']; ?> HQ "+
                "<br /><b>Latitude: </b><?php echo $ir['latitude'];?>" +
                "<br /><b>Longitude: </b><?php echo $ir['longitude'];?>" +
                "<br /><button type=\"button\" class=\"btn btn-info\" data-bs-toggle=\"modal\" data-bs-target=\"#myModalhq\">Options</button>";
                parent.hqMarker = parent.L.marker([<?php echo $ir['latitude'];?>, <?php echo $ir['longitude'];?>], {icon: myHQIcon}).bindPopup(hqPopup);
                parent.map.addLayer(parent.hqMarker);
            </script>
        <?php 
            if (!isset($_POST['latitude'])) { $_POST['latitude'] = ''; }
            if (!isset($_POST['longitude'])) { $_POST['longitude'] = ''; }
            $lat = $db->escape($_POST['latitude']);
            $lon = $db->escape($_POST['longitude']);
            $db->query("UPDATE users SET latitude='$lat', longitude='$lon', airlinehq='1', bucks=bucks-'$cost' WHERE userid=$userid");
            $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`) VALUES ('$userid','out','$cost','Building: Headquarters',unix_timestamp())");
            echo 'Success<br /><br />';
            echo "<a href='https://con-airline.com' target='_parent'>Continue</a>";
    
        }
    }
}    
?>