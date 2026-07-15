/**
 * dashboard.js
 * Behaviour layer for the "Digital Ledger" admin dashboard.
 * Reads its server-rendered data from the JSON island #dash-data
 * (see dashboard.blade.php) so this file stays framework-agnostic.
 */

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('dashRoot');
    if (!root) return;

    const dataEl = document.getElementById('dash-data');
    const data = dataEl ? JSON.parse(dataEl.textContent || '{}') : {};

    initTheme(root);
    initRefresh();
    initCounters(root);
    initChart(data);
    initTableFilters();
    initTransactionsToggle();
  });

  /* ------------------------------------------------------------------
   * Theme (dark / light) — persisted in localStorage
   * ---------------------------------------------------------------- */
  function initTheme(root) {
    const buttons = document.querySelectorAll('[data-theme-toggle]');

    const paint = function (theme) {
      root.setAttribute('data-theme', theme);
      buttons.forEach(function (btn) {
        const icon = btn.querySelector('i');
        const label = btn.querySelector('span');
        if (icon) icon.className = theme === 'dark' ? 'fa fa-sun' : 'fa fa-moon';
        if (label) label.textContent = theme === 'dark' ? 'Light mode' : 'Dark mode';
      });
    };

    paint(localStorage.getItem('dashboardTheme') || 'light');

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        localStorage.setItem('dashboardTheme', next);
        paint(next);
      });
    });
  }

  /* ------------------------------------------------------------------
   * Refresh button + "last updated" clock
   * ---------------------------------------------------------------- */
  function initRefresh() {
    const btn = document.getElementById('refreshBtn');
    const label = document.getElementById('lastUpdatedLabel');

    if (btn) {
      btn.addEventListener('click', function () {
        btn.querySelector('i').classList.add('spin');
        btn.disabled = true;
        setTimeout(function () { window.location.reload(); }, 450);
      });
    }

    if (label) {
      const tick = function () {
        label.textContent = 'Last updated ' + new Date().toLocaleTimeString();
      };
      tick();
      setInterval(tick, 60000);
    }
  }

  /* ------------------------------------------------------------------
   * Odometer-style animated counters (fires when scrolled into view)
   * ---------------------------------------------------------------- */
  function initCounters(root) {
    const counters = root.querySelectorAll('.stat-card__value[data-target]');

    const run = function (el) {
      const target = parseFloat(el.dataset.target) || 0;
      const prefix = el.dataset.prefix || '';
      const duration = 900;
      const start = performance.now();

      const step = function (now) {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = Math.round(target * eased);
        el.textContent = prefix + value.toLocaleString('en-IN');
        if (progress < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };

    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver(function (entries, obs) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            run(entry.target);
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.4 });

      counters.forEach(function (el) { observer.observe(el); });
    } else {
      counters.forEach(run);
    }
  }

  /* ------------------------------------------------------------------
   * Chart.js — bar/line toggle + doughnut breakdown
   * ---------------------------------------------------------------- */
  function initChart(data) {
    const canvas = document.getElementById('dashboardChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const palette = {
      grid: getComputedStyle(document.getElementById('dashRoot')).getPropertyValue('--border').trim(),
      text: getComputedStyle(document.getElementById('dashRoot')).getPropertyValue('--ink-soft').trim()
    };

    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.color = palette.text || '#64748b';

    let trendChart = new Chart(canvas, buildTrendConfig('bar', data, palette));

    document.querySelectorAll('[data-chart-type]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('[data-chart-type]').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        trendChart.destroy();
        trendChart = new Chart(canvas, buildTrendConfig(btn.dataset.chartType, data, palette));
      });
    });

    const pieCanvas = document.getElementById('servicePieChart');
    if (pieCanvas && data.serviceBreakdown) {
      new Chart(pieCanvas, {
        type: 'doughnut',
        data: {
          labels: data.serviceBreakdown.labels,
          datasets: [{
            data: data.serviceBreakdown.values,
            backgroundColor: ['#6366f1', '#22c55e', '#f43f5e', '#334155', '#f59e0b'],
            borderWidth: 0,
            hoverOffset: 6
          }]
        },
        options: {
          responsive: true,
          cutout: '68%',
          plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14, usePointStyle: true } } }
        }
      });
    }
  }

  function buildTrendConfig(type, data, palette) {
    const isLine = type === 'line';
    return {
      type: type,
      data: {
        labels: data.months || [],
        datasets: [{
          label: 'Applications',
          data: data.chartValues || [],
          backgroundColor: isLine ? 'rgba(99,102,241,0.14)' : gradientBars(),
          borderColor: '#6366f1',
          borderWidth: isLine ? 2.5 : 0,
          borderRadius: isLine ? 0 : 10,
          maxBarThickness: 34,
          fill: isLine,
          tension: 0.4,
          pointRadius: isLine ? 3 : 0,
          pointBackgroundColor: '#6366f1'
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, border: { display: false } },
          y: {
            grid: { color: palette.grid || 'rgba(15,23,42,0.06)' },
            border: { display: false },
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    };

    function gradientBars() { return '#6366f1'; }
  }

  /* ------------------------------------------------------------------
   * Recent Applications: search + status pills + status cards + sort
   * ---------------------------------------------------------------- */
  function initTableFilters() {
    const searchInput = document.getElementById('appSearchInput');
    const pills = document.querySelectorAll('[data-status-filter]');
    const tableBody = document.getElementById('appTableBody');
    const noResults = document.getElementById('noResultsMessage');
    let activeStatus = 'all';

    const apply = function () {
      if (!tableBody) return;
      const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
      const rows = tableBody.querySelectorAll('tr[data-name]');
      let visible = 0;

      rows.forEach(function (row) {
        const matchesQuery = !query || row.dataset.name.includes(query) || row.dataset.service.includes(query);
        const matchesStatus = activeStatus === 'all' || row.dataset.status === activeStatus;
        const show = matchesQuery && matchesStatus;
        row.classList.toggle('row-hidden', !show);
        if (show) visible++;
      });

      if (noResults) noResults.style.display = (rows.length > 0 && visible === 0) ? '' : 'none';
    };

    if (searchInput) searchInput.addEventListener('input', apply);

    pills.forEach(function (pill) {
      pill.addEventListener('click', function () {
        pills.forEach(function (p) { p.classList.remove('active'); });
        pill.classList.add('active');
        activeStatus = pill.dataset.statusFilter;
        apply();
      });
    });

    document.querySelectorAll('[data-status-card]').forEach(function (card) {
      card.addEventListener('click', function () {
        const status = card.dataset.statusCard;
        const match = document.querySelector('[data-status-filter="' + status + '"]');
        if (match) {
          match.click();
          const table = document.getElementById('appTable');
          if (table) table.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      });
    });

    let sortState = {};
    document.querySelectorAll('th.sortable').forEach(function (th) {
      th.addEventListener('click', function () {
        if (!tableBody) return;
        const key = th.dataset.sortKey;
        const direction = sortState[key] === 'asc' ? 'desc' : 'asc';
        sortState = {};
        sortState[key] = direction;

        document.querySelectorAll('th.sortable i').forEach(function (i) { i.className = 'fa fa-sort'; });
        th.querySelector('i').className = direction === 'asc' ? 'fa fa-sort-up' : 'fa fa-sort-down';

        const rows = Array.from(tableBody.querySelectorAll('tr[data-name]'));
        rows.sort(function (a, b) {
          const valA = a.dataset[key] || '';
          const valB = b.dataset[key] || '';
          if (valA < valB) return direction === 'asc' ? -1 : 1;
          if (valA > valB) return direction === 'asc' ? 1 : -1;
          return 0;
        });
        rows.forEach(function (row) { tableBody.appendChild(row); });
      });
    });
  }

  /* ------------------------------------------------------------------
   * Wallet transactions show more / less
   * ---------------------------------------------------------------- */
  function initTransactionsToggle() {
    const btn = document.getElementById('toggleTransactionsBtn');
    if (!btn) return;
    btn.addEventListener('click', function () {
      const extras = document.querySelectorAll('.transaction-extra');
      const isHidden = extras.length && extras[0].classList.contains('d-none');
      extras.forEach(function (row) { row.classList.toggle('d-none', !isHidden); });
      btn.textContent = isHidden ? 'Show less' : 'Show all';
    });
  }
})();
