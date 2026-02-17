(() => {
  const config = window.CRM2Sites;
  if (!config) return;

  const form = document.getElementById('siteFilters');
  const wrap = document.getElementById('sitesTableWrap');
  const loading = document.getElementById('tableLoading');

  const state = {
    page: 1,
    limit: config.defaultLimit || 50,
    sort_by: 'id',
    sort_dir: 'DESC'
  };

  let debounceTimer = null;

  const showLoading = (active) => loading?.classList.toggle('d-none', !active);

  const payload = () => {
    const params = new URLSearchParams(new FormData(form));
    Object.entries(state).forEach(([k, v]) => params.set(k, String(v)));
    return params.toString();
  };

  const renderTable = (result) => {
    const rows = result.data || [];
    const total = result.total || 0;
    const page = result.page || 1;
    const pages = result.pages || 1;

    const columns = [
      ['site_url', 'Site URL'], ['category', 'Category'], ['country', 'Country'], ['da', 'DA'],
      ['dr', 'DR'], ['traffic', 'Traffic'], ['price', 'Price'], ['created_at', 'Created']
    ];

    const head = columns.map(([k, label]) => {
      const next = state.sort_by === k && state.sort_dir === 'ASC' ? 'DESC' : 'ASC';
      return `<th><button class="btn btn-link p-0 table-sort" data-sort="${k}" data-dir="${next}">${label}</button></th>`;
    }).join('');

    const body = rows.length
      ? rows.map((r) => `<tr>
          <td>${escapeHtml(r.site_url)}</td>
          <td>${escapeHtml(r.category)}</td>
          <td>${escapeHtml(r.country)}</td>
          <td>${escapeHtml(r.da)}</td>
          <td>${escapeHtml(r.dr)}</td>
          <td>${escapeHtml(r.traffic)}</td>
          <td>$${Number(r.price).toFixed(2)}</td>
          <td>${escapeHtml(r.created_at)}</td>
        </tr>`).join('')
      : '<tr><td colspan="8" class="text-center py-4 text-muted">No sites match these filters.</td></tr>';

    wrap.innerHTML = `
      <div class="table-responsive table-sticky-wrap">
        <table class="table align-middle mb-0">
          <thead><tr>${head}</tr></thead>
          <tbody>${body}</tbody>
        </table>
      </div>
      <div class="d-flex justify-content-between align-items-center p-3 border-top">
        <small class="text-muted">Total: ${total} records</small>
        <div class="btn-group">
          <button class="btn btn-outline-secondary btn-sm table-page" data-page="${Math.max(1, page - 1)}" ${page <= 1 ? 'disabled' : ''}>Prev</button>
          <button class="btn btn-outline-secondary btn-sm" disabled>Page ${page} / ${Math.max(1, pages)}</button>
          <button class="btn btn-outline-secondary btn-sm table-page" data-page="${Math.min(Math.max(1, pages), page + 1)}" ${page >= pages ? 'disabled' : ''}>Next</button>
        </div>
      </div>`;
  };

  const fetchSites = async () => {
    showLoading(true);
    try {
      const res = await fetch(`${config.endpoint}?${payload()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const json = await res.json();
      renderTable(json);
    } catch (e) {
      console.error(e);
    } finally {
      showLoading(false);
    }
  };

  const escapeHtml = (value) => {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
  };

  form.addEventListener('input', (event) => {
    if (event.target.name === 'search') {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        state.page = 1;
        fetchSites();
      }, 500);
      return;
    }

    state.page = 1;
    fetchSites();
  });

  form.addEventListener('change', () => {
    state.page = 1;
    fetchSites();
  });

  wrap.addEventListener('click', (event) => {
    const sortBtn = event.target.closest('.table-sort');
    if (sortBtn) {
      state.sort_by = sortBtn.dataset.sort;
      state.sort_dir = sortBtn.dataset.dir;
      state.page = 1;
      fetchSites();
      return;
    }

    const pageBtn = event.target.closest('.table-page');
    if (pageBtn && !pageBtn.disabled) {
      state.page = Number(pageBtn.dataset.page || 1);
      fetchSites();
    }
  });

  fetchSites();
})();
