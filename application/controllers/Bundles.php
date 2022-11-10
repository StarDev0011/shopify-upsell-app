<?php

/**
 * t: Target product(main product), P : Triggerd Product(child product)
 */
class Bundles extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('shop');
        $this->load->model('products');
        $this->load->model('DiscountCodes');
        $this->load->model('bundle');
        $this->load->model('Discounts');
        $shop = $this->shopDomain;
        if (empty($shop)) {
            $uri = $_SERVER['REQUEST_URI'];
            $parts = parse_url($_SERVER['REQUEST_URI']);
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $query);
                redirect('auth/auth?shop=' . $query['shop']);
            } else {
                redirect('auth/refresh_page');
            }
        }
    }

    /**
     * Bundle listing page
     * @author Dhara
     */
    public function index() {
        $shop = $this->shopDomain;
        $shopInfo = $this->shop->get_by_domain($shop);
        $bundleList = [];
        $shop_id = '';
        $prodList = [];
        if ($shopInfo) {
            $shop_id = $shopInfo->shop_id;
            $bundleList = $this->bundle->get($shop_id, '', 0, 'b.id,b.status,dm.discount_code,bundle_label,bundle_title,offer_description,discount_type');
            if ($bundleList) {
                foreach ($bundleList as $bundle) {
                    $products = array();
                    $triggers = array();
                    $bundleProds = $this->bundle->get_bundle_prods($bundle->id, 't', 1);
                    $bundleTrigers = $this->bundle->get_bundle_prods($bundle->id, 'p', 1);

                    $bundle->triggers = $bundleTrigers;
                    $bundle->products = $bundleProds;
                    if (count($bundle->triggers) > 0) {
                        foreach ($bundle->triggers as $key => $prods) {
                            $prodvariants = $this->products->get_product_variant($prods->product_id);
                            if ($prodvariants) {
                                foreach ($prodvariants as $key2 => $variants) {
                                    $bundle->triggers[$key]->variants[$key2] = $variants;
                                }
                            }
                        }
                    }

                    if (count($bundle->products) > 0) {
                        foreach ($bundle->products as $key => $prods) {
                            $prodvariants = $this->products->get_product_variant($prods->product_id);
                            if ($prodvariants) {
                                foreach ($prodvariants as $key2 => $variants) {
                                    $bundle->products[$key]->variants[$key2] = $variants;
                                }
                            }
                        }
                    }
                }
            }
            $data = array(
                'bundleList' => $bundleList,
                'shop_id' => $shop_id,
                'curr_uri' => 'bundle-list',
                'shop' => $shop,
            );
            $this->view('admin/bundles/bundle-list', $data);
        } else {
            redirect('auth/auth?shop=' . $shop);
        }
    }

    /**
     * load view of create/edit bundle
     * @author Dhara
     * @param type $bundle_id
     */
    public function create($bundle_id = '') {
        
        
        $shop = $this->shopDomain;
        $shopInfo = $shop = $this->shop->get_by_domain($shop);
        if ($shopInfo) {
            $shop = $shopInfo->domain;
            $shop_id = $shopInfo->shop_id;
            $shop_currency = $shopInfo->currency;
            $this->syncDiscountCode($shop_id);
            $this->syncProducts($shop_id);
            $prodList = $this->products->get($shop_id);
            $collectionsList = $this->products->get_collections($shop_id);
            $bundleData = array();
            $bundleProds = array();
            $bundleTrigers = array();
            $editSelectedTarget = '';
            $editSelectedTargetVariant = '';
            $editSelectedTargetAry = [];
            $editSelectedTrigger = '';
            $editSelectedTriggerVariant = '';
            $editSelectedTriggerAry = [];
            $discountCodes = [];
            if (!empty($this->input->get('id'))) {
                $bundle_id = $this->input->get('id');
                $bundleData = $this->bundle->get($shop_id, $bundle_id);
                if (!empty($bundleData[0]))
                    $bundleData = $bundleData[0];
                $bundleProds = $this->bundle->get_bundle_prods($bundle_id, 't', 1);
                $bundleTrigers = $this->bundle->get_bundle_prods($bundle_id, 'p', 1);
                if (!empty($bundleProds)) {
                    foreach ($bundleProds as $key => $bp) {
                        $editSelectedTarget .= $bp->product_id . ',';
                        $editSelectedTargetVariant .= $bp->variant_id . '|' . $bp->product_id . ',';
                        array_push($editSelectedTargetAry, $bp->product_id);
                        if ($bp->product_options != '') {
                            $ProductVariants = $this->products->get_variants($bp->product_id);
                            $bundleProds[$key]->ProductVariants = $ProductVariants;
                        }
                    }
                    $editSelectedTarget = rtrim($editSelectedTarget, ',');
                    $editSelectedTargetVariant = rtrim($editSelectedTargetVariant, ',');
                }
                if (!empty($bundleTrigers)) {
                    foreach ($bundleTrigers as $key => $bp) {
                        $editSelectedTrigger .= $bp->product_id . ',';
                        $editSelectedTriggerVariant .= $bp->variant_id . '|' . $bp->product_id . ',';
                        array_push($editSelectedTriggerAry, $bp->product_id);
                        if ($bp->product_options != '') {
                            $ProductVariants = $this->products->get_variants($bp->product_id);
                            $bundleTrigers[$key]->ProductVariants = $ProductVariants;
                        }
                    }
                    $editSelectedTrigger = rtrim($editSelectedTrigger, ',');
                    $editSelectedTriggerVariant = rtrim($editSelectedTriggerVariant, ',');
                }

                $discountCodes = $this->DiscountCodes->get_discounts($shop_id);
            }

            if ($prodList) {
                foreach ($prodList as $key => $prod) {
                    if ($prod->product_options != '') {
                        $ProductVariants = $this->products->get_variants($prod->product_id);
                        $prodList[$key]->ProductVariants = $ProductVariants;
                    }
                }
            }
            $discountData = $this->Discounts->get_discounts($shop_id);
            //last_query();
            $data = array(
                'curr_uri' => 'bundle_create',
                'prodList' => $prodList,
                'collectionsList' => $collectionsList,
                'bundleData' => $bundleData,
                'shop_id' => $shop_id,
                'shop' => $shop,
                'shop_currency' => $shop_currency,
                'bundleProds' => $bundleProds,
                'bundleTrigers' => $bundleTrigers,
                'editSelectedTarget' => $editSelectedTarget,
                'editSelectedTargetVariant' => $editSelectedTargetVariant,
                'editSelectedTrigger' => $editSelectedTrigger,
                'editSelectedTriggerVariant' => $editSelectedTriggerVariant,
                'editSelectedTargetAry' => $editSelectedTargetAry,
                'editSelectedTriggerAry' => $editSelectedTriggerAry,
                'discountData' => $discountData,
                'discountCodes' => $discountCodes,
            );
//             echo "<pre>"; print_r($data);die;
            $this->view('admin/bundles/form', $data);
        } else {
            redirect('auth/auth?shop=' . $shop);
        }
    }
    
    /**
     * sync all Discount Code from shopify
     * @author Jass
     */
    
    public function syncDiscountCode ($shop_id) {
        
//         $shop_id = "52260962503";
        $shops = $this->shop->get_by_shop_id($shop_id);
        if (!empty($shops))
        {
            $this->load->library('Shopify');
            
            if ($shops->shop_id != 0)
            {
                $data = array(
                    'API_KEY'      => $this->config->item('shopify_api_key'),
                    'API_SECRET'   => $this->config->item('shopify_secret'),
                    'SHOP_DOMAIN'  => $shops->myshopify_domain,
                    'ACCESS_TOKEN' => $shops->access_token
                );
                $this->shopify->setup($data);
                
                try {
                    
                    $response = $this->shopify->call(array('URL' => $shops->myshopify_domain . '/admin/price_rules.json'),
                        true, $data);
                    
                    $this->addDiscounts($response, $shops->shop_id, $shops->myshopify_domain);
                    
                    if (isset($response->price_rules))
                    {
                        $discount_array = [];
                        foreach($response->price_rules as $k => $v) {
                            $discount_array [] = $v->id;
                        }
                        
                        $discounts = implode(", ", $discount_array);
                        $ids = explode(",", $discounts);
                        $data = [];
                        $data['discount_status'] = 0;
                        $test = $this->DiscountCodes->update_discount_status($data, $ids, $shop_id);
                    }
                } catch (Exception $ex) {
                    
                    print($ex);
                }
                
            }
            
        }
    }
    
    private function addDiscounts($discounts, $shop_id, $domain) {
        foreach ($discounts->price_rules as $disc) {
            $discount_id = $disc->id;
            $oldDiscount     = $this->DiscountCodes->get_discount_by_id($discount_id);
            
            $discountData = array(
                'title'       => $disc->title,
                'value_type'  => $disc->value_type,
                'value'       => $disc->value,
                'starts_at'   => $discount->starts_at,
                'ends_at'     => $disc->ends_at,
                'target_type' => $disc->target_type,
                'creator'     => 'Cron:262'
            );
            if (empty($oldDiscount)) {
                $otherData   = array(
                    'shop_id'               => $shop_id,
                    'is_discount_processed' => 0,
                    'discount_id'           => $discount_id);
                $discountData = array_merge($discountData, $otherData);
                $this->DiscountCodes->add($discountData);
            } else {
                $this->DiscountCodes->update_discount($discountData, $discount_id);
            }
        }
    }

    public function syncProducts($shop_id) {
        $shops = $this->shop->get_by_shop_id($shop_id);
        if (!empty($shops))
        {
            $this->load->library('Shopify');

            if ($shops->shop_id != 0)
            {
                $data = array(
                    'API_KEY'      => $this->config->item('shopify_api_key'),
                    'API_SECRET'   => $this->config->item('shopify_secret'),
                    'SHOP_DOMAIN'  => $shops->myshopify_domain,
                    'ACCESS_TOKEN' => $shops->access_token
                );
                $this->shopify->setup($data);

                try {
                    $response = $this->shopify->call(
                        array(
                            'URL' => $shops->myshopify_domain . '/admin/products.json',
                        ),
                        true,
                        $data
                    );

                    $this->addProducts($response, $shops->shop_id, $shops->myshopify_domain);
                    
                } catch (Exception $ex) {
                    print($ex);
                }
            }
        }
    }

    private function addProducts($products, $shop_id, $domain) {
        $includedProducts = [];
        foreach ($products->products as $product) {
            $product_id = $product->id;
            $includedProducts[] = $product_id;
            $oldProduct = $this->products->get_product_by_id($product_id);

            $options         = $product->options;
            $product_options = '';
            if ($options)
            {
                foreach ($options as $opt) {
                    $product_options .= $opt->name . '|';
                }
            }

            $productData = array(
                'title'           => $product->title,
                'image'           => $product->image->src,
                'product_link'    => 'https://' . $domain . '/products/' . $product->handle,
                'product_slug'    => $product->handle,
                'product_options' => trim($product_options, '|')
            );

            if (empty($oldProduct)) {
                $otherData = array(
                    'shop_id' => $shop_id,
                    'is_collection_processed' => 0,
                    'product_id' => $product_id
                );
                $productData = array_merge($productData, $otherData);
                $this->products->add($productData);
            } else {
                $this->products->update_product($productData, $product_id);
            }

            $variants = $product->variants;
            if ($variants)
            {
                foreach ($variants as $var) {
                    $variantImage = isset($product->image->src) ? $product->image->src : '';
                    $varImageId   = !empty($var->image_id) ? $var->image_id : '';
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
        if (!empty($includedProducts)) {
            $excludedProducts = $this->products->get_products_not_in($includedProducts);
            foreach ($excludedProducts as $prod) {
                $this->products->delete_product($prod->product_id);
            }
        }
    }
    
    /**
     * Insert/update bundle
     * @author Dhara
     */
    public function insert() {
        $post = $this->input->post();
        $response['status'] = ERROR;
        
        if($this->trialDays <= 0 && in_array($this->chargeStatus, $this->shopRejectedChargeStatus) || $this->shopStatus=="frozen"){
            $response['msg'] = 'You have been '.$this->chargeStatus.' payment. You cannot proceed further.';
            echo json_encode($response);
            exit;
        }
        if (!empty($post)) {

            $discount_id = 0;
            if (isset($post['discount_id']))
                $discount_id = explode('|', $post['discount_id']);
            $post['check_stock'] = !empty($post['check_stock']) ? 1 : 0;
            $post['use_product_quantity'] = !empty($post['use_product_quantity']) ? 1 : 0;
            $post['discount_id'] = $discount_id[0];
            if (!empty($post['start_date']))
                $post['start_date'] = save_date($post['start_date']);
            if (!empty($post['end_date']))
                $post['end_date'] = save_date($post['end_date']);

//            prExit($this->input->post());
            unset($post['sel_variant']);
            unset($post['discount_value']);
            unset($post['trigger_variant']);
            unset($post['target_product']);
            unset($post['target_product_variant']);
            unset($post['trigger_product']);
            unset($post['trigger_product_variant']);
            unset($post['search_trigger_product']);
            unset($post['search_bundle_product']);
            unset($post['triggered_category']);
            unset($post['bundle_category']);
            $post['upsell_condition'] = (int) $post['upsell_condition'];

            if (empty($post['id'])) {
                $post['created_date'] = date('Y-m-d H:i:s');
                $id = $this->bundle->insert($post);
                if (!empty($id)) {
                    $response['status'] = SUCCESS;
                }
            } else {
                $id = $post['id'];
                $this->bundle->delete_bundleProducts($id, $_POST['trigger_product']);
//                $post['discount_goal_amount'] = 
                $this->bundle->update($post, $post['id']);
                $response['status'] = SUCCESS;
            }
            if ($_POST['target_product'] != '') {
                $products = explode(',', $_POST['target_product']);
                $products_variant = explode(',', $_POST['target_product_variant']);
                if ($products) {
                    foreach ($products as $p_key => $prods) {
                        if ($prods != '') {
                            $variant = isset($products_variant[$p_key]) ? (explode('|', $products_variant[$p_key])[0]) : 0;
                            $data = array('bundle_id' => $id, 'product_id' => $prods, 'variant_id' => $variant, 'type' => 't');
                            $this->bundle->insert_bundle_product($data);
                        }
                    }
                }
            }
            if ($_POST['trigger_product'] != '') {
                $products = explode(',', $_POST['trigger_product']);
                $products_variant = explode(',', $_POST['trigger_product_variant']);
                if ($products) {
                    foreach ($products as $p_key => $prods) {
                        if ($prods != '') {
                            $variant = isset($products_variant[$p_key]) ? (explode('|', $products_variant[$p_key])[0]) : 0;
                            $data = array('bundle_id' => $id, 'product_id' => $prods, 'variant_id' => $variant, 'type' => 'p');
                            $this->bundle->insert_bundle_product($data);
                        }
                    }
                }
            }
        }
        echo json_encode($response);
        exit;
    }

    /**
     * Returns bundle items on base of parameter
     * @param type $bundle_id
     * @param type $req_type
     * @return type
     */
    public function get_bundle_items($bundle_id = '', $req_type = 'p') {
        $bundleProds = $this->bundle->get_bundle_prods($bundle_id, 'p');
        //$bundleTrigers	=	$this->bundle->get_bundle_prods($bundle_id, 't');	
        return $bundleProds;
    }

    /**
     * Change status of bundle
     * @author Dhara
     */
    public function update_bundle() {

        $bundle = $this->bundle->getbundle_info($_POST['bundle_id']);
        $products = '';
        $bundle_pro = '';
        $response['status'] = SUCCESS;

        if ($bundle[0]->status == 0) {
            $triggered_products = $this->bundle->get_bundle_prods($_POST['bundle_id'], 't');
            if (!empty($triggered_products)) {
                foreach ($triggered_products as $tp) {
                    $products .= $tp->product_id . ', ';
                }
                $products = rtrim($products, ', ');
                $another_bundle = $this->bundle->get_another_bundle_products($products, $this->shopId, $_POST['bundle_id'], 't');
                if (!empty($another_bundle)) {
                    $response = $this->validate_trigger_products($another_bundle, 1);
                }
            }
            $status = 1;
            $msg = 'Record activated successfully';
        } else {
            $status = 0;
            $msg = 'Record deactivated successfully';
        }

        if ($response['status'] == SUCCESS) {
            $bundleData = array('status' => $status);
            $bundleId = $this->bundle->update($bundleData, $_POST['bundle_id']);
            $response['msg'] = $msg;
            $response['status'] = SUCCESS;
        }
        echo json_encode($response);
        exit;
    }

    /**
     * Deletes bundle
     * @author Dhara
     */
    public function delete_bundle() {
        $bundleId = $this->bundle->delete($_POST['bundle_id']);
    }

    /**
     * Front Upsell popup
     * @author Dhara
     */
    function getCartPagePopup() {

        $shopId = $this->shopDomain;
        $product_slug = isset($_POST['product_slug']) ? $_POST['product_slug'] : '';
        $variantId = isset($_POST['variantId']) ? $_POST['variantId'] : '';
        $bundleId = isset($_POST['bundleId']) ? $_POST['bundleId'] : '';
        $cur_cart_prodID = '';
        $firstPrice = 0;
        if ($product_slug != '') {
            $product_slugarr = explode('?', $product_slug);
            if ($product_slugarr[0]) {
                $product_slug = $product_slugarr[0];
            }
            $cartProduct = $this->products->get_product_id_by_slug($product_slug, 1);
            
            if ($cartProduct) {
                $cur_cart_prodID = $cartProduct->product_id;
                $variantPrice = $this->products->get_product_variants($cartProduct->product_id);
                $firstPrice = $variantPrice[0]->price;
            }
        }
        if (!empty($cartItems)) {
            foreach ($cartItems as $ci) {
                if ($ci['variant_id'] == $variantId) {
                    $quantity += $ci['quantity']; //we have to add 1 bcaz at the time of add to cart we are considering one selected quantity added
                    continue;
                }
            }
        }

        $cart_variants = array();
        $shopInfo = $this->shop->get_by_domain($shopId);
        $products_array = array();
        $final_products = array();
        $bundleInfo = $this->bundle->get_bundle_by_prod($cur_cart_prodID, 't', $bundleId);
        $noOfBundle = count($bundleInfo);
        $variant = [];
        if ($shopInfo) {
            $shop = $shopInfo->domain;
            $shop_id = $shopInfo->shop_id;
            $shop_currency = $shopInfo->currency;
            if (!empty($variantId)) {
                $variant = $this->products->get_product_variant_by_id($variantId);
            }

            $pop_array = array();
            $info = array();
            $firstProduct = '';
            $final_array = [];
            if ($cur_cart_prodID != '') {
                if ($bundleInfo) {
                    $isPopupShown = 1;
                    $first_bundle = '';
                    foreach ($bundleInfo as $k => $bprod) {
                        $bundle_id = $bprod->bundle_id;
                        $getbundle_info = $this->bundle->getbundle_info($bundle_id, 1);
                        if (!empty($getbundle_info)) {
                            if ($getbundle_info[0]->status != '0') {
                                if ($k == 0) {
                                    $first_bundle = $getbundle_info;
                                }
                                $isPopupShown = $this->checkConditions($getbundle_info, $isPopupShown, $variant);
                                if ($isPopupShown == 1) {
                                    $products = $this->bundle->get_bundle_prods($bundle_id, 'p');
                                    if ($products) {
                                        foreach ($products as $key => $prod) {
                                            $products_array[$key] = $this->products->get_product_by_id($prod->product_id);
                                            $products_array[$key][0]->bundle = $getbundle_info[0];

                                            if ($products_array) {
                                                $prodvariants = $this->products->get_product_variant($products_array[$key][0]->product_id);
                                                if ($prodvariants) {
                                                    if ($getbundle_info[0]->check_stock == 1) {
                                                        $variantsArray = [];
                                                        $i = 0;
                                                        foreach ($prodvariants as $key2 => $variants) {
                                                            $isTrue = $this->check_bundle_stock($variants);
                                                            if ($isTrue == 1) {
                                                                $variantsArray[$i] = $variants;
                                                                $i++;
                                                            }
                                                        }
                                                        if (!empty($variantsArray)) {
                                                            $products_array[$key][0]->variants = $variantsArray;
                                                        } else {
                                                            unset($products_array[$key]);
                                                        }
                                                    } else {
                                                        foreach ($prodvariants as $key2 => $variants) {
                                                            $products_array[$key][0]->variants[$key2] = $variants;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $final_array = array_merge($final_array, $products_array);
                                }
                            }
                        }
                    }
                }
            }
            if (count($final_array) > 0) {
                $firstProduct = $final_array[0][0]->product_id;
                $bundleData = $this->bundle->get($shop_id, $bundleInfo[0]->bundle_id, 1);
                $bundle_title = '';
                $bundle_goal = 0;
                $discount_type = 0;
                $bundle_id = 0;
                $variant_price = 0;
                $goal_text = '';
                $default_text = '';
                $reached_ratio = 0;
                if ($first_bundle) {
                    $first_bundle=$first_bundle[0];
                    $bundle_title = $first_bundle->bundle_title;
                    $bundle_goal = $first_bundle->discount_goal_amount;
                    $discount_type = $first_bundle->discount_type;
                    $bundle_id = $first_bundle->id;
                    if ($first_bundle->discount_type != 0) {
                        $goal_text = $this->Discounts->get_goal_away_text($shop_currency, $first_bundle->discount_type, $first_bundle->value_type, $first_bundle->value);
                        $default_text = $goal_text['default'];
                        $goal_text = $goal_text['text'];
                        if($bundle_goal!=0)
                            $reached_ratio = round(($firstPrice * 100) / $bundle_goal);
                    }
                }
                $this->load->model('setting');
                $setting = $this->setting->find_all($shop_id);
                $is_head_desc = ($first_bundle->value_type=='fixed_amount' || $first_bundle->value_type=='free_shipping')?1:0;
                $data = array(
                    'products_array' => $final_array,
                    'bundle_title' => $bundle_title,
                    'bundle_id' => $bundle_id,
                    'cart_variants' => $cart_variants,
                    'shop_currency' => $shop_currency,
                    'setting' => $setting,
                    'bundleData' => $bundleData,
                    'firstProduct' => $firstProduct,
                    'reached_goal_amount' => $bundle_goal,
                    'remain_goal_amount' => $bundle_goal - $firstPrice,
                    'reached_ratio' => $reached_ratio,
                    'goal_text' => $goal_text,
                    'default_text' => $default_text,
                    'bundle_goal'=>$bundle_goal,
                    'is_head_desc'=>$is_head_desc,
                );
                $isSkipNext = 1;
                if (count($final_array) <= 5) {
                    $content = $this->load->view('admin/bundles/includes/preview-popup', $data, true);
                } else {
                    $content = $this->load->view('admin/bundles/includes/scrolling-popup', $data, true);
                }
                echo json_encode(array('status' => SUCCESS, 'content' => $content, 'isSkipNext' => $isSkipNext, 'no_of_bundle' => $noOfBundle, 'total_products' => count($final_array)));
                exit;
            } else {
                echo json_encode(array('status' => ERROR));
                exit;
            }
        }
    }

    function check_bundle_stock($variant) {
        $isStockValid = 1;
        $isStockAdded = 0;
        if ($variant->inventory_management == 'shopify') {
            if ($variant->inventory_policy != 'continue') {
                if ($variant->inventory < 1) {
                    $isStockValid = 0;
                }
            }
            $isStockAdded = 1;
        }
        return $isStockValid;
    }

    /**
     * This will check all conditions to show popup
     * @author Dhara
     * @param type $getbundle_info
     * @param type $isPopupShown
     * @return int
     */
    function checkConditions($getbundle_info, $isPopupShown, $variant) {

        $isDateValid = 1;
        $isStockValid = 1;
        $isTriggerStockValid = 1;
        $isQtyValid = 1;

        $isDateAdded = 0;
        $isStockAdded = 0;
        $isTriggerStockAdded = 0;
        $isQtyAdded = 0;
        $checkArray = [];

        //Date checking
        if (($getbundle_info[0]->start_date != '0000-00-00') || ($getbundle_info[0]->end_date != '0000-00-00')) {
            if ($getbundle_info[0]->start_date != '0000-00-00') {
                if ((date('Y-m-d') < $getbundle_info[0]->start_date)) {
                    $isDateValid = 0;
                }
                $isDateAdded = 1;
            }
            if ($getbundle_info[0]->end_date != '0000-00-00') {
                if ((date('Y-m-d') > $getbundle_info[0]->end_date)) {
                    $isDateValid = 0;
                }
                $isDateAdded = 1;
            }
            array_push($checkArray, $isDateValid);
        }

        if (!empty($checkArray)) {
            //If bundle has OR condition then if only one condition found then will return 1
            if ($getbundle_info[0]->upsell_condition == 0) {
                if (in_array(1, $checkArray)) {
                    return 1;
                } else {
                    return 0;
                }
            } else {//If bundle has AND condition then all condition has to match
                if (in_array(0, $checkArray)) {
                    return 0;
                } else {
                    return 1;
                }
            }
        } else {
            return 1;
        }
        return 1;
    }

    /**
     * 
     */
    public function get_discount_code() {
        $discounts = $this->DiscountCodes->get_discounts($this->input->post('shop_id'));
        $response['status'] = 'error';
        $response['is_bxgy'] = 0;
        if (!empty($discounts)) {
            if (!empty($this->input->post('id')) && ($this->input->post('type') == 3)) {
                $content = $this->get_discount_products($this->input->post('id'), 0, $this->input->post('shop_id'));
                $response['is_bxgy'] = 1;
                $response = $response + $content;
            }
            $response['status'] = 'success';
            $response['content'] = $discounts;
        }
        echo json_encode($response);
        return $this->Discounts->get_discounts($this->input->post('shop_id'));
        exit;
    }

    /**
     * @author Dhara
     * @param type $result
     * @param type $from
     * @return type
     */
    public function validate_trigger_products($trigger_result = '', $from = 0) {
        if ($from == 0) {
            $trigger_product = $this->input->post('trigger_product');
            $id = $this->input->post('id');
            $trigger_result = $this->bundle->get_another_bundle_products($trigger_product, $this->shopId, $id, 't');
        }
        $response['status'] = 'success';
        $msg = '';
        if (!empty($trigger_result)) {
//             $response = $this->common_validate_products($trigger_result, $from, 't');
        }

        if ($from == 1) {
            return $response;
        }
        echo json_encode($response);
        exit;
    }

    /**
     * 
     * @param type $result
     * @param type $from
     * @param type $type
     * @return type
     */
    function common_validate_products($result, $from, $type) {
        $response['status'] = 'success';
        $products = '';
        $bundles = '';
        $old_bundle = '';
        $old_product = '';
        foreach ($result as $r) {
            if ($r->title != $old_product)
                $products .= $r->title . ', ';
            if ($r->bundle_id != $old_bundle)
                $bundles .= $r->bundle_title . ', ';

            $old_bundle = $r->bundle_id;
            $old_product = $r->title;
        }
        $bundles = rtrim($bundles, ', ');
        $products = rtrim($products, ', ');
        if ($type == 't') {
            if ($from == 1) {
                $msg = str_replace(["<products>", "<bundles>"], [$products, $bundles], $this->lang->line('duplicate_trigger_msg_activation'));
            } else {
                $msg = str_replace(["<products>", "<bundles>"], [$products, $bundles], $this->lang->line('duplicate_trigger_msg_form'));
            }
        }
        $response['status'] = 'error';
        $response['msg'] = $msg;
        return $response;
    }

    /**
     * @author Dhara
     * @date 3-12-2018
     * @param type $id
     * @param type $is_post
     * @return type
     */
    public function get_discount_products($id = '', $is_post = 1, $shop_id = '') {
        if ($is_post == 1) {
            $id = $this->input->post('id');
            $shop_id = $this->input->post('shop_id');
        }
        $id = explode('|', $id)[0];
        $return_array = ['status' => ERROR];
        if (!empty($id)) {
            $bundle_product = $this->Discounts->get_bxgy_discount_details($id);
            $trigger_product = $this->Discounts->get_bxgy_discount_details($id, 'prerequisite_product_ids,prerequisite_variant_ids', 'prerequisite_type','prerequisite_product_ids');
            if (!empty($bundle_product)) {
                if ($bundle_product[0]->entitled_type == 1) {
                    $bundle_collection = '';
                    foreach ($bundle_product as $row) {
                        $bundle_collection .= ',' . $row->entitled_product_ids;
                    }
                    $bundle_collection = ltrim($bundle_collection, ',');
                    if (!empty($bundle_collection)) {
                        $bundle_product = $this->products->get($shop_id, '', '', $bundle_collection, 'products.product_id as entitled_product_ids');
                    }
                }
            }
            foreach ($bundle_product as &$bp){
                if(empty($bp->entitled_variant_ids)){
                    $firstVarint = $this->products->getFirstVariant($bp->entitled_product_ids);
                    if(isset($firstVarint))
                        $bp->entitled_variant_ids = $firstVarint->variant_id;
                }
            }
            if (!empty($trigger_product)) {
                if ($trigger_product[0]->prerequisite_type == 1) {
                    $trigger_collection = '';
                    foreach ($trigger_product as $row) {
                        $trigger_collection .= ',' . $row->prerequisite_product_ids;
                    }
                    $trigger_collection = ltrim($trigger_collection, ',');
                    if (!empty($trigger_collection)) {
                        $trigger_product = $this->products->get($shop_id, '', '', $trigger_collection, 'products.product_id as prerequisite_product_ids');
                    }
                }
            }
            foreach ($trigger_product as &$bp){
                if(empty($bp->prerequisite_variant_ids)){
                    $firstVarint = $this->products->getFirstVariant($bp->prerequisite_product_ids);
                    if(isset($firstVarint))
                        $bp->prerequisite_variant_ids = $firstVarint->variant_id;
                }
            }
            $return_array = ['status' => SUCCESS, 'trigger_product' => $trigger_product, 'bundle_product' => $bundle_product];
        }
        if ($is_post == 1) {
            echo json_encode($return_array);
            exit;
        } else {
            return $return_array;
        }
    }

}
