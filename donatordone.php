<!DOCTYPE html>
<html style="height: 100%; width: 100%;" lang="en">
  <head>
   
    <link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
    	<link rel="stylesheet" href="css/windows.css">
	<link rel="stylesheet" href="required/src/window-engine.css">
	<link rel="stylesheet" href="css/login.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
<center>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<style>
    body {
        background-image: url(images/backer5.png);
        background-repeat: no-repeat;
        background-position: right 50px;
        zoom: 100%;
        
    }
    
</style>
<div class="container-fluid" style="margin-top:150px">
<div class="row">
  <div class="col-sm-4"></div>
  <div class="col-sm-4 border bg-dark text-white opacity-85"><br /><br />


<?php
session_start();
require "lib/basic_error_handler.php";
set_error_handler('error_php');
include "required/config.php";
define("MONO_ON", 1);
require "class/class_db_{$_CONFIG['driver']}.php";
require_once('required/global_func.php');
$db = new database;
$db->configure($_CONFIG['hostname'], $_CONFIG['username'],
        $_CONFIG['password'], $_CONFIG['database'], $_CONFIG['persistent']);
$db->connect();
$c = $db->connection_id;
$set = array();
$settq = $db->query("SELECT * FROM `settings`");
while ($r = $db->fetch_row($settq))
{
    $set[$r['conf_name']] = $r['conf_value'];
}

$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'done': done(); break;
case 'cancel': cancel(); break;
default:index(); break;
}
?>
<title><?php echo $set['gamename']; ?></title>
<?php
function index() {
    global $db;
    echo "errr ok? how did you get here?<br />
    <a href='https://airlinemanagement.makeweb.games'>Continue</a><br />";
    ?>
    <META HTTP-EQUIV=Refresh CONTENT='10; URL=https://airlinemanagement.makeweb.games'>
    <?php
}

function cancel() {
    global $db;
    $user = $_GET['u'];
    $data = $db->query("SELECT * FROM users WHERE userid=$user");
    $ir = $db->fetch_row($data);
    
    echo "Well ".$ir['username'].", It seems you have cancelled your donation.<br />
    I'd like to hope that this is a mistake, but then it may not be aswell.<br />
    Well lets continue below to go back to the game. Maybe try again?<br />
    <a href='https://airlinemanagement.makeweb.games'>Continue</a><br />";
    ?>
    <META HTTP-EQUIV=Refresh CONTENT='10; URL=https://airlinemanagement.makeweb.games'>
    <?php
}


function done() {
    global $db,$set;
    
    
// AI rewrite    
$pack = $_GET['pack'];
$user = (int)$_GET['u'];

$packs = array(
    1 => 30,
    2 => 50,
    3 => 115,
    4 => 300,
    5 => 850,
    6 => 2000,
);

if (isset($packs[$pack])) {
    $output = $packs[$pack];
} else {
    // handle invalid pack value
    exit('Invalid pack value');
}

$data = $db->query("SELECT username FROM users WHERE userid=$user");
if ($db->num_rows($data) === 0) {
    // handle user not found
    exit('User not found');
}
$ir = $db->fetch_row($data);

$airbucks = $ir['airbucks'] + $output;
$db->query("UPDATE users SET airbucks=$airbucks WHERE userid=$user");

$message = "<b>Your Donation Credit:</b> You Received: $output Coins.";
$now = time();
$db->query("INSERT INTO `money`(`userid`, `outin`, `amount`, `item`, `date`, `type`) 
            VALUES ('$user', 'in', '$output', '$message', '$now', 'airbucks')");

$db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) 
            VALUES ('', '$user', 'User has Donated. Coins: $output', '$now')");

echo "Thank you ".$ir['username']." for your Donation.<br />
      <br />
      Your account has been credited with ".$output." Coins.<br />
      Your Donations Helps us keep this site running.<br />
      A big Thank you from ".$set['gamename']." team.<br /><Br />
      <a href='https://airlinemanagement.makeweb.games'>Continue</a><br />";

    
    
    
    
    
// original   
//    $pack = $_GET['pack'];
//    $user = $_GET['u'];
//    if($pack == '1') { $output="30"; }
//    if($pack == '2') { $output="50"; }
//    if($pack == '3') { $output="115"; }
//    if($pack == '4') { $output="300"; }
//    if($pack == '5') { $output="850"; }
//    if($pack == '6') { $output="2000"; }
//
//    $data = $db->query("SELECT * FROM users WHERE userid=$user");
//    $ir = $db->fetch_row($data);
//    echo "Thank you ".$ir['username']." for your Donation.<br />
//    <br />
//    Your account has been credited with ".$output." Coins.<br />
//    Your Donations Helps us keep this site running.<br />
//    A big Thank you from ".$set['gamename']." team.<br /><Br />
//    <a href='https://airlinemanagement.makeweb.games'>Continue</a><br />";
//    $db->query("UPDATE users SET airbucks=airbucks+$output WHERE userid=$user");
//    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$user','in','$output','<b>Your Donation Credit:</b> You Received: $output Coins.',unix_timestamp(),'airbucks')");
//    $db->query("INSERT INTO `staff_log`(`id`, `who`, `action`, `date`) VALUES ('','$user','User has Donated. Coins: $output',unix_timestamp())");
    
    
    
    
    
    
    
    
    
    
    ?>
    <META HTTP-EQUIV=Refresh CONTENT='10; URL=https://airlinemanagement.makeweb.games'>
    <?php
}


?>
<br />
</div>
  <div class="col-sm-4"></div>
</div>


</div>
  <div class="col-sm-4"></div>
</div>


<script type="text/javascript" src="required/src/window-engine.js"></script>

</body>
</html>

