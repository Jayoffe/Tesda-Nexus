<?php
$students = $students ?? [];
$courses = $courses ?? [];
$enrollments = $enrollments ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor portal | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Instructor navigation">
                <a href="<?= site_url('/instructor'); ?>" aria-current="page">Instructor portal</a>
                <a href="<?= site_url('/logout'); ?>">Log out</a>
            </nav>
        </aside>
        <div class="workspace">
            <header class="app-header">
                <p>Instructor services · <?= htmlspecialchars($training_center_name ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                <span class="user-chip"><?= htmlspecialchars(($full_name ?: $username) . ' · Instructor · ' . ($training_center_name ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
            </header>
            <main class="page-content role-page role-instructor">
                <section class="page-heading role-heading">
                    <div>
                        <h1>Instructor portal</h1>
                        <p>Register students in open National Certificate courses and review your rosters.</p>
                    </div>
                </section>
                <?php if (!empty($notification)): ?><p class="notice <?= !empty($notification_success) ? 'notice-success' : ''; ?>" role="<?= !empty($notification_success) ? 'status' : 'alert'; ?>"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                <div class="content-grid instructor-layout">
                    <section class="panel role-panel enrollment-panel">
                        <h2 class="section-title">Enroll a student</h2>
                        <p class="panel-intro">Choose an active student and an open NC course.</p>
                        <?php if ($students && $courses): ?>
                            <form action="<?= site_url('/instructor/enroll'); ?>" method="post">
                                <div class="field">
                                    <label for="student_id">Student account</label>
                                    <select id="student_id" name="student_id" required>
                                        <option value="">Choose a student</option>
                                        <?php foreach ($students as $student): ?>
                                            <option value="<?= (int) $student['id']; ?>"><?= htmlspecialchars(($student['full_name'] ?: $student['username']) . ' (' . $student['email'] . ')', ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="course_id">Open NC course</label>
                                    <select id="course_id" name="course_id" required>
                                        <option value="">Choose a course</option>
                                        <?php foreach ($courses as $course): ?>
                                            <option value="<?= (int) $course['id']; ?>"><?= htmlspecialchars($course['course_name'] . ' (' . $course['qualification'] . ')', ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-actions"><button class="button" type="submit">Enroll student</button></div>
                            </form>
                        <?php else: ?>
                            <div class="empty-state">
                                <strong><?= !$students ? 'No active student accounts' : 'No open courses'; ?></strong>
                                <span><?= !$students ? 'Ask an administrator to create or activate a student account.' : 'Ask an administrator to open a course before enrolling students.'; ?></span>
                            </div>
                        <?php endif; ?>
                    </section>
                    <section class="panel role-panel roster-panel">
                        <div class="section-heading-row">
                            <div><h2 class="section-title">Your roster</h2><p class="panel-intro">Students registered through your account.</p></div>
                            <span class="count-badge"><?= count($enrollments); ?> <?= count($enrollments) === 1 ? 'student' : 'students'; ?></span>
                        </div>
                        <?php if ($enrollments): ?>
                            <div class="table-scroll">
                                <table class="data-table management-table">
                                    <thead><tr><th>Student</th><th>Course</th><th>Enrolled</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($enrollments as $enrollment): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($enrollment['full_name'] ?: $enrollment['username'], ENT_QUOTES, 'UTF-8'); ?></strong><br><?= htmlspecialchars($enrollment['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><a href="<?= site_url('/course/' . (int) $enrollment['course_id']); ?>"><?= htmlspecialchars($enrollment['course_name'] . ' (' . $enrollment['qualification'] . ')', ENT_QUOTES, 'UTF-8'); ?></a></td>
                                                <td><?= htmlspecialchars($enrollment['enrolled_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state"><strong>No students enrolled yet</strong><span>Enroll a student to start building your roster.</span></div>
                        <?php endif; ?>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
