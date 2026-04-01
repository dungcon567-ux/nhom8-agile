<?php
class Review {
    private $conn;
    private $table = 'reviews';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllReviews() {
        $query = "SELECT r.*, p.name as product_name, u.username FROM $this->table r 
                 LEFT JOIN products p ON r.product_id = p.id 
                 LEFT JOIN users u ON r.user_id = u.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewsByProductId($product_id) {
        $query = "SELECT r.*, u.username FROM $this->table r 
                 LEFT JOIN users u ON r.user_id = u.id WHERE r.product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO $this->table (product_id, user_id, rating, comment) 
                 VALUES (:product_id, :user_id, :rating, :comment)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function delete($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function getTotalReviews() {
    $query = "SELECT COUNT(*) as total FROM $this->table";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}
}
?>