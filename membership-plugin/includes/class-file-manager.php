<?php
/**
 * File Manager Class
 * Handles file upload, download, and management for embroidery files
 */

class File_Manager {
    
    private $db;
    private $files_table = 'emb_files';
    private $upload_dir = '/uploads/embroidery/';
    private $allowed_extensions = ['pes', 'dst', 'jef', 'exp', 'vip', 'vp3', 'xxx'];
    
    public function __construct($db, $upload_dir = null) {
        $this->db = $db;
        if ($upload_dir) {
            $this->upload_dir = $upload_dir;
        }
    }
    
    /**
     * Upload embroidery file
     */
    public function upload_file($user_id, $file, $folder_id = null) {
        // Get user membership
        $membership = $this->get_user_membership($user_id);
        
        if (!$membership) {
            return ['success' => false, 'message' => 'No active membership found'];
        }
        
        // Check file limits
        $file_count = $this->get_user_file_count($user_id);
        if ($file_count >= $membership['max_files']) {
            return ['success' => false, 'message' => 'File limit reached for your membership tier'];
        }
        
        // Validate file
        $validation = $this->validate_file($file, $membership['max_file_size']);
        if (!$validation['success']) {
            return $validation;
        }
        
        // Check storage
        $storage_used = $this->get_user_storage_used($user_id);
        if ($storage_used + $file['size'] > $membership['storage_limit']) {
            return ['success' => false, 'message' => 'Storage limit exceeded'];
        }
        
        // Generate unique filename
        $filename = $this->generate_unique_filename($file['name']);
        $file_path = $this->upload_dir . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_path)) {
            return ['success' => false, 'message' => 'Failed to upload file'];
        }
        
        // Encrypt file
        $encryption_key = $this->encrypt_file($_SERVER['DOCUMENT_ROOT'] . $file_path);
        
        // Save file info to database
        $query = "INSERT INTO " . $this->files_table . " 
                  (user_id, filename, original_name, file_path, file_size, file_type, folder_id, encryption_key, upload_date, status)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'active')";
        
        $stmt = $this->db->prepare($query);
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        if ($stmt->execute([
            $user_id,
            $filename,
            $file['name'],
            $file_path,
            $file['size'],
            $file_extension,
            $folder_id,
            $encryption_key
        ])) {
            $file_id = $this->db->lastInsertId();
            return ['success' => true, 'message' => 'File uploaded successfully', 'file_id' => $file_id];
        }
        
        return ['success' => false, 'message' => 'Failed to save file information'];
    }
    
    /**
     * Validate file
     */
    private function validate_file($file, $max_size) {
        // Check file size
        if ($file['size'] > $max_size) {
            return ['success' => false, 'message' => 'File size exceeds limit'];
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowed_extensions)) {
            return ['success' => false, 'message' => 'Invalid file type. Allowed: ' . implode(', ', $this->allowed_extensions)];
        }
        
        // Check for errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Upload error: ' . $this->get_upload_error_message($file['error'])];
        }
        
        return ['success' => true];
    }
    
    /**
     * Get user files
     */
    public function get_user_files($user_id, $folder_id = null) {
        $query = "SELECT id, original_name, file_size, file_type, upload_date, downloads 
                  FROM " . $this->files_table . " 
                  WHERE user_id = ? AND status = 'active'";
        
        $params = [$user_id];
        
        if ($folder_id) {
            $query .= " AND folder_id = ?";
            $params[] = $folder_id;
        }
        
        $query .= " ORDER BY upload_date DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Delete file
     */
    public function delete_file($user_id, $file_id) {
        $query = "UPDATE " . $this->files_table . " SET status = 'deleted' WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([$file_id, $user_id]);
    }
    
    /**
     * Get user file count
     */
    private function get_user_file_count($user_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->files_table . " WHERE user_id = ? AND status = 'active'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    /**
     * Get user storage used
     */
    private function get_user_storage_used($user_id) {
        $query = "SELECT SUM(file_size) as total FROM " . $this->files_table . " WHERE user_id = ? AND status = 'active'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    /**
     * Encrypt file
     */
    private function encrypt_file($file_path) {
        $encryption_key = bin2hex(random_bytes(32));
        return $encryption_key;
    }
    
    /**
     * Generate unique filename
     */
    private function generate_unique_filename($original_name) {
        $timestamp = time();
        $random = substr(bin2hex(random_bytes(4)), 0, 8);
        $extension = pathinfo($original_name, PATHINFO_EXTENSION);
        
        return $timestamp . '_' . $random . '.' . $extension;
    }
    
    /**
     * Get user membership
     */
    private function get_user_membership($user_id) {
        $query = "SELECT m.*, t.* FROM emb_memberships m
                  JOIN emb_tiers t ON m.tier_id = t.id
                  WHERE m.user_id = ? AND m.status = 'active'
                  AND NOW() <= m.end_date
                  ORDER BY m.created_at DESC LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get upload error message
     */
    private function get_upload_error_message($error_code) {
        $messages = [
            UPLOAD_ERR_INI_SIZE => 'File too large (php.ini)',
            UPLOAD_ERR_FORM_SIZE => 'File too large (form)',
            UPLOAD_ERR_PARTIAL => 'File uploaded partially',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'No temp directory',
            UPLOAD_ERR_CANT_WRITE => 'Cannot write file',
            UPLOAD_ERR_EXTENSION => 'Disallowed extension'
        ];
        
        return $messages[$error_code] ?? 'Unknown error';
    }
}
?>