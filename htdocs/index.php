<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

$skills = [];
$projects = [];
$banner = null;

if (table_exists($pdo, 'skills')) {
    try {
        $activeColumn = first_column($pdo, 'skills', ['is_active', 'active']);
        $sql = 'SELECT * FROM skills';
        if ($activeColumn !== null) {
            $sql .= " WHERE `$activeColumn` = 1";
        }
        $categoryColumn = first_column($pdo, 'skills', ['category']);
        $levelColumn = first_column($pdo, 'skills', ['level']);
        if ($categoryColumn) {
            $sql .= " ORDER BY `$categoryColumn`";
            if ($levelColumn) $sql .= ", `$levelColumn` DESC";
        } elseif ($levelColumn) {
            $sql .= " ORDER BY `$levelColumn` DESC";
        }
        $sql .= ' LIMIT 100';
        $skills = $pdo->query($sql)->fetchAll();
    } catch (Throwable $e) {
        error_log('Skills display failed: ' . $e->getMessage());
    }
}

if (table_exists($pdo, 'projects')) {
    try {
        $publishedColumn = first_column($pdo, 'projects', ['is_published', 'published', 'is_active']);
        $sql = 'SELECT * FROM projects';
        if ($publishedColumn) {
            $sql .= " WHERE `$publishedColumn` = 1";
        }
        $createdColumn = first_column($pdo, 'projects', ['created_at', 'id']);
        if ($createdColumn) $sql .= " ORDER BY `$createdColumn` DESC";
        $sql .= ' LIMIT 12';
        $projects = $pdo->query($sql)->fetchAll();
    } catch (Throwable $e) {
        error_log('Projects display failed: ' . $e->getMessage());
    }
}

if (table_exists($pdo, 'site_banner')) {
    try {
        $banner = $pdo->query('SELECT * FROM site_banner LIMIT 1')->fetch() ?: null;
    } catch (Throwable $e) {
        error_log('Banner display failed: ' . $e->getMessage());
    }
}

function pick(array $row, array $keys, string $fallback = ''): string {
    foreach ($keys as $key) {
        if (isset($row[$key]) && is_scalar($row[$key]) && trim((string)$row[$key]) !== '') {
            return (string)$row[$key];
        }
    }
    return $fallback;
}

$bannerTitle = $banner ? pick($banner, ['title', 'heading', 'banner_title'], '') : '';
$bannerText  = $banner ? pick($banner, ['message', 'description', 'content', 'banner_text'], '') : '';
?>
<!doctype html>
<html lang="en" data-theme="dark" data-accent="violet">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Rodny — full-stack developer building modern websites, applications and digital systems.">
    <meta name="theme-color" content="#101018">
    <title>Rodny Portfolio | Full-Stack Developer</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<header class="site-header">
    <a class="brand" href="#home" aria-label="Rodny Portfolio home">
        <span class="brand-mark">R<span>.</span></span>
        <span>rodny<span class="muted">.dev</span></span>
    </a>

    <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation" aria-expanded="false">☰</button>

    <nav class="nav-links" id="mainNav">
        <a href="#about">About</a>
        <a href="#skills">Skills</a>
        <a href="#projects">Projects</a>
        <a href="#contact">Contact</a>
        <a class="nav-admin" href="admin/index.php">Admin</a>
    </nav>

    <div class="theme-tools">
        <label class="sr-only" for="themeSelect">Choose theme</label>
        <select id="themeSelect" aria-label="Choose theme">
            <option value="dark">Dark</option>
            <option value="light">Light</option>
            <option value="midnight">Midnight</option>
        </select>
        <label class="sr-only" for="accentSelect">Choose accent color</label>
        <select id="accentSelect" aria-label="Choose accent color">
            <option value="violet">Violet</option>
            <option value="blue">Blue</option>
            <option value="emerald">Emerald</option>
            <option value="rose">Rose</option>
        </select>
    </div>
