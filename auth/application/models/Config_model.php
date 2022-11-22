<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_model extends CI_Model {
	
	public function __construct() {
		$this->homedb = $this->load->database('home',true);
	}
	
	public function listing() {
		$this->homedb->select('*');
		$this->homedb->from('configuration');
		$this->homedb->order_by('config_id','DESC');
		$query = $this->homedb->get();
		return $query->row();
	}

	function getOneBy($where = array()){
		$this->homedb->select("*")->from("configuration"); 
		$this->homedb->where($where); 
		$query = $this->homedb->get();
		if ($query->num_rows() >0){  
    		return $query->row(); 
    	} 
    	return FALSE;
	}
}