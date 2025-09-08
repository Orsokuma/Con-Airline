<?php
require_once('required/globals_nonauth.php');

//die("DISABLED");


function valid_email($email)
{
    return (filter_var($email, FILTER_VALIDATE_EMAIL) === $email);
}
print
        <<<EOF
        <!DOCTYPE html>
<html style="height: 100%; width: 100%;" lang="en">
  <head>
    <title>Airline Management</title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>

<center>



<nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
<div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
          
        <li class="nav-item">
          <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#myModal" href="login.php"><b>Airline Management</b></button>
        </li>
      </ul>
      <b><span id='ct7'></span></b>
    </div>
  </div>
</nav>




<nav class="navbar navbar-expand-sm bg-light navbar-light fixed-bottom">
<div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav=item">
           <span id="button3" class="btn btn-light">DMCA</span>
        </li>
        <li class="nav=item">
           <span id="button4" class="btn btn-light">CONTACT</span>
        </li>
        <li class="nav=item">
           <span id="button5" class="btn btn-light">FAQ</span>
        </li>
        <li class="nav=item">
           <span id="button6" class="btn btn-light">ToS</span>
        </li>
        <li class="nav=item">
           <span id="button7" class="btn btn-light">Privacy</span>
        </li>
      </ul>
      Airline Management - ©2023 - Founders: Peterisgb & IntelliRon
    </div>
  </div>
</nav>


<script>
function display_ct7() {
    var x = new Date().toLocaleString("en-UK", {timeZone: "Europe/London"})
    document.getElementById('ct7').innerHTML = x.split(", ")[1];
    display_c7();
}
function display_c7(){
    var refresh=1000; // Refresh rate in milli seconds
    mytime=setTimeout('display_ct7()',refresh);
}
display_ct7();
display_c7()
</script>





EOF;

$IP = str_replace(array('/', '\\', '\0'), '', $_SERVER['REMOTE_ADDR']);
if (file_exists('ipbans/' . $IP))
{
    die(
            "<span style='font-weight: bold; color:red;'>
            Your IP has been banned, there is no way around this.
            </span></body></html>");
}
$username =
        (isset($_POST['username'])
                && preg_match(
                        "/^[a-z0-9_]+([\\s]{1}[a-z0-9_]|[a-z0-9_])+$/i",
                        $_POST['username'])
                && ((strlen($_POST['username']) < 32)
                        && (strlen($_POST['username']) >= 3)))
                ? stripslashes($_POST['username']) : '';
if (!empty($username))
{
    if (!isset($_POST['email']) || !valid_email(stripslashes($_POST['email'])))
    {
        echo "Sorry, the email is invalid.<br />
		&gt; <a href='register.php'>Back</a>";
        register_footer();
    }
    // Check Gender
    $sm = 100;
    if (isset($_POST['promo']) && $_POST['promo'] == "Your Promo Code Here")
    {
        $sm += 100;
    }
    $e_username = $db->escape($username);
    $e_email = $db->escape(stripslashes($_POST['email']));
    $q =
            $db->query(
                    "SELECT COUNT(`userid`)
                     FROM `users`
                     WHERE `username` = '{$e_username}'
                     OR `login_name` = '{$e_username}'");
    $q2 =
            $db->query(
                    "SELECT COUNT(`userid`)
    				 FROM `users`
    				 WHERE `email` = '{$e_email}'");
    $u_check = $db->fetch_single($q);
    $e_check = $db->fetch_single($q2);
    $db->free_result($q);
    $db->free_result($q2);
    $base_pw =
            (isset($_POST['password']) && is_string($_POST['password']))
                    ? stripslashes($_POST['password']) : '';
    $check_pw =
            (isset($_POST['cpassword']) && is_string($_POST['cpassword']))
                    ? stripslashes($_POST['cpassword']) : '';
    if ($u_check > 0)
    {
        echo "Username already in use. Choose another.<br />
		&gt; <a href='register.php'>Back</a>";
    }
    else if ($e_check > 0)
    {
        echo "E-Mail already in use. Choose another.<br />
		&gt; <a href='register.php'>Back</a>";
    }
    else if (empty($base_pw) || empty($check_pw))
    {
        echo "You must specify your password and confirm it.<br />
		&gt; <a href='register.php'>Back</a>";
    }
    else if ($base_pw != $check_pw)
    {
        echo "The passwords did not match, go back and try again.<br />
		&gt; <a href='register.php'>Back</a>";
    }
    else
    {
        $_POST['ref'] =
                (isset($_POST['ref']) && is_numeric($_POST['ref']))
                        ? abs(intval($_POST['ref'])) : '';
        $IP = $db->escape($_SERVER['REMOTE_ADDR']);
        $salt = generate_pass_salt();
        $e_salt = $db->escape($salt);
        $encpsw = encode_password($base_pw, $salt);
        $e_encpsw = $db->escape($encpsw);
        $airlinename = $db->escape($_POST['airlinename']);
        $airlinecolour = $db->escape($_POST['airlinecolour']);
        $joineddate = time();
        $db->query("INSERT INTO `users`
                 (`username`, `userpass`, `email`, `login_name`, `pass_salt`, `staff`, `level`, `bucks`, `airbucks`, `airlinename`, `airlinecolour`, `joineddate`) VALUES 
                 ('{$e_username}','{$e_encpsw}','{$e_email}','{$e_username}','{$e_salt}','0','1','15000000','0','{$airlinename}','{$airlinecolour}','{$joineddate}')");
        echo "<br /><br /><br />You have signed up, enjoy the game.<br />
		&gt; <a href='login.php'>Login</a>";
    }
}
else
{

    echo "<div class='container-fluid' style='margin-top:80px'>";
}
register_footer();

function register_footer()
{
    print
            <<<OUT



</center>
</body>
</html>
OUT;
    exit;
}
