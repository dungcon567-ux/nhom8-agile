<?php
// views/admin/products/edit.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Sửa sản phẩm</title>
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
            display: <?php echo $product['image'] ? 'block' : 'none'; ?>;
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
                <h2 class="mb-4">Sửa sản phẩm #<?php echo htmlspecialchars($product['id']); ?></h2>
                <div class="card shadow">
                    <div class="card-body">
                        <!-- Thông báo trạng thái -->
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_GET['error'] == 'invalid_data' ? 'Dữ liệu không hợp lệ!' : 'Cập nhật sản phẩm thất bại!'; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="?controller=admin&action=update_product" enctype="multipart/form-data" id="edit-product-form">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm:</label>
                                <input type="text" name="name" class="form-control" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required minlength="3">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả:</label>
                                <textarea name="description" class="form-control" id="description" rows="5"><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục:</label>
                                <select name="category_id" class="form-select" id="category_id">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái:</label>
                                <select name="status" class="form-select" id="status">
                                    <option value="active" <?php echo $product['status'] == 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                                    <option value="inactive" <?php echo $product['status'] == 'inactive' ? 'selected' : ''; ?>>Ngừng</option>
                                </select>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_featured" value="1" id="is_featured" class="form-check-input" <?php echo $product['is_featured'] ? 'checked' : ''; ?>>
                                <label for="is_featured" class="form-check-label">Nổi bật</label>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh (nếu thay đổi):</label>
                                <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                <img id="image-preview" src="public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="Ảnh hiện tại" class="mt-2">
                            </div>

                            <!-- Phần edit variants -->
                            <h4 class="mb-3">Biến thể hiện có</h4>
                            <div id="variants-container">
                                <?php if (isset($variants) && is_array($variants) && !empty($variants)): ?>
                                    <?php $index = 0; ?>
                                    <?php foreach ($variants as $variant): ?>
                                        <div class="variant-group mb-3 border p-3 rounded">
                                            <input type="hidden" name="variants[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars($variant['id']); ?>">
                                            <div class="mb-3">
                                                <label for="sku" class="form-label">SKU:</label>
                                                <input type="text" name="variants[<?php echo $index; ?>][sku]" class="form-control" value="<?php echo htmlspecialchars($variant['sku']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="color" class="form-label">Màu sắc:</label>
                                                <input type="text" name="variants[<?php echo $index; ?>][color]" class="form-control" value="<?php echo htmlspecialchars($variant['color']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="size" class="form-label">Kích thước:</label>
                                                <input type="text" name="variants[<?php echo $index; ?>][size]" class="form-control" value="<?php echo htmlspecialchars($variant['size']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Giá:</label>
                                                <input type="number" name="variants[<?php echo $index; ?>][price]" class="form-control" value="<?php echo htmlspecialchars($variant['price']); ?>" min="0" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Số lượng:</label>
                                                <input type="number" name="variants[<?php echo $index; ?>][quantity]" class="form-control" value="<?php echo htmlspecialchars($variant['quantity']); ?>" min="0" required>
                                            </div>
                                        </div>
                                        <?php $index++; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-secondary mb-3" id="add-variant">Thêm biến thể mới</button>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
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
        let variantIndex = <?php echo count($variants ?? []); ?>;
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
        document.getElementById('edit-product-form').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value;
            if (name.length < 3) {
                e.preventDefault();
                alert('Tên sản phẩm phải có ít nhất 3 ký tự!');
            }
        });
    </script>
</body>
</html>