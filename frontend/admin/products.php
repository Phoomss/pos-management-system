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
    </style>
</head>

<?php
include '../../backend/config/condb.php';

// Fetch products from the database
$result = $conn->query("SELECT * FROM products_table");

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
                            <h1 class="m-0">เมนูอาหาร</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Add Product Button -->
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-plus"></i> เพิ่มข้อมูล สินค้า
                    </button>

                    <!-- Products Table -->
                    <div class="table-container">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Detail</th>
                                    <th scope="col">Price</th>

                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0) { ?>
                                    <?php foreach ($result as $row_product) { ?>
                                        <tr>
                                            <td><?php echo @$l += 1; ?></td>
                                            <td>
                                            <img src="../../uploads/<?php echo $row_product['p_image']; ?>" class="img-thumbnail" alt="Product Image" style="width: 120px; height: 120px; object-fit: cover;">
                                            </td>
                                            <td><?php echo $row_product['p_name']; ?></td>
                                            <td><?php echo $row_product['p_detail']; ?></td>
                                            <td><?php echo $row_product['p_price']; ?></td>
                                            <td>
                                                <div class="d-flex justify-content-center align-content-center">
                                                    <p class="mx-2">
                                                        <a href="./product_edit.php?p_id=<?php echo $row_product['p_id']; ?>"
                                                            class="btn btn-warning"><i class="fas fa-pencil-alt"></i> แก้ไข</a>
                                                    </p>
                                                    <p>
                                                        <a href="../../backend/product_db.php?p_id=<?php echo $row_product['p_id']; ?>&&product=del"
                                                            class="del-btn btn btn-danger"><i class="fas fas fa-trash"></i> ลบ</a>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" class="no-data">ไม่พบข้อมูล</td>
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
                                const href = $(this).attr('href')
                                Swal.fire({
                                    title: 'ต้องการลบสินค้านี้ใช่ไหม?',
                                    text: "คุณจะไม่สามารถกู้ได้หลังจากลบไปแล้ว",
                                    // icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes'
                                }).then((result) => {
                                    if (result.value) {
                                        document.location.href = href;

                                    }
                                })
                            })
                            const flashdata = $('.flash-data').data('flashdata')
                            if (flashdata) {
                                swal.fire({
                                    type: 'success',
                                    title: 'Record Deleted',
                                    text: 'Record has been deleted',
                                    icon: 'success'
                                })
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

    <!-- Modal for Adding Products -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="../../backend/product_db.php" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="product" value="add">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title" id="exampleModalLabel">เพิ่มข้อมูล สินค้า</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="p_name" class="col-sm-3 col-form-label">ชื่อ </label>
                            <div class="col-sm-9">
                                <input id="p_name" name="p_name" type="text" required class="form-control" placeholder="" minlength="3" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="p_detail" class="col-sm-3 col-form-label">รายละเอียด </label>
                            <div class="col-sm-9">
                                <textarea id="p_detail" name="p_detail" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="p_price" class="col-sm-3 col-form-label">ราคา </label>
                            <div class="col-sm-9">
                                <input id="p_price" name="p_price" type="number" min="0" required class="form-control" placeholder="" minlength="3" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="p_image" class="col-sm-3 col-form-label">รูปภาพ</label>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="p_image" name="p_image" onchange="readURL(this);">
                                    <label class="custom-file-label" for="p_image">เลือกรูปภาพ</label>
                                </div>
                                <br><br>
                                <img id="blah" src="#" alt="your image" width="300" style="display:none;" />
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

    <script>
        // Function to preview image
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result).show();
                    $('.custom-file-label').text(input.files[0].name);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>