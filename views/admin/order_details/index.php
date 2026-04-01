<!-- views/admin/order_details/index.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Chi tiết đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
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
        
        
    </style>

<body>
    <?php include_once 'views/admin/layouts/sidebar.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4">Chi tiết đơn hàng #<?php echo htmlspecialchars($order_id); ?></h2>
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">Thông tin người mua hàng</h4>
            </div>
            <div class="card-body">
                <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($order['full_name'] ?? 'Không có'); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['phone'] ?? 'Không có'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email'] ?? 'Không có'); ?></p>
                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address'] ?? 'Không có'); ?></p>
                <p><strong>Tỉnh/Thành phố:</strong> <?php echo htmlspecialchars($order['province'] ?? 'Không có'); ?></p>
                <p><strong>Phường/Xã:</strong> <?php echo htmlspecialchars($order['ward'] ?? 'Không có'); ?></p>
                <p><strong>Ghi chú:</strong> <?php echo nl2br(htmlspecialchars($order['order_notes'] ?? 'Không có')); ?></p>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Chi tiết sản phẩm</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Kích thước</th>
                            <th>Màu sắc</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($order_details) && is_array($order_details) && !empty($order_details)): ?>
                            <?php
                            $total_amount = 0;
                            foreach ($order_details as $detail) {
                                $total_amount += ($detail['price'] ?? 0) * ($detail['quantity'] ?? 0);
                            }
                            ?>
                            <?php foreach ($order_details as $detail): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($detail['name'] ?? 'Không có'); ?></td>
                                    <td><?php echo htmlspecialchars($detail['size'] ?? 'Không có'); ?></td>
                                    <td><?php echo htmlspecialchars($detail['color'] ?? 'Không có'); ?></td>
                                    <td><?php echo htmlspecialchars($detail['quantity'] ?? 0); ?></td>
                                    <td><?php echo number_format($detail['price'] ?? 0, 0, ',', '.') . ' VNĐ'; ?></td>
                                    <td><?php echo number_format(($detail['price'] ?? 0) * ($detail['quantity'] ?? 0), 0, ',', '.') . ' VNĐ'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có chi tiết đơn hàng.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (isset($total_amount)): ?>
                    <p class="text-end fw-bold">Tổng số tiền: <?php echo number_format($total_amount, 0, ',', '.') . ' VNĐ'; ?></p>
                <?php endif; ?>
                <a href="?controller=admin&action=orders" class="btn btn-primary mt-3">Quay lại</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>