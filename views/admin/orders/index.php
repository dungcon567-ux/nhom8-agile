<!-- views/admin/orders/index.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Quản lý đơn hàng</title>
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
                <h2>Quản lý đơn hàng</h2>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">Cập nhật thành công!</div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_GET['error'] == 'invalid_data' ? 'Dữ liệu không hợp lệ!' : ($_GET['error'] == 'update_failed' ? 'Cập nhật thất bại!' : htmlspecialchars($_GET['error'])); ?>
                    </div>
                <?php endif; ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Họ và tên</th>
                            <th>Địa chỉ</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Tỉnh/Thành phố</th>
                            <th>Phường/Xã</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($orders) && is_array($orders) && !empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo number_format($order['total'], 0, ',', '.') . ' VNĐ'; ?></td>
                                    <td>
                                        <form method="POST" action="?controller=admin&action=update_order_status" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                            <select name="status" class="form-select form-select-sm d-inline w-auto">
                                                <?php
                                                $current_status = $order['status'];
                                                $status_options = [
                                                    'pending' => 'Đang chờ',
                                                    'processing' => 'Đang xử lý',
                                                    'shipped' => 'Đã giao hàng',
                                                    'delivered' => 'Đã nhận',
                                                    'cancelled' => 'Đã hủy',
                                                    'returned' => 'Hoàn hàng'
                                                ];
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
                                                        $allowed_status = ['returned'];
                                                        break;
                                                    case 'cancelled':
                                                    case 'returned':
                                                        $allowed_status = [];
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
                                            <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                                        </form>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($order['province'] ?? 'NULL'); ?></td>
                                    <td><?php echo htmlspecialchars($order['ward'] ?? 'NULL'); ?></td>
                                    <td><?php echo htmlspecialchars($order['order_notes'] ?? 'NULL'); ?></td>
                                    <td>
                                        <a href="?controller=admin&action=order_details&order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">Chi tiết</a>
                                        <a href="?controller=admin&action=delete_order&id=<?php echo $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="text-center">Không có đơn hàng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Phân trang -->
                <?php if (isset($total_orders) && $total_orders > 0): ?>
                    <?php
                    $total_pages = ceil($total_orders / $items_per_page);
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=admin&action=orders&page=<?php echo $current_page - 1; ?>">Trước</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $current_page == $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?controller=admin&action=orders&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=admin&action=orders&page=<?php echo $current_page + 1; ?>">Sau</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>