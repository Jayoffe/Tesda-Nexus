<?php $courseRows = $courses ?? []; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NC course catalog | TESDA NEXUS</title>
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
            <header class="app-header"><p>NC course management</p><a class="button button-secondary button-small" href="<?= site_url('/dashboard'); ?>">Back to analytics</a></header>
            <main class="page-content role-page role-courses">
                <section class="page-heading role-heading">
                    <div><h1>NC course catalog</h1><p><?= !empty($is_center_admin) ? 'Manage the NC courses offered at your training center.' : 'Maintain the National Certificate courses available across training centers.'; ?></p></div>
                    <a class="button" href="<?= site_url('/admin/courses/create'); ?>">Add course</a>
                </section>
                <?php if (!empty($notification)): ?><p class="notice notice-success" role="status"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                <section class="data-panel role-data-panel">
                    <?php if ($courseRows): ?>
                        <div class="table-scroll">
                            <table class="data-table management-table">
                                <thead><tr><th>Course</th><th>Training center</th><th>Qualification</th><th>Enrolled</th><th>Status</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <?php foreach ($courseRows as $course): ?>
                                        <tr>
                                            <td><a href="<?= site_url('/course/' . (int) $course['id']); ?>"><strong><?= htmlspecialchars($course['course_name'], ENT_QUOTES, 'UTF-8'); ?></strong></a><br><?= htmlspecialchars($course['course_code'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($course['training_center_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($course['qualification'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= (int) $course['enrollment_count']; ?> / <?= (int) $course['capacity']; ?></td>
                                            <td><span class="status <?= $course['status'] === 'open' ? 'status-active' : 'status-inactive'; ?>"><?= htmlspecialchars(ucfirst($course['status']), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td class="row-actions">
                                                <a href="<?= site_url('/admin/courses/edit/' . (int) $course['id']); ?>">Edit</a>
                                                <form action="<?= site_url('/admin/courses/delete/' . (int) $course['id']); ?>" method="post" onsubmit="return confirm('Delete this course and its enrollment records?');">
                                                    <button class="text-button delete" type="submit">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state"><strong>No courses in the catalog</strong><span>Add an NC course to make it available for instructor enrollment.</span></div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
