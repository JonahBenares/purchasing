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
           // echo "SELECT sum(quantity) AS total FROM po_items WHERE pr_details_id = '$pr->pr_details_id'";
            $unserved_qty=0;
            $unserved_uom='';
            if($sum_po_qty!=0){
                if($sum_po_qty < $pr->quantity){
                      $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    if($count_rfd == 0){
                        $status = 'PO Done';
                        $status_remarks = 'Pending RFD - Partial';
                    } else {
                        $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $po_id);
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);

                        $unserved_qty = $this->super_model->select_column_where('aoq_offers', 'balance', 'pr_details_id', $pr->pr_details_id);
                        $unserved_uom = $this->super_model->select_column_where('aoq_offers', 'uom', 'pr_details_id', $pr->pr_details_id);

                        $status = 'Partially Served';
                        $status_remarks = date('m.d.y', strtotime($dr_date)) . " - Served ". number_format($served_qty) . " " . $served_uom. " DR# ".$dr_no;
                    }
                } else {
                    $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    if($count_rfd == 0){
                        $status = 'PO Done';
                        $status_remarks = 'Pending RFD - Full';
                    } else {
                        $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $po_id);
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);

                        $status = 'Fully Served';
                        $status_remarks = date('m.d.y', strtotime($dr_date)) . " - Served DR# ".$dr_no;
                    }
                }
            } else {
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                    $status = 'Cancelled';
                    $status_remarks = $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                } else {

                    $count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                    $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '1'");
                    if($count_rfq!=0 && $count_aoq==0 && $count_aoq_awarded==0){
                        $status = 'Pending';
                        $status_remarks = 'For canvassing';
                    } else if($count_rfq!=0 && $count_aoq!=0 && $count_aoq_awarded==0){ 

                         $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                         $status = 'Pending';
                         $status_remarks = 'AOQ Done - For TE ' .date('m.d.y', strtotime($aoq_date)) ;
                    } else if($count_rfq!=0 && $count_aoq_awarded!=0){ 

                         $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '1'");
                         $status = 'Pending';
                         $status_remarks = 'For PO - AOQ Done (awarded)';
                    }

                }
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
                'status_remarks'=>$status_remarks,
                'unserved_qty'=>$unserved_qty,
                'unserved_uom'=>$unserved_uom

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

     public function add_remarks(){
        $pr_details_id =$this->input->post('pr_details_id');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $remarks=$this->input->post('remarks');
        $remark_date = date('Y-m-d H:i:s');


        $data=array(
            'add_remarks'=>$remarks,
            'remark_date'=>$remark_date,
            'remark_by'=>$_SESSION['user_id']
        );
      /*  echo  $pr_details_id;
        print_r($data);*/
        if($this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id)){
            redirect(base_url().'reports/pr_report/'.$year.'/'.$month);
        }
    } 
}
?>