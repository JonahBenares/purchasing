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
        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $pr){
            $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
           // echo "SELECT sum(quantity) AS total FROM po_items WHERE pr_details_id = '$pr->pr_details_id'";
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
            $unserved_qty=0;
            $unserved_uom='';  
            if($sum_po_qty!=0){
                if($sum_po_qty < $pr->quantity){
                      $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                 /*   if($count_rfd == 0){
                        $status = 'PO Done';
                        $status_remarks = 'Pending RFD - Partial';
                    } else {*/
                       /* $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $po_id);*/
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                        $delivered_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);

                      /*  $unserved_qty = $this->super_model->select_column_custom_where('aoq_offers', 'balance', "pr_details_id='$pr->pr_details_id' AND recommended = '1'");
                        $unserved_uom = $this->super_model->select_column_custom_where('aoq_offers', 'uom', "pr_details_id='$pr->pr_details_id' AND recommended = '1'");*/

                        $unserved_qty = $pr->quantity - $served_qty;
                        $unserved_uom =  $served_uom;

                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'pr_details_id', $pr->pr_details_id);
                        if($served==0){
                            if($cancelled_items_po==0){
                                $status = 'PO Issued - Partial';
                            }else {
                                $status='Cancelled';
                            }
                            //$status = "Partially Delivered <span style='font-size:11px; color:green; font-weight:bold'>(". $delivered_qty ." ".$served_uom.")</span>";
                            $status_remarks = '';
                        } else {

                            $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                            if($cancelled_items_po==0){
                                $status = 'Partially Delivered';
                            }else {
                                $status='Cancelled';
                            }
                            //$status_remarks = date('m.d.y', strtotime($date_delivered)) . " - Delivered ". number_format($served_qty) . " " . $served_uom. " DR# ".$dr_no;

                               $status_remarks='';
                           foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                            if(!empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                 $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";

                                }
                                if(empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND ph.po_id = '$del->po_id' AND pi.pr_details_id = '$pr->pr_details_id'");
                                        $status_remarks.="PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty .")</span>";
                                }
                            }
                         
                        }
                        $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                        if($cancelled_head_po==1){
                            $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                            $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                            $status = "Cancelled";
                            $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                        }
                  //  }

                } else {
                    $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                    $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'pr_details_id', $pr->pr_details_id);
                    if($served==0){
                        if($cancelled_items_po==0){
                            $status = 'PO Issued';
                        }else {
                            $status = "Cancelled";
                        }
                        $status_remarks = '';
                    } else {
                        if($cancelled_items_po==0){
                            $status = 'Fully Delivered';
                        } else {
                            $status = "Cancelled";
                        }
                        $status_remarks='';
                        foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                             $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                        }
                    }

                    if($cancelled_head_po==1){
                        $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                        $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                        $status = "Cancelled";
                        $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                    }
                }

                $prs[] = array(
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
                );
                
            } else {
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'pr_details_id', $pr->pr_details_id);
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                    $status = "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                } else {
                
                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

                    $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='1' AND pr.pr_id = '$pr->pr_id' AND served = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

                    /*  echo "SELECT sum(delivered_quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id' = ". $sum_po_delivered_qty . "<br>";*/
                  
                    $count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                    //$count_rfq_completed = $this->super_model->count_custom_query("SELECT rh.rfq_id FROM rfq_head rh INNER JOIN rfq_details rd ON rh.rfq_id = rd.rfq_id WHERE rd.pr_details_id= '$pr->pr_details_id' AND completed='1'");
                    $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                    
                   $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                 

                    if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0){
                        if($cancelled_items_po==0){
                            $status = 'Pending';
                        } else {
                            $status = "Cancelled";
                        }
                        $status_remarks = 'For RFQ';
                    } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                        if($cancelled_items_po==0){
                            $status = 'Pending';
                        } else {
                            $status = "Cancelled";
                        }
                        $status_remarks = 'Canvassing Ongoing';
                    
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                        //$status = 'Pending';
                        if($cancelled_items_po==0){
                            $status = 'Pending';
                        } else {
                            $status = "Cancelled";
                        }
                        $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                        //$status = 'Pending';
                        if($cancelled_items_po==0){
                            $status = 'Pending';
                        } else {
                            $status = "Cancelled";
                        }
                        $status_remarks = 'AOQ Done - For TE ' .date('m.d.y', strtotime($aoq_date));
                    } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){

                        //$status = 'Pending';
                        if($cancelled_items_po==0){
                            $status = 'Pending';
                        } else {
                            $status = "Cancelled";
                        }
                        $status_remarks = 'For PO - AOQ Done (awarded)';

                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                         if($cancelled_items_po==0){
                            $status = "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                        } else {
                            $status = "Cancelled";
                        }
                      
                        $status_remarks = '';
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po_served!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po_served!=0)){ 
                         if($cancelled_items_po==0){
                            $status = "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$pr->uom .")</span>";
                        } else {
                            $status = "Cancelled";
                        }
                      
                        $status_remarks = '';
                    } 

                     $prs[] = array(
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
                );
            }

                }

               
            
        }
        print_r($prs);
