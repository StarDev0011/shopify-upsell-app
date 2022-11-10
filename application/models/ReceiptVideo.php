<?php

class ReceiptVideo extends CI_Model {

    private $_table     = 'receipt_videos';
    public $buttonStyle = [
        0 => 'style="background-color:#ddd; color: #49bd23; border: 2px solid #49bd23; border-radius:0px; height: auto; text-decoration:none; text-transform: uppercase; padding: 12px 20px;min-width:200px; text-align: center; display: inline-block; box-sizing: border-box;"',
        1 => 'style="background-color:#ddd; color: #FFF; border: none; border-radius:0px; height: auto; text-decoration:none; text-transform: uppercase; padding: 12px 20px;min-width:200px; text-align: center; display: inline-block; box-sizing: border-box;"',
        2 => 'style="background-color:#ddd; color: #FFF; border: none; border-radius:6px; height: auto; text-decoration:none; text-transform: uppercase; padding: 12px 20px;min-width:200px; text-align: center; display: inline-block; box-sizing: border-box;"'];

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert record
     * @param type $ordersData
     * @return type
     */
    public function save($data, $shopId, $isCount = 0)
    {
        if (empty($data['id']))
        {
            $data['shop_id'] = $shopId;
            unset($data['id']);
            $this->db->insert($this->_table, $data);
            $id                = $this->db->insert_id();
        } else
        {
            if ($isCount == 0)
            {
                $record = $this->getRecord($shopId);
                if (($record->video_url_1 != $data['video_url_1']) || ($record->redirection_url_1 != $data['redirection_url_1']))
                {
                    $data['count_url_1'] = 0;
                }
                if (($record->video_url_2 != $data['video_url_2']) || ($record->redirection_url_2 != $data['redirection_url_2']))
                {
                    $data['count_url_2'] = 0;
                }
                if (($record->video_url_3 != $data['video_url_3']) || ($record->redirection_url_3 != $data['redirection_url_3']))
                {
                    $data['count_url_3'] = 0;
                }
            }
                $this->db->where('id', $data['id']);
                $this->db->update($this->_table, $data);
            $id = $data['id'];
        }
        return $id;
    }

    public function getRecord($shopId)
    {
        $query  = $this->db->select()->from($this->_table)->Where('shop_id',
                $shopId);
        $result = $this->db->get()->row();
        return $result;
    }

}
