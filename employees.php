<?php
// ── All logic BEFORE any output ────────────────────────
require_once 'config/database.php';

// Delete handler (needs header redirect — must run before HTML)
if (isset($_GET['delete'])) {
    $id  = (int) $_GET['delete'];
    $sql = "DELETE FROM employees WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header('Location: employees.php?success=deleted');
        exit;
    }
    // If delete failed we fall through and show the error below
}

// ── Now safe to output HTML ────────────────────────────
$pageTitle = 'Employees';
require_once 'includes/header.php';

// ── Flash messages ─────────────────────────────────────
$success = $error = '';
if (isset($_GET['success'])) {
    $map = [
        'added'   => 'Employee added successfully!',
        'updated' => 'Employee updated successfully!',
        'deleted' => 'Employee deleted successfully!',
    ];
    $success = $map[$_GET['success']] ?? '';
}

if (isset($_GET['delete']) && !isset($success)) {
    $error = 'Delete failed. Please try again.';
}

// ── Fetch all employees ────────────────────────────────
$result = mysqli_query($conn, "SELECT * FROM employees ORDER BY created_at DESC");

// Palette for avatars
$colours = ['#e94560','#3b82f6','#8b5cf6','#10b981','#f59e0b','#ec4899'];
?>

<?php if ($success) : ?>
<div class="alert ems-alert alert-success auto-dismiss alert-dismissible fade show mb-3" role="alert" style="background:rgba(16,185,129,.12);color:#4ade80;border:1px solid rgba(16,185,129,.25);">
    <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error) : ?>
<div class="alert ems-alert alert-danger auto-dismiss alert-dismissible fade show mb-3" role="alert" style="background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.2);">
    <i class="bi bi-exclamation-circle-fill me-2"></i><?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- ── Toolbar ─────────────────────────────────────────── -->
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <div class="search-wrap flex-grow-1" style="max-width:380px;">
    <label for="searchInput" class="visually-hidden">
        Search employees
    </label>

    <i class="bi bi-search"></i>

    <input type="text" id="searchInput" class="form-control"
           placeholder="Search employees…">
</div>

<!-- ── Table card ─────────────────────────────────────── -->
<div class="ems-card p-0" style="overflow:hidden;">
    <div style="overflow-x:auto;padding:1.25rem 1.5rem;">
        <?php if (mysqli_num_rows($result) === 0) : ?>
            <div class="text-center py-5" style="color:var(--muted);">
                <i class="bi bi-people" style="font-size:2.5rem;display:block;margin-bottom:.75rem;"></i>
                No employees found. <a href="add_employee.php" style="color:var(--accent-soft);">Add your first one!</a>
            </div>
        <?php else : ?>
        <table class="ems-table" id="employeeTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Salary (RM)</th>
                    <th>Hire Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1; while ($emp = mysqli_fetch_assoc($result)) :
                $initials = strtoupper(substr($emp['first_name'],0,1).substr($emp['last_name'],0,1));
                $col      = $colours[crc32($emp['email']) % count($colours)];
            ?>
                <tr>
                    <td style="color:var(--muted);font-size:.78rem;"><?= $i++ ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="background:<?= $col ?>22;color:<?= $col ?>"><?= $initials ?></div>
                            <div>
                                <div style="font-weight:600;font-size:.85rem;">
                                    <?= htmlspecialchars($emp['first_name'].' '.$emp['last_name']) ?>
                                </div>
                                <div style="color:var(--muted);font-size:.72rem;"><?= htmlspecialchars($emp['email']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--muted);font-size:.83rem;"><?= htmlspecialchars($emp['phone'] ?: '—') ?></td>
                    <td style="font-size:.83rem;"><?= htmlspecialchars($emp['department'] ?: '—') ?></td>
                    <td style="font-size:.83rem;"><?= htmlspecialchars($emp['position']   ?: '—') ?></td>
                    <td style="font-size:.83rem;"><?= $emp['salary'] ? number_format($emp['salary'],2) : '—' ?></td>
                    <td style="color:var(--muted);font-size:.83rem;">
                        <?= $emp['hire_date'] ? date('d M Y', strtotime($emp['hire_date'])) : '—' ?>
                    </td>
                    <td>
                        <span class="status-badge <?= $emp['status']==='Active' ? 'badge-active' : 'badge-inactive' ?>">
                            <?= $emp['status'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="edit_employee.php?id=<?= $emp['id'] ?>" class="btn-icon edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn-icon delete" title="Delete"
                                onclick="confirmDelete(<?= $emp['id'] ?>, '<?= htmlspecialchars($emp['first_name'].' '.$emp['last_name'], ENT_QUOTES) ?>')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- ── Delete confirmation modal ──────────────────────── -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" style="font-family:'Syne',sans-serif;font-weight:700;">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Delete Employee
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="font-size:.875rem;color:var(--muted);">
                Are you sure you want to delete <strong id="deleteName" style="color:var(--text);"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal"
                    style="background:var(--border);color:var(--text);border:none;border-radius:7px;padding:.45rem 1rem;">
                    Cancel
                </button>
                <a id="deleteConfirmBtn" href="#" class="btn btn-sm btn-danger" style="border-radius:7px;padding:.45rem 1rem;">
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteConfirmBtn').href = 'employees.php?delete=' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
