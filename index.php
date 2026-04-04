<?php
// index.php
require_once 'config/database.php';

$profiles = supabase_fetch('profile');
$profile = !empty($profiles) ? $profiles[0] : [
    'name' => 'Irfan Hossain',
    'title' => 'AI & Automation Engineer',
    'bio' => 'AI Engineer specializing in Machine Learning, Deep Learning, and Intelligent Automation systems.',
    'email' => 'irfanhossain582@gmail.com',
    'github_url' => 'https://github.com/IrfanHossain582',
    'linkedin_url' => 'https://www.linkedin.com/in/irfanhossain582/',
    'portfolio_url' => 'https://www.behance.net/irfan_hossain',
    'profile_image' => 'assets/img/profile.jpg'
];

$skills = supabase_fetch('skills', 'skill_name,skill_category');
$grouped_skills = [];
foreach ($skills as $skill) {
    $grouped_skills[$skill['skill_category']][] = $skill['skill_name'];
}

$projects = supabase_fetch('projects');
$coding_projects = [];
$ui_projects = [];
foreach ($projects as $proj) {
    if (strpos(strtolower($proj['technology_used'] ?? ''), 'ui/ux') !== false) {
        $ui_projects[] = $proj;
    } else {
        $coding_projects[] = $proj;
    }
}

$education = supabase_fetch('education');
$experience = supabase_fetch('experience');
$achievements = supabase_fetch('achievements');

