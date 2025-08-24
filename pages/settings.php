<style>
    input[type="radio"]+label > img {
        border-radius: 50%;
        border: 0px solid skyblue;
        display: inline;
        
    }

    input[type="radio"]:checked+label > img {
        border-radius: 50%;
        border: 5px solid skyblue;
        
    }
</style>
<?php include "../pages/dbconnect.php";
$theme = 'dark';
$userid = $_GET['u'];
$getir = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($getir);
?>





<table width="100%" height="100%" border="1">
    <tr>
        <td width="19%" valign="top">
                <div class="container mt-3">
                  <ul class="nav flex-column">
                    <li class="nav-item"><h4>Options Menu</h4></li>
                    <li class="nav-item"><form action="?a=index&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Settings Index" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                    <li class="nav-item"><form action="?a=changeprofileimage&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Profile Picture" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                    <li class="nav-item"><form action="?a=namechange&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Username Change" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                    <li class="nav-item"><form action="?a=passchange&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Change Password" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                    <li class="nav-item"><form action="?a=fav&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Favourite Airport" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                    <li class="nav-item"><form action="?a=changecompanyimage&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Company Picture" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                    <li class="nav-item"><form action="?a=companycolour&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Company Colour" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                    <li class="nav-item"><form action="?a=deleteaccount&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Delete Account" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                <?php if($ir['premiumdays'] >= '1') { ?>
                    <li class="nav-item"><form action="?a=navcolour&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Game Theme" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>
                <?php } else { ?>  
                    <li class="nav-item"><form action="#"><input type="submit" name="" value="Game Theme" class="btn btn-<?php echo $theme; ?> col-12 btn-sm" disabled></form></li>
                <?php } ?>
                
                </ul></div>
        </td>
        <td width="1%"></td>
        <td width="80%" valign="top"><br />






<?php


$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'changeprofileimage': changeprofileimage(); break;
case 'changeprofileimagedo': changeprofileimagedo(); break;
case 'changecompanyimage': changecompanyimage(); break;
case 'changecompanyimagedo': changecompanyimagedo(); break;
case 'companycolour': companycolour(); break;
case 'companycolourdo': companycolourdo(); break;
case 'navcolour': navcolour(); break;
case 'navcolourdo': navcolourdo(); break;
case 'deleteaccount': deleteaccount(); break;
case 'deleteaccountdo': deleteaccountdo(); break;
case 'passchange2': do_pass_change(); break;
case 'passchange': pass_change(); break;
case 'namechange2': do_name_change(); break;
case 'namechange': name_change(); break;
case 'fav': fav(); break;
case 'favdo': favdo(); break;
default:index(); break;
}







function index()
{
global $db;
$userid = $_GET['u'];
$dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($dbbd);
?>

<table width="100%" border="0">
    
    <tr>
        <td colspan="3"><h4>Your Game Settings</h4></td>
    </tr>

    <tr>
        <td width="4%"></td>
        <td width="48%"><b>Setting</b></td>
        <td width="48%"><b>Current</b></td>
    </tr>
    
    <tr>
        <td></td>
        <td>Display Picture</td>
        <td><img src="<?php echo $ir['profileimage']; ?>" width="100px"></td>
    </tr>
    
    <tr>
        <td></td>
        <td>Username Change</td>
        <td><?php echo $ir['username']; ?></td>
    </tr>
    
    <tr>
        <td></td>
        <td>Password Change</td>
        <td>********</td>
    </tr>
    
    <tr>
        <td></td>
        <td>Favourite Airport</td>
        <td><?php echo $ir['fav']; ?></td>
    </tr>
    
    
    <tr>
        <td colspan="3"><h4>Premium Settings</h4></td>
    </tr>
    <?php if($ir['premiumdays'] >= '1') { 
        $themedisplay = $ir['theme'];
        if($themedisplay == 'primary') { $themecol = 'Navy Blue'; }
        if($themedisplay == 'secondary') { $themecol = 'Grey'; }
        if($themedisplay == 'success') { $themecol = 'Green'; }
        if($themedisplay == 'info') { $themecol = 'Light Blue'; }
        if($themedisplay == 'warning') { $themecol = 'Orange'; }
        if($themedisplay == 'danger') { $themecol = 'Red'; }
        if($themedisplay == 'dark') { $themecol = 'Black'; }
        if($themedisplay == 'light') { $themecol = 'White'; }
        ?>
    <tr>
        <td></td>
        <td>Website Colour</td>
        <td><button type="button" class="btn btn-<?php echo $ir['theme']; ?>"><?php echo $themecol; ?></button></td>
    </tr>
    <?php } else { ?>  
    <tr>
        <td></td>
        <td>Website Colour</td>
        <td><button type="button" class="btn btn-<?php echo $ir['theme']; ?>"><?php echo $themecol; ?></button></td>
    </tr>
    <?php } ?>
    
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    
    
    <tr>
        <td colspan="3"><h4>Your Company Settings</h4></td>
    </tr>

    <tr>
        <td></td>
        <td>Company Picture</td>
        <td><img src="<?php echo $ir['airlineimage']; ?>" width="100px"></td>
    </tr>
    <tr>
        <td></td>
        <td>Company Colour</td>
        <td><font color="<?php echo $ir['airlinecolour']; ?>"><?php echo $ir['airlinecolour']; ?></font></td>
    </tr>
    
    
</table>

<?php
}



