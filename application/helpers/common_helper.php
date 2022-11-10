<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Dhara
 * View array in pre tag 
 * @param  (array)  $arr 
 * @return Formated array with pre tag
 */
function pr($arr,$title='') {
    echo "<pre>";
    if(!empty($title))
        echo '<b>'.$title.'</b><br>';
    print_r($arr);
    echo "</pre>";
}

/*
  @Description:
  @Author: Dhara
  @Output:
  @Date: 8-8-2017
 */

function prExit($array) {
    echo '<pre>';
    print_r($array);
    exit;
}

/**
 * @author Dhara
 * @param type $string
 */
function echoExit($string) {
    echo $string;
    exit;
}

/**
 * get shop id from domain and returns
 * @author Dhara
 * @param type $domain
 * @param type $mode
 * @return type
 */
function getDomainId($domain, $mode = 0) {
    if ($mode == 0) {
        $domainAry = explode('.', $domain);
        if (isset($domainAry[0])) {
            $id = explode('-', $domainAry[0]);
            return isset($id[1]) ? $id[1] : '';
        }
    } else {
        return $domain;
    }
}

/**
 * Last query print and exit
 * @Author: Dhara
 */
function last_query() {
    $CI = & get_instance();
    echo $CI->db->last_query();
    exit;
}

/**
 * date format in view
 * @param type $date
 * @return string
 */
function view_date($date) {
    if ($date != '0000-00-00' && $date != '0000-00-00 00:00:00')
        return date('m-d-Y', strtotime($date));
    else
        return '';
}

/**
 * status
 * @param type $status
 * @return type
 */
function status($status) {
    return $status == 1 ? 'Active' : 'In Active';
}

function save_date($date, $time = 0) {
    if ($time == 1)
        return date('Y-m-d H:i:s', strtotime(str_replace('-', '/', trim($date))));
    else
        return date('Y-m-d', strtotime(str_replace('-', '/', trim($date))));
}

/**
 * database saving format
 * @return type
 */
function save_db_date(){
    return date('Y-m-d H:i:s');
}

/**
 * Used to get colloumn record from mutlisimentional array. alternate to array_column function 
 * @Author: Dhara
 * @param type $array
 * @param type $column
 * @param type $is_object
 * @param type $is_unique
 * @return type
 */
function get_array_columns($array, $column, $is_object = 0, $is_unique = 1) {
    if ($is_object == 1) {
        if ($is_unique == 1) {
            $result = array_unique(array_map(function($element) use ($column) {
                        return $element->$column;
                    }, $array));
        } else {
            $result = (array_map(function($element) use ($column) {
                        return $element->$column;
                    }, $array));
        }
    } else {
        if ($is_unique == 1) {
            $result = array_unique(array_map(function($element) use ($column) {
                        return $element[$column];
                    }, $array));
        } else {
            $result = (array_map(function($element) use ($column) {
                        return $element[$column];
                    }, $array));
        }
    }
    return $result;
}

function get_price($price){
    $decimal = substr($price,-2);
    $original = substr($price, 0, -2);
    if($decimal>0){
        $original = $original.'.'.$decimal;
    }
    return $original;
}

function send_email($to = '', $subject = '', $message = '', $from = '', $cc = '', $bcc = '', $data = '') {

    $CI = & get_instance();  //get instance, access the CI superobject
    $CI->load->library('email');
    $config = Array(
        'protocol' => $CI->config->item('protocol'),
        'smtp_host' => $CI->config->item('smtp_host'),
        'smtp_port' => $CI->config->item('smtp_port'),
        'smtp_user' => $CI->config->item('smtp_user'),
        'smtp_pass' => $CI->config->item('smtp_pass'),
        'smtp_timeout' => $CI->config->item('smtp_timeout'),
        'mailtype' => $CI->config->item('mailtype'),
        'charset' => 'iso-8859-1',
        'crlf' => "\r\n",
    );

    $CI->email->initialize($config);
    $CI->email->set_newline("\r\n");
    $CI->email->set_priority(1);
    $CI->email->subject($subject);
    $CI->email->message($message);
    $CI->email->from($from, $CI->config->item('sitename'));
    $CI->email->to($to);
    $CI->email->cc($cc);
    $CI->email->bcc($bcc);

    if (!empty($data['attachment_email'])) {
        foreach ($data['attachment_email'] as $row_attachment)
            $CI->email->attach("uploads/attachment_file/" . $row_attachment['attachment']);
    }
//    exit;
    $CI->email->send();
//    echo '<pre>';print_r($CI->email->print_debugger());die;
    $CI->email->clear(TRUE);
}


function get_headers_from_curl_response($response)
{
    $headers = array();

    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

    foreach (explode("\r\n", $header_text) as $i => $line)
        if ($i === 0)
            $headers['http_code'] = $line;
        else
        {
            list ($key, $value) = explode(': ', $line);

            $headers[$key] = $value;
        }

    return $headers;
}