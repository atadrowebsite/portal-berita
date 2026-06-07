<?php
/**
 * Article Model
 */

class Article {
    private $db;
    private $table = 'articles';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function get_all($limit = 10, $offset = 0) {
        $query = 'SELECT a.*, c.name as category_name, u.name as author_name FROM ' . $this->table . ' a 
                  LEFT JOIN categories c ON a.category_id = c.id 
                  LEFT JOIN users u ON a.user_id = u.id 
                  WHERE a.status = "published" 
                  ORDER BY a.created_at DESC 
                  LIMIT ?, ?';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_by_id($id) {
        $query = 'SELECT a.*, c.name as category_name, u.name as author_name FROM ' . $this->table . ' a 
                  LEFT JOIN categories c ON a.category_id = c.id 
                  LEFT JOIN users u ON a.user_id = u.id 
                  WHERE a.id = ?';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function get_by_category($category_id, $limit = 10, $offset = 0) {
        $query = 'SELECT a.*, c.name as category_name, u.name as author_name FROM ' . $this->table . ' a 
                  LEFT JOIN categories c ON a.category_id = c.id 
                  LEFT JOIN users u ON a.user_id = u.id 
                  WHERE a.category_id = ? AND a.status = "published" 
                  ORDER BY a.created_at DESC 
                  LIMIT ?, ?';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $category_id, $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function search($keyword, $limit = 10, $offset = 0) {
        $keyword = '%' . $keyword . '%';
        $query = 'SELECT a.*, c.name as category_name, u.name as author_name FROM ' . $this->table . ' a 
                  LEFT JOIN categories c ON a.category_id = c.id 
                  LEFT JOIN users u ON a.user_id = u.id 
                  WHERE (a.title LIKE ? OR a.content LIKE ?) AND a.status = "published" 
                  ORDER BY a.created_at DESC 
                  LIMIT ?, ?';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssii', $keyword, $keyword, $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_admin_articles($limit = 10, $offset = 0) {
        $query = 'SELECT a.*, c.name as category_name, u.name as author_name FROM ' . $this->table . ' a 
                  LEFT JOIN categories c ON a.category_id = c.id 
                  LEFT JOIN users u ON a.user_id = u.id 
                  ORDER BY a.created_at DESC 
                  LIMIT ?, ?';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $offset, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $slug = generate_slug($data['title']);
        $query = 'INSERT INTO ' . $this->table . ' (title, slug, content, category_id, user_id, image, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssiiiss', $data['title'], $slug, $data['content'], $data['category_id'], 
                         $data['user_id'], $data['image'], $data['status']);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id, $data) {
        $query = 'UPDATE ' . $this->table . ' SET title = ?, content = ?, category_id = ?, status = ?';
        $params = [$data['title'], $data['content'], $data['category_id'], $data['status']];
        $types = 'ssii';
        
        if (isset($data['image']) && !empty($data['image'])) {
            $query .= ', image = ?';
            $params[] = $data['image'];
            $types .= 's';
        }
        
        $query .= ' WHERE id = ?';
        $params[] = $id;
        $types .= 'i';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...$params);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function get_total_articles() {
        $query = 'SELECT COUNT(*) as total FROM ' . $this->table . ' WHERE status = "published"';
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function get_latest_articles($limit = 5) {
        $query = 'SELECT a.*, c.name as category_name FROM ' . $this->table . ' a 
                  LEFT JOIN categories c ON a.category_id = c.id 
                  WHERE a.status = "published" 
                  ORDER BY a.created_at DESC 
                  LIMIT ?';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>