<?php include "../pages/dbconnect.php";

$action = array_key_exists('a', $_GET) ? $_GET['a'] : null;
$theme = 'dark';
$userid = $_GET['u'];
$getir = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($getir);
?>



<table width="100%" height="100%" border="1">
    <tr>
        <td width="19%" valign="top">
            <h4>Comminuty Menu</h4>
                <a href="?a=index&u=<?php echo $userid; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Community Main</a><br />
                <a href="?a=members&u=<?php echo $userid; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Community Members</a><br />
                <a href="?a=money&u=<?php echo $userid; ?>" class="btn btn-<?php echo $theme; ?> col-12 btn-sm">Community Money & Contributions</a><br />
        </td>
        <td width="1%"></td>
        <td width="80%" valign="top"><br />





<?php

switch ($action) {
case 'pres': pres(); break;
case 'vicepres': vicepres(); break;
case 'viewapps': viewapps(); break;
case 'accept': accept(); break;
case 'decline': decline(); break;
case 'members': members(); break;
case 'yeet': yeet(); break;
case 'yeetdo': yeetdo(); break;
case 'alliancesettings': alliancesettings(); break;
case 'alliancesettingsdo': alliancesettingsdo(); break;
case 'money': money(); break;
case 'moneyperc': moneyperc(); break;
case 'moneypercdo': moneypercdo(); break;
default:index(); break;
}




function index() {
    global $db,$userid;
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd); 
    $ualliance = $ir['alliance'];
    $query = $db->query("SELECT * FROM alliance WHERE allianceID=$ualliance");
    
     
    $airs = $db->query("SELECT * FROM users WHERE alliance=$ualliance"); 
    $counts = $db->num_rows($airs);
    
    $all = $db->fetch_row($query);
    
    ?>
    <table width="100%">
        <tr>
            <td width="25%"><b>Community Funds:</b> <?php echo money_formatter($all['allianceMONEY']);?></td>
            <td width="25%"><b>Members:</b> <?php echo $counts; ?></td>
            <td width="25%"></td>
            <td width="25%"></td>
        </tr>
    </table>
    
    
    <hr />
    <div class="btn-group"><?php
    if ($all['alliancePRESIDENT'] == $userid) { ?>
        <form action="?a=pres&u=<?php echo $userid; ?>" method="post" target="ualliance">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="hidden" name="alliance" value="<?php echo $all['allianceID']; ?>">
            <input type="submit" name="" value="President Panel" class="btn btn-info">
        </form>
    <?php } else { echo ''; } ?>
    <?php
    if ($all['allianceVICEPRES'] == $userid) { ?>
        <form action="?a=vicepres&u=<?php echo $userid; ?>" method="post" target="ualliance">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="hidden" name="alliance" value="<?php echo $all['allianceID']; ?>">
            <input type="submit" name="" value="Vice President Panel" class="btn btn-info">
        </form>
    <?php } else { echo ''; } ?>
    </div>

<h4>Your Community: <?php echo $all['allianceNAME']; ?></h4>
<?php echo $all['allianceWELL']; ?><hr />
<?php 
}




function money() {
    global $db;
    $userid = $_GET['u'];
    
    $que = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($que);
    $alliance = $ir['alliance'];
    $query = $db->query("SELECT * FROM alliance WHERE allianceID=$alliance");
    $all = $db->fetch_row($query);
    ?>
    Your Community has <?php echo money_formatter($all['allianceMONEY']); ?> in the Vault.<Br />
    You are currently donating <?php echo $ir['allianceperc']; ?>% to your Community.<Br />
        <form action="?a=moneyperc&u=<?php echo $userid; ?>" method="post" target="ualliance">
            <input type="submit" name="" value="Give Percentage to Community" class="btn btn-info">
        </form>
    <hr /> <?php
     $getinfo = $db->query("SELECT * FROM `alliancemoney` WHERE allianceID=$alliance ORDER BY id DESC LIMIT 100"); ?>
        <center><h4>Finances</h4></center>
        <table width="100%" border="1">
            <tr align="center">
                <td><b>Who</b></td>
                <td><b>Amount</b></td>
                <td><b>Description</b></td>
                <td><b>Date</b></td>
            </tr>
        <?php
         while($info=$db->fetch_row($getinfo)) {
                $outin = $info['outin'];
                $amount = $info['amount'];
                $item = $info['item'];
                $type = $info['type'];
                $time = date("jS F, Y, H:i:s",$info['date']+3600);
                
                if ($outin == 'out') { $col='red'; $sym='-'; } else { $col='green'; $sym='+'; }
                if ($type == 'airbucks') { $col='blue'; }
                if ($type == 'airbucks') { $moneytype=number_format($amount); } else { $moneytype=money_formatter($amount); }
                
                $infoid = $info['userid'];
                $quer = $db->query("SELECT * FROM users WHERE userid=$infoid");
                $dunno = $db->fetch_row($quer);
                
                
                ?>
                <tr border="1" align="center">
                    <td><b><?php echo $dunno['username'].'</b> <small>(ID: '.$dunno['userid'].')</small>'; ?></td>
                    <td><font color="<?php echo $col; ?>"><b><?php echo $sym.$moneytype; ?></b></font></td>
                    <td><?php echo $item; ?></td>
                    <td><?php echo $time; ?></td>
                </tr>
                <?php } ?>

            </table>
            <?php
    
    
    
    
    
    
}


