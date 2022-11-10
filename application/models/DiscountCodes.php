<?php

class DiscountCodes extends CI_Model {

    private $_table = 'discount_codes';
    public $shop;

    function __construct() {
        parent::__construct();
    }

    /**
     * Insert into table
     * @param type $discountData
     */
    public function add($discountData) {
        $this->db->insert($this->_table, $discountData);
    }

    /**
     * get discounts
     * @param type $shop_id
     * @return type
     */
    public function get_discounts($shop_id = '') {
        $query = $this->db->select()->from('discount_codes')
        ->Where('shop_id', $shop_id)
        ->Where('discount_status', 1);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get discount by id
     * @param type $discount_id
     * @return type
     */
    public function get_discount_by_id($discount_id = '') {
        $query = $this->db->select()->from($this->_table)->Where('discount_id', $discount_id);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * Updates discount
     * @param type $discountData
     * @param type $discount_id
     */
    public function update_discount($discountData, $discount_id) {
        $this->db->where('discount_id', $discount_id);
        $this->db->update($this->_table, $discountData);
    }
    
    /**
     * Updates discount
     * @param type $discountData
     * @param type $discount_ids
     * @param type $shop_id
     */
    public function update_discount_status ($discountData, $discount_ids, $shop_id) {
        $this->db->where_not_in('discount_id', $discount_ids);
        $this->db->where('shop_id', $shop_id);
        $this->db->update($this->_table, $discountData);
    }
    
}
