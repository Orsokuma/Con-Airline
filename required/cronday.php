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

// Daily Rewards
$db->query("UPDATE users SET box=0 WHERE box=1 AND laston<unix_timestamp()-1440*60");    



// Premium Day Reductions
$user_update_query = "UPDATE `users` SET `premiumdays` = `premiumdays` - IF(`premiumdays` > 0, 1, 0)";
$db->query($user_update_query);

// Repuation Deductions    
$deduct = '0.10000';
$db->query("UPDATE users SET reputation=reputation-$deduct WHERE reputation>=15 AND laston<unix_timestamp()-1440*60");





//    $airportpop = rand($citypop * 0.01, $citypop * 0.95);
//    $db->query("UPDATE airports SET airportpop=$airportpop WHERE id=$id");

    
    

echo 'Success';
?>