function moneyperc() {
    global $db;
    $userid = $_GET['u'];
    
    $que = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($que);
    $alliance = $ir['alliance'];
    $query = $db->query("SELECT * FROM alliance WHERE allianceID=$alliance");
    $all = $db->fetch_row($query);
    ?>
    How much of your money would you like to donate to your Community per flight.<Br />
        <form action="?a=moneypercdo&u=<?php echo $userid; ?>" method="post" target="ualliance">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                <select class="form-select" name="allianceperc">
                    <option value="0">None</option>
                    <?php for ($i=1; $i<=99; $i++) {
                        echo "<option value='$i'>$i%</option>";
                    }
                    ?>
                </select>
            <input type="submit" name="" value="Set Percentage" class="btn btn-info">
        </form>
    <?php
}


function moneypercdo() {
    global $db;
    $userid = $_POST['userid'];
    $perc = $_POST['allianceperc'];
    $db->query("UPDATE users SET allianceperc=$perc WHERE userid=$userid");
    ?>
    You will now give <?php echo $perc; ?>% of your profits to your allaince<Br />
    <?php
}


function members() {
    global $db, $userid;
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd); 
    $ualliance = $ir['alliance'];
    $query = $db->query("SELECT * FROM alliance WHERE allianceID=$ualliance");
    $all = $db->fetch_row($query);
    $query = $db->query("SELECT * FROM users WHERE alliance=$ualliance"); ?>
    <table width='100%' cellspacing='1' cellpadding='1' class='table'>
        <tr>
            <td>Profile Pic</td>
            <td>Status</td>
            <td>Username</td>
            <td>Airline Name</td>
            <td>Aircrafts</td>
            <td>Bucks</td>
            <td>HQ?</td>
            <td></td>
        </tr>
    <?php while ($data = $db->fetch_row($query)) { ?>
    
        <tr>
            <td><img src="<?php echo $data['profileimage']; ?>" width="50px"></td>
            <td><?php if($data['laston'] >= $_SERVER['REQUEST_TIME'] - 15 * 60) { echo '<img src="../images/online.gif"> '; } else { echo '<img src="../images/offline.gif"> '; } echo $ula; ?><br /></td>
            <td><b> <?php echo $data['username']; ?> ID:<?php echo $data['userid']; ?></b></td>
            <td><img src="<?php echo $data['airlineimage']; ?>" width="50px"> <font color="<?php echo $data['airlinecolour']; ?>"><b><?php echo $data['airlinename'];?></b></font></td>
            <td><?php $user = $data['userid']; $air = $db->query("SELECT planeOWNER FROM userairplanes WHERE planeOWNER=$user"); $count=$db->num_rows($air); echo $count; ?></td>
            <td><span class="bucks"><?php echo money_formatter($data['bucks']); ?></span></td>
            <td><?php if($data['airlinehq'] == '1') { echo 'HQ'; } else { echo 'No HQ'; } ?></td>
            <td>
                <div class="btn-group"><?php
                $president = $all['alliancePRESIDENT'];
                $vicepresident = $all['allianceVICEPRES'];
                if ($president == $userid or $vicepresident == $userid) { ?>
                    <form action="?a=yeet&u=<?php echo $userid; ?>" method="post" target="ualliance">
                        <input type="hidden" name="userid" value="<?php echo $data['userid']; ?>">
                        <input type="submit" name="" value="Yeet" class="btn btn-info">
                    </form>
                <?php } else { echo ''; } ?>
                </div>
            </td>
        </tr>
    <?php    
    }
}

