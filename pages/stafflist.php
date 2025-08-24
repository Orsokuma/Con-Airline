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



$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
default:index(); break;
}







function index() {
    global $db;
    ?>
    
    
    
    <table width="100%">
        <tr>
            <td align="center"><b><u>Username</u></b></td>
            <td align="center"><b><u>Roles</u></b></td>
        </tr>
        <tr>
            <td colspan="2"><hr /></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><b><u>Administrators</u></b></td>
        </tr>
   <?php $start3 = $db->query("SELECT * FROM users WHERE staff='3'");
    while ($star3 = $db->fetch_row($start3)) { 
    if($star3['premiumdays'] >= '1') { $donatorimage = "<img src='../images/donator.png' alt='".$star3['premiumdays']."' width='12'>"; } else { $donatorimage = ""; } ?>
        <tr>
            <td align="center"><img src="../images/admin.gif" alt="Staff" width="12"> <?php echo $star3['username']; ?> <?php echo $donatorimage; ?></td>
            <td align="center"><?php echo $star3['roles']; ?></td>
        </tr>
    <?php } ?>  
        <tr>
            <td colspan="2"><hr /></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><b><u>Game Moderators</u></b></td>
        </tr>
    <?php $start2 = $db->query("SELECT * FROM users WHERE staff='2'");
    while ($star2 = $db->fetch_row($start2)) { 
    if($star2['premiumdays'] >= '1') { $donatorimage = "<img src='../images/donator.png' alt='".$star2['premiumdays']."' width='12'>"; } else { $donatorimage = ""; }?>
        <tr>
            <td align="center"><img src="../images/staff.png" alt="Staff" width="12"> <?php echo $star2['username']; ?> <?php echo $donatorimage; ?></td>
            <td align="center"><?php echo $star2['roles']; ?></td>
        </tr>
    <?php } ?>
        <tr>
            <td colspan="2"><hr /></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><b><u>Chat Moderators</u></b></td>
        </tr>
    <?php $start1 = $db->query("SELECT * FROM users WHERE staff='1'");
    while ($star1 = $db->fetch_row($start1)) { 
    if($star1['premiumdays'] >= '1') { $donatorimage = "<img src='../images/donator.png' alt='".$star1['premiumdays']."' width='12'>"; } else { $donatorimage = ""; }?>
        <tr>
            <td align="center"><img src="../images/staff.png" alt="Staff" width="12"> <?php echo $star1['username']; ?> <?php echo $donatorimage; ?></td>
            <td align="center"><?php echo $star1['roles']; ?></td>
        </tr>
    <?php } ?>
    
    </table>
    
    
    
    <?php
}
?>
