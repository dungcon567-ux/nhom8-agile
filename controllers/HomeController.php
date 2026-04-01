<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'models/Post.php';


class HomeController {
    private $productModel;
   

    public function __construct() {
        $db = (new Database())->connect();
        $this->productModel = new Product($db);
    }
     public function index() {
        $products = $this->productModel->getFeaturedProducts(12);
        $newProducts = array_slice($products, 0, 6);
        $bestSellingProducts = array_slice($products, 6, 6); 
        // Truyền dữ liệu vào view
        $data = [
            'newProducts' => $newProducts,
            'bestSellingProducts' => $bestSellingProducts,
            'products' => $products,
            // 'posts' => $posts
        ];
        
        // Giả sử main.php là layout chứa nội dung
        require_once 'views/home/index.php';
    }
    public function detail() {
        $id = $_GET['id'] ?? null; // Lấy ID từ request
        if (!$id || !is_numeric($id)) {
            header('Location: ?controller=home&action=index&error=no_id');
            exit;
        }
        $product = $this->productModel->getProductById($id);
        $variants = $this->productModel->getVariantsByProductId($id);
        require_once 'views/home/detail.php'; // Giả sử view chi tiết
    }
    
}
?>