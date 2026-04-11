// assets/js/main.js — Interactive Portfolio

document.addEventListener('DOMContentLoaded', () => {

    // ======================================================
    // THEME TOGGLE
    // ======================================================
    const themeToggleBtn = document.getElementById('themeToggle');
    const htmlEl = document.documentElement;

    function getPreferredTheme() {
        const saved = localStorage.getItem('theme');
        if (saved) return saved;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    function applyTheme(theme) {
        htmlEl.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (themeToggleBtn) {
            const icon = themeToggleBtn.querySelector('.toggle-icon');
            if (icon) icon.textContent = theme === 'dark' ? '☀️' : '🌙';
        }
    }
    applyTheme(getPreferredTheme());
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            applyTheme(htmlEl.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
        });
    }

    // ======================================================
    // SMOOTH SCROLLING
    // ======================================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            e.preventDefault();
            const target = document.getElementById(anchor.getAttribute('href').substring(1));
            if (target) {
                window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - 80, behavior: 'smooth' });
            }
        });
    });

    // ======================================================
    // SCROLL PROGRESS BAR
    // ======================================================
    const scrollProgress = document.getElementById('scrollProgress');
    function updateScrollProgress() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        if (scrollProgress) scrollProgress.style.width = pct + '%';
    }
    window.addEventListener('scroll', updateScrollProgress, { passive: true });

    // ======================================================
    // NAVBAR SCROLL SHRINK
    // ======================================================
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive: true });

    // ======================================================
    // CUSTOM CURSOR
    // ======================================================
    const cursorDot  = document.getElementById('cursorDot');
    const cursorRing = document.getElementById('cursorRing');

    if (cursorDot && cursorRing && window.matchMedia('(pointer: fine)').matches) {
        let mouseX = 0, mouseY = 0;
        let ringX  = 0, ringY  = 0;

        document.addEventListener('mousemove', e => {
            mouseX = e.clientX; mouseY = e.clientY;
            cursorDot.style.left  = mouseX + 'px';
            cursorDot.style.top   = mouseY + 'px';
        }, { passive: true });

        // Ring lags slightly
        function animateCursor() {
            ringX += (mouseX - ringX) * 0.12;
            ringY += (mouseY - ringY) * 0.12;
            cursorRing.style.left = ringX + 'px';
            cursorRing.style.top  = ringY + 'px';
            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        // Hover state on interactive elements
        const hoverTargets = 'a, button, .card-glass, .skill-item, .filter-btn, .skill-badge, .hero-stat, .footer-social-link, input, textarea';
        document.querySelectorAll(hoverTargets).forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursorDot.classList.add('hovering');
                cursorRing.classList.add('hovering');
            });
            el.addEventListener('mouseleave', () => {
                cursorDot.classList.remove('hovering');
                cursorRing.classList.remove('hovering');
            });
        });

        document.addEventListener('mousedown', () => cursorRing.classList.add('clicking'));
        document.addEventListener('mouseup',   () => cursorRing.classList.remove('clicking'));
    }

    // ======================================================
    // CANVAS PARTICLE NETWORK (HERO)
    // ======================================================
    const canvas = document.getElementById('heroCanvas');
    if (canvas) {
        const ctx    = canvas.getContext('2d');
        const hero   = document.getElementById('hero');
        let W, H, particles = [], mouse = { x: -9999, y: -9999 };
        const COUNT  = 80;
        const LINK_DIST = 130;
        const MOUSE_REPEL = 90;

        function resize() {
            W = canvas.width  = hero.offsetWidth;
            H = canvas.height = hero.offsetHeight;
        }
        resize();
        window.addEventListener('resize', resize, { passive: true });

        hero.addEventListener('mousemove', e => {
            const r = hero.getBoundingClientRect();
            mouse.x = e.clientX - r.left;
            mouse.y = e.clientY - r.top;
        }, { passive: true });
        hero.addEventListener('mouseleave', () => { mouse.x = -9999; mouse.y = -9999; });

        function isDark() { return document.documentElement.getAttribute('data-theme') === 'dark'; }

        class Particle {
            constructor() { this.reset(true); }
            reset(init) {
                this.x  = Math.random() * W;
                this.y  = init ? Math.random() * H : (Math.random() > 0.5 ? -5 : H + 5);
                this.vx = (Math.random() - 0.5) * 0.55;
                this.vy = (Math.random() - 0.5) * 0.55;
                this.r  = Math.random() * 1.8 + 0.8;
                this.opacity = Math.random() * 0.5 + 0.15;
                this.baseVx = this.vx; this.baseVy = this.vy;
            }
            update() {
                // Mouse repulsion
                const dx = this.x - mouse.x, dy = this.y - mouse.y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < MOUSE_REPEL) {
                    const force = (MOUSE_REPEL - dist) / MOUSE_REPEL;
                    this.vx += (dx / dist) * force * 1.4;
                    this.vy += (dy / dist) * force * 1.4;
                }
                // Dampen back to base velocity
                this.vx += (this.baseVx - this.vx) * 0.03;
                this.vy += (this.baseVy - this.vy) * 0.03;

                this.x += this.vx; this.y += this.vy;
                // Wrap
                if (this.x < -10) this.x = W + 10;
                if (this.x > W + 10) this.x = -10;
                if (this.y < -10) this.y = H + 10;
                if (this.y > H + 10) this.y = -10;
            }
            draw() {
                const dark = isDark();
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
                ctx.fillStyle = dark
                    ? `rgba(129,140,248,${this.opacity})`
                    : `rgba(99,102,241,${this.opacity})`;
                ctx.fill();
            }
        }

        for (let i = 0; i < COUNT; i++) particles.push(new Particle());

        function drawConnections() {
            const dark = isDark();
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < LINK_DIST) {
                        const alpha = (1 - dist / LINK_DIST) * 0.35;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.strokeStyle = dark
                            ? `rgba(129,140,248,${alpha})`
                            : `rgba(99,102,241,${alpha})`;
                        ctx.lineWidth = 0.8;
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, W, H);
            drawConnections();
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(animate);
        }
        animate();
    }

    // ======================================================
    // TYPEWRITER EFFECT (hero badge)
    // ======================================================
    const twEl = document.getElementById('typewriter');
    if (twEl) {
        const phrases = [
            'AI & Automation Engineer',
            'Machine Learning Engineer',
            'Deep Learning Specialist',
            'Python & AI Developer',
            'Intelligent Systems Builder'
        ];
        let pi = 0, ci = 0, deleting = false;
        const SPEED_TYPE = 65, SPEED_DEL = 35, PAUSE = 2000;

        function type() {
            const phrase = phrases[pi];
            if (!deleting) {
                twEl.textContent = phrase.slice(0, ++ci);
                if (ci === phrase.length) { deleting = true; setTimeout(type, PAUSE); return; }
            } else {
                twEl.textContent = phrase.slice(0, --ci);
                if (ci === 0) { deleting = false; pi = (pi + 1) % phrases.length; }
            }
            setTimeout(type, deleting ? SPEED_DEL : SPEED_TYPE);
        }
        setTimeout(type, 800);
    }

    // ======================================================
    // MAGNETIC BUTTONS
    // ======================================================
    document.querySelectorAll('.btn-primary-custom, .btn-outline-custom, .btn-hire').forEach(btn => {
        btn.addEventListener('mousemove', e => {
            const rect = btn.getBoundingClientRect();
            const dx = e.clientX - (rect.left + rect.width  / 2);
            const dy = e.clientY - (rect.top  + rect.height / 2);
            btn.style.transform = `translate(${dx * 0.22}px, ${dy * 0.22}px)`;
        });
        btn.addEventListener('mouseleave', () => { btn.style.transform = ''; });
    });

    // ======================================================
    // HERO ORB PARALLAX (mouse tracking)
    // ======================================================
    const heroOrbs  = document.querySelectorAll('.hero-orb');
    const heroSect  = document.getElementById('hero');
    if (heroSect && heroOrbs.length) {
        let mx = 0, my = 0, cx = 0, cy = 0;
        const depths = [0.020, 0.015, 0.011, 0.017, 0.008];
        heroSect.addEventListener('mousemove', e => {
            const r = heroSect.getBoundingClientRect();
            mx = e.clientX - r.left - r.width  / 2;
            my = e.clientY - r.top  - r.height / 2;
        }, { passive: true });
        (function parallaxLoop() {
            cx += (mx - cx) * 0.055; cy += (my - cy) * 0.055;
            heroOrbs.forEach((orb, i) => {
                orb.style.transform = `translate(${cx * (depths[i] || 0.01)}px, ${cy * (depths[i] || 0.01)}px)`;
            });
            requestAnimationFrame(parallaxLoop);
        })();
    }

    // ======================================================
    // SKILLS FILTER
    // ======================================================
    const filterBtns  = document.querySelectorAll('.filter-btn');
    const skillItems  = document.querySelectorAll('.skill-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            skillItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.classList.remove('filtered-out');
                } else {
                    item.classList.add('filtered-out');
                }
            });
        });
    });

    // ======================================================
    // 3D TILT + SPOTLIGHT (combined per-card)
    // ======================================================
    document.querySelectorAll('.card-glass').forEach(card => {
        card.addEventListener('mousemove', e => {
            const r  = card.getBoundingClientRect();
            const px = e.clientX - r.left;   // pixel x in card
            const py = e.clientY - r.top;    // pixel y in card
            const nx = px / r.width  - 0.5;  // normalised -0.5..0.5
            const ny = py / r.height - 0.5;
            // Spotlight CSS vars
            card.style.setProperty('--spotlight-x', px + 'px');
            card.style.setProperty('--spotlight-y', py + 'px');
            // 3D tilt
            card.style.transform =
                `translateY(-10px) scale(1.015) rotateX(${-ny * 6}deg) rotateY(${nx * 6}deg)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
            card.style.setProperty('--spotlight-x', '50%');
            card.style.setProperty('--spotlight-y', '50%');
        });
    });

    // ======================================================
    // SCROLL REVEAL
    // ======================================================
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver(entries => {
            entries.forEach(en => {
                if (en.isIntersecting) { en.target.classList.add('revealed'); obs.unobserve(en.target); }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(el => obs.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('revealed'));
    }

    // ======================================================
    // STAT COUNTER ANIMATION
    // ======================================================
    document.querySelectorAll('.hero-stat strong').forEach(el => {
        const target = parseInt(el.textContent);
        if (isNaN(target)) return;
        let current = 0;
        const obs = new IntersectionObserver(entries => {
            if (!entries[0].isIntersecting) return;
            const step = Math.max(1, Math.floor(target / 28));
            const t = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = current + '+';
                if (current >= target) clearInterval(t);
            }, 40);
            obs.unobserve(el);
        }, { threshold: 0.5 });
        obs.observe(el);
    });

    // ======================================================
    // CONTACT FORM
    // ======================================================
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = contactForm.querySelector('button[type="submit"]');
            const orig = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
            btn.disabled = true;
            fetch('/api/contact', { method: 'POST', body: new FormData(this) })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '✅ Sent Successfully!';
                    btn.style.background = 'linear-gradient(135deg,#10b981,#059669)';
                    contactForm.reset();
                    setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.disabled = false; }, 3500);
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong.'));
                    btn.innerHTML = orig; btn.disabled = false;
                }
            })
            .catch(() => { alert('Network error.'); btn.innerHTML = orig; btn.disabled = false; });
        });
    }

});
