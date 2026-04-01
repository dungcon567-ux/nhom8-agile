<?php
// views/reviews/index.php
?>
<h2>Đánh giá sản phẩm</h2>
<div class="reviews">
    <?php foreach ($reviews as $review): ?>
        <div class="review">
            <p>Đánh giá: <?php echo $review['rating']; ?>/5</p>
            <p>Bình luận: <?php echo $review['comment']; ?></p>
            <p>Người dùng: <?php echo $review['username']; ?> - <?php echo $review['created_at']; ?></p>
        </div>
    <?php endforeach; ?>
</div>
<h3>Thêm đánh giá</h3>
<form action="?controller=review&action=add" method="POST">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <label>Đánh giá (1-5):</label>
    <input type="number" name="rating" min="1" max="5" required>
    <label>Bình luận:</label>
    <textarea name="comment"></textarea>
    <button type="submit">Gửi</button>
</form>