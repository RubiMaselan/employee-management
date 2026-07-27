<?php
$pageTitle = 'Dashboard';
require_once 'config/database.php'; //make sure correct path
require_once 'includes/header.php';

// ── Stats ──────────────────────────────────────────────
$total      = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*)                         FROM employees"))[0];
$active     = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM employees WHERE status='Active'"))[0];
$inactive   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM employees WHERE status='Inactive'"))[0];
$avgSalary  = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(AVG(salary),0)          FROM employees"))[0];

// ── Department breakdown ───────────────────────────────
$deptResult = mysqli_query($conn, "SELECT department, COUNT(*) as cnt FROM employees WHERE department != '' GROUP BY department ORDER BY cnt DESC LIMIT 5");

// ── Recent employees ───────────────────────────────────
$recentResult = mysqli_query($conn, "SELECT * FROM employees ORDER BY created_at DESC LIMIT 5");

// Avatar colour palette
$colours = ['#4f46e5','#0ea5e9','#8b5cf6','#059669','#d97706','#db2777'];
?>

<!-- ── Stat cards ──────────────────────────────────────── -->
<div class="row g-3 mb-4">
    <?php
    $stats = [
        ['Total Employees',   $total,    'bi-people-fill',       '#4f46e5', '#eef2ff'],
        ['Active',            $active,   'bi-person-check-fill', '#059669', '#ecfdf5'],
        ['Inactive',          $inactive, 'bi-person-x-fill',     '#d97706', '#fffbeb'],
        ['Avg. Salary (RM)',  number_format($avgSalary, 2), 'bi-cash-coin', '#0ea5e9', '#f0f9ff'],
    ];
    foreach ($stats as [$label, $value, $icon, $color, $bg]) : ?>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:<?= $bg ?>; color:<?= $color ?>">
                <i class="bi <?= $icon ?>"></i>
            </div>
            <div>
                <h3 style="color:<?= $color ?>"><?= $value ?></h3>
                <p><?= $label ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ── Bottom grid ────────────────────────────────────── -->
<div class="row g-3">

    <!-- Recent employees -->
    <div class="col-lg-8">
        <div class="ems-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="mb-0" style="font-weight:700;">Recent Employees</h6>
                <a href="employees.php" class="btn btn-accent" style="padding:.4rem .9rem;font-size:.78rem;">View All</a>
            </div>

            <?php if (mysqli_num_rows($recentResult) === 0) : ?>
                <p class="text-center py-4" style="color:var(--muted);">No employees yet. <a href="add_employee.php" style="color:var(--accent-soft);">Add one!</a></p>
            <?php else : ?>
            <table class="ems-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($emp = mysqli_fetch_assoc($recentResult)) :
                    $initials = strtoupper(substr($emp['first_name'],0,1).substr($emp['last_name'],0,1));
                    $col = $colours[abs(crc32($emp['email'])) % count($colours)];
                ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="background:<?= $col ?>22;color:<?= $col ?>"><?= $initials ?></div>
                                <div>
                                    <div style="font-weight:600;font-size:.85rem;"><?= htmlspecialchars($emp['first_name'].' '.$emp['last_name']) ?></div>
                                    <div style="color:var(--muted);font-size:.72rem;"><?= htmlspecialchars($emp['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="color:var(--muted);font-size:.83rem;"><?= htmlspecialchars($emp['department'] ?: '—') ?></td>
                        <td style="color:var(--muted);font-size:.83rem;"><?= htmlspecialchars($emp['position']   ?: '—') ?></td>
                        <td>
                            <span class="status-badge <?= $emp['status']==='Active' ? 'badge-active' : 'badge-inactive' ?>">
                                <?= htmlspecialchars($emp['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Department breakdown -->
    <div class="col-lg-4">
        <div class="ems-card h-100">
            <h6 class="mb-3" style="font-weight:700;">By Department</h6>

            <?php if (mysqli_num_rows($deptResult) === 0) : ?>
                <p style="color:var(--muted);font-size:.85rem;">No department data yet.</p>
            <?php else :
                // need total for bar widths
                $deptRows = mysqli_fetch_all($deptResult, MYSQLI_ASSOC);
                $maxCnt   = max(array_column($deptRows, 'cnt'));
                foreach ($deptRows as $i => $row) :
                    $pct = $maxCnt > 0 ? round($row['cnt'] / $maxCnt * 100) : 0;
                    $col = $colours[$i % count($colours)];
            ?>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1" style="font-size:.82rem;">
                    <span><?= htmlspecialchars($row['department']) ?></span>
                    <span style="color:var(--muted);"><?= $row['cnt'] ?></span>
                </div>
                <div style="height:7px;background:#eef2f7;border-radius:4px;overflow:hidden;">
                    <div style="height:100%;width:<?= $pct ?>%;background:<?= $col ?>;border-radius:4px;transition:width .6s;"></div>
                </div>
            </div>
            <?php endforeach; endif; ?>

            <a href="add_employee.php" class="btn btn-accent w-100 mt-3" style="font-size:.83rem;">
                <i class="bi bi-plus-lg me-1"></i> Add Employee
            </a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
