<?php
require_once __DIR__ . '/config.php';

$siteTitle = APP_NAME;
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $successMessage = 'Thanks for reaching out! Your message has been received.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="topbar">
        <div class="container nav">
            <div class="brand">My CMS</div>
            <nav>
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <main id="home">
        <section class="hero">
            <div class="container hero-inner">
                <div>
                    <p class="eyebrow">Welcome</p>
                    <h1>Build your online presence with confidence.</h1>
                    <p class="lead">A simple, modern website starter for your business, portfolio, or content platform.</p>
                    <div class="cta-row">
                        <a href="#contact" class="btn primary">Get Started</a>
                        <a href="admin/index.php" class="btn secondary">Admin</a>
                    </div>
                </div>
                <div class="hero-card">
                    <h3>Launch-ready</h3>
                    <ul>
                        <li>Responsive layout</li>
                        <li>Contact form</li>
                        <li>Simple admin area</li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="about" class="section">
            <div class="container">
                <h2>About Us</h2>
                <p>
                    This project provides a clean starting point for a small business site, personal landing page,
                    or CMS-style frontend. It is designed to be easy to customize and extend for new content.
                </p>
            </div>
        </section>

        <section id="services" class="section alt">
            <div class="container">
                <h2>Services</h2>
                <div class="grid three-col">
                    <article class="card">
                        <h3>Web Design</h3>
                        <p>Clean and modern layouts tailored to your brand.</p>
                    </article>
                    <article class="card">
                        <h3>Content Strategy</h3>
                        <p>Focused messaging to help your audience take action.</p>
                    </article>
                    <article class="card">
                        <h3>Support</h3>
                        <p>Ongoing maintenance and improvements as your site grows.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="contact" class="section">
            <div class="container contact-wrap">
                <div>
                    <h2>Contact</h2>
                    <p>Tell us about your project and we will get back to you shortly.</p>
                    <p><strong>Email:</strong> <?= htmlspecialchars(APP_EMAIL) ?></p>
                </div>

                <form action="contact_handler.php" method="post" class="contact-form">
                    <label>
                        Name
                        <input type="text" name="name" required>
                    </label>
                    <label>
                        Email
                        <input type="email" name="email" required>
                    </label>
                    <label>
                        Message
                        <textarea name="message" rows="5" required></textarea>
                    </label>
                    <button type="submit" class="btn primary">Send Message</button>
                </form>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/app.js"></script>
</body>
</html>
