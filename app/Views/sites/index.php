<div class="card border-0 shadow-sm rounded-4 mb-3">
    <div class="card-body">
        <form id="siteFilters" class="row g-2">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" placeholder="Search URL">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="category">
                    <option value="">All categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars((string) $category, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $category, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="country">
                    <option value="">All countries</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?= htmlspecialchars((string) $country, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $country, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1"><input type="number" class="form-control" name="da_min" placeholder="DA min"></div>
            <div class="col-md-1"><input type="number" class="form-control" name="da_max" placeholder="DA max"></div>
            <div class="col-md-1"><input type="number" class="form-control" name="dr_min" placeholder="DR min"></div>
            <div class="col-md-1"><input type="number" class="form-control" name="dr_max" placeholder="DR max"></div>
            <div class="col-md-1"><input type="number" class="form-control" name="traffic_min" placeholder="T min"></div>
            <div class="col-md-1"><input type="number" class="form-control" name="traffic_max" placeholder="T max"></div>
            <div class="col-md-1"><input type="number" step="0.01" class="form-control" name="price_min" placeholder="$ min"></div>
            <div class="col-md-1"><input type="number" step="0.01" class="form-control" name="price_max" placeholder="$ max"></div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 position-relative">
    <div id="tableLoading" class="table-loading d-none">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <div id="sitesTableWrap">
        <?php include __DIR__ . '/table.php'; ?>
    </div>
</div>

<script>
window.CRM2Sites = {
    endpoint: '/public/ajax/fetch_sites.php',
    defaultLimit: 50
};
</script>
<script src="/public/assets/js/sites.js"></script>
