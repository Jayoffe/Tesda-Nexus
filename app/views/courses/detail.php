<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['course_name'], ENT_QUOTES, 'UTF-8'); ?> | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <?php if ($role === 'student'): ?>
                <nav class="side-nav" aria-label="Student navigation">
                    <a href="<?= site_url('/dashboard'); ?>">Dashboard</a>
                    <a href="<?= site_url('/my-courses'); ?>" aria-current="page">My courses</a>
                    <a href="<?= site_url('/student-profile'); ?>">My profile</a>
                </nav>
            <?php elseif ($role === 'instructor'): ?>
                <nav class="side-nav" aria-label="Instructor navigation">
                    <a href="<?= site_url('/instructor'); ?>">Instructor portal</a>
                </nav>
            <?php elseif ($role === 'admin'): ?>
                <nav class="side-nav" aria-label="Administration">
                    <a href="<?= site_url('/dashboard'); ?>">Analytics</a>
                    <a href="<?= site_url('/show-users'); ?>">Manage users</a>
                    <a href="<?= site_url('/admin/courses'); ?>" aria-current="page">Manage NC courses</a>
                    <a href="<?= site_url('/admin/centers'); ?>">Training centers</a>
                </nav>
            <?php else: ?>
                <nav class="side-nav" aria-label="Training center administration">
                    <a href="<?= site_url('/dashboard'); ?>">Center overview</a>
                    <a href="<?= site_url('/center/applications'); ?>">Applications</a>
                    <a href="<?= site_url('/show-users'); ?>">Manage users</a>
                    <a href="<?= site_url('/admin/courses'); ?>" aria-current="page">Manage NC courses</a>
                </nav>
            <?php endif; ?>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header">
                <p><?= $role === 'student' ? 'Student services' : ($role === 'instructor' ? 'Instructor services' : ($role === 'center_admin' ? 'Center administration' : 'Administration')); ?></p>
                <span class="user-chip"><?= htmlspecialchars($course['training_center_name'], ENT_QUOTES, 'UTF-8'); ?></span>
            </header>
            <main class="page-content role-page role-course-detail">
                <section class="page-heading role-heading">
                    <div>
                        <p class="course-center-line"><?= htmlspecialchars(implode(', ', array_filter([$course['city'] ?? '', $course['province'] ?? '', $course['region'] ?? ''])), ENT_QUOTES, 'UTF-8'); ?></p>
                        <h1><?= htmlspecialchars($course['course_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                        <p><?= htmlspecialchars($course['course_code'] . ' · ' . $course['qualification'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <?php if ($role === 'student' && $enrollment): ?>
                        <span class="course-position"><?= (int) $enrollment['course_position']; ?> of <?= (int) $enrollment['course_student_count']; ?> enrolled students</span>
                    <?php elseif ($role !== 'student'): ?>
                        <span class="course-position"><?= (int) $course['enrollment_count']; ?> students enrolled</span>
                    <?php endif; ?>
                </section>
                <?php if (!empty($notification)): ?><p class="notice" role="status"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                <?php if (in_array($role, ['admin', 'center_admin'], true)): ?>
                    <p class="course-admin-action"><a class="button button-secondary" href="<?= site_url('/admin/courses/edit/' . (int) $course['id']); ?>">Edit course details</a></p>
                <?php endif; ?>
                <div class="course-detail-grid">
                    <section class="panel role-panel course-overview">
                        <h2 class="section-title">Course information</h2>
                        <p><?= nl2br(htmlspecialchars($course['description'] ?: 'Course information has not been added yet.', ENT_QUOTES, 'UTF-8')); ?></p>
                        <dl class="course-facts">
                            <div><dt>Training center</dt><dd><?= htmlspecialchars($course['training_center_name'], ENT_QUOTES, 'UTF-8'); ?></dd></div>
                            <div><dt>Days</dt><dd><?= htmlspecialchars($course['class_days'] ?: 'Schedule to be announced', ENT_QUOTES, 'UTF-8'); ?></dd></div>
                            <div><dt>Class schedule</dt><dd><?= nl2br(htmlspecialchars($course['class_schedule'] ?: 'Schedule to be announced', ENT_QUOTES, 'UTF-8')); ?></dd></div>
                            <div><dt>Enrollment</dt><dd><?= (int) $course['enrollment_count']; ?> of <?= (int) $course['capacity']; ?> seats</dd></div>
                        </dl>
                    </section>
                    <section class="panel role-panel course-outline">
                        <h2 class="section-title">Course outline</h2>
                        <?php if (trim((string) $course['course_outline']) !== ''): ?>
                            <div class="course-outline-copy"><?= nl2br(htmlspecialchars($course['course_outline'], ENT_QUOTES, 'UTF-8')); ?></div>
                        <?php else: ?>
                            <div class="empty-state"><strong>Outline not available yet</strong><span>The training center has not published the course outline.</span></div>
                        <?php endif; ?>
                    </section>
                </div>
                <?php if ($role === 'instructor'): ?>
                    <section class="panel role-panel course-edit-panel">
                        <h2 class="section-title">Update course schedule and outline</h2>
                        <form class="management-form" action="<?= site_url('/instructor/course/' . (int) $course['id']); ?>" method="post">
                            <div class="field"><label for="class_days">Course days</label><input id="class_days" name="class_days" maxlength="150" value="<?= htmlspecialchars($course['class_days'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                            <div class="field"><label for="class_schedule">Class schedule and location</label><textarea id="class_schedule" name="class_schedule" maxlength="10000"><?= htmlspecialchars($course['class_schedule'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                            <div class="field"><label for="course_outline">Course outline</label><textarea id="course_outline" name="course_outline" maxlength="20000"><?= htmlspecialchars($course['course_outline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                            <div class="form-actions"><button class="button" type="submit">Save course details</button></div>
                        </form>
                    </section>
                <?php endif; ?>
                <section class="panel role-panel course-materials-panel">
                    <div class="section-heading-row">
                        <div><h2 class="section-title">Lecture materials</h2><p class="panel-intro">Shared links and references for this class.</p></div>
                    </div>
                    <?php if (!empty($materials)): ?>
                        <div class="center-list">
                            <?php foreach ($materials as $material): ?>
                                <article class="course-material">
                                    <div><h3><a href="<?= htmlspecialchars($material['resource_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($material['title'], ENT_QUOTES, 'UTF-8'); ?></a></h3>
                                        <?php if (!empty($material['description'])): ?><p><?= htmlspecialchars($material['description'], ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                                    </div>
                                    <time datetime="<?= htmlspecialchars($material['created_at'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars(date('M j, Y', strtotime($material['created_at'])), ENT_QUOTES, 'UTF-8'); ?></time>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state"><strong>No materials shared yet</strong><span>Shared class resources will appear here for enrolled students.</span></div>
                    <?php endif; ?>
                    <?php if ($role === 'instructor'): ?>
                        <form class="management-form material-form" action="<?= site_url('/instructor/course/' . (int) $course['id'] . '/materials'); ?>" method="post">
                            <h3>Share a material link</h3>
                            <div class="field"><label for="material_title">Title</label><input id="material_title" name="title" maxlength="150" required></div>
                            <div class="field"><label for="material_url">Resource URL</label><input id="material_url" name="resource_url" type="url" maxlength="2048" placeholder="https://..." required></div>
                            <div class="field"><label for="material_description">Description (optional)</label><input id="material_description" name="description" maxlength="500"></div>
                            <div class="form-actions"><button class="button" type="submit">Share with class</button></div>
                        </form>
                    <?php endif; ?>
                </section>
                <?php if ($role !== 'student'): ?>
                    <section class="panel role-panel course-roster-panel">
                        <div class="section-heading-row">
                            <div><h2 class="section-title">Enrolled students</h2><p class="panel-intro">Student contact details are visible to staff assigned to this center.</p></div>
                            <span class="count-badge"><?= count($roster); ?> <?= count($roster) === 1 ? 'student' : 'students'; ?></span>
                        </div>
                        <?php if (!empty($roster)): ?>
                            <div class="table-scroll">
                                <table class="data-table management-table">
                                    <thead><tr><th>Student</th><th>Email</th><th>Enrolled</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($roster as $student): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($student['full_name'] ?: $student['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($student['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars($student['enrolled_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?= htmlspecialchars(ucfirst($student['status']), ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state"><strong>No students enrolled yet</strong><span>Students added to this course will appear here.</span></div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
            </main>
        </div>
    </div>
</body>
</html>
