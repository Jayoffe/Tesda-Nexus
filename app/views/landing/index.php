<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="<?= site_url('/'); ?>">
            <img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">
            <span>TESDA NEXUS</span>
        </a>
        <nav class="main-nav" aria-label="Main navigation">
            <a href="<?= site_url('/'); ?>" aria-current="page">Home</a>
            <a href="<?= site_url('/login'); ?>">Log in</a>
            <a href="<?= site_url('/apply'); ?>">Apply</a>
        </nav>
    </header>
    <main class="landing">
        <section class="landing-copy">
            <h1>Student services, all in one place.</h1>
            <p>Access learning resources, allowance updates, and important documents through one secure TESDA platform.</p>
            <div class="landing-actions">
                <a class="button" href="<?= site_url('/login'); ?>">Log in to your account</a>
                <a class="button button-secondary" href="<?= site_url('/apply'); ?>">Apply to a center</a>
            </div>
        </section>
        <img class="landing-photo" src="<?= site_url('/images/bg.png'); ?>" alt="TESDA MIMAROPA Regional Office">
    </main>
</body>
</html>
