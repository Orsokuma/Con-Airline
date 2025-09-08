<?php
require_once('required/globals_nonauth.php');
// Check CSRF input

// Check username and password input
$email =
        (array_key_exists('email', $_POST) && is_string($_POST['email']))
                ? $_POST['email'] : '';
$password =
        (array_key_exists('password', $_POST) && is_string($_POST['password']))
                ? $_POST['password'] : '';
if (empty($email) || empty($password))
{
    die(
            "
	You did not fill in the login form!<br />
	<a href='login.php'>&gt; Back</a>");
}
$form_username = $db->escape(stripslashes($email));
$raw_password = stripslashes($password);
$uq =
        $db->query(
                "SELECT `userid`, `userpass`, `pass_salt`, `email`
                 FROM `users`
                 WHERE `email` = '$form_username'");
if ($db->num_rows($uq) == 0)
{
    $db->free_result($uq);
    die(
            "
	Invalid username or password!<br />
	<a href='login.php'>&gt; Back</a>");
}
else
{
    $mem = $db->fetch_row($uq);
    $db->free_result($uq);
    $login_failed = false;
    // Pass Salt generation: autofix
    if (empty($mem['pass_salt']))
    {
        if (md5($raw_password) != $mem['userpass'])
        {
            $login_failed = true;
        }
        $salt = generate_pass_salt();
        $enc_psw = encode_password($mem['userpass'], $salt, true);
        $e_salt = $db->escape($salt); // in case of changed salt function
        $e_encpsw = $db->escape($enc_psw); // ditto for password encoder
        $db->query(
                "UPDATE `users`
        		 SET `pass_salt` = '{$e_salt}', `userpass` = '{$e_encpsw}'
        		 WHERE `userid` = {$mem['userid']}");
    }
    else
    {
        $login_failed =
                !(verify_user_password($raw_password, $mem['pass_salt'],
                        $mem['userpass']));
    }
    if ($login_failed)
    {
        die("Invalid username or password!<br />
		<a href='login.php'>&gt; Back</a>");
    }
    session_regenerate_id();
    $_SESSION['loggedin'] = 1;
    $_SESSION['userid'] = $mem['userid'];
    $loggedin_url = 'https://' . determine_game_urlbase();
    header("Location: {$loggedin_url}");
    exit;
}
