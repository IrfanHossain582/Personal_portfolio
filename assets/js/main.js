// assets/js/main.js

document.addEventListener("DOMContentLoaded", () => {

    // ===== THEME TOGGLE =====
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
        updateToggleIcon(theme);
    }

    function updateToggleIcon(theme) {
        if (!themeToggleBtn) return;
        const icon = themeToggleBtn.querySelector('.toggle-icon');
        if (theme === 'dark') {
            if (icon) icon.textContent = '☀️';
            themeToggleBtn.setAttribute('aria-label', 'Switch to light mode');
        } else {
            if (icon) icon.textContent = '🌙';
            themeToggleBtn.setAttribute('aria-label', 'Switch to dark mode');
        }
    }

    applyTheme(getPreferredTheme());

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const current = htmlEl.getAttribute('data-theme');
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    // ===== SMOOTH SCROLLING =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                const offset = 80;
                const top = targetEl.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });

    // ===== NAVBAR SCROLL SHRINK =====
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive: true });

    // ===== HERO PARALLAX — ORB MOUSE TRACKING =====
    const heroOrbs = document.querySelectorAll('.hero-orb');
    const heroSection = document.getElementById('hero');

    if (heroSection && heroOrbs.length > 0) {
        let mouseX = 0, mouseY = 0;
        let currentX = 0, currentY = 0;
        const depths = [0.022, 0.016, 0.012, 0.018, 0.009];

        heroSection.addEventListener('mousemove', (e) => {
            const rect = heroSection.getBoundingClientRect();
            mouseX = (e.clientX - rect.left - rect.width  / 2);
            mouseY = (e.clientY - rect.top  - rect.height / 2);
        }, { passive: true });

        function animateParallax() {
            currentX += (mouseX - currentX) * 0.06;
            currentY += (mouseY - currentY) * 0.06;
            heroOrbs.forEach((orb, i) => {
                const d = depths[i] || 0.01;
                orb.style.transform = `translate(${currentX * d}px, ${currentY * d}px)`;
            });
            requestAnimationFrame(animateParallax);
        }
        animateParallax();
    }

    // ===== SCROLL REVEAL ANIMATION (with stagger by index) =====
    const revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach(el => observer.observe(el));
    } else {
        revealEls.forEach(el => el.classList.add('revealed'));
    }

    // ===== CARD TILT EFFECT =====
    document.querySelectorAll('.card-glass').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width  - 0.5;
            const y = (e.clientY - rect.top)  / rect.height - 0.5;
            card.style.transform = `translateY(-10px) scale(1.01) rotateX(${-y * 5}deg) rotateY(${x * 5}deg)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    // ===== CONTACT FORM =====
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
            submitBtn.disabled = true;

            const formData = new FormData(this);
            fetch('/api/contact', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    submitBtn.innerHTML = '✅ Sent Successfully!';
                    submitBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                    contactForm.reset();
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                        submitBtn.disabled = false;
                    }, 3500);
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong.'));
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(() => {
                alert('Network error. Please try again.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // ===== HERO STAT COUNTER ANIMATION =====
    const stats = document.querySelectorAll('.hero-stat strong');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = parseInt(el.textContent);
            if (isNaN(target)) return;
            let current = 0;
            const step = Math.max(1, Math.floor(target / 30));
            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = current + '+';
                if (current >= target) clearInterval(timer);
            }, 40);
            counterObserver.unobserve(el);
        });
    }, { threshold: 0.5 });
    stats.forEach(s => counterObserver.observe(s));

});
