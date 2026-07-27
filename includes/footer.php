</div><!-- /.main-content -->

<!-- Bootstrap 5 JS Bundle -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous">
</script>

<script>
// Auto-dismiss alerts after 4 seconds
document.querySelectorAll('.alert.auto-dismiss').forEach(function(alert) {
    setTimeout(function() {
        var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
    }, 4000);
});

// Sidebar toggle (mobile)
var sidebar = document.querySelector('.sidebar');
var sidebarToggle = document.getElementById('sidebarToggle');
var sidebarBackdrop = document.getElementById('sidebarBackdrop');

function closeSidebar() {
    sidebar.classList.remove('open');
    sidebarBackdrop.classList.remove('show');
}

if (sidebar && sidebarToggle && sidebarBackdrop) {
    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
        sidebarBackdrop.classList.toggle('show', sidebar.classList.contains('open'));
    });
    sidebarBackdrop.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });
}

// Live search + status filter for the employee table
var searchInput  = document.getElementById('searchInput');
var statusFilter = document.getElementById('statusFilter');
var resultCount  = document.getElementById('resultCount');
var noResultsRow = document.getElementById('noResultsRow');

function filterEmployees() {
    var term   = searchInput ? searchInput.value.toLowerCase().trim() : '';
    var status = statusFilter ? statusFilter.value : '';
    var shown  = 0;

    document.querySelectorAll('#employeeTable tbody tr[data-employee]').forEach(function(row) {
        var matchesTerm   = row.textContent.toLowerCase().includes(term);
        var matchesStatus = !status || row.dataset.status === status;
        var visible = matchesTerm && matchesStatus;
        row.style.display = visible ? '' : 'none';
        if (visible) shown++;
    });

    if (noResultsRow) noResultsRow.style.display = shown === 0 ? '' : 'none';
    if (resultCount)  resultCount.textContent = shown + (shown === 1 ? ' employee' : ' employees');
}

if (searchInput)  searchInput.addEventListener('input', filterEmployees);
if (statusFilter) statusFilter.addEventListener('change', filterEmployees);
if (searchInput || statusFilter) filterEmployees();
</script>
</body>
</html>
