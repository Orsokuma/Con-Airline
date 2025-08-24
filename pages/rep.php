<?php include "../pages/dbconnect.php";



$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {

default:index(); break;
}






function index()
{
global $db, $set;
$userid = $_GET['u'];
$dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($dbbd);

$datapoints = $ir['reputationa']; ?>


<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
<script>
    dataPointsArray = '<?php echo $datapoints; ?>'.split(',');
    dataPoints = [];
    
    for (let i = 0; i < dataPointsArray.length; i++) {
        dataPoints.push({x: i, y: parseFloat(dataPointsArray[i])});
    }
    dataPoints.push({x: dataPointsArray.length, y: parseFloat(<?php echo $ir['reputation']; ?>)});
    
    window.onload = () => {
        var chart = new CanvasJS.Chart("chartContainer", {
    	animationEnabled: true,
    	theme: "light2",
    	zoomEnabled: true,
    	title: {
    		text: "Your Company Reputation"
    	},
    	axisY: {
    		title: "Reputation",
    		titleFontSize: 24,
    		prefix: ""
    	},
    	axisX: {
    	    valueFormatString: "#,#####",
    	    minimum: 0,
    	    maximum: dataPoints.length - 1,
    	    interval: Math.max(1, Math.round(dataPoints.length) / 10)
    	},
    	data: [{
    		type: "line",
    		yValueFormatString: "#,#####0.00000",
    		xValueFormatString: "#,#####",
    		dataPoints: dataPoints
    	}]
    });
    chart.render();
}
</script>



<?php

   
   
   
   
    
}






?>
