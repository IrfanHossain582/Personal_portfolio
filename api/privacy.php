<?php
// api/privacy.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy — Irfan Hossain Portfolio</title>
    <meta name="description" content="Privacy Policy for Irfan Hossain's personal portfolio website.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            line-height: 1.8;
            padding: 40px 20px 80px;
        }
        .container { max-width: 780px; margin: 0 auto; }
        .back-link {
            display: inline-flex; align-items: center; gap: 8px;
            color: #818cf8; text-decoration: none; font-size: 0.9rem;
            margin-bottom: 40px; transition: opacity 0.2s;
        }
        .back-link:hover { opacity: 0.7; }
        h1 { font-size: 2.2rem; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .subtitle { color: #94a3b8; font-size: 0.9rem; margin-bottom: 48px; }
        h2 { font-size: 1.2rem; font-weight: 700; color: #818cf8; margin: 40px 0 12px; }
        p { color: #94a3b8; margin-bottom: 16px; }
        ul { color: #94a3b8; padding-left: 24px; margin-bottom: 16px; }
        ul li { margin-bottom: 8px; }
        .card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 32px;
        }
        a { color: #818cf8; }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">← Back to Portfolio</a>

        <h1>Privacy Policy</h1>
        <p class="subtitle">Last updated: April 2026</p>

        <div class="card">
            <h2>1. Who We Are</h2>
            <p>This is the personal portfolio website of <strong>Irfan Hossain</strong>, an AI & Automation Engineer. This site is for professional presentation purposes only.</p>

            <h2>2. Information We Collect</h2>
            <p>We only collect information that you voluntarily provide through the contact form, specifically:</p>
            <ul>
                <li>Your full name</li>
                <li>Your email address</li>
                <li>Your message content</li>
            </ul>
            <p>We do <strong>not</strong> collect any tracking cookies, browsing behavior, or personal data beyond what you submit in the contact form.</p>

            <h2>3. How We Use Your Information</h2>
            <p>Information submitted through the contact form is used solely to:</p>
            <ul>
                <li>Respond to your inquiry or message</li>
                <li>Connect with potential collaborators or employers</li>
            </ul>
            <p>Your information is never sold, shared with third parties for marketing, or used for any commercial purpose.</p>

            <h2>4. Data Storage</h2>
            <p>Contact form submissions are stored securely in a Supabase-hosted PostgreSQL database with row-level security enabled. Only the site owner (Irfan Hossain) has access to submitted messages.</p>

            <h2>5. Third-Party Services</h2>
            <p>This site uses the following third-party services:</p>
            <ul>
                <li><strong>Vercel</strong> — web hosting and deployment</li>
                <li><strong>Supabase</strong> — secure database storage</li>
                <li><strong>Google Fonts</strong> — web typography</li>
                <li><strong>Bootstrap CDN</strong> — UI framework</li>
            </ul>
            <p>Each of these services has its own privacy policy. We recommend reviewing them independently.</p>

            <h2>6. Your Rights</h2>
            <p>You may request deletion of any information you have submitted by emailing <a href="mailto:irfanhossain582@gmail.com">irfanhossain582@gmail.com</a>. We will respond within 30 days.</p>

            <h2>7. Contact</h2>
            <p>For any privacy-related questions, please reach out at:<br>
            <a href="mailto:irfanhossain582@gmail.com">irfanhossain582@gmail.com</a></p>
        </div>
    </div>
</body>
</html>
