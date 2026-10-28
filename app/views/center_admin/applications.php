<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Center applications | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Training center administration">
                <a href="<?= site_url('/dashboard'); ?>">Center overview</a>
                <a href="<?= site_url('/center/applications'); ?>" aria-current="page">Applications</a>
                <a href="<?= site_url('/show-users'); ?>">Manage users</a>
                <a href="<?= site_url('/admin/courses'); ?>">Manage NC courses</a>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>Center applications</p><span class="user-chip"><?= htmlspecialchars($center_name ?: 'Training center', ENT_QUOTES, 'UTF-8'); ?></span></header>
            <main class="page-content role-page role-users">
                <section class="page-heading role-heading">
                    <div>
                        <h1>Applications</h1>
                        <p>Approve student and instructor applicants for your training center.</p>
                    </div>
                </section>
                <?php if (!empty($notification)): ?><p class="notice notice-success" role="status"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                <section class="data-panel role-data-panel">
                    <?php if (!empty($applications)): ?>
                        <div class="table-scroll">
                            <table class="data-table management-table">
                                <thead><tr><th>Applicant</th><th>Email</th><th>Applying as</th><th>Submitted</th><th>Decision</th></tr></thead>
                                <tbody>
                                    <?php foreach ($applications as $application): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($application['full_name'] ?: $application['username'], ENT_QUOTES, 'UTF-8'); ?></strong><br><?= htmlspecialchars($application['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($application['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars(ucfirst($application['applied_role']), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= htmlspecialchars($application['applied_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td class="row-actions">
                                                <form action="<?= site_url('/center/applications/' . (int) $application['application_id'] . '/approve'); ?>" method="post">
                                                    <button class="button button-small" type="submit">Approve</button>
                                                </form>
                                                <form action="<?= site_url('/center/applications/' . (int) $application['application_id'] . '/reject'); ?>" method="post" onsubmit="return confirm('Reject this application?');">
                                                    <button class="text-button delete" type="submit">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state"><strong>No applications awaiting review</strong><span>New student and instructor applications for your center will appear here.</span></div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
