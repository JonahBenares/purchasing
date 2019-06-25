<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pr extends CI_Controller {

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


    public function pr_list(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/pr_list');
        $this->load->view('template/footer');
    }

    public function purchase_request(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/purchase_request');
        $this->load->view('template/footer');
    }

    public function pr_group(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');        
        $this->load->view('pr/pr_group');
        $this->load->view('template/footer');
    }
    
    public function choose_vendor(){
        $this->load->view('template/header');
        $this->load->view('pr/choose_vendor');
        $this->load->view('template/footer');
    }
}

?>