function name_change()
{
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    ?>
	<h3>Name Change</h3>
	<form action='?a=namechange2&u=<?php echo $ir['userid'];?>' method='post' target="settings">
        Current Name: <?php echo $ir['username']; ?><Br />
    	New Name: <input type='text' name='newname' class="form-control" />
    	<br />
    	<input type="hidden" name="userid" value="<?php echo $userid;?>">
    	<input type='submit' value='Change Name' class="btn btn-info" />
	</form>
   	<?php
}


function do_name_change()
{
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $_POST['newname'] = (isset($_POST['newname']) && is_string($_POST['newname'])) ? stripslashes($_POST['newname']) : '';
    if (empty($_POST['newname'])) { ?>
		You did not enter a new username.<br />
		&gt; <a href="?a=namechange&u=<?php echo $userid; ?>" target="settings">Back</a><?php
    }
    elseif (((strlen($_POST['newname']) > 32)
            OR (strlen($_POST['newname']) < 3)))
    { echo '
		Usernames can only be a max of 32 characters or a min of 3 characters.<br />
		&gt; <a href="?a=namechange&u='.$userid.'" target="settings">Back</a>';
    }
    if (!preg_match("/^[a-z0-9_]+([\\s]{1}[a-z0-9_]|[a-z0-9_])+$/i",
            $_POST['newname']))
    {
        echo '
		Your username can only consist of Numbers, Letters, underscores and spaces.<br />
		&gt; <a href="?a=namechange&u='.$userid.'" target="settings">Back</a>';
    }
    $check_ex = $db->query('SELECT `userid` FROM `users` WHERE `username` = "' . $db->escape($_POST['newname']).'"');
    if ($db->num_rows($check_ex) > 0) {
        echo '
		This username is already in use.<br />
		&gt; <a href="?a=index&u='.$userid.'" target="settings">Back</a>';
    }
    $_POST['newname'] = $db->escape(htmlentities($_POST['newname'], ENT_QUOTES, 'ISO-8859-1'));
    $db->query("UPDATE `users` SET `username` = '{$_POST['newname']}' WHERE `userid` = $userid");
    echo "Username changed!";
}





function deleteaccount() {
    global $db;
    $userid = $_POST['u'];
        $getinfo = $db->query("SELECT * FROM users WHERE userid=$userid");
        $data=$db->fetch_row($getinfo);
        ?>
        Do you want to Delete your account?<Br />
        Type YES into the Textbox to confirm you want to Delete your account.<br />
        <form action="?a=deleteaccountdo&u=<?php echo $ir['userid'];?>" target="settings" method="post">
        <table width="100%">
            <tr>
                <td><input type="hidden" name="userid" value="<?php echo $userid;?>">
                    <input type="text" name="confirm" placeholder="Type YES to Confirm Deletion of your account" class="form-control"></td>
                <td><input type="submit" name="submit" value="Delete Account" class="btn btn-info"></td>
            </tr>
        </table>
        </form><br />
        <a href='?a=index&=<?php echo $userid; ?>'>Back to Settings</a>
        <?php
}

function deleteaccountdo() {
    global $db;
        $user = $_POST['userid'];
        $confirm = $_POST['confirm'];
        
        if($confirm == 'YES') { 
            echo 'Your Account has been deleted.<br />
            Thanks for Playing!<br />
            <br />
            <a href="https://airlinemanagement.makeweb.games/login.php" target="_parent">Click to EXIT GAME</a>';
        $db->query("DELETE FROM `users` WHERE userid=$user");
        $db->query("DELETE FROM `userairplanes` WHERE planeOWNER=$user");
        $db->query("DELETE FROM `activeflights` WHERE planeOWNER=$user");
        $db->query("DELETE FROM `money` WHERE userid=$user");
        $db->query("DELETE FROM `bankloans` WHERE userid=$user");
        } else {
            echo "You Didn't type out the Form Correctly.<br />
            <a href='?a=index&u=".$user."'>Back to Settings</a>";
        }
}





