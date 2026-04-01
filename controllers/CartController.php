<?php
require_once 'config/database.php';
require_once 'models/Cart.php';
require_once 'models/Product.php';

class CartController {
    private $cartModel;

    public function __construct() {
        $db = (new Database())->connect();
        $this->cartModel = new Cart($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=user&action=login');
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $cart_items = $this->cartModel->getCartItems($user_id);
        require_once 'views/layouts/head.php';
        require_once 'views/cart/index.php';
    }

    public function add() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=user&action=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'variant_id' => $_POST['variant_id'],
                'quantity' => (int)($_POST['quantity'] ?? 1)
            ];

            // Kiểm tra tồn kho
            $productModel = new Product((new Database())->connect());
            $variant = $productModel->getVariantById($data['variant_id']);

            if ($variant && $variant['quantity'] >= $data['quantity']) {
                $this->cartModel->add($data);

                if (isset($_GET['redirect']) && $_GET['redirect'] === 'checkout') {
                    header('Location: ?controller=order&action=checkout');
                } else {
                    header('Location: ?controller=cart&action=index');
                }
            } else {
                // Hiển thị thông báo lỗi nếu không đủ số lượng
                $_SESSION['error'] = "Sản phẩm không đủ số lượng trong kho hoặc không tồn tại.";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $quantity = $_POST['quantity'];
            $this->cartModel->update($id, $quantity);
            header('Location: ?controller=cart&action=index');
        }
    }

    public function delete() {
        $id = $_GET['id'];
        $this->cartModel->delete($id);
        header('Location: ?controller=cart&action=index');
    }
}
?>