<?php

class Receipt_video extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ReceiptVideo');
    }

    public function index()
    {
        $record                 = $this->ReceiptVideo->getRecord($this->shopId);
        $data[ 'curr_uri' ]     = 'receipt_video';
        $data[ 'shopCurrency' ] = $this->shopCurrency;
        $data[ 'result' ]         = $record;
        $data[ 'shop_id' ]         = $this->shopId;
        $this->view('admin/receipt_video/_form', $data);
    }

    public function save()
    {
        $this->ReceiptVideo->save($this->input->post(), $this->input->post('shop_id'));
        $response['msg'] = 'Record saved successfully.';
        $response['status'] = SUCCESS;
        echo json_encode($response);
        exit;
    }

}
