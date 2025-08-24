<?php include "../pages/dbconnect.php";


$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'training': training(); break;
case 'trainingdo': trainingdo(); break;
case 'viewpilots': viewpilots(); break;
case 'recruitpilots': recruitpilots(); break;
case 'courses': courses(); break;
default:index(); break;
}









function index(){
    global $db;
$userid = isset($_GET['u']) ? $_GET['u'] : 0;
$get = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($get);
if($ir['airlinehq'] == '0') { 
    die("You must build a Headquarters before building a Training Center.");
}
if($ir['airlinetraininghq'] == '0') { ?>
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
                  <td><b>Training Center</b></td>
                  <td><span class="bucks">$1,000,000</span></td>
                  <td><form action="?a=training" method="post" target="training">
                        <input type="hidden" name="u" value="<?php echo $ir['userid']; ?>">
                        <input type="submit" name="submit" value="Build" class="btn btn-info">
                    </form>
                      
                      
                </td>
                
              </tr>
          </table>
        <?php } else { ?>
        <div class="btn-group">
        <form action="?a=viewpilots" method="post" target="training">
            <input type="hidden" name="u" value="<?php echo $ir['userid']; ?>">
            <input type="submit" name="submit" value="Recruit Pilots" class="btn btn-info">
        </form>
        <form action="?a=courses" method="post" target="training">
            <input type="hidden" name="u" value="<?php echo $ir['userid']; ?>">
            <input type="submit" name="submit" value="Available Courses" class="btn btn-info">
        </form>
        
        
        </div>
        <br />
            <p>This is Where you will Train and Manage All your Pilots</p>
            
    <hr />
    <b>Your Current Pilots</b>
    <table width="100%" class="table">
        <tr>
            <td><b>Name</b></td>
            <td><b>Rank</b></td>
            <td><b>Pay</b></td>
            <td><b>Qualification</b></td>
            <td><b>Options</b></td>
        </tr>
    
    <?php
    $query = $db->query("SELECT * FROM pilots WHERE userID=$userid");
    while ($dis = $db->fetch_row($query)) { ?>
    
    <tr>
        <td><?php echo $dis['name']; ?></td>
        <td><?php echo $dis['rank']; ?></td>
        <td>$<?php echo number_format($dis['pay'],2); ?></td>
        <td><?php echo $dis['qualifications']; ?></td>
        <td>Options</td>
    </tr>    
    
    <?php    }
    ?></table>
        <?php }
}





function courses() {
    global $db;
    $userid = isset($_POST['u']) ? $_POST['u'] : 0;
    ?>
    <a href='?a=index&u=<?php echo $userid; ?>' target='training' class='btn btn-info'>Back</a>
    <table width="100%" class="table">
        <tr>
            <td><b>Course</b></td>
            <td><b>Time</b></td>
            <td><b>Qualification</b></td>
            <td><b>Cost</b></td>
            <td><b>Options</b></td>
        </tr>
    
    <?php
    $query = $db->query("SELECT * FROM courses");
    while ($dis = $db->fetch_row($query)) { ?>
    
    <tr>
        <td><?php echo $dis['course']; ?></td>
        <td><?php echo $dis['coursetime']; ?></td>
        <td><?php echo $dis['courseoutcome']; ?></td>
        <td><?php echo money_formatter($dis['coursecost']); ?></td>
        <td>Options</td>
    </tr>    
    
    <?php    }
    ?></table> <?php
}





function viewpilots() {
    global $db;
    $userid = isset($_POST['u']) ? $_POST['u'] : 0;
    ?>
    <a href='?a=index&u=<?php echo $userid; ?>' target='training' class='btn btn-info'>Back</a><br />
    There is a Recuirtment fee of $500 Per Pilot Recruited.<br />
    <table width="100%" class="table">
        <tr>
            <td><b>Pilot Name</b></td>
            <td><b>Rank</b></td>
            <td><b>Wages</b></td>
            <td><b>Qualifications</b></td>
            <td><b>Options</b></td>
        </tr>
    
    <?php
    $query = $db->query("SELECT * FROM avpilots ORDER BY pay");
    while ($dis = $db->fetch_row($query)) { ?>
    
    <tr>
        <td><?php echo $dis['name']; ?></td>
        <td><?php echo $dis['rank']; ?></td>
        <td>$<?php echo number_format($dis['pay'],2); ?> /ph</td>
        <td><?php echo $dis['qualifications']; ?></td>
        <td><form action="?a=recruitpilots" target="training" method="post">
                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                <input type="hidden" name="pilot" value="<?php echo $dis['id']; ?>">
                <input type="submit" name="" value="Recruit" class="btn btn-success">
            </form></td>
    </tr>    
    
    <?php    }
    ?></table> <?php
}



