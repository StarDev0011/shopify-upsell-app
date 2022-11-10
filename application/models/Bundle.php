<?php

class Bundle extends CI_Model {

    private $_table = 'bundles_master';
    public $shop;

    function __construct() {

        parent::__construct();
    }

    /**
     * bundle insert
     * @param type $bundleData
     * @return type
     */
    public function insert($bundleData) {
        if(isset($bundleData['id']))
            unset($bundleData['id']);
        $this->db->insert($this->_table, $bundleData);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    /**
     * bundle update
     * @param type $bundleData
     * @param type $id
     */
    public function update($bundleData, $id) {
        $this->db->where('id', $id);
        $this->db->update($this->_table, $bundleData);
    }

    /**
     * insert bundle product
     * @param type $data
     */
    public function insert_bundle_product($data = FALSE) {
        $this->db->insert('bundle_products', $data);
    }

    /**
     * update bundle product
     * @param type $bundleData
     * @param type $id
     */
    public function update_bundle_product($bundleData, $id) {
        $this->db->where('id', $id);
        $this->db->update('bundle_products', $bundleData);
    }

    /**
     * get product count
     * @param type $product_id
     * @param type $type
     * @return type
     */
    public function get_count_Product($product_id = '', $type = '') {
        $cond = array('product_id' => $product_id);
        if ($type != '') {
            $cond = array_merge($cond, array('type' => $type));
        }
        $query = $this->db->select()->from('bundle_products')->Where($cond);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * insert views
     * @param type $data
     */
    public function insert_views($data = FALSE) {
        $this->db->insert('views_log', $data);
    }

    /**
     * delete bundle products
     * @param type $bundleId
     */
    public function delete_bundleProducts($bundleId = '',$triggerProduct='') {
        
        $bundleProd = $this->get_bundle_prods($bundleId,'p');
        if(!empty($bundleProd)){
            $bundlePr = array();
            $triggerProduct = explode(',', $triggerProduct);
            foreach ($bundleProd as $bp){
                array_push($bundlePr, $bp->product_id);
            }
            $remove = array_diff($bundlePr, $triggerProduct);
            if(!empty($remove)){
                $prd = implode(',', $remove);
                $this->delete_bundle_cart_product($bundleId, $prd);
                //$this->update_bundle_order_product($bundleId, $prd);
            }
        }
        $this->db->where('bundle_id', $bundleId);
        $this->db->delete('bundle_products');
    }
    
    /**
     * Update cart product from bundle. change is_upsell_product=0 as it is removed from upsell
     * @param type $bundleId
     * @param type $productIds
     */
    public function update_bundle_order_product($bundleId = '',$productIds='') {
        $this->db->where('bundle_id', $bundleId);
        $this->db->where('product_id IN ('.$productIds.')');
        $this->db->update('order_details', array('is_upsell_product'=>0));
    }
    
    /**
     * Deletes cart product from bundle
     * @param type $bundleId
     * @param type $productIds
     */
    public function delete_bundle_cart_product($bundleId = '',$productIds='') {
        $this->db->where('bundle_id', $bundleId);
        $this->db->where('product_id IN ('.$productIds.')');
        $this->db->delete('cart_log');
    }

    /**
     * delete bundle views
     * @param type $bundleId
     */
    public function delete_bundle_views($bundleId = '') {
        $this->db->where('bundle_id', $bundleId);
        $this->db->delete('views_log');
    }
    
    /**
     * delete bundle from cart
     * @param type $bundleId
     */
    public function delete_bundle_cart_added($bundleId = '') {
        $this->db->where('bundle_id', $bundleId);
        $this->db->delete('cart_log');
    }

    /**
     * bundle delete
     * @param type $id
     */
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->_table);

        $this->delete_bundleProducts($id);
        $this->delete_bundle_views($id);
        $this->delete_bundle_cart_added($id);
    }

    /**
     * get bundles
     * @param type $shop_id
     * @param type $type
     * @return type
     */
    public function get_discount_bundle($shop_id = '', $type = '2') {
        $cond = array('shop_id' => $shop_id, 'bundle_type' => $type, 'status' => 1);
        $query = $this->db->select()->from($this->_table)->Where($cond);
        $result = $this->db->get()->result();
        //echo $this->db->last_query();
        return $result;
    }

