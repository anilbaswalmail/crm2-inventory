<?php
$rows = $rows ?? [];
$total = $total ?? 0;
$page = $page ?? 1;
$pages = $pages ?? 1;
$sortBy = $sort_by ?? 'id';
$sortDir = $sort_dir ?? 'DESC';
?>
<div class="table-responsive table-sticky-wrap">
    <table class="table align-middle mb-0">
        <thead>
        <tr>
            <?php
            $columns = ['site_url' => 'Site URL', 'category' => 'Category', 'country' => 'Country', 'da' => 'DA', 'dr' => 'DR', 'traffic' => 'Traffic', 'price' => 'Price', 'created_at' => 'Created'];
            foreach ($columns as $key => $label):
                $nextDir = ($sortBy === $key && strtoupper($sortDir) === 'ASC') ? 'DESC' : 'ASC';
            ?>
                <th><button class="btn btn-link p-0 table-sort" data-sort="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" data-dir="<?= $nextDir ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></button></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php if ($rows === []): ?>
            <tr><td colspan="8" class="text-center py-4 text-muted">No sites match these filters.</td></tr>
        <?php else: ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $row['site_url'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $row['category'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $row['country'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $row['da'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $row['dr'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $row['traffic'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>$<?= htmlspecialchars(number_format((float) $row['price'], 2), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center p-3 border-top">
    <small class="text-muted">Total: <?= (int) $total ?> records</small>
    <div class="btn-group">
        <button class="btn btn-outline-secondary btn-sm table-page" data-page="<?= max(1, $page - 1) ?>" <?= $page <= 1 ? 'disabled' : '' ?>>Prev</button>
        <button class="btn btn-outline-secondary btn-sm" disabled>Page <?= (int) $page ?> / <?= max(1, (int) $pages) ?></button>
        <button class="btn btn-outline-secondary btn-sm table-page" data-page="<?= min(max(1, (int) $pages), $page + 1) ?>" <?= $page >= $pages ? 'disabled' : '' ?>>Next</button>
    </div>
</div>