/*

        foreach($prs AS $p){

             $data['pr'][] = array(
                'pr_details_id'=>$p['pr_details_id'],
                'date_prepared'=>$p['date_prepared'],
                'purchase_request'=>$p['purchase_request'],
                'pr_no'=>$p['pr_no'],
                'purpose'=>$p['purpose'],
                'enduse'=>$p['enduse'],
                'department'=>$p['department'],
                'requestor'=>$p['requestor'],
                'grouping_id'=>$p['grouping_id'],
                'item_description'=>$p['item_description'],
                'item_no'=>$p['item_no'],
                'wh_stocks'=>$p['wh_stocks'],
                'qty'=>$p['qty'],
                'revised_qty'=>$p['revised_qty'],
                'uom'=>$p['uom'],
                'status'=>$p['status'],
                'status_remarks'=>$p['status_remarks'],
                'date_needed'=>$p['date_needed'],
                'unserved_qty'=>$p['unserved_qty'],
                'unserved_uom'=>$p['unserved_uom'],
                'remarks'=>$p['remarks'],
                'cancelled'=>$p['cancelled'],
            );
         }
*/

        print_r($prs);
        $this->load->view('template/header');        
        $this->load->view('reports/pr_report',$data);
        $this->load->view('template/footer');
        
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
        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ".$query) AS $pr){
            $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $unserved_qty=0;
            $unserved_uom='';
            if($sum_po_qty!=0){
                if($sum_po_qty < $pr->quantity){
                    $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                    $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                    $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);
                    $unserved_qty = $pr->quantity - $served_qty;
                    $unserved_uom =  $served_uom;
                    $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                    if($served==0){
                         $status = 'PO Issued - Partial';
                         $status_remarks = '';
                    } else {

                        $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                        $status = 'Partially Delivered';
                        $status_remarks='';
                       foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                            if(!empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                //$status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                                $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."(". $del->quantity . " ".$del->uom .")"."\n";
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
                    $cancelled_items_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                    if($served==0){
                        $status = 'PO Issued';
                        $status_remarks = '';
                    } else {
                        $status = 'Fully Delivered';
                        $status_remarks='';
                       foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                            //$status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                            $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
                        }
                    }

                    if($cancelled_items_po==1){
                        $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                        $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                        $status = "Cancelled";
                        $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    } 
                }
            } else {
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                    $status = "Cancelled";
                    $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                } else {
                
                    //$count_po = $this->super_model->count_custom_where("po_items","pr_details_id = '$pr->pr_details_id'");
                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(delivered_quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                    $count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                    //$count_rfq_completed = $this->super_model->count_custom_query("SELECT rh.rfq_id FROM rfq_head rh INNER JOIN rfq_details rd ON rh.rfq_id = rd.rfq_id WHERE rd.pr_details_id= '$pr->pr_details_id' AND completed='1'");
                    $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                    $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                    //$count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1'");
                    //$count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '1'");

                    if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0){
                        $status = 'Pending';
                        $status_remarks = 'For RFQ';
                    } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                         $status = 'Pending';
                         $status_remarks = 'Canvassing Ongoing';
                    
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                         $status = 'Pending';
                         $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                         $status = 'Pending';
                         $status_remarks = 'AOQ Done - For TE ' .date('m.d.y', strtotime($aoq_date));
                    } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){

                         $status = 'Pending';
                         $status_remarks = 'For PO - AOQ Done (awarded)';

                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                         $status = "PO Issued  (". $sum_po_issued_qty . " ".$pr->uom .")";
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

            $data['pr'][] = array(
                'pr_details_id'=>$pr->pr_details_id,
                'date_prepared'=>$pr->date_prepared,
                'purchase_request'=>$pr->purchase_request,
                'pr_no'=>$pr->pr_no,
                'purpose'=>$pr->purpose,
                'enduse'=>$pr->enduse,
                'department'=>$pr->department,
                'requestor'=>$pr->requestor,
                'item_no'=>$pr->item_no,
                'item_description'=>$pr->item_description,
                'wh_stocks'=>$pr->wh_stocks,
                'revised_qty'=>$revised,
                'grouping_id'=>$pr->grouping_id,
                'qty'=>$pr->quantity,
                'uom'=>$pr->uom,
                'status'=>$status,
                'date_needed'=>$pr->date_needed,
                'status_remarks'=>$status_remarks,
                'unserved_qty'=>$unserved_qty,
                'unserved_uom'=>$unserved_uom,
                'remarks'=>$pr->add_remarks,
                'cancelled'=>$pr->cancelled,
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "Date Received/Emailed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "Purchase Request");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Purpose");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "Enduse");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "PR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "Requestor");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "WH Stocks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Item No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Revised Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Grouping");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Item Description");
        /*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "RO/with AOQ");*/
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Status Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P4', "Date Needed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q4', "Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R4', "End User's Comments");
        foreach(range('A','R') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:R4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:R4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:R4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ".$query) AS $pr){
                $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                $unserved_qty=0;
                $unserved_uom='';
                if($sum_po_qty!=0){
                    if($sum_po_qty < $pr->quantity){
                        $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);
                        $unserved_qty = $pr->quantity - $served_qty;
                        $unserved_uom =  $served_uom;
                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        if($served==0){
                             $status = 'PO Issued - Partial';
                             $status_remarks = '';
                        } else {

                            $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                            $status = 'Partially Delivered';
                            $status_remarks='';
                           foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                                if(!empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                    //$status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
                                    $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ." (". $del->quantity . " ".$del->uom .")"."\n";
                                }
                            }
                         
                        }
                    } else {
                        $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        $cancelled_items_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                        if($served==0){
                            $status = 'PO Issued';
                            $status_remarks = '';
                        } else {
                            $status = 'Fully Delivered';
                            $status_remarks='';
                           foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                                 //$status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
                                $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
                            }
                        }

                        if($cancelled_items_po==1){
                            $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                            $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                            $status = "Cancelled";
                            $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                        }
                    }
                } else {
                    $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                    if($cancelled_items==1){
                        $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                        $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                        $status = "Cancelled";
                        $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    } else {
                    
                        //$count_po = $this->super_model->count_custom_where("po_items","pr_details_id = '$pr->pr_details_id'");
                        $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(delivered_quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                        $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                        //$count_rfq_completed = $this->super_model->count_custom_query("SELECT rh.rfq_id FROM rfq_head rh INNER JOIN rfq_details rd ON rh.rfq_id = rd.rfq_id WHERE rd.pr_details_id= '$pr->pr_details_id' AND completed='1'");
                        //$count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1'");
                        $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                        $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                        //$count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '1'");

                        if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0){
                            $status = 'Pending';
                            $status_remarks = 'For RFQ';
                        } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                             $status = 'Pending';
                             $status_remarks = 'Canvassing Ongoing';
                        
                        } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                             $status = 'Pending';
                             $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                        } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                             $status = 'Pending';
                             $status_remarks = 'AOQ Done - For TE ' .date('m.d.y', strtotime($aoq_date));
                        } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){

                             $status = 'Pending';
                             $status_remarks = 'For PO - AOQ Done (awarded)';

                        } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                             $status = "PO Issued (". $sum_po_issued_qty . " ".$pr->uom .")";
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

                if($status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                } else if($status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f3ff9e');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr->date_prepared");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$pr->purchase_request");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$pr->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$pr->pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$pr->wh_stocks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$pr->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$revised");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$pr->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$pr->grouping_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$pr->item_description $unserved");
                /*$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "");*/
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$status_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$status");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$pr->date_needed");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$pr->add_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "");

                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setWrapText(true);
                /*$objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
                /*$objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->applyFromArray($styleArray);
                $num++;
            }
        }else {
            $num = 5;
            foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $pr){
                $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
               // echo "SELECT sum(quantity) AS total FROM po_items WHERE pr_details_id = '$pr->pr_details_id'";
                $unserved_qty=0;
                $unserved_uom='';
                if($sum_po_qty!=0){
                    if($sum_po_qty < $pr->quantity){
                          $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                     /*   if($count_rfd == 0){
                            $status = 'PO Done';
                            $status_remarks = 'Pending RFD - Partial';
                        } else {*/
                           /* $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $po_id);*/
                            $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                            $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $pr->pr_details_id);
                            $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr->pr_details_id);

                          /*  $unserved_qty = $this->super_model->select_column_custom_where('aoq_offers', 'balance', "pr_details_id='$pr->pr_details_id' AND recommended = '1'");
                            $unserved_uom = $this->super_model->select_column_custom_where('aoq_offers', 'uom', "pr_details_id='$pr->pr_details_id' AND recommended = '1'");*/

                            $unserved_qty = $pr->quantity - $served_qty;
                            $unserved_uom =  $served_uom;

                            $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);

                            if($served==0){
                                 $status = 'PO Issued - Partial';
                                 $status_remarks = '';
                            } else {

                                $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                                $status = 'Partially Delivered';
                                //$status_remarks = date('m.d.y', strtotime($date_delivered)) . " - Delivered ". number_format($served_qty) . " " . $served_uom. " DR# ".$dr_no;

                                   $status_remarks='';
                               foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                                     //$status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
                                    if(!empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                     $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ." (". $del->quantity . " ".$del->uom .")"."\n";

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
                        $cancelled_items_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                        if($served==0){
                            $status = 'PO Issued';
                            $status_remarks = '';
                        } else {
                            $status = 'Fully Delivered';
                            $status_remarks='';
                           foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $pr->pr_details_id) AS $del){
                                 $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
                            }
                        }

                        if($cancelled_items_po==1){
                            $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                            $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                            $status = "Cancelled";
                            $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                        } 
                    }
                } else {
                    $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                    if($cancelled_items==1){
                        $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                        $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                        $status = "Cancelled";
                        $status_remarks = $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    } else {
                    
                        $count_po = $this->super_model->count_custom_where("po_items","pr_details_id = '$pr->pr_details_id'");
                        $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
                        $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(delivered_quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

                        /*  echo "SELECT sum(delivered_quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id' = ". $sum_po_delivered_qty . "<br>";*/
                        
                        $count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                        //$count_rfq_completed = $this->super_model->count_custom_query("SELECT rh.rfq_id FROM rfq_head rh INNER JOIN rfq_details rd ON rh.rfq_id = rd.rfq_id WHERE rd.pr_details_id= '$pr->pr_details_id' AND completed='1'");
                        $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                        $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND cancelled='0'");
                        $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr->pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
                        //$count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1'");
                        //$count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '1'");

                       // echo 'ITEM = ' . $pr->item_description . '<br> rfq = ' . $count_rfq . '<br> aoq = ' . $count_aoq . '<br> aoq awarded = ' . $count_aoq_awarded . '<br> po='.$count_po . "<br><br>";


                        if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0){
                            $status = 'Pending';
                            $status_remarks = 'For RFQ';
                        } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                             $status = 'Pending';
                             $status_remarks = 'Canvassing Ongoing';
                        
                        } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                             $status = 'Pending';
                             $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                        } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                                $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                             $status = 'Pending';
                             $status_remarks = 'AOQ Done - For TE ' .date('m.d.y', strtotime($aoq_date));
                        } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){

                             $status = 'Pending';
                             $status_remarks = 'For PO - AOQ Done (awarded)';

                        } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                             $status = "PO Issued (". $sum_po_issued_qty . " ".$pr->uom .")";
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

                if($status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                } else if($status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f3ff9e');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr->date_prepared");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$pr->purchase_request");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$pr->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$pr->pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$pr->wh_stocks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$pr->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$revised");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$pr->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$pr->grouping_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$pr->item_description $unserved");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$status_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$status");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$pr->date_needed");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$pr->add_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "");

                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setWrapText(true);
                /*$objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
                /*$objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":R".$num)->applyFromArray($styleArray);
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
                        } else {
                             $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                           // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $dr_no;
                               $status='';
                           foreach($this->super_model->select_custom_where("po_dr_items", "pr_details_id= '$i->pr_details_id' AND po_id = '$p->po_id'") AS $del){
                                 $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
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
                         $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
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
        $filter = " ";

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
            $filter .= "Requestor - ".$requestor.", ";
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
            $filter .= "Item Description - ".$description.", ";
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
                    } else {
                         $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                       // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $dr_no;
                           $status='';
                       foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $p->pr_details_id) AS $del){
                             $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
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

                if($p->served=='1'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                } else if($p->cancelled=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                }
                //$partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$p->pr_id' AND ai.aoq_items_id = '$p->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->po_date");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->po_no");
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
                        $total=$i->quantity*$i->unit_price;
                foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$po_date%' GROUP BY po_id") AS $p){
                    $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                    $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);

                    foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                        $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
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
                                } else {
                                     $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                                   // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $dr_no;
                                       $status='';
                                   foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $i->pr_details_id) AS $del){
                                         $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
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
                        if($p->served=='1'){
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                        } else if($p->cancelled=='1') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                        }
                        //$partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$pr->pr_id' AND ai.aoq_items_id = '$i->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr_no");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$pr->purpose");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->enduse");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->po_date");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->po_no");
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
        $pr_details_id =$this->input->post('pr_details_id');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $remarks=$this->input->post('remarks');
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
        //}

        if($this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id)){
            if($cancel!=0){
                $po_id = $this->super_model->select_column_where("po_items",'po_id','pr_details_id',$pr_details_id);
                $data_po=array(
                    'cancel'=>$cancel,
                    'cancelled_by'=>$_SESSION['user_id'],
                    'cancelled_date'=>date('Y-m-d'),
                );
                $this->super_model->update_where("po_items", $data_po, "po_id", $po_id);
            }
            redirect(base_url().'reports/pr_report/'.$year.'/'.$month);
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
        /*$data['year']=$year;
        $data['month']=$month;*/
       
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

        
        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $prs){
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$prs->pr_details_id'");
            $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $prs->pr_details_id);
            if($sum_po_qty!=0){
                if($sum_po_qty < $prs->quantity){
                    $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $prs->pr_details_id);
                    $unserved_qty = $prs->quantity - $served_qty;
                    $data['unserved'][]=array(
                        'pr_details_id'=>$prs->pr_details_id,
                        'unserved_qty'=>$unserved_qty,
                    );
                }

            }
        }

        foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$po_date%' GROUP BY po_id") AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);

            foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
                foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                    $date_needed = $this->super_model->select_column_where("pr_details",'date_needed','pr_details_id',$i->pr_details_id);
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
                        } else {
                             $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                           // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $dr_no;
                               $status='';
                           foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $i->pr_details_id) AS $del){
                                 $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                            }
                        }
                    }
                    /*$partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$pr->pr_id' AND ai.aoq_items_id = '$i->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");*/
                    $data['po'][]=array(
                        'pr_details_id'=>$i->pr_details_id,
                        'po_id'=>$i->po_id,
                        'pr_no'=>$pr_no,
                        'enduse'=>$pr->enduse,
                        'purpose'=>$pr->purpose,
                        'requested_by'=>$requestor,
                        'qty'=>$i->quantity,
                        'uom'=>$i->uom,
                        'item'=>$item,
                        'unit_price'=>$i->unit_price,
                        'notes'=>$pr->notes,
                        'po_id'=>$p->po_id,
                        'po_date'=>$p->po_date,
                        'po_no'=>$p->po_no,
                        'saved'=>$p->saved,
                        'cancelled'=>$p->cancelled,
                        'status'=>$status,
                        'date_needed'=>$date_needed,
                        'supplier'=>$supplier,
                        'terms'=>$terms,
                    );
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

        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $prs){
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$prs->pr_details_id'");
            $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $prs->pr_details_id);
            if($sum_po_qty!=0){
                if($sum_po_qty < $prs->quantity){
                    $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $prs->pr_details_id);
                    $unserved_qty = $prs->quantity - $served_qty;
                    $data['unserved'][]=array(
                        'pr_details_id'=>$prs->pr_details_id,
                        'unserved_qty'=>$unserved_qty,
                    );
                }

            }
        }

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
                         $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."<br>";
                    }
                }
            }
            $data['po'][]=array(
                'po_id'=>$p->po_id,
                'pr_details_id'=>$p->pr_details_id,
                'pr_no'=>$pr_no,
                'enduse'=>$p->enduse,
                'purpose'=>$p->purpose,
                'requested_by'=>$requestor,
                'qty'=>$p->quantity,
                'uom'=>$p->uom,
                'item'=>$item,
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
            );
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
            $filter .= $description.", ";
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
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id);
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
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
                    $status = "Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason;
                } else {
                    
                    if($p->served==0){
                        $status = 'PO Issued';
                    } else {
                        $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                        $status='';
                        foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $p->pr_details_id) AS $del){
                            $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
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
                foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $prs){
                    $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$prs->pr_details_id'");
                    $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $prs->pr_details_id);
                    if($sum_po_qty!=0){
                        if($sum_po_qty < $prs->quantity){
                            $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $prs->pr_details_id);
                            $unserved_qty = $prs->quantity - $served_qty;
                            $total=$unserved_qty*$p->unit_price;
                            if($p->pr_details_id==$prs->pr_details_id){
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
                    }
                }
            }
        }else {
            $num = 5;
            foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$po_date%' GROUP BY po_id") AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                    $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
                    foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                        
                        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $prs){
                                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$prs->pr_details_id'");
                                $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $prs->pr_details_id);
                                if($sum_po_qty!=0){
                                    if($sum_po_qty < $prs->quantity){
                                        $served_qty = $this->super_model->select_column_where('po_items', 'quantity', 'pr_details_id', $prs->pr_details_id);
                                        $unserved_qty = $prs->quantity - $served_qty;
                                    

                                $total=$unserved_qty*$i->unit_price;
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
                                    } else {
                                        $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $p->po_id);
                                        $status='';
                                        foreach($this->super_model->select_row_where("po_dr_items", 'pr_details_id', $i->pr_details_id) AS $del){
                                            $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id) ."\n";
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
                                if($i->pr_details_id==$prs->pr_details_id){
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
}
?>