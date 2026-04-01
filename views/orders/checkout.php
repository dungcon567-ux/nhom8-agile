<!DOCTYPE html>
<html lang="vi">
    <?php
// Khởi tạo mặc định nếu chưa được gán từ controller
$success = $success ?? false;
$error = $error ?? '';
?>
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng - Dung Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 15px; }
        .card-header { border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; }
        .btn-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; border-radius: 10px; padding: 15px; font-weight: bold; font-size: 18px; }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4); }
        .form-control, .form-select { border-radius: 10px; border: 2px solid #e9ecef; padding: 12px; }
        .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); }
    </style>
</head>
<body>
    <?php include_once 'views/layouts/head.php' ?>
    <div class="container py-5">
        <?php if ($success): ?>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="alert alert-success text-center" style="border-radius: 15px;">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h3>🎉 Đặt hàng thành công!</h3>
                        <p class="lead">Mã đơn hàng của bạn:</p>
                        <div class="bg-light p-3 rounded">
                            <h4 class="text-primary mb-0"><strong><?php echo htmlspecialchars($order_code); ?></strong></h4>
                        </div>
                        <p class="mt-3">Vui lòng lưu lại mã này để tra cứu đơn hàng!</p>
                        <div class="mt-4">
                            <a href="../../index.php" class="btn btn-primary me-2">
                                <i class="fas fa-home"></i> Về trang chủ
                            </a>
                            <a href="../../index.php?controller=product&action=index" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-cart"></i> Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header text-white">
                            <h4 class="mb-0"><i class="fas fa-shopping-bag"></i> Đặt hàng</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($error): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (empty($cart_items)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5>Giỏ hàng trống</h5>
                                    <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                                    <a href="../../index.php?controller=product&action=index" class="btn btn-primary">
                                        <i class="fas fa-store"></i> Mua sắm ngay
                                    </a>
                                </div>
                            <?php else: ?>
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label"><i class="fas fa-user"></i> Họ và tên *</label>
                                                <input type="text" name="full_name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label"><i class="fas fa-phone"></i> Số điện thoại *</label>
                                                <input type="tel" name="phone" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Địa chỉ *</label>
                                        <input type="text" name="address" class="form-control" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label"><i class="fas fa-city"></i> Tỉnh/Thành phố</label>
                                                <input type="text" name="province" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label"><i class="fas fa-home"></i> Phường/Xã</label>
                                                <input type="text" name="ward" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label"><i class="fas fa-comment"></i> Ghi chú đơn hàng</label>
                                        <textarea name="order_notes" class="form-control" rows="3" placeholder="Ghi chú về đơn hàng (không bắt buộc)"></textarea>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label"><i class="fas fa-credit-card"></i> Phương thức thanh toán</label>
                                        <select name="payment_method" class="form-select">
                                            <option value="cod">💳 Thanh toán khi nhận hàng (COD)</option>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-check"></i> Đặt hàng ngay
                                    </button>
                                </form>
                                
                                <hr class="my-4">
                                
                                <h5><i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn:</h5>
                                <div class="list-group">
                                    <?php $total = 0; ?>
                                    <?php foreach ($cart_items as $item): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-ruler"></i> Size: <?php echo htmlspecialchars($item['size']); ?> | 
                                                    <i class="fas fa-palette"></i> Màu: <?php echo htmlspecialchars($item['color']); ?>
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-hashtag"></i> Số lượng: <?php echo $item['quantity']; ?> x 
                                                    <span class="text-primary"><?php echo number_format($item['price']); ?> VNĐ</span>
                                                </small>
                                            </div>
                                            <span class="badge bg-primary rounded-pill fs-6">
                                                <?php echo number_format($item['price'] * $item['quantity']); ?> VNĐ
                                            </span>
                                        </div>
                                        <?php $total += $item['price'] * $item['quantity']; ?>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="text-end mt-4">
                                    <h4 class="text-success">
                                        <i class="fas fa-calculator"></i> Tổng cộng: 
                                        <span class="text-danger fw-bold"><?php echo number_format($total); ?> VNĐ</span>
                                    </h4>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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