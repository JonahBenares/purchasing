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

    public function generate_jor_summary(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        redirect(base_url().'reports/jor_report/'.$year.'/'.$month);
    }

    public function generate_joi_summary(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        redirect(base_url().'reports/joi_report/'.$year.'/'.$month);
    }

    public function generate_jounserved_report(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        redirect(base_url().'reports/jo_unserved_report/'.$year.'/'.$month);
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

    public function generate_purch_calendar_report(){
        $year = $this->input->post('year');
        redirect(base_url().'reports/purch_calendar/'.$year);
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

        $controller_name=$this->uri->segment(2);
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
        $data['pr_no_1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['company']=$this->super_model->select_all_order_by("company","company_name","ASC");
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        $data['terms']=$this->super_model->select_all_order_by("terms","terms","ASC");
        $data['proj_act']=$this->super_model->select_custom_where("project_activity","status='Active' ORDER BY proj_activity ASC");
        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%' ORDER BY ph.date_prepared DESC, pd.item_no ASC") AS $pr){
            //echo $pr->wh_stocks;

            $count_number_po = $this->super_model->count_custom_where("po_items", "pr_details_id = '$pr->pr_details_id'");
           
            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $pr->pr_details_id);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $pr->pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr->pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            if($count_number_po > 1){
           // echo $pr->pr_details_id . " = " . $count_number_po . "<br>";
               $po_id = $this->super_model->select_column_innerjoin_where("po_id", "po_items","po_head", "pr_details_id='$pr->pr_details_id' AND cancelled!='1'","po_id");
            } else {

            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $pr->pr_details_id, "po_id", "DESC", "1");
            }
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->onhold_by);
            $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr->pr_details_id);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr->pr_details_id);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr->pr_details_id);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr->pr_details_id);
     
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

        
          
         
            $unserved_qty=0;
            $unserved_uom='';  
            /********************** START STATUS ******************/
         
            $stat = $this->pr_item_status($pr->pr_details_id,$controller_name,$po_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
        
            /********************** END STATUS ********************/
            $revised='';
            $current_qty='';
            $revision_no = $this->super_model->select_column_where("po_head","revision_no","po_id",$po_id);
            if($revision_no!=0){
                foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM po_items_revised WHERE po_id = '$po_id' AND pr_details_id = '$pr->pr_details_id' GROUP BY revision_no") AS $rev){
                    if($rev->revision_no == 0){
                         $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                    } else {
                         $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                         $qty = $this->super_model->select_column_custom_where("po_items","delivered_quantity","po_id='$po_id' AND pr_details_id='$pr->pr_details_id'");
                         $uom = $this->super_model->select_column_custom_where("po_items","uom","po_id='$po_id' AND pr_details_id='$pr->pr_details_id'");
                         $current_qty = "Current Qty: ".$qty." ".$uom;
                    }
                }
            }



            $pr_id = $pr->pr_id;

           // echo $pr->pr_details_id . " = " . $pr->item_no."<br>";

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
                'current_qty'=>$current_qty,
                'uom'=>$pr->uom,
                'status'=>$status,
                'status_remarks'=>$status_remarks,
                'date_needed'=>$pr->date_needed,
                'unserved_qty'=>$unserved_qty,
                'unserved_uom'=>$unserved_uom,
                'remarks'=>$pr->add_remarks,
                'company'=>$this->super_model->select_column_where('company','company_name','company_id',$pr->company_id),
                'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$pr->vendor_id),
                'company_id'=>$pr->company_id,
                'supplier_id'=>$pr->vendor_id,
                'date_delivered'=>$pr->date_delivered,
                'unit_price'=>$pr->unit_price,
                'qty_delivered'=>$pr->qty_delivered,
                'cancel_remarks'=>$pr->cancel_remarks,
                'fulfilled_by'=>$pr->fulfilled_by,
                'for_recom'=>$pr->for_recom,
                'recom_qty'=>$pr->recom_qty,
                'recom_by'=>$this->super_model->select_column_where('users','fullname','user_id',$pr->recom_by),
                'recom_date_from'=>$pr->recom_date_from,
                'recom_date_to'=>$pr->recom_date_to,
                'cancelled'=>$pr->cancelled,
                'recom_unit_price'=>$recom_unit_price,
                'ver_date_needed'=>$ver_date_needed,
                'cancelled_items_po'=>$cancelled_items_po,
                'on_hold'=>$pr->on_hold,
                'pr_calendar_id'=>$pr_calendar_id,
                'estimated_price'=>$estimated_price,
                'proj_act_id'=>$proj_act_id,
              
                
            );

        }
        //print_r($prs);

        $this->load->view('template/header');        
        $this->load->view('reports/pr_report',$data);
        $this->load->view('template/footer');
        
    }


    public function pr_item_status($pr_details_id,$controller_name,$po_id){
         /*
            $pr_details_id = '2754';
            $controller_name = 'pr_report';
            $po_id = '1523';
*/

           
            $statuss='';
            $status='';
            $status_remarks='';
            $item_cancelled = $this->super_model->select_column_where("pr_details", "cancelled","pr_details_id",$pr_details_id); // check if item is cancelled
            $po_cancelled = $this->super_model->select_column_where("po_head", "cancelled","po_id",$po_id); // check if po is cancelled
            $item_on_hold = $this->super_model->select_column_where("pr_details", "on_hold","pr_details_id",$pr_details_id); // check if item is on hold
            $item_for_recom = $this->super_model->select_column_where("pr_details", "for_recom","pr_details_id",$pr_details_id); // check if item is for recom
            $pr_id = $this->super_model->select_column_where("pr_details", "pr_id","pr_details_id",$pr_details_id); // check if item is for recom
            $item_fulfilled_by_mnl = $this->super_model->select_column_where("pr_details", "fulfilled_by","pr_details_id",$pr_details_id); // check if item is fulfilled by MNL
          //  echo "SELECT rd.rfq_details_id FROM rfq_details rd INNER JOIN rfq_head rh WHERE rd.pr_details_id = '$pr_details_id' AND rh.saved = '1' AND rh.cancelled = '0'";
            $count_rfq = $this->super_model->count_custom_query("SELECT rd.rfq_details_id FROM rfq_details rd INNER JOIN rfq_head rh ON rd.rfq_id=rh.rfq_id WHERE rd.pr_details_id = '$pr_details_id' AND rh.saved = '1' AND rh.cancelled = '0'"); // check if item is already in the RFQ process

            $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' AND cancelled='0'"); // check if item is already in the AOQ process but not awarded

            $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'"); // check if item is already in the AOQ process but already awarded

            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '0' and draft = '0' AND pi.pr_details_id = '$pr_details_id'"); //checks if item has already PO but not yet served or delivered

            $count_po_served_draft = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '1'  and draft = '1' AND pi.pr_details_id = '$pr_details_id'"); //checks if item has already PO but already served or delivered

            $count_po_draft = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '0' and draft = '1' AND pi.pr_details_id = '$pr_details_id'"); //checks if item has already PO but not yet served or delivered

            $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '1'  and draft = '0' AND pi.pr_details_id = '$pr_details_id' AND ph.po_id = '$po_id'"); //checks if item has already PO but already served or delivered

            //$count_po_not_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '0'  and draft = '0' AND pi.pr_details_id = '$pr_details_id'");

            $pr_qty = $this->super_model->select_column_where("pr_details", "quantity","pr_details_id",$pr_details_id); // gets the PR qty of the item

            if($controller_name=='po_report'){
                // $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id' AND ph.po_id = '$po_id'"); // gets the total received qty of the item
               // $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id' AND pi.pr_id = '$pr_id'"); // gets the total received qty of the item

                $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_dr_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id' AND pi.pr_id = '$pr_id' AND pi.po_id='$po_id'");
            }else{
                $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id'");
            }

            
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id' AND ph.po_id = '$po_id'"); // gets the total delivered qty of the item


            $po_qty = $this->super_model->select_column_where("po_items","delivered_quantity","pr_details_id",$pr_details_id);
            $po_rec_qty = $this->super_model->select_column_where("po_items","quantity","pr_details_id",$pr_details_id);

           

            $draft = $this->super_model->select_column_where("po_head","draft","po_id",$po_id);

            $count_po_zero = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '1' AND pi.quantity='0' AND draft = '0' AND pi.pr_details_id = '$pr_details_id'");
            $count_po_served_zero = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '1' AND pi.quantity='0'  AND draft = '0' AND pi.pr_details_id = '$pr_details_id' AND ph.po_id = '$po_id'");
               //echo $pr_details_id . " - rfq = " .$count_rfq. ", aoq = ".  $count_aoq . " , award = ".  $count_aoq_awarded . " ,po =  " . $count_po   . ", served = " . $count_po_served . " ,draft = ". $draft."<br>";

            /*$po_id = $this->super_model->select_column_where("po_items","po_id","pr_details_id",$pr_details_id);
            echo $pr_details_id." - ".$po_id."<br>";*/
            if(empty($sum_received_qty)){
                $sum_received_qty = 0;
            } else {
                $sum_received_qty = $sum_received_qty;
            }
           
         //  echo $po_id . ", ". $pr_details_id . " -  rfq = ". $count_rfq .", aoq = ". $count_aoq. ", awarded = ".  $count_aoq_awarded . ", po = ". $count_po . ", poserved = ". $count_po_served ."<br>"; 
                          

           //echo $pr_qty ." < " . $sum_received_qty;

            /*if($item_cancelled == 1 && $po_cancelled==0){
                if($controller_name=='pr_report'){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr_details_id);
                    $status= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                }
            }else if($po_cancelled==1 && $item_cancelled==0){
                if($controller_name=='po_report'){
                    $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                    $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                    $status= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                }
            } */

          

            if($pr_qty > $sum_received_qty || $sum_received_qty == 0){
            
                if($item_cancelled == 1 && $controller_name=='pr_report'){
                    //if($controller_name=='pr_report'){
                        $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr_details_id);
                        $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr_details_id);
                       
                        $status= "Cancelled";
                        $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                    //}
                } else if($po_cancelled==1 && $controller_name=='po_report'){
                    //if($controller_name=='po_report'){
                        $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                        $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                        $status= "Cancelled";
                        $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                    //}
                } else {

               /*    
                    if($item_on_hold == 1){
                         $status = 'On-Hold';
                         $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                         $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
          
                        $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                        $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                    }*/

                    if($item_for_recom == 1){
                        $recom_date_from = $this->super_model->select_column_where("pr_details", "recom_date_from","pr_details_id",$pr_details_id); 
                        $recom_date_to = $this->super_model->select_column_where("pr_details", "recom_date_to","pr_details_id",$pr_details_id); 
                        $recom_by_id = $this->super_model->select_column_where("pr_details", "recom_by","pr_details_id",$pr_details_id); 
                        $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$recom_by_id);
                        $statuss = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                    }
                   
                    //if($count_po_served == 0){
                        
                        //echo $po_id . " ---- " . $count_rfq ." aoq =  " . $count_aoq . ", award = ". $count_aoq_awarded . ", po = ".$count_po . ", served = ". $count_po_served . "<br>";

                        if($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0){
                             
                             if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                                $status_remarks = 'For RFQ <br>' . $statuss;
                             } else {
                                if($item_fulfilled_by_mnl == 1){
                                    $status = 'Delivered by CENPRI-MNL';
                                     $status_remarks = 'For RFQ <br>' . $statuss;
                                } else {
                                    if($item_for_recom==0){
                                        $status = 'Pending';
                                    }else{
                                        $status = 'For Recom';
                                    }
                                    $status_remarks = 'For RFQ <br>' . $statuss;
                                }
                                
                             }


                        } else if($count_rfq != 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0){
                             if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                                $status_remarks = 'Canvassing Ongoing  <br>' . $statuss;
                             } else {
                                 $status = 'Pending';
                                 $status_remarks = 'Canvassing Ongoing <br>' . $statuss;
                            }
                            
                        } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0 && $draft!=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0 && $draft==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po == 0 && $count_po_served ==0 && $draft!=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po_draft != 0 && $count_po_served_draft ==0 && $draft!=0)){
                             if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                                $status_remarks = 'AOQ Done - For TE - '. $aoq_date .'<br>' . $statuss;
                             } else {
                                 $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' ");
                                 $status = 'Pending';
                                 $status_remarks = 'AOQ Done - For TE - '. $aoq_date .'<br>' . $statuss;

                            }
                          
                        } else if($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po == 0 && $count_po_served ==0){

                            if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                                $status_remarks = 'For PO - AOQ Done (awarded) <br>' . $statuss;
                             } else {
                                 $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' ");
                                 $status = 'Pending';
                                 $status_remarks = 'For PO - AOQ Done (awarded) <br>' . $statuss;

                            }


                          
                        } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0)) {
                            $uom = $this->super_model->select_column_where("pr_details", "uom","pr_details_id",$pr_details_id);
                            //echo $pr_qty ."<=". $sum_delivered_qty;
                            if($pr_qty < $sum_delivered_qty){
                                 $status = 'PO Issued - Partial';
                            } else {
                                 //$status = 'PO Issued';
                                 $status = "PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_delivered_qty . " ".$uom .")</span><br>" . $statuss;
                            }
                           
                        } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po_zero != 0 && $count_po_served_zero ==0) || ($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po_zero != 0 && $count_po_served_zero !=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po_zero != 0 && $count_po_served_zero !=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po_zero != 0 && $count_po_served_zero !=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po_zero != 0 && $count_po_served_zero !=0)) {
                            $uom = $this->super_model->select_column_where("pr_details", "uom","pr_details_id",$pr_details_id);
                            //echo $pr_qty ."<=". $sum_delivered_qty;
                            if($pr_qty < $sum_delivered_qty){
                                 $status = 'PO Issued - Partial';
                            } else {
                                 //$status = 'PO Issued';
                                 $status = "PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_delivered_qty . " ".$uom .")</span><br>" . $statuss;
                            }
                           
                        }else if($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po == 0 && $count_po_served !=0 && $draft==0 || $count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served !=0 && $draft==0 || $count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served !=0 && $draft==0 || $count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0 && $count_po_served !=0 && $draft==0){
                            $status_remarks='';

                         
                            if($controller_name=='pr_report' && $po_rec_qty!=0){
                                foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id'AND date_received!='' AND quantity!='0'") AS $del){
                                    //if($po_rec_qty!=0){
                                        // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                    $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                                    $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

                                        $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                    //}else{
                                       // $status_remarks.='';
                                    //}
                                }
                            }else{
                                foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id' AND pdr.po_id = '$po_id' AND date_received!='' AND quantity!='0'") AS $del){
                                    //if($po_rec_qty!=0){
                                    $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                                    $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

                                        // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                        $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                    //}else{
                                       // $status_remarks.='';
                                    //}
                                }
                            }
                            //echo $sum_delivered_qty ."<". $sum_received_qty;
                            if($controller_name=='po_report'){
                                //echo $po_id ." - " . $pr_qty ." ,". $sum_delivered_qty . " = ". $sum_received_qty ."<br>";
                               
                                if($sum_delivered_qty > $sum_received_qty){

                                    //$status="Partially Delivered";
                                    if($controller_name=='po_report'){
                                        if($po_qty != $sum_received_qty){
                                            $status = 'Partially Delivered';
                                        }else{
                                            $status = 'Fully Delivered';
                                        }
                                    }else{
                                        $status = 'Partially Delivered';
                                    }
                                }else if($pr_qty > $sum_delivered_qty && $sum_delivered_qty < $sum_received_qty){
                                    //echo $po_id ."<br>" ;
                                     $status = 'PO Issued - Partial <br> Partially Delivered';

                                } else if($sum_delivered_qty == $sum_received_qty || $po_qty == $po_rec_qty){
                                    $status = 'Fully Delivered';
                                }
                            }else{

                                //echo $pr_details_id . " - " . $controller_name . " - " . $po_id . ", " . $pr_qty . ", " . $po_qty . ", " . $sum_received_qty  .", " . $sum_delivered_qty . ", " .  $controller_name ."<br>";
                                $canrem  = $this->super_model->select_column_where("pr_details", "cancel_remarks","pr_details_id",$pr_details_id); 
                                if($sum_delivered_qty < $sum_received_qty || $pr_qty > $sum_received_qty){
                                    if($controller_name=='po_report'){
                                        if($po_qty != $sum_received_qty){
                                            $status = 'Partially Delivered';
                                        }else{
                                            $status = 'Fully Delivered';
                                        }
                                    }else {
                                        //if($po_rec_qty!=0){
                                        if($pr_qty != $sum_received_qty && empty($canrem)){
                                            $status = 'Partially Delivered';
                                        }else if($pr_qty == $sum_received_qty || !empty($canrem)){
                                            $status = 'Fully Delivered';
                                        }else{
                                            $uom = $this->super_model->select_column_where("pr_details", "uom","pr_details_id",$pr_details_id);
                                            $status = "PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_delivered_qty . " ".$uom .")</span><br>" . $statuss;
                                        }
                                    }
                                }else if($pr_qty > $sum_delivered_qty && $sum_delivered_qty < $sum_received_qty){
                                     $status = 'PO Issued - Partial <br> Partially Delivered';

                                } else if($sum_delivered_qty == $sum_received_qty || $po_qty == $po_rec_qty){
                                    $status = 'Fully Delivered';
                                }
                            }
                        }


    
                    }
              //  }

            } else if($pr_qty <= $sum_received_qty && $po_cancelled==0) {

                $status_remarks='';
                if($controller_name=='pr_report' && $po_rec_qty!=0){
                    foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id' AND date_received!='' AND quantity!='0'") AS $del){

                        $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                        $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

      
                         // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                         $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY."<br>";
                    }
                }else {
                    foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id' AND pdr.po_id = '$po_id' AND date_received!='' AND quantity!='0'") AS $del){

                        $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                        $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

              
                         // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                         $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY."<br>";
                    }
                }
                          
                $status = 'Fully Delivered';
            }

          
            $data=array(
                'status'=>$status,
                'remarks'=>$status_remarks
            ); 
              // echo $status . " - " . $status_remarks;
            return $data;
    }

    public function pr_item_status_export($pr_details_id,$controller_name,$po_id){
         /*
            $pr_details_id = '2754';
            $controller_name = 'pr_report';
            $po_id = '1523';
*/

            //echo $pr_details_id . " - " . $controller_name . " - " . $po_id . "<br>";
            $statuss='';
            $status='';
            $status_remarks='';
            $item_cancelled = $this->super_model->select_column_where("pr_details", "cancelled","pr_details_id",$pr_details_id); // check if item is cancelled
            $po_cancelled = $this->super_model->select_column_where("po_head", "cancelled","po_id",$po_id); // check if po is cancelled
            $item_on_hold = $this->super_model->select_column_where("pr_details", "on_hold","pr_details_id",$pr_details_id); // check if item is on hold
            $item_for_recom = $this->super_model->select_column_where("pr_details", "for_recom","pr_details_id",$pr_details_id); // check if item is for recom
            $pr_id = $this->super_model->select_column_where("pr_details", "pr_id","pr_details_id",$pr_details_id); // check if item is for recom
            $item_fulfilled_by_mnl = $this->super_model->select_column_where("pr_details", "fulfilled_by","pr_details_id",$pr_details_id); // check if item is fulfilled by MNL
          //  echo "SELECT rd.rfq_details_id FROM rfq_details rd INNER JOIN rfq_head rh WHERE rd.pr_details_id = '$pr_details_id' AND rh.saved = '1' AND rh.cancelled = '0'";
            $count_rfq = $this->super_model->count_custom_query("SELECT rd.rfq_details_id FROM rfq_details rd INNER JOIN rfq_head rh ON rd.rfq_id=rh.rfq_id WHERE rd.pr_details_id = '$pr_details_id' AND rh.saved = '1' AND rh.cancelled = '0'"); // check if item is already in the RFQ process

            $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' AND cancelled='0'"); // check if item is already in the AOQ process but not awarded

            $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'"); // check if item is already in the AOQ process but already awarded

            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '0' and draft = '0' AND pi.pr_details_id = '$pr_details_id'"); //checks if item has already PO but not yet served or delivered

            $count_po_served_draft = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '1'  and draft = '1' AND pi.pr_details_id = '$pr_details_id'"); //checks if item has already PO but already served or delivered

            $count_po_draft = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '0' and draft = '1' AND pi.pr_details_id = '$pr_details_id'"); //checks if item has already PO but not yet served or delivered

            $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '1'  and draft = '0' AND pi.pr_details_id = '$pr_details_id' AND ph.po_id = '$po_id'"); //checks if item has already PO but already served or delivered

            //$count_po_not_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '0'  and draft = '0' AND pi.pr_details_id = '$pr_details_id'");

            $pr_qty = $this->super_model->select_column_where("pr_details", "quantity","pr_details_id",$pr_details_id); // gets the PR qty of the item

            if($controller_name=='po_report'){
                // $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id' AND ph.po_id = '$po_id'"); // gets the total received qty of the item
                $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id' AND pi.pr_id = '$pr_id'"); // gets the total received qty of the item
            }else{
                $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id'");
            }

            
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id' AND ph.po_id = '$po_id'"); // gets the total delivered qty of the item


            $po_qty = $this->super_model->select_column_where("po_items","delivered_quantity","pr_details_id",$pr_details_id);
            $po_rec_qty = $this->super_model->select_column_where("po_items","quantity","pr_details_id",$pr_details_id);

           

            $draft = $this->super_model->select_column_where("po_head","draft","po_id",$po_id);

            $count_po_zero = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '1' AND pi.quantity='0' AND draft = '0' AND pi.pr_details_id = '$pr_details_id'");
            $count_po_served_zero = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0'  AND served = '1' AND pi.quantity='0'  AND draft = '0' AND pi.pr_details_id = '$pr_details_id' AND ph.po_id = '$po_id'");
               //echo $pr_details_id . " - rfq = " .$count_rfq. ", aoq = ".  $count_aoq . " , award = ".  $count_aoq_awarded . " ,po =  " . $count_po   . ", served = " . $count_po_served . " ,draft = ". $draft."<br>";

            /*$po_id = $this->super_model->select_column_where("po_items","po_id","pr_details_id",$pr_details_id);
            echo $pr_details_id." - ".$po_id."<br>";*/
            if(empty($sum_received_qty)){
                $sum_received_qty = 0;
            } else {
                $sum_received_qty = $sum_received_qty;
            }
           
         //  echo $po_id . ", ". $pr_details_id . " -  rfq = ". $count_rfq .", aoq = ". $count_aoq. ", awarded = ".  $count_aoq_awarded . ", po = ". $count_po . ", poserved = ". $count_po_served ."<br>"; 
                          

           //echo $pr_qty ." < " . $sum_received_qty;

            /*if($item_cancelled == 1 && $po_cancelled==0){
                if($controller_name=='pr_report'){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr_details_id);
                    $status= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                }
            }else if($po_cancelled==1 && $item_cancelled==0){
                if($controller_name=='po_report'){
                    $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                    $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                    $status= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                }
            } */

            if($pr_qty > $sum_received_qty || $sum_received_qty == 0){
            
                if($item_cancelled == 1 && $controller_name=='pr_report'){
                    //if($controller_name=='pr_report'){
                        $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr_details_id);
                        $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr_details_id);
                       
                        $status= "Cancelled";
                        //$status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                        $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    //}
                } else if($po_cancelled==1 && $controller_name=='po_report'){
                    //if($controller_name=='po_report'){
                        $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                        $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                        $status= "Cancelled";
                        //$status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                        $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                    //}
                } else {

               /*    
                    if($item_on_hold == 1){
                         $status = 'On-Hold';
                         $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                         $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
          
                        $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                        $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                    }*/

                    if($item_for_recom == 1){
                        $recom_date_from = $this->super_model->select_column_where("pr_details", "recom_date_from","pr_details_id",$pr_details_id); 
                        $recom_date_to = $this->super_model->select_column_where("pr_details", "recom_date_to","pr_details_id",$pr_details_id); 
                        $recom_by_id = $this->super_model->select_column_where("pr_details", "recom_by","pr_details_id",$pr_details_id); 
                        $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$recom_by_id);
                        $statuss = "-Recom By: ".$recom_by."\n -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                    }
                   
                    //if($count_po_served == 0){
                        
                       // echo $count_rfq ." aoq =  " . $count_aoq . ", award = ". $count_aoq_awarded . ", po = ".$count_po . ", served = ". $count_po_served;

                        if($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0){
                             
                             if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."\n -On Hold By: ".$onhold_by;
                                $status_remarks = 'For RFQ'."\n" . $statuss;
                             } else {
                                if($item_fulfilled_by_mnl == 1){
                                    $status = 'Delivered by CENPRI-MNL';
                                     $status_remarks = 'For RFQ'."\n" . $statuss;
                                } else {
                                    if($item_for_recom==0){
                                        $status = 'Pending';
                                    }else{
                                        $status = 'For Recom';
                                    }
                                    $status_remarks = 'For RFQ'."\n" . $statuss;
                                }
                                
                             }


                        } else if($count_rfq != 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0){
                             if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."\n -On Hold By: ".$onhold_by;
                                $status_remarks = 'Canvassing Ongoing'."\n" . $statuss;
                             } else {
                                 $status = 'Pending';
                                 $status_remarks = 'Canvassing Ongoing'."\n" . $statuss;
                            }
                            
                        } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0 && $draft!=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0 && $draft==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po == 0 && $count_po_served ==0 && $draft!=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po_draft != 0 && $count_po_served_draft ==0 && $draft!=0)){
                             if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."\n -On Hold By: ".$onhold_by;
                                $status_remarks = 'AOQ Done - For TE - '. $aoq_date ."\n" . $statuss;
                             } else {
                                 $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' ");
                                 $status = 'Pending';
                                 $status_remarks = 'AOQ Done - For TE - '. $aoq_date ."\n" . $statuss;

                            }
                          
                        } else if($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po == 0 && $count_po_served ==0){

                            if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("pr_details", "onhold_by","pr_details_id",$pr_details_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("pr_details", "onhold_date","pr_details_id",$pr_details_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."\n -On Hold By: ".$onhold_by;
                                $status_remarks = 'For PO - AOQ Done (awarded) '."\n" . $statuss;
                             } else {
                                 $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' ");
                                 $status = 'Pending';
                                 $status_remarks = 'For PO - AOQ Done (awarded)'."\n" . $statuss;

                            }


                          
                        } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0)) {
                            $uom = $this->super_model->select_column_where("pr_details", "uom","pr_details_id",$pr_details_id);
                            //echo $pr_qty ."<=". $sum_delivered_qty;
                            if($pr_qty < $sum_delivered_qty){
                                 $status = 'PO Issued - Partial';
                            } else {
                                 //$status = 'PO Issued';
                                 //$status = "PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_delivered_qty . " ".$uom .")</span>\n" . $statuss;
                                 $status = "PO Issued (". $sum_delivered_qty . " ".$uom .")\n" . $statuss;
                            }
                           
                        } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po_zero != 0 && $count_po_served_zero ==0) || ($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po_zero != 0 && $count_po_served_zero !=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po_zero != 0 && $count_po_served_zero !=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po_zero != 0 && $count_po_served_zero !=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po_zero != 0 && $count_po_served_zero !=0)) {
                            $uom = $this->super_model->select_column_where("pr_details", "uom","pr_details_id",$pr_details_id);
                            //echo $pr_qty ."<=". $sum_delivered_qty;
                            if($pr_qty < $sum_delivered_qty){
                                 $status = 'PO Issued - Partial';
                            } else {
                                 //$status = 'PO Issued';
                                 //$status = "PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_delivered_qty . " ".$uom .")</span>\n" . $statuss;
                                 $status = "PO Issued (". $sum_delivered_qty . " ".$uom .")\n" . $statuss;
                            }
                           
                        }else if($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po == 0 && $count_po_served !=0 && $draft==0 || $count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served !=0 && $draft==0 || $count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served !=0 && $draft==0 || $count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0 && $count_po_served !=0 && $draft==0){
                            $status_remarks='';
                            if($controller_name=='pr_report' && $po_rec_qty!=0){
                                foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id'AND date_received!='' AND quantity!='0'") AS $del){
                                    //if($po_rec_qty!=0){
                                        // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                    $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                                    $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

                                        //$status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span>\n";
                                        $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY." (". $del->quantity . " ".$del->uom .")\n";
                                    //}else{
                                       // $status_remarks.='';
                                    //}
                                }
                            }else{
                                foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id' AND pdr.po_id = '$po_id' AND date_received!='' AND quantity!='0'") AS $del){
                                    //if($po_rec_qty!=0){
                                    $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                                    $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

                                        // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                        //$status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span>\n";
                                        $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY." (". $del->quantity . " ".$del->uom .")\n";
                                    //}else{
                                       // $status_remarks.='';
                                    //}
                                }
                            }
                            //echo $sum_delivered_qty ."<". $sum_received_qty;
                            if($controller_name=='po_report'){
                                if($sum_delivered_qty > $sum_received_qty){
                                    //$status="Partially Delivered";
                                    if($controller_name=='po_report'){
                                        if($po_qty != $sum_received_qty){
                                            $status = 'Partially Delivered';
                                        }else{
                                            $status = 'Fully Delivered';
                                        }
                                    }else{
                                        $status = 'Partially Delivered';
                                    }
                                }else if($pr_qty > $sum_delivered_qty && $sum_delivered_qty < $sum_received_qty){
                                     $status = 'PO Issued - Partial' ."\n". 'Partially Delivered';

                                } else if($sum_delivered_qty == $sum_received_qty || $po_qty == $po_rec_qty){
                                    $status = 'Fully Delivered';
                                }
                            }else{
                                if($sum_delivered_qty < $sum_received_qty || $pr_qty > $sum_received_qty){
                                    if($controller_name=='po_report'){
                                        if($po_qty != $sum_received_qty){
                                            $status = 'Partially Delivered';
                                        }else{
                                            $status = 'Fully Delivered';
                                        }
                                    }else {
                                        //if($po_rec_qty!=0){
                                        if($pr_qty != $sum_received_qty){
                                            $status = 'Partially Delivered';
                                        }else if($pr_qty == $sum_received_qty){
                                            $status = 'Fully Delivered';
                                        }else{
                                            $uom = $this->super_model->select_column_where("pr_details", "uom","pr_details_id",$pr_details_id);
                                            //$status = "PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_delivered_qty . " ".$uom .")</span>\n" . $statuss;
                                            $status = "PO Issued (". $sum_delivered_qty . " ".$uom .")\n" . $statuss;
                                        }
                                    }
                                }else if($pr_qty > $sum_delivered_qty && $sum_delivered_qty < $sum_received_qty){
                                     $status = 'PO Issued - Partial' ."\n". 'Partially Delivered';

                                } else if($sum_delivered_qty == $sum_received_qty || $po_qty == $po_rec_qty){
                                    $status = 'Fully Delivered';
                                }
                            }
                        }


    
                    }
              //  }

            } else if($pr_qty <= $sum_received_qty && $po_cancelled==0) {

                $status_remarks='';
                if($controller_name=='pr_report' && $po_rec_qty!=0){
                    foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id' AND date_received!='' AND quantity!='0'") AS $del){

                        $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                        $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

      
                         // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                         $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY."\n";
                    }
                }else {
                    foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id' AND pdr.po_id = '$po_id' AND date_received!='' AND quantity!='0'") AS $del){

                        $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                        $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

              
                         // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                         $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY."\n";
                    }
                }
                          
                $status = 'Fully Delivered';
            }

          
            $data=array(
                'status'=>$status,
                'remarks'=>$status_remarks
            ); 
              // echo $status . " - " . $status_remarks;
            return $data;
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
            if($this->super_model->update_where("pr_details", $data_proceed, "pr_details_id",$proceed)){
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

        /*if(!empty($this->input->post('date_receive'))){
            $data['date_receive'] = $this->input->post('date_receive');
        } else {
            $data['date_receive']= "null";
        }*/

        if(!empty($this->input->post('date_receive_from'))){
            $data['date_receive_from'] = $this->input->post('date_receive_from');
        } else {
            $data['date_receive_from']= "null";
        }

        if(!empty($this->input->post('date_receive_to'))){
            $data['date_receive_to'] = $this->input->post('date_receive_to');
        } else {
            $data['date_receive_to']= "null";
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
        $date_from = $this->input->post('date_receive_from');
        $date_to = $this->input->post('date_receive_to');
        if(!empty($this->input->post('date_receive_from')) && ($this->input->post('date_receive_to'))){
            $date_receive = $this->input->post('date_receive');
            $sql.="ph.date_prepared BETWEEN '$date_from' AND '$date_to' AND";
            $filter .= "Date Received/Emailed From - ".$date_from." Date Received/Emailed To - ".$date_to.", ";
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
            $filter .= "Pr No. - ".$pr_no."-".COMPANY.", ";
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
        $controller_name='pr_report';
        $data['pr_no_1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['company']=$this->super_model->select_all_order_by("company","company_name","ASC");
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        $data['terms']=$this->super_model->select_all_order_by("terms","terms","ASC");
        $data['proj_act']=$this->super_model->select_custom_where("project_activity","status='Active' ORDER BY proj_activity ASC");
        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ".$query) AS $pr){
            $count_number_po = $this->super_model->count_custom_where("po_items", "pr_details_id = '$pr->pr_details_id'");
           
            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $pr->pr_details_id);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $pr->pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr->pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            if($count_number_po > 1){
           // echo $pr->pr_details_id . " = " . $count_number_po . "<br>";
               $po_id = $this->super_model->select_column_innerjoin_where("po_id", "po_items","po_head", "pr_details_id='$pr->pr_details_id' AND cancelled!='1'","po_id");
            } else {

            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $pr->pr_details_id, "po_id", "DESC", "1");
            }
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->onhold_by);
            $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr->pr_details_id);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr->pr_details_id);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr->pr_details_id);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr->pr_details_id);
     
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

        
          
         
            $unserved_qty=0;
            $unserved_uom='';  
            /********************** START STATUS ******************/
         
            $stat = $this->pr_item_status($pr->pr_details_id,$controller_name,$po_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
        
            /********************** END STATUS ********************/
            $revised='';
            $current_qty='';
            $revision_no = $this->super_model->select_column_where("po_head","revision_no","po_id",$po_id);
            if($revision_no!=0){
                foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM po_items_revised WHERE po_id = '$po_id' AND pr_details_id = '$pr->pr_details_id' GROUP BY revision_no") AS $rev){
                    if($rev->revision_no == 0){
                         $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                    } else {
                         $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                         $qty = $this->super_model->select_column_custom_where("po_items","delivered_quantity","po_id='$po_id' AND pr_details_id='$pr->pr_details_id'");
                         $uom = $this->super_model->select_column_custom_where("po_items","uom","po_id='$po_id' AND pr_details_id='$pr->pr_details_id'");
                         $current_qty = "Current Qty: ".$qty." ".$uom;
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
                'current_qty'=>$current_qty,
                'date_needed'=>$pr->date_needed,
                'unserved_qty'=>$unserved_qty,
                'unserved_uom'=>$unserved_uom,
                'remarks'=>$pr->add_remarks,
                'company'=>$this->super_model->select_column_where('company','company_name','company_id',$pr->company_id),
                'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$pr->vendor_id),
                'company_id'=>$pr->company_id,
                'supplier_id'=>$pr->vendor_id,
                'date_delivered'=>$pr->date_delivered,
                'unit_price'=>$pr->unit_price,
                'qty_delivered'=>$pr->qty_delivered,
                'cancel_remarks'=>$pr->cancel_remarks,
                'fulfilled_by'=>$pr->fulfilled_by,
                'for_recom'=>$this->super_model->select_column_where('users','fullname','user_id',$pr->for_recom),
                'recom_by'=>$pr->recom_by,
                'recom_qty'=>$pr->recom_qty,
                'recom_date_from'=>$pr->recom_date_from,
                'recom_date_to'=>$pr->recom_date_to,
                 'current_qty'=>$current_qty,
                'cancelled'=>$pr->cancelled,
                'recom_unit_price'=>$recom_unit_price,
                'ver_date_needed'=>$ver_date_needed,
                'cancelled_items_po'=>$cancelled_items_po,
                'on_hold'=>$pr->on_hold,
                'pr_calendar_id'=>$pr_calendar_id,
                'estimated_price'=>$estimated_price,
                'proj_act_id'=>$proj_act_id,
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

        $controller_name='pr_report';
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
        
        //$date_received=$this->uri->segment(5);
        $date_receive_from=$this->uri->segment(5);
        $date_receive_to=$this->uri->segment(6);
        $purpose=str_replace("%20", " ", $this->uri->segment(7));
        $enduse=str_replace("%20", " ", $this->uri->segment(8));
        $pr_no=$this->uri->segment(9);
        $requestor=str_replace("%20", " ", $this->uri->segment(10));
        $description=str_replace("%20", " ", $this->uri->segment(11));
        $purchase_request=str_replace("%20", " ", $this->uri->segment(12));

        $sql="";
        $filter = " ";

        /*if($date_received!='null'){
            $sql.=" ph.date_prepared LIKE '%$date_received%' AND";
            $filter .= $date_received;
        }*/

        if($date_receive_from!='null' && $date_receive_to!='null'){
           $sql.= " ph.date_prepared BETWEEN '$date_receive_from' AND '$date_receive_to' AND";
           $filter .= $date_receive_from ."-". $date_receive_to;
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


        //echo $query;
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
                            $count_number_po = $this->super_model->count_custom_where("po_items", "pr_details_id = '$pr->pr_details_id'");
           
            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $pr->pr_details_id);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $pr->pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr->pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            if($count_number_po > 1){
           // echo $pr->pr_details_id . " = " . $count_number_po . "<br>";
               $po_id = $this->super_model->select_column_innerjoin_where("po_id", "po_items","po_head", "pr_details_id='$pr->pr_details_id' AND cancelled!='1'","po_id");
            } else {

            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $pr->pr_details_id, "po_id", "DESC", "1");
            }
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->onhold_by);
            $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr->pr_details_id);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr->pr_details_id);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr->pr_details_id);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr->pr_details_id);
     
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

        
          
         
            $unserved_qty=0;
            $unserved_uom='';  
            /********************** START STATUS ******************/
         
            $stat = $this->pr_item_status_export($pr->pr_details_id,$controller_name,$po_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
        
            /********************** END STATUS ********************/
            $revised='';
            $current_qty='';
            $revision_no = $this->super_model->select_column_where("po_head","revision_no","po_id",$po_id);
            if($revision_no!=0){
                foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM po_items_revised WHERE po_id = '$po_id' AND pr_details_id = '$pr->pr_details_id' GROUP BY revision_no") AS $rev){
                    if($rev->revision_no == 0){
                         $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."\n";
                    } else {
                         $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."\n";
                         $qty = $this->super_model->select_column_custom_where("po_items","delivered_quantity","po_id='$po_id' AND pr_details_id='$pr->pr_details_id'");
                         $uom = $this->super_model->select_column_custom_where("po_items","uom","po_id='$po_id' AND pr_details_id='$pr->pr_details_id'");
                         $current_qty = "Current Qty: ".$qty." ".$uom;
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

                $revise_qty = $revised."\n".$current_qty;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->date_prepared");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$pr->purchase_request");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$pr->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$pr->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$pr->wh_stocks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$pr->item_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$pr->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$revise_qty");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$pr->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$pr->grouping_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$pr->item_description $unserved");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$ver_date_needed");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$estimated_price");
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
                            $count_number_po = $this->super_model->count_custom_where("po_items", "pr_details_id = '$pr->pr_details_id'");
           
            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $pr->pr_details_id);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $pr->pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr->pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            if($count_number_po > 1){
           // echo $pr->pr_details_id . " = " . $count_number_po . "<br>";
               $po_id = $this->super_model->select_column_innerjoin_where("po_id", "po_items","po_head", "pr_details_id='$pr->pr_details_id' AND cancelled!='1'","po_id");
            } else {

            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $pr->pr_details_id, "po_id", "DESC", "1");
            }
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->onhold_by);
            $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr->pr_details_id);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr->pr_details_id);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr->pr_details_id);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr->pr_details_id);
     
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr->pr_details_id'");

        
          
         
            $unserved_qty=0;
            $unserved_uom='';  
            /********************** START STATUS ******************/
         
            $stat = $this->pr_item_status_export($pr->pr_details_id,$controller_name,$po_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
        
            /********************** END STATUS ********************/
            $revised='';
            $current_qty='';
            $revision_no = $this->super_model->select_column_where("po_head","revision_no","po_id",$po_id);
            if($revision_no!=0){
                foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM po_items_revised WHERE po_id = '$po_id' AND pr_details_id = '$pr->pr_details_id' GROUP BY revision_no") AS $rev){
                    if($rev->revision_no == 0){
                         $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."\n";
                    } else {
                         $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."\n";
                         $qty = $this->super_model->select_column_custom_where("po_items","delivered_quantity","po_id='$po_id' AND pr_details_id='$pr->pr_details_id'");
                         $uom = $this->super_model->select_column_custom_where("po_items","uom","po_id='$po_id' AND pr_details_id='$pr->pr_details_id'");
                         $current_qty = "Current Qty: ".$qty." ".$uom;
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
                $revise_qty = $revised."\n".$current_qty;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->date_prepared");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$pr->purchase_request");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$pr->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$pr->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$pr->wh_stocks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$pr->item_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$pr->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$revise_qty");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$pr->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$pr->grouping_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$pr->item_description $unserved");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$ver_date_needed");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$estimated_price");
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
        $controller_name=$this->uri->segment(2);
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
                        $item=$i->offer;
                    }

               
                    $requestor = $pr->requestor;
                    /*if($p->cancelled ==1){
                        $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
                    } else {
                        
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
                    }*/
                    $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $i->pr_details_id);
                    $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $i->pr_details_id);
                    $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $i->pr_details_id);
                    if($po_offer_id==0){
                        $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                    }else{
                        $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                    }
                    //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                    $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $i->pr_details_id, "po_id", "DESC", "1");
                    $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                    $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '1' AND pi.pr_details_id = '$i->pr_details_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $unserved_qty=0;
                    $unserved_uom='';  

                    $stat = $this->pr_item_status($i->pr_details_id,$controller_name,$i->po_id);
                    $status = $stat['status'];
                    $status_remarks = $stat['remarks'];
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
                        'status_remarks'=>$status_remarks,
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
        $controller_name='po_report';
        $po_date = date('Y-m', strtotime($date));
        $data['pr_no1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->custom_query("SELECT * FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id INNER JOIN po_pr pp ON pi.po_id = pp.po_id WHERE ".$query." GROUP BY ph.po_id") AS $p){
            $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id);
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
            foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                if($i->item_id!=0){
                    foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                        $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                        $item=$it->item_name." - ".$it->item_specs;
                    }
                }else {
                    $item=$i->offer;
                }

       
            $requestor = $p->requestor;
            /*if($p->cancelled ==1){
                $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
            } else {
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
            }*/
            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $i->pr_details_id);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $i->pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $i->pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $i->pr_details_id, "po_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '0' AND pi.pr_details_id = '$i->pr_details_id'");
            $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '1' AND pi.pr_details_id = '$i->pr_details_id'");
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
            $unserved_qty=0;
            $unserved_uom='';  

            $stat = $this->pr_item_status($i->pr_details_id,$controller_name,$i->po_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
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
                'status_remarks'=>$status_remarks,
                'supplier'=>$supplier,
                'terms'=>$terms,
                'served'=>$p->served,
                'cancelled'=>$p->cancelled,
                );
            }
        }
        $this->load->view('template/header');        
        $this->load->view('reports/po_report',$data);
        $this->load->view('template/footer');
    }

    public function export_po(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="PO Summary.xlsx";
        $controller_name="po_report";
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Status Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Payment Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P4', "Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q4', "Remarks");
        foreach(range('A','Q') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:Q4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:Q4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:Q4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT * FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id INNER JOIN po_pr pp ON pi.po_id = pp.po_id  WHERE ".$query." GROUP BY ph.po_id") AS $p){
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
                foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                    if($i->item_id!=0){
                        foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                            $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                            $item=$it->item_name." - ".$it->item_specs;
                        }
                    }else {
                        $item=$i->offer;
                    }

           
                $requestor = $p->requestor;
                /*if($p->cancelled ==1){
                    $status = "Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason;
                } else {
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
                }*/
                $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $i->pr_details_id);
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $i->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $i->pr_details_id);
                if($po_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $i->pr_details_id, "po_id", "DESC", "1");
                $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '1' AND pi.pr_details_id = '$i->pr_details_id'");
                $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $unserved_qty=0;
                $unserved_uom='';  

                $stat = $this->pr_item_status_export($i->pr_details_id,$controller_name,$i->po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                /*if($p->served=='1' && $p->delivered_quantity > $p->quantity){ 
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }else if($p->served=='1'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                } else if($p->cancelled=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='PO Issued') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }*/

                $po_issue=$this->like($status, "PO Issued");
                if($status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($po_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
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
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$status_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$supplier");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$terms");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$p->currency $p->unit_price");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$total");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$p->notes");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setWrapText(true);
                $num++;
                }
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
                            /*if($p->cancelled ==1){
                                $status = "Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason;
                            } else {
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
                            }*/
                            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $i->pr_details_id);
                            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $i->pr_details_id);
                            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $i->pr_details_id);
                            if($po_offer_id==0){
                                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                            }else{
                                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                            }
                            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $i->pr_details_id, "po_id", "DESC", "1");
                            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                            $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '1' AND pi.pr_details_id = '$i->pr_details_id'");
                            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                            $unserved_qty=0;
                            $unserved_uom='';
                            $stat = $this->pr_item_status_export($i->pr_details_id,$controller_name,$i->po_id);
                            $status = $stat['status'];
                            $status_remarks = $stat['remarks'];
                        $styleArray = array(
                            'borders' => array(
                                'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        );
                        /*if($p->served=='1' && $i->delivered_quantity > $i->quantity){ 
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                        }else if($p->served=='1'){
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                        } else if($p->cancelled=='1') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($status=='PO Issued') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                        }*/
                        //$partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$pr->pr_id' AND ai.aoq_items_id = '$i->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
                        $po_issue=$this->like($status, "PO Issued");
                        if($status=='Fully Delivered'){
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                        } else if($status=='Partially Delivered') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                        } else if($status=='Cancelled') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($status=='Partially Delivered / Cancelled') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($po_issue=='1') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                        }
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
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$status_remarks");
                        /*if($partial==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Partially Served");
                        }else if($p->saved==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Fully Served");
                        }else if($p->cancelled==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Cancelled");
                        }*/
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$supplier");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$terms");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$i->currency $i->unit_price");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$total");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$pr->notes");
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->applyFromArray($styleArray);
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
        $fulfilled_by =$this->input->post('fulfilled');
        
        if($fulfilled_by==''){
        $data=array(
            'date_delivered'=>$date,
            'vendor_id'=>$supp,
            'unit_price'=>$unit_price,
            'qty_delivered'=>$qty_delivered,
            'fulfilled_by'=>1,
            'company_id'=>$comp,
        );
        $this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id);{
            echo "<script>alert('Successfully Added!'); window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
        }
    }else{
        $data=array(
            'date_delivered'=>$date,
            'vendor_id'=>$supp,
            'unit_price'=>$unit_price,
            'qty_delivered'=>$qty_delivered,
            'company_id'=>$comp,
        );
        $this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id);{
            echo "<script>alert('Successfully Added!'); window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
    }
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
        $terms =$this->input->post('terms');
        $work_duration=$this->input->post('work_duration');
        $recom_unit_price=$this->input->post('recom_unit_price');
        $recom_qty=$this->input->post('recom_qty');
        

        $data=array(
            'recom_date_from'=>$recom_date_from,
            'recom_date_to'=>$recom_date_to,
            'for_recom'=>1,
            'recom_date'=>$recom_date,
            'terms_id'=>$terms,
            'work_duration'=>$work_duration,
            'recom_unit_price'=>$recom_unit_price,
            'recom_qty'=>$recom_qty,
            'recom_by'=>$_SESSION['user_id']


        );
        $this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id);
    {
            echo "<script>alert('Successfully Added!'); window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
        }
    }

    public function unserved_report(){
        $controller_name="po_report";
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
                    /*if($p->cancelled ==1){
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
                    }*/
                    $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $i->pr_details_id);
                    $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $i->pr_details_id);
                    $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $i->pr_details_id);
                    if($po_offer_id==0){
                        $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                    }else{
                        $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                    }
                    //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                    $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $i->pr_details_id, "po_id", "DESC", "1");
                    $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                    $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '1' AND pi.pr_details_id = '$i->pr_details_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                    //$unserved_qty=0;
                    $unserved_uom='';  
                    $stat = $this->pr_item_status($i->pr_details_id,$controller_name,$i->po_id);
                    $status = $stat['status'];
                    $status_remarks = $stat['remarks'];
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
                            'status_remarks'=>$status_remarks,
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
        $controller_name='po_report';
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
            /*if($p->cancelled ==1){
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
            }*/
            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $p->pr_details_id);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $p->pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $p->pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $p->pr_details_id, "po_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$p->pr_id' AND served = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$p->pr_id' AND served = '1' AND pi.pr_details_id = '$p->pr_details_id'");
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            $unserved_qty=0;
            $unserved_uom='';  
            $stat = $this->pr_item_status($p->pr_details_id,$controller_name,$p->po_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
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
                    'status_remarks'=>$status_remarks,
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
        $controller_name='po_report';
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Status Remarks");
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
                /*if($p->cancelled ==1){
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
                }*/
                $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $i->pr_details_id);
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $i->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $i->pr_details_id);
                if($po_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $i->pr_details_id, "po_id", "DESC", "1");
                $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '1' AND pi.pr_details_id = '$i->pr_details_id'");
                $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                $unserved_qty=0;
                $unserved_uom='';  
                $stat = $this->pr_item_status_export($pr->pr_details_id,$controller_name,$i->po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $total = $unserved_qty * $p->unit_price;
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                $po_issue=$this->like($status, "PO Issued");
                if($status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($po_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }
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
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$status_remarks");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$supplier");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$terms");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$p->unit_price");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$total");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$p->notes");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->applyFromArray($styleArray);
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
                        /*if($p->cancelled ==1){
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
                        }*/
                        $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $i->pr_details_id);
                        $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $i->pr_details_id);
                        $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $i->pr_details_id);
                        if($po_offer_id==0){
                            $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                        }else{
                            $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                        }
                        //$po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
                        $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $i->pr_details_id, "po_id", "DESC", "1");
                        $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
                        $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                        $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                        $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                        $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$i->pr_id' AND served = '1' AND pi.pr_details_id = '$i->pr_details_id'");
                        $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                        $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$i->pr_details_id'");
                        $unserved_qty=0;
                        $unserved_uom='';  
                        $stat = $this->pr_item_status_export($i->pr_details_id,$controller_name,$i->po_id);
                        $status = $stat['status'];
                        $status_remarks = $stat['remarks'];
                        $po_issue=$this->like($status, "PO Issued");
                        if($status=='Fully Delivered'){
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                        } else if($status=='Partially Delivered') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                        } else if($status=='Cancelled') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($status=='Partially Delivered / Cancelled') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($po_issue=='1') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":Q".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
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
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$status_remarks");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$supplier");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$terms");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$i->unit_price");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$total");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$pr->notes");
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->applyFromArray($styleArray);
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

    public function add_remarks_weekly(){
        $po_offer_id =$this->input->post('po_offer_id');
        $pr_id =$this->input->post('pr_id');
        $pr_details_id =$this->input->post('pr_details_id');
        $status =$this->input->post('status');
        $recom_date_from =$this->input->post('recom_date_from');
        $recom_date_to =$this->input->post('recom_date_to');
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
                redirect(base_url().'reports/sum_weekly_recom/'.$recom_date_from.'/'.$recom_date_to);
            
        } else {
            echo "<script>alert('You can only use this cancel button for partially delivered items.');
            window.location = '".base_url()."reports/sum_weekly_recom/".$recom_date_from."/".$recom_date_to."';</script>";
        }
    }

    public function insert_changestatus_weekly(){
        $count_onhold = count($this->input->post('onhold'));
        $count_proceed = count($this->input->post('proceed'));
        $date_recom_from = $this->input->post('date_recom_from');
        $date_recom_to = $this->input->post('date_recom_to');
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
                echo "<script>window.location = '".base_url()."reports/sum_weekly_recom/".$date_recom_from."/".$date_recom_to."';</script>";
            }
        }

        for($y=0;$y<$count_proceed;$y++){
            $proceed = $this->input->post('proceed['.$y.']');
            $data_proceed=array(
                'on_hold'=>0,
            );
            if($this->super_model->update_where("pr_details", $data_proceed, "pr_details_id",$proceed)){
                echo "<script>alert('Successfully Changed Status!');</script>"; 
                echo "<script>window.location = '".base_url()."reports/sum_weekly_recom/".$date_recom_from."/".$date_recom_to."';</script>";
            }
        }
    }

    public function sum_weekly_recom(){
        $controller_name='pr_report';
        $date_from=$this->uri->segment(3);
        $date_to=$this->uri->segment(4);  
        $data['recom_date_from']=$date_from;
        $data['recom_date_to']=$date_to;
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $count_sum_weekly_recom = $this->super_model->count_custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$date_from' AND '$date_to' AND pd.recom_date_to BETWEEN '$date_from' AND '$date_to' AND pd.for_recom='1'");
        if($count_sum_weekly_recom!=0){
            foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$date_from' AND '$date_to' AND pd.recom_date_to BETWEEN '$date_from' AND '$date_to' AND pd.for_recom='1'") AS $p){
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $p->recom_unit_price;
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $p->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $p->pr_details_id);
                if($po_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $p->pr_details_id, "po_id", "DESC", "1");
                $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$p->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $stat = $this->pr_item_status($p->pr_details_id,$controller_name,$po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                if($served==0 && $cancelled_items_po==0){
                    $data['weekly_recom'][]=array(
                        'enduse'=>$p->enduse,
                        'requestor'=>$p->requestor,
                        'quantity'=>$p->quantity,
                        'recom_qty'=>$p->recom_qty,
                        'uom'=>$p->uom,
                        'item_description'=>$p->item_description,
                        'supplier'=>$supplier,
                        'status'=>$status,
                        'status_remarks'=>$status_remarks,
                        'pr_no'=>$p->pr_no,
                        'terms'=>$this->super_model->select_column_where('terms','terms',"terms_id",$p->terms_id),
                        'recom_unit_price'=>$p->recom_unit_price,
                        'work_duration'=>$p->work_duration,
                        'recom_date'=>$p->recom_date,
                        'recom_date_from'=>$p->recom_date_from,
                        'recom_date_to'=>$p->recom_date_to,
                        'on_hold'=>$p->on_hold,
                        'pr_details_id'=>$p->pr_details_id,
                        'total'=>$total,
                        'po_offer_id'=>$po_offer_id,
                        'remarks'=>$p->add_remarks,
                        'pr_id'=>$p->pr_id,
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

        /*if($recom_date_from!='null'){
            $sql.=" pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_from.", ";
        }

        if($recom_date_to!='null'){
            $sql.=" pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_to.", ";
        }*/

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
        $controller_name='pr_report';
        $date = $recom_date_from."-".$recom_date_to;
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        $count_search_weekly = $this->super_model->count_custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE $query AND pd.for_recom='1'");
        if($count_search_weekly!=0){
        foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE $query AND pd.for_recom='1'") AS $p){
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $p->recom_unit_price;
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $p->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $p->pr_details_id);
                if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $p->pr_details_id, "po_id", "DESC", "1");
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$p->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                $stat = $this->pr_item_status($p->pr_details_id,$controller_name,$po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                if($served==0 && $cancelled_items_po==0){
                $data['weekly_recom'][]=array(
                    'enduse'=>$p->enduse,
                    'requestor'=>$p->requestor,
                    'quantity'=>$p->quantity,
                    'recom_qty'=>$p->recom_qty,
                    'uom'=>$p->uom,
                    'item_description'=>$p->item_description,
                    'supplier'=>$supplier,
                    'status_remarks'=>$status_remarks,
                    'status'=>$status,
                    'pr_no'=>$p->pr_no,
                    'terms'=>$this->super_model->select_column_where('terms','terms',"terms_id",$p->terms_id),
                    'recom_unit_price'=>$p->recom_unit_price,
                    'work_duration'=>$p->work_duration,
                    'recom_date'=>$p->recom_date,
                    'recom_date_from'=>$p->recom_date_from,
                    'recom_date_to'=>$p->recom_date_to,
                    'on_hold'=>$p->on_hold,
                    'pr_details_id'=>$p->pr_details_id,
                    'total'=>$total,
                    'po_offer_id'=>$po_offer_id,
                    'remarks'=>$p->add_remarks,
                    'pr_id'=>$p->pr_id,
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
        $controller_name='pr_report';
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "MATERIALS RECOMMENDATION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', "$date");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Recom Date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "Recom Date From");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "Recom Date To");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "End-use");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "Requested by:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', "QTY as per PR");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "QTY as per Recom");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', "UoM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J5', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "Status Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L5', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M5', "Site PR/JO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N5', "Delivery Lead Time / Work Duration");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O5', "UNIT PRICE (PESO)");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P5', "TOTAL PESO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q5', "15 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R5', "30 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S5', "60 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T5', "TERMS");
        foreach(range('A','T') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('E2:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E2:E3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:T5')->applyFromArray($styleArray1);
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
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $p->recom_unit_price;
                $terms =  $this->super_model->select_column_where('terms','terms','terms_id',$p->terms_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$p->pr_details_id' AND recommended='1'");
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $p->pr_details_id, "po_id", "DESC", "1");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $total_array[] = $p->quantity * $p->recom_unit_price;
                $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                $stat = $this->pr_item_status($p->pr_details_id,$controller_name,$po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $total_peso = array_sum($total_array);
                $po_issue=$this->like($status, "PO Issued");
                $delivered_by=$this->like($status, "Delivered by");
                if($served==0 && $cancelled_items_po==0){
                    if($status=='Fully Delivered'){
                       $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                    } else if($status=='Partially Delivered') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                    } else if($status=='Cancelled') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                        $objPHPExcel->getActiveSheet()->getStyle("L".$num)->getFont()->getColor()->setRGB('ff0000');
                    }else if($status=='Partially Delivered / Cancelled') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                        $objPHPExcel->getActiveSheet()->getStyle("L".$num)->getFont()->getColor()->setRGB('ff0000');
                    }else if($status=='For Recom') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('fd9c77');
                    }else if($status=='On-Hold') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('d2deff');
                    }else if($po_issue=='1') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                    }else if($delivered_by=='1') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('eeccff');
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$p->recom_date");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->recom_date_from");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->recom_date_to");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->enduse");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->requestor");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$p->quantity");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->recom_qty");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->uom");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$p->item_description");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$supplier");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$status_remarks");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$status");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$p->pr_no");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$p->work_duration");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$p->recom_unit_price");
                    if($terms!="15 days PDC" || $terms!="30 days PDC" || $terms!="60 days PDC" || $terms==""){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "0.00");
                    }

                    if($terms=="15 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "0.00");
                    }

                    if($terms=="30 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "0.00");
                    }

                    if($terms=="60 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "0.00");
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$terms");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":S".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":S".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->applyFromArray($styleArray);
                    $num++; 

                    $disp_terms[] = array(
                        'terms'=>$terms,
                    );
                }
            }
            $a = $num+1;
            $b = $num;
            foreach($disp_terms AS $var=>$key){
                if($key['terms']!="15 days PDC" || $key['terms']!="30 days PDC" || $key['terms']!="60 days PDC" || $key['terms']==""){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$b, $total_peso);
                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$b, "0.00");
                }
                if($key['terms']=="15 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$b, $total_peso);
                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$b, "0.00");
                }

                if($key['terms']=="30 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$b, $total_peso);
                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$b, "0.00");
                }

                if($key['terms']=="60 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$b, $total_peso);
                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$b, "0.00");
                }
            }
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$a, "Total (in PESO)");
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$a, $total_peso);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$a.":O".$a)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('O'.$a)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('P'.$b.":S".$b)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                
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
                $terms =  $this->super_model->select_column_where('terms','terms','terms_id',$p->terms_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$p->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $p->recom_unit_price;
                if($terms!="15 days PDC" || $terms!="30 days PDC" || $terms!="60 days PDC" || $terms==""){
                    $total_array[] = $p->quantity * $p->recom_unit_price;
                    $total_peso = array_sum($total_array);
                }
                if($terms=="15 days PDC"){
                    $total_array15[] = $p->quantity * $p->recom_unit_price;
                    $total_peso15 = array_sum($total_array15);
                }

                if($terms=="30 days PDC"){
                    $total_array30[] = $p->quantity * $p->recom_unit_price;
                    $total_peso30 = array_sum($total_array30);
                }

                if($terms=="60 days PDC"){
                    $total_array60[] = $p->quantity * $p->recom_unit_price;
                    $total_peso60 = array_sum($total_array60);
                }
                $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id', $p->pr_details_id);
                $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $p->pr_details_id);
                if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
                }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
                }
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $p->pr_details_id, "po_id", "DESC", "1");
                $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                $stat = $this->pr_item_status_export($p->pr_details_id,$controller_name,$po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $po_issue=$this->like($status, "PO Issued");
                $delivered_by=$this->like($status, "Delivered by");
                if($served==0 && $cancelled_items_po==0){
                    if($status=='Fully Delivered'){
                       $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                    } else if($status=='Partially Delivered') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                    } else if($status=='Cancelled') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                        $objPHPExcel->getActiveSheet()->getStyle("L".$num)->getFont()->getColor()->setRGB('ff0000');
                    }else if($status=='Partially Delivered / Cancelled') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                        $objPHPExcel->getActiveSheet()->getStyle("L".$num)->getFont()->getColor()->setRGB('ff0000');
                    }else if($status=='For Recom') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('fd9c77');
                    }else if($status=='On-Hold') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('d2deff');
                    }else if($po_issue=='1') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                    }else if($delivered_by=='1') {
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('eeccff');
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$p->recom_date");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->recom_date_from");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->recom_date_to");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->enduse");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->requestor");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$p->quantity");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->recom_qty");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->uom");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$p->item_description");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$supplier");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$status_remarks");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$status");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$p->pr_no");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$p->work_duration");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$p->recom_unit_price");
                    if($terms!="15 days PDC" || $terms!="30 days PDC" || $terms!="60 days PDC" || $terms==""){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "0.00");
                    }

                    if($terms=="15 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "0.00");
                    }

                    if($terms=="30 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "0.00");
                    }

                    if($terms=="60 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "0.00");
                    }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$terms");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":S".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num.":S".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->applyFromArray($styleArray);
                $num++;

                $disp_terms[] = array(
                    'terms'=>$terms,
                );
            }
        }
            $a = $num+1;
            $b = $num;
            foreach($disp_terms AS $var=>$key){
                if($key['terms']!="15 days PDC" || $key['terms']!="30 days PDC" || $key['terms']!="60 days PDC" || $key['terms']==""){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$b, $total_peso);
                }
                if($key['terms']=="15 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$b, $total_peso15);
                }

                if($key['terms']=="30 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$b, $total_peso30);
                }

                if($key['terms']=="60 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$b, $total_peso60);
                }
            }

            $objPHPExcel->getActiveSheet()->setCellValue('M'.$a, "Total (in PESO)");
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$a, $total_peso);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$a.":O".$a)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('O'.$a)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('P'.$b.":S".$b)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

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
            $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
            $total = $p->quantity * $p->recom_unit_price;
            $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$p->pr_details_id' AND recommended='1'");
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$p->pr_id' AND served = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            if($count_po==0){
                $data['pending_weekly_recom'][]=array(
                    'enduse'=>$p->enduse,
                    'requestor'=>$p->requestor,
                    'quantity'=>$p->quantity,
                    'recom_qty'=>$p->recom_qty,
                    'uom'=>$p->uom,
                    'item_description'=>$p->item_description,
                    'supplier'=>$supplier,
                    'pr_no'=>$p->pr_no,
                    'terms'=>$this->super_model->select_column_where('terms','terms',"terms_id",$p->terms_id),
                    'recom_unit_price'=>$p->recom_unit_price,
                    'work_duration'=>$p->work_duration,
                    'total'=>$total
                );
            }else{
                $data['pending_weekly_recom']=array();
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

        /*if($recom_date_from!='null'){
            $sql.=" pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_from.", ";
        }

        if($recom_date_to!='null'){
            $sql.=" pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND";
            $filter .= $recom_date_to.", ";
        }*/

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
        $count_search_pending_weekly = $this->super_model->count_custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.for_recom='1' AND $query");
        if($count_search_pending_weekly!=0){
        foreach($this->super_model->custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON ph.pr_id = pd.pr_id WHERE pd.recom_date_from BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.recom_date_to BETWEEN '$recom_date_from' AND '$recom_date_to' AND pd.for_recom='1' AND $query") AS $p){
            $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
            $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$p->pr_details_id' AND recommended='1'");
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
            $total = $p->quantity * $p->recom_unit_price;
            $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$p->pr_id' AND served = '0' AND pi.pr_details_id = '$p->pr_details_id'");
            if($count_po==0){
                $data['pending_weekly_recom'][]=array(
                    'enduse'=>$p->enduse,
                    'requestor'=>$p->requestor,
                    'quantity'=>$p->quantity,
                    'recom_qty'=>$p->recom_qty,
                    'uom'=>$p->uom,
                    'item_description'=>$p->item_description,
                    'supplier'=>$supplier,
                    'pr_no'=>$p->pr_no,
                    'terms'=>$this->super_model->select_column_where('terms','terms',"terms_id",$p->terms_id),
                    'recom_unit_price'=>$p->recom_unit_price,
                    'work_duration'=>$p->work_duration,
                    'total'=>$total
                );
            }
        }
        }else{
            $data['pending_weekly_recom']=array();
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "MATERIALS RECOMMENDATION (PENDING)");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', "$date");
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "QTY as per Recom");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "UoM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', "Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', "Site PR/JO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "Delivery Lead Time / Work Duration");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J5', "UNIT PRICE (PESO)");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "TOTAL PESO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L5', "15 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M5', "30 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N5', "60 days PDC");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O5', "TERMS");
        foreach(range('A','O') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('E2:E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E1:E3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:O5')->applyFromArray($styleArray1);
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
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $terms =  $this->super_model->select_column_where('terms','terms','terms_id',$p->terms_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$p->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $total = $p->quantity * $p->recom_unit_price;
                $total_array[] = $p->quantity * $p->recom_unit_price;
                $total_peso = array_sum($total_array);
                if($terms!="15 days PDC" || $terms!="30 days PDC" || $terms!="60 days PDC" || $terms==""){
                    $total_array[] = $p->quantity * $p->recom_unit_price;
                    $total_peso = array_sum($total_array);
                }
                if($terms=="15 days PDC"){
                    $total_array15[] = $p->quantity * $p->recom_unit_price;
                    $total_peso15 = array_sum($total_array15);
                }

                if($terms=="30 days PDC"){
                    $total_array30[] = $p->quantity * $p->recom_unit_price;
                    $total_peso30 = array_sum($total_array30);
                }

                if($terms=="60 days PDC"){
                    $total_array60[] = $p->quantity * $p->recom_unit_price;
                    $total_peso60 = array_sum($total_array60);
                }

                 
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$p->enduse");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->requestor");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->quantity");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->recom_qty");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->uom");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$p->item_description");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$supplier");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->pr_no");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$p->work_duration");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$p->recom_unit_price");
                    if($terms!="15 days PDC" || $terms!="30 days PDC" || $terms!="60 days PDC" || $terms==""){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "0.00");
                    }

                    if($terms=="15 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "0.00");
                    }

                    if($terms=="30 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "0.00");
                    }

                    if($terms=="60 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "0.00");
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$terms");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$num.":M".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":O".$num)->applyFromArray($styleArray);
                    $num++; 
                    $disp_terms[] = array(
                        'terms'=>$terms,
                    );
            }
            $a = $num+1;
            $b = $num;
            foreach($disp_terms AS $var=>$key){
                if($key['terms']!="15 days PDC" || $key['terms']!="30 days PDC" || $key['terms']!="60 days PDC" || $key['terms']==""){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$b, $total_peso);
                }
                if($key['terms']=="15 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$b, $total_peso15);
                }

                if($key['terms']=="30 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$b, $total_peso30);
                }

                if($key['terms']=="60 days PDC"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$b, $total_peso60);
                }
            }
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$a, "Total (in PESO)");
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, $total_peso);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$a.":J".$a)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$a)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$b.":N".$b)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                
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
                $terms =  $this->super_model->select_column_where('terms','terms','terms_id',$p->terms_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$p->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $aoq_id = $this->super_model->select_column_custom_where('aoq_offers','aoq_id',"pr_details_id='$p->pr_details_id' AND recommended='1'");
                $total = $p->quantity * $p->recom_unit_price;
                $total_array[] = $p->quantity * $p->recom_unit_price;
                $total_peso = array_sum($total_array);
                if($terms!="15 days PDC" || $terms!="30 days PDC" || $terms!="60 days PDC" || $terms==""){
                    $total_array[] = $p->quantity * $p->recom_unit_price;
                    $total_peso = array_sum($total_array);
                }
                if($terms=="15 days PDC"){
                    $total_array15[] = $p->quantity * $p->recom_unit_price;
                    $total_peso15 = array_sum($total_array15);
                }

                if($terms=="30 days PDC"){
                    $total_array30[] = $p->quantity * $p->recom_unit_price;
                    $total_peso30 = array_sum($total_array30);
                }

                if($terms=="60 days PDC"){
                    $total_array60[] = $p->quantity * $p->recom_unit_price;
                    $total_peso60 = array_sum($total_array60);
                }
                $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$p->pr_id' AND served = '0' AND pi.pr_details_id = '$p->pr_details_id'");
                if($count_po==0){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$p->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->recom_qty");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$p->item_description");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$supplier");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$p->work_duration");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$p->recom_unit_price");
                    if($terms!="15 days PDC" || $terms!="30 days PDC" || $terms!="60 days PDC" || $terms==""){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "0.00");
                    }

                    if($terms=="15 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "0.00");
                    }

                    if($terms=="30 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "0.00");
                    }

                    if($terms=="60 days PDC"){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, $total);
                    }else{
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "0.00");
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$terms");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$num.":M".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":O".$num)->applyFromArray($styleArray);
                    $num++;
                    $disp_terms[] = array(
                        'terms'=>$terms,
                    );
            }
        }
        $a = $num+1;
        $b = $num;
        foreach($disp_terms AS $var=>$key){
            if($key['terms']!="15 days PDC" || $key['terms']!="30 days PDC" || $key['terms']!="60 days PDC" || $key['terms']==""){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$b, $total_peso);
            }
            if($key['terms']=="15 days PDC"){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$b, $total_peso15);
            }

            if($key['terms']=="30 days PDC"){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$b, $total_peso30);
            }

            if($key['terms']=="60 days PDC"){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$b, $total_peso60);
            }
        }
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$a, "Total (in PESO)");
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$a, $total_peso);
        $objPHPExcel->getActiveSheet()->getStyle('H'.$a.":J".$a)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('J'.$a)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $objPHPExcel->getActiveSheet()->getStyle('K'.$b.":N".$b)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
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
        $pr_id =$this->input->post('pr_id');
        $pr_details_id =$this->input->post('pr_details_id');
        $pr_calendar_id =$this->input->post('pr_calendar_id');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $proj_activity =$this->input->post('proj_act');
        $ver_date_needed=$this->input->post('ver_date_needed');
        $estimated_price =$this->input->post('estimated_price');
        
        if($pr_calendar_id==''){
            $data=array(
                'pr_id'=>$pr_id,
                'pr_details_id'=>$pr_details_id,
                'proj_act_id'=>$proj_activity,
                'ver_date_needed'=>$ver_date_needed,
                'estimated_price'=>$estimated_price,
            );
            if($this->super_model->insert_into("pr_calendar", $data)){
                echo "<script>alert('Successfully Added!'); window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
            }
        }else{
            $data=array(
                'ver_date_needed'=>$ver_date_needed,
            );
            if($this->super_model->update_where("pr_calendar", $data, "pr_calendar_id", $pr_calendar_id)){
                echo "<script>alert('Successfully Updated!'); window.location = '".base_url()."reports/pr_report/".$year."/".$month."';</script>";
            }
        }
    }


    public function getCalendar_disp(){
        $this->load->view('template/header');
        //$pr_id=$this->input->post('pr_id');
        $controller_name='pr_report';
        $proj_act_id=$this->uri->segment(3);
        $year=$this->uri->segment(4);
        $data['year']=$year;
        $data['proj_act_id']=$proj_act_id;
        $data['pr_list']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $count = $this->super_model->count_custom_where("pr_calendar","ver_date_needed LIKE '$year%' AND proj_act_id='$proj_act_id' ORDER BY ver_date_needed DESC");
        if($count!=0){
            foreach($this->super_model->select_custom_where("pr_calendar","ver_date_needed LIKE '$year%' AND proj_act_id='$proj_act_id' ORDER BY ver_date_needed DESC") AS $cp){
                $po_id = $this->super_model->select_column_where('po_items','po_id',"pr_details_id",$cp->pr_details_id);
                $actual_price = $this->super_model->select_column_where('po_items','unit_price',"pr_details_id",$cp->pr_details_id);
                $quantity = $this->super_model->select_column_where('pr_details','quantity',"pr_details_id",$cp->pr_details_id);
                $cal_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $cp->pr_details_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$cp->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $total_array[] = $cp->estimated_price;
                $total_est = array_sum($total_array);
                $total_est_price = $quantity * $cp->estimated_price;
                $total_array_tep[] = $total_est_price;
                $total_ep = array_sum($total_array_tep);
                $actual_total_price = $quantity * $actual_price;
                $total_array_ap[] = $actual_price;
                $total_actual = array_sum($total_array_ap);
                $total_array_tap[] = $actual_total_price;
                $total_actualp = array_sum($total_array_tap);
                $total_arrayp[] = $cal_unit_price;
                $total_unit = array_sum($total_arrayp);
                //$status= $this->item_status($cp->pr_details_id);
                $stat = $this->pr_item_status($cp->pr_details_id,$controller_name,$po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){
                    $data['purch'][]=array(
                        'pr_no'=>$this->super_model->select_column_where('pr_head','pr_no',"pr_id",$cp->pr_id),
                        'purpose'=>$this->super_model->select_column_where('pr_head','purpose',"pr_id",$cp->pr_id),
                        'enduse'=>$this->super_model->select_column_where('pr_head','enduse',"pr_id",$cp->pr_id),
                        'requestor'=>$this->super_model->select_column_where('pr_head','requestor',"pr_id",$cp->pr_id),
                        'item_description'=>$this->super_model->select_column_where('pr_details','item_description',"pr_details_id",$cp->pr_details_id),
                        'quantity'=>$quantity,
                        'uom'=>$this->super_model->select_column_where('pr_details','uom',"pr_details_id",$cp->pr_details_id),
                        'estimated_price'=>$cp->estimated_price,
                        'unit_price'=>$cal_unit_price,
                        'supplier'=>$supplier,
                        'total_est'=>$total_est,
                        'total_ep'=>$total_ep,
                        'total_actual'=>$total_actual,
                        'total_actualp'=>$total_actualp,
                        'estimated_total_price'=>$total_est_price,
                        'actual_total_price'=>$actual_total_price,
                        'total_unit'=>$total_unit,
                        'actual_price'=>$actual_price,
                        'status'=>$status,
                        'status_remarks'=>$status_remarks,
                        'ver_date_needed'=>$cp->ver_date_needed,
                    );
                }
            }
        }else{
            $data['purch']=array();
        }
        $this->load->view('reports/getCalendar_disp',$data);
        $this->load->view('template/footer');
    }

    public function search_item_calendar(){
        $this->load->view('template/header');
        $controller_name = 'pr_report';
        $year = $this->input->post('year');
        $proj_act_id = $this->input->post('proj_act_id');
        $data['year']=$year;
        $data['proj_act_id']=$proj_act_id;

        if(!empty($this->input->post('ver_date_needed'))){
            $data['ver_date_needed'] = $this->input->post('ver_date_needed');
        } else {
            $data['ver_date_needed']= "null";
        }

        if(!empty($this->input->post('purpose'))){
            $data['purpose'] = $this->input->post('purpose');
        } else {
            $data['purpose']= "null";
        }

        if(!empty($this->input->post('enduse'))){
            $data['enduse'] = $this->input->post('enduse');
        } else {
            $data['enduse'] = "null";
        }

        if(!empty($this->input->post('pr_no'))){
            $data['pr_no'] = $this->input->post('pr_no');
        } else {
            $data['pr_no'] = "null";
        }

        if(!empty($this->input->post('supplier'))){
            $data['supplier'] = $this->input->post('supplier');
        } else {
            $data['supplier'] = "null";
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

        if(!empty($this->input->post('ver_date_needed'))){
            $ver_date_needed = $this->input->post('ver_date_needed');
            $sql.=" pc.ver_date_needed LIKE '%$ver_date_needed%' AND";
            $filter .= "Date Needed - ".$ver_date_needed.", ";
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
            $sql.=" ph.pr_id = '$pr_no' AND";
            $filter .= "PR NO. - ".$this->super_model->select_column_where('pr_head', 'pr_no', 
                        'pr_id', $pr_no).", ";
        }

        $supply='';
        $groupby='';
        if(!empty($this->input->post('supplier'))){
            $supplier = $this->input->post('supplier');
            $sql.=" ao.vendor_id = '$supplier' AND";
            $filter .= "Supplier - ".$this->super_model->select_column_where('vendor_head', 'vendor_name', 
                        'vendor_id', $supplier).", ";
            $supply='LEFT JOIN aoq_offers ao ON ao.pr_details_id=pd.pr_details_id';
            $groupby='GROUP BY ao.pr_details_id';
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
        $data['pr_list']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $count = $this->super_model->count_custom_query("SELECT * FROM pr_calendar pc LEFT JOIN pr_details pd ON pc.pr_details_id = pd.pr_details_id LEFT JOIN pr_head ph ON pd.pr_id = ph.pr_id $supply WHERE pc.ver_date_needed LIKE '$year%' AND pc.proj_act_id='$proj_act_id' AND $query ORDER BY ver_date_needed DESC");
        if($count!=0){
            foreach($this->super_model->custom_query("SELECT * FROM pr_calendar pc LEFT JOIN pr_details pd ON pc.pr_details_id = pd.pr_details_id LEFT JOIN pr_head ph ON pd.pr_id = ph.pr_id $supply WHERE pc.ver_date_needed LIKE '$year%' AND pc.proj_act_id='$proj_act_id' AND $query $groupby ORDER BY ver_date_needed DESC") AS $cp){
                $po_id = $this->super_model->select_column_where('po_items','po_id',"pr_details_id",$cp->pr_details_id);
                $actual_price = $this->super_model->select_column_where('po_items','unit_price',"po_items_id",$cp->pr_details_id);
                $quantity = $this->super_model->select_column_where('pr_details','quantity',"pr_details_id",$cp->pr_details_id);
                $cal_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $cp->pr_details_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$cp->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $total_array[] = $cp->estimated_price;
                $total_est = array_sum($total_array);
                $total_est_price = $quantity * $cp->estimated_price;
                $total_array_tep[] = $total_est_price;
                $total_ep = array_sum($total_array_tep);
                $actual_total_price = $quantity * $actual_price;
                $total_array_ap[] = $actual_price;
                $total_actual = array_sum($total_array_ap);
                $total_array_tap[] = $actual_total_price;
                $total_actualp = array_sum($total_array_tap);
                $total_arrayp[] = $cal_unit_price;
                $total_unit = array_sum($total_arrayp);
                //$status= $this->item_status($cp->pr_details_id);
                $stat = $this->pr_item_status($cp->pr_details_id,$controller_name,$po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){
                    $data['purch'][]=array(
                        'pr_no'=>$this->super_model->select_column_where('pr_head','pr_no',"pr_id",$cp->pr_id),
                        'purpose'=>$this->super_model->select_column_where('pr_head','purpose',"pr_id",$cp->pr_id),
                        'enduse'=>$this->super_model->select_column_where('pr_head','enduse',"pr_id",$cp->pr_id),
                        'requestor'=>$this->super_model->select_column_where('pr_head','requestor',"pr_id",$cp->pr_id),
                        'item_description'=>$this->super_model->select_column_where('pr_details','item_description',"pr_details_id",$cp->pr_details_id),
                        'quantity'=>$quantity,
                        'uom'=>$this->super_model->select_column_where('pr_details','uom',"pr_details_id",$cp->pr_details_id),
                        'estimated_price'=>$cp->estimated_price,
                        'unit_price'=>$cal_unit_price,
                        'supplier'=>$supplier,
                        'total_est'=>$total_est,
                        'total_ep'=>$total_ep,
                        'total_actual'=>$total_actual,
                        'total_actualp'=>$total_actualp,
                        'estimated_total_price'=>$total_est_price,
                        'actual_total_price'=>$actual_total_price,
                        'total_unit'=>$total_unit,
                        'actual_price'=>$actual_price,
                        'status'=>$status,
                        'status_remarks'=>$status_remarks,
                        'ver_date_needed'=>$cp->ver_date_needed,
                    );
                }
            }
        }else{
            $data['purch']=array();
        }
        $this->load->view('reports/getCalendar_disp',$data);
        $this->load->view('template/footer');
    }

    public function export_calendar_item(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Schedule of Activities.xlsx";
        $controller_name="pr_report";
        $proj_act_id=$this->uri->segment(3);
        $year=$this->uri->segment(4);
        $ver_date_needed=$this->uri->segment(5);
        $purpose=str_replace("%20", " ", $this->uri->segment(6));
        $enduse=str_replace("%20", " ", $this->uri->segment(7));
        $pr_no=$this->uri->segment(8);
        $supplier=$this->uri->segment(9);
        $requestor=str_replace("%20", " ", $this->uri->segment(10));
        $description=str_replace("%20", " ", $this->uri->segment(11));


        $sql="";
        $filter = " ";


        if($ver_date_needed!='null'){
            $sql.=" pc.ver_date_needed LIKE '%$ver_date_needed%' AND";
            $filter .= $ver_date_needed;
        }

        if($purpose!='null'){
            $sql.=" ph.purpose LIKE '%$purpose%' AND";
            $filter .= $purpose;
        }

        if($enduse!='null'){
            $sql.=" ph.enduse LIKE '%$enduse%' AND";
            $filter .= $enduse;
        }

        if($pr_no){
            $sql.=" ph.pr_id = '$pr_no' AND";
            $filter .= $this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $pr_no);
        }

        $supply='';
        $groupby='';
        if($supplier!='null'){
            $sql.=" ao.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 
                        'vendor_id', $supplier);
            $supply='LEFT JOIN aoq_offers ao ON ao.pr_details_id=pd.pr_details_id';
            $groupby='GROUP BY ao.pr_details_id';
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', "SCHEDULE OF ACTIVITIES");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', "$year");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Date Needed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "PR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "Purpose");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "Enduse");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "Requestor");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', "Item Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J5', "Status Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L5', "Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M5', "Estimated Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N5', "Estimated Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O5', "Actual Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P5', "Total Actual Price");
        foreach(range('A','P') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('F2:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A5:P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F1:F3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:P5')->getFont()->setBold(true);
        $num=6;
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        if($filt!=''){
            $x = 1;
             foreach($this->super_model->custom_query("SELECT * FROM pr_calendar pc LEFT JOIN pr_details pd ON pc.pr_details_id = pd.pr_details_id LEFT JOIN pr_head ph ON pd.pr_id = ph.pr_id $supply WHERE pc.ver_date_needed LIKE '$year%' AND pc.proj_act_id='$proj_act_id' AND $query $groupby ORDER BY ver_date_needed DESC") AS $cp){
                $po_id = $this->super_model->select_column_where('po_items','po_id',"pr_details_id",$cp->pr_details_id);
                $actual_price = $this->super_model->select_column_where('po_items','unit_price',"po_items_id",$cp->pr_details_id);
                $quantity = $this->super_model->select_column_where('pr_details','quantity',"pr_details_id",$cp->pr_details_id);
                $cal_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $cp->pr_details_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$cp->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $total_array[] = $cp->estimated_price;
                $total_est = array_sum($total_array);
                $total_est_price = $quantity * $cp->estimated_price;
                $total_array_tep[] = $total_est_price;
                $total_ep = array_sum($total_array_tep);
                $actual_total_price = $quantity * $actual_price;
                $total_array_ap[] = $actual_price;
                $total_actual = array_sum($total_array_ap);
                $total_array_tap[] = $actual_total_price;
                $total_actualp = array_sum($total_array_tap);
                $total_arrayp[] = $cal_unit_price;
                $total_unit = array_sum($total_arrayp);
                //$status= $this->item_status($cp->pr_details_id);
                $pr_no=$this->super_model->select_column_where('pr_head','pr_no',"pr_id",$cp->pr_id)."-".COMPANY;
                $purpose=$this->super_model->select_column_where('pr_head','purpose',"pr_id",$cp->pr_id);
                $enduse=$this->super_model->select_column_where('pr_head','enduse',"pr_id",$cp->pr_id);
                $requestor=$this->super_model->select_column_where('pr_head','requestor',"pr_id",$cp->pr_id);
                $item_description=$this->super_model->select_column_where('pr_details','item_description',"pr_details_id",$cp->pr_details_id);
                $uom=$this->super_model->select_column_where('pr_details','uom',"pr_details_id",$cp->pr_details_id);
                $stat = $this->pr_item_status_export($cp->pr_details_id,$controller_name,$po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $po_issue=$this->like($status, "PO Issued");
                if($status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($po_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }
                if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $cp->ver_date_needed);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $pr_no);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $purpose);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $enduse);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $requestor);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $item_description);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $quantity);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $uom);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $supplier);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $status_remarks);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $status);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $cal_unit_price);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $cp->estimated_price);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, $total_est_price);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, $actual_price);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, $actual_total_price);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":P".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $num++;
                    $x++;
                }
            }
            $num1=$num;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num1, "Total: ");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num1, $total_unit);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num1, $total_est);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num1, $total_ep);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num1, $total_actual);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num1, $total_actualp);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$num1.":P".$num1)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$num1.":P".$num1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        }else {
            $x = 1;
            foreach($this->super_model->select_custom_where("pr_calendar","ver_date_needed LIKE '$year%' AND proj_act_id='$proj_act_id' ORDER BY ver_date_needed DESC") AS $cp){
                $po_id = $this->super_model->select_column_where('po_items','po_id',"pr_details_id",$cp->pr_details_id);
                $actual_price = $this->super_model->select_column_where('po_items','unit_price',"po_items_id",$cp->pr_details_id);
                $quantity = $this->super_model->select_column_where('pr_details','quantity',"pr_details_id",$cp->pr_details_id);
                $cal_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $cp->pr_details_id);
                $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$cp->pr_details_id' AND recommended='1'");
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
                $total_array[] = $cp->estimated_price;
                $total_est = array_sum($total_array);
                $total_est_price = $quantity * $cp->estimated_price;
                $total_array_tep[] = $total_est_price;
                $total_ep = array_sum($total_array_tep);
                $actual_total_price = $quantity * $actual_price;
                $total_array_ap[] = $actual_price;
                $total_actual = array_sum($total_array_ap);
                $total_array_tap[] = $actual_total_price;
                $total_actualp = array_sum($total_array_tap);
                $total_arrayp[] = $cal_unit_price;
                $total_unit = array_sum($total_arrayp);
                //$status= $this->item_status($cp->pr_details_id);
                $pr_no=$this->super_model->select_column_where('pr_head','pr_no',"pr_id",$cp->pr_id)."-".COMPANY;
                $purpose=$this->super_model->select_column_where('pr_head','purpose',"pr_id",$cp->pr_id);
                $enduse=$this->super_model->select_column_where('pr_head','enduse',"pr_id",$cp->pr_id);
                $requestor=$this->super_model->select_column_where('pr_head','requestor',"pr_id",$cp->pr_id);
                $item_description=$this->super_model->select_column_where('pr_details','item_description',"pr_details_id",$cp->pr_details_id);
                $uom=$this->super_model->select_column_where('pr_details','uom',"pr_details_id",$cp->pr_details_id);
                $stat = $this->pr_item_status_export($cp->pr_details_id,$controller_name,$po_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $po_issue=$this->like($status, "PO Issued");
                if($status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($po_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }
                if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $cp->ver_date_needed);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $pr_no);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $purpose);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $enduse);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $requestor);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $item_description);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $quantity);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $uom);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $supplier);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $status_remarks);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $status);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $cal_unit_price);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, $cp->estimated_price);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, $total_est_price);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, $actual_price);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, $actual_total_price);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":B".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('L'.$num.":P".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $num++;
                    $x++;
                }
            }
            $num1=$num;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num1, "Total: ");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num1, $total_unit);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num1, $total_est);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num1, $total_ep);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num1, $total_actual);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num1, $total_actualp);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$num1.":P".$num1)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$num1.":P".$num1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        }
        $objPHPExcel->getActiveSheet()->getStyle("A5:P5")->applyFromArray($styleArray);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Schedule of Activities.xlsx"');
        readfile($exportfilename);
    }


    public function purch_calendar(){  
        $controller_name='pr_report';
        $year=$this->uri->segment(3);
        $data['year'] = $year;
  /*      $data['cal_date_from']=$cdate_from;
        $data['cal_date_to']=$cdate_to;*/
        $data['proj_act']=$this->super_model->select_custom_where("project_activity","status='Active' ORDER BY proj_activity ASC");
        $count_calendar = $this->super_model->count_custom_where("pr_calendar","ver_date_needed LIKE '$year%' ORDER BY ver_date_needed DESC");  
        if($count_calendar!=0){

    
        foreach($this->super_model->select_custom_where("pr_calendar","ver_date_needed LIKE '$year%' GROUP BY proj_act_id ORDER BY ver_date_needed DESC") AS $cp){
            $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $cp->pr_details_id);
            $cal_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $cp->pr_details_id);
            $aoq_vendor = $this->super_model->select_column_custom_where('aoq_offers','vendor_id', "pr_details_id='$cp->pr_details_id' AND recommended='1'");
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name', "vendor_id",$aoq_vendor);
            $total_array[] = $cp->estimated_price;
            $total_est = array_sum($total_array);
            $total_arrayp[] = $cal_unit_price;
            $total_unit = array_sum($total_arrayp);
           // $pr_no = '';
                $pr_no =array();
            foreach($this->super_model->select_row_where('pr_calendar',"proj_act_id",$cp->proj_act_id) AS $allpr){
            //    $pr_no .=$this->super_model->select_column_where("pr_head","pr_no","pr_id",$allpr->pr_id) . "-".COMPANY."<br>";
                   //$status= $this->item_status($allpr->pr_details_id);
                   //echo $allpr->pr_details_id . "-". $status . "<br>";
                    $stat = $this->pr_item_status($allpr->pr_details_id,$controller_name,$po_id);
                    $status = $stat['status'];
                    $status_remarks = $stat['remarks'];
                   if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){
                    //echo $this->super_model->select_column_where("pr_head","pr_no","pr_id",$allpr->pr_id) . "-".COMPANY."<br>";
                        $pr_no[] = $this->super_model->select_column_where("pr_head","pr_no","pr_id",$allpr->pr_id) . "-".COMPANY;

                       /* $data['purch'][]=array(
                            'purpose'=>$this->super_model->select_column_where('pr_head','purpose',"pr_id",$allpr->pr_id),
                            'enduse'=>$this->super_model->select_column_where('pr_head','enduse',"pr_id",$allpr->pr_id),
                            'requestor'=>$this->super_model->select_column_where('pr_head','requestor',"pr_id",$allpr->pr_id),
                            'item_description'=>$this->super_model->select_column_where('pr_details','item_description',"pr_details_id",$allpr->pr_details_id),
                            'quantity'=>$this->super_model->select_column_where('pr_details','quantity',"pr_details_id",$allpr->pr_details_id),
                            'uom'=>$this->super_model->select_column_where('pr_details','uom',"pr_details_id",$allpr->pr_details_id),
                            'pr_no'=>$this->super_model->select_column_where('pr_head','pr_no',"pr_id",$allpr->pr_id),
                            'estimated_price'=>$allpr->estimated_price,
                            'unit_price'=>$cal_unit_price,
                            'supplier'=>$supplier,
                            'total_est'=>$total_est,
                            'total_unit'=>$total_unit,
                        );*/
                    }
            }

            $pr = array_unique($pr_no);
            $prno='';
            foreach($pr AS $p){
                $prno .= $p."<br>";
            }

            if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){

                $data['purch_calendar'][] =  array(


                    'proj_act_id'=>$cp->proj_act_id,
                    'pr_calendar_id'=>$cp->pr_calendar_id,
                    'pr_details_id'=>$cp->pr_details_id,
                    'proj_activity'=>$this->super_model->select_column_where('project_activity','proj_activity',"proj_act_id",$cp->proj_act_id),
                    'status'=>$this->super_model->select_column_where('project_activity','status',"proj_act_id",$cp->proj_act_id),
                    'c_remarks'=>$this->super_model->select_column_where('project_activity','c_remarks',"proj_act_id",$cp->proj_act_id),
                    'pr_no'=>$prno,
                    'duration'=>$this->super_model->select_column_where('project_activity','duration',"proj_act_id",$cp->proj_act_id),
                    'target_start_date'=>$this->super_model->select_column_where('project_activity','target_start_date',"proj_act_id",$cp->proj_act_id),
                    'target_completion'=>$this->super_model->select_column_where('project_activity','target_completion',"proj_act_id",$cp->proj_act_id),
                    'actual_start'=>$this->super_model->select_column_where('project_activity','actual_start',"proj_act_id",$cp->proj_act_id),
                    'actual_completion'=>$this->super_model->select_column_where('project_activity','actual_completion',"proj_act_id",$cp->proj_act_id),
                    'ver_date_needed'=>$cp->ver_date_needed,
                    'estimated_price'=>$cp->estimated_price,
                    'purpose'=>$this->super_model->select_column_where('pr_head','purpose',"pr_id",$cp->pr_id),
                    'enduse'=>$this->super_model->select_column_where('pr_head','enduse',"pr_id",$cp->pr_id),
                    'requestor'=>$this->super_model->select_column_where('pr_head','requestor',"pr_id",$cp->pr_id),
                    'item_description'=>$this->super_model->select_column_where('pr_details','item_description',"pr_details_id",$cp->pr_details_id),
                    'quantity'=>$this->super_model->select_column_where('pr_details','quantity',"pr_details_id",$cp->pr_details_id),
                    'uom'=>$this->super_model->select_column_where('pr_details','uom',"pr_details_id",$cp->pr_details_id),
                    'unit_price'=>$cal_unit_price,
                    'supplier'=>$supplier,
                    'total_est'=>$total_est,
                    'total_unit'=>$total_unit,
                    'status'=>$status,
                    'status_remarks'=>$status_remarks,
                    'est_total_materials'=>0,

                );
            }
        }
        }else{
            $data['purch_calendar']=array();
        }

        $this->load->view('template/header');  
        $this->load->view('reports/purch_calendar',$data);
        $this->load->view('template/footer');
    }

    public function get_weekly_total($week_start, $week_end, $proj_act_id){
        $sum=array();
        foreach($this->super_model->select_custom_where('pr_calendar',"proj_act_id='$proj_act_id' AND ver_date_needed BETWEEN '$week_start' AND '$week_end'") AS $allpr){
       
               $status= $this->item_status($allpr->pr_details_id);
             
               if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){
            
                    $sum[] = $allpr->estimated_price;
                }
        }

        $total =array_sum($sum);
      //  $total = $this->super_model->select_sum_between("pr_calendar", "estimated_price","proj_act_id = '$proj_act_id' AND ver_date_needed BETWEEN '$week_start' AND '$week_end'");
        return $total;
    }

    public function update_ver_date_needed(){
        $pr_calendar_id =$this->input->post('pr_calendar_id');
        $year =$this->input->post('year');
        $ver_date_needed = trim($this->input->post('ver_date_needed')," ");

        $data = array(
            'ver_date_needed'=>$this->input->post('ver_date_needed'),
        );
        $pr_calendar_id = $this->input->post('pr_calendar_id');
        if($this->super_model->update_where('pr_calendar', $data, 'pr_calendar_id', $pr_calendar_id)){
            echo "<script>alert('Successfully Updated!'); window.location = '".base_url()."reports/purch_calendar/".$year."';</script>";
        }
    }

    public function search_purch_calendar(){
        if(!empty($this->input->post('year'))){
            $data['year'] = $this->input->post('year');
            $year = $this->input->post('year');
        } else {
            $data['year']= "null";
            $year= "null";
        }

        if(!empty($this->input->post('pr_no'))){
            $data['pr_no'] = $this->input->post('pr_no');
            $pr_no = $this->input->post('pr_no');
        } else {
            $data['pr_no'] = "null";
            $pr_no = "";
        }

        if(!empty($this->input->post('proj_act'))){
            $data['proj_act'] = $this->input->post('proj_act');
            $proj_act = $this->input->post('proj_act');
        } else {
            $data['proj_act']= "null";
            $proj_act= "";
        }

        if(!empty($this->input->post('c_remarks'))){
            $data['c_remarks'] = $this->input->post('c_remarks');
            $c_remarks = $this->input->post('c_remarks');
        } else {
            $data['c_remarks']= "null";
            $c_remarks= "";
        }

        if(!empty($this->input->post('ver_date_needed'))){
            $data['ver_date_needed'] = $this->input->post('ver_date_needed');
            $ver_date_needed = $this->input->post('ver_date_needed');
        } else {
            $data['ver_date_needed']= "null";
            $ver_date_needed= "";
        }

        if(!empty($this->input->post('target_start_date'))){
            $data['target_start_date'] = $this->input->post('target_start_date');
            $target_start_date = $this->input->post('target_start_date');
        } else {
            $data['target_start_date'] = "null";
            $target_start_date = "";
        }

        if(!empty($this->input->post('target_completion'))){
            $data['target_completion'] = $this->input->post('target_completion');
            $target_completion = $this->input->post('target_completion');
        } else {
            $data['target_completion'] = "null";
            $target_completion = "";
        } 

        if(!empty($this->input->post('actual_start'))){
            $data['actual_start'] = $this->input->post('actual_start');
            $actual_start = $this->input->post('actual_start');
        } else {
            $data['actual_start'] = "null";
            $actual_start = "";
        } 

        if(!empty($this->input->post('actual_completion'))){
            $data['actual_completion'] = $this->input->post('actual_completion');
            $actual_completion = $this->input->post('actual_completion');
        } else {
            $data['actual_completion'] = "null";
            $actual_completion = "";
        } 

        $sql="";
        $filter = "";

        if($pr_no!=''){
            $sql.=" ph.pr_no LIKE '%$pr_no%' AND";
            $filter .= $pr_no.", ";
        }

        /*if($proj_act!=''){
            $sql.=" pr_calendar.proj_act LIKE '%$proj_act%' AND";
            $filter .= $proj_act.", ";
        }*/
        if($proj_act!=''){
            $sql.=" pa.proj_act_id = '$proj_act' AND";
            $filter .= $this->super_model->select_column_where("project_activity","proj_activity","proj_act_id",$proj_act).", ";
        }

        if($c_remarks!=''){
            $sql.=" pa.c_remarks LIKE '%$c_remarks%' AND";
            $filter .= $c_remarks.", ";
        }

        if($ver_date_needed!=''){
            $sql.=" pc.ver_date_needed LIKE '%$ver_date_needed%' AND";
            $filter .= $ver_date_needed.", ";
        }

        if($target_start_date!=''){
            $sql.=" pa.target_start_date LIKE '%$target_start_date%' AND";
            $filter .= $target_start_date.", ";
        }

        if($target_completion!=''){
                $sql.=" pa.target_completion LIKE '%$target_completion%' AND";
            $filter .= $target_completion.", ";
        }

        if($actual_start!=''){
                $sql.=" pa.actual_start LIKE '%$actual_start%' AND";
            $filter .= $actual_start.", ";
        }

        if($actual_completion!=''){
                $sql.=" pa.actual_completion LIKE '%$actual_completion%' AND";
            $filter .= $actual_completion.", ";
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        $data['filt']=$filt;
        $date = $year;
        $data['proj_act']=$this->super_model->select_custom_where("project_activity","status='Active' ORDER BY proj_activity ASC");
        //$count_search_purch_calendar = $this->super_model->count_join_where_order("pr_calendar","pr_head"," $query AND pr_calendar.ver_date_needed LIKE '$year'","pr_id","ver_date_needed",'DESC');
        $count_search_purch_calendar = $this->super_model->count_custom_query("SELECT * FROM pr_calendar pc INNER JOIN pr_head ph ON pc.pr_id = ph.pr_id INNER JOIN project_activity pa ON pc.proj_act_id = pa.proj_act_id WHERE pc.ver_date_needed LIKE '%$year%' AND $query ORDER BY pc.ver_date_needed DESC");
        if($count_search_purch_calendar!=0){
            foreach($this->super_model->custom_query("SELECT * FROM pr_calendar pc INNER JOIN pr_head ph ON pc.pr_id = ph.pr_id INNER JOIN project_activity pa ON pc.proj_act_id = pa.proj_act_id WHERE pc.ver_date_needed LIKE '%$year%' AND $query ORDER BY pc.ver_date_needed DESC") AS $cp){
                $proj_activity = $this->super_model->select_column_where("project_activity","proj_activity","proj_act_id",$cp->proj_act_id);
                $data['purch_calendar'][] =  array(
                    'proj_activity'=>$proj_activity,
                    'c_remarks'=>$cp->c_remarks,
                    'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$cp->pr_id),
                    'duration'=>$cp->duration,
                    'target_start_date'=>$cp->target_start_date,
                    'target_completion'=>$cp->target_completion,
                    'actual_start'=>$cp->actual_start,
                    'actual_completion'=>$cp->actual_completion,
                    'ver_date_needed'=>$cp->ver_date_needed,
                    'estimated_price'=>$cp->estimated_price,
                    'est_total_materials'=>$cp->est_total_materials,

                );
            }
        }else{
            $data['purch_calendar']=array();
        }
        $this->load->view('template/header');        
        $this->load->view('reports/purch_calendar',$data);
        $this->load->view('template/footer');
    }

    public function export_purch_calendar(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="Schedule of Activities.xlsx";
        $cal_date_from=str_replace("null", " ", $this->uri->segment(3));
        $cal_date_to=str_replace("null", " ", $this->uri->segment(4));;
        $pr_no=str_replace("%20", " ", $this->uri->segment(5));
        $proj_act=str_replace("%20", " ", $this->uri->segment(6));
        $c_remarks=str_replace("%20", " ", $this->uri->segment(7));
        $ver_date_needed=str_replace("%20", " ", $this->uri->segment(8));
        $target_start_date=str_replace("%20", " ", $this->uri->segment(9));
        $target_completion=str_replace("%20", " ", $this->uri->segment(10));
        $actual_start=str_replace("%20", " ", $this->uri->segment(11));
        $actual_completion=str_replace("%20", " ", $this->uri->segment(12));


        $sql="";
        $filter = " ";


        if($pr_no!=''){
            $sql.=" pr_head.pr_no LIKE '%$pr_no%' AND";
            $filter .= $pr_no;
        }

        if($proj_act!=''){
            $sql.=" pr_calendar.proj_act LIKE '%$proj_act%' AND";
            $filter .= $proj_act;
        }

        if($c_remarks!=''){
            $sql.=" pr_calendar.c_remarks LIKE '%$c_remarks%' AND";
            $filter .= $c_remarks;
        }

        if($ver_date_needed!=''){
            $sql.=" pr_calendar.ver_date_needed LIKE '%$ver_date_needed%' AND";
            $filter .= $ver_date_needed;
        }

        if($target_start_date!=''){
            $sql.=" pr_calendar.target_start_date LIKE '%$target_start_date%' AND";
            $filter .= $target_start_date;
        }

        if($target_completion!=''){
                $sql.=" pr_calendar.target_completion LIKE '%$target_completion%' AND";
            $filter .= $target_completion;
        }

        if($actual_start!=''){
                $sql.=" pr_calendar.actual_start LIKE '%$actual_start%' AND";
            $filter .= $actual_start;
        }

        if($actual_completion!=''){
                $sql.=" pr_calendar.actual_completion LIKE '%$actual_completion%' AND";
            $filter .= $actual_completion;
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        $date = $cal_date_from." - ".$cal_date_to;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', "SCHEDULE OF ACTIVITIES");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', "$date");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Item Number");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "Project of Activity");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "PR No/s.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "Duration (# of Days)");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', "Target Start Date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "Target Completion");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', "Actual Start");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I5', "Actual Completion");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J5', "Verified Date Needed");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K5', "Estimated Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L5', "Est Total(Materials)");
        foreach(range('A','J') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('F2:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A5:L5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F1:F3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:L5')->getFont()->setBold(true);
        $num=6;
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        if($filt!=''){
            $x = 1;
             foreach($this->super_model->select_join_where_order("pr_calendar","pr_head","(pr_calendar.ver_date_needed BETWEEN '$cal_date_from' AND '$cal_date_to' OR pr_calendar.ver_date_needed LIKE '%$date1%') AND $query","pr_id","ver_date_needed",'DESC') AS $cp){
                $pr_no=$this->super_model->select_column_where("pr_head","pr_no","pr_id",$cp->pr_id);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $x);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $cp->proj_act);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $cp->c_remarks);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $pr_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $cp->duration);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $cp->target_start_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $cp->target_completion);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $cp->actual_start);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $cp->actual_completion);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $cp->ver_date_needed);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $cp->estimated_price);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $cp->est_total_materials);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":J".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num.":L".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $num++;
                $x++;
            }
        }else {
            $x = 1;
            foreach($this->super_model->select_custom_where("pr_calendar","(pr_calendar.ver_date_needed BETWEEN '$cal_date_from' AND '$cal_date_to' OR pr_calendar.ver_date_needed LIKE '%$date1%') ORDER BY ver_date_needed DESC") AS $cp){
                $pr_no=$this->super_model->select_column_where("pr_head","pr_no","pr_id",$cp->pr_id);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $x);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $cp->proj_act);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $cp->c_remarks);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $pr_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $cp->duration);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $cp->target_start_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $cp->target_completion);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $cp->actual_start);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $cp->actual_completion);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $cp->ver_date_needed);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, $cp->estimated_price);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, $cp->est_total_materials);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":L".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":J".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num.":L".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $num++;
                $x++;
            }
        }
        $objPHPExcel->getActiveSheet()->getStyle("A5:L5")->applyFromArray($styleArray);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Schedule of Activities.xlsx"');
        readfile($exportfilename);
    }

    public function pending_pr(){        
        $this->load->view('template/header');        
        $this->load->view('template/navbar');   
        $controller_name='pr_report';   
        $delivered = array();
        foreach($this->super_model->custom_query("SELECT pi.pr_details_id FROM po_dr_items pi INNER JOIN po_dr pd ON pi.dr_id = pd.dr_id WHERE pd.received='1'") AS $dr){
            $delivered[] = $dr->pr_details_id;
        }
         $calendar = array();
        foreach($this->super_model->custom_query("SELECT pr_details_id FROM pr_calendar") AS $cal){
            $calendar[] = $cal->pr_details_id;
        }

        $pending=array();
        foreach($calendar AS $cl){
            foreach($delivered AS $dl){
                if($cl != $dl){
                    $pending[]= $cl;
                }
            }
        }
        $data["pending_pr"]=array();

       $result= array_unique($pending);
       foreach($result AS $res){
        $pr_id= $this->super_model->select_column_where("pr_details","pr_id","pr_details_id",$res);
        $company_id= $this->super_model->select_column_custom_where("pr_details","company_id","pr_id='$pr_id' AND pr_details_id = '$res'");
        $on_hold= $this->super_model->select_column_custom_where("pr_details","on_hold","pr_id='$pr_id' AND pr_details_id = '$res'");
        $onhold_by= $this->super_model->select_column_custom_where("pr_details","onhold_by","pr_id='$pr_id' AND pr_details_id = '$res'");
        $recom_by= $this->super_model->select_column_custom_where("pr_details","recom_by","pr_id='$pr_id' AND pr_details_id = '$res'");
        $fulfilled_by= $this->super_model->select_column_custom_where("pr_details","fulfilled_by","pr_id='$pr_id' AND pr_details_id = '$res'");
        $for_recom= $this->super_model->select_column_custom_where("pr_details","for_recom","pr_id='$pr_id' AND pr_details_id = '$res'");
        $quantity= $this->super_model->select_column_custom_where("pr_details","quantity","pr_id='$pr_id' AND pr_details_id = '$res'");
        $uom= $this->super_model->select_column_custom_where("pr_details","uom","pr_id='$pr_id' AND pr_details_id = '$res'");


            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $res);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id',$res);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $res);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id",$res, "po_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$res'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$res'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$company_id);
            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by);
            $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$res);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$res);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$res);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$res);
         
            $unserved_qty=0;
            $unserved_uom='';  
            $stat = $this->pr_item_status($res,$controller_name,$po_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
            /*$statuss='';
            $status='';
            $status_remarks='';
            if($sum_po_qty!=0){
                if($sum_po_qty < $quantity){

                      $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
             
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        
                        $served_qty = $this->super_model->select_sum("po_items", "quantity", "pr_details_id",$res);
                        $delivered_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'pr_details_id', $res);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $res);

                  
                        if($cancelled_head_po==0){
                            $unserved_qty = $quantity - $served_qty;
                        }else{
                            $unserved_qty = '';
                        }
                        $unserved_uom =  $served_uom;

                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        $count_po_unserved = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '0' AND cancelled ='0' AND pr_details_id = '$res'");
                        $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '1' AND cancelled ='0' AND pr_details_id = '$res'");
                        $count_po_all = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE  cancelled ='0' AND pr_details_id = '$res'");
                    
                        if($count_po_unserved !=0 && $count_po_served==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= 'PO Issued - Partial<br><br>';
                            }

                            if($on_hold==1){
                                 $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                                $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            }else if($for_recom==1){
                                 $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                                $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                            }else{
                                $status_remarks='';
                            }
                        }else if($count_po_unserved !=0  && $count_po_served!=0 && $cancelled_head_po==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= 'PO Issued - Partial<br><br>';
                                $status .= 'Partially Delivered';
                            }

                            if($on_hold==1){
                                $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                                $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            }else if($for_recom==1){
                                   $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");
                                $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                            }else{
                                $status_remarks='';
                            }
                        } else if(($count_po_unserved == 0 && $count_po_served == $count_po_all) || ($count_po_unserved == 0 && $count_po_served !=0)) {
                        
                            
                            $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                            if($cancelled_head_po!=0){
                               
                                $statuss = 'Partially Delivered';
                                if($on_hold==1){
                                    $status .="On-Hold";
                                }else if($fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status.="Partially Delivered / Cancelled";
                                }
                            }else if($cancelled_items_po==0){
                                if($on_hold==1){
                                    $status .="On-Hold";
                                }else if($fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status .= 'Partially Delivered';
                                }
                            }else {
                                $statuss = 'Partially Delivered';
                                $status.="Cancelled";
                            }
                          
                            foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$res' AND date_received!=''") AS $del){
                               
                                 
                                if($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id!='')){

                                 $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                }
                                if(empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND ph.po_id = '$del->po_id' AND pi.pr_details_id = '$res'");
                                    $status_remarks.="PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty .")</span>";
                                }
                            }

                        }
                  
                } else {
                    
                    $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                    if($served==0){
                        if($cancelled_items_po==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else{
                                $status .= 'PO Issued';
                            }
                        }else if($cancelled_items_po==0 && $fulfilled_by==1){
                            $status="Delivered by ".$company;
                        }else if($cancelled_items_po==0 && $for_recom==1){
                            $status="For Recom";
                        }else {
                            $statuss = 'PO Issued';
                            $status .= 'Cancelled';
                        }

                        if($on_hold==1){
                            $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else {
                        if($cancelled_items_po==0){
                            $status .= 'Fully Delivered';
                        }else {
                            $statuss = 'Fully Delivered';
                            $status .= 'Cancelled';
                        }
                        $status_remarks='';
                       foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$res' AND date_received!=''") AS $del){
                     
                             $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                        }
                    }

                }
            } else {
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $res);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $res);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $res);
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

                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr_id' AND served = '0' AND pi.pr_details_id = '$res'");
                     $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr_id' AND served = '1' AND pi.pr_details_id = '$res'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$res'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$res'");

                   
                    $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$res'");
                   
                  
                    $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' AND cancelled='0'");
                    
                   $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' AND cancelled='0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$res' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
 
                    if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0){
                      
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      

                        if($on_hold==1){
                             $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            
                            $status_remarks = 'For RFQ'."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'For RFQ'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'For RFQ';
                        }
                    }else if(($count_rfq!=0 && $count_aoq==0 && $count_aoq_awarded==0 && $count_po!=0) || ($count_rfq==0 && $count_aoq==0 && $count_aoq_awarded==0 && $count_po!=0)){
                       
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                               $status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                      

                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                        $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' AND awarded = '0'");
                      
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = 'Canvassing Ongoing'."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'Canvassing Ongoing'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'Canvassing Ongoing';
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' AND awarded = '0'");
                        
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      
                        if($on_hold==1){
                             $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'RFQ Completed - No. of RFQ completed: '.$count_rfq_completed."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                               $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");


                            $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' ");
                         
                        if($on_hold==1){
                            $status .="On-Hold";
                        }else if($fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                    
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                               $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date;
                        }
                    } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){
                        //if($cancelled_items_po==0){
                        if($on_hold==1){
                            $status .="On-Hold";
                        }else if($fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                    
                        if($on_hold==1){
                            $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'For PO - AOQ Done (awarded)' .$aoq_date."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'For PO - AOQ Done (awarded)'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'For PO - AOQ Done (awarded)';
                        }
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq!=0 && $count_aoq_awarded==0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                        
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                    
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po_served!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po_served!=0)){ 
                     
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                    
                        if($on_hold==1){
                               $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                              $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } 

                }
            }*/
      
            if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){
                $data['pending_pr'][] = array(
                    'purpose'=>$this->super_model->select_column_where("pr_head","purpose","pr_id",$pr_id),
                    'enduse'=>$this->super_model->select_column_where("pr_head","enduse","pr_id",$pr_id),
                    'site_pr'=>$this->super_model->select_column_where("pr_details","add_remarks","pr_details_id",$res),
                    'requestor'=>$this->super_model->select_column_where("pr_head","requestor","pr_id",$pr_id),
                    'qty'=>$this->super_model->select_column_where("pr_details","quantity","pr_details_id",$res),
                    'uom'=>$this->super_model->select_column_where("pr_details","uom","pr_details_id",$res),
                    'description'=>$this->super_model->select_column_where("pr_details","item_description","pr_details_id",$res),
                    'status_remarks'=>$status_remarks,
                    'status'=>$status,
                    'ver_date_needed'=>$ver_date_needed,
                    'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$pr_id),
                );
            }
       }

        $this->load->view('reports/pending_pr',$data);
        $this->load->view('template/footer');
   }

   public function search_pending_pr(){
    $this->load->view('template/header');        
    $this->load->view('template/navbar'); 
        $filter = "";
        if(!empty($this->input->post('filter_date_from'))){
            $data['filter_date_from'] = $this->input->post('filter_date_from');
            $filter_date_from = $this->input->post('filter_date_from');
        } else {
            $data['filter_date_from']= "null";
            $filter_date_from= "null";
        }

        if(!empty($this->input->post('filter_date_to'))){
            $data['filter_date_to'] = $this->input->post('filter_date_to');
            $filter_date_to = $this->input->post('filter_date_to');
        } else {
            $data['filter_date_to']= "null";
            $filter_date_to= "null";
        }
        $data['filt'] = $filter_date_from." - ".$filter_date_to;


        $delivered = array();
        foreach($this->super_model->custom_query("SELECT pi.pr_details_id FROM po_dr_items pi INNER JOIN po_dr pd ON pi.dr_id = pd.dr_id WHERE pd.received='1'") AS $dr){
            $delivered[] = $dr->pr_details_id;
        }
         $calendar = array();
        foreach($this->super_model->custom_query("SELECT pr_details_id FROM pr_calendar WHERE ver_date_needed BETWEEN '$filter_date_from' AND '$filter_date_to'") AS $cal){
            $calendar[] = $cal->pr_details_id;
        }

        $pending=array();
        foreach($calendar AS $cl){
            foreach($delivered AS $dl){
                if($cl != $dl){
                    $pending[]= $cl;
                }
            }
        }

       $result= array_unique($pending);
       foreach($result AS $res){
        $pr_id= $this->super_model->select_column_where("pr_details","pr_id","pr_details_id",$res);
        $company_id= $this->super_model->select_column_custom_where("pr_details","company_id","pr_id='$pr_id' AND pr_details_id = '$res'");
        $on_hold= $this->super_model->select_column_custom_where("pr_details","on_hold","pr_id='$pr_id' AND pr_details_id = '$res'");
        $onhold_by= $this->super_model->select_column_custom_where("pr_details","onhold_by","pr_id='$pr_id' AND pr_details_id = '$res'");
        $recom_by= $this->super_model->select_column_custom_where("pr_details","recom_by","pr_id='$pr_id' AND pr_details_id = '$res'");
        $fulfilled_by= $this->super_model->select_column_custom_where("pr_details","fulfilled_by","pr_id='$pr_id' AND pr_details_id = '$res'");
        $for_recom= $this->super_model->select_column_custom_where("pr_details","for_recom","pr_id='$pr_id' AND pr_details_id = '$res'");
        $quantity= $this->super_model->select_column_custom_where("pr_details","quantity","pr_id='$pr_id' AND pr_details_id = '$res'");
        $uom= $this->super_model->select_column_custom_where("pr_details","uom","pr_id='$pr_id' AND pr_details_id = '$res'");


            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $res);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id',$res);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $res);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id",$res, "po_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$res'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$res'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$company_id);
            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by);
            $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$res);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$res);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$res);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$res);
         
            $unserved_qty=0;
            $unserved_uom='';  
            $statuss='';
            $status='';
            $status_remarks='';
            if($sum_po_qty!=0){
                if($sum_po_qty < $quantity){

                      $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
             
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        
                        $served_qty = $this->super_model->select_sum("po_items", "quantity", "pr_details_id",$res);
                        $delivered_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'pr_details_id', $res);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $res);

                  
                        if($cancelled_head_po==0){
                            $unserved_qty = $quantity - $served_qty;
                        }else{
                            $unserved_qty = '';
                        }
                        $unserved_uom =  $served_uom;

                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        $count_po_unserved = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '0' AND cancelled ='0' AND pr_details_id = '$res'");
                        $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '1' AND cancelled ='0' AND pr_details_id = '$res'");
                        $count_po_all = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE  cancelled ='0' AND pr_details_id = '$res'");
                    
                        if($count_po_unserved !=0 && $count_po_served==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= 'PO Issued - Partial<br><br>';
                            }

                            if($on_hold==1){
                                 $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                                $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            }else if($for_recom==1){
                                 $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                                $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                            }else{
                                $status_remarks='';
                            }
                        }else if($count_po_unserved !=0  && $count_po_served!=0 && $cancelled_head_po==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= 'PO Issued - Partial<br><br>';
                                $status .= 'Partially Delivered';
                            }

                            if($on_hold==1){
                                $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                                $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            }else if($for_recom==1){
                                   $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");
                                $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                            }else{
                                $status_remarks='';
                            }
                        } else if(($count_po_unserved == 0 && $count_po_served == $count_po_all) || ($count_po_unserved == 0 && $count_po_served !=0)) {
                        
                            
                            $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                            if($cancelled_head_po!=0){
                               
                                $statuss = 'Partially Delivered';
                                if($on_hold==1){
                                    $status .="On-Hold";
                                }else if($fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status.="Partially Delivered / Cancelled";
                                }
                            }else if($cancelled_items_po==0){
                                if($on_hold==1){
                                    $status .="On-Hold";
                                }else if($fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status .= 'Partially Delivered';
                                }
                            }else {
                                $statuss = 'Partially Delivered';
                                $status.="Cancelled";
                            }
                          
                            foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$res' AND date_received!=''") AS $del){
                               
                                 
                                if($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id!='')){
                                    $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                                    $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }


                                 // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                 $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                }
                                if(empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND ph.po_id = '$del->po_id' AND pi.pr_details_id = '$res'");
                                    $status_remarks.="PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty .")</span>";
                                }
                            }

                        }
                  
                } else {
                    
                    $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                    if($served==0){
                        if($cancelled_items_po==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else{
                                $status .= 'PO Issued';
                            }
                        }else if($cancelled_items_po==0 && $fulfilled_by==1){
                            $status="Delivered by ".$company;
                        }else if($cancelled_items_po==0 && $for_recom==1){
                            $status="For Recom";
                        }else {
                            $statuss = 'PO Issued';
                            $status .= 'Cancelled';
                        }

                        if($on_hold==1){
                            $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else {
                        if($cancelled_items_po==0){
                            $status .= 'Fully Delivered';
                        }else {
                            $statuss = 'Fully Delivered';
                            $status .= 'Cancelled';
                        }
                        $status_remarks='';
                       foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$res' AND date_received!=''") AS $del){
                            $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                            $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }

                     
                             // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                             $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY."<br>";
                        }
                    }

                }
            } else {
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $res);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $res);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $res);
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

                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr_id' AND served = '0' AND pi.pr_details_id = '$res'");
                     $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr_id' AND served = '1' AND pi.pr_details_id = '$res'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$res'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$res'");

                   
                    $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$res'");
                   
                  
                    $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' AND cancelled='0'");
                    
                   $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' AND cancelled='0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$res' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
 
                    if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0){
                      
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      

                        if($on_hold==1){
                             $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            
                            $status_remarks = 'For RFQ'."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'For RFQ'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'For RFQ';
                        }
                    }else if(($count_rfq!=0 && $count_aoq==0 && $count_aoq_awarded==0 && $count_po!=0) || ($count_rfq==0 && $count_aoq==0 && $count_aoq_awarded==0 && $count_po!=0)){
                       
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                               $status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                      

                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                        $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' AND awarded = '0'");
                      
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = 'Canvassing Ongoing'."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'Canvassing Ongoing'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'Canvassing Ongoing';
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' AND awarded = '0'");
                        
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      
                        if($on_hold==1){
                             $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'RFQ Completed - No. of RFQ completed: '.$count_rfq_completed."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                               $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");


                            $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$res' AND saved='1' ");
                         
                        if($on_hold==1){
                            $status .="On-Hold";
                        }else if($fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                    
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                               $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date;
                        }
                    } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){
                        //if($cancelled_items_po==0){
                        if($on_hold==1){
                            $status .="On-Hold";
                        }else if($fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                    
                        if($on_hold==1){
                            $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'For PO - AOQ Done (awarded)' .$aoq_date."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = 'For PO - AOQ Done (awarded)'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'For PO - AOQ Done (awarded)';
                        }
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq!=0 && $count_aoq_awarded==0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                        
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                    
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po_served!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po_served!=0)){ 
                     
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                    
                        if($on_hold==1){
                               $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                              $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$res'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$res'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } 

                }
            }
      
            if($status != 'Cancelled' && $status != 'On-Hold' && $status != 'Fully Delivered'){
                $data['pending_pr'][] = array(
                    'purpose'=>$this->super_model->select_column_where("pr_head","purpose","pr_id",$pr_id),
                    'enduse'=>$this->super_model->select_column_where("pr_head","enduse","pr_id",$pr_id),
                    'site_pr'=>$this->super_model->select_column_where("pr_details","add_remarks","pr_details_id",$res),
                    'requestor'=>$this->super_model->select_column_where("pr_head","requestor","pr_id",$pr_id),
                    'qty'=>$this->super_model->select_column_where("pr_details","quantity","pr_details_id",$res),
                    'uom'=>$this->super_model->select_column_where("pr_details","uom","pr_details_id",$res),
                    'description'=>$this->super_model->select_column_where("pr_details","item_description","pr_details_id",$res),
                    'status_remarks'=>$status_remarks,
                    'status'=>$status,
                    'ver_date_needed'=>$ver_date_needed,
                    'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$pr_id),
                );
            }
       }

        $this->load->view('reports/pending_pr',$data);
        $this->load->view('template/footer');
   }


   public function item_status($pr_details_id){
         $pr_id= $this->super_model->select_column_where("pr_details","pr_id","pr_details_id",$pr_details_id);
        $company_id= $this->super_model->select_column_custom_where("pr_details","company_id","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
         $on_hold= $this->super_model->select_column_custom_where("pr_details","on_hold","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
        $onhold_by= $this->super_model->select_column_custom_where("pr_details","onhold_by","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
        $recom_by= $this->super_model->select_column_custom_where("pr_details","recom_by","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
        $fulfilled_by= $this->super_model->select_column_custom_where("pr_details","fulfilled_by","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
        $for_recom= $this->super_model->select_column_custom_where("pr_details","for_recom","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
         $quantity= $this->super_model->select_column_custom_where("pr_details","quantity","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
         $uom= $this->super_model->select_column_custom_where("pr_details","uom","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");


            $recom_unit_price = $this->super_model->select_column_where('aoq_offers', 'unit_price', 'pr_details_id', $pr_details_id);
            $po_offer_id = $this->super_model->select_column_where('po_items', 'aoq_offer_id', 'pr_details_id',$pr_details_id);
            $po_items_id = $this->super_model->select_column_where('po_items', 'po_items_id', 'pr_details_id', $pr_details_id);
            if($po_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'po_items_id', $po_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('po_items', 'cancel', 'aoq_offer_id', $po_offer_id);
            }
            
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id",$pr_details_id, "po_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('po_head', 'cancelled', 'po_id', $po_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id'");
            $company=$this->super_model->select_column_where("company","company_name","company_id",$company_id);

            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by);
            $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr_details_id);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr_details_id);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr_details_id);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr_details_id);
         
            $unserved_qty=0;
            $unserved_uom='';  
            $statuss='';
            $status='';
            $status_remarks='';
            if($sum_po_qty!=0){
                if($sum_po_qty < $quantity){

                      $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
             
                        $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);
                        
                        $served_qty = $this->super_model->select_sum("po_items", "quantity", "pr_details_id",$pr_details_id);
                        $delivered_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'pr_details_id', $pr_details_id);
                        $served_uom = $this->super_model->select_column_where('po_items', 'uom', 'pr_details_id', $pr_details_id);

                  
                        if($cancelled_head_po==0){
                            $unserved_qty = $quantity - $served_qty;
                        }else{
                            $unserved_qty = '';
                        }
                        $unserved_uom =  $served_uom;

                        $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                        $count_po_unserved = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '0' AND cancelled ='0' AND pr_details_id = '$pr_details_id'");
                        $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE served = '1' AND cancelled ='0' AND pr_details_id = '$pr_details_id'");
                        $count_po_all = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE  cancelled ='0' AND pr_details_id = '$pr_details_id'");
                    
                        if($count_po_unserved !=0 && $count_po_served==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= 'PO Issued - Partial<br><br>';
                            }

                            if($on_hold==1){
                                 $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                                $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            }else if($for_recom==1){
                                 $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                                $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                            }else{
                                $status_remarks='';
                            }
                        }else if($count_po_unserved !=0  && $count_po_served!=0 && $cancelled_head_po==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else{
                                $status .= 'PO Issued - Partial<br><br>';
                                $status .= 'Partially Delivered';
                            }

                            if($on_hold==1){
                                $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                                $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            }else if($for_recom==1){
                                   $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                                $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                            }else{
                                $status_remarks='';
                            }
                        } else if(($count_po_unserved == 0 && $count_po_served == $count_po_all) || ($count_po_unserved == 0 && $count_po_served !=0)) {
                        
                            
                            $date_delivered=  $this->super_model->select_column_where('po_head', 'date_served', 'po_id', $po_id);
                            if($cancelled_head_po!=0){
                               
                                $statuss = 'Partially Delivered';
                                if($on_hold==1){
                                    $status .="On-Hold";
                                }else if($fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status.="Partially Delivered / Cancelled";
                                }
                            }else if($cancelled_items_po==0){
                                if($on_hold==1){
                                    $status .="On-Hold";
                                }else if($fulfilled_by==1){
                                    $status .= "Delivered by ".$company;
                                }else{
                                    $status .= 'Partially Delivered';
                                }
                            }else {
                                $statuss = 'Partially Delivered';
                                $status.="Cancelled";
                            }
                          
                            foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id' AND date_received!=''") AS $del){
                               
                                 
                                if($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id!='')){

                                    $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                                    $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }


                                 // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                 $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY." <span style='font-size:11px; color:green; font-weight:bold'>(". $del->quantity . " ".$del->uom .")</span><br>";
                                }
                                if(empty($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id))){
                                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND ph.po_id = '$del->po_id' AND pi.pr_details_id = '$pr_details_id'");
                                    $status_remarks.="PO Issued <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty .")</span>";
                                }
                            }

                        }
                  
                } else {
                    
                    $count_rfd = $this->super_model->count_custom_where("rfd","po_id = '$po_id'");
                    $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                    if($served==0){
                        if($cancelled_items_po==0){
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else{
                                $status .= 'PO Issued';
                            }
                        }else if($cancelled_items_po==0 && $fulfilled_by==1){
                            $status="Delivered by ".$company;
                        }else if($cancelled_items_po==0 && $for_recom==1){
                            $status="For Recom";
                        }else {
                            $statuss = 'PO Issued';
                            $status .= 'Cancelled';
                        }

                        if($on_hold==1){
                            $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else {
                        if($cancelled_items_po==0){
                            $status .= 'Fully Delivered';
                        }else {
                            $statuss = 'Fully Delivered';
                            $status .= 'Cancelled';
                        }
                        $status_remarks='';
                       foreach($this->super_model->custom_query("SELECT pdr.* FROM po_dr_items pdr INNER JOIN po_dr po ON pdr.dr_id = po.dr_id WHERE pr_details_id = '$pr_details_id' AND date_received!=''") AS $del){
                            
                            $dr_year = $this->super_model->select_column_where('po_dr', 'dr_year', 'dr_id', $del->dr_id);
                            $dr_series = $this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id);
                                    if($dr_year != 0){
                                        $dr_no = $dr_year."-".$dr_series;
                                    }else{
                                        $dr_no = $dr_series;
                                    }


                             // $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('po_dr', 'dr_no', 'dr_id', $del->dr_id)."-".COMPANY."<br>";
                             $status_remarks.=date('m.d.Y', strtotime($this->super_model->select_column_where('po_dr', 'date_received', 'dr_id', $del->dr_id)))  . " - Delivered DR# ".$dr_no."-".COMPANY."<br>";
                        }
                    }

                }
            } else {
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr_details_id);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr_details_id);
                    $statuss = "Cancelled";
                    $status .= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                }else if($cancelled_head_po==1){
                    $cancel_reason = $this->super_model->select_column_where('po_head', 'cancel_reason', 'po_id', $po_id);
                    $cancel_date = $this->super_model->select_column_where('po_head', 'cancelled_date', 'po_id', $po_id);
                    $statuss = "Cancelled";
                    $status .= "Pending";
                    //$status .= "Cancelled";
                    //$status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                } else {

                    $count_po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr_id' AND served = '0' AND pi.pr_details_id = '$pr_details_id'");
                     $count_po_served = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr_id' AND served = '1' AND pi.pr_details_id = '$pr_details_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM po_items pi INNER JOIN po_head ph ON  ph.po_id = pi.po_id WHERE ph.cancelled = '0' AND pi.pr_details_id = '$pr_details_id'");

                   
                    $count_rfq = $this->super_model->count_custom_query("SELECT rfq_details_id FROM rfq_details WHERE pr_details_id = '$pr_details_id'");
                   
                  
                    $count_rfq_completed = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' AND cancelled='0'");
                    
                   $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' AND cancelled='0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE ao.pr_details_id= '$pr_details_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'");
 
                    if($count_rfq==0 && $count_aoq_awarded==0  && $count_po==0){
                      
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      

                        if($on_hold==1){
                             $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            
                            $status_remarks = 'For RFQ'."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = 'For RFQ'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'For RFQ';
                        }
                    }else if(($count_rfq!=0 && $count_aoq==0 && $count_aoq_awarded==0 && $count_po!=0) || ($count_rfq==0 && $count_aoq==0 && $count_aoq_awarded==0 && $count_po!=0)){
                       
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                               $status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                      

                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed == 0 && $count_aoq_awarded==0  && $count_po==0){
                        $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' AND awarded = '0'");
                      
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $status_remarks = 'Canvassing Ongoing'."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                             $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = 'Canvassing Ongoing'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'Canvassing Ongoing';
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq==0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' AND awarded = '0'");
                        
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= 'Pending';
                            }
                      
                        if($on_hold==1){
                             $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = 'RFQ Completed - No. of RFQ completed: '.$count_rfq_completed."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){

                               $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");


                            $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'RFQ Completed - No. of RFQ completed: ' .  $count_rfq_completed;
                        }
                    } else if($count_rfq!=0 && $count_rfq_completed != 0 && $count_aoq!=0  && $count_aoq_awarded==0  && $count_po==0){
                            $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr_details_id' AND saved='1' ");
                         
                        if($on_hold==1){
                            $status .="On-Hold";
                        }else if($fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                    
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                               $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'AOQ Done - For TE - ' .$aoq_date;
                        }
                    } else if($count_rfq!=0 && $count_aoq_awarded!=0  && $count_po==0){
                        //if($cancelled_items_po==0){
                        if($on_hold==1){
                            $status .="On-Hold";
                        }else if($fulfilled_by==1){
                            $status .= "Delivered by ".$company;
                        }else if($for_recom==1){
                            $status .= "For Recom";
                        }else{
                            $status .= 'Pending';
                        }
                    
                        if($on_hold==1){
                            $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = 'For PO - AOQ Done (awarded)' .$aoq_date."<br> -On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = 'For PO - AOQ Done (awarded)'."<br> -Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = 'For PO - AOQ Done (awarded)';
                        }
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po!=0) || ($count_rfq!=0 && $count_aoq_awarded==0 && $count_po!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po!=0)){ 
                        
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "PO Issued  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                    
                        if($on_hold==1){
                              $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                            $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } else if(($count_rfq!=0 && $count_aoq_awarded!=0 && $count_po_served!=0) || ($count_rfq==0 && $count_aoq_awarded==0 && $count_po_served!=0)){ 
                     
                            if($on_hold==1){
                                $status .="On-Hold";
                            }else if($fulfilled_by==1){
                                $status .= "Delivered by ".$company;
                            }else if($for_recom==1){
                                $status .= "For Recom";
                            }else{
                                $status .= "Partially Delivered  <span style='font-size:11px; color:green; font-weight:bold'>(". $sum_po_issued_qty . " ".$uom .")</span>";
                            }
                    
                        if($on_hold==1){
                               $onhold_date= $this->super_model->select_column_custom_where("pr_details","onhold_date","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $status_remarks = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                        }else if($for_recom==1){
                              $recom_date_from= $this->super_model->select_column_custom_where("pr_details","recom_date_from","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");
                            $recom_date_to= $this->super_model->select_column_custom_where("pr_details","recom_date_to","pr_id='$pr_id' AND pr_details_id = '$pr_details_id'");

                            $status_remarks = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                        }else{
                            $status_remarks = '';
                        }
                    } 

                }
            }

        return $status;
   }

   public function jor_item_status($jor_items_id,$controller_name,$joi_id){
            //$jor_items_id='2626';
            $statuss='';
            $status='';
            $status_remarks='';
            
            $item_cancelled = $this->super_model->select_column_where("jor_items", "cancelled","jor_items_id",$jor_items_id); // check if item is cancelled
            $po_cancelled = $this->super_model->select_column_where("joi_head", "cancelled","joi_id",$joi_id); // check if po is cancelled
            $item_on_hold = $this->super_model->select_column_where("jor_items", "on_hold","jor_items_id",$jor_items_id); // check if item is on hold
            $item_for_recom = $this->super_model->select_column_where("jor_items", "for_recom","jor_items_id",$jor_items_id); // check if item is for recom
            $item_fulfilled_by_mnl = $this->super_model->select_column_where("jor_items", "fulfilled_by","jor_items_id",$jor_items_id); // check if item is fulfilled by MNL

            $count_rfq = $this->super_model->count_custom_query("SELECT rd.jo_rfq_details_id FROM jo_rfq_details rd INNER JOIN jo_rfq_head rh WHERE rd.jor_items_id = '$jor_items_id' AND rh.saved = '1' AND rh.cancelled = '0'"); // check if item is already in the RFQ process

            $count_aoq = $this->super_model->count_custom_query("SELECT ah.jor_aoq_id FROM jor_aoq_head ah INNER JOIN jor_aoq_offers ai ON ah.jor_aoq_id = ai.jor_aoq_id WHERE ai.jor_items_id= '$jor_items_id' AND saved='1' AND cancelled='0'"); // check if item is already in the AOQ process but not awarded

            $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.jor_aoq_id FROM jor_aoq_head ah INNER JOIN jor_aoq_offers ao ON ah.jor_aoq_id = ao.jor_aoq_id WHERE ao.jor_items_id= '$jor_items_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'"); // check if item is already in the AOQ process but already awarded

            $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0'  AND served = '0' AND pi.jor_items_id = '$jor_items_id'"); //checks if item has already PO but not yet served or delivered
            $count_po_served_draft = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0'  AND served = '1'  and draft = '1' AND pi.jor_items_id = '$jor_items_id'"); //checks if item has already PO but already served or delivered

            $count_po_draft = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0'  AND served = '0' and draft = '1' AND pi.jor_items_id = '$jor_items_id'"); //checks if item has already PO but not yet served or delivered
            $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0'  AND served = '1' AND pi.jor_items_id = '$jor_items_id'"); //checks if item has already PO but already served or delivered
         
            $pr_qty = $this->super_model->select_column_where("jor_items", "quantity","jor_items_id",$jor_items_id); // gets the PR qty of the item

            $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$jor_items_id'"); // gets the total received qty of the item
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$jor_items_id'"); // gets the total delivered qty of the item
            $po_qty = $this->super_model->select_column_where("joi_items","delivered_quantity","jor_items_id",$jor_items_id);
            $po_rec_qty = $this->super_model->select_column_where("joi_items","quantity","jor_items_id",$jor_items_id);
            $draft = $this->super_model->select_column_where("joi_head","draft","joi_id",$joi_id);
            if(empty($sum_received_qty)){
                $sum_received_qty = 0;
            } else {
                $sum_received_qty = $sum_received_qty;
            }

            if($item_cancelled == 1 || $po_cancelled==1){
                $status= "Cancelled";
                $status_remarks =  "";
            } 

            if($pr_qty > $sum_received_qty || $sum_received_qty == 0){
            
                if($item_cancelled == 1 || $po_cancelled==1){
                     $cancel_reason = $this->super_model->select_column_where('jor_items', 'cancelled_reason', 'jor_items_id', $jor_items_id);
                    $cancel_date = $this->super_model->select_column_where('jor_items', 'cancelled_date', 'jor_items_id', $jor_items_id);
                   
                    $status= "Cancelled";
                    $status_remarks =  "<span style='color:red'>".$cancel_reason ." " . date('m.d.y', strtotime($cancel_date))."</span>";
                  

                } else {
                    if($item_for_recom == 1){
                        $recom_date_from = $this->super_model->select_column_where("jor_items", "recom_date_from","jor_items_id",$jor_items_id); 
                        $recom_date_to = $this->super_model->select_column_where("jor_items", "recom_date_to","jor_items_id",$jor_items_id); 
                        $recom_by_id = $this->super_model->select_column_where("jor_items", "recom_by","jor_items_id",$jor_items_id); 
                        $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$recom_by_id);
                        $statuss = "-Recom By: ".$recom_by."<br> -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                    }

                    if($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0){
                         
                         if($item_on_hold == 1){
                            $status = 'On-Hold';
                            $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
              
                            $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                            $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            $status_remarks = 'For RFQ <br>' . $statuss;
                         } else {
                            if($item_fulfilled_by_mnl == 1){
                                $status = 'Delivered by CENPRI-MNL';
                                 $status_remarks = 'For RFQ <br>' . $statuss;
                            } else {
                                $status = 'Pending';
                                $status_remarks = 'For RFQ <br>' . $statuss;
                            }
                            
                         }


                    } else if($count_rfq != 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0){
                         if($item_on_hold == 1){
                            $status = 'On-Hold';
                            $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
              
                            $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                            $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            $status_remarks = 'Canvassing Ongoing  <br>' . $statuss;
                         } else {
                             $status = 'Pending';
                             $status_remarks = 'Canvassing Ongoing <br>' . $statuss;
                        }

                        
                    }  else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0 && $draft!=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0 && $draft==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0 && $count_po_served ==0 && $draft!=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po_draft != 0 && $count_po_served_draft ==0 && $draft!=0)){
                         if($item_on_hold == 1){
                            $status = 'On-Hold';
                            $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
              
                            $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                            $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            $status_remarks = 'JO AOQ Done - For TE - '. $aoq_date .'<br>' . $statuss;
                         } else {
                             $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM jor_aoq_head ah INNER JOIN jor_aoq_items ai ON ah.jor_aoq_id = ai.jor_aoq_id WHERE ai.jor_items_id= '$jor_items_id' AND saved='1' ");
                             $status = 'Pending';
                             $status_remarks = 'JO AOQ Done - For TE - '. $aoq_date .'<br>' . $statuss;

                        }
                      
                    } else if($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po == 0 && $count_po_served ==0){

                        if($item_on_hold == 1){
                            $status = 'On-Hold';
                            $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
              
                            $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                            $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                            $status_remarks = 'For JO - AOQ Done (awarded) <br>' . $statuss;
                         } else {
                             $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM jor_aoq_head ah INNER JOIN jor_aoq_items ai ON ah.jor_aoq_id = ai.jor_aoq_id WHERE ai.jor_items_id= '$jor_items_id' AND saved='1' ");
                             $status = 'Pending';
                             $status_remarks = 'For JO - AOQ Done (awarded) <br>' . $statuss;

                        }


                      
                    } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0 && $count_po_served ==0) || 
                        ($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0)) {
                        $uom = $this->super_model->select_column_where("jor_items", "uom","jor_items_id",$jor_items_id);
                        $status = "JO Issued";
                        /*if($pr_qty < $sum_delivered_qty){
                             $status = 'JO Issued - Partial';
                        } else {*/
                             
                        //}    
                    }
                }

            }

            $data=array(
                'status'=>$status,
                'remarks'=>$status_remarks
            ); 
              // echo $status . " - " . $status_remarks;
            return $data;
    }

    public function jor_item_status_export($jor_items_id,$controller_name,$joi_id){
            //$jor_items_id='2626';
            $statuss='';
            $status='';
            $status_remarks='';
            
            $item_cancelled = $this->super_model->select_column_where("jor_items", "cancelled","jor_items_id",$jor_items_id); // check if item is cancelled
            $po_cancelled = $this->super_model->select_column_where("joi_head", "cancelled","joi_id",$joi_id); // check if po is cancelled
            $item_on_hold = $this->super_model->select_column_where("jor_items", "on_hold","jor_items_id",$jor_items_id); // check if item is on hold
            $item_for_recom = $this->super_model->select_column_where("jor_items", "for_recom","jor_items_id",$jor_items_id); // check if item is for recom
            $item_fulfilled_by_mnl = $this->super_model->select_column_where("jor_items", "fulfilled_by","jor_items_id",$jor_items_id); // check if item is fulfilled by MNL

            $count_rfq = $this->super_model->count_custom_query("SELECT rd.jo_rfq_details_id FROM jo_rfq_details rd INNER JOIN jo_rfq_head rh WHERE rd.jor_items_id = '$jor_items_id' AND rh.saved = '1' AND rh.cancelled = '0'"); // check if item is already in the RFQ process

            $count_aoq = $this->super_model->count_custom_query("SELECT ah.jor_aoq_id FROM jor_aoq_head ah INNER JOIN jor_aoq_offers ai ON ah.jor_aoq_id = ai.jor_aoq_id WHERE ai.jor_items_id= '$jor_items_id' AND saved='1' AND cancelled='0'"); // check if item is already in the AOQ process but not awarded

            $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.jor_aoq_id FROM jor_aoq_head ah INNER JOIN jor_aoq_offers ao ON ah.jor_aoq_id = ao.jor_aoq_id WHERE ao.jor_items_id= '$jor_items_id' AND saved='1' AND ao.recommended = '1' AND cancelled='0'"); // check if item is already in the AOQ process but already awarded

            $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0'  AND served = '0' AND pi.jor_items_id = '$jor_items_id'"); //checks if item has already PO but not yet served or delivered
            $count_po_served_draft = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0'  AND served = '1'  and draft = '1' AND pi.jor_items_id = '$jor_items_id'"); //checks if item has already PO but already served or delivered

            $count_po_draft = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0'  AND served = '0' and draft = '1' AND pi.jor_items_id = '$jor_items_id'"); //checks if item has already PO but not yet served or delivered
            $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0'  AND served = '1' AND pi.jor_items_id = '$jor_items_id'"); //checks if item has already PO but already served or delivered
         
            $pr_qty = $this->super_model->select_column_where("jor_items", "quantity","jor_items_id",$jor_items_id); // gets the PR qty of the item

            $sum_received_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$jor_items_id'"); // gets the total received qty of the item
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$jor_items_id'"); // gets the total delivered qty of the item
            $po_qty = $this->super_model->select_column_where("joi_items","delivered_quantity","jor_items_id",$jor_items_id);
            $po_rec_qty = $this->super_model->select_column_where("joi_items","quantity","jor_items_id",$jor_items_id);
            $draft = $this->super_model->select_column_where("joi_head","draft","joi_id",$joi_id);
            if(empty($sum_received_qty)){
                $sum_received_qty = 0;
            } else {
                $sum_received_qty = $sum_received_qty;
            }
           
            if($item_cancelled == 1 || $po_cancelled==1){
                $status= "Cancelled";
                $status_remarks =  "";
            } 
            //echo $pr_qty ." < " . $sum_received_qty;
            if($pr_qty > $sum_received_qty || $sum_received_qty == 0){
            
                if($item_cancelled == 1){
                     $cancel_reason = $this->super_model->select_column_where('jor_items', 'cancelled_reason', 'jor_items_id', $jor_items_id);
                    $cancel_date = $this->super_model->select_column_where('jor_items', 'cancelled_date', 'jor_items_id', $jor_items_id);
                   
                    $status= "Cancelled";
                    $status_remarks =  $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                  

                } else {

               /*    
                    if($item_on_hold == 1){
                         $status = 'On-Hold';
                         $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                         $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
          
                        $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                        $statuss = "-On Hold Date: ".$onhold_date."<br> -On Hold By: ".$onhold_by;
                    }*/

                    if($item_for_recom == 1){
                        $recom_date_from = $this->super_model->select_column_where("jor_items", "recom_date_from","jor_items_id",$jor_items_id); 
                        $recom_date_to = $this->super_model->select_column_where("jor_items", "recom_date_to","jor_items_id",$jor_items_id); 
                        $recom_by_id = $this->super_model->select_column_where("jor_items", "recom_by","jor_items_id",$jor_items_id); 
                        $recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$recom_by_id);
                        $statuss = "-Recom By: ".$recom_by."\n -Recom Date: ".date("M j, Y",strtotime($recom_date_from))." To ".date("M j, Y",strtotime($recom_date_to));
                    }
                   
                    //if($count_po_served == 0){
                        
                       // echo $count_rfq ." aoq =  " . $count_aoq . ", award = ". $count_aoq_awarded . ", po = ".$count_po . ", served = ". $count_po_served;

                        if($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0){
                             
                             if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."\n -On Hold By: ".$onhold_by;
                                $status_remarks = "For RFQ \n" . $statuss;
                             } else {
                                if($item_fulfilled_by_mnl == 1){
                                    $status = 'Delivered by CENPRI-MNL';
                                     $status_remarks = "For RFQ \n" . $statuss;
                                } else {
                                    $status = 'Pending';
                                    $status_remarks = "For RFQ \n" . $statuss;
                                }
                                
                             }


                        } else if($count_rfq != 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0){
                             if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."\n -On Hold By: ".$onhold_by;
                                $status_remarks = "Canvassing Ongoing \n" . $statuss;
                             } else {
                                 $status = 'Pending';
                                 $status_remarks = "Canvassing Ongoing \n" . $statuss;
                            }

                            
                        } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po == 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0 && $draft!=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0 && $draft==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0 && $count_po_served ==0 && $draft!=0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po_draft != 0 && $count_po_served_draft ==0 && $draft!=0)){
                            if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."\n -On Hold By: ".$onhold_by;
                                $status_remarks = 'JO AOQ Done - For TE - '. $aoq_date ."\n" . $statuss;
                             } else {
                                 $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM jor_aoq_head ah INNER JOIN jor_aoq_items ai ON ah.jor_aoq_id = ai.jor_aoq_id WHERE ai.jor_items_id= '$jor_items_id' AND saved='1' ");
                                 $status = 'Pending';
                                 $status_remarks = 'JO AOQ Done - For TE - '. $aoq_date ."\n" . $statuss;

                            }
                          
                        } else if($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po == 0 && $count_po_served ==0){

                            if($item_on_hold == 1){
                                $status = 'On-Hold';
                                $onhold_by_id = $this->super_model->select_column_where("jor_items", "onhold_by","jor_items_id",$jor_items_id); // get onhold date
                                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$onhold_by_id);
                  
                                $onhold_date = $this->super_model->select_column_where("jor_items", "onhold_date","jor_items_id",$jor_items_id); // get onhold date
                                $statuss = "-On Hold Date: ".$onhold_date."\n -On Hold By: ".$onhold_by;
                                $status_remarks = "For JO - AOQ Done (awarded) \n" . $statuss;
                             } else {
                                 $aoq_date = $this->super_model->custom_query_single("aoq_date","SELECT aoq_date FROM jor_aoq_head ah INNER JOIN jor_aoq_items ai ON ah.jor_aoq_id = ai.jor_aoq_id WHERE ai.jor_items_id= '$jor_items_id' AND saved='1' ");
                                 $status = 'Pending';
                                 $status_remarks = "For JO - AOQ Done (awarded) \n" . $statuss;

                            }
                        } else if(($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0 && $count_po_served ==0) || 
                            ($count_rfq == 0 && $count_aoq == 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0 && $count_po_served ==0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded == 0 && $count_po != 0) || ($count_rfq != 0 && $count_aoq != 0 && $count_aoq_awarded != 0 && $count_po != 0)) {
                            $uom = $this->super_model->select_column_where("jor_items", "uom","jor_items_id",$jor_items_id);
                            $status = "JO Issued";
                        }     
                    }
              //  }

            }

            $data=array(
                'status'=>$status,
                'remarks'=>$status_remarks
            ); 
              // echo $status . " - " . $status_remarks;
            return $data;
    }

   public function jor_report(){
        $controller_name=$this->uri->segment(2);
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
        $data['company']=$this->super_model->select_all_order_by("company","company_name","ASC");
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        $data['terms']=$this->super_model->select_all_order_by("terms","terms","ASC");
        $data['proj_act']=$this->super_model->select_custom_where("project_activity","status='Active' ORDER BY proj_activity ASC");
        foreach($this->super_model->custom_query("SELECT ji.*, jh.* FROM jor_items ji INNER JOIN jor_head jh ON ji.jor_id = jh.jor_id WHERE jh.date_prepared LIKE '$date%'") AS $pr){
            $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $pr->jor_items_id);
            $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $pr->jor_items_id);
            $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $pr->jor_items_id);
            if($joi_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
            }
            $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $pr->jor_items_id, "joi_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
            //$company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->onhold_by);
            /*$recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr->pr_details_id);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr->pr_details_id);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr->pr_details_id);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr->pr_details_id);*/
            $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$pr->jor_id' AND served = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
             $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$pr->jor_id' AND served = '1' AND pi.jor_items_id = '$pr->jor_items_id'");
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
            $unserved_qty=0;
            $unserved_uom='';  
            $stat = $this->jor_item_status($pr->jor_items_id,$controller_name,$joi_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
            $revised='';
            $current_qty='';
            $revision_no = $this->super_model->select_column_where("joi_head","revision_no","joi_id",$joi_id);
            $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$pr->jor_items_id'");
            $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$pr->jor_items_id'");
            if($revision_no!=0){
                foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM joi_items_revised WHERE joi_id = '$joi_id' AND jor_items_id = '$pr->jor_items_id' GROUP BY revision_no") AS $rev){
                    if($rev->revision_no == 0){
                         $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                    } else {
                         $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                         $qty = $this->super_model->select_column_custom_where("joi_items","delivered_quantity","joi_id='$joi_id' AND jor_items_id='$pr->jor_items_id'");
                         $uom = $this->super_model->select_column_custom_where("joi_items","uom","joi_id='$joi_id' AND jor_items_id='$pr->jor_items_id'");
                         $current_qty = "Current Qty: ".$qty." ".$uom;
                    }
                }
            }

            $jor_id = $pr->jor_id;
            $jo=$this->super_model->select_column_where('jor_head','jo_no','jor_id',$pr->jor_id);
            if($jo!=''){
                $jo_no = $jo;
            }else{
                $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
            }

            $materials_offer = $this->super_model->select_column_where('joi_items', 'materials_offer', 'jor_items_id', $pr->jor_items_id);
            $materials_qty = $this->super_model->select_column_where('joi_items', 'materials_qty', 'jor_items_id', $pr->jor_items_id);
            $materials_unitprice = $this->super_model->select_column_where('joi_items', 'materials_unitprice', 'jor_items_id', $pr->jor_items_id);
            $materials_amount = $this->super_model->select_column_where('joi_items', 'materials_amount', 'jor_items_id', $pr->jor_items_id);
            $materials_received = $this->super_model->select_column_where('joi_items', 'materials_received', 'jor_items_id', $pr->jor_items_id);
            $materials_currency = $this->super_model->select_column_where('joi_items', 'materials_currency', 'jor_items_id', $pr->jor_items_id);
            $materials_unit = $this->super_model->select_column_where('joi_items', 'materials_unit', 'jor_items_id', $pr->jor_items_id);
            $data['jor'][] = array(
                'joi_offer_id'=>$joi_offer_id,
                'jor_items_id'=>$pr->jor_items_id,
                'jor_id'=>$jor_id,
                'date_prepared'=>$pr->date_prepared,
                'jo_request'=>$pr->jo_request,
                'jo_no'=>$jo_no,
                'user_jo_no'=>$pr->user_jo_no,
                'purpose'=>$pr->purpose,
                'department'=>$pr->department,
                'requestor'=>$pr->requested_by,
                'grouping_id'=>$pr->grouping_id,
                'scope_of_work'=>$pr->scope_of_work,
                //'item_no'=>$pr->item_no,
                'qty'=>$pr->quantity,
                'revised_qty'=>$revised,
                'current_qty'=>$current_qty,
                'uom'=>$pr->uom,
                'status'=>$status,
                'status_remarks'=>$status_remarks,
                'unserved_qty'=>$unserved_qty,
                'unserved_uom'=>$unserved_uom,
                'remarks'=>$pr->add_remarks,
                'delivery_date'=>$pr->delivery_date,
                'completion_date'=>$pr->completion_date,
                'duration'=>$pr->duration,
                'unit_cost'=>$pr->unit_cost,
                'cancelled_reason'=>$pr->cancelled_reason,
                'cancel_remarks'=>$pr->cancel_remarks,
                'cancelled'=>$pr->cancelled,
                'cancelled_items_po'=>$cancelled_items_po,
                'on_hold'=>$pr->on_hold, 
                'materials_offer'=>$materials_offer,
                'materials_qty'=>$materials_qty,
                'materials_unitprice'=>$materials_unitprice,
                'materials_amount'=>$materials_amount,
                'materials_received'=>$materials_received,
                'materials_currency'=>$materials_currency,
                'materials_unit'=>$materials_unit,
                'jor_status'=>$jor_status,
                'jor_status_remarks'=>$jor_status_remarks,
                /*'company'=>$this->super_model->select_column_where('company','company_name','company_id',$pr->company_id),
                'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$pr->vendor_id),
                'company_id'=>$pr->company_id,
                'supplier_id'=>$pr->vendor_id,
                'date_delivered'=>$pr->date_delivered,
                'unit_price'=>$pr->unit_price,
                'qty_delivered'=>$pr->qty_delivered,
                'fulfilled_by'=>$pr->fulfilled_by,
                'for_recom'=>$pr->for_recom,
                'recom_by'=>$this->super_model->select_column_where('users','fullname','user_id',$pr->recom_by),
                'recom_date_from'=>$pr->recom_date_from,
                'recom_date_to'=>$pr->recom_date_to,
                'recom_unit_price'=>$recom_unit_price,
                'ver_date_needed'=>$ver_date_needed,
                'pr_calendar_id'=>$pr_calendar_id,
                'estimated_price'=>$estimated_price,
                'proj_act_id'=>$proj_act_id,  */             
            );
        }
        $this->load->view('template/header');        
        $this->load->view('reports/jor_report',$data);
        $this->load->view('template/footer');   
    }

    public function search_jor(){
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

        if(!empty($this->input->post('jo_request'))){
            $data['jo_request'] = $this->input->post('jo_request');
        } else {
            $data['jo_request']= "null";
        }

        if(!empty($this->input->post('project_title'))){
            $data['project_title'] = $this->input->post('project_title');
        } else {
            $data['project_title']= "null";
        }

        if(!empty($this->input->post('jor_no'))){
            $data['jor_no'] = $this->input->post('jor_no');
        } else {
            $data['jor_no'] = "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
        } else {
            $data['requestor'] = "null";
        } 

        if(!empty($this->input->post('scope_of_work'))){
            $data['scope_of_work'] = $this->input->post('scope_of_work');
        } else {
            $data['scope_of_work'] = "null";
        } 

        $sql="";
        $filter = " ";

        if(!empty($this->input->post('date_receive'))){
            $date_receive = $this->input->post('date_receive');
            $sql.=" jh.date_prepared LIKE '%$date_receive%' AND";
            $filter .= "Date Received/Emailed - ".$date_receive.", ";
        }

        if(!empty($this->input->post('jo_request'))){
            $jo_request = $this->input->post('jo_request');
            $sql.=" jh.jo_request LIKE '%$jo_request%' AND";
            $filter .= "JOB Order Request - ".$jo_request.", ";
        }

        if(!empty($this->input->post('project_title'))){
            $project_title = $this->input->post('project_title');
            $sql.=" jh.purpose LIKE '%$project_title%' AND";
            $filter .= "Project Title - ".$project_title.", ";
        }

        if(!empty($this->input->post('jor_no'))){
            $jor_no = $this->input->post('jor_no');
            $sql.=" (jh.jo_no LIKE '%$jor_no%' || jh.user_jo_no LIKE '%$jor_no%') AND";
            $filter .= "JOR No. - ".$jor_no.", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" jh.requested_by LIKE '%$requestor%' AND";
            $filter .= "Requestor - ".$requestor.", ";
        }

        if(!empty($this->input->post('scope_of_work'))){
            $scope_of_work = $this->input->post('scope_of_work');
            $sql.=" ji.scope_of_work LIKE '%$scope_of_work%' AND";
            $filter .= "Scope of Work - ".$scope_of_work.", ";
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
        $controller_name='jor_report';
        $data['company']=$this->super_model->select_all_order_by("company","company_name","ASC");
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        $data['terms']=$this->super_model->select_all_order_by("terms","terms","ASC");
        $data['proj_act']=$this->super_model->select_custom_where("project_activity","status='Active' ORDER BY proj_activity ASC");
        foreach($this->super_model->custom_query("SELECT ji.*, jh.* FROM jor_items ji INNER JOIN jor_head jh ON ji.jor_id = jh.jor_id WHERE ".$query) AS $pr){
            $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $pr->jor_items_id);
            $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $pr->jor_items_id);
            $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $pr->jor_items_id);
            if($joi_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
            }
            $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $pr->jor_items_id, "joi_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
            //$company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
            $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->onhold_by);
            /*$recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->recom_by);
            $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr->pr_details_id);
            $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr->pr_details_id);
            $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr->pr_details_id);
            $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr->pr_details_id);*/
            $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$pr->jor_id' AND served = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
             $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$pr->jor_id' AND served = '1' AND pi.jor_items_id = '$pr->jor_items_id'");
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
            $unserved_qty=0;
            $unserved_uom='';  
            $stat = $this->jor_item_status($pr->jor_items_id,$controller_name,$joi_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
            $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$pr->jor_items_id'");
            $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$pr->jor_items_id'");
            $revised='';
            $current_qty='';
            $revision_no = $this->super_model->select_column_where("joi_head","revision_no","joi_id",$joi_id);
            if($revision_no!=0){
                foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM joi_items_revised WHERE joi_id = '$joi_id' AND jor_items_id = '$pr->jor_items_id' GROUP BY revision_no") AS $rev){
                    if($rev->revision_no == 0){
                         $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                    } else {
                         $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."<br>";
                         $qty = $this->super_model->select_column_custom_where("joi_items","delivered_quantity","joi_id='$joi_id' AND jor_items_id='$pr->jor_items_id'");
                         $uom = $this->super_model->select_column_custom_where("joi_items","uom","joi_id='$joi_id' AND jor_items_id='$pr->jor_items_id'");
                         $current_qty = "Current Qty: ".$qty." ".$uom;
                    }
                }
            }

            $jor_id = $pr->jor_id;
            $jo=$this->super_model->select_column_where('jor_head','jo_no','jor_id',$pr->jor_id);
            if($jo!=''){
                $jo_no = $jo;
            }else{
                $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
            }
            $materials_offer = $this->super_model->select_column_where('joi_items', 'materials_offer', 'jor_items_id', $pr->jor_items_id);
            $materials_qty = $this->super_model->select_column_where('joi_items', 'materials_qty', 'jor_items_id', $pr->jor_items_id);
            $materials_unitprice = $this->super_model->select_column_where('joi_items', 'materials_unitprice', 'jor_items_id', $pr->jor_items_id);
            $materials_amount = $this->super_model->select_column_where('joi_items', 'materials_amount', 'jor_items_id', $pr->jor_items_id);
            $materials_received = $this->super_model->select_column_where('joi_items', 'materials_received', 'jor_items_id', $pr->jor_items_id);
            $materials_currency = $this->super_model->select_column_where('joi_items', 'materials_currency', 'jor_items_id', $pr->jor_items_id);
            $materials_unit = $this->super_model->select_column_where('joi_items', 'materials_unit', 'jor_items_id', $pr->jor_items_id);
            $data['jor'][] = array(
                'joi_offer_id'=>$joi_offer_id,
                'jor_items_id'=>$pr->jor_items_id,
                'jor_id'=>$jor_id,
                'date_prepared'=>$pr->date_prepared,
                'jo_request'=>$pr->jo_request,
                'jo_no'=>$jo_no,
                'user_jo_no'=>$pr->user_jo_no,
                'purpose'=>$pr->purpose,
                'department'=>$pr->department,
                'requestor'=>$pr->requested_by,
                'grouping_id'=>$pr->grouping_id,
                'scope_of_work'=>$pr->scope_of_work,
                //'item_no'=>$pr->item_no,
                'qty'=>$pr->quantity,
                'revised_qty'=>$revised,
                'current_qty'=>$current_qty,
                'uom'=>$pr->uom,
                'status'=>$status,
                'status_remarks'=>$status_remarks,
                'unserved_qty'=>$unserved_qty,
                'unserved_uom'=>$unserved_uom,
                'remarks'=>$pr->add_remarks,
                'cancel_remarks'=>$pr->cancel_remarks,
                'delivery_date'=>$pr->delivery_date,
                'completion_date'=>$pr->completion_date,
                'duration'=>$pr->duration,
                'unit_cost'=>$pr->unit_cost,
                'cancelled_reason'=>$pr->cancelled_reason,
                'cancelled'=>$pr->cancelled,
                'cancelled_items_po'=>$cancelled_items_po,
                'on_hold'=>$pr->on_hold, 
                'materials_offer'=>$materials_offer,
                'materials_qty'=>$materials_qty,
                'materials_unitprice'=>$materials_unitprice,
                'materials_amount'=>$materials_amount,
                'materials_received'=>$materials_received,
                'materials_currency'=>$materials_currency,
                'materials_unit'=>$materials_unit, 
                'jor_status'=>$jor_status, 
                'jor_status_remarks'=>$jor_status_remarks, 
                /*'company'=>$this->super_model->select_column_where('company','company_name','company_id',$pr->company_id),
                'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$pr->vendor_id),
                'company_id'=>$pr->company_id,
                'supplier_id'=>$pr->vendor_id,
                'date_delivered'=>$pr->date_delivered,
                'unit_price'=>$pr->unit_price,
                'qty_delivered'=>$pr->qty_delivered,
                'fulfilled_by'=>$pr->fulfilled_by,
                'for_recom'=>$pr->for_recom,
                'recom_by'=>$this->super_model->select_column_where('users','fullname','user_id',$pr->recom_by),
                'recom_date_from'=>$pr->recom_date_from,
                'recom_date_to'=>$pr->recom_date_to,
                'recom_unit_price'=>$recom_unit_price,
                'ver_date_needed'=>$ver_date_needed,
                'pr_calendar_id'=>$pr_calendar_id,
                'estimated_price'=>$estimated_price,
                'proj_act_id'=>$proj_act_id,  */             
            );
        }
        $this->load->view('template/header');        
        $this->load->view('reports/jor_report',$data);
        $this->load->view('template/footer');   
    
    }

    public function insert_jo_changestatus(){
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
            if($this->super_model->update_where("jor_items", $data, "jor_items_id",$onhold)){
                echo "<script>alert('Successfully Changed Status!');</script>"; 
                echo "<script>window.location = '".base_url()."reports/jor_report/".$year."/".$month."';</script>";
            }
        }

        for($y=0;$y<$count_proceed;$y++){
            $proceed = $this->input->post('proceed['.$y.']');
            $data_proceed=array(
                'on_hold'=>0,
            );
            if($this->super_model->update_where("jor_items", $data_proceed, "jor_items_id",$proceed)){
                echo "<script>alert('Successfully Changed Status!');</script>"; 
                echo "<script>window.location = '".base_url()."reports/jor_report/".$year."/".$month."';</script>";
            }
        }
    }

    public function add_jo_remarks(){
        $joi_offer_id =$this->input->post('joi_offer_id');
        $jor_id =$this->input->post('jor_id');
        $jor_items_id =$this->input->post('jor_items_id');
        $status =$this->input->post('status');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $remarks=$this->input->post('remarks');
        $cancel_remarks=$this->input->post('cancel_remarks');
        $cancel=$this->input->post('cancel');
        $remark_date = date('Y-m-d H:i:s');

        $data=array(
            'add_remarks'=>$remarks,
            'remark_date'=>$remark_date,
            'remark_by'=>$_SESSION['user_id']
        );
        $this->super_model->update_where("jor_items", $data, "jor_id", $jor_id);
        $data=array(
            'cancel_remarks'=>$cancel_remarks,
        );
        $this->super_model->update_where("jor_items", $data, "jor_items_id", $jor_items_id);
        if($status == 'Partially Delivered'){
         
                if($cancel!=0){
                    $joi_id = $this->super_model->select_column_where("joi_items",'joi_id','jor_items_id',$jor_items_id);
                    $aoq_offer = $this->super_model->select_column_where("joi_items",'jor_aoq_offer_id','jor_items_id',$jor_items_id);
                    $data_jo=array(
                        'cancel'=>$cancel,
                        'cancelled_by'=>$_SESSION['user_id'],
                        'cancelled_date'=>date('Y-m-d'),
                    );
                    $this->super_model->update_custom_where("joi_items", $data_jo, "jor_id = '$jor_id' AND jor_items_id = '$jor_items_id'");
                }
                redirect(base_url().'reports/jor_report/'.$year.'/'.$month);
            
        } else {
            echo "<script>alert('You can only use this cancel button for partially delivered items.');
            window.location = '".base_url()."reports/jor_report/".$year."/".$month."';</script>";
        }
    }

    public function update_jo_remarks(){
        $jor_items_id =$this->input->post('jor_items_id');
        $jor_status=$this->input->post('jor_status');
        $status_remarks=$this->input->post('status_remarks');
        $year =$this->input->post('year');
        $month =$this->input->post('month');
        $data=array(
            'status'=>$jor_status,
            'status_remarks'=>$status_remarks,
        );
        if($this->super_model->update_where("jor_items", $data, "jor_items_id", $jor_items_id)){
            echo "<script>alert('Successfully Changed!');
            window.location = '".base_url()."reports/jor_report/".$year."/".$month."';</script>";
        }
    }



    public function export_jor(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="JOR Summary.xlsx";
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        if($month!='null'){
             $date = $year."-".$month;
        } else {
             $date = $year;
        }
        $controller_name='jor_report';
        $date_received=$this->uri->segment(5);
        $project_title=str_replace("%20", " ", $this->uri->segment(6));
        $jor_no=str_replace("%20", " ",$this->uri->segment(7));
        $requestor=str_replace("%20", " ", $this->uri->segment(8));
        $scope_of_work=str_replace("%20", " ", $this->uri->segment(9));
        $jo_request=str_replace("%20", " ",$this->uri->segment(10));

        $sql="";
        $filter = " ";

        if($date_received!='null'){
            $sql.=" jh.date_prepared LIKE '%$date_received%' AND";
            $filter .= $date_received;
        }

        if($jo_request!='null'){
            $sql.=" jh.jo_request LIKE '%$jo_request%' AND";
            $filter .= $jo_request;
        }

        if($project_title!='null'){
            $sql.=" jh.purpose LIKE '%$project_title%' AND";
            $filter .= $project_title;
        }

        if($jor_no!='null'){
            $sql.=" (jh.jo_no LIKE '%$jor_no%' || jh.user_jo_no LIKE '%$jor_no%') AND";
            $filter .= $jor_no;
        }

        if($requestor!='null'){
            $sql.=" jh.requested_by LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }

        if($scope_of_work!='null'){
            $sql.=" ji.scope_of_work LIKE '%$scope_of_work%' AND";
            $filter .= $scope_of_work;
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "JOR Summary $date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "JOB ORDER REQUEST");
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "Delivery Date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "Completion Date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "JO Request");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "Project Title");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "JO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "Requestor");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Revised Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Grouping");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Scope of Work");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Materials Offer");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P4', "Materials QTY");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q4', "Materials UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R4', "Status Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S4', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T4', "Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U4', "Cancel Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V4', "End User's Comments");
        foreach(range('A','V') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:V4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:V4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:V4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT ji.*, jh.* FROM jor_items ji INNER JOIN jor_head jh ON ji.jor_id = jh.jor_id WHERE ".$query) AS $pr){
                $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $pr->jor_items_id);
                $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $pr->jor_items_id);
                $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $pr->jor_items_id);
                if($joi_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
                }
                $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $pr->jor_items_id, "joi_id", "DESC", "1");
                $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                //$company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->onhold_by);
                /*$recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->recom_by);
                $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr->pr_details_id);
                $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr->pr_details_id);
                $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr->pr_details_id);
                $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr->pr_details_id);*/
                $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$pr->jor_id' AND served = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$pr->jor_id' AND served = '1' AND pi.jor_items_id = '$pr->jor_items_id'");
                $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                $unserved_qty=0;
                $unserved_uom='';  
                $stat = $this->jor_item_status_export($pr->jor_items_id,$controller_name,$joi_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $revised='';
                $current_qty='';
                $revision_no = $this->super_model->select_column_where("joi_head","revision_no","joi_id",$joi_id);
                $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$pr->jor_items_id'");
                $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$pr->jor_items_id'");
                if($revision_no!=0){
                    foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM joi_items_revised WHERE joi_id = '$joi_id' AND jor_items_id = '$pr->jor_items_id' GROUP BY revision_no") AS $rev){
                        if($rev->revision_no == 0){
                             $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."\n";
                        } else {
                             $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."\n";
                             $qty = $this->super_model->select_column_custom_where("joi_items","delivered_quantity","joi_id='$joi_id' AND jor_items_id='$pr->jor_items_id'");
                             $uom = $this->super_model->select_column_custom_where("joi_items","uom","joi_id='$joi_id' AND jor_items_id='$pr->jor_items_id'");
                             $current_qty = "Current Qty: ".$qty." ".$uom;
                        }
                    }
                }

                $jor_id = $pr->jor_id;
                $jo=$this->super_model->select_column_where('jor_head','jo_no','jor_id',$pr->jor_id);
                if($jo!=''){
                    $jo_no = $jo;
                }else{
                    $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
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

                $jo_issue=$this->like($status, "JO Issued");
                //$delivered_by=$this->like($status, "Delivered by");

                if($jor_status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($jor_status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }/*else if($status=='For Recom') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":S".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('fd9c77');
                }*/else if($status=='On-Hold') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('d2deff');
                }else if($jo_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }/*else if($delivered_by=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":S".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('eeccff');
                }*/
                $jor_no = $jo_no."-".COMPANY." / ".$pr->user_jo_no;
                if($jor_status!='Fully Delivered' && $status!='Cancelled'){
                    if($pr->on_hold==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "✓");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "");
                    }else if($pr->on_hold==0 && $pr->onhold_date!=''){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "✓");
                    }
                }
                $revise_qty = $revised."\n".$current_qty;
                if($jor_status!=''){
                    $stats = $jor_status;
                }else{
                    $stats = $status;
                }

                if($jor_status_remarks!=''){
                    $stats_rem = $jor_status_remarks;
                }else{
                    $stats_rem = $status_remarks;
                }
                $materials_offer = $this->super_model->select_column_where('joi_items', 'materials_offer', 'jor_items_id', $pr->jor_items_id);
                $materials_qty = $this->super_model->select_column_where('joi_items', 'materials_qty', 'jor_items_id', $pr->jor_items_id);
                $materials_unit = $this->super_model->select_column_where('joi_items', 'materials_unit', 'jor_items_id', $pr->jor_items_id);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->date_prepared");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$pr->delivery_date");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$pr->completion_date");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->jo_request");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$pr->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$jor_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$pr->requested_by");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$pr->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$revise_qty");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$pr->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$pr->grouping_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$pr->scope_of_work $unserved");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$materials_offer");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$materials_qty");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$materials_unit");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "$stats_rem");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "$stats");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$pr->add_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$num, "$pr->cancel_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$num, "");

                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num.":O".$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('S'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('R'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$num.":Q".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('O'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('R'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->applyFromArray($styleArray);
                $num++;  
            }
        }else{
            $num=5;
            foreach($this->super_model->custom_query("SELECT ji.*, jh.* FROM jor_items ji INNER JOIN jor_head jh ON ji.jor_id = jh.jor_id WHERE jh.date_prepared LIKE '$date%'") AS $pr){
                $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $pr->jor_items_id);
                $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $pr->jor_items_id);
                $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $pr->jor_items_id);
                if($joi_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
                }
                $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $pr->jor_items_id, "joi_id", "DESC", "1");
                $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                //$company=$this->super_model->select_column_where("company","company_name","company_id",$pr->company_id);
                $onhold_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->onhold_by);
                /*$recom_by = $this->super_model->select_column_where('users',"fullname",'user_id',$pr->recom_by);
                $ver_date_needed=$this->super_model->select_column_where('pr_calendar','ver_date_needed','pr_details_id',$pr->pr_details_id);
                $pr_calendar_id=$this->super_model->select_column_where('pr_calendar','pr_calendar_id','pr_details_id',$pr->pr_details_id);
                $estimated_price=$this->super_model->select_column_where('pr_calendar','estimated_price','pr_details_id',$pr->pr_details_id);
                $proj_act_id=$this->super_model->select_column_where('pr_calendar','proj_act_id','pr_details_id',$pr->pr_details_id);*/
                $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$pr->jor_id' AND served = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$pr->jor_id' AND served = '1' AND pi.jor_items_id = '$pr->jor_items_id'");
                $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$pr->jor_items_id'");
                $unserved_qty=0;
                $unserved_uom='';  
                $stat = $this->jor_item_status_export($pr->jor_items_id,$controller_name,$joi_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $revised='';
                $current_qty='';
                $revision_no = $this->super_model->select_column_where("joi_head","revision_no","joi_id",$joi_id);
                $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$pr->jor_items_id'");
                $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$pr->jor_items_id'");
                if($revision_no!=0){
                    foreach($this->super_model->custom_query("SELECT delivered_quantity, uom, revision_no FROM joi_items_revised WHERE joi_id = '$joi_id' AND jor_items_id = '$pr->jor_items_id' GROUP BY revision_no") AS $rev){
                        if($rev->revision_no == 0){
                             $revised.="Orig.: " . $rev->delivered_quantity . " ". $rev->uom."\n";
                        } else {
                             $revised.="Rev. ". $rev->revision_no.": " . $rev->delivered_quantity . " ". $rev->uom."\n";
                             $qty = $this->super_model->select_column_custom_where("joi_items","delivered_quantity","joi_id='$joi_id' AND jor_items_id='$pr->jor_items_id'");
                             $uom = $this->super_model->select_column_custom_where("joi_items","uom","joi_id='$joi_id' AND jor_items_id='$pr->jor_items_id'");
                             $current_qty = "Current Qty: ".$qty." ".$uom;
                        }
                    }
                }

                $jor_id = $pr->jor_id;
                $jo=$this->super_model->select_column_where('jor_head','jo_no','jor_id',$pr->jor_id);
                if($jo!=''){
                    $jo_no = $jo;
                }else{
                    $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
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

                $jo_issue=$this->like($status, "JO Issued");
                //$delivered_by=$this->like($status, "Delivered by");

                if($jor_status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($jor_status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }/*else if($status=='For Recom') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":S".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('fd9c77');
                }*/else if($status=='On-Hold') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('d2deff');
                }else if($jo_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }/*else if($delivered_by=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":S".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('eeccff');
                }*/
                $jor_no = $jo_no."-".COMPANY." / ".$pr->user_jo_no;
                if($jor_status!='Fully Delivered' && $status!='Cancelled'){
                    if($pr->on_hold==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "✓");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "");
                    }else if($pr->on_hold==0 && $pr->onhold_date!=''){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "✓");
                    }
                }
                $revise_qty = $revised."\n".$current_qty;
                if($jor_status!=''){
                    $stats = $jor_status;
                }else{
                    $stats = $status;
                }

                if($jor_status_remarks!=''){
                    $stats_rem = $jor_status_remarks;
                }else{
                    $stats_rem = $status_remarks;
                }
                $materials_offer = $this->super_model->select_column_where('joi_items', 'materials_offer', 'jor_items_id', $pr->jor_items_id);
                $materials_qty = $this->super_model->select_column_where('joi_items', 'materials_qty', 'jor_items_id', $pr->jor_items_id);
                $materials_unit = $this->super_model->select_column_where('joi_items', 'materials_unit', 'jor_items_id', $pr->jor_items_id);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->date_prepared");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$pr->delivery_date");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$pr->completion_date");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->jo_request");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$pr->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$jor_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$pr->requested_by");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$pr->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$revise_qty");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$pr->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$pr->grouping_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$pr->scope_of_work $unserved");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$materials_offer");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$materials_qty");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$materials_unit");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "$stats_rem");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "$stats");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$pr->add_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$num, "$pr->cancel_remarks");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$num, "");

                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num.":O".$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('S'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('R'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$num.":Q".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('R'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":V".$num)->applyFromArray($styleArray);
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
        header('Content-Disposition: attachment; filename="JOR Summary.xlsx"');
        readfile($exportfilename);
    }

    public function export_jor_summary(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="JOR SUMMARY Report.xlsx";
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        if($month!='null'){
             $date = $year."-".$month;
        } else {
             $date = $year;
        }
        $date_received=$this->uri->segment(5);
        $project_title=str_replace("%20", " ", $this->uri->segment(6));
        $jo_no=str_replace("%20", " ",$this->uri->segment(7));
        $requestor=str_replace("%20", " ", $this->uri->segment(8));
        $scope_of_work=str_replace("%20", " ", $this->uri->segment(9));
        $jo_request=str_replace("%20", " ",$this->uri->segment(10));

        $sql="";
        $filter = " ";

        if($date_received!='null'){
            $sql.=" jh.date_prepared LIKE '%$date_received%' AND";
            $filter .= $date_received;
        }

        if($jo_request!='null'){
            $sql.=" jh.jo_request LIKE '%$jo_request%' AND";
            $filter .= $jo_request;
        }

        if($project_title!='null'){
            $sql.=" jh.purpose LIKE '%$project_title%' AND";
            $filter .= $project_title;
        }

        if($jo_no!='null'){
            $sql.=" (jh.jo_no LIKE '%$jo_no%' || jh.user_jo_no LIKE '%$jo_no%') AND";
            $filter .= $jo_no;
        }

        if($requestor!='null'){
            $sql.=" jh.requested_by LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }

        if($scope_of_work!='null'){
            $sql.=" ji.scope_of_work LIKE '%$scope_of_work%' AND";
            $filter .= $scope_of_work;
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "STA. ISABEL CPGC POWER CORPORATION NPC,");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "COMPOUND STA. ISABEL CALAPAN CITY");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "MONITORING OF JOR $date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "Date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "JOR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Scope of Work");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "JOI No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "Name of Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Date of JO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "Amount");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "MMR #");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Assigned Engine");
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        foreach(range('A','M') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        if($filt!=''){
            $num=5;
            foreach($this->super_model->custom_query("SELECT ji.*, jh.* FROM jor_head jh INNER JOIN jor_items ji ON jh.jor_id = ji.jor_id WHERE ".$query) AS $pr){
            //foreach($this->super_model->select_all_order_by("pr_head","pr_no","ASC") AS $pr){
                //foreach($this->super_model->select_row_where("pr_details","pr_id",$pr->pr_id) AS $pd){
                $qty='';
                $unit_price='';
                $joi_id='';
                $joi_no='';
                $vendor='';
                $joi_date='';
                foreach($this->super_model->select_row_where("joi_items","jor_items_id",$pr->jor_items_id) AS $po){
                    $joi_id.=$this->super_model->select_column_where("joi_head","joi_id","joi_id",$po->joi_id);
                    $joi_no.=$this->super_model->select_column_where("joi_head","joi_no","joi_id",$po->joi_id);
                    $vendor_id=$this->super_model->select_column_where("joi_head","vendor_id","joi_id",$po->joi_id);
                    $vendor.=$this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$vendor_id);
                    $joi_date.=$this->super_model->select_column_where("joi_head","joi_date","joi_id",$po->joi_id);
                    $qty.=$po->quantity;
                    $unit_price.=$po->unit_price;
                }
                $jor_aoq_id=$this->super_model->select_column_where("jor_aoq_offers","jor_aoq_id","jor_items_id",$pr->jor_items_id);
                $terms=$this->super_model->select_column_where("jor_aoq_vendors","payment_terms","jor_aoq_id",$jor_aoq_id);
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
                $jo=$this->super_model->select_column_where("jor_head","jo_no","jor_id",$pr->jor_id);
                if($jo!=''){
                    $jo_no=$jo."-".COMPANY;
                }else{
                     $jo_no=$this->super_model->select_column_where("jor_head","user_jo_no","jor_id",$pr->jor_id)."-".COMPANY;
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $pr->date_prepared);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $jo_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $pr->scope_of_work);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, (empty($joi_id)) ? $pr->quantity : $qty);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $pr->uom);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, ($pr->cancelled==0) ? $joi_no : "Cancelled");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $vendor);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $joi_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $amount);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $terms);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":M".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $num++;
            }
        }else {
            $num=5;
            foreach($this->super_model->custom_query("SELECT ji.*, jh.* FROM jor_head jh INNER JOIN jor_items ji ON jh.jor_id = ji.jor_id ORDER BY jh.jo_no ASC") AS $pr){
                $qty='';
                $unit_price='';
                $joi_id='';
                $joi_no='';
                $vendor='';
                $joi_date='';
                foreach($this->super_model->select_row_where("joi_items","jor_items_id",$pr->jor_items_id) AS $po){
                    $joi_id.=$this->super_model->select_column_where("joi_head","joi_id","joi_id",$po->joi_id);
                    $joi_no.=$this->super_model->select_column_where("joi_head","joi_no","joi_id",$po->joi_id);
                    $vendor_id=$this->super_model->select_column_where("joi_head","vendor_id","joi_id",$po->joi_id);
                    $vendor.=$this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$vendor_id);
                    $joi_date.=$this->super_model->select_column_where("joi_head","joi_date","joi_id",$po->joi_id);
                    $qty.=$po->quantity;
                    $unit_price.=$po->unit_price;
                }
                $jor_aoq_id=$this->super_model->select_column_where("jor_aoq_offers","jor_aoq_id","jor_items_id",$pr->jor_items_id);
                $terms=$this->super_model->select_column_where("jor_aoq_vendors","payment_terms","jor_aoq_id",$jor_aoq_id);
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
                $jo=$this->super_model->select_column_where("jor_head","jo_no","jor_id",$pr->jor_id);
                if($jo!=''){
                    $jo_no=$jo."-".COMPANY;
                }else{
                     $jo_no=$this->super_model->select_column_where("jor_head","user_jo_no","jor_id",$pr->jor_id)."-".COMPANY;
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $pr->date_prepared);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $jo_no);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $pr->scope_of_work);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, (empty($joi_id)) ? $pr->quantity : $qty);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $pr->uom);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, ($pr->cancelled==0) ? $joi_no : "Cancelled");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $vendor);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $joi_date);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $amount);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, $terms);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":M".$num)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $num++;
            }
        }
        $phpColor1 = new PHPExcel_Style_Color();
        $phpColor1->setRGB('00C851'); 
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setColor($phpColor1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setColor($phpColor1);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setColor($phpColor1);
        $objPHPExcel->getActiveSheet()->getStyle('A4:M4')->applyFromArray($styleArray);
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
        $objPHPExcel->getActiveSheet()->getStyle('A4:M4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:M4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="JOR SUMMARY Report.xlsx"');
        readfile($exportfilename);
    }

    public function joi_report(){
        $controller_name='joi_report';
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

        $joi_date = date('Y-m', strtotime($date));
        $data['jor_no']=$this->super_model->select_custom_where('jor_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->select_custom_where("joi_head","joi_date LIKE '%$date%' GROUP BY joi_id") AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);

            foreach($this->super_model->select_row_where('joi_jor','joi_id',$p->joi_id) AS $pr){
                $jo = $this->super_model->select_column_where('jor_head','jo_no','jor_id',$pr->jor_id);
                $user_jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
                if($jo!=''){
                    $jo_no=$jo;
                }else{
                    $jo_no=$this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
                }
                foreach($this->super_model->select_row_where('joi_items','joi_id',$p->joi_id) AS $i){
                    if($i->item_id!=0){
                        foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                            $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                            $item=$it->item_name." - ".$it->item_specs;
                        }
                    }else {
                        $item=$i->offer;
                    }

                    /*if($p->cancelled ==1){
                        $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
                    } else {
                        if($p->served==0){
                            $status = 'JO Issued';
                        } else if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                            $status = 'JO Issued';
                        }else {
                             $dr_no = $this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_id', $p->joi_id);
                               $status='';
                           foreach($this->super_model->select_custom_where("joi_dr_items", "jor_items_id= '$i->jor_items_id' AND joi_items_id = '$i->joi_items_id' ORDER BY joi_dr_id DESC LIMIT 1") AS $del){
                                $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('joi_dr', 'date_received', 'joi_dr_id', $del->joi_dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_dr_id', $del->joi_dr_id) ."-".COMPANY."<br>";
                            }
                        }
                    }*/
                    $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $i->jor_items_id);
                    $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $i->jor_items_id);
                    $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $i->jor_items_id);
                    if($joi_offer_id==0){
                        $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
                    }else{
                        $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
                    }
                    $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $i->jor_items_id, "joi_id", "DESC", "1");
                    $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
                    $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                    $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                    $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$i->jor_id' AND served = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                     $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$i->jor_id' AND served = '1' AND pi.jor_items_id = '$i->jor_items_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                    $unserved_qty=0;
                    $unserved_uom='';  
                    $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$i->jor_items_id'");
                    $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$i->jor_items_id'");
                    $stat = $this->jor_item_status($i->jor_items_id,$controller_name,$i->joi_id);
                    $status = $stat['status'];
                    $status_remarks = $stat['remarks'];
                    $data['po'][]=array(
                        'joi_id'=>$i->joi_id,
                        'jo_no'=>$jo_no,
                        'user_jo_no'=>$user_jo_no,
                        'project_title'=>$p->project_title,
                        'qty'=>$i->quantity,
                        'joi_qty'=>$i->delivered_quantity,
                        'uom'=>$i->uom,
                        'item'=>$item,
                        'currency'=>$i->currency,
                        'unit_price'=>$i->unit_price,
                        'notes'=>$pr->notes,
                        'requestor'=>$pr->requestor,
                        'joi_id'=>$p->joi_id,
                        'joi_date'=>$p->joi_date,
                        'joi_no'=>$p->joi_no,
                        'saved'=>$p->saved,
                        'cancelled'=>$p->cancelled,
                        'status'=>$status,
                        'status_remarks'=>$status_remarks,
                        'supplier'=>$supplier,
                        'terms'=>$terms,
                        'served'=>$p->served,
                        'materials_offer'=>$i->materials_offer,
                        'materials_qty'=>$i->materials_qty,
                        'materials_unitprice'=>$i->materials_unitprice,
                        'materials_amount'=>$i->materials_amount,
                        'materials_received'=>$i->materials_received,
                        'materials_currency'=>$i->materials_currency,
                        'materials_unit'=>$i->materials_unit,
                        'jor_status'=>$jor_status,
                        'jor_status_remarks'=>$jor_status_remarks,
                    );
                }
            }
        }
        $this->load->view('template/header');        
        $this->load->view('reports/joi_report',$data);
        $this->load->view('template/footer');
    }

    public function search_joi(){
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

        if(!empty($this->input->post('jor_no'))){
            $data['jor_no1'] = $this->input->post('jor_no');
        } else {
            $data['jor_no1']= "null";
        }

        if(!empty($this->input->post('date_joi'))){
            $data['date_joi'] = $this->input->post('date_joi');
        } else {
            $data['date_joi']= "null";
        }

        if(!empty($this->input->post('joi_no'))){
            $data['joi_no'] = $this->input->post('joi_no');
        } else {
            $data['joi_no'] = "null";
        }

        if(!empty($this->input->post('project_title'))){
            $data['project_title'] = $this->input->post('project_title');
        } else {
            $data['project_title'] = "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
        } else {
            $data['requestor'] = "null";
        } 

        if(!empty($this->input->post('scope_of_work'))){
            $data['scope_of_work'] = $this->input->post('scope_of_work');
        } else {
            $data['scope_of_work'] = "null";
        } 

        if(!empty($this->input->post('supplier'))){
            $data['supplier'] = $this->input->post('supplier');
        } else {
            $data['supplier'] = "null";
        }

        $sql="";
        $filter = " ";

        if(!empty($this->input->post('jor_no'))){
            $jor_no = $this->input->post('jor_no');
            $sql.=" jj.jor_id = '$jor_no' AND";
            $jo = $this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $jor_no);
            if($jo!=''){
                $jo_no = $jo;
            }else{
                $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$jor_no);
            }
            $filter .= "JO No. - ".$jo_no.", ";
        }

        if(!empty($this->input->post('date_joi'))){
            $date_joi = $this->input->post('date_joi');
            $sql.=" jh.joi_date LIKE '%$date_joi%' AND";
            $filter .= "JOI Date. - ".$date_joi.", ";
        }

        if(!empty($this->input->post('joi_no'))){
            $joi_no = $this->input->post('joi_no');
            $sql.=" jh.joi_no LIKE '%$joi_no%' AND";
            $filter .= "JO No. - ".$joi_no.", ";
        }

        if(!empty($this->input->post('project_title'))){
            $project_title = $this->input->post('project_title');
            $sql.=" jj.purpose LIKE '%$project_title%' AND";
            $filter .= "Project Title - ".$project_title.", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" jj.requestor = '$requestor' AND";
            $filter .= "Requestor - ".$requestor.", ";
        }

        if(!empty($this->input->post('scope_of_work'))){
            $scope_of_work = $this->input->post('scope_of_work');
            $sql.=" ji.offer LIKE '%$scope_of_work%' AND";
            $filter.=$scope_of_work;
            /*foreach($this->super_model->select_custom_where('jor_aoq_items', "scope_of_work LIKE '%$scope_of_work%'") AS $t){
                $it = $t->jor_items_id;
                $sql.=" ji.jor_items_id = '$it' OR";
            }*/
        }

        if(!empty($this->input->post('supplier'))){
            $supplier = $this->input->post('supplier');
            $sql.=" jh.vendor_id = '$supplier' AND";
            $filter .= "Supplier - ".$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier).", ";
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
        $controller_name='joi_report';
        $joi_date = date('Y-m', strtotime($date));
        $data['jor_no']=$this->super_model->select_custom_where('jor_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->custom_query("SELECT * FROM joi_head jh INNER JOIN joi_items ji ON jh.joi_id = ji.joi_id INNER JOIN joi_jor jj ON ji.joi_id = jj.joi_id WHERE ".$query) AS $p){
            $jo = $this->super_model->select_column_where('jor_head','jo_no','jor_id',$p->jor_id);
            $user_jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$p->jor_id);
            if($jo!=''){
                $jo_no = $jo;
            }else{
                $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$p->jor_id);
            }
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
            /*if($p->cancelled ==1){
                $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
            } else {
                
                if($p->served==0){
                    $status = 'JO Issued';
                } else {
                    $dr_no = $this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_id', $p->joi_id);
                    // $status = date('m.d.Y', strtotime($p->date_served))." - Delivered DR# ". $joi_dr_no;
                    $status='';
                   foreach($this->super_model->select_custom_where("joi_dr_items", "jor_items_id='$p->jor_items_id' AND joi_items_id='$p->joi_items_id'") AS $del){
                         $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('joi_dr', 'date_received', 'joi_dr_id', $del->joi_dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_dr_id', $del->joi_dr_id) ."-".COMPANY."<br>";
                    }
                }
            }*/
            $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $p->jor_items_id);
            $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $p->jor_items_id);
            $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $p->jor_items_id);
            if($joi_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
            }
            $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $p->jor_items_id, "joi_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
            $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$p->jor_id' AND served = '0' AND pi.jor_items_id = '$p->jor_items_id'");
             $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$p->jor_id' AND served = '1' AND pi.jor_items_id = '$p->jor_items_id'");
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
            $unserved_qty=0;
            $unserved_uom='';  
            $stat = $this->jor_item_status($p->jor_items_id,$controller_name,$p->joi_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
            $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$p->jor_items_id'");
            $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$p->jor_items_id'");
            $data['po'][]=array(
                'joi_id'=>$p->joi_id,
                'jo_no'=>$jo_no,
                'user_jo_no'=>$user_jo_no,
                'project_title'=>$p->project_title,
                'requested_by'=>$requestor,
                'joi_qty'=>$p->delivered_quantity,
                'qty'=>$p->quantity,
                'uom'=>$p->uom,
                'item'=>$item,
                'unit_price'=>$p->unit_price,
                'requestor'=>$p->requestor,
                'currency'=>$p->currency,
                'notes'=>$p->notes,
                'joi_id'=>$p->joi_id,
                'joi_date'=>$p->joi_date,
                'joi_no'=>$p->joi_no,
                'saved'=>$p->saved,
                'cancelled'=>$p->cancelled,
                'status'=>$status,
                'status_remarks'=>$status_remarks,
                'supplier'=>$supplier,
                'terms'=>$terms,
                'served'=>$p->served,
                'cancelled'=>$p->cancelled,
                'materials_offer'=>$p->materials_offer,
                'materials_qty'=>$p->materials_qty,
                'materials_unitprice'=>$p->materials_unitprice,
                'materials_amount'=>$p->materials_amount,
                'materials_received'=>$p->materials_received,
                'materials_currency'=>$p->materials_currency,
                'materials_unit'=>$p->materials_unit,
                'jor_status'=>$jor_status,
                'jor_status_remarks'=>$jor_status_remarks,
            );
        }
        $this->load->view('template/header');        
        $this->load->view('reports/joi_report',$data);
        $this->load->view('template/footer');
    }

    public function export_joi(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="JOI Summary.xlsx";
        $controller_name='joi_report';
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $jor_no=$this->uri->segment(5);
        $date_joi=$this->uri->segment(6);
        $joi_no=str_replace("%20", " ", $this->uri->segment(7));
        $project_title=str_replace("%20", " ", $this->uri->segment(8));
        $requestor=str_replace("%20", " ", $this->uri->segment(9));
        $scope_of_work=str_replace("%20", " ", $this->uri->segment(10));
        $supplier=$this->uri->segment(11);

        $sql="";
        $filter = "";

        if($jor_no!='null'){
            $sql.=" jj.jor_id = '$jor_no' AND";
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $jor_no);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $jor_no);
            }
            $filter .= $jo_no;
        }

        if($date_joi!='null'){
            $sql.=" jh.joi_date LIKE '%$date_joi%' AND";
            $filter .= $date_joi;
        }

        if($joi_no!='null'){
            $sql.=" jh.joi_no LIKE '%$joi_no%' AND";
            $filter .= $joi_no;
        }

        if($project_title!='null'){
            $sql.=" jj.purpose LIKE '%$project_title%' AND";
            $filter .= $project_title;
        }

        if($requestor!='null'){
            $sql.=" jj.requestor = '$requestor' AND";
            $filter .= $requestor;
        }

        if($scope_of_work!='null'){
            /*foreach($this->super_model->select_custom_where('item', "item_name LIKE '%$scope_of_work%'") AS $t){
                $it = $t->item_id;
                $sql.=" ji.item_id = '$it' OR";
            }*/
            $sql.=" ji.offer LIKE '%$scope_of_work%' AND";
            $filter .= $scope_of_work;
        }

        if($scope_of_work!='null'){
            foreach($this->super_model->select_custom_where('jor_aoq_items', "scope_of_work LIKE '%$scope_of_work%'") AS $t){
                $it = $t->jor_items_id;
                $sql.=" ji.jor_items_id = '$it' OR";
            }
        }

        if($supplier!='null'){
            $sql.=" jh.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        if($month!='null'){
             $date = $year."-".$month;
        } else {
             $date = $year;
        }

        $po_date = date('Y-m', strtotime($date));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "JOI Summary $date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "JOB ORDER");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "JOR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "Project Title");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Date of JO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "JO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "Requested By");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "JO Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "Received Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "Scope of Work");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Materials Offer");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Materials QTY");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Materials UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Status Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P4', "Payment Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q4', "Materials Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R4', "Materials Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S4', "Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T4', "Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U4', "Remarks");
        foreach(range('A','U') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:U4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:U4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:U4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT * FROM joi_head jh INNER JOIN joi_items ji ON jh.joi_id = ji.joi_id INNER JOIN joi_jor  jj ON ji.joi_id = jj.joi_id  WHERE ".$query) AS $p){
                $total=$p->delivered_quantity*$p->unit_price;
                $mat_total=$p->materials_qty*$p->materials_unitprice;
                $jo=$this->super_model->select_column_where('jor_head','jo_no','jor_id',$p->jor_id);
                $user_jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$p->jor_id);
                if($jo!=''){
                    $jo_no=$jo."-".COMPANY;
                }else{
                    $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$p->jor_id)."-".COMPANY;
                }

                $jor_no = $jo."-".COMPANY." / ".$user_jo_no;
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
                /*if($p->cancelled ==1){
                    $status = "Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason;
                } else {
                    if($p->served==0){
                        $status = 'JO Issued';
                    } else if($p->served==1 && $p->delivered_quantity>$p->quantity){ 
                        $status = 'JO Issued';
                    }else {
                        $dr_no = $this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_id', $p->joi_id);
                        $status='';
                        foreach($this->super_model->select_custom_where("joi_dr_items", "jor_items_id= '$p->jor_items_id' AND joi_items_id = '$p->joi_items_id' ORDER BY joi_dr_id DESC LIMIT 1") AS $del){
                             $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('joi_dr', 'date_received', 'joi_dr_id', $del->joi_dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_dr_id', $del->joi_dr_id) ."-".COMPANY."\n";
                        }
                    }
                }*/
                $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $p->jor_items_id);
                $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $p->jor_items_id);
                $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $p->jor_items_id);
                if($joi_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
                }
                $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $p->jor_items_id, "joi_id", "DESC", "1");
                $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$p->jor_id' AND served = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                 $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$p->jor_id' AND served = '1' AND pi.jor_items_id = '$p->jor_items_id'");
                $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                $unserved_qty=0;
                $unserved_uom='';  
                $stat = $this->jor_item_status_export($p->jor_items_id,$controller_name,$p->joi_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$p->jor_items_id'");
                $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$p->jor_items_id'");
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                /*if($p->served=='1' && $p->delivered_quantity > $p->quantity){ 
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }else if($p->served=='1'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                } else if($p->cancelled=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='JO Issued') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }*/
                $jo_issue=$this->like($status, "JO Issued");
                if($jor_status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($jor_status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($jo_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }

                $joi_no = $p->joi_no."-".COMPANY;
                if($jor_status!=''){
                    $stats = $jor_status;
                }else{
                    $stats = $status;
                }

                if($jor_status_remarks!=''){
                    $stats_rem = $jor_status_remarks;
                }else{
                    $stats_rem = $status_remarks;
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$jor_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->joi_date");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$joi_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$p->delivered_quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$item");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$p->materials_offer");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$p->materials_qty");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$p->materials_unit");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$stats");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$stats_rem");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$supplier");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$terms");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$p->materials_currency $p->materials_unitprice");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "$mat_total");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "$p->currency $p->unit_price");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$total");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$num, "$p->notes");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setWrapText(true);
                $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":T".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":T".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->applyFromArray($styleArray);
                $num++;
            }
        }else {
            $num = 5;
            foreach($this->super_model->select_custom_where("joi_head","joi_date LIKE '%$date%' GROUP BY joi_id") AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                foreach($this->super_model->select_row_where('joi_jor','joi_id',$p->joi_id) AS $pr){
                    $jo = $this->super_model->select_column_where('jor_head','jo_no','jor_id',$pr->jor_id);
                    $user_jo_no =$this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
                    if($jo!=''){
                        $jo_no=$jo."-".COMPANY;
                    }else{
                        $jo_no=$this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id)."-".COMPANY;
                    }
                    $jor_no = $jo."-".COMPANY." / ".$user_jo_no;
                    foreach($this->super_model->select_row_where('joi_items','joi_id',$p->joi_id) AS $i){
                        $total=$i->delivered_quantity*$i->unit_price;
                        $mat_total=$i->materials_qty*$i->materials_unitprice;
                        if($i->item_id!=0){
                            foreach($this->super_model->select_row_where('item','item_id',$i->item_id) AS $it){
                                $uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                                $item=$it->item_name." - ".$it->item_specs;
                            }
                        }else {
                            $item=$i->offer;
                        }

                        /*if($p->cancelled ==1){
                            $status = "Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason;
                        } else {
                            if($p->served==0){
                                $status = 'JO Issued';
                            } else if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                                $status = 'JO Issued';
                            }else {
                                 $dr_no = $this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_id', $p->joi_id);
                                   $status='';
                               foreach($this->super_model->select_custom_where("joi_dr_items", "jor_items_id= '$i->jor_items_id' AND joi_items_id = '$i->joi_items_id' ORDER BY joi_dr_id DESC LIMIT 1") AS $del){
                                    $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('joi_dr', 'date_received', 'joi_dr_id', $del->joi_dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_dr_id', $del->joi_dr_id) ."-".COMPANY."\n";
                                }
                            }
                        }*/
                        $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $i->jor_items_id);
                        $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $i->jor_items_id);
                        $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $i->jor_items_id);
                        if($joi_offer_id==0){
                            $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
                        }else{
                            $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
                        }
                        $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $i->jor_items_id, "joi_id", "DESC", "1");
                        $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
                        $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                        $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                        $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$i->jor_id' AND served = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                         $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$i->jor_id' AND served = '1' AND pi.jor_items_id = '$i->jor_items_id'");
                        $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                        $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                        $unserved_qty=0;
                        $unserved_uom='';  
                        $stat = $this->jor_item_status_export($i->jor_items_id,$controller_name,$i->joi_id);
                        $status = $stat['status'];
                        $status_remarks = $stat['remarks'];
                        $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$i->jor_items_id'");
                        $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$i->jor_items_id'");
                        $styleArray = array(
                            'borders' => array(
                                'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        );
                        /*if($p->served=='1' && $i->delivered_quantity > $i->quantity){ 
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                        }else if($p->served=='1'){
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('b9ffb9');
                        } else if($p->cancelled=='1') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("K".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($status=='JO Issued') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":P".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                        }*/
                        $jo_issue=$this->like($status, "JO Issued");
                        if($jor_status=='Fully Delivered'){
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                        } else if($jor_status=='Partially Delivered') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                        } else if($status=='Cancelled') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($status=='Partially Delivered / Cancelled') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($jo_issue=='1') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                        }
                        $joi_no = $p->joi_no."-".COMPANY;
                        if($jor_status!=''){
                            $stats = $jor_status;
                        }else{
                            $stats = $status;
                        }

                        if($jor_status_remarks!=''){
                            $stats_rem = $jor_status_remarks;
                        }else{
                            $stats_rem = $status_remarks;
                        }
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$jor_no");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$pr->purpose");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->joi_date");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$joi_no");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$requestor");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$i->delivered_quantity");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$i->quantity");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$i->uom");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$item");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$i->materials_offer");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$i->materials_qty");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$i->materials_unit");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$stats");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$stats_rem");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$supplier");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$terms");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$i->materials_currency $i->materials_unitprice");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "$mat_total");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "$i->currency $i->unit_price");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$total");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$num, "$pr->notes");
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":T".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('Q'.$num.":T".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":U".$num)->applyFromArray($styleArray);
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
        header('Content-Disposition: attachment; filename="JOI Summary.xlsx"');
        readfile($exportfilename);
    }

    public function jo_unserved_report(){
        $controller_name='joi_report';
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
        $joi_date = date('Y-m', strtotime($date));
        $data['jor_no']=$this->super_model->select_custom_where('jor_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->select_custom_where("joi_head","joi_date LIKE '%$date%' GROUP BY joi_id") AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
            foreach($this->super_model->select_row_where('joi_jor','joi_id',$p->joi_id) AS $pr){
                $jo = $this->super_model->select_column_where('jor_head','jo_no','jor_id',$pr->jor_id);
                if($jo!=''){
                    $jo_no = $jo;
                }else{
                    $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
                }
                foreach($this->super_model->select_row_where('joi_items','joi_id',$p->joi_id) AS $i){
                    $unserved_qty = $i->delivered_quantity - $i->quantity;
                    $unserved_materials_qty = $i->materials_qty - $i->materials_received;
                    $requestor = $pr->requestor;
                    /*if($p->cancelled ==1){
                        $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
                    } else {
                        if($p->served==0){
                            $status = 'JO Issued';
                        } else if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                            $status = 'JO Issued';
                        }else {
                            $dr_no = $this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_id', $p->joi_id);
                            $status='';
                            foreach($this->super_model->select_custom_where("joi_dr_items", "jor_items_id= '$i->jor_items_id' AND joi_items_id = '$i->joi_items_id' ORDER BY joi_dr_id DESC LIMIT 1") AS $del){
                                $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('joi_dr', 'date_received', 'joi_dr_id', $del->joi_dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_dr_id', $del->joi_dr_id) ."<br>";
                            }
                        }
                    }*/
                    $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $i->jor_items_id);
                    $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $i->jor_items_id);
                    $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $i->jor_items_id);
                    if($joi_offer_id==0){
                        $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
                    }else{
                        $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
                    }
                    $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $i->jor_items_id, "joi_id", "DESC", "1");
                    $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
                    $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                    $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                    $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$i->jor_id' AND served = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                     $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$i->jor_id' AND served = '1' AND pi.jor_items_id = '$i->jor_items_id'");
                    $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                    $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                    //$unserved_qty=0;
                    //$unserved_materials_qty=0;
                    $unserved_uom='';  
                    $stat = $this->jor_item_status($i->jor_items_id,$controller_name,$i->joi_id);
                    $status = $stat['status'];
                    $status_remarks = $stat['remarks'];
                    $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$i->jor_items_id'");
                    $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$i->jor_items_id'");
                    $total = $unserved_qty * $i->unit_price;
                    $mat_total = $unserved_materials_qty * $i->materials_unitprice;
                    if($p->served==1 && ($i->delivered_quantity>$i->quantity || $i->materials_qty>$i->materials_received)){ 
                        $data['po'][]=array(
                            'joi_id'=>$i->joi_id,
                            'jo_no'=>$jo_no,
                            'project_title'=>$pr->purpose,
                            'requested_by'=>$requestor,
                            'qty'=>$i->quantity,
                            'joi_qty'=>$i->delivered_quantity,
                            'uom'=>$i->uom,
                            'unserved_qty'=>$unserved_qty,
                            'item'=>$i->offer,
                            'currency'=>$i->currency,
                            'unit_price'=>$i->unit_price,
                            'unserved_materials_qty'=>$unserved_materials_qty,
                            'materials_offer'=>$i->materials_offer,
                            'materials_qty'=>$i->materials_qty,
                            'materials_unit'=>$i->materials_unit,
                            'materials_unitprice'=>$i->materials_unitprice,
                            'materials_currency'=>$i->materials_currency,
                            'mat_total'=>$mat_total,
                            'notes'=>$pr->notes,
                            'joi_id'=>$p->joi_id,
                            'joi_date'=>$p->joi_date,
                            'joi_no'=>$p->joi_no,
                            'saved'=>$p->saved,
                            'cancelled'=>$p->cancelled,
                            'status'=>$status,
                            'status_remarks'=>$status_remarks,
                            'supplier'=>$supplier,
                            'terms'=>$terms,
                            'served'=>$p->served,
                            'cancelled'=>$p->cancelled,
                            'total'=>$total,
                            'jor_status'=>$jor_status,
                            'jor_status_remarks'=>$jor_status_remarks,
                        );
                    }
                }
            }
        }
        $this->load->view('template/header');        
        $this->load->view('reports/jo_unserved_report',$data);
        $this->load->view('template/footer');
    } 

    public function search_jo_unserved(){
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

        if(!empty($this->input->post('jor_no'))){
            $data['jor_no1'] = $this->input->post('jor_no');
        } else {
            $data['jor_no1']= "null";
        }

        if(!empty($this->input->post('date_joi'))){
            $data['date_joi'] = $this->input->post('date_joi');
        } else {
            $data['date_joi']= "null";
        }

        if(!empty($this->input->post('joi_no'))){
            $data['joi_no'] = $this->input->post('joi_no');
        } else {
            $data['joi_no'] = "null";
        }

        if(!empty($this->input->post('project_title'))){
            $data['project_title'] = $this->input->post('project_title');
        } else {
            $data['project_title'] = "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
        } else {
            $data['requestor'] = "null";
        } 

        if(!empty($this->input->post('scope_of_work'))){
            $data['scope_of_work'] = $this->input->post('scope_of_work');
        } else {
            $data['scope_of_work'] = "null";
        } 

        if(!empty($this->input->post('supplier'))){
            $data['supplier'] = $this->input->post('supplier');
        } else {
            $data['supplier'] = "null";
        }

        $sql="";
        $filter = " ";

        if(!empty($this->input->post('jor_no'))){
            $jor_no = $this->input->post('jor_no');
            $sql.=" jj.jor_id = '$jor_no' AND";
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $jor_no);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $jor_no);
            }
            $filter .= "JOR No. - ".$jo_no.", ";
        }

        if(!empty($this->input->post('date_joi'))){
            $date_joi = $this->input->post('date_joi');
            $sql.=" jh.joi_date LIKE '%$date_joi%' AND";
            $filter .= "JO Date. - ".$date_joi.", ";
        }

        if(!empty($this->input->post('joi_no'))){
            $joi_no = $this->input->post('joi_no');
            $sql.=" jh.joi_no LIKE '%$joi_no%' AND";
            $filter .= "JO No. - ".$joi_no.", ";
        }

        if(!empty($this->input->post('project_title'))){
            $project_title = $this->input->post('project_title');
            $sql.=" jj.purpose LIKE '%$project_title%' AND";
            $filter .= "Project Title - ".$project_title.", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" jj.requestor = '$requestor' AND";
            $filter .= "Requestor - ".$requestor.", ";
        }

        if(!empty($this->input->post('scope_of_work'))){
            $scope_of_work = $this->input->post('scope_of_work');
            $sql.=" ji.offer LIKE '%$scope_of_work%' AND";
            $filter .= "Scope of Work - ".$scope_of_work.", ";
        }

        if(!empty($this->input->post('supplier'))){
            $supplier = $this->input->post('supplier');
            $sql.=" jh.vendor_id = '$supplier' AND";
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
        $controller_name='joi_report';
        $joi_date = date('Y-m', strtotime($date));
        $data['jor_no']=$this->super_model->select_custom_where('jor_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->custom_query("SELECT * FROM joi_head jh INNER JOIN joi_items ji ON jh.joi_id = ji.joi_id INNER JOIN joi_jor jj ON ji.joi_id = jj.joi_id WHERE ".$query) AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
            $jo = $this->super_model->select_column_where('jor_head','jo_no','jor_id',$p->jor_id);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$p->jor_id);
            }
            $unserved_qty = $p->delivered_quantity - $p->quantity;
            $unserved_materials_qty = $p->materials_qty - $p->materials_received;
            $requestor = $p->requestor;
            /*if($p->cancelled ==1){
                $status = "<span style='color:red'>Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
            } else {
                if($p->served==0){
                    $status = 'JO Issued';
                } else if($p->served==1 && $p->delivered_quantity>$p->quantity){ 
                    $status = 'JO Issued';
                }else {
                    $dr_no = $this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_id', $p->joi_id);
                    $status='';
                    foreach($this->super_model->select_custom_where("joi_dr_items", "jor_items_id= '$p->jor_items_id' AND joi_items_id = '$p->joi_items_id' ORDER BY joi_dr_id DESC LIMIT 1") AS $del){
                        $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('joi_dr', 'date_received', 'joi_dr_id', $del->joi_dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_dr_id', $del->joi_dr_id) ."<br>";
                    }
                }
            }*/
            $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $p->jor_items_id);
            $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $p->jor_items_id);
            $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $p->jor_items_id);
            if($joi_offer_id==0){
                $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
            }else{
                $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
            }
            $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $p->jor_items_id, "joi_id", "DESC", "1");
            $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
            $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
            $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$p->jor_id' AND served = '0' AND pi.jor_items_id = '$p->jor_items_id'");
             $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$p->jor_id' AND served = '1' AND pi.jor_items_id = '$p->jor_items_id'");
            $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
            $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
            $unserved_qty=0;
            $unserved_uom='';  
            $stat = $this->jor_item_status($p->jor_items_id,$controller_name,$p->joi_id);
            $status = $stat['status'];
            $status_remarks = $stat['remarks'];
            $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$p->jor_items_id'");
            $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$p->jor_items_id'");
            $total = $unserved_qty * $p->unit_price;
            $mat_total = $unserved_materials_qty * $p->materials_unitprice;
            if($p->served==1 && ($p->delivered_quantity>$p->quantity || $p->materials_qty>$p->materials_received)){ 
                $data['po'][]=array(
                    'joi_id'=>$p->joi_id,
                    'jo_no'=>$jo_no,
                    'project_title'=>$p->purpose,
                    'requested_by'=>$requestor,
                    'qty'=>$p->quantity,
                    'joi_qty'=>$p->delivered_quantity,
                    'uom'=>$p->uom,
                    'unserved_qty'=>$unserved_qty,
                    'item'=>$p->offer,
                    'currency'=>$p->currency,
                    'unit_price'=>$p->unit_price,
                    'unserved_materials_qty'=>$unserved_materials_qty,
                    'materials_offer'=>$p->materials_offer,
                    'materials_qty'=>$p->materials_qty,
                    'materials_unit'=>$p->materials_unit,
                    'materials_unitprice'=>$p->materials_unitprice,
                    'materials_currency'=>$p->materials_currency,
                    'mat_total'=>$mat_total,
                    'notes'=>$p->notes,
                    'joi_id'=>$p->joi_id,
                    'joi_date'=>$p->joi_date,
                    'joi_no'=>$p->joi_no,
                    'saved'=>$p->saved,
                    'cancelled'=>$p->cancelled,
                    'status'=>$status,
                    'status_remarks'=>$status_remarks,
                    'supplier'=>$supplier,
                    'terms'=>$terms,
                    'served'=>$p->served,
                    'cancelled'=>$p->cancelled,
                    'total'=>$total,
                    'jor_status'=>$jor_status,
                    'jor_status_remarks'=>$jor_status_remarks,
                );
            }
        }
        $this->load->view('template/header');        
        $this->load->view('reports/jo_unserved_report',$data);
        $this->load->view('template/footer');
    }

    public function export_jo_unserved(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="JO Unserved Report.xlsx";
        $controller_name='joi_report';
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $jor_no=$this->uri->segment(5);
        $date_joi=$this->uri->segment(6);
        $joi_no=str_replace("%20", " ", $this->uri->segment(7));
        $project_title=str_replace("%20", " ", $this->uri->segment(8));
        $requestor=str_replace("%20", " ", $this->uri->segment(9));
        $scope_of_work=str_replace("%20", " ", $this->uri->segment(10));
        $supplier=str_replace("%20", " ", $this->uri->segment(11));

        $sql="";
        $filter = "";

        if($jor_no!='null'){
            $sql.=" jj.jor_id = '$jor_no' AND";
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $jor_no);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $jor_no);
            }
            $filter .= $jo_no;
        }

        if($date_joi!='null'){
            $sql.=" jh.joi_date LIKE '%$date_joi%' AND";
            $filter .= $date_joi;
        }

        if($joi_no!='null'){
            $sql.=" jh.joi_no LIKE '%$joi_no%' AND";
            $filter .= $joi_no;
        }

        if($project_title!='null'){
            $sql.=" jj.purpose LIKE '%$project_title%' AND";
            $filter .= $project_title;
        }

        if($requestor!='null'){
            $sql.=" jj.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }

        if($scope_of_work!='null'){
            $sql.=" ji.offer LIKE '%$scope_of_work%' AND";
            $filter .= $scope_of_work;
        }

        if($supplier!='null'){
            $sql.=" jh.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);
        if($month!='null'){
             $date = $year."-".$month;
        } else {
             $date = $year;
        }
        $po_date = date('Y-m', strtotime($date));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "Undelivered JO Report $date");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "UNDELIVERED JO Report");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "JOR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "Project Title");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Date of JO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "JO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "Requested By");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Scope of Work");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "Materials Offer");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Materials Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Materials UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Status Remarks");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Payment Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P4', "Materials Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q4', "Materials Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R4', "Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S4', "Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T4', "Remarks");
        foreach(range('A','T') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:T4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:T4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:T4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT * FROM joi_head jh INNER JOIN joi_items ji ON jh.joi_id = ji.joi_id INNER JOIN joi_jor jj ON ji.joi_id = jj.joi_id  WHERE ".$query) AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                $jo = $this->super_model->select_column_where('jor_head','jo_no','jor_id',$p->jor_id);
                if($jo!=''){
                    $jo_no=$jo." - ".COMPANY;
                }else{
                    $jo_no= $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$p->jor_id)." - ".COMPANY;
                }
                $unserved_qty = $p->delivered_quantity - $p->quantity;
                $unserved_materials_qty = $p->materials_qty - $p->materials_received;
                $requestor = $p->requestor;
                /*if($p->cancelled ==1){
                    $status = "Cancelled / ".date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason;
                } else {
                    if($p->served==0){
                        $status = 'JO Issued';
                    } else if($p->served==1 && $p->delivered_quantity>$p->quantity){ 
                        $status = 'JO Issued';
                    }else {
                        $dr_no = $this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_id', $p->joi_id);
                        $status='';
                        foreach($this->super_model->select_custom_where("joi_dr_items", "jor_items_id= '$p->jor_items_id' AND joi_items_id = '$p->joi_items_id' ORDER BY joi_dr_id DESC LIMIT 1") AS $del){
                            $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('joi_dr', 'date_received', 'joi_dr_id', $del->joi_dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_dr_id', $del->joi_dr_id) ."\n";
                        }
                    }
                }*/
                $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $p->jor_items_id);
                $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $p->jor_items_id);
                $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $p->jor_items_id);
                if($joi_offer_id==0){
                    $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
                }else{
                    $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
                }
                $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $p->jor_items_id, "joi_id", "DESC", "1");
                $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
                $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$p->jor_id' AND served = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                 $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$p->jor_id' AND served = '1' AND pi.jor_items_id = '$p->jor_items_id'");
                $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$p->jor_items_id'");
                //$unserved_qty=0;
                $unserved_uom='';  
                $stat = $this->jor_item_status_export($p->jor_items_id,$controller_name,$p->joi_id);
                $status = $stat['status'];
                $status_remarks = $stat['remarks'];
                $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$p->jor_items_id'");
                $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$p->jor_items_id'");
                $jo_issue=$this->like($status, "JO Issued");
                if($jor_status=='Fully Delivered'){
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                } else if($jor_status=='Partially Delivered') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                } else if($status=='Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($status=='Partially Delivered / Cancelled') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                    $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                }else if($jo_issue=='1') {
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                }
                $total = $unserved_qty * $p->unit_price;
                $mat_total = $unserved_materials_qty * $p->materials_unitprice;
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                if($jor_status!=''){
                    $stats = $jor_status;
                }else{
                    $stats = $status;
                }

                if($jor_status_remarks!=''){
                    $stats_rem = $jor_status_remarks;
                }else{
                    $stats_rem = $status_remarks;
                }
                if($p->served==1 && ($p->delivered_quantity>$p->quantity || $p->materials_qty>$p->materials_unitprice)){ 
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$jo_no");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->purpose");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->joi_date");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->joi_no-".COMPANY);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$requestor");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$unserved_qty");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->uom");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->offer");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$p->materials_offer");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$unserved_materials_qty");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$p->materials_unit");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$stats");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$stats_rem");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$supplier");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$terms");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$p->materials_unitprice");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$mat_total");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "$p->unit_price");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "$total");
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$p->notes");
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('P'.$num.":S".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('P'.$num.":S".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('P'.$num.":S".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->applyFromArray($styleArray);
                    $num++;   
                }
            }
                
        }else {
            $num = 5;
            foreach($this->super_model->select_custom_where("joi_head","joi_date LIKE '%$date%' GROUP BY joi_id") AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                foreach($this->super_model->select_row_where('joi_jor','joi_id',$p->joi_id) AS $pr){
                    $jo = $this->super_model->select_column_where('jor_head','jo_no','jor_id',$pr->jor_id);
                    if($jo!=''){
                        $jo_no=$jo;
                    }else{
                        $jo_no = $this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$pr->jor_id);
                    }
                    foreach($this->super_model->select_row_where('joi_items','joi_id',$p->joi_id) AS $i){
                        $unserved_qty = $i->delivered_quantity - $i->quantity;
                        $unserved_materials_qty = $i->materials_qty - $i->materials_received;
                        $requestor = $pr->requestor;
                        /*if($p->cancelled ==1){
                            $status = "Cancelled / ". date('d.m.Y', strtotime($p->cancelled_date)). "/ " . $p->cancel_reason."</span>";
                        } else {
                            if($p->served==0){
                                $status = 'JO Issued';
                            } else if($p->served==1 && $i->delivered_quantity>$i->quantity){ 
                                $status = 'JO Issued';
                            }else {
                                $dr_no = $this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_id', $p->joi_id);
                                $status='';
                                foreach($this->super_model->select_custom_where("joi_dr_items", "jor_items_id= '$i->jor_items_id' AND joi_items_id = '$i->joi_items_id' ORDER BY joi_dr_id DESC LIMIT 1") AS $del){
                                    $status.=date('m.d.Y', strtotime($this->super_model->select_column_where('joi_dr', 'date_received', 'joi_dr_id', $del->joi_dr_id)))  . " - Delivered DR# ".$this->super_model->select_column_where('joi_dr', 'joi_dr_no', 'joi_dr_id', $del->joi_dr_id) ."\n";
                                }
                            }
                        }*/
                        $recom_unit_price = $this->super_model->select_column_where('jor_aoq_offers', 'unit_price', 'jor_items_id', $i->jor_items_id);
                        $joi_offer_id = $this->super_model->select_column_where('joi_items', 'jor_aoq_offer_id', 'jor_items_id', $i->jor_items_id);
                        $joi_items_id = $this->super_model->select_column_where('joi_items', 'joi_items_id', 'jor_items_id', $i->jor_items_id);
                        if($joi_offer_id==0){
                            $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'joi_items_id', $joi_items_id);
                        }else{
                            $cancelled_items_po = $this->super_model->select_column_where('joi_items', 'cancel', 'jor_aoq_offer_id', $joi_offer_id);
                        }
                        $joi_id = $this->super_model->select_column_row_order_limit2("joi_id","joi_items","jor_items_id", $i->jor_items_id, "joi_id", "DESC", "1");
                        $cancelled_head_po = $this->super_model->select_column_where('joi_head', 'cancelled', 'joi_id', $joi_id);
                        $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                        $sum_delivered_qty = $this->super_model->custom_query_single("deltotal","SELECT sum(delivered_quantity) AS deltotal FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                        $count_po = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$i->jor_id' AND served = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                         $count_po_served = $this->super_model->count_custom_query("SELECT ph.joi_id FROM joi_head ph INNER JOIN joi_jor pr ON ph.joi_id = pr.joi_id INNER JOIN joi_items pi ON ph.joi_id=pi.joi_id WHERE ph.cancelled='0' AND pr.jor_id = '$i->jor_id' AND served = '1' AND pi.jor_items_id = '$i->jor_items_id'");
                        $sum_po_issued_qty = $this->super_model->custom_query_single("issued_total","SELECT sum(delivered_quantity) AS issued_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                        $sum_po_delivered_qty = $this->super_model->custom_query_single("delivered_total","SELECT sum(quantity) AS delivered_total FROM joi_items pi INNER JOIN joi_head ph ON  ph.joi_id = pi.joi_id WHERE ph.cancelled = '0' AND pi.jor_items_id = '$i->jor_items_id'");
                        $unserved_uom='';  
                        $stat = $this->jor_item_status_export($i->jor_items_id,$controller_name,$i->joi_id);
                        $status = $stat['status'];
                        $status_remarks = $stat['remarks'];
                        $jor_status = $this->super_model->select_column_custom_where("jor_items","status","jor_items_id='$i->jor_items_id'");
                        $jor_status_remarks = $this->super_model->select_column_custom_where("jor_items","status_remarks","jor_items_id='$i->jor_items_id'");
                        $jo_issue=$this->like($status, "JO Issued");
                        if($jor_status=='Fully Delivered'){
                           $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('bcffc7');
                        } else if($jor_status=='Partially Delivered') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f7ffb9');
                        } else if($status=='Cancelled') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($status=='Partially Delivered / Cancelled') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('cacaca');
                            $objPHPExcel->getActiveSheet()->getStyle("N".$num)->getFont()->getColor()->setRGB('ff0000');
                        }else if($jo_issue=='1') {
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffecd0');
                        }
                        $total = $unserved_qty * $i->unit_price;
                        $mat_total = $unserved_materials_qty * $i->materials_unitprice;
                        $styleArray = array(
                            'borders' => array(
                                'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        );
                        if($jor_status!=''){
                            $stats = $jor_status;
                        }else{
                            $stats = $status;
                        }

                        if($jor_status_remarks!=''){
                            $stats_rem = $jor_status_remarks;
                        }else{
                            $stats_rem = $status_remarks;
                        }
                        if($p->served==1 && ($i->delivered_quantity>$i->quantity || $i->materials_qty>$i->materials_unitprice)){ 
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$jo_no");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$pr->purpose");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->joi_date");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->joi_no-".COMPANY);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$requestor");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$unserved_qty");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$i->uom");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$i->offer");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$i->materials_offer");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "$unserved_materials_qty");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$i->materials_unit");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$stats");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$stats_rem");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$supplier");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$terms");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$num, "$i->materials_unitprice");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$num, "$mat_total");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$num, "$i->unit_price");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$num, "$total");
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$num, "$pr->notes");
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('C'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('P'.$num.":S".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $objPHPExcel->getActiveSheet()->getStyle('J'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $objPHPExcel->getActiveSheet()->getStyle('P'.$num.":S".$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('P'.$num.":S".$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getAlignment()->setWrapText(true);
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":T".$num)->applyFromArray($styleArray);
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
        header('Content-Disposition: attachment; filename="JO Unserved Report.xlsx"');
        readfile($exportfilename);
    }
}
?>