<?php $course = $course ?? []; ?>
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
                <a href="<?= site_url('/show-users'); ?>">Manage users</a>
                <a href="<?= site_url('/admin/courses'); ?>" aria-current="page">Manage NC courses</a>
                <?php if (empty($is_center_admin)): ?>
                    <a href="<?= site_url('/admin/centers'); ?>">Training centers</a>
                    <a href="<?= site_url('/products'); ?>">Manage inventory</a>
                <?php endif; ?>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>NC course management</p><a class="button button-secondary button-small" href="<?= site_url('/admin/courses'); ?>">Back to courses</a></header>
            <main class="page-content role-page role-form-page">
                <section class="page-heading role-heading"><div><h1><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></h1><p>Course details determine what instructors can enroll students into.</p></div></section>
                <?php if (!empty($errors)): ?>
                    <ul class="error-list" role="alert"><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li><?php endforeach; ?></ul>
                <?php endif; ?>
                <section class="panel role-panel management-form-panel">
                    <form class="management-form" action="<?= htmlspecialchars($form_action, ENT_QUOTES, 'UTF-8'); ?>" method="post">
                        <div class="content-grid form-grid">
                            <div class="field"><label for="course_code">Course code</label><input id="course_code" name="course_code" maxlength="40" value="<?= htmlspecialchars($course['course_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
                            <div class="field"><label for="qualification">Qualification</label><input id="qualification" name="qualification" maxlength="30" placeholder="NC II" value="<?= htmlspecialchars($course['qualification'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
                        </div>
                        <div class="field"><label for="course_name">Course name</label><input id="course_name" name="course_name" maxlength="150" value="<?= htmlspecialchars($course['course_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
                        <div class="field"><label for="description">Description</label><textarea id="description" name="description"><?= htmlspecialchars($course['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                        <div class="field">
                            <label for="training_center_id">Training center</label>
                            <?php if (!empty($is_center_admin)): ?>
                                <?php $assignedCenter = reset($training_centers); ?>
                                <input id="training_center_display" type="text" value="<?= htmlspecialchars($assignedCenter['name'] ?? 'No assigned center', ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                <input id="training_center_id" name="training_center_id" type="hidden" value="<?= (int) ($assignedCenter['id'] ?? 0); ?>">
                            <?php else: ?>
                                <select id="training_center_id" name="training_center_id" required>
                                    <option value="">Choose a training center</option>
                                    <?php foreach (($training_centers ?? []) as $center): ?>
                                        <option value="<?= (int) $center['id']; ?>" <?= (int) ($course['training_center_id'] ?? 0) === (int) $center['id'] ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($center['name'], ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                        <div class="content-grid form-grid">
                            <div class="field"><label for="class_days">Course days</label><input id="class_days" name="class_days" maxlength="150" value="<?= htmlspecialchars($course['class_days'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Monday, Wednesday, Friday"></div>
                            <div class="field"><label for="class_schedule">Schedule</label><textarea id="class_schedule" name="class_schedule" maxlength="10000" placeholder="8:00 AM to 12:00 PM, Training Room 2"><?= htmlspecialchars($course['class_schedule'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                        </div>
                        <div class="field"><label for="course_outline">Course outline</label><textarea id="course_outline" name="course_outline" maxlength="20000" placeholder="Add one topic or module per line"><?= htmlspecialchars($course['course_outline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                        <div class="content-grid form-grid">
                            <div class="field"><label for="capacity">Enrollment capacity</label><input id="capacity" name="capacity" type="number" min="1" max="10000" value="<?= (int) ($course['capacity'] ?? 30); ?>" required></div>
                            <div class="field"><label for="status">Enrollment status</label><select id="status" name="status"><option value="open" <?= ($course['status'] ?? 'open') === 'open' ? 'selected' : ''; ?>>Open</option><option value="closed" <?= ($course['status'] ?? '') === 'closed' ? 'selected' : ''; ?>>Closed</option></select></div>
                        </div>
                        <div class="form-actions"><button class="button" type="submit">Save course</button><a class="button button-secondary" href="<?= site_url('/admin/courses'); ?>">Cancel</a></div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
