<!-- views/products/detail.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'public/css/style.css'; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .product-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }
        
        .product-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        
        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .stars {
            color: #ffd700;
            font-size: 1.2rem;
            margin-right: 10px;
        }
        
        .product-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .variant-section {
            margin-bottom: 25px;
        }
        
        .variant-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }
        
        .size-options, .color-options {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .size-option, .color-option {
            padding: 10px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .size-option:hover, .color-option:hover {
            border-color: #007bff;
        }
        
        .size-option.selected, .color-option.selected {
            border-color: #007bff;
            background: #007bff;
            color: white;
        }
        
        .color-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #333;
        }
        
        .quantity-section {
            margin-bottom: 25px;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .quantity-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #e1e5e9;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        
        .quantity-btn:hover {
            border-color: #007bff;
            background: #f8f9fa;
        }
        
        .quantity-input {
            width: 80px;
            height: 40px;
            text-align: center;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .btn-add-cart, .btn-buy-now {
            flex: 1;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-add-cart {
            background: #28a745;
            color: white;
        }
        
        .btn-add-cart:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        .btn-buy-now {
            background: #007bff;
            color: white;
        }
        
        .btn-buy-now:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        
        .product-details {
            margin-bottom: 25px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        
        .detail-value {
            color: #333;
        }
        
        .share-section {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .share-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #e1e5e9;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #666;
        }
        
        .share-btn:hover {
            border-color: #007bff;
            color: #007bff;
        }
        
        .tabs-section {
            margin: 40px 0;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #e1e5e9;
            margin-bottom: 20px;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 600;
            padding: 15px 25px;
            margin-right: 10px;
            border-radius: 8px 8px 0 0;
        }
        
        .nav-tabs .nav-link.active {
            color: #007bff;
            background: white;
            border-bottom: 3px solid #007bff;
        }
        
        .tab-content {
            padding: 20px 0;
        }
        
        .related-products {
            margin-top: 60px;
        }
        
        .related-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .related-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .related-image {
            height: 200px;
            overflow: hidden;
        }
        
        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .related-card:hover .related-image img {
            transform: scale(1.1);
        }
        
        .related-info {
            padding: 20px;
        }
        
        .related-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        
        .related-price {
            font-weight: 700;
            color: #e74c3c;
            font-size: 1.1rem;
        }
        
        .btn-view-more {
            text-align: center;
            margin-top: 30px;
        }
        
        .btn-view-more .btn {
            padding: 12px 30px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-view-more .btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .size-options, .color-options {
                flex-wrap: wrap;
            }
            
            .related-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
    </style>
</head>

<body>
    <?php include_once 'views/layouts/head.php'; ?>

    <div class="banner">
        <img src="<?php echo BASE_URL . 'public/images/banner.jpg'; ?>" alt="Banner" class="img-fluid">
    </div>

    <div class="product-detail-container">
        <?php if (!isset($product) || !$product): ?>
            <div class="alert alert-danger">Sản phẩm không tồn tại!</div>
            <a href="?controller=product&action=index" class="btn btn-outline-secondary">Quay lại</a>
        <?php else: ?>
            <div class="row">
                <!-- Hình ảnh sản phẩm -->
                <div class="col-lg-6">
                    <img src="<?php echo BASE_URL . 'public/images/' . htmlspecialchars($product['image'] ?? 'placeholder.jpg'); ?>"
                        class="img-fluid product-image"
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                
                <!-- Thông tin sản phẩm -->
                <div class="col-lg-6">
                    <div class="product-info">
                        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                        
                        <div class="product-price"><?php echo number_format($product['price'] ?? 0); ?> VNĐ</div>
                        
                        <div class="product-rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span>5 Customer Review</span>
                        </div>
                        
                        <p class="product-description">
                            <?php echo htmlspecialchars($product['description'] ?? 'Chưa có mô tả'); ?>
                        </p>

                        <form action="?controller=cart&action=add" method="POST" id="add-to-cart-form">
                            <input type="hidden" name="variant_id" id="selected_variant_id" value="">
                            
                            <!-- Chọn Size -->
                            <div class="variant-section">
                                <label class="variant-label">Size</label>
                                <div class="size-options">
                                    <?php 
                                    $unique_sizes = array_unique(array_column($variants, 'size'));
                                    foreach ($unique_sizes as $size): ?>
                                        <div class="size-option" data-size="<?php echo htmlspecialchars($size); ?>">
                                            <?php echo htmlspecialchars($size); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Chọn Màu -->
                            <div class="variant-section">
                                <label class="variant-label">Color</label>
                                <div class="color-options">
                                    <?php 
                                    $unique_colors = array_unique(array_column($variants, 'color'));
                                    foreach ($unique_colors as $color): ?>
                                        <div class="color-option" data-color="<?php echo htmlspecialchars($color); ?>" 
                                             style="background-color: <?php echo strtolower($color); ?>">
                                            <?php echo htmlspecialchars($color); ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Số lượng -->
                            <div class="quantity-section">
                                <label class="variant-label">Quantity</label>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                                    <input type="number" name="quantity" id="quantity" class="quantity-input" value="1" min="1" readonly>
                                    <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                                </div>
                            </div>
                            
                            <!-- Nút hành động -->
                            <div class="action-buttons">
                                <button type="submit" class="btn-add-cart" id="add_to_cart_btn" disabled>Thêm vào giỏ hàng</button>
                                <button type="button" class="btn-buy-now" id="buy_now_btn" disabled>Mua hàng</button>
                            </div>
                        </form>
                        
                        <!-- Thông tin chi tiết -->
                        <div class="product-details">
                            <div class="detail-item">
                                <span class="detail-label">SKU:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($product['id']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Category:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($product['category_name'] ?? 'Chưa xác định'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tags:</span>
                                <span class="detail-value">Nike, Sneakers, Sports</span>
                            </div>
                        </div>
                        
                        <!-- Chia sẻ -->
                        <div class="share-section">
                            <div class="share-btn">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                            <div class="share-btn">
                                <i class="fab fa-linkedin-in"></i>
                            </div>
                            <div class="share-btn">
                                <i class="fas fa-share-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabs thông tin -->
            <div class="tabs-section">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">Description</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="additional-tab" data-bs-toggle="tab" data-bs-target="#additional" type="button" role="tab">Additional Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews (5)</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <div class="product-description">
                            <p><strong>Thiết kế:</strong> <?php echo htmlspecialchars($product['name']); ?> có thiết kế đơn giản và thoải mái, phù hợp cho cả nam và nữ, kiểu dáng rộng rãi.</p>
                            <p><strong>Màu sắc:</strong> Màu <?php echo htmlspecialchars($product['color'] ?? 'đen'); ?> tạo nên vẻ ngoài dịu dàng và đáng yêu.</p>
                            <p><strong>Chất liệu:</strong> Được làm từ vải cotton chất lượng cao, mềm mại, thoáng khí và bền bỉ.</p>
                            <p><strong>Họa tiết:</strong> Họa tiết "<?php echo htmlspecialchars($product['name']); ?>" được in ở mặt trước, đơn giản và thời trang.</p>
                            <p><strong>Logo:</strong> Logo <?php echo htmlspecialchars($product['category_name'] ?? 'Nike'); ?> được thêu hoặc in nhỏ ở mặt sau hoặc ngực trái.</p>
                            <p><strong>Đa dạng:</strong> Kiểu dáng đơn giản và đáng yêu, có thể kết hợp với quần jean, quần short hoặc váy.</p>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="additional" role="tabpanel">
                        <div class="product-details">
                            <div class="detail-item">
                                <span class="detail-label">Material:</span>
                                <span class="detail-value">Premium Cotton</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Care Instructions:</span>
                                <span class="detail-value">Machine wash cold, tumble dry low</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Origin:</span>
                                <span class="detail-value">Vietnam</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="product-rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span>5.0 out of 5</span>
                        </div>
                        <p>Khách hàng rất hài lòng với chất lượng sản phẩm và dịch vụ của chúng tôi.</p>
                    </div>
                </div>
            </div>
            
            <!-- Sản phẩm liên quan -->
            <div class="related-products">
                <h2 class="related-title">Sản phẩm liên quan</h2>
                <div class="related-grid">
                    <?php
                    // Lấy sản phẩm liên quan (cùng danh mục)
                    if (isset($related_products) && !empty($related_products)):
                        foreach ($related_products as $related):
                    ?>
                        <div class="related-card">
                            <div class="related-image">
                                <img src="<?php echo BASE_URL . 'public/images/' . htmlspecialchars($related['image'] ?? 'placeholder.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($related['name']); ?>">
                            </div>
                            <div class="related-info">
                                <h3 class="related-name"><?php echo htmlspecialchars($related['name']); ?></h3>
                                <div class="related-price"><?php echo number_format($related['price'] ?? 0); ?> VNĐ</div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else:
                        // Hiển thị sản phẩm mẫu nếu không có sản phẩm liên quan
                    ?>
                        <div class="related-card">
                            <div class="related-image">
                                <img src="<?php echo BASE_URL . 'public/images/jordan 1.jpg'; ?>" alt="Sản phẩm mẫu">
                            </div>
                            <div class="related-info">
                                <h3 class="related-name">Nike Air Max 90</h3>
                                <div class="related-price">4,109,000 VNĐ</div>
                            </div>
                        </div>
                        <div class="related-card">
                            <div class="related-image">
                                <img src="<?php echo BASE_URL . 'public/images/react 3.jpg'; ?>" alt="Sản phẩm mẫu">
                            </div>
                            <div class="related-info">
                                <h3 class="related-name">Nike Dunk Low</h3>
                                <div class="related-price">3,519,000 VNĐ</div>
                            </div>
                        </div>
                        <div class="related-card">
                            <div class="related-image">
                                <img src="<?php echo BASE_URL . 'public/images/react 2.jpg'; ?>" alt="Sản phẩm mẫu">
                            </div>
                            <div class="related-info">
                                <h3 class="related-name">Nike Air Force 1</h3>
                                <div class="related-price">3,239,000 VNĐ</div>
                            </div>
                        </div>
                        <div class="related-card">
                            <div class="related-image">
                                <img src="<?php echo BASE_URL . 'public/images/react 1.jpg'; ?>" alt="Sản phẩm mẫu">
                            </div>
                            <div class="related-info">
                                <h3 class="related-name">Nike Blazer</h3>
                                <div class="related-price">2,929,000 VNĐ</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="btn-view-more">
                    <a href="?controller=product&action=index" class="btn">Xem thêm</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include_once 'views/layouts/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // Truyền variants từ PHP sang JS
        const variants = <?php echo json_encode($variants); ?>;

        document.addEventListener('DOMContentLoaded', function() {
            const sizeOptions = document.querySelectorAll('.size-option');
            const colorOptions = document.querySelectorAll('.color-option');
            const variantIdInput = document.getElementById('selected_variant_id');
            const addToCartBtn = document.getElementById('add_to_cart_btn');
            const buyNowBtn = document.getElementById('buy_now_btn');
            const form = document.getElementById('add-to-cart-form');

            let selectedSize = '';
            let selectedColor = '';

            // Xử lý chọn size
            sizeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    sizeOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedSize = this.dataset.size;
                    updateVariant();
                });
            });

            // Xử lý chọn màu
            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    colorOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedColor = this.dataset.color;
                    updateVariant();
                });
            });

            function updateVariant() {
                variantIdInput.value = '';

                if (selectedSize && selectedColor) {
                    // Tìm variant phù hợp
                    const matchingVariant = variants.find(v => v.size === selectedSize && v.color === selectedColor);
                    if (matchingVariant) {
                        variantIdInput.value = matchingVariant.id;
                        addToCartBtn.disabled = false;
                        buyNowBtn.disabled = false;
                    } else {
                        alert('Không có biến thể phù hợp với size và màu này!');
                        addToCartBtn.disabled = true;
                        buyNowBtn.disabled = true;
                    }
                } else {
                    addToCartBtn.disabled = true;
                    buyNowBtn.disabled = true;
                }
            }

            // Xử lý "Mua ngay": Submit form với redirect=checkout
            buyNowBtn.addEventListener('click', function() {
                form.action = '?controller=cart&action=add&redirect=checkout';
                form.submit();
            });
        });

        // Xử lý thay đổi số lượng
        function changeQuantity(delta) {
            const quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            let newValue = currentValue + delta;
            
            if (newValue >= 1) {
                quantityInput.value = newValue;
            }
        }
    </script>
</body>

</html>