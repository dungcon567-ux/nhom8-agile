<?php
// views/admin/orders/update.php
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Cập nhật đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .nav-link {
            color: #000;
            padding: 10px 20px;
            font-size: 16px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #e9ecef;
            color: #0d6efd;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                height: auto;
                width: 100%;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include_once 'views/admin/layouts/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto col-lg-10 main-content">
                <h2 class="mb-4">Cập nhật đơn hàng #<?php echo htmlspecialchars($order['id']); ?></h2>
                <div class="card shadow">
                    <div class="card-body">
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($_GET['error']); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="?controller=admin&action=update_order">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($order['id']); ?>">
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái:</label>
                                <select name="status" class="form-select" id="status">
                                    <?php
                                    $current_status = $order['status'];
                                    $status_options = [
                                        'pending' => 'Đang chờ',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đang giao hàng',
                                        'delivered' => 'Giao thành công',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Hủy'
                                    ];
                                    // Logic không cho phép cập nhật ngược
                                    $allowed_status = [];
                                    switch ($current_status) {
                                        case 'pending':
                                            $allowed_status = ['processing', 'cancelled'];
                                            break;
                                        case 'processing':
                                            $allowed_status = ['shipped', 'cancelled'];
                                            break;
                                        case 'shipped':
                                            $allowed_status = ['delivered', 'cancelled'];
                                            break;
                                        case 'delivered':
                                            $allowed_status = ['completed'];
                                            break;
                                        case 'completed':
                                            $allowed_status = []; // Không cho phép thay đổi
                                            break;
                                        case 'cancelled':
                                            $allowed_status = []; // Không cho phép thay đổi
                                            break;
                                    }
                                    foreach ($status_options as $key => $value) {
                                        if (empty($allowed_status) || in_array($key, $allowed_status)) {
                                            $selected = ($current_status == $key) ? 'selected' : '';
                                            echo "<option value='$key' $selected>$value</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="?controller=admin&action=orders" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>