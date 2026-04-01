<?php // views/layouts/head.php 
require_once 'config/database.php';
require_once 'models/Cart.php';
?>
<header>
    <h1>CHUC STORE</h1>
    <nav>
        <a href="?controller=home&action=index">Trang chủ</a>
        <a href="?controller=product&action=index">Sản phẩm</a>
        <a href="?controller=post&action=index">Tin tức</a>
        <a href="?controller=order&action=track_order">📋 Tra cứu đơn hàng</a>
        <?php
        if (isset($_SESSION['user_id'])) {
            $username = $_SESSION['username'] ?? 'User';
            $role = $_SESSION['role'] ?? 'user';

            if ($role === 'admin') {
                echo "<span>Xin chào Dũng Academy</span>";
                echo "<a href='?controller=admin&action=dashboard' class='nav-link'>Quản trị</a>";
            } else {
                echo "<span class='nav-item'>Xin chào $username</span>";
            }

            echo "<a href='?controller=user&action=logout'>Đăng xuất</a>";
            // Tính tổng số lượng sản phẩm trong giỏ hàng
            $cartCount = 0;
            if (isset($_SESSION['user_id'])) {
                try {
                    $cartModel = new Cart((new Database())->connect());
                    $cart_items = $cartModel->getCartItems($_SESSION['user_id']);
                    foreach ($cart_items as $item) {
                        $cartCount += $item['quantity'];
                    }
                } catch (PDOException $e) {
                    error_log("Error fetching cart items in head.php: " . $e->getMessage());
                    $cartCount = 0; // Gán mặc định nếu lỗi
                }
            }
            echo "<a href='?controller=cart&action=index' class='nav-link cart-icon'>";
            echo "Giỏ hàng <span class='cart-count'>($cartCount)</span>";
            echo "</a>";
        } else {
            echo "<a href='?controller=user&action=login'>Đăng nhập</a>";
            echo "<a href='?controller=user&action=register'>Đăng ký</a>";
        }
        ?>
    </nav>
</header>