<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

// Load the Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Auth extends REST_Controller {

    public function __construct() { 
        parent::__construct();     

        $this->load->model('user_model');
        $this->load->model('store_model');
        $this->load->model('email_model');
        $this->load->model('config_model');
        $this->load->model('ion_auth_model');
		$this->load->model("menu_model");
        $this->load->library('ion_auth');
        
        $this->data = array();          
		$this->data['users'] = array();         
		$this->data['is_superadmin'] = false; 
		$this->data['is_can_read'] = false;
		$this->data['is_can_rescheduled'] = false;
		$this->data['is_can_approval'] = false;
		$this->data['is_can_create'] = false;
		$this->data['is_can_edit'] = false;
		$this->data['is_can_delete'] = false;
		$this->data['is_can_search'] = false;
		$this->data['is_can_download'] = false;

		if ($this->ion_auth->in_group(1))
		{
			$this->data['is_superadmin'] = true;
		}

		if ($this->data['is_superadmin'])
		{
			$this->data['is_can_read'] = true;
			$this->data['is_can_edit'] = true;
			$this->data['is_can_create'] = true;
			$this->data['is_can_delete'] = true;
			$this->data['is_can_rescheduled'] = true;
			$this->data['is_can_approval'] = true;
			$this->data['is_can_search'] = true;
			$this->data['is_can_download'] = true;
		} 
	} 

    private function updateTokenWeb($token)
    { 
        $where = array("user_id" => $this->ion_auth->user()->row()->id, "type_key" => "web");
        $isHasToken = $this->user_model->checkToken($where);
        if (!$isHasToken) { 
            if($this->data['is_superadmin']){
                $level = 4; // superadmin
            }else{
                $level = 1;
            }
            $dataToken = array(
                "key" => $token,
                "user_id" => $this->ion_auth->user()->row()->id,
                "type_key" => "web",
                "level" => $level,
                "date_created" => time()
            );
            $this->user_model->addToken($dataToken);
        }else{
            $dataToken = array("key" => $token);
            $this->user_model->updateToken($dataToken, 
                    array("user_id" => $this->ion_auth->user()->row()->id, "type_key" => "web"));
        }
        
       return $token;
    }

    private function updateTokenMobile($token)
    { 
        $where = array("user_id" => $this->ion_auth->user()->row()->id, "type_key" => "mobile");
        $isHasToken = $this->user_model->checkToken($where);
        if (!$isHasToken) {
            $dataToken = array(
                "key" => $token,
                "user_id" => $this->ion_auth->user()->row()->id,
                "type_key" => "mobile",
                "level" => 1,
                "date_created" => time());
            $this->user_model->addToken($dataToken);
        } else {
            $dataToken = array("key" => $token);
            $this->user_model->updateToken($dataToken, 
                    array("user_id" => $this->ion_auth->user()->row()->id, "type_key" => "mobile"));
        }
        
       return $token;
    }

    private function getStore($id)
    {
        $data  = [];
        $store = $this->store_model->getAllById(array('user_id' => $id));
        if (!empty($store)) {
            foreach ($store as $val) {
                $data[] = array(
                    'store_id' => $val->store_id,
                    'store_name' => $val->store_name,
                    'store_avatar' => REST_Controller::CLOUD_URL . 'user/store/'.$val->store_avatar,
                );
            }
        }
        return $data;
    }    

    private function sendEmail($email, $kode)
    {
        $this->data['hash'] = base64_encode($email);
        $this->data['code'] = $kode;
        $config = $this->config_model->listing();
        $subject = 'Aktivasi akun BlanjaQue';
        $message = $this->load->view('auth/email/activation.tpl.php', $this->data,true);
        $email = $this->email_model->sendVerificatinEmail(
            $email,
            $subject,
            $message,
            $config);
    }

    private function getCodeRandom()
    {
        $number = '123456789';
        $code = array();
        $number_length = strlen($number) - 1;
        for ($i = 0; $i < 4; $i++)
        {
            $n = rand(0, $number_length);
            $code[] = $number[$n];
        }
        return implode($code);
    }    

    private function check($name, $value = '')
    {
        if (empty($value))
        {
            return FALSE;
        }

        return $this->db->where($name, $value)
                        ->limit(1)
                        ->count_all_results("users_temps") > 0;
    }

    private function usersCheck($name, $value = '')
    {
        if (empty($value))
        {
            return FALSE;
        }

        return $this->db->where($name, $value)
                        ->limit(1)
                        ->count_all_results("users") > 0;
    }    
    
    public function login_web_post() 
    {
        if ($this->_detect_api_key() == NULL) {
            $this->set_response([
                'message' => 'Methods not allowed',
            ], 404);   
        }elseif (!$this->_detect_api_key()) {
            $this->set_response([
                'message' => 'Incorrect token',
            ], 404);   
        }
        else
        {  
            $token = substr(hash('sha256', $this->input->post('password') . time()), 0, config_item('rest_key_length'));
            $remember = (bool)$this->post('remember');
            if($this->ion_auth->login($this->post('phone_or_email'), $this->input->post('password'), $remember)){
                $data = array('id'  => $this->ion_auth->user()->row()->id);
                $this->ion_auth->user()->row()->token = $this->updateTokenWeb($token); 
                $users[] = array(
                    'id' => $this->ion_auth->user()->row()->id,                    
                    'email' => $this->ion_auth->user()->row()->email,                                                    
                    'fullname' => $this->ion_auth->user()->row()->first_name,                    
                    'phone' => $this->ion_auth->user()->row()->phone,                    
                    'gender' => $this->ion_auth->user()->row()->gender,                    
                    'date_of_birth' => date('Y-m-d', strtotime($this->ion_auth->user()->row()->date_of_birth)),                    
                    'point' => $this->ion_auth->user()->row()->point,                    
                    'balance' => $this->ion_auth->user()->row()->balance,                    
                    'avatar' => REST_Controller::CLOUD_URL . 'user/profil/'.$this->ion_auth->user()->row()->photo,
                    'token' => $token,                                    
                    'is_deleted' => $this->ion_auth->user()->row()->is_deleted,        
                );    
                $this->_response = [
                    'status' => TRUE,
                    'data' => $users,
                    'message' => "Login Berhasil"
                ];
                $this->set_response($this->_response, 200);
            } 
            else 
            {           
                $this->_response = [
                    'status' => FALSE,
                    'message' => "User tidak ditemukan"
                ];
                $this->set_response($this->_response, 404);
            }
        }
    }

    public function login_mobile_post() 
    {
        if ($this->_detect_api_key() == NULL) {
            $this->set_response([
                'message' => 'Methods not allowed',
            ], 404);   
        }elseif (!$this->_detect_api_key()) {
            $this->set_response([
                'message' => 'Incorrect token',
            ], 404);   
        }
        else
        {  
            $token = substr(hash('sha256', $this->input->post('password') . time()), 0, config_item('rest_key_length'));
            $remember = (bool)$this->post('remember');
            if($this->ion_auth->login_mobile($this->post('phone_or_email'), $this->input->post('password'), $remember)){
                $data = array('id'  => $this->ion_auth->user()->row()->id);
                $this->ion_auth->user()->row()->token = $this->updateTokenMobile($token); 
                $users[] = array(
                    'id' => $this->ion_auth->user()->row()->id,                    
                    'email' => $this->ion_auth->user()->row()->email,                                                    
                    'fullname' => $this->ion_auth->user()->row()->first_name,                    
                    'phone' => $this->ion_auth->user()->row()->phone,                    
                    'gender' => $this->ion_auth->user()->row()->gender,                    
                    'date_of_birth' => date('Y-m-d', strtotime($this->ion_auth->user()->row()->date_of_birth)),                    
                    'point' => $this->ion_auth->user()->row()->point,                    
                    'balance' => $this->ion_auth->user()->row()->balance,                    
                    'avatar' => REST_Controller::CLOUD_URL . 'user/profil/'.$this->ion_auth->user()->row()->photo,
                    'token' => $token,                                    
                    'is_deleted' => $this->ion_auth->user()->row()->is_deleted,        
                    'store' => $this->getStore($this->ion_auth->user()->row()->id)
                );    
                $this->_response = [
                    'status' => TRUE,
                    'data' => $users,
                    'message' => "Login Berhasil"
                ];
                $this->set_response($this->_response, 200);
            } 
            else 
            {           
                $this->_response = [
                    'status' => FALSE,
                    'message' => "User tidak ditemukan"
                ];
                $this->set_response($this->_response, 404);
            }
        }
    }    

    public function register_post()
    {
        if ($this->_detect_api_key() == NULL) {
            $this->set_response([
                'message' => 'Methods not allowed',
            ], 404);   
        }elseif (!$this->_detect_api_key()) {
            $this->set_response([
                'message' => 'Incorrect token',
            ], 404);   
        }
        else
        {            
            $post = trim($this->post('phone_or_email'));
            $type = (is_numeric($post) === TRUE)? "phone" : "email";
            $code = $this->getCodeRandom();
            if (!empty($post)) {
                if($this->usersCheck($type,$post) === FALSE){
                    if($this->check("phone_or_email",$post) === TRUE){
                        $data = array(
                            'phone_or_email' => $post,
                            'activation_code' => $code,
                            'created' => time()
                        );
                        $this->user_model->update_step_1($data, array('phone_or_email' => $post));
                    }
                    else
                    {
                        $data = array(
                            'phone_or_email' => $post,
                            'activation_code' => $code,
                            'created' => time()
                        );
                        $this->user_model->insert_step_1($data);
                    }
                        $this->sendEmail($post, $code);
                        $this->_response = [
                            'status' => TRUE,
                            'message' => "Email telah dikirim"
                        ];
                    $this->set_response($this->_response, 200);
                    
                }else{
                    // $message = (is_numeric($post) === TRUE)? "Nomor ponsel" : "Alamat email";
                    $message = ($post === TRUE)? "Nomor ponsel" : "Alamat email";
                    $this->_response = [
                        'status' => FALSE,
                        'message' => $message." sudah digunakan"
                    ];   

                    $this->set_response($this->_response, 404);
                }
            }else{
                $this->_response = [
                    'status' => FALSE,
                    'message' => "Email harus diisi"
                ];   

                $this->set_response($this->_response, 404);            
            }
        }
    }

    public function otp_post()
    {
        if ($this->_detect_api_key() == NULL) {
            $this->set_response([
                'message' => 'Methods not allowed',
            ], 404);   
        }elseif (!$this->_detect_api_key()) {
            $this->set_response([
                'message' => 'Incorrect token',
            ], 404);   
        }
        else
        {         
            $otp = $this->input->post('otp');
            if($this->check('activation_code', $otp) === TRUE){
                $this->_response = [
                    'status' => TRUE,
                    'message' => "Aktivasi berhasil, silahkan lengkapi pendaftaran"
                ];   
                $this->set_response($this->_response, 200);            
            }else{
                $this->_response = [
                    'status' => FALSE,
                    'message' => "Kode yang Anda masukkan salah"
                ];   
                $this->set_response($this->_response, 404);              
            }   
        }     
    }    

    public function account_post()
    {
        if ($this->_detect_api_key() == NULL) {
            $this->set_response([
                'message' => 'Methods not allowed',
            ], 404);   

        }elseif (!$this->_detect_api_key()) {
            $this->set_response([
                'message' => 'Incorrect token',
            ], 404);   
        }
        else
        {         
            $arrX = array("1", "2","3","4");
            $randIndex = array_rand($arrX);        
            $data = array(
                'first_name'    => $this->post('name'),
                'phone'         => $this->post('phone'),
                'email'         => $this->post('type_value'),
                'photo'         => 'default/default-picture-'.$arrX[$randIndex].'.png',
                'active'        => 1,
                'is_deleted'    => 0
            );
            $role = array(2);
            $password = $this->input->post('password'); 
            $email   = $this->post('type_value'); 
            $insert = $this->ion_auth->register(null,$password,$email,$data,$role);
            if ($insert)
            {   
                $delete = $this->user_model->delete_step_1(array('phone_or_email' => $this->input->post('type_value')));
                if($delete){
                    $this->_response = [
                        'status' => TRUE,
                        'message' => "Pendaftaran berhasil"
                    ];   

                    $this->set_response($this->_response, 200);                   
                }else{
                    $this->_response = [
                        'status' => FALSE,
                        'message' => "Pendaftaran akun gagal"
                    ];   

                    $this->set_response($this->_response, 404);   
                }
            }else{
                $this->_response = [
                    'status' => FALSE,
                    'message' => "Pendaftaran akun gagal"
                ];   
                $this->set_response($this->_response, 404);   
            }
        }
    }

    public function logout_post()
    {
        $api_key_variable = $this->config->item('rest_key_name');
        $user  = $this->ion_auth->getTokenBy(['api_keys.key' =>$this->_args[$api_key_variable]]);
        if ($user) {
        $where = ["user_id" => $user->id];
        $delete = $this->ion_auth->deleteToken($where);
            if($delete){
                $this->set_response([
                    'status' => TRUE,
                    'message' => 'Berhasil Logout',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }else{
                    $this->set_response([
                        'status' => FALSE,
                        'message' => 'Gagal Logout',
                        'data' => [],
                    ], 404);
                }
            }else{
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'Anda Belum Login',
                    'data' => [],
                ], 404);

        }
    }

}