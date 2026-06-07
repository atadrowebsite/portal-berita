<?php
/**
 * Comment Model
 */

class Comment {
    private $db;
    private $table = 'comments';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function get_by_article($article_id) {
        $query = 'SELECT c.*, u.name, u.email FROM ' . $this->table . ' c 
                  LEFT JOIN users u ON c.user_id = u.id 
                  WHERE c.article_id = ? AND c.status = "approved" 
                  ORDER BY c.created_at DESC';
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $article_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_all_admin() {
        $query = 'SELECT c.*, u.name as user_name, a.title as article_title FROM ' . $this->table . ' c 
                  LEFT JOIN users u ON c.user_id = u.id 
                  LEFT JOIN articles a ON c.article_id = a.id 
                  ORDER BY c.created_at DESC';
        
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($data) {
        $query = 'INSERT INTO ' . $this->table . ' (article_id, user_id, content, status) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($query);
        
        $status = 'pending';
        $stmt->bind_param('iiss', $data['article_id'], $data['user_id'], $data['content'], $status);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function approve($id) {
        $query = 'UPDATE ' . $this->table . ' SET status = "approved" WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function reject($id) {
        $query = 'UPDATE ' . $this->table . ' SET status = "rejected" WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
?>