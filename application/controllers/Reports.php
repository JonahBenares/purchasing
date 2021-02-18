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

    public function generate_po_summary(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        redirect(base_url().'reports/po_report/'.$year.'/'.$month);
    }

    public function generate_unserved_report(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        redirect(base_url().'reports/unserved_report/'.$year.'/'.$month);
    }
    public function generate_sum_weekly_recom_report(){
        $date_recom_from = $this->input->post('date_recom_from');
        $date_recom_to = $this->input->post('date_recom_to');
        redirect(base_url().'reports/sum_weekly_recom/'.$date_recom_from.'/'.$date_recom_to);
    }
    public function generate_pending_weekly_recom_report(){
        $date_recom_from = $this->input->post('date_recom_from');
        $date_recom_to = $this->input->post('date_recom_to');
        redirect(base_url().'reports/pending_weekly_recom/'.$date_recom_from.'/'.$date_recom_to);
    }

    public function like($str, $searchTerm) {
        $searchTerm = strtolower($searchTerm);
        $str = strtolower($str);
        $pos = strpos($str, $searchTerm);
        if ($pos === false)
            return false;
        else
            return true;
    }

	public function pr_report(){

        $year1=$this->uri->segment(3);
        $month1=$this->uri->segment(4);
        /*$data['year']=$year;
        $data['month']=$month;*/
        if(!empty($year1)){
            $data['year'] = $year1;
            $year = $year1;
        } else {
            $data['year']= "null";
            $year= "null";
        }

        if(!empty($month1)){
            $data['month'] = $month1;
            $month = $month1;
        } else {
            $data['month']= "null";
            $month= "null";
        }
       
        if($month!='null'){
             $date = $year."-".$month;
             $data['date']=date('F Y', strtotime($date));
        } else {
             $date = $year;
             $data['date']=$date;
        }

        /*if(!empty($month)){
             $date = $year."-".$month;
             $data['date']=date('F Y', strtotime($date));
        } else {
             $date = $year;
             $data['date']=$date;
        }*/
        $data['company']=$this->super_model->select_all_order_by("company","company_name","ASC");
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $pr){
            //echo $pr->wh_stocks;
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $pr->pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr->pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $pr->pr_details_id, "po_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
            //echo $po_id." - ".$po_items_id." - ".$cancelled_items_po."<br>";
           //echo $pr->pr_details_id . " = " . $sum_po_qty . " - " .  $sum_delivered_qty . ", " . $pr->quantity . "<br>";
           // echo "SELECT sum(quantity) AS total FROM po_items WHERE pr_details_id = '$pr->pr_details_id'";
            $unserved_qty=0;
            $unserved_uom='';  
            $statuss='';
            $status='';
            $status_remarks='';
            if($sum_po_qty!=0){
                if($sum_po_qty < $pr->quantity){

                      $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                 /*   if($count_rfd == 0){
                        $status = 'PO Done';
                        $status_remarks = 'Pending RFD - Partial';
                    } else {*/
                       /* $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $po_id);*/
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        //$served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_qty = $this->super_model->select_sum("po_items", "quantity", "pr_details_id",$pr->pr_details_id);
                        $delivered_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);

                      /*  $unserved_qty = $this->super_model->select_column_custom_where('aoq_offers', 'balance', "pr_details_id='$pr->pr_details_id' AND recommended = '1'");
                        $unserved_uom = $this->super_model->select_column_custom_where('aoq_offers', 'uom', "pr_details_id='$pr->pr_details_id' AND recommended = '1'");*/
                        if($cancelled_head_po==0){
                            $unserved_qty = $pr->quantity - $served_qty;
                        }else{
                            $unserved_qty = '';
                        }
                        $unserved_uom =  $served_uom;

                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        $count_po_unserved = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '0' AND cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                        $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '1' AND cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                        $count_po_all = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE  cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                       //  echo $po_id. " = " . $served . "<br>";
                        //if($served==0){
                        //echo $count_po_unserved . "<br>";
                        //echo $count_po_served . "<br>"; 
                        if($count_po_unserved !=0 && $count_po_served==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status = 'PO Issued - Partial<br><br>';
                            }
                            $status_remarks='';
                        }else if($count_po_unserved !=0  && $count_po_served!=0 && $cancelled_head_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= 'PO Issued - Partial<br><br>';
                                $status .= 'Partially Delivered';
                            }
                            $status_remarks='';
                        } else if(($count_po_unserved == 0 && $count_po_served == $count_po_all) || ($count_po_unserved == 0 && $count_po_served !=0)) {
                           // $status_remarks = '';
                            
                       // } else {
                            
                            $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                            if($cancelled_head_po!=0){
                               // $status .='';
                                $statuss = 'Partially Delivered';
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status.="Partially Delivered / Cancelled";
                                }
                            }else if($cancelled_items_po==0){
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status .= 'Partially Delivered';
                                }
                            }else {
                                $statuss = 'Partially Delivered';
                                $status.="Cancelled";
                            }
                            //$status_remarks ='';
                            //$status_remarks = date('m.d.y', strtotime($date_delivered)) . " - Delivered ". number_format($served_qty) . " " . $served_uom. " DR# ".$dr_no;

                           // $status_remarks='';
                            foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr->pr_details_id' AND date_received!=''") AS $del){
                               // foreach($this->super_model->select_row_where("po_dr_items", "pr_details_id", $pr->pr_details_id) AS $del){
                                 
                                if($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id!='')){
 //echo $pr->pr_details_id."date<br>";
                                 $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                }
                                if(empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND ph.po_id = '$del->po_id' AND pi.pr_details_id = '$pr->pr_details_id'");
                                    $status_remarks.="PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty .")</span>";
                                }
                            }

                        }
                       // echo $pr->pr_details_id ." = " . $count_po_unserved . "-". $count_po_served. "-". $cancelled_head_po. ", ". $status_remarks."<br>";
                  //  }
                } else {
                    //echo $pr->pr_details_id ."hello<br>";
                    $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                    if($served==0){
                        if($cancelled_items_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else{
                                $status .= 'PO Issued';
                            }
                        }else if($cancelled_items_po==0 && $pr->fulfilled_by==1){
                            $status="Delivered by ".$company;
                        }else if($cancelled_items_po==0 && $pr->for_recom==1){
                            $status="For Recom";
                        }else {
                            $statuss = 'PO Issued';
                            $status .= 'Cancelled';
                        }
                        $status_remarks = '';
                    } else {
                        if($cancelled_items_po==0){
                            $status .= 'Fully Delivered';
                        }else {
                            $statuss = 'Fully Delivered';
                            $status .= 'Cancelled';
                        }
                        $status_remarks='';
                       foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr->pr_details_id' AND date_received!=''") AS $del){
                        //foreach($this->super_model->select_row_where("po_dr_items", "pr_details_id", $pr->pr_details_id) AS $del){
                             $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                        }
                    }
