<?php
session_start();
$login_url = 'pages/auth/login.php';
$search_url = 'pages/search.php';
$is_logged = isset($_SESSION['is_logged']) && $_SESSION['is_logged'] === true;
$link_url = $is_logged ? $search_url : $login_url;
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>HIKI - Home</title>
</head>
<body>
    <canvas id="starCanvas" style="position:fixed;inset:0;z-index:0;pointer-events:none;"></canvas>
    <nav class="navbar navbar-dark">
        <div class="container">
            <a href="." class="navbar-brand">HIKI</a>
            <ul class="navbar-nav nav-links">
                <li class="nav-item">
                    <a href="#pages/shops.html" class="nav-link">shops</a>
                </li>
                <li class="nav-item">
                    <a href="#pages/weather.html" class="nav-link">weather</a>
                </li>
                <li class="nav-item">
                    <a href="#pages/community.html" class="nav-link">community</a>
                </li>
                <li class="nav-item">
                    <a href="#pages/equipement.html" class="nav-link">equipement</a>
                </li>
                <li class="nav-item">
                    <a href="#pages/moon.html" class="nav-link">moon</a>
                </li>
                <li class="nav-item">
                    <a href="pages/lost&found/lost&found.php" class="nav-link">Lost & found</a>
                </li>
            </ul>
        </div>
    </nav>
    <section class="text-center pt-3 mt-3 style="position:relative;z-index:0;">
        <p class="hero-sub">Explore Our website for a</p>
        <p class="hero-title">Better Experience</p>
    </section>
    <div class="wrapper">
    <div class="campfire">
        <div class="ground-glow"></div>
    <div class="pillars">
      <div class="pillar p1">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
        </div>
        <div class="pillar p2">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
      </div>
      <div class="pillar p3">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
      </div>
      <div class="pillar p4">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
        </div>
      <div class="pillar p5">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
    </div>
    <div class="pillar p6">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
    </div>
      <div class="pillar p7">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
      </div>
    </div>
    <div class="logs">
        <div class="spillar">
            <div class="ps1">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
            </div>
            <div class="ps2">
        <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
            </div>
            <div class="ps3">
           <div class="section bottom"></div>
        <div class="section mid2"></div>
        <div class="section mid1"></div>
        <div class="section top"></div>
            </div>
        </div>
      <img class="log log-1" src="assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-2" src="assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-3" src="assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-4" src="assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-5" src="assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-6" src="assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-7" src="assets/Images/piecesOfWood.png" alt="log"/>
    </div>
</div>
<a href="<?= $link_url ?>" class="glowy-btn">
    CAMPING SITES NEAR ME
</a>
   <footer>
<button class="sparky-mascot">
  <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36 "
       viewBox="0 0 24 24" fill="none" stroke="black"
       stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>
  </svg>
</button>
        <div class="theme-toggle">
            <input type="checkbox" id="toggle" hidden>
            <label for="toggle" class="toggle-track">
                <div class="toggle-thumb"></div>
            </label>
        </div>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>