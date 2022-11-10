<?php

class Front extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('shop');
        $this->load->model('products');
        $this->load->model('bundle');
        $this->load->model('setting');
    }

    /**
     * Front Upsell popup
     * @author Dhara
     */
    function getCartPagePopup() {
        $shopId = $_POST['shopId'];
        $product_slug = isset($_POST['product_slug']) ? $_POST['product_slug'] : '';
        $variantId = isset($_POST['variantId']) ? $_POST['variantId'] : '';
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
        $cartItems = isset($_POST['cart_items']) ? $_POST['cart_items'] : [];
        $isUpsellDiv = isset($_POST['isUpsellDiv']) ? $_POST['isUpsellDiv'] : 0;
        $cur_cart_prodID = '';
        if ($product_slug != '') {
            $product_slugarr = explode('?', $product_slug);
            if ($product_slugarr[0]) {
                $product_slug = $product_slugarr[0];
            }
            $cartProduct = $this->products->get_product_id_by_slug($product_slug, 1);
            if ($cartProduct) {
                $cur_cart_prodID = $cartProduct->product_id;
            }
        }
        //if ($quantity == 1) {
            if (!empty($cartItems)) {
                foreach ($cartItems as $ci) {
                    if($ci['variant_id']==$variantId){
                        //$quantity=$ci['quantity'];
                        $quantity+=$ci['quantity'];//we have to add 1 bcaz at the time of add to cart we are considering one selected quantity added
                        continue;
                    }
                }
            }
        //}
            
        $cart_variants = array();
//        $domain = getDomainId($domain, SERVER_MODE);
//        if (SERVER_MODE == 0) {
            $shopInfo = $this->shop->get_by_shop_id($shopId);
//        } else {
//            $shopInfo = $this->shop->get_by_domain($domain);
//        }
        $products_array = array();
        $final_products = array();
        $bundleInfo = $this->bundle->get_bundle_by_prod($cur_cart_prodID, 't');
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
            if ($cur_cart_prodID != '') {
                if ($bundleInfo) {
                    $isPopupShown = 1;
                    foreach ($bundleInfo as $bprod) {
                        $bundle_id = $bprod->bundle_id;
                        $getbundle_info = $this->bundle->getbundle_info($bundle_id);
                        if (!empty($getbundle_info)) {
                            if ($getbundle_info[0]->status != '0') {
                                $isPopupShown = $this->checkConditions($getbundle_info, $isPopupShown, $variant, $quantity);
//                                echo ' || $isPopupShown ' . $isPopupShown;
//                                prExit($getbundle_info);
                                if ($isPopupShown == 1) {
                                    $products = $this->bundle->get_bundle_prods($bundle_id, 'p');
                                    if ($products) {
                                        foreach ($products as $prod) {
                                            $info[$prod->product_id] = $getbundle_info[0];
                                            array_push($pop_array, $prod->product_id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (count($pop_array) > 0) {
                if ($pop_array) {
                    $mainProd = $this->products->get_product_by_id($cur_cart_prodID);
                    $mainProdVariant = $this->products->get_product_variant($cur_cart_prodID);
                    $mainProd[0]->variants = $mainProdVariant;
                    $mainProd[0]->bundle = $info[$pop_array[0]];
                    foreach ($pop_array as $key => $prods) {
                        $products_array[$key] = $this->products->get_product_by_id($prods);
                        $products_array[$key][0]->bundle = $info[$prods];
                        if ($products_array) {
                            $prodvariants = $this->products->get_product_variant($products_array[$key][0]->product_id);
                            if ($prodvariants) {
                                foreach ($prodvariants as $key2 => $variants) {
                                    $products_array[$key][0]->variants[$key2] = $variants;
                                }
                            }
                        }
                    }
                    //if single bundle and multiple products
                    /*if ($noOfBundle == 1 && (count($products_array) > 1)) {
                        array_unshift($products_array, $mainProd);
                    }*/
                    $firstProduct = $products_array[0][0]->product_id;
                }
//                echo '$noOfBundle='.$noOfBundle.'$products_array='.count($products_array);exit;
                /* Get Bundle details */
                $bundleData = $this->bundle->get($shop_id, $bundleInfo[0]->bundle_id, 1);
                $bundle_title = '';
                if ($bundleData) {
                    $bundle_title = $bundleData->bundle_title;
                }
                $setting = $this->setting->find_all($shop_id);
                $data = array(
                    'products_array' => $products_array,
                    'bundle_title' => $bundle_title,
                    'bundle_id' => $bundle_id,
                    'cart_variants' => $cart_variants,
                    'shop_currency' => $shop_currency,
                    'setting' => $setting,
                    'bundleData' => $bundleData,
                    'firstProduct' => $firstProduct,
                    'variantPrice'=>$variant[0]->price,
                );
                
                /*if (($noOfBundle <= 1) && (count($products_array) > 1)) {//single bundle and mutliple products
                    if($isUpsellDiv==0){//if popup found then div page
                        $content = $this->load->view('front/multiple-upsell-product-popup', $data, true);
                        $isPopUp=1;
                    }else{//if if popup found then div page
                        $content = $this->load->view('front/multiple-upsell-product-div', $data, true);
                        $isPopUp=0;
                    }
                    $isSkipNext=0;
                } else {*/ //multiple bundle with multiple products
                    $content = $this->load->view('front/popup.php', $data);
                    exit();
                    $isSkipNext=1;
                    $isPopUp=1;
                //}
                echo json_encode(array('status' => SUCCESS, 'content' => $content,'q'=>$quantity, 'no_of_bundle' => $noOfBundle,'isSkipNext'=>$isSkipNext,'isPopUp'=>$isPopUp));
                exit;
            } else {
                echo json_encode(array('status' => ERROR));
                exit;
            }
        }
    }

    /**
     * This will check all conditions to show popup
     * @author Dhara
     * @param type $getbundle_info
     * @param type $isPopupShown
     * @return int
     */
    function checkConditions($getbundle_info, $isPopupShown, $variant, $quantity) {
        $isPriceValid = 1;
        $isDateValid = 1;
        $isStockValid = 1;
        $isTriggerStockValid = 1;
        $isQtyValid = 1;

        $isPriceAdded = 0;
        $isDateAdded = 0;
        $isStockAdded = 0;
        $isTriggerStockAdded = 0;
        $isQtyAdded = 0;
        $checkArray = [];
        if (($getbundle_info[0]->min_price > 0) || ($getbundle_info[0]->max_price > 0)) {
            if (($getbundle_info[0]->min_price > 0)) {
                if ($variant[0]->price < $getbundle_info[0]->min_price) {
//                echo 'min_price';
                    $isPriceValid = 0;
                }
                $isPriceAdded = 1;
            }
            if (($getbundle_info[0]->max_price > 0)) {
                if ($variant[0]->price > $getbundle_info[0]->max_price) {
//                echo 'max_price';
                    $isPriceValid = 0;
                }
                $isPriceAdded = 1;
            }
            array_push($checkArray, $isPriceValid);
        }
        if (($getbundle_info[0]->min_qty > 0) || ($getbundle_info[0]->max_qty > 0)) {
            if (($getbundle_info[0]->min_qty > 0)) {
                if ($quantity < $getbundle_info[0]->min_qty) {
//                echo 'min_price';
                    $isQtyValid = 0;
                }
                $isQtyAdded = 1;
            }
            if (($getbundle_info[0]->max_qty > 0)) {
                if ($quantity > $getbundle_info[0]->max_qty) {
//                echo 'max_price';
                    $isQtyValid = 0;
                }
                $isQtyAdded = 1;
            }
            array_push($checkArray, $isQtyValid);
        }
        //Date checking
        if (($getbundle_info[0]->start_date != '0000-00-00') || ($getbundle_info[0]->end_date != '0000-00-00')) {
            if ($getbundle_info[0]->start_date != '0000-00-00') {
                if ((date('Y-m-d') < $getbundle_info[0]->start_date)) {
//                echo 'start_date';
                    $isDateValid = 0;
                }
                $isDateAdded = 1;
            }
            if ($getbundle_info[0]->end_date != '0000-00-00') {
                if ((date('Y-m-d') > $getbundle_info[0]->end_date)) {
//                echo 'end_date';
                    $isDateValid = 0;
                }
                $isDateAdded = 1;
            }
            array_push($checkArray, $isDateValid);
        }
        if ($getbundle_info[0]->check_stock == 1) {
//            echo 'inventory';
            if ($variant[0]->inventory < 1) {
                $isStockValid = 0;
            }
            $isStockAdded = 1;
            array_push($checkArray, $isStockValid);
        }
//        pr($getbundle_info[0]);
//        pr($variant);
//        exit;
        if ($getbundle_info[0]->check_stock_trigger == 1) {
            if (($variant[0]->inventory_management=='shopify') && ($variant[0]->inventory_policy=='continue')) {
                if($variant[0]->inventory <= 1){
                    $isTriggerStockValid = 0;
                }
                $isTriggerStockAdded = 1;
            }
            array_push($checkArray, $isTriggerStockValid);
        }
        if (!empty($checkArray)) {
            //If bundle has OR condition then if only one condition found then will return 1
            if($getbundle_info[0]->upsell_condition==0){
                if (in_array(1, $checkArray)) {
                    return 1;
                } else {
                    return 0;
                }
            }else{//If bundle has AND condition then all condition has to match
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
     * Add record of bundle view
     * @author Dhara
     */
    function updateBundleView() {
        $bundleId = '';
        if ($_POST['bundle_id']) {
            $bundleData = array('bundle_id' => $_POST['bundle_id'], 'created_date' => date('Y-m-d'));
            $bundleId = $this->bundle->insert_views($bundleData, $_POST['bundle_id']);
        }
        echo $bundleId;
    }
}
