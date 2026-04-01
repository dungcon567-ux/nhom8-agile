<?php 
date_default_timezone_set('Asia/Ho_Chi_Minh'); 
session_start();

// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Load database config
    require_once 'config/database.php';
    
    // Load controllers
    require_once 'controllers/HomeController.php';
    require_once 'controllers/ProductController.php';
    require_once 'controllers/OrderController.php';
    require_once 'controllers/AdminController.php';
    require_once 'controllers/UserController.php';
    require_once 'controllers/CartController.php';
    require_once 'controllers/PostController.php';
    require_once 'controllers/ReviewController.php';

    // Lấy controller và action từ URL
    $controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';

    // Routing
    switch ($controller) {
        case 'home':
            $controller = new HomeController();
            $controller->index();
            break;
            
        case 'product':
            $controller = new ProductController();
            if ($action == 'index') {
                $controller->index();
            } elseif ($action == 'detail') {
                $id = isset($_GET['id']) ? intval($_GET['id']) : null;
                $controller->detail($id);
            }
            break;
            
        case 'order':
            $controller = new OrderController();
            if ($action == 'checkout') {
                $controller->checkout();
            } elseif ($action == 'my_orders') {
                $controller->my_orders();
            } elseif ($action == 'order_detail') {
                $controller->order_detail();
            } elseif ($action == 'cancel_order') {
                $controller->cancel_order();
            } elseif ($action == 'track_order') {
                $controller->track_order();
            }
            break;
            
        case 'admin':
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
                header('Location: ?controller=user&action=login');
                exit;
            }
            $controller = new AdminController();
            switch ($action) {
                case 'dashboard':
                    $controller->dashboard();
                    break;
                case 'products':
                    $controller->products();
                    break;
                case 'add_product':
                    $controller->add_product();
                    break;
                case 'edit_product':
                    $controller->edit_product();
                    break;
                case 'categories':
                    $controller->categories();
                    break;
                case 'add_category':
                    $controller->add_category();
                    break;
                case 'edit_category':
                    $controller->edit_category();
                    break;
                case 'delete_category':
                    $controller->delete_category();
                    break;
                case 'orders':
                    $controller->orders();
                    break;
                case 'update_order':
                    $controller->update_order();
                    break;
                case 'delete_order':
                    $controller->delete_order();
                    break;
                case 'order_details':
                    $controller->order_details();
                    break;
                case 'quick_update_order_status':
                    $controller->quick_update_order_status();
                    break;
                case 'users':
                    $controller->users();
                    break;
                case 'add_user':
                    $controller->add_user();
                    break;
                case 'edit_user':
                    $controller->edit_user();
                    break;
                case 'delete_user':
                    $controller->delete_user();
                    break;
                case 'posts':
                    $controller->posts();
                    break;
                case 'add_post':
                    $controller->add_post();
                    break;
                case 'edit_post':
                    $controller->edit_post();
                    break;
                case 'delete_post':
                    $controller->delete_post();
                    break;
                case 'reviews':
                    $controller->reviews();
                    break;
                case 'delete_review':
                    $controller->delete_review();
                    break;
                default:
                    echo "404 Not Found";
            }
            break;
            
        case 'user':
            $controller = new UserController();
            switch ($action) {
                case 'login':
                    $controller->login();
                    break;
                case 'register':
                    $controller->register();
                    break;
                case 'logout':
                    $controller->logout();
                    break;
                default:
                    echo "404 Not Found";
            }
            break;
            
        case 'cart':
            $controller = new CartController();
            switch ($action) {
                case 'index':
                    $controller->index();
                    break;
                case 'add':
                    $controller->add();
                    break;
                case 'update':
                    $controller->update();
                    break;
                case 'delete':
                    $controller->delete();
                    break;
                default:
                    echo "404 Not Found";
            }
            break;
            
        case 'post':
            $controller = new PostController();
            if ($action == 'index') {
                $controller->index();
            }
            break;
            
        case 'review':
            $controller = new ReviewController();
            switch ($action) {
                case 'index':
                    $controller->index();
                    break;
                case 'add':
                    $controller->add();
                    break;
                default:
                    echo "404 Not Found";
            }
            break;
            
        default:
            echo "404 Not Found";
    }

} catch (Exception $e) {
    // Hiển thị lỗi chi tiết
    echo "<h1>Lỗi hệ thống</h1>";
    echo "<p><strong>Lỗi:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . htmlspecialchars($e->getLine()) . "</p>";
    
    if (isset($_GET['debug']) && $_GET['debug'] == 1) {
        echo "<h2>Stack Trace:</h2>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}
?>
