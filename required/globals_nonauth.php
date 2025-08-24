<?php

if (strpos($_SERVER['PHP_SELF'], "globals_nonauth.php") !== false)
{
    exit;
}
session_name('MCCSID');
@session_start();
if (!isset($_SESSION['started']))
{
    session_regenerate_id();
    $_SESSION['started'] = true;
}
ob_start();

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
$settq = $db->query("SELECT *
					 FROM `settings`");
while ($r = $db->fetch_row($settq))
{
    $set[$r['conf_name']] = $r['conf_value'];
}
