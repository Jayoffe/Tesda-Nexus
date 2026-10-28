<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin dashboard | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Administration">
                <a href="<?= site_url('/dashboard'); ?>" aria-current="page">Analytics</a>
                <a href="<?= site_url('/show-users'); ?>">Manage users</a>
                <a href="<?= site_url('/admin/courses'); ?>">Manage NC courses</a>
                <a href="<?= site_url('/admin/centers'); ?>">Training centers</a>
                <a href="<?= site_url('/products'); ?>">Manage inventory</a>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header">
                <p>Administration</p>
                <span class="user-chip"><?= htmlspecialchars($username ?? 'Administrator', ENT_QUOTES, 'UTF-8'); ?> · Admin</span>
            </header>
            <main class="page-content role-page role-admin">
                <section class="page-heading role-heading">
                    <div>
                        <h1>Portal analytics</h1>
                        <p>Live totals from the accounts, NC course catalog, enrollments, and inventory.</p>
                    </div>
                </section>
                <div class="stats-grid role-stats">
                    <section class="stat-card"><span>Students</span><strong><?= (int) $analytics['students']; ?></strong><small>Registered accounts</small></section>
                    <section class="stat-card"><span>Instructors</span><strong><?= (int) $analytics['instructors']; ?></strong><small>Registered accounts</small></section>
                    <section class="stat-card"><span>NC courses</span><strong><?= (int) $analytics['courses']; ?></strong><small>In the course catalog</small></section>
                    <section class="stat-card stat-card-featured"><span>Enrollments</span><strong><?= (int) $analytics['enrollments']; ?></strong><small>Across all courses</small></section>
                </div>
                <div class="content-grid role-admin-links">
                    <section class="panel role-panel">
                        <h2 class="section-title">Administration</h2>
                        <div class="list-rows">
                            <a class="list-row admin-action-link" href="<?= site_url('/show-users'); ?>"><strong>User accounts</strong><span>Manage roles and account access</span></a>
                            <a class="list-row admin-action-link" href="<?= site_url('/admin/courses'); ?>"><strong>NC course catalog</strong><span>Create and update training courses</span></a>
                            <a class="list-row admin-action-link" href="<?= site_url('/admin/centers'); ?>"><strong>Training centers</strong><span>Set up locations and assign local offerings</span></a>
                            <a class="list-row admin-action-link" href="<?= site_url('/products'); ?>"><strong>Inventory</strong><span><?= (int) $analytics['products']; ?> items in the catalog</span></a>
                        </div>
                    </section>
                    <section class="panel role-panel access-panel">
                        <h2 class="section-title">Account access</h2>
                        <p class="muted-copy">Instructors manage student enrollments. Students can view their own profile and course registrations.</p>
                        <div class="access-roles">
                            <div><strong>Instructor</strong><span>Student rosters and enrollment</span></div>
                            <div><strong>Student</strong><span>Personal profile and NC courses</span></div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