/*
                    if($cancelled_head_po==1){
                        $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                        $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                        $statuss = "Cancelled";
                        $status = "Cancelled";
                        $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                    }*/
                }
            } else {
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                    $statuss = "Cancelled";
                    $status .= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                }else if($cancelled_head_po==1){
                    $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                    $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                    $statuss = "Cancelled";
                    $status .= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                } else {

                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                     $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '1' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

                    /*  echo "SELECT sum(delivered_quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id' = ". $sum_po_delivered_qty . "<br>";*/
                  
                    $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$pr->pr_details_id'");
                   
                    //$count_rfq_completed = $this->super_model->count_custom_query("SELECT rh.rfq_id FROM rfq_head rh INNER JOIN rfq_details rd ON rh.rfq_id = rd.rfq_id WHERE rd.pr_details_id= '$pr->pr_details_id' AND completed='1'");
                    $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                    
                   $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                 
 //$count_rfq ="SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$pr->pr_details_id'";
                    //echo $po_id . "<br>";
                    if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0 && $pr->for_recom==0){
                        //if($cancelled_items_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = 'For RFQ';
                    } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                        $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                         //if($cancelled_items_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = 'Canvassing Ongoing';
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                        //if($cancelled_items_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' ");
                         //if($cancelled_items_po==0){
                        if($pr->on_hold==1){
                            $status .="On-Hold";
                        }else if($pr->fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($pr->for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                      /*  if(!empty($aoq_date)){
                            $date=date('m.d.y', strtotime($aoq_date));
                        }else{
                            $date='';
                        }*/

                        $status_remarks = 'AOQ Done - For TE - ' .$aoq_date;
                    } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){
                        //if($cancelled_items_po==0){
                        if($pr->on_hold==1){
                            $status .="On-Hold";
                        }else if($pr->fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($pr->for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = 'For PO - AOQ Done (awarded)';
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                        //if($cancelled_items_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            }
                        /*}else {
                            $statuss = "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            $status = 'Cancelled';
                        }   */
                        $status_remarks = '';
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po_served!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po_served!=0)){ 
                        //if($cancelled_items_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            }
                        /*}else {
                            $statuss = "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = '';
                    } 

                }
            }
            $revised='';
            $revision_no = $this->super_model->select_column_where("po_head","revision_no","po_id",$po_id);
            if($revision_no!=0){
                foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM po_items_revised WHERE po_id = '$po_id' AND pr_details_id = '$pr->pr_details_id' GROUP BY revision_no") AS $rev){
                    if($rev->revision_no == 0){
                         $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                    } else {
                         $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                    }
                }
            }


        /*      $prs[] = array(
                    'pr_details_id'=>$pr->pr_details_id,
                    'date_prepared'=>$pr->date_prepared,
                    'purchase_request'=>$pr->purchase_request,
                    'pr_no'=>$pr->pr_no,
                    'purpose'=>$pr->purpose,
                    'enduse'=>$pr->enduse,
                    'department'=>$pr->department,
                    'requestor'=>$pr->requestor,
                    'grouping_id'=>$pr->grouping_id,
                    'item_description'=>$pr->item_description,
                    'item_no'=>$pr->item_no,
                    'wh_stocks'=>$pr->wh_stocks,
                    'qty'=>$pr->quantity,
                    'revised_qty'=>$revised,
                    'uom'=>$pr->uom,
                    'status'=>$status,
                    'status_remarks'=>$status_remarks,
                    'date_needed'=>$pr->date_needed,
                    'unserved_qty'=>$unserved_qty,
                    'unserved_uom'=>$unserved_uom,
                    'remarks'=>$pr->add_remarks,
                    'cancelled'=>$pr->cancelled,
                );*/

            $pr_id = $pr->pr_id;


            $data['pr'][] = array(
                'po_offer_id'=>$po_offer_id,
                'pr_details_id'=>$pr->pr_details_id,
                'pr_id'=>$pr_id,
                'date_prepared'=>$pr->date_prepared,
                'purchase_request'=>$pr->purchase_request,
                'pr_no'=>$pr->pr_no,
                'purpose'=>$pr->purpose,
                'enduse'=>$pr->enduse,
                'department'=>$pr->department,
                'requestor'=>$pr->requestor,
                'grouping_id'=>$pr->grouping_id,
                'item_description'=>$pr->item_description,
                'item_no'=>$pr->item_no,
                'wh_stocks'=>$pr->wh_stocks,
                'qty'=>$pr->quantity,
                'revised_qty'=>$revised,
                'uom'=>$pr->uom,
                'status'=>$status,
                'status_remarks'=>$status_remarks,
                'date_needed'=>$pr->date_needed,
                'unserved_qty'=>$unserved_qty,
                'unserved_uom'=>$unserved_uom,
                'remarks'=>$pr->add_remarks,
                'company'=>$this->super_model->select_column_where('company','company_name','company_id',$pr->company_id),
                'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$pr->vendor_id),
                'ver_date_needed'=>$pr->ver_date_needed,
                'estimated_price'=>$pr->estimated_price,
                'date_delivered'=>$pr->date_delivered,
                'unit_price'=>$pr->unit_price,
                'qty_delivered'=>$pr->qty_delivered,
                'cancel_remarks'=>$pr->cancel_remarks,
                'fulfilled_by'=>$pr->fulfilled_by,
                'for_recom'=>$pr->for_recom,
                'cancelled'=>$pr->cancelled,
                'cancelled_items_po'=>$cancelled_items_po,
                'on_hold'=>$pr->on_hold,
               /* 'count_rfq'=>$count_rfq,
                'count_aoq_awarded'=>$count_aoq_awarded,
                'po_id'=>$po_id,
            
                'sum_po_qty'=>$sum_po_qty,*/
                
            );

        }
        //print_r($prs);

        $this->load->view('template/header');        
        $this->load->view('reports/pr_report',$data);
        $this->load->view('template/footer');
        
    }

    public function insert_changestatus(){
        $count_onhold = count($this->input->post('onhold'));
        $count_proceed = count($this->input->post('proceed'));
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        for($x=0;$x<$count_onhold;$x++){
            $onhold = $this->input->post('onhold['.$x.']');
            //$onhold_date=date('Y-m-d',strtotime($this->super_model->select_column_where("pr_details","onhold_date","pr_details_id",$onhold)));
            $data=array(
                'on_hold'=>1,
                'onhold_date'=>date('Y-m-d h:i:s'),
                'onhold_by'=>$_SESSION['user_id'],
            );
            if($this->super_model->update_where("pr_details", $data, "pr_details_id",$onhold)){
                echo "<script>alert('Successfully Changed Status!');</script>"; 
                echo "<script>window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
            }
        }

        for($y=0;$y<$count_proceed;$y++){
            $proceed = $this->input->post('proceed['.$y.']');
            $data_proceed=array(
                'on_hold'=>0,
            );
            if($this->super_model->update_where("pr_details", $data_proceed, "pr_details_id=",$proceed)){
                echo "<script>alert('Successfully Changed Status!');</script>"; 
                echo "<script>window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
            }
        }
    }

    public function search_pr(){
        if(!empty($this->input->post('year'))){
            $data['year'] = $this->input->post('year');
            $year = $this->input->post('year');
        } else {
            $data['year']= "null";
            $year= "null";
        }

        if(!empty($this->input->post('month'))){
            $data['month'] = $this->input->post('month');
            $month = $this->input->post('month');
        } else {
            $data['month']= "null";
            $month= "null";
        }

        if(!empty($this->input->post('date_receive'))){
            $data['date_receive'] = $this->input->post('date_receive');
        } else {
            $data['date_receive']= "null";
        }

        if(!empty($this->input->post('purchase_request'))){
            $data['purchase_request'] = $this->input->post('purchase_request');
        } else {
            $data['purchase_request']= "null";
        }

        if(!empty($this->input->post('purpose'))){
            $data['purpose1'] = $this->input->post('purpose');
        } else {
            $data['purpose1']= "null";
        }

        if(!empty($this->input->post('enduse'))){
            $data['enduse1'] = $this->input->post('enduse');
        } else {
            $data['enduse1'] = "null";
        }

        if(!empty($this->input->post('pr_no'))){
            $data['pr_no1'] = $this->input->post('pr_no');
        } else {
            $data['pr_no1'] = "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
        } else {
            $data['requestor'] = "null";
        } 

        if(!empty($this->input->post('description'))){
            $data['description'] = $this->input->post('description');
        } else {
            $data['description'] = "null";
        } 

        $sql="";
        $filter = " ";

        if(!empty($this->input->post('date_receive'))){
            $date_receive = $this->input->post('date_receive');
            $sql.=" ph.date_prepared LIKE '%$date_receive%' AND";
            $filter .= "Date Received/Emailed - ".$date_receive.", ";
        }

        if(!empty($this->input->post('purchase_request'))){
            $purchase_request = $this->input->post('purchase_request');
            $sql.=" ph.purchase_request LIKE '%$purchase_request%' AND";
            $filter .= "Purchase Request - ".$purchase_request.", ";
        }

        if(!empty($this->input->post('purpose'))){
            $purpose = $this->input->post('purpose');
            $sql.=" ph.purpose LIKE '%$purpose%' AND";
            $filter .= "Purpose - ".$purpose.", ";
        }

        if(!empty($this->input->post('enduse'))){
            $enduse = $this->input->post('enduse');
            $sql.=" ph.enduse LIKE '%$enduse%' AND";
            $filter .= "Enduse - ".$enduse.", ";
        }

        if(!empty($this->input->post('pr_no'))){
            $pr_no = $this->input->post('pr_no');
            $sql.=" ph.pr_no LIKE '%$pr_no%' AND";
            $filter .= "Pr No. - ".$this->super_model->select_column_where('pr_head', 'pr_no', 
                        'pr_id', $pr_no).", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" ph.requestor LIKE '%$requestor%' AND";
            $filter .= "Requestor - ".$requestor.", ";
        }

        if(!empty($this->input->post('description'))){
            $description = $this->input->post('description');
            $sql.=" pd.item_description LIKE '%$description%' AND";
            $filter .= "Item Description - ".$description.", ";
        }

        $query=substr($sql, 0, -3);
        $data['filt']=substr($filter, 0, -2);
        /*$year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $data['year']=$year;
        $data['month']=$month;*/
        /*if(empty($month)){
            $date = $year;
        } else {
         $date = $year."-".$month;
        }*/
        if($month!='null'){
             $date = $year."-".$month;
             $data['date']=date('F Y', strtotime($date));
        } else {
             $date = $year;
             $data['date']=$date;
        }
        //$data['date']=date('F Y', strtotime($date));
        $data['company']=$this->super_model->select_all_order_by("company","company_name","ASC");
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ".$query) AS $pr){
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $pr->pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr->pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            //$cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $pr->pr_details_id, "po_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
           //echo $pr->pr_details_id . " = " . $sum_po_qty . " - " .  $sum_delivered_qty . ", " . $pr->quantity . "<br>";
           // echo "SELECT sum(quantity) AS total FROM po_items WHERE pr_details_id = '$pr->pr_details_id'";
            $unserved_qty=0;
            $unserved_uom='';  
            $statuss='';
            $status='';
            if($sum_po_qty!=0){
                if($sum_po_qty < $pr->quantity){
                      $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                 /*   if($count_rfd == 0){
                        $status = 'PO Done';
                        $status_remarks = 'Pending RFD - Partial';
                    } else {*/
                       /* $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $po_id);*/
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        //$served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_qty = $this->super_model->select_sum("po_items", "quantity", "pr_details_id",$pr->pr_details_id);
                        $delivered_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);

                      /*  $unserved_qty = $this->super_model->select_column_custom_where('aoq_offers', 'balance', "pr_details_id='$pr->pr_details_id' AND recommended = '1'");
                        $unserved_uom = $this->super_model->select_column_custom_where('aoq_offers', 'uom', "pr_details_id='$pr->pr_details_id' AND recommended = '1'");*/

                        //$unserved_qty = $pr->quantity - $served_qty;
                        if($cancelled_head_po==0){
                            $unserved_qty = $pr->quantity - $served_qty;
                        }else{
                            $unserved_qty = '';
                        }
                        $unserved_uom =  $served_uom;

                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        $count_po_unserved = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '0' AND cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                        $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '1' AND cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                        $count_po_all = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE  cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                       //  echo $po_id. " = " . $served . "<br>";
                        //if($served==0){
                        //echo $count_po_unserved . "<br>";
                        //echo $count_po_served . "<br>"; 
                        if($count_po_unserved !=0 && $count_po_served==0){
                            //$status = 'PO Issued - Partial<br><br>';
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status = 'PO Issued - Partial<br><br>';
                            }
                            $status_remarks = '';
                        }else if($count_po_unserved !=0  && $count_po_served!=0 && $cancelled_head_po==0){
                            //$status .= 'PO Issued - Partial<br><br>';
                             //$status .= 'Partially Delivered';
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= 'PO Issued - Partial<br><br>';
                                $status .= 'Partially Delivered';
                            }
                            $status_remarks = '';
                        } //else if($count_po_unserved == 0 && $count_po_served == $count_po_all) {
                        else if(($count_po_unserved == 0 && $count_po_served == $count_po_all) || ($count_po_unserved == 0 && $count_po_served !=0)) {
                           // $status_remarks = '';
                            
                       // } else {

                            $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                            /*if($cancelled_head_po!=0){
                                $status .='';
                            }else if($cancelled_items_po==0){
                                $status .= 'Partially Delivered';
                            }else {
                                $statuss = 'Partially Delivered';
                                $status.="Cancelled";
                            }*/
                            if($cancelled_head_po!=0){
                               // $status .='';
                                $statuss = 'Partially Delivered';
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status.="Partially Delivered / Cancelled";
                                }
                            }else if($cancelled_items_po==0){
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status .= 'Partially Delivered';
                                }
                            }else {
                                $statuss = 'Partially Delivered';
                                $status.="Cancelled";
                            }
                            $status_remarks ='';
                            //$status_remarks = date('m.d.y', strtotime($date_delivered)) . " - Delivered ". number_format($served_qty) . " " . $served_uom. " DR# ".$dr_no;

                           // $status_remarks='';
                            foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr->pr_details_id' AND date_received!=''") AS $del){
                               // foreach($this->super_model->select_row_where("po_dr_items", "pr_details_id", $pr->pr_details_id) AS $del){
                                if($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id!='')){
                                    $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                }
                                if(empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND ph.po_id = '$del->po_id' AND pi.pr_details_id = '$pr->pr_details_id'");
                                    $status_remarks.="PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty .")</span>";
                                }
                            }
                        }
                  //  }
                } else {
                    $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                    if($served==0){
                        if($cancelled_items_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else{
                                $status .= 'PO Issued';
                            }
                        }else if($cancelled_items_po==0 && $pr->fulfilled_by==1){
                            $status="Delivered by ".$company;
                        }else if($cancelled_items_po==0 && $pr->for_recom==1){
                            $status="For Recom";
                        }else {
                            $statuss = 'PO Issued';
                            $status .= 'Cancelled';
                        }
                        $status_remarks = '';
                    } else {
                        if($cancelled_items_po==0){
                            $status .= 'Fully Delivered';
                        }else {
                            $statuss = 'Fully Delivered';
                            $status .= 'Cancelled';
                        }
                        $status_remarks='';
                       foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr->pr_details_id' AND date_received!=''") AS $del){
                        //foreach($this->super_model->select_row_where("po_dr_items", "pr_details_id", $pr->pr_details_id) AS $del){
                             $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                        }
                    }
