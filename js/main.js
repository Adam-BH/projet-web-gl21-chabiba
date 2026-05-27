const canvas = document.getElementById('starCanvas');
const ctx = canvas.getContext('2d');
let stars = [];
function resize() {
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
}

function initStars(n = canvas.width*canvas.height/1500) {
    stars = Array.from({ length: n }, () => ({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      r: Math.random() * 1.4 + 0.3,
      a: Math.random(),
      speed: Math.random() * 0.4 + 0.1,
      dir: Math.random() > 0.5 ? 1 : -1,
    }));
}

function drawStars() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    stars.forEach(s => {
      s.a += s.speed * s.dir * 0.03;
      if (s.a > 1 || s.a < 0.1) s.dir *= -1;
      ctx.beginPath();
      ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(255,255,255,${s.a})`;
      ctx.fill();
    });
    requestAnimationFrame(drawStars);
}

resize();
initStars();
drawStars();
window.addEventListener('resize', () => { resize(); initStars(); });


/* HIKI Website - Global Interactions */

document.addEventListener("DOMContentLoaded", () => {
    // Navbar hamburger toggle
    const toggler = document.querySelector('.navbar-toggler');
    const collapse = document.querySelector('.navbar-collapse');
    if (toggler && collapse) {
        toggler.addEventListener('click', () => {
            const isOpen = collapse.classList.toggle('is-open');
            toggler.classList.toggle('is-open', isOpen);
            toggler.setAttribute('aria-expanded', isOpen);
        });
        // Close on link click
        collapse.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                collapse.classList.remove('is-open');
                toggler.classList.remove('is-open');
                toggler.setAttribute('aria-expanded', 'false');
            });
        });
    }

    const sparkyMascot = document.querySelector('.sparky-mascot');
    
    // Array of helpful hiking tips from Sparky(better be replaced later by tips that comes from a database that can be fetched using PHP to flex a bit xd)
    const sparkyTips = [
        "Sparky says: Keep your camping area clean!",
        "Sparky says: Always pack an extra pair of socks!",
        "Sparky says: Stargazing is best at 2 AM!",
        "Sparky says: Stay hydrated on the trails!",
        "Sparky says: Don't feed the wild animals!"
    ];

    if (sparkyMascot) {
        sparkyMascot.addEventListener('click', () => {
          // Pick a random tip
          const randomTip = sparkyTips[Math.floor(Math.random() * sparkyTips.length)];
          alert(randomTip);
          
        });
    }

    // ── Dark Mode Toggle ──
    const toggle = document.getElementById('toggle');
    if (toggle) {
        const html = document.documentElement;

        // Restore saved theme or default to dark
        const savedTheme = localStorage.getItem('hiki-theme') || 'dark';
        html.setAttribute('data-bs-theme', savedTheme);
        toggle.checked = savedTheme === 'light';

        toggle.addEventListener('change', () => {
            const theme = toggle.checked ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', theme);
            localStorage.setItem('hiki-theme', theme);
        });
    }
});
