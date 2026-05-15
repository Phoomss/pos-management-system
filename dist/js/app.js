document.addEventListener('DOMContentLoaded', function() {
    console.log('Modern POS Frontend Initialized');

    // Sidebar Toggle Logic
    const sidebarToggle = document.querySelector('[data-widget="pushmenu"]');
    const sidebar = document.querySelector('.main-sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('show');
        });
    }

    // Centralized Delete Confirmation (Event Delegation)
    document.addEventListener('click', function(e) {
        const delBtn = e.target.closest('.del-btn');
        if (delBtn) {
            e.preventDefault();
            const href = delBtn.getAttribute('href');
            
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    confirmButton: 'btn btn-primary px-4 me-2',
                    cancelButton: 'btn btn-danger px-4'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        }
    });

    // POS Cart Scroll helper
    const cartBody = document.querySelector('.pos-cart-body');
    if (cartBody) {
        cartBody.scrollTop = cartBody.scrollHeight;
    }

    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
