<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Api_key_model extends CI_Model
{
	 
	public function __construct()
	{
		parent::__construct(); 
    }  

    function getOneBy($where = array()){
        $this->db->select("*")->from("api_keys"); 
		$this->db->where($where); 

		$query = $this->db->get();
		if ($query->num_rows() >0){  
    		return $query->row(); 
    	} 
    	return FALSE;
	}

}