function recruitpilots() {
    global $db;
    $userid = $_POST['userid'];
    $pilot = $_POST['pilot'];
    $query = $db->query("SELECT * FROM avpilots WHERE id=$pilot");
    $dis = $db->fetch_row($query);
    ?>
    <a href='?a=index&u=<?php echo $userid; ?>' target='training' class='btn btn-info'>Back</a><br />
    <br /><br />
    Your Pilot <?php echo $dis['name'];?> will be paid $<?php echo $dis['pay']; ?> Per Hour (You will be Charged Daily).<br />
    <?php    
    $name = $dis['name'];
    $rank = $dis['rank'];
    $pay = $dis['pay'];
    $qualifications = $dis['qualifications'];
    $db->query("INSERT INTO `pilots`(`id`, `userID`, `name`, `rank`, `pay`, `qualifications`) VALUES ('','$userid','$name','$rank','$pay','$qualifications')");
    $db->query("UPDATE users SET bucks=bucks-500 WHERE userid=$userid");
}





function training() {
    global $db;
$userid = isset($_POST['u']) ? $_POST['u'] : 0;
$get = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($get);

echo "<a href='?a=index&u=".$ir['userid']."' target='training' class='btn btn-info'>Back</a>";


    if($ir['airlinetraininghq'] == '0') { ?>
        <script>
            parent.allowMarkerMovement = true;
            parent.document.getElementById("hqTempLatInput").onchange = (e) => {
                console.log(e);
                document.getElementById("hqLatInput").value = e.target.value;
            };
            parent.document.getElementById("hqTempLngInput").onchange = (e) => {
                document.getElementById("hqLngInput").value = e.target.value;
            };
        </script>
    
        <table>
            <tr>
                <td>Click on the Map on where you wish to place your training HQ.<br />
                Then click Build on Marker.<br />
                      
                    <form action="?a=trainingdo" method="post" target="training">
                        <input type="hidden" name="tlatitude" id="hqLatInput" placeholder="latitude" value="2">
                        <input type="hidden" name="tlongitude" id="hqLngInput" placeholder="longitude" value="2">
                        <input type="hidden" name="user" value="<?php echo $ir['userid']; ?>">
                        <input type="submit" name="submit" value="Build on Marker" class="btn btn-success">
                    </form>
                      
                      
                </td>
              </tr>
          </table>
        <?php }
}





function trainingdo(){
    global $db;
    $userid = isset($_POST['user']) ? $_POST['user'] : 0;
$get = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($get);
?>
<script>
    parent.allowMarkerMovement = false;
    //parent.document.hqMarker = null;
    var myHQIcon = parent.L.icon({
        iconUrl: 'images/hqPinLogo24.png',
        iconRetinaUrl: 'images/hqPinLogo48.png',
        iconSize: [40, 40],
        iconAnchor: [9, 21],
        popupAnchor: [0, -14],
    });
    var hqPopup = "<?php echo $ir['airlinename']; ?> HQ "+
    "<br /><b>Latitude: </b><?php echo $ir['tlatitude'];?>" +
    "<br /><b>Longitude: </b><?php echo $ir['tlongitude'];?>" +
    "<br /><button type=\"button\" class=\"btn btn-info\" data-bs-toggle=\"modal\" data-bs-target=\"#myModalthq\">Options</button>";
    parent.hqMarker = parent.L.marker([<?php echo $ir['tlatitude'];?>, <?php echo $ir['tlongitude'];?>], {icon: myHQIcon}).bindPopup(hqPopup);
    parent.map.addLayer(hqMarker);
</script>
<?php 
$cost = '1000000';
if(isset($_POST['submit'])){
    if($ir['bucks'] < $cost) {
        echo 'You dont have enough money for this.';
    } else if ($ir['airlinetraininghq'] == '0') {
        if (!isset($_POST['tlatitude'])) { $_POST['tlatitude'] = ''; }
        if (!isset($_POST['tlongitude'])) { $_POST['tlongitude'] = ''; }
        $lat = $db->escape($_POST['tlatitude']);
        $lon = $db->escape($_POST['tlongitude']);
        $db->query("UPDATE users SET tlatitude='$lat', tlongitude='$lon', airlinetraininghq='1', bucks=bucks-'$cost' WHERE userid=$userid");
        $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$cost','Building: Training Center',unix_timestamp(),'bucks')");
        echo 'Success';
    }
}
}





?>
        