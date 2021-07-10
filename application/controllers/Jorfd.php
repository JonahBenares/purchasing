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
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        foreach($this->super_model->select_all_order_by("joi_rfd","rfd_date","DESC") AS $rfd){
            $vendor = $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$rfd->pay_to);
            $joi_no = $this->super_model->select_column_where("joi_head","joi_no","joi_id",$rfd->joi_id);
            $revision_no = $this->super_model->select_column_where("joi_head","revision_no","joi_id",$rfd->joi_id);
            $count = $this->super_model->count_rows_where("joi_rfd","joi_id",$rfd->joi_id);
            $data['head'][]=array(
                "joi_id"=>$rfd->joi_id,
                "rfd_date"=>$rfd->rfd_date,
                "company"=>$rfd->company,
                "apv_no"=>$rfd->apv_no,
                "total_amount"=>$rfd->total_amount,
                "rfd_type"=>$rfd->rfd_type,
                "vendor"=>$vendor,
                "joi_no"=>$joi_no,
                "revision_no"=>$revision_no,
                "count"=>$count,
            );
        }
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        $this->load->view('jorfd/jorfd_list',$data);
        $this->load->view('template/footer');
    }
    
}

?>