<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Settings extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setting');
        $shop = $this->shopDomain;
        if (empty($shop)) {
            $uri = $_SERVER['REQUEST_URI'];
            $parts = parse_url($_SERVER['REQUEST_URI']);
            if(!empty($parts['query'])){
                parse_str($parts['query'], $query);
                redirect('auth/auth?shop=' . $query['shop']);
            }else{
                redirect('auth/refresh_page');
            }
        }
        /*else{
            try{
                $data = array(
                    'API_KEY' => $this->config->item('shopify_api_key'),
                    'API_SECRET' => $this->config->item('shopify_secret'),
                    'SHOP_DOMAIN' => $shop,
                    'ACCESS_TOKEN' => $this->session->userdata('access_token'),
                );
                $this->load->library('Shopify', $data);
                $res = $this->shopify->call(array('URL' => $shop . '/admin/shop.json'), true);
            } catch (Exception $ex) {
                redirect('auth/refresh_page');
            }
        }*/
    }

    /**
     * display form of setting
     * @author Dhara
     */
    public function index() {
        $records = $this->setting->find_all($this->shopId);
        $data['curr_uri'] = 'settings';
        $data['records'] = $records;
        $this->view('admin/settings/form', $data);
    }
    
    /**
     * Updates setting records
     * @author Dhara
     */
    public function update_setting() {
        $response['status']=ERROR;
        if (!empty($this->input->post())) {
            $post = $this->input->post();
            $post['show_sku_product'] = isset($post['show_sku_product'])?1:0;
            $post['show_no_thank_link'] = isset($post['show_no_thank_link'])?1:0;
            if($post['id']!=''){
                $id= $post['id'];
                unset($post['id']);
                $post['modified_date'] = save_db_date();
                $this->setting->update($post,$id);
            }else{
                $post['created_date'] = save_db_date();
                $this->setting->insert($post);
            }
            $response['status']=SUCCESS;
        }
        echo json_encode($response);exit;
    }

    /**
     * Code snippet rendering
     * @author Dhara
     */
    public function snippet(){
        $data['curr_uri'] = 'snippet';
        $this->view('admin/settings/snippet', $data);
    }
}
