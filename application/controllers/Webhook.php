<?php

/**
 * 
 */
class Webhook extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('shop');
        $this->load->model('bundle');
        $this->load->model('products');
        $this->load->model('Discounts');
        $this->load->model('cart');
        $this->load->model('orders');
        $this->load->model('Setting');
    }
    
    /**
     * used when uninstalled app
     * @author Dhara
     */
    public function uninstallHook() {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        $shop = json_decode($data);
        if ($shop) {
            $shop_id = $shop->id;

            /* * * * * * * *
                Mailchimp: For uninstallers
            * * * * * * * */
            $list = "9704ede958";
            $apikey = "24e1f883ec9845b812f915a87760220b-us1";
            $email = strtolower($shop->email);
            $data = json_encode([
                "tags" => [
                    [
                        "name" => "Uninstaller",
                        "status" => "active"
                    ]
                ]
            ]);
            $headers = [
                "Authorization:" . sprintf('Basic %s', base64_encode('scub:' . $apikey ))
            ];

            $ch = curl_init("https://us1.api.mailchimp.com/3.0/lists/$list/members/" . md5($email) . "/tags");

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $res = curl_exec($ch);
            var_dump($res);

            curl_close($ch);
            /* * * * * * * *
                End Mailchimp
            * * * * * * * */

            $this->shop->delete($shop_id);

            /*             * Bundles* */
            $bundles = $this->bundle->get($shop_id, '', 0, 'b.id');
            if ($bundles) {
                foreach ($bundles as $bundle) {
                    $this->bundle->delete($bundle->id);
                }
            }

            /* Products */
            $products = $this->products->get($shop_id);
            if ($products) {
                foreach ($products as $product) {
                    $this->products->delete_product($product->product_id);
                }
            }

            $this->products->delete_collection_by_shop($shop_id);
            $this->orders->delete_orders($shop_id);
            $this->Setting->delete_setting($shop_id);

            /* Delete Discounts */
            $discounts = $this->Discounts->get_discounts($shop_id);
            if ($discounts) {
                foreach ($discounts as $dis) {
                    $this->Discounts->delete_discount($dis->id);
                }
            }
        }
    }

    public function removeShop() {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        $shop = json_decode($data);
        if ($shop)
        {
            $shop_id = $shop->shop_id;
        }
    }

    /**
     * insert collection
     * @author Dhara
     */
    public function insertCollections()
    {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        //file_put_contents('insert_collections.json', $data);

        $shop     = $hmac_domain;
        $shopInfo = $this->shop->get_by_domain($shop);
        if ($shopInfo)
        {
            $shop_id = $shopInfo->shop_id;
            $domain  = $shopInfo->domain;
        }
        if ($shop_id)
        {
            $smart_collections = json_decode($data);
            if ($smart_collections)
            {
                $collectionsData = array(
                    'collections_slug' => $smart_collections->handle,
                    'shop_id'          => $shop_id,
                    'collections_id'   => $smart_collections->id,
                    'title'            => $smart_collections->title,
                    'body_html'        => $smart_collections->body_html,
                    'sort_order'       => $smart_collections->sort_order,
                    'rules'            => json_encode($smart_collections->rules),
                );
                //$this->products->add_collections($collectionsData);
                $isExist         = $this->products->get_collection_record($smart_collections->id);
                if (!empty($isExist))
                        $this->products->add_collections($collectionsData);
                else
                        $this->products->update_collections($collectionsData,
                            $smart_collections->id);
            }
        }
    }

    /**
     * Update collection
     * @author Dhara
     */
    public function updateCollections()
    {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        //file_put_contents('update_collections.json', $data);
        //$data = file_get_contents('update_collections.json');

        $shop     = $hmac_domain;
        $shopInfo = $this->shop->get_by_domain($shop);
        if ($shopInfo)
        {
            $shop_id = $shopInfo->shop_id;
            $domain  = $shopInfo->domain;
        }

        if ($shop_id)
        {
            $smart_collections = json_decode($data);
            if ($smart_collections)
            {
                $collectionsData = array(
                    'collections_slug' => $smart_collections->handle,
                    'shop_id'          => $shop_id,
                    'collections_id'   => $smart_collections->id,
                    'title'            => $smart_collections->title,
                    'body_html'        => $smart_collections->body_html,
                    'sort_order'       => $smart_collections->sort_order,
//                    'rules'            => json_encode($smart_collections->rules),
                );
                $this->products->update_collections($collectionsData,
                        $smart_collections->id);
            }
        }
    }

    /**
     * Delete collections
     * @author Dhara
     */
    public function deleteCollections()
    {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        $smart_collections = json_decode($data);
        if ($smart_collections)
        {
            $collections_id = $smart_collections->id;

            $this->products->delete_collections($collections_id);
        }
    }

    /**
     * Product create 
     * @author Dhara
     */
    public function prodCreateHook()
    {

        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        $shop     = $hmac_domain;
        $shopInfo = $this->shop->get_by_domain($shop);
        if ($shopInfo)
        {
            $shop_id      = $shopInfo->shop_id;
            $domain       = $shopInfo->domain;
            $access_token = $shopInfo->access_token;
        }

        if ($shop_id)
        {

            $product = json_decode($data);
            if ($product)
            {
                $product_id      = $product->id;
                $productData     = array();
                $product_options = '';
                $oldProduct      = $this->products->get_product_by_id($product_id);
                $options         = $product->options;
                if ($options)
                {
                    foreach ($options as $opt) {
                        if (!empty($opt->name))
                                $product_options .= $opt->name . '|';
                    }
                }

                $shop_data = array(
                    'API_KEY'      => $this->config->item('shopify_api_key'),
                    'API_SECRET'   => $this->config->item('shopify_secret'),
                    'SHOP_DOMAIN'  => $shop,
                    'ACCESS_TOKEN' => $access_token
                );

                $this->load->library('Shopify', $shop_data);

                $tags        = $product->tags;
                $productData = array(
                    'title'                   => $product->title,
                    'image'                   => !empty($product->image) ? $product->image->src : '',
                    'product_link'            => 'https://' . $hmac_domain . '/products/' . $product->handle,
                    'product_slug'            => $product->handle,
                    'product_options'         => trim($product_options, '|'),
                    'is_collection_processed' => 0,
                    'tags'                    => trim($tags),
                );
                if (empty($oldProduct))
                {
                    $otherData   = array(
                        'shop_id'    => $shop_id,
                        'product_id' => $product_id);
                    $productData = array_merge($productData, $otherData);
                    $this->products->add($productData);
                } else
                {
                    $this->products->update_product($productData, $product_id);
                }

                $variants = $product->variants;
                if ($variants)
                {
                    $variantImage = !empty($product->image) ? $product->image->src : '';
                    foreach ($variants as $var) {
                        $varData    = array();
                        $varImageId = !empty($var->image_id) ? $var->image_id : '';
                        $varData    = array(
                            'variant_id'           => $var->id,
                            'variant_title'        => $var->title,
                            'price'                => $var->price,
                            'sku'                  => $var->sku,
                            'image_id'             => $varImageId,
                            'is_image_processed'   => (empty($varImageId)) ? 0 : 1,
                            'image'                => $variantImage,
                            'product_id'           => $var->product_id,
                            'inventory'            => $var->inventory_quantity,
                            'inventory_management' => $var->inventory_management,
                            'inventory_policy'     => $var->inventory_policy,
                        );
                        $this->products->add_variant($varData);
                    }/* foreach */
                }/* $variants */
            }/* $product */
        }/* $shopInfo */
    }

    /**
     * Product delete
     * @author Dhara
     */
    public function prodDeleteHook()
    {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        $product = json_decode($data);
        if ($product)
        {
            $product_id = $product->id;

            $this->products->delete_product($product_id);
        }
    }

    /**
     * Product update
     * @author Dhara
     */
    public function prodUpdateHook()
    {

        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        //file_put_contents('product.json', $data);

        $shop     = $hmac_domain;
        $shopInfo = $this->shop->get_by_domain($shop);

        //file_put_contents('shopInfo.json', json_encode($shopInfo));

        if ($shopInfo)
        {
            $shop_id      = $shopInfo->shop_id;
            $domain       = $shopInfo->domain;
            $access_token = $shopInfo->access_token;
        }

        $product = json_decode($data);
        if ($product)
        {
            $product_id  = $product->id;
            $productData = array();
            $options     = $product->options;

            if ($options)
            {
                $product_options = '';
                foreach ($options as $opt) {
                    if (!empty($opt->name))
                            $product_options .= $opt->name . '|';
                }
            }

            $shop_data = array(
                'API_KEY'      => $this->config->item('shopify_api_key'),
                'API_SECRET'   => $this->config->item('shopify_secret'),
                'SHOP_DOMAIN'  => $shop,
                'ACCESS_TOKEN' => $access_token
            );

            $this->load->library('Shopify', $shop_data);

            $tags = $product->tags;

            $productData = array(
                'product_id'              => $product_id,
                'title'                   => $product->title,
                'image'                   => !empty($product->image) ? $product->image->src : '',
                'product_link'            => 'https://' . $hmac_domain . '/products/' . $product->handle,
                'product_slug'            => $product->handle,
                'product_options'         => trim($product_options, '|'),
                'is_collection_processed' => 0,
                'tags'                    => trim($tags),
            );
            $this->products->update_product($productData, $product_id);
            $variants    = $product->variants;
            if ($variants)
            {
                $this->products->delete_variants($product_id);
                $variantImage = !empty($product->image) ? $product->image->src : '';
                foreach ($variants as $var) {
                    $varImageId     = !empty($var->image_id) ? $var->image_id : '';
                    $isImageProcess = 0;
                    if (!empty($varImageId))
                    {
                        $isImageProcess = 0;
                    } elseif (!empty($variantImage))
                    {
                        $isImageProcess = 1;
                    }
                    $varData    = array(
                        'variant_title'        => $var->title,
                        'price'                => $var->price,
                        'sku'                  => $var->sku,
                        'image_id'             => $varImageId,
                        'is_image_processed'   => $isImageProcess,
                        'image'                => $variantImage,
                        'product_id'           => $var->product_id,
                        'inventory'            => $var->inventory_quantity,
                        'inventory_management' => $var->inventory_management,
                        'inventory_policy'     => $var->inventory_policy,
                    );
                    $oldVairant = $this->products->get_product_variant_by_id($var->id);
                    if (empty($oldVairant))
                    {
                        $varData['variant_id'] = $var->id;
                        $this->products->add_variant($varData);
                    } else
                    {
                        $this->products->update_variant($varData, $var->id);
                    }
                }/* foreach */
            }/* $variants */
        }
    }

    /**
     * NOT IN USE
     */
    public function cartCreateHook()
    {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        //file_put_contents('cart.json', $data);
        //$data = file_get_contents('cart.json');

        $cart = json_decode($data);

        $created_at = date('Y-m-d H:i:s');
        $token      = $cart->token;

        if ($cart)
        {
            foreach ($cart->line_items as $item) {
                //get_bundle_by_prod
                $bundle = $this->bundle->get_bundle_by_prod($item->product_id,
                        'p');
                if (isset($bundle[0]))
                {
                    $cartData = array(
                        'product_id'   => $item->product_id,
                        'bundle_id'    => $bundle[0]->bundle_id,
                        'price'        => $item->price,
                        'created_date' => $created_at,
                        'token'        => $token,
                    );
                }
                //$this->cart->insert($cartData);
            }/* foreach */
        }
    }

    /**
     * NOT IN USE
     */
    public function cartUpdateHook()
    {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        $cart       = json_decode($data);
        $created_at = date('Y-m-d H:i:s');
        $token      = $cart->token;

        if ($cart)
        {
            foreach ($cart->line_items as $item) {
                $existCart = $this->cart->get_cart_product($item->product_id,
                        $token);
                if ($existCart)
                {
                    
                } else
                {
                    $bundle = $this->bundle->get_bundle_by_prod($item->product_id,
                            'p');
                    /* $cartData = array(
                      'product_id'   => $item->product_id,
                      'bundle_id'    => $bundle[ 0 ]->bundle_id,
                      'price'        => $item->price,
                      'created_date' => $created_at,
                      'token'        => $token,
                      ); */
                    //$this->cart->insert($cartData);
                }
            }/* foreach */
        }
    }

    public function orderCreateHook()
    {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        $shopInfo = $this->shop->get_by_domain($hmac_domain);
        $shop_id  = $shopInfo->shop_id;

        //$data = file_get_contents('order.json');
        $order      = json_decode($data);
        $created_at = date('Y-m-d H:i:s');

        /* array of all bundles */
        $allsbundles     = $this->bundle->get($shop_id, '', 0, 'b.id');
        $newbundle_array = array();
        foreach ($allsbundles as $key => $allsbundle) {
            array_push($newbundle_array, $allsbundle->id);
        }
        $Orderexist = $this->orders->check_by_id($order->id);
        if (empty($Orderexist))
        {
            //Add master table data
            $orderContent                      = [];
            $orderContent['shop_id']         = $shop_id;
            $orderContent['order_id']        = $order->id;
            $orderContent['order_number']    = $order->order_number;
            $orderContent['total_price']     = $order->total_price;
            $orderContent['subtotal_price']  = $order->subtotal_price;
            $orderContent['total_tax']       = $order->total_tax;
            $orderContent['total_discounts'] = $order->total_discounts;
            $orderContent['confirmed']       = ($order->confirmed == false) ? 0 : 1;
            $orderContent['created_date']    = save_db_date();
            $order_id                          = $this->orders->add($orderContent);
            $newbundle_ids                     = array();
            //Add child order table data
            $target_product = NULL;
            foreach ($order->line_items as $key => $item) {
                $orderDetails                        = [];
                $orderDetails['master_order_id']   = $order_id;
                $orderDetails['shop_id']           = $shop_id;
                $orderDetails['product_id']        = $item->product_id;
                $orderDetails['name']              = $item->name;
                $orderDetails['price']             = ($item->price - $item->discount_allocations[0]->amount);
                $orderDetails['quantity']          = $item->quantity;
                $orderDetails['variant_id']        = $item->variant_id;
                $orderDetails['is_upsell_product'] = 0;
                $orderDetails['created_date']      = save_db_date();
                $pro_bundle                          = $this->bundle->get_count_Product($item->product_id,
                        'p');
                
                // Assign the precursor product as the target product, then find out if the other products under it are children
                if ($target_product == NULL) {
                    $target_product = $this->bundle->get_bundle($orderDetails['product_id'], 't');
                    $target_product = $target_product[0]->bundle_id;
                } else {
                    $potential_child = $this->bundle->get_bundle($orderDetails['product_id'], 'p');
                    foreach ($potential_child as $child) {
                        if ($target_product == $child->bundle_id) {
                            $orderDetails['is_upsell_product'] = 1;
                            $query = "INSERT INTO development_output (title, content)
                                VALUES ('Comparing:', '" . $target_product . " vs " . $child->bundle_id . "');";
                            $this->db->query($query);
                        }
                    }
                    
                }
                if (!empty($pro_bundle))
                {
                    if (in_array($pro_bundle[0]->bundle_id, $newbundle_array))
                    {
                        $orderDetails['bundle_id']         = $pro_bundle[0]->bundle_id;
                    }
                }
                $this->orders->add_child($orderDetails);
                $this->products->update_variant_quantity($item->variant_id,
                        $item->quantity);
                $orderDetails['is_upsell_product'] = 0;
            }//foreach
        }
        //file_put_contents('order.json', $data);
    }

    /* cartUpdateHook */

    public function orderCreateHookOld()
    {
        $hmac_header = isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256']) ? $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] : '';
        $hmac_domain = isset($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN']) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : '';
        $data        = file_get_contents('php://input');

        $shopInfo = $this->shop->get_by_domain($hmac_domain);
        $shop_id  = $shopInfo->shop_id;

        //$data = file_get_contents('order.json');
        $order           = json_decode($data);
        $created_at      = date('Y-m-d H:i:s');
        /* array of all bundles */
        $allsbundles     = $this->bundle->get($shop_id);
        $newbundle_array = array();
        foreach ($allsbundles as $key => $allsbundle) {
            array_push($newbundle_array, $allsbundle->id);
        }
        $Orderexist = $this->orders->check_by_id($order->id);
        if (empty($Orderexist))
        {
            $orderData     = array('shop_id'        => $shop_id, 'order_id'       => $order->id,
                'subtotal_price' => $order->subtotal_price);
            $line_items    = array();
            $newbundle_ids = array();
            foreach ($order->line_items as $key => $item) {
                $line_items[$key] = array("product_id" => $item->product_id, "price"      => $item->price,
                    "variant_id" => $item->variant_id, "name"       => $item->name);
                $pro_bundle         = $this->bundle->get_count_Product($item->product_id,
                        'p');

                if (in_array($pro_bundle[0]->bundle_id, $newbundle_array))
                {
                    $orderData['upsells_amount'] += $item->price;
                }
            }//foreach

            $orderData['line_items']   = json_encode($line_items);
            $orderData['created_date'] = $created_at;
            $this->orders->add($orderData);
        }

        //file_put_contents('order.json', $data);
    }

    /* orderCreateHook */
}
