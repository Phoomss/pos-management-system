<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้าวมันไก่น้องนัน</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php
    include('../backend/config/condb.php');
    csrf_verify();

    if (isset($_POST['product']) && $_POST['product'] == "add") {
        $p_name = $_POST["p_name"];
        $p_detail = $_POST["p_detail"];
        $p_price = $_POST["p_price"];

        $newname = '';
        if (isset($_FILES['p_image']) && $_FILES['p_image']['error'] == 0) {
            $path = "../uploads/";
            $filename = $_FILES['p_image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'webp');
            
            if (in_array($ext, $allowed)) {
                $newname = mt_rand() . date("Ymd_His") . "." . $ext;
                move_uploaded_file($_FILES['p_image']['tmp_name'], $path . $newname);
            }
        }

        $stmt = $conn->prepare("INSERT INTO products_table (p_name, p_detail, p_price, p_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $p_name, $p_detail, $p_price, $newname);
        $result = $stmt->execute();

        if ($result) {
            echo "<script>
                Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: 'เพิ่มสินค้าเรียบร้อยแล้ว!' })
                .then(() => { window.location = '../frontend/admin/products.php'; });
            </script>";
        } else {
            echo "<script>
                Swal.fire({ icon: 'error', title: 'ผิดพลาด!', text: 'เกิดข้อผิดพลาดในการเพิ่มสินค้า!' })
                .then(() => { window.location = '../frontend/admin/products.php'; });
            </script>";
        }
        $stmt->close();

    } elseif (isset($_POST['product']) && $_POST['product'] == "edit") {
        $p_id = (int)$_POST["p_id"];
        $p_name = $_POST["p_name"];
        $p_detail = $_POST["p_detail"];
        $p_price = $_POST["p_price"];
        $file1 = $_POST['file1'];

        $newname = $file1;
        if (isset($_FILES['p_image']) && $_FILES['p_image']['error'] == 0) {
            $path = "../uploads/";
            $filename = $_FILES['p_image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $allowed = array('jpg', 'jpeg', 'png', 'webp');
            
            if (in_array($ext, $allowed)) {
                $newname = mt_rand() . date("Ymd_His") . "." . $ext;
                move_uploaded_file($_FILES['p_image']['tmp_name'], $path . $newname);
            }
        }

        $stmt = $conn->prepare("UPDATE products_table SET p_name = ?, p_detail = ?, p_price = ?, p_image = ? WHERE p_id = ?");
        $stmt->bind_param("ssdsi", $p_name, $p_detail, $p_price, $newname, $p_id);
        $result = $stmt->execute();

        if ($result) {
            echo "<script>
                Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: 'แก้ไขข้อมูลเรียบร้อยแล้ว!' })
                .then(() => { window.location = '../frontend/admin/products.php'; });
            </script>";
        } else {
            echo "<script>
                Swal.fire({ icon: 'error', title: 'ผิดพลาด!', text: 'เกิดข้อผิดพลาดในการแก้ไขสินค้า!' })
                .then(() => { window.location = '../frontend/admin/product_edit.php?p_id=$p_id'; });
            </script>";
        }
        $stmt->close();

    } elseif (isset($_GET['product']) && $_GET['product'] == "del") {
        $p_id = (int)$_GET["p_id"];
        $stmt = $conn->prepare("DELETE FROM products_table WHERE p_id = ?");
        $stmt->bind_param("i", $p_id);
        $result_del = $stmt->execute();

        if ($result_del) {
            echo "<script>
                Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: 'ลบสินค้าเรียบร้อยแล้ว!' })
                .then(() => { window.location = '../frontend/admin/products.php'; });
            </script>";
        } else {
            echo "<script>
                Swal.fire({ icon: 'error', title: 'ผิดพลาด!', text: 'เกิดข้อผิดพลาดในการลบสินค้า!' })
                .then(() => { window.location = '../frontend/admin/products.php'; });
            </script>";
        }
        $stmt->close();
    }
    ?>
</body>
</html>