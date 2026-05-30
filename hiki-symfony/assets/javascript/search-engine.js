const state = {
	currentStep: 1,
	map: null,
	marker: null,
	circle: null,
	center: { lat: 45.0, lng: 3.0 },
	sites: [],
	markers: [],
};

// IDs match search/index.html.twig
const stepButtons = document.querySelectorAll('.step-btn');
const steps = document.querySelectorAll('.step');
const prevButton = document.getElementById('prevBtn');
const nextButton = document.getElementById('nextBtn');
const searchButton = document.getElementById('searchBtn');
const resultsContainer = document.getElementById('searchResults');
const useLocationCheckbox = document.getElementById('use-location');
const radiusSelect = document.getElementById('radius');
const radiusValueLabel = document.getElementById('radiusValue');
const selectedInfo = document.getElementById('selected-info');
const startDateInput = document.getElementById('start-date');
const endDateInput = document.getElementById('end-date');
const peopleInput = document.getElementById('people-count');

// URLs injected from Twig; fall back to Symfony defaults
const SEARCH_API_URL = typeof SEARCH_API !== 'undefined' ? SEARCH_API : '/api/search';
const SITES_API_URL = typeof SITES_API !== 'undefined' ? SITES_API : '/api/sites';
const CATALOGUE_BASE_URL = typeof CATALOGUE_BASE !== 'undefined' ? CATALOGUE_BASE : '/catalogue';
const APP_LOGIN_URL = typeof LOGIN_URL !== 'undefined' ? LOGIN_URL : '/login';
const APP_EQUIPMENT_URL = typeof EQUIPMENT_URL !== 'undefined' ? EQUIPMENT_URL : '/equipment';

function updateStepper() {
	stepButtons.forEach((btn) => {
		const s = parseInt(btn.dataset.step, 10);
		btn.classList.toggle('active', s === state.currentStep);
	});
	steps.forEach((step, index) => {
		step.style.display = state.currentStep === index + 1 ? 'block' : 'none';
	});
	if (prevButton) prevButton.style.display = state.currentStep > 1 ? '' : 'none';
	if (nextButton) nextButton.style.display = state.currentStep < 3 ? '' : 'none';
	if (searchButton) searchButton.style.display = state.currentStep === 3 ? '' : 'none';
}

function setMapCenter(lat, lng) {
	state.center = { lat, lng };
	if (!state.map) return;
	state.map.setView(state.center, 9);
	state.marker.setLatLng(state.center);
	updateCircle();
	updateSelectedInfo();
}

function updateCircle() {
	const radiusKm = Number(radiusSelect ? radiusSelect.value : 0);
	if (state.circle) {
		state.map.removeLayer(state.circle);
		state.circle = null;
	}
	if (radiusKm > 0) {
		state.circle = L.circle(state.center, {
			radius: radiusKm * 1000,
			color: '#8fd3ff',
			fillColor: '#8fd3ff33',
			weight: 2,
		}).addTo(state.map);
	}
}

function updateSelectedInfo() {
	const radiusKm = Number(radiusSelect ? radiusSelect.value : 0);
	if (radiusValueLabel) {
		radiusValueLabel.textContent = radiusKm === 0 ? 'No preference' : `${radiusKm} km`;
	}
	if (selectedInfo) {
		selectedInfo.innerHTML = `
        <div><strong>Center:</strong> ${state.center.lat.toFixed(4)}, ${state.center.lng.toFixed(4)}</div>
        <div><strong>Radius:</strong> ${radiusKm === 0 ? 'No preference' : radiusKm + ' km'}</div>
        <div><strong>Location filter:</strong> ${useLocationCheckbox && useLocationCheckbox.checked ? 'enabled' : 'disabled'}</div>
    `;
	}
}

function initMap() {
	state.map = L.map('map').setView([state.center.lat, state.center.lng], 7);
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '© OpenStreetMap contributors',
	}).addTo(state.map);
	state.marker = L.marker(state.center, { draggable: true }).addTo(state.map);
	state.marker.on('dragend', () => {
		const pos = state.marker.getLatLng();
		setMapCenter(pos.lat, pos.lng);
	});
	updateCircle();
	updateSelectedInfo();
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition((position) => {
			if (useLocationCheckbox && useLocationCheckbox.checked) {
				setMapCenter(position.coords.latitude, position.coords.longitude);
			}
		});
	}
	fetch(SITES_API_URL)
		.then((res) => res.json())
		.then((data) => {
			state.sites = data;
			addSiteMarkers(state.sites);
		})
		.catch(() => {});
}

