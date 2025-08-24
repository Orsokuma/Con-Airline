<?php include "../pages/dbconnect.php";

$alliancecost = $set['alliancecost'];
$action = array_key_exists('a', $_GET) ? $_GET['a'] : null;
$userid = $_GET['u'];

?>

<table width="100%">
    <tr>
        <td><a href="?a=index&u=<?php echo $userid; ?>">Communities List</a></td>
        <td><a href="?a=create&u=<?php echo $userid; ?>">Create an Community</a></td>
    </tr>
</table>
<hr />

<?php

switch ($action) {
case 'create': create(); break;
case 'createalliance': createalliance(); break;
case 'apply': apply(); break;
case 'view': view(); break;
default:index(); break;
}




function index() {
    global $db,$userid;
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd); ?>
        <h4>Community List</h4>
        <table width="100%">
            <tr>
                <td><b>Name</b></td>
                <td><b>Tag</b></td>
                <td><b>Image</b></td>
                <td><b>President</b></td>
                <td><b>Vice President</b></td>
                <td><b>Members</b></td>
                <td><b>Options</b></td>
            </tr>
        <?php $alliances = $db->query("SELECT * FROM alliance ORDER BY allianceID");
        while ($alliance = $db->fetch_row($alliances)) {
        $pres = $alliance['alliancePRESIDENT'];
        $vice = $alliance['allianceVICEPRES'];
        $image = $alliance['allianceIMAGE'];
        
        $userpre = $db->query("SELECT * FROM users WHERE userid=$pres");
        $pre = $db->fetch_row($userpre);
        
        $uservic = $db->query("SELECT * FROM users WHERE userid=$vice");
        $vicer = $db->fetch_row($uservic);
        
        if ($vice == '0') { $ovice='None'; } else { $ovice=$vicer['username']; }
        ?>
            <tr>
                <td><?php echo $alliance['allianceNAME']; ?></td>
                <td><?php echo $alliance['alliancePREF']; ?></td>
                <td><img src="<?php echo $image; ?>" width="100"></td>
                <td><?php echo $pre['username']; ?></td>
                <td><?php echo $ovice; ?></td>
                <td><?php $alli = $alliance['allianceID']; $uc = $db->query("SELECT * FROM users WHERE alliance=$alli"); $usc = $db->num_rows($uc); echo $usc; ?></td>
                <td><form action="?a=view&u=<?php echo $userid; ?>" method="post" target="alliance">
                        <input type="hidden" name="userid" value="<?php echo $ir['userid'];?>">
                        <input type="hidden" name="alliance" value="<?php echo $alliance['allianceID'];?>">
                        <input type="submit" name="" value="View" class="btn btn-info">
                    </form></td>
            </tr>
        <?php } ?>
        </table>
<?php 
}


function view() {
    global $db;
    $userid = $_POST['userid'];
    $alliance = $_POST['alliance'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $quer = $db->query("SELECT * FROM alliance WHERE allianceID=$alliance");
    $all = $db->fetch_row($quer);
    ?>
    <h4>Viewing Community: <?php echo $all['allianceNAME']; ?></h4>
    <?php echo $all['allianceDESC']; ?><br /><br />
    
    <?php if($ir['alliance'] == '0') { echo '
        <form action="?a=apply&u='.$userid.'" method="post" target="alliance">
            <input type="hidden" name="userid" value="'.$userid.'">
            <input type="hidden" name="alliance" value="'.$alliance['allianceID'].'">
            <input type="submit" name="" value="Apply" class="btn btn-info">
        </form>'; } else { echo 'You are already in an community'; }
    
}



function apply() {
    global $db;
    $userid = $_POST['userid'];
    $alliance = $_POST['alliance'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $quer = $db->query("SELECT * FROM alliance WHERE allianceID=$alliance");
    $all = $db->fetch_row($quer);
    ?>
    <h4>Applied for : <?php echo $all['allianceNAME']; ?></h4>
    <?php
    $db->query("INSERT INTO `allianceapply`(`id`, `applying`, `appliedfor`) VALUES ('','$userid','$alliance')");
}





function create() {
    global $db,$userid,$alliancecost;
    $userid = $_GET['u'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    if ($ir['alliance'] >= '1') {
        die("You are already in an community");
    }
    ?>
        <h4>Create a Community</h4>
        <p>The Current cost to Create a Comminuty is: <?php echo $alliancecost;?> AirBucks</p>
        <form action="?a=createalliance&u=<?php echo $userid; ?>" method="post">
            <input type="hidden" name="userid" value="<?php echo $userid;?>" target="alliance">
            <table width="50%">
                <tr>
                    <td width="50%">Community Name</td>
                    <td width="50%"><input type="text" name="name" class="form-control"></td>
                </tr>
                <tr>
                    <td>Community Description</td>
                    <td><textarea name="desc" cols="50" rows="7" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <td>Community Image URL</td>
                    <td><input type="text" name="image" class="form-control"></td>
                </tr>
                <tr>
                    <td>Community Tag</td>
                    <td><input type="text" name="pref" class="form-control"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="" value="Create" class="btn btn-info"></td>
                </tr>
            </table>    
        </form>
<?php 
}



function createalliance() {
    global $db,$userid,$alliancecost;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    
    if ($ir['airbucks'] <= $alliancecost) {
        die("You don't have enough Airbucks for this purchase.");
    }
    $name = $db->escape($_POST['name']);
    $desc = $db->escape($_POST['desc']);
    $pref = $db->escape($_POST['pref']);
    $image = $db->escape($_POST['image']);
    $db->query("INSERT INTO `alliance`(`allianceID`, `allianceNAME`, `allianceDESC`, `allianceIMAGE`, `alliancePREF`, `alliancePRESIDENT`, `allianceVICEPRES`) VALUES ('','$name','$desc','$image','$pref','$userid','0')");
    $i = $db->insert_id();
    $db->query("UPDATE users SET airbucks=airbucks-$alliancecost, alliance=$i WHERE userid=$userid");
    
    ?>
        <h4>Created an Community</h4>
        <p>Community Created.</p>
<?php 
}

// SELECT `allianceID`, `allianceNAME`, `allianceDESC`, `alliancePREF`, `alliancePRESIDENT`, `allianceVICEPRES` FROM `alliance` WHERE 1


























?>
