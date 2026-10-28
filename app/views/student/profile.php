<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student profile | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Main navigation">
                <a href="<?= site_url('/dashboard'); ?>">Dashboard</a>
                <a href="<?= site_url('/my-courses'); ?>">My courses</a>
                <a href="<?= site_url('/student-profile'); ?>" aria-current="page">My profile</a>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>TESDA student services · <?= htmlspecialchars($training_center_name ?: 'Center not assigned', ENT_QUOTES, 'UTF-8'); ?></p><span class="user-chip"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">Student profile</span></header>
            <main class="page-content role-page role-student role-profile">
                <section class="page-heading role-heading">
                    <div>
                        <h1>Student profile</h1>
                        <p>Update the name and email address used for your account.</p>
                    </div>
                    <a class="button button-secondary" href="<?= site_url('/profile'); ?>">Back to student portal</a>
                </section>
                <?php if (!empty($notification)): ?><p class="notice notice-success" role="status"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                <?php if (!empty($errors)): ?><ul class="error-list" role="alert"><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li><?php endforeach; ?></ul><?php endif; ?>
                <?php
                    $profileInitials = '';
                    foreach (preg_split('/\s+/', trim($name)) as $namePart) {
                        if ($namePart !== '') {
                            $profileInitials .= strtoupper(substr($namePart, 0, 1));
                        }
                    }
                    $profileInitials = substr($profileInitials, 0, 2);
                ?>
                <div class="profile-layout">
                    <section class="profile-summary">
                        <div class="profile-avatar" aria-hidden="true"><?= htmlspecialchars($profileInitials, ENT_QUOTES, 'UTF-8'); ?></div>
                        <h2><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php if (!empty($training_center_name)): ?><p class="muted-copy">Training center: <?= htmlspecialchars($training_center_name, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                    </section>
                    <section class="panel role-panel profile-edit-panel">
                        <h2 class="section-title">Edit your details</h2>
                        <form class="management-form" action="<?= site_url('/student-profile'); ?>" method="post">
                            <div class="field"><label for="full_name">Full name</label><input id="full_name" name="full_name" type="text" maxlength="150" value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="name" required></div>
                            <div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" maxlength="255" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="email" required></div>
                            <div class="form-actions"><button class="button" type="submit">Save profile</button></div>
                        </form>
                    </section>
                </div>
                <section class="panel student-courses-panel">
                    <h2 class="section-title">Registered courses</h2>
                    <?php if (!empty($enrollments)): ?>
                        <div class="list-rows">
                            <?php foreach ($enrollments as $enrollment): ?>
                                <a class="list-row" href="<?= site_url('/course/' . (int) $enrollment['course_id']); ?>"><strong><?= htmlspecialchars($enrollment['course_name'], ENT_QUOTES, 'UTF-8'); ?></strong><span><?= htmlspecialchars($enrollment['qualification'], ENT_QUOTES, 'UTF-8'); ?></span></a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="muted-copy">You are not registered in an NC course yet.</p>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
