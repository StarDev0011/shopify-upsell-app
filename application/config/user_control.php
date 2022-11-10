<?php
/*
    @Description        :User WS controller
    @Author             :Arun Baghel
    @input              :
    @Output             :
    @Date               :20-11-2017
    @Webservices link   :
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class User_control extends REST_Controller {
    function __construct() {
        parent::__construct();
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        
        $this->load->model('Common_function_model');
        $this->load->model('Imageupload_model');
        $this->viewName = $this->router->uri->segments[2];
        
        
    }

    function alpha_numeric_space($str) {
        return (!preg_match("/^[\S]+$/i", $str)) ? false : true;
    }

    /*
        @Description        :User Login
        @Author             :Mit Makwana
        @input              :email_id,password,device_token
        @Output             :User Login
        @Date               :21-12-2017
        @Webservices link   :
     */
    function user_login_post()
    {
        $data = $this->post();
        
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_alpha_numeric_space');
        $this->form_validation->set_message('alpha_numeric_space', 'spaces not allow.');
        
        if ($this->form_validation->run() == false) {
            $msg['MESSAGE'] = strip_tags(validation_errors());
            $msg['code']    = "400";
        } else {

            $passwd = $this->Common_function_model->encrypt_script($data['password']);
            $params = array(
                    'table'=>'user_master',
                    'where'=>array('username' => "'".$data['username']."'"),
                    'fields'=>array('id','first_name','last_name','username','password','email_id','date_of_birth','profile_image','device_type','device_token','latitude','longitude','is_verify','status'),
                    'compare_type' => '='
                );
            $chkUser = $this->Common_function_model->getmultiple_tables($params);
            
            if (!empty($chkUser)) {
                
                if ($chkUser[0]['is_verify'] == 1 && $chkUser[0]['status'] == 1) {

                    if ($chkUser[0]['password'] == $passwd) {

                        $udata['device_type']   = !empty($data['device_type'])?$data['device_type']:'';
                        $udata['device_token']  = !empty($data['device_token'])?$data['device_token']:'';
                        $udata['latitude']      = !empty($data['latitude'])?$data['latitude']:'';
                        $udata['longitude']     = !empty($data['longitude'])?$data['longitude']:'';

                        $where = array('id' => $chkUser[0]['id']);
                        $this->Common_function_model->update('user_master', $udata, $where);

                        $msg['MESSAGE']         = "logged In Successfully";
                        //$msg['FLAG']            = true;
                        unset($chkUser[0]['password']);
                        $msg['LOGIN_DETAILS']   = !empty($chkUser)?$chkUser:array();
                        //$msg['BOOKING']         = !empty($userBooking)?$userBooking:array();
                    } else {
                        $msg['MESSAGE'] = $this->lang->line('invalid_pass');
                        //$msg['FLAG']    = false;
                    }
                }
                else {
                    if($chkUser[0]['is_verify'] == 0)
                    {
                        //$msg['FLAG']    = false;
                        $msg['MESSAGE'] = $this->lang->line('verify_email_address');
                    }
                    elseif($chkUser[0]['status'] == 0)
                    {
                        //$msg['FLAG']    = false;
                        $msg['MESSAGE'] = $this->lang->line('account_not_activated');
                    }
                    else
                    {
                        //$msg['FLAG']    = false;
                        $msg['MESSAGE'] = $this->lang->line('user_not_registered');
                    }
                }
            }
            else {
                //$msg['FLAG']    = false;
                $msg['MESSAGE'] = $this->lang->line('user_not_registered');
            }
        }
        $this->response($msg, 200);
    }

    /*
        @Description        :User Social Login
        @Author             :Parag Joshi
        @input              :email_id,password,device_token
        @Output             :User Social Login
        @Date               :21-12-2017
        @Webservices link   :
     */
    function social_login_post()
    {
        $data = $this->post();
        
        $this->form_validation->set_rules('uuid', 'UUID', 'trim|required');
        $this->form_validation->set_rules('facebook_id', 'Facebook ID', 'trim|required');
        $this->form_validation->set_rules('email_id', 'Email Id', 'trim|valid_email');
        
        if($this->form_validation->run() == false) {
            $msg['MESSAGE'] = strip_tags(validation_errors());
            $msg['code']    = "400";
        }
        else
        {
            if(!empty($data['facebook_id']) || !empty($data['instagram_id']))
            {
                $where = "";
                /*if(!empty($data['email_id']))
                {
                    $where .= " email_id = '".$data['email_id']."' OR ";
                }*/
                if(!empty($data['facebook_id']))
                {
                    $where .= " facebook_id = '".$data['facebook_id']."' OR ";
                }
                if(!empty($data['instagram_id']))
                {
                    $where .= " instagram_id = '".$data['instagram_id']."'";
                }
                
                //$passwd = $this->Common_function_model->encrypt_script($data['password']);
                
                $params = array(
                        'table'=>'user_master',
                        'wherestring'=>$where, 
                        'fields'=>array('id','is_facebook_url','profile_image','first_name','last_name','password','email_id','date_of_birth','device_type','device_token','mobile_number','is_verify','status'),
                        'compare_type' => '='
                    );
                
                $chkUser = $this->Common_function_model->getmultiple_tables($params);
                
                if(!empty($chkUser) && $chkUser[0]['status'] == 0)
                {
                    $msg['code']        = "400";
                    $msg['IS_ACTIVE']   = "0";
                    $msg['MESSAGE']     = $this->lang->line('account_not_activated');
                }
                elseif(!empty($chkUser))
                {
                    //check exist user fb profile or not
//                    $is_facebook_url = ((!empty($chkUser[0]['is_facebook_url']) && $chkUser[0]['is_facebook_url'] == '1') ? '1' : '0');
//                    //update fb profile
//                    if(!empty($is_facebook_url) && $is_facebook_url == '1' && !empty($data['facebook_profile']))
//                    {
//                        //update fb profile pic
//                        $udata['facebook_profile']  = $data['facebook_profile'];
//                    }
                    if(!empty(trim($chkUser[0]['email_id'])) || !empty(trim($chkUser[0]['mobile_number'])))
                    {
                        $msg['code']            = "200";
                    }
                    else
                    {
                        if(empty($data['email_id']) && empty($data['mobile_number']))
                        {
                            $msg['code']            = "201";  
                        }
                        else
                        {
                            $udata['email_id']          = !empty($data['email_id'])?$data['email_id']:'';
                            $udata['mobile_number']     = !empty($data['mobile_number'])?$data['mobile_number']:'';
                            $msg['code']            = "200";
                        }   
                    }
                        
                    $udata['device_type']   = !empty($data['device_type'])?$data['device_type']:'';
                    $udata['device_token']  = !empty($data['device_token'])?$data['device_token']:'';
                    $udata['latitude']      = !empty($data['latitude'])?$data['latitude']:'';
                    $udata['longitude']     = !empty($data['longitude'])?$data['longitude']:'';
                    $udata['modified_date'] = date('Y-m-d H:i:s');
                    $udata['uuid']          = !empty($data['uuid'])?$data['uuid']:'';
                    
                    if(!empty($data['facebook_id']))
                        $udata['facebook_id']     = $data['facebook_id'];
                    
                    if(!empty($data['instagram_id']))
                            $udata['instagram_id']     = $data['instagram_id'];

                    $udata['is_verify']     = 1;
                    $udata['status']        = 1;

                    $where = array('id' => $chkUser[0]['id']);
                    $this->Common_function_model->update('user_master', $udata, $where);

                    $params = array(
                            'table'=>'user_master',
                            'where'=>$where,
                            'fields'=>array('id','is_facebook_url','profile_image','first_name','last_name','email_id','date_of_birth','gender','mobile_number','facebook_id','instagram_id','device_type','device_token','uuid','is_verify','status'),
                            'compare_type' => '='
                        );
                    
                    $userData = $this->Common_function_model->getmultiple_tables($params);

                    if($userData[0]['date_of_birth'] != '0000-00-00')
                    {
                        $userData[0]['date_of_birth'] = dateformat($userData[0]['date_of_birth']);
                    }
                    else
                    {
                        $userData[0]['date_of_birth'] = 'Not available';
                    }
                    $userData[0]['is_facebook_url'] = ((!empty($userData[0]['is_facebook_url']) && $userData[0]['is_facebook_url'] == '1') ? '1' : '0');
                    if(empty($userData[0]['is_facebook_url']) && !empty($userData[0]['profile_image']) && file_exists($this->config->item('user_big_path') . $userData[0]['profile_image']))
                    {
                        $userData[0]['profile_image'] = $this->config->item('user_big_url').$userData[0]['profile_image'];
                    }
                    else if(!empty($userData[0]['is_facebook_url']) && $userData[0]['is_facebook_url'] == '1')
                    {
                        $userData[0]['profile_image'] = (!empty($userData[0]['profile_image']) ? $userData[0]['profile_image'] : ''); 
                    }
                    else
                    {
                        $userData[0]['profile_image'] = '';
                    }
                    $msg['MESSAGE']         = $this->lang->line('success_logged_in');
                    //unset($chkUser[0]['password']);
                    $msg['LOGIN_DETAILS']   = !empty($userData)?$userData:array();
                }
                else 
                {
                    $ins_data['first_name']     = !empty($data['first_name'])?$data['first_name']:'';
                    $ins_data['last_name']      = !empty($data['last_name'])?$data['last_name']:'';
                    $ins_data['username']     = !empty($data['username'])?$data['username']:'';
                    $ins_data['email_id']       = !empty($data['email_id'])?strtolower($data['email_id']):'';
                    $ins_data['date_of_birth']  = !empty($data['date_of_birth'])?$data['date_of_birth']:'';
                    $ins_data['gender']         = !empty($data['gender'])?$data['gender']:'';
                    $ins_data['mobile_number']  = !empty($data['mobile_number'])?$data['mobile_number']:'';
                    $ins_data['facebook_id']    = !empty($data['facebook_id'])?$data['facebook_id']:'';
                    $ins_data['instagram_id']    = !empty($data['instagram_id'])?$data['instagram_id']:'';
                    $ins_data['device_type']    = !empty($data['device_type'])?$data['device_type']:'';
                    $ins_data['device_token']   = !empty($data['device_token'])?$data['device_token']:'';
                    $ins_data['latitude']       = !empty($data['latitude'])?$data['latitude']:'';
                    $ins_data['longitude']      = !empty($data['longitude'])?$data['longitude']:'';
                    $ins_data['created_date']   = date('Y-m-d H:i:s');
                    //$ins_data['is_facebook_url'] = '1';
                    $ins_data['is_facebook_url']      = !empty($data['is_facebook_url'])?$data['is_facebook_url']:'';
                    $ins_data['profile_image'] = !empty($data['profile_image'])?$data['profile_image']:'';
                    
                    if (!empty($_FILES['profile_image']['name'])) 
                    {
                        if (!empty($user_data[0]['profile_image'])) 
                        {
                            if(file_exists($this->config->item('user_small_path') . $user_data[0]['profile_image']))
                                unlink ($this->config->item('user_small_path') . $user_data[0]['profile_image']);
                            if(file_exists($this->config->item('user_big_path') . $user_data[0]['profile_image']))
                                unlink ($this->config->item('user_big_path') . $user_data[0]['profile_image']);
                        }

                        $bgImgPath      = $this->config->item('user_big_path');
                        $smallImgPath   = $this->config->item('user_small_path');
                        $uploadFile     = 'profile_image';
                        $thumb          = "thumb";

                        $img_data[] = array(
                            'imagepath'=>$smallImgPath,
                            'width'=>'150',
                            'Height'=>'100');

                        //$ins_data['is_facebook_url'] = '0';
                        $ins_data['profile_image'] = $this->Imageupload_model->uploadBigImage($uploadFile, $bgImgPath,$thumb,'',$img_data , '1');
                    }

                    if(!empty($data['facebook_id']))
                    {
                        $ins_data['is_verify']  = 1;
                        $ins_data['status']     = 1; 
                    }

                    if(!empty($ins_data))
                    {
                        $last_insert_id = $this->Common_function_model->insert('user_master', $ins_data);
                        $message = $this->lang->line('success_reg_in');
                    }

                    if($last_insert_id)
                    {
                        $udata['device_type']   = !empty($data['device_type'])?$data['device_type']:'';
                        $udata['device_token']  = !empty($data['device_token'])?$data['device_token']:'';
                        $udata['latitude']      = !empty($data['latitude'])?$data['latitude']:'';
                        $udata['longitude']     = !empty($data['longitude'])?$data['longitude']:'';
                        $udata['uuid']          = !empty($data['uuid'])?$data['uuid']:'';
                        if(!empty($data['facebook_id']))
                        {
                            $udata['modified_date']  = date('Y-m-d H:i:s');
                            $udata['is_verify']  = 1;
                            $udata['status']     = 1; 
                        }
                        
                        $where = array('id' => $last_insert_id);
                        $this->Common_function_model->update('user_master', $udata, $where);

                        $params = array(
                                'table'=>'user_master',
                                'where'=>$where,
                                'fields'=>array('id','is_facebook_url','profile_image','first_name','last_name','username','email_id','date_of_birth','gender','mobile_number','facebook_id','instagram_id','device_type','device_token','uuid','is_verify','status'),
                                'compare_type' => '='
                            );
                        $userData = $this->Common_function_model->getmultiple_tables($params);
                        //pr($userData); exit;
                        if($userData[0]['date_of_birth'] != '0000-00-00')
                        {
                            $userData[0]['date_of_birth'] = dateformat($userData[0]['date_of_birth']);
                        }
                        else
                        {
                            $userData[0]['date_of_birth'] = 'Not available';
                        }
                        $userData[0]['is_facebook_url'] = ((!empty($userData[0]['is_facebook_url']) && $userData[0]['is_facebook_url'] == '1') ? '1' : '0');
//                        $userData[0]['facebook_profile'] = '';
//                        $userData[0]['profile_image'] = '';
                        if(empty($userData[0]['is_facebook_url']) && !empty($userData[0]['profile_image']) && file_exists($this->config->item('user_big_path') . $userData[0]['profile_image']))
                        {
                            $userData[0]['profile_image'] = $this->config->item('user_big_url').$userData[0]['profile_image'];
                        }
                        else if(!empty($userData[0]['is_facebook_url']) && $userData[0]['is_facebook_url'] == '1')
                        {
                            $userData[0]['profile_image'] = (!empty($userData[0]['profile_image']) ? $userData[0]['profile_image'] : ''); 
                        }
                        else
                        {
                            $userData[0]['profile_image'] = '';
                        }

                        $msg['MESSAGE']         = $this->lang->line('success_logged_in');
                        $msg['code']            = "201";
                        //unset($chkUser[0]['password']);
                        $msg['LOGIN_DETAILS']   = !empty($userData)?$userData:array();
                    }
                }
            }
            else
            {
                $msg['MESSAGE']     = $this->lang->line('social_id_required');
                $msg['code']        = "400";
            }
        }
        $this->response($msg, 200);
    }

    /*
        @Description        :Signup from app side
        @Author             :Mit Makwana
        @input              :user data
        @Output             :user details
        @Date               :21-12-2017
        @Webservices link   :
     */
    function user_signup_post() 
    {
        $data = $this->post();
        $this->form_validation->set_rules('first_name', 'First name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email_id', 'Email', 'trim|required|valid_email');
        //if(empty($data['facebook_id']) && empty($data['google_id']) && empty($data['instagram_id']))
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_alpha_numeric_space');

        //$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required');
        //$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required');
        //$this->form_validation->set_rules('device_type', 'Device Type', 'trim|required');
        $this->form_validation->set_rules('device_token', 'Device Token', 'trim');
        $this->form_validation->set_message('alpha_numeric_space', 'spaces not allow');

        if ($this->form_validation->run() == FALSE)
        {
            $msg['MESSAGE'] = strip_tags(validation_errors());
            //$msg['FLAG']    = FALSE;
        }
        else 
        {
            $params = array(
                    'table'=>'user_master',
                    'wherestring'=>' email_id = "'.$data['email_id'].'"',
                    //'or_where'=>array('email_id' => $data['email_id'],'username' => $data['username']),
                    'fields'=>array('id', 'first_name', 'last_name', 'username', 'email_id', 'date_of_birth', 'gender', 'mobile_number', 'is_verify', 'facebook_id', 'instagram_id', 'profile_image', 'device_type', 'device_token', 'latitude', 'longitude', 'created_date'),
                    'compare_type' => '='
                );
            $chk_email = $this->Common_function_model->getmultiple_tables($params);

            $params_user = array(
                    'table'=>'user_master',
                    'wherestring'=>' username = "'.$data['username'].'"',
                    'fields'=>array('id', 'first_name', 'last_name', 'username', 'email_id', 'date_of_birth', 'gender', 'mobile_number', 'is_verify', 'facebook_id', 'instagram_id', 'profile_image', 'device_type', 'device_token', 'latitude', 'longitude', 'created_date'),
                    'compare_type' => '='
                );
            $chk_username = $this->Common_function_model->getmultiple_tables($params_user);
            //prd($this->db->last_dquery());

            if ((count($chk_email) > 0 || count($chk_username) > 0) && (empty($data['facebook_id']) && empty($data['instagram_id'])))
            {
                if(count($chk_email) > 0)
                    $msg['MESSAGE'] = $this->lang->line('existing_email');
                elseif(count($chk_username) > 0)
                    $msg['MESSAGE'] = $this->lang->line('existing_username');

                $msg['FLAG']    = FALSE;
            }
            elseif (count($chk_email) > 0 && (empty($chk_email[0]['facebook_id']) || empty($chk_email[0]['instagram_id'])))
            {

                $udata['facebook_id']   = !empty($data['facebook_id'])?$data['facebook_id']:'';
                $udata['instagram_id']    = !empty($data['instagram_id'])?$data['instagram_id']:'';
                $udata['device_type']   = !empty($data['device_type'])?$data['device_type']:'';
                $udata['device_token']  = !empty($data['device_token'])?$data['device_token']:'';
                $udata['latitude']      = !empty($data['latitude'])?$data['latitude']:'';
                $udata['longitude']     = !empty($data['longitude'])?$data['longitude']:'';
                $udata['modified_date'] = date('Y-m-d H:i:s');

                $where = array('id' => $chk_email[0]['id']);
                $this->Common_function_model->update('user_master', $udata, $where);

                $params = array(
                    'table'         => 'user_master',
                    'where'         => array('id' => $chk_email[0]['id']),
                    'fields'        => array('id', 'first_name', 'last_name', 'username', 'email_id', 'date_of_birth', 'gender', 'mobile_number', 'is_verify', 'facebook_id', 'instagram_id', 'profile_image', 'device_type', 'device_token', 'latitude', 'longitude', 'created_date'),
                    'compare_type'  => '='
                );
                $userDetails = $this->Common_function_model->getmultiple_tables($params);
                
                if (!empty($userDetails[0]['profile_image']) && file_exists($this->config->item('user_small_path') . $userDetails[0]['profile_image']))
                {
                    $userDetails[0]['profile_image'] = $this->config->item("user_small_url") . $userDetails[0]['profile_image'];
                }
                else
                {
                    $userDetails[0]['profile_image'] = "";
                }

                $msg['MESSAGE']         = $this->lang->line('success_reg_in');
                $msg['FLAG']            = TRUE;
                $msg['user_details']    = !empty($userDetails)?$userDetails:array();
            } 
            else 
            {
                $password = $this->Common_function_model->encrypt_script($data['password']);
                $ins_data['first_name']     = !empty($data['first_name'])?$data['first_name']:'';
                $ins_data['last_name']      = !empty($data['last_name'])?$data['last_name']:'';
                $ins_data['email_id']       = !empty($data['email_id'])?strtolower($data['email_id']):'';
                $ins_data['username']       = !empty($data['username'])?$data['username']:'';
                $ins_data['password']       = !empty($password)?$password:'';
                $ins_data['date_of_birth']  = !empty($data['date_of_birth'])?$data['date_of_birth']:'';
                $ins_data['gender']         = !empty($data['gender'])?$data['gender']:'';
                $ins_data['mobile_number']  = !empty($data['mobile_number'])?$data['mobile_number']:'';
                $ins_data['facebook_id']    = !empty($data['facebook_id'])?$data['facebook_id']:'';
                $ins_data['instagram_id']     = !empty($data['instagram_id'])?$data['instagram_id']:'';
                $ins_data['device_type']    = !empty($data['device_type'])?$data['device_type']:'';
                $ins_data['device_token']   = !empty($data['device_token'])?$data['device_token']:'';
                $ins_data['latitude']       = !empty($data['latitude'])?$data['latitude']:'';
                $ins_data['longitude']      = !empty($data['longitude'])?$data['longitude']:'';
                $ins_data['is_notification'] = 1;
                $ins_data['created_date']   = date('Y-m-d H:i:s');
                if(!empty($data['facebook_id']) || !empty($data['google_id']) || !empty($data['instagram_id']))
                {
                    $ins_data['is_verify']  = 1;
                    $ins_data['status']     = 1; 
                }
                
                // upload image //
                if (!empty($_FILES['profile_image']['name'])) {
                    $bgImgPath      = $this->config->item('user_big_path');
                    $smallImgPath   = $this->config->item('user_small_path');
                    $uploadFile     = 'profile_image';
                    $thumb          = "thumb";

                    $img_data[] = array(
                                    'imagepath'=>$smallImgPath,
                                    'width'=>'150',
                                    'Height'=>'100');

                    $ins_data['profile_image'] = $this->Imageupload_model->uploadBigImage($uploadFile, $bgImgPath,$thumb,'',$img_data , '1');
                }

                //  registration user //                    
                $last_insert_id = $this->Common_function_model->insert('user_master', $ins_data);
                $message = $this->lang->line('success_reg_in');

                if(empty($data['facebook_id']) && empty($data['instagram_id']))
                {
                    // Send Verification Email
                    $user_id_new = base64_encode($last_insert_id);
                    $verify_link = $this->config->item('base_url') . 'verify_management/verify_user?id=' . $user_id_new;

                    $data['actdata'] = array(
                                            'name' => (!empty($data['first_name'])?$data['first_name']:'') .' '. (!empty($data['last_name'])?$data['last_name']:''),
                                            'verify_link' => $verify_link);

                    $activation_tmpl = $this->load->view('email_template/new_user_register_app', $data, true);
                    
                    $sub    = $this->config->item('sitename')." : Verify your email address";
                    $from   = $this->config->item('admin_email');
                    $to     = $data['email_id'];

                    $this->Common_function_model->send_email($to, $sub, $activation_tmpl, $from);
                    $message = $this->lang->line('verify_email_address');
                }
               
                ////////////
                if(!empty($data['facebook_id']) || !empty($data['instagram_id']))
                {
                    $params = array(
                        'table'         => 'user_master',
                        'where'         => array('id' => $last_insert_id),
                        'fields'        => array('id', 'first_name', 'last_name', 'username', 'email_id', 'date_of_birth', 'gender', 'mobile_number', 'is_verify', 'facebook_id', 'instagram_id', 'profile_image', 'device_type', 'device_token', 'latitude', 'longitude', 'created_date'),
                        'compare_type'  => '='
                    );
                    $userDetails = $this->Common_function_model->getmultiple_tables($params);
                   
                    if (!empty($userDetails[0]['profile_image']) && file_exists($this->config->item('user_small_path') . $userDetails[0]['profile_image']))
                    {
                        $userDetails[0]['profile_image'] = $this->config->item("user_small_url") . $userDetails[0]['profile_image'];
                    }
                    else
                    {
                        $userDetails[0]['profile_image'] = "";
                    }
                }

                if (!empty($last_insert_id))
                {
                    $msg['MESSAGE']         = $message;   
                    $msg['FLAG']            = TRUE;
                    if(!empty($userDetails)) 
                        $msg['user_details']    = !empty($userDetails)?$userDetails:array();
                }
                else
                {
                    $msg['MESSAGE']     = $this->lang->line('wrong_data_error');   
                    $msg['FLAG']        = FALSE;
                }
            }
        }
        $this->response($msg, 200);
    }

    /*
        @Description        : Forget password send mail for new password
        @Author             : Mit Makwana
        @input              : email_id
        @Output             : forget password
        @Date               : 21-12-2017
        @Webservices link   :
    */
    function forget_password_post()
    {
        $data = $this->post();
        
        $this->form_validation->set_rules('email_id', 'Email Id', 'trim|required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            $msg['MESSAGE']     = strip_tags(validation_errors());
            //$msg['FLAG']        = FALSE;
            $msg['IS_ACTIVE']   = TRUE;
        } else {
           
            $params = array(
                    'table'=>'user_master',
                    'where'=>array('email_id' => "'".$data['email_id']."'"),
                    'fields'=>array('id', 'CONCAT_WS(" ",first_name,last_name) as user_name','email_id', 'username', 'password', 'facebook_id', 'instagram_id', 'status','is_verify'),
                    'compare_type' => '='
                );
            $chk_email = $this->Common_function_model->getmultiple_tables($params);
            
            /*if (count($chk_email) > 0 && (!empty($chk_email[0]['facebook_id']) || !empty($chk_email[0]['google_id']) || !empty($chk_email[0]['instagram_id'])))
            {
                $msg['MESSAGE'] = $this->lang->line('social_not_allowed_to_access');
                $msg['FLAG']    = FALSE;
            }
            else */ 
            if (count($chk_email) > 0 && $chk_email[0]['status'] == 1 && $chk_email[0]['is_verify'] == 1) 
            { 
                if ($chk_email[0]['password'] == "") {
                    $msg['MESSAGE']     = $this->lang->line('signed_up_with_Facebook');
                    //$msg['FLAG']        = FALSE;
                    $msg['IS_ACTIVE']   = FALSE;
                }
                else
                {

                    $encBlastId = urlencode(base64_encode($chk_email[0]['id']));

                    $loginLink = $this->config->item('base_url') . 'reset_password_mobile/reset_password_template/' . $encBlastId;

                    $pass_variable_activation = array('name' => $chk_email[0]['username'], 'loginLink' => $loginLink);
                    $data['actdata'] = $pass_variable_activation;
                    $activation_tmpl = $this->load->view('email_template/user_forget_password_link', $data, true);

                    // SEND EMAIL
                    $to     = !empty($chk_email[0]['email_id'])?$chk_email[0]['email_id']:'';
                    $from   = $this->config->item('admin_email');
                    $sub    = $this->config->item('sitename')." - Forgot Password";
                    $this->Common_function_model->send_email($to, $sub, $activation_tmpl, $from);

                    $this->Common_function_model->update("user_master", array('is_forgot_password' => '1'), array('id' => $chk_email[0]['id']), '', '=');

                    $msg['MESSAGE']     = $this->lang->line('email_sent_successfully');
                    $msg['FLAG']        = TRUE;
                    $msg['IS_ACTIVE']   = TRUE;
                }
            }else 
            {
                
                $msg['FLAG']        = FALSE;
                $msg['IS_ACTIVE']   = FALSE;
                if (isset($chk_email[0]['status']) && $chk_email[0]['status'] == '0')
                {
                    $msg['MESSAGE'] = $this->lang->line('account_not_activated');
                } 
                else if (isset($chk_email[0]['is_verify']) && $chk_email[0]['is_verify'] == '0')
                {
                    $msg['MESSAGE'] = $this->lang->line('unable_to_send_mail');
                } 
                else
                {
                    $msg['MESSAGE'] = $this->lang->line('mail_not_registered');
                }
            }
        }
        $this->response($msg, 200);
    }

    /*
        @Description        : Change Password
        @Author             : Mit Makwana
        @input              : old_password
        @Output             : forget password,new_password
        @Date               : 22-2-2017
        @Webservices link   :
    */
    function change_password_post() 
    {
        $id = $this->get('id');
        $data = $this->post();
        $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|min_length[6]|callback_alpha_numeric_space');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|callback_alpha_numeric_space');
        $this->form_validation->set_message('alpha_numeric_space', 'spaces not allow');
        if ($this->form_validation->run() == FALSE) {
            $msg['MESSAGE']     = strip_tags(validation_errors());
            $msg['FLAG']        = FALSE;
            $msg['IS_ACTIVE']   = TRUE;
        } else {

            $params = array(
                    'table'=>'user_master',
                    'where'=>array('id' => $id),
                    'fields'=>array('id', 'CONCAT_WS(" ",first_name,last_name) as user_name','email_id', 'password', 'status','is_verify'),
                    'compare_type' => '='
                );
            $chk_data = $this->Common_function_model->getmultiple_tables($params);

            if (!empty($chk_data) && count($chk_data) > 0)
            {
                $old_password = $this->Common_function_model->encrypt_script($data['old_password']);
                if ($chk_data[0]['status'] == '0')
                {
                    $msg['MESSAGE']     = $this->lang->line('account_not_activated');
                    $msg['FLAG']        = FALSE;
                    $msg['IS_ACTIVE']   = FALSE;
                }
                elseif ($chk_data[0]['is_verify'] == '0')
                {
                    $msg['MESSAGE']     = $this->lang->line('unable_to_change_password');
                    $msg['FLAG']        = FALSE;
                    $msg['IS_ACTIVE']   = FALSE;
                }
                else if($old_password != $chk_data[0]['password'])
                {
                    $msg['MESSAGE']     = $this->lang->line('invalid_old_password');
                    $msg['FLAG']        = FALSE;
                    $msg['IS_ACTIVE']   = TRUE;
                }
                else 
                {
                    $data['user_id'] = $id;
                    $new_passwd = $this->Common_function_model->encrypt_script($data['new_password']);

                    $this->Common_function_model->update("user_master", array('password' => $new_passwd), array('id' => $data['user_id']), '', '=');

                    $msg['MESSAGE']     = $this->lang->line('password_change_succ');
                    $msg['FLAG']        = TRUE;
                    $msg['IS_ACTIVE']   = TRUE;
                }
            } 
            else 
            {
                $msg['MESSAGE']     = $this->lang->line('mail_not_registered');
                $msg['FLAG']        = FALSE;
                $msg['IS_ACTIVE']   = FALSE;
            }
        }
        $this->response($msg, 200);
    }

    /*
        @Description        :Contact us
        @Author             :Parag Joshi
        @input              :email_id,password,device_token
        @Output             :
        @Date               :31-3-2017
        @Webservices link   :
     */
    function contact_us_post()
    {
        $data = $this->post();
        
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('contact_no', 'Contact number', 'trim|required');
        //$this->form_validation->set_rules('email_id', 'Email Id', 'trim|valid_email');
        
        if ($this->form_validation->run() == false) {
            $msg['MESSAGE'] = strip_tags(validation_errors());
            $msg['FLAG']    = false;
        } else {
            $id = $data['user_id'];

            $params = array(
                    'table'=>'user_master',
                    'where'=>array('id' => $id),
                    'fields'=>array('id', 'CONCAT_WS(" ",first_name,last_name) as full_name','email_id', 'mobile_number','status','is_verify'),
                    'compare_type' => '='
                );
            $chk_data = $this->Common_function_model->getmultiple_tables($params);

            if (!empty($chk_data) && count($chk_data) > 0)
            {
                if ($chk_data[0]['status'] == '0')
                {
                    $msg['MESSAGE']     = $this->lang->line('account_not_activated');
                    $msg['FLAG']        = FALSE;
                    $msg['IS_ACTIVE']   = FALSE;
                }
                elseif ($chk_data[0]['is_verify'] == '0')
                {
                    $msg['MESSAGE']     = $this->lang->line('unable_to_change_password');
                    $msg['FLAG']        = FALSE;
                    $msg['IS_ACTIVE']   = FALSE;
                }
                else
                {
                    $contact_data['full_name']      = !empty($chk_data[0]['full_name'])?$chk_data[0]['full_name']:'';
                    $contact_data['email_id']       = !empty($chk_data[0]['email_id'])?strtolower($chk_data[0]['email_id']):'';
                    $contact_data['contact_number'] = !empty($data['contact_no'])?$data['contact_no']:'';
                    $contact_data['message']        = !empty($data['message'])?$data['message']:'';
                    $contact_data['created_date']   = date('Y-m-d H:i:s');
                    $contact_data['status']         = 1;

                    $contact_id = $this->Common_function_model->insert('contact_us', $contact_data);

                    if(!empty($contact_id))
                    {

                        // SENN NOTIFICATION TO ADMIN
                        $notification = $contact_data['full_name']." wants to contact Admin.";
                        
                        $insdata['type'] = 7;
                        $insdata['username'] = !empty($contact_data['full_name'])?$contact_data['full_name']:'';
                        $insdata['notification'] = $notification;
                        $insdata['is_read'] = 0;
                        $insdata['created_date'] = date('Y-m-d H:i:s');

                        $this->Common_function_model->insert('notifications', $insdata);

                        ////////
                        $msg['MESSAGE']         = $this->lang->line('thank_you');
                        $msg['FLAG']            = true;
                        $msg['IS_ACTIVE']       = TRUE;
                    }
                    else 
                    {
                        $msg['FLAG']        = false;
                        $msg['IS_ACTIVE']   = TRUE;
                        $msg['MESSAGE'] = $this->lang->line('something_wrong');
                    }
                }
            }
            else
            {
                $msg['FLAG']        = TRUE;
                $msg['IS_ACTIVE']   = FALSE;
                $msg['MESSAGE']     = $this->lang->line('user_not_registered');
            }

        }
        $this->response($msg, 200);
    }

    /*
        @Description        :Profile details
        @Author             :Arun Baghel
        @input              :user_id
        @Output             :
        @Date               :21-12-2017
        @Webservices link   :
     */
    function profile_details_post()
    {
        $data = $this->post();

        $this->form_validation->set_rules('user_id', 'User id', 'trim|required');
        $this->form_validation->set_rules('uuid', 'UUID', 'trim|required');

        if ($this->form_validation->run() == false) {
            $msg['MESSAGE'] = strip_tags(validation_errors());
            $msg['code']    = "400";
        } 
        else 
        {    
            if($this->Common_function_model->check_device_uuid($data['user_id'],$data['uuid']) == 'false')
            {
                $msg['code']        = "400";
                $msg['MESSAGE']     = $this->lang->line('uuid_mismatch');
            }
            else
            {
                $params = array(
                        'table'=>'user_master',
                        'where'=>array('id' => "'".$data['user_id']."'"),
                        'fields'=>array('id', 'is_facebook_url','profile_image','first_name', 'last_name', 'email_id', 'date_of_birth', 'mobile_number', 'gender','uuid','device_token','device_type','status','is_verify'),
                        'compare_type' => '='
                    );
                $user_details = $this->Common_function_model->getmultiple_tables($params);

                if (!empty($user_details) && count($user_details) > 0) 
                {
                    if($user_details[0]['uuid'] === $data['uuid'])
                    {
                        if($user_details[0]['status'] == 0)
                        {
                            $msg['code']        = "400";
                            $msg['MESSAGE']     = $this->lang->line('user_inactive');
                        }
                        elseif ($user_details[0]['is_verify'] == '0')
                        {
                            $msg['MESSAGE']     = $this->lang->line('account_not_verified');
                            $msg['code']        = "400";
                        }
                        else
                        {
                            if($user_details[0]['date_of_birth'] != '0000-00-00')
                            {
                                $user_details[0]['date_of_birth'] = dateformat($user_details[0]['date_of_birth']);
                            }
                            else
                            {
                                $user_details[0]['date_of_birth'] = 'Not available';
                            }

                            $user_details[0]['is_facebook_url'] = ((!empty($user_details[0]['is_facebook_url']) && $user_details[0]['is_facebook_url'] == '1') ? '1' : '0');
                            //$user_details[0]['facebook_profile'] = '';
                            //$user_details[0]['profile_image'] = '';
                            if(empty($user_details[0]['is_facebook_url']) && !empty($user_details[0]['profile_image']) && file_exists($this->config->item('user_big_path') . $user_details[0]['profile_image']))
                            {
                                $user_details[0]['profile_image'] = $this->config->item('user_big_url').$user_details[0]['profile_image'];
                            }
                            else if(!empty($user_details[0]['is_facebook_url']) && $user_details[0]['is_facebook_url'] == '1')
                            {
                                $user_details[0]['profile_image'] = (!empty($user_details[0]['profile_image']) ? $user_details[0]['profile_image'] : ''); 
                            }
                            else
                            {
                                $user_details[0]['profile_image'] = '';
                            }

                            $msg['MESSAGE']         = $this->lang->line('message_type_success');
                            $msg['code']            = "200";
                            $msg['USER_DETAILS']    = $user_details;
                        }
                    }
                    else
                    {
                        $msg['code']        = "400";
                        $msg['MESSAGE']     = $this->lang->line('uuid_mismatch');
                    }
                }
                else
                {
                    $msg['code']        = "400";
                    $msg['MESSAGE']     = $this->lang->line('user_not_registered');
                }
            }
        }
        $this->response($msg, 200);
    }

    /*
      @Description        : Edit my profile
      @Author             : Parag Joshi
      @input              : User id
      @Output             : Update user profile 
      @Date               : 21-12-2017
      @Webservices link   :
    */
    function edit_profile_post() 
    {
        $data = $this->post();

        $this->form_validation->set_rules('uuid', 'UUID', 'trim|required');

        if(empty($data['email_id']) && empty($data['mobile_number']))
        {
            $this->form_validation->set_rules('email_id', 'Either Email Id OR Contact', 'trim|required');
        }

        if ($this->form_validation->run() == false) {
            $msg['MESSAGE'] = strip_tags(validation_errors());
            $msg['code']    = "400";
        }
        else 
        {   
            if($this->Common_function_model->check_device_uuid($this->get('id'),$data['uuid']) == 'false')
            {
                $msg['code']        = "400";
                $msg['MESSAGE']     = $this->lang->line('uuid_mismatch');
            }
            else
            {
                $id = $this->get('id');
                $uuid = !empty($data['uuid'])?$data['uuid']:'';
                if(empty($id) || $id == '')
                {        
                    $msg['code']        = "400";
                    $msg['MESSAGE']     = $this->lang->line('insert_proper_parameter');
                }
                else
                {

                    $params = array(
                            'table'=>'user_master',
                            'where'=>array('id' => $id),
                            'fields'=>array('id', 'first_name','last_name', 'profile_image', 'date_of_birth','uuid', 'status','is_verify'),
                            'compare_type' => '='
                        );
                    $user_data = $this->Common_function_model->getmultiple_tables($params);
                    
                    if (!empty($user_data) && count($user_data) > 0) 
                    {
                        // check email is already registered or not
                        if(!empty(trim($data['email_id'])))
                        {
                            $params_email = array(
                                    'table'=>'user_master',
                                    'where'=> "email_id = '".$data['email_id']."' AND id != ".$id,
                                    'fields'=>array('id'),
                                    'compare_type' => '='
                                );
                            $user_email = $this->Common_function_model->getmultiple_tables($params_email);
                        }
                        else
                        {
                            $user_email = array();
                        }

                        if (!empty($user_email) && count($user_email) > 0) 
                        {
                            $msg['code']        = "400";
                            $msg['MESSAGE']     = $this->lang->line('email_exist_error');
                        }
                        else
                        {   
                            if ($user_data[0]['status'] == '0') 
                            {
                                $msg['code']    = "400";
                                $msg['MESSAGE'] = $this->lang->line('user_inactive');
                            }
                            elseif ($user_data[0]['is_verify'] == '0')
                            {
                                $msg['MESSAGE']     = $this->lang->line('account_not_verified');
                                $msg['coed']        = "400";
                            }
                            else
                            {
                                $upd_data['first_name']     = !empty($data['first_name'])?$data['first_name']:'';
                                $upd_data['last_name']      = !empty($data['last_name'])?$data['last_name']:'';
                                $upd_data['date_of_birth']  = !empty($data['date_of_birth'])?$data['date_of_birth']:'';
                                $upd_data['gender']         = !empty($data['gender'])?$data['gender']:'';
                                $upd_data['email_id']       = !empty($data['email_id'])?$data['email_id']:'';
                                $upd_data['mobile_number']  = !empty($data['mobile_number'])?$data['mobile_number']:'';
                                $upd_data['modified_date']  = date('Y-m-d H:i:s');
                                
                                $is_delete_photo = isset($data['is_delete_photo'])?$data['is_delete_photo']:'';
                                
                                if(!empty($is_delete_photo) && $is_delete_photo == 1 && !empty($user_data[0]['profile_image']))
                                {
                                    if(file_exists($this->config->item('user_small_path') . $user_data[0]['profile_image']))
                                        unlink ($this->config->item('user_small_path') . $user_data[0]['profile_image']);
                                    if(file_exists($this->config->item('user_big_path') . $user_data[0]['profile_image']))
                                        unlink ($this->config->item('user_big_path') . $user_data[0]['profile_image']);
                                    $upd_data['profile_image'] = "";
                                }
                                else
                                {   
                                    
                                    $is_facebook_url = ((!empty($data['is_facebook_url']) && $data['is_facebook_url'] == '1') ? '1' : '0');
//                                    pr($is_facebook_url); die;
                                    if(!empty($is_facebook_url) && $is_facebook_url == '1')
                                    {
                                        //update fb profile pic
                                        $upd_data['is_facebook_url']  = '1';
                                        if(!empty($data['profile_image']))
                                        {
                                            $upd_data['profile_image']  = $data['profile_image'];
                                        }
                                    }
                                    else
                                    {
                                        $upd_data['is_facebook_url'] = '0';
                                        //$upd_data['profile_image'] = '';
                                        // upload image //
                                        if (!empty($_FILES['profile_image']['name'])) 
                                        {
                                            if (!empty($user_data[0]['profile_image'])) 
                                            {
                                                if(file_exists($this->config->item('user_small_path') . $user_data[0]['profile_image']))
                                                    unlink ($this->config->item('user_small_path') . $user_data[0]['profile_image']);
                                                if(file_exists($this->config->item('user_big_path') . $user_data[0]['profile_image']))
                                                    unlink ($this->config->item('user_big_path') . $user_data[0]['profile_image']);
                                            }

                                            $bgImgPath      = $this->config->item('user_big_path');
                                            $smallImgPath   = $this->config->item('user_small_path');
                                            $uploadFile     = 'profile_image';
                                            $thumb          = "thumb";

                                            $img_data[] = array(
                                                            'imagepath'=>$smallImgPath,
                                                            'width'=>'150',
                                                            'Height'=>'100');

//                                            $upd_data['is_facebook_url'] = '0';
//                                            $upd_data['facebook_profile'] = '';
                                            $upd_data['profile_image'] = $this->Imageupload_model->uploadBigImage($uploadFile, $bgImgPath,$thumb,'',$img_data , '1');
                                        }
                                    }
                                }
//                                pr($upd_data); die;

                                $this->Common_function_model->update('user_master', $upd_data, array('id'=>$user_data[0]['id']));

                                $params = array(
                                        'table'=>'user_master',
                                        'where'=>array('id' => $id),
                                        'fields'=>array('id', 'is_facebook_url','first_name','last_name', 'email_id', 'profile_image', 'gender', 'date_of_birth', 'mobile_number', 'facebook_id', 'status','is_verify'),
                                        'compare_type' => '='
                                    );
                                $user_details = $this->Common_function_model->getmultiple_tables($params);

                                if($user_details[0]['date_of_birth'] != '0000-00-00')
                                {
                                    $user_details[0]['date_of_birth'] = dateformat($user_details[0]['date_of_birth']);
                                }
                                else
                                {
                                    $user_details[0]['date_of_birth'] = 'Not available';
                                }

                                $user_details[0]['is_facebook_url'] = ((!empty($user_details[0]['is_facebook_url']) && $user_details[0]['is_facebook_url'] == '1') ? '1' : '0');
//                                $user_details[0]['facebook_profile'] = '';
//                                $user_details[0]['profile_image'] = '';
                                if(empty($user_details[0]['is_facebook_url']) && !empty($user_details[0]['profile_image']) && file_exists($this->config->item('user_big_path') . $user_details[0]['profile_image']))
                                {
                                    $user_details[0]['profile_image'] = $this->config->item('user_big_url').$user_details[0]['profile_image'];
                                }
                                else if(!empty($user_details[0]['is_facebook_url']) && $user_details[0]['is_facebook_url'] == '1')
                                {
                                    $user_details[0]['profile_image'] = (!empty($user_details[0]['profile_image']) ? $user_details[0]['profile_image'] : ''); 
                                }
                                else
                                {
                                    $user_details[0]['profile_image'] = '';
                                }

                                $msg['MESSAGE']         = $this->lang->line('record_update_success');
                                $msg['code']            = "200";
                                $msg['USER_DETAILS']    = $user_details;
                            }
                        }
                    }
                    else
                    {
                        $msg['code']        = "400";
                        $msg['MESSAGE']     = $this->lang->line('user_not_registered');
                    }
                }
            }
        }
        $this->response($msg, 200);
    }

    /*
        @Description        :Check User Details
        @Author             :Parag Joshi
        @input              :username,password
        @Output             :
        @Date               :24-6-2017
        @Webservices link   :
     */
    function check_user_details_post()
    {
        $data = $this->post();
        
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_alpha_numeric_space');
        $this->form_validation->set_message('alpha_numeric_space', 'spaces not allow.');
        
        if ($this->form_validation->run() == false) {
            $msg['MESSAGE'] = strip_tags(validation_errors());
            $msg['FLAG']    = false;
            $msg['IS_ACTIVE']   = TRUE;
        } else {

            $passwd = $this->Common_function_model->encrypt_script($data['password']);
            
            $params = array(
                    'table'=>'user_master',
                    'where'=>array('id' => $data['user_id']),
                    'fields'=>array('id','first_name','last_name','username','password','email_id','date_of_birth','profile_image','device_type', 'bt_customer_id','device_token','latitude','longitude','is_verify','status'),
                    'compare_type' => '='
                );
            $chkUser = $this->Common_function_model->getmultiple_tables($params);
            //pr($this->db->last_query());
            //prd($chkUser);
            if (!empty($chkUser)) {
                
                if ($chkUser[0]['is_verify'] == 1 && $chkUser[0]['status'] == 1) {

                    if ($chkUser[0]['password'] == $passwd) {

                        $msg['MESSAGE']         = $this->lang->line('message_type_success');
                        $msg['FLAG']            = true;
                        $msg['IS_ACTIVE']       = TRUE;

                    } else {

                        $msg['MESSAGE'] = $this->lang->line('invalid_pass');
                        $msg['FLAG']    = false;
                        $msg['IS_ACTIVE'] = TRUE;
                    }
                }
                else 
                {
                    if($chkUser[0]['is_verify'] == 0)
                    {
                        $msg['MESSAGE']     = $this->lang->line('account_not_verified');
                        $msg['FLAG']        = FALSE;
                        $msg['IS_ACTIVE']   = FALSE;
                    }
                    elseif($chkUser[0]['status'] == 0)
                    {
                        $msg['MESSAGE']     = $this->lang->line('account_not_activated');
                        $msg['FLAG']        = FALSE;
                        $msg['IS_ACTIVE']   = FALSE;
                    }
                    else
                    {
                        $msg['FLAG']        = TRUE;
                        $msg['IS_ACTIVE']   = FALSE;
                        $msg['MESSAGE']     = $this->lang->line('user_not_registered');
                    }
                }
            }
            else {
                $msg['MESSAGE'] = $this->lang->line('user_not_registered');
                $msg['FLAG']    = false;
                $msg['IS_ACTIVE']   = FALSE;
            }
        }
        $this->response($msg, 200);
    }

    /*
        @Description        :Connection details
        @Author             :Parag Joshi
        @input              :user_id
        @Output             :
        @Date               :29-11-2017
        @Webservices link   :
     */
    function connection_details_post()
    {
        $data = $this->post();

        $this->form_validation->set_rules('user_id', 'User id', 'trim|required');
        $this->form_validation->set_rules('to_user_id', 'To User id', 'trim|required');
        $this->form_validation->set_rules('uuid', 'UUID', 'trim|required');

        if ($this->form_validation->run() == false) {
            $msg['MESSAGE'] = strip_tags(validation_errors());
            $msg['code']    = "400";
        } 
        else 
        {    
            if($this->Common_function_model->check_device_uuid($data['user_id'],$data['uuid']) == 'false')
            {
                $msg['code']        = "400";
                $msg['MESSAGE']     = $this->lang->line('uuid_mismatch');
            }
            else
            {
                $params = array(
                        'table'=>'user_master',
                        'where'=>array('id' => "'".$data['to_user_id']."'"),
                        'fields'=>array('id', 'is_facebook_url','profile_image','first_name', 'last_name', 'email_id', 'date_of_birth','anniversary', 'mobile_number', 'gender','uuid','device_token','device_type','status','is_verify'),
                        'compare_type' => '='
                    );
                $user_details = $this->Common_function_model->getmultiple_tables($params);

                if (!empty($user_details) && count($user_details) > 0) 
                {
                    if($user_details[0]['status'] == 0)
                    {
                        $msg['code']        = "400";
                        $msg['MESSAGE']     = $this->lang->line('user_inactive');
                    }
                    elseif ($user_details[0]['is_verify'] == '0')
                    {
                        $msg['MESSAGE']     = $this->lang->line('account_not_verified');
                        $msg['code']        = "400";
                    }
                    else
                    {
                        $user_details[0]['is_facebook_url'] = ((!empty($user_details[0]['is_facebook_url']) && $user_details[0]['is_facebook_url'] == '1') ? '1' : '0');
                        
                        if(empty($user_details[0]['is_facebook_url']) && !empty($user_details[0]['profile_image']) && file_exists($this->config->item('user_big_path') . $user_details[0]['profile_image']))
                        {
                            $user_details[0]['profile_image'] = $this->config->item('user_big_url').$user_details[0]['profile_image'];
                        }
                        else if(!empty($user_details[0]['is_facebook_url']) && $user_details[0]['is_facebook_url'] == '1')
                        {
                            $user_details[0]['profile_image'] = (!empty($user_details[0]['profile_image']) ? $user_details[0]['profile_image'] : ''); 
                        }
                        else
                        {
                            $user_details[0]['profile_image'] = '';
                        }

                        if($user_details[0]['anniversary'] != '0000-00-00')
                        {
                            $user_details[0]['anniversary'] = dateformat($user_details[0]['anniversary']);
                        }
                        else
                        {
                            $user_details[0]['anniversary'] = "";
                        }

                        if($user_details[0]['date_of_birth'] != '0000-00-00')
                        {
                            $user_details[0]['date_of_birth'] = dateformat($user_details[0]['date_of_birth']);
                        }
                        else
                        {
                            $user_details[0]['date_of_birth'] = 'Not available';
                        }

                        $user_details[0]['connection_status'] = $this->Common_function_model->check_friend_status($data['user_id'],$data['to_user_id']);

                        if($user_details[0]['connection_status'] == '1')
                        {
                            $params = array(
                                'table'=>'board',
                                'where'=>array('created_by' => "'".$data['to_user_id']."'"),
                                'fields'=>array('id','name','occasion','date','view','status','board_image'),
                                'compare_type' => '='
                            );
                            $board_details = $this->Common_function_model->getmultiple_tables($params);

                            if($data['user_id'] == $data['to_user_id'])
                            {
                                $count = count($board_details);

                                for ($i=0; $i < $count ; $i++) 
                                { 
                                    if($board_details[$i]['date'] != '0000-00-00')
                                    {
                                        $board_details[$i]['date'] = dateformat($board_details[$i]['date']);
                                    }
                                    else
                                    {
                                        $board_details[$i]['date'] = "";
                                    }
                                    
                                    if(!empty($board_details[$i]['board_image']) && file_exists($this->config->item('board_big_path') . $board_details[$i]['board_image']))
                                    {
                                        $board_details[$i]['board_image'] = $this->config->item('board_big_url').$board_details[$i]['board_image'];
                                    }
                                    elseif(!empty($board_details[$i]['board_image']) && file_exists($this->config->item('occasion_path') . $board_details[$i]['board_image']))
                                    {
                                        $board_details[$i]['board_image'] = $this->config->item('occasion_url').$board_details[$i]['board_image'];
                                    }
                                    else
                                    {
                                        $board_details[$i]['board_image'] = '';
                                    }
                                }
                                $user_details[0]['board_detail'] = $board_details;
                            }
                            elseif(!empty($board_details) && count($board_details) > 0)
                            {
                                $count = count($board_details);
                                $user_details[0]['board_detail'] = array();
                                for ($i=0; $i < $count ; $i++) 
                                { 
                                    if(!empty($board_details[$i]['board_image']) && file_exists($this->config->item('board_big_path') . $board_details[$i]['board_image']))
                                    {
                                        $board_details[$i]['board_image'] = $this->config->item('board_big_url').$board_details[$i]['board_image'];
                                    }
                                    elseif(!empty($board_details[$i]['board_image']) && file_exists($this->config->item('occasion_path') . $board_details[$i]['board_image']))
                                    {
                                        $board_details[$i]['board_image'] = $this->config->item('occasion_url').$board_details[$i]['board_image'];
                                    }
                                    else
                                    {
                                        $board_details[$i]['board_image'] = '';
                                    }

                                    if($board_details[$i]['view'] == '0' && $board_details[$i]['status'] == '0')
                                    {   
                                        $check_params = array(
                                            'table'=>'board_member_invite',
                                            'where'=>array('board_id' => "'".$board_details[$i]['id']."'", 'member_id' => "'".$data['user_id']."'"),
                                            'fields'=>array('id'),
                                            'compare_type' => '='
                                        );
                                        $check_board_member = $this->Common_function_model->getmultiple_tables($check_params);
                                        if(!empty($check_board_member))
                                        {
                                            array_push($user_details[0]['board_detail'], $board_details[$i]);
                                        }
                                    }
                                    elseif ($board_details[$i]['view'] == '1' && $board_details[$i]['status'] == '0') 
                                    {
                                        array_push($user_details[0]['board_detail'], $board_details[$i]);
                                    }
                                }
                            }
                        }

                        $msg['MESSAGE']         = $this->lang->line('message_type_success');
                        $msg['code']            = "200";
                        $msg['USER_DETAILS']    = $user_details;
                    }
                }
                else
                {
                    $msg['code']        = "400";
                    $msg['MESSAGE']     = $this->lang->line('user_not_registered');
                }
            }
        }
        $this->response($msg, 200);
    }

    public function delete_record_get()
    {
        $id = $this->get('id');

        if(!empty($id))
        {
            $this->db->where('requested_by', $id);
            $this->db->or_where('requested_to', $id);
            $this->db->delete('friend_request');
        }
        if(!empty($id))
        {
            $this->db->where('id', $id);
            $this->db->delete('user_master');
        }
    }

    public function invitation_url_get()
    {
        $id = $this->get('id');
        $url = base_url().'invitation/id/'.$id;

        $msg['code']            = "200";
        $msg['MESSAGE']         = $url;
        $this->response($msg, 200);
    }
}