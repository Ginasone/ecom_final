<?php
/**
 * Pickup Point Class
 * Handles database operations for pickup points across Ghana
 * K-Connect Ghana - Nationwide K-Pop Merchandise Delivery
 */

require_once("../settings/db_class.php");

class pickup_point_class extends db_connection
{
    /**
     * Get all active pickup points
     */
    public function get_all_pickup_points()
    {
        $sql = "SELECT * FROM pickup_points WHERE is_active = 1 ORDER BY region, city, name";
        return $this->db_fetch_all($sql);
    }
    
    /**
     * Get pickup points by region
     */
    public function get_pickup_points_by_region($region)
    {
        $region = mysqli_real_escape_string($this->db_conn(), $region);
        $sql = "SELECT * FROM pickup_points 
                WHERE region = '$region' AND is_active = 1 
                ORDER BY city, name";
        return $this->db_fetch_all($sql);
    }
    
    /**
     * Get pickup points by city
     */
    public function get_pickup_points_by_city($city)
    {
        $city = mysqli_real_escape_string($this->db_conn(), $city);
        $sql = "SELECT * FROM pickup_points 
                WHERE city = '$city' AND is_active = 1 
                ORDER BY name";
        return $this->db_fetch_all($sql);
    }
    
    /**
     * Get single pickup point by ID
     */
    public function get_pickup_point_by_id($point_id)
    {
        $point_id = (int)$point_id;
        $sql = "SELECT * FROM pickup_points WHERE point_id = $point_id";
        return $this->db_fetch_one($sql);
    }
    
    /**
     * Add new pickup point
     */
    public function add_pickup_point($name, $region, $city, $address, $contact_person = '', 
                                     $contact_phone = '', $operating_hours = 'Mon-Sat: 9AM-6PM', 
                                     $is_active = 1)
    {
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $region = mysqli_real_escape_string($this->db_conn(), $region);
        $city = mysqli_real_escape_string($this->db_conn(), $city);
        $address = mysqli_real_escape_string($this->db_conn(), $address);
        $contact_person = mysqli_real_escape_string($this->db_conn(), $contact_person);
        $contact_phone = mysqli_real_escape_string($this->db_conn(), $contact_phone);
        $operating_hours = mysqli_real_escape_string($this->db_conn(), $operating_hours);
        $is_active = (int)$is_active;
        
        $sql = "INSERT INTO pickup_points (name, region, city, address, contact_person, 
                contact_phone, operating_hours, is_active) 
                VALUES ('$name', '$region', '$city', '$address', '$contact_person', 
                '$contact_phone', '$operating_hours', $is_active)";
        
        return $this->db_query($sql);
    }
    
    /**
     * Update pickup point
     */
    public function update_pickup_point($point_id, $name, $region, $city, $address, 
                                       $contact_person, $contact_phone, $operating_hours, 
                                       $is_active)
    {
        $point_id = (int)$point_id;
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $region = mysqli_real_escape_string($this->db_conn(), $region);
        $city = mysqli_real_escape_string($this->db_conn(), $city);
        $address = mysqli_real_escape_string($this->db_conn(), $address);
        $contact_person = mysqli_real_escape_string($this->db_conn(), $contact_person);
        $contact_phone = mysqli_real_escape_string($this->db_conn(), $contact_phone);
        $operating_hours = mysqli_real_escape_string($this->db_conn(), $operating_hours);
        $is_active = (int)$is_active;
        
        $sql = "UPDATE pickup_points SET 
                name = '$name',
                region = '$region',
                city = '$city',
                address = '$address',
                contact_person = '$contact_person',
                contact_phone = '$contact_phone',
                operating_hours = '$operating_hours',
                is_active = $is_active
                WHERE point_id = $point_id";
        
        return $this->db_query($sql);
    }
    
    /**
     * Delete/deactivate pickup point
     */
    public function delete_pickup_point($point_id)
    {
        $point_id = (int)$point_id;
        // Soft delete - just deactivate
        $sql = "UPDATE pickup_points SET is_active = 0 WHERE point_id = $point_id";
        return $this->db_query($sql);
    }
    
    /**
     * Check if pickup point exists
     */
    public function pickup_point_exists($name, $city)
    {
        $name = mysqli_real_escape_string($this->db_conn(), $name);
        $city = mysqli_real_escape_string($this->db_conn(), $city);
        
        $sql = "SELECT point_id FROM pickup_points WHERE name = '$name' AND city = '$city'";
        $result = $this->db_fetch_one($sql);
        
        return $result ? true : false;
    }
    
    /**
     * Get pickup point statistics
     */
    public function get_pickup_point_stats()
    {
        $sql = "SELECT 
                COUNT(*) as total_points,
                COUNT(DISTINCT region) as regions_covered,
                COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_points
                FROM pickup_points";
        
        return $this->db_fetch_one($sql);
    }
    
    /**
     * Get regions with pickup points
     */
    public function get_regions_with_points()
    {
        $sql = "SELECT DISTINCT region, COUNT(*) as point_count 
                FROM pickup_points 
                WHERE is_active = 1 
                GROUP BY region 
                ORDER BY region";
        
        return $this->db_fetch_all($sql);
    }
    
    /**
     * Search pickup points
     */
    public function search_pickup_points($search_term)
    {
        $search_term = mysqli_real_escape_string($this->db_conn(), $search_term);
        
        $sql = "SELECT * FROM pickup_points 
                WHERE (name LIKE '%$search_term%' 
                OR city LIKE '%$search_term%' 
                OR region LIKE '%$search_term%'
                OR address LIKE '%$search_term%')
                AND is_active = 1
                ORDER BY region, city";
        
        return $this->db_fetch_all($sql);
    }
}
?>