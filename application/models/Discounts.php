<?php

class Discounts extends CI_Model {

    private $_table = 'discount_master';
    
    private $_detail_table = 'discount_details';
    
    public $shop;
    
    public $discountType = [];
    
    function __construct() {
        parent::__construct();
        $this->discountType = [
		0=>['title'=>lang('standard'),'value'=>'','text'=>'The upsell product will simply be presented and offered to the customer without being tied to a discount of any sort'],
            	1=>['title'=>lang('discount_code'),'value'=>'fixed_amount,percentage','text'=>'The upsell bundle will be tied to any Standard, Free Shipping or BOGO discount code created in your store admin panel']
            /*2=>['title'=>lang('free_shipping'),'value'=>'free_shipping','text'=>'The upsell bundle will be tied to a free shipping discount code created in your store admin panel'],
            3=>['title'=>lang('buy_one_get_one'),'value'=>'bxgy','text'=>'The upsell bundle will be tied to a buy one get one type discount code created in your store admin panel.']*/
];
    }

        
    public function get_goal_away_text($shop_currency,$discount_type,$type,$value){
        switch ($discount_type){       
            case 1:
                $value = abs($value);
                if($type=='fixed_amount'){
                    $text = 'You are only <amount> ('.$shop_currency.') away from '.$value.' ('.$shop_currency.') off your total purchase!';
                    $default = 'Congratulations! Please add below product to get a '.$value.' ('.$shop_currency.') off your total purchase!';
                }else{
                    $text = 'You are only <amount> ('.$shop_currency.') away from '.$value.'% off!';
                    $default = 'Congratulations! Please add below product to get a '.$value.'% off!';
                }
                break;
            case 2:
                $text = 'You are only <amount> ('.$shop_currency.') away from free shipping!';
                $default = 'Congratulations! Please add below product to get a free shipping!';
                break;
            case 3:
                $text = 'You are only <amount> ('.$shop_currency.') away from getting a free product';
                $default = 'Congratulations! Please add below product to get a free product';
                break;
        }
        return ['text'=>$text,'default'=>$default];
    }
    
    /**
     * Add/Update
     * @author Dhara
     * @date 17-10-2018
     * @param type $data
     */
    public function save($data) {
        if(!empty($data['id'])){
            $data['modified_date'] = date('Y-m-d H:i:s');
            $this->dbqueries->update($this->_table,$data,['id'=>$data['id']]);
            return $data['id'];
        }else{
            $data['created_date'] = date('Y-m-d H:i:s');
            $this->dbqueries->insert($this->_table, $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
    }
    
    /**
     * Add/Update
     * @author Dhara
     * @date 17-10-2018
     * @param type $data
     */
    public function save_details($data) {
        if(!empty($data['id'])){
            $this->dbqueries->update($this->_detail_table,$data,['id'=>$data['id']]);
        }else{
            $this->dbqueries->insert($this->_detail_table, $data);
        }
    }
    
    /**
     * @author Dhara
     * @date 17-10-2018
     * @param type $shop
     * @return type
     */
    public function get_discounts($shop){
        return $this->dbqueries->find_all($this->_table,['discount_code','value_type','value','id','discount_id'],['shop_id'=>$shop]);
    }
    
    /**
     * @author Dhara
     * @date 17-10-2018
     * @param type $shop
     * @return type
     */
    public function get_record($discount_id,$fields='*'){
        return $this->dbqueries->find($this->_table,['discount_id'=>$discount_id],$fields);
    }
    
    /**
     * @author Dhara
     * @date 3-12-2018
     * @param type $discount_type
     * @return type
     */
    public function get_discount_types($discount_type,$shop_id=''){
        $discount = $this->discountType[$discount_type];
        $discountvalue = explode(',', $discount['value']);
        $discountvalue  = implode('","', $discountvalue);
        
        $this->db->select(['discount_id','discount_code','value','value_type']);
        $this->db->where('value_type IN ("'.$discountvalue.'")');
        $this->db->where('shop_id!=0');
        $this->db->order_by('id','DESC');
        if(!empty($shop_id))
            $this->db->where('shop_id',$shop_id);
        $result = $this->db->get($this->_table);
//         print_r($this->db->last_query());die;
        $result = $result->result();
        return $result;
    }
    
    /**
     * @author Dhara
     * @date 3-12-2018
     * @param type $discount_id
     * @param type $fields
     * @return type
     */
    public function get_discount_by_id($discount_id,$fields=['id']){
        return $this->dbqueries->find($this->_table,['discount_id'=>$discount_id],$fields);
    }
    
    /**
     * @author Dhara
     * @date 3-12-2018
     * @param type $discount_id
     */
    public function delete_discount($id){
        $this->dbqueries->delete($this->_table,['id'=>$id]);
        $this->delete_discount_details($id);
    }
    
    /**
     * @author Dhara
     * @date 3-12-2018
     * @param type $discount_id
     */
    public function delete_discount_details($discount_id){
        $this->dbqueries->delete($this->_detail_table,['discount_master_id'=>$discount_id]);
    }
    
    /**
     * @author Dhara
     * @date 3-12-2018
     * @param type $discount_id
     * @param type $field
     * @return type
     */
    public function get_bxgy_discount_details($discount_id,$select_field='entitled_product_ids,entitled_variant_ids',$type='entitled_type',$field='entitled_product_ids'){
        $this->db->select($select_field.','.$type);
        $this->db->join('discount_details dd', 'dd.discount_master_id=dm.id','left');
        $this->db->where('dm.discount_id='.$discount_id.'  AND dm.shop_id!=0 AND '.$field.' is NOT NULL');
        $result = $this->db->get($this->_table.' as dm');
        return $result->result();
    }

    public function delete($discount_id){
        $id = $this->get_record($discount_id, 'id');
        if(!empty($id)){
            $this->delete_discount_details($id->id);
            $this->dbqueries->delete($this->_table,['discount_id'=>$discount_id]);
            $this->dbqueries->update('bundles_master',['discount_type'=>0],['discount_id'=>$discount_id]);
        }
    }
}
