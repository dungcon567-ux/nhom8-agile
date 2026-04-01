<?php
// views/orders/my_orders.php
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Đơn Hàng Của Tôi</h2>
            
            <?php if (isset($_GET['success']) && $_GET['success'] == 'cancelled'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Đơn hàng đã được hủy thành công!
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error']) && $_GET['error'] == 'unauthorized'): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Bạn không có quyền truy cập đơn hàng này!
                </div>
            <?php endif; ?>

            <?php if (empty($orders)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Bạn chưa có đơn hàng nào</h4>
                    <p class="text-muted">Hãy mua sắm để có đơn hàng đầu tiên!</p>
                    <a href="?controller=product&action=index" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Mua sắm ngay
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <strong>#<?php echo $order['id']; ?></strong>
                                    </td>
                                    <td>
                                        <div class="order-products">
                                            <?php 
                                            $product_names = explode(',', $order['product_names'] ?? '');
                                            $sizes = explode(',', $order['sizes'] ?? '');
                                            $colors = explode(',', $order['colors'] ?? '');
                                            
                                            for ($i = 0; $i < count($product_names); $i++): 
                                                if (!empty($product_names[$i])): ?>
                                                    <div class="product-item">
                                                        <span class="product-name"><?php echo htmlspecialchars($product_names[$i]); ?></span>
                                                        <?php if (isset($sizes[$i]) && !empty($sizes[$i])): ?>
                                                            <span class="badge bg-secondary">Size: <?php echo $sizes[$i]; ?></span>
                                                        <?php endif; ?>
                                                        <?php if (isset($colors[$i]) && !empty($colors[$i])): ?>
                                                            <span class="badge bg-info">Màu: <?php echo $colors[$i]; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-danger fw-bold">
                                            <?php echo number_format($order['total']); ?> VNĐ
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $status_class = '';
                                        $status_text = '';
                                        switch ($order['status']) {
                                            case 'pending':
                                                $status_class = 'bg-warning';
                                                $status_text = 'Chờ xử lý';
                                                break;
                                            case 'processing':
                                                $status_class = 'bg-info';
                                                $status_text = 'Đang xử lý';
                                                break;
                                            case 'shipped':
                                                $status_class = 'bg-primary';
                                                $status_text = 'Đã gửi hàng';
                                                break;
                                            case 'delivered':
                                                $status_class = 'bg-success';
                                                $status_text = 'Đã giao hàng';
                                                break;
                                            case 'completed':
                                                $status_class = 'bg-success';
                                                $status_text = 'Hoàn thành';
                                                break;
                                            case 'cancelled':
                                                $status_class = 'bg-danger';
                                                $status_text = 'Đã hủy';
                                                break;
                                            default:
                                                $status_class = 'bg-secondary';
                                                $status_text = $order['status'];
                                        }
                                        ?>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="?controller=order&action=order_detail&id=<?php echo $order['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Chi tiết
                                            </a>
                                            
                                            <?php if ($order['status'] == 'pending'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmCancelOrder(<?php echo $order['id']; ?>)">
                                                    <i class="fas fa-times"></i> Hủy
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Phân trang đơn hàng">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?controller=order&action=my_orders&page=<?php echo $page-1; ?>">
                                        <i class="fas fa-chevron-left"></i> Trước
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $page-2); $i <= min($total_pages, $page+2); $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?controller=order&action=my_orders&page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?controller=order&action=my_orders&page=<?php echo $page+1; ?>">
                                        Sau <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal xác nhận hủy đơn hàng -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy đơn hàng này?</p>
                <p class="text-muted small">Lưu ý: Chỉ có thể hủy đơn hàng khi đang ở trạng thái "Chờ xử lý"</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                <form id="cancelOrderForm" method="POST" action="?controller=order&action=cancel_order">
                    <input type="hidden" name="order_id" id="cancelOrderId">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Hủy đơn hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.order-products {
    max-width: 300px;
}

.product-item {
    margin-bottom: 5px;
    padding: 5px;
    border-radius: 4px;
    background-color: #f8f9fa;
}

.product-name {
    font-weight: 600;
    color: #333;
}

.badge {
    margin-left: 5px;
    font-size: 0.75rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.btn-group .btn {
    margin-right: 5px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>

<script>
function confirmCancelOrder(orderId) {
    document.getElementById('cancelOrderId').value = orderId;
    var modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
    modal.show();
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>


