<?php

class CrossSellBundle extends CI_Model {

    private $_table = 'cross_sell_bundle';
    private $_childTable = 'cross_sell_products';
    public $discountType = [];
    
    function __construct() {
        parent::__construct();
        $this->discountType = [0=>['title'=>lang('standard'),'value'=>'','text'=>'The cross sell product will simply be presented and offered to the customer without being tied to a discount of any sort'],
            1=>['title'=>lang('discount_code'),'value'=>'fixed_amount,percentage','text'=>'The cross sell bundle will be tied to a discount code created in your store admin panel'],
            2=>['title'=>lang('free_shipping'),'value'=>'free_shipping','text'=>'The cross sell bundle will be tied to a free shipping discount code created in your store admin panel'],
            3=>['title'=>lang('buy_one_get_one'),'value'=>'bxgy','text'=>'The cross sell bundle will be tied to a buy one get one type discount code created in your store admin panel.']];
    }

    /**
     * bundle insert
     * @param type $bundleData
     * @return type
     */
    public function insert($bundleData) {
        $bundleData['created_at'] = date('Y-m-d H:i:s');
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
        $this->db->insert($this->_childTable, $data);
    }

    /**
     * update bundle product
     * @param type $bundleData
     * @param type $id
     */
    public function update_bundle_product($bundleData, $id) {
        $this->db->where('id', $id);
        $this->db->update($this->_childTable, $bundleData);
    }

    /**
     * delete bundle products
     * @param type $bundleId
     */
    public function delete_bundleProducts($bundleId = '') {
        $this->db->where('cross_sell_bundle_id', $bundleId);
        $this->db->delete($this->_childTable);
    }

    /**
     * Delete the record
     * @param type $prodID
     */
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->_table);
        $this->delete_bundleProducts($id);
    }

    public function getBundles($shopId = '',$isTargetProduct=0) {
        $this->db->select('cs.*,c.title');
        $this->db->join('collections c', 'cs.collection_id=c.collections_id', 'left');
        if($isTargetProduct==1){
            $this->db->select('csc.product_id,p.title as product_title,p.image,dm.discount_code');
            $this->db->join($this->_childTable . ' as csc', 'cs.id=csc.cross_sell_bundle_id AND csc.type="0"', 'inner');
            $this->db->join('products as p', 'p.product_id=csc.product_id', 'inner');
            $this->db->join('discount_master dm', 'cs.discount_id=dm.discount_id AND dm.shop_id!=0','left');
        }
        $this->db->from($this->_table . ' as cs');
        $this->db->where(array('cs.shop_id' => $shopId));
        $this->db->order_by('cs.id', 'desc');
        $this->db->group_by('cs.id');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function get($bundleId = '') {
        $this->db->select('cs.*');
        $this->db->from($this->_table . ' as cs');
        $this->db->where(array('cs.id' => $bundleId));
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function getBundleProducts($bundleId = '', $is_product_join = 0, $type = '') {
        $this->db->select('cs.*');
        $this->db->from($this->_childTable . ' as cs');
        if ($is_product_join == 1) {
            $this->db->select('product_link,image,title,product_options,product_slug');
            $this->db->join('products', 'products.product_id=cs.product_id', 'inner');
        }
        if ($type != '') {
            $this->db->where('type', $type);
        }
        $this->db->where(array('cs.cross_sell_bundle_id' => $bundleId));
        $this->db->order_by('type', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getOtherTargetBundleProducts($bundleId, $productId, $shopId = null) {
        $this->db->select('cs.*');
        $this->db->from($this->_childTable . ' as cs');
        $this->db->join($this->_table . ' as c', 'c.id=cs.cross_sell_bundle_id', 'inner');
        $this->db->where('type', '0');
        if($bundleId!=0)
            $this->db->where('cs.cross_sell_bundle_id !=', $bundleId);
        $this->db->where('c.shop_id', $shopId);
        $this->db->where('c.status', 1);
        $this->db->where('cs.product_id IN(' . implode(',', $productId) . ')');
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function getCrossSellTargetProduct($productId,$isDiscountJoin=false) {
        $this->db->select('cs.*,c.bundle_title,c.offer_headline,c.discount_type,success_text');
        $this->db->from($this->_childTable . ' as cs');
        $this->db->join($this->_table . ' as c', 'c.id=cs.cross_sell_bundle_id', 'inner');
        if($isDiscountJoin==true){
            $this->db->select('dm.discount_code');
            $this->db->join('discount_master dm', 'c.discount_id=dm.discount_id AND dm.shop_id!=0','left');
        }
        $this->db->where('cs.type', '0');
        $this->db->where('c.status', 1);
        $this->db->where('cs.product_id', $productId);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function getCrossSellProductDetails($bundleId = '') {
        $this->db->select('cs.*,product_variants.variant_id,product_link,product_variants.image,title,product_options,product_slug,product_variants.price,product_variants.inventory_management,inventory_policy,inventory');
        $this->db->from($this->_childTable . ' as cs');
        $this->db->join('products', 'products.product_id=cs.product_id', 'inner');
        $this->db->join('product_variants', 'products.product_id=product_variants.product_id AND product_variants.inventory>0', 'inner');
        $this->db->where('type', '1');
        $this->db->where(array('cs.cross_sell_bundle_id' => $bundleId));
        $this->db->order_by('type', 'asc');
        $this->db->group_by('product_id');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

}