/*
                    if($cancelled_head_po==1){
                        $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                        $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                        $statuss = "Cancelled";
                        $status = "Cancelled";
                        $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                    }*/
                }
            } else {
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                    $statuss = "Cancelled";
                    $status .= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                }else if($cancelled_head_po==1){
                    $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                    $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                    $statuss = "Cancelled";
                    $status .= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                } else {

                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                     $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '1' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

                    /*  echo "SELECT sum(delivered_quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id' = ". $sum_po_delivered_qty . "<br>";*/
                  
                    //$count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                    $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$pr->pr_details_id'");
                    //$count_rfq_completed = $this->super_model->count_custom_query("SELECT rh.rfq_id FROM rfq_head rh INNER JOIN rfq_details rd ON rh.rfq_id = rd.rfq_id WHERE rd.pr_details_id= '$pr->pr_details_id' AND completed='1'");
                    $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                    
                   $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                 

                    //echo $po_id . "<br>";
                    if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0 && $pr->for_recom==0){
                        //if($cancelled_items_po==0){
                            //$status .= 'Pending';
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = 'For RFQ';
                    }else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                        $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                         //if($cancelled_items_po==0){
                            //$status .= 'Pending';
                        if($pr->on_hold==1){
                            $status .="On-Hold";
                        }else if($pr->fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($pr->for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = 'Canvassing Ongoing';
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                        //if($cancelled_items_po==0){
                            //$status .= 'Pending';
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                         //if($cancelled_items_po==0){
                            //$status .= 'Pending';
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        if(!empty($aoq_date)){
                            $date=date('m.d.y', strtotime($aoq_date));
                        }else{
                            $date='';
                        }

                        $status_remarks = 'AOQ Done - For TE ' .$date;
                    } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){
                        //if($cancelled_items_po==0){
                            //$status .= 'Pending';
                        if($pr->on_hold==1){
                            $status .="On-Hold";
                        }else if($pr->fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($pr->for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                        /*}else {
                            $statuss = 'Pending';
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = 'For PO - AOQ Done (awarded)';
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                        //if($cancelled_items_po==0){
                            //$status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            }
                        /*}else {
                            $statuss = "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            $status = 'Cancelled';
                        }   */
                        $status_remarks = '';
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po_served!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po_served!=0)){ 
                        //if($cancelled_items_po==0){
                            //$status .= "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            }
                        /*}else {
                            $statuss = "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                            $status = 'Cancelled';
                        }*/
                        $status_remarks = '';
                    } 

                }
            }
            $revised='';
            $revision_no = $this->super_model->select_column_where("po_head","revision_no","po_id",$po_id);
            if($revision_no!=0){
                foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM po_items_revised WHERE po_id = '$po_id' AND pr_details_id = '$pr->pr_details_id' GROUP BY revision_no") AS $rev){
                    if($rev->revision_no == 0){
                         $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                    } else {
                         $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                    }
                }
            }


        /*      $prs[] = array(
                    'pr_details_id'=>$pr->pr_details_id,
                    'date_prepared'=>$pr->date_prepared,
                    'purchase_request'=>$pr->purchase_request,
                    'pr_no'=>$pr->pr_no,
                    'purpose'=>$pr->purpose,
                    'enduse'=>$pr->enduse,
                    'department'=>$pr->department,
                    'requestor'=>$pr->requestor,
                    'grouping_id'=>$pr->grouping_id,
                    'item_description'=>$pr->item_description,
                    'item_no'=>$pr->item_no,
                    'wh_stocks'=>$pr->wh_stocks,
                    'qty'=>$pr->quantity,
                    'revised_qty'=>$revised,
                    'uom'=>$pr->uom,
                    'status'=>$status,
                    'status_remarks'=>$status_remarks,
                    'date_needed'=>$pr->date_needed,
                    'unserved_qty'=>$unserved_qty,
                    'unserved_uom'=>$unserved_uom,
                    'remarks'=>$pr->add_remarks,
                    'cancelled'=>$pr->cancelled,
                );*/

            $pr_id = $pr->pr_id;


            $data['pr'][] = array(
                'po_offer_id'=>$po_offer_id,
                'pr_details_id'=>$pr->pr_details_id,
                'pr_id'=>$pr_id,
                'date_prepared'=>$pr->date_prepared,
                'purchase_request'=>$pr->purchase_request,
                'pr_no'=>$pr->pr_no,
                'purpose'=>$pr->purpose,
                'enduse'=>$pr->enduse,
                'department'=>$pr->department,
                'requestor'=>$pr->requestor,
                'grouping_id'=>$pr->grouping_id,
                'item_description'=>$pr->item_description,
                'item_no'=>$pr->item_no,
                'wh_stocks'=>$pr->wh_stocks,
                'qty'=>$pr->quantity,
                'revised_qty'=>$revised,
                'uom'=>$pr->uom,
                'status'=>$status,
                'status_remarks'=>$status_remarks,
                'date_needed'=>$pr->date_needed,
                'unserved_qty'=>$unserved_qty,
                'unserved_uom'=>$unserved_uom,
                'remarks'=>$pr->add_remarks,
                'company'=>$this->super_model->select_column_where('company','company_name','company_id',$pr->company_id),
                'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$pr->vendor_id),
                'ver_date_needed'=>$pr->ver_date_needed,
                'estimated_price'=>$pr->estimated_price,
                'date_delivered'=>$pr->date_delivered,
                'unit_price'=>$pr->unit_price,
                'qty_delivered'=>$pr->qty_delivered,
                'cancel_remarks'=>$pr->cancel_remarks,
                'fulfilled_by'=>$pr->fulfilled_by,
                'for_recom'=>$pr->for_recom,
                'cancelled'=>$pr->cancelled,
                'cancelled_items_po'=>$cancelled_items_po,
                'on_hold'=>$pr->on_hold,
            );

        }
        
        $this->load->view('template/header');        
        $this->load->view('reports/pr_report',$data);
        $this->load->view('template/footer');
    }

    public function export_pr(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="PR Summary.xlsx";

        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        /*if(empty($month)){
            $date = $year;
        } else {
            $date = $year."-".$month;
        }
        $monthyear=date('F Y', strtotime($date));*/
        if($month!='null'){
             $date = $year."-".$month;
        } else {
             $date = $year;
        }
        $date_received=$this->uri->segment(5);
        $purpose=str_replace("%20", " ", $this->uri->segment(6));
        $enduse=str_replace("%20", " ", $this->uri->segment(7));
        $pr_no=$this->uri->segment(8);
        $requestor=str_replace("%20", " ", $this->uri->segment(9));
        $description=str_replace("%20", " ", $this->uri->segment(10));
        $purchase_request=$this->uri->segment(11);

        $sql="";
        $filter = " ";

        if($date_received!='null'){
            $sql.=" ph.date_prepared LIKE '%$date_received%' AND";
            $filter .= $date_received;
        }

        if($purchase_request!='null'){
            $sql.=" ph.purchase_request LIKE '%$purchase_request%' AND";
            $filter .= $purchase_request;
        }

        if($purpose!='null'){
            $sql.=" ph.purpose LIKE '%$purpose%' AND";
            $filter .= $purpose;
        }

        if($enduse!='null'){
            $sql.=" ph.enduse LIKE '%$enduse%' AND";
            $filter .= $enduse;
        }

        if($pr_no!='null'){
            $sql.=" ph.pr_no LIKE '%$pr_no%' AND";
            $filter .= $pr_no;
        }

        if($requestor!='null'){
            $sql.=" ph.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }

        if($description!='null'){
            $sql.=" pd.item_description LIKE '%$description%' AND";
            $filter .= $description;
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);



        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "PR Summary $date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "PURCHASE REQUEST");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "On Hold");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "Proceed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Date Received/Emailed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "Purchase Request");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "Purpose");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "Enduse");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "PR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Requestor");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "WH Stocks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Item No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Revised Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Grouping");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Item Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P4', "Verified Date Needed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q4', "Estimated");
        /*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "RO/with AOQ");*/
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R4', "Status Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S4', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T4', "Date Needed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U4', "Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V4', "Cancel Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W4', "End User's Comments");
        foreach(range('A','W') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:W4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:W4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:W4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ".$query) AS $pr){
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $pr->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr->pr_details_id);
                if($po_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                //$cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $pr->pr_details_id, "po_id", "DESC", "1");
                $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                $company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
                $unserved_qty=0;
                $unserved_uom='';
                $statuss='';
                $status='';
                if($sum_po_qty!=0){
                    if($sum_po_qty < $pr->quantity){
                        $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        //$served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_qty = $this->super_model->select_sum("po_items", "quantity", "pr_details_id",$pr->pr_details_id);
                        $delivered_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);
                        if($cancelled_head_po==0){
                            $unserved_qty = $pr->quantity - $served_qty;
                        }else{
                            $unserved_qty = '';
                        }
                        //$unserved_qty = $pr->quantity - $served_qty;
                        $unserved_uom =  $served_uom;
                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        $count_po_unserved = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '0' AND cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                        $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '1' AND cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                        $count_po_all = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE  cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");

                        if($count_po_unserved !=0 && $count_po_served==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status = "PO Issued - Partial\n \n";
                            }
                            $status_remarks='';
                        }else if($count_po_unserved !=0  && $count_po_served!=0 && $cancelled_head_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= "PO Issued - Partial\n \n";
                                $status .= 'Partially Delivered';
                            }
                            $status_remarks='';
                        }  else if(($count_po_unserved == 0 && $count_po_served == $count_po_all) || ($count_po_unserved == 0 && $count_po_served !=0)) {
                            $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                            /*if($cancelled_head_po!=0){
                                //$status .='';
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status.="Partially Delivered / Cancelled";
                                }
                            }else if($cancelled_items_po==0){
                                $status .= 'Partially Delivered';
                            }else {
                                $statuss = 'Partially Delivered';
                                $status.="Cancelled";
                            }*/
                            if($cancelled_head_po!=0){
                               // $status .='';
                                $statuss = 'Partially Delivered';
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status.="Partially Delivered / Cancelled";
                                }
                            }else if($cancelled_items_po==0){
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status .= 'Partially Delivered';
                                }
                            }else {
                                $statuss = 'Partially Delivered';
                                $status.="Cancelled";
                            }
                            $status_remarks ='';
                            foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr->pr_details_id' AND date_received!=''") AS $del){
                                if($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id!='')){
                                $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."(". $del->quantity . " ".$del->uom .")\n";
                                }
                                if(empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND ph.po_id = '$del->po_id' AND pi.pr_details_id = '$pr->pr_details_id'");
                                    $status_remarks.="PO Issued (". $sum_po_issued_qty .")";
                                }
                            }
                        }
                    } else {
                        $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        if($served==0){
                            if($cancelled_items_po==0){
                                //$status .= 'PO Issued';
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else{
                                    $status .= 'PO Issued';
                                }
                            }else if($cancelled_items_po==0 && $pr->fulfilled_by==1){
                                $status="Delivered by ".$company;
                            }else if($cancelled_items_po==0 && $pr->for_recom==1){
                                $status="For Recom";
                            }else {
                                $statuss = 'PO Issued';
                                $status .= 'Cancelled';
                            }
                            $status_remarks = '';
                        } else {
                            if($cancelled_items_po==0){
                                $status .= 'Fully Delivered';
                            }else {
                                $statuss = 'Fully Delivered';
                                $status .= 'Cancelled';
                            }
                            $status_remarks='';
                        foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr->pr_details_id' AND date_received!=''") AS $del){
                            //foreach($this->super_model->select_row_where("po_dr_items", "pr_details_id", $pr->pr_details_id) AS $del){
                                 $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."\n";
                            }
                        }

                        /*if($cancelled_head_po==1){
                            $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                            $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                            $statuss = "Cancelled";
                            $status = "Cancelled";
                            $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                        }*/
                    }
                } else {
                    $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                    if($cancelled_items==1){
                        $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                        $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                        $statuss = "Cancelled";
                        $status .= "Cancelled";
                        $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    }else if($cancelled_head_po==1){
                        $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                        $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                        $statuss = "Cancelled";
                        $status .= "Cancelled";
                        $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    } else {

                        $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                         $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '1' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

                        /*  echo "SELECT sum(delivered_quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id' = ". $sum_po_delivered_qty . "<br>";*/
                      
                        //$count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                        $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$pr->pr_details_id'");
                        //$count_rfq_completed = $this->super_model->count_custom_query("SELECT rh.rfq_id FROM rfq_head rh INNER JOIN rfq_details rd ON rh.rfq_id = rd.rfq_id WHERE rd.pr_details_id= '$pr->pr_details_id' AND completed='1'");
                        $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                        
                       $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                        $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                     

                        //echo $po_id . "<br>";
                        if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0 && $pr->for_recom==0){
                            //if($cancelled_items_po==0){
                                //$status .= 'Pending';
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                            /*}else {
                                $statuss = 'Pending';
                                $status = 'Cancelled';
                            }*/
                            $status_remarks = 'For RFQ';
                        } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                             //if($cancelled_items_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                                //$status .= 'Pending';
                            /*}else {
                                $statuss = 'Pending';
                                $status = 'Cancelled';
                            }*/
                            $status_remarks = 'Canvassing Ongoing';
                        } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                            //if($cancelled_items_po==0){
                                //$status .= 'Pending';
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else if($pr->for_recom==1){
                                    $status .= "For Recom";
                                }else{
                                    $status .= 'Pending';
                                }
                            /*}else {
                                $statuss = 'Pending';
                                $status = 'Cancelled';
                            }*/
                            $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                        } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                             //if($cancelled_items_po==0){
                                //$status .= 'Pending';
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else if($pr->for_recom==1){
                                    $status .= "For Recom";
                                }else{
                                    $status .= 'Pending';
                                }
                            /*}else {
                                $statuss = 'Pending';
                                $status = 'Cancelled';
                            }*/
                            if(!empty($aoq_date)){
                                $date=date('m.d.y', strtotime($aoq_date));
                            }else{
                                $date='';
                            }
                            $status_remarks = 'AOQ Done - For TE ' .$date;
                        } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){
                            //if($cancelled_items_po==0){
                                //$status .= 'Pending';
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                            /*}else {
                                $statuss = 'Pending';
                                $status = 'Cancelled';
                            }*/
                            $status_remarks = 'For PO - AOQ Done (awarded)';
                        } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                            //if($cancelled_items_po==0){
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else if($pr->for_recom==1){
                                    $status .= "For Recom";
                                }else{
                                    $status .= "PO Issued (". $sum_po_issued_qty . " ".$pr->uom .")";
                                }
                            /*}else {
                                $statuss = "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                                $status = 'Cancelled';
                            }   */
                            $status_remarks = '';
                        } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po_served!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po_served!=0)){ 
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "Partially Delivered (". $sum_po_issued_qty . " ".$pr->uom .")";
                            }
                            $status_remarks = '';
                        } 

                    }
                }
                $revised='';
                $revision_no = $this->super_model->select_column_where("po_head","revision_no","po_id",$po_id);
                if($revision_no!=0){
                    foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM po_items_revised WHERE po_id = '$po_id' AND pr_details_id = '$pr->pr_details_id' GROUP BY revision_no") AS $rev){
                        if($rev->revision_no == 0){
                             $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."\n";
                        } else {
                             $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."\n";
                        }
                    }
                }
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );

                if($unserved_qty!=0){
                    $unserved = " - UNSERVED ".$unserved_qty." ".$unserved_uom;
                }else {
                    $unserved = '';
                }

                $po_issue=$this->like($status, "PO Issued");
                $delivered_by=$this->like($status, "Delivered by");

                if($status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='For Recom') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('fd9c77');
                }else if($status=='On-Hold') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('d2deff');
                }else if($po_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }else if($delivered_by=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('eeccff');
                }
                $pr_no = $pr->pr_no."-".COMPANY;

                if(empty($pr->date_needed)){
                    $date_needed='';
                }else{
                    $date_needed=date('M j, Y', strtotime($pr->date_needed));
                }

                $supplier=$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$pr->vendor_id);
                if($pr->fulfilled_by==1){
                    $remarks=$pr->add_remarks."\n -".$pr->date_delivered."\n -".$supplier."\n -".$pr->unit_price."\n -".$pr->qty_delivered;
                }else{
                    $remarks=$pr->add_remarks;
                }
               if($status!='Fully Delivered' && $status!='Cancelled'){
                    if($pr->on_hold==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "✓");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "");
                    }else if($pr->on_hold==0 && $pr->onhold_date!=''){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "✓");
                    }
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->date_prepared");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$pr->purchase_request");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$pr->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$pr->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$pr->wh_stocks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$pr->item_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$pr->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$revised");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$pr->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$pr->grouping_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$pr->item_description $unserved");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$pr->ver_date_needed");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$pr->estimated_price");
                /*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "");*/
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "$status_remarks");
                //if($cancelled_items_po==0){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "$status");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$date_needed");
                //}else {
                    //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$statuss");
                //}
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$num, "$remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$num, "$pr->cancel_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$num, "");

                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('T'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('Q'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('Q'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setWrapText(true);
                /*$objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
                /*$objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->applyFromArray($styleArray);
                $num++;
            }
        }else {
            $num = 5;
            foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $pr){
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $pr->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr->pr_details_id);
                if($po_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                //$cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $pr->pr_details_id, "po_id", "DESC", "1");
                $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                $company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
               // echo "SELECT sum(quantity) AS total FROM po_items WHERE pr_details_id = '$pr->pr_details_id'";
                $unserved_qty=0;
                $unserved_uom='';
                $statuss='';
                $status='';
                if($sum_po_qty!=0){
                    if($sum_po_qty < $pr->quantity){
                          $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                            $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                            //$served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                            $served_qty = $this->super_model->select_sum("po_items", "quantity", "pr_details_id",$pr->pr_details_id);
                            $delivered_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'pr_details_id', $pr->pr_details_id);
                            $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);
                            if($cancelled_head_po==0){
                                $unserved_qty = $pr->quantity - $served_qty;
                            }else{
                                $unserved_qty = '';
                            }
                            //$unserved_qty = $pr->quantity - $served_qty;
                            $unserved_uom =  $served_uom;

                            $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                            $count_po_unserved = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '0' AND cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                            $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '1' AND cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                            $count_po_all = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE  cancelled ='0' AND pr_details_id = '$pr->pr_details_id'");
                            if($count_po_unserved !=0 && $count_po_served==0){
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status = "PO Issued - Partial \n \n";
                                }
                                $status_remarks='';
                            }else if($count_po_unserved !=0  && $count_po_served!=0 && $cancelled_head_po==0){
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else if($pr->fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status .= "PO Issued - Partial \n \n";
                                    $status .= 'Partially Delivered';
                                }
                                $status_remarks='';
                            } else if(($count_po_unserved == 0 && $count_po_served == $count_po_all) || ($count_po_unserved == 0 && $count_po_served !=0)) {
                                $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                                /*if($cancelled_head_po!=0){
                                    $status .='';
                                }else if($cancelled_items_po==0){
                                    $status .= 'Partially Delivered';
                                }else {
                                    $statuss = 'Partially Delivered';
                                    $status.="Cancelled";
                                }*/
                                if($cancelled_head_po!=0){
                                   // $status .='';
                                    $statuss = 'Partially Delivered';
                                    if($pr->on_hold==1){
                                        $status .="On-Hold";
                                    }else if($pr->fulfilled_by==1){
                                        $status .= "Delivered by ".$company;
                                    }else{
                                        $status.="Partially Delivered / Cancelled";
                                    }
                                }else if($cancelled_items_po==0){
                                    if($pr->on_hold==1){
                                        $status .="On-Hold";
                                    }else if($pr->fulfilled_by==1){
                                        $status .= "Delivered by ".$company;
                                    }else{
                                        $status .= 'Partially Delivered';
                                    }
                                }else {
                                    $statuss = 'Partially Delivered';
                                    $status.="Cancelled";
                                }
                                $status_remarks ='';
                                foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr->pr_details_id' AND date_received!=''") AS $del){
                                   // foreach($this->super_model->select_row_where("po_dr_items", "pr_details_id", $pr->pr_details_id) AS $del){
                                    if($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id!='')){
                                     $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."(". $del->quantity . " ".$del->uom .")\n";
                                    }
                                    if(empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                        $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND ph.po_id = '$del->po_id' AND pi.pr_details_id = '$pr->pr_details_id'");
                                        $status_remarks.="PO Issued (". $sum_po_issued_qty .")";
                                    }
                                }
                            }
                      //  }
                    } else {
                        $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        if($served==0){
                            //$status = 'PO Issued';
                            if($cancelled_items_po==0){
                                if($pr->on_hold==1){
                                    $status .="On-Hold";
                                }else{
                                    $status = 'PO Issued';
                                }
                            }else if($cancelled_items_po==0 && $pr->fulfilled_by==1){
                                $status="Delivered by ".$company;
                            }else if($cancelled_items_po==0 && $pr->for_recom==1){
                                $status="For Recom";
                            }else {
                                $statuss = 'PO Issued';
                                $status = 'Cancelled';
                            }
                            $status_remarks = '';
                        } else {
                            //$status = 'Fully Delivered';
                            if($cancelled_items_po==0){
                                $status = 'Fully Delivered';
                            }else {
                                $statuss = 'Fully Delivered';
                                $status = 'Cancelled';
                            }
                            $status_remarks='';
                            //foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                            foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr->pr_details_id' AND date_received!=''") AS $del){
                                 $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."\n";
                            }
                        }

                        /*if($cancelled_head_po==1){
                            $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                            $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                            $statuss = "Cancelled";
                            $status = "Cancelled";
                            $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                        } */
                    }
                } else {
                    $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                    if($cancelled_items==1){
                        $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                        $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                        $statuss = "Cancelled";
                        $status .= "Cancelled";
                        $status_remarks = $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    }else if($cancelled_head_po==1){
                        $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                        $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                        $statuss = "Cancelled";
                        $status .= "Cancelled";
                        $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    } else {
                        $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '1' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                        //$count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                        $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$pr->pr_details_id'");
                        $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                        $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                        $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                        if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0 && $pr->for_recom==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                            $status_remarks = 'For RFQ';
                        } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                            $status_remarks = 'Canvassing Ongoing';
                        } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                            $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                        } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                            $status_remarks = 'AOQ Done - For TE ' .date('m.d.y', strtotime($aoq_date));
                        } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                            $status_remarks = 'For PO - AOQ Done (awarded)';
                        } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "PO Issued (". $sum_po_issued_qty . " ".$pr->uom .")";
                            }
                            $status_remarks = '';
                        } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po_served!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po_served!=0)){ 
                            if($pr->on_hold==1){
                                $status .="On-Hold";
                            }else if($pr->fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($pr->for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "Partially Delivered (". $sum_po_issued_qty . " ".$pr->uom .")";
                            }
                            $status_remarks = '';
                        }
                    }
                }

                $revised='';
                $revision_no = $this->super_model->select_column_where("po_head","revision_no","po_id",$po_id);
                if($revision_no!=0){
                    foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM po_items_revised WHERE po_id = '$po_id' AND pr_details_id = '$pr->pr_details_id' GROUP BY revision_no") AS $rev){
                        if($rev->revision_no == 0){
                             $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."\n";
                        } else {
                             $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."\n";
                        }
                    }
                }

                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );

                if($unserved_qty!=0){
                    $unserved = " - UNSERVED ".$unserved_qty." ".$unserved_uom;
                }else {
                    $unserved = '';
                }

                $po_issue=$this->like($status, "PO Issued");
                $delivered_by=$this->like($status, "Delivered by");

                if($status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='For Recom') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('fd9c77');
                }else if($status=='On-Hold') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('d2deff');
                }else if($po_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }else if($delivered_by=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('eeccff');
                }
                $pr_no = $pr->pr_no."-".COMPANY;
                if(empty($pr->date_needed)){
                    $date_needed='';
                }else{
                    $date_needed=date('M j, Y', strtotime($pr->date_needed));
                }

                $supplier=$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$pr->vendor_id);
                if($pr->fulfilled_by==1){
                    $remarks=$pr->add_remarks."\n -".$pr->date_delivered."\n -".$supplier."\n -".$pr->unit_price."\n -".$pr->qty_delivered;
                }else{
                    $remarks=$pr->add_remarks;
                }
                if($status!='Fully Delivered' && $status!='Cancelled'){
                    if($pr->on_hold==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "✓");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "");
                    }else if($pr->on_hold==0 && $pr->onhold_date!=''){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "✓");
                    }
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->date_prepared");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$pr->purchase_request");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$pr->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$pr->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$pr->wh_stocks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$pr->item_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$pr->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$revised");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$pr->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$pr->grouping_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$pr->item_description $unserved");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$pr->ver_date_needed");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$pr->estimated_price");
                /*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "");*/
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "$status_remarks");
                //if($cancelled_items_po==0){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "$status");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$date_needed");
                //}else {
                    //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$statuss");
                //}
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$num, "$remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$num, "$pr->cancel_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$num, "");

                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('T'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('Q'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('Q'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setWrapText(true);
                /*$objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
                /*$objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":W".$num)->applyFromArray($styleArray);
                $num++;
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="PR Summary.xlsx"');
        readfile($exportfilename);
    }

    public function po_report(){
        $year1=$this->uri->segment(3);
        $month1=$this->uri->segment(4);
        if(!empty($year1)){
            $data['year'] = $year1;
            $year = $year1;
        } else {
            $data['year']= "null";
            $year= "null";
        }

        if(!empty($month1)){
            $data['month'] = $month1;
            $month = $month1;
        } else {
            $data['month']= "null";
            $month= "null";
        }
        /*$data['year']=$year;
        $data['month']=$month;*/
       
        if($month!='null'){
             $date = $year."-".$month;
             $data['date']=date('F Y', strtotime($date));
        } else {
             $date = $year;
             $data['date']=$date;
        }
        /*$data['year']=$year;
        $data['month']=$month;
       
        if(!empty($month)){
             $date = $year."-".$month;
             $data['date']=date('F Y', strtotime($date));
        } else {
             $date = $year;
             $data['date']=$date;
        }*/
        $po_date = date('Y-m', strtotime($date));
        $data['pr_no1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$date%' GROUP BY po_id") AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);

            foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
                //echo $pr_no."<br>";
                foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                    if($i->item_id!=0){
                        foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                            $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                            $item=$it->item_name." - ".$it->item_specs;
                        }
                    }else {
                        //foreach($this->super_model->select_row_where('aoq_items','pr_details_id',$i->pr_details_id) AS $it){
                            //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                            $item=$i->offer;
                       // }
                    }

               
                        $requestor = $pr->requestor;
                    if($p->cancelled ==1){
                        $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
                    } else {
                       /* $rfd_rows = $this->super_model->count_rows_where("rfd","po_id",$p->po_id);
                        if($rfd_rows==0){
                            $status = 'Pending RFD';
                        } else {
                            $status = 'Fully Served';
                        }*/
                        
                        if($p->served==0){
                            $status = 'PO Issued';
                        } else if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                            $status = 'PO Issued';
                        }else {
                             $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                           // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $dr_no;
                               $status='';
                           foreach($this->super_model->select_custom_where("po_dr_items", "pr_details_id= '$i->pr_details_id' AND po_items_id = '$i->po_items_id' ORDER BY dr_id DESC LIMIT 1") AS $del){
                                 $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."-".COMPANY."<br>";
                            }
                        }
                    }
                    /*$partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$pr->pr_id' AND ai.aoq_items_id = '$i->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");*/
                    $data['po'][]=array(
                        'po_id'=>$i->po_id,
                        'pr_no'=>$pr_no,
                        'enduse'=>$pr->enduse,
                        'purpose'=>$pr->purpose,
                        'requested_by'=>$requestor,
                        'qty'=>$i->quantity,
                        'po_qty'=>$i->delivered_quantity,
                        'uom'=>$i->uom,
                        'item'=>$item,
                        'currency'=>$i->currency,
                        'unit_price'=>$i->unit_price,
                        'notes'=>$pr->notes,
                        'po_id'=>$p->po_id,
                        'po_date'=>$p->po_date,
                        'po_no'=>$p->po_no,
                        'saved'=>$p->saved,
                        'cancelled'=>$p->cancelled,
                        'status'=>$status,
                        'supplier'=>$supplier,
                        'terms'=>$terms,
                        'served'=>$p->served,
                        'cancelled'=>$p->cancelled,
                    );
                }
            }
        }
        $this->load->view('template/header');        
        $this->load->view('reports/po_report',$data);
        $this->load->view('template/footer');
    }

    public function search_po(){
        if(!empty($this->input->post('year'))){
            $data['year'] = $this->input->post('year');
            $year = $this->input->post('year');
        } else {
            $data['year']= "null";
            $year= "null";
        }

        if(!empty($this->input->post('month'))){
            $data['month'] = $this->input->post('month');
            $month = $this->input->post('month');
        } else {
            $data['month']= "null";
            $month= "null";
        }

        if(!empty($this->input->post('pr_no'))){
            $data['pr_no'] = $this->input->post('pr_no');
        } else {
            $data['pr_no']= "null";
        }

        if(!empty($this->input->post('date_po'))){
            $data['date_po'] = $this->input->post('date_po');
        } else {
            $data['date_po']= "null";
        }

        if(!empty($this->input->post('po_no'))){
            $data['po_no'] = $this->input->post('po_no');
        } else {
            $data['po_no'] = "null";
        }

        if(!empty($this->input->post('purpose'))){
            $data['purpose'] = $this->input->post('purpose');
        } else {
            $data['purpose'] = "null";
        }

        if(!empty($this->input->post('enduse'))){
            $data['enduse'] = $this->input->post('enduse');
        } else {
            $data['enduse'] = "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
        } else {
            $data['requestor'] = "null";
        } 

        if(!empty($this->input->post('description'))){
            $data['description'] = $this->input->post('description');
        } else {
            $data['description'] = "null";
        } 

        if(!empty($this->input->post('supplier'))){
            $data['supplier'] = $this->input->post('supplier');
        } else {
            $data['supplier'] = "null";
        }

        $sql="";
        $filter = " ";

        if(!empty($this->input->post('pr_no'))){
            $pr_no = $this->input->post('pr_no');
            $sql.=" pp.pr_id = '$pr_no' AND";
            $filter .= "Pr No. - ".$this->super_model->select_column_where('pr_head', 'pr_no', 
                        'pr_id', $pr_no).", ";
        }

        if(!empty($this->input->post('date_po'))){
            $date_po = $this->input->post('date_po');
            $sql.=" ph.po_date LIKE '%$date_po%' AND";
            $filter .= "PO Date. - ".$date_po.", ";
        }

        if(!empty($this->input->post('po_no'))){
            $po_no = $this->input->post('po_no');
            $sql.=" ph.po_no LIKE '%$po_no%' AND";
            $filter .= "PO No. - ".$po_no.", ";
        }

        if(!empty($this->input->post('purpose'))){
            $purpose = $this->input->post('purpose');
            $sql.=" pp.purpose LIKE '%$purpose%' AND";
            $filter .= "Purpose - ".$purpose.", ";
        }

        if(!empty($this->input->post('enduse'))){
            $enduse = $this->input->post('enduse');
            $sql.=" pp.enduse LIKE '%$enduse%' AND";
            $filter .= "Enduse - ".$enduse.", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            foreach($this->super_model->select_custom_where('employees', "employee_name LIKE '%$requestor%'") AS $r){
                $empid = $r->employee_id;
                $sql.=" pp.requestor = '$empid' OR";
            }
            $filter .= "Requestor - ".$requestor.", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" pp.requestor = '$requestor' AND";
            //$filter .= "Requestor - ".$requestor.", ";
        }

        /*if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" pp.requestor = '$requestor' AND";
            $filter .= "Requestor - ".$requestor.", ";
        }*/

        if(!empty($this->input->post('description'))){
            $description = $this->input->post('description');
            foreach($this->super_model->select_custom_where('item', "item_name LIKE '%$description%'") AS $t){
                $it = $t->item_id;
                $sql.=" pi.item_id = '$it' OR";
            }
            $filter .= "Item Description - ".$description.", ";
        }

        if(!empty($this->input->post('description'))){
            $description = $this->input->post('description');
            foreach($this->super_model->select_custom_where('aoq_items', "item_description LIKE '%$description%'") AS $t){
                $it = $t->pr_details_id;
                $sql.=" pi.pr_details_id = '$it' OR";
            }
        }

        /*if(!empty($this->input->post('description'))){
            $description = $this->input->post('description');
            $sql.=" pi.aoq_items_id = '$description' AND";
            $filter .= "Item Description - ".$this->super_model->select_column_where('item', 'item_name', 
                        'item_id', $description).", ";
        }*/

        if(!empty($this->input->post('supplier'))){
            $supplier = $this->input->post('supplier');
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= "Supplier - ".$this->super_model->select_column_where('vendor_head', 'vendor_name', 
                        'vendor_id', $supplier).", ";
        }

        $query=substr($sql, 0, -3);
        $data['filt']=substr($filter, 0, -2);
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $data['year']=$year;
        $data['month']=$month;
        if($month!='null'){
             $date = $year."-".$month;
             $data['date']=date('F Y', strtotime($date));
        } else {
             $date = $year;
             $data['date']=$date;
        }
        /*$date = $year."-".$month;
        $data['date']=date('F Y', strtotime($date));*/
        $po_date = date('Y-m', strtotime($date));
        $data['pr_no1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->custom_query("SELECT * FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id INNER JOIN po_pr pp ON pi.po_id = pp.po_id WHERE ".$query) AS $p){
            $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id);
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
            if($p->item_id!=0){
                foreach($this->super_model->select_row_where('item','item_id',$p->item_id) AS $it){
                    $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                    $item=$it->item_name." - ".$it->item_specs;
                }
            }else {
                //foreach($this->super_model->select_row_where('aoq_items','pr_details_id',$p->pr_details_id) AS $it){
                    //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                    $item=$p->offer;
               // }
            }

       
            $requestor = $p->requestor;
            if($p->cancelled ==1){
                $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
            } else {
               /* $rfd_rows = $this->super_model->count_rows_where("rfd","po_id",$p->po_id);
                if($rfd_rows==0){
                    $status = 'Pending RFD';
                } else {
                    $status = 'Fully Served';
                }*/
                
                if($p->served==0){
                    $status = 'PO Issued';
                } else {
                     $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                   // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $dr_no;
                       $status='';
                   foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $p->pr_details_id) AS $del){
                         $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."-".COMPANY."<br>";
                    }
                }
            }
            $data['po'][]=array(
                'po_id'=>$p->po_id,
                'pr_no'=>$pr_no,
                'enduse'=>$p->enduse,
                'purpose'=>$p->purpose,
                'requested_by'=>$requestor,
                'po_qty'=>$p->delivered_quantity,
                'qty'=>$p->quantity,
                'po_qty'=>$p->delivered_quantity,
                'uom'=>$p->uom,
                'item'=>$item,
                'unit_price'=>$p->unit_price,
                'currency'=>$p->currency,
                'notes'=>$p->notes,
                'po_id'=>$p->po_id,
                'po_date'=>$p->po_date,
                'po_no'=>$p->po_no,
                'saved'=>$p->saved,
                'cancelled'=>$p->cancelled,
                'status'=>$status,
                'supplier'=>$supplier,
                'terms'=>$terms,
                'served'=>$p->served,
                'cancelled'=>$p->cancelled,
            );
        }
        $this->load->view('template/header');        
        $this->load->view('reports/po_report',$data);
        $this->load->view('template/footer');
    }

    public function export_po(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="PO Summary.xlsx";
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $pr_no1=$this->uri->segment(5);
        $date_po=$this->uri->segment(6);
        $po_no=$this->uri->segment(7);
        $purpose1=str_replace("%20", " ", $this->uri->segment(8));
        $enduse1=str_replace("%20", " ", $this->uri->segment(9));
        $requestor=str_replace("%20", " ", $this->uri->segment(10));
        $description=str_replace("%20", " ", $this->uri->segment(11));
        $supplier=$this->uri->segment(12);

        $sql="";
        $filter = "";

        if($pr_no1!='null'){
            $sql.=" pp.pr_id = '$pr_no1' AND";
            $filter .= $this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $pr_no1);
        }

        if($date_po!='null'){
            $sql.=" ph.po_date LIKE '%$date_po%' AND";
            $filter .= $date_po;
        }

        if($po_no!='null'){
            $sql.=" ph.po_no LIKE '%$po_no%' AND";
            $filter .= $po_no;
        }

        if($purpose1!='null'){
            $sql.=" pp.purpose LIKE '%$purpose1%' AND";
            $filter .= $purpose1;
        }

        if($enduse1!='null'){
            $sql.=" pp.enduse LIKE '%$enduse1%' AND";
            $filter .= $enduse1;
        }

        if($requestor!='null'){
            foreach($this->super_model->select_custom_where('employees', "employee_name LIKE '%$requestor%'") AS $r){
                $empid = $r->employee_id;
                $sql.=" pp.requestor = '$empid' OR";
            }
            $filter .= $requestor;
        }

        if($requestor!='null'){
            $sql.=" pp.requestor = '$requestor' AND";
            //$filter .= "Requestor - ".$requestor.", ";
        }

        /*if($requestor!='null'){
            $sql.=" pp.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }*/

        if($description!='null'){
            foreach($this->super_model->select_custom_where('item', "item_name LIKE '%$description%'") AS $t){
                $it = $t->item_id;
                $sql.=" pi.item_id = '$it' OR";
            }
            $filter .= $description;
        }

        if($description!='null'){
            foreach($this->super_model->select_custom_where('aoq_items', "item_description LIKE '%$description%'") AS $t){
                $it = $t->pr_details_id;
                $sql.=" pi.pr_details_id = '$it' OR";
            }
        }

        /*if($description!='null'){
            $sql.=" pi.aoq_items_id = '$description' AND";
            $filter .= $this->super_model->select_column_where('item', 'item_name', 'item_id', $description);
        }*/

        if($supplier!='null'){
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);

        if($month!='null'){
             $date = $year."-".$month;
        } else {
             $date = $year;
        }

        /*$data['year']=$year;
        $data['month']=$month;
        $date = $year."-".$month;
        $monthyear=date('F Y', strtotime($date));*/
        $po_date = date('Y-m', strtotime($date));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "PO Summary $date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "PURCHASE ORDER");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "PR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "Purpose");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Enduse");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "Date of PO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "PO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "Requested By");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "PO Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Received Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Item Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Payment Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P4', "Remarks");
        foreach(range('A','P') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:P4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:P4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:P4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT * FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id INNER JOIN po_pr pp ON pi.po_id = pp.po_id  WHERE ".$query) AS $p){
                /*$terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id);
                foreach($this->super_model->select_row_where('item','item_id',$p->aoq_items_id) AS $it){
                    $item=$it->item_name." - ".$it->item_specs;
                }
                if($p->item_id!=0){
                    foreach($this->super_model->select_row_where('item','item_id',$p->item_id) AS $it){
                        //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                        $item=$it->item_name." - ".$it->item_specs;
                    }
                }else {
                    foreach($this->super_model->select_row_where('aoq_items','pr_details_id',$p->pr_details_id) AS $it){
                        //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                        $item=$it->item_description;
                    }
                }

                if($p->po_type!=0){
                    $requestor = $this->super_model->select_column_where('employees','employee_name','employee_id',$p->requestor);
                }else {
                    $requestor = $p->requestor;
                }*/
                $total=$p->quantity*$p->unit_price;
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id)."-".COMPANY;
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                if($p->item_id!=0){
                    foreach($this->super_model->select_row_where('item','item_id',$p->item_id) AS $it){
                        $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                        $item=$it->item_name." - ".$it->item_specs;
                    }
                }else {
                    //foreach($this->super_model->select_row_where('aoq_items','pr_details_id',$p->pr_details_id) AS $it){
                        //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                        $item=$p->offer;
                   // }
                }

           
                $requestor = $p->requestor;
                if($p->cancelled ==1){
                    $status = "Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason;
                } else {
                   /* $rfd_rows = $this->super_model->count_rows_where("rfd","po_id",$p->po_id);
                    if($rfd_rows==0){
                        $status = 'Pending RFD';
                    } else {
                        $status = 'Fully Served';
                    }*/
                    
                    if($p->served==0){
                        $status = 'PO Issued';
                    } else if($p->served==1 && $p->delivered_quantity>$p->quantity){ 
                        $status = 'PO Issued';
                    }else {
                         $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                       // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $dr_no;
                           $status='';
                       //foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $p->pr_details_id) AS $del){
                        foreach($this->super_model->select_custom_where("po_dr_items", "pr_details_id= '$p->pr_details_id' AND po_items_id = '$p->po_items_id' ORDER BY dr_id DESC LIMIT 1") AS $del){
                             $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."-".COMPANY."\n";
                        }
                    }
                }
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                if($p->served=='1' && $p->delivered_quantity > $p->quantity){ 
                    
                }else if($p->served=='1'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                } else if($p->cancelled=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                }
                //$partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$p->pr_id' AND ai.aoq_items_id = '$p->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
                $po_no = $p->po_no."-".COMPANY;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->po_date");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$po_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->delivered_quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$p->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$item");
                /*if($partial==1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Partially Served");
                }else if($p->saved==1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Fully Served");
                }else if($p->cancelled==1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Cancelled");
                }*/
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$status");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$supplier");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$terms");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$p->currency $p->unit_price");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$total");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$p->notes");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setWrapText(true);
                $num++;
            }
        }else {
            $num = 5;
            /*foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$po_date%'") AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                    $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
                    foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                        foreach($this->super_model->select_row_where('item','item_id',$i->aoq_items_id) AS $it){
                            $item=$it->item_name." - ".$it->item_specs;
                        }
                        if($i->item_id!=0){
                            foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                                //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                                $item=$it->item_name." - ".$it->item_specs;
                            }
                        }else {
                            foreach($this->super_model->select_row_where('aoq_items','pr_details_id',$i->pr_details_id) AS $it){
                                //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                                $item=$it->item_description;
                            }
                        }

                        if($p->po_type!=0){
                            $requestor = $this->super_model->select_column_where('employees','employee_name','employee_id',$pr->requestor);
                        }else {
                            $requestor = $pr->requestor;
                        }*/
                foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$date%' GROUP BY po_id") AS $p){
                    $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                    $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);

                    foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                        $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id)."-".COMPANY;
                        foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                            $total=$i->quantity*$i->unit_price;
                            if($i->item_id!=0){
                                foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                                    $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                                    $item=$it->item_name." - ".$it->item_specs;
                                }
                            }else {
                                //foreach($this->super_model->select_row_where('aoq_items','pr_details_id',$i->pr_details_id) AS $it){
                                    //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                                    $item=$i->offer;
                               // }
                            }

                       
                                $requestor = $pr->requestor;
                            if($p->cancelled ==1){
                                $status = "Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason;
                            } else {
                               /* $rfd_rows = $this->super_model->count_rows_where("rfd","po_id",$p->po_id);
                                if($rfd_rows==0){
                                    $status = 'Pending RFD';
                                } else {
                                    $status = 'Fully Served';
                                }*/
                                
                                if($p->served==0){
                                    $status = 'PO Issued';
                                } else if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                                    $status = 'PO Issued';
                                }else {
                                     $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                                   // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $dr_no;
                                       $status='';
                                   //foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $i->pr_details_id) AS $del){
                                    foreach($this->super_model->select_custom_where("po_dr_items", "pr_details_id= '$i->pr_details_id' AND po_items_id = '$i->po_items_id' ORDER BY dr_id DESC LIMIT 1") AS $del){
                                         $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."\n";
                                    }
                                }
                            }
                        $styleArray = array(
                            'borders' => array(
                                'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        );
                        if($p->served=='1' && $i->delivered_quantity > $i->quantity){ 
                            
                        }else if($p->served=='1'){
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                        } else if($p->cancelled=='1') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                        }
                        //$partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$pr->pr_id' AND ai.aoq_items_id = '$i->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
                        $po_no = $p->po_no."-".COMPANY;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr_no");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$pr->purpose");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->enduse");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->po_date");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$po_no");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$requestor");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$i->delivered_quantity");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$i->quantity");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$i->uom");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$item");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$status");
                        /*if($partial==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Partially Served");
                        }else if($p->saved==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Fully Served");
                        }else if($p->cancelled==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Cancelled");
                        }*/
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$supplier");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$terms");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$i->currency $i->unit_price");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$total");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$pr->notes");
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->applyFromArray($styleArray);
                        $num++;
                    }
                }
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="PO Summary.xlsx"');
        readfile($exportfilename);
    }

    public function add_remarks(){
        $po_offer_id =$this->input->post('po_offer_id');
        $pr_id =$this->input->post('pr_id');
        $pr_details_id =$this->input->post('pr_details_id');
        $status =$this->input->post('status');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $remarks=$this->input->post('remarks');
        $cancel_remarks=$this->input->post('cancel_remarks');
        $cancel=$this->input->post('cancel');
        $remark_date = date('Y-m-d H:i:s');

        /*if($cancel!=0){
            $data=array(
                'add_remarks'=>$remarks,
                'remark_date'=>$remark_date,
                'cancelled'=>$cancel,
                'cancelled_date'=>date('Y-m-d'),
                'cancelled_by'=>$_SESSION['user_id'],
                'remark_by'=>$_SESSION['user_id']
            );
        }else {*/
        $data=array(
            'add_remarks'=>$remarks,
            'remark_date'=>$remark_date,
            'remark_by'=>$_SESSION['user_id']
        );
        $this->super_model->update_where("pr_details", $data, "pr_id", $pr_id);
        $data=array(
            'cancel_remarks'=>$cancel_remarks,
        );
        $this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id);
        //}
        if($status == 'Partially Delivered'){
         
                if($cancel!=0){
                    $po_id = $this->super_model->select_column_where("po_items",'po_id','pr_details_id',$pr_details_id);
                    $aoq_offer = $this->super_model->select_column_where("po_items",'aoq_offer_id','pr_details_id',$pr_details_id);
                    $data_po=array(
                        'cancel'=>$cancel,
                        'cancelled_by'=>$_SESSION['user_id'],
                        'cancelled_date'=>date('Y-m-d'),
                    );
                    $this->super_model->update_custom_where("po_items", $data_po, "pr_id = '$pr_id' AND pr_details_id = '$pr_details_id'");
                }
                redirect(base_url().'reports/pr_report/'.$year.'/'.$month);
            
        } else {
            echo "<script>alert('You can only use this cancel button for partially delivered items.');
            window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
        }
    }

    public function add_sister_company(){
        $po_offer_id =$this->input->post('po_offer_id');
        $pr_id =$this->input->post('pr_id');
        $pr_details_id =$this->input->post('pr_details_id');
        $status =$this->input->post('status');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $date=$this->input->post('date_delivered');
        $supp =$this->input->post('supp');
        $unit_price=$this->input->post('unit_price');
        $qty_delivered=$this->input->post('qty_delivered');
        $comp =$this->input->post('comp');
        

        $data=array(
            'date_delivered'=>$date,
            'vendor_id'=>$supp,
            'unit_price'=>$unit_price,
            'qty_delivered'=>$qty_delivered,
            'fulfilled_by'=>1,
            'company_id'=>$comp,
        );
        $this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id);
    {
            echo "<script>alert('Successfully Added!'); window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
        }
    }
    public function on_recom(){
        $po_offer_id =$this->input->post('po_offer_id');
        $pr_id =$this->input->post('pr_id');
        $pr_details_id =$this->input->post('pr_details_id');
        $status =$this->input->post('status');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $recom_date_from=$this->input->post('recom_date_from');
        $recom_date_to =$this->input->post('recom_date_to');
        $recom_date = date('Y-m-d H:i:s');
        

        $data=array(
            'recom_date_from'=>$recom_date_from,
            'recom_date_to'=>$recom_date_to,
            'for_recom'=>1,
            'recom_date'=>$recom_date,
            'recom_by'=>$_SESSION['user_id']


        );
        $this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id);
    {
            echo "<script>alert('Successfully Added!'); window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
        }
    }

    public function unserved_report(){
        $year1=$this->uri->segment(3);
        $month1=$this->uri->segment(4);
        if(!empty($year1)){
            $data['year'] = $year1;
            $year = $year1;
        } else {
            $data['year']= "null";
            $year= "null";
        }

        if(!empty($month1)){
            $data['month'] = $month1;
            $month = $month1;
        } else {
            $data['month']= "null";
            $month= "null";
        }
       
        if($month!='null'){
             $date = $year."-".$month;
             $data['date']=date('F Y', strtotime($date));
        } else {
             $date = $year;
             $data['date']=$date;
        }
        $po_date = date('Y-m', strtotime($date));
        $data['pr_no1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$date%' GROUP BY po_id") AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
            foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
                foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                    $unserved_qty = $i->delivered_quantity - $i->quantity;
                    if($i->item_id!=0){
                        foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                            $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                            $item=$it->item_name." - ".$it->item_specs;
                        }
                    }else {
                        $item=$i->offer;
                    }

                    $requestor = $pr->requestor;
                    if($p->cancelled ==1){
                        $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
                    } else {
                        if($p->served==0){
                            $status = 'PO Issued';
                        } else if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                            $status = 'PO Issued';
                        }else {
                            $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                            $status='';
                            foreach($this->super_model->select_custom_where("po_dr_items", "pr_details_id= '$i->pr_details_id' AND po_items_id = '$i->po_items_id' ORDER BY dr_id DESC LIMIT 1") AS $del){
                                $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                            }
                        }
                    }
                    $total = $unserved_qty * $i->unit_price;
                    if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                        $data['po'][]=array(
                            'po_id'=>$i->po_id,
                            'pr_no'=>$pr_no,
                            'enduse'=>$pr->enduse,
                            'purpose'=>$pr->purpose,
                            'requested_by'=>$requestor,
                            'qty'=>$i->quantity,
                            'po_qty'=>$i->delivered_quantity,
                            'uom'=>$i->uom,
                            'unserved_qty'=>$unserved_qty,
                            'item'=>$item,
                            'currency'=>$i->currency,
                            'unit_price'=>$i->unit_price,
                            'notes'=>$pr->notes,
                            'po_id'=>$p->po_id,
                            'po_date'=>$p->po_date,
                            'po_no'=>$p->po_no,
                            'saved'=>$p->saved,
                            'cancelled'=>$p->cancelled,
                            'status'=>$status,
                            'supplier'=>$supplier,
                            'terms'=>$terms,
                            'served'=>$p->served,
                            'cancelled'=>$p->cancelled,
                            'total'=>$total,
                        );
                    }
                }
            }
        }
        $this->load->view('template/header');        
        $this->load->view('reports/unserved_report',$data);
        $this->load->view('template/footer');
    } 

    public function search_unserved(){
        if(!empty($this->input->post('year'))){
            $data['year'] = $this->input->post('year');
            $year = $this->input->post('year');
        } else {
            $data['year']= "null";
            $year= "null";
        }

        if(!empty($this->input->post('month'))){
            $data['month'] = $this->input->post('month');
            $month = $this->input->post('month');
        } else {
            $data['month']= "null";
            $month= "null";
        }

        if(!empty($this->input->post('pr_no'))){
            $data['pr_no'] = $this->input->post('pr_no');
        } else {
            $data['pr_no']= "null";
        }

        if(!empty($this->input->post('date_po'))){
            $data['date_po'] = $this->input->post('date_po');
        } else {
            $data['date_po']= "null";
        }

        if(!empty($this->input->post('po_no'))){
            $data['po_no'] = $this->input->post('po_no');
        } else {
            $data['po_no'] = "null";
        }

        if(!empty($this->input->post('purpose'))){
            $data['purpose'] = $this->input->post('purpose');
        } else {
            $data['purpose'] = "null";
        }

        if(!empty($this->input->post('enduse'))){
            $data['enduse'] = $this->input->post('enduse');
        } else {
            $data['enduse'] = "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
        } else {
            $data['requestor'] = "null";
        } 

        if(!empty($this->input->post('description'))){
            $data['description'] = $this->input->post('description');
        } else {
            $data['description'] = "null";
        } 

        if(!empty($this->input->post('supplier'))){
            $data['supplier'] = $this->input->post('supplier');
        } else {
            $data['supplier'] = "null";
        }

        $sql="";
        $filter = " ";

        if(!empty($this->input->post('pr_no'))){
            $pr_no = $this->input->post('pr_no');
            $sql.=" pp.pr_id = '$pr_no' AND";
            $filter .= "Pr No. - ".$this->super_model->select_column_where('pr_head', 'pr_no', 
                        'pr_id', $pr_no).", ";
        }

        if(!empty($this->input->post('date_po'))){
            $date_po = $this->input->post('date_po');
            $sql.=" ph.po_date LIKE '%$date_po%' AND";
            $filter .= "PO Date. - ".$date_po.", ";
        }

        if(!empty($this->input->post('po_no'))){
            $po_no = $this->input->post('po_no');
            $sql.=" ph.po_no LIKE '%$po_no%' AND";
            $filter .= "PO No. - ".$po_no.", ";
        }

        if(!empty($this->input->post('purpose'))){
            $purpose = $this->input->post('purpose');
            $sql.=" pp.purpose LIKE '%$purpose%' AND";
            $filter .= "Purpose - ".$purpose.", ";
        }

        if(!empty($this->input->post('enduse'))){
            $enduse = $this->input->post('enduse');
            $sql.=" pp.enduse LIKE '%$enduse%' AND";
            $filter .= "Enduse - ".$enduse.", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            foreach($this->super_model->select_custom_where('employees', "employee_name LIKE '%$requestor%'") AS $r){
                $empid = $r->employee_id;
                $sql.=" pp.requestor = '$empid' OR";
            }
            $filter .= "Requestor - ".$requestor.", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" pp.requestor = '$requestor' AND";
            //$filter .= "Requestor - ".$requestor.", ";
        }

        /*if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" pp.requestor = '$requestor' AND";
            $filter .= "Requestor - ".$requestor.", ";
        }*/

        if(!empty($this->input->post('description'))){
            $description = $this->input->post('description');
            foreach($this->super_model->select_custom_where('item', "item_name LIKE '%$description%'") AS $t){
                $it = $t->item_id;
                $sql.=" pi.item_id = '$it' OR";
            }
            $filter .= "Item Description - ".$description.", ";
        }

        if(!empty($this->input->post('description'))){
            $description = $this->input->post('description');
            foreach($this->super_model->select_custom_where('aoq_items', "item_description LIKE '%$description%'") AS $t){
                $it = $t->pr_details_id;
                $sql.=" pi.pr_details_id = '$it' OR";
            }
        }

        /*if(!empty($this->input->post('description'))){
            $description = $this->input->post('description');
            $sql.=" pi.aoq_items_id = '$description' AND";
            $filter .= "Item Description - ".$this->super_model->select_column_where('item', 'item_name', 
                        'item_id', $description).", ";
        }*/

        if(!empty($this->input->post('supplier'))){
            $supplier = $this->input->post('supplier');
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= "Supplier - ".$this->super_model->select_column_where('vendor_head', 'vendor_name', 
                        'vendor_id', $supplier).", ";
        }

        $query=substr($sql, 0, -3);
        $data['filt']=substr($filter, 0, -2);
        if($month!='null'){
             $date = $year."-".$month;
             $data['date']=date('F Y', strtotime($date));
        } else {
             $date = $year;
             $data['date']=$date;
        }
        /*$date = $year."-".$month;
        $data['date']=date('F Y', strtotime($date));*/
        $po_date = date('Y-m', strtotime($date));
        $data['pr_no1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->custom_query("SELECT * FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id INNER JOIN po_pr pp ON pi.po_id = pp.po_id WHERE ".$query) AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
            $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id);
            $unserved_qty = $p->delivered_quantity - $p->quantity;
            if($p->item_id!=0){
                foreach($this->super_model->select_row_where('item','item_id',$p->item_id) AS $it){
                    $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                    $item=$it->item_name." - ".$it->item_specs;
                }
            }else {
                $item=$p->offer;
            }
            $requestor = $p->requestor;
            if($p->cancelled ==1){
                $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
            } else {
                if($p->served==0){
                    $status = 'PO Issued';
                } else if($p->served==1 && $p->delivered_quantity>$p->quantity){ 
                    $status = 'PO Issued';
                }else {
                    $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                    $status='';
                    foreach($this->super_model->select_custom_where("po_dr_items", "pr_details_id= '$p->pr_details_id' AND po_items_id = '$p->po_items_id' ORDER BY dr_id DESC LIMIT 1") AS $del){
                        $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                    }
                }
            }
            $total = $unserved_qty * $p->unit_price;
            if($p->served==1 && $p->delivered_quantity>$p->quantity){ 
                $data['po'][]=array(
                    'po_id'=>$p->po_id,
                    'pr_no'=>$pr_no,
                    'enduse'=>$p->enduse,
                    'purpose'=>$p->purpose,
                    'requested_by'=>$requestor,
                    'qty'=>$p->quantity,
                    'po_qty'=>$p->delivered_quantity,
                    'uom'=>$p->uom,
                    'unserved_qty'=>$unserved_qty,
                    'item'=>$item,
                    'currency'=>$p->currency,
                    'unit_price'=>$p->unit_price,
                    'notes'=>$p->notes,
                    'po_id'=>$p->po_id,
                    'po_date'=>$p->po_date,
                    'po_no'=>$p->po_no,
                    'saved'=>$p->saved,
                    'cancelled'=>$p->cancelled,
                    'status'=>$status,
                    'supplier'=>$supplier,
                    'terms'=>$terms,
                    'served'=>$p->served,
                    'cancelled'=>$p->cancelled,
                    'total'=>$total,
                );
            }
        }
        $this->load->view('template/header');        
        $this->load->view('reports/unserved_report',$data);
        $this->load->view('template/footer');
    }

    public function export_unserved(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Unserved Report.xlsx";
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $pr_no1=$this->uri->segment(5);
        $date_po=$this->uri->segment(6);
        $po_no=$this->uri->segment(7);
        $purpose1=$this->uri->segment(8);
        $enduse1=$this->uri->segment(9);
        $requestor=$this->uri->segment(10);
        $description=$this->uri->segment(11);
        $supplier=$this->uri->segment(12);

        $sql="";
        $filter = "";

        if($pr_no1!='null'){
            $sql.=" pp.pr_id = '$pr_no1' AND";
            $filter .= $this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $pr_no1);
        }

        if($date_po!='null'){
            $sql.=" ph.po_date LIKE '%$date_po%' AND";
            $filter .= $date_po;
        }

        if($po_no!='null'){
            $sql.=" ph.po_no LIKE '%$po_no%' AND";
            $filter .= $po_no;
        }

        if($purpose1!='null'){
            $sql.=" pp.purpose LIKE '%$purpose1%' AND";
            $filter .= $purpose1;
        }

        if($enduse1!='null'){
            $sql.=" pp.enduse LIKE '%$enduse1%' AND";
            $filter .= $enduse1;
        }

        if($requestor!='null'){
            foreach($this->super_model->select_custom_where('employees', "employee_name LIKE '%$requestor%'") AS $r){
                $empid = $r->employee_id;
                $sql.=" pp.requestor = '$empid' OR";
            }
            $filter .= $requestor;
        }

        if($requestor!='null'){
            $sql.=" pp.requestor = '$requestor' AND";
            $filter .= $requestor;
        }

        /*if($requestor!='null'){
            $sql.=" pp.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }*/

        if($description!='null'){
            foreach($this->super_model->select_custom_where('item', "item_name LIKE '%$description%'") AS $t){
                $it = $t->item_id;
                $sql.=" pi.item_id = '$it' OR";
            }
            $filter .= $description;
        }

        if($description!='null'){
            foreach($this->super_model->select_custom_where('aoq_items', "item_description LIKE '%$description%'") AS $t){
                $it = $t->pr_details_id;
                $sql.=" pi.pr_details_id = '$it' OR";
            }
        }

        /*if($description!='null'){
            $sql.=" pi.aoq_items_id = '$description' AND";
            $filter .= $this->super_model->select_column_where('item', 'item_name', 'item_id', $description);
        }*/

        if($supplier!='null'){
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);

        //echo $query;

        /*$data['year']=$year;
        $data['month']=$month;*/
        if($month!='null'){
             $date = $year."-".$month;
        } else {
             $date = $year;
        }
        /*$date = $year."-".$month;
        $monthyear=date('F Y', strtotime($date));*/
        $po_date = date('Y-m', strtotime($date));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Undelivered PO Report $date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "UNDELIVERED PO Report");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "PR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "Purpose");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Enduse");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "Date of PO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "PO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "Requested By");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "Item Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Payment Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Remarks");
        foreach(range('A','O') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT * FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id INNER JOIN po_pr pp ON pi.po_id = pp.po_id  WHERE ".$query) AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id);
                $unserved_qty = $p->delivered_quantity - $p->quantity;
                if($p->item_id!=0){
                    foreach($this->super_model->select_row_where('item','item_id',$p->item_id) AS $it){
                        $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                        $item=$it->item_name." - ".$it->item_specs;
                    }
                }else {
                    $item=$p->offer;
                }
                $requestor = $p->requestor;
                if($p->cancelled ==1){
                    $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
                } else {
                    if($p->served==0){
                        $status = 'PO Issued';
                    } else if($p->served==1 && $p->delivered_quantity>$p->quantity){ 
                        $status = 'PO Issued';
                    }else {
                        $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                        $status='';
                        foreach($this->super_model->select_custom_where("po_dr_items", "pr_details_id= '$p->pr_details_id' AND po_items_id = '$p->po_items_id' ORDER BY dr_id DESC LIMIT 1") AS $del){
                            $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                        }
                    }
                }
                $total = $unserved_qty * $p->unit_price;
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                if($p->served==1 && $p->delivered_quantity>$p->quantity){ 
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr_no");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->purpose");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->enduse");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->po_date");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->po_no");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$requestor");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$unserved_qty");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->uom");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$item");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$status");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$supplier");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$terms");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$p->unit_price");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$total");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$p->notes");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":O".$num)->applyFromArray($styleArray);
                    $num++;   
                }
            }
                
        }else {
            $num = 5;
            foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$date%' GROUP BY po_id") AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                    $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
                    foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                        $unserved_qty = $i->delivered_quantity - $i->quantity;
                        if($i->item_id!=0){
                            foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                                $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                                $item=$it->item_name." - ".$it->item_specs;
                            }
                        }else {
                            $item=$i->offer;
                        }

                        $requestor = $pr->requestor;
                        if($p->cancelled ==1){
                            $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
                        } else {
                            if($p->served==0){
                                $status = 'PO Issued';
                            } else if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                                $status = 'PO Issued';
                            }else {
                                $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                                $status='';
                                foreach($this->super_model->select_custom_where("po_dr_items", "pr_details_id= '$i->pr_details_id' AND po_items_id = '$i->po_items_id' ORDER BY dr_id DESC LIMIT 1") AS $del){
                                    $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                                }
                            }
                        }
                        $total = $unserved_qty * $i->unit_price;
                        $styleArray = array(
                            'borders' => array(
                                'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        );
                        if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr_no");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$pr->purpose");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->enduse");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->po_date");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->po_no");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$requestor");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$unserved_qty");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$i->uom");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$item");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$status");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$supplier");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$terms");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$i->unit_price");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$total");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$pr->notes");
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":O".$num)->applyFromArray($styleArray);
                            $num++;
                        }
                    }
                }
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Unserved Report.xlsx"');
        readfile($exportfilename);
    }

    public function export_pr_summary(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Unserved Report.xlsx";
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        if($month!='null'){
             $date = $year."-".$month;
        } else {
             $date = $year;
        }
        $date_received=$this->uri->segment(5);
        $purpose=str_replace("%20", " ", $this->uri->segment(6));
        $enduse=str_replace("%20", " ", $this->uri->segment(7));
        $pr_no=$this->uri->segment(8);
        $requestor=str_replace("%20", " ", $this->uri->segment(9));
        $description=str_replace("%20", " ", $this->uri->segment(10));
        $purchase_request=$this->uri->segment(11);

        $sql="";
        $filter = " ";

        if($date_received!='null'){
            $sql.=" ph.date_prepared LIKE '%$date_received%' AND";
            $filter .= $date_received;
        }

        if($purchase_request!='null'){
            $sql.=" ph.purchase_request LIKE '%$purchase_request%' AND";
            $filter .= $purchase_request;
        }

        if($purpose!='null'){
            $sql.=" ph.purpose LIKE '%$purpose%' AND";
            $filter .= $purpose;
        }

        if($enduse!='null'){
            $sql.=" ph.enduse LIKE '%$enduse%' AND";
            $filter .= $enduse;
        }

        if($pr_no!='null'){
            $sql.=" ph.pr_no LIKE '%$pr_no%' AND";
            $filter .= $pr_no;
        }

        if($requestor!='null'){
            $sql.=" ph.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }

        if($description!='null'){
            $sql.=" pd.item_description LIKE '%$description%' AND";
            $filter .= $description;
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "STA. ISABEL CPGC POWER CORPORATION NPC,");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "COMPOUND STA. ISABEL CALAPAN CITY");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "MONITORING OF PR $date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "Date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "PR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Item No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "Part No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Date Needed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "PO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Name of Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Date of PO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Amount");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "MMR #");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P4', "Assigned Engine");
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        foreach(range('A','P') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        if($filt!=''){
            $num=5;
            foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_head ph INNER JOIN pr_details pd ON ph.pr_id = pd.pr_id WHERE ".$query) AS $pr){
            //foreach($this->super_model->select_all_order_by("pr_head","pr_no","ASC") AS $pr){
                //foreach($this->super_model->select_row_where("pr_details","pr_id",$pr->pr_id) AS $pd){
                $qty='';
                $unit_price='';
                $po_no='';
                $vendor='';
                $po_date='';
                foreach($this->super_model->select_row_where("po_items","pr_details_id",$pr->pr_details_id) AS $po){
                    $po_no.=$this->super_model->select_column_where("po_head","po_no","po_id",$po->po_id);
                    $vendor_id=$this->super_model->select_column_where("po_head","vendor_id","po_id",$po->po_id);
                    $vendor.=$this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$vendor_id);
                    $po_date.=$this->super_model->select_column_where("po_head","po_date","po_id",$po->po_id);
                    $qty.=$po->quantity;
                    $unit_price.=$po->unit_price;
                }
                $aoq_id=$this->super_model->select_column_where("aoq_offers","aoq_id","pr_details_id",$pr->pr_details_id);
                $terms=$this->super_model->select_column_where("aoq_vendors","payment_terms","aoq_id",$aoq_id);
                $amount= $qty*$unit_price;
                if($pr->item_no==1){
                    $phpColor = new PHPExcel_Style_Color();
                    $phpColor->setRGB('853998'); 
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getFont()->setColor($phpColor);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $pr->purpose);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getFont()->setItalic(true);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getFont()->setBold(true);
                    $num--;
                    $num++;
                    $num++;
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $pr->date_prepared);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $pr->pr_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $pr->item_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $pr->item_description);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $pr->part_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, (empty($po_id)) ? $pr->quantity : $qty);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $pr->uom);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $pr->date_needed);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, ($pr->cancelled==0) ? $po_no : "Cancelled");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $vendor);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $po_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $amount);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $terms);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $num++;
            }
        }else {
            $num=5;
            foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_head ph INNER JOIN pr_details pd ON ph.pr_id = pd.pr_id ORDER BY ph.pr_no ASC") AS $pr){
            //foreach($this->super_model->select_all_order_by("pr_head","pr_no","ASC") AS $pr){
                //foreach($this->super_model->select_row_where("pr_details","pr_id",$pr->pr_id) AS $pd){
                $qty='';
                $unit_price='';
                $po_no='';
                $vendor='';
                $po_date='';
                foreach($this->super_model->select_row_where("po_items","pr_details_id",$pr->pr_details_id) AS $po){
                    $po_no.=$this->super_model->select_column_where("po_head","po_no","po_id",$po->po_id);
                    $vendor_id=$this->super_model->select_column_where("po_head","vendor_id","po_id",$po->po_id);
                    $vendor.=$this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$vendor_id);
                    $po_date.=$this->super_model->select_column_where("po_head","po_date","po_id",$po->po_id);
                    $qty.=$po->quantity;
                    $unit_price.=$po->unit_price;
                }
                $aoq_id=$this->super_model->select_column_where("aoq_offers","aoq_id","pr_details_id",$pr->pr_details_id);
                $terms=$this->super_model->select_column_where("aoq_vendors","payment_terms","aoq_id",$aoq_id);
                $amount= $qty*$unit_price;
                if($pr->item_no==1){
                    $phpColor = new PHPExcel_Style_Color();
                    $phpColor->setRGB('853998'); 
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getFont()->setColor($phpColor);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $pr->purpose);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getFont()->setItalic(true);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getFont()->setBold(true);
                    $num--;
                    $num++;
                    $num++;
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $pr->date_prepared);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $pr->pr_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $pr->item_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $pr->item_description);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $pr->part_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, (empty($po_id)) ? $pr->quantity : $qty);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $pr->uom);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $pr->date_needed);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, ($pr->cancelled==0) ? $po_no : "Cancelled");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $vendor);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $po_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $amount);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $terms);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $num++;
            }
        }
        $phpColor1 = new PHPExcel_Style_Color();
        $phpColor1->setRGB('00C851'); 
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setColor($phpColor1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setColor($phpColor1);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setColor($phpColor1);
        $objPHPExcel->getActiveSheet()->getStyle('A4:P4')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true)->setName('Arial Narrow')->setSize(22);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setBold(true)->setName('Arial Narrow')->setSize(22);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true)->setName('Arial Narrow')->setSize(22);
        $objPHPExcel->getActiveSheet()->getStyle('A4:P4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:P4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="PR SUMMARY Report.xlsx"');
        readfile($exportfilename);
    }

    public function sum_weekly_recom(){
        $date_from=$this->uri->segment(3);
        $date_to=$this->uri->segment(4);  
        $data['recom_date_from']=$date_from;
        $data['recom_date_to']=$date_to;
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $count_sum_weekly_recom = $this->super_model->count_custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$date_from' AND '$date_to' AND pd.recom_date_to BETWEEN '$date_from' AND '$date_to' AND pd.for_recom='1'");
        if($count_sum_weekly_recom!=0){
            foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$date_from' AND '$date_to' AND pd.recom_date_to BETWEEN '$date_from' AND '$date_to' AND pd.for_recom='1'") AS $p){
                $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $unit_price;
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $p->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $p->pr_details_id);
                if($po_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $p->pr_details_id, "po_id", "DESC", "1");
                $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                if($served==0 && $cancelled_items_po==0){
                    $data['weekly_recom'][]=array(
                        'enduse'=>$p->enduse,
                        'requestor'=>$p->requestor,
                        'quantity'=>$p->quantity,
                        'uom'=>$p->uom,
                        'item_description'=>$p->item_description,
                        'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id),
                        'pr_no'=>$p->pr_no,
                        'terms'=>$this->super_model->select_column_custom_where('aoq_vendors','payment_terms',"vendor_id='$p->vendor_id' AND aoq_id='$aoq_id'"),
                        'unit_price'=>$unit_price,
                        'total'=>$total
                    );
                }
            }
        }else{
            $data['weekly_recom']=array();
        }
        $this->load->view('template/header');  
        $this->load->view('reports/sum_weekly_recom',$data);
        $this->load->view('template/footer');
    }

    public function search_weekly_recom(){
        if(!empty($this->input->post('recom_date_from'))){
            $data['recom_date_from'] = $this->input->post('recom_date_from');
            $recom_date_from = $this->input->post('recom_date_from');
        } else {
            $data['recom_date_from']= "null";
            $recom_date_from= "null";
        }

        if(!empty($this->input->post('recom_date_to'))){
            $data['recom_date_to'] = $this->input->post('recom_date_to');
            $recom_date_to = $this->input->post('recom_date_to');
        } else {
            $data['recom_date_to']= "null";
            $recom_date_to= "null";
        }

        if(!empty($this->input->post('enduse'))){
            $data['enduse'] = $this->input->post('enduse');
            $enduse = $this->input->post('enduse');
        } else {
            $data['enduse']= "null";
            $enduse= "null";
        }

        if(!empty($this->input->post('purpose'))){
            $data['purpose'] = $this->input->post('purpose');
            $purpose = $this->input->post('purpose');
        } else {
            $data['purpose']= "null";
            $purpose= "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
            $requestor = $this->input->post('requestor');
        } else {
            $data['requestor']= "null";
            $requestor= "null";
        }

        if(!empty($this->input->post('uom'))){
            $data['uom'] = $this->input->post('uom');
            $uom = $this->input->post('uom');
        } else {
            $data['uom']= "null";
            $uom= "null";
        }

        if(!empty($this->input->post('description'))){
            $data['description'] = $this->input->post('description');
            $description = $this->input->post('description');
        } else {
            $data['description'] = "null";
            $description = "null";
        }

        if(!empty($this->input->post('supplier'))){
            $data['supplier'] = $this->input->post('supplier');
            $supplier = $this->input->post('supplier');
        } else {
            $data['supplier'] = "null";
            $supplier = "null";
        }

        if(!empty($this->input->post('pr_no'))){
            $data['pr_no'] = $this->input->post('pr_no');
            $pr_no = $this->input->post('pr_no');
        } else {
            $data['pr_no'] = "null";
            $pr_no = "null";
        } 

        $sql="";
        $filter = "";

        if($recom_date_from!='null'){
            $sql.=" pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_from.", ";
        }

        if($recom_date_to!='null'){
            $sql.=" pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_to.", ";
        }

        if($enduse!='null'){
            $sql.=" ph.enduse LIKE '%$enduse%' AND";
            $filter .= $enduse.", ";
        }

        if($purpose!='null'){
            $sql.=" ph.purpose LIKE '%$purpose%' AND";
            $filter .= $purpose.", ";
        }

        if($requestor!='null'){
            $sql.=" ph.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor.", ";
        }

        if($uom!='null'){
            $sql.=" pd.uom LIKE '%$uom%' AND";
            $filter .= $uom.", ";
        }

        if($description!='null'){
                $sql.=" pd.item_description LIKE '%$description%' AND";
            $filter .= $description.", ";
        }


        if($supplier!='null'){
            $sql.=" pd.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$supplier).", ";
        }
        
        if($pr_no!='null'){
            $sql.=" ph.pr_no LIKE '%$pr_no%' AND";
            $filter .= $pr_no.", ";
        }

        /*if($supplier!='null'){
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }*/

        /*if($terms!='null'){
            $sql.=" pp.terms = '$terms' AND";
            $filter .= $terms;
        }*/

       /* if($company!='null'){
            $sql.=" pd.company_id = '$company' AND";
            $filter .= $this->super_model->select_column_where('company', 'company_name', 'company_id', $company);
        }*/

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        $data['filt']=$filt;
        $date = $recom_date_from."-".$recom_date_to;
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        $count_search_weekly = $this->super_model->count_custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE $query AND pd.for_recom='1'");
        if($count_search_weekly!=0){
        foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE $query AND pd.for_recom='1'") AS $p){
                $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $unit_price;
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $p->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $p->pr_details_id);
                if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $p->pr_details_id, "po_id", "DESC", "1");
                $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                if($served==0 && $cancelled_items_po==0){
                $data['weekly_recom'][]=array(
                    'enduse'=>$p->enduse,
                    'requestor'=>$p->requestor,
                    'quantity'=>$p->quantity,
                    'uom'=>$p->uom,
                    'item_description'=>$p->item_description,
                    'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id),
                    'pr_no'=>$p->pr_no,
                    'terms'=>$this->super_model->select_column_custom_where('aoq_vendors','payment_terms',"vendor_id='$p->vendor_id' AND aoq_id='$aoq_id'"),
                    'unit_price'=>$p->unit_price,
                    'total'=>$total
                );
            }
        }
        }else{
            $data['weekly_recom']=array();
        }
        $this->load->view('template/header');        
        $this->load->view('reports/sum_weekly_recom',$data);
        $this->load->view('template/footer');
    }

    public function export_weekly_recom(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Summary of Weekly Recommendation.xlsx";
        $recom_date_from=$this->uri->segment(3);
        $recom_date_to=$this->uri->segment(4);
        $enduse=str_replace("%20", " ", $this->uri->segment(5));
        $purpose=str_replace("%20", " ", $this->uri->segment(6));
        $requestor=str_replace("%20", " ", $this->uri->segment(7));
        $uom=$this->uri->segment(8);
        $description=str_replace("%20", " ", $this->uri->segment(9));
        $supplier=$this->uri->segment(10);
        $pr_no=str_replace("%20", " ", $this->uri->segment(11));

        $sql="";
        $filter = "";

        /*if($recom_date_from!='null'){
            $sql.=" pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_from;
        }

        if($recom_date_to!='null'){
            $sql.=" pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_to;
        }*/

        if($enduse!='null'){
            $sql.=" ph.enduse LIKE '%$enduse%' AND";
            $filter .= $enduse;
        }

        if($purpose!='null'){
            $sql.=" ph.purpose LIKE '%$purpose%' AND";
            $filter .= $purpose;
        }

        if($requestor!='null'){
            $sql.=" ph.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }

        if($uom!='null'){
            $sql.=" pd.uom LIKE '%$uom%' AND";
            $filter .= $uom;
        }

        if($description!='null'){
                $sql.=" pd.item_description LIKE '%$description%' AND";
            $filter .= $description;
        }

        /*if($supplier!='null'){
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }*/

        if($pr_no!='null'){
            $sql.=" ph.pr_no = '$pr_no' AND";
            $filter .= $pr_no;
        }

        /*if($terms!='null'){
            $sql.=" pp.terms = '$terms' AND";
            $filter .= $terms;
        }*/

        /*if($company!='null'){
            $sql.=" pd.company_id = '$company' AND";
            $filter .= $this->super_model->select_column_where('company', 'company_name', 'company_id', $company);
        }*/

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        $date = $recom_date_from." - ".$recom_date_to;
        //$date = date('Y-m', strtotime($date));
        /*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "$company");*/
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "MATERIALS RECOMMENDATION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "$date");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "End-use");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "Requested by:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "QTY as per PR");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "UoM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "Site PR/JO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', "Delivery Lead Time / Work Duration");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "UNIT PRICE (PESO)");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J5', "TOTAL PESO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "15 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L5', "30 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M5', "60 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N5', "TERMS");
        foreach(range('A','N') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A5:N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:N5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:N5')->applyFromArray($styleArray1);
        if($filt!=''){
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $num = 6;
            foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND $query AND pd.for_recom='1'") AS $p){
                $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $unit_price;
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);

                 
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$p->enduse");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->requestor");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->quantity");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->uom");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->item_description");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$supplier");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->pr_no");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$unit_price");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$total");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$terms");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":N".$num)->applyFromArray($styleArray);
                    $num++; 
            }
                
        }else {
            $num = 6;
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.for_recom='1'") AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $unit_price;
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $p->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $p->pr_details_id);
                if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $p->pr_details_id, "po_id", "DESC", "1");
                $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                if($served==0 && $cancelled_items_po==0){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$p->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->item_description");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$supplier");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$unit_price");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$total");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$terms");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":N".$num)->applyFromArray($styleArray);
                $num++;
            }
        }
    }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Summary of Weekly Recommendation.xlsx"');
        readfile($exportfilename);
    }

    public function pending_weekly_recom(){
        $date_from=$this->uri->segment(3);
        $date_to=$this->uri->segment(4);  
        $data['recom_date_from']=$date_from;
        $data['recom_date_to']=$date_to;
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$date_from' AND '$date_to' AND pd.recom_date_to BETWEEN '$date_from' AND '$date_to' AND pd.for_recom='1'") AS $p){
            $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
            $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
            $total = $p->quantity * $unit_price;
            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$p->pr_id' AND served = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$p->pr_details_id'");
            $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$p->pr_details_id' AND saved='1' AND cancelled='0'");
            $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$p->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
            if($count_rfq==0 && $count_aoq==0 && $count_po==0){
                $data['weekly_recom'][]=array(
                    'enduse'=>$p->enduse,
                    'requestor'=>$p->requestor,
                    'quantity'=>$p->quantity,
                    'uom'=>$p->uom,
                    'item_description'=>$p->item_description,
                    'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id),
                    'pr_no'=>$p->pr_no,
                    'terms'=>$this->super_model->select_column_custom_where('aoq_vendors','payment_terms',"vendor_id='$p->vendor_id' AND aoq_id='$aoq_id'"),
                    'unit_price'=>$unit_price,
                    'total'=>$total
                );
            }else{
                $data['weekly_recom']=array();
            }
        }
        $this->load->view('template/header');  
        $this->load->view('reports/pending_weekly_recom',$data);
        $this->load->view('template/footer');
    }

    public function search_pending_weekly_recom(){
        if(!empty($this->input->post('recom_date_from'))){
            $data['recom_date_from'] = $this->input->post('recom_date_from');
            $recom_date_from = $this->input->post('recom_date_from');
        } else {
            $data['recom_date_from']= "null";
            $recom_date_from= "null";
        }

        if(!empty($this->input->post('recom_date_to'))){
            $data['recom_date_to'] = $this->input->post('recom_date_to');
            $recom_date_to = $this->input->post('recom_date_to');
        } else {
            $data['recom_date_to']= "null";
            $recom_date_to= "null";
        }

        if(!empty($this->input->post('enduse'))){
            $data['enduse'] = $this->input->post('enduse');
            $enduse = $this->input->post('enduse');
        } else {
            $data['enduse']= "null";
            $enduse= "null";
        }

        if(!empty($this->input->post('purpose'))){
            $data['purpose'] = $this->input->post('purpose');
            $purpose = $this->input->post('purpose');
        } else {
            $data['purpose']= "null";
            $purpose= "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
            $requestor = $this->input->post('requestor');
        } else {
            $data['requestor']= "null";
            $requestor= "null";
        }

        if(!empty($this->input->post('uom'))){
            $data['uom'] = $this->input->post('uom');
            $uom = $this->input->post('uom');
        } else {
            $data['uom']= "null";
            $uom= "null";
        }

        if(!empty($this->input->post('description'))){
            $data['description'] = $this->input->post('description');
            $description = $this->input->post('description');
        } else {
            $data['description'] = "null";
            $description = "null";
        }

        if(!empty($this->input->post('supplier'))){
            $data['supplier'] = $this->input->post('supplier');
            $supplier = $this->input->post('supplier');
        } else {
            $data['supplier'] = "null";
            $supplier = "null";
        }

        if(!empty($this->input->post('pr_no'))){
            $data['pr_no'] = $this->input->post('pr_no');
            $pr_no = $this->input->post('pr_no');
        } else {
            $data['pr_no'] = "null";
            $pr_no = "null";
        } 

        $sql="";
        $filter = "";

        if($recom_date_from!='null'){
            $sql.=" pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_from.", ";
        }

        if($recom_date_to!='null'){
            $sql.=" pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_to.", ";
        }

        if($enduse!='null'){
            $sql.=" ph.enduse LIKE '%$enduse%' AND";
            $filter .= $enduse.", ";
        }

        if($purpose!='null'){
            $sql.=" ph.purpose LIKE '%$purpose%' AND";
            $filter .= $purpose.", ";
        }

        if($requestor!='null'){
            $sql.=" ph.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor.", ";
        }

        if($uom!='null'){
            $sql.=" pd.uom LIKE '%$uom%' AND";
            $filter .= $uom.", ";
        }

        if($description!='null'){
                $sql.=" pd.item_description LIKE '%$description%' AND";
            $filter .= $description.", ";
        }


        if($supplier!='null'){
            $sql.=" pd.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$supplier).", ";
        }

        if($pr_no!='null'){
            $sql.=" ph.pr_no LIKE '%$pr_no%' AND";
            $filter .= $pr_no.", ";
        }

        /*if($supplier!='null'){
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }*/

        /*if($terms!='null'){
            $sql.=" pp.terms = '$terms' AND";
            $filter .= $terms;
        }*/

       /* if($company!='null'){
            $sql.=" pd.company_id = '$company' AND";
            $filter .= $this->super_model->select_column_where('company', 'company_name', 'company_id', $company);
        }*/

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        $data['filt']=$filt;
        $date = $recom_date_from."-".$recom_date_to;
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        $count_search_pending_weekly = $this->super_model->count_custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.for_recom='1'");
        if($count_search_pending_weekly!=0){
        foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.for_recom='1'") AS $p){
            $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
            $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
            $total = $p->quantity * $unit_price;
            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$p->pr_id' AND served = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$p->pr_details_id'");
            $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$p->pr_details_id' AND saved='1' AND cancelled='0'");
            $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$p->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
            if($count_rfq==0 && $count_aoq==0 && $count_po==0){
                $data['weekly_recom'][]=array(
                    'enduse'=>$p->enduse,
                    'requestor'=>$p->requestor,
                    'quantity'=>$p->quantity,
                    'uom'=>$p->uom,
                    'item_description'=>$p->item_description,
                    'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id),
                    'pr_no'=>$p->pr_no,
                    'terms'=>$this->super_model->select_column_custom_where('aoq_vendors','payment_terms',"vendor_id='$p->vendor_id' AND aoq_id='$aoq_id'"),
                    'unit_price'=>$unit_price,
                    'total'=>$total
                );
            }
        }
        }else{
            $data['weekly_recom']=array();
        }
        $this->load->view('template/header');        
        $this->load->view('reports/pending_weekly_recom',$data);
        $this->load->view('template/footer');
    }

    public function export_pending_weekly_recom(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Summary of Pending Weekly Recommendation.xlsx";
        $recom_date_from=$this->uri->segment(3);
        $recom_date_to=$this->uri->segment(4);
        $enduse=str_replace("%20", " ", $this->uri->segment(5));
        $purpose=str_replace("%20", " ", $this->uri->segment(6));
        $requestor=str_replace("%20", " ", $this->uri->segment(7));
        $uom=$this->uri->segment(8);
        $description=str_replace("%20", " ", $this->uri->segment(9));
        $supplier=$this->uri->segment(10);
        $pr_no=str_replace("%20", " ", $this->uri->segment(11));

        $sql="";
        $filter = "";

        /*if($recom_date_from!='null'){
            $sql.=" pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_from;
        }

        if($recom_date_to!='null'){
            $sql.=" pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_to;
        }*/

        if($enduse!='null'){
            $sql.=" ph.enduse LIKE '%$enduse%' AND";
            $filter .= $enduse;
        }

        if($purpose!='null'){
            $sql.=" ph.purpose LIKE '%$purpose%' AND";
            $filter .= $purpose;
        }

        if($requestor!='null'){
            $sql.=" ph.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }

        if($uom!='null'){
            $sql.=" pd.uom LIKE '%$uom%' AND";
            $filter .= $uom;
        }

        if($description!='null'){
                $sql.=" pd.item_description LIKE '%$description%' AND";
            $filter .= $description;
        }

        /*if($supplier!='null'){
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }*/

        if($pr_no!='null'){
            $sql.=" ph.pr_no = '$pr_no' AND";
            $filter .= $pr_no;
        }

        /*if($terms!='null'){
            $sql.=" pp.terms = '$terms' AND";
            $filter .= $terms;
        }*/

        /*if($company!='null'){
            $sql.=" pd.company_id = '$company' AND";
            $filter .= $this->super_model->select_column_where('company', 'company_name', 'company_id', $company);
        }*/

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        $date = $recom_date_from." - ".$recom_date_to;
        //$date = date('Y-m', strtotime($date));
        /*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "$company");*/
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "MATERIALS RECOMMENDATION (PENDING)");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "$date");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "End-use");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "Requested by:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "QTY as per PR");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "UoM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "Site PR/JO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', "Delivery Lead Time / Work Duration");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "UNIT PRICE (PESO)");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J5', "TOTAL PESO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "15 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L5', "30 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M5', "60 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N5', "TERMS");
        foreach(range('A','N') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A5:N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:N5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:N5')->applyFromArray($styleArray1);
        if($filt!=''){
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $num = 6;
            foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND $query AND pd.for_recom='1'") AS $p){
                $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $unit_price;
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);

                 
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$p->enduse");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->requestor");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->quantity");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->uom");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->item_description");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$supplier");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->pr_no");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$unit_price");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$total");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$terms");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":N".$num)->applyFromArray($styleArray);
                    $num++; 
            }
                
        }else {
            $num = 6;
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.for_recom='1'") AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $unit_price;
                $unit_price = $this->super_model->select_column_custom_where('aoq_offers','unit_price',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $unit_price;
                $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$p->pr_id' AND served = '0' AND pi.pr_details_id = '$p->pr_details_id'");
                $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$p->pr_details_id'");
                $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$p->pr_details_id' AND saved='1' AND cancelled='0'");
                $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$p->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                if($count_rfq==0 && $count_aoq==0 && $count_po==0){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$p->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->item_description");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$supplier");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$unit_price");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$total");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$terms");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":N".$num)->applyFromArray($styleArray);
                $num++;
            }
        }
    }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Summary of Pending Weekly Recommendation.xlsx"');
        readfile($exportfilename);
    }

    public function calendar(){
        $po_offer_id =$this->input->post('po_offer_id');
        $pr_id =$this->input->post('pr_id');
        $pr_details_id =$this->input->post('pr_details_id');
        $status =$this->input->post('status');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $ver_date_needed=$this->input->post('ver_date_needed');
        $estimated_price =$this->input->post('estimated_price');
        

        $data=array(
            'ver_date_needed'=>$ver_date_needed,
            'estimated_price'=>$estimated_price,


        );
        $this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id);
    {
            echo "<script>alert('Successfully Added!'); window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
        }
    }
    public function purch_calendar(){
        $date_from=$this->uri->segment(3);
        $date_to=$this->uri->segment(4);  
        $data['recom_date_from']=$date_from;
        $data['recom_date_to']=$date_to;
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$date_from' AND '$date_to' AND pd.recom_date_to BETWEEN '$date_from' AND '$date_to' AND pd.for_recom='1'") AS $p){
            $estimated_price = $this->super_model->select_column_custom_where('pr_details','estimated_price',"pr_details_id='$ca->pr_details_id'");
            $total_ep = $ca->quantity * $estimated_price;
            $total_array[] = $total_ep;
            $total_disp = array_sum($total_array);
            $data['total_disp']=$total_disp;
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $ca->pr_details_id, "po_id", "DESC", "1");
            $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
            $data['dash_calendar'][] =  array(
                'ver_date_needed'=>$ca->ver_date_needed,
                'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$ca->pr_id),
                'description'=>$ca->item_description,
                'quantity'=>$ca->quantity,
                'estimated_price'=>$ca->estimated_price,
                'total_ep'=>$total_ep,
                'served'=>$served

            );
        }
        $this->load->view('template/header');  
        $this->load->view('reports/purch_calendar',$data);
        $this->load->view('template/footer');
    }
}
?>