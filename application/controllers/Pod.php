<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pod extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
        $this->load->model('super_model');
        date_default_timezone_set("Asia/Manila");
        
        function arrayToObject($array){
            if(!is_array($array)) { return $array; }
            $object = new stdClass();
            if (is_array($array) && count($array) > 0) {
                foreach ($array as $name=>$value) {
                    $name = strtolower(trim($name));
                    if (!empty($name)) { $object->$name = arrayToObject($value); }
                }
                return $object;
            } 
            else {
                return false;
            }
        }

	}

    public function additempod(){
        $this->load->view('template/header');   
        $this->load->view('pod/additempod');
        $this->load->view('template/footer');
    }
    
    public function pod_list(){
        $this->load->view('template/header');   
        $this->load->view('template/navbar');
        $this->load->view('pod/pod_list');
        $this->load->view('template/footer');
    }

    public function po_direct(){
        $this->load->view('template/header');  
        $this->load->view('pod/po_direct');
        $this->load->view('template/footer');
    }

    public function delivery_receipt(){
        $this->load->view('template/header');  
        $this->load->view('pod/delivery_receipt');
        $this->load->view('template/footer');
    }

    public function rfd_prnt(){
        $this->load->view('template/header');  
        $this->load->view('pod/rfd_prnt');
        $this->load->view('template/footer');
    }

}

?>