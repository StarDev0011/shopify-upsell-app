<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MY_Controller extends CI_Controller
{

    public $shopDomain                  = '';
    public $shopId                      = '';
    public $accessToken                 = '';
    public $shopCurrency                = '';
    public $shopRejectedChargeStatus    = ['declined', /* 'expired', */ 'frozen',
        'cancelled'];
    public $shopRejectedChargeStatusMsg = ['declined'  => 'You have been declined payment. You cannot proceed further.',
        'expired'   => 'Your trial period has been expired. You cannot proceed further.',
        'frozen'    => 'Your account has been frozen. You cannot proceed further.',
        'cancelled' => 'You have been cancelled payment. You cannot proceed further.'];
    public $chargeStatus                = '';
    public $shopStatus                  = '';
    public $trialDays                   = '';
    public $customer_id                 = '';
    public $stripeSecret                = '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'; // sk_test_4fxs8SqHAB0IdgvlY8lHUh27
    public $stripePublic                = 'pk_live_51JoZjTFc66FMVWkKKPjMjblbLznS8hfQFti8utIY2RiALrz1x9NBmjunLwPDwMN67E6BhPFCmszTxm2uGMlqB5U900nkzARr3D'; // pk_test_nAKDBFzDBGKIiPTxdf5FjzWI

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('shop');
        $this->load->model('products');
        $this->shopDomain  = $this->session->userdata('shop');
        $this->accessToken = $this->session->userdata('access_token');
        
//      echo  $this->session->userdata('shop');
//      prExit($this->session->all_userdata());
        require(APPPATH . 'third_party/stripe/init.php');

        $this->stripe = new \Stripe\StripeClient(
            $this->stripeSecret
        );
        
        \Stripe\Stripe::setCABundlePath(APPPATH . "third_party/stripe/ca.crt");

        $shopInfo          = $this->shop->get_by_domain($this->session->userdata('shop'));

        if (!empty($shopInfo))
        {
            // echo "<br>";
            // //var_dump($shopInfo->myshopify_domain);
            // echo "</br>";
            $this->shopId       = $shopInfo->shop_id;
            $this->shopCurrency = $shopInfo->currency;
            $this->chargeStatus = $shopInfo->charge_status;
            $this->shopStatus   = $shopInfo->shop_status;
            $this->shopDomain   = $shopInfo->myshopify_domain;
            $charge_id          = $shopInfo->charge_id;
            
            $this->check_billing();
        }
    }

    /**
     * Remove expired recurring charge
     * @param type $shop
     * @param type $charge_id
     */
    public function remove_expired_charges($shop, $charge_id)
    {
        $shop_api_data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $this->session->userdata('access_token'),
        );
        $this->load->library('Shopify', $shop_api_data);

        $res = $this->shopify->call(array(
            'METHOD' => 'DELETE',
            'URL'    => $shop . '/admin/recurring_application_charges/' . $charge_id . '.json'
                ), true
        );
    }

    /**
     * Add billing when any app is installed
     * @param type $shop
     */
    public function add_billing($shop)
    {   
        // $charge = $this->stripe->charges->create([
        //     'amount' => $this->get_plugin_price_tier(),
        //     'currency' => 'usd',
        //     'customer' => $this->customer_id,
        //     'description' => 'SmartCart - ' . $shop
        //     // 'source' => $this->stripe->customers->retrieve($this->customer_id, [])->default_source,
        // ]);

        $shopData = array(
            'charge_id'        => $charge->id,
            'confirmation_url' => '',
            'charge_status'    => $charge->status,
            'created_date'     => date('Y-m-d H:i:s'),
            'charge_date'      => date('Y-m-d H:i:s'),
        );

        $this->shop->update_shop($shopData, $shop);
    }

    /**
     * Check if billing
     */
    public function check_billing()
    {
        $shop = $this->session->userdata('shop');

        $query = "SELECT customer_id FROM stripe_customers WHERE isOld = 0 AND shop = '" . $shop .  "';";
        $result = $this->db->query($query);
        
        // See if they are a new store or not
        if ($result->num_rows() == NULL) {
            // Create a Stripe customer for them if new
            $query = "SELECT email FROM shop WHERE myshopify_domain = '" . $shop .  "';";
            $email = $this->db->query($query)->row()->email;

            $result = $this->stripe->customers->create([
                'description' => $shop,
                'email' => $email
            ]);

            $query = "INSERT INTO stripe_customers (shop, customer_id)
                    VALUES ('" . $shop .  "', '" . $result->id . "');";
            $this->db->query($query);
            
            $this->customer_id = $result->id;
        } else {
            $row = $result->row();
            $this->customer_id = $row->customer_id;
        }

        $this->get_plugin_price_tier(); 
        // If they have no billing within the last 30 days and have an active card on file
        if (
            $this->products->check_last_billing() && 
            $this->stripe->customers->retrieve($this->customer_id, [])->default_source != NULL &&
            $this->get_plugin_price_tier() != 0
        ) {
            $this->add_billing($shop);
        }

        // redirect('auth/dashboard');
    }

    public function get_plugin_price_tier() {
        $query = 'SELECT SUM(price*quantity) AS total
            FROM order_details
            WHERE shop_id = ' . $this->shopId . ' GROUP BY shop_id
            LIMIT 0, 1;';
        $result = $this->db->query($query)->row()->total;

        switch(true) {
            case $result >= 50000:
                $returnResult = 14900;
                break;
            case $result >= 10000:
                $returnResult = 9900;
                break;
            case $result >= 1000:
                $returnResult = 5900;
                break;
            case $result >= 500:
                $returnResult = 3900;
                break;
            case $result >= 100:
                $returnResult = 1900;
                break;
            default:
                break;
        }
        
        $query = "UPDATE shop SET price_tier = '" . $returnResult . "', profit = '" . $result . "' WHERE shop_id = " . $this->shopId . ";";
        $this->db->query($query);
        return $returnResult;
    }

    function view($view, $vars = array(), $string = false)
    {
        if ($string)
        {
            $result = $this->load->view('templates/header', $vars, true);
            $result .= $this->load->view($view, $vars, true);
            $result .= $this->load->view('templates/footer', $vars, true);
            return $result;
        }
        else
        {
            $this->load->view('templates/header', $vars);
            $this->load->view($view, $vars);
            $this->load->view('templates/footer', $vars);
            
        }
    }

}
