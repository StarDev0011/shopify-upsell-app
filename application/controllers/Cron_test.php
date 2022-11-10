<?php

class Cron_test extends CI_Controller
{

    public $accessToken;
    public $shopObj;

    public function __construct()
    {
        ini_set('memory_limit', '-1');
        parent::__construct();
        $this->load->model('shop');
        $this->load->model('CronManage');
        $this->load->model('Discounts');
        $this->load->model('products');
        $this->load->library('session');
        $this->load->model('bundle');
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
        echo json_encode($response);
        header("Status: 200");
        header($_SERVER[ "SERVER_PROTOCOL" ] . " 200 Ok");
        header("Content-Type: application/json");
        header('Content-Length: ' . ob_get_length());
        ob_end_flush();
        ob_flush();
        flush();
    }

	public function check_data()
    {
		$this->output->enable_profiler(TRUE);
		$cronManage = $this->CronManage->find_not_running('install_process');
		$daata = $this->bundle->get(14452916278, '', 0, 'b.id,b.status,dm.discount_code,bundle_label,bundle_title,offer_description,discount_type');
		$this->load->view('admin/test', $daata);
		//prExit($daata);
	}

    public function send_mail()
    {
        $headers = "From: support@smartcartupsell.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message = "Hello World";
        $to      = 'tushar@topsinfosolutions.com';
        $to      = ['tushar@topsinfosolutions.com', 'dhara@topsinfosolutions.com'];
        echo 'To:<pre>' . print_r($to) . '</pre><br>';
        $from    = $this->config->item('admin_email');
        echo 'From:' . $from . '<br>';
        send_email($to, 'Smart Cart Upsell: Contact Us', $message, $from);

        /* if (mail($to, 'Smart Cart Upsell: Contact Us', $message, $headers))
          {
          echo "Mail accepted";
          } else
          {
          echo "Error: mail not sent";
          } */
    }

    public function get_install_process()
    {
        $cronManage = $this->CronManage->find_not_running('install_process');
        if ($cronManage)
        {
            $this->CronManage->update(['is_running' => 1, 'start_time' => date('Y-m-d H:i:s')],
                                                                               'install_process');
            $shops = $this->shop->get_not_processed_shop();
            if (!empty($shops))
            {
                $this->load->library('Shopify');
                foreach ($shops as $k => $row)
                {
//                    if ($row->domain == 'jaydeep-store.myshopify.com')
//                    {
                    if (!empty($row->shop_id) && $row->shop_id != 0)
                    {
                        $data              = array(
                            'API_KEY'      => $this->config->item('shopify_api_key'),
                            'API_SECRET'   => $this->config->item('shopify_secret'),
                            'SHOP_DOMAIN'  => $row->myshopify_domain,
                            'ACCESS_TOKEN' => $row->access_token
                        );
                        $this->accessToken = $row->access_token;
                        $this->shopify->setup($data);
                        $this->shop->update_shop(['is_data_added' => 1],
                                                 $row->domain);
                        $this->shopObj     = $this->shop->get_by_domain($row->domain);
                        try
                        {
                            $this->register_hooks($row->myshopify_domain);
                            $this->insert_collections($row->myshopify_domain);
//                            $this->register_scripts($row->myshopify_domain);
//                                $this->insert_products($row);
                            //$this->insert_price_rules($row);
                        }
                        catch (Exception $ex)
                        {
                            
                        }
                    }
//                    }
                }
            }
            $this->CronManage->update(['is_running' => 0, 'end_time' => date('Y-m-d H:i:s')],
                                                                             'install_process');
        }
    }

