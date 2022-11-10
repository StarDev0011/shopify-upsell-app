<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Setting extends CI_Model {

    private $_table = 'settings';
    public $shop;

    function __construct() {

        parent::__construct();
    }

    /**
     * returns all records
     * @author Dhara
     * @return type
     */
    function find_all($shopId) {
        $this->db->select('*');
        $this->db->where('shop_id', $shopId);
        $result = $this->db->get($this->_table);
        $result = $result->row();
        return $result;
    }

    /**
     * updates the setting values
     * @author Dhara
     * @param type $key
     * @param type $value
     */
    function update($data, $id) {
        $this->db->where('id', $id);
        $this->db->update($this->_table, $data);
    }

    /**
     * insert to table
     * @param type $data
     * @return type
     */
    function insert($data) {
        $this->db->insert($this->_table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * 
     * @param type $shop_id
     */
    function delete_setting($shop_id){
        $this->db->where('shop_id', $shop_id);
        $this->db->delete($this->_table);
    }
}
