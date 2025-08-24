<?php include "../pages/dbconnect.php";

$userid = isset($_GET['u']) ? $_GET['u'] : 0;
$fuelcost = '250';

$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'increasestorage': increasestorage(); break;
case 'increasestoragedo': increasestoragedo(); break;
case 'buyfuel': buyfuel(); break;
case 'buyfueldo': buyfueldo(); break;
default:index(); break;
}




function index()
{
global $db, $userid;
$pla = $db->query("SELECT * FROM userairplanes WHERE planeOWNER=$userid");
$planeinfo = $db->fetch_row($pla);
$use = $db->query("SELECT * FROM users WHERE userid=$userid");
$userinfo = $db->fetch_row($use);

?>    

<table width="100%">
    <tr>
        <td colspan="3"><h4><b><u>Fuel</u></b></h4></td>
    </tr>
    <tr>
        <td><b>Fuel Storage:</b></td>
        <td align="left"><?php echo number_format($userinfo['fuelstorage']); ?> / <?php echo number_format($userinfo['fuelstoragemax']); ?> litres</td>
        <td><div class="btn-group"><form action="?a=buyfuel" target="fuel" method="post">
        
                        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                        <input type="submit" name="" value="Buy Fuel" class="btn btn-info">
                    
            </form> 
            <form action="?a=increasestorage" target="fuel" method="post">
        
                        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                        <input type="submit" name="" value="Increase Storage" class="btn btn-info">
                    
            </form></div>
        </td>
    </tr>
    <tr>
        <td colspan="3"><h4><b><u>Catering</u></b></h4></td>
    </tr>
</table>
<?php
}



