/**
 * Client login — background particles only
 */
(function () {
  'use strict';

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  const canvas = document.getElementById('sv-particles');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  let w, h, particles = [];
  const count = window.innerWidth < 768 ? 28 : 50;

  function resize() {
    w = canvas.width = window.innerWidth;
    h = canvas.height = window.innerHeight;
  }

  function init() {
    particles = [];
    for (let i = 0; i < count; i++) {
      particles.push({
        x: Math.random() * w,
        y: Math.random() * h,
        vx: (Math.random() - 0.5) * 0.3,
        vy: (Math.random() - 0.5) * 0.3,
        r: Math.random() * 1.2 + 0.4,
      });
    }
  }

  function frame() {
    ctx.clearRect(0, 0, w, h);
    const link = 100;

    particles.forEach((p, i) => {
      p.x += p.vx;
      p.y += p.vy;
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = i % 3 === 0 ? 'rgba(249, 115, 22, 0.45)' : 'rgba(37, 99, 235, 0.4)';
      ctx.fill();

      for (let j = i + 1; j < particles.length; j++) {
        const q = particles[j];
        const d = Math.hypot(p.x - q.x, p.y - q.y);
        if (d < link) {
          ctx.strokeStyle = `rgba(37, 99, 235, ${0.08 * (1 - d / link)})`;
          ctx.beginPath();
          ctx.moveTo(p.x, p.y);
          ctx.lineTo(q.x, q.y);
          ctx.stroke();
        }
      }
    });

    requestAnimationFrame(frame);
  }

  resize();
  init();
  window.addEventListener('resize', () => { resize(); init(); });
  frame();
})();
