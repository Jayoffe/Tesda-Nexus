<?php $enrollments = $enrollments ?? []; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My courses | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Student navigation">
                <a href="<?= site_url('/dashboard'); ?>">Dashboard</a>
                <a href="<?= site_url('/my-courses'); ?>" aria-current="page">My courses</a>
                <a href="<?= site_url('/student-profile'); ?>">My profile</a>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>TESDA student services</p><span class="user-chip">My courses</span></header>
            <main class="page-content role-page role-student">
                <section class="page-heading role-heading"><div><h1>My NC courses</h1><p>Your National Certificate course registrations.</p></div></section>
                <section class="data-panel role-data-panel">
                    <?php if ($enrollments): ?>
                        <div class="table-scroll">
                            <table class="data-table management-table">
                                <thead><tr><th>Course</th><th>Qualification</th><th>Status</th><th>Course position</th><th>Enrolled</th></tr></thead>
                                <tbody>
                                    <?php foreach ($enrollments as $enrollment): ?>
                                        <tr>
                                            <td><a href="<?= site_url('/course/' . (int) $enrollment['course_id']); ?>"><strong><?= htmlspecialchars($enrollment['course_name'], ENT_QUOTES, 'UTF-8'); ?></strong></a><br><?= htmlspecialchars($enrollment['course_code'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><span class="qualification-label"><?= htmlspecialchars($enrollment['qualification'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td><span class="status status-active"><?= htmlspecialchars(ucfirst($enrollment['enrollment_status']), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td><?= (int) $enrollment['course_position']; ?> of <?= (int) $enrollment['course_student_count']; ?></td>
                                            <td><?= htmlspecialchars($enrollment['enrolled_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state"><strong>No course registrations yet</strong><span>An instructor can enroll you in an open NC course.</span></div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
