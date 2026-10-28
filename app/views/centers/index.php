<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training centers | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Administration">
                <a href="<?= site_url('/dashboard'); ?>">Analytics</a>
                <a href="<?= site_url('/show-users'); ?>">Manage users</a>
                <a href="<?= site_url('/admin/courses'); ?>">Manage NC courses</a>
                <a href="<?= site_url('/admin/centers'); ?>" aria-current="page">Training centers</a>
                <a href="<?= site_url('/products'); ?>">Manage inventory</a>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>Administration</p><span class="user-chip">Training centers</span></header>
            <main class="page-content role-page role-centers">
                <section class="page-heading role-heading">
                    <div><h1>Training centers</h1><p>Keep course offerings, staff, and student accounts associated with the right center.</p></div>
                </section>
                <?php if (!empty($notification)): ?><p class="notice notice-success" role="status"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                <?php if (!empty($errors)): ?><ul class="error-list" role="alert"><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li><?php endforeach; ?></ul><?php endif; ?>
                <div class="content-grid center-management-layout">
                    <section class="panel role-panel">
                        <h2 class="section-title">Add a training center</h2>
                        <form class="management-form" action="<?= site_url('/admin/centers'); ?>" method="post">
                            <div class="field"><label for="center_code">Center code</label><input id="center_code" name="center_code" maxlength="40" required value="<?= htmlspecialchars($center['center_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                            <div class="field"><label for="name">Center name</label><input id="name" name="name" maxlength="150" required value="<?= htmlspecialchars($center['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                            <div class="field"><label for="region">Region</label><input id="region" name="region" maxlength="100" value="<?= htmlspecialchars($center['region'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                            <div class="content-grid form-grid">
                                <div class="field"><label for="province">Province</label><input id="province" name="province" maxlength="100" value="<?= htmlspecialchars($center['province'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                                <div class="field"><label for="city">City or municipality</label><input id="city" name="city" maxlength="100" value="<?= htmlspecialchars($center['city'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                            </div>
                            <div class="field"><label for="address">Address</label><input id="address" name="address" maxlength="255" value="<?= htmlspecialchars($center['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                            <div class="form-actions"><button class="button" type="submit">Add center</button></div>
                        </form>
                    </section>
                    <section class="panel role-panel">
                        <h2 class="section-title">Registered centers</h2>
                        <?php if (!empty($centers)): ?>
                            <div class="center-list">
                                <?php foreach ($centers as $trainingCenter): ?>
                                    <article class="center-list-item">
                                        <div><h3><?= htmlspecialchars($trainingCenter['name'], ENT_QUOTES, 'UTF-8'); ?></h3><p><?= htmlspecialchars(implode(', ', array_filter([$trainingCenter['city'], $trainingCenter['province'], $trainingCenter['region']])), ENT_QUOTES, 'UTF-8'); ?></p></div>
                                        <dl><div><dt>Courses</dt><dd><?= (int) $trainingCenter['course_count']; ?></dd></div><div><dt>Accounts</dt><dd><?= (int) $trainingCenter['user_count']; ?></dd></div></dl>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state"><strong>No training centers</strong><span>Add a center before setting up local accounts and course offerings.</span></div>
                        <?php endif; ?>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
