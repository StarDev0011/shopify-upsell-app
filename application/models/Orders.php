<?php

class Orders extends CI_Model {

    private $_table = 'orders';
    private $_childTable = 'order_details';
    public $shop;

    function __construct() {
        parent::__construct();
    }

    /**
     * Insert record
     * @param type $ordersData
     * @return type
     */
    public function add($ordersData) {
        $this->db->insert($this->_table, $ordersData);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    
    /**
     * Add child record
     * @param type $ordersData
     * @return type
     */
    public function add_child($ordersData) {
        $this->db->insert($this->_childTable, $ordersData);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * get order by order_id
     * @param type $order_id
     * @return type
     */
    public function check_by_id($order_id = '') {
        $query = $this->db->select()->from($this->_table)->Where('order_id', $order_id);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get orders by shopid
     * @param type $shop_id
     * @return type
     */
    public function get_detail_by_id($shop_id = '') {
        $query = $this->db->select()->from($this->_childTable)->Where('shop_id', $shop_id)->group_by('shop_id');
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get orders by shopid
     * @param type $shop_id
     * @return type
     */
    public function get_by_id($shop_id = '') {
        $query = $this->db->select()->from($this->_table)->Where('shop_id', $shop_id)->group_by('shop_id');
        $result = $this->db->get()->result();
        return $result;
    }
    /**
     * Returns all order of shop
     * @param type $shop_id
     * @return type
     */
    public function get_all_by_id($shop_id = '') {
        $query = $this->db->select()->from($this->_table)->Where('shop_id', $shop_id);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * Updates orders
     * @param type $ordersData
     * @param type $order_id
     */
    public function update_orders($ordersData, $order_id) {
        $this->db->where('order_id', $order_id);
        $this->db->update($this->_table, $ordersData);
    }
    
    /**
     * returns count and sum of price using products
     * @author Dhara
     * @param type $shopId
     * @param type $products
     * @param type $type
     * @return type
     */
    public function get_order_product_count($shopId,$products='',$type='',$dateRange=''){
        $typeCond = '';
        if ($type == 'today') {
            $typeCond = array('DATE(created_date) =' => date('Y-m-d'));
        } elseif ($type == 'yesterday') {
            $typeCond = array('DATE(created_date) =' => date('Y-m-d',strtotime("-1 days")));
        }elseif ($type == 'last_month') {
            $date = date("Y-m-d", strtotime("-1 months"));
            $typeCond = 'DATE(created_date) >="'.$date.'" and DATE(created_date)<"'.date('Y-m-d').'"';
        }
        if(!empty($dateRange)){
           $typeCond = 'DATE(created_date) >="'.$dateRange['startDate'].'" and DATE(created_date)<="'.$dateRange['endDate'].'"'; 
        }
        $this->db->select('COUNT(id) as cnt,SUM(price*quantity) as amount');
        $this->db->from('order_details');
        $this->db->where('is_upsell_product',1);
        $this->db->where('shop_id',$shopId);
        if(!empty($products)){
            $products = implode(',', $products);
            $this->db->where('product_id IN ('.$products.')');
        }
        if(!empty($typeCond))
            $this->db->where($typeCond);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * get count of no of orders for shop
     * @param type $shopId
     * @return type
     */
    public function get_order_count($shopId, $searchData=''){
        $this->db->select('count(id) as no_of_products');
        $this->db->where('shop_id', $shopId);
        if(!empty($searchData)){
            $typeCond = 'DATE(created_date) >="'.$searchData['startDate'].'" and DATE(created_date)<="'.$searchData['endDate'].'"'; 
            $this->db->where($typeCond);
        }
        $result = $this->db->get('order_details');
        $result = $result->row();
        return ($result)?$result->no_of_products:0;
    }

    public function get_views_count($shopId, $searchData=''){
        $this->db->select('count(id) as no_of_views');
        $this->db->where('shop_id', $shopId);
        if(!empty($searchData)){
            $typeCond = 'DATE(created_date) >="'.$searchData['startDate'].'" and DATE(created_date)<="'.$searchData['endDate'].'"'; 
            $this->db->where($typeCond);
        }
        $result = $this->db->get('views_log');
        $result = $result->row();
        return ($result)?$result->no_of_views:0;
    }

    public function get_cart_count($shopId, $searchData=''){
        $this->db->select('count(id) as no_of_cart');
        $this->db->where('shop_id', $shopId);
        if(!empty($searchData)){
            $typeCond = 'DATE(created_date) >="'.$searchData['startDate'].'" and DATE(created_date)<="'.$searchData['endDate'].'"'; 
            $this->db->where($typeCond);
        }
        $result = $this->db->get('cart_log');
        $result = $result->row();
        return ($result)?$result->no_of_cart:0;
    }
    public function get_upsell_amount($shopId, $dateRange=''){        
        $this->db->select('COUNT(id) as cnt, SUM(price*quantity) as amount');
        $this->db->from('order_details');
        $this->db->where('is_upsell_product',1);
        $this->db->where('shop_id',$shopId);
        if(!empty($dateRange)){
            $typeCond = 'DATE(created_date) >="'.$dateRange['startDate'].'" and DATE(created_date)<="'.$dateRange['endDate'].'"'; 
            $this->db->where($typeCond);
        }
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }
    
    /**
     * get count of total upsell ourchase
     * @param type $shopId
     * @return type
     */
    public function get_total_upsell_purchase_count($shopId){
        $this->db->select('count(id) as no_of_products');
        $this->db->where('shop_id', $shopId);
        $this->db->where('is_upsell_product',1);
        $result = $this->db->get('order_details');
        $result = $result->row();
        return ($result)?$result->no_of_products:0;
    }
    
    public function delete_orders($shop_id)
    {
        $this->db->where('shop_id', $shop_id);
        $this->db->delete($this->_table);
        
        $this->db->where('shop_id', $shop_id);
        $this->db->delete($this->_childTable);
    }
}
