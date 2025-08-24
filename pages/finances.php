<?php include "../pages/dbconnect.php";

$userid = isset($_GET['u']) ? intval($_GET['u']) : 0;

if (!$userid) {
    die("Invalid user ID.");
}

$data = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($data);

if (!$ir) {
    die("User not found.");
}

$getinfo = $db->query("SELECT * FROM `money` WHERE userid=$userid ORDER BY id DESC LIMIT 100");

if ($getinfo) {
    ?>
    <table width="100%" border="1">
        <tr align="center">
            <td><b>Amount</b></td>
            <td><b>Description</b></td>
            <td><b>Date</b></td>
        </tr>
    <?php
    $i = 0;
    while($info=$db->fetch_row($getinfo)) {
        $outin = htmlspecialchars($info['outin'], ENT_QUOTES);
        $amount = intval($info['amount']);
        $item = $info['item'];
        $type = htmlspecialchars($info['type'], ENT_QUOTES);
        $time = date("jS F, Y, H:i:s",$info['date']+3600);
        
        if ($outin == 'out') { 
            $col='red'; 
            $sym='-'; 
        } else { 
            $col='green'; 
            $sym='+'; 
        }
        
        if ($type == 'airbucks') { 
            $col='blue'; 
            $moneytype=number_format($amount); 
        } else { 
            $moneytype=money_formatter($amount); 
        }
        ?>
        <tr border="1" align="center">
            <td><font color="<?php echo $col; ?>"><b><?php echo $sym.$moneytype; ?></b></font></td>
            <td><?php echo $item; ?></td>
            <td><?php echo $time; ?></td>
        </tr>
        <?php 
        $i++;
        //Delete Logs After the 100 Logs that are displayed have displayed. E.G Log 101 for user will auto delete leaving each user taking up 100 money logs max as they dont see past 100 anyways
        if ($i >= 100) {
            $deleteid = $info['id'];
            $db->query("DELETE FROM `money` WHERE id=$deleteid");
        }
    } 
    ?>
    <tr>
        <td colspan="3" align="center"><hr /></td>
    </tr>
    <tr>
        <td colspan="3" align="center" border="1"><b>LEGEND: <font color="red">Minus Bucks</font> - <font color="green">Plus Bucks</font> - <font color="blue">Minus AirBucks</font></b></td>
    </tr>
    </table>
    <?php
}
 else {
    die("Error retrieving user's transactions.");
}
