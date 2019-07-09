<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po extends CI_Controller {

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

    public function purchase_order(){
        $this->load->view('template/header');        
        $this->load->view('po/purchase_order');
        $this->load->view('template/footer');
    }

    public function purchase_order_saved(){
        $this->load->view('template/header');        
        $this->load->view('po/purchase_order_saved');
        $this->load->view('template/footer');
    }

    public function cancelled_po(){
        $this->load->view('template/header');        
        $this->load->view('template/navbar');
        $this->load->view('po/cancelled_po');
        $this->load->view('template/footer');
    }
    
    public function po_list(){
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');   
        $this->load->view('template/navbar');     
        $this->load->view('po/po_list',$data);
        $this->load->view('template/footer');
    }

    public function view_history(){
        $this->load->view('template/header');      
        $this->load->view('po/view_history');
        $this->load->view('template/footer');
    }

    public function delivery_receipt(){
        $this->load->view('template/header');        
        $this->load->view('po/delivery_receipt');
        $this->load->view('template/footer');
    }

    public function rfd_prnt(){     
        $this->load->view('template/header');        
        $this->load->view('po/rfd_prnt');
        $this->load->view('template/footer');
    }

    public function reporder_prnt(){
        $this->load->view('template/header');        
        $this->load->view('po/reporder_prnt');
        $this->load->view('template/footer');
    }

    public function delivery_receipt_r(){
        $this->load->view('template/header');        
        $this->load->view('po/delivery_receipt_r');
        $this->load->view('template/footer');
    }


    public function rfd_prnt_r(){        
        $this->load->view('template/header');        
        $this->load->view('po/rfd_prnt_r');
        $this->load->view('template/footer');
    }


    public function done_po(){
        $this->load->view('template/header');        
        $this->load->view('template/navbar'); 
        $this->load->view('po/done_po');
        $this->load->view('template/footer');
    }

}

?>