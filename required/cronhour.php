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






// Set airport population
$airports = $db->query("SELECT * FROM airports");
while ($a = $db->fetch_row($airports)) {
    $citypop = $a["citypop"];
    $airportpop = $a['airportpop'];
    $id = $a["id"];
    $rand1 = rand(1,100);
    $rand2 = rand(1,2);
    $increase = min((int) ($airportpop / $citypop * 100), 100);
    $increaseperc = 100 - $increase;
    if($rand2 == '1') { 
        $airport = $airportpop - $increaseperc - $rand1;
    } else { 
        $airport = $airportpop + $increaseperc + $rand1;
    }
    $db->query("UPDATE airports SET airportpop='$airport' WHERE id='$id'");
}

echo "SUCCESS";



?>