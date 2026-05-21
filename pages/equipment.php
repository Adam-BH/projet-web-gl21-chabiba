<?php
$pageTitle = 'Equipment Catalogue - HIKI';
$pageActive = 'equipment';
$extraStyles = ['css/pages/equipment.css'];
include __DIR__ . '/includes/header.php';
?>

    <main class="equipment-shell">
        <section class="hero text-center">
            <p class="eyebrow">Gear Up</p>
            <h1>Equipment Catalogue</h1>
            <p class="lead">Find the best gear for your next adventure. High-quality recommendations with links to trusted retailers.</p>
        </section>

        <!-- Category Filters -->
        <div class="category-filters" id="categoryFilters">
            <button class="filter-chip is-active" data-cat="all">All</button>
            <button class="filter-chip" data-cat="footwear">Footwear</button>
            <button class="filter-chip" data-cat="bags">Bags &amp; Packs</button>
            <button class="filter-chip" data-cat="accessories">Accessories</button>
            <button class="filter-chip" data-cat="safety">Safety</button>
            <button class="filter-chip" data-cat="hydration">Hydration</button>
        </div>

        <section class="catalogue-section">
            <div class="equipment-grid" id="equipmentGrid">

                <!-- 1 – Hiking Boots -->
                <article class="equipment-card glass-panel" data-category="footwear">
                    <div class="card-img-wrap">
                        <img src="../assets/Images/hiking_boots.png" alt="All-Terrain Hiking Boots" class="equipment-img" loading="lazy">
                        <span class="cat-badge">Footwear</span>
                    </div>
                    <div class="equipment-info">
                        <h3>All-Terrain Hiking Boots</h3>
                        <div class="rating">★★★★★ <span class="review-count">(2,431)</span></div>
                        <p class="price">$120 <span class="price-note">from</span></p>
                        <p class="desc">Waterproof and breathable boots with ankle support for maximum comfort on rough terrain.</p>
                        <div class="buy-links">
                            <a href="https://www.amazon.com/s?k=hiking+boots+waterproof" target="_blank" rel="noopener" class="buy-btn amazon">Buy on Amazon</a>
                            <a href="https://www.decathlon.com/collections/hiking-boots" target="_blank" rel="noopener" class="buy-btn decathlon">Buy on Decathlon</a>
                        </div>
                    </div>
                </article>

                <!-- 2 – Backpack -->
                <article class="equipment-card glass-panel" data-category="bags">
                    <div class="card-img-wrap">
                        <img src="../assets/Images/hiking_backpack.png" alt="40L Expedition Backpack" class="equipment-img" loading="lazy">
                        <span class="cat-badge">Bags</span>
                    </div>
                    <div class="equipment-info">
                        <h3>40L Expedition Backpack</h3>
                        <div class="rating">★★★★☆ <span class="review-count">(1,847)</span></div>
                        <p class="price">$85 <span class="price-note">from</span></p>
                        <p class="desc">Lightweight pack with multiple compartments, hydration bladder support and rain cover.</p>
                        <div class="buy-links">
                            <a href="https://www.amazon.com/s?k=hiking+backpack+40L" target="_blank" rel="noopener" class="buy-btn amazon">Buy on Amazon</a>
                            <a href="https://www.decathlon.com/collections/hiking-backpacks" target="_blank" rel="noopener" class="buy-btn decathlon">Buy on Decathlon</a>
                        </div>
                    </div>
                </article>

                <!-- 3 – Trekking Poles -->
                <article class="equipment-card glass-panel" data-category="accessories">
                    <div class="card-img-wrap">
                        <img src="../assets/Images/trekking_poles.png" alt="Carbon Fiber Trekking Poles" class="equipment-img" loading="lazy">
                        <span class="cat-badge">Accessories</span>
                    </div>
                    <div class="equipment-info">
                        <h3>Carbon Fiber Trekking Poles</h3>
                        <div class="rating">★★★★★ <span class="review-count">(963)</span></div>
                        <p class="price">$45 <span class="price-note">from</span></p>
                        <p class="desc">Ultra-light collapsible poles with ergonomic cork grips, adjustable length.</p>
                        <div class="buy-links">
                            <a href="https://www.amazon.com/s?k=carbon+fiber+trekking+poles" target="_blank" rel="noopener" class="buy-btn amazon">Buy on Amazon</a>
                            <a href="https://www.decathlon.com/collections/trekking-poles" target="_blank" rel="noopener" class="buy-btn decathlon">Buy on Decathlon</a>
                        </div>
                    </div>
                </article>

                <!-- 4 – Headlamp -->
                <article class="equipment-card glass-panel" data-category="accessories">
                    <div class="card-img-wrap">
                        <img src="../assets/Images/headlamp.png" alt="Rechargeable LED Headlamp" class="equipment-img" loading="lazy">
                        <span class="cat-badge">Accessories</span>
                    </div>
                    <div class="equipment-info">
                        <h3>Rechargeable LED Headlamp</h3>
                        <div class="rating">★★★★☆ <span class="review-count">(3,120)</span></div>
                        <p class="price">$25 <span class="price-note">from</span></p>
                        <p class="desc">1000-lumen USB-C rechargeable headlamp with red-light mode for night hiking.</p>
                        <div class="buy-links">
                            <a href="https://www.amazon.com/s?k=rechargeable+led+headlamp+hiking" target="_blank" rel="noopener" class="buy-btn amazon">Buy on Amazon</a>
                            <a href="https://www.decathlon.com/collections/headlamps" target="_blank" rel="noopener" class="buy-btn decathlon">Buy on Decathlon</a>
                        </div>
                    </div>
                </article>

                <!-- 5 – Water Bottle -->
                <article class="equipment-card glass-panel" data-category="hydration">
                    <div class="card-img-wrap">
                        <img src="../assets/Images/water_bottle.png" alt="Insulated Water Bottle" class="equipment-img" loading="lazy">
                        <span class="cat-badge">Hydration</span>
                    </div>
                    <div class="equipment-info">
                        <h3>Insulated Steel Bottle – 1L</h3>
                        <div class="rating">★★★★★ <span class="review-count">(5,612)</span></div>
                        <p class="price">$28 <span class="price-note">from</span></p>
                        <p class="desc">Double-wall vacuum insulated – keeps water cold 24 hrs or hot 12 hrs on the trail.</p>
                        <div class="buy-links">
                            <a href="https://www.amazon.com/s?k=insulated+hiking+water+bottle" target="_blank" rel="noopener" class="buy-btn amazon">Buy on Amazon</a>
                            <a href="https://www.decathlon.com/collections/water-bottles" target="_blank" rel="noopener" class="buy-btn decathlon">Buy on Decathlon</a>
                        </div>
                    </div>
                </article>

                <!-- 6 – First Aid Kit -->
                <article class="equipment-card glass-panel" data-category="safety">
                    <div class="card-img-wrap">
                        <img src="../assets/Images/first_aid_kit.png" alt="Compact First Aid Kit" class="equipment-img" loading="lazy">
                        <span class="cat-badge">Safety</span>
                    </div>
                    <div class="equipment-info">
                        <h3>Compact Trail First Aid Kit</h3>
                        <div class="rating">★★★★★ <span class="review-count">(1,298)</span></div>
                        <p class="price">$18 <span class="price-note">from</span></p>
                        <p class="desc">Lightweight 120-piece kit with bandages, antiseptic, emergency blanket and whistle.</p>
                        <div class="buy-links">
                            <a href="https://www.amazon.com/s?k=hiking+first+aid+kit" target="_blank" rel="noopener" class="buy-btn amazon">Buy on Amazon</a>
                            <a href="https://www.decathlon.com/collections/first-aid" target="_blank" rel="noopener" class="buy-btn decathlon">Buy on Decathlon</a>
                        </div>
                    </div>
                </article>

                <!-- 7 – Rain Jacket (no generated image) -->
                <article class="equipment-card glass-panel" data-category="accessories">
                    <div class="card-img-wrap placeholder-wrap">
                        <div class="placeholder-icon">🧥</div>
                        <span class="cat-badge">Accessories</span>
                    </div>
                    <div class="equipment-info">
                        <h3>Ultralight Rain Jacket</h3>
                        <div class="rating">★★★★☆ <span class="review-count">(2,054)</span></div>
                        <p class="price">$65 <span class="price-note">from</span></p>
                        <p class="desc">Packable waterproof shell that folds into its own pocket. Breathable and seam-sealed.</p>
                        <div class="buy-links">
                            <a href="https://www.amazon.com/s?k=ultralight+waterproof+hiking+jacket" target="_blank" rel="noopener" class="buy-btn amazon">Buy on Amazon</a>
                            <a href="https://www.decathlon.com/collections/rain-jackets" target="_blank" rel="noopener" class="buy-btn decathlon">Buy on Decathlon</a>
                        </div>
                    </div>
                </article>

                <!-- 8 – GPS / Navigation -->
                <article class="equipment-card glass-panel" data-category="safety">
                    <div class="card-img-wrap placeholder-wrap">
                        <div class="placeholder-icon">🧭</div>
                        <span class="cat-badge">Safety</span>
                    </div>
                    <div class="equipment-info">
                        <h3>Handheld GPS Navigator</h3>
                        <div class="rating">★★★★★ <span class="review-count">(876)</span></div>
                        <p class="price">$199 <span class="price-note">from</span></p>
                        <p class="desc">Rugged satellite GPS with preloaded topo maps, 16 h battery, and SOS beacon.</p>
                        <div class="buy-links">
                            <a href="https://www.amazon.com/s?k=handheld+hiking+GPS" target="_blank" rel="noopener" class="buy-btn amazon">Buy on Amazon</a>
                            <a href="https://www.decathlon.com/collections/gps-devices" target="_blank" rel="noopener" class="buy-btn decathlon">Buy on Decathlon</a>
                        </div>
                    </div>
                </article>

            </div>
        </section>

        <!-- Buying Tips -->
        <section class="buying-tips glass-panel">
            <h2>💡 Buying Tips</h2>
            <div class="tips-grid">
                <div class="tip-item">
                    <h4>Check Reviews</h4>
                    <p>Always read trail-tested reviews, not just store ratings. Look for comments about durability and weather performance.</p>
                </div>
                <div class="tip-item">
                    <h4>Buy Quality, Not Brand</h4>
                    <p>A well-made mid-range boot will outlast a cheap branded one. Prioritise materials like Gore-Tex and Vibram soles.</p>
                </div>
                <div class="tip-item">
                    <h4>Try Before Long Hikes</h4>
                    <p>Break in new boots on short walks first. Never take untested gear on a summit day.</p>
                </div>
            </div>
        </section>
    </main>

    <script src="/projet-web-gl21-chabiba/js/equipment.js"></script>
    <script src="/projet-web-gl21-chabiba/js/main.js"></script>
</div>
</body>
</html>
