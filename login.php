<?php
require_once('required/globals_nonauth.php');
$login_csrf = request_csrf_code('login');
$IP = str_replace(array('/', '\\', "\0"), '', $_SERVER['REMOTE_ADDR']);
if (file_exists('ipbans/' . $IP)) {
    die("<span style='font-weight: bold; color:red;'> Your IP has been banned, there is no way around this. </span></body></html>");
}
$year = date('Y');

// Stats
$q1 = $db->query("SELECT * FROM `airports`");
$airports = $db->num_rows($q1);
$q2 = $db->query("SELECT * FROM `airplanes` WHERE planeACTIVE='1'");
$planes = $db->num_rows($q2);
$totalc = 0;
$q3 = $db->query("SELECT flighttime FROM userairplanes");
while ($catch = $db->fetch_row($q3)) {
    $totalc += $catch['flighttime'];
}
$totald = 0;
$q4 = $db->query("SELECT totaldistance FROM users");
while ($catch2 = $db->fetch_row($q4)) {
    $totald += $catch2['totaldistance'];
}
$q4 = $db->query("SELECT * FROM `activeflights`");
$activeflights = $db->num_rows($q4);
$q5 = $db->query("SELECT * FROM `userairplanes`");
$totalplanes = $db->num_rows($q5);
$totalf = 0;
$q6 = $db->query("SELECT totalflights FROM userairplanes");
while ($catch = $db->fetch_row($q6)) {
    $totalf += $catch['totalflights'];
}
$q7 = $db->query("SELECT * FROM `airplanes` WHERE planeACTIVE='1'");
$gameplanes = $db->num_rows($q7);
$ct = $db->query("SELECT * FROM users"); 
$rdy = $db->num_rows($ct);
$q6 = $db->query('SELECT userid FROM users WHERE laston >= UNIX_TIMESTAMP()-900', $c);
$online = $db->num_rows($q6);

$earthCircumference = 40075; // km
$timesAroundEarth = $totald / $earthCircumference;

$distanceMarsRoundTrip = 450000000; // km
$timesMarsRoundTrip = $totald / $distanceMarsRoundTrip;

