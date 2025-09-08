<?php

if (strpos($_SERVER['PHP_SELF'], "globals.php") !== false)
{
    exit;
}
session_name('MCCSID');
session_start();
if (!isset($_SESSION['started']))
{
    session_regenerate_id();
    $_SESSION['started'] = true;
}
ob_start();

require "lib/basic_error_handler.php";
//set_error_handler('error_php');
require "global_func.php";
$domain = determine_game_urlbase();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == 0)
{
    $login_url = "https://{$domain}/login.php";
    header("Location: {$login_url}");
    exit;
}
$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;

//get the game layout and data. all the important stuff
require "header.php";

include "config.php";
define("MONO_ON", 1);
require "class/class_db_{$_CONFIG['driver']}.php";
$db = new database;
$db->configure($_CONFIG['hostname'], $_CONFIG['username'],
        $_CONFIG['password'], $_CONFIG['database']);
$db->connect();
$c = $db->connection_id;
$set = array();
$settq = $db->query("SELECT *
					 FROM `settings`");
while ($r = $db->fetch_row($settq))
{
    $set[$r['conf_name']] = $r['conf_value'];
}

check_level();
$h = new headers;
if (isset($nohdr) == false || !$nohdr)
{
    $h->startheaders();
    $fm = '0';
    $cm = '0';
    global $atkpage;
    if ($atkpage)
    {
        $h->userdata($ir, $fm, $cm, 0);
    }
    else
    {
        $h->userdata($ir, $fm, $cm);
    }
}
