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