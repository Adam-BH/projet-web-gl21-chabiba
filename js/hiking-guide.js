document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('suggestionForm');
    const container = document.getElementById('trailsContainer');

    // Initial fetch with no filters to show all suggestions
    fetchTrails();

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const age = document.getElementById('ageInput').value;
        const groupSize = document.getElementById('groupSizeInput').value;
        const difficulty = document.getElementById('difficultySelect').value;

        // Build query string
        const params = new URLSearchParams();
        if (age) params.append('age', age);
        if (groupSize) params.append('group_size', groupSize);
        if (difficulty && difficulty !== 'all') params.append('difficulty', difficulty);

        container.innerHTML = '<p class="text-center" style="color: #8fd3ff;">Finding the perfect activities for you...</p>';
        fetchTrails(params.toString());
    });

    async function fetchTrails(queryString = '') {
        try {
            const url = `/projet-web-gl21-chabiba/api/getTrails.php${queryString ? '?' + queryString : ''}`;
            const response = await fetch(url);
            const data = await response.json();
            renderTrails(data);
        } catch (error) {
            console.error('Error fetching trails:', error);
            container.innerHTML = '<p class="error-text text-center">Failed to load suggestions. Please try again later.</p>';
        }
    }

    function renderTrails(trails) {
        container.innerHTML = '';
        if (trails.length === 0) {
            container.innerHTML = '<div class="glass-panel text-center"><h3>No exact matches</h3><p>Try adjusting your filters to see more activities.</p></div>';
            return;
        }

        trails.forEach((trail, index) => {
            const article = document.createElement('article');
            article.className = 'detailed-activity-card';
            article.style.animationDelay = `${index * 0.15}s`;

            // Parse JSON strings to arrays safely
            let tips = [];
            let checklist = [];
            try {
                tips = trail.tips ? JSON.parse(trail.tips) : [];
                checklist = trail.checklist ? JSON.parse(trail.checklist) : [];
            } catch (e) {
                console.error("Failed to parse tips or checklist", e);
            }

            const tipsHtml = tips.length ? 
                `<div class="tips-section">
                    <p class="section-label">Pro Tips</p>
                    <ul>${tips.map(tip => `<li>${tip}</li>`).join('')}</ul>
                </div>` : '';

            const checklistHtml = checklist.length ? 
                `<div class="checklist-section">
                    <p class="section-label">Gear Checklist</p>
                    <ul>${checklist.map((item, i) => `
                        <li>
                            <label class="checklist-item">
                                <input type="checkbox"> ${item}
                            </label>
                        </li>`).join('')}
                    </ul>
                    <a href="/projet-web-gl21-chabiba/pages/equipment.php" class="button secondary mt-3" style="display: inline-block; width: 100%; text-align: center;">🛒 Buy Equipment</a>
                </div>` : '';

            let videoHtml = '';
            if (trail.video_url && (trail.video_url.includes('youtube.com') || trail.video_url.includes('youtu.be'))) {
                let videoId = '';
                if (trail.video_url.includes('watch?v=')) {
                    videoId = trail.video_url.split('watch?v=')[1].split('&')[0];
                } else if (trail.video_url.includes('youtu.be/')) {
                    videoId = trail.video_url.split('youtu.be/')[1].split('?')[0];
                } else if (trail.video_url.includes('embed/')) {
                    videoId = trail.video_url.split('embed/')[1].split('?')[0];
                }

                if (videoId) {
                    // loop=1 requires playlist=VIDEO_ID in YouTube iframe API
                    videoHtml = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1&loop=1&playlist=${videoId}&mute=1&controls=0&rel=0&showinfo=0" 
                                    title="YouTube video player" frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen style="pointer-events: none;"></iframe>`;
                }
            } else {
                videoHtml = `<video src="${trail.video_url || ''}" loop autoplay muted playsinline poster="../assets/Images/placeholder.jpg"></video>`;
            }

            article.innerHTML = `
                <div class="activity-video-container">
                    ${videoHtml}
                </div>
                <div class="activity-content">
                    <span class="badge">${trail.level}</span>
                    <h2>${trail.name}</h2>
                    <p style="color: rgba(238, 245, 255, 0.72);">${trail.details}</p>
                    
                    <div class="info-grid">
                        ${tipsHtml}
                        ${checklistHtml}
                    </div>
                </div>
            `;
            container.appendChild(article);
        });
    }
});