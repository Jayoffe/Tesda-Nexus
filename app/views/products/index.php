<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Main navigation">
                <?php if (($user_role ?? '') === 'admin'): ?>
                    <a href="<?= site_url('/dashboard'); ?>">Analytics</a>
                    <a href="<?= site_url('/show-users'); ?>">Manage users</a>
                    <a href="<?= site_url('/admin/courses'); ?>">Manage NC courses</a>
                    <a href="<?= site_url('/admin/centers'); ?>">Training centers</a>
                    <a href="<?= site_url('/products'); ?>" aria-current="page">Manage inventory</a>
                <?php elseif (($user_role ?? '') === 'instructor'): ?>
                    <a href="<?= site_url('/instructor'); ?>">Instructor portal</a>
                    <a href="<?= site_url('/products'); ?>" aria-current="page">Inventory</a>
                <?php else: ?>
                    <a href="<?= site_url('/dashboard'); ?>">Dashboard</a>
                    <a href="<?= site_url('/my-courses'); ?>">My courses</a>
                    <a href="<?= site_url('/student-profile'); ?>">My profile</a>
                    <a href="<?= site_url('/products'); ?>" aria-current="page">Inventory</a>
                <?php endif; ?>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>TESDA student services</p><span class="user-chip"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">Inventory</span></header>
            <main class="page-content">
                <section class="page-heading">
                    <div>
                        <h1>Inventory</h1>
                        <p>Browse the resources and items available in the catalog.</p>
                    </div>
                    <?php if (($user_role ?? '') === 'admin'): ?>
                        <a class="button" href="<?= site_url('/products/create'); ?>">Add an item</a>
                    <?php endif; ?>
                </section>
                <?php if (!empty($notification)): ?>
                    <p class="notice notice-success" role="status"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
                <section class="data-panel">
                    <?php if (!empty($products)): ?>
                        <div class="table-scroll">
                            <table class="data-table">
                                <thead><tr><th>Item</th><th>Description</th><th>Price</th><th>Quantity</th><th>Added</th><?php if (($user_role ?? '') === 'admin'): ?><th>Actions</th><?php endif; ?></tr></thead>
                                <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($product['product_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                        <td><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>₱<?= htmlspecialchars((string) ($product['price'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars((string) ($product['quantity'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars($product['created_at'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <?php if (($user_role ?? '') === 'admin'): ?>
                                            <td><div class="row-actions">
                                                <a href="<?= site_url('/products/edit/' . rawurlencode((string) ($product['id'] ?? ''))); ?>">Edit</a>
                                                <a class="delete" href="<?= site_url('/products/delete/' . rawurlencode((string) ($product['id'] ?? ''))); ?>" onclick="return confirm('Delete this item?');">Delete</a>
                                            </div></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <strong>No inventory items yet</strong>
                            <span><?php if (($user_role ?? '') === 'admin'): ?>Add an item to start building the catalog.<?php else: ?>Items added to the catalog will appear here.<?php endif; ?></span>
                        </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
