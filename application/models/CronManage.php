<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CronManage extends CI_Model
{

    private $_table = 'cron_management';
    public $shop;

    function __construct()
    {

        parent::__construct();
    }

    /**
     * returns all records
     * @author Dhara
     * @return type
     */
    function find_all($shopId)
    {
        $this->db->select('*');
        $this->db->where('shop_id', $shopId);
        $result = $this->db->get($this->_table);
        $result = $result->row();
        return $result;
    }

    function find_not_running($name,$time=10)
    {
        $this->db->select('*');
        $this->db->where('cron_name', $name);
        $this->db->where('is_running', 0);
        $result = $this->db->get($this->_table);
        $result = $result->row();
        if (empty($result))
        {
            $this->db->select('*');
            $this->db->where('cron_name', $name);
            $this->db->where('is_running', 1);
            $result1      = $this->db->get($this->_table);
            $result1      = $result1->row();
            $start_date  = new DateTime($result1->end_time);
            $since_start = $start_date->diff(new DateTime(date('Y-m-d H:i:s')));
            if ($since_start->i >= $time)
            {
                $this->update(['is_running' => 0, 'end_time' => date('Y-m-d H:i:s')],
                                                                     'install_process');
                return $result1;
            }
        }
        return $result;
    }

    /**
     * updates the setting values
     * @author Dhara
     * @param type $key
     * @param type $value
     */
    function update($data, $name)
    {
        $this->db->where('cron_name', $name);
        $this->db->update($this->_table, $data);
    }

}
