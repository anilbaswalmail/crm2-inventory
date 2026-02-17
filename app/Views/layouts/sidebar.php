<aside id="sidebar" class="sidebar">
    <div class="sidebar-brand">CRM2</div>
    <nav class="nav flex-column px-2">
        <a class="nav-link" href="/dashboard">Dashboard</a>
        <a class="nav-link" href="/sites">Sites</a>
        <a class="nav-link" href="#">Bulk Upload</a>
        <a class="nav-link disabled" aria-disabled="true" href="#">Tags</a>
        <a class="nav-link disabled" aria-disabled="true" href="#">Analytics</a>
        <a class="nav-link disabled" aria-disabled="true" href="#">Settings</a>
    </nav>
</aside>
<main class="content-wrap">
    <header class="topbar d-flex justify-content-between align-items-center">
        <button id="sidebarToggle" class="btn btn-sm btn-outline-secondary">☰</button>
        <div class="fw-semibold"><?= htmlspecialchars($pageTitle ?? '', ENT_QUOTES, 'UTF-8') ?></div>
    </header>
    <section class="content-body container-fluid py-3">
