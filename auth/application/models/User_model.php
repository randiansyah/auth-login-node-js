<?php 
/* 
 * COPYRIGHT PT.TEKNOLOGI KOTA HUJAN
 * API SERVICE - USER MODELS
 * IN DEV
 */
defined('BASEPATH') OR exit('No direct script access allowed'); 
class User_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct(); 
	}  

    public function getAllById($where = array())
    {
        $this->db->select("users.*")->from("users");
        $this->db->where($where);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    function getOneBy($where = array()){
		$this->db->select("users.*")->from("users"); 
		$this->db->where($where); 

		$query = $this->db->get();
		if ($query->num_rows() >0){  
    		return $query->row(); 
    	} 
    	return FALSE;
	}

    public function addToken($data) {
        $result = $this->db->insert("api_keys", $data);
        if ( $this->db->affected_rows()> 0) {  
            $insert_id = $this->db->insert_id();
            return  $insert_id;
        }else{
            return false;
        }
    }

    public function checkToken($where){
        $this->db->select("*")->from("api_keys");
        $this->db->where($where);
        $query = $this->db->get();
        if ($query->num_rows() >0){
            return $query->row();
        }
        return FALSE;
    }
    
    public function updateToken($data,$where){
        $this->db->update('api_keys', $data, $where);
        return $this->db->affected_rows();
    } 

    public function getTokenBy($where){ 
        $this->db->select("users.*")->from("users")
        ->join('api_keys','users.id = api_keys.user_id')
        ->where($where); 
        $query = $this->db->get();
        if ($query->num_rows() >0){  
            return $query->row(); 
        } else{
            return false;
        }
    }

    public function checkLogin($where){
        $this->db->select("users.*")->from("users");
        $this->db->where($where);
        $query = $this->db->get();
        if ($query->num_rows() >0){
            return $query->row();
        }
        return FALSE;
    }

    public function update($data,$where){
		$this->db->update('users', $data, $where);
		return $this->db->affected_rows();
	}

    public function insert_step_1($data){
        $this->db->insert('users_temps', $data);
        return $this->db->insert_id();
    }

    public function update_step_1($data,$where){
        $this->db->update('users_temps', $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_step_1($where){
        $this->db->where($where);
        $this->db->delete('users_temps'); 
        if($this->db->affected_rows()){
            return TRUE;
        }
        return FALSE;
    }    

}
