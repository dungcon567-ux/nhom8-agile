<?php
// views/admin/products/add.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Thêm sản phẩm</title>
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
        #image-preview {
            max-width: 200px;
            height: auto;
            margin-top: 10px;
            display: none;
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
                <h2 class="mb-4">Thêm sản phẩm</h2>
                <div class="card shadow">
                    <div class="card-body">
                        <!-- Thông báo trạng thái -->
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_GET['error'] == 'invalid_data' ? 'Dữ liệu không hợp lệ!' : 'Thêm sản phẩm thất bại!'; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="?controller=admin&action=add_product" enctype="multipart/form-data" id="add-product-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm:</label>
                                <input type="text" name="name" class="form-control" id="name" required minlength="3">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả:</label>
                                <textarea name="description" class="form-control" id="description" rows="5"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục:</label>
                                <select name="category_id" class="form-select" id="category_id">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái:</label>
                                <select name="status" class="form-select" id="status">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Ngừng</option>
                                </select>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_featured" value="1" id="is_featured" class="form-check-input">
                                <label for="is_featured" class="form-check-label">Nổi bật</label>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh:</label>
                                <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                <img id="image-preview" src="#" alt="Ảnh xem trước" class="mt-2">
                            </div>

                            <!-- Phần thêm variants (multi) -->
                            <h4 class="mb-3">Thêm biến thể</h4>
                            <div id="variants-container">
                                <div class="variant-group mb-3 border p-3 rounded">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU:</label>
                                        <input type="text" name="variants[0][sku]" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Màu sắc:</label>
                                        <input type="text" name="variants[0][color]" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="size" class="form-label">Kích thước:</label>
                                        <input type="text" name="variants[0][size]" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Giá:</label>
                                        <input type="number" name="variants[0][price]" class="form-control" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Số lượng:</label>
                                        <input type="number" name="variants[0][quantity]" class="form-control" min="0" required>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" id="add-variant">Thêm biến thể</button>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                                <a href="?controller=admin&action=products" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Xem trước hình ảnh
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('image-preview');
            const file = e.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });

        // Thêm biến thể động
        let variantIndex = 1;
        document.getElementById('add-variant').addEventListener('click', function() {
            const container = document.getElementById('variants-container');
            const newVariant = document.createElement('div');
            newVariant.classList.add('variant-group', 'mb-3', 'border', 'p-3', 'rounded');
            newVariant.innerHTML = `
                <div class="mb-3">
                    <label for="sku" class="form-label">SKU:</label>
                    <input type="text" name="variants[${variantIndex}][sku]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Màu sắc:</label>
                    <input type="text" name="variants[${variantIndex}][color]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="size" class="form-label">Kích thước:</label>
                    <input type="text" name="variants[${variantIndex}][size]" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Giá:</label>
                    <input type="number" name="variants[${variantIndex}][price]" class="form-control" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Số lượng:</label>
                    <input type="number" name="variants[${variantIndex}][quantity]" class="form-control" min="0" required>
                </div>
            `;
            container.appendChild(newVariant);
            variantIndex++;
        });

        // Client-side validation
        document.getElementById('add-product-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            if (name.length < 3) {
                e.preventDefault();
                alert('Tên sản phẩm phải có ít nhất 3 ký tự!');
            }
        });
    </script>
</body>
</html>