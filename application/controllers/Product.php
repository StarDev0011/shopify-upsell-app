<?php

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('shop');
        $this->load->model('products');
        $this->load->model('bundle');
    }

    /**
     * Search products 
     * @author Dhara
     */
    public function search_by_keyword() {
        $prodList = $this->products->get($_POST['shop_id'], $_POST['keyword'], $_POST['selected'],$_POST['category'],'*',$_POST['type']);
        $shopInfo = $this->shop->get_by_shop_id($_POST['shop_id']);
        $prodIds = '';
        if ($prodList) {
            foreach ($prodList as $key => $prod) {
                if ($prod->product_options != '') {
                    $ProductVariants = $this->products->get_variants($prod->product_id);
                    $prodList[$key]->ProductVariants = $ProductVariants;
                    $prodIds.=$prod->product_id.',';
                }
            }
        }
        if(isset($_POST['bundle']) && $_POST['bundle']==1){
            if($_POST['type']=='cross-sell'){
                $editSelectedTargetAry = !empty($_POST['selected']) ? explode(',', $_POST['selected']) : array();
                $editSelectedBundleAry = !empty($_POST['another_selected']) ? explode(',', $_POST['another_selected']) : array();
                $response = $this->load->view('admin/cross-sell/_ajax_cross_sell_products', array('shop_currency'=> $shopInfo->currency,'prodList' => $prodList, 'editSelectedTargetAry' => $editSelectedTargetAry,'editSelectedBundleAry'=>$editSelectedBundleAry), true);
            }else{
                $editSelectedTargetAry = !empty($_POST['selected']) ? explode(',', $_POST['selected']) : array();
                $editSelectedBundleAry = !empty($_POST['another_selected']) ? explode(',', $_POST['another_selected']) : array();
                $response = $this->load->view('admin/cross-sell/_ajax_target_product_list', array('shop_currency'=> $shopInfo->currency,'prodList' => $prodList, 'editSelectedTargetAry' => $editSelectedTargetAry,'editSelectedBundleAry'=>$editSelectedBundleAry), true);
            }
        }else{
            if ($_POST['type'] == 'trigger') {
                $editSelectedTargetAry = !empty($_POST['selected']) ? explode(',', $_POST['selected']) : array();
                $editSelectedTriggerAry = !empty($_POST['another_selected']) ? explode(',', $_POST['another_selected']) : array();
                $response = $this->load->view('admin/bundles/_ajax_trigger_product_list', array('shop_currency'=> $shopInfo->currency,'prodList' => $prodList, 'editSelectedTargetAry' => $editSelectedTargetAry,'editSelectedTriggerAry'=>$editSelectedTriggerAry), true);
            } else {
                $editSelectedTriggerAry = !empty($_POST['selected']) ? explode(',', $_POST['selected']) : array();
                $editSelectedTargetAry = !empty($_POST['another_selected']) ? explode(',', $_POST['another_selected']) : array();
                $response = $this->load->view('admin/bundles/_ajax_target_product_list', array('shop_currency'=> $shopInfo->currency,'prodList' => $prodList, 'editSelectedTargetAry' => $editSelectedTargetAry,'editSelectedTriggerAry'=>$editSelectedTriggerAry), true);
            }
        }
        echo json_encode(array('content' => $response,'prodIds'=> rtrim($prodIds,',')));
        exit();
    }
}
