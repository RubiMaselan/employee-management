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
    integrity="sha384-4Q6Gf2oB1Qq6Zl8n6v+8a7cQ4l5Q4Q3z3z3z3z3z3z3z3z3z3z3z3z3z3z3"
    crossorigin="anonymous">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:    #1a1a2e;
            --accent:     #e94560;
            --accent-soft:#ff6b6b;
            --surface:    #16213e;
            --card:       #0f3460;
            --text:       #eaeaea;
            --muted:      #8892a4;
            --border:     rgba(255,255,255,0.08);
            --radius:     12px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--primary);
            color: var(--text);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .brand {
            font-family: 'Syne', sans-serif;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 250px;
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
            padding: 1.75rem 1.5rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: var(--accent);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #fff; margin-bottom: .6rem;
        }

        .sidebar-brand h5 {
            margin: 0; font-size: 1.15rem; font-weight: 800;
            letter-spacing: -.3px;
        }

        .sidebar-brand small { color: var(--muted); font-size: .72rem; }

        .sidebar-nav { padding: 1.25rem .75rem; flex: 1; }

        .nav-label {
            font-size: .65rem; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; color: var(--muted);
            padding: .5rem .75rem; margin-top: .5rem;
        }

        .nav-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .65rem .75rem; border-radius: 8px;
            color: var(--muted); font-size: .88rem; font-weight: 500;
            transition: all .2s; text-decoration: none;
        }

        .nav-link i { font-size: 1.05rem; }

        .nav-link:hover, .nav-link.active {
            background: rgba(233,69,96,.12);
            color: var(--accent-soft);
        }

        .nav-link.active { font-weight: 600; }

        .sidebar-footer {
            padding: 1rem .75rem;
            border-top: 1px solid var(--border);
            font-size: .75rem; color: var(--muted);
            text-align: center;
        }

        /* ── Main content ── */
        .main-content {
            margin-left: 250px;
            padding: 2rem 2.25rem;
            min-height: 100vh;
        }

        /* ── Top bar ── */
        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        .topbar h4 {
            margin: 0; font-size: 1.5rem; font-weight: 700;
        }

        .topbar .badge-env {
            background: rgba(233,69,96,.15);
            color: var(--accent-soft);
            border: 1px solid rgba(233,69,96,.25);
            padding: .3rem .75rem;
            border-radius: 30px;
            font-size: .72rem; font-weight: 600;
        }

        /* ── Cards ── */
        .ems-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
        }

        /* ── Stat cards ── */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            display: flex; align-items: center; gap: 1rem;
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; flex-shrink: 0;
        }

        .stat-card h3 { margin: 0; font-size: 1.6rem; font-weight: 800; }
        .stat-card p  { margin: 0; color: var(--muted); font-size: .8rem; }

        /* ── Table ── */
        .ems-table { width: 100%; border-collapse: separate; border-spacing: 0 6px; }

        .ems-table thead th {
            font-family: 'Syne', sans-serif;
            font-size: .7rem; font-weight: 700;
            letter-spacing: 1.2px; text-transform: uppercase;
            color: var(--muted); padding: .6rem 1rem;
            border: none; background: transparent;
        }

        .ems-table tbody tr {
            background: var(--surface);
            transition: background .15s;
        }

        .ems-table tbody tr:hover { background: rgba(15,52,96,.7); }

        .ems-table tbody td {
            padding: .85rem 1rem;
            border: none;
            vertical-align: middle;
            font-size: .875rem;
        }

        .ems-table tbody td:first-child { border-radius: var(--radius) 0 0 var(--radius); }
        .ems-table tbody td:last-child  { border-radius: 0 var(--radius) var(--radius) 0; }

        /* Avatar initials */
        .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700; font-size: .8rem;
            flex-shrink: 0;
        }

        /* Status badge */
        .badge-active   { background: rgba(34,197,94,.15); color: #4ade80; border: 1px solid rgba(34,197,94,.25); }
        .badge-inactive { background: rgba(239,68,68,.12); color: #f87171; border: 1px solid rgba(239,68,68,.2); }

        .status-badge {
            display: inline-block;
            padding: .25rem .65rem;
            border-radius: 20px;
            font-size: .72rem; font-weight: 600;
        }

        /* ── Buttons ── */
        .btn-accent {
            background: var(--accent);
            color: #fff; border: none;
            border-radius: 8px;
            font-family: 'Syne', sans-serif;
            font-weight: 600; font-size: .875rem;
            padding: .55rem 1.2rem;
            transition: all .2s;
        }
        .btn-accent:hover { background: #c73652; color: #fff; transform: translateY(-1px); }

        .btn-icon {
            width: 32px; height: 32px;
            border-radius: 7px; border: 1px solid var(--border);
            background: transparent; color: var(--muted);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .9rem; transition: all .2s; cursor: pointer;
        }
        .btn-icon:hover { color: var(--text); border-color: var(--muted); }
        .btn-icon.edit:hover  { color: #60a5fa; border-color: #60a5fa; }
        .btn-icon.delete:hover{ color: var(--accent); border-color: var(--accent); }

        /* ── Forms ── */
        .form-label { font-size: .82rem; color: var(--muted); font-weight: 500; margin-bottom: .35rem; }

        .form-control, .form-select {
            background: var(--primary);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 8px;
            font-size: .875rem;
            padding: .55rem .9rem;
        }
        .form-control:focus, .form-select:focus {
            background: var(--primary);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(233,69,96,.15);
            color: var(--text);
        }
        .form-control::placeholder { color: #4a5568; }
        .form-select option { background: var(--primary); }

        /* ── Search bar ── */
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: .85rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: .95rem; pointer-events: none;
        }
        .search-wrap .form-control { padding-left: 2.5rem; }

        /* ── Alerts ── */
        .ems-alert {
            border-radius: var(--radius); border: none;
            font-size: .875rem; padding: .9rem 1.1rem;
        }

        /* ── Modal ── */
        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            color: var(--text);
        }
        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 1.25rem 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 1rem 1.5rem;
        }
        .btn-close { filter: invert(1) brightness(.7); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 1.25rem; }
        }
    </style>
</head>
<body>

<!-- ════════════ SIDEBAR ════════════ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-people-fill"></i></div>
        <h5>EMS</h5>
        <small>Employee Management</small>
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

        <div class="nav-label" style="margin-top:1rem;">System</div>
        <a href="setup.php" class="nav-link">
            <i class="bi bi-database"></i> DB Setup / Reset
        </a>
    </nav>

    <div class="sidebar-footer">
        &copy; <?= date('Y') ?> EMS &mdash; v1.0
    </div>
</aside>

<!-- ════════════ MAIN ════════════ -->
<div class="main-content">
    <div class="topbar">
        <h4><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard' ?></h4>
        <span class="badge-env"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Live</span>
    </div>
