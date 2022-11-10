<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Contact_us extends CI_Model {

    private $_table = 'contact_us';
    public $shop;

    function __construct() {

        parent::__construct();
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

}
