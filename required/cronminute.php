<?php
session_start();
require "../lib/basic_error_handler.php";
//set_error_handler('../error_php');
include "../required/config.php";
define("MONO_ON", 1);
require "../class/class_db_{$_CONFIG['driver']}.php";
require_once('../required/global_func.php');
$db = new database;
$db->configure($_CONFIG['hostname'], $_CONFIG['username'],
        $_CONFIG['password'], $_CONFIG['database'], $_CONFIG['persistent']);
$db->connect();
$c = $db->connection_id;
$set = array();
$settq = $db->query("SELECT * FROM `settings`");
while ($r = $db->fetch_row($settq)) {
    $set[$r['conf_name']] = $r['conf_value'];
}

// START CRON


$getplane = $db->query("SELECT * FROM activeflights");
while ($plane = $db->fetch_row($getplane)) {
    
    
    $currenttime = intval(time());
    $flightend = $plane['flightEndTime'];
    echo $flightend.", ".$currenttime.", ".($flightend >= '$currenttime')."<br />";
    
    if($flightend <= $currenttime) {
        $weightout = $db->fetch_row($db->query("SELECT * FROM userairplanes WHERE id=".$plane['planeID']))['planeFUELCURRENT']*0.819;
        $db->query("DELETE FROM activeflights WHERE planeID=".$plane['planeID']);
        $db->query("UPDATE userairplanes SET planeACTIVE=0,planePASSENGERCURRENT=0, planeCURRENTWEIGHT=$weightout WHERE id=".$plane['planeID']);
        echo 'Cron Successful';
    }
}

?>