function yeet() {
    global $db;
    $user = $_GET['userid'];
    $userid = $_POST['userid'];
    $ques = $db->query("SELECT * FROM users WHERE userid=$userid");
    $r = $db->fetch_row($ques); ?>
    Are you Sure you want to Yeet User: <?php echo $r['username']; ?> From your Community?<br />
    <div class="btn-group">
        <form action="?a=yeetdo&u=<?php echo $userid; ?>" method="post" target="ualliance">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="submit" name="" value="Yeet" class="btn btn-danger">
        </form>
        <form action="?a=index&u=<?php echo $userid; ?>" method="post" target="ualliance">
            <input type="submit" name="" value="Don't Yeet" class="btn btn-info">
        </form>
    </div>
    
    <?php
}


function yeetdo() {
    global $db;
    $userid = $_POST['userid'];
    $db->query("UPDATE users SET alliance=0 WHERE userid=$userid");
    echo 'You have Yeeted this Person from your Community';
}




function pres() {
    global $db;
    $userid = $_POST['userid'];
    $alliance = $_POST['alliance'];
    $que = $db->query("SELECT * FROM allianceapply WHERE appliedfor=$alliance");
    $appc = $db->num_rows($que);
    ?><div class="btn-group">
    <form action="?a=viewapps&u=<?php echo $userid; ?>" method="post" target="ualliance">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <input type="hidden" name="alliance" value="<?php echo $alliance; ?>">
        <input type="submit" name="" value="View Applications (<?php echo $appc; ?>)" class="btn btn-info">
    </form>
    <form action="?a=alliancesettings&u=<?php echo $userid; ?>" method="post" target="ualliance">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <input type="hidden" name="alliance" value="<?php echo $alliance; ?>">
        <input type="submit" name="" value="Community Settings" class="btn btn-info">
    </form>
    </div>
    <?php
}


function vicepres () {
    global $db;
    $userid = $_POST['userid'];
    $alliance = $_POST['alliance'];
    $que = $db->query("SELECT * FROM allianceapply WHERE appliedfor=$alliance");
    $appc = $db->num_rows($que);
    ?><div class="btn-group">
    <form action="?a=viewapps&u=<?php echo $userid; ?>" method="post" target="ualliance">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <input type="hidden" name="alliance" value="<?php echo $alliance; ?>">
        <input type="submit" name="" value="View Applications (<?php echo $appc; ?>)" class="btn btn-info">
    </form>
    </div>
    <?php    
}







function alliancesettings() {
    global $db;
    $userid = $_POST['userid'];
    $alliance = $_POST['alliance'];
    ?><form action="?a=alliancesettingsdo&u=<?php echo $userid; ?>" method="post" target="ualliance">
        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
        <input type="hidden" name="allianceID" value="<?php echo $alliance; ?>">
    <table width='100%' cellspacing='1' cellpadding='1' class='table'>
        <tr>
            <td><b>Setting Name</b></td>
            <td><b>Current</b></td>
        </tr><?php 
            $que = $db->query("SELECT * FROM alliance WHERE allianceID=$alliance"); 
            while ($all = $db->fetch_row($que)) { ?>
        <tr>
            <td>Name</td>
            <td><input type="text" name="allianceNAME" value="<?php echo $all['allianceNAME']; ?>" class="form-control"></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><textarea name="allianceDESC" cols="50" rows="7" class="form-control"><?php echo $all['allianceDESC']; ?></textarea></td>
        </tr>
        <tr>
            <td>Image URL</td>
            <td><input type="text" name="allianceIMAGE" value="<?php echo $all['allianceIMAGE']; ?>" class="form-control"></td>
        </tr>
        <tr>
            <td>Tag</td>
            <td><input type="text" name="alliancePREF" value="<?php echo $all['alliancePREF']; ?>" class="form-control"></td>
        </tr>
        <tr>
            <td>President</td>
            <td><select class="form-select" name="alliancePRESIDENT">
                <option value="<?php echo $userid; ?>">Don't Change</option><?php
                $didi = $db->query("SELECT * FROM users WHERE alliance=$alliance");
                while ($vicei = $db->fetch_row($didi)) { ?>
                    <option value="<?php echo $vicei['userid']; ?>"><?php echo $vicei['username']; ?></option>
                <?php } ?></select></td>
        </tr>
        <tr>
            <td>Vice President</td>
            <td><select class="form-select" name="allianceVICEPRES">
                <option value="0">None</option><?php
                $did = $db->query("SELECT * FROM users WHERE alliance=$alliance");
                while ($vice = $db->fetch_row($did)) { ?>
                    <option value="<?php echo $vice['userid']; ?>"><?php echo $vice['username']; ?></option>
                <?php } ?></select></td>
        </tr>
        <tr>
            <td>Community Page Message</td>
            <td><textarea name="allianceWELL" cols="50" rows="7" class="form-control"><?php echo $all['allianceWELL']; ?></textarea></td>
        </tr>
            
        <?php } ?>
        <tr>
            <td colspan="2"><input type="submit" name="" value="UPDATE" class="btn btn-info"></td>
        </tr>
    </table>
    </form>
    <?php
}


