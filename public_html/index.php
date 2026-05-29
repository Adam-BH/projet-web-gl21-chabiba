<?php
session_start();
$login_url = '/projet-web-gl21-chabiba/public_html/auth/login.php';
$search_url = '/projet-web-gl21-chabiba/public_html/search-engine/index.php';
$is_logged = isset($_SESSION['is_logged']) && $_SESSION['is_logged'] === true;
$link_url = $is_logged ? $search_url : $login_url;

$pageTitle = 'HIKI - Home';
$pageActive = 'home';
$extraStyles = ['css/style.css'];
include __DIR__ . '/../src/Includes/header.php';
?>
    <section class="hero-section text-center pt-3 mt-3">
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
      <img class="log log-1" src="/projet-web-gl21-chabiba/assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-2" src="/projet-web-gl21-chabiba/assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-3" src="/projet-web-gl21-chabiba/assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-4" src="/projet-web-gl21-chabiba/assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-5" src="/projet-web-gl21-chabiba/assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-6" src="/projet-web-gl21-chabiba/assets/Images/piecesOfWood.png" alt="log"/>
      <img class="log log-7" src="/projet-web-gl21-chabiba/assets/Images/piecesOfWood.png" alt="log"/>
    </div>
</div>
<a href="<?= $link_url ?>" class="glowy-btn">
    CAMPING SITES NEAR ME
</a>
    </div>
    <footer>
        <div class="theme-toggle">
            <input type="checkbox" id="toggle" hidden>
            <label for="toggle" class="toggle-track">
                <div class="toggle-thumb"></div>
            </label>
        </div>
    </footer>
    <script src="/projet-web-gl21-chabiba/js/main.js"></script>
    </div>
</body>
</html>
