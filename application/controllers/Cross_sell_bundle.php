<?php

/**
 * 0: Target product, 1: Cross-sell oroducts
 */
class Cross_sell_bundle extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('shop');
        $this->load->model('products');
        $this->load->model('CrossSellBundle');
        $this->load->model('Discounts');
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
    }

    /**
     * Bundle listing page
     * @author Dhara
     */
    public function index()
    {
        $shop_id    = $this->shopId;
        $bundleList = $this->CrossSellBundle->getBundles($shop_id, 1);
//        echo last_query();
//        prExit($bundleList);
        $data       = array(
            'shop_id'    => $shop_id,
            'curr_uri'   => 'cross-sell-list',
            'bundleList' => $bundleList,
        );
        $this->view('admin/cross-sell/index', $data);
    }

    public function create($bundle_id = '')
    {
        $shop     = $this->shopDomain;
        $shopInfo = $shop     = $this->shop->get_by_domain($shop);
        if ($shopInfo)
        {
            $shop                  = $this->shopDomain;
            $shop_id               = $this->shopId;
            $shop_currency         = $this->shopCurrency;
            $prodList              = $this->products->get($shop_id);
            $collectionsList       = $this->products->get_collections($shop_id);
            $bundleData            = array();
            $bundleProds           = array();
            $bundleTrigers         = array();
            $editSelectedTarget    = '';
            $editSelectedTargetAry = [];
            $editSelectedBundle    = '';
            $editSelectedBundleAry = [];
            $editSelectedBundleProductAry = [];
            $discountCodes = [];
            if (!empty($this->input->get('id')))
            {
                $bundleData                               = $this->CrossSellBundle->get($this->input->get('id'));
                $bundleProds                              = $this->CrossSellBundle->getBundleProducts($this->input->get('id'),
                        1,'0');
                $crossSellProds                              = $this->CrossSellBundle->getBundleProducts($this->input->get('id'),
                        1,'1');
//                last_query();
                $editSelectedTargetAry                    = $bundleProds;
                $editSelectedTarget                       = implode(',',
                        array_column($editSelectedTargetAry, 'product_id'));

                foreach ($editSelectedTargetAry as $key => $prods) {
                    $prodvariants = $this->products->get_product_variant($prods['product_id']);
                    $editSelectedTargetAry[$key]['ProductVariants'] = $prodvariants;
                }
                
                $editSelectedBundleAry                    = $crossSellProds;
                $editSelectedBundle                       = implode(',',
                        array_column($editSelectedBundleAry, 'product_id'));
//                prExit($editSelectedTargetAry);
                if ($editSelectedBundleAry)
                {
                    foreach ($editSelectedBundleAry as $key => $prod) {
                        if ($prod['product_options'] != '')
                        {
                            $ProductVariants                                = $this->products->get_variants($prod['product_id']);
                            $editSelectedBundleAry[$key]['ProductVariants'] = $ProductVariants;
                            array_push($editSelectedBundleProductAry, $prod['product_id']);
                        }
                    }
                }
                $discountCodes = $this->Discounts->get_discount_types($bundleData->discount_type,$shop_id);
            }
            if ($prodList)
            {
                foreach ($prodList as $key => $prod) {
                    if ($prod->product_options != '')
                    {
                        $ProductVariants                 = $this->products->get_variants($prod->product_id);
                        $prodList[$key]->ProductVariants = $ProductVariants;
                    }
                }
            }
//            pr($editSelectedBundleProductAry);
//            pr($editSelectedBundle);
//            prExit($discountCodes);
            $discountData = $this->Discounts->get_discounts($shop_id);
            $data = array(
                'curr_uri'              => 'cross_bundle_create',
                'bundleData'            => $bundleData,
                'prodList'              => $prodList,
                'shop_id'               => $shop_id,
                'shop'                  => $shop,
                'shop_currency'         => $shop_currency,
                'collectionsList'       => $collectionsList,
                'editSelectedTarget'    => $editSelectedTarget,
                'editSelectedBundle'    => $editSelectedBundle,
                'editSelectedTargetAry' => $editSelectedTargetAry,
                'editSelectedBundleAry' => $editSelectedBundleAry,
                'editSelectedBundleProductAry' => $editSelectedBundleProductAry,
                'curr_uri'              => 'cross-sell-list',
                'discountData' => $discountData,
                'discountCodes' => $discountCodes,
            );
        } else
        {
            redirect('auth/auth?shop=' . $shop);
        }
        $this->view('admin/cross-sell/form', $data);
    }

    public function insert()
    {
        $response['status'] = ERROR;

        if ($this->trialDays <= 0 && in_array($this->chargeStatus,
                        $this->shopRejectedChargeStatus) || $this->shopStatus == "frozen")
        {
            $response['msg'] = 'You have been ' . $this->chargeStatus . ' payment. You cannot proceed further.';
            echo json_encode($response);
            exit;
        }
        if (!empty($this->input->post()))
        {
            $discount_id = 0;
            $discount = $this->input->post('discount_id');
            if (isset($discount))
                $discount_id = explode('|', $discount)[0];
            if (empty($this->input->post('id')))
            {
                $id = $this->CrossSellBundle->insert([
                    'shop_id'       => $this->input->post('shop_id'),
                    'bundle_title'  => $this->input->post('bundle_title'),
                    'offer_headline'  => $this->input->post('offer_headline'),
                    'discount_type'  => $this->input->post('discount_type'),
                    'success_text'  => $this->input->post('success_text'),
                    'discount_id'  => $discount_id,
                    'status'        => 1,
                    'collection_id' => !empty($this->input->post('collection_id'))?$this->input->post('collection_id'):null
                    ]);
                if (!empty($id))
                {
                    $response['status'] = SUCCESS;
                }
            } else
            {
                $id                 = $this->input->post('id');
                $this->CrossSellBundle->delete_bundleProducts($id);
                $this->CrossSellBundle->update([
                    'bundle_title'  => $this->input->post('bundle_title'),
                    'discount_id'  => $discount_id,
                    'offer_headline'  => $this->input->post('offer_headline'),
                    'discount_type'  => $this->input->post('discount_type'),
                    'success_text'  => $this->input->post('success_text'),
                    'collection_id' => !empty($this->input->post('collection_id'))?$this->input->post('collection_id'):null
                    ], $id);
                $response['status'] = SUCCESS;
            }
            if ($_POST['target_product'] != '')
            {
                $products = explode(',', $this->input->post('target_product'));
                if ($products)
                {
                    foreach ($products as $p_key => $prods) {
                        if ($prods != '')
                        {
                            $data = array('cross_sell_bundle_id' => $id, 'product_id'           => $prods,
                                'type'                 => '0');
                            $this->CrossSellBundle->insert_bundle_product($data);
                        }
                    }
                }
            }
            if ($_POST['bundle_product'] != '')
            {
                $products = explode(',', $_POST['bundle_product']);
                if ($products)
                {
                    foreach ($products as $p_key => $prods) {
                        if ($prods != '')
                        {
                            $data = array('cross_sell_bundle_id' => $id, 'product_id'           => $prods,
                                'type'                 => '1');
                            $this->CrossSellBundle->insert_bundle_product($data);
                        }
                    }
                }
            }
        }
        echo json_encode($response);
        exit;
    }

    public function update_bundle()
    {
        $bundle             = $this->CrossSellBundle->get($_POST['bundle_id']);
        $products           = '';
        $bundle_pro         = '';
        $response['status'] = SUCCESS;

        if ($bundle->status == 0)
        {
            $response = $this->validate_trigger_products($_POST['bundle_id']);
            $status   = 1;
            $msg      = 'Record activated successfully';
        } else
        {
            $status = 0;
            $msg    = 'Record deactivated successfully';
        }
        if ($response['status'] == SUCCESS)
        {
            $bundleData         = array('status' => $status);
            $bundleId           = $this->CrossSellBundle->update($bundleData,
                    $_POST['bundle_id']);
            $response['msg']    = $msg;
            $response['status'] = SUCCESS;
        }
        echo json_encode($response);
        exit;
    }

    public function validate_trigger_products($bundleId)
    {
        $response['status'] = 'success';
        $selectedProducts   = $this->CrossSellBundle->getBundleProducts($bundleId,
                0, '0');
        $selectedProducts   = array_column($selectedProducts, 'product_id');
        $triggered_products = $this->CrossSellBundle->getOtherTargetBundleProducts($bundleId,
                $selectedProducts, $this->shopId);
        if (!empty($triggered_products))
        {
            $response['status'] = 'error';
            $response['msg']    = 'Target product is already used in other bundle.';
        }
        return $response;
    }

    public function validate_target_product()
    {
        $response['status'] = 'success';
        $trigger_product    = $this->input->post('trigger_product');
        $id                 = $this->input->post('id');
        if (!empty($id))
        {
            $response = $this->validate_trigger_products($id);
        } else
        {
            $triggered_products = $this->CrossSellBundle->getOtherTargetBundleProducts(0,
                    [$trigger_product], $this->shopId);
            if (!empty($triggered_products))
            {
                $response['status'] = 'error';
                $response['msg']    = 'Target product is already used in other bundle.';
            }
        }
        echo json_encode($response);
        exit;
    }

    public function delete_bundle()
    {
        $bundleId = $this->CrossSellBundle->delete($_POST['bundle_id']);
    }

    public function installation()
    {
        $data = array(
            'curr_uri' => 'cross-sell-list'
        );
        $this->view('admin/cross-sell/installation', $data);
    }

}
