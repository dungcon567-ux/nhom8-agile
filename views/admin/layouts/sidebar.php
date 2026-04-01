<nav class="col-md-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $controller == 'admin' && $action == 'settings' ? 'active' : ''; ?>" href="?controller=admin&action=dashboard">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $controller == 'admin' && $action == 'products' ? 'active' : ''; ?>" href="?controller=admin&action=products">Quản lý sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $controller == 'admin' && $action == 'categories' ? 'active' : ''; ?>" href="?controller=admin&action=categories">Quản lý danh mục</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $controller == 'admin' && $action == 'orders' ? 'active' : ''; ?>" href="?controller=admin&action=orders">Quản lý đơn hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $controller == 'admin' && $action == 'users' ? 'active' : ''; ?>" href="?controller=admin&action=users">Quản lý người dùng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $controller == 'admin' && $action == 'posts' ? 'active' : ''; ?>" href="?controller=admin&action=posts">Quản lý bài viết</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $controller == 'admin' && $action == 'reviews' ? 'active' : ''; ?>" href="?controller=admin&action=reviews">Quản lý đánh giá</a>
                        </li>
                        <!-- Back to Home Button -->
                        <li class="nav-item">
                            <a class="nav-link" href="?controller=home&action=index">Trở về trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?controller=user&action=logout">Đăng xuất</a>
                        </li>
                        <?php include_once 'views/layouts/head.php'; ?>
                    </ul>
                </div>
</nav>