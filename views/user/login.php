<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chúc Store</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php
include_once 'views/layouts/head.php';
?>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Đăng nhập</h3>
                        <?php if (isset($error)): ?>
                            <p class="text-danger text-center"><?php echo htmlspecialchars($error); ?></p>
                        <?php endif; ?>
                        <form action="?controller=user&action=login" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tài khoản</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Nhập email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()" tabindex="-1">
                                        👁️
                                    </button>
                                </div>
                            </div>

                            <script>
                                function togglePassword() {
                                    const passwordInput = document.getElementById("password");
                                    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
                                }
                            </script>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Đăng nhập</button>
                            </div>
                            <div class="text-center mt-3">
                                <p>Chưa có tài khoản? <a href="?controller=user&action=register">Đăng ký</a></p>
                                <p><a href="#">Quên mật khẩu?</a></p>
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