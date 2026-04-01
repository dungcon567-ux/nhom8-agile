<?php
class User
{
    private $conn;
    private $table = 'users';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM $this->table WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        try {
            $query = "INSERT INTO $this->table (username, password, email, full_name, role, status) 
                     VALUES (:username, :password, :email, :full_name, :role, :status)";
            $stmt = $this->conn->prepare($query);

            $params = [
                ':username' => $data['username'],
                ':password' => $data['password'],
                ':email' => $data['email'],
                ':full_name' => $data['full_name'] ?? $data['username'],
                ':role' => $data['role'] ?? 'user',
                ':status' => 'active'
            ];

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Create user error: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $query = "UPDATE $this->table SET username = :username, email = :email, role = :role";
            if (isset($data['password'])) {
                $query .= ", password = :password";
            }
            $query .= " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $data['id'] = $id;
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Update user error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {

            $query = "DELETE od FROM order_details od 
                     INNER JOIN orders o ON od.order_id = o.id 
                     WHERE o.user_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['id' => $id]);

            $this->conn->prepare("DELETE FROM orders WHERE user_id = :id")->execute(['id' => $id]);

            $this->conn->prepare("DELETE FROM reviews WHERE user_id = :id")->execute(['id' => $id]);

            $this->conn->prepare("DELETE FROM posts WHERE user_id = :id")->execute(['id' => $id]);

            $query = "DELETE FROM $this->table WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            return false;
        }
    }

    public function updateLastLogin($id, $time)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE $this->table SET last_login = :time WHERE id = :id");
            $stmt->bindParam(':time', $time);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Update last login error: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalUsers()
    {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
