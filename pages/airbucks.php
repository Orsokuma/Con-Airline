
<?php include "../pages/dbconnect.php";

$paypal = 'contact@airlinemanagement.makeweb.games';        
$domain = 'airlinemanagement.makeweb.games';
$userid = isset($_GET['u']) ? $_GET['u'] : 0;
$data = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($data);

$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'trade': trade(); break;
case 'tradedo': tradedo(); break;
default:index(); break;
}



function index()
{
global $db,$userid,$ir,$paypal,$domain,$set;

$newjoin = time() - $ir['joineddate'] + 3600 <= 86400;
$value30 = 3;
$value50 = 5;
$value115 = 10;
$value300 = 20;
$value850 = 50;
$value2000 = 90;

if ($newjoin) {
    $value30 = 1.5;
    $value50 = 2.5;
    $value115 = 5;
    $value300 = 10;
    $value850 = 25;
    $value2000 = 45;
}


$sale = time() - $set['saleend'] + 3600 <= 86400;
if ($sale AND $set['sale'] == '1') {
    $saleperc = $set['saleperc']; //current 10
    // defining function
     function cal_percentage($saleperc, $total) {
      $count1 = ($total / 100) * $saleperc;
      $count2 = $total - $count1;
      $count = number_format($count2, 2);
      return $count;
    }
    $value30 = cal_percentage($saleperc, 3);
    $value50 = cal_percentage($saleperc, 5);
    $value115 = cal_percentage($saleperc, 10);
    $value300 = cal_percentage($saleperc, 20);
    $value850 = cal_percentage($saleperc, 50);
    $value2000 = cal_percentage($saleperc, 90);
    $event = $set['saleevent'];
}


$button1 = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type=hidden name=cmd value=_xclick>
                <input type="hidden" name="business" value="'.$paypal.'">
                <input type="hidden" name="item_name" value="'.$userid.' - 30 Coins">
                <input type="hidden" name="amount" value="'.$value30.'">
                <input type="hidden" name="no_shipping" value="0">
                <input type="hidden" name="return" value="https://'.$domain.'/donatordone.php?a=done&pack=1&u='.$userid.'">
                <input type="hidden" name="cancel_return" value="https://'.$domain.'/donatordone.php?a=cancel&u='.$userid.'">
                <input type="hidden" name="cn" value="Your Player ID">
                <input type="hidden" name="currency_code" value="GBP">
                <input type="hidden" name="tax" value="0">
                <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
            </form>';
            
            
$button2 = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type=hidden name=cmd value=_xclick>
                <input type="hidden" name="business" value="'.$paypal.'">
                <input type="hidden" name="item_name" value="'.$userid.' - 50 Coins">
                <input type="hidden" name="amount" value="'.$value50.'">
                <input type="hidden" name="no_shipping" value="0">
                <input type="hidden" name="return" value="https://'.$domain.'/donatordone.php?a=done&pack=2&u='.$userid.'">
                <input type="hidden" name="cancel_return" value="https://'.$domain.'/donatordone.php?a=cancel&u='.$userid.'">
                <input type="hidden" name="cn" value="Your Player ID">
                <input type="hidden" name="currency_code" value="GBP">
                <input type="hidden" name="tax" value="0">
                <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
            </form>';
            
$button3 = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type=hidden name=cmd value=_xclick>
                <input type="hidden" name="business" value="'.$paypal.'">
                <input type="hidden" name="item_name" value="'.$userid.' - 115 Coins">
                <input type="hidden" name="amount" value="'.$value115.'">
                <input type="hidden" name="no_shipping" value="0">
                <input type="hidden" name="return" value="https://'.$domain.'/donatordone.php?a=done&pack=3&u='.$userid.'">
                <input type="hidden" name="cancel_return" value="https://'.$domain.'/donatordone.php?a=cancel&u='.$userid.'">
                <input type="hidden" name="cn" value="Your Player ID">
                <input type="hidden" name="currency_code" value="GBP">
                <input type="hidden" name="tax" value="0">
                <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
            </form>';
            
$button4 = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type=hidden name=cmd value=_xclick>
                <input type="hidden" name="business" value="'.$paypal.'">
                <input type="hidden" name="item_name" value="'.$userid.' - 300 Coins">
                <input type="hidden" name="amount" value="'.$value300.'">
                <input type="hidden" name="no_shipping" value="0">
                <input type="hidden" name="return" value="https://'.$domain.'/donatordone.php?a=done&pack=4&u='.$userid.'">
                <input type="hidden" name="cancel_return" value="https://'.$domain.'/donatordone.php?a=cancel&u='.$userid.'">
                <input type="hidden" name="cn" value="Your Player ID">
                <input type="hidden" name="currency_code" value="GBP">
                <input type="hidden" name="tax" value="0">
                <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
            </form>';

$button5 = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type=hidden name=cmd value=_xclick>
                <input type="hidden" name="business" value="'.$paypal.'">
                <input type="hidden" name="item_name" value="'.$userid.' - 850 Coins">
                <input type="hidden" name="amount" value="'.$value850.'">
                <input type="hidden" name="no_shipping" value="0">
                <input type="hidden" name="return" value="https://'.$domain.'/donatordone.php?a=done&pack=5&u='.$userid.'">
                <input type="hidden" name="cancel_return" value="https://'.$domain.'/donatordone.php?a=cancel&u='.$userid.'">
                <input type="hidden" name="cn" value="Your Player ID">
                <input type="hidden" name="currency_code" value="GBP">
                <input type="hidden" name="tax" value="0">
                <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
            </form>';
            
$button6 = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type=hidden name=cmd value=_xclick>
                <input type="hidden" name="business" value="'.$paypal.'">
                <input type="hidden" name="item_name" value="'.$userid.' - 2000 Coins">
                <input type="hidden" name="amount" value="'.$value2000.'">
                <input type="hidden" name="no_shipping" value="0">
                <input type="hidden" name="return" value="https://'.$domain.'/donatordone.php?a=done&pack=6&u='.$userid.'">
                <input type="hidden" name="cancel_return" value="https://'.$domain.'/donatordone.php?a=cancel&u='.$userid.'">
                <input type="hidden" name="cn" value="Your Player ID">
                <input type="hidden" name="currency_code" value="GBP">
                <input type="hidden" name="tax" value="0">
                <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" border="0" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
            </form>';
?>

<table width="100%">
    <tr>
        <td><h4><center>You have <span class="airbucks"> <?php echo number_format($ir['airbucks']); ?></span> AirBucks.</center></h4>

<?php if ($newjoin) {?>
    <hr />
    <h5><center><b style="color:red">New Account Sale: 25% off on all AirBucks!</b></center></h5>
    
    <center><b><h6><span id="newUserCountdown"></span> remaining</h6></b></center>
    
    <script>
        
        var x = setInterval(() => {
            endtime =  ((<?php echo $ir['joineddate']; ?> + 86400) * 1000) - new Date().getTime();
            
            hours = Math.floor((endtime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            minutes = Math.floor((endtime % (1000 * 60 * 60)) / (1000 * 60));
            seconds = Math.floor((endtime % (1000 * 60)) / (1000));
            
            document.getElementById("newUserCountdown").innerHTML = hours + " hours " + minutes + " minutes " + seconds + " seconds ";
            
            if (endtime <= 0) {
                clearInterval(x);
            }
        }, 1000);
        
    </script>
<?php }?>


<?php if ($sale AND $set['sale'] == '1') {?>
    <hr />
    <h5><center><b style="color:red"><?php echo $event;?> Sale: <?php echo $set['saleperc']; ?>% off on all AirBucks!</b></center></h5>
    
    <center><b><h6><span id="SaleCountdown"></span> remaining</h6></b></center>
    
    <script>
        
        var x = setInterval(() => {
            endtime =  (<?php echo $set['saleend']; ?> * 1000) - new Date().getTime();
            
            days = Math.floor((endtime % (1000 * 60 * 60 * 60 * 24)) / (1000 * 60 * 60 * 24));
            hours = Math.floor((endtime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            minutes = Math.floor((endtime % (1000 * 60 * 60)) / (1000 * 60));
            seconds = Math.floor((endtime % (1000 * 60)) / (1000));
            
            document.getElementById("SaleCountdown").innerHTML = days + " days " + hours + " hours " + minutes + " minutes " + seconds + " seconds ";
            
            if (endtime <= 0) {
                clearInterval(x);
            }
        }, 1000);

    </script>
<?php }?>

<div class="row"><hr /></div>
<div class="row">
    
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">30 AirBucks</h4><br />
      <p class="card-text"><b>£3<?php if (!$newjoin){ echo ""; } else { echo "<s style='color: red'>£3</s> £2.25";} ?><?php if ($set['sale'] == '0'){ echo ""; } else { echo "<s style='color: red'>£3</s> £".$value30;} ?></b></p>
      <?php echo $button1; ?>
    </div>
</div>
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">50 AirBucks</h4><br />
      <p class="card-text"><b>£5<?php if (!$newjoin){ echo ""; } else { echo "<s style='color: red'>£5</s> £3.75";} ?><?php if ($set['sale'] == '0'){ echo ""; } else { echo "<s style='color: red'>£5</s> £".$value50;} ?></b></p>
      <?php echo $button2; ?>
    </div>
</div>
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">115 AirBucks</h4>
      <span class="badge bg-success">13% cheaper</span><br />
      <p class="card-text"><b>£10<?php if (!$newjoin){ echo ""; } else { echo "<s style='color: red'>£10</s> £7.50";} ?><?php if ($set['sale'] == '0'){ echo ""; } else { echo "<s style='color: red'>£10</s> £".$value115;} ?></b></p>
      <?php echo $button3; ?>
    </div>
</div>
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">300 AirBucks</h4>
      <span class="badge bg-success">33% cheaper</span><br />
      <p class="card-text"><b>£20<?php if (!$newjoin){ echo ""; } else { echo "<s style='color: red'>£20</s> £15";} ?><?php if ($set['sale'] == '0'){ echo ""; } else { echo "<s style='color: red'>£20</s> £".$value300;} ?></b></p>
      <?php echo $button4; ?>
    </div>
</div>

</div>


<div class="row"><hr /></div>
<div class="row">
    
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">850 AirBucks</h4>
      <span class="badge bg-success">41% cheaper</span><br />
      <p class="card-text"><b>£50<?php if (!$newjoin){ echo ""; } else { echo "<s style='color: red'>£50</s> £37.50";} ?><?php if ($set['sale'] == '0'){ echo ""; } else { echo "<s style='color: red'>£50</s> £".$value850;} ?></b></p>
      <?php echo $button5; ?>
    </div>
</div>
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">2000 AirBucks</h4>
      <span class="badge bg-success">55% cheaper</span><br />
      <p class="card-text"><b>£90<?php if (!$newjoin){ echo ""; } else { echo "<s style='color: red'>£90</s> £67.50";} ?><?php if ($set['sale'] == '0'){ echo ""; } else { echo "<s style='color: red'>£90</s> £".$value2000;} ?></b></p>
      <?php echo $button6; ?>
    </div>
</div>

</div>
<div class="row"><hr /></div>

<h4><center>You have <span class="airbucks"> <?php echo number_format($ir['premiumdays']); ?></span> Premium Days.</center></h4>
<div class="row"><hr /></div>
<div class="row">
    
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">30 Premium Days <br /><br /></h4>
      <p class="card-text"><b>34 Airbucks<br /></b></p>
        <form action="?a=trade&u=<?php echo $userid; ?>" target="airbucks" method="post">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="hidden" name="p" value="1">
            <input type="submit" name="" value="Trade" class="btn btn-info">
        </form>
    </div>
</div>
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">3 Months Premium</h4>
      <span class="badge bg-success">14% cheaper</span><br />
      <p class="card-text"><b>88 Airbucks</b></p>
        <form action="?a=trade&u=<?php echo $userid; ?>" target="airbucks" method="post">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="hidden" name="p" value="2">
            <input type="submit" name="" value="Trade" class="btn btn-info">
        </form>
    </div>
</div>

<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">6 Months Premium</h4>
      <span class="badge bg-success">29% cheaper</span><br />
      <p class="card-text"><b>147 Airbucks</b></p>
        <form action="?a=trade&u=<?php echo $userid; ?>" target="airbucks" method="post">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="hidden" name="p" value="3">
            <input type="submit" name="" value="Trade" class="btn btn-info">
        </form>
    </div>
</div>
<div class="card" style="width:350px">
    <div class="card-body">
      <h4 class="card-title">12 Months Premium</h4>
      <span class="badge bg-success">57% cheaper</span><br />
      <p class="card-text"><b>180 Airbucks</b></p>
        <form action="?a=trade&u=<?php echo $userid; ?>" target="airbucks" method="post">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="hidden" name="p" value="4">
            <input type="submit" name="" value="Trade" class="btn btn-info">
        </form>
    </div>
</div>

</div>
<ul>
    <li><u><b>You Get Access to:</b></u></li>
    <li>Change Site Theme Colours.</li>
    <li>Custom Company Logos.</li>
    <li>Better Payouts on Flights.</li>
</ul>

        </td>
    </tr>
</table>
 <?php
}
        
        
        
function trade()
{
global $db,$paypal,$domain;
$pack = $_POST['p'];
$userid = $_POST['userid'];
$data = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($data);

if($pack == '1') {
    if($ir['airbucks'] <= '33') {
        echo 'You dont have enough Airbucks for this action.<br /><a href="airbucks.php?u='.$userid.'" target="airbucks">Continue</a>';
    } else {
        $amount = '30';
        $period = 'Days';
        $cost = '34';
        $db->query("UPDATE users SET airbucks=airbucks-$cost, premiumdays=premiumdays+$amount WHERE userid=$userid");
        echo 'You have Traded '.$cost.' AirBucks for '.$amount.' '.$period.'<br /><br /><a href="airbucks.php?u='.$userid.'" target="airbucks">Continue</a>';
        $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$cost','Premium 30 Days',unix_timestamp(),'airbucks')");
    }
} else 
if($pack == '2') {
    if($ir['airbucks'] <= '87') {
        echo 'You dont have enough Airbucks for this action.<br /><a href="airbucks.php?u='.$userid.'" target="airbucks">Continue</a>';
    } else {
        $amount = '3';
        $period = 'Months';
        $cost = '88';
        $db->query("UPDATE users SET airbucks=airbucks-$cost, premiumdays=premiumdays+92 WHERE userid=$userid");
        echo 'You have Traded '.$cost.' AirBucks for '.$amount.' '.$period.'<br /><br /><a href="airbucks.php?u='.$userid.'" target="airbucks">Continue</a>';
        $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$cost','Premium 3 Months',unix_timestamp(),'airbucks')");
    }
} else
if($pack == '3') {
    if($ir['airbucks'] <= '149') {
        echo 'You dont have enough Airbucks for this action.<br /><a href="airbucks.php?u='.$userid.'" target="airbucks">Continue</a>';
    } else {
        $amount = '6';
        $period = 'Months';
        $cost = '150';
        $db->query("UPDATE users SET airbucks=airbucks-$cost, premiumdays=premiumdays+184 WHERE userid=$userid");
        echo 'You have Traded '.$cost.' AirBucks for '.$amount.' '.$period.'<br /><br /><a href="airbucks.php?u='.$userid.'" target="airbucks">Continue</a>';
        $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$cost','Premium 6 Months',unix_timestamp(),'airbucks')");
    }
} else
if($pack == '4') {
    if($ir['airbucks'] <= '179') {
        echo 'You dont have enough Airbucks for this action.<br /><a href="airbucks.php?u='.$userid.'" target="airbucks">Continue</a>';
    } else {
        $amount = '12';
        $period = 'Months';
        $cost = '180';
        $db->query("UPDATE users SET airbucks=airbucks-$cost, premiumdays=premiumdays+365 WHERE userid=$userid");
        echo 'You have Traded '.$cost.' AirBucks for '.$amount.' '.$period.'<br /><br /><a href="airbucks.php?u='.$userid.'" target="airbucks">Continue</a>';
        $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$cost','Premium 12 Months',unix_timestamp(),'airbucks')");
    }
}

}




















