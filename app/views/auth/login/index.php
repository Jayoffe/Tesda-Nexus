<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <main class="auth-page">
        <div class="auth-panel">
            <aside class="auth-visual" aria-label="TESDA MIMAROPA Regional Office">
                <img src="<?= site_url('/images/tsda.jpg'); ?>" alt="TESDA seal">
                <p>Technical education and skills for a stronger future.</p>
            </aside>
            <section class="auth-form">
                <h1>Welcome back</h1>
                <p class="auth-intro">Log in to continue to TESDA NEXUS.</p>
                <?php if (!empty($error)): ?>
                    <p class="form-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
                <form action="<?= site_url('/login'); ?>" method="post">
                    <div class="field">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($identity ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="name@example.com" autocomplete="username" required>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" autocomplete="current-password" required>
                    </div>
                    <div class="form-actions">
                        <button class="button" type="submit">Log in</button>
                        <a class="button button-secondary" href="<?= site_url('/'); ?>">Back to home</a>
                    </div>
                </form>
                <p class="auth-intro">New to the portal? <a href="<?= site_url('/apply'); ?>">Apply as a student or instructor</a>.</p>
            </section>
        </div>
    </main>
</body>
</html>
