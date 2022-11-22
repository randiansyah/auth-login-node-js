<?php 
/* 
 * COPYRIGHT PT.TEKNOLOGI KOTA HUJAN
 * API SERVICE - STORE MODELS
 * IN DEV
 */
defined('BASEPATH') OR exit('No direct script access allowed'); 
class Store_model extends CI_Model
{
	public function __construct()
	{
		$this->storedb = $this->load->database('store',true);
		$this->shipmentdb = $this->load->database('shipment',true);
    }  

    public function insert($data){
        $this->storedb->insert('store', $data);
        return $this->storedb->insert_id();
    }

    function getOneBy($where = array()){
        $this->storedb->select("store.*")->from("store"); 
        $this->storedb->where($where); 

        $query = $this->storedb->get();
        if ($query->num_rows() >0){  
            return $query->row(); 
        } 
        return FALSE;
    }

    
    public function getAllById($where = array())
    {
        $this->storedb->select("store.store_id, 
            store.region_id, 
            store.store_name, 
            store.store_avatar, 
            store_badge.badge_label as badge, 
            store_badge.badge_img, 
            ")->from("store");
        $this->shipmentdb->join("shipment_area","store.region_id = shipment_area.id");        
        $this->storedb->join("store_badge","store_badge.badge_id = store.badge_id");        
        $this->storedb->where($where);
        $query = $this->storedb->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }
}