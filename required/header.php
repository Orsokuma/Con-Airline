<!--script type="text/javascript" src="/maps/markers.min.js"></script-->
<?php

class headers
{

    function startheaders()
    {
        global $ir, $set, $db; 
        
        $backpage = '<br /><button onclick="history.go(-1);">Back </button><br />';
        
        
        ?><script>var markers = [<?php
            $airports = $db->query("SELECT * FROM airports");
            $firstrow = true;

            while ($a = $db->fetch_row($airports)) {
                if (!$firstrow){
                    echo ",";
                } else {
                    $firstrow = false;
                }

                echo "{\"name\" : \"".$a["name"]."\", \"city\" : \"".$a["city"]."\", \"iata_faa\" : \"".$a["iata_faa"]."\",
                    \"icao\" : \"".$a["icao"]."\", \"lat\" : ".$a["lat"].", \"lng\" : ".$a["lng"].", \"alt\" : ".$a["alt"].",
                    \"tz\" : \"".$a["tz"]."\", \"airportpop\" : ".$a["airportpop"].", \"citypop\" : ".$a["citypop"]."}";
            }
        ?>]</script><?php
    ?>
<!DOCTYPE html>
<html lang="en">
<html style="height: 100%; width: 100%;" lang="en">
  <head>

    <link rel="icon" href="images/favicon.ico" type="image/x-icon"/>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <link rel="mask-icon" href="images/safari-pinned-tab.svg" color="#5bbad5">
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="required/src/window-engine.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.css" integrity="sha384-pmjIAcz2bAn0xukfxADbZIb3t8oRT9Sv0rvO+BR5Csr6Dhqq+nZs59P0pPKQJkEV" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.Default.css" integrity="sha384-wgw+aLYNQ7dlhK47ZPK7FRACiq7ROZwgFNg0m04avm4CaXS+Z9Y7nMu8yNjBKYC+" crossorigin="anonymous" />
    <script src="https://unpkg.com/jquery@3.6.0/dist/jquery.min.js" integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous" ></script>
    <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.0/dist/leaflet.markercluster.js" integrity="sha384-89yDmbSkL9reFc77m10ZbqLaUMf1sp7FAJs2oAD/rczNnY7I+17yN9KML6tpYpCk" crossorigin="anonymous"></script>

    <!-- Shizzle for Satelite -->
	<script src="../satilite/leaflet/Permalink.js"></script>
	<script src="../satilite/leaflet/Permalink.Layer.js"></script>
	<script src="../satilite/leaflet/Permalink.Overlay.js"></script>
	<script src="../satilite/leaflet-openweathermap.js"></script>
	<script src="../satilite/leaflet/leaflet-languageselector.js"></script>
	<script src="../satilite/files/map_i18n.js"></script>
	
	<script src="../leafletTextpath/leaflet.textpath.js"></script>
	

<style>
* {
  cursor: crosshair;
}

a {
  cursor: pointer;
}



    .fontface {font: 18px/27px 'monospace', Arial, sans-serif;}
    .bucks { color:#379e00; font-weight: bold; }
    .airbucks { color:#33879D; font-weight: bold; }
    .modal {
      --bs-modal-zindex: 1055;
      --bs-modal-width: 50%;
      --bs-model-height: 100%;
      --bs-modal-padding: 1rem;
      --bs-modal-margin: 0.5rem;
      --bs-modal-color: ;
      --bs-modal-bg: #fff;
      --bs-modal-border-color: var(--bs-border-color-translucent);
      --bs-modal-border-width: 1px;
      --bs-modal-border-radius: 0.5rem;
      --bs-modal-box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      --bs-modal-inner-border-radius: calc(0.5rem - 1px);
      --bs-modal-header-padding-x: 1rem;
      --bs-modal-header-padding-y: 1rem;
      --bs-modal-header-padding: 1rem 1rem;
      --bs-modal-header-border-color: var(--bs-border-color);
      --bs-modal-header-border-width: 1px;
      --bs-modal-title-line-height: 1.5;
      --bs-modal-footer-gap: 0.5rem;
      --bs-modal-footer-bg: ;
      --bs-modal-footer-border-color: var(--bs-border-color);
      --bs-modal-footer-border-width: 1px;
      position: fixed;
      top: 0;
      left: 0;
      z-index: var(--bs-modal-zindex);
      display: none;
      width: 100%;
      height: 100%;
      overflow-x: hidden;
      overflow-y: auto;
      outline: 0;
    }
    .leaflet-top,
    .leaflet-bottom {
      position: absolute;
      z-index: 499; /* was 1000 */
      pointer-events: none;
    }
</style>
</head>

<body>
    
<input type="hidden" name="latitude" id="hqTempLatInput" placeholder="latitude" value="2">
<input type="hidden" name="longitude" id="hqTempLngInput" placeholder="longitude" value="2">
<input type="hidden" name="tlatitude" id="hqTempLatInput" placeholder="latitude" value="2">
<input type="hidden" name="tlongitude" id="hqTempLngInput" placeholder="longitude" value="2">
<?php }
    function userdata($ir, $fm, $cm, $dosessh = 1) {
        global $db, $c, $userid, $set;
        $time = time();
        $db->query("UPDATE users SET laston = '$time' WHERE `userid` = $userid");
        $data = $db->query("SELECT * FROM users WHERE userid=$userid");
        $ir = $db->fetch_row($data);
        $theme = $ir['theme'];
        if($theme == 'light' OR $theme == 'info' OR $theme == 'warning') {
            $icons = 'dark';
            $closebtn = 'black';
        } else {
            $icons = 'light';
            $closebtn = 'white';
        }
        $allanceid = $ir['alliance'];
        if($allanceid >= '1') { 
            $getalliance = $db->query("SELECT * FROM alliance WHERE allianceID=$allanceid");
            $all = $db->fetch_row($getalliance);
            $alliance = '<hr /><a class="btn btn-'.$theme.' col-12" id="button25">Your Community - '.$all['allianceNAME'].'</a>'; 
        } else { 
            $alliance = ''; 
        } 
    
        $cnt = $db->query("SELECT * FROM userairplanes WHERE planeACTIVE=0 AND planeOWNER=$userid");
        $ready = $db->num_rows($cnt);
        
        $event = $set['saleevent'];
        ?>

<span style="display: none" id="gameversion"><?php echo $set['gamename']." - v".$set['version']; ?></span>

<title><?php echo $ir['airlinename']; ?></title>

<div class="offcanvas offcanvas-start text-bg-<?php echo $theme; ?>" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel" style="margin-top: 47px;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasScrollingLabel"><?php echo $set['gamename']; ?></h5>
    <button type="button" class="btn-close btn-close-<?php echo $closebtn; ?>" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button9">Buy Aircraft</a><br />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button18">Maintenance</a><br />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button10">Fuel & Catering</a><br />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button20">Employee Management*</a><br />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button11">Headquarters</a><br />
            <hr />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button17">Training Center</a><br />
            <hr />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button19">Marketing*</a><br />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button16">Banking</a><br />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button24">Communities</a><br />
            <a class="btn btn-<?php echo $theme; ?> col-12" id="button4">User List</a><br />
            <?php echo $alliance; ?>
  </div>
</div>

<div class="offcanvas offcanvas-end text-bg-<?php echo $theme; ?>" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling1" aria-labelledby="offcanvasScrollingLabel1" style="margin-top: 47px;">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasScrollingLabel1">Settings</h5>
    <button type="button" class="btn-close btn-close-<?php echo $closebtn; ?>" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <a class="btn btn-<?php echo $theme; ?> col-12" id="button15">Company & User Settings</a><br />
    <a class="btn btn-<?php echo $theme; ?> col-12" id="button28">Your Stats</a><br />
    <a class="btn btn-<?php echo $theme; ?> col-12" id="button12">Staff List</a><br />
    <a class="btn btn-<?php echo $theme; ?> col-12" id="button13">Acknowledgements</a><br />
    <a class="btn btn-<?php echo $theme; ?> col-12" id="button8">Report an issue</a><br />
    <a class="btn btn-<?php echo $theme; ?> col-12" id="button27">Changelog</a><br />
    <a class="btn btn-<?php echo $theme; ?> col-12" id="button14">Help</a><br />
    <?php if($ir['staff'] >= '1') { echo '<a class="btn btn-'.$theme.' col-12" id="button3">Staff Panel</a>'; } else { echo ''; } ?>
    <a class="btn btn-<?php echo $theme; ?> col-12" href="logout.php">Logout</a>
  </div>
</div>


<nav class="navbar navbar-expand-sm bg-<?php echo $theme; ?> navbar-<?php echo $theme; ?> fixed-top py-0" style="font-size:smaller;">
<div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
<div class="collapse navbar-collapse" id="mynavbar">
  <ul class="navbar-nav me-auto">
    <li class="nav-item">
      <button type="button" class="btn btn-<?php echo $theme; ?>" id="button5">
        <font color="<?php echo $ir['airlinecolour']; ?>"><b><?php echo $ir['airlinename']; ?></b></font>
      </button>
    </li>

    
    <li class="nav-item">
      <button type="button" class="btn btn-<?php echo $theme; ?>" id="button1"><b><div id="load_updatesb"> </div></b></button>
    </li>
    <li class="nav-item">
      <button type="button" class="btn btn-<?php echo $theme; ?>" id="button6"><b><div id="load_updatesab"> </div></b></button>
    </li>
    <li class="nav-item">
      <button type="button" class="btn btn-<?php echo $theme; ?>" id="button7"><b>Fleet List <span class="badge bg-success"><div id="load_updatesav"> </div></span></b></button>
    </li>
    <li class="nav-item">
      <button type="button" class="btn btn-<?php echo $theme; ?>" id="button22"><b>Reputation <span class="badge bg-success"><div id="load_updaterep"> </div></span></b></button>
    </li>

    <script type="text/javascript">
      function loadAndUpdate(id, url) {
        $('#' + id).load(url + '?u=<?php echo $ir['userid']; ?>');
        setInterval(function () {
          $('#' + id).load(url + '?u=<?php echo $ir['userid']; ?>');
        }, 2000);
      }
    
      loadAndUpdate('load_updatesb', 'required/livebucks.php');
      loadAndUpdate('load_updatesab', 'required/liveairbucks.php');
      loadAndUpdate('load_updatesav', 'required/liveairavail.php');
      loadAndUpdate('load_updaterep', 'required/liverep.php');
      loadAndUpdate('load_updatemail', 'required/livemail.php');
    </script>



        <li clsss="nav-item">
            <button class="btn btn-<?php echo $theme; ?>" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"><b>Management</b></button>
        </li>
        <li class="nav-item">
            <button onClick="window.location.reload(true);" class="btn btn-<?php echo $theme; ?>" data-bs-toggle="tooltip" title="Reload Entire Page!"><img src="images/refresh-icon-<?php echo $icons; ?>.png" width="17"></button>
        </li>
    </ul>
        
        
    <?php $sales = $set['sale']; if ($sales == '1') { ?>    

        <button type="button" class="btn btn-<?php echo $theme; ?>" id="button26"><b style="color: red">🎁 <?php echo $event; ?> Sale <?php echo $set['saleperc']; ?>% Off (<span id="SaleCountdown"></span> left)</b></button>
        <script>
            function firstDiscount() {
                endtime =  (<?php echo $set['saleend']; ?> * 1000) - new Date().getTime();
                if (endtime <= 0) {
                    clearInterval(x);
                    if (document.getElementById("button26") !== null) {
                        document.getElementById("button26").remove();
                    }
                    return;
                }
                days = Math.floor((endtime % (1000 * 60 * 60 * 60 * 24)) / (1000 * 60 * 60 * 24));
                hours = Math.floor((endtime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                minutes = Math.floor((endtime % (1000 * 60 * 60)) / (1000 * 60));
            
                document.getElementById("SaleCountdown").innerHTML = days + "d " + hours + "h " + minutes + "m ";
            }
            firstDiscount();
            var x = setInterval(firstDiscount, 1000);
        </script>
        <?php } else { echo ' '; } ?>

        <button type="button" class="btn btn-<?php echo $theme; ?>" id="button21"><b style="color: red">🎁 25% Sale (<span id="newUserCountdown"></span> left)</b></button>
        <script>
            function firstDiscount() {
                endtime =  ((<?php echo $ir['joineddate']; ?> + 86400) * 1000) - new Date().getTime();
                if (endtime <= 0) {
                    clearInterval(x);
                    if (document.getElementById("button21") !== null) {
                        document.getElementById("button21").remove();
                    }
                    return;
                }
                hours = Math.floor((endtime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                minutes = Math.floor((endtime % (1000 * 60 * 60)) / (1000 * 60));
                document.getElementById("newUserCountdown").innerHTML = hours + "h " + minutes + "m ";
            }
            firstDiscount();
            var x = setInterval(firstDiscount, 1000);
        </script>
        
        
        
        <?php if ($ir['box'] == '1') { echo ''; } else { ?>
            <button type="button" class="btn btn-<?php echo $theme; ?>" id="button23"><b>Daily Reward</b></button>
        <?php } ?>
        

        
        
        <?php if ($ir['fav'] == 'XXXX') { echo ''; } else { ?>
        <form id="airportFavSearch" autocomplete="off">
            <div class="input-group mb-3-sm">
                <input type="hidden" class="form-control" name="airportFavSearchbox" id="airportFavSearchbox" value="<?php echo $ir['fav']; ?>">
                <input type="submit" name="airportFavSearchButton" value="Favourite" class="btn btn-<?php echo $theme; ?>" style="height: 35px;top: 5px; font-weight: bold;">
            </div>
        </form>
        <?php } ?>
        
        <form id="airportSearch" onsubmit="return false" autocomplete="off">
            <div class="input-group mb-3-sm">
                <input type="text" class="form-control" name="airportSearchbox" id="airportSearchbox" placeholder="Airport Name/ICAO" style="height: 27px; font-size: 12;top: 8px;">
            </div>
        </form>
        
        <button type="button" class="btn btn-<?php echo $theme; ?>" id="button2"><b>💬 Chat</b></button>
        <button type="button" class="btn btn-<?php echo $theme; ?>" id="button29"><b>Mailbox</b><span class="badge bg-success"><div id="load_updatemail"> </div></span></button>
        
        <span id='ct7' class="btn btn-<?php echo $theme; ?>"></span>


        <script>
          function updateTime() {
              const now = new Date();
              const timeString = now.toLocaleString("en-UK", {timeZone: "Europe/London", timeStyle: "medium"});
              document.getElementById("ct7").innerHTML = "<b>" + timeString + "</b>";
              // Schedule the next update on the browser's next repaint cycle
              requestAnimationFrame(updateTime);
            }
            updateTime(); // initial call to avoid delay on first load
        </script>

                
        <button class="btn btn-<?php echo $theme; ?>" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling1" aria-controls="offcanvasScrolling1"><b><?php echo $ir['username']; ?> <img src="<?php echo $ir['profileimage']; ?>" class="rounded" width="30px" height="30px"> </b></button>


    </div>
  </div>
  
</nav>




<body style="width: 100%; height: 95%; margin-top: 44px">

<div id="map" style="width: 100%; height: 100%; border: 1px solid #aaa; margin-left: 0px"></div>
<script type="text/javascript" src="maps/leaf-demo.js"></script>
<script src="satilite/files/map.js"></script>
<script>
    var allowMarkerMovement = false;
    var allowTrainingMarkerMovement = false;
    
    var hqMarker = null;
    var thqMarker = null;
    
	initMap();
</script>

<div class="windowGroup">



<?php 
    $windows = array(
        array(
            "id" => "window1",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Your Finances",
            "iframe_src" => "../pages/finances.php?u=$userid",
			"framename" => "finance",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window2",
            "class" => "window fade",
            "colour" => "green",
            "title" => "Chat",
            "iframe_src" => "../pages/chat.php?u=$userid",
			"framename" => "chat",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window3",
            "class" => "window fade",
            "colour" => "crimson",
            "title" => "Staff Panel",
            "iframe_src" => "../pages/staffpanel.php?u=$userid",
			"framename" => "staffpanel",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window4",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Userlist",
            "iframe_src" => "../pages/userlist.php",
			"framename" => "userlist",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window5",
            "class" => "window fade",
            "colour" => "blue",
            "title" => "Company for {$ir['username']} - ID:{$ir['userid']}",
            "iframe_src" => "../pages/profile.php?u={$ir['userid']}",
			"framename" => "yourcompany",
			"width" => "100%",
			"height" => "85%"
        ),
        array(
            "id" => "window6",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Air Bucks",
            "iframe_src" => "../pages/airbucks.php?u={$ir['userid']}",
			"framename" => "airbucks",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window7",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Fleet List",
            "iframe_src" => "../pages/fleetmanage.php?u={$ir['userid']}",
			"framename" => "fleetmanage",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window8",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Bug Report",
            "iframe_src" => "../pages/bugreport.php?u={$ir['userid']}",
			"framename" => "bugreport",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window9",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Buy Aircraft",
            "iframe_src" => "../pages/fleets.php?u={$ir['userid']}",
			"framename" => "fleets",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window10",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Fuel & Catering Management",
            "iframe_src" => "../pages/fuelcater.php?u={$ir['userid']}",
			"framename" => "fuel",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window11",
            "class" => "window small",
            "colour" => "blue",
            "title" => "Property Management",
            "iframe_src" => "../pages/property.php?u={$ir['userid']}",
			"framename" => "properties",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window12",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Staff List",
            "iframe_src" => "../pages/stafflist.php",
			"framename" => "stafflist",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window13",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Acknowledgements",
            "iframe_src" => "../pages/acknowledge.php",
			"framename" => "acknowledge",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window14",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Help",
            "iframe_src" => "../pages/help/help.php?u={$ir['userid']}",
			"framename" => "help",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window15",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Settings",
            "iframe_src" => "../pages/settings.php?u={$ir['userid']}",
			"framename" => "settings",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window16",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Banking",
            "iframe_src" => "../pages/banking.php?u={$ir['userid']}",
			"framename" => "banking",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window17",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Training Center",
            "iframe_src" => "../pages/trainingcenter.php?u={$ir['userid']}",
			"framename" => "training",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window18",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Maintenance",
            "iframe_src" => "../pages/maintenance.php?u={$ir['userid']}",
			"framename" => "maintenance",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window19",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Marketing",
            "iframe_src" => "../pages/marketing.php?u={$ir['userid']}",
			"framename" => "marketing",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window20",
            "class" => "window large",
            "colour" => "blue",
            "title" => "Employee Manage",
            "iframe_src" => "../pages/employeemanage.php?u={$ir['userid']}",
			"framename" => "employeemanage",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window21",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Air Bucks",
            "iframe_src" => "../pages/airbucks.php?u={$ir['userid']}",
			"framename" => "airbucks",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window22",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Company Reputation",
            "iframe_src" => "../pages/rep.php?u={$ir['userid']}",
			"framename" => "rep",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window23",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Daily Reward",
            "iframe_src" => "../pages/daily.php?u={$ir['userid']}",
			"framename" => "daily",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window24",
            "class" => "window finance",
            "colour" => "crimson",
            "title" => "Communites",
            "iframe_src" => "../pages/alliancelist.php?u={$ir['userid']}",
			"framename" => "alliance",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window25",
            "class" => "window finance",
            "colour" => "crimson",
            "title" => "Community",
            "iframe_src" => "../pages/alliance.php?u={$ir['userid']}",
			"framename" => "ualliance",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window26",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Air Bucks",
            "iframe_src" => "../pages/airbucks.php?u={$ir['userid']}",
			"framename" => "airbucks",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window27",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Changelog",
            "iframe_src" => "../pages/changelog.php?u={$ir['userid']}",
			"framename" => "changelog",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window28",
            "class" => "window finance",
            "colour" => "crimson",
            "title" => "Your Stats",
            "iframe_src" => "../pages/stats.php?u={$ir['userid']}",
			"framename" => "stats",
			"width" => "100%",
			"height" => "90%"
        ),
        array(
            "id" => "window29",
            "class" => "window finance",
            "colour" => "blue",
            "title" => "Mailbox",
            "iframe_src" => "../pages/mailbox.php?u={$ir['userid']}",
			"framename" => "mailbox",
			"width" => "100%",
			"height" => "90%"
        )
    );
    ?>
	
	<?php foreach ($windows as $window) { ?>
    <div id="<?php echo $window['id']; ?>" class="<?php echo $window['class']; ?>">
        <div class="<?php echo $window['colour']; ?>">
            <p class="windowTitle"><?php echo $window['title']; ?></p>
        </div>
        <div class="mainWindow">
            <iframe name="<?php echo $window['framename']; ?>" id="<?php echo $window['framename']; ?>" src="<?php echo $window['iframe_src']; ?>" width="<?php echo $window['width']; ?>" height="<?php echo $window['height']; ?>" border="0" cellpadding="0" srolling="0"></iframe>
        </div>
    </div>
    <?php } ?>

</div> 




<?php 
    $reloader = array(
        array(
            "id" => "1",
			"framename" => "finance"
        ),
        array(
            "id" => "2",
            "framename" => "chat"
        ),
        array(
            "id" => "3",
            "framename" => "staffpanel"
        ),
        array(
            "id" => "4",
            "framename" => "userlist"
        ),
        array(
            "id" => "5",
            "framename" => "yourcompany"
        ),
        array(
            "id" => "6",
            "framename" => "airbucks"
        ),
        array(
            "id" => "7",
            "framename" => "fleetmanage"
        ),
        array(
            "id" => "8",
            "framename" => "fleets"
        ),
        array(
            "id" => "9",
            "framename" => "fuel"
        ),
        array(
            "id" => "10",
            "framename" => "properties"
        ),
        array(
            "id" => "11",
            "framename" => "stafflist"
        ),
        array(
            "id" => "12",
            "framename" => "acknowledge"
        ),
        array(
            "id" => "13",
            "framename" => "help"
        ),
        array(
            "id" => "14",
            "framename" => "settings"
        ),
        array(
            "id" => "15",
            "framename" => "banking"
        ),
        array(
            "id" => "16",
            "framename" => "training"
        ),
        array(
            "id" => "17",
            "framename" => "maintenance"
        ),
        array(
            "id" => "18",
            "framename" => "marketing"
        ),
        array(
            "id" => "19",
            "framename" => "ticketprices"
        ),
        array(
            "id" => "20",
            "framename" => "employeemanage"
        ),
        array(
            "id" => "22",
            "framename" => "rep"
        ),
        array(
            "id" => "23",
            "framename" => "daily"
        ),
        array(
            "id" => "27",
            "framename" => "changelog"
        ),
        array(
            "id" => "28",
            "framename" => "stats"
        ),
        array(
            "id" => "29",
            "framename" => "mailbox"
        )
    );
?>
<?php foreach ($reloader as $reload) { ?>
    <script>
      document.getElementById("button<?php echo $reload['id']; ?>").addEventListener("mouseover", function() {
        document.getElementById("<?php echo $reload['framename']; ?>").contentWindow.location.reload();
      });
    </script>
<?php } ?>

	

</div>
<div class="windowGroupExcl">	
    <div id="windowExcl15" class="window airport">
        <div class="blue">
    		<p class="windowTitle">Property Management</p>
    	</div>
    	<div class="mainWindow">
    		<iframe name="airplane" src="../pages/airport.php?u=<?php echo $ir['userid']; ?>&lat=0&lng=0" width="100%" height="700px" border="0" cellpadding="0" srolling="0"></iframe>
    	</div>
    </div>
</div>
	

		

<script type="text/javascript" src="required/src/window-engine.js"></script> 
<script>
        function getAirportByICAONew(icao) {
            for (let item of markers) {
                if (item.icao == icao) return item;
            }
            return null;
        }
        
        var recentAirportWindowByICAO = "";
        
        function createAirportWindowExcl(recentICAO) {
            airport = getAirportByICAONew(recentICAO);
            document.getElementById("windowExcl15").innerHTML = '<div class="blue">' +
    			'<p class="windowTitle">Airport</p>' +
    		'</div>' +
    		'<div class="mainWindow">' +
    			'<iframe name="airplane" src="../pages/airport.php?u=' + <?php echo $ir['userid']; ?> + '&lat=' + airport.lat + '&lng=' + airport.lng + '" width="100%" height="700px" border="0" cellpadding="0" srolling="0"></iframe>' +
    		'</div>';
            let windowID = document.getElementById("windowExcl15");
            let headerID = windowID.firstElementChild;
            headerID.id = "windowExcl15header";
        
            let createCloseButton = document.createElement("b");
            createCloseButton.id = "closeExclButton15";
            createCloseButton.innerHTML = "x";
            createCloseButton.style = "color: white; cursor: pointer; position: relative; bottom: 9px; font-size: 24px;";
            document.getElementById("windowExcl15header").appendChild(createCloseButton);
        
            document.getElementById("closeExclButton15").onclick = function () {
                fadeOut(windowID);
            };
            document.getElementById("buttonExcl15").onclick = function () {
                localStorage.setItem('selectedAirportICAO', ' + markers[i].icao + ');
                if (windowID.style.display === "initial") {
        			activeWindow(windowID);
                } else {
                    windowID.style = "position: absolute;";
                    windowID.style = "top: 80px;";
                    fadeIn(windowID);
                }
            };
            dragElement(windowID);
            recentAirportWindowByICAO = recentICAO;
        }
</script>





<script>
            var aircraftMarkers = [];
            var stopAnimations = false;
            
            function vectorFromLatLong(startlat, startlng, endlat, endlng) {
                return [(endlat - startlat) / 180, (endlng - startlng) / 180];
            }
            
            function updateAirplaneAnimation() {
                for (let i = aircraftMarkers.length - 1; i >= 0; i--) {
                    dLat = aircraftMarkers[i][1][0] * (aircraftMarkers[i][2] / 28691.111);
                    dLng = aircraftMarkers[i][1][1] * (aircraftMarkers[i][2] / 28691.111);
                    aircraftMarkers[i][0].setLatLng([aircraftMarkers[i][0].getLatLng().lat + dLat, aircraftMarkers[i][0].getLatLng().lng + dLng]);
                    
                    if (aircraftMarkers[i][4] <= ((new Date().getTime()) / 1000)) {
                        map.removeLayer(aircraftMarkers[i][0]);
                        map.removeLayer(aircraftMarkers[i][3]);
                        aircraftMarkers.splice(i, 1);
                        
                        break;
                    }
                }
            }
            
            function updateAirplaneAnimationNoCaller(i) {
                dLat = aircraftMarkers[i][1][0] * (aircraftMarkers[i][2] / 28691.111);
                dLng = aircraftMarkers[i][1][1] * (aircraftMarkers[i][2] / 28691.111);
                
                aircraftMarkers[i][0].setLatLng([aircraftMarkers[i][0].getLatLng().lat + dLat, aircraftMarkers[i][0].getLatLng().lng + dLng]);
            }
            
            function updateAirplaneAnimationCaller() {
                setInterval(updateAirplaneAnimation, 1000);
            }
        </script>


<?php
global $db, $userid, $set; 




// GET OTHER PLAYERS AND DISPLAY THEM ON SAME MAP.
$alliance = $ir['alliance'];
$alliancedata = $db->query("SELECT * FROM users WHERE alliance=$alliance AND $alliance>=1 AND userid != $userid");
while ($rdat = $db->fetch_row($alliancedata)) {
    $usersi = $rdat['userid'];
    $hata2 = $db->query("SELECT * FROM activeflights WHERE planeOWNER=$usersi");
        if ($rdat['airlinetraininghq'] == '1') { ?>
            <script>
                    var myHQIcon = L.icon({
                        iconUrl: '<?php echo $rdat['airlineimage'] ;?>',
                        iconRetinaUrl: '<?php echo $rdat['airlineimage'] ;?>',
                        iconSize: [40, 40],
                        iconAnchor: [20, 20],
                        popupAnchor: [0, -20],
                    });
                    var hqPopup = "<?php echo $rdat['airlinename']; ?> Training Center "+
                    "<br /><b>Owner</b>: <?php echo $rdat['username'].' <small>(ID:'.$rdat['userid'].')</small>'; ?>" +
                    "<br /><b>Latitude: </b><?php echo $rdat['latitude'];?>" +
                    "<br /><b>Longitude: </b><?php echo $rdat['longitude'];?>" +
                    "";
                    hqMarker = L.marker([<?php echo $rdat['latitude'];?>, <?php echo $rdat['longitude'];?>], {icon: myHQIcon}).bindPopup(hqPopup);
                    map.addLayer(hqMarker);
            </script>
        <?php }
        if ($rdat['airlinehq'] == '1') { ?>
            <script>
                    var myHQIcon = L.icon({
                        iconUrl: '<?php echo $rdat['airlineimage'] ;?>',
                        iconRetinaUrl: '<?php echo $rdat['airlineimage'] ;?>',
                        iconSize: [40, 40],
                        iconAnchor: [20, 20],
                        popupAnchor: [0, -20],
                    });
                    var hqPopup = "<?php echo $rdat['airlinename']; ?> HQ "+
                    "<br /><b>Owner</b>: <?php echo $rdat['username'].' <small>(ID:'.$rdat['userid'].')</small>'; ?>" +
                    "<br /><b>Latitude: </b><?php echo $rdat['latitude'];?>" +
                    "<br /><b>Longitude: </b><?php echo $rdat['longitude'];?>" +
            		"";
                    hqMarker = L.marker([<?php echo $rdat['latitude'];?>, <?php echo $rdat['longitude'];?>], {icon: myHQIcon}).bindPopup(hqPopup);
                    map.addLayer(hqMarker);
            </script>
        <?php }
        $i = 0;
        while ($activeRow = $db->fetch_row($hata2)) {
            $data3 = $db->query("SELECT * FROM userairplanes WHERE id=".$activeRow['planeID']);
            $airplane = $db->fetch_row($data3);
            $data4 = $db->query("SELECT * FROM airplanes WHERE planeID=".$airplane['planeID']);
            $airplaneData = $db->fetch_row($data4);
            if ($airplane['planeUname'] == 'Not Set') {
                $flightname = $airplaneData['planeMAKE'];
            } else {
                $flightname = $airplane['planeUname'];
            }
            $flightendt = $activeRow['flightEndTime']+3600;
            $flightend = date("h:i A",$flightendt);
            echo "<script>
                    myAnimationIcon = L.icon({
                    iconUrl: '".$airplaneData['planeIMAGE']."',
                    iconRetinaUrl: '".$airplaneData['planeIMAGE']."',
                    iconSize: [50, 50],
                    iconAnchor: [20, 20],
                });
                
                speed = ".$airplane['planeSPEED']." / 3.6;
                endtime = ".$activeRow['flightEndTime'].";
                startLat = ".$activeRow['startLat'].";
                startLng = ".$activeRow['startLon'].";
                endLat = ".$activeRow['endLat'].";
                endLng = ".$activeRow['endLon'].";
                
                line = L.polyline([[startLat, startLng], [endLat, endLng]], {color: '".$rdat['airlinecolour']."'}).addTo(map);
                line.setText('           \u27A4 ".$flightname." (ETA: ".$flightend.") \u27A4', {
                    repeat: true,
                    offset: 12,
                    attributes: {fill: 'black'}});
                

                aircraftMarkers.push([L.marker([startLat, startLng], {icon: myAnimationIcon,}).addTo(map), vectorFromLatLong(startLat, startLng, endLat, endLng), speed, line, endtime, ".$activeRow['planeID']."]);

                for (let i = 0; i < ".(time() - $activeRow['flightStartTime'])."; i++) {
                    updateAirplaneAnimationNoCaller(".$i.");
                    
                    if (endtime <= ((new Date().getTime())/1000)) {
                        map.removeLayer(aircraftMarkers[".$i."][0]);
                        map.removeLayer(aircraftMarkers[".$i."][3]);
                        aircraftMarkers.splice(".$i.", 1);
                        break;
                    }
                }
            </script>";
            $i++;
        }
        
}













// DISPLAYS OWN MAP
$data2 = $db->query("SELECT * FROM activeflights WHERE planeOWNER=$userid");
if ($ir['airlinetraininghq'] == '1') { ?>
            <script>
                    var myTHQIcon = L.icon({
                        iconUrl: 'images/traininghq.png',
                        iconRetinaUrl: 'images/traininghq.png',
                        iconSize: [25, 25],
                        iconAnchor: [20, 20],
                        popupAnchor: [0, -20],
                    });
                    var hqPopup = "<?php echo $ir['airlinename']; ?> Training Center"+
                    "<br /><b>Latitude: </b><?php echo $ir['tlatitude'];?>" +
                    "<br /><b>Longitude: </b><?php echo $ir['tlongitude'];?>" +
                    "";
                    hqMarker = L.marker([<?php echo $ir['tlatitude'];?>, <?php echo $ir['tlongitude'];?>], {icon: myTHQIcon}).bindPopup(hqPopup);
                    map.addLayer(hqMarker);
            </script>
        <?php }


if ($ir['airlinehq'] == '1') { ?>
            <script>
                    var myHQIcon = L.icon({
                        iconUrl: '<?php echo $ir['airlineimage'] ;?>',
                        iconRetinaUrl: '<?php echo $ir['airlineimage'] ;?>',
                        iconSize: [40, 40],
                        iconAnchor: [20, 20],
                        popupAnchor: [0, -20],
                    });
                    var hqPopup = "<?php echo $ir['airlinename']; ?> HQ "+
                    "<br /><b>Latitude: </b><?php echo $ir['latitude'];?>" +
                    "<br /><b>Longitude: </b><?php echo $ir['longitude'];?>" +
                    "";
                    hqMarker = L.marker([<?php echo $ir['latitude'];?>, <?php echo $ir['longitude'];?>], {icon: myHQIcon}).bindPopup(hqPopup);
                    map.addLayer(hqMarker);
            </script>
        <?php }
        $i = 0;
        while ($activeRow = $db->fetch_row($data2)) {
            $data3 = $db->query("SELECT * FROM userairplanes WHERE id=".$activeRow['planeID']);
            $airplane = $db->fetch_row($data3);
            $data4 = $db->query("SELECT * FROM airplanes WHERE planeID=".$airplane['planeID']);
            $airplaneData = $db->fetch_row($data4);
            if ($airplane['planeUname'] == 'Not Set') {
                $flightname = $airplaneData['planeMAKE'];
            } else {
                $flightname = $airplane['planeUname'];
            }
            $flightendt = $activeRow['flightEndTime']+3600;
            $flightend = date("h:i A",$flightendt);
            echo "<script>
                    myAnimationIcon = L.icon({
                    iconUrl: '".$airplaneData['planeIMAGE']."',
                    iconRetinaUrl: '".$airplaneData['planeIMAGE']."',
                    iconSize: [50, 50],
                    iconAnchor: [20, 20],
                });
                
                speed = ".$airplane['planeSPEED']." / 3.6;
                endtime = ".$activeRow['flightEndTime'].";
                startLat = ".$activeRow['startLat'].";
                startLng = ".$activeRow['startLon'].";
                endLat = ".$activeRow['endLat'].";
                endLng = ".$activeRow['endLon'].";
                
                line = L.polyline([[startLat, startLng], [endLat, endLng]], {color: '".$ir['airlinecolour']."'}).addTo(map);
                line.setText('           \u27A4 Flight: ".number_format($activeRow['planeID'])." - ".$flightname." (ETA: ".$flightend.") \u27A4', {
                    repeat: true,
                    offset: 12,
                    attributes: {fill: 'black'}});
                

                aircraftMarkers.push([L.marker([startLat, startLng], {icon: myAnimationIcon,}).addTo(map), vectorFromLatLong(startLat, startLng, endLat, endLng), speed, line, endtime, ".$activeRow['planeID']."]);

                for (let i = 0; i < ".(time() - $activeRow['flightStartTime'])."; i++) {
                    updateAirplaneAnimationNoCaller(".$i.");
                    
                    if (endtime <= ((new Date().getTime())/1000)) {
                        map.removeLayer(aircraftMarkers[".$i."][0]);
                        map.removeLayer(aircraftMarkers[".$i."][3]);
                        aircraftMarkers.splice(".$i.", 1);
                        break;
                    }
                }
            </script>";
            $i++;
        }
        ?>
        
        
        <script>
            updateAirplaneAnimationCaller();
        
            function getAirportLatLongByName(name) {
                for (let airport of markers) {
                    if (airport.name.toLowerCase().includes(name.toLowerCase()) || airport.icao.toLowerCase().includes(name.toLowerCase())) return [airport.lat, airport.lng];
                }
                return [0, 0];
            }
        
            document.getElementById("airportSearchbox").oninput = (e) => {
                if (e.target.value.length >= 4) {
                    airportlatlng = getAirportLatLongByName(e.target.value);
                    if (airportlatlng[0] != 0 && airportlatlng[1] != 0) {
                        map.flyTo(airportlatlng, 14, {
                            animate: true,
                            duration: 1.5
                        });
                    }
                }
            };
            
            document.getElementById("airportFavSearch").onsubmit = (e) => {
                airportlatlng = getAirportLatLongByName(document.getElementById("airportFavSearchbox").value);
                if (airportlatlng[0] != 0 && airportlatlng[1] != 0) {
                    map.flyTo(airportlatlng, 10, {
                        animate: true,
                        duration: 1.5
                    });
                }
                
                return false;
            };
        </script><?php
    }
}




?>
