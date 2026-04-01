<?php
class Order {
    private $conn;
    private $table = 'orders';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        try {
            // Tạo mã đơn hàng
            $order_code = $this->generateOrderCode();
            $data['order_code'] = $order_code;
            
            // Sử dụng execute với array thay vì bindParam
            $query = "INSERT INTO $this->table (order_code, user_id, full_name, address, email, phone, province, ward, order_notes, total, payment_method, status) 
                      VALUES (:order_code, :user_id, :full_name, :address, :email, :phone, :province, :ward, :order_notes, :total, :payment_method, :status)";
            
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute($data);
            
            if ($result) {
                return $this->conn->lastInsertId();
            } else {
                error_log("Failed to create order: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (Exception $e) {
            error_log("Error creating order: " . $e->getMessage());
            return false;
        }
    }

    private function generateOrderCode() {
        $prefix = 'CHUC';
        $timestamp = date('YmdHis');
        $random = mt_rand(1000, 9999);
        return $prefix . $timestamp . $random;
    }

    public function getAllOrders($limit = null, $offset = null) {
        $query = "SELECT o.*, u.username FROM $this->table o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
        
        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit !== null && $offset !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id) {
        $query = "SELECT o.*, u.username FROM $this->table o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderByCode($order_code) {
        $query = "SELECT o.*, u.username FROM $this->table o LEFT JOIN users u ON o.user_id = u.id WHERE o.order_code = :order_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_code', $order_code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        try {
            $query = "UPDATE orders SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating order: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE $this->table SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getTotalOrders() {
        $query = "SELECT COUNT(*) FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getRecentOrders($limit = 5) {
        $query = "SELECT o.id, u.username, o.total, o.status, o.created_at 
                  FROM $this->table o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  ORDER BY o.created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy đơn hàng theo user_id
    public function getOrdersByUserId($user_id, $limit = null, $offset = null) {
        $query = "SELECT o.*, 
                  GROUP_CONCAT(DISTINCT p.name) as product_names,
                  GROUP_CONCAT(DISTINCT pv.size) as sizes,
                  GROUP_CONCAT(DISTINCT pv.color) as colors
                  FROM $this->table o 
                  LEFT JOIN order_details od ON o.id = od.order_id
                  LEFT JOIN product_variants pv ON od.variant_id = pv.id
                  LEFT JOIN products p ON pv.product_id = p.id
                  WHERE o.user_id = :user_id 
                  GROUP BY o.id 
                  ORDER BY o.created_at DESC";
        
        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($limit !== null && $offset !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số đơn hàng của user
    public function getTotalOrdersByUserId($user_id) {
        $query = "SELECT COUNT(DISTINCT o.id) FROM $this->table o WHERE o.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Cập nhật đơn hàng với nhiều trường
    public function updateOrder($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    $fields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $query = "UPDATE $this->table SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating order: " . $e->getMessage());
            return false;
        }
    }
}
?>