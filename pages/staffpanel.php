<?php 
include "../pages/dbconnect.php"; 
$theme = 'dark';





$userid = $_GET['u'];
$getir = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($getir);
?>

<table width="100%" height="100%" border="1">
    <tr>
        <td width="19%" valign="top">
                <a href="?a=index&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Staff Panel Main</a><br />
            <?php if($ir['staff'] >= '1') { ?>
                <h4>Chat Mod Options</h4>
                <a href="?a=showdeleted&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Show Deleted Chats</a><br />
            <?php } ?>
            <?php if($ir['staff'] >= '2') { ?>
                <h4>Game Mod Options</h4>
                <a href="?a=player&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Edit an Account</a><br />
                <a href="?a=playerplane&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Edit Players Aircraft</a><br />
            <?php } ?>
            <?php if($ir['staff'] >= '3') { ?>
                <h4>Admin Options</h4>
                <a href="?a=gamesettings&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Game Settings</a><br />
                <a href="?a=viewstafflog&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Staff Logs</a><br />
                <a href="?a=viewbugs&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">View Bug Reports</a><br />
                <a href="?a=resetaccount&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Reset an Account</a><br />
                <a href="?a=addairport&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Add Airport</a><br />
                <a href="?a=airport&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Edit an Airport</a><br />
                <a href="?a=deleteairport&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Delete Airport</a><br />
                <a href="?a=addplane&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Add New Plane</a><br />
                <a href="?a=editplaneselect&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Edit Plane</a><br />
                <a href="?a=fuelcost&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Fuel Costs</a><br />
                <a href="?a=sale&u=<?php echo $ir['userid']; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Start Sale</a><br />
            <?php } ?>
        </td>
        <td width="1%"></td>
        <td width="80%" valign="top"><br />


<?php
$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'addplane': addplane(); break;
case 'addplanedo': addplanedo(); break;
case 'editplaneselect': editplaneselect(); break;
case 'editplaneedit': editplaneedit(); break;
case 'editplanedo': editplanedo(); break;
case 'showdeleted': showdeleted(); break;
case 'resetaccount': resetaccount(); break;
case 'resetaccountdo': resetaccountdo(); break;
case 'fuelcost': fuelcost(); break;
case 'fuelcostdo': fuelcostdo(); break;
case 'player': player(); break;
case 'playerplane': playerplane(); break;
case 'editplayerplane': editplayerplane(); break;
case 'editplayerplane2': editplayerplane2(); break;
case 'editplayerplanedo' : editplayerplanedo(); break;
case 'editplayer': editplayer(); break;
case 'editplayerdo': editplayerdo(); break;
case 'gamesettings': gamesettings(); break;
case 'gamesettingsdo': gamesettingsdo(); break;
case 'delbug' : delbug(); break;
case 'delchat' : delchat(); break;
case 'airport': airport(); break;
case 'editairport': editairport(); break;
case 'editairportdo': editairportdo(); break;
case 'deleteairport': deleteairport(); break;
case 'deleteairportdo': deleteairportdo(); break;
case 'addairport': addairport(); break;
case 'addairportdo': addairportdo(); break;
case 'viewbugs': viewbugs(); break;
case 'viewstafflog': viewstafflog(); break;
case 'sale': sale(); break;
case 'saledo': saledo(); break;
default:index(); break;
}

function index() {
    global $db, $set, $_CONFIG;
    $userid = isset($_GET['u']) ? $_GET['u'] : 0;
    $version = $set['version'];
    $data = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($data);
    ?>
    <table width='75%' cellspacing='1' class='table'>
        		<tr>
        			<th>PHP Version:</th>
        			<td><?php echo phpversion(); ?></td>
        		</tr>
        		<tr>
        			<th>Game Version:</th>
        			<td><?php echo $version; ?></td>
        		</tr>

        </table>
    <br />
    Welcome to the Staff Panel <?php echo $ir['username']; ?>. <Br /><Br />
    You can find the links to the left you need.<br />
    <hr />
    <h3>Staff Notepad</h3><hr />
    <?php
        if (isset($_POST['pad'])) {
            $pad = $db->escape(stripslashes($_POST['pad']));
            $db->query("UPDATE `settings` SET `conf_value` = '{$pad}' WHERE `conf_name` = 'staff_pad'");
            $whos = $_GET['u'];
            $db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Updated Staff Notepad',unix_timestamp())");
            $set['staff_pad'] = stripslashes($_POST['pad']);
        echo '<b>Staff Notepad Updated!</b><hr />';
    }
    ?>
	<form action="?=index&u=<?php echo $ir['userid']; ?>" method="POST">
	    <textarea rows="10" cols="60" name="pad" class="form-control"><?php echo htmlentities($set['staff_pad'], ENT_QUOTES, 'ISO-8859-1'); ?></textarea>
		<br />
		<input type="submit" value="Update Notepad" class="btn btn-info" />
	</form>
    <?php     
}


function gamesettings() {
    global $db,$set,$ir; ?>
    <form action="?a=gamesettingsdo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <table width="100%">
            <tr>
                <td width="20%"><b><u>Setting</u></b><br /><br /></td>
                <td width="80%"><b><u>Current</u></b><br /><br /></td>
            </tr>
            <tr>
                <td><b><u>Game Name</u></b>:</td>
                <td><input type="text" class="form-control" name="gamename" value="<?php echo $set['gamename']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Game Description</u></b>:</td>
                <td><textarea rows="10" cols="60" name="gamedesc" class="form-control"><?php echo htmlentities($set['gamedesc'], ENT_QUOTES, 'ISO-8859-1'); ?></textarea></td>
            </tr>
            <tr>
                <td><b><u>Game Version</u></b></td>
                <td><input type="text" class="form-control" name="version" value="<?php echo $set['version']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Ad Space 1</u></b></td>
                <td><input type="text" class="form-control" name="space1" value="<?php echo $set['space1']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Ad Space 2</u></b></td>
                <td><input type="text" class="form-control" name="space2" value="<?php echo $set['space2']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Ad Space Cost</u></b></td>
                <td><input type="text" class="form-control" name="spacecost" value="<?php echo $set['spacecost']; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><br /><br /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Update" class="btn btn-info"></td>
            </tr>
        </table>
    </form>
<?php
}



function gamesettingsdo() {
global $db,$set,$ir;
    $gamename = $_POST['gamename'];
    $version = $_POST['version'];
    $gamedesc = $_POST['gamedesc'];
    $space1 = $_POST['space1'];
    $space2 = $_POST['space2'];
    $spacecost = $_POST['spacecost'];

    $db->query("UPDATE settings SET conf_value='$gamename' WHERE conf_name='gamename'");
    $db->query("UPDATE settings SET conf_value='$version' WHERE conf_name='version'");
    $db->query("UPDATE settings SET conf_value='$gamedesc' WHERE conf_name='gamedesc'");
    $db->query("UPDATE settings SET conf_value='$space1' WHERE conf_name='space1'");
    $db->query("UPDATE settings SET conf_value='$space2' WHERE conf_name='space2'");
    $db->query("UPDATE settings SET conf_value='$spacecost' WHERE conf_name='spacecost'");
    
    echo 'Game Settings Set';
    $whos = $_GET['u'];
    $db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Updated Game Settings',unix_timestamp())");
} 



















