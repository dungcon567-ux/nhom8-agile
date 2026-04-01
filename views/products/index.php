<?php
// views/products/index.php
require_once 'views/layouts/head.php';
?>

<link rel="stylesheet" href="public/css/products.css">

<div class="products-container">
    <div class="products-header">
        <h1>Sản Phẩm Nike</h1>
        <p>Khám phá bộ sưu tập giày thể thao cao cấp</p>
    </div>

    <div class="products-layout">
        <!-- Bộ lọc sản phẩm - Bên trái -->
        <div class="filters-sidebar">
            <div class="filters-section">
                <h3>Bộ Lọc Sản Phẩm</h3>

                <form method="GET" action="?controller=product&action=index">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="index">

                    <!-- Danh mục -->
                    <div class="filter-group">
                        <label for="category_id">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Size -->
                    <div class="filter-group">
                        <label for="size">Size</label>
                        <select name="size" id="size" class="form-select">
                            <option value="">Tất cả size</option>
                            <?php if (isset($available_sizes)): ?>
                                <?php foreach ($available_sizes as $size): ?>
                                    <option value="<?php echo $size; ?>"
                                        <?php echo (isset($_GET['size']) && $_GET['size'] == $size) ? 'selected' : ''; ?>>
                                        <?php echo $size; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Màu sắc -->
                    <div class="filter-group">
                        <label for="color">Màu sắc</label>
                        <select name="color" id="color" class="form-select">
                            <option value="">Tất cả màu</option>
                            <?php if (isset($available_colors)): ?>
                                <?php foreach ($available_colors as $color): ?>
                                    <option value="<?php echo $color; ?>"
                                        <?php echo (isset($_GET['color']) && $_GET['color'] == $color) ? 'selected' : ''; ?>>
                                        <?php echo $color; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Khoảng giá -->
                    <div class="filter-group">
                        <label for="min_price">Giá từ</label>
                        <input type="number" name="min_price" id="min_price" class="form-control"
                            placeholder="0" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                    </div>

                    <div class="filter-group">
                        <label for="max_price">Giá đến</label>
                        <input type="number" name="max_price" id="max_price" class="form-control"
                            placeholder="10000000" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                    </div>

                    <!-- Tìm kiếm -->
                    <div class="filter-group">
                        <label for="search">Tìm kiếm</label>
                        <div class="search-box">
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Nhập tên sản phẩm..."
                                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            <button type="submit">
                                <i class="fas fa-search"></i> 🔍
                            </button>
                        </div>
                    </div>

                    <!-- Sắp xếp -->
                    <div class="filter-group">
                        <label for="sort">Sắp xếp</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>Tên A-Z</option>
                            <option value="name_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : ''; ?>>Tên Z-A</option>
                            <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Giá tăng dần</option>
                            <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Giá giảm dần</option>
                        </select>
                    </div>

                    <!-- Nút áp dụng -->
                    <div class="filter-group">
                        <button type="submit" class="apply-filters">
                            <i class="fas fa-filter"></i> Áp dụng bộ lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Nội dung sản phẩm - Bên phải -->
        <div class="products-content">
            <!-- Hiển thị kết quả tìm kiếm -->
            <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                <div class="alert alert-info">
                    <strong>Kết quả tìm kiếm:</strong> "<?php echo htmlspecialchars($_GET['search']); ?>"
                    <span class="badge bg-primary ms-2"><?php echo $total_products; ?> sản phẩm</span>
                </div>
            <?php endif; ?>

            <!-- Danh sách sản phẩm -->
            <?php if (empty($products)): ?>
                <div class="products-empty">
                    <h3>Không tìm thấy sản phẩm</h3>
                    <p>Không có sản phẩm nào phù hợp với bộ lọc của bạn.</p>
                    <a href="?controller=product&action=index" class="btn-refresh">Làm mới bộ lọc</a>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($product['image']): ?>
                                    <img src="public/images/<?php echo htmlspecialchars($product['image']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>

                                <?php if ($product['is_featured']): ?>
                                    <div class="product-badge">Nổi bật</div>
                                <?php endif; ?>
                            </div>

                            <div class="product-info">
                                <div class="product-category">
                                    <?php echo htmlspecialchars($product['category_name'] ?? 'Không phân loại'); ?>
                                </div>

                                <h3 class="product-name">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h3>

                                <p class="product-description">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </p>

                                <!-- Hiển thị biến thể sản phẩm -->
                                <div class="product-variants">
                                    <?php
                                    $sizes = explode(',', $product['sizes'] ?? '');
                                    $colors = explode(',', $product['colors'] ?? '');
                                    $min_price = $product['min_price'] ?? 0;
                                    $max_price = $product['max_price'] ?? 0;
                                    ?>

                                    <?php if (!empty($sizes) && $sizes[0] !== ''): ?>
                                        <div class="variant-item">
                                            <div class="variant-details">
                                                <span class="variant-size">Size: <?php echo implode(', ', array_unique($sizes)); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($colors) && $colors[0] !== ''): ?>
                                        <div class="variant-item">
                                            <div class="variant-details">
                                                <span class="variant-color" style="background-color: <?php echo strtolower($colors[0]); ?>"></span>
                                                <span>Màu: <?php echo implode(', ', array_unique($colors)); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="variant-item">
                                        <div class="variant-details">
                                            <span>Giá từ:</span>
                                        </div>
                                        <span class="variant-price">
                                            <?php echo number_format($min_price); ?> VNĐ
                                        </span>
                                    </div>
                                </div>

                                <div class="product-actions">
                                    <a href="?controller=product&action=detail&id=<?php echo $product['id']; ?>"
                                        class="btn-view">Xem chi tiết</a>
                                    <button class="btn-add-cart" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Phân trang -->
                <?php if (isset($total_pages) && $total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?controller=product&action=index&page=<?php echo $page - 1; ?>&<?php echo http_build_query(array_diff_key($_GET, ['page' => ''])); ?>">
                                &laquo; Trước
                            </a>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?controller=product&action=index&page=<?php echo $i; ?>&<?php echo http_build_query(array_diff_key($_GET, ['page' => ''])); ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?controller=product&action=index&page=<?php echo $page + 1; ?>&<?php echo http_build_query(array_diff_key($_GET, ['page' => ''])); ?>">
                                Sau &raquo;
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function addToCart(productId) {
        // Chuyển hướng đến trang chi tiết sản phẩm để chọn variant
        window.location.href = '?controller=product&action=detail&id=' + productId;
    }

    // Auto-submit form khi thay đổi select
    document.addEventListener('DOMContentLoaded', function() {
        const autoSubmitSelects = ['category_id', 'size', 'color', 'sort'];

        autoSubmitSelects.forEach(function(selectName) {
            const select = document.getElementById(selectName);
            if (select) {
                select.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    });
</script>

<?php require_once 'views/layouts/footer.php'; ?>