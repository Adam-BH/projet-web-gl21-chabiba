(function () {
	function qs(s, el = document) {
		return el.querySelector(s);
	}
	function qsa(s, el = document) {
		return Array.from(el.querySelectorAll(s));
	}

	const modal = qs('#bookingModal');
	const form = qs('#bookingForm');
	const siteIdInput = qs('#bookingSiteId');
	const startInput = qs('#bookingStart');
	const endInput = qs('#bookingEnd');
	const peopleInput = qs('#bookingPeople');
	const availabilityDiv = qs('#bookingAvailability');
	const cancelBtn = qs('#bookingCancel');

	function showModal(siteId, siteName, capacity) {
		siteIdInput.value = siteId;
		qs('#bookingModalTitle').textContent = 'Book: ' + (siteName || 'site');
		modal.style.display = 'flex';
		modal.setAttribute('aria-hidden', 'false');
		// set defaults
		startInput.value = '';
		endInput.value = '';
		peopleInput.value = 1;
		availabilityDiv.textContent = '';
	}

	function hideModal() {
		modal.style.display = 'none';
		modal.setAttribute('aria-hidden', 'true');
	}

	cancelBtn?.addEventListener('click', hideModal);
	modal?.addEventListener('click', function (e) {
		if (e.target === modal) hideModal();
	});

	function updateAvailability() {
		const siteId = siteIdInput.value;
		const start = startInput.value;
		const end = endInput.value;
		if (!siteId || !start || !end) return;
		fetch(
			`/projet-web-gl21-chabiba/pages/catalogue/availability.php?site_id=${encodeURIComponent(siteId)}&start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}`,
		)
			.then((r) => r.json())
			.then((data) => {
				if (data && typeof data.available !== 'undefined') {
					availabilityDiv.textContent = `Available spots: ${data.available} / ${data.capacity}`;
					peopleInput.max = data.available > 0 ? data.available : 1;
					if (Number(peopleInput.value) > data.available)
						peopleInput.value = Math.max(1, data.available);
				}
			})
			.catch((err) => {
				availabilityDiv.textContent = 'Could not check availability';
			});
	}

	startInput?.addEventListener('change', updateAvailability);
	endInput?.addEventListener('change', updateAvailability);
	peopleInput?.addEventListener('change', function () {
		if (Number(this.value) < 1) this.value = 1;
	});

	// Attach handlers to all 'Book Now' buttons rendered on page
	function attachButtons() {
		qsa('.site-card-button').forEach((btn) => {
			if (!btn.classList.contains('book-now-btn')) return;
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				const article = this.closest('.site-card');
				const siteId =
					this.dataset.siteId ||
					(article ? article.dataset.id : null) ||
					this.getAttribute('data-site-id');
				const name = article ? article.dataset.name : this.dataset.siteName;
				showModal(siteId, name);
			});
		});
	}

	// initial attach
	document.addEventListener('DOMContentLoaded', attachButtons);
	// Also in case dynamic content loaded later
	window.addEventListener('load', attachButtons);

	// Re-check availability on submit to avoid race conditions
	form?.addEventListener('submit', function (e) {
		e.preventDefault();
		const siteId = siteIdInput.value;
		const start = startInput.value;
		const end = endInput.value;
		const people = Number(peopleInput.value) || 1;
		if (!siteId || !start || !end) {
			alert('Please choose dates');
			return;
		}
		fetch(
			`/projet-web-gl21-chabiba/pages/catalogue/availability.php?site_id=${encodeURIComponent(siteId)}&start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}`,
		)
			.then((r) => r.json())
			.then((data) => {
				const avail = data.available ?? 0;
				if (people > avail) {
					alert('Not enough capacity for selected dates. Available: ' + avail);
					updateAvailability();
					return;
				}
				// submit the form normally
				form.submit();
			})
			.catch((err) => {
				alert('Could not verify availability');
			});
	});
})();