function alliancesettingsdo() {
    global $db;
    $userid = $db->escape($_POST['userid']);
    $allianceID = $db->escape($_POST['allianceID']);
    $allianceNAME = $db->escape($_POST['allianceNAME']);
    $allianceDESC = $db->escape($_POST['allianceDESC']);
    $allianceIMAGE = $db->escape($_POST['allianceIMAGE']);
    $alliancePREF = $db->escape($_POST['alliancePREF']);
    $allianceVICEPRES = $db->escape($_POST['allianceVICEPRES']);
    $alliancePRESIDENT = $db->escape($_POST['alliancePRESIDENT']);
    $allianceWELL = $db->escape($_POST['allianceWELL']);
    $db->query("UPDATE alliance SET allianceNAME='$allianceNAME', allianceDESC='$allianceDESC', allianceIMAGE='$allianceIMAGE', alliancePREF='$alliancePREF', alliancePRESIDENT='$alliancePRESIDENT', allianceVICEPRES='$allianceVICEPRES', allianceWELL='$allianceWELL' WHERE allianceID=$allianceID");
    echo 'Settings Updated';
}



function viewapps() {
    global $db;
    $userid = $_POST['userid'];
    $alliance = $_POST['alliance'];
    $query = $db->query("SELECT * FROM allianceapply WHERE appliedfor=$alliance"); ?>
        <table width="100%">
            <tr>
                <td width="75%">User</td>
                <td width="25%">Options</td>
            </tr>
    <?php while ($all = $db->fetch_row($query)) {
            $applicant = $all['applying'];
            $q = $db->query("SELECT * FROM users WHERE userid=$applicant");
            $d = $db->fetch_row($q);
            ?>
            <tr>
                <td><?php echo $d['username']; ?></td>
                <td><form action="?a=accept&u=<?php echo $userid; ?>" method="post" target="ualliance">
                        <input type="hidden" name="app" value="<?php echo $all['id']; ?>">
                        <input type="hidden" name="userid" value="<?php echo $d['userid']; ?>">
                        <input type="hidden" name="alliance" value="<?php echo $alliance; ?>">
                        <input type="submit" name="" value="Accept" class="btn btn-info">
                    </form>
                    <form action="?a=decline&u=<?php echo $userid; ?>" method="post" target="ualliance">
                        <input type="hidden" name="app" value="<?php echo $all['id']; ?>">
                        <input type="hidden" name="userid" value="<?php echo $d['userid']; ?>">
                        <input type="hidden" name="alliance" value="<?php echo $alliance; ?>">
                        <input type="submit" name="" value="Decline" class="btn btn-info">
                    </form></td>
            </tr>
    <?php }
}


function accept() {
    global $db;
    $app = $_POST['app'];
    $user = $_POST['userid'];
    $alliance = $_POST['alliance'];
    $db->query("UPDATE users SET alliance=$alliance WHERE userid=$user");
    $db->query("DELETE FROM `allianceapply` WHERE id=$app");
    echo 'Application Accepted';
}



function decline() {
    global $db;
    $app = $_POST['app'];
    $user = $_POST['userid'];
    $alliance = $_POST['alliance'];
    $db->query("DELETE FROM `allianceapply` WHERE id=$app");
    echo 'Application Declined';
}

























?>
