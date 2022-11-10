<?php

class Shop extends CI_Model {

    private $_table = 'shop';
    private $_setting_table = 'settings';
    public $shop;

    function __construct() {
        parent::__construct();
    }

    /**
     * insert shop data
     * @param type $shop
     */
    public function insert_shop($shop) {
        $data = array();
        $query = $this->db->where('domain', $shop->domain)->or_where('myshopify_domain',$shop->domain)->get($this->_table);
        $row = $query->row();
        if (empty($row)) {
            $data['shop_id'] = $shop->id;
            $data['name'] = $shop->name;
            $data['email'] = $shop->email;
            $data['myshopify_domain'] = $shop->myshopify_domain;
            $data['domain'] = $shop->domain;
            $data['country'] = $shop->country;
            $data['address'] = $shop->address1 . '.';
            $data['zip'] = $shop->zip . '.';
            $data['city'] = $shop->city;
            $data['phone'] = $shop->phone;
            $data['currency'] = $shop->currency;
            $data['shop_owner'] = $shop->shop_owner;
            $data['shop_status'] = $shop->plan_name;
            $data['access_token'] = $this->session->userdata('access_token');
            $this->db->insert($this->_table, $data);
            
            $setting['shop_id']=$data['shop_id'];
            $setting['default_offer_title']='';
            $setting['default_offer_description']='';
            $setting['show_sku_product']=0;
            $setting['show_no_thank_link']=1;
            $setting['created_date']= save_db_date();
            $this->db->insert($this->_setting_table, $setting);

            /* * * * * * * *
                Mailchimp: For first time users
            * * * * * * * */
            $list = "9704ede958";
            $apikey = "24e1f883ec9845b812f915a87760220b-us1";
            $data = json_encode([
                "email_address" => $shop->email,
                "status" => "subscribed",
                "tags" => [
                    "Installer"
                ]
            ]);
            $headers = [
                "Authorization:" . sprintf('Basic %s', base64_encode('scub:' . $apikey ))
            ];

            $ch = curl_init("https://us1.api.mailchimp.com/3.0/lists/$list/members");

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_exec($ch);
            curl_close($ch);
            /* * * * * * * *
                End Mailchimp
            * * * * * * * */
        }

        $this->session->set_userdata(array('email' => $shop->email));
    }

    /**
     * Updates record
     * @param type $data
     * @param type $shop
     */
    public function update_shop($data = FALSE, $shop = '') {
        $this->db->where('domain', $shop);
        $this->db->or_where('myshopify_domain',$shop);
        $this->db->update($this->_table, $data);
    }

    /**
     * get record by domain
     * @param type $domain
     * @return type
     */
    public function get_by_domain($domain = '') {
        $query = $this->db->where('domain', $domain)->or_where('myshopify_domain',$domain)->get($this->_table);
        return $query->row();
        
    }
    
    /**
     * delete record
     * @param type $shop_id
     */
    public function delete($shop_id) {
        $this->db->where('shop_id', $shop_id);
        $this->db->delete($this->_table);
    }

    /**
     * add billing log
     * @param type $data
     */
    public function add_billing($data = FALSE) {
        $this->db->insert('billing_log', $data);
    }

    /**
     * get record by shop id
     * @param type $shopId
     * @return type
     */
    public function get_by_shop_id($shopId = '') {
        $query = $this->db->where('shop_id', $shopId)->get($this->_table);
        return $query->row();
    }
    
    /**
     * get all shope records
     * @author Dhara
     * @return type
     */
    public function get_shops(){
        $query = $this->db->select('shop_id,domain,myshopify_domain,access_token,charge_id')->where('(charge_status!="expired" and charge_status!="removed" and charge_status!="declined")')->get($this->_table);
        return $query->result();
    }
    
    public function get_not_processed_shop()
    {
        $query = $this->db->select('shop_id,domain,myshopify_domain,access_token')->where('(charge_status!="expired" and charge_status!="removed" and charge_status!="declined")')->where('is_data_added', 0)->get($this->_table);
        return $query->result();
    }
    
    public function get_not_processed_shop_products()
    {
        $query = $this->db->select('shop_id,domain,myshopify_domain,access_token')->where('(charge_status!="expired" and charge_status!="removed" and charge_status!="declined")')->where('is_products_added', 0)->get($this->_table);
        return $query->result();
    }
    
    
    public function installProcess($shop)
    {
        while (ob_get_level())
            ob_end_clean();
        header('Connection: close');
        ignore_user_abort();
        ob_start();

        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        flush();

        
    }
}
