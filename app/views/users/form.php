<?php $account = $account ?? []; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?> | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Administration">
                <a href="<?= site_url('/dashboard'); ?>"><?= !empty($is_center_admin) ? 'Center overview' : 'Analytics'; ?></a>
                <?php if (!empty($is_center_admin)): ?><a href="<?= site_url('/center/applications'); ?>">Applications</a><?php endif; ?>
                <a href="<?= site_url('/show-users'); ?>" aria-current="page">Manage users</a>
                <a href="<?= site_url('/admin/courses'); ?>">Manage NC courses</a>
                <?php if (empty($is_center_admin)): ?>
                    <a href="<?= site_url('/admin/centers'); ?>">Training centers</a>
                    <a href="<?= site_url('/products'); ?>">Manage inventory</a>
                <?php endif; ?>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>Account management</p><a class="button button-secondary button-small" href="<?= site_url('/show-users'); ?>">Back to users</a></header>
            <main class="page-content role-page role-form-page">
                <section class="page-heading role-heading"><div><h1><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></h1><p>Account roles control which portal features are available.</p></div></section>
                <?php if (!empty($errors)): ?>
                    <ul class="error-list" role="alert"><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li><?php endforeach; ?></ul>
                <?php endif; ?>
                <section class="panel role-panel management-form-panel">
                    <form class="management-form" action="<?= htmlspecialchars($form_action, ENT_QUOTES, 'UTF-8'); ?>" method="post">
                        <div class="content-grid form-grid">
                            <div class="field">
                                <label for="full_name">Full name</label>
                                <input id="full_name" name="full_name" type="text" maxlength="150" value="<?= htmlspecialchars($account['full_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="field">
                                <label for="username">Username</label>
                                <input id="username" name="username" type="text" maxlength="100" value="<?= htmlspecialchars($account['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                        </div>
                        <div class="content-grid form-grid">
                            <div class="field">
                                <label for="email">Email address</label>
                                <input id="email" name="email" type="email" maxlength="255" value="<?= htmlspecialchars($account['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="field">
                                <label for="role">Account role</label>
                                <select id="role" name="role" required>
                                    <?php
                                    $roleOptions = !empty($is_center_admin)
                                        ? ['student' => 'Student', 'instructor' => 'Instructor']
                                        : ['student' => 'Student', 'instructor' => 'Instructor', 'center_admin' => 'Center organizer', 'admin' => 'TESDA administrator'];
                                    foreach ($roleOptions as $roleValue => $roleLabel):
                                    ?>
                                        <option value="<?= $roleValue; ?>" <?= ($account['role'] ?? 'student') === $roleValue ? 'selected' : ''; ?>><?= $roleLabel; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field">
                                <label for="training_center_id">Training center</label>
                                <?php if (!empty($is_center_admin)): ?>
                                    <?php $assignedCenter = reset($training_centers); ?>
                                    <input id="training_center_display" type="text" value="<?= htmlspecialchars($assignedCenter['name'] ?? 'No assigned center', ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                    <input id="training_center_id" name="training_center_id" type="hidden" value="<?= (int) ($assignedCenter['id'] ?? 0); ?>">
                                <?php else: ?>
                                    <select id="training_center_id" name="training_center_id">
                                        <option value="">No center (TESDA administrator)</option>
                                        <?php foreach (($training_centers ?? []) as $center): ?>
                                            <option value="<?= (int) $center['id']; ?>" <?= (int) ($account['training_center_id'] ?? 0) === (int) $center['id'] ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($center['name'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="content-grid form-grid">
                            <div class="field">
                                <label for="password"><?= empty($account['id']) ? 'Password' : 'New password'; ?></label>
                                <input id="password" name="password" type="password" autocomplete="new-password" <?= empty($account['id']) ? 'required' : ''; ?>>
                                <?php if (!empty($account['id'])): ?><small class="muted-copy">Leave blank to keep the current password.</small><?php endif; ?>
                            </div>
                            <div class="field">
                                <label for="is_active">Account status</label>
                                <select id="is_active" name="is_active">
                                    <option value="1" <?= (int) ($account['is_active'] ?? 1) === 1 ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?= (int) ($account['is_active'] ?? 1) === 0 ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit">Save account</button>
                            <a class="button button-secondary" href="<?= site_url('/show-users'); ?>">Cancel</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
