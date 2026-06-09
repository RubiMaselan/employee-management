<?php
// ── All logic BEFORE any output ────────────────────────
require_once 'config/database.php';

$pageTitle = 'Add Employee';
$errors    = [];

// ── Handle form submission ─────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitise inputs
    $first_name  = trim(mysqli_real_escape_string($conn, $_POST['first_name']));
    $last_name   = trim(mysqli_real_escape_string($conn, $_POST['last_name']));
    $email       = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $phone       = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $department  = trim(mysqli_real_escape_string($conn, $_POST['department']));
    $position    = trim(mysqli_real_escape_string($conn, $_POST['position']));
    $salary      = trim(mysqli_real_escape_string($conn, $_POST['salary']));
    $hire_date   = trim(mysqli_real_escape_string($conn, $_POST['hire_date']));
    $status      = ($_POST['status'] === 'Inactive') ? 'Inactive' : 'Active';

    // Validate
    if (!$first_name)  $errors[] = 'First name is required.';
    if (!$last_name)   $errors[] = 'Last name is required.';
    if (!$email)       $errors[] = 'Email is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
    if ($salary !== '' && !is_numeric($salary)) $errors[] = 'Salary must be a number.';

    // Check duplicate email
    if (!$errors) {
        $check = mysqli_query($conn, "SELECT id FROM employees WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'An employee with this email already exists.';
        }
    }

    // Insert
    if (!$errors) {
        $salaryVal = $salary !== '' ? "'$salary'" : 'NULL';
        $dateVal   = $hire_date   !== '' ? "'$hire_date'"   : 'NULL';

        $sql = "INSERT INTO employees (first_name, last_name, email, phone, department, position, salary, hire_date, status)
                VALUES ('$first_name','$last_name','$email','$phone','$department','$position',$salaryVal,$dateVal,'$status')";

        if (mysqli_query($conn, $sql)) {
            header('Location: employees.php?success=added');
            exit;
        } else {
            $errors[] = 'Database error: ' . mysqli_error($conn);
        }
    }
}

require_once 'includes/header.php';

$departments = ['Engineering','Marketing','Sales','Human Resources','Finance','Operations','Design','Legal','Support','Information Technology'];
$positions   = ['Manager','Senior Developer','Junior Developer','Designer','Analyst','HR Executive','Sales Executive','Support Agent','Intern'];
?>

<?php if ($errors) : ?>
<div class="alert ems-alert alert-dismissible fade show mb-3" role="alert"
     style="background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.2);">
    <strong><i class="bi bi-exclamation-circle-fill me-2"></i>Please fix the following:</strong>
    <ul class="mb-0 mt-1 ps-3">
        <?php foreach ($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="ems-card" style="max-width:820px;">
    <p class="mb-4" style="color:var(--muted);font-size:.875rem;">
        Fill in the details below to register a new employee.
    </p>

    <form method="POST" action="add_employee.php" novalidate>

        <!-- Row 1 -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">First Name <span style="color:var(--accent)">*</span></label>
                <input type="text" name="first_name" class="form-control"
                       value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>"
                       placeholder="e.g. Ahmad" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Last Name <span style="color:var(--accent)">*</span></label>
                <input type="text" name="last_name" class="form-control"
                       value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>"
                       placeholder="e.g. Razali" required>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Email Address <span style="color:var(--accent)">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="ahmad@company.com" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                       placeholder="+60 12-345 6789">
            </div>
        </div>

        <!-- Row 3 -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Department</label>
                <select name="department" class="form-select">
                    <option value="">— Select Department —</option>
                    <?php foreach ($departments as $d) : ?>
                    <option value="<?= $d ?>" <?= (($_POST['department'] ?? '') === $d) ? 'selected' : '' ?>>
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
                    <option value="<?= $p ?>" <?= (($_POST['position'] ?? '') === $p) ? 'selected' : '' ?>>
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
                       value="<?= htmlspecialchars($_POST['salary'] ?? '') ?>"
                       placeholder="3500.00">
            </div>
            <div class="col-md-4">
                <label class="form-label">Hire Date</label>
                <input type="date" name="hire_date" class="form-control"
                       value="<?= htmlspecialchars($_POST['hire_date'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active"   <?= (($_POST['status'] ?? 'Active') === 'Active')   ? 'selected':'' ?>>Active</option>
                    <option value="Inactive" <?= (($_POST['status'] ?? '')        === 'Inactive') ? 'selected':'' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid var(--border);">
            <button type="submit" class="btn btn-accent">
                <i class="bi bi-person-plus-fill me-1"></i> Add Employee
            </button>
            <a href="employees.php" class="btn btn-sm"
               style="background:var(--border);color:var(--text);border:none;border-radius:8px;padding:.55rem 1.1rem;">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>