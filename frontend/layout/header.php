<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3">
    <div class="container-fluid px-4">
        <!-- Sidebar Toggle -->
        <button class="btn btn-light border me-3" data-widget="pushmenu">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Brand/Title -->
        <a class="navbar-brand fw-bold text-primary" href="#">
            <i class="fas fa-store me-2"></i> ข้าวมันไก่น้องนัน
        </a>

        <!-- Right Side -->
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../user-info.png" alt="mdo" width="32" height="32" class="rounded-circle me-2">
                    <span class="d-none d-sm-inline fw-semibold"><?php echo htmlspecialchars($_SESSION["fullname"] ?? 'Guest'); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item py-2" href="user_info.php"><i class="fas fa-user-circle me-2 text-muted"></i> โปรไฟล์</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 text-danger" href="../../backend/auth/logout.php" onclick="return confirm('ยืนยันออกจากระบบ?');"><i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- /.navbar -->
