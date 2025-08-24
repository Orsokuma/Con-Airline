<?php include "../pages/dbconnect.php";


    
$userid = isset($_GET['u']) ? $_GET['u'] : 0;  
$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
    case 'add': add(); break;
    case 'adddo': adddo(); break;
    case 'edit': edit(); break;
    case 'del': delete();
    default:index(); break;
}


function index() {
    global $db;
        $userid = $_GET['u'];
        $fet = $db->query("SELECT * FROM users WHERE userid=$userid");
        $ir = $db->fetch_row($fet);
        if($ir['staff'] == '3') { echo "<a href='?a=add&u=$userid'>ADD CHANGE</a>"; }
        
        ?>
        <table width="100%">
            <tr>
                <th>Change</th>
                <th>By</th>
                <th>Time</th>
                <td></td>
            </tr>
            <tr>
                <td colspan="4"><hr /></td>
            </tr>
            <?php
            $clq = $db->query("SELECT * FROM changelog ORDER BY id DESC");
            while($data=$db->fetch_row($clq)) {
                $who = $data['who'];
                $fetch = $db->query("SELECT * FROM users WHERE userid=$who");
                $r = $db->fetch_row($fetch);
                $dat = $data['date'];
                $date = date('d/m/Y h:i:s a', $dat);
                $id = $data['id'];
                ?>
            <tr>
                <td><?php echo $data['changed']; ?></td>
                <td><?php echo $r['username']; ?></td>
                <td><?php echo $date; ?></td>
                <td><?php if($ir['staff'] == '3') { echo "<a href='?a=del&u=$userid&id=$id'>[X]</a>"; } ?></td>
            </tr>
            <?php } ?>
           
        </table>
        
        
        <?php
}

function add() {
    global $db;
        $userid = $_GET['u'];
        $fet = $db->query("SELECT * FROM users WHERE userid=$userid");
        $ir = $db->fetch_row($fet);
        if(!$ir['staff'] == '3') { die("ACCESS DENIED"); }
        
        ?>
        <form action='?a=adddo&u=<?php echo $userid; ?>' method='POST' target="changelog">
            Change: <input type='text' name='change' class="form-control" /><br />
            <input type='hidden' name='date' value="<?php echo time(); ?>" />
        	<input type="hidden" name="userid" value="<?php echo $userid;?>">
        	<input type='submit' value='Add Change' class="btn btn-info" />
    	</form>
    	<form action='?a=index&u=<?php echo $userid; ?>' method='POST' target="changelog">
        	<input type="hidden" name="u" value="<?php echo $userid;?>">
        	<input type='submit' value='Back' class="btn btn-info" />
    	</form>
        <?php
}

function adddo() {
    global $db; 
        $userid = $_GET['u'];
        $fet = $db->query("SELECT * FROM users WHERE userid=$userid");
        $ir = $db->fetch_row($fet);
        if(!$ir['staff'] == '3') { die("ACCESS DENIED"); }
        $change = $_POST['change'];
        $date = $_POST['date'];
        $userid = $_POST['userid'];
        $db->query("INSERT INTO `changelog`(`id`, `changed`, `who`, `date`) VALUES ('','$change','$userid','$date')");
        echo "Change log Added.<Br /><br />"; ?>
        <form action='?a=index&u=<?php echo $userid; ?>' method='POST' target="changelog">
        	<input type="hidden" name="u" value="<?php echo $userid;?>">
        	<input type='submit' value='Continue' class="btn btn-info" />
    	</form>
        <?php
}




function delete() {
    global $db; 
        $userid = $_GET['u'];
        $id = $_GET['id'];
        $fet = $db->query("SELECT * FROM users WHERE userid=$userid");
        $ir = $db->fetch_row($fet);
        if(!$ir['staff'] == '3') { die("ACCESS DENIED"); }
        $db->query("DELETE FROM `changelog` WHERE id=$id");
        echo "Change log Deleted.<Br /><br />"; ?>
        <form action='?a=index&u=<?php echo $userid; ?>' method='POST' target="changelog">
        	<input type="hidden" name="u" value="<?php echo $userid;?>">
        	<input type='submit' value='Continue' class="btn btn-info" />
    	</form>
        <?php
}

















