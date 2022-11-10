<?php

class Cart extends CI_Model {

    private $_table = 'cart_log';

    function __construct() {
        parent::__construct();
    }

    public function insert($cartData) {
        $this->db->insert($this->_table, $cartData);
    }

    /**
     * NOT IN USE
     * @param type $prodID
     * @param type $type
     * @return type
     */
    public function cart_sums($prodID, $type = 'today') {

        $cond = array('product_id' => $prodID);

        if ($type == 'today') {

            $this->db->where('created_date = ', date('Y-m-d'));
        } elseif ($type == 'last_week') {

            $this->db->where('created_date >=', date('Y-m-d', strtotime('-1 week')));
            $this->db->where('created_date <=', date('Y-m-d'));
        } elseif ($type == 'last_month') {

            $this->db->where('created_date >=', date('Y-m-d', strtotime('-1 month')));
            $this->db->where('created_date <=', date('Y-m-d'));
        } elseif ($type == 'all') {
            $this->db->where('created_date <= ', date('Y-m-d'));
        }

        $this->db->group_by('created_date');

        $query = $this->db->select('SUM(upsells_amount) as price')->from('orders');
        $result = $this->db->get()->result();
        return isset($result->price)?$result->price:0;
    }

    /**
     * Returns sum to total views
     * @param type $bundle_id
     * @param type $type
     * @return type
     */
    public function get_views_sum($type = 'today',$shop_id='') {
        //echo date('Y-m-d').'<br>'.date('Y-m-d',strtotime("-1 days"));
        if ($type == 'today') {
            $typeCond = array('created_date =' => date('Y-m-d'));
        } elseif ($type == 'yesterday') {
            $typeCond = array('created_date =' => date('Y-m-d',strtotime("-1 days")));
        }elseif ($type == 'last_week') {
            $typeCond = array('created_date <=' => date('Y-m-d', strtotime('last week')));
        } elseif ($type == 'last_month') {
            $date = date("Y-m-d", strtotime("-1 months"));
            $typeCond = '(DATE(created_date) between "'.$date.'" and "'.date('Y-m-d').'")';
//            $typeCond = array('created_date <=' => date('Y-m-d', strtotime('last month')));
        } elseif ($type == 'all') {
            $typeCond = array();
        }

        $this->db->select('COUNT(id) as views');
        $this->db->from('views_log');
        $this->db->where($typeCond);
        $this->db->where('shop_id', $shop_id);
        $query = $this->db->get();
        $result = $query->row()->views;
        return $result;
    }

    /**
     * 
     * @param type $bundle_id
     * @param type $type
     * @return type
     */
    public function get_viewsums($bundle_id = '', $type = 'today') {
        $cond = array('bundle_id' => $bundle_id);

        if ($type == 'today') {

            $this->db->where('created_date = ', date('Y-m-d'));
        } elseif ($type == 'last_week') {

            $this->db->where('created_date >=', date('Y-m-d', strtotime('-1 week')));
            $this->db->where('created_date <=', date('Y-m-d'));
        } elseif ($type == 'last_month') {
            $date = date("Y-m-d", strtotime("-1 months"));
            $this->db->where('(DATE(created_date) between "'.$date.'" and "'.date('Y-m-d').'")');
//            $this->db->where('created_date <=', date('Y-m-d'));
        } elseif ($type == 'all') {
            $this->db->where('created_date <= ', date('Y-m-d'));
        }

        $this->db->group_by('created_date');
        $this->db->where($cond);
        $query = $this->db->select('created_date,COUNT(id) as views')->from('views_log');
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * Returns count of added products in cart
     * @param type $bundle_id
     * @param type $type
     * @return type
     */
    public function item_added($bundle_id, $type) {

        $cond = array('bundle_id' => $bundle_id);
        if ($type == 'today') {
            $this->db->where('created_date = ', date('Y-m-d'));
        } elseif ($type == 'last_week') {
            $this->db->where('created_date >=', date('Y-m-d', strtotime('last week')));
            $this->db->where('created_date <=', date('Y-m-d'));
        } elseif ($type == 'last_month') {
            $this->db->where('created_date >=', date('Y-m-d', strtotime('last month')));
            $this->db->where('created_date <=', date('Y-m-d'));
        } elseif ($type == 'all') {
            $this->db->where('created_date <= ', date('Y-m-d'));
        }

        $this->db->where($cond);
        $query = $this->db->select('created_date,COUNT(product_id) as total')->from($this->_table);
        $this->db->group_by('created_date');
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * Returns count of item added in cart
     * @param type $prodID
     * @param type $type
     * @return type
     */
    public function items_added($prodID, $type = 'today') {
        $cond = array('product_id' => $prodID);
        if ($type == 'today') {
            $typeCond = array('created_date =' => date('Y-m-d'));
        }elseif ($type == 'yesterday') {
            $typeCond = array('created_date =' => date('Y-m-d',strtotime("-1 days")));
        } elseif ($type == 'last_week') {
            $typeCond = array('created_date <=' => date('Y-m-d', strtotime('last week')));
        } elseif ($type == 'last_month') {
            $typeCond = array('created_date <=' => date('Y-m-d', strtotime('last month')));
        } elseif ($type == 'all') {
            $typeCond = array('created_date <= ' => date('Y-m-d'));
        }
        $this->db->select('created_date,COUNT(product_id) as total');
        $this->db->from($this->_table);
        $this->db->where($cond);
        $this->db->where($typeCond);
        $query = $this->db->get();
        $result = $query->row()->total;
        return $result;
    }

    /**
     * 
     * @param type $prodID
     * @param type $type
     * @return type
     */
    public function cart_sum($type = 'today',$shop_id) {
        
        if ($type == 'today') {
            $typeCond = array('created_date =' => date('Y-m-d'));
        }elseif ($type == 'yesterday') {
            $typeCond = array('created_date =' => date('Y-m-d',strtotime("-1 days")));
        } elseif ($type == 'last_week') {
            $typeCond = array('created_date <=' => date('Y-m-d', strtotime('last week')));
        } elseif ($type == 'last_month') {
            $date = date("Y-m-d", strtotime("-1 months"));
            $typeCond = '(DATE(created_date) between "'.$date.'" and "'.date('Y-m-d').'")';
        } elseif ($type == 'all') {
            $typeCond = array();
        }

        $this->db->select('count(id) as cnt');
        $this->db->from($this->_table);
        $this->db->where($typeCond);
        $this->db->where('shop_id', $shop_id);
        $query = $this->db->get();
        return $query->row()->cnt;
    }

    /**
     * Return cart product using product id
     * @param type $prodID
     * @param type $token
     * @return type
     */
    public function get_cart_product($prodID, $token) {
        $cond = array('product_id' => $prodID, 'token' => $token);
        $query = $this->db->select('price')->from($this->_table)->Where($cond);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * Delete the record
     * @param type $prodID
     */
    public function delete($prodID) {
        $this->db->where('product_id', $prodID);
        $this->db->delete($this->_table);
    }

/* delete */
}
