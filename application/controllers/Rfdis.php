<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfdis extends CI_Controller {

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

     public function get_name($column, $table, $where){
        $col = $this->super_model->select_column_custom_where($table, $column, $where);
        return $col;
    }

	public function rfdis_list(){	
        $data['head']= $this->super_model->select_all_order_by("rfd", "rfd_date", "DESC");
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfdis/rfdis_list',$data);
        $this->load->view('template/footer');
    }

    public function rfdis_prnt(){
        $this->load->view('template/header');
        $this->load->view('rfdis/rfdis_prnt');
        $this->load->view('template/footer');
    }   

    public function additemrfd(){	
        $this->load->view('template/header');
        $this->load->view('rfdis/additemrfd');
        $this->load->view('template/footer');
    }

    public function rfdis_dr(){           
        $this->load->view('template/header');
        $this->load->view('rfdis/rfdis_dr');
        $this->load->view('template/footer');
    }

}

?>