function clearMarkers() {
	state.markers.forEach((marker) => state.map.removeLayer(marker));
	state.markers = [];
}

function addSiteMarkers(sites) {
	clearMarkers();
	sites.forEach((site) => {
		if (site.lat && site.lon) {
			const marker = L.marker([site.lat, site.lon]).addTo(state.map);
			marker.bindPopup(`<strong>${site.name}</strong><br>${site.city || ''}`);
			state.markers.push(marker);
		}
	});
}

function switchStep(step) {
	state.currentStep = Math.min(3, Math.max(1, step));
	updateStepper();
}

function renderMessage(type, text, extra = '') {
	const div = document.createElement('div');
	div.className = `${type}-message`;
	div.innerHTML = `<p>${text}</p>${extra}`;
	return div;
}

function renderResults(sites) {
	if (!resultsContainer) return;
	resultsContainer.innerHTML = '';
	if (!sites.length) {
		resultsContainer.appendChild(
			renderMessage('info', 'No camping sites matched your current filters. Try expanding your radius or removing date constraints.'),
		);
		return;
	}

	sites.forEach((site) => {
		const card = document.createElement('div');
		card.className = 'site-card';
		const distance = site.distance_km
			? `<span class="badge bg-info">${site.distance_km} km</span>`
			: '';
		card.innerHTML = `
            <div class="site-card-content" style="padding:16px;">
                <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:8px;">
                    <div>
                        <h3 style="margin:0 0 4px;">${site.name}</h3>
                        <div style="color:var(--text-muted);font-size:0.9rem;">${site.city || 'Unknown location'} · Capacity ${site.capacity || 'N/A'}</div>
                    </div>
                    <div>${distance}</div>
                </div>
                <p style="margin:0 0 12px;color:var(--text-muted);">${site.description || ''}</p>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a href="${CATALOGUE_BASE_URL}/${site.id}" class="site-card-button">View Details</a>
                    <a href="${CATALOGUE_BASE_URL}/${site.id}#book" class="site-card-button">Book Now</a>
                </div>
            </div>
        `;
		resultsContainer.appendChild(card);
	});
}

function getSearchPayload() {
	const start = startDateInput ? startDateInput.value : '';
	const end = endDateInput ? endDateInput.value : '';
	const people = Math.max(1, parseInt(peopleInput ? peopleInput.value : '1', 10) || 1);
	const locationEnabled = useLocationCheckbox ? useLocationCheckbox.checked : false;
	const radiusKm = Number(radiusSelect ? radiusSelect.value : 0);

	const payload = { start, end, people };
	if (locationEnabled && radiusKm > 0) {
		payload.lat = state.center.lat;
		payload.lon = state.center.lng;
		payload.radiusKm = radiusKm;
	}
	return payload;
}

function searchSites() {
	const payload = getSearchPayload();
	const params = new URLSearchParams();
	Object.entries(payload).forEach(([key, value]) => {
		if (value !== '' && value !== null && value !== undefined) {
			params.set(key, value);
		}
	});

	if (resultsContainer) {
		resultsContainer.innerHTML = '<p style="text-align:center;color:var(--text-muted);">Searching...</p>';
	}

	fetch(`${SEARCH_API_URL}?${params.toString()}`)
		.then((res) => res.json())
		.then((data) => {
			state.sites = data;
			renderResults(state.sites);
		})
		.catch(() => {
			if (resultsContainer) {
				resultsContainer.innerHTML = '';
				resultsContainer.appendChild(renderMessage('error', 'Unable to fetch sites. Please try again.'));
			}
		});
}

function bindEvents() {
	stepButtons.forEach((button) => {
		button.addEventListener('click', () => switchStep(Number(button.dataset.step)));
	});
	if (prevButton) prevButton.addEventListener('click', () => switchStep(state.currentStep - 1));
	if (nextButton) nextButton.addEventListener('click', () => switchStep(state.currentStep + 1));
	if (searchButton) searchButton.addEventListener('click', searchSites);
	if (radiusSelect) {
		radiusSelect.addEventListener('input', () => {
			updateCircle();
			updateSelectedInfo();
		});
	}
	if (useLocationCheckbox) {
		useLocationCheckbox.addEventListener('change', () => updateSelectedInfo());
	}
}

window.addEventListener('DOMContentLoaded', () => {
	initMap();
	bindEvents();
	updateStepper();
	updateSelectedInfo();
});
