<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrafficControl extends CI_Controller {
	function __construct()
    {
        parent::__construct();		
        $this->load->model('TrafficControl_model');
    }
	public function index()
	{
		$data['traffic_control'] = $this->TrafficControl_model->getData(); 
		$this->load->view('traffic_control',$data);
	}
	public function start(){
        try {
        	if(!empty($this->input->post('trafic_arr'))){
        		$trafficArray = $this->input->post('trafic_arr');
        		foreach ($trafficArray as $key => $value) {
        			$this->TrafficControl_model->updateRecords($value,$value['id']);
        		}
        	}
			$data['traffic_control'] = $this->TrafficControl_model->getData();
			$response = array('action' => 'success','traffic_control' => $this->load->view('ajax_traffic_control',$data,true));
        } catch (Exception $e) {
        	log_message('error',$e->getMessage());
			$response = array('action' => 'error');

        }
    echo json_encode($response);
    }
}
