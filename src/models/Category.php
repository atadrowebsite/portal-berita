<?php
/**
 * Category Model
 */

class Category {
    private $db;
    private $table = 'categories';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function get_all() {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY name ASC';
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

    public function get_by_slug($slug) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE slug = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $slug = generate_slug($data['name']);
        $query = 'INSERT INTO ' . $this->table . ' (name, slug, description) VALUES (?, ?, ?)';
        $stmt = $this->db->prepare($query);
        
        $stmt->bind_param('sss', $data['name'], $slug, $data['description']);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id, $data) {
        $slug = generate_slug($data['name']);
        $query = 'UPDATE ' . $this->table . ' SET name = ?, slug = ?, description = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);
        
        $stmt->bind_param('sssi', $data['name'], $slug, $data['description'], $id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function get_category_count() {
        $query = 'SELECT COUNT(*) as total FROM ' . $this->table;
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}
?>