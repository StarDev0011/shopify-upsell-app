<?php

class Front extends CI_Controller {

    public $no_of_cart_items = 0;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('shop');
        $this->load->model('products');
        $this->load->model('bundle');
        $this->load->model('CrossSellBundle');
        $this->load->model('setting');
    }

    function updateCartUpsell()
    {
        echo "test";
    }

    function getCartPagePopup2()
    {
        $shopId    = $_POST['shopId'];
        $cartItems = isset($_POST['cart_items']) ? $_POST['cart_items'] : [];
        $shopInfo  = $this->shop->get_by_shop_id($shopId);
        $content = array('status' => ERROR, 'is_empty_cart' => 3);
        if ($shopInfo)
        {
            $shop                    = $shopInfo->domain;
            $shop_id                 = $shopInfo->shop_id;
            $shop_currency           = $shopInfo->currency;
            $is_bundle_product_added = 0;
            $reached_goal_amount     = 0;
            if(!empty($_POST['product_slug']) && $_POST['product_slug']!='undefined'){
                $singleCartItem = ['quantity'=>1,'variant_id'=>$_POST['product_slug']];
                if($_POST['slugType']=='string'){
                    $product = $this->products->getProductVariantBySlug(['slug'=>$_POST['product_slug'],'shop_id'=>$shopInfo->shop_id]);
                    if (is_object($product)) {
                        $singleCartItem['variant_id'] = $product->variant_id;
                        $singleCartItem['price'] = $product->price;
                    } else {
                        $singleCartItem['variant_id'] = '';
                        $singleCartItem['price'] = '';
                    }
                }else{
                    $product = $this->products->get_product_variant_by_id($_POST['product_slug'],1);
                    $singleCartItem['price'] = isset($product->price)?$product->price:0;
                }
                $content     = $this->get_popup_content($singleCartItem,
                    $shopInfo, $singleCartItem['price'], 0, 0, 1);
            }else
                if (!empty($cartItems))
                {

                    $this->no_of_cart_items = count($cartItems);
                    $last_added_bundle      = $this->input->post('last_added_bundle');
                    $latest_used_bundle     = $this->input->post('latest_used_bundle');
                    $last_used_variant      = $this->input->post('last_used_variant');
                    $reached_goal_amount    = $this->input->post('goal_amount');
                    $bundle_products_array  = [];
                    $goal_amount            = 0;
                    //If last added bundle met then display its content otherwise find new product

                    if (!empty($latest_used_bundle))
                    {
                        $ci              = [];
                        $addedBundleInfo = $this->bundle->get($shopInfo->shop_id,
                            $latest_used_bundle, 1, 'discount_goal_amount');
                        $bundle_products = $this->bundle->get_bundle_prods($latest_used_bundle,
                            'p');
                        foreach ($bundle_products as $bp) {
                            array_push($bundle_products_array, $bp->variant_id);
                        }
                        $cartItemsCount           = count($cartItems);
                        $is_bundle_matched        = 0;
                        $is_trigger_product_exist = 0;
                        $matched_trigger_qty      = 1;
                        $is_trigger_in_bundle     = 0;
                        $trigger_price            = 0;
                        /**
                         * check if trigger product is in bundle product.
                         * if yes then remove it from bundle product
                         */
                        if (in_array($last_used_variant, $bundle_products_array))
                        {
                            $is_trigger_in_bundle  = 1;
                            $bundle_products_array = array_filter($bundle_products_array,
                                function($e) use ($last_used_variant) {
                                    return ($e !== $last_used_variant);
                                });
                        }
                        foreach ($cartItems as $cart) {

                            $amt = get_price($cart['line_price']);
                            //check if any variant of product is added to cart which in bundle product
                            if (in_array($cart['variant_id'], $bundle_products_array))
                            {
                                $goal_amount             += $amt;
                                $is_bundle_product_added = 1;
                                $is_bundle_matched       = 1;
                            }
                            if ($cart['variant_id'] == $last_used_variant)
                            {
                                $is_trigger_product_exist = 1;
                                $ci                       = $cart;
                                $last_used_variant_price  = $this->products->get_product_variant_by_id($last_used_variant);
                                if (!empty($last_used_variant_price))
                                {
                                    //                                $goal_amount += $last_used_variant_price[0]->price;
                                    $goal_amount   += $amt;
                                    $trigger_price = $last_used_variant_price[0]->price;
                                }
                                if ($cartItemsCount > 1)
                                    $is_bundle_product_added = 1;

                                    $matched_trigger_qty = $cart['quantity'];
                            }
                        }
                        //                    echo '$is_trigger_in_bundle = '.$is_trigger_in_bundle.' | $matched_trigger_qty = '.$matched_trigger_qty.' | $trigger_price='.$trigger_price.' | $goal_amount='.$goal_amount;
                        //                    pr($addedBundleInfo);
                        //                    echo '$addedBundleInfo->discount_goal_amount='.$addedBundleInfo->discount_goal_amount.' | $is_bundle_matched = '.$is_bundle_matched.' | $is_bundle_product_added='.$is_bundle_product_added.' | $is_trigger_product_exist='.$is_trigger_product_exist;
                        //                    pr($ci);
                        //                    echo '$addedBundleInfo->discount_goal_amount='.$addedBundleInfo->discount_goal_amount.' || $goal_amount='.$goal_amount.' || $reached_goal_amount='.$reached_goal_amount;
                        //                    exit;
                        if ($addedBundleInfo)
                        {
                            if ($is_bundle_product_added == 0 || $is_bundle_matched == 0
                                || $is_trigger_product_exist == 0)
                            {
                                /**
                                 * if there is no bundle product added from any bundle OR bundle is not matched Or triggered product is not matched with last used variant
                                 * then get last added product and find bundle */
                                $ci          = $cartItems[0];
                                $goal_amount = get_price($ci['line_price']);
                                if ($cartItemsCount > 1)
                                {
                                    //when there is no already bundle product used and items are more then 1 then check if last added product has bundle
                                    //and if yes then calculate goal amount using whole cart items
                                    $bundleInfo  = $this->bundle->get_bundle_by_variant($ci['variant_id'],
                                        't');
                                    if (!empty($bundleInfo))
                                        $goal_amount = $this->calculate_goal_amount($cartItems,
                                            $ci['variant_id'],
                                            $bundleInfo[0]->bundle_id,
                                            $cartItemsCount);
                                }
                                $content = $this->get_popup_content($ci, $shopInfo,
                                    $goal_amount, $reached_goal_amount, 0, 1);
                            } else if (($is_bundle_product_added == 1 && $is_bundle_matched == 1
                                && $is_trigger_product_exist == 1) && ($addedBundleInfo->discount_goal_amount <= $goal_amount))
                            {//check if last added bundle has met to its goal or not. if met then returnn nothing else return popup content of that bundle
                                /**
                                 * If bundle product added with triggered product in cart and goal amount is fulfilled then don't show popup
                                 */
                                echo json_encode(array('status' => ERROR, 'is_empty_cart' => 5));
                                exit;
                            } else
                            {
                                if (!empty($ci))
                                {

                                    /**
                                     * If there is single product into cart and its not matching with last used variant and not matched with bundle
                                     * then get last added product and find bundle
                                     */
                                    $reached_goal_ratio = 0;
                                    if ($addedBundleInfo->discount_goal_amount != 0)
                                        $reached_goal_ratio = (((int) $goal_amount)
                                            * 100) / (int) $addedBundleInfo->discount_goal_amount;
                                            $content            = $this->get_popup_content($ci,
                                                $shopInfo, $goal_amount,
                                                $addedBundleInfo->discount_goal_amount,
                                                (int) $reached_goal_ratio);
                                }
                            }
                        }
                    } else
                    {

                        foreach($cartItems as $cartItem) {
                            $goal_amountTem = get_price($cartItem['line_price']);
                            $contentTem = $this->get_popup_content($cartItem,
                                $shopInfo, $goal_amountTem, 0, 0, 1);
                            if($contentTem['status'] != 'error') {
                                $content[] = $contentTem;
                                $goal_amount[] = $goal_amountTem;
                                $is_bundle_product_added++;
                                $content['status'] = "success";
                            }
                        }

                    }
                } else
                {
                    $null_content = array('bundle_id'         => '', 'goal_amount'       => 0,
                        'last_used_variant' => '',
                        'is_empty_cart'     => 1);

                        $content      = array_merge($content, $null_content);
                }
                $content['is_bundle_product_added'] = $is_bundle_product_added;
                echo json_encode($content);
                exit;
        }
    }


    /**
     * Front Upsell popup
     * @author Dhara
     */
    function getCartPagePopup()
    {
        $shopId    = $_POST['shopId'];
        $cartItems = isset($_POST['cart_items']) ? $_POST['cart_items'] : [];
        $shopInfo  = $this->shop->get_by_shop_id($shopId);
        $content = array('status' => ERROR, 'is_empty_cart' => 3);
        if ($shopInfo)
        {

            $shop                    = $shopInfo->domain;
            $shop_id                 = $shopInfo->shop_id;
            $shop_currency           = $shopInfo->currency;
            $is_bundle_product_added = 0;
            $reached_goal_amount     = 0;
            if(!empty($_POST['product_slug']) && $_POST['product_slug']!='undefined'){
                 $singleCartItem = ['quantity'=>1,'variant_id'=>$_POST['product_slug']];
                 if($_POST['slugType']=='string'){
                    $product = $this->products->getProductVariantBySlug(['slug'=>$_POST['product_slug'],'shop_id'=>$shopInfo->shop_id]);
                    if (is_object($product)) {
                        $singleCartItem['variant_id'] = $product->variant_id;
                        $singleCartItem['price'] = $product->price;
                    } else {
                        $singleCartItem['variant_id'] = '';
                        $singleCartItem['price'] = '';
                    }
                 }else{
                     $product = $this->products->get_product_variant_by_id($_POST['product_slug'],1);
                     $singleCartItem['price'] = isset($product->price)?$product->price:0;
                 }
                $content     = $this->get_popup_content($singleCartItem,
                $shopInfo, $singleCartItem['price'], 0, 0, 1);
            }else
                if (!empty($cartItems))
            {

                $this->no_of_cart_items = count($cartItems);
                $last_added_bundle      = $this->input->post('last_added_bundle');
                $latest_used_bundle     = $this->input->post('latest_used_bundle');
                $last_used_variant      = $this->input->post('last_used_variant');
                $reached_goal_amount    = $this->input->post('goal_amount');
                $bundle_products_array  = [];
                $goal_amount            = 0;
                //If last added bundle met then display its content otherwise find new product

                if (!empty($latest_used_bundle))
                {

                    $ci              = [];
                    $addedBundleInfo = $this->bundle->get($shopInfo->shop_id,
                            $latest_used_bundle, 1, 'discount_goal_amount');
                    $bundle_products = $this->bundle->get_bundle_prods($latest_used_bundle,
                            'p');
                    foreach ($bundle_products as $bp) {
                        array_push($bundle_products_array, $bp->variant_id);
                    }
                    $cartItemsCount           = count($cartItems);
                    $is_bundle_matched        = 0;
                    $is_trigger_product_exist = 0;
                    $matched_trigger_qty      = 1;
                    $is_trigger_in_bundle     = 0;
                    $trigger_price            = 0;
                    /**
                     * check if trigger product is in bundle product.
                     * if yes then remove it from bundle product
                     */
                    if (in_array($last_used_variant, $bundle_products_array))
                    {
                        $is_trigger_in_bundle  = 1;
                        $bundle_products_array = array_filter($bundle_products_array,
                                function($e) use ($last_used_variant) {
                            return ($e !== $last_used_variant);
                        });
                    }
                    foreach ($cartItems as $cart) {

                        $amt = get_price($cart['line_price']);
                        //check if any variant of product is added to cart which in bundle product
                        if (in_array($cart['variant_id'], $bundle_products_array))
                        {
                            $goal_amount             += $amt;
                            $is_bundle_product_added = 1;
                            $is_bundle_matched       = 1;
                        }
                        if ($cart['variant_id'] == $last_used_variant)
                        {
                            $is_trigger_product_exist = 1;
                            $ci                       = $cart;
                            $last_used_variant_price  = $this->products->get_product_variant_by_id($last_used_variant);
                            if (!empty($last_used_variant_price))
                            {
//                                $goal_amount += $last_used_variant_price[0]->price;
                                $goal_amount   += $amt;
                                $trigger_price = $last_used_variant_price[0]->price;
                            }
                            if ($cartItemsCount > 1)
                                    $is_bundle_product_added = 1;

                            $matched_trigger_qty = $cart['quantity'];
                        }
                    }
//                    echo '$is_trigger_in_bundle = '.$is_trigger_in_bundle.' | $matched_trigger_qty = '.$matched_trigger_qty.' | $trigger_price='.$trigger_price.' | $goal_amount='.$goal_amount;
//                    pr($addedBundleInfo);
//                    echo '$addedBundleInfo->discount_goal_amount='.$addedBundleInfo->discount_goal_amount.' | $is_bundle_matched = '.$is_bundle_matched.' | $is_bundle_product_added='.$is_bundle_product_added.' | $is_trigger_product_exist='.$is_trigger_product_exist;
//                    pr($ci);
//                    echo '$addedBundleInfo->discount_goal_amount='.$addedBundleInfo->discount_goal_amount.' || $goal_amount='.$goal_amount.' || $reached_goal_amount='.$reached_goal_amount;
//                    exit;
                    if ($addedBundleInfo)
                    {
                        if ($is_bundle_product_added == 0 || $is_bundle_matched == 0
                                || $is_trigger_product_exist == 0)
                        {
                            /**
                             * if there is no bundle product added from any bundle OR bundle is not matched Or triggered product is not matched with last used variant
                             * then get last added product and find bundle */
                            $ci          = $cartItems[0];
                            $goal_amount = get_price($ci['line_price']);
                            if ($cartItemsCount > 1)
                            {
                                //when there is no already bundle product used and items are more then 1 then check if last added product has bundle
                                //and if yes then calculate goal amount using whole cart items
                                $bundleInfo  = $this->bundle->get_bundle_by_variant($ci['variant_id'],
                                        't');
                                if (!empty($bundleInfo))
                                        $goal_amount = $this->calculate_goal_amount($cartItems,
                                            $ci['variant_id'],
                                            $bundleInfo[0]->bundle_id,
                                            $cartItemsCount);
                            }
                            $content = $this->get_popup_content($ci, $shopInfo,
                                    $goal_amount, $reached_goal_amount, 0, 1);
                        } else if (($is_bundle_product_added == 1 && $is_bundle_matched == 1
                                && $is_trigger_product_exist == 1) && ($addedBundleInfo->discount_goal_amount <= $goal_amount))
                        {//check if last added bundle has met to its goal or not. if met then returnn nothing else return popup content of that bundle
                            /**
                             * If bundle product added with triggered product in cart and goal amount is fulfilled then don't show popup
                             */
                            echo json_encode(array('status' => ERROR, 'is_empty_cart' => 5));
                            exit;
                        } else
                        {
                            if (!empty($ci))
                            {

                                /**
                                 * If there is single product into cart and its not matching with last used variant and not matched with bundle
                                 * then get last added product and find bundle
                                 */
                                $reached_goal_ratio = 0;
                                if ($addedBundleInfo->discount_goal_amount != 0)
                                        $reached_goal_ratio = (((int) $goal_amount)
                                            * 100) / (int) $addedBundleInfo->discount_goal_amount;
                                $content            = $this->get_popup_content($ci,
                                        $shopInfo, $goal_amount,
                                        $addedBundleInfo->discount_goal_amount,
                                        (int) $reached_goal_ratio);

                            }
                        }
                    }
                } else
                {
					$contentT = array();
                    $goal_amount = get_price($cartItems[0]['line_price']);

					for ($iii = 0 ; $iii < count($cartItems); $iii++) {
						if($iii == 0)
						{
                    $contentT     = $this->get_popup_content($cartItems[$iii],
                            $shopInfo, $goal_amount, 0, 0, 1);


						}
						else{

							$contentAll     = $this->get_popup_contentextra($cartItems[$iii],
                            $shopInfo, $goal_amount, 0, 0, 1);
						//	print_r($contentAll['content'] );
						//	die();
						//die();
							$contentT['content'] = str_replace("<itemplacex>",$contentAll['content'], $contentT['content']);
						//die($contentAll->content);

						}


					}

					$content = $contentT;
					echo json_encode($content);
					exit;
                }
            } else
            {
                $null_content = array('bundle_id'         => '', 'goal_amount'       => 0,
                    'last_used_variant' => '',
                    'is_empty_cart'     => 1);
                $content      = array_merge($content, $null_content);
            }
            $content['is_bundle_product_added'] = $is_bundle_product_added;
            echo json_encode($content);
            exit;
        }
    }

    public function calculate_goal_amount($cartItems, $last_used_variant,
            $latest_used_bundle, $cartItemsCount)
    {
        $goal_amount           = 0;
        $bundle_products_array = [];
        $bundle_products       = $this->bundle->get_bundle_prods($latest_used_bundle,
                'p');
        foreach ($bundle_products as $bp) {
            array_push($bundle_products_array, $bp->variant_id);
        }
        /**
         * check if trigger product is in bundle product.
         * if yes then remove it from bundle product
         */
        if (in_array($last_used_variant, $bundle_products_array))
        {
            $bundle_products_array = array_filter($bundle_products_array,
                    function($e) use ($last_used_variant) {
                return ($e !== $last_used_variant);
            });
        }
        foreach ($cartItems as $cart) {
            $amt = get_price($cart['line_price']);
            if (in_array($cart['variant_id'], $bundle_products_array))
            {
                $goal_amount += $amt;
            }
            if ($cart['variant_id'] == $last_used_variant)
            {
                $last_used_variant_price = $this->products->get_product_variant_by_id($last_used_variant);
                if (!empty($last_used_variant_price))
                {
                    $goal_amount += $amt;
                }
            }
        }
        return $goal_amount;
    }

    /**
     * Internal function used to bind popup
     * @author Dhara
     * @param type $ci
     * @param type $shopInfo
     * @param type $goal_amount
     * @param type $main_goal_amount
     * @param type $reached_ratio
     * @param type $is_calculate_ratio
     * @return type
     */
    private function get_popup_content($ci, $shopInfo, $goal_amount,
            $main_goal_amount, $reached_ratio = 0, $is_calculate_ratio = 0)
    {
        $shop              = $shopInfo->domain;
        $shop_id           = $shopInfo->shop_id;
        $shop_currency     = $shopInfo->currency;
        $products_array    = array();
        $final_products    = array();
        $cart_variants     = array();
        $final_array       = [];
        $variant           = [];
        $bundleInfo        = $this->bundle->get_bundle_by_variant($ci['variant_id'],
                't');
        $last_used_variant = $ci['variant_id'];
        $content           = array('status' => ERROR, 'is_empty_cart' => 0);


        if ($bundleInfo)
        {
            $isPopupShown = 1;
            $this->load->model('Discounts');
            $this->load->model('DiscountCodes');
            foreach ($bundleInfo as $k => $bprod) {
                $bundle_id      = $bprod->bundle_id;
                $getbundle_info = $bprod;
                $discount_info  = $this->DiscountCodes->get_discount_by_id($getbundle_info->discount_id);
                if ($getbundle_info->status != '0')
                {
                    if ($k == 0)
                    {
                        $first_bundle = $getbundle_info;
                    }
                    $isPopupShown = $this->checkConditions($getbundle_info,
                            $isPopupShown, $bprod, $ci['quantity']);
//                                echo ' || $isPopupShown ' . $isPopupShown;
//                    $is_trigger_stock_allowed = $this->check_trigger_stock($getbundle_info, $bprod);
//                    if ($is_trigger_stock_allowed == 1) {
                    if ($isPopupShown == 1)
                    {
                        $products = $this->bundle->get_bundle_prods($bundle_id,
                                'p');
                        if ($products)
                        {
                            foreach ($products as $key => $prod) {
                                $products_array[$key]            = $this->products->get_product_by_id($prod->product_id);
                                $products_array[$key][0]->bundle = $getbundle_info;
                                $products_array[$key][0]->discountcode_title = $discount_info[0]->title;

                                if ($products_array)
                                {
                                    $prodvariants = $this->products->get_product_variant($products_array[$key][0]->product_id);
                                    if ($prodvariants)
                                    {
                                        if ($getbundle_info->check_stock == 1)
                                        {
                                            $variantsArray = [];
                                            $i             = 0;
                                            foreach ($prodvariants as $key2 =>
                                                        $variants) {
                                                $isTrue = $this->check_bundle_stock($variants);
                                                if ($isTrue == 1)
                                                {
                                                    $variantsArray[$i] = $variants;
                                                    $i++;
                                                }
                                            }
                                            if (!empty($variantsArray))
                                            {
                                                $products_array[$key][0]->variants = $variantsArray;
                                            } else
                                            {
                                                unset($products_array[$key]);
                                            }
                                        } else
                                        {
                                            foreach ($prodvariants as $key2 =>
                                                        $variants) {
                                                $products_array[$key][0]->variants[$key2] = $variants;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $final_array = array_merge($final_array, $products_array);
                    }
//                    }
                }
            }
        }

        if (count($final_array) > 0)
        {
            $firstProduct  = $final_array[0][0]->product_id;
            $bundleData    = $this->bundle->get($shop_id,
                    $first_bundle->bundle_id, 1);
            $bundle_title  = '';
            $bundle_goal   = 0;
            $discount_type = 0;
            $bundle_id     = 0;
            $variant_price = 0;
            $goal_text     = '';
            $default_text  = '';
            $isGoalAmount  = 0;
            $is_head_desc  = 0;
            if ($first_bundle)
            {
                $bundle_title  = $first_bundle->bundle_title;
                $variant_price = $first_bundle->price;
                $bundle_goal   = $first_bundle->discount_goal_amount;
                $discount_type = $first_bundle->discount_type;
                $bundle_id     = $first_bundle->bundle_id;
                $value_type    = $first_bundle->value_type;
                if ($first_bundle->discount_type != 0 && $first_bundle->discount_goal_amount != 0)
                {
                    if ($value_type != 'percentage')
                    {
                        $isGoalAmount = 1;
                    }
                    $goal_text    = $this->Discounts->get_goal_away_text($shop_currency,
                            $first_bundle->discount_type,
                            $first_bundle->value_type, $first_bundle->value);
                    $default_text = $goal_text['default'];
                    $goal_text    = $goal_text['text'];
                }
                $is_head_desc = ($first_bundle->value_type == 'fixed_amount' || $first_bundle->value_type == 'free_shipping') ? 1 : 0;
            }
            if ($is_calculate_ratio == 1)
            {
                $main_goal_amount = $bundle_goal;
//                $goal_amount = $variant_price;
                if ($main_goal_amount != 0)
                        $reached_ratio    = round(($goal_amount * 100) / $main_goal_amount);
            }
//            echo $main_goal_amount.'='.$goal_amount.'='.$bundle_goal.'='.$reached_ratio;exit;
            $setting    = $this->setting->find_all($shop_id);
//            prExit($final_array);
            $data       = array(
                'products_array'      => $final_array,
                'bundle_title'        => $bundle_title,
                'bundle_id'           => $bundle_id,
                'cart_variants'       => $cart_variants,
                'shop_currency'       => $shop_currency,
                'setting'             => $setting,
                'bundleData'          => $first_bundle,
                'firstProduct'        => $firstProduct,
                'bundle_goal'         => $bundle_goal,
                'variantPrice'        => $variant_price,
                'total_products'      => count($final_array),
                'reached_goal_amount' => $main_goal_amount,
                'remain_goal_amount'  => $main_goal_amount - $goal_amount,
                'reached_ratio'       => $reached_ratio,
                'goal_text'           => $goal_text,
                'default_text'        => $default_text,
                'is_head_desc'        => $is_head_desc
            );
//            prExit($data);
            $content    = $this->load->view('front/popup.php', $data, true);
            $isSkipNext = 1;
            $isPopUp    = 1;
            $content    = array('status'            => SUCCESS, 'goal_away_text'    => $goal_text,
                'content'           => $content, 'goal_amount'       => $goal_amount,
                'no_of_bundle'      => count($final_array),
                'bundle_goal'       => $bundle_goal, 'bundle_id'         => $bundle_id,
                'discount_type'     => $discount_type,
                'isGoalAmount'      => $isGoalAmount, 'last_used_variant' => $last_used_variant,
                'isSkipNext'        => $isSkipNext);
        }
        return $content;
    }

	private function get_popup_contentextra($ci, $shopInfo, $goal_amount,
            $main_goal_amount, $reached_ratio = 0, $is_calculate_ratio = 0)
    {
        $shop              = $shopInfo->domain;
        $shop_id           = $shopInfo->shop_id;
        $shop_currency     = $shopInfo->currency;
        $products_array    = array();
        $final_products    = array();
        $cart_variants     = array();
        $final_array       = [];
        $variant           = [];
        $bundleInfo        = $this->bundle->get_bundle_by_variant($ci['variant_id'],
                't');
        $last_used_variant = $ci['variant_id'];
        $content           = array('status' => ERROR, 'is_empty_cart' => 0);


        if ($bundleInfo)
        {
            $isPopupShown = 1;
            $this->load->model('Discounts');
            foreach ($bundleInfo as $k => $bprod) {
                $bundle_id      = $bprod->bundle_id;
                $getbundle_info = $bprod;
                if ($getbundle_info->status != '0')
                {
                    if ($k == 0)
                    {
                        $first_bundle = $getbundle_info;
                    }
                    $isPopupShown = $this->checkConditions($getbundle_info,
                            $isPopupShown, $bprod, $ci['quantity']);
//                                echo ' || $isPopupShown ' . $isPopupShown;
//                    $is_trigger_stock_allowed = $this->check_trigger_stock($getbundle_info, $bprod);
//                    if ($is_trigger_stock_allowed == 1) {
                    if ($isPopupShown == 1)
                    {
                        $products = $this->bundle->get_bundle_prods($bundle_id,
                                'p');
                        if ($products)
                        {
                            foreach ($products as $key => $prod) {
                                $products_array[$key]            = $this->products->get_product_by_id($prod->product_id);
                                $products_array[$key][0]->bundle = $getbundle_info;

                                if ($products_array)
                                {
                                    $prodvariants = $this->products->get_product_variant($products_array[$key][0]->product_id);
                                    if ($prodvariants)
                                    {
                                        if ($getbundle_info->check_stock == 1)
                                        {
                                            $variantsArray = [];
                                            $i             = 0;
                                            foreach ($prodvariants as $key2 =>
                                                        $variants) {
                                                $isTrue = $this->check_bundle_stock($variants);
                                                if ($isTrue == 1)
                                                {
                                                    $variantsArray[$i] = $variants;
                                                    $i++;
                                                }
                                            }
                                            if (!empty($variantsArray))
                                            {
                                                $products_array[$key][0]->variants = $variantsArray;
                                            } else
                                            {
                                                unset($products_array[$key]);
                                            }
                                        } else
                                        {
                                            foreach ($prodvariants as $key2 =>
                                                        $variants) {
                                                $products_array[$key][0]->variants[$key2] = $variants;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $final_array = array_merge($final_array, $products_array);
                    }
//                    }
                }
            }
        }

        if (count($final_array) > 0)
        {
            $firstProduct  = $final_array[0][0]->product_id;
            $bundleData    = $this->bundle->get($shop_id,
                    $first_bundle->bundle_id, 1);
            $bundle_title  = '';
            $bundle_goal   = 0;
            $discount_type = 0;
            $bundle_id     = 0;
            $variant_price = 0;
            $goal_text     = '';
            $default_text  = '';
            $isGoalAmount  = 0;
            $is_head_desc  = 0;
            if ($first_bundle)
            {
                $bundle_title  = $first_bundle->bundle_title;
                $variant_price = $first_bundle->price;
                $bundle_goal   = $first_bundle->discount_goal_amount;
                $discount_type = $first_bundle->discount_type;
                $bundle_id     = $first_bundle->bundle_id;
                $value_type    = $first_bundle->value_type;
                if ($first_bundle->discount_type != 0 && $first_bundle->discount_goal_amount != 0)
                {
                    if ($value_type != 'percentage')
                    {
                        $isGoalAmount = 1;
                    }
                    $goal_text    = $this->Discounts->get_goal_away_text($shop_currency,
                            $first_bundle->discount_type,
                            $first_bundle->value_type, $first_bundle->value);
                    $default_text = $goal_text['default'];
                    $goal_text    = $goal_text['text'];
                }
                $is_head_desc = ($first_bundle->value_type == 'fixed_amount' || $first_bundle->value_type == 'free_shipping') ? 1 : 0;
            }
            if ($is_calculate_ratio == 1)
            {
                $main_goal_amount = $bundle_goal;
//                $goal_amount = $variant_price;
                if ($main_goal_amount != 0)
                        $reached_ratio    = round(($goal_amount * 100) / $main_goal_amount);
            }
//            echo $main_goal_amount.'='.$goal_amount.'='.$bundle_goal.'='.$reached_ratio;exit;
            $setting    = $this->setting->find_all($shop_id);
//            prExit($final_array);
            $data       = array(
                'products_array'      => $final_array,
                'bundle_title'        => $bundle_title,
                'bundle_id'           => $bundle_id,
                'cart_variants'       => $cart_variants,
                'shop_currency'       => $shop_currency,
                'setting'             => $setting,
                'bundleData'          => $first_bundle,
                'firstProduct'        => $firstProduct,
                'bundle_goal'         => $bundle_goal,
                'variantPrice'        => $variant_price,
                'total_products'      => count($final_array),
                'reached_goal_amount' => $main_goal_amount,
                'remain_goal_amount'  => $main_goal_amount - $goal_amount,
                'reached_ratio'       => $reached_ratio,
                'goal_text'           => $goal_text,
                'default_text'        => $default_text,
                'is_head_desc'        => $is_head_desc
            );
//            prExit($data);
            $content    = $this->load->view('front/popup-extra.php', $data, true);

            $isSkipNext = 1;
            $isPopUp    = 1;
            $content    = array('status'            => SUCCESS, 'goal_away_text'    => $goal_text,
                'content'           => $content, 'goal_amount'       => $goal_amount,
                'no_of_bundle'      => count($final_array),
                'bundle_goal'       => $bundle_goal, 'bundle_id'         => $bundle_id,
                'discount_type'     => $discount_type,
                'isGoalAmount'      => $isGoalAmount, 'last_used_variant' => $last_used_variant,
                'isSkipNext'        => $isSkipNext);
        }
       return $content;
    }

    /**
     * check bundle product stock
     * @author Dhara
     * @param type $variant
     * @return int
     */
    function check_bundle_stock($variant)
    {
        $isStockValid = 1;
        $isStockAdded = 0;
        if ($variant->inventory_management == 'shopify')
        {
            if ($variant->inventory_policy != 'continue')
            {
                if ($variant->inventory < 1)
                {
                    $isStockValid = 0;
                }
            }
            $isStockAdded = 1;
        }
        return $isStockValid;
    }

    /**
     * check trigger product stock
     * @author Dhara
     * @param type $getbundle_info
     * @param type $variant
     * @return int
     */
    function check_trigger_stock($getbundle_info, $variant)
    {
        $isTriggerStockValid = 1;
        $isTriggerStockAdded = 0;
        if ($getbundle_info->check_stock_trigger == 1)
        {
            if (($variant->inventory_management == 'shopify'))
            {
                if ($variant->inventory_policy != 'continue')
                {
                    if ($variant->inventory < 1)
                    {
                        $isTriggerStockValid = 0;
                    }
                }
                $isTriggerStockAdded = 1;
            }
        }
        return $isTriggerStockValid;
    }

    /**
     * This will check all conditions to show popup
     * @author Dhara
     * @param type $getbundle_info
     * @param type $isPopupShown
     * @return int
     */
    function checkConditions($getbundle_info, $isPopupShown, $variant, $quantity)
    {

        $isDateValid  = 1;
        $isStockValid = 1;
        $isQtyValid   = 1;

        $isDateAdded  = 0;
        $isStockAdded = 0;
        $isQtyAdded   = 0;
        $checkArray   = [];

        if (($getbundle_info->min_qty > 0) || ($getbundle_info->max_qty > 0))
        {
            if (($getbundle_info->min_qty > 0))
            {
                if ($quantity < $getbundle_info->min_qty)
                {
                    $isQtyValid = 0;
                }
                $isQtyAdded = 1;
            }
            if (($getbundle_info->max_qty > 0))
            {
                if ($quantity > $getbundle_info->max_qty)
                {
                    $isQtyValid = 0;
                }
                $isQtyAdded = 1;
            }
            array_push($checkArray, $isQtyValid);
        }
        //Date checking
        if (($getbundle_info->start_date != '0000-00-00') || ($getbundle_info->end_date != '0000-00-00'))
        {
            if ($getbundle_info->start_date != '0000-00-00')
            {
                if ((date('Y-m-d') < $getbundle_info->start_date))
                {
//                echo 'start_date';
                    $isDateValid = 0;
                }
                $isDateAdded = 1;
            }
            if ($getbundle_info->end_date != '0000-00-00')
            {
                if ((date('Y-m-d') > $getbundle_info->end_date))
                {
//                echo 'end_date';
                    $isDateValid = 0;
                }
                $isDateAdded = 1;
            }
            array_push($checkArray, $isDateValid);
        }

        if (!empty($checkArray))
        {
            //If bundle has OR condition then if only one condition found then will return 1
            if ($getbundle_info->upsell_condition == 0)
            {
                if (in_array(1, $checkArray))
                {
                    return 1;
                } else
                {
                    return 0;
                }
            } else
            {//If bundle has AND condition then all condition has to match
                if (in_array(0, $checkArray))
                {
                    return 0;
                } else
                {
                    return 1;
                }
            }
        } else
        {
            return 1;
        }
        return 1;
    }

    /**
     * Add record of bundle view
     * @author Dhara
     */
    function updateBundleView()
    {
        $bundleId = '';
        if ($_POST['bundle_id'])
        {
            $bundleData = array('shop_id'      => $_POST['shopId'], 'bundle_id'    => $_POST['bundle_id'],
                'created_date' => date('Y-m-d'));
            $bundleId   = $this->bundle->insert_views($bundleData,
                    $_POST['bundle_id']);
        }
        echo $bundleId;
    }

    function getCrossSellProducts()
    {
        $crossSellProducts = [];
        $shopInfo          = $this->shop->get_by_shop_id($_POST['shop_id']);

        $currency = $shopInfo->currency;
        if ($_POST['slugType'] != 'number')
                $product  = $this->products->get_product_id_by_slug($_POST['slug'],
                    1);
        else
                $product  = $this->products->get_product_variant_by_id($_POST['slug'],
                    1);

        $isCrossSellBundle = $this->CrossSellBundle->getCrossSellTargetProduct($product->product_id,true);
        $content           = '';
        $response          = ['status' => ERROR];
        if (!empty($isCrossSellBundle))
        {
            $crossSellProducts = $this->CrossSellBundle->getCrossSellProductDetails($isCrossSellBundle->cross_sell_bundle_id);
            $finalcrossSellProducts = [];
            if (!empty($crossSellProducts))
            {
                foreach ($crossSellProducts as $prod){
                    $checkInventory = $this->checkVariantInventory($prod);
                    if($checkInventory==1){
                        $finalcrossSellProducts[] = $prod;
                    }
                }
                $data['crossSellProducts'] = $finalcrossSellProducts;
                $data['targetProduct']     = $isCrossSellBundle;
                $data['currecny']          = $currency;
                $content                   = $this->load->view('front/cross-sell-layout',
                        $data, true);
                $response                  = ['status' => SUCCESS, 'content' => $content];
            }
        }
        echo json_encode($response);
        exit;
    }

    private function checkVariantInventory($variant)
    {
        $isTriggerStockValid = 1;
        if (($variant->inventory_management == 'shopify'))
        {
            if ($variant->inventory_policy != 'continue')
            {
                if ($variant->inventory < 1)
                {
                    $isTriggerStockValid = 0;
                }
            }
        }
        return $isTriggerStockValid;
    }

    /**
     * Front Upsell Get Random Videos
     * @author Rahul Arora
     */
    function getRandomVideo()
    {
        $shopId = $_POST['shopId'];

        $this->load->model('ReceiptVideo');

        $record = $this->ReceiptVideo->getRecord($shopId);
        if(!empty($record)){
        $count = 0;
        if((!empty($record->video_url_1) && (!empty($record->redirection_url_1)))){
           $count = 1;
        }
        if((!empty($record->video_url_2) && (!empty($record->redirection_url_2)))){
           $count = $count + 1;
        }
        if((!empty($record->video_url_3) && (!empty($record->redirection_url_3)))){
           $count = $count + 1;
        }
        $min    = 1;
        $max    = $count;
        $rand   = rand($min, $max);
        if ($rand == 1)
        {
            $video    = $record->video_url_1;
            $redirect = $record->redirection_url_1;
        } elseif ($rand == 2)
        {
            $video    = $record->video_url_2;
            $redirect = $record->redirection_url_2;
        } else
        {
            $video    = $record->video_url_3;
            $redirect = $record->redirection_url_3;
        }

        echo json_encode(['status'=>'success','video' => $video, 'redirectUrl' => $redirect, 'rand' => $rand,
            'style' => $record->button_css,'text'=>$record->button_text,'title'=>$record->title]);
        exit;
        }else{
            echo json_encode(['status'=>'error']);exit;
        }
    }

    public function getProductVariant()
    {
        $variant = $this->products->getProductVariantBySlug($this->input->post());
        $returnArray['status'] = ERROR;
        if(!empty($variant)){
            $returnArray['status'] = SUCCESS;
            $returnArray['variant_id'] = $variant->variant_id;
        }
        echo json_encode($returnArray);exit;
    }

    /**
     * Front Upsell Update Count
     * @author Rahul Arora
     */
    function updateVideoCount()
    {
        $key    = $_POST['key'];
        $shopId = $_POST['shopId'];

        $this->load->model('ReceiptVideo');

        $record = $this->ReceiptVideo->getRecord($shopId);

        $data['id'] = $record->id;

        if ($key == 1)
        {
            $data['count_url_1'] = $record->count_url_1 + 1;
        } elseif ($key == 2)
        {
            $data['count_url_2'] = $record->count_url_2 + 1;
        } else
        {
            $data['count_url_3'] = $record->count_url_3 + 1;
        }

        $this->ReceiptVideo->save($data, $shopId, 1);

        echo "success";
    }

}
