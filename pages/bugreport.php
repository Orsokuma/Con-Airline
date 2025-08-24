<?php include "../pages/dbconnect.php";



$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'submitbug': submitbug(); break;
default:index(); break;
}



function index() {
    global $db;
    $userid = $_GET['u'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $staff = $ir['staff'];

    ?>
	<h3 class=fontface>Bug Report</h3>
	<br />
	Please put a brief description of the issue below. Any links to Screenshots would be helpful.
	<br />
    <form action='?a=submitbug' target='bugreport' method='post'>
    <input type="hidden" name="reporterid" value="<?php echo $userid;?>">

    <select class="form-select" name="bugtype">
        <option value="General Bug">General Bug</option>
        <option value="Spelling/Grammer">Spelling / Grammer</option>
        <option value="Other">Other</option>
    </select>
    <textarea class="form-control" rows="5" name="bug" required></textarea>
    <input type='submit' value='Submit Bug Report' class="btn btn-info"/></form>
<?php
}



function submitbug() {
    global $db;
    $user = $_POST['reporterid'];
    $bugtype = $_POST['bugtype'];
    $bug = $_POST['bug'];
    $postbug = $db->escape($bug);
    
    $db->query("INSERT INTO `bugs`(`id`, `reporterid`, `bugtype`, `bug`, `date`) VALUES ('','$user','$bugtype','$postbug',unix_timestamp())");
    echo 'Thanks for taking the time of filling out a Bug Report.<br />
    <a href="?a=index&u='.$user.'" target="bugreport">Continue</a>';
}






?>
