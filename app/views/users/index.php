<?php $userRows = $users ?? []; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User directory | TESDA NEXUS</title>
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
            <header class="app-header"><p>Account administration</p><span class="user-chip">User directory</span></header>
            <main class="page-content role-page role-users">
                <section class="page-heading role-heading">
                    <div>
                        <h1>User directory</h1>
                        <p><?= !empty($is_center_admin) ? 'Manage student and instructor accounts assigned to your center.' : 'Manage TESDA administrators, center organizers, instructors, and students.'; ?></p>
                    </div>
                    <a class="button" href="<?= site_url('/admin/users/create'); ?>">Create account</a>
                </section>
                <?php if (!empty($notification)): ?><p class="notice notice-success" role="status"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                <div class="stats-grid role-stats role-account-stats">
                    <section class="stat-card"><span>Registered accounts</span><strong><?= count($userRows); ?></strong></section>
                </div>
                <section class="data-panel role-data-panel">
                    <?php if (!empty($userRows)): ?>
                        <div class="table-scroll">
                            <table class="data-table management-table">
                                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Training center</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <?php foreach ($userRows as $user): ?>
                                        <?php $isActive = (int) ($user['is_active'] ?? 1) === 1; ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($user['full_name'] ?: $user['username'], ENT_QUOTES, 'UTF-8'); ?></strong><br><?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <?php $roleLabels = ['center_admin' => 'Center organizer', 'admin' => 'TESDA administrator', 'instructor' => 'Instructor', 'student' => 'Student']; ?>
                                            <td><span class="role-label role-label-<?= htmlspecialchars($user['role'] ?? 'student', ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($roleLabels[$user['role'] ?? ''] ?? ucfirst($user['role'] ?? 'student'), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td><?= htmlspecialchars($user['training_center_name'] ?? 'TESDA central office', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php if (($user['application_status'] ?? '') === 'pending'): ?>
                                                    <span class="status status-inactive">Pending review</span>
                                                <?php elseif (($user['application_status'] ?? '') === 'rejected'): ?>
                                                    <span class="status status-inactive">Application rejected</span>
                                                <?php else: ?>
                                                    <span class="status <?= $isActive ? 'status-active' : 'status-inactive'; ?>"><?= $isActive ? 'Active' : 'Inactive'; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($user['created_at'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="row-actions">
                                                <?php $canManageRow = empty($is_center_admin) || in_array($user['role'] ?? '', ['student', 'instructor'], true); ?>
                                                <?php if ($canManageRow && !in_array($user['application_status'] ?? '', ['pending', 'rejected'], true)): ?>
                                                    <a href="<?= site_url('/admin/users/edit/' . (int) $user['id']); ?>">Edit</a>
                                                <?php endif; ?>
                                                <?php if ($canManageRow && (int) $user['id'] !== (int) $current_user_id && !in_array($user['application_status'] ?? '', ['pending', 'rejected'], true)): ?>
                                                    <form action="<?= site_url('/admin/users/delete/' . (int) $user['id']); ?>" method="post" onsubmit="return confirm('Delete this account? Enrollments for this student will also be removed.');">
                                                        <button class="text-button delete" type="submit">Delete</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <strong>No user accounts yet</strong>
                            <span>Once accounts are added, they will appear in this directory.</span>
                        </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
