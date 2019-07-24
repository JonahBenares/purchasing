<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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

     public function generate_pr_summary(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        redirect(base_url().'reports/pr_report/'.$year.'/'.$month);
    }

	public function pr_report(){
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $data['year']=$year;
        $data['month']=$month;
        if(empty($month)){
            $date = $year;
        } else {
         $date = $year."-".$month;
        }
        $data['date']=date('F Y', strtotime($date));

        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $pr){
            $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items WHERE pr_details_id = '$pr->pr_details_id'");

            if($sum_po_qty!=0){
                if($sum_po_qty < $pr->quantity){
                    $status = 'Partially Served';
                    $status_remarks = '';
                } else {
                    $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $po_id);
                    $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);

                    $status = 'Fully Served';
                    $status_remarks = date('m.d.y', strtotime($dr_date)) . " - Served DR# ".$dr_no;
                }
            } else {
                $status = 'Pending';
                $status_remarks = '';
            }
            $data['pr'][] = array(
                'date_prepared'=>$pr->date_prepared,
                'pr_no'=>$pr->pr_no,
                'purpose'=>$pr->purpose,
                'enduse'=>$pr->enduse,
                'department'=>$pr->department,
                'requestor'=>$pr->requestor,
                'item_description'=>$pr->item_description,
                'qty'=>$pr->quantity,
                'uom'=>$pr->uom,
                'status'=>$status,
                'status_remarks'=>$status_remarks

            );
        }

        $this->load->view('template/header');        
        $this->load->view('reports/pr_report',$data);
        $this->load->view('template/footer');
    }

    public function po_report(){
      
        $this->load->view('template/header');        
        $this->load->view('reports/po_report');
        $this->load->view('template/footer');
    }
}
?>