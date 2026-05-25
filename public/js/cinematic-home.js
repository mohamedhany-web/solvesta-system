/**
 * Solvesta Cinematic Homepage — particles, scroll, interactions
 */
(function () {
  'use strict';

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ── Custom cursor ── */
  function initCursor() {
    if (prefersReduced || window.innerWidth < 768) return;
    const ring = document.getElementById('sv-cursor');
    const dot = document.getElementById('sv-cursor-dot');
    if (!ring || !dot) return;

    let mx = 0, my = 0, rx = 0, ry = 0;
    document.addEventListener('mousemove', (e) => {
      mx = e.clientX;
      my = e.clientY;
      dot.style.left = mx + 'px';
      dot.style.top = my + 'px';
    });

    function loop() {
      rx += (mx - rx) * 0.15;
      ry += (my - ry) * 0.15;
      ring.style.left = rx + 'px';
      ring.style.top = ry + 'px';
      requestAnimationFrame(loop);
    }
    loop();

    document.querySelectorAll('a, button, .sv-svc-card, .sv-btn').forEach((el) => {
      el.addEventListener('mouseenter', () => ring.classList.add('is-hover'));
      el.addEventListener('mouseleave', () => ring.classList.remove('is-hover'));
    });
  }

  /* ── Particle network ── */
  function initParticles() {
    const canvas = document.getElementById('sv-particles');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let w, h, particles = [];
    const isMobile = window.innerWidth < 768;
    const count = prefersReduced ? 20 : isMobile ? 35 : 80;
    let mouse = { x: -9999, y: -9999 };

    function resize() {
      w = canvas.width = canvas.offsetWidth;
      h = canvas.height = canvas.offsetHeight;
    }

    function create() {
      particles = [];
      for (let i = 0; i < count; i++) {
        particles.push({
          x: Math.random() * w,
          y: Math.random() * h,
          vx: (Math.random() - 0.5) * 0.4,
          vy: (Math.random() - 0.5) * 0.4,
          r: Math.random() * 1.5 + 0.5,
        });
      }
    }

    canvas.closest('.sv-hero')?.addEventListener('mousemove', (e) => {
      const rect = canvas.getBoundingClientRect();
      mouse.x = e.clientX - rect.left;
      mouse.y = e.clientY - rect.top;
    });

    function draw() {
      ctx.clearRect(0, 0, w, h);
      const linkDist = 120;

      particles.forEach((p, i) => {
        const dx = mouse.x - p.x;
        const dy = mouse.y - p.y;
        const dist = Math.hypot(dx, dy);
        if (dist < 150 && !prefersReduced) {
          p.vx += dx * 0.00008;
          p.vy += dy * 0.00008;
        }
        p.x += p.vx;
        p.y += p.vy;
        if (p.x < 0 || p.x > w) p.vx *= -1;
        if (p.y < 0 || p.y > h) p.vy *= -1;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = i % 3 === 0 ? 'rgba(249, 115, 22, 0.55)' : 'rgba(37, 99, 235, 0.5)';
        ctx.fill();

        for (let j = i + 1; j < particles.length; j++) {
          const p2 = particles[j];
          const d = Math.hypot(p.x - p2.x, p.y - p2.y);
          if (d < linkDist) {
            ctx.strokeStyle = `rgba(37, 99, 235, ${0.12 * (1 - d / linkDist)})`;
            ctx.lineWidth = 0.5;
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p2.x, p2.y);
            ctx.stroke();
          }
        }
      });
      requestAnimationFrame(draw);
    }

    resize();
    create();
    window.addEventListener('resize', () => { resize(); create(); });
    draw();
  }

  /* ── Service card glow follow ── */
  function initServiceGlow() {
    document.querySelectorAll('.sv-svc-card, .sv-module-card').forEach((card) => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--mx', ((e.clientX - rect.left) / rect.width) * 100 + '%');
        card.style.setProperty('--my', ((e.clientY - rect.top) / rect.height) * 100 + '%');
      });
    });
  }

  /* ── Magnetic buttons ── */
  function initMagnetic() {
    if (prefersReduced) return;
    document.querySelectorAll('.sv-btn-magnetic').forEach((btn) => {
      btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        btn.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
      });
      btn.addEventListener('mouseleave', () => {
        btn.style.transform = '';
      });
    });
  }

  /* ── Counter animation ── */
  function animateCounter(el, target, suffix = '') {
    const duration = 2000;
    const start = performance.now();
    const from = 0;
    function tick(now) {
      const p = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - p, 3);
      const val = Math.round(from + (target - from) * eased);
      el.textContent = val + suffix;
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  function initCounters() {
    const obs = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        if (el.dataset.animated) return;
        el.dataset.animated = '1';
        const target = parseFloat(el.dataset.count) || 0;
        const suffix = el.dataset.suffix || '';
        const isFloat = el.dataset.float === '1';
        if (isFloat) {
          let start = performance.now();
          const dur = 2000;
          function f(now) {
            const p = Math.min((now - start) / dur, 1);
            const v = (target * (1 - Math.pow(1 - p, 3))).toFixed(1);
            el.textContent = v + suffix;
            if (p < 1) requestAnimationFrame(f);
          }
          requestAnimationFrame(f);
        } else {
          animateCounter(el, target, suffix);
        }
        document.querySelectorAll('.sv-graph-bar').forEach((bar) => bar.classList.add('is-animated'));
        obs.unobserve(el);
      });
    }, { threshold: 0.3 });
    document.querySelectorAll('[data-count]').forEach((el) => obs.observe(el));
  }

  /* ── Process timeline ── */
  function initProcess() {
    const steps = document.querySelectorAll('.sv-step');
    const fill = document.querySelector('.sv-process-line-fill');
    if (!steps.length) return;

    const obs = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) entry.target.classList.add('is-active');
      });
      const active = [...steps].filter((s) => s.classList.contains('is-active')).length;
      if (fill) fill.style.height = (active / steps.length) * 100 + '%';
    }, { threshold: 0.45, rootMargin: '-8% 0px' });

    steps.forEach((s) => obs.observe(s));
  }

  /* ── Transformation morph on scroll ── */
  function initTransform() {
    const section = document.getElementById('sv-transform');
    if (!section || prefersReduced) return;
    const chaosBars = section.querySelectorAll('.sv-chaos-bar');
    const orderBars = section.querySelectorAll('.sv-order-bar');

    const obs = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        const ratio = entry.intersectionRatio;
        orderBars.forEach((bar, i) => {
          bar.style.width = Math.min(95, 40 + ratio * 60 + i * 5) + '%';
        });
        chaosBars.forEach((bar) => {
          bar.style.opacity = 1 - ratio * 0.7;
        });
      });
    }, { threshold: Array.from({ length: 11 }, (_, i) => i / 10) });

    obs.observe(section);
  }

  /* ── Scroll reveal (always show content) ── */
  function initReveal() {
    document.documentElement.classList.add('sv-js');
    const items = document.querySelectorAll('.sv-reveal');

    if (prefersReduced) {
      items.forEach((el) => el.classList.add('is-visible'));
      return;
    }

    const obs = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -5% 0px' });

    items.forEach((el) => obs.observe(el));

    // Safety: force-visible after 2s if observer missed any
    setTimeout(() => {
      items.forEach((el) => el.classList.add('is-visible'));
    }, 2000);
  }

  /* ── Optional GSAP enhancements (hero only — never hide sections) ── */
  function initGSAP() {
    if (prefersReduced || typeof gsap === 'undefined') return;

    if (typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);
      const hero = document.querySelector('.sv-hero');
      if (hero) {
        gsap.to('.sv-hero-glow--blue', {
          scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 1 },
          y: 60,
          opacity: 0.15,
        });
      }
      const cta = document.querySelector('.sv-cta-final');
      if (cta) {
        ScrollTrigger.create({
          trigger: cta,
          start: 'top 75%',
          onEnter: () => document.querySelector('.sv-burst')?.classList.add('is-active'),
        });
      }
    }

    gsap.from('.sv-hero .sv-eyebrow', { opacity: 0, y: 16, duration: 0.7, delay: 0.15, ease: 'power3.out' });
    gsap.from('.sv-hero-title', { opacity: 0, y: 28, duration: 0.9, delay: 0.3, ease: 'power3.out' });
    gsap.from('.sv-hero-sub', { opacity: 0, y: 20, duration: 0.8, delay: 0.5, ease: 'power3.out' });
    gsap.from('.sv-hero-cta', { opacity: 0, y: 16, duration: 0.7, delay: 0.7, ease: 'power3.out' });

    gsap.utils.toArray('.sv-float-ui').forEach((el, i) => {
      gsap.to(el, { y: '+=12', duration: 3 + i * 0.4, repeat: -1, yoyo: true, ease: 'sine.inOut' });
    });
  }

  /* ── Parallax depth layers ── */
  function initParallax() {
    if (prefersReduced) return;
    document.querySelectorAll('[data-parallax]').forEach((el) => {
      const speed = parseFloat(el.dataset.parallax) || 0.1;
      window.addEventListener('scroll', () => {
        const rect = el.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > window.innerHeight) return;
        const y = (window.innerHeight / 2 - rect.top) * speed;
        el.style.transform = `translateY(${y}px)`;
      }, { passive: true });
    });
  }

  /* ── Hero zoom on scroll ── */
  function initHeroZoom() {
    const hero = document.querySelector('.sv-hero-content');
    if (!hero || prefersReduced) return;
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      const scale = 1 + Math.min(y * 0.0002, 0.08);
      hero.style.transform = `scale(${scale})`;
      hero.style.opacity = 1 - Math.min(y / 600, 0.4);
    }, { passive: true });
  }

  let booted = false;
  function boot() {
    if (booted) return;
    booted = true;
    initReveal();
    initCursor();
    initParticles();
    initServiceGlow();
    initMagnetic();
    initCounters();
    initProcess();
    initTransform();
    initParallax();
    initHeroZoom();
    initGSAP();
  }

  document.addEventListener('DOMContentLoaded', boot);
  window.addEventListener('load', boot);
})();
