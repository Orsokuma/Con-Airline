<?php
require_once 'googleAPI/vendor/autoload.php';
require_once('required/globals_nonauth.php');

$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
    case 'formSubmit':
        formSubmit();
        break;
    default:
        index();
        break;
}

function index()
{
    global $db, $set;

// Get $id_token via HTTPS POST.
    $id_token  = $_POST['credential'];
    $CLIENT_ID = 'YOURGOOGLECLIENTIDHERE';

    $client  = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
    $payload = $client->verifyIdToken($id_token);

    if ($payload) {
        $userid = $payload['sub'];
        $email  = $db->escape(stripslashes($payload['email']));
        $d      = $db->query("SELECT `email` FROM `users` WHERE email='$email'");
        $data   = $db->fetch_row($d);
        if ($data) {
            $form_username = $db->escape(stripslashes($email));
            $uq            = $db->query("SELECT * FROM `users` WHERE email='$form_username'");
            $mem           = $db->fetch_row($uq);
            $email         = $payload['email'];
            $d             = $db->query("SELECT `email` FROM `users` WHERE email='$email'");
            $data          = $db->fetch_row($d);
            session_regenerate_id();
            $_SESSION['loggedin'] = 1;
            $_SESSION['userid']   = $mem['userid'];
            $loggedin_url         = 'https://' . determine_game_urlbase() . '';
            header("Location: {$loggedin_url}");
            exit;
        } else {
            include 'required/headerout.php';
            $back = rand(1, 4);
            ?>
            <title>Airline Management</title>
            <link rel="icon" href="/images/favicon.ico" type="image/x-icon"/>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <link href="/css/bootstrap.min.css" rel="stylesheet">
            <script src="/required/src/bootstrap-bundle-5.2.1.min.js"></script>
            <link rel="stylesheet" href="/required/src/window-engine.css">
            <link rel="stylesheet" href="/css/login.css">

            <style>
              body {
                background-image:    url(/images/backer<?php echo $back; ?>.png);
                background-repeat:   no-repeat;
                background-position: right 50px;
                zoom:                100%;

              }

            </style>

            <div class="container-fluid" style="margin-top:250px">
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 border bg-dark text-white opacity-85">
                        <form action="?a=formSubmit" method=post>
                            <table>
                                <tr>
                                    <td><font size="+2"><b><u>Your Details</u></b></font></td>
                                </tr>
                                <input type='hidden' value="<?php echo $payload['email']; ?>" name='email'>
                                <input type="hidden" value="<?php echo $payload['picture']; ?>" name="picture">
                                <tr>
                                    <td>
                                        <div class='input-group mb-3'>
                                            <button class='btn btn-outline-primary' type='button'>Username</button>
                                            <input type='text' class='form-control' value="<?php echo $payload['name']; ?>" name='username'></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <hr/>
                                    </td>
                                </tr>
                                <tr>
                                    <td><font size="+2"><b><u>Your Airline Details</u></b></font></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class='input-group mb-3'>
                                            <button class='btn btn-outline-primary' type='button'>Airline Name</button>
                                            <input type='text' class='form-control' name='airlinename'></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class='input-group mb-3'>
                                            <button class='btn btn-outline-primary' type='button'>Airline Colour</button>
                                            <input type="color" class="form-control form-control-color" name="airlinecolour" value="#CCCCCC"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type='submit' class='btn btn-info' value='Register'/></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

            <script type="text/javascript" src="required/src/window-engine.js"></script>
            <?php
        }
    }
}

function formSubmit()
{
    global $db;

    $salt          = 'NONE';
    $e_salt        = 'NONE';
    $encpsw        = 'NONE';
    $e_encpsw      = 'NONE';
    $e_email       = $db->escape($_POST['email']);
    $e_username    = $db->escape($_POST['username']);
    $airlinename   = $db->escape($_POST['airlinename']);
    $airlinecolour = $db->escape($_POST['airlinecolour']);
    $picture       = $_POST['picture'];
    $joineddate    = time();
    $db->query("INSERT INTO `users`
             (`userid`,`username`, `userpass`, `email`, `login_name`, `pass_salt`, `staff`, `level`, `bucks`, `airbucks`, `profileimage`, `airlinename`, `airlinecolour`, `joineddate`) VALUES 
             ('','{$e_username}','{$e_encpsw}','{$e_email}','{$e_username}','{$e_salt}','0','1','15000000','0', '{$picture}','{$airlinename}','{$airlinecolour}','{$joineddate}')");
    echo "<br /><br /><br />You have signed up, enjoy the game.<br />
	&gt; <a href='login.php'>Login</a>";
}
