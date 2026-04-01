<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tra cứu đơn hàng - Chuc Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 15px; }
        .card-header { border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .btn-primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border: none; border-radius: 10px; padding: 12px; }
        .form-control { border-radius: 10px; border: 2px solid #e9ecef; padding: 12px; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
    </style>
</head>
<body>
    <?php include_once 'views/layouts/head.php'; ?>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header text-white">
                        <h4 class="mb-0"><i class="fas fa-search"></i> Tra cứu đơn hàng</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-barcode"></i> Mã đơn hàng</label>
                                <input type="text" name="order_code" class="form-control" placeholder="Nhập mã đơn hàng của bạn" required>
                                <div class="form-text">Ví dụ: DUNG202508211234567890</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Tra cứu
                            </button>
                        </form>

                        <?php if ($error): ?>
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($order): ?>
                            <hr class="my-4">
                            <div class="order-info">
                                <h5><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Mã đơn hàng:</strong> <?php echo htmlspecialchars($order['order_code_new']); ?></p>
                                        <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                                        <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold"><?php echo number_format($order['total']); ?> VNĐ</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Trạng thái:</strong> 
                                            <?php 
                                            $status_labels = [
                                                'pending' => '<span class="badge bg-warning">Đang chờ</span>',
                                                'processing' => '<span class="badge bg-info">Đang xử lý</span>',
                                                'shipped' => '<span class="badge bg-primary">Đã giao hàng</span>',
                                                'delivered' => '<span class="badge bg-success">Đã nhận</span>',
                                                'cancelled' => '<span class="badge bg-danger">Đã hủy</span>',
                                                'returned' => '<span class="badge bg-secondary">Hoàn hàng</span>'
                                            ];
                                            echo $status_labels[$order['status']] ?? '<span class="badge bg-secondary">Không xác định</span>';
                                            ?>
                                        </p>
                                        <p><strong>Phương thức thanh toán:</strong> 
                                            <?php echo $order['payment_method'] == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : $order['payment_method']; ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <h6><i class="fas fa-user"></i> Thông tin giao hàng</h6>
                                    <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
                                    <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                                    <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                                    <?php if ($order['province']): ?>
                                        <p><strong>Tỉnh/Thành phố:</strong> <?php echo htmlspecialchars($order['province']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($order['ward']): ?>
                                        <p><strong>Phường/Xã:</strong> <?php echo htmlspecialchars($order['ward']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($order['order_notes']): ?>
                                        <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($order['order_notes']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2025 Chuc Store. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

