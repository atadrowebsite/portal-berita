<?php
/**
 * User Model
 */

class User {
    private $db;
    private $table = 'users';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function get_all() {
        $query = 'SELECT id, name, email, role, created_at FROM ' . $this->table . ' ORDER BY created_at DESC';
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_by_id($id) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_by_email($email) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $query = 'INSERT INTO ' . $this->table . ' (name, email, password, role) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($query);
        
        $stmt->bind_param('ssss', $data['name'], $data['email'], $data['password'], $data['role']);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id, $data) {
        $query = 'UPDATE ' . $this->table . ' SET name = ?, email = ?, role = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);
        
        $stmt->bind_param('sssi', $data['name'], $data['email'], $data['role'], $id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function get_user_count() {
        $query = 'SELECT COUNT(*) as total FROM ' . $this->table;
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>