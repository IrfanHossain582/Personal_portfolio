<?php
// index.php
require_once __DIR__ . '/../config/database.php';

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
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Prevent flash of wrong theme -->
    <script>
        (function() {
            var t = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
</head>
<body>

<!-- Scroll progress, cursor -->
<div id="scrollProgress"></div>
<div id="cursorDot" class="cursor-dot"></div>
<div id="cursorRing" class="cursor-ring"></div>

<!-- ========== NAVIGATION ========== -->
<nav id="navbar" class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <?php if (!empty($profile['logo_url'])): ?>
                <img src="<?= htmlspecialchars($profile['logo_url']) ?>" alt="Logo" style="height: 32px; width: auto; object-fit: contain;">
            <?php else: ?>
                <?= htmlspecialchars(explode(' ', $profile['name'] ?? 'Portfolio')[0]) ?>.
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

    <!-- Particle Canvas -->
    <canvas id="heroCanvas" class="hero-canvas"></canvas>

    <!-- Animated Background -->
    <div class="hero-bg">
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>
        <div class="hero-orb hero-orb-4"></div>
        <div class="hero-orb hero-orb-5"></div>
    </div>
    <div class="hero-grid"></div>
    <div class="hero-dots"></div>

    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <div class="hero-badge reveal">
                    <span class="pulse-dot"></span>
                    <span id="typewriter">AI &amp; Automation Engineer</span><span class="tw-cursor"></span>
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
                <!-- Hero Stats -->
                <div class="hero-stats reveal reveal-delay-3">
                    <div class="hero-stat"><strong><?= count($coding_projects) ?>+</strong> Projects</div>
                    <div class="hero-stat"><strong><?= count($skills) ?>+</strong> Skills</div>
                    <div class="hero-stat"><strong><?= count($experience) ?>+</strong> Experiences</div>
                </div>
            </div>
            <div class="col-lg-5 text-center reveal">
                <?php if (!empty($profile['profile_image'])): ?>
                    <div class="profile-img-wrapper d-inline-block position-relative">
                        <div class="profile-img-glow profile-img-glow-1"></div>
                        <div class="profile-img-glow profile-img-glow-2"></div>
                        <div class="profile-img-ring"></div>
                        <img src="<?= htmlspecialchars($profile['profile_image']) ?>"
                             alt="<?= htmlspecialchars($profile['name']) ?>"
                             class="profile-img">
                    </div>
                <?php else: ?>
                    <div class="profile-img d-flex align-items-center justify-content-center mx-auto"
                         style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;font-size:4rem;font-weight:800;border-radius:30px;aspect-ratio:1/1;">
                         <?php
                         $initial = !empty($profile['name']) ? strtoupper(substr($profile['name'], 0, 1)) : 'I';
                         echo htmlspecialchars($initial);
                         ?>
                    </div>
                <?php endif; ?>
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

        <!-- Filter Buttons -->
        <div class="skills-filter reveal">
            <button class="filter-btn active" data-filter="all"><span>✦ All Skills</span></button>
            <?php foreach (array_keys($grouped_skills) as $cat): ?>
            <button class="filter-btn" data-filter="<?= htmlspecialchars($cat) ?>"><span><?= $category_icons[$cat] ?? '💡' ?> <?= htmlspecialchars($cat) ?></span></button>
            <?php endforeach; ?>
        </div>

        <!-- Flat Skills Cloud -->
        <div class="skills-cloud reveal">
            <?php foreach ($grouped_skills as $category => $skill_list): ?>
                <?php foreach ($skill_list as $skill): ?>
                <div class="skill-item" data-category="<?= htmlspecialchars($category) ?>">
                    <span class="skill-cat-icon"><?= $category_icons[$category] ?? '💡' ?></span>
                    <span><?= htmlspecialchars($skill) ?></span>
                </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== PROJECTS SECTION ========== -->
<section id="projects" class="section-alt py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="section-tag reveal">Portfolio</span>
            <h2 class="section-title reveal">AI &amp; Dev <span>Projects</span></h2>
            <div class="section-divider reveal"></div>
        </div>
        <div class="row g-4">
            <?php foreach ($coding_projects as $i => $proj): ?>
            <div class="col-md-6 col-lg-4">
                <div class="modern-card project-card reveal reveal-delay-<?= ($i % 3) + 1 ?>">
                    <div class="project-tech-tag">
                        <?= htmlspecialchars($proj['technology_used'] ?? 'AI') ?>
                    </div>
                    <span class="project-title"><?= htmlspecialchars($proj['project_name']) ?></span>
                    <p class="project-desc"><?= htmlspecialchars($proj['description'] ?? '') ?></p>
                    <?php if (!empty($proj['live_demo_link'])): ?>
                    <div class="mt-auto">
                        <a href="<?= htmlspecialchars($proj['live_demo_link']) ?>" target="_blank" class="project-link">
                            View Project <span class="arrow">&#8594;</span>
                        </a>
                    </div>
                    <?php endif; ?>
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
            <?php foreach ($ui_projects as $i => $proj): ?>
            <div class="col-md-6">
                <div class="modern-card project-card reveal">
                    <div class="project-tech-tag">UI/UX Design</div>
                    <span class="project-title"><?= htmlspecialchars($proj['project_name']) ?></span>
                    <p class="project-desc"><?= htmlspecialchars($proj['description'] ?? '') ?></p>
                    <?php if (!empty($proj['live_demo_link'])): ?>
                    <div class="mt-auto">
                        <a href="<?= htmlspecialchars($proj['live_demo_link']) ?>" target="_blank" class="project-link">
                            View on Behance <span class="arrow">&#8594;</span>
                        </a>
                    </div>
                    <?php endif; ?>
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
            <h2 class="section-title reveal">Education &amp; <span>Achievements</span></h2>
            <div class="section-divider reveal"></div>
        </div>
        <div class="row g-5">
            <!-- Education -->
            <div class="col-lg-6">
                <div class="modern-card h-100 reveal">
                    <div class="section-body">
                        <div class="section-card-header">
                            <div class="section-card-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                            </div>
                            <h4 class="section-card-title">Education</h4>
                        </div>
                        <div>
                            <?php foreach ($education as $edu): ?>
                            <div class="list-item-clean">
                                <span class="item-meta-clean"><?= htmlspecialchars($edu['end_year']) ?></span>
                                <span class="item-title-clean"><?= htmlspecialchars($edu['degree']) ?></span>
                                <p class="item-subtitle-clean"><?= htmlspecialchars($edu['institution_name']) ?> &bull; CGPA: <?= htmlspecialchars($edu['cgpa']) ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Achievements -->
            <div class="col-lg-6">
                <div class="modern-card h-100 reveal reveal-delay-1">
                    <div class="section-body">
                        <div class="section-card-header">
                            <div class="section-card-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <h4 class="section-card-title">Achievements</h4>
                        </div>
                        <div>
                            <?php foreach ($achievements as $ach): ?>
                            <div class="list-item-clean">
                                <span class="item-title-clean"><?= htmlspecialchars($ach['title']) ?></span>
                                <p class="item-subtitle-clean"><?= htmlspecialchars($ach['description']) ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
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
                <div class="modern-card reveal">
                    <div class="section-body">
                        <div class="section-card-header">
                            <div class="section-card-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <h4 class="section-card-title">Work History</h4>
                        </div>
                        <div>
                            <?php foreach ($experience as $exp): ?>
                            <div class="list-item-clean">
                                <span class="item-title-clean"><?= htmlspecialchars($exp['company_name']) ?></span>
                                <p class="item-subtitle-clean"><?= htmlspecialchars($exp['description']) ?></p>
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
                <div class="modern-card p-4 p-md-5 reveal">
                    <div>
                        <form id="contactForm" method="POST" action="/api/contact">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.5px;">Full Name</label>
                                <input type="text" name="name" class="form-control hover-border-light" placeholder="Your name" required style="border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); transition: border-color 0.2s;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.5px;">Email</label>
                                <input type="email" name="email" class="form-control hover-border-light" placeholder="you@example.com" required style="border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); transition: border-color 0.2s;">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" style="font-weight: 600; font-size: 0.85rem; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.5px;">Message</label>
                                <textarea name="message" class="form-control hover-border-light" rows="5" placeholder="Tell me about your project..." required style="border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); transition: border-color 0.2s;"></textarea>
                            </div>
                            <button type="submit" class="btn-primary-custom w-100 justify-content-center" style="border-radius: 10px; font-weight: 600; letter-spacing: -0.2px;">Send Message &rarr;</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="footer-section py-5">
    <div class="container footer-inner">
        <!-- Top Row -->
        <div class="text-center pb-4">
            <a href="#" class="footer-brand mb-3 d-inline-block">
                <?= htmlspecialchars(explode(' ', $profile['name'])[0]) ?>.
            </a>
            <p style="color: #475569; font-size: 0.92rem; margin-top: 10px; margin-bottom: 28px; max-width: 420px; margin-left: auto; margin-right: auto;"><?= htmlspecialchars($profile['title'] ?? '') ?></p>
            <!-- Social Links -->
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-2">
                <?php if (!empty($profile['github_url'])): ?>
                <a href="<?= htmlspecialchars($profile['github_url']) ?>" target="_blank" class="footer-social-link">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                    GitHub
                </a>
                <?php endif; ?>
                <?php if (!empty($profile['linkedin_url'])): ?>
                <a href="<?= htmlspecialchars($profile['linkedin_url']) ?>" target="_blank" class="footer-social-link">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    LinkedIn
                </a>
                <?php endif; ?>
                <?php if (!empty($profile['portfolio_url'])): ?>
                <a href="<?= htmlspecialchars($profile['portfolio_url']) ?>" target="_blank" class="footer-social-link">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M0 1.826C0 .818.819 0 1.826 0H22.17C23.18 0 24 .82 24 1.826v20.348C24 23.18 23.18 24 22.17 24H1.826C.818 24 0 23.18 0 22.174V1.826zm19.063 16.91v-8.48l-7.082 7.08 1.404 1.4 4.65-4.64v4.64h1.028zm-9.647 0v-4.64l-4.65 4.64-1.404-1.4 7.082-7.08v8.48H9.416zm9.647-11.472v-4.64h-1.028v4.64l-4.65-4.64-1.404 1.4 7.082 7.08v-8.48zm-9.647 0v8.48l7.082-7.08-1.404-1.4-4.65 4.64V7.264H9.416z"/></svg>
                    Behance
                </a>
                <?php endif; ?>
                <?php if (!empty($profile['email'])): ?>
                <a href="mailto:<?= htmlspecialchars($profile['email']) ?>" class="footer-social-link">
                    <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636C.732 21.002 0 20.27 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L5.455 4.64 12 9.548l6.545-4.91 1.528-1.145C21.69 2.28 24 3.434 24 5.457z"/></svg>
                    Email
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-divider"></div>
        <!-- Bottom Row -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <p style="color: #334155; font-size: 0.8rem; margin: 0;">© <?= date('Y') ?> <?= htmlspecialchars($profile['name']) ?>. All rights reserved.</p>
            <div class="d-flex gap-3">
                <a href="/privacy" class="footer-legal-link">Privacy Policy</a>
                <span style="color: #1e293b;">·</span>
                <a href="/terms" class="footer-legal-link">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js"></script>
</body>
</html>