// Skill Category Icons
$category_icons = [
    'Programming & AI' => '🤖',
    'Database' => '🗄️',
    'Development' => '💻',
    'Tools' => '🛠️',
    'UI/UX' => '🎨',
    'Design Tools' => '✏️',
];
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($profile['name']) ?> — <?= htmlspecialchars($profile['title']) ?></title>
    <meta name="description" content="Portfolio of <?= htmlspecialchars($profile['name']) ?>, <?= htmlspecialchars($profile['title']) ?>. Specializing in Machine Learning, Deep Learning, and Automation systems.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Prevent flash of wrong theme -->
    <script>
        (function() {
            var t = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    <style>
        /* Reveal Animation */
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.revealed { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .navbar.scrolled { padding: 8px 0 !important; }
    </style>
</head>
<body>

<!-- ========== NAVIGATION ========== -->
<nav id="navbar" class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <?php if (!empty($profile['logo_url'])): ?>
                <img src="<?= htmlspecialchars($profile['logo_url']) ?>" alt="Logo" style="height: 32px; width: auto; object-fit: contain;">
            <?php else: ?>
                <?= htmlspecialchars(explode(' ', $profile['name'])[0]) ?>.
            <?php endif; ?>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navLinks">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#skills">Skills</a></li>
                <li class="nav-item"><a class="nav-link" href="#projects">Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="#education">Education</a></li>
                <li class="nav-item"><a class="nav-link" href="#experience">Experience</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <!-- Theme Toggle -->
                <button id="themeToggle" class="theme-toggle" aria-label="Switch theme">
                    <span class="toggle-icon">🌙</span>
                </button>
                <!-- Hire Me -->
                <a href="#contact" class="btn-hire text-decoration-none">Hire Me</a>
            </div>
        </div>
    </div>
</nav>

<!-- ========== HERO SECTION ========== -->
<section id="hero" class="hero">

    <!-- Animated Background -->
    <div class="hero-bg">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>
        <div class="hero-orb hero-orb-4"></div>
    </div>
    <div class="hero-grid"></div>
    <div class="hero-dots"></div>

    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <div class="hero-badge reveal">
                    AI & Automation Engineer
                </div>
                <h1 class="reveal reveal-delay-1">
                    Turning Ideas Into<br>
                    <span class="hero-name-gradient">Intelligent Systems</span>
                </h1>
                <p class="hero-subtitle reveal reveal-delay-2">
                    <?= htmlspecialchars($profile['bio'] ?? '') ?>
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start reveal reveal-delay-3">
                    <a href="#projects" class="btn-primary-custom">View My Work →</a>
                    <a href="<?= htmlspecialchars($profile['linkedin_url'] ?? '#') ?>" target="_blank" class="btn-outline-custom">LinkedIn ↗</a>
                </div>
            </div>
            <div class="col-lg-5 text-center reveal">
                <img
                    src="<?= !empty($profile['profile_image']) ? htmlspecialchars($profile['profile_image']) : 'assets/img/profile.jpg' ?>"
                    alt="<?= htmlspecialchars($profile['name'] ?? '') ?>"
                    class="profile-img"
                >
            </div>
        </div>
    </div>
</section>

<!-- ========== ABOUT SECTION ========== -->
<section id="about" class="section-alt py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-tag reveal">About</span>
            <h2 class="section-title reveal">Who I <span>Am</span></h2>
            <div class="section-divider reveal"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <p class="lead reveal" style="color: var(--text-secondary); font-size: 1.1rem; line-height: 1.9;">
                    <?= nl2br(htmlspecialchars($profile['bio'] ?? '')) ?>
                </p>
                <div class="d-flex justify-content-center gap-4 mt-5 flex-wrap reveal">
                    <?php if (!empty($profile['github_url'])): ?>
                    <a href="<?= htmlspecialchars($profile['github_url']) ?>" target="_blank" class="btn-outline-custom" style="padding: 8px 20px; font-size: 0.88rem;">
                        GitHub ↗
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($profile['linkedin_url'])): ?>
                    <a href="<?= htmlspecialchars($profile['linkedin_url']) ?>" target="_blank" class="btn-outline-custom" style="padding: 8px 20px; font-size: 0.88rem;">
                        LinkedIn ↗
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($profile['portfolio_url'])): ?>
                    <a href="<?= htmlspecialchars($profile['portfolio_url']) ?>" target="_blank" class="btn-outline-custom" style="padding: 8px 20px; font-size: 0.88rem;">
                        Behance ↗
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== SKILLS SECTION ========== -->
<section id="skills" class="section-base py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-tag reveal">Capabilities</span>
            <h2 class="section-title reveal">My <span>Skills</span></h2>
            <div class="section-divider reveal"></div>
        </div>
        <div class="row g-4">
            <?php foreach ($grouped_skills as $category => $skill_list): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card-glass h-100 p-4 reveal">
                    <div class="card-accent"></div>
                    <div class="p-2">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span style="font-size: 1.4rem;"><?= $category_icons[$category] ?? '💡' ?></span>
                            <h5 class="fw-700 mb-0" style="color: var(--text-primary); font-weight: 700; font-size: 1rem;"><?= htmlspecialchars($category) ?></h5>
                        </div>
                        <div>
                            <?php foreach ($skill_list as $skill): ?>
                                <span class="skill-badge"><?= htmlspecialchars($skill) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== PROJECTS SECTION ========== -->
<section id="projects" class="section-alt py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-tag reveal">Portfolio</span>
            <h2 class="section-title reveal">AI & Dev <span>Projects</span></h2>
            <div class="section-divider reveal"></div>
        </div>
        <div class="row g-4">
            <?php foreach ($coding_projects as $i => $proj): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card-glass h-100 p-4 reveal reveal-delay-<?= ($i % 3) + 1 ?>">
                    <div class="card-accent"></div>
                    <div class="p-2">
                        <span class="skill-badge mb-3 d-inline-block"><?= htmlspecialchars($proj['technology_used'] ?? 'AI') ?></span>
                        <h5 class="fw-bold mt-2 mb-2" style="color: var(--text-primary);"><?= htmlspecialchars($proj['project_name']) ?></h5>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;"><?= htmlspecialchars($proj['description'] ?? '') ?></p>
                        <?php if (!empty($proj['live_demo_link'])): ?>
                        <a href="<?= htmlspecialchars($proj['live_demo_link']) ?>" target="_blank" class="btn-outline-custom mt-2" style="padding: 7px 18px; font-size: 0.82rem;">
                            View Demo ↗
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($ui_projects)): ?>
        <div class="text-center mt-5 pt-4 mb-5">
            <span class="section-tag reveal">Design</span>
            <h2 class="section-title reveal">UI/UX <span>Projects</span></h2>
            <div class="section-divider reveal"></div>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($ui_projects as $proj): ?>
            <div class="col-md-6">
                <div class="card-glass p-4 text-center reveal">
                    <div class="card-accent"></div>
                    <div class="p-3">
                        <span style="font-size: 2rem;">🎨</span>
                        <h5 class="fw-bold mt-3 mb-2" style="color: var(--text-primary);"><?= htmlspecialchars($proj['project_name']) ?></h5>
                        <p style="color: var(--text-secondary); font-size: 0.9rem;"><?= htmlspecialchars($proj['description'] ?? '') ?></p>
                        <?php if (!empty($proj['live_demo_link'])): ?>
                        <a href="<?= htmlspecialchars($proj['live_demo_link']) ?>" target="_blank" class="btn-primary-custom mt-2" style="font-size: 0.88rem; padding: 10px 24px;">
                            View on Behance ↗
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========== EDUCATION & ACHIEVEMENTS ========== -->
<section id="education" class="section-base py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-tag reveal">Background</span>
            <h2 class="section-title reveal">Education & <span>Achievements</span></h2>
            <div class="section-divider reveal"></div>
        </div>
        <div class="row g-5">
            <!-- Education -->
            <div class="col-lg-6">
                <div class="card-glass p-4 h-100 reveal">
                    <div class="card-accent"></div>
                    <div class="p-3">
                        <h4 class="fw-bold mb-4" style="color: var(--text-primary);">🎓 Education</h4>
                        <div class="timeline">
                            <?php foreach ($education as $edu): ?>
                            <div class="timeline-item">
                                <span class="timeline-label"><?= htmlspecialchars($edu['end_year']) ?></span>
                                <h5 class="fw-bold mb-1" style="color: var(--text-primary);"><?= htmlspecialchars($edu['degree']) ?></h5>
                                <p style="color: var(--accent-1); font-weight: 600; font-size: 0.9rem; margin-bottom: 4px;"><?= htmlspecialchars($edu['institution_name']) ?></p>
                                <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">CGPA: <?= htmlspecialchars($edu['cgpa']) ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Achievements -->
            <div class="col-lg-6">
                <div class="card-glass p-4 h-100 reveal reveal-delay-1">
                    <div class="card-accent"></div>
                    <div class="p-3">
                        <h4 class="fw-bold mb-4" style="color: var(--text-primary);">🏆 Achievements</h4>
                        <?php foreach ($achievements as $ach): ?>
                        <div class="mb-4 pb-4" style="border-bottom: 1px solid var(--border-color);">
                            <div class="d-flex align-items-start gap-3">
                                <span style="font-size: 1.5rem; min-width: 36px;">⭐</span>
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: var(--text-primary);"><?= htmlspecialchars($ach['title']) ?></h6>
                                    <p style="color: var(--text-secondary); font-size: 0.87rem; margin: 0;"><?= htmlspecialchars($ach['description']) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== EXPERIENCE SECTION ========== -->
