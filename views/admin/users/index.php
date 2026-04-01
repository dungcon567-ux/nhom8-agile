<?php
// views/admin/users/index.php
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Quản lý người dùng</title>
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
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            echo $_GET['error'] == 'invalid_data' ? 'Dữ liệu không hợp lệ!' : ($_GET['error'] == 'cannot_delete_self' ? 'Không thể xóa chính mình!' : ($_GET['error'] == 'failed' ? 'Xóa thất bại! Có thể do ràng buộc dữ liệu (orders, reviews,...). Hãy xóa dữ liệu liên kết trước.' : 'Thao tác thất bại!'));
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include_once 'views/admin/layouts/sidebar.php'; ?>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto col-lg-10 main-content">
                <h2 class="mb-4">Quản lý người dùng</h2>

                <!-- Thông báo trạng thái -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php
                        echo $_GET['success'] == 'added' ? 'Thêm người dùng thành công!' : ($_GET['success'] == 'updated' ? 'Cập nhật người dùng thành công!' : 'Xóa người dùng thành công!');
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php
                        echo $_GET['error'] == 'invalid_data' ? 'Dữ liệu không hợp lệ!' : 'Thao tác thất bại!';
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Form tìm kiếm -->
                <form method="GET" action="?controller=admin&action=users" class="mb-4">
                    <div class="input-group">
                        <input type="hidden" name="controller" value="admin">
                        <input type="hidden" name="action" value="users">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên đăng nhập hoặc email..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </form>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Danh sách người dùng</h5>
                            <a href="?controller=admin&action=add_user" class="btn btn-primary">Thêm người dùng</a>
                        </div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Không có người dùng nào.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                                            <td>
                                                <a href="?controller=admin&action=edit_user&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm me-2">Sửa</a>
                                                <a href="?controller=admin&action=delete_user&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?');">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Phân trang -->
                        <!-- <?php if (!empty($users)): ?>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    $total_pages = ceil($total_users / $items_per_page); // Giả sử $total_users và $items_per_page được định nghĩa trong controller
                                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    ?>
                                    <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?controller=admin&action=users&page=<?php echo $current_page - 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Trước</a>
                                    </li>
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo $current_page == $i ? 'active' : ''; ?>">
                                            <a class="page-link" href="?controller=admin&action=users&page=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?controller=admin&action=users&page=<?php echo $current_page + 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Sau</a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?> -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>