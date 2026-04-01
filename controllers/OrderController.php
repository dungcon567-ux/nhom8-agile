<?php
require_once 'models/Order.php';
require_once 'models/OrderDetail.php';
require_once 'models/Cart.php';
require_once 'models/Product.php';

class OrderController
{
    private $orderModel;
    private $orderDetailModel;
    private $cartModel;
    private $productModel;

    public function __construct()
    {
        $db = (new Database())->connect();
        $this->orderModel = new Order($db);
        $this->orderDetailModel = new OrderDetail($db);
        $this->cartModel = new Cart($db);
        $this->productModel = new Product($db);
    }

    public function checkout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=user&action=login');
            exit;
        }

        // Chỉ hiển thị trang checkout, không xử lý logic
        require_once 'views/layouts/head.php';
        require_once 'views/orders/checkout.php';
    }

    public function track_order() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $order = null;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_code = $_POST['order_code'] ?? '';
            
            if (!empty($order_code)) {
                $db = (new Database())->connect();
                
                // Tìm đơn hàng theo order_code_new
                $sql = "SELECT o.*, u.username FROM orders o 
                        LEFT JOIN users u ON o.user_id = u.id 
                        WHERE o.order_code_new = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$order_code]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$order) {
                    // Thử tìm theo order_id
                    $sql = "SELECT o.*, u.username FROM orders o 
                            LEFT JOIN users u ON o.user_id = u.id 
                            WHERE o.id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$order_code]);
                    $order = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                
                if (!$order) {
                    $error = "Không tìm thấy đơn hàng với mã: " . htmlspecialchars($order_code);
                }
            } else {
                $error = "Vui lòng nhập mã đơn hàng";
            }
        }

        require_once 'views/layouts/head.php';
        require_once 'views/orders/track_order.php';
    }

    public function history() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=user&action=login');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $db = (new Database())->connect();
        
        // Lấy lịch sử đơn hàng của user
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/layouts/head.php';
        require_once 'views/orders/history.php';
    }

    public function cancel() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=user&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
            $order_id = intval($_POST['order_id']);
            $user_id = $_SESSION['user_id'];
            
            $db = (new Database())->connect();
            
            // Kiểm tra đơn hàng có thuộc về user không và có status pending không
            $sql = "SELECT * FROM orders WHERE id = ? AND user_id = ? AND status = 'pending'";
            $stmt = $db->prepare($sql);
            $stmt->execute([$order_id, $user_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                // Cập nhật status thành cancelled
                $sql = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$order_id]);
                
                // Hoàn trả số lượng sản phẩm
                $sql = "SELECT od.variant_id, od.quantity FROM order_details od WHERE od.order_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$order_id]);
                $order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($order_details as $detail) {
                    $sql = "UPDATE product_variants SET quantity = quantity + ? WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$detail['quantity'], $detail['variant_id']]);
                }
                
                header('Location: ?controller=order&action=history&success=1');
                exit;
            }
        }
        
        header('Location: ?controller=order&action=history');
        exit;
    }
}
?>