<?php
class OrderDetail
{
    private $conn;
    private $table = 'order_details';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $query = "INSERT INTO $this->table (order_id, variant_id, quantity, price) 
                  VALUES (:order_id, :variant_id, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
    }

    public function getOrderDetailsByOrderId($order_id) {
        $query = "SELECT od.*, p.name, pv.size, pv.color, pv.price, p.image 
                  FROM $this->table od 
                  LEFT JOIN product_variants pv ON od.variant_id = pv.id
                  LEFT JOIN products p ON pv.product_id = p.id 
                  WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getOrderById($id)
    {
        $query = "SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if (!$stmt->execute()) {
            error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
            return false;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>