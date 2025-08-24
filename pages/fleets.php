<?php include "../pages/dbconnect.php";


$userid = isset($_GET['u']) ? $_GET['u'] : 0;    
$orderby = isset($_GET['ord']) ? $_GET['ord'] : planeCOST;  
$ads = isset($_GET['ads']) ? $_GET['ads'] : ASC;  
$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
case 'search': search(); break;
default:index(); break;
}



function search() {
    global $db,$ir,$c; 
    $userid = $_GET['u'];

    $_POST['airplanes'] = (isset($_POST['airplanes']) && is_string($_POST['airplanes'])) ? stripslashes($_POST['airplanes']) : '';
    if (!$_POST['airplanes']) {
        echo "You can't leave the search box empty";
    } else if (!preg_match("/^[a-z0-9_]+([\\s]{1}[a-z0-9_]|[a-z0-9_])+$/i",
        $_POST['airplanes']))
    {
        echo 'Airplanes can only consist of Numbers, Letters, underscores and spaces.';
    }   else if (((strlen($_POST['airplanes']) > 32) OR (strlen($_POST['airplanes']) < 2)))
    {
        echo 'Airplanes can only be a max of 32 characters or a min of 2 characters.';
    } else {
    $e_name_check = '%' . $db->escape($_POST['airplanes']) . '%';
    $q = $db->query("SELECT `planeID`, `planeMODEL`, `planeMAKE`, `planeIMAGE`, `planePASSENGER`, `planeFUEL`, `planeSPEED`, `planeDISTANCE`, `planeCOST`, `planeWEIGHT`, `planeCONSUMPTIONRATE`, `planeACTIVE`, `premiumcost` FROM `airplanes` WHERE `planeMAKE` LIKE ('{$e_name_check}')");
    echo '<h1>'.$db->num_rows($q) .' planes found. </h1>'; ?>
    <form action="?a=index&u=<?php echo $userid; ?>" method="POST">
        <button type="submit" value="Search" class="btn btn-info">Back</button>
    </form>
	<table width="70%" cellpadding="1" cellspacing="1" >
	<?php
        while($data=$db->fetch_row($q)) {
            $planeID = $data['planeID'];
        ?>

            <tr valign="middle">
                <td><img src="<?php echo $data['planeIMAGE']; ?>" width="175px" class="rounded"></td>
                <td><?php echo $data['planeMAKE']; ?><br /> <?php echo $data['planeMODEL']; ?></td>
                <td>Passengers: <?php echo number_format($data['planePASSENGER']); ?><br />
                    Fuel: <?php echo number_format($data['planeFUEL']); ?> L<br />
                    Speed: <?php echo number_format($data['planeSPEED']); ?> KM/H<br />
                    Distance: <?php echo number_format($data['planeDISTANCE']); ?> KM<br />
                    Max Capacity: <?php echo number_format($data['planeWEIGHT']); ?> KG<br />
                    Fuel Rate: <?php echo number_format($data['planeCONSUMPTIONRATE']); ?> L/H</td>
                <td><?php 
                    if ($data['premiumcost'] >= '1') {
                        echo '<font color="#33879D"><b>✈'.number_format($data['premiumcost']).'</b></font>'; 
                        $former = '<input type="hidden" name="premium" value="1">';
                    } else {
                        echo '<font color="#379e00"><b>'.money_formatter($data['planeCOST']).'</b></font>'; 
                        $former = '<input type="hidden" name="premium" value="0">';
                    } ?></td>
                <td>
                    <?php
                    $user = $db->query("SELECT * FROM users WHERE userid=$userid");
                    $par = $db->fetch_row($user);
                    if($par['airlinehq'] == '0') { ?>
                    <form>
                        <input type="submit" name="" value="Purchase" class="btn btn-info" disabled>
                    </form>
                    <?php } else { ?>
                    <form action="fleetsub.php" target="fleets" method="post">
                        <input type="hidden" name="planeID" value="<?php echo $data['planeID']; ?>">
                        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                        <?php echo $former; ?>
                        <input type="submit" name="" value="Purchase" class="btn btn-info">
                    </form>
                    <?php }?>
                    </td>
            </tr>
            <?php } ?>
  </table>
<br /><?php
}
}



