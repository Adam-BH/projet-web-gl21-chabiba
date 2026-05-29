<?php
$pageTitle = 'HIKI - Activity Suggestion Engine';
$pageActive = 'guide';
$extraStyles = ['css/pages/hiking-guide.css'];
include __DIR__ . '/../src/Includes/header.php';
?>

    <main class="guide-shell">
        <section class="hero text-center">
            <p class="eyebrow">Personalized for you</p>
            <h1>Discover Your Next Adventure</h1>
            <p class="lead">Tell us about your group and we'll suggest the perfect outdoor activities, complete with videos, tips, and packing checklists.</p>
            
            <form id="suggestionForm" class="filter-form glass-panel mt-4">
                <div class="form-row">
                    <div class="form-group">
                        <label for="ageInput">Youngest Member's Age</label>
                        <input type="number" id="ageInput" name="age" min="1" max="99" placeholder="e.g. 10" class="glass-input">
                    </div>
                    <div class="form-group">
                        <label for="groupSizeInput">Group Size</label>
                        <input type="number" id="groupSizeInput" name="group_size" min="1" max="50" placeholder="e.g. 4" class="glass-input">
                    </div>
                    <div class="form-group">
                        <label for="difficultySelect">Preferred Difficulty</label>
                        <select id="difficultySelect" name="difficulty" class="glass-input">
                            <option value="all">Any</option>
                            <option value="Easy">Easy</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div class="form-group align-bottom">
                        <button type="submit" class="button primary w-100">Find Activities</button>
                    </div>
                </div>
            </form>
        </section>

        <section class="results-section mt-5" id="resultsSection">
            <div class="activity-feed" id="trailsContainer">
                <!-- Suggested activities will be loaded dynamically here via JS -->
            </div>
        </section>

        <!-- General Hiking Tips -->
        <section class="general-tips-section mt-5 glass-panel text-left">
            <h2 class="hero-title" style="font-size: 2rem; margin-bottom: 24px;">General Hiking Guidelines</h2>
            <div class="info-grid" style="border-top: none; padding-top: 0; margin-top: 0;">
                <div class="tips-section">
                    <p class="section-label">Safety First</p>
                    <ul>
                        <li>Tell someone your route and estimated return time before you leave.</li>
                        <li>Carry more water than you think you need, especially in hot weather.</li>
                        <li>Turn around immediately if storms, heavy fog, or darkness arrive early.</li>
                        <li>Stay on marked trails to protect yourself and local wildlife.</li>
                    </ul>
                </div>
                <div class="tips-section">
                    <p class="section-label">Trail Etiquette</p>
                    <ul>
                        <li>Leave no trace: Carry out all your trash, including food scraps.</li>
                        <li>Hikers going uphill have the right of way. Step aside to let them pass.</li>
                        <li>Keep noise levels down to enjoy nature and not disturb animals.</li>
                        <li>Respect local wildlife by observing from a distance.</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="campfire-section" style="margin-top: 50px; display: flex; justify-content: center;">
            <div class="wrapper" style="position: relative; width: 100%; display: flex; justify-content: center; transform: scale(0.6);">
                <div class="campfire">
                    <div class="ground-glow"></div>
                    <div class="pillars">
                      <div class="pillar p1"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                      <div class="pillar p2"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                      <div class="pillar p3"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                      <div class="pillar p4"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                      <div class="pillar p5"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                      <div class="pillar p6"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                      <div class="pillar p7"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                    </div>
                    <div class="logs">
                        <div class="spillar">
                            <div class="ps1"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                            <div class="ps2"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
                            <div class="ps3"><div class="section bottom"></div><div class="section mid2"></div><div class="section mid1"></div><div class="section top"></div></div>
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
            </div>
        </section>
    </main>

    <script src="/projet-web-gl21-chabiba/js/hiking-guide.js"></script>
    <script src="/projet-web-gl21-chabiba/js/main.js"></script>
</div>
</body>
</html>