<section id="experience" class="section-alt py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-tag reveal">Work</span>
            <h2 class="section-title reveal">My <span>Experience</span></h2>
            <div class="section-divider reveal"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-glass p-4 reveal">
                    <div class="card-accent"></div>
                    <div class="p-3">
                        <div class="timeline">
                            <?php foreach ($experience as $exp): ?>
                            <div class="timeline-item">
                                <h5 class="fw-bold mb-1" style="color: var(--text-primary);"><?= htmlspecialchars($exp['company_name']) ?></h5>
                                <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0;"><?= htmlspecialchars($exp['description']) ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== CONTACT SECTION ========== -->
<section id="contact" class="section-base py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-tag reveal">Contact</span>
            <h2 class="section-title reveal">Get In <span>Touch</span></h2>
            <div class="section-divider reveal"></div>
            <p class="reveal" style="color: var(--text-secondary); max-width: 480px; margin: 0 auto;">Have a project in mind or want to collaborate? I'd love to hear from you!</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card-glass p-5 reveal">
                    <div class="card-accent"></div>
                    <div class="p-2">
                        <form id="contactForm">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Your name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Tell me about your project..." required></textarea>
                            </div>
                            <button type="submit" class="btn-primary-custom w-100 justify-content-center" style="border-radius: 12px;">Send Message →</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="footer-section py-5">
    <div class="container text-center">
        <a class="navbar-brand d-inline-block mb-3" href="#" style="background: linear-gradient(135deg, #818cf8, #a78bfa); background-clip:text; -webkit-background-clip:text; -webkit-text-fill-color:transparent; font-size: 1.6rem; font-weight: 900; text-decoration: none;">
            <?= htmlspecialchars(explode(' ', $profile['name'])[0]) ?>.
        </a>
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 24px;"><?= htmlspecialchars($profile['title'] ?? '') ?></p>
        <div class="d-flex justify-content-center gap-4 mb-4 flex-wrap">
            <?php if (!empty($profile['github_url'])): ?>
            <a href="<?= htmlspecialchars($profile['github_url']) ?>" target="_blank">GitHub</a>
            <?php endif; ?>
            <?php if (!empty($profile['linkedin_url'])): ?>
            <a href="<?= htmlspecialchars($profile['linkedin_url']) ?>" target="_blank">LinkedIn</a>
            <?php endif; ?>
            <?php if (!empty($profile['portfolio_url'])): ?>
            <a href="<?= htmlspecialchars($profile['portfolio_url']) ?>" target="_blank">Behance</a>
            <?php endif; ?>
            <?php if (!empty($profile['email'])): ?>
            <a href="mailto:<?= htmlspecialchars($profile['email']) ?>">Email</a>
            <?php endif; ?>
        </div>
        <p style="color: #334155; font-size: 0.8rem;">© <?= date('Y') ?> <?= htmlspecialchars($profile['name']) ?>. Built with ❤️ & PHP.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