function pass_change()
{
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    
    if ($ir['userpass'] == 'NONE') {
        echo 'You have set up your account via Google Login.<nt /> This Means a Password is not Setable.';
    } else {
    ?>
	<h3>Password Change</h3>
	<form action='?a=passchange2&u=<?php echo $ir['userid'];?>' method='post' target="settings">
	<input type="hidden" name="userid" value="<?php echo $userid;?>">
    	Current Password: <input type='password' name='oldpw' class="form-control" /><br />
    	New Password: <input type='password' name='newpw' class="form-control" /><br />
    	Confirm: <input type='password' name='newpw2' class="form-control" /><br />
    	<input type='submit' value='Change PW' class="btn btn-info" />
	</form>
   	<?php
    }
}

function do_pass_change()
{
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $oldpw = stripslashes($_POST['oldpw']);
    $newpw = stripslashes($_POST['newpw']);
    $newpw2 = stripslashes($_POST['newpw2']);
    if (!verify_user_password($oldpw, $ir['pass_salt'], $ir['userpass'])) {
        echo "The current password you entered was wrong.<br /><a href='?a=passchange&u=$userid' target='settings'>&gt; Back</a>";
    } else if ($newpw !== $newpw2) {
        echo "The new passwords you entered did not match!<br /><a href='?a=passchange&u=$userid' target='settings'>&gt; Back</a>";
    } else {
        // Re-encode password
        $new_psw = $db->escape(encode_password($newpw, $ir['pass_salt']));
        $db->query(
                "UPDATE `users`
                 SET `userpass` = '{$new_psw}'
                 WHERE `userid` = {$ir['userid']}");
        echo "Password changed!<br />
        &gt; <a href='?a=index&u=$userid' target='settings'>Go Back</a>";
    }
}




function changeprofileimage()
{
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
?>

        <form action="?a=changeprofileimagedo&u=<?php echo $ir['userid'];?>" method="post" target="settings">
        <table width="100%" border="1">
            <tr>
                <td width="75%"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" >
                <input type="text" name="image" placeholder="URL of image. e.g https://www.google.co.uk/image.png" target="settings" class="form-control"></td>
                <td width="25%"><input type="submit" name="" value="Set" class="btn btn-info"></td>
            </tr>
        </table>
        </form>

<?php
}



function changeprofileimagedo()
{
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $image = $db->escape($_POST['image']);
    
    $db->query("UPDATE users SET profileimage='$image' WHERE userid=$userid");
    
    echo "Image Set";
echo '<a href="https://airportmanagement.makeweb.games/" target="_parent"></a>';


}



function changecompanyimage()
{
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
?>

<form action="?a=changecompanyimagedo&u=<?php echo $ir['userid'];?>" method="post">
    <table width="100%" border="1" align="center">
        <tr>
            <td align="center">
                
                <input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings">
                <table>
                    <tr>
                        <td>
                            <?php $images = glob("../images/companies/*.png"); 
                            $picsPerLine = 5;
                            for($i = 0; $i < count($images); ++$i) {
                                if ($i % $picsPerLine == 0 && $i != 0) {
                                    echo "</tr><tr>";
                                }
                                
                                if ($i >= count($images) - (count($images) % $picsPerLine)) {
                                    if (count($images) % $picsPerLine == 1) {
                                        echo "<td></td><td></td>";
                                    } else if (count($images) % $picsPerLine == 2 || count($images) % $picsPerLine == 3) {
                                        echo "<td></td>";
                                    }
                                }
                                
                                $image = $images[$i];
                                $company = str_replace(['/', '.png', 'images', 'companies', '..'], '', $image);
                                echo "<td><input type='radio' name='companyImage' target='settings' id='".$company."' value='".$image."' style='visibility:hidden;'><label for='".$company."'><img src=".$image." width='100px'></label></td>";
                                
                                if ($i >= count($images) - (count($images) % $picsPerLine)) {
                                    if (count($images) % $picsPerLine == 1 || count($images) % $picsPerLine == 2) {
                                        echo "<td></td><td></td>";
                                    } else if (count($images) % $picsPerLine == 3 || count($images) % $picsPerLine == 4) {
                                        echo "<td></td>";
                                    }
                                }
                            }?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <?php if($ir['premiumdays'] >= '1') { ?>
        <tr>
            <td><input type="text" class="form-control" name="companyImage" value="<?php echo $ir['airlineimage']; ?>"></td>
        </tr>
        <?php } else { ?>  
        <tr>
            <td><input type="text" class="form-control" name="" value="Set Custom Company Image - Premium Option" disabled></td>
        </tr>
        <?php } ?>
        <tr>
            <td align="center">
                <input type="submit" name="" value="Set" class="btn btn-info">
            </td>
        </tr>
    </table>
</form>

<?php
}



function changecompanyimagedo()
{
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $image = $db->escape($_POST['companyImage']);
    
    $db->query("UPDATE users SET airlineimage='$image' WHERE userid=$userid");
    
    echo "Image Set<br />New Image:<br />";
    echo "<img src='$image'>";
echo '<a href="https://airportmanagement.makeweb.games/" target="_parent"></a>';


}




function companycolour()
{
	global $db;
	$userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
	?>
	<h3 class=fontface>Profile text Colour Change</h3>
	<br />
    <form action='?a=companycolourdo&u=<?php echo $ir['userid'];?>' target='settings' method='post'>
    <input type="hidden" name="userid" value="<?php echo $ir['userid'];?>">
    <input type="color" class="form-control form-control-color" name="colour" value="#CCCCCC">
    <input type='submit' value='Change Colour' class="btn btn-info" /></form>
<?php
}

function companycolourdo()
{
global $db,$ir,$c,$userid,$h;
$userid = $_POST['userid'];
$dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($dbbd);
$colour= $_POST['colour'];
$db->query("UPDATE users SET airlinecolour='{$colour}' WHERE userid=$userid");
print "colour changed!";
echo '<a href="https://airportmanagement.makeweb.games/" target="_parent"></a>';
}







function navcolour()
{
	global $db;
	$userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
	?>
	<h3 class=fontface>Navbar Colour Change</h3>
	<br />
    <form action='?a=navcolourdo&u=<?php echo $ir['userid'];?>' target='settings' method='post'>
    <input type="hidden" name="userid" value="<?php echo $ir['userid'];?>">

    <select class="form-select" name="colour">
        <option value="light">White</option>
        <option value="dark">Black</option>
        <option value="primary">Navy Blue</option>
        <option value="secondary">Grey</option>
        <option value="success">Green</option>
        <option value="info">Light Blue</option>
        <option value="warning">Orange</option>
        <option value="danger">Red</option>
    </select>

    <input type='submit' value='Change Colour' class="btn btn-info"/></form>
<?php
}

function navcolourdo()
{
global $db,$ir,$c,$userid,$h;
$userid = $_POST['userid'];
$dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($dbbd);
$colour= $_POST['colour'];
$db->query("UPDATE users SET theme='{$colour}' WHERE userid=$userid");
print "theme changed!";
echo '<a href="https://airportmanagement.makeweb.games/" target="_parent"></a>';
}









function fav() {
    global $db;
	$userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $getinfo = $db->query("SELECT * FROM `airports` ORDER BY name ASC"); 
    $ir = $db->fetch_row($dbbd);
	?>
	<h3 class=fontface>Favourite Airport ICAO</h3>
	<br />
    <form action='?a=favdo&u=<?php echo $ir['userid'];?>' target='settings' method='post'>
    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
    <select name='icao' type='dropdown' class="form-select"><?php
        while($data=$db->fetch_row($getinfo)) { ?>
            <option value="<?php echo $data['icao']; ?>"><?php echo $data['name'].' - '.$data['icao']; ?></option>
        <?php } ?></select>
    <input type='submit' value='Set Favourite Airport' class="btn btn-info" /></form>
    <hr />
    <form action='?a=favdo&u=<?php echo $ir['userid'];?>' target='settings' method='post'>
    <input type="hidden" name="userid" value="<?php echo $ir['userid'];?>">
    <input type="hidden" class="form-control" name="icao" value="XXXX">
    <input type='submit' value='Deselect Favourite Airport' class="btn btn-info" /></form>
<?php
}

function favdo() {
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $fav= $db->escape($_POST['icao']);
    $db->query("UPDATE users SET fav='{$fav}' WHERE userid=$userid");
    print "Favourite Airport Set!";
    echo '<a href="https://airportmanagement.makeweb.games/" target="_parent"></a>';
}












?>
