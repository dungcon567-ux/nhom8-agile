<!-- views/admin/dashboard.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Trang quản trị</title>
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
        .card-dashboard {
            min-height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
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
                <h2>Trang quản trị</h2>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card card-dashboard bg-primary text-white">
                            <div class="card-body">
                                <?php echo isset($total_products) ? $total_products : 0; ?> Sản phẩm
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card card-dashboard bg-success text-white">
                            <div class="card-body">
                                <?php echo isset($total_orders) ? $total_orders : 0; ?> Đơn hàng
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card card-dashboard bg-warning text-white">
                            <div class="card-body">
                                <?php echo isset($total_users) ? $total_users : 0; ?> Người dùng
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card card-dashboard bg-info text-white">
                            <div class="card-body">
                                <?php echo isset($total_reviews) ? $total_reviews : 0; ?> Đánh giá
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">Đơn hàng gần đây</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($recent_orders) && is_array($recent_orders) && !empty($recent_orders)): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Người dùng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                                            <td><?php echo number_format($order['total'], 0, ',', '.') . ' VNĐ'; ?></td>
                                            <td>
                                                <?php
                                                $status = $order['status'];
                                                $badgeClass = '';
                                                switch ($status) {
                                                    case 'pending':
                                                        $badgeClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'processing':
                                                        $badgeClass = 'bg-info text-white';
                                                        break;
                                                    case 'shipped':
                                                        $badgeClass = 'bg-primary text-white';
                                                        break;
                                                    case 'delivered':
                                                        $badgeClass = 'bg-success text-white';
                                                        break;
                                                    case 'completed':
                                                        $badgeClass = 'bg-success text-white';
                                                        break;
                                                    case 'cancelled':
                                                        $badgeClass = 'bg-danger text-white';
                                                        break;
                                                    default:
                                                        $badgeClass = 'bg-secondary text-white';
                                                }
                                                echo "<span class='badge $badgeClass'>" . ucfirst($status) . "</span>";
                                                ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Không có đơn hàng gần đây.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>