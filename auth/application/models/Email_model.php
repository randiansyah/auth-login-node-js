<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Email_model extends CI_Model
{
  
  public function __construct()
  {
    parent::__construct(); 
        $this->load->library('email');
  } 

    function sendVerificatinEmail($email,$subject,$message,$con){

      $config = Array(
         'protocol'     => 'smtp',
         'smtp_host'    => 'srv80.niagahoster.com',
         'smtp_port'    =>  465,
         'smtp_timeout' => '7',
         'smtp_user'    => 'noreply@blanjaque.com', // change it to yours
         'smtp_pass'    => '{xqau97C=bB?', // change it to yours
         'mailtype'     => 'html',
         'charset'      => 'utf-8',
         'newline'      => '\r\n',
         'wordwrap'     =>  TRUE
      );
       
      $this->load->library('email', $config);
      $this->email->set_newline("\r\n");
      $this->email->set_mailtype("html");
      $this->email->from("noreply@blanjaque.com", $con->company_name);
      $this->email->to($email);  
      $this->email->subject($subject);
      $this->email->message($message);
      $this->email->send();
    }

}
