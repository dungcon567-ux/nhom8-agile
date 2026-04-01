<?php
// views/user/register.php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chúc Store</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-light">
    <?php include_once 'views/layouts/head.php'; ?>
    <div class="banner">
        <img src="public/images/banner.jpg" alt="Banner" class="img-fluid w-100">
    </div>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Đăng ký</h3>
                        <?php if (isset($error)): ?>
                            <p class="text-danger text-center"><?php echo htmlspecialchars($error); ?></p>
                        <?php endif; ?>
                        <form action="?controller=user&action=register" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">👁️</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Nhập lại mật khẩu" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirmPassword')">👁️</button>
                                </div>
                            </div>
                            <script>
                                function togglePassword(id) {
                                    const input = document.getElementById(id);
                                    input.type = input.type === 'password' ? 'text' : 'password';
                                }
                            </script>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Đăng ký</button>
                            </div>
                            <div class="text-center mt-3">
                                <p>Đã có tài khoản? <a href="?controller=user&action=login">Đăng nhập</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php
include_once 'views/layouts/footer.php';
?>