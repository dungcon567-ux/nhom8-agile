<!-- views/admin/products/index.php -->

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Quản lý sản phẩm</title>
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
        .table img {
            max-width: 50px;
            height: auto;
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
                <h2 class="mb-4">Quản lý sản phẩm</h2>

                <!-- Thông báo trạng thái -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_GET['success'] == 'added' ? 'Thêm sản phẩm thành công!' : ($_GET['success'] == 'updated' ? 'Cập nhật sản phẩm thành công!' : 'Xóa sản phẩm thành công!'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_GET['error'] == 'invalid_data' ? 'Dữ liệu không hợp lệ!' : 'Thêm sản phẩm thất bại!'; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Nút thêm sản phẩm -->
                <a href="?controller=admin&action=add_product" class="btn btn-primary mb-3">Thêm sản phẩm mới</a>

                <!-- Bảng sản phẩm -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Mô tả</th>
                            <th>Hình ảnh</th>
                            <th>Danh mục</th>
                            <th>Giá (min)</th>
                            <th>Số lượng (tổng)</th>
                            <th>Trạng thái</th>
                            <th>Nổi bật</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($products) && is_array($products) && !empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></td>
                                    <td><img src="public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>"></td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'Không có'); ?></td>
                                    <td><?php echo number_format($product['price'] ?? 0, 0, ',', '.') . ' VNĐ'; ?></td>
                                    <td><?php echo htmlspecialchars($product['quantity'] ?? 0); ?></td>
                                    <td><?php echo $product['status'] == 'active' ? 'Hoạt động' : 'Ngừng'; ?></td>
                                    <td><?php echo $product['is_featured'] ? 'Có' : 'Không'; ?></td>
                                    <td>
                                        <a href="?controller=admin&action=edit_product&id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                        <a href="?controller=admin&action=delete_product&id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Không có sản phẩm nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Phân trang -->
                <?php if (isset($total_products) && $total_products > 0): ?>
                    <?php
                    $total_pages = ceil($total_products / $items_per_page);
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=admin&action=products&page=<?php echo $current_page - 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Trước</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $current_page == $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?controller=admin&action=products&page=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?controller=admin&action=products&page=<?php echo $current_page + 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Sau</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>