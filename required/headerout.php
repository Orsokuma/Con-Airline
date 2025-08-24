<nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
<div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
          
        <li class="nav-item">
          <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#myModal" href="login.php"><b><?php echo $set['gamename'];?></b></button>
        </li>
      </ul>
      <b><span id='ct7'></span></b>
    </div>
  </div>
</nav>




<nav class="navbar navbar-expand-sm bg-light navbar-light fixed-bottom">
<div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav=item">
           <span id="button3" class="btn btn-light">DMCA</span>
        </li>
        <li class="nav=item">
           <span id="button4" class="btn btn-light">CONTACT</span>
        </li>
        <li class="nav=item">
           <span id="button5" class="btn btn-light">ADVERTISE</span>
        </li>
        <li class="nav=item">
           <span id="button6" class="btn btn-light">ToS</span>
        </li>
        <li class="nav=item">
           <span id="button7" class="btn btn-light">Privacy</span>
        </li>
      </ul>
      <?php echo $set['gamename']; ?> v<?php echo $set['version']; ?><?php echo $rdy; ?>.<?php echo $online; ?> - ©<?php $year = date('Y'); echo $year; ?> - Founders: Peterisgb & IntelliRon
    </div>
  </div>
</nav>


<script>
function display_ct7() {
    var x = new Date().toLocaleString("en-UK", {timeZone: "Europe/London"})
    document.getElementById('ct7').innerHTML = x.split(", ")[1];
    display_c7();
}
function display_c7(){
    var refresh=1000; // Refresh rate in milli seconds
    mytime=setTimeout('display_ct7()',refresh);
}
display_ct7();
display_c7()
</script>
