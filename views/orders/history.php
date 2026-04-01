<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng - Chuc Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 15px; }
        .card-header { border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .btn-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none; border-radius: 8px; }
        .order-card { transition: transform 0.2s; }
        .order-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">Chuc Store</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../../index.php">Trang chủ</a>
                <a class="nav-link" href="../../index.php?controller=product&action=index">Sản phẩm</a>
                <a class="nav-link" href="../../index.php?controller=cart&action=index">Giỏ hàng</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="../../index.php?controller=user&action=logout">Đăng xuất</a>
                <?php else: ?>
                    <a class="nav-link" href="../../index.php?controller=user&action=login">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header text-white">
                        <h4 class="mb-0"><i class="fas fa-history"></i> Lịch sử đơn hàng</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> Đơn hàng đã được hủy thành công!
                            </div>
                        <?php endif; ?>

                        <?php if (empty($orders)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                <h5>Chưa có đơn hàng nào</h5>
                                <p class="text-muted">Bạn chưa có đơn hàng nào trong lịch sử.</p>
                                <a href="../../index.php?controller=product&action=index" class="btn btn-primary">
                                    <i class="fas fa-store"></i> Mua sắm ngay
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($orders as $order): ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="card order-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h6 class="card-title mb-1">
                                                            <i class="fas fa-barcode"></i> 
                                                            <?php echo htmlspecialchars($order['order_code_new']); ?>
                                                        </h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar"></i> 
                                                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                                        </small>
                                                    </div>
                                                    <div>
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
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <p class="mb-1"><strong>Tổng tiền:</strong> 
                                                        <span class="text-danger fw-bold"><?php echo number_format($order['total']); ?> VNĐ</span>
                                                    </p>
                                                    <p class="mb-1"><strong>Phương thức:</strong> 
                                                        <?php echo $order['payment_method'] == 'cod' ? 'COD' : $order['payment_method']; ?>
                                                    </p>
                                                    <p class="mb-0"><strong>Người nhận:</strong> 
                                                        <?php echo htmlspecialchars($order['full_name']); ?>
                                                    </p>
                                                </div>
                                                
                                                <div class="d-flex gap-2">
                                                    <a href="../../index.php?controller=order&action=track_order" class="btn btn-outline-primary btn-sm flex-fill">
                                                        <i class="fas fa-search"></i> Tra cứu
                                                    </a>
                                                    <?php if ($order['status'] == 'pending'): ?>
                                                        <form method="POST" action="../../index.php?controller=order&action=cancel" class="flex-fill" 
                                                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                                                <i class="fas fa-times"></i> Hủy đơn
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
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