function viewstafflog() {
    global $db,$set;
    $userid = isset($_GET['u']) ? $_GET['u'] : 0;
    $data = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($data);
    if(!$ir['staff'] ==  '3') { die("ACCESS DENIED"); } else {
    ?>View Staff logs.<br />
    <br />
    <table width="100%">
        <tr>
            <td>By</td>
            <td>Action</td>
            <td>Date</td>
        </tr>
    <?php
    $qu = $db->query("SELECT * FROM `staff_log` ORDER BY id DESC");
        while ($br = $db->fetch_row($qu)) { 
            $date = $br['date'];
            $actioned = $br['who'];
            $que = $db->query("SELECT * FROM users WHERE userid='$actioned'");
            $r = $db->fetch_row($que);
    ?>   
        <tr>
            <td><?php echo $r['username']; ?></td>
            <td><?php echo $br['action']; ?></td>
            <td><?php echo date("jS F, Y, H:i",$date+3660); ?></td>
        </tr>
    <?php } ?>
    </table>
    <?php
}
$whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Viewed Staff Logs',unix_timestamp())");
}






function addairport() {
    global $db,$set,$ir;
    $id = $_GET['u'];
    ?>

    <form action="?a=addairportdo&u=<?php echo $id; ?>" target="staffpanel" method="post">
        <table width="100%">
            <tr>
                <td width="25%"></td>
                <td width="75%"></td>
            </tr>
            <tr>
                <td><b>Name:</b></td>
                <td><input type="text" class="form-control" name="name" placeholder="Name"></td>
            </tr>
            <tr>
                <td><b>City:</b></td>
                <td><input type="text" class="form-control" name="city" placeholder="City"></td>
            </tr>
            <tr>
                <td><b>IATA_FAA:</b></td>
                <td><input type="text" class="form-control" name="iata_faa" placeholder="IATA_FAA"></td>
            </tr>
            <tr>
                <td><b>ICAO:</b></td>
                <td><input type="text" class="form-control" name="icao" placeholder="ICAO"></td>
            </tr>
            <tr>
                <td><b>Latitude:</b></td>
                <td><input type="text" class="form-control" name="lat" placeholder="Latitude"></td>
            </tr>
            <tr>
                <td><b>Longtitude:</b></td>
                <td><input type="text" class="form-control" name="lng" placeholder="Longtitude"></td>
            </tr>
            <tr>
                <td><b>Altitude:</b></td>
                <td><input type="text" class="form-control" name="alt" placeholder="Altitude"></td>
            </tr>
            <tr>
                <td><b>Timezone:</b></td>
                <td><input type="text" class="form-control" name="tz" placeholder="Timezone"></td>
            </tr>
            <tr>
                <td><b>Airport Population:</b></td>
                <td><input type="text" class="form-control" name="airportpop" placeholder="Airport Population"></td>
            </tr>
            <tr>
                <td><b>City Population:</b></td>
                <td><input type="text" class="form-control" name="citypop" placeholder="City Population"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Add Airport" class="btn btn-info"></td>
            </tr>
        </table>
        </form>

           <?php 
}



