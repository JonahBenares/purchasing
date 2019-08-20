<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jo extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->model('super_model');
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

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

    public function jo_list(){  

        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jo/jo_list');
        $this->load->view('template/footer');
    }

    public function getJoNo(){
        $year = $this->input->post('year');
        $rows_jo = $this->super_model->count_rows_where("jo_series", "year", $year);
        if($rows_jo==0){
            $jo_no='JO '.$year."-1";
        } else {
            $max = $this->super_model->get_max_where("jo_series", "series","year='$year'");
            $next= $max+1;
            $jo_no = 'JO '.$year."-".$next;
        }
        $return = array('jo_no' => $jo_no);
        echo json_encode($return);

    }

    public function job_order(){  
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');
        $this->load->view('jo/job_order',$data);
        $this->load->view('template/footer');
    }


    public function getVendorInformation(){

        $vendor = $this->input->post('vendor');
        $address= $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $vendor);
        $phone= $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $vendor);

        
        $return = array('address' => $address, 'phone' => $phone);
        echo json_encode($return);
    
    }


    public function jo_rfd(){  
        $this->load->view('template/header');
        $this->load->view('jo/jo_rfd');
        $this->load->view('template/footer');
    }
    public function jo_ac(){  
        $this->load->view('template/header');
        $this->load->view('jo/jo_ac');
        $this->load->view('template/footer');
    }
    public function jo_dr(){  
        $this->load->view('template/header');
        $this->load->view('jo/jo_dr');
        $this->load->view('template/footer');
    }

   
}

?>