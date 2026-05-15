<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้าวมันไก่น้องนัน</title>
    <?php include_once('../layout/config/library.php') ?>
    <style>
        .content-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .table td,
        .table th {
            padding: 0.75rem;
            text-align: center;
        }

        .table-container {
            overflow-x: auto;
        }

        .custom-file-label::after {
            content: "Browse";
        }

        .no-data {
            text-align: center;
            color: #dc3545;
            /* Red color for "No data found" message */
            font-weight: bold;
        }
    </style>
</head>

<?php
include '../../backend/config/condb.php';

$result = $conn->query("SELECT * FROM roles");

$conn->close();
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once('../layout/header.php') ?>
        <?php include_once('./sidenav.php') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">สถานะผู้ใช้งาน</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-plus"></i> เพิ่มข้อมูล สถานะผู้ใช้งาน
                    </button>

                    <div class="table-container">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">สถานะการใช้งาน</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0) { ?>
                                    <?php foreach ($result as $row_role) { ?>
                                        <tr>
                                            <td>
                                                <?php echo @$l += 1; ?>
                                            </td>
                                            <td>
                                                <?php echo $row_role['name']; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-content-center">
                                                    <p class="mx-2">
                                                        <a href="./role_edit.php?r_id=<?php echo $row_role['id']; ?>"
                                                            class="btn btn-warning"><i class="fas fa-pencil-alt"></i> แก้ไข</a>
                                                    </p>
                                                    <p>
                                                        <a href="../../backend/role_db.php?r_id=<?php echo $row_role['id']; ?>&role=del" class="del-btn btn btn-danger"><i class="fas fas fa-trash"></i> ลบ</a>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="3" class="no-data">ไม่พบข้อมูล</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php if (isset($_GET['d'])) { ?>
                            <div class="flash-data" data-flashdata="<?php echo $_GET['d']; ?>"></div>
                        <?php } ?>
                        <script>
                            $('.del-btn').on('click', function(e) {
                                e.preventDefault();
                                const href = $(this).attr('href');
                                Swal.fire({
                                    title: 'ต้องการลบสถานะการใช้งานนี้ใช่ไหม?',
                                    text: "คุณจะไม่สามารถกู้ได้หลังจากลบไปแล้ว",
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes'
                                }).then((result) => {
                                    if (result.value) {
                                        document.location.href = href;
                                    }
                                });
                            });
                            const flashdata = $('.flash-data').data('flashdata');
                            if (flashdata) {
                                swal.fire({
                                    type: 'success',
                                    title: 'Record Deleted',
                                    text: 'Record has been deleted',
                                    icon: 'success'
                                });
                            }
                        </script>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include_once('../layout/footer.php') ?>
    </div>

    <!-- Modal for Adding Roles -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="../../backend/role_db.php" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="role" value="add">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มข้อมูล สถานะผู้ใช้งาน</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="r_name" class="col-sm-3 col-form-label">สถานะการใช้งาน </label>
                            <div class="col-sm-9">
                                <input id="r_name" name="r_name" type="text" required class="form-control" placeholder="" minlength="3" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยัน</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php include_once('../layout/config/script.php') ?>

</body>

</html>