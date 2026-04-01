<?php
// views/cart/index.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .order-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        
        .order-card .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            padding: 1.5rem;
        }
        
        .order-table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .order-table th {
            background-color: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 1rem 0.75rem;
        }
        
        .order-table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }
        
        .order-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }
        
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 20px;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }
        
        .empty-orders {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 3rem 2rem;
        }
        
        .empty-orders i {
            color: #6c757d;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .order-table {
                font-size: 0.875rem;
            }
            
            .order-table th,
            .order-table td {
                padding: 0.5rem 0.25rem;
            }
            
            .badge {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include_once 'views/layouts/head.php'; ?>
    
    <div class="container my-5">
        <div class="banner mb-4">
            <img src="public/images/banner.jpg" alt="Banner" class="img-fluid rounded">
        </div>
        <h2 class="text-center mb-4">Giỏ hàng</h2>
        <?php if (empty($cart_items)): ?>
            <p class="text-center text-muted">Giỏ hàng của bạn đang trống.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th scope="col">Hình ảnh</th>
            <th scope="col">Sản phẩm</th>
            <th scope="col">Size</th>
            <th scope="col">Màu</th>
            <th scope="col">Số lượng</th>
            <th scope="col">Giá</th>
            <th scope="col">Tổng</th>
            <th scope="col">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0; ?>
        <?php foreach ($cart_items as $item): ?>
            <tr>
                <td>
                    <?php if (!empty($item['image'])): ?>
                        <img src="public/images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                    <?php else: ?>
                        <span>Không có hình ảnh</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($item['color'] ?? 'N/A'); ?></td>
                <td>
                    <form action="?controller=cart&action=update" method="POST" class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control w-25">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Cập nhật</button>
                    </form>
                </td>
                <td><?php echo number_format($item['price']); ?> VNĐ</td>
                <td><?php echo number_format($item['price'] * $item['quantity']); ?> VNĐ</td>
                <td>
                    <a href="?controller=cart&action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm">Xóa</a>
                </td>
            </tr>
            <?php $total += $item['price'] * $item['quantity']; ?>
        <?php endforeach; ?>
    </tbody>
</table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h4>Tổng cộng: <span class="text-danger"><?php echo number_format($total); ?> VNĐ</span></h4>
                <a href="?controller=order&action=checkout" class="btn btn-success">Thanh toán</a>
            </div>
        <?php endif; ?>
        
        <!-- Phần hiển thị đơn hàng đã đặt -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="mt-5">
                <div class="card shadow order-card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>
                            📋 Đơn hàng đã đặt
                        </h3>
                        <small>Hiển thị 5 đơn hàng gần nhất của bạn</small>
                    </div>
                    <div class="card-body">
                        <?php
                        // Lấy danh sách đơn hàng của user
                        $orderModel = new Order((new Database())->connect());
                        $user_orders = $orderModel->getOrdersByUserId($_SESSION['user_id'], 5, 0); // Lấy 5 đơn hàng gần nhất
                        ?>
                        
                        <?php if (!empty($user_orders)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle order-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 25%;">Mã đơn hàng</th>
                                            <th class="text-center" style="width: 20%;">Ngày đặt</th>
                                            <th class="text-center" style="width: 20%;">Tổng tiền</th>
                                            <th class="text-center" style="width: 20%;">Trạng thái</th>
                                            <th class="text-center" style="width: 15%;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($user_orders as $order): ?>
                                            <tr class="border-bottom">
                                                <td class="text-center">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <strong class="text-primary"><?php echo htmlspecialchars($order['order_code'] ?? 'N/A'); ?></strong>
                                                        <small class="text-muted">ID: #<?php echo $order['id']; ?></small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="fw-bold"><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></span>
                                                        <small class="text-muted"><?php echo date('H:i', strtotime($order['created_at'])); ?></small>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold text-success fs-6">
                                                        <?php echo number_format($order['total'], 0, ',', '.') . ' VNĐ'; ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $status_badges = [
                                                        'pending' => '<span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-clock me-1"></i>Đang chờ</span>',
                                                        'processing' => '<span class="badge bg-info text-white px-3 py-2"><i class="fas fa-cog me-1"></i>Đang xử lý</span>',
                                                        'shipped' => '<span class="badge bg-primary text-white px-3 py-2"><i class="fas fa-shipping-fast me-1"></i>Đã giao hàng</span>',
                                                        'delivered' => '<span class="badge bg-success text-white px-3 py-2"><i class="fas fa-check-circle me-1"></i>Đã nhận</span>',
                                                        'cancelled' => '<span class="badge bg-danger text-white px-3 py-2"><i class="fas fa-times-circle me-1"></i>Đã hủy</span>',
                                                        'returned' => '<span class="badge bg-secondary text-white px-3 py-2"><i class="fas fa-undo me-1"></i>Hoàn hàng</span>'
                                                    ];
                                                    echo $status_badges[$order['status']] ?? '<span class="badge bg-secondary text-white px-3 py-2">' . $order['status'] . '</span>';
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-column gap-2">
                                                        <a href="?controller=order&action=track_order&order_code=<?php echo urlencode($order['order_code']); ?>" 
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-search me-1"></i>Tra cứu
                                                        </a>
                                                        <?php if ($order['status'] === 'pending'): ?>
                                                            <button class="btn btn-outline-danger btn-sm" 
                                                                    onclick="confirmCancelOrder(<?php echo $order['id']; ?>, '<?php echo htmlspecialchars($order['order_code']); ?>')">
                                                                <i class="fas fa-times me-1"></i>Hủy đơn
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-center mt-4">
                                <a href="?controller=order&action=my_orders" class="btn btn-outline-info me-2">
                                    <i class="fas fa-list me-1"></i>Xem tất cả đơn hàng
                                </a>
                                <a href="?controller=order&action=track_order" class="btn btn-outline-secondary">
                                    <i class="fas fa-search me-1"></i>Tra cứu đơn hàng khác
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5 empty-orders">
                                <div class="mb-4">
                                    <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted mb-3">Bạn chưa có đơn hàng nào</h5>
                                <p class="text-muted mb-4">Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên!</p>
                                <a href="?controller=product&action=index" class="btn btn-primary btn-lg">
                                    <i class="fas fa-shopping-cart me-2"></i>Bắt đầu mua sắm
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include_once 'views/layouts/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script>
        // Hàm xác nhận hủy đơn hàng
        function confirmCancelOrder(orderId, orderCode) {
            if (confirm(`Bạn có chắc chắn muốn hủy đơn hàng ${orderCode}?\n\nLưu ý: Chỉ có thể hủy đơn hàng khi đang ở trạng thái "Đang chờ"`)) {
                // Tạo form để submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '?controller=order&action=cancel_order';
                
                const orderIdInput = document.createElement('input');
                orderIdInput.type = 'hidden';
                orderIdInput.name = 'order_id';
                orderIdInput.value = orderId;
                
                form.appendChild(orderIdInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Thêm hiệu ứng hover cho các hàng trong bảng
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                    this.style.transition = 'background-color 0.3s ease';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
            
            // Thêm tooltip cho các badge trạng thái
            const statusBadges = document.querySelectorAll('.badge');
            statusBadges.forEach(badge => {
                badge.title = badge.textContent.trim();
            });
        });
    </script>
</body>
</html>