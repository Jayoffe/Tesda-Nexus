<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply to a training center | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <main class="auth-page">
        <div class="auth-panel">
            <aside class="auth-visual" aria-label="TESDA training services">
                <img src="<?= site_url('/images/tsda.jpg'); ?>" alt="TESDA seal">
                <p>Apply to a training center for student or instructor access.</p>
            </aside>
            <section class="auth-form">
                <?php if (!empty($submitted)): ?>
                    <h1>Application submitted</h1>
                    <p class="auth-intro">Your application to <strong><?= htmlspecialchars($submitted_center_name ?? 'your selected training center', ENT_QUOTES, 'UTF-8'); ?></strong> will be reviewed by its organizer. You can log in after it is approved.</p>
                    <div class="form-actions">
                        <a class="button" href="<?= site_url('/login'); ?>">Go to log in</a>
                        <a class="button button-secondary" href="<?= site_url('/'); ?>">Back to home</a>
                    </div>
                <?php else: ?>
                    <h1>Apply to a center</h1>
                    <p class="auth-intro">Choose your role and training center. The center organizer must approve your application before you can log in.</p>
                    <?php if (!empty($errors)): ?>
                        <ul class="error-list" role="alert"><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li><?php endforeach; ?></ul>
                    <?php endif; ?>
                    <form action="<?= site_url('/apply'); ?>" method="post">
                        <div class="field">
                            <label for="full_name">Full name</label>
                            <input id="full_name" name="full_name" type="text" maxlength="150" autocomplete="name" value="<?= htmlspecialchars($application['full_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="field">
                            <label for="username">Username</label>
                            <input id="username" name="username" type="text" maxlength="100" autocomplete="username" value="<?= htmlspecialchars($application['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="field">
                            <label for="email">Email address</label>
                            <input id="email" name="email" type="email" maxlength="255" autocomplete="email" value="<?= htmlspecialchars($application['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="field">
                            <label for="role">Applying as</label>
                            <select id="role" name="role" required>
                                <option value="">Choose a role</option>
                                <option value="student" <?= ($application['role'] ?? '') === 'student' ? 'selected' : ''; ?>>Student</option>
                                <option value="instructor" <?= ($application['role'] ?? '') === 'instructor' ? 'selected' : ''; ?>>Instructor</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="training_center_id">Training center</label>
                            <select id="training_center_id" name="training_center_id" required>
                                <option value="">Choose a training center</option>
                                <?php foreach (($centers ?? []) as $center): ?>
                                    <option value="<?= (int) $center['id']; ?>" <?= (int) ($application['training_center_id'] ?? 0) === (int) $center['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($center['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required>
                            <small class="muted-copy">Use at least 8 characters.</small>
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit">Submit application</button>
                            <a class="button button-secondary" href="<?= site_url('/login'); ?>">Back to log in</a>
                        </div>
                    </form>
                <?php endif; ?>
            </section>
        </div>
    </main>
</body>
</html>
