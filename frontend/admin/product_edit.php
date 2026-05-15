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

        .card-header {
            background-color: #007bff;
            color: white;
        }

        .table thead th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .form-control {
            margin-bottom: 1rem;
        }

        .custom-file-label::after {
            content: "Browse";
        }

        #blah {
            display: none;
            margin-top: 1rem;
        }
    </style>
</head>

<?php
include_once '../../backend/config/condb.php';

// Ensure p_id is set and is numeric to prevent SQL injection
if (isset($_GET['p_id']) && is_numeric($_GET['p_id'])) {
    $p_id = $_GET['p_id'];

    // Correct the query by removing 'or die' and use error handling with mysqli_error
    $query_product = "SELECT * FROM products WHERE id = $p_id";
    $rs_product = mysqli_query($conn, $query_product) or die("Error: " . mysqli_error($conn));

    if (mysqli_num_rows($rs_product) > 0) {
        $row = mysqli_fetch_array($rs_product);
    } else {
        echo "No product found with this ID.";
    }

    $conn->close();
} else {
    // If p_id is not set, show an error message
    echo "Invalid Product ID";
    exit();
}
?>


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once('../layout/header.php') ?>
        <?php include_once('./sidenav.php') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper center">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">เมนูอาหาร</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">แก้ไขสินค้า</h3>
                        </div>
                        <br>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../../backend/product_db.php" method="POST" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="product" value="edit">
                                        <input type="hidden" name="p_id" value="<?php echo $row['id']; ?>">
                                        <input name="file1" type="hidden" id="file1" value="<?php echo $row['image']; ?>" />

                                        <!-- Product Name -->
                                        <div class="form-group">
                                            <label for="p_name">ชื่อสินค้า</label>
                                            <input name="p_name" type="text" class="form-control" required placeholder="ชื่อสินค้า" value="<?php echo $row['name']; ?>" minlength="3">
                                        </div>

                                        <!-- Product Detail -->
                                        <div class="form-group">
                                            <label for="p_detail">รายละเอียด</label>
                                            <textarea name="p_detail" class="form-control" rows="3"><?php echo $row['detail']; ?></textarea>
                                        </div>

                                        <!-- Product Price -->
                                        <div class="form-group">
                                            <label for="p_price">ราคา</label>
                                            <input name="p_price" type="number" class="form-control" min="0" required placeholder="ราคา" value="<?php echo $row['price']; ?>">
                                        </div>

                                        <!-- Old Image -->
                                        <div class="form-group">
                                            <label>ภาพเก่า</label><br>
                                            <img src="../../uploads/<?php echo $row['image']; ?>" width="300" alt="Old Product Image">
                                            <input type="hidden" name="mem_img2" value="<?php echo $row['image']; ?>">
                                        </div>

                                        <!-- New Image -->
                                        <div class="form-group">
                                            <label for="p_image">เลือกรูปใหม่</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="p_image" name="p_image" onchange="readURL(this);">
                                                <label class="custom-file-label" for="file">Choose file</label>
                                            </div>
                                            <img id="blah" src="#" alt="your image" width="300" />
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary btn-block">อัปเดตสินค้า</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <?php include_once('../layout/footer.php') ?>
        <?php include_once('../layout/config/script.php') ?>

        <script>
            // Image preview script
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('blah').style.display = 'block';
                        document.getElementById('blah').src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </div>
</body>

</html>