</header>

<main>
    <?php if ($bannerTitle !== '' || $bannerText !== ''): ?>
    <aside class="announcement">
        <strong><?= e($bannerTitle ?: 'Announcement') ?></strong>
        <span><?= e($bannerText) ?></span>
    </aside>
    <?php endif; ?>

    <section class="hero section-wrap" id="home">
        <div class="hero-copy">
            <p class="eyebrow"><span class="status-dot"></span> AVAILABLE FOR SELECT PROJECTS</p>
            <h1>Building digital<br><span class="gradient-text">experiences that work.</span></h1>
            <p class="hero-lead">I'm Rodny, a full-stack developer focused on dependable web applications, thoughtful interfaces and practical automation.</p>
            <div class="hero-actions">
                <a class="button button-primary" href="#projects">Explore my work <span>↗</span></a>
                <a class="button button-quiet" href="#contact">Let's talk</a>
            </div>
            <div class="hero-meta">
                <span><span class="tiny-dot"></span> PHP · JavaScript · MySQL</span>
                <span>Based in Rotterdam, Netherlands</span>
            </div>
        </div>
        <div class="hero-visual" aria-label="Developer workspace illustration">
            <div class="orb orb-one"></div><div class="orb orb-two"></div>
            <div class="code-window">
                <div class="window-bar"><span></span><span></span><span></span><small>build.php</small></div>
                <pre><code><i>&lt;?php</i>
<span class="code-purple">$project</span> = [
  <span class="code-green">'design'</span> =&gt; <b>true</b>,
  <span class="code-green">'quality'</span> =&gt; <b>true</b>,
  <span class="code-green">'future'</span> =&gt; <b>'ready'</b>,
];

