<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jorfd extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->model('super_model');

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

    public function jorfd_list(){
        $data['head']=$this->super_model->select_all_order_by("joi_rfd","rfd_date","DESC");
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        $this->load->view('jorfd/jorfd_list',$data);
        $this->load->view('template/footer');
    }
    
}

?>