<?php

class Products extends CI_Model {

    private $_table = 'products';
    public $shop;

    function __construct() {
        parent::__construct();
    }

    public function check_last_billing() {
        $shop = $this->session->userdata('shop');
        $query = "SELECT charge_date FROM shop WHERE myshopify_domain = '" . $shop .  "';";
        $result = $this->db->query($query);
        $row = $result->row();
        $diff = ((strtotime(date('Y-m-d H:i:s')) - strtotime($row->charge_date))/60/60/24/30);

        if ($diff >= 1) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Insert into table
     * @param type $productData
     */
    public function add($productData) {
        $this->db->insert($this->_table, $productData);
    }

    /**
     * add collections
     * @param type $collectionsData
     */
    public function add_collections($collectionsData) {
        $collectionsData['created_date'] = save_db_date();
        $this->db->insert('collections', $collectionsData);
    }
    
    /**
     * add collections
     * @param type $collectionsData
     */
    public function add_collections_batch($collectionsData) {
        $this->db->insert_batch('product_collections', $collectionsData);
    }

    /**
     * update collections
     * @param type $collectionsData
     * @param type $collections_id
     */
    public function update_collections($collectionsData, $collections_id) {
        $collectionsData['modified_date'] = save_db_date();
        $this->db->where('collections_id', $collections_id);
        $this->db->update('collections', $collectionsData);
    }

    /**
     * delete collections
     * @param type $collections_id
     */
    public function delete_collections($collections_id) {
        $this->db->where('collections_id', $collections_id);
        $this->db->delete('collections');
    }
    
    /**
     * 
     * @param type $shop_id
     */
    public function delete_collection_by_shop($shop_id)
    {
        $this->db->where('shop_id', $shop_id);
        $this->db->delete('collections');
    }

    /**
     * get collections
     * @param type $shop_id
     * @return type
     */
    public function get_collections($shop_id = '') {
        $query = $this->db->select()->from('collections')->Where('shop_id', $shop_id);
        $result = $this->db->get()->result();
        return $result;
    }
    
    /**
     * get collections
     * @param type $shop_id
     * @return type
     */
    public function get_collection_record($collectionId = '') {
        $query = $this->db->select()->from('collections')->Where('collections_id', $collectionId);
        $result = $this->db->get()->row();
        return $result;
    }

    /**
     * ger collections of product
     * @param type $prod_id
     * @return type
     */
    public function get_product_collection($prod_id = '') {
        $table = 'product_collections';
        $cond = array('a.product_id' => $prod_id);

        $this->db->select('*')
                ->from('product_collections a')
                ->join('products b', 'a.product_id=b.product_id')
                ->group_by('a.collection_id')
                ->Where($cond);

        $result = $this->db->get()->result();

        return $result;
    }

    /**
     * Add collection
     * @param type $varData
     */
    public function add_collection($varData) {
        $table = 'product_collections';
        $this->db->insert($table, $varData);
    }

    /**
     * Update collection
     * @param type $varData
     * @param type $collection_id
     */
    public function update_collection($varData, $collection_id) {
        $table = 'product_collections';
        $this->db->where('collection_id', $collection_id);
        $this->db->update($table, $varData);
    }

    /**
     * Get product by collection
     * @param type $collection_id
     * @return type
     */
    public function get_pro_by_collections($collection_id) {
        $query = $this->db->select('product_id')->from('product_collections')->Where('collection_id', $collection_id);
        $result = $this->db->get()->result();
        return $result;
    }
    
    /**
     * Get product by collection
     * @param type $collection_id
     * @return type
     */
    public function get_pro_by_collections_product($product_id,$collection_id) {
        $query = $this->db->select('id')->from('product_collections')->Where('product_id', $product_id)->Where('collection_id', $collection_id);
        $result = $this->db->count_all_results();
        return $result;
    }
    
    /**
     * Get product by collection
     * @param type $collection_id
     * @return type
     */
    public function delete_product_collection($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->delete('product_collections');
    }

    /**
     * get product Id by id
     * @param type $product_id
     * @return type
     */
    public function get_product_id_by_id($product_id = '') {
        $query = $this->db->select()->from($this->_table)->Where('product_id', $product_id)->group_by('product_id');
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get product by id
     * @param type $product_id
     * @return type
     */
    public function get_product_by_id($product_id = '') {
        $query = $this->db->select()->from($this->_table)->Where('product_id', $product_id);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * Returns products list using shop id and other conditions
     * @author Dhara
     * @param type $shop_id
     * @param type $searchText
     * @param type $addedProducts
     * @param type $category
     * @return type
     */
    public function get($shop_id = '', $searchText = '', $addedProducts = '', $category = '',$fields='*',$type='') {
        
        $this->db->select($fields);
        $this->db->from($this->_table);
        $this->db->where('shop_id', $shop_id);
        if (!empty($searchText))
            $this->db->where('title like "%' . $searchText . '%"');
        if (!empty($addedProducts) && !empty($searchText))
            $this->db->where('product_id NOT IN (' . $addedProducts . ')');
        if (!empty($category)) {
            $this->db->join('product_collections pc', 'pc.product_id=products.product_id');
            $this->db->where('collection_id IN ('.$category.')');
        }
        $this->db->group_by('products.product_id');
        if($type=='cross-sell'){
            $this->db->limit(3);
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    /**
     * Returns product variants
     * @param type $prod_id
     * @return type
     */
    public function get_variants($prod_id = '') {
        $table = 'product_variants';
        $cond = array('a.product_id' => $prod_id);
        //$this->db->where('inventory >', 0); 
        $this->db->select('*')
                ->from('product_variants a')
                ->group_by('a.variant_id')
                ->Where($cond);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get product by slug
     * @param type $slug
     * @return type
     */
    public function get_product_id_by_slug($slug = '', $isObject = 0) {
        $query = $this->db->select()->from($this->_table)->Where('product_slug', $slug);
        if ($isObject == 0) {
            $result = $this->db->get()->result();
        } else {
            $result = $this->db->get()->row();
        }
        return $result;
    }

    /**
     * returns product data by title
     * @param type $keyword
     * @param type $shop_id
     * @return type
     */
    public function search_by_keyword($keyword = '', $shop_id) {
        $query = $this->db->select('title')->from($this->_table)->Where('shop_id', $shop_id)->like('title', '' . $keyword . '', 'both')->or_like('tags', '' . $keyword . '', 'both');
        $this->db->limit(10);
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_products_not_in($arr) {
        $products = implode(",", $arr);
        $query = ("SELECT product_id FROM " . $this->_table . " WHERE shop_id = '" . $this->shopId . "' AND product_id NOT IN (" . $products . ");");
        return $this->db->query($query)->result();
        // return $result;
    }

    /**
     * Match title and returns data
     * @param type $keyword
     * @param type $shop_id
     * @return type
     */
    public function search_by_string($keyword = '', $shop_id) {
        $query = $this->db->select()->from($this->_table)->Where('shop_id', $shop_id)->like('title', '' . $keyword . '', 'both')->or_like('tags', '' . $keyword . '', 'both');
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * Updates product
     * @param type $productData
     * @param type $prod_id
     */
    public function update_product($productData, $prod_id) {
        $this->db->where('product_id', $prod_id);
        $this->db->update($this->_table, $productData);
    }

    /**
     * Delete product
     * @param type $prod_id
     */
    public function delete_product($prod_id) {
        $this->db->where('product_id', $prod_id);
        $this->db->delete($this->_table);

        $this->delete_variants($prod_id);

        $this->delete_cart_log($prod_id);
        
        $this->delete_discount_products($prod_id);
    }
    
    public function delete_discount_products($prod_id){
        $this->db->where('entitled_product_ids', $prod_id);
        $this->db->or_where('prerequisite_product_ids', $prod_id);
        $this->db->delete('discount_details');
    }

    /**
     * Add product variant
     * @param type $varData
     */
    public function add_variant($varData) {
        $table = 'product_variants';
        $this->db->insert($table, $varData);
    }

    /**
     * Updates product variant
     * @param type $varData
     * @param type $var_id
     */
    public function update_variant($varData, $var_id) {
        $table = 'product_variants';
        $this->db->where('variant_id', $var_id);
        $this->db->update($table, $varData);
    }

    /**
     * Deletes product variant
     * @param type $prod_id
     */
    public function delete_variants($prod_id) {
        $table = 'product_variants';
        $this->db->where('product_id', $prod_id);
        $this->db->delete($table);
    }

    public function delete_variant_by_id($var_id) {
        $table = 'product_variants';
        $this->db->where('variant_id', $var_id);
        $this->db->delete($table);
    }

    /**
     * Deletes cart log
     * @param type $prod_id
     */
    public function delete_cart_log($prod_id) {
        $table = 'cart_log';
        $this->db->where('product_id', $prod_id);
        $this->db->delete($table);
    }

    public function get_front() {
        $this->db->select('*')
                ->from('product_variants a')
                ->join('products b', 'a.product_id=b.product_id')
                ->group_by('a.product_id');

        $result = $this->db->get()->result();

        return $result;
    }

    /**
     * get products by variants
     * @param type $prod_id
     * @return type
     */
    public function get_product_variants($prod_id = '') {
        $table = 'product_variants';
        $cond = array('a.product_id' => $prod_id);

        $this->db->select('*')
                ->from('product_variants a')
                ->join('products b', 'a.product_id=b.product_id')
                ->group_by('a.variant_id')
                ->Where($cond);

        $result = $this->db->get()->result();

        return $result;
    }

    /**
     * get products by variant
     * @author Dhara
     * @param type $prod_id
     * @return type
     */
    public function get_product_variant($prod_id = '') {
        $table = 'product_variants';
        $cond = array('a.product_id' => $prod_id);

        $this->db->select('*,a.variant_title')
                ->from('product_variants a')
                ->Where($cond);

        $result = $this->db->get()->result();

        return $result;
    }

    /**
     * get product by variant id
     * @author Dhara
     * @param type $variantId
     * @return type
     */
    public function get_product_variant_by_id($variantId = '',$isObject=0) {
        $table = 'product_variants';
        $query = $this->db->select()->from($table)->Where('variant_id', $variantId);
        echo $query;
        if($isObject==0)
            $result = $this->db->get()->result();
        else
            $result = $this->db->get()->row();
        return $result;
    }

    /* getProductByVariant */
    
    function update_variant_quantity($variant_id, $inventory) {
        $this->db->query("UPDATE product_variants "
                . "SET inventory  = inventory - $inventory "
                . "WHERE variant_id = " . $variant_id);
    }
    
    function get_products_via_variant($variant_ids)
    {
        $this->db->select('product_id')
                ->from('product_variants')
                ->where_in('variant_id',$variant_ids);
        return $this->db->get()->result_array();
    }
    
    public function get_not_processed_collection()
    {
        $this->db->select('product_id,s.shop_id,myshopify_domain,access_token,domain')
                ->from($this->_table)
                ->join('shop s', 's.shop_id='.$this->_table.'.shop_id')
                ->where('is_collection_processed',0);
        return $this->db->get()->result();
    }
    
    public function get_not_processed_variants()
    {
        $this->db->select('pv.variant_id,p.product_id,pv.image_id,pv.image,s.shop_id,s.charge_status,myshopify_domain,access_token,domain')
                ->from('product_variants pv')
                ->join('products p', 'p.product_id=pv.product_id')
                ->join('shop s', 's.shop_id=p.shop_id')
                ->where('is_image_processed',0)
                ->where('image_id is not null')
//                ->where('s.myshopify_domain!="shop-andina.myshopify.com"')
                ->where('(s.charge_status!="expired" and s.charge_status!="removed" and s.charge_status!="declined")');
        return $this->db->get()->result();
    }
    
    public function getFirstVariant($productId)
    {
        $this->db->select('variant_id')
                ->from('product_variants')
                ->where_in('product_id',$productId);
        return $this->db->get()->row();
    }
    
    public function getProductVariantBySlug($data)
    {
        $this->db->select('variant_id,price')
                ->from('products p')
                ->join('product_variants pv', 'pv.product_id=p.product_id')
                ->Where('product_slug', $data['slug'])
                ->Where('p.shop_id', $data['shop_id']);
        return $this->db->get()->row();
    }
    
    public function update_variant_image_status($shopDomain) {
        $this->db->query("UPDATE product_variants pv "
                . "JOIN products p On p.product_id=pv.product_id JOIN shop s ON s.shop_id=p.shop_id "
                . "SET is_image_processed= 2 WHERE pv.is_image_processed= 0 and s.myshopify_domain = '$shopDomain'");
    }
}