function index() {
global $db,$userid,$orderby,$ads;

        $getinfo = $db->query("SELECT * FROM `airplanes` WHERE planeACTIVE='1' ORDER BY $orderby $ads");
        
        $query = $db->query("SELECT * FROM users WHERE userid=$userid");
        $ir = $db->fetch_row($query);
        $hq = $ir['airlinehq'];
        if ($hq == '0') {
            die("NOTE: You need to buy a HQ before you can purchase your First Aircraft");
        }

        $hqLevel = $ir['level'];
        $maxAircraftCount = ($hqLevel * 10) + 15;

        $airplanesQuery = $db->query("SELECT * FROM `userairplanes` WHERE planeOWNER=$userid");
        $currentAircraftCount = $db->num_rows($airplanesQuery);

        // Check if buying another aircraft is going to put the user over their limit
        if ($currentAircraftCount >= $maxAircraftCount) {
          die("NOTE: You need to upgrade your HQ to purchase more aircraft.");
        }
        
        
        $count = $db->num_rows($getinfo);
        
        ?>
        
        <ul class="nav bg-<?php echo $ir['theme']; ?> fixed-top">
            <body style="width: 100%; margin-top: 38px">
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>"><b>ORDER BY:</b></a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=planeCOST&u=<?php echo $userid; ?>" target="fleets">Cost</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=planePASSENGER&u=<?php echo $userid; ?>" target="fleets">Passengers</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=planeFUEL&u=<?php echo $userid; ?>" target="fleets">Fuel</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=planeSPEED&u=<?php echo $userid; ?>" target="fleets">Speed</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=planeDISTANCE&u=<?php echo $userid; ?>" target="fleets">Distance</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=planeWEIGHT&u=<?php echo $userid; ?>" target="fleets">Max Capacity</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=planeCONSUMPTIONRATE&u=<?php echo $userid; ?>" target="fleets">Fuel Rate</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>">|</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=<?php echo $orderby; ?>&ads=DESC&u=<?php echo $userid; ?>" target="fleets">Descending</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>" href="?a=index&ord=<?php echo $orderby; ?>&ads=ASC&u=<?php echo $userid; ?>" target="fleets">Ascending</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>">|</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>"><b>Current: </b> <?php echo $orderby; ?> ordered by <?php echo $ads; ?></a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>">|</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-<?php echo $ir['theme']; ?>"><b>Planes: </b> <?php echo $count; ?></a>
            </li>
         </ul>
        <form action="?a=search&u=<?php echo $userid; ?>" method="POST">
            
            <div class="input-group mb-3">
              <input type="text" name="airplanes" autocomplete="off" placeholder="Search for a Airplane Make" class="form-control">
              <button class="btn btn-success" value="Search" type="submit">Search</button>
            </div>

            </form>
        
        <table border="0" class="table">

        <?php
        while($data=$db->fetch_row($getinfo)) {
            $planeID = $data['planeID'];
        ?>

            <tr valign="middle">
                <td><img src="<?php echo $data['planeIMAGE']; ?>" width="175px" class="rounded"></td>
                <td><?php echo $data['planeMAKE']; ?><br /> <?php echo $data['planeMODEL']; ?></td>
                <td>Passengers: <?php echo number_format($data['planePASSENGER']); ?><br />
                    Fuel: <?php echo number_format($data['planeFUEL']); ?> L<br />
                    Speed: <?php echo number_format($data['planeSPEED']); ?> KM/H<br />
                    Distance: <?php echo number_format($data['planeDISTANCE']); ?> KM<br />
                    Max Capacity: <?php echo number_format($data['planeWEIGHT']); ?> KG<br />
                    Fuel Rate: <?php echo number_format($data['planeCONSUMPTIONRATE']); ?> L/H</td>
                <td><?php 
                
                if ($data['premiumcost'] >= '1') {
                    echo '<font color="#33879D"><b>✈'.number_format($data['premiumcost']).'</b></font>'; 
                    $former = '<input type="hidden" name="premium" value="1">';
                } else {
                    echo '<font color="#379e00"><b>'.money_formatter($data['planeCOST']).'</b></font>'; 
                    $former = '<input type="hidden" name="premium" value="0">';
                }
                
                
                ?></td>
                <td>
                    
                    <?php
                    $user = $db->query("SELECT * FROM users WHERE userid=$userid");
                    $par = $db->fetch_row($user);
                    if($par['airlinehq'] == '0') { ?>
                    <form>
                        <input type="submit" name="" value="Purchase" class="btn btn-info" disabled>
                    </form>
                    <?php } else { ?>
                    <form action="fleetsub.php" target="fleets" method="post">
                        <input type="hidden" name="planeID" value="<?php echo $data['planeID']; ?>">
                        <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                        <?php echo $former; ?>
                        <input type="submit" name="" value="Purchase" class="btn btn-info">
                    </form>
                    <?php }?>
                    
                    
                    
                    
                    
                    </td>
            </tr>
            
    <?php  }  ?>    
            <tr>
                <td colspan="11"></td>
            </tr>

        </table>

      </div>
<?php 
}