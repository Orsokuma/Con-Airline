<?php include "../pages/dbconnect.php";

$userid = $_GET['u'];
$dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($dbbd);
$theme = $ir['theme'];

if ($theme == 'light') { $color='f7f7f7'; }
if ($theme == 'dark') { $color='292b2c'; }
if ($theme == 'primary') { $color='0275d8'; }
if ($theme == 'secondary') { $color='cccccc'; }
if ($theme == 'success') { $color='5cb85c'; }
if ($theme == 'info') { $color='5bc0de'; }
if ($theme == 'warning') { $color='f0ad4e'; }
if ($theme == 'danger') { $color='d9534f'; }

if($theme == 'light' OR $theme == 'info' OR $theme == 'warning') {
    $text = '000000';
} else {
    $text = 'ffffff';
}

?>

<style type="text/css" media="screen">
table.greenTable {
  font-family: Georgia, serif;
  border: 6px solid #<?php echo $color;?>;
  background-color: #<?php echo $color;?>;
  width: 100%;
  text-align: center;
}
table.greenTable td, table.greenTable th {
  border: 1px solid #<?php echo $color;?>;
  padding: 3px 2px;
  color: #<?php echo $text;?>;
}
table.greenTable tbody td {
  font-size: 13px;
}
table.greenTable tr:nth-child(even) {
  background: #<?php echo $color;?>;
}
table.greenTable thead {
  background: #<?php echo $color;?>;
  background: -moz-linear-gradient(top, #b3d965 0%, #a4d246 66%, #<?php echo $color;?> 100%);
  background: -webkit-linear-gradient(top, #b3d965 0%, #a4d246 66%, #<?php echo $color;?> 100%);
  background: linear-gradient(to bottom, #b3d965 0%, #a4d246 66%, #<?php echo $color;?> 100%);
  border-bottom: 0px solid #444444;
}
table.greenTable thead th {
  font-size: 19px;
  font-weight: bold;
  color: #<?php echo $text;?>;
  text-align: left;
  border-left: 2px solid #<?php echo $color;?>;
}
table.greenTable thead th:first-child {
  border-left: none;
}

table.greenTable tfoot {
  font-size: 13px;
  font-weight: bold;
  color: #<?php echo $text;?>;
  background: #24943A;
  background: -moz-linear-gradient(top, #5baf6b 0%, #3a9e4d 66%, #24943A 100%);
  background: -webkit-linear-gradient(top, #5baf6b 0%, #3a9e4d 66%, #24943A 100%);
  background: linear-gradient(to bottom, #5baf6b 0%, #3a9e4d 66%, #24943A 100%);
  border-top: 1px solid #24943A;
}
table.greenTable tfoot td {
  font-size: 13px;
}
table.greenTable tfoot .links {
  text-align: right;
}
table.greenTable tfoot .links a{
  display: inline-block;
  background: #FFFFFF;
  color: #<?php echo $text;?>;
  padding: 2px 8px;
  border-radius: 5px;
}

    
</style>

<?php
$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'x': x(); break;
default:index(); break;
}





function index() {
    global $db;
    $userid = $_GET['u'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $box_cost = $ir['box'];
    $bc_format = money_formatter($box_cost);
    
    if (isset($_GET['day']) && $_GET['day']) {
        if ($ir['box'] >= 1) {
            die("Sorry, you have already opened a box today. Come back tomorrow.");
        }
        $num = $_GET['day'];
        $db->query("UPDATE users SET box=1 WHERE userid=$userid");
            
            switch ($num) {
                case 1:
                    $bucks = '100000';
                    $buck = money_formatter($bucks);
                    echo "Todays Box you got: ".$buck." bucks";
                    $db->query("UPDATE users SET bucks=bucks+$bucks, day=2 WHERE userid=$userid");
                    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$bucks','Daily Reward: $buck',unix_timestamp(),'bucks')");
                break;
                
                case 2:
                    $bucks = '200000';
                    $buck = money_formatter($bucks);
                    echo "Todays Box you got: ".$buck." bucks";
                    $db->query("UPDATE users SET bucks=bucks+$bucks, day=3 WHERE userid=$userid");
                    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$bucks','Daily Reward: $buck',unix_timestamp(),'bucks')");
                break;
                
                case 3:
                    $bucks = '300000';
                    $buck = money_formatter($bucks);
                    echo "Todays Box you got: ".$buck." bucks";
                    $db->query("UPDATE users SET bucks=bucks+$bucks, day=4 WHERE userid=$userid");
                    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$bucks','Daily Reward: $buck',unix_timestamp(),'bucks')");
                break;
                
                case 4:
                    $bucks = '400000';
                    $buck = money_formatter($bucks);
                    echo "Todays Box you got: ".$buck." bucks";
                    $db->query("UPDATE users SET bucks=bucks+$bucks, day=5 WHERE userid=$userid");
                    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$bucks','Daily Reward: $buck',unix_timestamp(),'bucks')");
                break;
                
                case 5:
                    $bucks = '500000';
                    $buck = money_formatter($bucks);
                    echo "Todays Box you got: ".$buck." bucks";
                    $db->query("UPDATE users SET bucks=bucks+$bucks, day=6 WHERE userid=$userid");
                    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$bucks','Daily Reward: $buck',unix_timestamp(),'bucks')");
                break;
                
                case 6:
                    $bucks = '600000';
                    $buck = money_formatter($bucks);
                    echo "Todays Box you got: ".$buck." bucks";
                    $db->query("UPDATE users SET bucks=bucks+$bucks, day=7 WHERE userid=$userid");
                    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$bucks','Daily Reward: $buck',unix_timestamp(),'bucks')");
                break;
                
                case 7:
                    $bucks = '5';
                    $buck = number_format($bucks);
                    echo "Todays Box you got: ".$buck." bucks";
                    $db->query("UPDATE users SET airbucks=airbucks+$bucks, day=1 WHERE userid=$userid");
                    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$bucks','Daily Reward: $buck',unix_timestamp(),'airbucks')");
                break;
            }
        echo "<hr />";
    } else {

    $day = $ir['day'];
    $box = $ir['box'];
    if ($box == '0') { echo 'Ready to Open Todays Box'; } else { echo 'Come Back Tomorrow.'; } echo '<hr /><br />
    <table class="greenTable" style="height: 296px;" width="615"><tbody>
        
        <tr>';
            $reward = $db->query("SELECT * FROM dailyrewards WHERE week=1 ORDER BY day");
            while ($dr = $db->fetch_row($reward)) {    
                echo '<td>DAY: '.$dr['day'].'<br /><br /><b>'.number_format($dr['amount']).' '.$dr['prizedisplay'].'</b></td>';
            }
            echo '</tr>
        
        
        <tr>
            <td>'; if ($day <= '1') { echo '<a href="?a=index&day=1&u='.$userid.'"><span class="brightred"><b>CLAIM</b></span></a>'; } echo '</td>
            <td>'; if ($day == '2') { echo '<a href="?a=index&day=2&u='.$userid.'"><span class="brightred"><b>CLAIM</b></span></a>'; } echo '</td>
            <td>'; if ($day == '3') { echo '<a href="?a=index&day=3&u='.$userid.'"><span class="brightred"><b>CLAIM</b></span></a>'; } echo '</td>
            <td>'; if ($day == '4') { echo '<a href="?a=index&day=4&u='.$userid.'"><span class="brightred"><b>CLAIM</b></span></a>'; } echo '</td>
            <td>'; if ($day == '5') { echo '<a href="?a=index&day=5&u='.$userid.'"><span class="brightred"><b>CLAIM</b></span></a>'; } echo '</td>
            <td>'; if ($day == '6') { echo '<a href="?a=index&day=6&u='.$userid.'"><span class="brightred"><b>CLAIM</b></span></a>'; } echo '</td>
            <td>'; if ($day == '7') { echo '<a href="?a=index&day=7&u='.$userid.'"><span class="brightred"><b>CLAIM</b></span></a>'; } echo '</td>
        </tr>

    

    </tbody>
    </table>
    <br /><hr /></center>
            <h5>Info</h5><small>
            1. You can Claim 1 Reward a day.<br />
            2. Miss a Day and you back to Day 1.<br />';
    }
}
