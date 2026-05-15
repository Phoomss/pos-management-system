<?php
include_once('../../backend/config/condb.php');

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : 10;
$offset = ($page - 1) * $records_per_page;

$query = "SELECT o.*, u.fullname 
          FROM orders as o 
          INNER JOIN users as u ON o.user_id = u.id
          ORDER BY o.id DESC
          LIMIT $offset, $records_per_page";

$rs_order = mysqli_query($conn, $query) or die("Error: " . mysqli_error($conn));

$total_query = "SELECT COUNT(*) as total FROM orders";
$result_total = mysqli_query($conn, $total_query);
$row_total = mysqli_fetch_assoc($result_total);
$total_pages = ceil($row_total['total'] / $records_per_page);
?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom-0">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0 fw-bold">ประวัติการทำรายการ</h5>
            </div>
            <div class="col-auto">
                <form method="GET" action="" class="d-flex align-items-center">
                    <label class="me-2 small text-muted text-nowrap">แสดงแถวต่อหน้า:</label>
                    <select name="records_per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="10" <?php if ($records_per_page == 10) echo 'selected'; ?>>10</option>
                        <option value="20" <?php if ($records_per_page == 20) echo 'selected'; ?>>20</option>
                        <option value="50" <?php if ($records_per_page == 50) echo 'selected'; ?>>50</option>
                        <option value="100" <?php if ($records_per_page == 100) echo 'selected'; ?>>100</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" width="100">Order ID</th>
                        <th>พนักงานขาย</th>
                        <th>ประเภท</th>
                        <th>ยอดรวม</th>
                        <th>วันที่ทำรายการ</th>
                        <th class="text-center pe-4" width="150">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($rs_order) > 0): ?>
                        <?php foreach ($rs_order as $row): ?>
                            <tr>
                                <td class="ps-4"><span class="badge bg-light text-dark border">#<?php echo $row['id']; ?></span></td>
                                <td class="fw-medium text-dark"><?php echo $row['fullname']; ?></td>
                                <td>
                                    <?php if($row['order_type'] == 'ทานที่ร้าน'): ?>
                                        <span class="badge rounded-pill bg-success-subtle text-success border-success-subtle px-3">ทานที่ร้าน</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-info-subtle text-info border-info-subtle px-3">กลับบ้าน</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold text-primary">฿<?php echo number_format($row['total_amount'], 2); ?></td>
                                <td class="text-muted small"><?php echo date('d/m/y H:i', strtotime($row['created_at'])); ?></td>
                                <td class="text-center pe-4">
                                    <a href="order_detail.php?order_id=<?php echo $row['id']; ?>&act=view" target="_blank" class="btn btn-white btn-sm border shadow-sm px-3">
                                        <i class="fas fa-external-link-alt me-1 small text-muted"></i> เปิดดู
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">ไม่พบข้อมูลรายการขาย</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if ($total_pages > 1): ?>
    <div class="card-footer bg-white py-3 border-top">
        <nav>
            <ul class="pagination pagination-sm justify-content-center mb-0">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&records_per_page=<?php echo $records_per_page; ?>">ก่อนหน้า</a>
                </li>
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&records_per_page=<?php echo $records_per_page; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&records_per_page=<?php echo $records_per_page; ?>">ถัดไป</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
