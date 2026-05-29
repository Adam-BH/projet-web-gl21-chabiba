const state = {
	currentStep: 1,
	map: null,
	marker: null,
	circle: null,
	center: { lat: 45.0, lng: 3.0 },
	sites: [],
	markers: [],
	searchPerformed: false,
};

const stepButtons = document.querySelectorAll('.step-btn');
const steps = document.querySelectorAll('.step');
const prevButton = document.getElementById('prev');
const nextButton = document.getElementById('next');
const searchButton = document.getElementById('search');
const resultsContainer = document.getElementById('results');
const searchPlaceholder = document.getElementById('searchPlaceholder');
const useLocationCheckbox = document.getElementById('use-location');
const radiusSelect = document.getElementById('radius');
const radiusValueLabel = document.getElementById('radiusValue');
const selectedInfo = document.getElementById('selected-info');
const startDateInput = document.getElementById('start-date');
const endDateInput = document.getElementById('end-date');
const peopleInput = document.getElementById('people');

function updateStepper() {
	stepButtons.forEach((btn) => {
		btn.classList.toggle(
			'active',
			parseInt(btn.dataset.step, 10) === state.currentStep,
		);
		btn.classList.toggle(
			'btn-primary',
			parseInt(btn.dataset.step, 10) === state.currentStep,
		);
		btn.classList.toggle(
			'btn-outline-secondary',
			parseInt(btn.dataset.step, 10) !== state.currentStep,
		);
	});
	steps.forEach((step, index) => {
		step.style.display = state.currentStep === index + 1 ? 'block' : 'none';
	});
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
	const radiusKm = Number(radiusSelect.value);
	if (state.circle) {
		state.map.removeLayer(state.circle);
		state.circle = null;
	}
	if (radiusKm > 0) {
		state.circle = L.circle(state.center, {
			radius: radiusKm * 1000,
			color: '#198754',
			fillColor: '#0d6efd33',
			weight: 2,
		}).addTo(state.map);
	}
}

