<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kết nối database
require_once 'config/database.php';

$db = (new Database())->connect();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ?controller=user&action=login');
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy giỏ hàng
$cart_query = "SELECT c.*, p.name, pv.price, p.image, pv.sku, pv.size, pv.color 
               FROM cart c 
               LEFT JOIN product_variants pv ON c.variant_id = pv.id 
               LEFT JOIN products p ON pv.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = $db->prepare($cart_query);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = false;

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $province = $_POST['province'] ?? '';
    $ward = $_POST['ward'] ?? '';
    $order_notes = $_POST['order_notes'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'cod';
    
    if (!empty($full_name) && !empty($phone) && !empty($address) && !empty($cart_items)) {
        // Tính tổng tiền
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Tạo mã đơn hàng
        $order_code = 'CHUC' . date('YmdHis') . mt_rand(1000, 9999);
        
        try {
            // Bắt đầu transaction
            $db->beginTransaction();
            
            // Tạo đơn hàng
            $order_sql = "INSERT INTO orders (user_id, full_name, address, email, phone, province, ward, order_notes, total, payment_method, status, order_code_new) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)";
            $stmt = $db->prepare($order_sql);
            $stmt->execute([$user_id, $full_name, $address, $email, $phone, $province, $ward, $order_notes, $total, $payment_method, $order_code]);
            
            $order_id = $db->lastInsertId();
            
            if ($order_id) {
                // Tạo chi tiết đơn hàng
                foreach ($cart_items as $item) {
                    $detail_sql = "INSERT INTO order_details (order_id, variant_id, quantity, price) VALUES (?, ?, ?, ?)";
                    $stmt = $db->prepare($detail_sql);
                    $stmt->execute([$order_id, $item['variant_id'], $item['quantity'], $item['price']]);
                    
                    // Cập nhật số lượng sản phẩm
                    $update_sql = "UPDATE product_variants SET quantity = quantity - ? WHERE id = ?";
                    $stmt = $db->prepare($update_sql);
                    $stmt->execute([$item['quantity'], $item['variant_id']]);
                }
                
                // Xóa giỏ hàng
                $clear_sql = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $db->prepare($clear_sql);
                $stmt->execute([$user_id]);
                
                // Commit transaction
                $db->commit();
                
                $success = true;
            } else {
                $db->rollback();
                $error = "Không thể tạo đơn hàng";
            }
        } catch (Exception $e) {
            $db->rollback();
            $error = "Lỗi: " . $e->getMessage();
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin bắt buộc và có sản phẩm trong giỏ hàng!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng - Chuc Store</title>
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
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Chuc Store</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Trang chủ</a>
                <a class="nav-link" href="?controller=product&action=index">Sản phẩm</a>
                <a class="nav-link" href="?controller=cart&action=index">Giỏ hàng</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="?controller=user&action=logout">Đăng xuất</a>
                <?php else: ?>
                    <a class="nav-link" href="?controller=user&action=login">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

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
                            <a href="index.php" class="btn btn-primary me-2">
                                <i class="fas fa-home"></i> Về trang chủ
                            </a>
                            <a href="?controller=product&action=index" class="btn btn-outline-primary">
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
                                    <a href="?controller=product&action=index" class="btn btn-primary">
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

