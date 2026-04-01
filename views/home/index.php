<?php
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chúc Store</title>
    <link rel="stylesheet" href="public/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    include_once 'views/layouts/head.php';
    ?>
    <div class="banner">
        <img src="public/images/banner.jpg" alt="Banner">
    </div>

    <div class="container">

        <h2>Sản phẩm mới</h2>
        <div class="product-box">
            <?php foreach ($products as $product): ?>
                <div class="product" onclick="window.location.href='?controller=product&action=detail&id=<?php echo $product['id']; ?>'" style="cursor: pointer;">
                    <img src="public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo number_format($product['price']); ?> VNĐ</p>

                </div>
            <?php endforeach; ?>
        </div>

        <h2>Sản phẩm bán chạy</h2>
        <div class="product-box">
            <?php foreach ($products as $product): ?>
                <div class="product" onclick="window.location.href='?controller=product&action=detail&id=<?php echo $product['id']; ?>'" style="cursor: pointer;">
                    <img src="public/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo number_format($product['price']); ?> VNĐ</p>

                </div>
            <?php endforeach; ?>
        </div>


        <?php if (!empty($posts)): ?>
            <h2>Bài viết mới</h2>
            <div class="row">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-4">
                        <h4><?php echo htmlspecialchars($post['title']); ?></h4>
                        <p><?php echo htmlspecialchars(substr($post['content'], 0, 100)) . '...'; ?></p>
                        <a href="?controller=post&action=detail&id=<?php echo $post['id']; ?>" class="btn btn-outline-primary">Xem thêm</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

    <?php
    include_once 'views/layouts/footer.php';
    ?>
</body>