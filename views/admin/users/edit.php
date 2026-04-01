<?php
// views/admin/users/edit.php
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Sửa người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .nav-link {
            color: #000;
            padding: 10px 20px;
            font-size: 16px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #e9ecef;
            color: #0d6efd;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: static;
                height: auto;
                width: 100%;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include_once 'views/admin/layouts/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto col-lg-10 main-content">
                <h2 class="mb-4">Sửa người dùng #<?php echo htmlspecialchars($user['id']); ?></h2>
                <div class="card shadow">
                    <div class="card-body">
                        <!-- Thông báo trạng thái -->
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_GET['error'] == 'invalid_data' ? 'Dữ liệu không hợp lệ!' : 'Cập nhật người dùng thất bại!'; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="?controller=admin&action=edit_user&id=<?php echo htmlspecialchars($user['id']); ?>" method="POST" id="edit-user-form">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập:</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required minlength="3" maxlength="50">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu (để trống nếu không đổi):</label>
                                <input type="password" class="form-control" id="password" name="password" minlength="6">
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Vai trò:</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" disabled>Chọn vai trò</option>
                                    <option value="customer" <?php echo $user['role'] == 'customer' ? 'selected' : ''; ?>>Khách hàng</option>
                                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Quản trị viên</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Cập nhật người dùng</button>
                                <a href="?controller=admin&action=users" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validation
        document.getElementById('edit-user-form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (username.length < 3) {
                e.preventDefault();
                alert('Tên đăng nhập phải có ít nhất 3 ký tự!');
            }
            if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                e.preventDefault();
                alert('Email không hợp lệ!');
            }
            if (password && password.length < 6) {
                e.preventDefault();
                alert('Mật khẩu phải có ít nhất 6 ký tự!');
            }
        });
    </script>
</body>
</html>