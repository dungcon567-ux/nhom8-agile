<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'models/Category.php';

class ProductController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $db = (new Database())->connect();
        $this->productModel = new Product($db);
        $this->categoryModel = new Category($db);
    }

    public function index() {
        // Lấy các tham số lọc
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        $size = isset($_GET['size']) ? $_GET['size'] : null;
        $color = isset($_GET['color']) ? $_GET['color'] : null;
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
        $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';
        
        // Phân trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 12;
        $offset = ($page - 1) * $per_page;
        
        // Lấy dữ liệu
        $filters = [
            'category_id' => $category_id,
            'size' => $size,
            'color' => $color,
            'search' => $search,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'sort' => $sort
        ];
        
        $products = $this->productModel->getFilteredProducts($filters, $per_page, $offset);
        $total_products = $this->productModel->getTotalFilteredProducts($filters);
        $categories = $this->categoryModel->getAllCategories();
        
        // Lấy danh sách size và color có sẵn
        $available_sizes = $this->productModel->getAvailableSizes();
        $available_colors = $this->productModel->getAvailableColors();
        
        // Tính tổng số trang
        $total_pages = ceil($total_products / $per_page);
        
        require_once 'views/layouts/head.php';
        require_once 'views/products/index.php';
    }

    public function showDetail($id) {
        if (!$id || !is_numeric($id)) {
            header('Location: ?controller=product&action=index&error=no_product_id');
            exit;
        }

        $product = $this->productModel->getProductById($id);
        $variants = $this->productModel->getVariantsByProductId($id);
        if (!$product) {
            header('Location: ?controller=product&action=index&error=product_not_found');
            exit;
        }

        // Lấy vai trò từ session
        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';

        // Xử lý thêm vào giỏ hàng cho khách hàng
        if ($role === 'customer' && isset($_POST['add_to_cart'])) {
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $variant_id = isset($_POST['variant_id']) ? $_POST['variant_id'] : null;
            // Logic thêm vào giỏ hàng (gọi CartController)
            header('Location: ?controller=cart&action=add&variant_id=' . $variant_id . '&quantity=' . $quantity);
            exit;
        }
        
    }

    public function detail($id = null) {
        if (!$id || !is_numeric($id)) {
            header('Location: ' . BASE_URL . '?controller=product&action=index&error=no_product_id');
            exit;
        }
        $product = $this->productModel->getProductById($id);
        $variants = $this->productModel->getVariantsByProductId($id);
        if (!$product) {
            header('Location: ' . BASE_URL . '?controller=product&action=index&error=product_not_found');
            exit;
        }
        require_once 'views/layouts/head.php';
        require_once 'views/products/detail.php';
    }
}
?>