<?php
require_once __DIR__ . '/../autoloader.php';

$pageTitle = 'HIKI - Camping Sites Catalogue';
$pageActive = 'catalogue';
$extraStyles = ['css/pages/catalogue.css'];
include __DIR__ . '/../includes/header.php';

$repo = new CampingSiteRepository();
$sites = [];
$error = null;
try{
    $sites = $repo->findAll();
}catch(Exception $e){
    $error = $e->getMessage();
}

/**
 * Render a site card component
 * @param object $site The site object
 * @param bool $listView Whether to render in list view
 */
function renderSiteCard($site, $listView = false) {
    $classModifier = $listView ? 'list-view' : '';
    $dataName = htmlspecialchars($site->name ?? '');
    $dataCity = htmlspecialchars($site->city ?? '');
    $dataCapacity = (int)($site->capacity ?? 0);
    $dataLat = is_numeric($site->lat) ? $site->lat : '';
    $dataLon = is_numeric($site->lon) ? $site->lon : '';
    $dataDesc = htmlspecialchars($site->description ?? '');
    ?>
    <article class="site-card <?php echo htmlspecialchars($classModifier); ?>" 
        data-name="<?php echo $dataName; ?>" 
        data-city="<?php echo $dataCity; ?>" 
        data-capacity="<?php echo $dataCapacity; ?>" 
        data-lat="<?php echo $dataLat; ?>" 
        data-lon="<?php echo $dataLon; ?>" 
        data-description="<?php echo $dataDesc; ?>">
        <div class="site-card-image">
            <?php if ($site->image): ?>
                <img src="<?php echo htmlspecialchars($site->image); ?>" alt="<?php echo htmlspecialchars($site->name); ?>">
            <?php else: ?>
                <span>No image available</span>
            <?php endif; ?>
        </div>
        <div class="site-card-content">
            <div class="site-card-header">
                <h2 class="site-card-title"><?php echo htmlspecialchars($site->name ?? 'Untitled'); ?></h2>
                <p class="site-card-city"><?php echo htmlspecialchars($site->city ?? 'Location unavailable'); ?></p>
            </div>
            <div class="site-card-meta">
                <div class="site-meta-item">
                    <span class="site-meta-label">Capacity:</span>
                    <span><?php echo htmlspecialchars($site->capacity ?? '-'); ?></span>
                </div>
            </div>
            <p class="site-card-description">
                <?php echo nl2br(htmlspecialchars($site->description ?? 'No description available')); ?>
            </p>
            <div class="site-card-actions">
                <a href="#" class="site-card-button">View Details</a>
                <a href="#" class="site-card-button">Book Now</a>
            </div>
        </div>
    </article>
    <?php
}
?>

    <main class="catalogue-shell">
        <section class="catalogue-hero">
            <p class="catalogue-eyebrow">Explore &amp; Discover</p>
            <h1>Camping Sites</h1>
            <p class="catalogue-lead">Discover amazing camping sites with detailed information, beautiful locations, and all the facilities you need for your outdoor adventure.</p>
            <div class="catalogue-hero-actions">
                <button type="button" class="view-toggle active" data-view="grid" aria-label="Switch to grid view">
                    <span>Grid View</span>
                </button>
                <button type="button" class="view-toggle" data-view="list" aria-label="Switch to list view">
                    <span>List View</span>
                </button>
            </div>
        </section>

        <div class="catalogue-controls" style="width:min(1180px,calc(100vw - 32px));margin:18px auto 0;display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <input id="siteSearch" type="search" placeholder="Search sites, city, description" style="flex:1;min-width:200px;padding:10px 12px;border-radius:10px;border:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);color:var(--catalogue-text);">
            <select id="siteSort" style="padding:10px 12px;border-radius:10px;border:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);color:var(--catalogue-text);">
                <option value="recommended">Recommended</option>
                <option value="capacity-desc">Capacity: High → Low</option>
                <option value="capacity-asc">Capacity: Low → High</option>
                <option value="distance-asc">Distance: Near → Far</option>
                <option value="distance-desc">Distance: Far → Near</option>
            </select>
            <button id="locateBtn" class="view-toggle" type="button" style="padding:10px 14px;">Use my location</button>
            <button id="clearLocation" class="view-toggle" type="button" style="padding:10px 14px;">Clear location</button>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($sites)): ?>
            <div class="sites-grid">
                <div class="empty-state">
                    <h2>No Sites Available</h2>
                    <p>We couldn't find any camping sites at the moment. Please try again later.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="sites-grid" id="sitesContainer">
                <?php foreach($sites as $site): ?>
                    <?php renderSiteCard($site, false); ?>
                <?php endforeach; ?>
            </div>

            <div class="sites-list hidden" id="sitesListContainer">
                <?php foreach($sites as $site): ?>
                    <?php renderSiteCard($site, true); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // View toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const gridViewBtn = document.querySelector('[data-view="grid"]');
            const listViewBtn = document.querySelector('[data-view="list"]');
            const gridContainer = document.getElementById('sitesContainer');
            const listContainer = document.getElementById('sitesListContainer');

            if (gridViewBtn && listViewBtn && gridContainer && listContainer) {
                gridViewBtn.addEventListener('click', function() {
                    gridViewBtn.classList.add('active');
                    listViewBtn.classList.remove('active');
                    gridContainer.classList.remove('hidden');
                    listContainer.classList.add('hidden');
                });

                listViewBtn.addEventListener('click', function() {
                    listViewBtn.classList.add('active');
                    gridViewBtn.classList.remove('active');
                    gridContainer.classList.add('hidden');
                    listContainer.classList.remove('hidden');
                });
            }
        });
    </script>
    <script>
        // Search, sort and geolocation-based filtering
        (function(){
            function haversine(lat1, lon1, lat2, lon2){
                function toRad(v){return v*Math.PI/180;}
                var R = 6371; // km
                var dLat = toRad(lat2-lat1);
                var dLon = toRad(lon2-lon1);
                var a = Math.sin(dLat/2)*Math.sin(dLat/2) + Math.cos(toRad(lat1))*Math.cos(toRad(lat2))*Math.sin(dLon/2)*Math.sin(dLon/2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c;
            }

            document.addEventListener('DOMContentLoaded', function(){
                const searchInput = document.getElementById('siteSearch');
                const sortSelect = document.getElementById('siteSort');
                const locateBtn = document.getElementById('locateBtn');
                const clearLocation = document.getElementById('clearLocation');
                const gridContainer = document.getElementById('sitesContainer');
                const listContainer = document.getElementById('sitesListContainer');

                let userLocation = null;

                function readCards(container){
                    return Array.from(container.querySelectorAll('.site-card'));
                }

                function filterAndSort(){
                    const q = (searchInput.value || '').toLowerCase().trim();
                    const sort = sortSelect.value;

                    // merge both grid and list cards but operate on DOM separately
                    const gridCards = readCards(gridContainer);
                    const listCards = readCards(listContainer);
                    const allCards = gridCards.concat(listCards);

                    // compute filtering
                    allCards.forEach(card => {
                        const name = (card.dataset.name || '').toLowerCase();
                        const city = (card.dataset.city || '').toLowerCase();
                        const desc = (card.dataset.description || '').toLowerCase();
                        let matches = true;
                        if (q){
                            matches = name.includes(q) || city.includes(q) || desc.includes(q);
                        }

                        // distance computation if user location known
                        if (userLocation && card.dataset.lat && card.dataset.lon){
                            const d = haversine(userLocation.lat, userLocation.lon, parseFloat(card.dataset.lat), parseFloat(card.dataset.lon));
                            card.dataset.distanceKm = d.toFixed(2);
                        } else {
                            delete card.dataset.distanceKm;
                        }

                        card.style.display = matches ? '' : 'none';
                    });

                    // sorting within visible cards in each container
                    function sortContainer(container){
                        const cards = readCards(container).filter(c => c.style.display !== 'none');
                        if (!cards.length) return;
                        let sorted = cards.slice();
                        if (sort === 'capacity-desc'){
                            sorted.sort((a,b)=> parseInt(b.dataset.capacity||0)-parseInt(a.dataset.capacity||0));
                        } else if (sort === 'capacity-asc'){
                            sorted.sort((a,b)=> parseInt(a.dataset.capacity||0)-parseInt(b.dataset.capacity||0));
                        } else if (sort === 'distance-asc' && userLocation){
                            sorted.sort((a,b)=> (parseFloat(a.dataset.distanceKm||1e9)-parseFloat(b.dataset.distanceKm||1e9)));
                        } else if (sort === 'distance-desc' && userLocation){
                            sorted.sort((a,b)=> (parseFloat(b.dataset.distanceKm||0)-parseFloat(a.dataset.distanceKm||0)));
                        } else {
                            // recommended: by capacity desc then name
                            sorted.sort((a,b)=> (parseInt(b.dataset.capacity||0)-parseInt(a.dataset.capacity||0)) || a.dataset.name.localeCompare(b.dataset.name));
                        }

                        // re-append in new order
                        sorted.forEach(c=> container.appendChild(c));
                    }

                    sortContainer(gridContainer);
                    sortContainer(listContainer);
                }

                // events
                if (searchInput) searchInput.addEventListener('input', filterAndSort);
                if (sortSelect) sortSelect.addEventListener('change', filterAndSort);

                locateBtn && locateBtn.addEventListener('click', function(){
                    if (!navigator.geolocation){
                        alert('Geolocation is not available in your browser.');
                        return;
                    }
                    locateBtn.disabled = true;
                    locateBtn.textContent = 'Locating...';
                    navigator.geolocation.getCurrentPosition(function(pos){
                        userLocation = {lat: pos.coords.latitude, lon: pos.coords.longitude};
                        locateBtn.textContent = 'Location set';
                        locateBtn.classList.add('active');
                        filterAndSort();
                        locateBtn.disabled = false;
                    }, function(err){
                        alert('Unable to get location: ' + (err.message || err.code));
                        locateBtn.textContent = 'Use my location';
                        locateBtn.disabled = false;
                    }, {enableHighAccuracy:true, maximumAge:60000, timeout:20000});
                });

                clearLocation && clearLocation.addEventListener('click', function(){
                    userLocation = null;
                    // remove distance data attributes
                    readCards(gridContainer).concat(readCards(listContainer)).forEach(c=> delete c.dataset.distanceKm);
                    filterAndSort();
                    locateBtn.classList.remove('active');
                    locateBtn.textContent = 'Use my location';
                });

                // initial sort/filter pass
                filterAndSort();
            });
        })();
    </script>
    <script src="/projet-web-gl21-chabiba/js/main.js"></script>
</div>
</body>
</html>