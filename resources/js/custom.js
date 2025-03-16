document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');

    sidebarCollapse.addEventListener('click', function () {
        sidebar.classList.toggle('active');
    });
});