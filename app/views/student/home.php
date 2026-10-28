<!DOCTYPE html>
<?php $enrollments = $enrollments ?? []; ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student portal | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Main navigation">
                <a href="<?= site_url('/dashboard'); ?>" aria-current="page">Dashboard</a>
                <a href="<?= site_url('/my-courses'); ?>">My courses</a>
                <a href="<?= site_url('/student-profile'); ?>">My profile</a>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>TESDA student services · <?= htmlspecialchars($training_center_name ?: 'Center not assigned', ENT_QUOTES, 'UTF-8'); ?></p><span class="user-chip"><?= htmlspecialchars(($full_name ?: $username) . ' · Student', ENT_QUOTES, 'UTF-8'); ?></span></header>
            <main class="page-content role-page role-student">
                <section class="page-heading role-heading">
                    <div>
                        <h1>Student portal</h1>
                        <p>View your student information and registered National Certificate courses.</p>
                    </div>
                    <a class="button" href="<?= site_url('/my-courses'); ?>">View my courses</a>
                </section>
                <div class="content-grid">
                    <section class="panel role-panel student-course-preview">
                        <h2 class="section-title">My NC courses</h2>
                        <?php if ($enrollments): ?>
                            <div class="list-rows">
                                <?php foreach (array_slice($enrollments, 0, 4) as $enrollment): ?>
                                    <a class="list-row" href="<?= site_url('/course/' . (int) $enrollment['course_id']); ?>">
                                        <strong><?= htmlspecialchars($enrollment['course_name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <span><?= htmlspecialchars($enrollment['qualification'], ENT_QUOTES, 'UTF-8'); ?> · <?= (int) $enrollment['course_position']; ?> of <?= (int) $enrollment['course_student_count']; ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <p class="panel-link"><a href="<?= site_url('/my-courses'); ?>">View all courses</a></p>
                        <?php else: ?>
                            <div class="empty-state"><strong>No course registrations yet</strong><span>An instructor can enroll you in an open NC course.</span></div>
                        <?php endif; ?>
                    </section>
                    <section class="panel role-panel student-services-panel">
                        <h2 class="section-title">Portal services</h2>
                        <div class="list-rows">
                            <a class="list-row" href="<?= site_url('/student-profile'); ?>"><strong>My profile</strong><span>Edit details</span></a>
                            <a class="list-row" href="<?= site_url('/my-courses'); ?>"><strong>My courses</strong><span>View registrations</span></a>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