$distanceMoonRoundTrip = 768800; // km
$timesMoonRoundTrip = $totald / $distanceMoonRoundTrip;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $set['gamename'] . ' ' . $set['version']; ?></title>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        :root {
            --primary-color: #00bfff;
            --bg-dark: rgba(0, 0, 0, 0.7);
            --text-light: #fff;
        }
        body {
            background-color: #111;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url(images/backer7.png);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background-color: var(--bg-dark);
            border: none;
            border-radius: 1rem;
            padding: 2rem;
            color: var(--text-light);
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s forwards;
        }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.4s; }
        .card:nth-child(4) { animation-delay: 0.6s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .form-control {
            background-color: #1a1a1a;
            border: 1px solid #444;
            color: white;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background-color: #222;
            border-color: var(--primary-color);
            color: white;
            box-shadow: 0 0 10px var(--primary-color);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #009acd;
            transform: scale(1.05);
        }
        .list-group-item {
            background: transparent;
            color: white;
            border: none;
            padding-left: 0;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }
        .list-group-item strong {
            display: inline-block;
        }
        .footer-version {
            position: fixed;
            bottom: 10px;
            right: 20px;
            font-size: 0.85rem;
            color: #ccc;
            background: rgba(0,0,0,0.6);
            padding: 6px 12px;
            border-radius: 6px;
        }

        #register-card {
            display: none;
            animation: fadeInUp 0.6s forwards;
        }
    </style>
    <script>
        function toggleRegister() {
            var reg = document.getElementById("register-card");
            reg.style.display = reg.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <!-- Login card -->
        <div class="card">
            <h2 class="text-center mb-4">Login</h2>
            <form action="authenticate.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label"></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Your Email Address" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Your Password" required>
                </div>
                <input type="hidden" name="verf" value="<?php echo $login_csrf; ?>">
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            
            
            <div class="mt-4 text-center"><center>
                <div id="g_id_onload"
                    data-client_id="926291496324-hnfrqle8bi3h13cue1gnnk9htp3chgj2.apps.googleusercontent.com"
                    data-login_uri="https://con-airline.com/googlelogin.php"
                    data-auto_prompt="false">
                </div>
                <div class="g_id_signin"
                    data-type="standard"
                    data-size="large"
                    data-theme="outline"
                    data-text="sign_in_with"
                    data-shape="rectangular"
                    data-logo_alignment="left">
                </div>
            </div></center>
            
            <br />
            <button class="btn btn-primary" onclick="toggleRegister()">Register Today</button>
            
            <center>
                <a href="https://infamous.con-airline.com/login.php" target="_blank"><br /><br />
                    <font size="-2">Sponsered By</font><br />
                    <img src="https://infamous.con-airline.com/images/logo.png" width="100%">
                </a>
            </center>
        </div>

        <div class="card" id="register-card">
            <h2 class="text-center mb-4">Register</h2>
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="cpassword" required>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label">Airline Name</label>
                    <input type="text" class="form-control" name="airlinename" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Airline Colour</label>
                    <input type="color" class="form-control form-control-color" name="airlinecolour" value="#CCCCCC">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2 class="text-center mb-4">Game Stats</h2>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">🛫 Total Airports: <strong id="airports">0</strong></li>
                <li class="list-group-item">✈️ Active Flights: <strong id="activeflights">0</strong></li>
                <li class="list-group-item">📍 Total Travelled: <strong id="totald">0</strong> km</li>
                <li class="list-group-item">🌍 Times Around Earth: <strong id="aroundearth">0.00</strong></li>
                <li class="list-group-item">🌙 Times to Moon & Back: <strong id="moonroundtrip">0.00</strong></li>
                <li class="list-group-item">🚀 Times to Mars & Back: <strong id="marsroundtrip">0.00</strong></li>
                <li class="list-group-item">🛩️ User Planes: <strong id="userplanes">0</strong></li>
                <li class="list-group-item">📋 Total Flights: <strong id="totalf">0</strong></li>
                <li class="list-group-item">🛠 Game Planes: <strong id="gameplanes">0</strong></li>
                <li class="list-group-item">⏱ Total Flight Hours: <strong id="flighttime"></strong></li>
            </ul>

        </div>
        
    </div>

    
    
    <div class="footer-version">
        <?php echo $set['gamename']; ?> v<?php echo $set['version']; ?> <?php echo $rdy; ?>.<?php echo $online; ?> - ©<?php echo date('Y'); ?>
    </div>
    <script>
        function animateValue(id, start, end, duration, decimals = 0) {
            let obj = document.getElementById(id);
            let range = end - start;
            if (range === 0) {
                obj.textContent = end.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                return;
            }
            let minStepTime = 10;
            let maxDuration = 1500;
            let stepTime = Math.max(minStepTime, Math.floor(duration / Math.abs(range)));
            let steps = Math.min(Math.abs(range), Math.floor(maxDuration / stepTime));
            let increment = range / steps;
            let current = start;
            let count = 0;
        
            let timer = setInterval(function () {
                current += increment;
                count++;
                if ((increment > 0 && current >= end) || (increment < 0 && current <= end) || count >= steps) {
                    obj.textContent = end.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                    clearInterval(timer);
                } else {
                    obj.textContent = current.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                }
            }, stepTime);
        }

        
        
        animateValue("airports", 0, <?php echo $airports; ?>, 1000);
        animateValue("activeflights", 0, <?php echo $activeflights; ?>, 1000);
        animateValue("totald", 0, <?php echo $totald; ?>, 1000);
        animateValue("aroundearth", 0, <?php echo $timesAroundEarth; ?>, 1000, 2);
        animateValue("moonroundtrip", 0, <?php echo $timesMoonRoundTrip; ?>, 1000, 2);
        animateValue("marsroundtrip", 0, <?php echo $timesMarsRoundTrip; ?>, 1000, 2);
        animateValue("userplanes", 0, <?php echo $totalplanes; ?>, 1000);
        animateValue("totalf", 0, <?php echo $totalf; ?>, 1000);
        animateValue("gameplanes", 0, <?php echo $gameplanes; ?>, 1000);
        




        window.addEventListener("DOMContentLoaded", function () {
            let seconds = <?php echo $totalc % 60; ?>;
            let minutes = <?php echo ($totalc / 60) % 60; ?>;
            let hours = <?php echo floor($totalc / 3600); ?>;
            let flightTimeEl = document.getElementById("flighttime");
            flightTimeEl.textContent = `${hours.toLocaleString()} Hours, ${Math.floor(minutes)} Minutes and ${Math.floor(seconds)} Seconds`;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
