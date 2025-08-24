<?php
session_name('MCCSID');
session_start();
if (!isset($_SESSION['started']))
{
    session_regenerate_id();
    $_SESSION['started'] = true;
}
require_once('required/global_func.php');
if (isset($_SESSION['userid']))
{
    $sessid = (int) $_SESSION['userid'];
        require_once('required/globals_nonauth.php');
        session_regenerate_id(true);
        session_unset();
        session_destroy();
        $login_url = 'https://' . determine_game_urlbase() . '/login.php';
header("Location: {$login_url}");
}
session_unset();
$login_url = 'https://' . determine_game_urlbase() . '/login.php';
header("Location: {$login_url}");
