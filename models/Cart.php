<?php
class Cart {
    private $conn;
    private $table = 'cart';
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($data) {
        $user_id = $data['user_id'];
        $variant_id = $data['variant_id'];
        $quantity = $data['quantity'];

        $existingItem = $this->getCartItem($user_id, $variant_id);
        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            return $this->update($existingItem['id'], $newQuantity);
        } else {
            $query = "INSERT INTO $this->table (user_id, variant_id, quantity) 
                     VALUES (:user_id, :variant_id, :quantity)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':variant_id', $variant_id);
            $stmt->bindParam(':quantity', $quantity);
            return $stmt->execute();
        }
    }

    public function getCartItems($user_id) {
        $query = "SELECT c.*, p.name, pv.price, p.image, pv.sku, pv.size, pv.color 
                 FROM $this->table c 
                 LEFT JOIN product_variants pv ON c.variant_id = pv.id 
                 LEFT JOIN products p ON pv.product_id = p.id 
                 WHERE c.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $quantity) {
        $query = "UPDATE $this->table SET quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function clearCart($user_id) {
        $query = "DELETE FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public function getCartItem($user_id, $variant_id) {
        $query = "SELECT * FROM $this->table WHERE user_id = :user_id AND variant_id = :variant_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':variant_id', $variant_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>