function addairportdo() {
global $db,$set,$ir;
$name = $_POST['name'];
$city = $_POST['city'];
$iata_faa = $_POST['iata_faa'];
$icao = $_POST['icao'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$alt = $_POST['alt'];
$tz = $_POST['tz'];
$airportpop = $_POST['airportpop'];
$citypop = $_POST['citypop'];
$db->query("INSERT INTO `airports`(`id`, `name`, `city`, `iata_faa`, `icao`, `lat`, `lng`, `alt`, `tz`, `airportpop`, `citypop`) VALUES ('','$name','$city','$iata_faa','$icao','$lat','$lng','$alt','$tz','$airportpop','$citypop')");
   echo 'Airport Added';
$whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Added Airport',unix_timestamp())");
}



function sale() {
    global $db,$set,$ir;
    $sale = $set['sale'];
    if ($sale == '1') { $saled='Sale On'; } else { $saled='Sale Off'; } ?>
    <script type="text/javascript">
        function insert(el,ins) {
        if (el.setSelectionRange){
        el.value = el.value.substring(0,el.selectionStart) + ins + el.value.substring(el.selectionStart,el.selectionEnd) +
        
        el.value.substring(el.selectionEnd,el.value.length);
        }
        else if (document.selection && document.selection.createRange) {
        el.focus();
        var range = document.selection.createRange();
        range.text = ins + range.text;
        }
        }
    </script>
    
    <form action="?a=saledo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <table width="50%">
            <tr>
                <td width="35%"><b><u>Setting</u></b><br /><br /></td>
                <td width="65%"><b><u>Current</u></b><br /><br /></td>
            </tr>
            <tr>
                <td><b>Sale?</b></td>
                <td><select name='sale' type='dropdown' class="form-select">
                        <option value="<?php echo $set['sale']; ?>" disabled selected><?php echo $saled; ?></option>
                        <option value="0">Sale Off</option>
                        <option value="1">Sale On</option>
                    </select></td>
            </tr>
            <tr>
                <td><b>Event?</b></td>
                <td>
                    <input type="text" class="form-control" name="saleevent" value="<?php echo $set['saleevent']; ?>">
                </td>
            </tr>
            <tr>
                <td><b>Sale Percent Off:</b></td>
                <td><select class="form-select" name="saleperc">
                        <option value="<?php echo $set['saleperc']; ?>"><?php echo $set['saleperc']; ?>%</option>
                        <?php for ($i=1; $i<=99; $i++) {
                            echo "<option value='$i'>$i%</option>";
                        }
                        ?>
                    </select></td>
            </tr>
            <!--tr>
                <td><b>Current Sale Start Time:</b></td>
                <td><?php echo $set['salestart']; ?> - <input type="button" value="Insert" onclick="insert(this.form.salestart,'<?php echo $set['salestart']; ?>'); return false;" class="btn btn-info btn-sm" /><br /></td>
            </tr>
            <tr>
                <td><b>Current Sale End Time:</b></td>
                <td><?php echo $set['saleend']; ?> - <input type="button" value="Insert" onclick="insert(this.form.saleend,'<?php echo $set['saleend']; ?>'); return false;" class="btn btn-info btn-sm" /><br /></td>
            </tr-->
            <tr>
                <td><b>Start Sale Time:</b></td>
                <td><input type="datetime-local" class="form-control" name="salestart"></td>
            </tr>
            <!--tr>
                <td><b></b></td>
                <td><input type="button" value="Click to Insert Current Timestamp" onclick="insert(this.form.salestart,'<?php echo time(); ?>'); return false;" class="btn btn-info btn-sm" /></td>
            </tr-->
            <tr>
                <td><b>End Sale Time:</b></td>
                <td><input type="datetime-local" class="form-control" name="saleend" maxlength="10" max="10"></td>
            </tr>
            <!--tr>
                <td><b></b></td>
                <td><input type="button" value="Click to Insert Current Timestamp" onclick="insert(this.form.saleend,'<?php echo time(); ?>'); return false;" class="btn btn-info btn-sm" /></td>
            </tr-->
            <tr>
                <td colspan="2"><br /><br /></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Update" class="btn btn-info"></td>
            </tr>
        </table>
    </form>
<?php
}



function saledo() {
global $db,$set,$ir;
    $sale = $_POST['sale'];
    $saleperc = $_POST['saleperc'];
    $salestart = strtotime($_POST['salestart']);
    $saleend = strtotime($_POST['saleend']);
    $saleevent = $_POST['saleevent'];
    
    $db->query("UPDATE settings SET conf_value=$sale WHERE conf_name='sale'");
    $db->query("UPDATE settings SET conf_value=$saleperc WHERE conf_name='saleperc'");
    $db->query("UPDATE settings SET conf_value=$salestart WHERE conf_name='salestart'");
    $db->query("UPDATE settings SET conf_value=$saleend WHERE conf_name='saleend'");
    $db->query("UPDATE settings SET conf_value='$saleevent' WHERE conf_name='saleevent'");
    echo 'Sale Settings Set';
    $whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Updated Sales Page',unix_timestamp())");
} 


function viewbugs() {
    global $db,$set,$ir;
    $query = $db->query("SELECT * FROM bugs");
    ?>View Bug Reports.<br />
    <br />
    <table width="100%">
        <tr>
            <td>Reporter</td>
            <td>Type</td>
            <td>Bug</td>
            <td>Date</td>
            <td>Options</td>
        </tr>
    <?php while ($br = $db->fetch_row($query)) { 
    $time = $br['date'];
    $report = $br['reporterid'];
    $que = $db->query("SELECT * FROM users WHERE userid=$report");
    $r = $db->fetch_row($que);
    ?>   
        <tr>
            <td><?php echo $r['username']; ?></td>
            <td><?php echo $br['bugtype']; ?></td>
            <td><?php echo $br['bug']; ?></td>
            <td><?php echo date("jS F, Y, H:i",$time+3660); ?></td>

            <td><a href="?a=delbug&id=<?php echo $br["id"]; ?>&u=<?php echo $_GET['u']; ?>"><font color="black">[X]</span></a></td>
        </tr>
    <?php } ?>
    </table>
    <?php
    $whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Viewed Bug Reports',unix_timestamp())");
}





function delbug()
{
    global $db;
    $id = $_GET['id'];
    echo '
    <div class="alert alert-danger">';
    echo 'DELETE BUG ID:'.$id.'<br />Bug Deleted.<br />
        <a href="?a=index&u='.$_GET['u'].'">Continue</a>
    </div>';
    $db->query("DELETE FROM `bugs` WHERE id=$id"); // Delete Completely
    $whos = $_GET['u'];
    $db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Deleted a Bug Report',unix_timestamp())");
}



function deleteairport() {
    global $db,$set,$ir;
    $getinfo = $db->query("SELECT * FROM `airports` ORDER BY name ASC"); ?>
        <form action="?a=deleteairportdo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <table width="100%">
            <tr>
                <td><select name='id' type='dropdown' class="form-select"><?php
        while($data=$db->fetch_row($getinfo)) { ?>
            <option value="<?php echo $data['id']; ?>"><?php echo $data['name'].' - '.$data['id']; ?></option>
        <?php } ?></select></td>
                <td><input type="submit" name="submit" value="Delete Airport" class="btn btn-info"></td>
            </tr>
        </table>
        </form><?php 
}

function deleteairportdo() {
    global $db,$set,$ir;
    $id = $_POST['id'];

    $db->query("DELETE FROM `airports` WHERE id=$id");
       echo 'Airport Deleted';
    $whos = $_GET['u'];
    $db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Deleted Airport ID: $id',unix_timestamp())");
        
}





function airport() {
    global $db,$set,$ir;
    $getinfo = $db->query("SELECT * FROM `airports` ORDER BY name ASC"); ?>
        <form action="?a=editairport&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <table width="100%">
            <tr>
                <td><select name='id' type='dropdown' class="form-select"><?php
        while($data=$db->fetch_row($getinfo)) { ?>
            <option value="<?php echo $data['id']; ?>"><?php echo $data['name'].' - '.$data['id']; ?></option>
        <?php } ?></select></td>
                <td><input type="submit" name="submit" value="Select Airport" class="btn btn-info"></td>
            </tr>
        </table>
        </form><?php 
}



function editairport() {
    global $db,$set,$ir;
    $id = $_POST['id'];
    $getinfo = $db->query("SELECT * FROM airports WHERE id=$id");
    $ir = $db->fetch_row($getinfo); 
    ?>

    <form action="?a=editairportdo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <input type="hidden" name="id" value="<?php echo $ir['id']; ?>">
        <table width="100%">
            <tr>
                <td width="25%">Setting</td>
                <td width="75%">Current</td>
            </tr>
            <tr>
                <td><b>Name:</b></td>
                <td><input type="text" class="form-control" name="name" value="<?php echo $ir['name']; ?>"></td>
            </tr>
            <tr>
                <td><b>City:</b></td>
                <td><input type="text" class="form-control" name="city" value="<?php echo $ir['city']; ?>"></td>
            </tr>
            <tr>
                <td><b>IATA_FAA:</b></td>
                <td><input type="text" class="form-control" name="iata_faa" value="<?php echo $ir['iata_faa']; ?>"></td>
            </tr>
            <tr>
                <td><b>ICAO:</b></td>
                <td><input type="text" class="form-control" name="icao" value="<?php echo $ir['icao']; ?>"></td>
            </tr>
            <tr>
                <td><b>Latitude:</b></td>
                <td><input type="text" class="form-control" name="lat" value="<?php echo $ir['lat']; ?>"></td>
            </tr>
            <tr>
                <td><b>Longtitude:</b></td>
                <td><input type="text" class="form-control" name="lng" value="<?php echo $ir['lng']; ?>"></td>
            </tr>
            <tr>
                <td><b>Altitude:</b></td>
                <td><input type="text" class="form-control" name="alt" value="<?php echo $ir['alt']; ?>"></td>
            </tr>
            <tr>
                <td><b>Timezone:</b></td>
                <td><input type="text" class="form-control" name="tz" value="<?php echo $ir['tz']; ?>"></td>
            </tr>
            <tr>
                <td><b>Airport Population:</b></td>
                <td><input type="text" class="form-control" name="airportpop" value="<?php echo $ir['airportpop']; ?>"></td>
            </tr>
            <tr>
                <td><b>City Population:</b></td>
                <td><input type="text" class="form-control" name="citypop" value="<?php echo $ir['citypop']; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Edit Airport" class="btn btn-info"></td>
            </tr>
        </table>
        </form>

           <?php 
}

function editairportdo() {
global $db,$set,$ir;
$id = $_POST['id'];
$name = $_POST['name'];
$city = $_POST['city'];
$iata_faa = $_POST['iata_faa'];
$icao = $_POST['icao'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$alt = $_POST['alt'];
$tz = $_POST['tz'];
$airportpop = $_POST['airportpop'];
$citypop = $_POST['citypop'];

$db->query("UPDATE airports SET name='$name', city='$city', iata_faa='$iata_faa', icao='$icao', lat='$lat', lng='$lng', alt='$alt', tz='$tz', airportpop='$airportpop', citypop='$citypop' WHERE id=$id");
   echo 'Airport Edited';
$whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Edited Airport ID: $id',unix_timestamp())");
}





function playerplane() {
    global $db,$set,$ir;
    $getinfo = $db->query("SELECT * FROM `users` ORDER BY userid ASC"); ?>
        <form action="?a=editplayerplane&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="POST">
        <table width="100%">
            <tr>
                <td><select name='userid' type='dropdown' class="form-select"><?php
        while($data=$db->fetch_row($getinfo)) { ?>
            <option value="<?php echo $data['userid']; ?>"><?php echo $data['username'].' - '.$data['userid']; ?></option>
        <?php } ?></select></td>
                <td><input type="submit" name="submit" value="Select User First" class="btn btn-info"></td>
            </tr>
        </table>
        </form><?php 
}

function editplayerplane() {
    global $db,$set;
    $user = $_POST['userid'];
    $getinfo = $db->query("SELECT * FROM users WHERE userid='$user'");
    $ir = $db->fetch_row($getinfo); 
    $getplaneinfo = $db->query("SELECT * FROM userairplanes WHERE planeOWNER='$user' ORDER BY id");
    ?>

    <form action="?a=editplayerplane2&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <input type="hidden" name="userid" value="<?php echo $ir['userid']; ?>">
        <table width="100%">
            <tr>
                <td><select name='id' type='dropdown' class="form-select"><?php
                    while($data=$db->fetch_row($getplaneinfo)) { ?>
                        <option value="<?php echo $data['id']; ?>"><?php echo $data['planeUname'].' - '.$data['id']; ?></option>
                    <?php } ?></select></td>
            </tr>
            <tr>
                <td colspan="1"><input type="submit" name="submit" value="Edit Users Plane" class="btn btn-info"></td>
            </tr>
        </table>
        </form>

           <?php 
}



function editplayerplane2() {
    global $db,$set;
    $plane = $_POST['id'];
    $userid = $_POST['userid'];
    $getinfo = $db->query("SELECT * FROM users WHERE userid='$userid'");
    $ir = $db->fetch_row($getinfo); 
    $getplaneinfo = $db->query("SELECT * FROM userairplanes WHERE id='$plane'");
    $planeinfo = $db->fetch_row($getplaneinfo); 
    $gplane = $planeinfo['planeID'];
    $getplanetype = $db->query("SELECT * FROM airplanes");
    $getplanetyp = $db->query("SELECT * FROM airplanes WHERE planeID=$gplane");
    $getplaneh=$db->fetch_row($getplanetyp);
    
    ?>
    <form action="?a=editplayerplanedo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <input type="hidden" name="plane" value="<?php echo $planeinfo['id']; ?>">
        <table width="100%">
            <tr>
                <td><b><u>Plane Type</u></b></td>
                <td><select name='planeID' type='dropdown' class="form-select">
                    <option value="<?php echo $planeinfo['planeID']; ?>">Current <?php echo $getplaneh['planeMAKE'].' - '.$getplaneh['planeMODEL'].' - '.$planeinfo['planeID']; ?></option>
                    <?php
                    while($getplanet=$db->fetch_row($getplanetype)) { ?>
                        <option value="<?php echo $getplanet['planeID']; ?>"><?php echo $getplanet['planeMAKE'].$getplanet['planeMODEL'].' - '.$getplanet['planeID']; ?></option>
                    <?php } ?>
                    </select>
                    </td>
            </tr>
            <tr>
                <td><b><u>Max Passengers</u></b></td>
                <td><input type="text" name="planePASSENGER" value="<?php echo $planeinfo['planePASSENGER']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Current Passengers</u></b></td>
                <td><input type="text" name="planePASSENGERCURRENT" value="<?php echo $planeinfo['planePASSENGERCURRENT']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Max Fuel</u></b></td>
                <td><input type="text" name="planeMAXFUEL" value="<?php echo $planeinfo['planeMAXFUEL']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Current Fuel</u></b></td>
                <td><input type="text" name="planeFUELCURRENT" value="<?php echo $planeinfo['planeFUELCURRENT']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Plane Speed</u></b></td>
                <td><input type="text" name="planeSPEED" value="<?php echo $planeinfo['planeSPEED']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Max Distance</u></b></td>
                <td><input type="text" name="planeMAXDISTANCE" value="<?php echo $planeinfo['planeMAXDISTANCE']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Distance Travelled</u></b></td>
                <td><input type="text" name="planeDISTANCETRAVELLED" value="<?php echo $planeinfo['planeDISTANCETRAVELLED']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Max Weight</u></b></td>
                <td><input type="text" name="planeMAXWEIGHT" value="<?php echo $planeinfo['planeMAXWEIGHT']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Current Weight</u></b></td>
                <td><input type="text" name="planeCURRENTWEIGHT" value="<?php echo $planeinfo['planeCURRENTWEIGHT']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Consumption Rate</u></b></td>
                <td><input type="text" name="planeCONSUMPTIONRATE" value="<?php echo $planeinfo['planeCONSUMPTIONRATE']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><?php
                    $loclat = $planeinfo['planeLOCATIONLAT'];
                    $loclng = $planeinfo['planeLOCATIONLON']; 
                    $getpf = $db->query("SELECT * FROM airports WHERE lat=$loclat");
                        while($get=$db->fetch_row($getpf)) {  $curloc = $get['city']; }
                    ?>
                    <b><u>Plane Location</u></b></td>
                <td>
                    <select name='planelocation' type='dropdown' class="form-select">
                        <option value="<?php echo $loclat; ?>:<?php echo $loclng; ?>"><?php echo $curloc; ?> - LAT: <?php echo $loclat; ?>, LON: <?php echo $loclng; ?></option>
                        <option value="<?php echo $ir['latitude']; ?>:<?php echo $ir['longitude']; ?>">Bring Back to HQ - LAT: <?php echo $ir['latitude']; ?>, LON: <?php echo $ir['longitude']; ?></option>
                        <?php $getpr = $db->query("SELECT * FROM airports");
                            while($geth=$db->fetch_row($getpr)) { ?>
                        <option value="<?php echo $geth['lat']; ?>:<?php echo $geth['lng']; ?>"><?php echo $geth['city']; ?> - LAT: <?php echo $geth['lat']; ?>, LON: <?php echo $geth['lng']; ?></option>
                    <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><b><u>Plane Inflight</u></b></td>
                <td><?php
                    $actives = $planeinfo['planeACTIVE'];
                    if ($actives == '1') { $value = "1"; $text = "Inflight"; } else { $value = "0"; $text = "Landed"; } ?>
                    <select name='planeACTIVE' type='dropdown' class="form-select">
                        <option value="<?php echo $value; ?>"><?php echo $text; ?></option>
                        <option value="1">Inflight</option>
                        <option value="0">Land</option>
                    </select>
                    </td>
            </tr>
            <tr>
                <td><b><u>Money Made</u></b></td>
                <td><input type="text" name="planeMONEYMADE" value="<?php echo $planeinfo['planeMONEYMADE']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Plane Nickname</u></b></td>
                <td><input type="text" name="planeUname" value="<?php echo $planeinfo['planeUname']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Plane Health</u></b></td>
                <td><input type="text" name="planeHEALTH" value="<?php echo $planeinfo['planeHEALTH']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Plane Max Health</u></b></td>
                <td><input type="text" name="planeMAXHEALTH" value="<?php echo $planeinfo['planeMAXHEALTH']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Pilot</u></b></td>
                <td><input type="text" name="pilot" value="<?php echo $planeinfo['pilot']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Co-Pilot</u></b></td>
                <td><input type="text" name="copilot" value="<?php echo $planeinfo['copilot']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td><b><u>Flight Time</u></b></td>
                <td><input type="text" name="flighttime" value="<?php echo $planeinfo['flighttime']; ?>" class="form-control"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Edit Users Plane" class="btn btn-info"></td>
            </tr>
        </table>
        </form>
           <?php 
}







function editplayerplanedo() {
global $db,$set,$ir;
$planeid = $_POST['plane'];
$planeIDs = $_POST['planeID'];
$planePASSENGER = $_POST['planePASSENGER'];
$planePASSENGERCURRENT = $_POST['planePASSENGERCURRENT'];
$planeMAXFUEL = $_POST['planeMAXFUEL'];
$planeFUELCURRENT = $_POST['planeFUELCURRENT'];
$planeSPEED = $_POST['planeSPEED'];
$planeMAXDISTANCE = $_POST['planeMAXDISTANCE'];
$planeDISTANCETRAVELLED = $_POST['planeDISTANCETRAVELLED'];
$planeMAXWEIGHT = $_POST['planeMAXWEIGHT'];
$planeCURRENTWEIGHT = $_POST['planeCURRENTWEIGHT'];
$planeCONSUMPTIONRATE = $_POST['planeCONSUMPTIONRATE'];
//$planeLOCATIONLAT = $_POST['planeLOCATIONLAT'];
//$planeLOCATIONLON = $_POST['planeLOCATIONLON'];
$planeACTIVE = $_POST['planeACTIVE'];
$planeMONEYMADE = $_POST['planeMONEYMADE'];
$planeUname = $_POST['planeUname'];
$planeHEALTH = $_POST['planeHEALTH'];
$planeMAXHEALTH = $_POST['planeMAXHEALTH'];
$pilot = $_POST['pilot'];
$copilot = $_POST['copilot'];
$flighttime = $_POST['flighttime'];


$values = explode(':', $_POST['planelocation']);
$planeLOCATIONLAT = $values[0];
$planeLOCATIONLON = $values[1];


$db->query("UPDATE `userairplanes` SET `planeID`='$planeIDs',`planePASSENGER`='$planePASSENGER',`planePASSENGERCURRENT`='$planePASSENGERCURRENT',`planeMAXFUEL`='$planeMAXFUEL',`planeFUELCURRENT`='$planeFUELCURRENT',
                                        `planeSPEED`='$planeSPEED',`planeMAXDISTANCE`='$planeMAXDISTANCE',`planeDISTANCETRAVELLED`='$planeDISTANCETRAVELLED',`planeMAXWEIGHT`='$planeMAXWEIGHT',`planeCURRENTWEIGHT`='$planeCURRENTWEIGHT',`planeCONSUMPTIONRATE`='$planeCONSUMPTIONRATE',
                                        `planeLOCATIONLAT`='$planeLOCATIONLAT',`planeLOCATIONLON`='$planeLOCATIONLON',`planeACTIVE`='$planeACTIVE',`planeMONEYMADE`='$planeMONEYMADE',`planeUname`='$planeUname',`planeHEALTH`='$planeHEALTH',
                                        `planeMAXHEALTH`='$planeMAXHEALTH',`pilot`='$pilot',`copilot`='$copilot',`flighttime`='$flighttime' 
                                        WHERE id=$planeid");
echo 'Plane Edited';
$whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Edited Plane ID: $planeid',unix_timestamp())");
}





function player() {
    global $db,$set,$ir;
    $getinfo = $db->query("SELECT * FROM `users` ORDER BY userid ASC"); ?>
        <form action="?a=editplayer&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="POST">
        <table width="100%">
            <tr>
                <td><select name='userid' type='dropdown' class="form-select"><?php
        while($data=$db->fetch_row($getinfo)) { ?>
            <option value="<?php echo $data['userid']; ?>"><?php echo $data['username'].' - '.$data['userid']; ?></option>
        <?php } ?></select></td>
                <td><input type="submit" name="submit" value="Select User" class="btn btn-info"></td>
            </tr>
        </table>
        </form><?php 
}
function editplayer() {
    global $db,$set;
    $user = $_POST['userid'];
    $getinfo = $db->query("SELECT * FROM users WHERE userid='$user'");
    $ir = $db->fetch_row($getinfo); 
    ?>

    <form action="?a=editplayerdo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <input type="hidden" name="userid" value="<?php echo $ir['userid']; ?>">
        <table width="100%">
            <tr>
                <td width="25%">Setting</td>
                <td width="75%">Current</td>
            </tr>
            <tr>
                <td><b>Username:</b></td>
                <td><input type="text" class="form-control" name="username" value="<?php echo $ir['username']; ?>"></td>
            </tr>
            <tr>
                <td><b>E-mail</b></td>
                <td><input type="text" class="form-control" name="email" value="<?php echo $ir['email']; ?>"></td>
            </tr>
            <tr>
                <td><b>Staff Rank</b></td>
                <td><select name='staff' type='dropdown' class="form-select"><option value="0">User</option><option value="1">Chat Mod</option><option value="2">Game Mod</option></select></td>
            </tr>
            <tr>
                <td><b>Staff Roles</b></td>
                <td><input type="text" class="form-control" name="staffroles" value="<?php echo $ir['roles']; ?>"></td>
            </tr>
            <tr>
                <td><b>Bucks:</b></td>
                <td><input type="text" class="form-control" name="bucks" value="<?php echo $ir['bucks']; ?>"></td>
            </tr>
            <tr>
                <td><b>AirBucks:</b></td>
                <td><input type="text" class="form-control" name="airbucks" value="<?php echo $ir['airbucks']; ?>"></td>
            </tr>
            <tr>
                <td><b>Profile Image:</b></td>
                <td><input type="text" class="form-control" name="profileimage" value="<?php echo $ir['profileimage']; ?>"></td>
            </tr>
            <tr>
                <td><b>Airline Image:</b></td>
                <td><input type="text" class="form-control" name="airlineimage" value="<?php echo $ir['airlineimage']; ?>"></td>
            </tr>
            <tr>
                <td><b>Airline Name:</b></td>
                <td><input type="text" class="form-control" name="airlinename" value="<?php echo $ir['airlinename']; ?>"></td>
            </tr>
            <tr>
                <td><b>Airline Colour:</b></td>
                <td><input type="text" class="form-control" name="airlinecolour" value="<?php echo $ir['airlinecolour']; ?>"></td>
            </tr>
            <tr>
                <td><b>Premium Days:</b></td>
                <td><input type="text" class="form-control" name="premiumdays" value="<?php echo $ir['premiumdays']; ?>"></td>
            </tr>
            <tr>
                <td><b>Joined:</b></td>
                <td><input type="text" class="form-control" name="joineddate" value="<?php echo $ir['joineddate']; ?>"></td>
            </tr>
            <tr>
                <td><b>Account Blocked:</b></td>
                <td><input type="text" class="form-control" name="accountblock" value="<?php echo $ir['accountblock']; ?>"></td>
            </tr>
            <tr>
                <td><b>Airline HQ:</b></td>
                <td><select name='airlinehq' type='dropdown' class="form-select"><option value="1">HQ</option><option value="0">No HQ</option></select></td>
            </tr>
            <tr>
                <td><b>HQ Latitude:</b></td>
                <td><input type="text" class="form-control" name="latitude" value="<?php echo $ir['latitude']; ?>"></td>
            </tr>
            <tr>
                <td><b>HQ Longitude:</b></td>
                <td><input type="text" class="form-control" name="longitude" value="<?php echo $ir['longitude']; ?>"></td>
            </tr>
            <tr>
                <td><b>Fuel Storage:</b></td>
                <td><input type="text" class="form-control" name="fuelstorage" value="<?php echo $ir['fuelstorage']; ?>"></td>
            </tr>
            <tr>
                <td><b>Fuel Storage Max:</b></td>
                <td><input type="text" class="form-control" name="fuelstoragemax" value="<?php echo $ir['fuelstoragemax']; ?>"></td>
            </tr>
            <tr>
                <td><b>User Theme:</b></td>
                <td><select name='theme' type='dropdown' class="form-select">
                            <option value="light">light</option>
                            <option value="dark">dark</option>
                            <option value="primary">primary</option>
                            <option value="secondary">secondary</option>
                            <option value="success">success</option>
                            <option value="info">info</option>
                            <option value="warning">warning</option>
                            <option value="danger">danger</option>
                        </select></td>
            </tr>
            <tr>
                <td><b>Training HQ</b></td>
                <td><select name='airlinetraininghq' type='dropdown' class="form-select"><option value="1">Training HQ</option><option value="0">No Training HQ</option></select></td>
            </tr>
            <tr>
                <td><b>Training HQ Lat:</b></td>
                <td><input type="text" class="form-control" name="tlatitude" value="<?php echo $ir['tlatitude']; ?>"></td>
            </tr>
            <tr>
                <td><b>Training HQ Long:</b></td>
                <td><input type="text" class="form-control" name="tlongitude" value="<?php echo $ir['tlongitude']; ?>"></td>
            </tr>
            <tr>
                <td><b>Tickets Price:</b></td>
                <td><input type="text" class="form-control" name="tickets1" value="<?php echo $ir['tickets1']; ?>"></td>
            </tr>
            <tr>
                <td><b>Reputation:</b></td>
                <td><input type="text" class="form-control" name="reputation" value="<?php echo $ir['reputation']; ?>"></td>
            </tr>
            <tr>
                <td><b>Reputation Array:</b></td>
                <td><input type="text" class="form-control" name="reputationa" value="<?php echo $ir['reputationa']; ?>"></td>
            </tr>
            <tr>
                <td><b>Last on:</b></td>
                <td><input type="text" class="form-control" name="laston" value="<?php echo $ir['laston']; ?>"></td>
            </tr>  
            <tr>
                <td><b>Loan:</b></td>
                <td><input type="text" class="form-control" name="loan" value="<?php echo $ir['loan']; ?>"></td>
            </tr>
            <tr>
                <td><b>Favourite Airport:</b></td>
                <td><input type="text" class="form-control" name="fav" value="<?php echo $ir['fav']; ?>"></td>
            </tr>
            <tr>
                <td><b>Daily Reward:</b></td>
                <td><input type="text" class="form-control" name="box" value="<?php echo $ir['box']; ?>"></td>
            </tr>
            <tr>
                <td><b>Daily Day:</b></td>
                <td><input type="text" class="form-control" name="day" value="<?php echo $ir['day']; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Edit User" class="btn btn-info"></td>
            </tr>
        </table>
        </form>

           <?php 
}


function editplayerdo() {
global $db,$set,$ir;
$userid = $_POST['userid'];
$username = $_POST['username'];
$email = $_POST['email'];
$staff = $_POST['staff'];
$roles = $_POST['staffroles'];
$bucks = $_POST['bucks'];
$airbucks = $_POST['airbucks'];
$profileimage = $_POST['profileimage'];
$airlineimage = $_POST['airlineimage'];
$airlinename = $_POST['airlinename'];
$airlinecolour = $_POST['airlinecolour'];
$joineddate = $_POST['joineddate'];
$accountblock = $_POST['accountblock'];
$airlinehq= $_POST['airlinehq'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$fuelstorage = $_POST['fuelstorage'];
$fuelstoragemax = $_POST['fuelstoragemax'];
$premiumdays = $_POST['premiumdays'];
$theme = $_POST['theme'];
$airlinetraininghq = $_POST['airlinetraininghq'];
$tlatitude = $_POST['tlatitude'];
$tlongitude = $_POST['tlongitude'];
$tickets1 = $_POST['tickets1'];
$reputation = $_POST['reputation'];
$reputationa = $_POST['reputationa'];
$laston = $_POST['laston'];
$loan = $_POST['loan'];
$fav = $_POST['fav'];
$box = $_POST['box'];
$day = $_POST['day'];


$db->query("UPDATE users SET username='$username', email='$email',staff='$staff',bucks='$bucks', airbucks='$airbucks', joineddate='$joineddate', accountblock='$accountblock', airlinehq='$airlinehq', latitude='$latitude', longitude='$longitude',
                                 fuelstorage='fuelstorage', fuelstoragemax='$fuelstoragemax', profileimage='$profileimage',airlineimage='$airlineimage',airlinename='$airlinename', airlinecolour='$airlinecolour', premiumdays='$premiumdays', theme='$theme',
                                 airlinetraininghq='$airlinetraininghq', tlatitude='$tlatitude', tlongitude='$tlongitude', tickets1='$tickets1', reputation='$reputation', reputationa='$reputationa', laston='$laston', loan='$loan', fav='$fav', box='$box', 
                                 day='$day', roles='$roles'
                                 WHERE userid=$userid");
   echo 'User Edited';
  $whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Edited User: $userid',unix_timestamp())");
}





























function fuelcost(){
    global $db,$set,$ir;
    echo "Current fuel price: ".$set['fuelcost']."<br />";
    
    $datapoints = $set['fuelcosttrack'];
    
    echo "<div id=\"chartContainer\" style=\"height: 370px; width: 100%;\"></div>
        <script src=\"https://canvasjs.com/assets/script/jquery-1.11.1.min.js\"></script>
        <script src=\"https://canvasjs.com/assets/script/jquery.canvasjs.min.js\"></script>
        <script>
        dataPointsArray = '".$datapoints."'.split(',');
        dataPoints = [];
        
        for (let i = 0; i < dataPointsArray.length; i++) {
            dataPoints.push({x: i, y: parseFloat(dataPointsArray[i])});
        }
        
        window.onload = () => {
            var chart = new CanvasJS.Chart(\"chartContainer\", {
        	animationEnabled: true,
        	theme: \"light2\",
        	zoomEnabled: true,
        	title: {
        		text: \"Jet Fuel Price\"
        	},
        	axisY: {
        		title: \"Price (Bucks)\",
        		titleFontSize: 24,
        		prefix: \"$\"
        	},
        	axisX: {
        	    valueFormatString: \"#,##\",
        	    minimum: 0,
        	    maximum: dataPoints.length - 1,
        	    interval: Math.max(1, Math.round(dataPoints.length) / 10)
        	},
        	data: [{
        		type: \"line\",
        		yValueFormatString: \"$#,##0.00\",
        		xValueFormatString: \"#,##\",
        		dataPoints: dataPoints
        	}]
        });
        chart.render();
    }
    </script>";
    
    ?>
    <form action='?a=fuelcostdo&u=<?php echo $_GET['u']; ?>' target='staffpanel' method='post'>
                <label for='addfuelprice'>Set Fuel Price: </label>
                <input type='text' name='addfuelprice' id='addfuelprice' placeholder='Fuel Price'>
                <input type='submit' value='Change Price' class='btn btn-info'>
            </form><?php
}


function fuelcostdo() {
    global $db,$set,$ir;
    $newCost = $_POST['addfuelprice'];
    $currentCostPoints = $set['fuelcosttrack'];
    $currentCostPointsArray = explode(",", $currentCostPoints);
    
    if (count($currentCostPointsArray) > 50) {
        $currentCostPoints = $currentCostPointsArray[count($currentCostPointsArray - 49)];
        for ($i = count($currentCostPointsArray - 49); i < count($currentCostPointsArray) - 1; $i++) {
            $currentCostPoints = $currentCostPoints.",".$currentCostPointsArray[$i];
        }
    } else {
        $currentCostPoints = $currentCostPoints.",".$newCost;
    }
    
    $db->query("UPDATE settings SET conf_value=$newCost WHERE conf_name='fuelcost'");
    $db->query("UPDATE settings SET conf_value='$currentCostPoints' WHERE conf_name='fuelcosttrack'");
    
    echo "Fuel price set to $".$newCost;
    $whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Updated Fuel Costs',unix_timestamp())");
}




function addplane() {
    global $db,$set,$ir; ?>

<form action="?a=addplanedo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <table width="100%">
            <tr>
                <td>
                    <input type="text" class="form-control" name="planeMAKE" placeholder="Plane Make">
                    <input type="text" class="form-control" name="planeMODEL" placeholder="Plane Model">
                    <input type="text" class="form-control" name="planeIMAGE" placeholder="Plane Image default is ../images/planes/PLANEID.png">
                    <input type="text" class="form-control" name="planePASSENGER" placeholder="Plane Max Passengers">
                    <input type="text" class="form-control" name="planeFUEL" placeholder="Plane Max Fuel in Litres">
                    <input type="text" class="form-control" name="planeSPEED" placeholder="Plane Max Speed in KM/H">
                    <input type="text" class="form-control" name="planeDISTANCE" placeholder="Plane Max Distance in KM">
                    <input type="text" class="form-control" name="planeCOST" placeholder="Plane Cost - Set to 0 If Premium Plane">
                    <input type="text" class="form-control" name="premiumcost" placeholder="Plane Cost - Premium - Airbucks">
                    <input type="text" class="form-control" name="planeWEIGHT" placeholder="Plane Max Weight in KG">
                    <input type="text" class="form-control" name="planeCONSUMPTIONRATE" placeholder="Plane Fuel Consumption Rate in Litres / H">
                    <select name='planeACTIVE' type='dropdown' class="form-select">
                        <option value="1">Plane Available to Buy</option>
                        <option value="0">Place Unavailable to Buy</option>
                    </select>
                
                
                </td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Add Plane" class="btn btn-info"></td>
            </tr>
        </table>
        </form>
<?php    
}

function addplanedo() {
global $db,$set,$ir;
$planeMODEL = $_POST['planeMODEL'];
$planeMAKE = $_POST['planeMAKE'];
$planeIMAGE = $_POST['planeIMAGE'];
$planePASSENGER = $_POST['planePASSENGER'];
$planeFUEL = $_POST['planeFUEL'];
$planeSPEED = $_POST['planeSPEED'];
$planeDISTANCE = $_POST['planeDISTANCE'];
$planeCOST = $_POST['planeCOST'];
$planeWEIGHT = $_POST['planeWEIGHT'];
$planeCONSUMPTIONRATE = $_POST['planeCONSUMPTIONRATE'];
$planeACTIVE = $_POST['planeACTIVE'];
$premiumcost = $_POST['premiumcost'];

$db->query("INSERT INTO `airplanes`(`planeID`, `planeMODEL`, `planeMAKE`, `planeIMAGE`, `planePASSENGER`, `planeFUEL`, `planeSPEED`, `planeDISTANCE`, `planeCOST`, `planeWEIGHT`, `planeCONSUMPTIONRATE`, `planeACTIVE`,`premiumcost`) VALUES ('','$planeMODEL','$planeMAKE','$planeIMAGE','$planePASSENGER','$planeFUEL','$planeSPEED','$planeDISTANCE','$planeCOST','$planeWEIGHT','$planeCONSUMPTIONRATE','$planeACTIVE','$premiumcost')");
    echo 'Plane Added';
    $whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Added a Plane',unix_timestamp())");
}



function editplaneselect() {
    global $db,$set,$ir;
    $getinfo = $db->query("SELECT * FROM `airplanes` ORDER BY planeID ASC"); ?>
        <form action="?a=editplaneedit&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <table width="100%">
            <tr>
                <td><select name='planeid' type='dropdown' class="form-select"><?php
        while($data=$db->fetch_row($getinfo)) { ?>
            <option value="<?php echo $data['planeID']; ?>"><?php echo $data['planeMAKE'].' - '.$data['planeMODEL']; ?> - (<?php echo $data['planeID']; ?>)</option>
        <?php } ?></select></td>
                <td><input type="submit" name="submit" value="Select Plane" class="btn btn-info"></td>
            </tr>
        </table>
        </form><?php 
}



function editplaneedit() {
    global $db,$set,$ir;
    $planeid = $_POST['planeid'];
    $getinfo = $db->query("SELECT * FROM airplanes WHERE planeID=$planeid");
    $plane = $db->fetch_row($getinfo); ?>

    <form action="?a=editplanedo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <table width="100%">
            <tr>
                <td></td>
                <td><input type="hidden" name="planeID" value="<?php echo $plane['planeID']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Make</u></b>:</td>
                <td><input type="text" class="form-control" name="planeMAKE" value="<?php echo $plane['planeMAKE']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Model</u></b>:</td>
                <td><input type="text" class="form-control" name="planeMODEL" value="<?php echo $plane['planeMODEL']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Image</u></b>:</td>
                <td><input type="text" class="form-control" name="planeIMAGE" value="<?php echo $plane['planeIMAGE']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Passengers</u></b>:</td>
                <td><input type="text" class="form-control" name="planePASSENGER" value="<?php echo $plane['planePASSENGER']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Fuel</u></b>:</td>
                <td><input type="text" class="form-control" name="planeFUEL" value="<?php echo $plane['planeFUEL']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Speed</u></b>:</td>
                <td><input type="text" class="form-control" name="planeSPEED" value="<?php echo $plane['planeSPEED']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Distance</u></b>:</td>
                <td><input type="text" class="form-control" name="planeDISTANCE" value="<?php echo $plane['planeDISTANCE']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Costs</u></b>:</td>
                <td><input type="text" class="form-control" name="planeCOST" value="<?php echo $plane['planeCOST']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Premium Cost</u></b>:</td>
                <td><input type="text" class="form-control" name="premiumcost" value="<?php echo $plane['premiumcost']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Weight</u></b>:</td>
                <td><input type="text" class="form-control" name="planeWEIGHT" value="<?php echo $plane['planeWEIGHT']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Comsumption Rate</u></b>:</td>
                <td><input type="text" class="form-control" name="planeCONSUMPTIONRATE" value="<?php echo $plane['planeCONSUMPTIONRATE']; ?>"></td>
            </tr>
            <tr>
                <td><b><u>Place Active</u></b>:</td>
                <td><select name='planeACTIVE' type='dropdown' class="form-select">
                        <option value="1">Plane Active</option>
                        <option value="0">Place Disabled</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Edit Plane" class="btn btn-info"></td>
            </tr>
        </table>
        </form>
           <?php 
}


function editplanedo() {
global $db,$set,$ir;
$planeID = $_POST['planeID'];
$planeMODEL = $_POST['planeMODEL'];
$planeMAKE = $_POST['planeMAKE'];
$planeIMAGE = $_POST['planeIMAGE'];
$planePASSENGER = $_POST['planePASSENGER'];
$planeFUEL = $_POST['planeFUEL'];
$planeSPEED = $_POST['planeSPEED'];
$planeDISTANCE = $_POST['planeDISTANCE'];
$planeCOST = $_POST['planeCOST'];
$planeWEIGHT = $_POST['planeWEIGHT'];
$planeCONSUMPTIONRATE = $_POST['planeCONSUMPTIONRATE'];
$planeACTIVE = $_POST['planeACTIVE'];
$premiumcost = $_POST['premiumcost'];
$db->query("UPDATE airplanes SET planeMODEL='$planeMODEL', planeMAKE='$planeMAKE',planeIMAGE='$planeIMAGE',planePASSENGER='$planePASSENGER', planeFUEL='$planeFUEL',
                                 planeDISTANCE='$planeDISTANCE',planeCOST='$planeCOST',planeWEIGHT='$planeWEIGHT', planeCONSUMPTIONRATE='$planeCONSUMPTIONRATE', 
                                 planeACTIVE='$planeACTIVE', premiumcost='$premiumcost' WHERE planeID=$planeID");
   echo 'Plane Edited';
   $whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Edited Airplane ID: $planeID',unix_timestamp())");
}


function showdeleted()
{
global $db,$set,$ir;
$anpdata=$db->query("SELECT * FROM chat_box_deleted ORDER BY chat_id DESC LIMIT 25"); ?>
<table width="100%">
    <tr>
        <td><b>Who Posted</b></td>
        <td><b>Time</b></td>
        <td><b>Message</b></td>
        <td><b>Deleted By</b></td>
        <td><b>Options</b></td>
    </tr>
<?php
while($npdata=$db->fetch_row($anpdata))
{
    $user=$npdata['chat_user'];
    $q=$db->query("SELECT * FROM users WHERE userid=$user");
    
    $getuser = $db->query("SELECT * FROM users WHERE userid=$user");
    $r = $db->fetch_row($getuser);
        $msg = $npdata['chat_text'];

        $times = date("H:i",$npdata['chat_time']);

        $time = date('H:i', strtotime($times. ' + 1 hours'));
        
        $msg = $npdata['chat_text'];
        
        $who = $npdata['deletedby'];
        $fetch = $db->query("SELECT * FROM users WHERE userid=$who");
        $disname = $db->fetch_row($fetch);
        

    echo "
        <tr>
            <td>{$r['username']} </td>
            <td>$time</td>
            <td><font color='black'>".$msg."</font></td>
            <td>".$disname['username']."</td>
            <td><a href='?a=delchat&id=".$npdata['chat_id']."'><font color='black'>[X]</span></a></td>
        </tr>";
}

echo "</table>";
$whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Viewed Deleted Logs',unix_timestamp())");
}



function delchat()
{
    global $db;
    $id = $_GET['id'];
    echo '
    <div class="alert alert-danger">';
    echo 'DELETE CHAT ID:'.$id.'<br />Chat Deleted.<br />
        <a href="?a=showdeleted">Continue</a>
    </div>';
    $db->query("DELETE FROM `chat_box_deleted` WHERE chat_id=$id"); // Delete Completely
    $whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Deleted a Chat Post',unix_timestamp())");
}






function resetaccount() {
    global $db,$set,$ir;
        $getinfo = $db->query("SELECT * FROM `users` ORDER BY userid ASC"); ?>
        <form action="?a=resetaccountdo&u=<?php echo $_GET['u']; ?>" target="staffpanel" method="post">
        <table width="100%">
            <tr>
                <td><select name='userid' type='dropdown' class="form-select"><?php
        while($data=$db->fetch_row($getinfo)) { ?>
            <option value="<?php echo $data['userid']; ?>"><?php echo $data['username'].' - '.$data['userid']; ?></option>
        <?php } ?></select></td>
                <td><input type="submit" name="submit" value="RESET USER" class="btn btn-info"></td>
            </tr>
        </table>
        </form>
        </form>
        <?php
}


function resetaccountdo() {
    global $db,$set,$ir;
        $user = $_POST['userid'];
        $db->query("UPDATE users SET bucks='15000000', airbucks='0', airlinehq='0',airlinetraininghq='0',longitude='NULL',latitude='NULL',tlongitude='NULL',tlatitude='NULL',fuelstorage='0',fuelstoragemax='20000', premiumdays='0', theme='light',tickets1='68', reputation='30.00000', reputationa='30.00000', loan='0', fav='XXXX', box='1', alliance='0', allianceperc='0', totalmoney='0', totaldistance='0' WHERE userid=$user");
        $db->query("DELETE FROM `userairplanes` WHERE planeOWNER=$user");
        $db->query("DELETE FROM `activeflights` WHERE planeOWNER=$user");
        $db->query("DELETE FROM `money` WHERE userid=$user");
        $db->query("DELETE FROM `bankloans` WHERE userid=$user");
        echo 'Account Reset';
        $whos = $_GET['u'];
$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$whos','Reset Account ID: $user',unix_timestamp())");
}
echo '</td>
    </tr>
</table>';








?>
