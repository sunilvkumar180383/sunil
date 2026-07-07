<?php
/**
 * Membership User Class
 * Handles user registration, authentication, and membership management
 */

class Membership_User {
    
    private $db;
    private $table = 'emb_users';
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Register new user
     */
    public function register($data) {
        // Validate input
        if (!$this->validate_email($data['email'])) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        if (strlen($data['password']) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters'];
        }
        
        // Check if user exists
        if ($this->user_exists($data['email'])) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
        
        // Hash password
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
        
        // Generate verification token
        $verification_token = bin2hex(random_bytes(32));
        
        // Insert user
        $query = "INSERT INTO " . $this->table . " 
                  (email, password, first_name, last_name, verification_token, created_at, status)
                  VALUES (?, ?, ?, ?, ?, NOW(), 'pending')";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt->execute([$data['email'], $password_hash, $data['first_name'], $data['last_name'], $verification_token])) {
            $user_id = $this->db->lastInsertId();
            
            // Create free trial membership
            $this->create_free_trial($user_id);
            
            // Send verification email
            $this->send_verification_email($data['email'], $verification_token);
            
            return ['success' => true, 'message' => 'Registration successful. Please verify your email.', 'user_id' => $user_id];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }
    
    /**
     * Verify user email
     */
    public function verify_email($token) {
        $query = "UPDATE " . $this->table . " 
                  SET status = 'active', verification_token = NULL 
                  WHERE verification_token = ?";
        
        $stmt = $this->db->prepare($query);
        
        if ($stmt->execute([$token])) {
            return ['success' => true, 'message' => 'Email verified successfully'];
        }
        
        return ['success' => false, 'message' => 'Invalid verification token'];
    }
    
    /**
     * Login user
     */
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ? AND status = 'active'";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $this->update_last_login($user['id']);
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
            
            return ['success' => true, 'message' => 'Login successful', 'user_id' => $user['id']];
        }
        
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
    
    /**
     * Create free trial membership
     */
    private function create_free_trial($user_id) {
        $query = "INSERT INTO emb_memberships 
                  (user_id, tier_id, status, start_date, end_date)
                  VALUES (?, 1, 'active', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY))";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
    }
    
    /**
     * Check if user exists
     */
    private function user_exists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Validate email
     */
    private function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Update last login
     */
    private function update_last_login($user_id) {
        $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
    }
    
    /**
     * Send verification email
     */
    private function send_verification_email($email, $token) {
        $verification_link = "https://yoursite.com/verify.php?token=" . $token;
        
        $subject = "Verify Your Embroidery File Manager Account";
        $message = "Click the link below to verify your email:\n\n" . $verification_link;
        
        mail($email, $subject, $message);
    }
    
    /**
     * Get user by ID
     */
    public function get_user($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get current membership tier
     */
    public function get_current_membership($user_id) {
        $query = "SELECT m.*, t.tier_name, t.max_files, t.max_file_size, t.storage_limit
                  FROM emb_memberships m
                  JOIN emb_tiers t ON m.tier_id = t.id
                  WHERE m.user_id = ? AND m.status = 'active'
                  ORDER BY m.end_date DESC LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Logout user
     */
    public function logout() {
        session_destroy();
        return true;
    }
}
?>