function increasestorage()
{
global $db,$set,$fuelcost;
$userid = $_POST['userid'];
$query = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($query);
$rep = round($ir['reputation']);
$max = $rep * 10000;
?>
<br />
You current have a Storage Limit of <b><?php echo number_format($ir['fuelstoragemax']); ?></b><br />

<form action="?a=increasestoragedo" target="fuel" method="post">
            <script>
                function myFunction() {
                  var x = document.getElementById("storage").value;
                  document.getElementById("result1").innerHTML = + x;
                  num1 = document.getElementById("fuelcost").value;
                  num2 = document.getElementById("storage").value;
                  document.getElementById("result2").innerHTML = (Math.round(num1 * num2 * 100) / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            </script>
            <input type="hidden" id="fuelcost" value="<?php echo $fuelcost; ?>" />
            <input type="range" value="100" min="100" step="100" max="<?php echo $max; ?>" class="form-range" id="storage" name="storage" oninput="myFunction()"><br />

            <b>Storage by: </b> <span id="result1">0</span> litres<br />
            <b>Cost: </b> $<span id="result2">0</span>

<br />
<input type="hidden" name="userid" value="<?php echo $userid; ?>">

<input type="submit" name="" value="Increase Storage" class="btn btn-info">
</form><?php 
}






function increasestoragedo()
{
global $db,$set,$fuelcost;
$userid = $_POST['userid'];
$query = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($query);

$storage = $_POST['storage'];
$userbucks = $ir['bucks'];
$cost = $storage*$fuelcost;

if($cost > $userbucks) {
    echo 'You do not have enough bucks to afford to buy this much storage.<br /><a href="?a=index&u='.$userid.'" target="fuel" class="btn btn-info">Continue</a>';
} else {
    echo 'You have increased your storage by '.number_format($storage).' litres at a cost of '.money_formatter($cost);
    echo '<a href="../pages/fuelcater.php?u='.$userid.'" target="fuel" class="btn btn-info">Continue</a>';
    $db->query("UPDATE users SET fuelstoragemax=fuelstoragemax+$storage, bucks=bucks-$cost WHERE userid=$userid");
    $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$cost','Purchase: Fuel Storage: <b>+$storage</b>',unix_timestamp(),'bucks')");
    
}

}





function buyfuel()
{
global $db, $userid,$set;
$userid = isset($_POST['userid']) ? $_POST['userid'] : 0;
$pla = $db->query("SELECT * FROM userairplanes WHERE planeOWNER=$userid");
$planeinfo = $db->fetch_row($pla);
$use = $db->query("SELECT * FROM users WHERE userid=$userid");
$userinfo = $db->fetch_row($use);
if($userinfo['airlinehq'] == '0') {
die("You cannot buy fuel when you don't own a HQ<br />
    <a href='fuelcater.php?u=".$userinfo['userid']."' target='fuel' class='btn btn-info'>Back</a>");
}
$fuelcost = $set['fuelcost'];
$currentfuel = $userinfo['fuelstorage'];
$maxfuel = $userinfo['fuelstoragemax'];
$purchase = $maxfuel-$currentfuel;
?>
<br />
Current Price per litre of Fuel: <b>$<?php echo number_format($set['fuelcost'],2); ?></b><br />
You can buy a Max of <b><?php echo number_format($purchase); ?></b> litres of fuel.<br />
<form action="?a=buyfueldo" target="fuel" method="post">
<input type="hidden" name="userid" value="<?php echo $userid; ?>">
<table width="100%">
    <tr>
        <td width="75%">
            <script>
                function myFunction() {
                  var x = document.getElementById("fuel").value;
                  document.getElementById("result1").innerHTML = + x;
                  num1 = document.getElementById("fuelcost").value;
                  num2 = document.getElementById("fuel").value;
                  document.getElementById("result2").innerHTML = (Math.round(num1 * num2 * 100) / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            </script>
            <input type="hidden" id="fuelcost" value="<?php echo $set['fuelcost']; ?>" />
            <input type="range" value="1" min="1" max="<?php echo $purchase; ?>" class="form-range" id="fuel" name="fuel" oninput="myFunction()"><br />

            <b>Fuel: </b> <span id="result1">0</span> litres<br />
            <b>Cost: </b> $<span id="result2">0</span>

           </td>
        <td width="25%">
            </td>
    </tr>
</table>
<input type="submit" name="" value="Buy" class="btn btn-info">
</form>

<?php $datapoints = $set['fuelcosttrack']; ?>

<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script>
    dataPointsArray = '<?php echo $datapoints; ?>'.split(',');
    dataPoints = [];
    
    for (let i = 0; i < dataPointsArray.length; i++) {
        dataPoints.push({x: i, y: parseFloat(dataPointsArray[i])});
    }
    
    window.onload = () => {
        var chart = new CanvasJS.Chart("chartContainer", {
    	animationEnabled: true,
    	theme: "light2",
    	zoomEnabled: true,
    	title: {
    		text: "Jet Fuel Price"
    	},
    	axisY: {
    		title: "Price (Bucks)",
    		titleFontSize: 24,
    		prefix: "$"
    	},
    	axisX: {
    	    valueFormatString: "#,##",
    	    minimum: 0,
    	    maximum: dataPoints.length - 1,
    	    interval: Math.max(1, Math.round(dataPoints.length) / 10)
    	},
    	data: [{
    		type: "line",
    		yValueFormatString: "$#,##0.00",
    		xValueFormatString: "#,##",
    		dataPoints: dataPoints
    	}]
    });
    chart.render();
}
</script>
<br />
<br />
<?php 
}










function buyfueldo()
{
global $db, $userid,$set;

$userid = isset($_POST['userid']) ? $_POST['userid'] : 0;
$qty = isset($_POST['fuel']) ? $_POST['fuel'] :0;
$pla = $db->query("SELECT * FROM userairplanes WHERE planeOWNER=$userid");
$planeinfo = $db->fetch_row($pla);
$use = $db->query("SELECT * FROM users WHERE userid=$userid");
$userinfo = $db->fetch_row($use);
$fuelcost = $set['fuelcost'];
$total = $fuelcost*$qty;
$fuel = $userinfo['fuelstorage'];
$fuelmax = $userinfo['fuelstoragemax'];
$bucks = $userinfo['bucks'];
if($total > $bucks) {
die("
You don't have enough bucks to buy this.<br />
<a href='../pages/fuelcater.php?u=".$userid."' target='fuel' class='btn btn-info'>Continue</a>
"); 
}
$tfuel = $fuel+$qty;
if($tfuel > $fuelmax){
    die("You don't have enough storage for this.<br />
<a href='../pages/fuelcater.php?u=".$userid."' target='fuel' class='btn btn-info'>Continue</a>");
}
if($qty > $fuelmax) {
die("
You don't have enough storage for this.<br />
<a href='../pages/fuelcater.php?u=".$userid."' target='fuel' class='btn btn-info'>Continue</a>
");
} else { ?>

Fuel Cost: $<?php echo number_format($fuelcost,2); ?> per litre.<br />
You ordered <?php echo number_format($qty); ?> litres.<br />
<br />
Total cost: <?php echo number_format($total); ?><br />
<a href="../pages/fuelcater.php?u=<?php echo $userid; ?>" target="fuel" class="btn btn-info">Continue</a>

<?php
$db->query("UPDATE users SET fuelstorage=fuelstorage+$qty, bucks=bucks-$total WHERE userid=$userid");
$qtys = number_format($qty);
$db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$userid','out','$total','Fuel: <b>$qtys</b> Litres',unix_timestamp(),'bucks')");
} 
}