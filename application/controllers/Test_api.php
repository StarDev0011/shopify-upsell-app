<?php

class Test_api extends CI_Controller {

    public $accessToken;
    public $shopObj;

    public function __construct() {
        ini_set('memory_limit', '-1');
        parent::__construct();
        $this->load->model('shop');
        $this->load->library('session');
        $this->load->library('Shopify');
    }

    public function snippet_create() {
        $this->shopObj = $this->shop->get_by_domain('jaydeep-store.myshopify.com');

        $shop_data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $this->shopObj->myshopify_domain,
            'ACCESS_TOKEN' => $this->shopObj->access_token
        );
        $this->shopify->setup($shop_data);
        $themes = $this->shopify->call(array('URL' => $this->shopObj->myshopify_domain . '/admin/themes.json'), true);
        $mainThemeId = '';
        foreach ($themes->themes as $theme) {
            if ($theme->role == 'main') {
                $mainThemeId = $theme->id;
                break;
            }
        }

//        $themesAssetd = $this->shopify->call(array('URL' => $this->shopObj->myshopify_domain . '/admin/themes/'.$mainThemeId.'/assets.json'),
//                                                          true);
//        prExit($themesAssetd);

        $data['asset'] = ['key' => 'snippets/smart-cross-sell.liquid',
            'value' => '{% comment %}
                    ============= Warning: PLEASE DO NOT EDIT THIS SNIPPET! =============
                    Editing this file could break Cross Sell app functionality. 
                    This file might be overwritten.
                    ============= Warning: PLEASE DO NOT EDIT THIS SNIPPET! =============
                    {% endcomment %}
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
                    '
        ];
//        prExit($data);
        $response = $this->shopify->call(array(
            'METHOD' => 'PUT',
            'DATA' => $data,
            'URL' => $this->shopObj->myshopify_domain . '/admin/themes/' . $mainThemeId . '/assets.json'
                ), true
        );
        echo $mainThemeId;
        prExit($response);
    }

    public function delete_script() {

        $this->shopObj = $this->shop->get_by_domain('jaydeep-store.myshopify.com');
        $shop_data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $this->shopObj->myshopify_domain,
            'ACCESS_TOKEN' => $this->shopObj->access_token
        );
        $this->shopify->setup($shop_data);

        $res = $this->shopify->call(array('URL' => $this->shopObj->myshopify_domain . '/admin/script_tags.json'), true);
        pr($res,'OLD script Tag: ');
        exit;
        if (!empty($res->script_tags[0])) {
            $id = $res->script_tags[0]->id;
            $script = $this->shopify->call(array('METHOD' => 'DELETE', 'URL' => $this->shopObj->myshopify_domain . '/admin/script_tags/' . $id . '.json'));
        }
        
       $src = $this->config->item('assets_base_url') . 'front/front_upsell.js';
       $data = array(
           "script_tag" => array(
               "event" => "onload",
               "src" => "$src"
       ));
       $script = $this->shopify->call(array('METHOD' => 'POST', 'URL' => $this->shopObj->myshopify_domain . '/admin/script_tags.json',
           'DATA' => $data));
        
        $src = $this->config->item('assets_base_url') . 'front/cross_sell.js';
        $data = array(
            "script_tag" => array(
                "event" => "onload",
                "src" => "$src"
        ));
        $script = $this->shopify->call(array('METHOD' => 'POST', 'URL' => $this->shopObj->myshopify_domain . '/admin/script_tags.json',
            'DATA' => $data));
        prExit($script);
    }

    public function newapi()
    {
        $this->shopObj = $this->shop->get_by_domain('jaydeep-store.myshopify.com');
        $shop_data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $this->shopObj->myshopify_domain,
            'ACCESS_TOKEN' => $this->shopObj->access_token
        );
        $this->shopify->setup($shop_data);

        $productCount = $this->shopify->call(array('URL' => $this->shopObj->myshopify_domain . '/admin/api/2019-07/products/count.json'),
                true);
        if (!empty($productCount->count) && $productCount->count>5)
        {
            $firstproducts = $this->shopify->call(array('URL' => $this->shopObj->myshopify_domain . '/admin/api/2019-07/products.json?limit=2'), true,true);
            $link = $firstproducts->next_link;
            do{
                $products = $this->shopify->call(array('URL' => $link), true,true);
                $link = $products->next_link;
                foreach ($firstproducts->products as $prod){
                    echo $prod->title.'<br>';
                }
                $firstproducts = $products;
                if($link==''){
                    foreach ($products->products as $prod){
                     echo $prod->title.'<br>';
                    } 
                }
            }while ($link!='') ;
        }
        prExit($productCount);
    }
    
    public function collectionnewapi()
    {
        $this->shopObj = $this->shop->get_by_domain('jaydeep-store.myshopify.com');
        $shop_data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $this->shopObj->myshopify_domain,
            'ACCESS_TOKEN' => $this->shopObj->access_token
        );
        $this->shopify->setup($shop_data);
        
        $collectionsRes = $this->shopify->call(array('URL' => $this->shopObj->myshopify_domain . '/admin/api/2019-07/custom_collections.json?limit=2'),
                                               true,true);
        prExit($collectionsRes);
    }
}
