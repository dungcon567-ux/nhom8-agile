<?php
require_once 'config/database.php';
require_once 'models/Review.php';
require_once 'models/Product.php';

class ReviewController {
    private $reviewModel;

    public function __construct() {
        $db = (new Database())->connect();
        $this->reviewModel = new Review($db);
    }

    public function index() {
        $product_id = $_GET['product_id'];
        $reviews = $this->reviewModel->getReviewsByProductId($product_id);
        require_once 'views/layouts/main.php';
        require_once 'views/reviews/index.php';
    }

    public function add() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=user&action=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'product_id' => $_POST['product_id'],
                'user_id' => $_SESSION['user_id'],
                'rating' => $_POST['rating'],
                'comment' => $_POST['comment']
            ];
            $this->reviewModel->create($data);
            header('Location: ?controller=review&action=index&product_id=' . $_POST['product_id']);
        }
    }
}
?>