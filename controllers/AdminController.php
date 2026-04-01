<?php
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/Order.php';
require_once 'models/User.php';
require_once 'models/Post.php';
require_once 'models/Review.php';
require_once 'models/OrderDetail.php';

class AdminController
{
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $userModel;
    private $postModel;
    private $reviewModel;
    private $orderDetailModel;

    
    public function __construct()
    {
        $db = (new Database())->connect();
        $this->productModel = new Product($db);
        $this->categoryModel = new Category($db);
        $this->orderModel = new Order($db);
        $this->userModel = new User($db);
        $this->postModel = new Post($db);
        $this->reviewModel = new Review($db);
        $this->orderDetailModel = new OrderDetail($db);
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ?controller=user&action=login');
            exit;
        }
        // Lấy số liệu thống kê
        $total_products = $this->productModel->getTotalProducts();
        $total_orders = $this->orderModel->getTotalOrders();
        $total_users = $this->userModel->getTotalUsers();
        $total_reviews = $this->reviewModel->getTotalReviews();
        $recent_orders = $this->orderModel->getRecentOrders(5); // Lấy 5 đơn hàng gần đây   
        // Xác định controller hiện tại từ URL
        $controller = isset($_GET['controller']) ? $_GET['controller'] : 'admin';
        $action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
        require_once 'views/layouts/head.php';
        require_once 'views/admin/dashboard.php';
    }
    // Quản lý sản phẩm
    public function products()
    {
        $products = $this->productModel->getAllProducts();
        require_once 'views/layouts/head.php';
        require_once 'views/admin/products/index.php';
    }

    public function add_product()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Giả sử logic upload image đã có
            $image_name = ''; // Thay bằng code upload thực tế
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'image' => $image_name,
                'category_id' => $_POST['category_id'],
                'status' => $_POST['status'],
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0
            ];
            $product_id = $this->productModel->create($data);

            // Thêm variants nếu có từ form (giả sử form gửi mảng variants)
            if ($product_id && isset($_POST['variants'])) {
                foreach ($_POST['variants'] as $variant) {
                    $variant_data = [
                        'product_id' => $product_id,
                        'sku' => $variant['sku'] ?? '',
                        'color' => $variant['color'] ?? '',
                        'size' => $variant['size'] ?? '',
                        'price' => $variant['price'] ?? 0,
                        'quantity' => $variant['quantity'] ?? 0
                    ];
                    $this->productModel->createVariant($variant_data);
                }
            }
            header('Location: ?controller=admin&action=products');
        }
        $categories = $this->categoryModel->getAllCategories();
        require_once 'views/layouts/head.php';
        require_once 'views/admin/products/add.php';
    }

    public function edit_product()
    {
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Giả sử logic upload image đã có
            $image_name = ''; // Thay bằng code upload thực tế nếu thay đổi
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'image' => $image_name,
                'category_id' => $_POST['category_id'],
                'status' => $_POST['status'],
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0
            ];
            $this->productModel->update($id, $data);

        }
        $product = $this->productModel->getProductById($id);
        $variants = $this->productModel->getVariantsByProductId($id);
        $categories = $this->categoryModel->getAllCategories();
        require_once 'views/layouts/head.php';
        require_once 'views/admin/products/edit.php';
    }

    // Quản lý danh mục
    public function categories()
    {
        $categories = $this->categoryModel->getAllCategories();
        require_once 'views/layouts/head.php';
        require_once 'views/admin/categories/index.php';
    }

    public function add_category()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = ['name' => $_POST['name']];
            $this->categoryModel->create($data);
            header('Location: ?controller=admin&action=categories');
        }
        require_once 'views/layouts/head.php';
        require_once 'views/admin/categories/add.php';
    }

    public function edit_category()
    {
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = ['name' => $_POST['name']];
            $this->categoryModel->update($id, $data);
            header('Location: ?controller=admin&action=categories');
        }
        $category = $this->categoryModel->getCategoryById($id);
        require_once 'views/layouts/head.php';
        require_once 'views/admin/categories/edit.php';
    }

    public function delete_category()
    {
        $id = $_GET['id'];
        $this->categoryModel->delete($id);
        header('Location: ?controller=admin&action=categories');
    }

    // Quản lý đơn hàng
    public function orders()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ?controller=user&action=login');
            exit;
        }
        $items_per_page = 10;
        $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($current_page - 1) * $items_per_page;
        $orders = $this->orderModel->getAllOrders($items_per_page, $offset);
        $total_orders = $this->orderModel->getTotalOrders();
        require_once 'views/layouts/head.php';
        require_once 'views/admin/orders/index.php';
    }


    public function edit_order()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ?controller=user&action=login');
            exit;
        }
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?controller=admin&action=orders');
            exit;
        }
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            header('Location: ?controller=admin&action=orders');
            exit;
        }
        require_once 'views/layouts/head.php';
        require_once 'views/admin/orders/update.php';
    }

    public function update_order()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ?controller=user&action=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $new_status = $_POST['status'] ?? null;
            if (!$id || !$new_status) {
                header('Location: ?controller=admin&action=orders&error=invalid_data');
                exit;
            }
            $order = $this->orderModel->getOrderById($id);
            if (!$order) {
                header('Location: ?controller=admin&action=orders&error=invalid_data');
                exit;
            }
            $current_status = $order['status'];
            // Logic không cho phép cập nhật ngược
            $allowed_transition = false;
            switch ($current_status) {
                case 'pending':
                    $allowed_transition = in_array($new_status, ['processing', 'cancelled']);
                    break;
                case 'processing':
                    $allowed_transition = in_array($new_status, ['shipped', 'cancelled']);
                    break;
                case 'shipped':
                    $allowed_transition = in_array($new_status, ['delivered', 'cancelled']);
                    break;
                case 'delivered':
                    $allowed_transition = ($new_status === 'completed');
                    break;
                case 'completed':
                case 'cancelled':
                    $allowed_transition = false;
                    break;
            }
            if (!$allowed_transition) {
                header('Location: ?controller=admin&action=edit_order&id=' . $id . '&error=Không thể cập nhật ngược trạng thái hoặc trạng thái đã hoàn tất.');
                exit;
            }
            $data = ['status' => $new_status];
            if ($this->orderModel->update($id, $data)) {
                header('Location: ?controller=admin&action=orders&success=1');
            } else {
                header('Location: ?controller=admin&action=edit_order&id=' . $id . '&error=Cập nhật thất bại');
            }
            exit;
        }
        header('Location: ?controller=admin&action=orders');
    }
    public function order_details()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ?controller=user&action=login');
            exit;
        }
        $order_id = $_GET['order_id'] ?? null;
        if ($order_id) {
            $order = $this->orderModel->getOrderById($order_id);
            $order_details = $this->orderDetailModel->getOrderDetailsByOrderId($order_id);
            require_once 'views/layouts/head.php';
            require_once 'views/admin/order_details/index.php';
        } else {
            header('Location: ?controller=admin&action=orders');
            exit;
        }
    }
    public function delete_order()
    {
        $id = $_GET['id'];
        if ($this->orderModel->delete($id)) {
            header('Location: ?controller=admin&action=orders&success=1');
        } else {
            header('Location: ?controller=admin&action=orders&error=invalid_data');
        }
    }
    public function update_order_status()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
            $id = intval($_POST['id']);
            $status = $_POST['status'];
            $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'];
            if (in_array($status, $valid_statuses)) {
                if ($this->orderModel->updateStatus($id, $status)) {
                    header('Location: ?controller=admin&action=orders&success=1');
                } else {
                    header('Location: ?controller=admin&action=orders&error=invalid_data');
                }
            } else {
                header('Location: ?controller=admin&action=orders&error=invalid_data');
            }
        } else {
            header('Location: ?controller=admin&action=orders');
        }
        exit;
    }

    // Quản lý người dùng
    public function users()
    {
        $users = $this->userModel->getAllUsers();
        require_once 'views/layouts/head.php';
        require_once 'views/admin/users/index.php';
    }

    public function add_user()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => $_POST['role']
            ];
            $this->userModel->create($data);
            header('Location: ?controller=admin&action=users');
        }
        require_once 'views/layouts/head.php';
        require_once 'views/admin/users/add.php';
    }

    public function edit_user()
    {
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'role' => $_POST['role']
            ];
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            $this->userModel->update($id, $data);
            header('Location: ?controller=admin&action=users');
        }
        $user = $this->userModel->getUserById($id);
        require_once 'views/layouts/head.php';
        require_once 'views/admin/users/edit.php';
    }
    // Xóa người dùng
    public function delete_user()
    {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: ?controller=admin&action=users&error=invalid_data');
            exit;
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($id == $_SESSION['user_id']) {
            header('Location: ?controller=admin&action=users&error=cannot_delete_self');
            exit;
        }
        try {
            if ($this->userModel->delete($id)) {
                header('Location: ?controller=admin&action=users&success=deleted');
            } else {
                error_log("Delete user failed for ID: " . $id);  // Log đơn giản, không cần errorInfo
                header('Location: ?controller=admin&action=users&error=failed');
            }
        } catch (PDOException $e) {
            error_log("PDO Exception: " . $e->getMessage());
            header('Location: ?controller=admin&action=users&error=failed');
        }
    }

    // Quản lý bài viết
    public function posts()
    {
        $posts = $this->postModel->getAllPosts();
        require_once 'views/layouts/head.php';
        require_once 'views/admin/posts/index.php';
    }

    public function add_post()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'user_id' => 1 // Giả sử admin đăng nhập
            ];
            $this->postModel->create($data);
            header('Location: ?controller=admin&action=posts');
        }
        require_once 'views/layouts/head.php';
        require_once 'views/admin/posts/add.php';
    }

    public function edit_post()
    {
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => $_POST['title'],
                'content' => $_POST['content']
            ];
            $this->postModel->update($id, $data);
            header('Location: ?controller=admin&action=posts');
        }
        $post = $this->postModel->getPostById($id);
        require_once 'views/layouts/head.php';
        require_once 'views/admin/posts/edit.php';
    }

    public function delete_post()
    {
        $id = $_GET['id'];
        $this->postModel->delete($id);
        header('Location: ?controller=admin&action=posts');
    }

    // Quản lý đánh giá
    public function reviews()
    {
        $reviews = $this->reviewModel->getAllReviews();
        require_once 'views/layouts/head.php';
        require_once 'views/admin/reviews/index.php';
    }

    public function delete_review()
    {
        $id = $_GET['id'];
        $this->reviewModel->delete($id);
        header('Location: ?controller=admin&action=reviews');
    }
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: ?controller=user&action=login');
        exit;
    }
}
