<?php include "../pages/dbconnect.php";

$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
    switch ($_GET['a']) {
        case 'search': search(); break;
    default:index(); break;
    }





function search() {
    global $db; 
    $_POST['user'] = (isset($_POST['user']) && is_string($_POST['user'])) ? stripslashes($_POST['user']) : '';
    if (!$_POST['user']) {
        echo "You cant leave the search box empty";
    } else if (!preg_match("/^[a-z0-9_]+([\\s]{1}[a-z0-9_]|[a-z0-9_])+$/i",
        $_POST['user']))
    {
        echo 'Users can only consist of Numbers, Letters, underscores and spaces.';
    }   else if (((strlen($_POST['user']) > 32) OR (strlen($_POST['user']) < 2)))
    {
        echo 'users can only be a max of 32 characters or a min of 2 characters.';
    } else {
    $e_name_check = '%' . $db->escape($_POST['user']) . '%';
    $q = $db->query("SELECT * FROM users WHERE username LIKE ('{$e_name_check}')");
    echo '<h1>'.$db->num_rows($q) .' users found. </h1>'; ?>
    <form action="?a=index" method="POST">
        <button type="submit" value="Search" class="btn btn-info">Back</button>
    </form>
	<div class="container mt-3"><div class="row">
	<?php
        while($data=$db->fetch_row($q)) {
            $lon = ($data['laston'] > 0) ? date('F j, Y g:i:s a', $data['laston']) : "Never"; $ula = ($data['laston'] == 0) ? 'Never' : DateTime_Parse($data['laston']);  
            $alls = $data['alliance'];
            $tag = $db->query("SELECT * FROM alliance WHERE allianceID=$alls");
            $tags = $db->fetch_row($tag);
            $distag = $tags['allianceNAME'];
            if($distag) { $distags='<b><u>'.$distag.'</u></b>'; } else { $distags='None'; }
            $user = $data['userid']; 
            $air = $db->query("SELECT planeOWNER FROM userairplanes WHERE planeOWNER=$user"); 
            $count=$db->num_rows($air);
            if($count >= '1') { $counts=$count; } else { $counts='None'; }
            $staffer = $data['staff'];
            if($staffer == '3') { $staff = "<img src='../images/admin.gif' alt='admin' width='12'>"; } else
            if($staffer == '2') { $staff = "<img src='../images/staff.png' alt='game moderator' width='12'>"; } else
            if($staffer == '1') { $staff = "<img src='../images/staff.png' alt='chat moderator' width='12'>"; } else { $staff=""; }
            if($data['premiumdays'] >= '1') {
                    $donatorimage = "<img src='../images/donator.png' alt='".$data['premiumdays']."' width='12'>";
                } else {
                    $donatorimage = "";
                } ?>
                <div class="col-sm-3 text-black p-3 border border-dark">
        <table width="100%">
            <tr>
                <td colspan="2" align="center"><img src="<?php echo $data['profileimage']; ?>" height="100px"></td>
            </tr>
            <tr>
                <td colspan="2"><?php echo $data['username']; ?> <small>ID:<?php echo $data['userid']; ?> <?php echo $donatorimage; ?> <?php echo $staff; ?></small></td>
            </tr>
            <tr>
                <td><b><u>AIRLINE:</u></b></td>
                <td><font color="<?php echo $data['airlinecolour']; ?>"><?php echo $data['airlinename'];?></font></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><b><u>STATS</u></b></td>
            </tr>
            <tr>
                <td><b><u>BUCKS:</u></b></td>
                <td><span class="bucks"><?php echo money_formatter($data['bucks']); ?></span></td>
            </tr>
            <tr>
                <td><b><u>MADE:</u></b></td>
                <td><span class="bucks"><?php echo money_formatter($data['totalmoney']); ?></span></td>
            </tr>
            <tr>
                <td><b><u>DISTANCE:</u></b></td>
                <td><span><?php echo number_format($data['totaldistance']); ?> km</span></td>
            </tr>
            <tr>
                <td><b><u>AIRCRAFTS:</u></b></td>
                <td><?php echo $counts; ?></td>
            </tr>
            <tr>
                <td><b><u>REPUTATION:</u></b></td>
                <td><?php echo number_format($data['reputation'],5); ?></td>
            </tr>
            <tr>
                <td><b><u>COMMUNITY:</u></b></td>
                <td><?php echo $distags; ?></td>
            </tr>
            <tr>
                <td><b><u>LAST ACTIVE:</u></b></td>
                <td><?php if($data['laston'] >= $_SERVER['REQUEST_TIME'] - 15 * 60) { echo '<img src="../images/online.gif"> '; } else { echo '<img src="../images/offline.gif"> '; } echo $ula; ?></td>
            </tr>
            <tr>
                <td><b><u>HQ:</u></b></td>
                <td><?php if($data['airlinehq'] == '1') { echo 'HQ'; } else { echo 'No HQ'; } ?></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><form action="company.php" target="userlist" method="post">
                                                    <input type="hidden" name="u" value="<?php echo $data['userid']; ?>">
                                                    <input type="submit" name="" value="VIEW" class="btn btn-<?php echo $data['theme']; ?>">
                                                </form></td>
            </tr>
        </table>
    </div>
                
                
        <?php }?>
  </div>
</div><?php
}
}