    /**
     * Insert collections
     * @param type $shop
     */
    public function insert_collections($shop = '')
    {
        sleep(1);
        $collectionsRes = $this->shopify->call(array('URL' => $shop . '/admin/custom_collections.json'),
                                               true);
        $shopData       = $this->shopObj;
        $shop_id        = $shopData->id;
        foreach ($collectionsRes->custom_collections as $collection)
        {
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

    public function add_shop_products()
    {
        $cronManage = $this->CronManage->find_not_running('insert_products');
        if ($cronManage)
        {
            $this->CronManage->update(['is_running' => 1, 'start_time' => date('Y-m-d H:i:s')],
                                                                               'insert_products');
            $shops = $this->shop->get_not_processed_shop_products();
            if (!empty($shops))
            {
                $this->load->library('Shopify');
                foreach ($shops as $k => $row)
                {
                    sleep(1);
//                    if ($row->domain == 'www.lifeflaskbottle.com')
//                    {
                    if (!empty($row->shop_id) && $row->shop_id != 0)
                    {
                        $data              = array(
                            'API_KEY'      => $this->config->item('shopify_api_key'),
                            'API_SECRET'   => $this->config->item('shopify_secret'),
                            'SHOP_DOMAIN'  => $row->myshopify_domain,
                            'ACCESS_TOKEN' => $row->access_token
                        );
                        $this->accessToken = $row->access_token;
                        $this->shopify->setup($data);
                        try
                        {
                            $this->insert_products($row);
                        }
                        catch (Exception $ex)
                        {
                            
                        }
                    }
                }
//                }
            }
            $this->CronManage->update(['is_running' => 0, 'end_time' => date('Y-m-d H:i:s')],
                                                                             'insert_products');
        }
    }

    /**
     * Insert products
     * @param type $shop
     */
    public function insert_products()
    {
        /*$shop_id = 14375810;
        $domain  = 'red-rain-buddha.myshopify.com';
        $shop    = 'red-rain-buddha.myshopify.com';

        $product_options   = '';
        $this->load->library('Shopify');
        $data              = array(
            'API_KEY'      => '92a16d3b23ee67843fea3c933fd739b1',
            'API_SECRET'   => 'bc26bca2101ad4a44956bcbf6d31f074',
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => '1b75c04ca8089be4284ae4fdb141a75a'
        );
        $this->accessToken = '1b75c04ca8089be4284ae4fdb141a75a';*/
        $shop_id = 14235500601;
        $domain  = 'jaydeep-store.myshopify.com';
        $shop    = 'jaydeep-store.myshopify.com';

        $product_options   = '';
        $this->load->library('Shopify');
        $data              = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
                            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            'ACCESS_TOKEN' => '1303b8eb5b1a37f6dea64fb490654afd'
        );
        $this->accessToken = '1303b8eb5b1a37f6dea64fb490654afd';
        $this->shopify->setup($data);

        $productCount = $this->shopify->call(array('URL' => $shop . '/admin/products/count.json'),
                                             true);
        if (!empty($productCount->count))
        {
            $pages = ceil($productCount->count / 250);
            for ($i = 0; $i < $pages; $i++)
            {
                $results = $this->shopify->call(array('URL' => $shop . '/admin/products.json?limit=250&page='.($i+1)),
                                         true);
                pr($results->products);
//                foreach ($results as $result)
//                {
//                    //pr(count($result->products));
//                }
            }
            echo $pages . ' | ';
        }
        prExit($productCount);
        $products = $this->shopify->call(array('URL' => $shop . '/admin/products.json?limit=250'),
                                         true);
        prExit(count($products->products));



        foreach ($products->products as $key => $prod)
        {
            $product_id      = $prod->id;
            $oldProduct      = $this->products->get_product_by_id($product_id);
            $options         = $prod->options;
            $product_options = '';
            if ($options)
            {
                foreach ($options as $opt)
                {
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
            }
            else
            {
                $this->products->update_product($productData, $product_id);
            }

            $variants = $prod->variants;
            if ($variants)
            {
                foreach ($variants as $var)
                {
                    $oldVairant   = $this->products->get_product_variant_by_id($var->id);
                    $variantImage = isset($prod->image->src) ? $prod->image->src : '';
                    $varData      = array();
                    $varData      = array(
                        'variant_title'        => $var->title,
                        'price'                => $var->price,
                        'sku'                  => $var->sku,
                        'product_id'           => $var->product_id,
                        'image_id'             => !empty($var->image_id) ? $var->image_id : '',
                        'is_image_processed'   => (empty($variantImage)) ? 0 : 1,
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
                    }
                    else
                    {
                        $this->products->update_variant($varData, $var->id);
                    }
                }
            }
        }
        $this->shop->update_shop(['is_products_added' => 1], $domain);
    }

    public function proccess_collection()
    {
        $cronManage = $this->CronManage->find_not_running('process_collection',
                                                          5);
        if ($cronManage)
        {
            $products = $this->products->get_not_processed_collection();
            if (!empty($products))
            {
                $this->CronManage->update(['is_running' => 1, 'start_time' => date('Y-m-d H:i:s')],
                                                                                   'process_collection');
                $this->load->library('Shopify');
                foreach ($products as $k => $row)
                {
                    sleep(1);
                    $product_id     = $row->product_id;
                    $shop_data      = array(
                        'API_KEY'      => $this->config->item('shopify_api_key'),
                        'API_SECRET'   => $this->config->item('shopify_secret'),
                        'SHOP_DOMAIN'  => $row->myshopify_domain,
                        'ACCESS_TOKEN' => $row->access_token
                    );
                    $this->shopify->setup($shop_data);
                    $collectionsRes = $this->shopify->call(array('URL' => $row->myshopify_domain . '/admin/collects.json?product_id=' . $product_id . ''),
                                                           true);
                    $collection     = $collectionsRes->collects;
                    if ($collection)
                    {
                        $varData = array();
                        $this->products->delete_product_collection($product_id);
                        foreach ($collection as $collects)
                        {
                            $varData[] = array(
                                'product_id'    => $product_id,
                                'collection_id' => $collects->collection_id,
                            );
                        }
                        $this->products->add_collections_batch($varData);
                    }
                    $this->products->update_product(['is_collection_processed' => 1],
                                                    $product_id);
                }
            }
            $this->CronManage->update(['is_running' => 0, 'end_time' => date('Y-m-d H:i:s')],
                                                                             'process_collection');
        }
    }

    public function proccess_variant_images()
    {
       $products = $this->products->get_not_processed_variants();
       if (!empty($products))
        {
			 echo count($products);
                foreach ($products as $k => $row)
                {
					try
                {
					pr($row);
                    sleep(1);
                    $product_id   = $row->product_id;
                    $variantImage = $row->image;
                    if (/* empty($row->image) && */!empty($row->image_id))
                    {
                        //echo $row->myshopify_domain . '<br>';
                        $this->load->library('Shopify');
                        $shop_data = array(
                            'API_KEY'      => $this->config->item('shopify_api_key'),
                            'API_SECRET'   => $this->config->item('shopify_secret'),
                            'SHOP_DOMAIN'  => $row->myshopify_domain,
                            'ACCESS_TOKEN' => $row->access_token
                        );
                        $this->shopify->setup($shop_data);
                        $imageData = $this->shopify->call(array('URL' => $row->myshopify_domain . '/admin/products/' . $product_id . '/images/' . $row->image_id . '.json'),
                                                          true);
                        if (!empty($imageData->image))
                        {
                            $variantImage = $imageData->image->src;
                        }
                    }
                    $this->products->update_variant(['is_image_processed' => 1,
                        'image' => $variantImage], $row->variant_id);
                        }
                catch (Exception $ex)
                {
					echo 'error caught';
					pr($row);
					pr($ex);
                    $this->CronManage->update(['is_running' => 0, 'end_time' => date('Y-m-d H:i:s')],
                                                                                     'proccess_variant_images');
                }
                }
                $this->CronManage->update(['is_running' => 0, 'end_time' => date('Y-m-d H:i:s')],
                                                                                 'proccess_variant_images');
            
        }
    }
    
    public function check_payment_status(){
		$this->load->library('Shopify');
		$myshopify_domain = 'leandrews-drop-shop.myshopify.com';
		$access_token = '0cf44048b27cd3852ad72ab791416786';
		$data = array(
			'API_KEY'      => $this->config->item('shopify_api_key'),
			'API_SECRET'   => $this->config->item('shopify_secret'),
			'SHOP_DOMAIN'  => $myshopify_domain,
			'ACCESS_TOKEN' => $access_token
		);
		$this->shopify->setup($data);
		
		$shopData   = $this->shopify->call(array('URL' => $myshopify_domain . '/admin/shop.json'),
                                                           true);
		$shopData   = $shopData->shop;
		prExit($shopData);
	}

    public function insert_price_rules($shop)
    {
        $shop_id  = $shop->shop_id;
        $domain   = $shop->domain;
        $shop     = $shop->domain;
        $response = $this->shopify->call(array('URL' => $shop . '/admin/price_rules.json'),
                                         true);

        if (isset($response->price_rules))
        {
            foreach ($response->price_rules as $rule)
            {
                $min_req    = 0;
                $applies_to = 0;
                if (!empty($rule->prerequisite_subtotal_range))
                {
                    $min_req = 1;
                }
                elseif (!empty($rule->prerequisite_quantity_range))
                {
                    $min_req = 2;
                }

                if (!empty($rule->entitled_product_ids))
                {
                    $applies_to = 2;
                }
                elseif (!empty($rule->entitled_collection_ids))
                {
                    $applies_to = 1;
                }

                $value_type = $rule->value_type;
                if ($rule->value_type == 'percentage')
                {
                    if ((!empty($rule->entitled_product_ids) || !empty($rule->entitled_collection_ids)) && (!empty($rule->prerequisite_product_ids) || !empty($rule->prerequisite_collection_ids)))
                    {
                        $value_type = 'bxgy';
                    }
                    else if (($rule->value == -100))
                    {
                        $value_type = 'free_shipping';
                    }
                    else
                    {
                        $value_type = 'percentage';
                    }
                }
                $data[ 'discount_id' ]         = $rule->id;
                $data[ 'shop_id' ]             = $shop_id;
                $data[ 'discount_code' ]       = $rule->title;
                $data[ 'value_type' ]          = $value_type;
                $data[ 'value' ]               = $rule->value;
                $data[ 'minimum_requirement' ] = $min_req;
                $data[ 'applies_to' ]          = $applies_to;
                $inserted_id                   = $this->Discounts->save($data);
                if (!empty($inserted_id))
                {
                    if ($value_type == 'bxgy')
                    {
                        $this->Discounts->delete_discount_details($inserted_id);
                        if (!empty($rule->entitled_product_ids))
                        {
                            $this->add_discount_details($inserted_id,
                                                        $rule->entitled_product_ids,
                                                        'entitled_product_ids',
                                                        'entitled_type', 0);
                        }
                        if (!empty($rule->entitled_collection_ids))
                        {
                            $this->add_discount_details($inserted_id,
                                                        $rule->entitled_collection_ids,
                                                        'entitled_product_ids',
                                                        'entitled_type', 1);
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

    /**
     * Registers webhook
     * @param type $shop
     */
    public function register_hooks($shop)
    {

        $shop_api_data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            //                    'ACCESS_TOKEN' => $this->session->userdata('access_token')
            'ACCESS_TOKEN' => $this->accessToken
        );
        $this->load->library('Shopify', $shop_api_data);

        $hooks = $this->shopify->call(array('METHOD' => 'GET', 'URL' => $shop . '/admin/webhooks.json'));
        //prExit($hooks);
        //Delete Webhooks
        /* $hooks = $hooks->webhooks;
          foreach ($hooks as $hk){
          $src = site_url('webhook/prodCreateHook');
          $hook_data = array(
          "webhook" => array(
          "topic" => "products/create",
          "address" => $src,
          "format" => "json"
          ));
          try
          {
          $script = $this->shopify->call(array('METHOD' => 'DELETE', 'URL' => $shop . '/admin/webhooks/'.$hk->id.'.json'));
          echo $hk->id.'= deleted<br>';
          } catch (Exception $e)
          {

          }
          } */

        /** 1. App Uninstall * */
        $src       = site_url('webhook/uninstallHook');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "app/uninstalled",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }

        /** 2. Create Product Hook * */
        $src       = site_url('webhook/prodCreateHook');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "products/create",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }
        sleep(1);
        /** 3. Delete Product Hook * */
        $src       = site_url('webhook/prodDeleteHook');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "products/delete",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }

        /** 4. Update Product Hook * */
        $src       = site_url('webhook/prodUpdateHook');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "products/update",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }
        sleep(1);
        /** 5. Cart Create * */
        $src       = site_url('webhook/cartCreateHook');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "carts/create",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }

        $src       = site_url('webhook/cartUpdateHook');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "carts/update",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }

        sleep(1);
        /** 6. Orders Create * */
        $src       = site_url('webhook/orderCreateHook');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "orders/create",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }

        /** 7. Create Collections Hook * */
        $src       = site_url('webhook/insertCollections');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "collections/create",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }

