<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Main navigation">
                <a href="<?= site_url('/dashboard'); ?>" aria-current="page">Dashboard</a>
                <a href="<?= site_url('/profile'); ?>">Student portal</a>
                <a href="<?= site_url('/student-profile'); ?>">Student profile</a>
                <a href="<?= site_url('/products'); ?>">Inventory</a>
                <a href="<?= site_url('/show-users'); ?>">User directory</a>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header">
                <p>TESDA student services</p>
                <span class="user-chip"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt=""> <?= htmlspecialchars($username ?? 'Account', ENT_QUOTES, 'UTF-8'); ?></span>
            </header>
            <main class="page-content">
                <section class="page-heading">
                    <div>
                        <h1>Dashboard</h1>
                        <p>Welcome back, <?= htmlspecialchars($username ?? 'there', ENT_QUOTES, 'UTF-8'); ?>. Here is a quick overview of the portal.</p>
                    </div>
                </section>
                <div class="stats-grid" aria-label="Sample portal figures">
                    <section class="stat-card"><span>Training programs</span><strong>05</strong><span>Sample data</span></section>
                    <section class="stat-card"><span>Enrolled learners</span><strong>87</strong><span>Sample data</span></section>
                    <section class="stat-card"><span>Inventory items</span><strong>24</strong><span>Sample data</span></section>
                    <section class="stat-card"><span>Open requests</span><strong>03</strong><span>Sample data</span></section>
                </div>
                <div class="content-grid">
                    <section class="panel">
                        <h2 class="section-title">Training programs</h2>
                        <div class="list-rows">
                            <div class="list-row"><strong>Cookery NC II</strong><span class="status status-active">Ongoing</span></div>
                            <div class="list-row"><strong>Bread and Pastry Production NC II</strong><span class="status status-active">Ongoing</span></div>
                            <div class="list-row"><strong>Computer Systems Servicing NC II</strong><span class="status status-pending">Enrolling</span></div>
                        </div>
                    </section>
                    <section class="panel">
                        <h2 class="section-title">Quick links</h2>
                        <div class="list-rows">
                            <a class="list-row" href="<?= site_url('/student-profile'); ?>"><strong>View student profile</strong><span>Open</span></a>
                            <a class="list-row" href="<?= site_url('/products'); ?>"><strong>Browse inventory</strong><span>Open</span></a>
                            <?php if (($user_role ?? '') === 'admin'): ?>
                                <a class="list-row" href="<?= site_url('/show-users'); ?>"><strong>Manage user directory</strong><span>Open</span></a>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