    /**
     * get bundle products
     * @param type $bundle_id
     * @param type $prod_type
     * @param type $is_product_join
     * @return type
     */
    public function get_bundle_prods($bundle_id = '', $prod_type,$is_product_join=0) {
        $cond = array('bundle_id' => $bundle_id, 'type' => $prod_type);
        $this->db->select('bundle_products.*');
        $this->db->from('bundle_products');
        if($is_product_join==1){
            $this->db->select('product_link,image,title,product_options,product_slug');
            $this->db->join('products','products.product_id=bundle_products.product_id','inner');
        }
        $this->db->where($cond);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    /**
     * get bundle by products
     * @author Dhara
     * @param type $product_id
     * @param type $type
     * @return type
     */
    public function get_bundle_by_prod($product_id, $type,$bundle_id='') {
        $cond = array('product_id' => $product_id, 'type' => $type);
        if($bundle_id!=''){
            $bundle = array('bundle_id'=>$bundle_id);
            $cond = array_merge($cond,$bundle);
        }
        $query = $this->db->select()->from('bundle_products')->Where($cond);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * @author Dhara
     * @param type $shop_id
     * @param type $bundle_id
     * @return type
     */
    public function get_active($shop_id = '', $bundle_id = '') {
        $cond = array('shop_id' => $shop_id, 'status' => 1);
        if ($bundle_id != '') {
            $cond = array_merge($cond, array('id' => $bundle_id));
        }

        $this->db->order_by('rand()');
        $this->db->limit(1);
        $query = $this->db->select()->from($this->_table)->Where($cond);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get bundle records
     * @author Dhara
     * @param type $shop_id
     * @param type $bundle_id
     * @return type
     */
    public function get($shop_id = '', $bundle_id = '',$isSingle=0,$fields='*') {
        $cond = array('b.shop_id' => $shop_id);
        if ($bundle_id != '') {
            $cond = array_merge($cond, array('b.id' => $bundle_id));
        }
        $query = $this->db->select($fields)->from($this->_table.' as b')->Where($cond)->order_by('b.id DESC');
        if($isSingle==0){
            if($bundle_id==''){
                $this->db->join('discount_master dm', 'b.discount_id=dm.discount_id AND dm.shop_id!=0','left');
            }
            $result = $this->db->get()->result();
        }else{
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function get_bundle($product_id, $type) {
        $this->db->select('bundle_id')->from('bundle_products')->Where('product_id = ' . $product_id . ' AND type = "' . $type . '"');
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * return views
     * @author Dhara
     * @param type $bundle_id
     * @return type
     */
    public function get_views($bundle_id) {

        $cond = array('id' => $bundle_id);
        $query = $this->db->select('views')->from($this->_table)->Where($cond);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * @author Dhara
     * @param type $bundle_id
     * @return type
     */
    public function getbundle_info($bundle_id,$is_discount='') {

        $cond = array('bm.id' => $bundle_id);
        if($is_discount==1){
             $this->db->select('bm.id,bm.status,dm.discount_id,dm.discount_code,dm.value_type,dm.value,bm.bundle_label,bm.bundle_title,bm.offer_description,'
                . 'bm.check_stock,bm.check_stock_trigger,bm.use_target_products,bm.use_product_quantity,bm.upsell_condition,bm.min_price,bm.max_price,'
                . 'bm.start_date,bm.end_date,bm.min_qty,bm.max_qty,bm.discount_type,bm.offer_headline,bm.discount_goal_amount,bm.discount_text');
            $this->db->join('discount_master dm', 'bm.discount_id=dm.discount_id','left');
        }else{
            $this->db->select();
        }
        $query = $this->db->from($this->_table.' as bm')->Where($cond);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * returns products by bundle id
     * @author Dhara
     * @param type $bundle_id
     * @return type
     */
    public function get_prods_by_bundle_id($bundle_id = '') {
        $cond = array('bundle_id' => $bundle_id);
        $query = $this->db->select()->from('bundle_products')->Where($cond);
        //$this->db->group_by('product_id');
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get bundle by variant id
     * @author Dhara
     * @param type $variant_id
     * @param type $type
     * @param type $bundle_id
     * @return type
     */
    public function get_bundle_by_variant($variant_id, $type,$bundle_id='') {
        $cond = array('bundle_products.variant_id' => $variant_id, 'type' => $type,'bm.status'=>1);
        if($bundle_id!=''){
            $bundle = array('bundle_products.bundle_id'=>$bundle_id);
            $cond = array_merge($cond,$bundle);
        }
        $query = $this->db->select('bundle_products.id,bundle_products.bundle_id,bm.status,dm.discount_id,dm.value,dm.value_type,bm.bundle_label,bm.bundle_title,bm.offer_description,'
                . 'bm.check_stock,bm.check_stock_trigger,bm.use_target_products,bm.use_product_quantity,bm.upsell_condition,bm.min_price,bm.max_price,'
                . 'bm.start_date,bm.end_date,bm.min_qty,bm.max_qty,bm.discount_type,bm.offer_headline,bm.discount_goal_amount,bm.discount_text,'
                . 'pv.product_id,pv.variant_id,pv.price,pv.sku,pv.inventory,pv.variant_title,pv.inventory_management,pv.inventory_policy')
                ->join('product_variants pv', 'pv.variant_id=bundle_products.variant_id','inner')
                ->join('bundles_master bm', 'bm.id=bundle_products.bundle_id','inner')
                ->join('discount_codes dm', 'bm.discount_id=dm.discount_id','left')
                ->from('bundle_products')
                ->Where($cond)
                ->order_by('bm.id desc')
                ->limit(1);
        $result = $this->db->get()->result();
        return $result;
    }
    
    /**
     * get bundle products which are is added in another bundle
     * @author Dhara
     * @param type $product_ids
     * @param type $shop_id
     * @param type $bundle_id
     * @param type $type
     * @return type
     */
    public function get_another_bundle_products($product_ids,$shop_id,$bundle_id='',$type='t'){
        $this->db->select('p.title,bp.product_id,bp.bundle_id,b.bundle_title');
        $this->db->join('products p', 'p.product_id=bp.product_id','inner');
        $this->db->join('bundles_master b', 'b.id=bp.bundle_id','inner');
        if($bundle_id!='')
            $this->db->where('bp.bundle_id!='.$bundle_id);
        $this->db->where('type="'.$type.'" AND p.shop_id='.$shop_id.' AND bp.product_id IN ('.$product_ids.') AND b.status=1');
        $this->db->from('bundle_products as bp');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
}