function updateSelectedInfo() {
	const radiusKm = Number(radiusSelect.value);
	radiusValueLabel.textContent =
		radiusKm === 0 ? 'No preference' : `${radiusKm} km`;
	selectedInfo.innerHTML = `
        <div><strong>Center:</strong> ${state.center.lat.toFixed(4)}, ${state.center.lng.toFixed(4)}</div>
        <div><strong>Radius:</strong> ${radiusKm === 0 ? 'No preference' : radiusKm + ' km'}</div>
        <div><strong>Location filter:</strong> ${useLocationCheckbox.checked ? 'enabled' : 'disabled'}</div>
    `;
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
			if (useLocationCheckbox.checked) {
				setMapCenter(position.coords.latitude, position.coords.longitude);
			}
		});
	}
	fetch('/projet-web-gl21-chabiba/search-engine/api/sites.php')
		.then((res) => res.json())
		.then((data) => {
			state.sites = data;
			addSiteMarkers(state.sites);
		});
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
	resultsContainer.innerHTML = '';
	if (!sites.length) {
		resultsContainer.appendChild(
			renderMessage(
				'info',
				'No camping sites matched your current filters. Try expanding your radius or removing date constraints.',
			),
		);
		return;
	}

	sites.forEach((site) => {
		const card = document.createElement('div');
		card.className = 'site-card';
		const distance = site.distance_km
			? `<span class="badge bg-info">${site.distance_km} km</span>`
			: '';
		const availableLabel =
			site.available !== undefined
				? `<span class="badge bg-success">Available: ${site.available}</span>`
				: '';
		card.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3>${site.name}</h3>
                    <div class="meta">${site.city || 'Unknown location'} · Capacity ${site.capacity || 'N/A'}</div>
                </div>
                <div>${distance}${availableLabel}</div>
            </div>
            <p>${site.description || ''}</p>
            <div class="result-actions">
                <button class="btn btn-outline-primary btn-sm" type="button" data-site-id="${site.id}">View details</button>
                <button class="btn btn-success btn-sm book-now-button" type="button" data-site-id="${site.id}">Book now</button>
            </div>
        `;
		resultsContainer.appendChild(card);
	});

	resultsContainer.querySelectorAll('.book-now-button').forEach((button) => {
		button.addEventListener('click', () =>
			handleBookClick(Number(button.dataset.siteId)),
		);
	});
	resultsContainer
		.querySelectorAll('.btn-outline-primary')
		.forEach((button) => {
			button.addEventListener('click', () => {
				window.location.href = `/projet-web-gl21-chabiba/pages/catalogue/details.php?id=${button.dataset.siteId}`;
			});
		});
}

function getSearchPayload() {
	const start = startDateInput.value || '';
	const end = endDateInput.value || '';
	const people = Math.max(1, parseInt(peopleInput.value, 10) || 1);
	const locationEnabled = useLocationCheckbox.checked;
	const radiusKm = Number(radiusSelect.value);

	const payload = {
		start,
		end,
		people,
	};
	if (locationEnabled && radiusKm > 0) {
		payload.lat = state.center.lat;
		payload.lon = state.center.lng;
		payload.radiusKm = radiusKm;
	}
	return payload;
}

function handleBookClick(siteId) {
	const start = startDateInput.value;
	const end = endDateInput.value;
	const people = Math.max(1, parseInt(peopleInput.value, 10) || 1);
	const requiredMissing = [];
	if (!start) requiredMissing.push('start date');
	if (!end) requiredMissing.push('end date');
	if (!people) requiredMissing.push('number of people');
	if (requiredMissing.length) {
		const message = `Please complete the following before booking: ${requiredMissing.join(', ')}.`;
		resultsContainer.prepend(renderMessage('error', message));
		switchStep(2);
		return;
	}
	if (new Date(end) < new Date(start)) {
		resultsContainer.prepend(
			renderMessage('error', 'End date must be after start date.'),
		);
		switchStep(2);
		return;
	}

	const formData = new FormData();
	formData.append('site_id', siteId);
	formData.append('start', start);
	formData.append('end', end);
	formData.append('people', people);

	fetch('/projet-web-gl21-chabiba/search-engine/api/book.php', {
		method: 'POST',
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			if (data.needLogin) {
				window.location.href = '/projet-web-gl21-chabiba/pages/auth/login.php';
				return;
			}
			if (data.success) {
				resultsContainer.innerHTML = '';
				resultsContainer.appendChild(
					renderMessage(
						'success',
						'Booking confirmed! Your camping site has been reserved.',
						`<p><a class="btn btn-sm btn-outline-secondary" href="/projet-web-gl21-chabiba/pages/equipment.html">Need equipments? Browse equipment</a></p>`,
					),
				);
			} else {
				const error = data.error || 'Could not complete booking.';
				resultsContainer.prepend(renderMessage('error', error));
			}
		})
		.catch(() => {
			resultsContainer.prepend(
				renderMessage('error', 'Network error. Please try again.'),
			);
		});
}

function showPlaceholder() {
	resultsContainer.style.display = 'none';
	searchPlaceholder.style.display = 'block';
}

function hidePlaceholder() {
	searchPlaceholder.style.display = 'none';
	resultsContainer.style.display = 'block';
}

function searchSites() {
	state.searchPerformed = true;
	hidePlaceholder();
	const payload = getSearchPayload();
	const params = new URLSearchParams();
	Object.entries(payload).forEach(([key, value]) => {
		if (value !== '' && value !== null && value !== undefined) {
			params.set(key, value);
		}
	});

	fetch(
		`/projet-web-gl21-chabiba/search-engine/api/search.php?${params.toString()}`,
	)
		.then((res) => res.json())
		.then((data) => {
			state.sites = data;
			renderResults(state.sites);
		})
		.catch(() => {
			resultsContainer.innerHTML = '';
			resultsContainer.appendChild(
				renderMessage('error', 'Unable to fetch sites. Please try again.'),
			);
			hidePlaceholder();
		});
}

function bindEvents() {
	stepButtons.forEach((button) => {
		button.addEventListener('click', () =>
			switchStep(Number(button.dataset.step)),
		);
	});
	prevButton.addEventListener('click', () => switchStep(state.currentStep - 1));
	nextButton.addEventListener('click', () => switchStep(state.currentStep + 1));
	searchButton.addEventListener('click', searchSites);
	radiusSelect.addEventListener('input', () => {
		updateCircle();
		updateSelectedInfo();
	});
	useLocationCheckbox.addEventListener('change', () => {
		updateSelectedInfo();
	});
}

window.addEventListener('DOMContentLoaded', () => {
	initMap();
	bindEvents();
	updateStepper();
	updateSelectedInfo();
	showPlaceholder();
});
