<?php
class Product 
{
    private $conn;
    private $table = 'products';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getFeaturedProducts($limit = 12)
    {
        $query = "SELECT p.*, MIN(pv.price) AS price 
                  FROM $this->table p 
                  LEFT JOIN product_variants pv ON p.id = pv.product_id 
                  WHERE p.is_featured = 1 GROUP BY p.id LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProducts($category_id = null, $size = null, $color = null)
    {
        $query = "SELECT p.*, c.name as category_name, MIN(pv.price) AS price, GROUP_CONCAT(DISTINCT pv.size) AS sizes, 
                  GROUP_CONCAT(DISTINCT pv.color) AS colors, SUM(pv.quantity) AS quantity 
                  FROM $this->table p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  LEFT JOIN product_variants pv ON p.id = pv.product_id";
        $conditions = [];
        if ($category_id) {
            $conditions[] = "p.category_id = :category_id";
        }
        if ($size) {
            $conditions[] = "pv.size = :size";
        }
        if ($color) {
            $conditions[] = "pv.color = :color";
        }
        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $query .= " GROUP BY p.id";
        $stmt = $this->conn->prepare($query);
        if ($category_id) {
            $stmt->bindParam(':category_id', $category_id);
        }
        if ($size) {
            $stmt->bindParam(':size', $size);
        }
        if ($color) {
            $stmt->bindParam(':color', $color);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id)
    {
        $query = "SELECT p.*, MIN(pv.price) AS price, GROUP_CONCAT(DISTINCT pv.size) AS sizes, 
                  GROUP_CONCAT(DISTINCT pv.color) AS colors, SUM(pv.quantity) AS quantity 
                  FROM $this->table p 
                  LEFT JOIN product_variants pv ON p.id = pv.product_id 
                  WHERE p.id = :id GROUP BY p.id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVariantsByProductId($product_id)
    {
        $query = "SELECT * FROM product_variants WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVariantById($variant_id)
    {
        $query = "SELECT * FROM product_variants WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $variant_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO $this->table (name, description, image, category_id, status, is_featured) 
                  VALUES (:name, :description, :image, :category_id, :status, :is_featured)";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute($data)) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function createVariant($data)
    {
        $query = "INSERT INTO product_variants (product_id, sku, color, size, price, quantity) 
                  VALUES (:product_id, :sku, :color, :size, :price, :quantity)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE $this->table SET 
                  name = :name, 
                  description = :description, 
                  image = :image, 
                  category_id = :category_id, 
                  status = :status,
                  is_featured = :is_featured 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function updateVariantQuantity($variant_id, $quantity)
    {
        $query = "UPDATE product_variants SET quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':id', $variant_id);
        return $stmt->execute();
    }

    public function getTotalProducts()
    {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getFilteredProducts($filters, $limit = null, $offset = null)
    {
        $query = "SELECT DISTINCT p.*, c.name as category_name, 
                  MIN(pv.price) AS min_price, MAX(pv.price) AS max_price,
                  GROUP_CONCAT(DISTINCT pv.size) AS sizes, 
                  GROUP_CONCAT(DISTINCT pv.color) AS colors, 
                  SUM(pv.quantity) AS total_quantity 
                  FROM $this->table p 
                  LEFT JOIN categories c ON p.category_id = c.id 
                  LEFT JOIN product_variants pv ON p.id = pv.product_id 
                  WHERE p.status = 'active'";

        $params = [];

        if (!empty($filters['category_id'])) {
            $query .= " AND p.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        if (!empty($filters['size'])) {
            $query .= " AND pv.size = :size";
            $params[':size'] = $filters['size'];
        }

        if (!empty($filters['color'])) {
            $query .= " AND pv.color = :color";
            $params[':color'] = $filters['color'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['min_price'])) {
            $query .= " AND pv.price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $query .= " AND pv.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        $query .= " GROUP BY p.id";


        switch ($filters['sort']) {
            case 'price_asc':
                $query .= " ORDER BY min_price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY min_price DESC";
                break;
            case 'name_desc':
                $query .= " ORDER BY p.name DESC";
                break;
            default:
                $query .= " ORDER BY p.name ASC";
        }

        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            if (strpos($key, 'limit') !== false || strpos($key, 'offset') !== false) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalFilteredProducts($filters)
    {
        $query = "SELECT COUNT(DISTINCT p.id) as total 
                  FROM $this->table p 
                  LEFT JOIN product_variants pv ON p.id = pv.product_id 
                  WHERE p.status = 'active'";

        $params = [];

        if (!empty($filters['category_id'])) {
            $query .= " AND p.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        if (!empty($filters['size'])) {
            $query .= " AND pv.size = :size";
            $params[':size'] = $filters['size'];
        }

        if (!empty($filters['color'])) {
            $query .= " AND pv.color = :color";
            $params[':color'] = $filters['color'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['min_price'])) {
            $query .= " AND pv.price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $query .= " AND pv.price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getAvailableSizes()
    {
        $query = "SELECT DISTINCT size FROM product_variants WHERE size IS NOT NULL AND size != '' ORDER BY size";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAvailableColors()
    {
        $query = "SELECT DISTINCT color FROM product_variants WHERE color IS NOT NULL AND color != '' ORDER BY color";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
