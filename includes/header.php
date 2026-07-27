<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : '' ?>EMS &mdash; Employee Management</title>

    <!-- Bootstrap 5 CSS -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
    <!-- Bootstrap Icons -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
    integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD"
    crossorigin="anonymous">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:     #f4f6fb;
            --accent:      #4f46e5;
            --accent-soft: #6366f1;
            --accent-wash: #eef2ff;
            --surface:     #ffffff;
            --card:        #ffffff;
            --text:        #0f172a;
            --muted:       #64748b;
            --border:      #e5e9f0;
            --radius:      14px;
            --shadow-sm:   0 1px 2px rgba(16,24,40,.05);
            --shadow-md:   0 4px 16px rgba(16,24,40,.06);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--primary);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -.02em;
        }

        a { text-decoration: none; }

        /* ── Sidebar ── */
        .sidebar {
            width: 258px;
            min-height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-brand {
            display: flex; align-items: center; gap: .75rem;
            padding: 1.5rem 1.35rem;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--accent) 0%, #7c3aed 100%);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #fff;
            box-shadow: 0 6px 14px rgba(79,70,229,.28);
            flex-shrink: 0;
        }

        .sidebar-brand h5 {
            margin: 0; font-size: 1.1rem; font-weight: 800;
        }

        .sidebar-brand small { color: var(--muted); font-size: .72rem; }

        .sidebar-nav { padding: 1rem .75rem; flex: 1; }

        .nav-label {
            font-size: .68rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: #94a3b8;
            padding: .5rem .75rem; margin-top: .25rem;
        }

        .nav-link {
            position: relative;
            display: flex; align-items: center; gap: .7rem;
            padding: .65rem .8rem; margin-bottom: .15rem;
            border-radius: 10px;
            color: var(--muted); font-size: .9rem; font-weight: 500;
            transition: background .15s, color .15s;
        }

        .nav-link i { font-size: 1.1rem; }

        .nav-link:hover { background: #f1f5f9; color: var(--text); }

        .nav-link.active {
            background: var(--accent-wash);
            color: var(--accent);
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute; left: -.75rem; top: 20%;
            width: 3px; height: 60%;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-footer {
            padding: 1rem .75rem;
            border-top: 1px solid var(--border);
            font-size: .74rem; color: #94a3b8;
            text-align: center;
        }

        /* ── Main content ── */
        .main-content {
            margin-left: 258px;
            padding: 1.5rem 2rem 3rem;
            min-height: 100vh;
        }

        /* ── Top bar ── */
        .topbar {
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem;
            margin: -1.5rem -2rem 1.75rem;
            padding: 1.1rem 2rem;
            background: rgba(244,246,251,.85);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
        }

        .topbar h4 {
            margin: 0; font-size: 1.35rem; font-weight: 700;
        }

        .topbar .badge-env {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: .3rem .8rem;
            border-radius: 30px;
            font-size: .72rem; font-weight: 600;
        }

        /* ── Cards ── */
        .ems-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        /* ── Stat cards ── */
        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.4rem;
            display: flex; align-items: center; gap: 1rem;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s, transform .2s;
        }

        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

        .stat-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem; flex-shrink: 0;
        }

        .stat-card h3 { margin: 0; font-size: 1.55rem; font-weight: 800; }
        .stat-card p  { margin: 0; color: var(--muted); font-size: .8rem; font-weight: 500; }

        /* ── Table ── */
        .ems-table { width: 100%; border-collapse: collapse; }

        .ems-table thead th {
            font-size: .7rem; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase;
            color: #94a3b8; padding: .7rem 1rem;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .ems-table thead th:first-child { border-radius: 10px 0 0 10px; }
        .ems-table thead th:last-child  { border-radius: 0 10px 10px 0; }

        .ems-table tbody tr { border-bottom: 1px solid var(--border); }
        .ems-table tbody tr:last-child { border-bottom: none; }
        .ems-table tbody tr:hover { background: #f8fafc; }

        .ems-table tbody td {
            padding: .8rem 1rem;
            vertical-align: middle;
            font-size: .875rem;
        }

        /* Avatar initials */
        .avatar {
            width: 38px; height: 38px;
            border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700; font-size: .8rem;
            flex-shrink: 0;
        }

        /* Status badge */
        .badge-active   { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .badge-inactive { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        .status-badge {
            display: inline-block;
            padding: .22rem .65rem;
            border-radius: 8px;
            font-size: .72rem; font-weight: 600;
        }

        /* ── Buttons ── */
        .btn-accent {
            background: var(--accent);
            color: #fff; border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600; font-size: .875rem;
            padding: .55rem 1.15rem;
            box-shadow: 0 4px 12px rgba(79,70,229,.22);
            transition: background .15s, box-shadow .15s, transform .15s;
        }
        .btn-accent:hover {
            background: #4338ca; color: #fff;
            box-shadow: 0 6px 16px rgba(79,70,229,.3);
            transform: translateY(-1px);
        }

        .btn-muted {
            background: #f1f5f9; color: var(--text);
            border: 1px solid var(--border); border-radius: 10px;
            font-weight: 600; font-size: .85rem;
            padding: .55rem 1.1rem;
        }
        .btn-muted:hover { background: #e2e8f0; color: var(--text); }

        .btn-icon {
            width: 34px; height: 34px;
            border-radius: 9px; border: 1px solid var(--border);
            background: #fff; color: var(--muted);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .9rem; transition: all .15s; cursor: pointer;
        }
        .btn-icon:hover { background: #f1f5f9; color: var(--text); }
        .btn-icon.edit:hover   { color: var(--accent);  border-color: var(--accent);  background: var(--accent-wash); }
        .btn-icon.delete:hover { color: #dc2626; border-color: #fecaca; background: #fef2f2; }

        /* ── Forms ── */
        .form-label {
            font-size: .8rem; color: var(--text); font-weight: 600;
            margin-bottom: .35rem;
        }

        .form-control, .form-select {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 10px;
            font-size: .875rem;
            padding: .6rem .9rem;
        }
        .form-control:focus, .form-select:focus {
            background: #fff;
            border-color: var(--accent-soft);
            box-shadow: 0 0 0 4px rgba(99,102,241,.12);
            color: var(--text);
        }
        .form-control::placeholder { color: #94a3b8; }

        /* ── Search bar ── */
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: .85rem; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; font-size: .95rem; pointer-events: none;
        }
        .search-wrap .form-control { padding-left: 2.5rem; }

        /* ── Alerts ── */
        .ems-alert {
            border-radius: var(--radius);
            font-size: .875rem; padding: .9rem 1.1rem;
            box-shadow: var(--shadow-sm);
        }

        /* ── Modal ── */
        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            color: var(--text);
            box-shadow: 0 20px 40px rgba(16,24,40,.16);
        }
        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 1.15rem 1.4rem;
        }
        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 1rem 1.4rem;
        }

        /* ── Sidebar toggle ── */
        .sidebar-toggle {
            display: none;
            width: 38px; height: 38px;
            border-radius: 10px; border: 1px solid var(--border);
            background: #fff; color: var(--text);
            align-items: center; justify-content: center;
            font-size: 1.1rem; cursor: pointer;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15,23,42,.4);
            z-index: 99;
        }

        .sidebar-backdrop.show { display: block; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s ease; box-shadow: 0 0 40px rgba(16,24,40,.15); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1.25rem 1rem 2.5rem; }
            .topbar { margin: -1.25rem -1rem 1.25rem; padding: 1rem; }
            .sidebar-toggle { display: inline-flex; }
            .topbar h4 { font-size: 1.15rem; }
        }
    </style>
</head>
<body>

<!-- ════════════ SIDEBAR ════════════ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-people-fill"></i></div>
        <div>
            <h5>EMS</h5>
            <small>Employee Management</small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="index.php"      class="nav-link <?= (basename($_SERVER['PHP_SELF'])=='index.php')     ?'active':'' ?>">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="employees.php"  class="nav-link <?= (basename($_SERVER['PHP_SELF'])=='employees.php') ?'active':'' ?>">
            <i class="bi bi-people"></i> Employees
        </a>
        <a href="add_employee.php" class="nav-link <?= (basename($_SERVER['PHP_SELF'])=='add_employee.php') ?'active':'' ?>">
            <i class="bi bi-person-plus"></i> Add Employee
        </a>

    </nav>

    <div class="sidebar-footer">
        &copy; <?= date('Y') ?> EMS &mdash; v1.0
    </div>
</aside>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- ════════════ MAIN ════════════ -->
<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle navigation">
                <i class="bi bi-list"></i>
            </button>
            <h4><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard' ?></h4>
        </div>
        <span class="badge-env"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Live</span>
    </div>
