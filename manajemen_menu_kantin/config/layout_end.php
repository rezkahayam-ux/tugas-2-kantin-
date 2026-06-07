        </div><!-- end page-body -->
    </div><!-- end main-content -->
</div><!-- end app-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle (mobile)
    const hamburger = document.getElementById('hamburgerBtn');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert-dismissible').forEach(el => {
            new bootstrap.Alert(el).close();
        });
    }, 3500);
</script>
</body>
</html>
