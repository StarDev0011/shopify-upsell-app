<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Support extends MY_Controller
{

    public $chargeStatus    = '';
    public $shopStatus      = '';
    public $trialDays       = '';
    public $confirmationUrl = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Contact_us');
        $shop = $this->shopDomain;
        
        /*if (empty($shop))
        {
            $uri   = $_SERVER[ 'REQUEST_URI' ];
            $parts = parse_url($_SERVER[ 'REQUEST_URI' ]);
            if (!empty($parts[ 'query' ]))
            {
                parse_str($parts[ 'query' ], $query);
                redirect('auth/auth?shop=' . $query[ 'shop' ]);
            }
            else
            {
                redirect('auth/refresh_page');
            }
        }*/
        /*$shopInfo = $this->shop->get_by_domain($this->shopDomain);
		prExit($shopInfo);
        $confirmation_url = $shopInfo->confirmation_url;
        $now              = time(); // or your date as well
        $your_date        = strtotime("+14 days",
                                      strtotime($shopInfo->created_date));
        $datediff         = $your_date - $now;

        $trial_days            = floor($datediff / (60 * 60 * 24));
        $this->trialDays       = $trial_days;
        $this->confirmationUrl = $shopInfo->confirmation_url;
        $this->chargeStatus    = $shopInfo->charge_status;
        $this->shopStatus      = $shopInfo->shop_status;*/
    }

    public function index()
    {
        $data[ 'curr_uri' ]                = 'support';
        /*$billingData[ 'confirmation_url' ] = $this->confirmationUrl;
        $billingData[ 'trial_days' ]       = $this->trialDays;
        $billingData[ 'charge_status' ]    = $this->chargeStatus;
        $billingData[ 'shop_status' ]      = $this->shopStatus;
        $data                              = $data + $billingData;*/
        $this->view('admin/support', $data);
    }

    public function create()
    {
        $post                   = $this->input->post();
        $post[ 'created_date' ] = save_db_date();
        $post[ 'shop_id' ]      = $this->shopId;
        $inserted               = $this->Contact_us->insert($post);

        $headers = "From: support@smartcartupsell.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message = $this->load->view('admin/mail/support_mail_template', $post,
                                     true);
        if (!empty($inserted))
        {
            $to       = $post[ 'email' ];
            $from     = $this->config->item('admin_email');
            $template = $this->load->view('admin/mail/support_mail_template',
                                          $post, true);
            send_email('support@powerpackedplugins.com', 'Smart Cart Upsell: Contact Us', $template, $from);
//            send_email($to, 'Smart Cart Upsell: Contact Us', $template, $from);
            //mail($to, 'Smart Cart Upsell: Contact Us', $message, $headers);
        }
        echo json_encode(['status' => 'success']);
        exit;
    }

    public function tutorial()
    {
        $data[ 'curr_uri' ]                = 'tutorial';
        /*$billingData[ 'confirmation_url' ] = $this->confirmationUrl;
        $billingData[ 'trial_days' ]       = $this->trialDays;
        $billingData[ 'charge_status' ]    = $this->chargeStatus;
        $billingData[ 'shop_status' ]      = $this->shopStatus;
        $data                              = $data + $billingData;*/
        $this->view('admin/tutorial', $data);
    }

}
