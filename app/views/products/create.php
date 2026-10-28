<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add inventory item | TESDA NEXUS</title>
    <link rel="stylesheet" href="<?= site_url('/css/nexus.css'); ?>">
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <a class="brand" href="<?= site_url('/dashboard'); ?>"><img src="<?= site_url('/images/tsda.jpg'); ?>" alt="">TESDA NEXUS</a>
            <nav class="side-nav" aria-label="Administration">
                <a href="<?= site_url('/dashboard'); ?>">Analytics</a>
                <a href="<?= site_url('/show-users'); ?>">Manage users</a>
                <a href="<?= site_url('/admin/courses'); ?>">Manage NC courses</a>
                <a href="<?= site_url('/products'); ?>" aria-current="page">Manage inventory</a>
            </nav>
            <div class="sidebar-bottom side-nav"><a href="<?= site_url('/logout'); ?>">Log out</a></div>
        </aside>
        <div class="workspace">
            <header class="app-header"><p>Inventory</p><a class="button button-secondary button-small" href="<?= site_url('/products'); ?>">Back to inventory</a></header>
            <main class="page-content">
                <section class="page-heading">
                    <div><h1>Add an item</h1><p>Enter the item details to add it to the inventory catalog.</p></div>
                </section>
                <?php if (!empty($errors)): ?>
                    <ul class="error-list" role="alert">
                        <?php foreach ($errors as $error): ?><li><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></li><?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <section class="panel">
                    <form action="<?= site_url('/products/create'); ?>" method="post">
                        <div class="field">
                            <label for="product_name">Item name</label>
                            <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($_POST['product_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="field">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" required><?= htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                        <div class="content-grid">
                            <div class="field">
                                <label for="price">Price</label>
                                <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($_POST['price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="field">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity" min="0" value="<?= htmlspecialchars($_POST['quantity'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit">Add to inventory</button>
                            <a class="button button-secondary" href="<?= site_url('/products'); ?>">Cancel</a>
                        </div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
