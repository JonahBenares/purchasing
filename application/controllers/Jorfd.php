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
        foreach($this->super_model->custom_query("SELECT * FROM joi_rfd GROUP BY joi_id ORDER BY rfd_date DESC") AS $rfd){
            //$revision_no = $this->super_model->select_column_where("joi_head","revision_no","joi_id",$rfd->joi_id);
            //$joi_no = $this->super_model->select_column_where("joi_head","joi_no","joi_id",$rfd->joi_id);
            $x=1;
            foreach($this->super_model->select_row_where("joi_rfd",'joi_id',$rfd->joi_id) AS $r){
                //$count = $this->super_model->count_rows_where("joi_rfd","joi_id",$r->joi_id);
                $vendor = $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$r->pay_to);
                $ewt= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $r->pay_to);
                $revision_no = $this->super_model->select_column_where("joi_head","revision_no","joi_id",$r->joi_id);
                $joi_no = $this->super_model->select_column_where("joi_head","joi_no","joi_id",$r->joi_id);
                $jo_no = "RFD - ".$joi_no."-".COMPANY. (($revision_no!=0) ? ".r".$revision_no : "")."-".$x;
                $data['head'][]=array(
                    "joi_rfd_id"=>$r->joi_rfd_id,
                    "joi_id"=>$r->joi_id,
                    "rfd_date"=>$r->rfd_date,
                    "company"=>$r->company,
                    "apv_no"=>$r->apv_no,
                    "total_amount"=>$r->total_amount,
                    "payment_amount"=>$r->payment_amount,
                    "rfd_type"=>$r->rfd_type,
                    "vendor"=>$vendor,
                    "joi_no"=>$jo_no,
                    "revision_no"=>$revision_no,
                    //"count"=>$count,
                );
                $x++;
            }  
        }
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        $this->load->view('jorfd/jorfd_list',$data);
        $this->load->view('template/footer');
    }
    
}

?>