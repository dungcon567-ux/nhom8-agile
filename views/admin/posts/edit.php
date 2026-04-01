<?php
// views/admin/posts/edit.php
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Sửa bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sửa bài viết</h2>
        <div class="card shadow">
            <div class="card-body">
                <form action="?controller=admin&action=edit_post&id=<?php echo $post['id']; ?>" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $post['title']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Nội dung:</label>
                        <textarea class="form-control" id="content" name="content" rows="5" required><?php echo $post['content']; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>