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
include '../../backend/config/condb.php';
if (isset($_GET['r_id']) && is_numeric($_GET['r_id'])) {
    $r_id = $_GET['r_id'];

    $query_role = "SELECT * FROM roles_table WHERE r_id = $r_id";
    $rs_role = mysqli_query($conn, $query_role) or die("Error: " . mysqli_error($conn));

    if (mysqli_num_rows($rs_role) > 0) {
        $row = mysqli_fetch_array($rs_role);
    } else {
        echo "No role found with this ID.";
    }

    $conn->close();
} else {
    echo "Invalid Role ID";
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
                            <h1 class="m-0">สถานะผู้ใช้งาน</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">แก้ไขสถานะผู้ใช้งาน</h3>
                        </div>
                        <br>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="../../backend/role_db.php" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="role" value="edit">
                                        <input type="hidden" name="r_id" value="<?php echo $row['r_id']; ?>">
                                        <div class="form-group">
                                            <input name="r_name" type="text" class="form-control" required placeholder="สถานะการใช้งาน" value="<?php echo $row['r_name']; ?>" minlength="3">
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary btn-block">อัปเดตสถานะผู้ใช้งาน</button>
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
    </div>
</body>

</html>