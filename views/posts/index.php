<?php
// views/posts/index.php
?>

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
<?php
include_once 'views/layouts/head.php';
?>
<div class="banner">
        <img src="public/images/banner.jpg" alt="Banner">
</div>

<h2>Tin tức</h2>
<div class="posts">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h3><?php echo $post['title']; ?></h3>
            <p><?php echo $post['content']; ?></p>
            <p>Đăng bởi: <?php echo $post['username']; ?> vào <?php echo $post['created_at']; ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php
include_once 'views/home/footer.php';
?>