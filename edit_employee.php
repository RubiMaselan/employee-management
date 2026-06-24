<?php
// ── All logic BEFORE any output ────────────────────────
require_once 'config/database.php';

$pageTitle = 'Edit Employee';

// ── Validate ID ────────────────────────────────────────
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: employees.php');
    exit;
}
$id = (int) $_GET['id'];

// ── Fetch existing record ──────────────────────────────
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) === 0) {
    header('Location: employees.php');
    exit;
}
if (mysqli_num_rows($result) === 0) {
    header('Location: employees.php');
    exit;
}
$emp    = mysqli_fetch_assoc($result);
$errors = [];

// ── Handle form submission ─────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $department = trim($_POST['department']);
    $position   = trim($_POST['position']);
    $salary     = trim($_POST['salary']);
    $hire_date  = trim($_POST['hire_date']);
    $status     = ($_POST['status'] === 'Inactive') ? 'Inactive' : 'Active';

    // Validate
    if (!$first_name) {
        $errors[] = 'First name is required.';
    }

if (!$last_name) {
    $errors[] = 'Last name is required.';
}

if (!$email) {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
}

if ($salary !== '' && !is_numeric($salary)) {
    $errors[] = 'Salary must be a number.';
}

    // Check duplicate email (exclude current employee)
    if (!$errors) {
    $stmt = $conn->prepare("SELECT id FROM employees WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'Another employee already uses this email.';
    }

    $stmt->close();
}

    // Update
    if (!$errors) {
    $stmt = $conn->prepare("
        UPDATE employees SET
            first_name = ?,
            last_name = ?,
            email = ?,
            phone = ?,
            department = ?,
            position = ?,
            salary = ?,
            hire_date = ?,
            status = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssssssdsii",
        $first_name,
        $last_name,
        $email,
        $phone,
        $department,
        $position,
        $salary,
        $hire_date,
        $status,
        $id
    );

    $stmt->execute();
    $stmt->close();

    header('Location: employees.php?success=updated');
    exit;
}

    // Repopulate with submitted values on error
    $emp = array_merge($emp, [
    'first_name' => htmlspecialchars($_POST['first_name'] ?? ''),
    'last_name'  => htmlspecialchars($_POST['last_name'] ?? ''),
    'email'      => htmlspecialchars($_POST['email'] ?? ''),
    'phone'      => htmlspecialchars($_POST['phone'] ?? ''),
    'department' => htmlspecialchars($_POST['department'] ?? ''),
    'position'   => htmlspecialchars($_POST['position'] ?? ''),
    'salary'     => htmlspecialchars($_POST['salary'] ?? ''),
    'hire_date'  => htmlspecialchars($_POST['hire_date'] ?? ''),
    'status'     => htmlspecialchars($_POST['status'] ?? ''),
]);
}

require_once 'includes/header.php';

$departments = ['Engineering','Marketing','Sales','Human Resources','Finance','Operations','Design','Legal','Support'];
$positions   = ['Manager','Senior Developer','Junior Developer','Designer','Analyst','HR Executive','Sales Executive','Support Agent','Intern'];
?>

<?php if ($errors) : ?>
<div class="alert ems-alert alert-dismissible fade show mb-3" role="alert"
     style="background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.2);">
    <strong><i class="bi bi-exclamation-circle-fill me-2"></i>Please fix the following:</strong>
    <ul class="mb-0 mt-1 ps-3">
        <?php foreach ($errors as $e) { echo "<li>" . htmlspecialchars($e) . "</li>"; } ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Employee info strip -->
<?php
$first = htmlspecialchars($emp['first_name'] ?? '');
$last  = htmlspecialchars($emp['last_name'] ?? '');
$email = htmlspecialchars($emp['email'] ?? '');

$initials = strtoupper(substr($first, 0, 1) . substr($last, 0, 1));

$colours  = ['#e94560','#3b82f6','#8b5cf6','#10b981','#f59e0b','#ec4899'];
$col      = $colours[abs(crc32($email)) % count($colours)];
?>
<div class="d-flex align-items-center gap-3 mb-4 p-3"
     style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);">
    <div class="avatar" style="width:52px;height:52px;font-size:1.1rem;background:<?= $col ?>22;color:<?= $col ?>">
    <?= htmlspecialchars($initials) ?>
</div>
    <div>
        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;">
            <?= htmlspecialchars($emp['first_name'].' '.$emp['last_name']) ?>
        </div>
        <div style="color:var(--muted);font-size:.8rem;">
            Employee ID #<?= $id ?> &middot; <?= htmlspecialchars($emp['email']) ?>
        </div>
    </div>
    <span class="status-badge ms-auto <?= $emp['status']==='Active'?'badge-active':'badge-inactive' ?>">
        <?= $emp['status'] ?>
    </span>
</div>

<div class="ems-card" style="max-width:820px;">
    <form method="POST" action="edit_employee.php?id=<?= $id ?>" novalidate>

        <!-- Row 1 -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">First Name <span style="color:var(--accent)">*</span></label>
                <input type="text" name="first_name" class="form-control"
                       value="<?= htmlspecialchars($emp['first_name']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name <span style="color:var(--accent)">*</span></label>
                <input type="text" name="last_name" class="form-control"
                       value="<?= htmlspecialchars($emp['last_name']) ?>" required>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Email Address <span style="color:var(--accent)">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($emp['email']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= htmlspecialchars($emp['phone']) ?>" placeholder="+60 12-345 6789">
            </div>
        </div>

        <!-- Row 3 -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Department</label>
                <select name="department" class="form-select">
                    <option value="">— Select Department —</option>
                    <?php foreach ($departments as $d) : ?>
                    <option value="<?= $d ?>" <?= ($emp['department'] === $d) ? 'selected' : '' ?>>
                        <?= $d ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Position / Job Title</label>
                <select name="position" class="form-select">
                    <option value="">— Select Position —</option>
                    <?php foreach ($positions as $p) : ?>
                    <option value="<?= $p ?>" <?= ($emp['position'] === $p) ? 'selected' : '' ?>>
                        <?= $p ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Row 4 -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">Monthly Salary (RM)</label>
                <input type="number" name="salary" class="form-control" step="0.01" min="0"
                       value="<?= htmlspecialchars($emp['salary']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Hire Date</label>
                <input type="date" name="hire_date" class="form-control"
                       value="<?= htmlspecialchars($emp['hire_date']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active"   <?= ($emp['status'] === 'Active')   ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= ($emp['status'] === 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid var(--border);">
            <button type="submit" class="btn btn-accent">
                <i class="bi bi-floppy-fill me-1"></i> Save Changes
            </button>
            <a href="employees.php" class="btn btn-sm"
               style="background:var(--border);color:var(--text);border:none;border-radius:8px;padding:.55rem 1.1rem;">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
