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

    $stmt = $conn->prepare("INSERT INTO products (name, detail, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $p_name, $p_detail, $p_price, $newname);
    $result = $stmt->execute();

    if ($result) {
        redirect_with_swal('success', 'สำเร็จ!', 'เพิ่มสินค้าเรียบร้อยแล้ว!', '../frontend/admin/products.php');
    } else {
        redirect_with_swal('error', 'ผิดพลาด!', 'เกิดข้อผิดพลาดในการเพิ่มสินค้า!', '../frontend/admin/products.php');
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

    $stmt = $conn->prepare("UPDATE products SET name = ?, detail = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $p_name, $p_detail, $p_price, $newname, $p_id);
    $result = $stmt->execute();

    if ($result) {
        redirect_with_swal('success', 'สำเร็จ!', 'แก้ไขข้อมูลเรียบร้อยแล้ว!', '../frontend/admin/products.php');
    } else {
        redirect_with_swal('error', 'ผิดพลาด!', 'เกิดข้อผิดพลาดในการแก้ไขสินค้า!', '../frontend/admin/product_edit.php?p_id=' . $p_id);
    }
    $stmt->close();

} elseif (isset($_GET['product']) && $_GET['product'] == "del") {
    $p_id = (int)$_GET["p_id"];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $p_id);
    $result_del = $stmt->execute();

    if ($result_del) {
        redirect_with_swal('success', 'สำเร็จ!', 'ลบสินค้าเรียบร้อยแล้ว!', '../frontend/admin/products.php');
    } else {
        redirect_with_swal('error', 'ผิดพลาด!', 'เกิดข้อผิดพลาดในการลบสินค้า!', '../frontend/admin/products.php');
    }
    $stmt->close();
}
?>