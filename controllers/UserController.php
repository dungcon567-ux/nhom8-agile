<?php
require_once 'config/database.php';
require_once 'models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $db = (new Database())->connect();
        $this->userModel = new User($db);
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ thông tin.";
            } else {
                $user = $this->userModel->getUserByUsername($username);
                if ($user && password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'] ?? 'customer'; // Default role nếu không có
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    // Cập nhật thời gian đăng nhập
                    $now = date('Y-m-d H:i:s');
                    $this->userModel->updateLastLogin($user['id'], $now);

                    header('Location: ?controller=home&action=index');
                    exit;
                } else {
                    $error = "Sai tên đăng nhập hoặc mật khẩu.";
                }
            }
        }
        require_once 'views/layouts/head.php';
        require_once 'views/user/login.php';
    }

    public function register() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                $error = "Vui lòng nhập đầy đủ thông tin.";
            } elseif ($password !== $confirmPassword) {
                $error = "Mật khẩu không khớp.";
            } else {
                $data = [
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'email' => $email,
                    'full_name' => $username,
                    'role' => 'user'
                ];
                // Kiểm tra username hoặc email đã tồn tại
                $existingUser = $this->userModel->getUserByUsername($username);
                if ($existingUser) {
                    $error = "Tên đăng nhập đã tồn tại.";
                } else {
                    if ($this->userModel->create($data)) {
                        header('Location: ?controller=user&action=login');
                        exit;
                    } else {
                        $error = "Đăng ký thất bại. Vui lòng thử lại.";
                    }
                }
            }
        }
        require_once 'views/layouts/head.php';
        require_once 'views/user/register.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ?controller=home&action=index');
        exit;
    }
}
?>