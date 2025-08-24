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
                    <li class="nav-item"><h4>Menu</h4></li>
                    <li class="nav-item"><form action="?a=index&u=<?php echo $ir['userid'];?>" method="post"><input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="settings"><input type="submit" name="" value="Index" class="btn btn-<?php echo $theme; ?> col-12 btn-sm"></form></li>

                </ul></div>
        </td>
        <td width="1%"></td>
        <td width="80%" valign="top"><br />






<?php


$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'xcv': xcv(); break;
default:index(); break;
}







function index() {
global $db;
$userid = $_GET['u'];
$dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($dbbd);
?>
Display User stats
<?php
}



