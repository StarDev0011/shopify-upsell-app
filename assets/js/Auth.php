<?php

class Auth extends MY_Controller {

    public $shopObj;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('shop');
        $this->load->model('products');
        $this->load->model('DiscountCodes');
        $this->load->model('bundle');
        $this->load->model('cart');
        $this->load->model('orders');
        $this->load->model('Discounts');
        $this->load->library('session');
    }

    public function test_connection()
    {
        echo 'connected';
        exit;
    }

    /**
     * Initial function used when app is redirecting to our source
     * @author Dhara
     */
    public function auth()
    {
        $data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $this->input->get('shop'),
            'ACCESS_TOKEN' => ''
        );
        $this->load->library('Shopify', $data);


        $scopes              = array('read_products', 'write_products', 'read_product_listings',
            'read_collection_listings', 'read_script_tags', 'write_script_tags',
            'read_orders', 'write_orders', 'read_checkouts', 'write_checkouts', 'read_price_rules',
            'write_price_rules', 'read_themes', 'write_themes');
        $redirect_url        = $this->config->item('redirect_url');
        $paramsforInstallURL = array(
            'scopes'   => $scopes,
            'redirect' => $redirect_url
        );

        $permission_url = $this->shopify->installURL($paramsforInstallURL);
	    $_SESSION['GETDATA'] = $_GET;
        $this->load->view('auth/escapeIframe', array('installUrl' => $permission_url));
    }

    /**
     * call back function called when app called
     * @author Dhara
     */
    public function authCallback()
    {
        $code = $this->input->get('code');
        $shop = $this->input->get('shop');
        if (isset($code))
        {
            $data = array(
                'API_KEY'      => $this->config->item('shopify_api_key'),
                'API_SECRET'   => $this->config->item('shopify_secret'),
                'SHOP_DOMAIN'  => $shop,
                'ACCESS_TOKEN' => ''
            );
            $this->load->library('Shopify', $data); //load shopify library and pass values in constructor
        }
        $accessToken              = $this->shopify->getAccessToken($code);
        //echo $accessToken;exit;
        $shopData['access_token'] = $accessToken;
        $this->shop->update_shop($shopData, $shop);
        $this->session->set_userdata(array('shop' => $shop, 'access_token' => $accessToken));
        $this->session->set_userdata('shop', $shop);
        $this->session->set_userdata('access_token', $accessToken);
         
        //redirect('auth/dashboard');
        redirect('auth/one_time_scripts/' . $shop);
    }

    /**
     * Ontime script
     * @param type $shop
     */
    public function one_time_scripts($shop)
    {
        $shopInfo = $this->shop->get_by_domain($shop);
      
        if ($shopInfo)
        {
            if ($shopInfo->is_new_change_reflected == 0)
            {
                $this->load_background($shop, 0);
            } else {   
                $this->load_background($shop, 1);
            }
            redirect('auth/dashboard');
            //already exist
        } else
        {
            $this->insert_shop_data($shop);
            $this->load_background($shop);
            redirect('support/tutorial');
        }
    }

    private function load_background($shop, $isNewChange = 0)
    {
        try {
            $client = new GuzzleHttp\Client(array(
                'curl'   => array(
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false),
                'verify' => false
            ));
            if ($isNewChange == 0)
            {
           
                $res = $client->request('post',
                        $this->config->item('base_url') . 'auth/load_data',
                        ['form_params' => ['access_token' => $this->session->userdata('access_token'),
                        'shop'         => $shop]]);

                        // var_dump($res->getBody()->read(536870));
            } else
            {
                $this->shop->update_shop(['is_new_change_reflected' => 1], $shop);
                $res = $client->request('post',
                        $this->config->item('base_url') . 'auth/load_changed_data',
                        ['form_params' => ['access_token' => $this->session->userdata('access_token'),
                        'shop'         => $shop]]);
                    
                        // var_dump($res->getBody()->read(536870));

            }
        } catch (GuzzleHttp\Exception\ServerException $e) {
            $response             = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            var_dump($responseBodyAsString);
        }
        
    }

    public function load_data()
    {
        echo "loadData: ";
        $this->register_scripts($this->input->post());
        $this->snippet_create($this->input->post());
        $this->register_hooks($this->input->post());
    }

    public function load_changed_data()
    {
        echo "changedData: ";
        $this->register_scripts($this->input->post());
        $this->snippet_create($this->input->post());
        $this->register_hooks($this->input->post());
    }

    public function snippet_create($postData)
    {
//        $postData['shop']         = 'jaydeep-store.myshopify.com';
//        $postData['access_token'] = '1303b8eb5b1a37f6dea64fb490654afd';

        $domain       = $postData['shop'];
        $shop         = $postData['shop'];
        $access_token = $postData['access_token'];

        $data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $access_token
        );
        $this->load->library('Shopify', $data);

        $shopData = $this->shop->get_by_domain($shop);
        $shop_id  = $shopData->shop_id;

        $themes      = $this->shopify->call(array('URL' => $domain . '/admin/themes.json'),
                true);
        $mainThemeId = '';
        $publishedThemeId = '';
        $publishedThemeName = '';
        foreach ($themes->themes as $theme) {
            $mainThemeId = $theme->id;
            if ($theme->role == 'main' || $theme->role == 'demo')
            {
                $publishedThemeId = $theme->id;
                $publishedThemeName = $theme->name;
            }
//        $themesAssetd = $this->shopify->call(array('URL' => $domain . '/admin/themes/'.$mainThemeId.'/assets.json'),
//                                                          true);
//        prExit($themesAssetd);

            $data['asset'] = ['key'   => 'snippets/smart-cross-sell.liquid',
                'value' => '{% comment %}
                    ============= Warning: PLEASE DO NOT EDIT THIS SNIPPET! =============
                    Editing this file could break Cross Sell app functionality. 
                    This file might be overwritten.
                    ============= Warning: PLEASE DO NOT EDIT THIS SNIPPET! =============
                    {% endcomment %}
                    
{% if template contains "product" %}
  {% assign currentPage = "product" %}
{% endif %}
{% if template == "cart" %}
  {% assign currentPage = "cart" %}
{% endif %}
<style>
    .smart-clearfix:after {
      content: ".";
      visibility: hidden;
      display: block;
      height: 0;
      clear: both;
    }
</style>
<div class="smart-clearfix"></div>
<div id="smart-cross-sell"></div>
<div class="smart-clearfix"></div>
{% assign cartitems = ""%}
{% for item in cart.items %}
  {% if forloop.first == true %}
    {% capture cartitems %}{{ item.product.handle }}{% endcapture %}
  {% else %}
    {% capture cartitems %}{{ cartitems }},{{ item.product.handle }}{% endcapture %}
  {% endif %}
{% endfor %}
<script type="text/javascript" charset="utf-8">
var currentPage = "{{ currentPage }}";
</script>
'
            ];
            $response      = $this->shopify->call(array(
                'METHOD' => 'PUT',
                'DATA'   => $data,
                'URL'    => $domain . '/admin/themes/' . $mainThemeId . '/assets.json'
                    ), true
            );
        }
        $this->shop->update_shop(['published_theme_id'=>$publishedThemeId,'published_theme_name'=>$publishedThemeName],$domain);
    }

    /**
     * Insert products
     * @param type $shop
     */
    public function insert_discounts($postData)
    {
        $shop         = $postData['shop'];
        $access_token = $postData['access_token'];
        $data         = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $access_token
        );
        $this->load->library('Shopify', $data);

        $shopData        = $this->shop->get_by_domain($shop);
        $shop_id         = $shopData->shop_id;
        $domain          = $shopData->myshopify_domain;
        $shop            = $shopData->myshopify_domain;
        
        $discountCount = $this->shopify->call(array('URL' => $shop . '/admin/api/2021-01/price_rules/count.json'),
                true);
        if (!empty($discountCount->count) && $discountCount->count > 49)
        {
            $firstdiscounts = $this->shopify->call(array('URL' => $domain . '/admin/api/2021-01/price_rules.json?limit=49'),
                    true, true);
            $link          = $firstdiscounts->next_link;
            $this->addDiscounts($firstdiscounts,$shop_id,$shop, $shopData, $domain); 
            do {
                $discounts      = $this->shopify->call(array('URL' => $link),
                        true, true);
                $link          = $discounts->next_link;
                $this->addDiscounts($discounts, $shop, $access_token, $shopData,
                        $domain);
                $fistdiscounts = $discounts;
                if ($link == '')
                {
                    $this->addDiscounts($discounts, $shop, $access_token,
                            $shopData, $domain);
                }
            } while ($link != '');
        }
        else {
            $discounts = $this->shopify->call(array('URL' => $domain . '/admin/api/2021-01/price_rules.json?limit=49'),
                    true, true);
            $this->addDiscounts($discounts, $shop, $access_token, $shopData,
                    $domain);
        }
    }

    /**
     * Insert products
     * @param type $shop
     */
    public function insert_products($postData)
    {
        $shop         = $postData['shop'];
        $access_token = $postData['access_token'];
        $data         = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $access_token
        );

        $this->load->library('Shopify', $data);

        $shopData        = $this->shop->get_by_domain($shop);
        $shop_id         = $shopData->shop_id;
        $domain          = $shopData->myshopify_domain;
        $shop            = $shopData->myshopify_domain;
        $product_options = '';

        $productCount = $this->shopify->call(array('URL' => $shop . '/admin/api/2019-07/products/count.json'),
                true);
        if (!empty($productCount->count) && $productCount->count > 49)
        {
            $firstproducts = $this->shopify->call(array('URL' => $domain . '/admin/api/2019-07/products.json?limit=49'),
                    true, true);
            $link          = $firstproducts->next_link;
            $this->addProducts($firstproducts,$shop_id,$shop, $shopData, $domain); 
            do {
                $products      = $this->shopify->call(array('URL' => $link),
                        true, true);
                $link          = $products->next_link;
                $this->addProducts($products, $shop, $access_token, $shopData,
                        $domain);
                $firstproducts = $products;
                if ($link == '')
                {
                    $this->addProducts($products, $shop, $access_token,
                            $shopData, $domain);
                }
            } while ($link != '');
        } else
        {
            $products = $this->shopify->call(array('URL' => $domain . '/admin/api/2019-07/products.json?limit=49'),
                    true, true);
            $this->addProducts($products, $shop, $access_token, $shopData,
                    $domain);
        }
    }

    private function addDiscounts($discounts, $shop, $access_token, $shop_data, $domain) {
        $shop_id = $shop_data->shop_id;
        foreach ($discounts->price_rules as $disc) {
            
            $discount_id = $disc->id;
            $shop_data  = array(
                'API_KEY'      => $this->config->item('shopify_api_key'),
                'API_SECRET'   => $this->config->item('shopify_secret'),
                'SHOP_DOMAIN'  => $shop,
                'ACCESS_TOKEN' => $access_token
            );

            $this->load->library('Shopify', $shop_data);

            $oldDiscount     = $this->DiscountCodes->get_discount_by_id($discount_id);

            $discountData = array(
                'title'       => $disc->title,
                'value_type'  => $disc->value_type,
                'value'       => $disc->value,
                'starts_at'   => $disc->starts_at,
                'ends_at'     => $disc->ends_at,
                'target_type' => $disc->target_type
            );
            if (empty($oldDiscount))
            {
                $otherData   = array(
                    'shop_id'                 => $shop_id,
                    'is_discount_processed' => 0,
                    'discount_id'              => $discount_id);
                $discountData = array_merge($discountData, $otherData);
                $this->DiscountCodes->add($discountData);
            } else
            {
                $this->DiscountCodes->update_discount($discountData, $discount_id);
            }
        }
    }

    private function addProducts($products, $shop, $access_token, $shop_data,
            $domain)
    {
        $shop_id = $shop_data->shop_id;
        foreach ($products->products as $prod) {
            
            $product_id = $prod->id;
            $shop_data  = array(
                'API_KEY'      => $this->config->item('shopify_api_key'),
                'API_SECRET'   => $this->config->item('shopify_secret'),
                'SHOP_DOMAIN'  => $shop,
                'ACCESS_TOKEN' => $access_token
            );

            $this->load->library('Shopify', $shop_data);

            $oldProduct      = $this->products->get_product_by_id($product_id);
            $options         = $prod->options;
            $product_options = '';
            if ($options)
            {
                foreach ($options as $opt) {
                    $product_options .= $opt->name . '|';
                }
            }
            $productData = array(
                'title'           => $prod->title,
                'image'           => isset($prod->image->src) ? $prod->image->src : '',
                'product_link'    => 'https://' . $domain . '/products/' . $prod->handle,
                'product_slug'    => $prod->handle,
                'product_options' => trim($product_options, '|'));

            if (empty($oldProduct))
            {
                $otherData   = array(
                    'shop_id'                 => $shop_id,
                    'is_collection_processed' => 0,
                    'product_id'              => $product_id);
                $productData = array_merge($productData, $otherData);
                $this->products->add($productData);
            } else
            {
                $this->products->update_product($productData, $product_id);
            }


            $variants = $prod->variants;
            if ($variants)
            {
                $variantImage = isset($prod->image->src) ? $prod->image->src : '';
                $varImageId   = !empty($var->image_id) ? $var->image_id : '';
                foreach ($variants as $var) {
                    $oldVairant     = $this->products->get_product_variant_by_id($var->id);
                    $isImageProcess = 0;
                    if (!empty($varImageId))
                    {
                        $isImageProcess = 0;
                    } elseif (!empty($variantImage))
                    {
                        $isImageProcess = 1;
                    }
                    $varData = array(
                        'variant_title'        => $var->title,
                        'price'                => $var->price,
                        'sku'                  => $var->sku,
                        'product_id'           => $var->product_id,
                        'image_id'             => $varImageId,
                        'is_image_processed'   => $isImageProcess,
                        'image'                => $variantImage,
                        'inventory'            => $var->inventory_quantity,
                        'inventory_management' => $var->inventory_management,
                    );
                    if (empty($oldVairant))
                    {
                        $var     = array(
                            'variant_id' => $var->id,
                        );
                        $varData = array_merge($varData, $var);
                        $this->products->add_variant($varData);
                    } else
                    {
                        $this->products->update_variant($varData, $var->id);
                    }
                }
            }
        }
    }

    /**
     * This will help to run process in background
     * @Author: Dhara
     */
    function partialResponse()
    {
        $response = array();
        ignore_user_abort(true);
        ob_start();
        //echo json_encode($response);
        header("Status: 200");
        header($_SERVER["SERVER_PROTOCOL"] . " 200 Ok");
        header("Content-Type: application/json");
        header('Content-Length: ' . ob_get_length());
        ob_end_flush();
        ob_flush();
        flush();
    }

    /**
     * Load dashboard
     * @param type $shop
     */
    public function dashboard($shop = '')
      {
  
//        if (!empty($_COOKIE))
//        {
//            $expireTime = (86400 * 30);
//            foreach ($_COOKIE as $key => $row) {
//                if ($key == 'ci_session')
//                {
//                    header('Set-Cookie: ' . $key . '=' . $row . '; expires=' . $expireTime . '; SameSite=None; Secure');
//                }
//            }
//        }
        $shop = $this->session->userdata('shop');
        if ($shop == '')
        {
            $shop = $this->input->get('shop');
        }
//        header('Set-Cookie: shopName='.$this->session->userdata('shop').'; expires='.$expireTime.'; SameSite=None; Secure');
        //echo $this->session->userdata('shop');exit;
        if ($shop != '' && $this->session->userdata('access_token'))
        {
            $shop = $this->shopDomain;
            if (empty($shop))
            {
                $uri   = $_SERVER['REQUEST_URI'];
                $parts = parse_url($_SERVER['REQUEST_URI']);
                if (!empty($parts['query']))
                {
                    parse_str($parts['query'], $query);
                    redirect('auth/auth?shop=' . $query['shop']);
                } else
                {
                    redirect('auth/refresh_page');
                }
            }
            $shopInfo = $this->shop->get_by_domain($this->shopDomain);

            if ($shopInfo)
            {
                $shop_id          = $shopInfo->shop_id;
                $shop_currency    = $shopInfo->currency;
                $charge_id        = $shopInfo->charge_id;
                $charge_status    = $shopInfo->charge_status;
                $confirmation_url = $shopInfo->confirmation_url;

                //When charge is pending redirect user to approve charges
                if ($charge_status == 'pending')
                {
                    // redirect($confirmation_url);
                } else if ($charge_status == 'expired')
                {
                    // $this->remove_expired_charges($shop, $charge_id);
                }

                //When charge is expired redirect user to add new charge and approve it
                if ($charge_id == '' || ($charge_status == 'expired') || ($charge_status == 'cancelled'))
                {
                    // $this->add_billing($shop);
                }

                $this->session->set_userdata($shop);

                $bundleViewsToday = 0;
                $bundleViewsYest  = 0;
                $bundleViewsMonth = 0;
                $bundleViewsAll   = 0;

                $upsellBoughtToday = 0;
                $upsellBoughtYest  = 0;
                $upsellBoughtMonth = 0;
                $upsellBoughtAll   = 0;

                $upsellAmountToday = 0;
                $upsellAmountYest  = 0;
                $upsellAmountMonth = 0;
                $upsellAmountAll   = 0;

                $addedCartToday  = 0;
                $addedCartYest   = 0;
                $addedCartMonth  = 0;
                $addedCartAll    = 0;
                $totalUpsellSell = 0;

                $converYest  = 0;
                $converMonth = 0;
                $converAll   = 0;

                $bundleList = $this->bundle->get($shop_id);
                $today      = $this->orders->get_order_product_count($shop_id,
                        '', 'today');

                if (!empty($today))
                {
                    $upsellBoughtToday = $today->cnt;
                    $upsellAmountToday = empty($today->amount) ? 0 : $today->amount;
                }
                $yest = $this->orders->get_order_product_count($shop_id, '',
                        'yesterday');

                if (!empty($yest))
                {
                    $upsellBoughtYest = $yest->cnt;
                    $upsellAmountYest = empty($yest->amount) ? 0 : $yest->amount;
                }
                $month = $this->orders->get_order_product_count($shop_id, '',
                        'last_month');

                if (!empty($month))
                {
                    $upsellBoughtMonth = $month->cnt;
                    $upsellAmountMonth = empty($month->amount) ? 0 : $month->amount;
                }

                $all = $this->orders->get_order_product_count($shop_id);
                if (!empty($all))
                {
                    $upsellBoughtAll = $all->cnt;
                    $upsellAmountAll = empty($all->amount) ? 0 : $all->amount;
                }
                if ($bundleList)
                {
                    $addedCartToday = $this->cart->cart_sum('today', $shop_id);
                    $addedCartYest  = $this->cart->cart_sum('yesterday',
                            $shop_id);
                    $addedCartMonth = $this->cart->cart_sum('last_month',
                            $shop_id);
                    $addedCartAll   = $this->cart->cart_sum('all', $shop_id);

                    $bundleViewsToday = $this->cart->get_views_sum('today',
                            $shop_id);
                    $bundleViewsYest  = $this->cart->get_views_sum('yesterday',
                            $shop_id);
                    $bundleViewsMonth = $this->cart->get_views_sum('last_month',
                            $shop_id);
                    $bundleViewsAll   = $this->cart->get_views_sum('all',
                            $shop_id);

                    $totalUpsellSell = $this->orders->get_order_product_count($shop_id);
                    if (!empty($totalUpsellSell))
                            $totalUpsellSell = $totalUpsellSell->amount;

                    if (!empty($bundleViewsYest))
                            $converYest  = ($upsellBoughtYest / $bundleViewsYest)
                                * 100;
                    if (!empty($bundleViewsMonth))
                            $converMonth = ($upsellBoughtMonth / $bundleViewsMonth)
                                * 100;
                    if (!empty($bundleViewsAll))
                            $converAll   = ($upsellBoughtAll / $bundleViewsAll) * 100;
                }
                $totalOrder     = $this->orders->get_order_count($shop_id);
                $upsellOrder    = $this->orders->get_total_upsell_purchase_count($shop_id);
                $totalPurchased = 0;
                if ($totalOrder != 0)
                {
                    $totalPurchased = ($upsellOrder * 100) / $totalOrder;
                    $totalPurchased = number_format($totalPurchased, 2);
                }
                $totalUpsellSell = $this->convert_currency($totalUpsellSell,
                        $shopInfo->currency, 'USD');
                $totalTodaysell  = $this->orders->get_order_count($shop_id,
                        array('startDate' => date('Y-m-d'),
                    'endDate'   => date('Y-m-d')));
                $data            = array(
                    'curr_uri'          => 'dashboard',
                    'shop'              => $shop,
                    'bundleViewsToday'  => $bundleViewsToday,
                    'bundleViewsMonth'  => $bundleViewsMonth,
                    'bundleViewsAll'    => $bundleViewsAll,
                    'upsellBoughtToday' => $upsellBoughtToday,
                    'upsellAmountYest'  => $upsellAmountYest,
                    'upsellBoughtMonth' => $upsellBoughtMonth,
                    'upsellBoughtAll'   => $upsellBoughtAll,
                    'upsellAmountToday' => $upsellAmountToday,
                    'upsellAmountMonth' => $upsellAmountMonth,
                    'upsellAmountAll'   => $upsellAmountAll,
                    'confirmation_url'  => $confirmation_url,
                    'shop_currency'     => $shop_currency,
                    'addedCartToday'    => $addedCartToday,
                    'addedCartMonth'    => $addedCartMonth,
                    'addedCartAll'      => $addedCartAll,
                    'addedCartYest'     => $addedCartYest,
                    'bundleViewsYest'   => $bundleViewsYest,
                    'totalPurchased'    => $totalPurchased,
                    'totalUpsell'       => count($bundleList),
                    'totalUpsellSell'   => $totalUpsellSell,
                    'upsellBoughtYest'  => $upsellBoughtYest,
                    'totalSale'         => $totalOrder,
                    'totalTodaysell'    => $totalTodaysell,
                    'converYest'        => $converYest,
                    'converMonth'       => $converMonth,
                    'converAll'         => $converAll
                );
                $now             = time(); // or your date as well
                $your_date       = strtotime("+" . TRIAL_DAYS . " days",
                        strtotime($shopInfo->created_date));
                $datediff        = $your_date - $now;

                $trial_days                      = floor($datediff / (60 * 60 * 24));
                $this->trialDays                 = $trial_days + 1;
                $billingData['confirmation_url'] = $confirmation_url;
                $billingData['trial_days']       = $trial_days + 1;
                $billingData['charge_status']    = $charge_status;
                $billingData['shop_status']      = $shopInfo->shop_status;
                $this->load->vars($billingData);
                $data                            = $data + $billingData;

                //$data['shop'] = "mydomain.com";
                //var_dump($this->session);

                $this->view('admin/dashboard', $data);
                //var_dump($test);
            } else
            {
                $this->auth($shop);
            }
        } else
        {
            //if (empty($shop)) {
            $uri   = $_SERVER['REQUEST_URI'];
            $parts = parse_url($_SERVER['REQUEST_URI']);
            if (!empty($parts['query']))
            {
                parse_str($parts['query'], $query);
                redirect('auth/auth?shop=' . $query['shop']);
            } else
            {
                redirect('auth/refresh_page');
            }
            //}
            //$this->auth($shop);
        }
    }

    /**
     * Check if billing
     */
    public function check_billing_status()
    {
        $this->check_billing();
    }

    /**
     * Add billing when any app is installed
     * @param type $shop
     */
    public function add_billing_old($shop)
    {
        $shop_api_data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $this->session->userdata('access_token'),
        );
        $this->load->library('Shopify', $shop_api_data);

        $POST = array('recurring_application_charge' =>
            array(
                'name'       => 'Smart Cart Upsell',
                'price'      => PLUGIN_PRICE,
                'return_url' => site_url('auth/check_billing'),
                'trial_days' => TRIAL_DAYS,
            // 'test'=>true
            )
        );

        $res = $this->shopify->call(array(
            'METHOD' => 'POST',
            'DATA'   => $POST,
            'URL'    => $shop . '/admin/recurring_application_charges.json'
                ), true
        );


        if ($res->recurring_application_charge->id)
        {
            $shopData = array(
                'charge_id'        => $res->recurring_application_charge->id,
                'confirmation_url' => $res->recurring_application_charge->confirmation_url,
                'charge_status'    => $res->recurring_application_charge->status
            );

            $this->shop->update_shop($shopData, $shop);
        }/* if recurring_application_charge */

        redirect('support/tutorial');
    }

    /**
     * Returns total sale based on filter dates
     * @author Dhara
     */
    public function get_total_sells()
    {
        $shop_id          = $this->input->post('shopId');
        $ary['startDate'] = date('Y-m-d', strtotime(str_replace('/', '-', trim($this->input->post('startDate')))));
        $ary['endDate']   = date('Y-m-d', strtotime(str_replace('/', '-', trim($this->input->post('endDate')))));
//        $all = $this->orders->get_order_product_count($shop_id, '', '', $ary);
        $orders           = $this->orders->get_order_count($shop_id, $ary);
        $views            = $this->orders->get_views_count($shop_id, $ary);
        $cart             = $this->orders->get_cart_count($shop_id, $ary);
        $upsells          = $this->orders->get_upsell_amount($shop_id, $ary);
        if (!$upsells->amount || $upsells->cnt == 0) { $upsells->amount = 0; $upsells->cnt = 1; }
        $upsells_avg = ($upsells->amount / $upsells->cnt);
        $upsells_total = $upsells->amount;
        $conversion_rate = round(($upsells / $views) * 100, 2) . '%';
        if ($conversion_rate = 'INF%') { $conversion_rate = '0%'; }
        echo json_encode([
            'orders' => $orders,
            'views' => $views,
            'cart' => $cart,
            'upsells_avg' => $upsells_avg,
            'upsells_total' => round($upsells_total, 2),
            'conversion_rate' => $conversion_rate
        ]);        
        exit;
    }

    /**
     * Returns converted amount into USD
     * @author Dhara
     * @param type $amount
     * @param type $from_currency
     * @param type $to_currency
     * @return string
     */
    public function convert_currency($amount, $from_currency, $to_currency)
    {
        $from_Currency = urlencode($from_currency);
        $to_Currency   = urlencode($to_currency);
        $query         = "{$from_Currency}_{$to_Currency}";

        if ($from_Currency == 'USD')
        {
            $return['amount']   = $amount;
            $return['currency'] = '$ ';
        }
//        $apikey = 'your-api-key-here';
//        $json = file_get_contents("https://api.currencyconverterapi.com/api/v6/convert?q={$query}&compact=ultra&apiKey={$apikey}");
        $json   = @file_get_contents("https://free.currencyconverterapi.com/api/v6/convert?q={$query}&compact=ultra");
        $obj    = json_decode($json, true);
        $return = [];
        if (!empty($obj))
        {
            $val                = floatval($obj["$query"]);
            $total              = $val * $amount;
            $total              = number_format($total, 2, '.', '');
            $return['amount']   = $total;
            $return['currency'] = '$ ';
        } else
        {
            $return['amount']   = $amount;
            $return['currency'] = '';
        }
        return $return;
    }

    /**
     * Insert collections
     * @param type $shop
     */
    public function insert_collections_old($shop = '')
    {
        $shop = $this->session->userdata('shop');
        if ($shop == '')
        {
            $shop = $this->input->get('shop');
        }
        $data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $this->session->userdata('access_token')
        );
        $this->load->library('Shopify', $data);

        $collectionsRes = $this->shopify->call(array('URL' => $shop . '/admin/custom_collections.json'),
                true);
        $shopData       = $this->shopify->call(array('URL' => $shop . '/admin/shop.json'),
                true);
        $shop_id        = $shopData->shop->id;
        foreach ($collectionsRes->custom_collections as $collection) {
            $collectionsData = array(
                'collections_slug' => $collection->handle,
                'shop_id'          => $shop_id,
                'collections_id'   => $collection->id,
                'title'            => $collection->title,
                'body_html'        => $collection->body_html,
                'sort_order'       => $collection->sort_order,
            );
            $this->products->add_collections($collectionsData);
        }//end foreach...
    }

    /**
     * Inserts shop data
     * @param type $shop
     */
    public function insert_shop_data($shop)
    {

        $data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $this->session->userdata('access_token')
        );
        $this->load->library('Shopify', $data);
        $res  = $this->shopify->call(array('URL' => $shop . '/admin/shop.json'),
                true);

        if (isset($res->shop))
        {
            $this->shop->insert_shop($res->shop);
        }
    }

    /**
     * Registers script which is going to use front panel also
     * @param type $shop
     */
    public function register_scripts($postData)
    {
//        $postData['shop']         = 'jaydeep-store.myshopify.com';
//        $postData['access_token'] = '1303b8eb5b1a37f6dea64fb490654afd';
//        prExit($postData);

        $shop         = $postData['shop'];
        $access_token = $postData['access_token'];

        $scriptArray = [];
        if (DEPLOYMENT == 0)
        {
			$scriptArray[] = $this->config->item('assets_base_url') . 'js/jquery-3.6.0.min.js';
            $scriptArray[] = $this->config->item('assets_base_url') . 'front/front_upsell.js';
            $scriptArray[] = $this->config->item('assets_base_url') . 'front/cross_sell/cross_sell.js';
        } else if (DEPLOYMENT == 2)
        {
            if ($_SERVER['HTTP_HOST'] == 'smartcartupsellbundle.com')
            {
				$scriptArray[] = $this->config->item('assets_base_url') . 'js/jquery-3.6.0.min.js';
                $scriptArray[] = $this->config->item('assets_base_url') . 'front/front_upsell.js';
                $scriptArray[] = $this->config->item('assets_base_url') . 'front/cross_sell/cross_sell.js';
            } else
            {
				$scriptArray[] = $this->config->item('assets_base_url') . 'js/jquery-3.6.0.min.js';
                $scriptArray[] = $this->config->item('assets_base_url') . 'front/front_upsell.js';
                $scriptArray[] = $this->config->item('assets_base_url') . 'front/cross_sell/cross_sell.js';
            }
        } else
        {
            if ($this->config->item('base_name') == '/qa/shopify/tops-upsell/')
            {
                $scriptArray[] = $this->config->item('assets_base_url') . 'front/front_live.js';
                $scriptArray[] = $this->config->item('assets_base_url') . 'front/cross_sell/cross_sell_tops_upsell.js';
            } else
            {
                $scriptArray[] = $this->config->item('assets_base_url') . 'front/front_live_dev.js';
                $scriptArray[] = $this->config->item('assets_base_url') . 'front/cross_sell/cross_sell_tops_dev.js';
            }
        }

        $shop_api_data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $access_token,
        );
        $this->load->library('Shopify', $shop_api_data);

        $res = $this->shopify->call(array('URL' => $shop . '/admin/script_tags.json'),
                true);
        if (!empty($res))
        {
            foreach ($res->script_tags as $script) {
                $id     = $script->id;
                $script = $this->shopify->call(array('METHOD' => 'DELETE', 'URL' => $shop . '/admin/script_tags/' . $id . '.json'));
            }
        }
        foreach ($scriptArray as $script) {
            $data   = array(
                "script_tag" => array(
                    "event" => "onload",
                    "src"   => "$script"
            ));
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/script_tags.json',
                'DATA'   => $data));
        }
    }

    /**
     * Registers webhook
     * @param type $shop
     */
    public function register_hooks($shop)
    {
        $shop = $shop['shop'];

        $shop_api_data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $this->session->userdata('access_token'),
        );
        $this->load->library('Shopify', $shop_api_data);
        
        $hooks = $this->shopify->call(array('METHOD' => 'GET', 'URL' => $shop . '/admin/webhooks.json'));
        // prExit($hooks);

        $hooks = $hooks->webhooks;

        // Delete Webhooks
        // foreach ($hooks as $hk) {
        //     try {
        //         $script = $this->shopify->call(array('METHOD' => 'DELETE', 'URL' => $shop . '/admin/webhooks/'.$hk->id.'.json'));
        //         echo $hk->id.'= deleted<br>';
        //     } catch (Exception $e) {
        //         echo "<p>deleteHook Error</p>";
        //         var_dump($e->getMessage());
        //     }
        // } 
        
        if (count($hooks) != 10) {
            /** 1. App Uninstall * */
            $src       = site_url('webhook/uninstallHook');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "app/uninstalled",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array(
                    'METHOD' => 'POST',
                    'URL' => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data
                ));
            } catch (Exception $e) {
                echo "<p>uninstallHook</p>";
                var_dump($e->getMessage());
            }

            /** 2. Create Product Hook * */
            $src       = site_url('webhook/prodCreateHook');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "products/create",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>prodCreateHook</p>";
                var_dump($e->getMessage());
            }

            /** 3. Delete Product Hook * */
            $src       = site_url('webhook/prodDeleteHook');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "products/delete",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>prodDeleteHook</p>";
                var_dump($e->getMessage());
            }

            /** 4. Update Product Hook * */
            $src       = site_url('webhook/prodUpdateHook');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "products/update",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>prodUpdateHook</p>";
                var_dump($e->getMessage());
            }

            /** 5. Cart Create * */
            $src       = site_url('webhook/cartCreateHook');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "carts/create",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>cartCreateHook</p>";
                var_dump($e->getMessage());
            }

            $src       = site_url('webhook/cartUpdateHook');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "carts/update",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>cartUpdateHook</p>";
                var_dump($e->getMessage());
            }

            /** 6. Orders Create * */
            $src       = site_url('webhook/orderCreateHook');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "orders/create",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>orderCreateHook</p>";
                var_dump($e->getMessage());
            }

            /** 7. Create Collections Hook * */
            $src       = site_url('webhook/insertCollections');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "collections/create",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>insertCollections</p>";
                var_dump($e->getMessage());
            }

            /** 8. Update Collections Hook * */
            $src       = site_url('webhook/updateCollections');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "collections/update",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>updateCollections</p>";
                var_dump($e->getMessage());
            }
            /** 9. Delete Collections Hook * */
            $src       = site_url('webhook/deleteCollections');
            $hook_data = array(
                "webhook" => array(
                    "topic"   => "collections/delete",
                    "address" => $src,
                    "format"  => "json"
            ));
            try {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                    'DATA'   => $hook_data));
            } catch (Exception $e) {
                echo "<p>deleteCollections</p>";
                var_dump($e->getMessage());
            }
        }
    }

    /**
     * Insert cart log
     */
    public function add_cart_log()
    {
        $cartData = array(
            'product_id'   => $_POST['product_id'],
            'shop_id'      => $_POST['shop_id'],
            'bundle_id'    => $_POST['popup_bundle_id'],
            'price'        => $_POST['prod_price'],
            'created_date' => date('Y-m-d'),
            'token'        => $_POST['cart_token'],
        );

        echo $this->cart->insert($cartData);
        die();
    }

    /**
     * Called when session timeout of any store
     * @author Dhara
     */
    function refresh_page()
    {
        $this->view('admin/refresh');
    }

    /**
     * Inserts price rule to database
     * @author Dhara
     * @date 18-10-2018
     * @param type $shop
     */
    public function insert_price_rules($postData)
    {
        $shop         = $postData['shop'];
        $access_token = $postData['access_token'];
        $this->prepare_api_call($shop, $access_token);
        $response     = $this->shopify->call(array('URL' => $shop . '/admin/price_rules.json'),
                true);
        $shops        = $this->shop->get_by_domain($shop);
        if (isset($response->price_rules))
        {

            foreach ($response->price_rules as $rule) {
                $min_req    = 0;
                $applies_to = 0;
                if (!empty($rule->prerequisite_subtotal_range))
                {
                    $min_req = 1;
                } elseif (!empty($rule->prerequisite_quantity_range))
                {
                    $min_req = 2;
                }

                if (!empty($rule->entitled_product_ids))
                {
                    $applies_to = 2;
                } elseif (!empty($rule->entitled_collection_ids))
                {
                    $applies_to = 1;
                }

                $value_type = $rule->value_type;
                if ($rule->value_type == 'percentage')
                {
                    if ((!empty($rule->entitled_product_ids) || !empty($rule->entitled_collection_ids))
                            && (!empty($rule->prerequisite_product_ids) || !empty($rule->prerequisite_collection_ids)))
                    {
                        $value_type = 'bxgy';
                    } else if (($rule->value == -100))
                    {
                        $value_type = 'free_shipping';
                    } else
                    {
                        $value_type = 'percentage';
                    }
                }
                $data['discount_id']         = $rule->id;
                $data['shop_id']             = $shops->shop_id;
                $data['discount_code']       = $rule->title;
                $data['value_type']          = $value_type;
                $data['value']               = $rule->value;
                $data['minimum_requirement'] = $min_req;
                $data['applies_to']          = $applies_to;
                $inserted_id                 = $this->Discounts->save($data);
                if (!empty($inserted_id))
                {
                    if ($value_type == 'bxgy')
                    {
                        $this->Discounts->delete_discount_details($inserted_id);
                        if (!empty($rule->entitled_product_ids))
                        {
                            $this->add_discount_details($inserted_id,
                                    $rule->entitled_product_ids,
                                    'entitled_product_ids', 'entitled_type', 0);
                        }
                        if (!empty($rule->entitled_collection_ids))
                        {
                            $this->add_discount_details($inserted_id,
                                    $rule->entitled_collection_ids,
                                    'entitled_product_ids', 'entitled_type', 1);
                        }
                        if (!empty($rule->prerequisite_product_ids))
                        {
                            $this->add_discount_details($inserted_id,
                                    $rule->prerequisite_product_ids,
                                    'prerequisite_product_ids',
                                    'prerequisite_type', 0);
                        }
                        if (!empty($rule->prerequisite_collection_ids))
                        {
                            $this->add_discount_details($inserted_id,
                                    $rule->prerequisite_collection_ids,
                                    'prerequisite_product_ids',
                                    'prerequisite_type', 1);
                        }
                    }
                }
            }
        }
    }

    private function add_discount_details($discount_id, $array, $field)
    {
        foreach ($array as $ar) {
            $data['discount_master_id'] = $discount_id;
            $data[$field]               = $ar;
            $this->Discounts->save_details($data);
        }
    }

    /**
     * load shop credentials
     * @author Dhara
     * @param type $shop
     */
    private function prepare_api_call($shop, $access_token = NULL)
    {
        $access_token = ($access_token != '') ? $access_token : '';
        $data         = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => $access_token
        );
        $this->load->library('Shopify', $data);
    }

}
