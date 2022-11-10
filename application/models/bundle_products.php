<?php

class Bundle_Products extends CI_Model {

    private $_table = 'bundle_products';
    public $shop;

    function __construct() {
        parent::__construct();
    }

    public function insatll($prodData) {
        $this->db->insert($this->_table, $prodData);
    }

    public function get($shop_id = '') {
        $query = $this->db->select()->from($this->_table)->Where('shop_id', $shop_id);
        $result = $this->db->get()->result();
        return $result;
    }

    /*
      public function get_awaiting_influencer($id){
      $this->db->select('brand.id, influencers.id, influencers.name, influencers.email, brand_influencer.*')
      ->from('brand_influencer')
      ->join('brand', 'brand.id = brand_influencer.brand_id')
      ->join('influencers', 'influencers.id = brand_influencer.influencer_id')
      ->where(['brand_influencer.brand_id'=>$id, 'brand_influencer.invite_status'=>0]);
      $result1 = $this->db->get()->result();

      $result2 = $this->db->where('brand_id',$id)->get('non_influencer')->result();
      $result = array_merge((array)$result1,(array)$result2);
      return  (object)$result;
      }

      public function get_active_influencer($id){
      $this->db->select('influencers.id, influencers.name, influencers.email, brand_influencer.*')
      ->from('brand_influencer')
      ->join('influencers', 'influencers.id = brand_influencer.influencer_id')
      ->where(['brand_influencer.brand_id'=>$id, 'brand_influencer.invite_status'=>1]);
      $result = $this->db->get()->result();
      return  $result;
      }

     */
}
