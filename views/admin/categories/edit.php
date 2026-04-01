<?php
// views/admin/categories/edit.php
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chúc Store - Sửa danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sửa danh mục</h2>
        <div class="card shadow">
            <div class="card-body">
                <form action="?controller=admin&action=edit_category&id=<?php echo $category['id']; ?>" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $category['name']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>