<?php
class TrafficControl_model extends CI_Model {

    function __construct()
    {
        parent::__construct();		
    }	 
    public function getData()
    {
        $this->db->select('id,name,sequence');
        return $this->db->get('traffic')->result();
    }
    public function updateRecords($updateData = array(),$id='')
    {
        //update traffic sequence
        $this->db->where('id',$id);
        $this->db->update('traffic',$updateData);
    }
}   
?>