function index() {
    global $db;
    $perpage = '50';
    $getinfo = $db->query("SELECT * FROM `users` ORDER BY userid ASC");
    $data=$db->fetch_row($getinfo);
    $st = (isset($_GET['st']) && is_numeric($_GET['st'])) ? abs(intval($_GET['st'])) : 0;
    $allowed_by = array('userid', 'username', 'bucks', 'laston', 'alliance', 'totalmoney', 'totaldistance');
    $by = (isset($_GET['by']) && in_array($_GET['by'], $allowed_by, true)) ? $_GET['by'] : 'userid';
    $allowed_ord = array('asc', 'desc', 'ASC', 'DESC');
    $ord = (isset($_GET['ord']) && in_array($_GET['ord'], $allowed_ord, true)) ? $_GET['ord'] : 'ASC';
    $cnt = $db->query("SELECT COUNT(`userid`) FROM `users`");
    $membs = $db->fetch_single($cnt);
    $db->free_result($cnt);
    $pages = (int) ($membs / $perpage) + 1;
    if ($membs % $perpage == 0) {
        $pages--;
    }
    ?>
<center>
<ul class="pagination">
    <li class="page-item disabled"><a class="page-link" href="#">Pages</a></li>
    <?php for ($i = 1; $i <= $pages; $i++) {
        $stl = ($i - 1) * $perpage;
        echo "<li class='page-item'><a href='?a=index&st=$stl&amp;by=$by&amp;ord=$ord' class='page-link'>$i</a></li>";
    }
    ?>
</ul>
</center>
        <form action="?a=search" method="POST">
            <div class="input-group mb-3">
              <input type="text" name="user" autocomplete="off" placeholder="Search for user" class="form-control">
              <button class="btn btn-success" value="Search" type="submit">Search</button>
            </div>
        </form>
<ul class="nav bg-<?php echo $data['theme']; ?> fixed-top">
    <body style="width: 100%; margin-top: 38px">
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>"><b>ORDER BY:</b></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=userid&u=<?php echo $userid; ?>" target="userlist"><?php echo $st1; ?>UserID<?php echo $en1; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=username&u=<?php echo $userid; ?>" target="userlist"><?php echo $st2; ?>Username<?php echo $en2; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=bucks&ord=DESC&u=<?php echo $userid; ?>" target="userlist"><?php echo $st3; ?>Bucks<?php echo $en3; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=laston&ord=DESC&u=<?php echo $userid; ?>" target="userlist"><?php echo $st3; ?>Last Active<?php echo $en3; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=alliance&ord=DESC&u=<?php echo $userid; ?>" target="userlist"><?php echo $st3; ?>Alliance<?php echo $en3; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=totalmoney&ord=DESC&u=<?php echo $userid; ?>" target="userlist"><?php echo $st3; ?>Bucks Made<?php echo $en3; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=totaldistance&ord=DESC&u=<?php echo $userid; ?>" target="userlist"><?php echo $st3; ?>Distance<?php echo $en3; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>">|</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=<?php echo $by; ?>&ord=DESC&u=<?php echo $userid; ?>" target="userlist"><?php echo $st7; ?>Desc<?php echo $en7; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>" href="?a=index&by=<?php echo $by; ?>&ord=ASC&u=<?php echo $userid; ?>" target="userlist"><?php echo $st8; ?>Asc<?php echo $en8; ?></a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>">|</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-<?php echo $data['theme']; ?>">Current: <b><?php echo $by; ?></b> ordered by <b><?php echo $ord; ?></b></a> | 100 users Per Page
        </li>
</ul>
<?php
$q = $db->query("SELECT * FROM `users` ORDER BY `$by` $ord LIMIT $st, $perpage");
$no1 = $st + 1;
$no2 = min($st + $perpage, $membs);
?><div class="container mt-3"><div class="row">
<?php
while ($data = $db->fetch_row($q)) {
    $lon = ($data['laston'] > 0) ? date('F j, Y g:i:s a', $data['laston']) : "Never"; $ula = ($data['laston'] == 0) ? 'Never' : DateTime_Parse($data['laston']);  
    $alls = $data['alliance'];
    $tag = $db->query("SELECT * FROM alliance WHERE allianceID=$alls");
    $tags = $db->fetch_row($tag);
    $distag = $tags['allianceNAME'];
    if($distag) { $distags='<b><u>'.$distag.'</u></b>'; } else { $distags='None'; }
    $user = $data['userid']; 
    $air = $db->query("SELECT planeOWNER FROM userairplanes WHERE planeOWNER=$user"); 
    $count=$db->num_rows($air);
    if($count >= '1') { $counts=$count; } else { $counts='None'; }
    $staffer = $data['staff'];
    if($staffer == '3') { $staff = "<img src='../images/admin.gif' alt='admin' width='12'>"; } else
    if($staffer == '2') { $staff = "<img src='../images/staff.png' alt='game moderator' width='12'>"; } else
    if($staffer == '1') { $staff = "<img src='../images/staff.png' alt='chat moderator' width='12'>"; } else { $staff=""; }
    if($data['premiumdays'] >= '1') {
            $donatorimage = "<img src='../images/donator.png' alt='".$data['premiumdays']."' width='12'>";
        } else {
            $donatorimage = "";
        }
    ?>
    <div class="col-sm-3 text-black p-3 border border-dark">
        <table width="100%">
            <tr>
                <td colspan="2" align="center"><img src="<?php echo $data['profileimage']; ?>" height="100px"></td>
            </tr>
            <tr>
                <td colspan="2"><b><?php echo $data['username']; ?></b> - <small>ID:<?php echo $data['userid']; ?> <?php echo $donatorimage; ?> <?php echo $staff; ?></small></td>
            </tr>
            <tr>
                <td><b><u>AIRLINE:</u></b></td>
                <td><font color="<?php echo $data['airlinecolour']; ?>"><?php echo $data['airlinename'];?></font></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><b><u>STATS</u></b></td>
            </tr>
            <tr>
                <td><b><u>BUCKS:</u></b></td>
                <td><span class="bucks"><?php echo money_formatter($data['bucks']); ?></span></td>
            </tr>
            <tr>
                <td><b><u>MADE:</u></b></td>
                <td><span class="bucks"><?php echo money_formatter($data['totalmoney']); ?></span></td>
            </tr>
            <tr>
                <td><b><u>DISTANCE:</u></b></td>
                <td><span><?php echo number_format($data['totaldistance']); ?> km</span></td>
            </tr>
            <tr>
                <td><b><u>AIRCRAFTS:</u></b></td>
                <td><?php echo $counts; ?></td>
            </tr>
            <tr>
                <td><b><u>REPUTATION:</u></b></td>
                <td><?php echo number_format($data['reputation'],5); ?></td>
            </tr>
            <tr>
                <td><b><u>COMMUNITY:</u></b></td>
                <td><?php echo $distags; ?></td>
            </tr>
            <tr>
                <td><b><u>LAST ACTIVE:</u></b></td>
                <td><?php if($data['laston'] >= $_SERVER['REQUEST_TIME'] - 15 * 60) { echo '<img src="../images/online.gif"> '; } else { echo '<img src="../images/offline.gif"> '; } echo $ula; ?></td>
            </tr>
            <tr>
                <td><b><u>HQ:</u></b></td>
                <td><?php if($data['airlinehq'] == '1') { echo 'HQ'; } else { echo 'No HQ'; } ?></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><form action="company.php" target="userlist" method="post">
                                                    <input type="hidden" name="u" value="<?php echo $data['userid']; ?>">
                                                    <input type="submit" name="" value="VIEW" class="btn btn-<?php echo $data['theme']; ?>">
                                                </form></td>
            </tr>
        </table>
    </div>


    
    <?php
}
$db->free_result($q);
?> </div></div><?php
}