        sleep(1);
        /** 8. Update Collections Hook * */
        $src       = site_url('webhook/updateCollections');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "collections/update",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }
        /** 9. Delete Collections Hook * */
        $src       = site_url('webhook/deleteCollections');
        $hook_data = array(
            "webhook" => array(
                "topic"   => "collections/delete",
                "address" => $src,
                "format"  => "json"
        ));
        try
        {
            $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/webhooks.json',
                'DATA'   => $hook_data));
        }
        catch (Exception $e)
        {
            
        }
    }

    public function register_scripts($shop)
    {
        //echo DEPLOYMENT;
        if (DEPLOYMENT == 0)
        {
//            echo 'xyz';
            $src = $this->config->item('assets_base_url') . 'front/front_upsell.js';
        }
        else if (DEPLOYMENT == 2)
        {
            if ($_SERVER[ 'HTTP_HOST' ] == 'smartcartupsellbundle.com')
            {
//                echo 'aac';
                $src = $this->config->item('assets_base_url') . 'front/front_client_live.js';
            }
            else
            {
//                echo 'def';
                $src = $this->config->item('assets_base_url') . 'front/front_client.js';
            }
        }
        else
        {
            if ($this->config->item('base_name') == '/qa/shopify/tops-upsell/')
            {
                $src = $this->config->item('assets_base_url') . 'front/front_live.js';
            }
            else
            {
                $src = $this->config->item('assets_base_url') . 'front/front_live_dev.js';
            }
        }
        $shop_api_data = array(
            'API_KEY'      => $this->config->item('shopify_api_key'),
            'API_SECRET'   => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN'  => $shop,
            //                    'ACCESS_TOKEN' => $this->session->userdata('access_token')
            'ACCESS_TOKEN' => $this->accessToken
        );
        $this->load->library('Shopify', $shop_api_data);

        $res = $this->shopify->call(array('URL' => $shop . '/admin/script_tags.json'),
                                    true);

        if (!empty($res))
        {
            if (empty($res->script_tags[ 0 ]))
            {
                $data = array(
                    "script_tag" => array(
                        "event" => "onload",
                        "src"   => "$src"
                ));
                try
                {
                    $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/script_tags.json',
                        'DATA'   => $data));
                }
                catch (Exception $e)
                {
                    
                }
            }
        }
        elseif (isset($res->script_tags[ 0 ]->src) and $res->script_tags[ 0 ]->src != $src)
        {
            $id     = $res->script_tags[ 0 ]->id;
            $script = $this->shopify->call(array('METHOD' => 'DELETE', 'URL' => $shop . '/admin/script_tags/' . $id . '.json'));
            $data   = array(
                "script_tag" => array(
                    "event" => "onload",
                    "src"   => "$src"
            ));
            try
            {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/script_tags.json',
                    'DATA'   => $data));
            }
            catch (Exception $e)
            {
                
            }
        }
        elseif (empty($res) && empty($res->script_tags[ 0 ]->src))
        {
            $data = array(
                "script_tag" => array(
                    "event" => "onload",
                    "src"   => "$src"
            ));
            try
            {
                $script = $this->shopify->call(array('METHOD' => 'POST', 'URL'    => $shop . '/admin/script_tags.json',
                    'DATA'   => $data));
            }
            catch (Exception $e)
            {
                
            }
        }
        /* else {
          $data = array(
          "script_tag" => array(
          "event" => "onload",
          "src" => "$src"
          ));
          try {
          $script = $this->shopify->call(array('METHOD' => 'POST', 'URL' => $shop . '/admin/script_tags.json', 'DATA' => $data));
          } catch (Exception $e) {

          }
          } */
    }

    /**
     * Add/update discount code of shops
     * @author Dhara 
     * @param type $shopId
     * @return type
     */
    public function get_discount_codes()
    {
        $cronManage = $this->CronManage->find_not_running('insert_discount_code');
        if ($cronManage)
        {
            $this->CronManage->update(['is_running' => 1, 'start_time' => date('Y-m-d H:i:s')],
                                                                               'insert_discount_code');
            $shops = $this->shop->get_shops();
            if (!empty($shops))
            {
                $this->load->library('Shopify');
                foreach ($shops as $k => $row)
                {
//                //dev-upsell.myshopify.com
//                if ($row->domain == 'jaydeep-store.myshopify.com')
//                {
                    if (!empty($row->shop_id) && $row->shop_id != 0)
                    {
                        $data = array(
                            'API_KEY'      => $this->config->item('shopify_api_key'),
                            'API_SECRET'   => $this->config->item('shopify_secret'),
                            'SHOP_DOMAIN'  => $row->myshopify_domain,
                            'ACCESS_TOKEN' => $row->access_token
                        );
                        $this->shopify->setup($data);
                        try
                        {
                            sleep(1);
                            $response = $this->shopify->call(array('URL' => $row->myshopify_domain . '/admin/price_rules.json'),
                                                             true, $data);
                            if (isset($response->price_rules))
                            {
                                $discount_array = [];
                                foreach ($response->price_rules as $rule)
                                {
//                                    if ($rule->id != '379091681337')
//                                    {
//                                        continue;
//                                    }
                                    $min_req    = 0;
                                    $applies_to = 0;
                                    if (!empty($rule->prerequisite_subtotal_range))
                                    {
                                        $min_req = 1;
                                    }
                                    elseif (!empty($rule->prerequisite_quantity_range))
                                    {
                                        $min_req = 2;
                                    }

                                    if (!empty($rule->entitled_product_ids))
                                    {
                                        $applies_to = 2;
                                    }
                                    elseif (!empty($rule->entitled_collection_ids))
                                    {
                                        $applies_to = 1;
                                    }

                                    $value_type = $rule->value_type;
                                    if ($rule->value_type == 'percentage')
                                    {
                                        if ((!empty($rule->entitled_product_ids) || !empty($rule->entitled_collection_ids) || !empty($rule->entitled_variant_ids)) && (!empty($rule->prerequisite_product_ids) || !empty($rule->prerequisite_collection_ids)) || !empty($rule->prerequisite_variant_ids))
                                        {
                                            $value_type = 'bxgy';
                                        }
                                        else if (($rule->value == -100))
                                        {
                                            $value_type = 'free_shipping';
                                        }
                                        else
                                        {
                                            $value_type = 'percentage';
                                        }
                                    }
                                    $save[ 'discount_id' ]         = $rule->id;
                                    $save[ 'shop_id' ]             = $row->shop_id;
                                    $save[ 'discount_code' ]       = $rule->title;
                                    $save[ 'value_type' ]          = $value_type;
                                    $save[ 'value' ]               = $rule->value;
                                    $save[ 'minimum_requirement' ] = $min_req;
                                    $save[ 'applies_to' ]          = $applies_to;
                                    $discount_record               = $this->Discounts->get_discount_by_id($rule->id);
                                    if ($discount_record)
                                            $save[ 'id' ]                  = $discount_record->id;
                                    $inserted_id                   = $this->Discounts->save($save);
                                    if (!empty($inserted_id))
                                    {
                                        $discount_array[] = $rule->id;
                                        if ($value_type == 'bxgy')
                                        {
                                            $this->Discounts->delete_discount_details($inserted_id);
                                            if (!empty($rule->entitled_product_ids))
                                            {
                                                $this->add_discount_details($inserted_id,
                                                                            $rule->entitled_product_ids,
                                                                            'entitled_product_ids',
                                                                            'entitled_type',
                                                                            0);
                                            }
                                            if (!empty($rule->entitled_collection_ids))
                                            {
                                                $this->add_discount_details($inserted_id,
                                                                            $rule->entitled_collection_ids,
                                                                            'entitled_product_ids',
                                                                            'entitled_type',
                                                                            1);
                                            }
                                            if (!empty($rule->prerequisite_product_ids))
                                            {
                                                $this->add_discount_details($inserted_id,
                                                                            $rule->prerequisite_product_ids,
                                                                            'prerequisite_product_ids',
                                                                            'prerequisite_type',
                                                                            0);
                                            }
                                            if (!empty($rule->prerequisite_collection_ids))
                                            {
                                                $this->add_discount_details($inserted_id,
                                                                            $rule->prerequisite_collection_ids,
                                                                            'prerequisite_product_ids',
                                                                            'prerequisite_type',
                                                                            1);
                                            }

                                            //variant based
                                            if (!empty($rule->entitled_variant_ids))
                                            {
                                                $entitled_product_ids = $this->products->get_products_via_variant($rule->entitled_variant_ids);
                                                $entitled_product_ids = get_array_columns($entitled_product_ids,
                                                                                          'product_id');
                                                $this->add_discount_details($inserted_id,
                                                                            $entitled_product_ids,
                                                                            'entitled_product_ids',
                                                                            'entitled_type',
                                                                            0,
                                                                            $rule->entitled_variant_ids,
                                                                            'entitled_variant_ids');
                                            }
                                            if (!empty($rule->prerequisite_variant_ids))
                                            {
                                                $prerequisite_variant_ids = $this->products->get_products_via_variant($rule->prerequisite_variant_ids);
                                                $prerequisite_variant_ids = get_array_columns($prerequisite_variant_ids,
                                                                                              'product_id');
                                                $this->add_discount_details($inserted_id,
                                                                            $prerequisite_variant_ids,
                                                                            'prerequisite_product_ids',
                                                                            'prerequisite_type',
                                                                            0,
                                                                            $rule->prerequisite_variant_ids,
                                                                            'prerequisite_variant_ids');
                                            }
                                        }
                                    }
                                }
                                if (!empty($discount_array))
                                {
                                    $stored_discounts       = $this->Discounts->get_discounts($row->shop_id);
                                    $stored_discounts_array = [];
                                    foreach ($stored_discounts as $sd)
                                    {
                                        $stored_discounts_array[] = $sd->discount_id;
                                    }
                                    $removed_discounts = array_diff($stored_discounts_array,
                                                                    $discount_array);
                                    if (!empty($removed_discounts))
                                    {
                                        foreach ($removed_discounts as $rm)
                                        {
                                            $this->Discounts->delete($rm);
                                        }
                                    }
                                }
                            }
                            unset($shops[ $k ]);
                        }
                        catch (Exception $ex)
                        {
                            //pr($ex);
                            //unset($shops[$k]);
                            //$this->execute_codes($shops);
                        }
                    }
                }
//            }
            }
            $this->CronManage->update(['is_running' => 0, 'end_time' => date('Y-m-d H:i:s')],
                                                                             'insert_discount_code');
        }
    }

    private function add_discount_details($discount_id, $array, $field,
                                          $type_name, $type_value,
                                          $variants = array(),
                                          $variant_name = '')
    {
        foreach ($array as $key => $ar)
        {
            $data[ 'discount_master_id' ] = $discount_id;
            $data[ $field ]               = $ar;
            $data[ $type_name ]           = $type_value;
            if (!empty($variants))
            {
                $data[ $variant_name ] = $variants[ $key ];
            }
            $this->Discounts->save_details($data);
        }
    }

    public function get_product_collections()
    {
        $shops = $this->shop->get_shops();
        if (!empty($shops))
        {
            $this->load->library('Shopify');
            foreach ($shops as $k => $row)
            {
                sleep(1);
//                //dev-upsell.myshopify.com
//                if ($row->domain == 'jaydeep-store.myshopify.com') {
                $data = array(
                    'API_KEY'      => $this->config->item('shopify_api_key'),
                    'API_SECRET'   => $this->config->item('shopify_secret'),
                    'SHOP_DOMAIN'  => $row->myshopify_domain,
                    'ACCESS_TOKEN' => $row->access_token
                );
                $this->shopify->setup($data);
                try
                {
                    $response = $this->shopify->call(array('URL' => $row->myshopify_domain . '/admin/custom_collections.json'),
                                                     true, $data);
                    if (!empty($response->custom_collections))
                    {
                        $varData = [];
                        foreach ($response->custom_collections as $collection)
                        {
                            $collectionsData = array(
                                'collections_slug' => $collection->handle,
                                'shop_id'          => $row->shop_id,
                                'collections_id'   => $collection->id,
                                'title'            => $collection->title,
                                'body_html'        => $collection->body_html,
                                'sort_order'       => $collection->sort_order,
                            );
                            $check           = $this->dbqueries->find('collections',
                                                                      ['collections_id' => $collection->id]);
                            if ($check)
                            {
                                $this->products->update_collections($collectionsData,
                                                                    $collection->id);
                            }
                            else
                            {
                                $this->products->add_collections($collectionsData);
                            }
                            $collectionsRes = $this->shopify->call(array('URL' => $row->myshopify_domain . '/admin/collects.json?collection_id=' . $collection->id . ''),
                                                                   true, $data);
                            $this->dbqueries->delete('product_collections',
                                                     ['collection_id' => $collection->id]);
                            if (!empty($collectionsRes->collects))
                            {
                                foreach ($collectionsRes->collects as $collects)
                                {
                                    $varData[] = array(
                                        'product_id'    => $collects->product_id,
                                        'collection_id' => $collects->collection_id
                                    );
                                }
                            }
                        }
                        if (!empty($varData))
                        {
                            $this->products->add_collections_batch($varData);
                        }
                    }
                    unset($shops[ $k ]);
                }
                catch (Exception $ex)
                {
                    //pr($ex);
                    //unset($shops[$k]);
                    //$this->execute_codes($shops);
                }
            }
//            }
        }
    }

    /**
     * update status regarding shop status and payment status every 10 min
     */
    public function update_shop_status()
    {
        $shops = $this->shop->get_shops();
        if (!empty($shops))
        {
            $this->load->library('Shopify');
            pr($shops);
            foreach ($shops as $k => $row)
            {
                if (!empty($row->shop_id) && $row->shop_id != 0)
                {
//                    if ($row->domain == 'jaydeep-store.myshopify.com') {
                    $data = array(
                        'API_KEY'      => $this->config->item('shopify_api_key'),
                        'API_SECRET'   => $this->config->item('shopify_secret'),
                        'SHOP_DOMAIN'  => $row->myshopify_domain,
                        'ACCESS_TOKEN' => $row->access_token
                    );
                    $this->shopify->setup($data);
                    try
                    {
                        sleep(1);
                        $shopData   = $this->shopify->call(array('URL' => $row->myshopify_domain . '/admin/shop.json'),
                                                           true);
                        $shopData   = $shopData->shop;
                        $updateData = [];
                        if ($row->charge_id != '')
                        {
                            $chargeData                    = $this->shopify->call(array(
                                'URL' => $row->myshopify_domain . '/admin/api/2019-10/recurring_application_charges/' . $row->charge_id . '.json'),
                                                                                  true);
                            $chargeData                    = $chargeData->recurring_application_charge;
                            $updateData[ 'charge_status' ] = $chargeData->status;
                        }
                        $updateData[ 'shop_status' ] = $shopData->plan_name;
                        $this->shop->update_shop($updateData, $row->domain);
                    }
                    catch (Exception $ex)
                    {
						//pr($row,'Catch=');
						//echo $ex->getCode();
                        //prExit($ex);
                    }
//                    }
                }
            }
        }
    }

}