<span class="code-purple">echo</span> <span class="code-green">"Let's build."</span>;</code></pre>
                <div class="code-status"><span class="status-dot"></span> All systems operational</div>
            </div>
            <div class="floating-chip chip-top">✳ Clean architecture</div>
            <div class="floating-chip chip-bottom">⌘ Built with purpose</div>
        </div>
    </section>

    <section class="section-wrap section-block" id="about">
        <div class="section-heading">
            <p class="eyebrow">01 / ABOUT</p>
            <h2>Engineering with <span class="gradient-text">intention.</span></h2>
        </div>
        <div class="about-grid">
            <p class="about-lead">I connect backend engineering, frontend design and automation to create systems that feel as good to use as they are to maintain.</p>
            <div class="about-detail">
                <p>From custom CMS platforms to database-backed tools, I care about performance, security, clarity and a polished user experience.</p>
                <div class="value-list">
                    <span>01 — Practical solutions</span>
                    <span>02 — Reliable foundations</span>
                    <span>03 — Detail-driven interfaces</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section-wrap section-block" id="skills">
        <div class="section-heading">
            <p class="eyebrow">02 / TOOLKIT</p>
            <h2>Tools of the <span class="gradient-text">trade.</span></h2>
            <p>Technologies and skills from the current CMS database.</p>
        </div>
        <?php if ($skills): ?>
        <div class="skill-grid">
            <?php foreach ($skills as $skill):
                $name = pick($skill, ['name', 'title'], 'Skill');
                $category = pick($skill, ['category'], 'Development');
                $level = (int)pick($skill, ['level'], '0');
                $level = max(0, min(5, $level));
            ?>
            <article class="skill-card">
                <div class="skill-card-top"><span class="skill-icon">⌘</span><span class="skill-category"><?= e(ucfirst($category)) ?></span></div>
                <h3><?= e($name) ?></h3>
                <?php if ($level > 0): ?>
                <div class="skill-meter" aria-label="<?= $level ?> out of 5"><span style="width:<?= (int)($level * 20) ?>%"></span></div>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state"><span>⌘</span><p>Your skills will appear here when published in the CMS.</p></div>
        <?php endif; ?>
    </section>

    <section class="section-wrap section-block" id="projects">
        <div class="section-heading heading-row">
            <div><p class="eyebrow">03 / SELECTED WORK</p><h2>Projects in <span class="gradient-text">focus.</span></h2></div>
            <span class="section-count"><?= str_pad((string)count($projects), 2, '0', STR_PAD_LEFT) ?> PROJECTS</span>
        </div>
        <?php if ($projects): ?>
        <div class="project-grid">
            <?php foreach ($projects as $project):
                $title = pick($project, ['title', 'name'], 'Untitled project');
                $description = pick($project, ['description', 'summary'], 'A project built with a focus on quality and usability.');
                $image = pick($project, ['image_url', 'image', 'thumbnail']);
                $link = pick($project, ['project_link', 'project_url', 'live_url', 'url']);
                $source = pick($project, ['github_link', 'source_url', 'github_url']);
                $technologies = pick($project, ['technologies', 'tech_stack']);
            ?>
            <article class="project-card">
                <div class="project-art">
                    <?php if ($image !== '' && preg_match('~^(https?://|uploads/)~i', $image)): ?>
                        <img src="<?= e($image) ?>" alt="<?= e($title) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="project-placeholder"><span>R<span>.</span></span><small>PROJECT / <?= e(strtoupper(substr($title, 0, 3))) ?></small></div>
                    <?php endif; ?>
                    <span class="project-index">PROJECT</span>
                </div>
                <div class="project-content">
                    <div class="project-title-row"><h3><?= e($title) ?></h3><span>↗</span></div>
                    <p><?= e($description) ?></p>
                    <?php if ($technologies !== ''): ?><div class="tag-list"><?php foreach (array_slice(array_map('trim', explode(',', $technologies)), 0, 6) as $tag): if ($tag !== ''): ?><span><?= e($tag) ?></span><?php endif; endforeach; ?></div><?php endif; ?>
                    <div class="project-links">
                        <?php if ($link && filter_var($link, FILTER_VALIDATE_URL)): ?><a href="<?= e($link) ?>" target="_blank" rel="noopener noreferrer">Live project ↗</a><?php endif; ?>
                        <?php if ($source && filter_var($source, FILTER_VALIDATE_URL)): ?><a href="<?= e($source) ?>" target="_blank" rel="noopener noreferrer">Source code ↗</a><?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state"><span>↗</span><p>Published projects will appear here. Add projects through the admin CMS.</p></div>
        <?php endif; ?>
    </section>

    <section class="contact-section" id="contact">
        <div class="section-wrap contact-inner">
            <div>
                <p class="eyebrow">04 / CONTACT</p>
                <h2>Have an idea?<br><span class="gradient-text">Let's make it real.</span></h2>
                <p class="contact-intro">Tell me a little about your project, challenge or idea.</p>
                <a class="contact-email" href="mailto:rodnysb@proton.me">rodnysb@proton.me ↗</a>
            </div>
            <form class="contact-form" action="contact_handler.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <label>Your name<input name="name" maxlength="100" required autocomplete="name" placeholder="Jane Smith"></label>
                <label>Email address<input type="email" name="email" maxlength="190" required autocomplete="email" placeholder="jane@example.com"></label>
                <label>Subject<input name="subject" maxlength="190" placeholder="What would you like to build?"></label>
                <label>Message<textarea name="message" rows="4" maxlength="5000" required placeholder="Tell me about your idea..."></textarea></label>
                <button class="button button-primary" type="submit">Send message <span>↗</span></button>
            </form>
        </div>
    </section>
</main>

<footer class="site-footer section-wrap">
    <a class="brand" href="#home"><span class="brand-mark">R<span>.</span></span><span>rodny<span class="muted">.dev</span></span></a>
    <span>Designed with care. Built to work.</span>
    <a href="admin/index.php">CMS access ↗</a>
    <span>© <?= date('Y') ?> Rodny</span>
</footer>
</body>
</html>
