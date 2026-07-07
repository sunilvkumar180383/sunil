<?php
/**
 * Membership Tier Class
 * Handles membership tiers and subscription management
 */

class Membership_Tier {
    
    private $db;
    private $tiers_table = 'emb_tiers';
    private $memberships_table = 'emb_memberships';
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Get all membership tiers
     */
    public function get_all_tiers() {
        $query = "SELECT * FROM " . $this->tiers_table . " WHERE status = 'active' ORDER BY price ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get tier by ID
     */
    public function get_tier($tier_id) {
        $query = "SELECT * FROM " . $this->tiers_table . " WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$tier_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Upgrade user membership
     */
    public function upgrade_membership($user_id, $new_tier_id, $billing_period = 'monthly') {
        // Check if user has active membership
        $current = $this->get_user_current_membership($user_id);
        
        // End current membership
        if ($current) {
            $query = "UPDATE " . $this->memberships_table . " 
                      SET status = 'cancelled' 
                      WHERE user_id = ? AND status = 'active'";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$user_id]);
        }
        
        // Create new membership
        $tier = $this->get_tier($new_tier_id);
        
        if ($billing_period === 'monthly') {
            $end_date = date('Y-m-d H:i:s', strtotime('+30 days'));
        } else {
            $end_date = date('Y-m-d H:i:s', strtotime('+365 days'));
        }
        
        $query = "INSERT INTO " . $this->memberships_table . " 
                  (user_id, tier_id, status, billing_period, start_date, end_date, amount_paid)
                  VALUES (?, ?, 'active', ?, NOW(), ?, ?)";
        
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([$user_id, $new_tier_id, $billing_period, $end_date, $tier['price']]);
    }
    
    /**
     * Get user current membership
     */
    public function get_user_current_membership($user_id) {
        $query = "SELECT m.*, t.* FROM " . $this->memberships_table . " m
                  JOIN " . $this->tiers_table . " t ON m.tier_id = t.id
                  WHERE m.user_id = ? AND m.status = 'active'
                  ORDER BY m.created_at DESC LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>