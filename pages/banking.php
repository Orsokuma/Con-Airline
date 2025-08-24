
<?php include "../pages/dbconnect.php";



$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'settickets': settickets(); break;
case 'setticketsdo': setticketsdo(); break;
case 'loan': loan(); break;
case 'loanview': loanview(); break;
case 'loanaccept': loanaccept(); break;
case 'loanrepay': loanrepay(); break;
case 'loanrepaydo': loanrepaydo(); break;
default:index(); break;
}




function index() {
    global $db;
    $userid = $_GET['u'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $fe=$db->query("SELECT * FROM userairplanes");
    $userplane = $db->query($fe);
    ?>
    <table>
        <tr>
            <td>Total Money Made:</td>
            <td><b><?php
                echo money_formatter($ir['totalmoney']); ?></b>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>Ticket Prices:</td>
            <td><b><?php echo money_formatter($ir['tickets1']); ?></b>
            </td>
            <td><form action="?a=settickets" method="post">
                    <input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="banking">
                    <input type="submit" name="" value="Set Ticket Price" class="btn btn-info btn-sm">
            </form></td>
        </tr>
        <tr>
            <td>Loans</td>
            <td></td>
            <td><form action="?a=loan" method="post">
                    <input type="hidden" name="userid" value="<?php echo $ir['userid'];?>" target="banking">
                    <input type="submit" name="" value="See Loans" class="btn btn-info btn-sm">
            </form></td>
        </tr>
    </table>
    <hr />
<?php
}






function loan() {
    global $db;
    $userid = $_POST['userid'];

    $query = $db->query("SELECT * FROM bank ORDER BY repreq");
    ?><center><a href="?a=index&u=<?php echo $userid; ?>" class="btn btn-info"><b><u>BACK TO BANKING</u></b></a><br /><hr /></center>
    <h4><b><u>New Loans</u></b></h4>
    <table width="95%" align="center">
        <tr>
            <td><b><u>Bank</u></b></td>
            <td><b><u>Loan Amounts (Min - Max)</u></b></td>
            <td><b><u>Interest</u></b></td>
            <td><b><u>Required Rep</u></b></td>
            <td><b><u>Options</u></b></td>
        </tr>
    <?php while ($bank = $db->fetch_row($query)) { ?>
        <tr>
            <td><?php echo $bank['bank']; ?></td>
            <td>
                <table width="250px">
                    <tr>
                        <td width="100px"><font color="red"><?php echo money_formatter($bank['min']); ?></font></td>
                        <td width="50px" align="center"> - </td>
                        <td width="100px"><font color="green"><?php echo money_formatter($bank['max']); ?></font></td>
                    </tr>
                </table>
                
                
                
                
                </td>
            <td><font color="blue"><?php echo $bank['interest']; ?>%</font></td>
            <td><?php echo number_format($bank['repreq'],5); ?></td>
            <td><form action="?a=loanview" method="post">
                <input type="hidden" name="userid" value="<?php echo $userid; ?>" target="banking">
                <input type="hidden" name="bank" value="<?php echo $bank['id']; ?>" target="banking">
                <input type="submit" name="" value="See Options" class="btn btn-info btn-sm">
        </form></td>
        </tr>
    <?php } ?>
    </table>
    <hr />
    <h4><b><u>Current Loans</u></b></h4>
    <table width="95%" align="center">
        <tr>
            <td><b><u>Bank</u></b></td>
            <td><b><u>Amount left to repay</u></b></td>
            <td><b><u>Repaying Daily</u></b></td>
            <td><b><u>Options</u></b></td>
        </tr>
    <?php $que = $db->query("SELECT * FROM bankloans WHERE userid=$userid"); while ($ba = $db->fetch_row($que)) { ?>
        <tr>
            <td><?php echo $ba['bank']; ?></td>
            <td><?php echo money_formatter($ba['amount']); ?></td>
            <td><?php echo money_formatter($ba['perday']); ?></td>
            <td><form action="?a=loanrepay" method="post">
                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                <input type="submit" name="" value="Payback" class="btn btn-info btn-sm">
        </form></td>
        </tr>
    
    <?php }
    echo '</table>';
    }


function loanrepay() {
    global $db;
    $userid = $_POST['userid'];
    $que = $db->query("SELECT * FROM bankloans WHERE userid=$userid"); 
    $ba = $db->fetch_row($que); 
    ?>
    So you want to repay you loan back early?<br />
    <form action="?a=loanrepaydo" method="post" target="banking">
    <b>Daily Repayment Rate: </b> <input type="range" step="1" value="1" min="1" max="<?php echo $ba['amount']; ?>" class="form-range" id="repay" name="repay" oninput="this.nextElementSibling.value = this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')">$<output>1</output><br />
    <input type="hidden" name="userid" value="<?php echo $userid; ?>" target="banking">
    <input type="submit" value="Payback" class="btn btn-info btn-sm">
    </form>
    <?php
}




function loanrepaydo() {
    global $db, $backpage;
    $userid = $_POST['userid'];
    $repay = $_POST['repay'];
    
    $que = $db->query("SELECT * FROM bankloans WHERE userid=$userid"); 
    $ba = $db->fetch_row($que); 
    $query = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($query);
    if ($ir['bucks'] <= $repay) {
        die("You are trying to pay back more than you have.");
    }
    
    if ($repay == $ba['amount']) {
        // DO Full Repayment Queries
        $db->query("UPDATE users SET bucks=bucks-$repay, loan=0 WHERE userid=$userid");
        $db->query("DELETE FROM bankloans WHERE userid=$userid");
        $numbs = number_format($repay);
        $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$repay','<b>Repaid Loan</b>',unix_timestamp(),'bucks')");
        echo 'You have paid off your loan to the bank';
        echo '<br /><a href="?a=index&u='.$userid.'" class="btn btn-info">BACK</a>';
    }
    
    if ($repay < $ba['amount']) {
        // Do Partial Repayment Queries
        $db->query("UPDATE users SET bucks=bucks-$repay WHERE userid=$userid");
        $db->query("UPDATE bankloans SET amount=amount-$repay WHERE userid=$userid");
        $numbs = number_format($repay);
        $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$repay','<b>Repaying Loan:</b> Paid: <b>$repay</b>',unix_timestamp(),'bucks')");
        echo 'You have paid '.money_formatter($repay).' off you loan to the bank';
        echo '<br /><a href="?a=index&u='.$userid.'" class="btn btn-info">BACK</a>';
    }
}









function loanview() {
    global $db;
    $userid = $_POST['userid'];
    $bank = $_POST['bank'];
    $que = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($que);
    $query = $db->query("SELECT * FROM bank WHERE id=$bank");
    $bank = $db->fetch_row($query);
    
    
    if ($ir['loan'] == '1') { die("<center>You already have a loan.<br />You may only have one loan at a time.<br /><a href='?a=index&u=$userid'>BACK</a></center>"); } 
    if ($ir['reputation'] <= $bank['repreq']) { die("<center>You don't have enough Reputation for this Loan.<br /><br /><a href='?a=index&u=$userid'>BACK</a></center>"); } ?>
    <center><a href="?a=index&u=<?php echo $userid; ?>" class="btn btn-info"><b><u>BACK TO BANKING</u></b></a><br /><hr /></center>
    <table width="95%" align="center">
        <tr>
            <td><b>Bank</b></td>
            <td><b>Loan Amounts</b></td>
            <td><b>Interest</b></td>
            <td><b>Required Rep</b></td>
        </tr>
        <tr>
            <td><?php echo $bank['bank']; ?></td>
            <td><font color="red"><?php echo money_formatter($bank['min']); ?></font> - <font color="green"><?php echo money_formatter($bank['max']); ?></font></td>
            <td><font color="blue"><?php echo $bank['interest']; ?>%</font></td>
            <td><?php echo number_format($bank['repreq'],5); ?></td>
        </tr>
        <tr>
            <td colspan="4"><hr /></td>
        </tr>
        <tr>
            <td colspan="3">
                <script>
                function myFunction() {
                  var x = parseFloat(document.getElementById("amount").value);
                  document.getElementById("result1").innerHTML = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                  num1 = parseFloat(document.getElementById("interest").value);
                  document.getElementById("result2").innerHTML = (Math.round((num1 / 100) * x, 0) + x).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                  document.getElementById("totalRepay").value = Math.round((num1 / 100) * x, 0) + x;
                }
            </script>
            <form action="?a=loanaccept" method="post" target="banking">
                <?php // This below is wrong only because of the above script? ?>
                <input type="hidden" name="userid" value="<?php echo $userid;?>">
                <input type="hidden" name="bank" value="<?php echo $bank['bank'];?>">
                <input type="hidden" id="interest" value="<?php echo $bank['interest']; ?>" />
                <b>Loan Amount: </b> $<span id="result1"><?php echo number_format($bank['min']); ?></span><br />
                <input type="range" step="100000" value="1" min="<?php echo $bank['min']; ?>" max="<?php echo $bank['max']; ?>" class="form-range" id="amount" name="amount" oninput="myFunction()"><br />
                <input type="hidden" id="totalRepay" name="totalRepay" value="100000">
                <b>Total Repayment: </b> $<span id="result2">100,000</span><br /><hr />
                <b>Daily Repayment Rate: </b> <input type="range" step="100000" value="1" min="100000" max="<?php echo $bank['max'] / 5; // This range should max out at the above range? ?>" class="form-range" id="repay" name="repay" oninput="this.nextElementSibling.value = this.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')">
                    $<output>100,000</output> Per day<br />
                    <hr />
                <input type="submit" name="" value="Take Loan" class="btn btn-info">
            </form></td>
            <td></td>
        </tr>
    </table>
    <?php 
}




function loanaccept() {
    global $db;
    $userid = $_POST['userid'];
    $amountwi = $_POST['totalRepay'];
    $amount = $_POST['amount'];
    $perday = $_POST['repay'];
	$bank = $_POST['bank'];
	
	$db->query("UPDATE users SET bucks=bucks+$amount, loan=1 WHERE userid=$userid");
	$db->query("INSERT INTO `bankloans`(`id`, `userid`, `amount`, `perday`, `bank`) VALUES ('','$userid','$amountwi','$perday', '$bank')");
	$numbs = number_format($perday);
	$db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','in','$amount','<b>Bank Loan</b>: Repaying <b>$numbs</b> per day.',unix_timestamp(),'bucks')");
	?>
	You have taken a loan from the bank of <?php echo money_formatter($amount); ?> from the bank.<br />
	You have to repay <?php echo money_formatter($perday); ?> per day at Midnight.<Br />
	<a href="?a=index&u=<?php echo $userid; ?>" class="btn btn-info">BACK</a>
	<?php
}



function settickets() {
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    
    $maxticket = "2000";
    $maxlevel = 20;
    $level = $ir['level'];
    $max = isset($level) ? min(intval($level) * 100, $maxticket, $maxlevel * 100) : null;

    
    ?>
    Move the Scroller to what you wish to set Ticket prices too.<br />
    You HQ Level is <b><?php echo $ir['level']; ?></b>.<br />
    This allows you to set a Max Ticket price of <b><?php echo money_formatter($ir['tickets1']); ?></b><br />
<form action="?a=setticketsdo" method="post" target="banking">
    
<table width="100%">
    <tr>
        <td width="75%"><input type="hidden" name="userid" value="<?php echo $userid;?>">
            <input type="range" value="<?php echo $ir['tickets1']; ?>" min="1" max="<?php echo $max; ?>" class="form-range" id="prices" name="prices" oninput="this.nextElementSibling.value = this.value"><output><?php echo $ir['tickets1']; ?></output></td>
        <td width="25%"><input type="submit" name="" value="Set Price" class="btn btn-info"></td>
    </tr>
</table>    

</form>    
    
<?php
}

function setticketsdo() {
    global $db;
    $userid = $_POST['userid'];
    $dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
    $ir = $db->fetch_row($dbbd);
    $ticket = $_POST['prices'];
    
    $db->query("UPDATE users SET tickets1=$ticket WHERE userid=$userid");
    echo 'Your passengers will now pay '.money_formatter($ticket).' per person.<br />
    <center><a href="?a=index&u='.$userid.'" class="btn btn-info"><b><u>BACK TO BANKING</u></b></a><br /><hr /></center>
    ';
}



?>
