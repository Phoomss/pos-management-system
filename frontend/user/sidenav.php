<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar shadow shadow-lg">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Brand -->
        <div class="user-panel">
            <div class="info">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="../user-info.png" class="rounded-circle shadow-sm" alt="User Image">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <a href="#" class="d-block mb-0">พนักงานขาย</a>
                        <small class="text-muted">Staff Member</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo $current_page == "index.php" ? "active" : ""; ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>หน้าจอขายสินค้า</span>
                    </a>
                </li>
                
                <li class="nav-header">รายงานส่วนตัว</li>
                <li class="nav-item">
                    <a href="list_sale.php" class="nav-link <?php echo $current_page == "list_sale.php" ? "active" : ""; ?>">
                        <i class="fas fa-history"></i>
                        <span>ประวัติการขาย</span>
                    </a>
                </li>
                
                <li class="nav-header mt-4">ตั้งค่า</li>
                <li class="nav-item">
                    <a href="user_info.php" class="nav-link <?php echo $current_page == "user_info.php" ? "active" : ""; ?>">
                        <i class="fas fa-user-circle"></i>
                        <span>ข้อมูลส่วนตัว</span>
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <a href="../../backend/auth/logout.php" class="nav-link text-danger-emphasis bg-danger-subtle mx-2" onclick="return confirm('ยืนยันออกจากระบบ?');">
                        <i class="fas fa-power-off text-danger"></i>
                        <span>ออกจากระบบ</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
