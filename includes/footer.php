</div><!-- /.main-content -->

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Auto-dismiss alerts after 4 seconds
document.querySelectorAll('.alert.auto-dismiss').forEach(function(alert) {
    setTimeout(function() {
        var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
    }, 4000);
});

// Live search filter for employee table
var searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        var val = this.value.toLowerCase();
        document.querySelectorAll('#employeeTable tbody tr').forEach(function(row) {
            var text = row.textContent.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });
}
</script>
</body>
</html>