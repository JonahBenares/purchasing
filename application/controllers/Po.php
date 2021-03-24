<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po extends CI_Controller {

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
        //$this->data['cenpri'] = "http://localhost/purchasing/assets/img/logo_cenpri.png";

	}

    public function test($cenpri){
        $cenpri = "http://localhost/purchasing/assets/img/logo_cenpri.png";
    }

    public function cancelled_po(){
        $data['header']=array();
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('template/navbar');
        foreach($this->super_model->select_custom_where("po_head", "done_po = '0' AND cancelled = '1' ORDER BY po_id DESC") AS $head){
            $pr_id = $this->super_model->select_column_where("po_items","pr_id","po_id",$head->po_id);
            $pr_no = $this->super_model->select_column_where("pr_head","pr_no","pr_id",$pr_id);
            $data['header'][]=array(
                'po_id'=>$head->po_id,
                'po_date'=>$head->po_date,
                'po_no'=>$head->po_no,
                'pr_no'=>$pr_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                'cancelled'=>$head->cancelled,
                'cancel_reason'=>$head->cancel_reason,
                'cancelled_date'=>$head->cancelled_date,
            );
        }
        $this->load->view('po/cancelled_po',$data);
        $this->load->view('template/footer');
    }

    public function cancel_po(){
        $po_id=$this->input->post('po_id');
        $reason=$this->input->post('reason');
        $create = date('Y-m-d H:i:s');

        foreach($this->super_model->select_row_where("po_items", "po_id", $po_id) AS $items){
            $quantity = $items->quantity;

            $balance = $this->super_model->select_column_where("aoq_offers", "balance", "aoq_offer_id", $items->aoq_offer_id);

            $new_balance = $balance + $quantity;
            $data=array(
                'balance'=>$new_balance
            );

            $this->super_model->update_where("aoq_offers",  $data, "aoq_offer_id", $items->aoq_offer_id);
        }

        $data = array(
            'cancelled'=>1,
            'cancelled_by'=>$_SESSION['user_id'],
            'cancel_reason'=>$reason,
            'cancelled_date'=>$create
        );

        if($this->super_model->update_where("po_head", $data, "po_id", $po_id)){
            redirect(base_url().'po/po_list', 'refresh');
        }
    }
    
    public function po_list(){
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');   
        $this->load->view('template/navbar');
        foreach($this->super_model->select_custom_where("po_head", "served = '0' AND cancelled = '0' ORDER BY po_id DESC") AS $head){
             $rfd=$this->super_model->count_rows_where("rfd","po_id",$head->po_id);
             $pr='';
             $pr_id='';
            foreach($this->super_model->select_row_where("po_pr", "po_id", $head->po_id) AS $prd){
                $pr_no=$this->super_model->select_column_where('pr_head','pr_no','pr_id', $prd->pr_id);
                $pr .= $pr_no."<br>";
                $pr_id=$prd->pr_id;
            }

            $dr_id = $this->super_model->select_column_custom_where("po_dr", "dr_id", "po_id = '$head->po_id' AND received='0'");
            $data['header'][]=array(
                'po_id'=>$head->po_id,
                'po_date'=>$head->po_date,
                'po_no'=>$head->po_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                'draft'=>$head->draft,
                'po_type'=>$head->po_type,
                'pr'=>$pr,
                'rfd'=>$rfd,
                'po_type'=>$head->po_type,
                'revised'=>$head->revised,
                'revision_no'=>$head->revision_no,
                'served'=>$head->served,
                'grouping_id'=>$head->grouping_id,
                'pr_id'=>$pr_id,
                'dr_id'=>$dr_id
            );
        }        
        $this->load->view('po/po_list',$data);
        $this->load->view('template/footer');
    }

    public function serve_po(){
        $poid=$this->input->post('po_id');
        $data = array(
            'served'=>1,
            'date_served'=>$this->input->post('date_delivered'),
            'served_by'=>$_SESSION['user_id']
        );
        if($this->super_model->update_where("po_head", $data, "po_id", $poid)){
            redirect(base_url().'po/po_list/', 'refresh');
        }
    }

    public function create_po(){
        $rows_head = $this->super_model->count_rows("po_head");
        if($rows_head==0){
            $po_id=1;
        } else {
            $max = $this->super_model->get_max("po_head", "po_id");
            $po_id = $max+1;
        }

        $rows_series = $this->super_model->count_rows("po_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("po_series", "series");
            $series = $max+1;
        }

        if(empty($this->input->post('dp'))){
            $pr_no = $this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id',$this->input->post('prno'));
            //$po_no = "P".$pr_no."-".$series;
            $po_no = "P".$pr_no."-".$series;
            $pr_id = $this->input->post('prno');
            $data_details = array(
                'po_id'=>$po_id,
                'pr_id'=>$pr_id,
                'aoq_id'=>$this->input->post('aoq_id'),
                'enduse'=>$this->super_model->select_column_where('pr_head', 'enduse', 'pr_id', $pr_id),
                'purpose'=>$this->super_model->select_column_where('pr_head', 'purpose', 'pr_id', $pr_id),
                'requestor'=>$this->super_model->select_column_where('pr_head', 'requestor', 'pr_id', $pr_id),

            );
            $this->super_model->insert_into("po_pr", $data_details);
            $data= array(
                'po_id'=>$po_id,
                'po_date'=>$this->input->post('po_date'),
                'po_no'=>$po_no,
                'vendor_id'=>$this->input->post('vendor'),
                'notes'=>$this->input->post('notes'),
                'po_type'=>0,
                'user_id'=>$_SESSION['user_id'],
                'prepared_date'=>date("Y-m-d H:i:s"),
            );  

            $data_series = array(
                'series'=>$series
            );
            $this->super_model->insert_into("po_series", $data_series);

            if($this->super_model->insert_into("po_head", $data)){
                 redirect(base_url().'po/purchase_order/'.$po_id);
            }
        }else {
            $po_no = "POD-".$series;
            $data= array(
                'po_id'=>$po_id,
                'po_date'=>$this->input->post('po_date'),
                'po_no'=>$po_no,
                'vendor_id'=>$this->input->post('vendor'),
                'notes'=>$this->input->post('notes'),
                'po_type'=>1,
                'user_id'=>$_SESSION['user_id'],
                'prepared_date'=>date("Y-m-d H:i:s"),
            );  

            $data_series = array(
                'series'=>$series
            );
            $this->super_model->insert_into("po_series", $data_series);

          
            if($this->super_model->insert_into("po_head", $data)){
                 redirect(base_url().'pod/po_direct/'.$po_id);
            }
        }
    }

    public function create_reorderpo(){
        $rows_head = $this->super_model->count_rows("po_head");
        if($rows_head==0){
            $po_id=1;
        } else {
            $max = $this->super_model->get_max("po_head", "po_id");
            $po_id = $max+1;
        }


            $rows_series = $this->super_model->count_rows("po_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("po_series", "series");
            $series = $max+1;
        }

        $po_no = 'RPO-'.$series;

           $data_series = array(
                'series'=>$series
            );
            $this->super_model->insert_into("po_series", $data_series);
     /*   $head_rows = $this->super_model->count_rows("po_head");
        if($head_rows==0){
            $po_no = 1000;
        } else {
            $maxno=$this->super_model->get_max("po_head", "po_series");
            $po_no = $maxno + 1;
        }*/

       // $po_series = $this->input->post('po_no')."-".$po_no;
        $data = array(
            'po_id'=>$po_id,
            'po_date'=>$this->input->post('po_date'),
            'po_no'=>$po_no,
            'notes'=>$this->input->post('notes'),
            'vendor_id'=>$this->input->post('supplier'),
            'user_id'=>$_SESSION['user_id'],
            'po_type'=>2,
            'prepared_date'=>date("Y-m-d H:i:s"),
        );
        if($this->super_model->insert_into("po_head", $data)){
            redirect(base_url().'po/reporder_prnt/'.$po_id);
        }
    }

    public function getsupplierPR(){
        $supplier = $this->input->post('supplier');
        echo '<option value="">-Select PR No-</option>';
        foreach($this->super_model->custom_query("SELECT ah.pr_id, ah.aoq_id, ah.aoq_date FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE vendor_id = '$supplier' AND recommended = '1' and cancelled='0' GROUP BY ah.aoq_id") AS $row){

            echo '<option value="'. $row->pr_id."_".$row->aoq_id .'">'. $this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $row->pr_id) .' ('.$row->aoq_date." - ".$row->aoq_id.')</option>';
      
         }
    }

    public function getPRinformation(){

        $prid = $this->input->post('prid');
        $prexp = explode('_',$prid);
        $pr_id=$prexp[0];
        $aoq_id=$prexp[1];
        $purpose= $this->super_model->select_column_where('pr_head', 'purpose', 'pr_id', $pr_id);
        $enduse= $this->super_model->select_column_where('pr_head', 'enduse', 'pr_id', $pr_id);
        $requestor= $this->super_model->select_column_where('pr_head', 'requestor', 'pr_id', $pr_id);

        
        $return = array('purpose' => $purpose, 'enduse' => $enduse, 'requestor' => $requestor, 'aoq_id'=>$aoq_id);
        echo json_encode($return);
    
    }

    public function getsupplier(){

        $supplier = $this->input->post('supplier');
        $address= $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $supplier);
        $phone= $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $supplier);
        $contact= $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $supplier);
        
        $return = array('address' => $address, 'phone' => $phone, 'contact' => $contact);
        echo json_encode($return);
    
    }

    public function item_checker($pr_details_id, $vendor_id){
        $pr_qty = $this->super_model->select_column_where('pr_details', 'quantity', 'pr_details_id', $pr_details_id);

        $delivered_qty = $this->super_model->select_sum_join("quantity","po_head","po_items", "pr_details_id = '$pr_details_id' AND cancelled = '0' ","po_id");

       // if($delivered_qty!=0){
            if($delivered_qty==$pr_qty){
                $qty = 0;
            } else {
                $qty = $pr_qty-$delivered_qty;
            }
      /*  } else {
            $qty = $this->super_model->select_column_join_where("balance", "aoq_head","aoq_offers", "vendor_id = '$vendor_id' AND recommended = '1'","aoq_id");
        }*/

        return $qty;
    }

    public function purchase_order(){
        $po_id = $this->uri->segment(3);
        $revised = $this->uri->segment(4);
        $data['revised'] = $revised;

        $data['po_id'] = $po_id;
        $vendor_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        $data['vendor_id']=$vendor_id;
        foreach($this->super_model->select_row_where('po_head', 'po_id', $po_id) AS $h){
            $data['head'][] = array(
                'po_date'=>$h->po_date,
                'po_no'=>$h->po_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['saved']=$h->saved;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
        }

        foreach($this->super_model->custom_query("SELECT ao.aoq_id, ah.pr_id, ao.currency FROM aoq_offers ao INNER JOIN aoq_head ah ON ao.aoq_id = ah.aoq_id WHERE ao.vendor_id = '$vendor_id' AND recommended = '1' GROUP BY pr_id") AS $off){
            $data['pr'][]=array(
                'pr_id'=>$off->pr_id,
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $off->pr_id),
            );
            //$data['currency'] = $off->currency;
        }

        if(empty($revised)){
            foreach($this->super_model->select_row_where("po_pr", "po_id" , $po_id) AS $popr){
             
                foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1' ORDER BY pr_details_id ASC") AS $off){
                    //echo $off->unit_price. " * " .$off->balance."<br>";
                  
                    $data['currency'] = $off->currency;
                    $balance = $this->item_checker($off->pr_details_id, $vendor_id);
                      $total = $off->unit_price*$balance;
                      
                    //echo $balance ."<br>";
                    $data['items'][] =  array(
                        'aoq_id'=>$off->aoq_id,
                        'aoq_offer_id'=>$off->aoq_offer_id,
                        'aoq_items_id'=>$off->aoq_items_id,
                        'pr_details_id'=>$off->pr_details_id,
                        'item_name'=>$this->super_model->select_column_where('aoq_items', 'item_description', 'aoq_items_id', $off->aoq_items_id),
                        'offer'=>$off->offer,
                        'currency'=>$off->currency,
                        'price'=>$off->unit_price,
                        'balance'=>$balance,
                        'amount'=>$off->amount,
                        'uom'=>$off->uom,
                        'total'=>$total
                    );
                    
                    $data['vendor_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'vendor_id', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'aoq_vendors_id', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                }
             } 
        } else {
             foreach($this->super_model->select_row_where("po_items", "po_id" , $po_id) AS $off){
                    $data['currency'] = $off->currency;
                  $total = $off->unit_price*$off->quantity;
                    $data['items'][] =  array(
                        'aoq_id'=>$this->super_model->select_column_where('po_pr', 'aoq_id', 'po_id', $po_id),
                        'aoq_offer_id'=>$off->aoq_offer_id,
                        'aoq_items_id'=>$off->aoq_items_id,
                        'pr_details_id'=>$off->pr_details_id,
                        'item_name'=>$this->super_model->select_column_where('aoq_items', 'item_description', 'aoq_items_id', $off->aoq_items_id),
                        'offer'=>$off->offer,
                        'price'=>$off->unit_price,
                        'balance'=>$off->quantity,
                        'amount'=>$off->amount,
                        'uom'=>$off->uom,
                        'total'=>$total
                    );
                

                    $data['vendor_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'vendor_id', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'aoq_vendors_id', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
                    $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
             }
        }

        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $ppr){
            $data['allpr'][]= array(
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $ppr->pr_id),
                'enduse'=>$ppr->enduse,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
        }

        $data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);

        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('po/purchase_order', $data);
        $this->load->view('template/footer');
    }

    public function update_terms(){
        $po_id = $this->input->post('po_id');
        $aoq_vendors_id = $this->input->post('aoq_vendors_id');
        $update = array(
            'payment_terms'=>$this->input->post('payments'),
            'delivery_date'=>$this->input->post('del_itm'),
            'item_warranty'=>$this->input->post('item_war'),
            'freight'=>$this->input->post('freigh'),
        ); 
        if($this->super_model->update_where("aoq_vendors", $update, "aoq_vendors_id",$aoq_vendors_id)){

            redirect(base_url().'po/purchase_order/'.$po_id);
        }
    }

    public function update_condition(){
        $po_id = $this->input->post('po_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("po_tc", $update, "po_tc_id",$tc_id)){
            redirect(base_url().'po/purchase_order/'.$po_id);
        }
    }

    public function add_notes(){
        $po_id = $this->input->post('po_id');
        $draft = $this->super_model->select_column_where("po_head", "draft", "po_id", $po_id);
        $data = array(
            'po_id'=>$this->input->post('po_id'),
            'notes'=>$this->input->post('notes'),
        );
        if($this->super_model->insert_into("po_tc", $data)){
            if($draft==0){
                redirect(base_url().'po/purchase_order/'.$po_id, 'refresh');
            } else {
                 redirect(base_url().'po/purchase_order_draft/'.$po_id, 'refresh');
            }
        }
    }

    public function update_notes(){
        $po_id = $this->input->post('po_id');
        $tc_id = $this->input->post('tc_id');
        $draft = $this->super_model->select_column_where("po_head", "draft", "po_id", $po_id);
        $saved = $this->super_model->select_column_where("po_head", "saved", "po_id", $po_id);
        $update = array(
            'notes'=>$this->input->post('notes'),
        ); 
        if($this->super_model->update_where("po_tc", $update, "po_tc_id",$tc_id)){
            if($saved==0 && $draft==0){
                redirect(base_url().'po/purchase_order/'.$po_id, 'refresh');
            } else if($saved!=0){
                redirect(base_url().'po/purchase_order_saved/'.$po_id, 'refresh');
            }else if($draft==1){
                redirect(base_url().'po/purchase_order_draft/'.$po_id, 'refresh');
            }
            //redirect(base_url().'po/purchase_order/'.$po_id);
        }
    }

    public function delete_inst(){
        $id=$this->uri->segment(3);
        $po_id=$this->uri->segment(4);
        $draft = $this->super_model->select_column_where("po_head", "draft", "po_id", $po_id);
        $saved = $this->super_model->select_column_where("po_head", "saved", "po_id", $po_id);
        if($this->super_model->delete_where('po_tc', 'po_tc_id', $id)){
            if($saved==0 && $draft==0){
                echo "<script>alert('Succesfully Deleted');</script>";
                redirect(base_url().'po/purchase_order/'.$po_id, 'refresh');
            }else if($saved!=0){
                echo "<script>alert('Succesfully Deleted');</script>";
                redirect(base_url().'po/purchase_order_saved/'.$po_id, 'refresh');
            }else if($draft==1){
                echo "<script>alert('Succesfully Deleted');</script>";
                redirect(base_url().'po/purchase_order_draft/'.$po_id, 'refresh');
            }

        }
    }

    public function save_po(){
        $submit = $this->input->post('submit');
        $po_id = $this->input->post('po_id');
        $count_item = $this->input->post('count_item');
        $a=1;


        $rows_dr = $this->super_model->count_rows("po_dr");
        if($rows_dr==0){
            $dr_no=1000;
            $dr_id = 1;
        } else {
            $max = $this->super_model->get_max("po_dr", "dr_no");
            $maxid = $this->super_model->get_max("po_dr", "dr_id");
            $dr_no = $max+1;
            $dr_id = $maxid+1;
        }


        $dr = array(
            'dr_id'=>$dr_id,
            'po_id'=>$po_id,
            'dr_no'=>$dr_no,
            'dr_date'=>$this->super_model->select_column_where('po_head', 'po_date', 'po_id', $po_id),
        );
        $this->super_model->insert_into("po_dr", $dr);

        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
         

             $rows_items = $this->super_model->count_rows("po_items");
                if($rows_items==0){
                    $po_items_id = 1;
                } else {
                    $maxid = $this->super_model->get_max("po_items", "po_items_id");
                    $po_items_id = $maxid+1;
                }


            $saved = $this->super_model->select_column_where("po_head",'saved','po_id',$po_id);
            if($qty!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $data=array(
                    'po_items_id'=>$po_items_id,
                    'pr_id'=>$this->super_model->select_column_where('aoq_head', 'pr_id', 'aoq_id', $this->input->post('aoq_id'.$x)),
                    'po_id'=>$po_id,
                    'aoq_offer_id'=>$this->input->post('aoq_offer_id'.$x),
                    'aoq_items_id'=>$this->input->post('aoq_items_id'.$x),
                    'pr_details_id'=>$this->input->post('pr_details_id'.$x),
                    'offer'=>$this->input->post('offer'.$x),
                    'delivered_quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );

                $data_dr=array(
                    'po_items_id'=>$po_items_id,  
                    'pr_id'=>$this->super_model->select_column_where('aoq_head', 'pr_id', 'aoq_id', $this->input->post('aoq_id'.$x)),
                    'dr_id'=>$dr_id,
                    'po_id'=>$po_id,
                    'aoq_offer_id'=>$this->input->post('aoq_offer_id'.$x),
                    'aoq_items_id'=>$this->input->post('aoq_items_id'.$x),
                    'pr_details_id'=>$this->input->post('pr_details_id'.$x),
                    'offer'=>$this->input->post('offer'.$x),
                    'delivered_quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );

           
                    $this->super_model->insert_into("po_items", $data);
                    $this->super_model->insert_into("po_dr_items", $data_dr);
            
             $a++;
            }
            
        }

        if($submit=='Save'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked'),
                'recommended_by'=>$this->input->post('recommended'),
                'approved_by'=>$this->input->post('approved'),
                'saved'=>1,
                'revised'=>0
            ); 
             if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'po/purchase_order_saved/'.$po_id);
             }

        } else if($submit=='Save as Draft'){
             $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked'),
                'recommended_by'=>$this->input->post('recommended'),
                'approved_by'=>$this->input->post('approved'),
                'saved'=>0,
                'draft'=>1,
                'revised'=>0
            ); 
             if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'po/purchase_order_draft/'.$po_id);
             }
        }
      

      
    }

    public function save_po_draft(){
        $submit = $this->input->post('submit');
        $po_id = $this->input->post('po_id');
        $count_item = $this->input->post('count_item');

        $a=1;
        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $uom=$this->input->post('uom'.$x);
            $po_items_id = $this->input->post('po_items_id'.$x);
            


        
            if($qty!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $offer = $this->input->post('offer'.$x);
                $data=array(
                 
                    'delivered_quantity'=>$qty,
                    'offer'=>$offer,
                    'uom'=>$uom,
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );
                $data_dr=array(
                    'delivered_quantity'=>$qty,
                    'uom'=>$uom,
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );

                    $this->super_model->update_where("po_items", $data, "po_items_id", $po_items_id);
                    $this->super_model->update_where("po_dr_items", $data_dr, "po_items_id", $po_items_id);
             $a++;
            } else {
                
                $this->super_model->delete_where("po_items", "po_items_id", $po_items_id);
                $this->super_model->delete_where("po_dr_items", "po_items_id", $po_items_id);
            }
            
        }

        if($submit=='Save'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked'),
                'recommended_by'=>$this->input->post('recommended'),
                'approved_by'=>$this->input->post('approved'),
                'saved'=>1,
                'draft'=>0,
                'revised'=>0
            ); 
             if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'po/purchase_order_saved/'.$po_id);
             }

        } else if($submit=='Save as Draft'){
             $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked'),
                'recommended_by'=>$this->input->post('recommended'),
                'approved_by'=>$this->input->post('approved'),
                'saved'=>0,
                'draft'=>1,
                'revised'=>0
            ); 
             if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'po/purchase_order_draft/'.$po_id);
             }
        }
      

      
    }

    public function save_po_revised(){
        $po_id = $this->input->post('po_id');
        $count_item = $this->input->post('count_item');
        $date= date('Y-m-d H:i:s');

       

        /**************** DUPLICATE TO REVISED TABLE PO HEAD/PO PR AND PO ITEMS ***************/

        $rev_no = $this->super_model->select_column_where("po_head",'revision_no','po_id',$po_id);

        foreach($this->super_model->select_row_where("po_head","po_id",$po_id) AS $head){
            $data_head = array(
                "po_id"=>$head->po_id,
                "po_date"=>$head->po_date,
                "po_no"=>$head->po_no,
                "dr_no"=>$head->dr_no,
                "vendor_id"=>$head->vendor_id,
                "notes"=>$head->notes,
                "po_type"=>$head->po_type,
                "user_id"=>$head->user_id,
                "approved_by"=>$head->approved_by,
                "saved"=>$head->saved,
                "done_po"=>$head->done_po,
                "date_revised"=>$head->date_revised,
                "revision_no"=>$head->revision_no,
                "revise_attachment"=>$head->revise_attachment,

            );

            $this->super_model->insert_into("po_head_revised", $data_head);
        }

        foreach($this->super_model->select_row_where("po_pr","po_id",$po_id) AS $popr){
            $data_popr = array(
                "po_pr_id"=>$popr->po_pr_id,
                "po_id"=>$popr->po_id,
                "po_id"=>$popr->po_id,
                "aoq_id"=>$popr->aoq_id,
                "enduse"=>$popr->enduse,
                "purpose"=>$popr->purpose,
                "requestor"=>$popr->requestor,
                "notes"=>$popr->notes,
                "revision_no"=>$rev_no
            );
            $this->super_model->insert_into("po_pr_revised", $data_popr);
        }

        foreach($this->super_model->select_row_where("po_items","po_id",$po_id) AS $poitems){
             

            $data_items = array(
                "po_items_id"=>$poitems->po_items_id,
                "pr_id"=>$poitems->pr_id,
                "po_id"=>$poitems->po_id,
                "aoq_offer_id"=>$poitems->aoq_offer_id,
                "aoq_items_id"=>$poitems->aoq_items_id,
                "pr_details_id"=>$poitems->pr_details_id,
                "offer"=>$poitems->offer,
                "item_id"=>$poitems->item_id,
                "quantity"=>$poitems->quantity,
                "currency"=>$poitems->currency,
                "unit_price"=>$poitems->unit_price,
                "uom"=>$poitems->uom,
                "amount"=>$poitems->amount,
                "item_no"=>$poitems->item_no,
                "revision_no"=>$rev_no
            );
            $this->super_model->insert_into("po_items_revised", $data_items);
        }

        foreach($this->super_model->select_row_where("po_dr","po_id",$po_id) AS $podr){
            $data_dr = array(
                "dr_id"=>$podr->dr_id,
                "po_id"=>$podr->po_id,
                "dr_no"=>$podr->dr_no,
                "revision_no"=>$rev_no
            );
            $this->super_model->insert_into("po_dr_revised", $data_dr);
        }

        /*************************************************************************/

        $a=1;
        for($x=1; $x<$count_item;$x++){


            $qty=$this->input->post('quantity'.$x);

                $old_qty = $this->super_model->select_column_where('po_items', 'quantity', 'aoq_offer_id', $this->input->post('aoq_offer_id'.$x));
                $curr_balance = $this->super_model->select_column_where('aoq_offers', 'balance', 'aoq_offer_id', $this->input->post('aoq_offer_id'.$x));
                $get_old_balance = $curr_balance + $old_qty;

                // echo "curr = ". $curr_balance . " + " . $old_qty . " ** " . $this->input->post('aoq_offer_id'.$x)."<br><br>";

                $data_old_balance = array(
                    'balance'=>$get_old_balance
                );

                $this->super_model->update_where("aoq_offers", $data_old_balance, "aoq_offer_id", $this->input->post('aoq_offer_id'.$x));
           
            $saved = $this->super_model->select_column_where("po_head",'saved','po_id',$po_id);
            if($qty!=0){
                $data=array(
                    'pr_id'=>$this->super_model->select_column_where('aoq_head', 'pr_id', 'aoq_id', $this->input->post('aoq_id'.$x)),
                    'po_id'=>$po_id,
                    'aoq_offer_id'=>$this->input->post('aoq_offer_id'.$x),
                    'aoq_items_id'=>$this->input->post('aoq_items_id'.$x),
                    'pr_details_id'=>$this->input->post('pr_details_id'.$x),
                    'offer'=>$this->input->post('offer'.$x),
                    'quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'unit_price'=>$this->input->post('price'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                    'item_no'=>$a
                );

               
                $this->super_model->update_where("po_items", $data, "aoq_offer_id", $this->input->post('aoq_offer_id'.$x));
                

                $new_balance = $this->super_model->select_column_where('aoq_offers', 'balance', 'aoq_offer_id', $this->input->post('aoq_offer_id'.$x));

                //echo $new_balance . " - " . $qty . "<br>";
                $balance = $new_balance-$qty;

                $data_aoq = array(
                    'balance'=>$balance
                );
                $this->super_model->update_where("aoq_offers", $data_aoq, "aoq_offer_id", $this->input->post('aoq_offer_id'.$x));
             $a++;
            }
            
        }

    
        $rows_dr = $this->super_model->count_rows("po_dr");
        if($rows_dr==0){
            $dr_no=1000;
        } else {
            $max = $this->super_model->get_max("po_dr", "dr_no");
            $dr_no = $max+1;
        }

         $max_revision = $this->super_model->get_max_where("po_head", "revision_no","po_id = '$po_id'");
        $revision_no = $max_revision+1;


        $dr = array(
            'dr_no'=>$dr_no,
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("po_dr", $dr, "po_id",$po_id);

        $head = array(
        
            'approved_by'=>$this->input->post('approved'),
            'saved'=>1,
            'revised'=>0,
            'revision_no'=>$revision_no,
            'date_revised'=>$date
        );
      

        if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
            redirect(base_url().'po/purchase_order_saved/'.$po_id);
        }
    }

    public function add_pr(){
        $po_id=$this->input->post('po_id');
        $vendor_id =$this->input->post('vendor_id');
        $pr_id = $this->input->post('pr');
        $aoq_id = $this->super_model->custom_query_single("aoq_id","SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_vendors av ON ah.aoq_id = av.aoq_id WHERE ah.pr_id = '$pr_id' AND av.vendor_id = '$vendor_id'");
        $data = array(
            'po_id'=>$po_id,
            'pr_id'=>$pr_id,
            'aoq_id'=>$aoq_id,
            'enduse'=>$this->input->post('enduse'),
            'purpose'=>$this->input->post('purpose'),
            'requestor'=>$this->input->post('requestor')
        );
        if($this->super_model->insert_into("po_pr", $data)){
             redirect(base_url().'po/purchase_order/'.$po_id);
        }
    }

    public function add_tc(){
        $po_id = $this->input->post('po_id');
        $draft = $this->super_model->select_column_where("po_head", "draft", "po_id", $po_id);
        $data = array(
            'po_id'=>$this->input->post('po_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );


        if($this->super_model->insert_into("po_tc", $data)){
            if($draft==0){
                redirect(base_url().'po/purchase_order/'.$po_id, 'refresh');
            } else {
                redirect(base_url().'po/purchase_order_draft/'.$po_id, 'refresh');
            }
        }
    }

    public function getpr(){

        $pr = $this->input->post('pr');
        foreach($this->super_model->select_custom_where("pr_head", "pr_id = '$pr'") AS $head){
            $purpose= $head->purpose;
            $requestor= $head->requestor;
            $enduse= $head->enduse;
            $return = array('purpose' => $purpose, 'requestor' => $requestor, 'enduse' => $enduse);
            echo json_encode($return);
        }
    
    }

     public function purchase_order_draft(){
        $po_id = $this->uri->segment(3);
        $data['po_id'] = $po_id;
        $vendor_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        foreach($this->super_model->select_row_where('po_head', 'po_id', $po_id) AS $h){

            $data['head'][] = array(
                'po_date'=>$h->po_date,
                'po_no'=>$h->po_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
             
            );
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['po_no']=$h->po_no;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved_by']=$h->approved_by;
            $data['checked_by']=$h->checked_by;
            $data['recommended_by']=$h->recommended_by;
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }

        $data['items'] = $this->super_model->select_row_where('po_items', 'po_id', $po_id);
        $data['currency'] = $this->super_model->select_column_where('po_items', 'currency', 'po_id', $po_id);
        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $ppr){
            $data['allpr'][]= array(
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $ppr->pr_id),
                'enduse'=>$ppr->enduse,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['vendor_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'vendor_id', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'aoq_vendors_id', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
        }
        $data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $data['dr'] = $this->super_model->select_row_where("po_dr", "po_id", $po_id);
          $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('po/purchase_order_draft',$data);
        $this->load->view('template/footer');
    }

    public function update_terms_draft(){
        $po_id = $this->input->post('po_id');
        $aoq_vendors_id = $this->input->post('aoq_vendors_id');
        $update = array(
            'payment_terms'=>$this->input->post('payments'),
            'delivery_date'=>$this->input->post('del_itm'),
            'item_warranty'=>$this->input->post('item_war'),
            'freight'=>$this->input->post('freigh'),
        ); 
        if($this->super_model->update_where("aoq_vendors", $update, "aoq_vendors_id",$aoq_vendors_id)){
            
            redirect(base_url().'po/purchase_order_draft/'.$po_id);
        }
    }

    public function update_condition_draft(){
        $po_id = $this->input->post('po_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("po_tc", $update, "po_tc_id",$tc_id)){
            redirect(base_url().'po/purchase_order_draft/'.$po_id);
        }
    }


    public function purchase_order_saved(){
        $po_id = $this->uri->segment(3);
        $data['po_id'] = $po_id;
        $vendor_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        foreach($this->super_model->select_row_where('po_head', 'po_id', $po_id) AS $h){

            $data['head'][] = array(
                'po_date'=>$h->po_date,
                'po_no'=>$h->po_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['po_no']=$h->po_no;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }

        $data['items'] = $this->super_model->select_row_where('po_items', 'po_id', $po_id);
        $data['currency'] = $this->super_model->select_column_where('po_items', 'currency', 'po_id', $po_id);
        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $ppr){
            $data['allpr'][]= array(
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $ppr->pr_id),
                'enduse'=>$ppr->enduse,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['vendor_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'vendor_id', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'aoq_vendors_id', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
        }
        $data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $data['dr'] = $this->super_model->select_row_where("po_dr", "po_id", $po_id);
        $this->load->view('template/header');        
        $this->load->view('po/purchase_order_saved',$data);
        $this->load->view('template/footer');
    }

    public function update_terms_saved(){
        $po_id = $this->input->post('po_id');
        $aoq_vendors_id = $this->input->post('aoq_vendors_id');
        $update = array(
            'payment_terms'=>$this->input->post('payments'),
            'delivery_date'=>$this->input->post('del_itm'),
            'item_warranty'=>$this->input->post('item_war'),
            'freight'=>$this->input->post('freigh'),
        ); 
        if($this->super_model->update_where("aoq_vendors", $update, "aoq_vendors_id",$aoq_vendors_id)){
            
            redirect(base_url().'po/purchase_order_saved/'.$po_id);
        }
    }

    public function update_condition_saved(){
        $po_id = $this->input->post('po_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("po_tc", $update, "po_tc_id",$tc_id)){
            redirect(base_url().'po/purchase_order_saved/'.$po_id);
        }
    }

    public function revise_po(){
        $po_id=$this->uri->segment(3);
        $data = array(
            'revised'=>1
        );
        if($this->super_model->update_where("po_head", $data, "po_id", $po_id)){
            redirect(base_url().'po/purchase_order_saved/'.$po_id);
        }
    }

    public function upload_revise(){
        $po_id = $this->input->post('po_id');
        $po_no = $this->input->post('po_no');
        $error_ext=0;
        $dest= realpath(APPPATH . '../uploads/');
        if(!empty($_FILES['revise_img']['name'])){
             $revise_img= basename($_FILES['revise_img']['name']);
             $revise_img=explode('.',$revise_img);
             $ext1=$revise_img[1];
            
            if($ext1=='php' || ($ext1!='png' && $ext1 != 'jpg' && $ext1!='jpeg')){
                $error_ext++;
            } else {
                 $filename=$po_no.'.'.$ext1;
                 move_uploaded_file($_FILES["revise_img"]['tmp_name'], $dest.'/'.$filename);
            }
        } else {
            $filename="";
        }

        $count_item = $this->input->post('count_item');
        $vendor_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
      /*  foreach($this->super_model->select_row_where("po_pr", "po_id" , $po_id) AS $popr){
            foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1'") AS $off){
                for($x=1; $x<$count_item;$x++){
                    $qty = $this->input->post('qty'.$x);
                    //$new_balance = $off->balance +$qty;
                    $data_aoq =  array(
                        'balance'=>$qty,
                    );
                    $this->super_model->update_where("aoq_offers", $data_aoq, "aoq_offer_id", $this->input->post('aoq_offer_id'.$x));
                }
            }
        }*/

        $data=array(
            'saved'=>0,
            'revise_attachment'=>$filename,
        );
        if($this->super_model->update_where("po_head", $data, "po_id", $po_id)){
            redirect(base_url().'po/purchase_order/'.$po_id.'/r');
        }

    }

    public function get_name($column, $table, $where){
        $col = $this->super_model->select_column_custom_where($table, $column, $where);
        return $col;
    }

    public function purchase_order_saved_r(){
        $this->load->view('template/header'); 
        $po_id=$this->uri->segment(3);
        $revise_no=$this->uri->segment(4);
        $data['po_id']=$po_id;
        $data['revise_no']=$revise_no;
        $vendor_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        foreach($this->super_model->select_custom_where('po_head_revised', "po_id = '$po_id' AND revision_no = '$revise_no'") AS $h){
            $data['head'][] = array(
                'po_date'=>$h->po_date,
                'po_no'=>$h->po_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['po_no']=$h->po_no;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }
        $data['items'] = $this->super_model->select_custom_where('po_items_revised', "po_id = '$po_id' AND revision_no = '$revise_no'");
        foreach($this->super_model->select_custom_where("po_pr_revised", "po_id = '$po_id' AND revision_no = '$revise_no'") AS $ppr){
            $data['allpr'][]= array(
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $ppr->pr_id),
                'enduse'=>$ppr->enduse,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
        }       
        $data['tc'] = $this->super_model->select_custom_where("po_tc_revised", "po_id='$po_id' AND revision_no = '$revise_no'");
        $this->load->view('po/purchase_order_saved_r',$data);
        $this->load->view('template/footer');
    }

    public function view_history(){
        $this->load->view('template/header'); 
        $poid=$this->uri->segment(3);
        $po_no=$this->uri->segment(4);
        $data['po_no']=$po_no;
        $data['po_id']=$poid;

        $row = $this->super_model->count_rows_where("po_head_revised", "po_id",$poid);
        if($row!=0){
            foreach($this->super_model->select_custom_where("po_head_revised", "po_id = '$poid'") AS $rev){
                $data['revise'][]=array(
                    'po_id'=>$poid,
                    'po_no'=>$rev->po_no,
                    'revised_date'=>$rev->date_revised,
                    'revision_no'=>$rev->revision_no,
                );
            }
        }else {
            $data['revise']=array();
        }     
        $this->load->view('po/view_history',$data);
        $this->load->view('template/footer');
    }

    public function delivery_receipt(){
        $po_id = $this->uri->segment(3); 
        $dr_id = $this->uri->segment(4); 
        $data['head']= $this->super_model->select_row_where('po_head', 'po_id', $po_id);
        $data['revision_no']= $this->super_model->select_column_where("po_dr", "revision_no", "po_id", $po_id);
        
        $user_id= $this->super_model->select_column_where("po_head", "user_id", "po_id", $po_id);
        $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $user_id);
        $data['cancelled']=$this->super_model->select_column_where("po_head", "cancelled", "po_id", $po_id);
        foreach($this->super_model->select_row_where('po_pr', 'po_id', $po_id) AS $pr){
             $itemno='';
            foreach($this->super_model->select_custom_where("po_dr_items", "pr_id= '$pr->pr_id' AND po_id='$po_id' AND dr_id = '$dr_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $data['pr'][]=array(
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $pr->pr_id),
                'enduse'=>$pr->enduse,
                'purpose'=>$pr->purpose,
                'requestor'=>$pr->requestor,
                'item_no'=>$item_no
            );
        }

        if(empty($dr_id)){
            $data['dr_no']= $this->super_model->select_column_where("po_dr", "dr_no", "po_id", $po_id);
            foreach($this->super_model->select_custom_where("po_dr_items", "po_id='$po_id' AND dr_id = '$dr_id'") AS $items){
               $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
                $data['items'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("aoq_items", "item_description", "aoq_items_id", $items->aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'uom'=>$items->uom,
                );
            }
        } else {
            $data['dr_no']= $this->super_model->select_column_custom_where("po_dr", "dr_no", "po_id='$po_id' AND dr_id = '$dr_id'");
            foreach($this->super_model->select_custom_where('po_dr_items', "po_id= '$po_id' AND dr_id = '$dr_id'") AS $items){
               $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
                $data['items'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("aoq_items", "item_description", "aoq_items_id", $items->aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'uom'=>$items->uom,
                );
            }
        }
        $this->load->view('template/header');        
        $this->load->view('po/delivery_receipt',$data);
        $this->load->view('template/footer');
    }

    public function rfd_prnt(){   
        $po_id = $this->uri->segment(3);   
        $data['revised']=$this->super_model->select_column_where('po_head', 'revised', 'po_id', $po_id);
        $data['revision_no']=$this->super_model->select_column_where('po_head', 'revision_no', 'po_id', $po_id);
        $data['saved']= $this->super_model->select_column_where("rfd", "saved", "po_id", $po_id);
        $data['rows_dr'] = $this->super_model->select_count("rfd","po_id",$po_id);
        $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
        $data['po_no']= $this->super_model->select_column_where("po_head", "po_no", "po_id", $po_id);
        $data['po_type']= $this->super_model->select_column_where("po_head", "po_type", "po_id", $po_id);
        $data['shipping']= $this->super_model->select_column_where("po_head", "shipping", "po_id", $po_id);
        $data['discount']= $this->super_model->select_column_where("po_head", "discount", "po_id", $po_id);
        $data['packing']= $this->super_model->select_column_where("po_head", "packing_fee", "po_id", $po_id);
        $data['vatt']= $this->super_model->select_column_where("po_head", "vat", "po_id", $po_id);
        $data['vat_percent']= $this->super_model->select_column_where("po_head", "vat_percent", "po_id", $po_id);
        $data['po_id']= $po_id;
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['tin']= $this->super_model->select_column_where("vendor_head", "tin", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);
        $data['dr_no']= $this->super_model->select_column_where("po_dr", "dr_no", "po_id", $po_id);
        $data['cancelled']=$this->super_model->select_column_where("po_head", "cancelled", "po_id", $po_id);
        foreach($this->super_model->select_row_where('po_items', 'po_id', $po_id) AS $items){
            $total = $items->unit_price*$items->delivered_quantity;
            if(!empty($items->offer)){
                $offer = $items->offer;
            } else {
                $offer = $this->super_model->select_column_where("item", "item_name", "item_id", $items->item_id) . ", " . $this->super_model->select_column_where("item", "item_specs", "item_id", $items->item_id);
            }
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'offer'=>$offer,
                'quantity'=>$items->delivered_quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
                'uom'=>$items->uom,
            );

            $data['currency'] = $items->currency;
        }

          foreach($this->super_model->select_row_where('po_pr', 'po_id', $po_id) AS $pr){
             $itemno='';
            foreach($this->super_model->select_custom_where('po_items', "pr_id='$pr->pr_id' AND po_id = '$po_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $data['pr'][]=array(
                'pr_id'=>$pr->pr_id,
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $pr->pr_id),
                'enduse'=>$pr->enduse,
                'purpose'=>$pr->purpose,
                'requestor'=>$pr->requestor,
                'item_no'=>$item_no
            );
        }

        foreach($this->super_model->select_row_where('rfd', 'po_id', $po_id) AS $r){
            
            $data['company']=$r->company;
            $data['pay_to']=$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $r->pay_to);
            $data['check_name']=$r->check_name;
            $data['apv_no']=$r->apv_no;
            $data['rfd_date']=$r->rfd_date;
            $data['due_date']=$r->due_date;
            $data['check_due']=$r->check_due;
            $data['cash']=$r->cash_check;
            $data['bank_no']=$r->bank_no;
            $data['notes']=$r->notes;
            
            $data['checked']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->checked_by);
            $data['endorsed']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->endorsed_by);
            $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->noted_by);
            $data['received']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->received_by);
            $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->approved_by);
        }
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");

        $this->load->view('template/header');        
        $this->load->view('po/rfd_prnt',$data);
        $this->load->view('template/footer');
    }

    public function save_rfd(){
        $po_id= $this->input->post('po_id');

      /*  $dr_data = array(
            'dr_date'=>$this->input->post('rfd_date')
        );
        $this->super_model->update_where("po_dr", $dr_data, "po_id", $po_id);*/
        $data = array(
            'po_id'=>$po_id,
            'apv_no'=>$this->input->post('apv_no'),
            'rfd_date'=>$this->input->post('rfd_date'),
            'due_date'=>$this->input->post('due_date'),
            'check_due'=>$this->input->post('check_due'),
            'company'=>$this->input->post('company'),
            'pay_to'=>$this->input->post('pay_to'),
            'check_name'=>$this->input->post('check_name'),
            'cash_check'=>$this->input->post('cash'),
            'bank_no'=>$this->input->post('bank_no'),
            'total_amount'=>$this->input->post('total_amount'),
            'checked_by'=>$this->input->post('checked'),
            'endorsed_by'=>$this->input->post('endorsed'),
            'approved_by'=>$this->input->post('approved'),
            'noted_by'=>$this->input->post('noted'),
            'received_by'=>$this->input->post('received'),
            'rfd_type'=>$this->input->post('po_type'),
            'notes'=>$this->input->post('notes'),
            'user_id'=>$_SESSION['user_id'],
            'saved'=>1
        );

         if($this->super_model->insert_into("rfd", $data)){
            redirect(base_url().'po/rfd_prnt/'.$po_id, 'refresh');
        }
    }


    public function update_rfd(){
           $po_id= $this->input->post('po_id');

   
        $data = array(
            'rfd_date'=>$this->input->post('rfd_date'),
            'due_date'=>$this->input->post('due_date'),
            'check_due'=>$this->input->post('check_due'),
            'saved'=>1
        );

         if($this->super_model->update_where("rfd", $data, "po_id", $po_id)){
            redirect(base_url().'po/rfd_prnt/'.$po_id, 'refresh');
        }
    }

    public function reporder_prnt(){
        $po_id=$this->uri->segment(3);
       // $pr_id=$this->uri->segment(4);
        $group_id=$this->uri->segment(5);
        $data['po_id']=$po_id;
        
       // $data['pr_id']=$pr_id;
         $pr_id=$this->super_model->select_column_where('po_pr', 'pr_id', 'po_id', $po_id);
        $data['pr_id']=$pr_id;
        $data['group_id']=$group_id;
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $data['saved']=$this->super_model->select_column_where('po_head', 'saved', 'po_id', $po_id);
        $data['revised']=$this->super_model->select_column_where('po_head', 'revised', 'po_id', $po_id);
        $data['revision_no']=$this->super_model->select_column_where('po_head', 'revision_no', 'po_id', $po_id);
        $recommended_id = $this->super_model->select_column_where('po_head', 'recommended_by', 'po_id', $po_id);
        $approved_id = $this->super_model->select_column_where('po_head', 'approved_by', 'po_id', $po_id);
        $checked_id = $this->super_model->select_column_where('po_head', 'checked_by', 'po_id', $po_id);
        $data['approved_id'] = $approved_id;
        $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id',  $recommended_id );
        $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id',  $approved_id );
        $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id',  $checked_id);
        $vendor_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        foreach($this->super_model->select_row_where("po_head", "po_id", $po_id) AS $head){
            
            $data['head'][]=array(
                'po_date'=>$head->po_date,
                'po_no'=>$head->po_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id',$head->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id',$head->vendor_id)
            );
            $data['pr_no']=$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $pr_id);
            $data['shipping']=$head->shipping;
            $data['discount']=$head->discount;
            $data['packing']=$head->packing_fee;
            $data['vat']=$head->vat;
            $data['vat_percent']=$head->vat_percent;
            $data['vendor_id']=$head->vendor_id;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $head->user_id);
        }

        foreach($this->super_model->select_row_where("po_items","po_id",$po_id) AS $items){


            if(!empty($items->offer)){
                $offer=$items->offer;
            } else {
                $offer=$this->super_model->select_column_where('item', 'item_name', 'item_id', $items->item_id). ", " . $this->super_model->select_column_where('item', 'item_specs', 'item_id', $items->item_id);
            }

            $orig_po=$this->super_model->select_column_where('po_head', 'po_no', 'po_id', $items->source_poid);

            $data['items'][]=array(
                'quantity'=>$items->delivered_quantity,
                'offer'=>$offer,
                'price'=>$items->unit_price,
                'uom'=>$items->uom,
                'amount'=>$items->amount,
                'item_no'=>$items->item_no,
                'orig_pono'=>$orig_po

            );

            foreach($this->super_model->select_row_where("po_pr", "po_id", $items->source_poid) AS $poprs){
                $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'aoq_vendors_id', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
            }
        }

        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $popr){
            $data['po_pr_id']=$popr->po_pr_id;
            $data['popr'][] = array(
                'po_pr_id'=>$popr->po_pr_id,
                //'requestor'=>$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $popr->requestor),
                'requestor'=>$popr->requestor,
                'enduse'=>$popr->enduse,
                'purpose'=>$popr->purpose,
                'notes'=>$popr->notes,
            );
        }
        $data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $this->load->view('template/header');        
        $this->load->view('po/reporder_prnt',$data);
        $this->load->view('template/footer');
    }

    public function update_condition_reporder(){
        $po_id = $this->input->post('po_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("po_tc", $update, "po_tc_id",$tc_id)){
            redirect(base_url().'po/reporder_prnt/'.$po_id);
        }
    }

    public function reporder_prnt_draft(){
        $po_id=$this->uri->segment(3);
        $pr_id=$this->uri->segment(4);
        $group_id=$this->uri->segment(5);
        $data['po_id']=$po_id;
        $data['pr_id']=$pr_id;
        $data['group_id']=$group_id;
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $data['saved']=$this->super_model->select_column_where('po_head', 'saved', 'po_id', $po_id);
        $data['draft']=$this->super_model->select_column_where('po_head', 'draft', 'po_id', $po_id);
        $data['revised']=$this->super_model->select_column_where('po_head', 'revised', 'po_id', $po_id);
        $data['revision_no']=$this->super_model->select_column_where('po_head', 'revision_no', 'po_id', $po_id);
        $approved_id = $this->super_model->select_column_where('po_head', 'approved_by', 'po_id', $po_id);
        $checked_id = $this->super_model->select_column_where('po_head', 'checked_by', 'po_id', $po_id);
        $recommended_id = $this->super_model->select_column_where('po_head', 'recommended_by', 'po_id', $po_id);
        $data['approved_id'] = $approved_id;
        $data['checked_id'] = $checked_id;
        $data['recommended_id'] = $recommended_id;
        $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id',  $approved_id );
        $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id',  $checked_id);
        $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id',  $recommended_id);
        $vendor_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        foreach($this->super_model->select_row_where("po_head", "po_id", $po_id) AS $head){
            
            $data['head'][]=array(
                'po_date'=>$head->po_date,
                'po_no'=>$head->po_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id',$head->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id',$head->vendor_id)
            );
            $data['pr_no']=$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $pr_id);
            $data['shipping']=$head->shipping;
            $data['discount']=$head->discount;
            $data['packing']=$head->packing_fee;
            $data['vat']=$head->vat;
            $data['vat_percent']=$head->vat_percent;
            $data['vendor_id']=$head->vendor_id;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $head->user_id);
        }

        foreach($this->super_model->select_row_where("po_items","po_id",$po_id) AS $items){


            if(!empty($items->offer)){
                $offer=$items->offer;
            } else {
                $offer=$this->super_model->select_column_where('item', 'item_name', 'item_id', $items->item_id). ", " . $this->super_model->select_column_where('item', 'item_specs', 'item_id', $items->item_id);
            }

            $orig_po=$this->super_model->select_column_where('po_head', 'po_no', 'po_id', $items->source_poid);

            $data['items'][]=array(
                'po_items_id'=>$items->po_items_id,
                'quantity'=>$items->delivered_quantity,
                'offer'=>$offer,
                'price'=>$items->unit_price,
                'uom'=>$items->uom,
                'amount'=>$items->amount,
                'item_no'=>$items->item_no,
                'orig_pono'=>$orig_po

            );
            foreach($this->super_model->select_row_where("po_pr", "po_id", $items->source_poid) AS $poprs){
                $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('aoq_vendors', 'aoq_vendors_id', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
                $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$poprs->aoq_id' AND vendor_id='$vendor_id'");
            }
        }

        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $popr){
            $data['po_pr_id']=$popr->po_pr_id;
            $data['popr'][] = array(
                'po_pr_id'=>$popr->po_pr_id,
                //'requestor'=>$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $popr->requestor),
                'requestor'=>$popr->requestor,
                'enduse'=>$popr->enduse,
                'purpose'=>$popr->purpose,
                'notes'=>$popr->notes,
            );

            /*$data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id'");*/
        }
        $data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $this->load->view('template/header');        
        $this->load->view('po/reporder_prnt_draft',$data);
        $this->load->view('template/footer');
    }

    public function update_terms__repordersave(){
        $po_id = $this->input->post('po_id');
        $aoq_vendors_id = $this->input->post('aoq_vendors_id');
        $update = array(
            'payment_terms'=>$this->input->post('payments'),
            'delivery_date'=>$this->input->post('del_itm'),
            'item_warranty'=>$this->input->post('item_war'),
            'freight'=>$this->input->post('freigh'),
        ); 
        if($this->super_model->update_where("aoq_vendors", $update, "aoq_vendors_id",$aoq_vendors_id)){
            
            redirect(base_url().'po/reporder_prnt/'.$po_id);
        }
    }

    public function update_terms__reporderdraft(){
        $po_id = $this->input->post('po_id');
        $aoq_vendors_id = $this->input->post('aoq_vendors_id');
        $update = array(
            'payment_terms'=>$this->input->post('payments'),
            'delivery_date'=>$this->input->post('del_itm'),
            'item_warranty'=>$this->input->post('item_war'),
            'freight'=>$this->input->post('freigh'),
        ); 
        if($this->super_model->update_where("aoq_vendors", $update, "aoq_vendors_id",$aoq_vendors_id)){
            
            redirect(base_url().'po/reporder_prnt_draft/'.$po_id);
        }
    }

    public function update_condition_reporderdraft(){
        $po_id = $this->input->post('po_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("po_tc", $update, "po_tc_id",$tc_id)){
            redirect(base_url().'po/reporder_prnt_draft/'.$po_id);
        }
    }

    public function add_tc_reporder(){
        $po_id = $this->input->post('po_id');
        $data = array(
            'po_id'=>$this->input->post('po_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );
        if($this->super_model->insert_into("po_tc", $data)){
            redirect(base_url().'po/reporder_prnt/'.$po_id, 'refresh');
        }
    }

    public function add_purpose(){
        $po_id = $this->input->post('po_id');
        $po_pr_id = $this->input->post('po_pr_id');
        $data= array(
            //'po_id'=>$po_id,
            'purpose'=>$this->input->post('purpose'),
            'requestor'=>$this->input->post('requested_by'),
            'enduse'=>$this->input->post('enduse'),
            'notes'=>$this->input->post('notes')
        );
        //if($this->super_model->insert_into("po_pr", $data)){
        if($this->super_model->update_where("po_pr", $data, "po_pr_id", $po_pr_id)){
            redirect(base_url().'po/reporder_prnt/'.$po_id, 'refresh');
        }

    }

    public function save_repeatPO(){
        $submit = $this->input->post('submit');
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
        $rows_dr = $this->super_model->count_rows("po_dr");
        if($rows_dr==0){
            $dr_id = 1;
            $dr_no=1000;
        } else {
            $max = $this->super_model->get_max("po_dr", "dr_no");
            $maxid = $this->super_model->get_max("po_dr", "dr_id");
            $dr_no = $max+1;
            $dr_id = $maxid+1;
        }

        $dr = array(
            'dr_id'=>$dr_id,
            'po_id'=>$po_id,
            'dr_no'=>$dr_no,
        );
        $this->super_model->insert_into("po_dr", $dr);

        foreach($this->super_model->select_row_where("po_items", "po_id", $po_id) AS $head){
            $data_dr=array(
                'po_items_id'=>$head->po_items_id,  
                'pr_id'=>$head->pr_id,
                'dr_id'=>$dr_id,
                'po_id'=>$head->po_id,
                'aoq_offer_id'=>$head->aoq_offer_id,
                'aoq_items_id'=>$head->aoq_items_id,
                'pr_details_id'=>$head->pr_details_id,
                'offer'=>$head->offer,
                'delivered_quantity'=>$head->delivered_quantity,
                'uom'=>$head->uom,
                'unit_price'=>$head->unit_price,
                'amount'=>$head->amount,
                'item_no'=>$head->item_no,
            );
            $this->super_model->insert_into("po_dr_items", $data_dr);
        }
        
        if($submit=='Save'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'approved_by'=>$this->input->post('approved'),
                'recommended_by'=>$this->input->post('recommended'),
                'checked_by'=>$this->input->post('checked'),
                'saved'=>1,
                'draft'=>0,
                'revised'=>0
            );
            if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'po/reporder_prnt/'.$po_id);
            }
        } else if($submit=='Save as Draft'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'approved_by'=>$this->input->post('approved'),
                'recommended_by'=>$this->input->post('recommended'),
                'checked_by'=>$this->input->post('checked'),
                'saved'=>0,
                'draft'=>1,
                'revised'=>0
            );
            if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'po/reporder_prnt_draft/'.$po_id.'/'.$pr_id.'/'.$group_id);
            }
        }
    }

    public function save_repeatPO_draft(){
        $submit = $this->input->post('submit');
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
        $count_item = $this->input->post('count_item');
        $rows_dr = $this->super_model->count_rows("po_dr");
        if($rows_dr==0){
            $dr_id = 1;
            $dr_no=1000;
        } else {
            $max = $this->super_model->get_max("po_dr", "dr_no");
            $maxid = $this->super_model->get_max("po_dr", "dr_id");
            $dr_no = $max+1;
            $dr_id = $maxid+1;
        }

        $dr = array(
            'dr_id'=>$dr_id,
            'po_id'=>$po_id,
            'dr_no'=>$dr_no,
        );
        $this->super_model->insert_into("po_dr", $dr);
        $a=1;
        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $uom=$this->input->post('uom'.$x);
            $po_items_id = $this->input->post('po_items_id'.$x);

            if($qty!=0){
                $offer = str_replace(",", "", $this->input->post('offer'.$x));
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $data=array(
                 
                    'offer'=>$offer,
                    'delivered_quantity'=>$qty,
                    'uom'=>$uom,
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );
                $data_dr=array(
                    'offer'=>$offer,
                    'delivered_quantity'=>$qty,
                    'uom'=>$uom,
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );
                $this->super_model->update_where("po_items", $data, "po_items_id", $po_items_id);
                $this->super_model->update_where("po_dr_items", $data_dr, "po_items_id", $po_items_id);
                $a++;
            }else {
                $this->super_model->delete_where("po_items", "po_items_id", $po_items_id);
                $this->super_model->delete_where("po_dr_items", "po_items_id", $po_items_id);
            }
        }
        
        if($submit=='Save'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'approved_by'=>$this->input->post('approved'),
                'recommended_by'=>$this->input->post('recommended'),
                'checked_by'=>$this->input->post('checked'),
                'saved'=>1,
                'draft'=>0,
                'revised'=>0
            );
            if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'po/reporder_prnt/'.$po_id);
            }
        } else if($submit=='Save as Draft'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'approved_by'=>$this->input->post('approved'),
                'recommended_by'=>$this->input->post('recommended'),
                'checked_by'=>$this->input->post('checked'),
                'saved'=>0,
                'draft'=>1,
                'revised'=>0
            );
            if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'po/reporder_prnt_draft/'.$po_id.'/'.$pr_id.'/'.$group_id);
            }
        }
    }

    public function reporder_dr(){
        $this->load->view('template/header'); 
        $po_id = $this->uri->segment(3); 
        $data['head']= $this->super_model->select_row_where('po_head', 'po_id', $po_id);
        $data['revision_no']= $this->super_model->select_column_where("po_dr", "revision_no", "po_id", $po_id);
        $data['dr_no']= $this->super_model->select_column_where("po_dr", "dr_no", "po_id", $po_id);
        $user_id= $this->super_model->select_column_where("po_head", "user_id", "po_id", $po_id);
        $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $user_id);
        
        foreach($this->super_model->select_row_where('po_pr', 'po_id', $po_id) AS $pr){
             $itemno='';
            foreach($this->super_model->select_custom_where("po_items", "pr_id= '$pr->pr_id' AND po_id='$po_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $data['pr'][]=array(
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $pr->pr_id),
                'enduse'=>$pr->enduse,
                'purpose'=>$pr->purpose,
                //'requestor'=>$this->super_model->select_column_where('employees','employee_name','employee_id',$pr->requestor),
                'requestor'=>$pr->requestor,
                'item_no'=>$item_no
            );
        }

        foreach($this->super_model->select_row_where('po_items', 'po_id', $po_id) AS $items){
            $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
            if(!empty($items->offer)){
                $offer=$items->offer;
            } else {
                $offer=$this->super_model->select_column_where('item', 'item_name', 'item_id', $items->item_id). ", " . $this->super_model->select_column_where('item', 'item_specs', 'item_id', $items->item_id);
            }
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                'offer'=>$offer,
                //'offer'=>$items->offer,
                'received_quantity'=>$items->quantity,
                'quantity'=>$items->delivered_quantity,
                'uom'=>$items->uom,
            );
        }       
        $this->load->view('po/reporder_dr',$data);
        $this->load->view('template/footer');
    }

    public function addPo(){
        $vendor_id=$this->uri->segment(3);
        $po_id=$this->uri->segment(4);
        $pr_id=$this->uri->segment(5);
        $group_id=$this->uri->segment(6);
        $old_po=$this->uri->segment(7);
        $data['group_id'] = $group_id;
        $data['pr_id'] = $pr_id;
        $data['po_id'] = $po_id;
        $data['old_po'] = $old_po;
        $data['vendor_id'] = $vendor_id;

        $quantity='';
        foreach($this->super_model->select_custom_where("pr_details", "pr_id='$pr_id' AND grouping_id='$group_id'") AS $p){
            $data['pr_det'][]=array(
                'pr_details_id'=>$p->pr_details_id,
                'item_description'=>$p->item_description,
                'quantity'=>$p->quantity,
            );
        }

        $data['head']=$this->super_model->select_custom_where("po_head", "vendor_id = '$vendor_id' AND saved='1' AND cancelled='0' AND repeat_order = '0'");
    
            foreach($this->super_model->select_row_where("po_items", "po_id", $old_po) AS $item){

                if($item->pr_id !=0){
                   // foreach($this->super_model->select_row_where("po_pr",'po_pr_id',$item->pr_id) AS $p){ 
                        $pr_no = $this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $item->pr_id);
                    //}
                } else {
                    $pr_no = '';
                }


                if(!empty($item->offer)){
                    $offer=$item->offer;
                } else {
                    $offer=$this->super_model->select_column_where('item', 'item_name', 'item_id', $item->item_id). ", " . $this->super_model->select_column_where('item', 'item_specs', 'item_id', $item->item_id);
                }
                //$pr_details_id = $this->super_model->select_column_where('pr_details', 'pr_details_id', 'pr_id', $item->pr_id);
                $quantity = $this->super_model->select_column_where('pr_details', 'quantity', 'pr_details_id', $item->pr_details_id);
                $data['items'][] = array(
                    'pr_no'=>$pr_no,
                    'pr_details_id'=>$item->pr_details_id,
                    'pr_id'=>$item->pr_id,
                    'item_id'=>$item->po_items_id,
                    'offer'=>$offer,
                    'quantity'=>$quantity,
                    'uom'=>$item->uom,
                    'price'=>$item->unit_price,
                    'amount'=>$item->amount
                );
            }
      
        $this->load->view('template/header');        
        $this->load->view('po/addPo',$data);
        $this->load->view('template/footer');
    }

    public function add_repeatPO(){
        $count_item = $this->input->post('count_item');
        $old_po = $this->input->post('old_po');
        $vendor_id = $this->input->post('vendor_id');
        $po_id = $this->input->post('po_id');
        for($x=1;$x<$count_item;$x++){

            $quantity = $this->input->post('quantity'.$x);
            if($quantity!=0){

                $max_item = $this->super_model->count_rows_where('po_items','po_id',$po_id);
                if($max_item==0){
                    $item_no = 1;
                } else {
                    $max_no = $this->super_model->get_max_where("po_items", "item_no","po_id='$po_id'");
                    $item_no = $max_no +1;
                }

                $source_po= $this->super_model->select_column_where('po_items', 'po_id', 'po_items_id', $this->input->post('po_items_id'.$x));
                $item= $this->super_model->select_column_where('po_items', 'item_id', 'po_items_id', $this->input->post('po_items_id'.$x));
                $offer= $this->super_model->select_column_where('po_items', 'offer', 'po_items_id', $this->input->post('po_items_id'.$x));
                $price=$this->super_model->select_column_where('po_items', 'unit_price', 'po_items_id', $this->input->post('po_items_id'.$x));
                $uom=$this->super_model->select_column_where('po_items', 'uom', 'po_items_id', $this->input->post('po_items_id'.$x));
                $orig_po=$this->super_model->select_column_where('po_head', 'po_no', 'po_id', $source_po);
                $amount=$quantity*$price;
                $data =  array(
                    'po_id'=>$po_id,
                    'pr_id'=>$this->input->post('pr_id'),
                    'pr_details_id'=>$this->input->post('pr_details_id'.$x),
                    'item_id'=>$item,
                    'offer'=>$offer,
                    'delivered_quantity'=>$quantity,
                    'unit_price'=>$price,
                    'uom'=>$uom,
                    'amount'=>$amount,
                    'item_no'=>$item_no,
                    'source_poid'=>$source_po,
                );
                $this->super_model->insert_into("po_items", $data);
            }
        }
     /*   $data_pr=array(
            'po_id'=>$po_id,
            'pr_id'=>$this->input->post('pr_id'),
        );
        $this->super_model->insert_into("po_pr", $data_pr);*/
        foreach($this->super_model->select_row_where("po_pr", "po_id", $old_po) AS $ppr){
            $price_validity = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $payment_terms= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $item_warranty= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $freight= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $delivery_time= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $rfq_id= $this->super_model->select_column_custom_where('aoq_vendors', 'rfq_id', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data_aoq = array(
                'vendor_id'=>$vendor_id,
                'price_validity'=>$price_validity,
                'payment_terms'=>$payment_terms,
                'item_warranty'=>$item_warranty,
                'freight'=>$freight,
                'delivery_date'=>$delivery_time,
            );
            $this->super_model->insert_into("aoq_vendors", $data_aoq);
        }

        foreach($this->super_model->select_row_where('po_tc', 'po_id',  $old_po) AS $tc){
            $data_tc = array(
                'po_id'=>$po_id,
                'tc_desc'=>$tc->tc_desc
            );
            $this->super_model->insert_into("po_tc", $data_tc);
        }

     /*    $data_head=array(
            'saved'=>1,
        );
       $this->super_model->update_where("po_head", $data_head, "po_id", $po_id);*/
        ?>
        <script>
              window.onunload = refreshParent;
            function refreshParent() {
                window.opener.location.reload();
            }
            window.close();
            
        </script>
        <?php
    }

    public function delivery_receipt_r(){
        $po_id=$this->uri->segment(3);
        $revise_no=$this->uri->segment(4);
        $data['po_id']=$po_id;
        $data['revise_no']=$revise_no;
        $this->load->view('template/header');  
        $data['head']= $this->super_model->select_custom_where('po_head_revised', "po_id = '$po_id' AND revision_no = '$revise_no'");
        $data['revision_no']= $this->super_model->select_column_where("po_dr_revised", "revision_no", "po_id", $po_id);
        $data['dr_no']= $this->super_model->select_column_custom_where("po_dr_revised", "dr_no", "po_id = '$po_id' AND revision_no = '$revise_no'");
        $user_id= $this->super_model->select_column_where("po_head_revised", "user_id", "po_id", $po_id);
        $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $user_id);
        $data['cancelled']=$this->super_model->select_column_where("po_head_revised", "cancelled", "po_id", $po_id);
        foreach($this->super_model->select_custom_where('po_pr_revised', "po_id = '$po_id' AND revision_no = '$revise_no'") AS $pr){
             $itemno='';
            foreach($this->super_model->select_custom_where("po_items_revised", "pr_id= '$pr->pr_id' AND po_id='$po_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $data['pr'][]=array(
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $pr->pr_id),
                'enduse'=>$pr->enduse,
                'purpose'=>$pr->purpose,
                'requestor'=>$pr->requestor,
                'item_no'=>$item_no
            );
        }

        foreach($this->super_model->select_custom_where('po_items_revised', "po_id = '$po_id' AND revision_no = '$revise_no'") AS $items){
            $vendor_id= $this->super_model->select_column_where("po_head_revised", "vendor_id", "po_id", $po_id);
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                'item'=>$this->super_model->select_column_where("aoq_items", "item_description", "aoq_items_id", $items->aoq_items_id),
                'offer'=>$items->offer,
                'quantity'=>$items->quantity,
                'uom'=>$items->uom,
            );
        }      
        $this->load->view('po/delivery_receipt_r',$data);
        $this->load->view('template/footer');
    }


    public function rfd_prnt_r(){        
        $this->load->view('template/header');        
        $this->load->view('po/rfd_prnt_r');
        $this->load->view('template/footer');
    }


    public function served_po(){
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('template/navbar'); 
        foreach($this->super_model->select_custom_where("po_head", "saved='1' AND served='1' ORDER BY po_id DESC") AS $head){
             $rfd=$this->super_model->count_rows_where("po_dr","po_id",$head->po_id);
             $pr='';
            foreach($this->super_model->select_row_where("po_pr", "po_id", $head->po_id) AS $prd){
                $pr_no=$this->super_model->select_column_where('pr_head','pr_no','pr_id', $prd->pr_id);
                $pr .= $pr_no."<br>";
            }
            $data['header'][]=array(
                'po_id'=>$head->po_id,
                'po_date'=>$head->po_date,
                'po_no'=>$head->po_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                'pr'=>$pr,
                'rfd'=>$rfd,
                'po_type'=>$head->po_type
            );
        }  
        $this->load->view('po/served_po',$data);
        $this->load->view('template/footer');
    }

    public function purchase_order_rev(){

        $po_id=$this->uri->segment(3); 
        foreach($this->super_model->select_row_where("po_head", "po_id",$po_id) AS $head){
            $data['approved_by']=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);
            $data['prepared_by']=$this->super_model->select_column_where('users','fullname','user_id', $head->user_id);
            $data['revised']=$head->revised;
        }
        
        $data['po_id'] = $po_id;
        $vendor_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        foreach($this->super_model->select_row_where('po_head', 'po_id', $po_id) AS $h){
            $data['head'][] = array(
                'po_date'=>$h->po_date,
                'po_no'=>$h->po_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['po_type']=$h->po_type;
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['po_no']=$h->po_no;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }

        $data['items'] = $this->super_model->select_row_where('po_items', 'po_id', $po_id);
        $data['currency']= $this->super_model->select_column_where('po_items', 'currency', 'po_id', $po_id);
        $data['items_temp'] = $this->super_model->select_row_where('po_items_temp', 'po_id', $po_id);
        $data['currency_temp']= $this->super_model->select_column_where('po_items', 'currency', 'po_id', $po_id);
      
        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $ppr){
            $data['allpr'][]= array(
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $ppr->pr_id),
                'enduse'=>$ppr->enduse,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['price_validity'] = $this->super_model->select_column_custom_where('aoq_vendors', 'price_validity', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('aoq_vendors', 'payment_terms', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('aoq_vendors', 'item_warranty', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('aoq_vendors', 'freight', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('aoq_vendors', 'delivery_date', "aoq_id = '$ppr->aoq_id' AND vendor_id='$vendor_id'");
        }
        //$data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $data['tc_notes'] = $this->super_model->select_column_where("po_tc_temp",'notes',"po_id",$po_id);
        $data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $data['tc_temp'] = $this->super_model->select_row_where("po_tc_temp", "po_id", $po_id);
        //$data['tc'] = $this->super_model->select_row_where("po_tc_temp", "po_id", $po_id);
        $data['shipping_temp'] = $this->super_model->select_column_where('po_head_temp', 'shipping', 'po_id', $po_id);
        $data['discount_temp'] = $this->super_model->select_column_where('po_head_temp', 'discount', 'po_id', $po_id);
        $data['packing_temp'] = $this->super_model->select_column_where('po_head_temp', 'packing_fee', 'po_id', $po_id);
        $data['vat_temp'] = $this->super_model->select_column_where('po_head_temp', 'vat', 'po_id', $po_id);
        $data['vat_percent_temp'] = $this->super_model->select_column_where('po_head_temp', 'vat_percent', 'po_id', $po_id);

        $datarfd = array(
            'saved'=>0
        );
        $this->super_model-> update_where("rfd", $datarfd, "po_id", $po_id);

        $this->load->view('template/header');        
        $this->load->view('po/purchase_order_rev', $data);
        $this->load->view('template/footer');
    }

    public function add_tc_temp(){
        $po_id = $this->input->post('po_id');

        $rows_head = $this->super_model->count_rows("po_tc");
        if($rows_head==0){
            $po_tc_id=1;
        } else {
            $max = $this->super_model->get_max("po_tc", "po_tc_id");
            $po_tc_id = $max+1;
        }
        $data = array(
            'po_tc_id'=>$po_tc_id,
            'po_id'=>$this->input->post('po_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );
        if($this->super_model->insert_into("po_tc", $data)){
            redirect(base_url().'po/purchase_order_rev/'.$po_id, 'refresh');
        }
    }

    public function add_otherins_temp(){
        $po_id = $this->input->post('po_id');
        $rows_head = $this->super_model->count_rows("po_tc");
        if($rows_head==0){
            $po_tc_id=1;
        } else {
            $max = $this->super_model->get_max("po_tc", "po_tc_id");
            $po_tc_id = $max+1;
        }
        $data = array(
            'po_tc_id'=>$po_tc_id,
            'po_id'=>$this->input->post('po_id'),
            'notes'=>$this->input->post('notes'),
        );
        if($this->super_model->insert_into("po_tc", $data)){
            redirect(base_url().'po/purchase_order_rev/'.$po_id, 'refresh');
        }
    }

    public function save_change_order(){
        $po_id = $this->input->post('po_id');
        $x=1;
        $timestamp = date('Y-m-d');
     /*   $max_revision = $this->super_model->get_max_where("po_head", "revision_no","po_id = '$po_id'");
        $revision_no = $max_revision+1;*/

        $data_head = array(
            'po_date'=>$timestamp,
            'po_id'=>$po_id,
            'shipping'=>$this->input->post('shipping'),
            'packing_fee'=>$this->input->post('packing'),
            'vat'=>$this->input->post('vat'),
            'vat_percent'=>$this->input->post('vat_percent'),
            'discount'=>$this->input->post('discount')
        );
        $this->super_model->insert_into("po_head_temp", $data_head);
          foreach($this->super_model->select_row_where("po_items","po_id",$po_id) AS $poitems){
            if($this->input->post('quantity'.$x)!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                
                    $data_items = array(
                        "po_items_id"=>$poitems->po_items_id,
                        "pr_id"=>$poitems->pr_id,
                        "po_id"=>$poitems->po_id,
                        "aoq_offer_id"=>$poitems->aoq_offer_id,
                        "aoq_items_id"=>$poitems->aoq_items_id,
                        "pr_details_id"=>$poitems->pr_details_id,
                        "offer"=>$this->input->post('offer'.$x),
                        "item_id"=>$poitems->item_id,
                        "delivered_quantity"=>$this->input->post('quantity'.$x),
                        "quantity"=>$poitems->delivered_quantity,
                        "unit_price"=>$price,
                        "uom"=>$this->input->post('uom'.$x),
                        "amount"=>$amount,
                        "item_no"=>$poitems->item_no,
                       /* "revision_no"=>$revision_no*/
                    );
                $this->super_model->insert_into("po_items_temp", $data_items);
        
            }
                $x++;
        }

        $y=1;
        foreach($this->super_model->select_row_where("po_tc","po_id",$po_id) AS $potc){
            $data_tci = array(
                "po_tc_id"=>$potc->po_tc_id,
                "po_id"=>$po_id,
                "tc_desc"=>$this->input->post('terms'.$y),
                //"notes"=>$this->input->post('notes'),
            );
            if($this->super_model->insert_into("po_tc_temp", $data_tci)){
                $data_notes = array(
                    "notes"=>$this->input->post('notes'.$y),
                );
                $this->super_model->update_where("po_tc_temp", $data_notes, "po_tc_id", $potc->po_tc_id);
            }


            $y++;
        }

        $data_head = array(
            'revised'=>1
        );

        if($this->super_model->update_where("po_head", $data_head, "po_id", $po_id)){
            redirect(base_url().'pod/purchase_order_rev/'.$po_id);
        }
    }

    public function approve_revision(){
        $po_id = $this->input->post('po_id');
        $max_revision = $this->super_model->get_max_where("po_head", "revision_no","po_id = '$po_id'");
        $revision_no = $max_revision+1;

       $rows_dr = $this->super_model->count_rows("po_dr");
        if($rows_dr==0){
            $dr_no=1000;
        } else {
            $max = $this->super_model->get_max("po_dr", "dr_no");
            $dr_no = $max+1;
        }

        $rows_series = $this->super_model->count_rows("po_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("po_series", "series");
            $series = $max+1;
        }

        $data_series = array(
            'series'=>$series
        );
        $this->super_model->insert_into("po_series", $data_series);

        foreach($this->super_model->select_row_where("po_dr","po_id",$po_id) AS $drs){
            $data_dr=array(
                'dr_id'=>$drs->dr_id,
                'po_id'=>$drs->po_id,
                'dr_no'=>$drs->dr_no,
                'dr_date'=>$drs->dr_date,
                'dr_type'=>$drs->dr_type,
                'saved'=>$drs->saved,
                'revision_no'=>$drs->revision_no,
            );
            if($this->super_model->insert_into("po_dr_revised", $data_dr)){
                $dr = array(
                    'dr_no'=>$dr_no,
                    'received'=>0,
                    'date_received'=>NULL
                );
                $this->super_model->update_where("po_dr", $dr, "dr_id", $drs->dr_id);
            }
        }

        foreach($this->super_model->select_row_where("po_dr_items","po_id",$po_id) AS $dritems){
            $data_dritems=array(
                'dr_items_id'=>$dritems->dr_items_id,
                'po_items_id'=>$dritems->po_items_id,
                'dr_id'=>$dritems->dr_id,
                'pr_id'=>$dritems->pr_id,
                'po_id'=>$dritems->po_id,
                'aoq_offer_id'=>$dritems->aoq_offer_id,
                'aoq_items_id'=>$dritems->aoq_items_id,
                'pr_details_id'=>$dritems->pr_details_id,
                'offer'=>$dritems->offer,
                'item_id'=>$dritems->item_id,
                'delivered_quantity'=>$dritems->delivered_quantity,
                'quantity'=>$dritems->quantity,
                'unit_price'=>$dritems->unit_price,
                'uom'=>$dritems->uom,
                'amount'=>$dritems->amount,
                'item_no'=>$dritems->item_no,
                'revision_no'=>$dritems->revision_no
            );
            $this->super_model->insert_into("po_dr_items_revised", $data_dritems);
        }
        
        $data_drs =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("po_dr", $data_drs, "po_id", $po_id);

        $pr_id = $this->super_model->select_column_where("po_items","pr_id","po_id",$po_id);
        $pr_no = $this->super_model->select_column_where("pr_head","pr_no","pr_id",$pr_id);
        $po_no = "P".$pr_no."-".$series;
        foreach($this->super_model->select_row_where("po_head","po_id",$po_id) AS $head){
            $data_head = array(
                "po_id"=>$head->po_id,
                "po_date"=>$head->po_date,
                "po_no"=>$head->po_no,
                "dr_no"=>$head->dr_no,
                "vendor_id"=>$head->vendor_id,
                "notes"=>$head->notes,
                "po_type"=>$head->po_type,
                "user_id"=>$head->user_id,
                "shipping"=>$head->shipping,
                "discount"=>$head->discount,
                "packing_fee"=>$head->packing_fee,
                "vat"=>$head->vat,
                "vat_percent"=>$head->vat_percent,
                "approved_by"=>$head->approved_by,
                "checked_by"=>$head->checked_by,
                "saved"=>$head->saved,
                "done_po"=>$head->done_po,
                "date_revised"=>$this->input->post('approve_date'),
                "revision_no"=>$head->revision_no,
                "revise_attachment"=>$head->revise_attachment,
            );
            if($this->super_model->insert_into("po_head_revised", $data_head)){
                foreach($this->super_model->select_row_where("po_head_temp","po_id",$po_id) AS $headt){
                    $data_po=array(
                        "po_date"=>$headt->po_date,
                        "shipping"=>$headt->shipping,
                        "packing_fee"=>$headt->packing_fee,
                        "vat"=>$headt->vat,
                        "vat_percent"=>$headt->vat_percent,
                        "discount"=>$headt->discount,
                      
                    );


                }

              
                $this->super_model->update_where("po_head", $data_po, "po_id", $po_id);
            }
        }

        foreach($this->super_model->select_row_where("po_pr","po_id",$po_id) AS $popr){
            $data_popr = array(
                "po_pr_id"=>$popr->po_pr_id,
                "po_id"=>$popr->po_id,
                "aoq_id"=>$popr->aoq_id,
                "enduse"=>$popr->enduse,
                "purpose"=>$popr->purpose,
                "requestor"=>$popr->requestor,
                "notes"=>$popr->notes,
                "revision_no"=>$popr->revision_no,
            );
            $this->super_model->insert_into("po_pr_revised", $data_popr);
        }

        foreach($this->super_model->select_row_where("po_items","po_id",$po_id) AS $poitems){
            $data_items = array(
                "po_items_id"=>$poitems->po_items_id,
                "pr_id"=>$poitems->pr_id,
                "po_id"=>$poitems->po_id,
                "aoq_offer_id"=>$poitems->aoq_offer_id,
                "aoq_items_id"=>$poitems->aoq_items_id,
                "pr_details_id"=>$poitems->pr_details_id,
                "offer"=>$poitems->offer,
                "item_id"=>$poitems->item_id,
                "delivered_quantity"=>$poitems->delivered_quantity,
                "unit_price"=>$poitems->unit_price,
                "uom"=>$poitems->uom,
                "amount"=>$poitems->amount,
                "item_no"=>$poitems->item_no,
                "revision_no"=>$poitems->revision_no,
            );
            $this->super_model->insert_into("po_items_revised", $data_items);
        }


        foreach($this->super_model->select_row_where("po_tc","po_id",$po_id) AS $potc){
            $data_potc = array(
                "po_tc_id"=>$potc->po_tc_id,
                "po_id"=>$popr->po_id,
                "tc_desc"=>$potc->tc_desc,
                "notes"=>$potc->notes,
                "revision_no"=>$potc->revision_no,
            );
            $this->super_model->insert_into("po_tc_revised", $data_potc);
        }

        $data_tcn =array(
            'revision_no'=>$revision_no
        );

        $this->super_model->update_where("po_tc", $data_tcn, "po_id", $po_id);

        foreach($this->super_model->select_row_where("po_tc_temp","po_id",$po_id) AS $potcr){
            $data_rev = array(
                "po_tc_id"=>$potcr->po_tc_id,
                "po_id"=>$popr->po_id,
                "tc_desc"=>$potcr->tc_desc,
                "notes"=>$potcr->notes,
                "revision_no"=>$potcr->revision_no,
            );
            $this->super_model->update_where("po_tc", $data_rev, "po_tc_id", $potcr->po_tc_id);
        }


        foreach($this->super_model->custom_query("SELECT po_items_id FROM po_items WHERE po_items_id NOT IN (SELECT po_items_id FROM po_items_temp WHERE po_id='$po_id')  AND po_id = '$po_id'") AS $omit){
           
            $delete_item = $this->super_model->delete_custom_where("po_items", "po_items_id= 
                '$omit->po_items_id'");

            $delete_dr = $this->super_model->delete_custom_where("po_dr_items", "po_items_id= 
                '$omit->po_items_id'");

        }


        foreach($this->super_model->select_row_where("po_items_temp","po_id",$po_id) AS $poitems){
            $oldqty = $this->super_model->select_column_where('po_items', 'quantity', 'po_items_id',  $poitems->po_items_id);
            if($oldqty==0){
                $nqty=0;
            } else {
                $nqty = $oldqty-$poitems->quantity;
            }


            $data_items = array(
           
                "pr_id"=>$poitems->pr_id,
                "po_id"=>$poitems->po_id,
                "aoq_offer_id"=>$poitems->aoq_offer_id,
                "aoq_items_id"=>$poitems->aoq_items_id,
                "pr_details_id"=>$poitems->pr_details_id,
                "offer"=>$poitems->offer,
                "item_id"=>$poitems->item_id,
                "delivered_quantity"=>$poitems->delivered_quantity,
                "quantity"=>$nqty,
                "unit_price"=>$poitems->unit_price,
                "uom"=>$poitems->uom,
                "amount"=>$poitems->amount,
                "item_no"=>$poitems->item_no,
                "revision_no"=>$revision_no
            );
             $this->super_model->update_where("po_items", $data_items, "po_items_id", $poitems->po_items_id);

            $data_dr_items = array(
                'delivered_quantity'=>$poitems->delivered_quantity,
                'quantity'=>0,
                "uom"=>$poitems->uom,
                'unit_price'=>$poitems->unit_price,
                'amount'=>$poitems->amount,
                'offer'=>$poitems->offer
            );

            $this->super_model->update_where("po_dr_items", $data_dr_items, "po_items_id", $poitems->po_items_id);

            $old_qty = $this->super_model->select_column_where('po_items', 'delivered_quantity', 'aoq_offer_id',  $poitems->aoq_offer_id);
            if($old_qty!=$poitems->delivered_quantity){
           
                $difference = $old_qty - $poitems->quantity;

                $old_balance = $this->super_model->select_column_where('aoq_offers', 'balance', 'aoq_offer_id',  $poitems->aoq_offer_id);

                $balance = $old_balance+$difference;

                $data_aoq = array(
                    'balance'=>$balance
                );
                $this->super_model->update_where("aoq_offers", $data_aoq, "aoq_offer_id", $poitems->aoq_offer_id);
            } else {
             
                $balance = $this->super_model->select_column_where('aoq_offers', 'balance', 'aoq_offer_id',  $poitems->aoq_offer_id);
                $data_aoq = array(
                    'balance'=>$balance
                );
                $this->super_model->update_where("aoq_offers", $data_aoq, "aoq_offer_id", $poitems->aoq_offer_id);
            }
         
            
        }
        $this->super_model->delete_where("po_head_temp", "po_id", $po_id);
        $this->super_model->delete_where("po_tc_temp", "po_id", $po_id);
        $this->super_model->delete_where("po_items_temp", "po_id", $po_id);    
        $data_pr =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("po_pr", $data_pr, "po_id", $po_id);

        $data =array(
            'served'=>0,
            'date_served'=>NULL,
            'served_by'=>0,
            'approve_rev_by'=>$this->input->post('approve_rev'),
            'approve_rev_date'=>$this->input->post('approve_date'),
            'revised'=>0,
            'revision_no'=>$revision_no
        );
        if($this->super_model->update_where("po_head", $data, "po_id", $po_id)){
            redirect(base_url().'po/po_list/', 'refresh');
        }
    }

    public function deliver_po(){
        $po_id = $this->uri->segment(3);
        $dr_id = $this->uri->segment(4);
        $data['po_id']=$po_id;
        $data['dr_id']=$dr_id;
        $data['items'] = $this->super_model->select_custom_where("po_dr_items","po_id='$po_id' AND dr_id = '$dr_id'");
        $this->load->view('template/header');        
        $this->load->view('po/deliver_po',$data);
        $this->load->view('template/footer');
    }


    public function save_delivery(){
        $count = $this->input->post('count');
        $po_id = $this->input->post('po_id');
        $dr_id = $this->input->post('dr_id');

        $data_dr = array(
            'received'=>1,
            'date_received'=>$this->input->post('date_delivered'),
        );

         $this->super_model->update_where("po_dr", $data_dr, "dr_id", $dr_id);

        $data_head = array(
            'served'=>1,
            'served_by'=>$_SESSION['user_id']
        );

         $this->super_model->update_where("po_head", $data_head, "po_id", $po_id);

        for($x=1;$x<$count;$x++){
            $po_items_id = $this->input->post('po_items_id'.$x);
            $curr_rec_balance = $this->super_model->select_column_where('po_items', 'quantity', 'po_items_id', $po_items_id);
            $new_qty = $curr_rec_balance + $this->input->post('received_qty'.$x);

             $data_poitems=array(
                'quantity'=>$new_qty
            );

            $data=array(
                'quantity'=>$this->input->post('received_qty'.$x)
            );


           
            $this->super_model->update_where("po_items", $data_poitems, "po_items_id", $po_items_id);
            $this->super_model->update_custom_where("po_dr_items", $data, "po_items_id='$po_items_id' AND dr_id='$dr_id'");

          $curr_balance = $this->super_model->select_column_where('aoq_offers', 'balance', 'aoq_offer_id', $this->input->post('aoq_offer_id'.$x));
          $new_balance = $curr_balance-$this->input->post('received_qty'.$x);

            $data_aoq = array(
                'balance'=>$new_balance
            );
            $this->super_model->update_where("aoq_offers", $data_aoq, "aoq_offer_id", $this->input->post('aoq_offer_id'.$x));

        }  

            ?>
        <script>
              window.onunload = refreshParent;
            function refreshParent() {
                window.opener.location.reload();
            }
            window.close();
            
        </script>
        <?php
    }

    public function incom_podel(){
           $data=array();
         foreach($this->super_model->custom_query("SELECT ph.* FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id WHERE saved='1' AND cancelled = '0' AND served = '1' AND pi.delivered_quantity > pi.quantity GROUP BY po_id ORDER BY ph.po_id DESC") AS $head){
             $rfd=$this->super_model->count_rows_where("rfd","po_id",$head->po_id);
             $pr='';
            foreach($this->super_model->select_row_where("po_pr", "po_id", $head->po_id) AS $prd){
                $pr_no=$this->super_model->select_column_where('pr_head','pr_no','pr_id', $prd->pr_id);
                $pr .= $pr_no."<br>";
            }
            $dr_id = $this->super_model->select_column_custom_where("po_dr", "dr_id", "po_id = '$head->po_id' AND received='0'");
            $unreceived_dr = $this->super_model->count_custom_where("po_dr","po_id = '$head->po_id' AND received ='0'");
            $data['header'][]=array(
                'po_id'=>$head->po_id,
                'po_date'=>$head->po_date,
                'po_no'=>$head->po_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                'po_type'=>$head->po_type,
                'pr'=>$pr,
                'rfd'=>$rfd,
                'po_type'=>$head->po_type,
                'revised'=>$head->revised,
                'revision_no'=>$head->revision_no,
                'served'=>$head->served,
                'unreceived_dr'=>$unreceived_dr,
                'dr_id'=>$dr_id
            );
        }        
        $this->load->view('template/header');        
        $this->load->view('template/navbar');        
        $this->load->view('po/incom_podel',$data);
        $this->load->view('template/footer');
    }


    public function create_dr(){
         $po_id = $this->uri->segment(3);

        $rows_dr = $this->super_model->count_rows("po_dr");
        if($rows_dr==0){
            $dr_no=1000;
            $dr_id = 1;
        } else {
            $max = $this->super_model->get_max("po_dr", "dr_no");
            $maxid = $this->super_model->get_max("po_dr", "dr_id");
            $dr_no = $max+1;
            $dr_id = $maxid+1;
        }

         $dr = array(
            'dr_id'=>$dr_id,
            'po_id'=>$po_id,
            'dr_no'=>$dr_no,
            'dr_date'=>$this->super_model->select_column_where('po_head', 'po_date', 'po_id', $po_id),
        );
        $this->super_model->insert_into("po_dr", $dr);

        foreach($this->super_model->custom_query("SELECT * FROM po_items WHERE delivered_quantity > quantity AND po_id = '$po_id'") AS $items){
            $new_qty = $items->delivered_quantity-$items->quantity;
            $data = array(
                'po_items_id'=>$items->po_items_id,
                'dr_id'=>$dr_id,
                'pr_id'=>$items->pr_id,
                'po_id'=>$items->po_id,
                'aoq_offer_id'=>$items->aoq_offer_id,
                'aoq_items_id'=>$items->aoq_items_id,
                'pr_details_id'=>$items->pr_details_id,
                'offer'=>$items->offer,
                'item_id'=>$items->item_id,
                'delivered_quantity'=>$new_qty,
                'unit_price'=>$items->unit_price,
                'uom'=>$items->uom,
                'amount'=>$items->amount,
                'item_no'=>$items->item_no
            );
            $this->super_model->insert_into("po_dr_items", $data);
        }

          redirect(base_url().'po/delivery_receipt/'.$po_id.'/'.$dr_id, 'refresh');
    }

    public function get_pn($pr_details_id){
        $name = $this->super_model->select_column_where("pr_details", "part_no", "pr_details_id", $pr_details_id);
        return $name;
    }
    public function rfd_calapan(){ 
        $this->load->view('template/header');    
        $po_id = $this->uri->segment(3);   
        $data['saved']= $this->super_model->select_column_where("rfd", "saved", "po_id", $po_id);
        $data['rows_dr'] = $this->super_model->select_count("rfd","po_id",$po_id);
        $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
        $data['po_no']= $this->super_model->select_column_where("po_head", "po_no", "po_id", $po_id);
        $data['po_type']= $this->super_model->select_column_where("po_head", "po_type", "po_id", $po_id);
        $data['shipping']= $this->super_model->select_column_where("po_head", "shipping", "po_id", $po_id);
        $data['discount']= $this->super_model->select_column_where("po_head", "discount", "po_id", $po_id);
        $data['packing']= $this->super_model->select_column_where("po_head", "packing_fee", "po_id", $po_id);
        $data['vatt']= $this->super_model->select_column_where("po_head", "vat", "po_id", $po_id);
        $data['vat_percent']= $this->super_model->select_column_where("po_head", "vat_percent", "po_id", $po_id);
        $data['po_id']= $po_id;
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['tin']= $this->super_model->select_column_where("vendor_head", "tin", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);
        $data['dr_no']= $this->super_model->select_column_where("po_dr", "dr_no", "po_id", $po_id);
        $data['cancelled']=$this->super_model->select_column_where("po_head", "cancelled", "po_id", $po_id);
        foreach($this->super_model->select_row_where('po_items', 'po_id', $po_id) AS $items){
            $total = $items->unit_price*$items->delivered_quantity;
            if(!empty($items->offer)){
                $offer = $items->offer;
            } else {
                $offer = $this->super_model->select_column_where("item", "item_name", "item_id", $items->item_id) . ", " . $this->super_model->select_column_where("item", "item_specs", "item_id", $items->item_id);
            }
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'offer'=>$offer,
                'quantity'=>$items->delivered_quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
                'uom'=>$items->uom,
            );

            $data['currency'] = $items->currency;
        }

          foreach($this->super_model->select_row_where('po_pr', 'po_id', $po_id) AS $pr){
             $itemno='';
            foreach($this->super_model->select_custom_where('po_items', "pr_id='$pr->pr_id' AND po_id = '$po_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $data['pr'][]=array(
                'pr_id'=>$pr->pr_id,
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $pr->pr_id),
                'enduse'=>$pr->enduse,
                'purpose'=>$pr->purpose,
                'requestor'=>$pr->requestor,
                'item_no'=>$item_no
            );
        }

        foreach($this->super_model->select_row_where('rfd', 'po_id', $po_id) AS $r){
            
            $data['company']=$r->company;
            $data['pay_to']=$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $r->pay_to);
            $data['check_name']=$r->check_name;
            $data['apv_no']=$r->apv_no;
            $data['rfd_date']=$r->rfd_date;
            $data['due_date']=$r->due_date;
            $data['check_due']=$r->check_due;
            $data['cash']=$r->cash_check;
            $data['bank_no']=$r->bank_no;
            $data['notes']=$r->notes;
            
            $data['checked']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->checked_by);
            $data['endorsed']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->endorsed_by);
            $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->noted_by);
            $data['received']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->received_by);
            $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->approved_by);
        }
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");     
        $this->load->view('po/rfd_calapan',$data);
        $this->load->view('template/footer');
    }

    public function save_rfd_calapan(){
        $po_id= $this->input->post('po_id');
        $data = array(
            'po_id'=>$po_id,
            'apv_no'=>$this->input->post('apv_no'),
            'rfd_date'=>$this->input->post('rfd_date'),
            'due_date'=>$this->input->post('due_date'),
            'company'=>$this->input->post('company'),
            'pay_to'=>$this->input->post('pay_to'),
            'cash_check'=>$this->input->post('cash'),
            'bank_no'=>$this->input->post('bank_no'),
            'checked_by'=>$this->input->post('checked'),
            'endorsed_by'=>$this->input->post('endorsed'),
            'approved_by'=>$this->input->post('approved'),
            'noted_by'=>$this->input->post('noted'),
            'received_by'=>$this->input->post('received'),
            'rfd_type'=>$this->input->post('po_type'),
            'user_id'=>$_SESSION['user_id'],
            'saved'=>1
        );

        if($this->super_model->insert_into("rfd", $data)){
            redirect(base_url().'po/rfd_calapan/'.$po_id, 'refresh');
        }
    }


    public function update_rfd_calapan(){
        $po_id= $this->input->post('po_id');
        $data = array(
            'rfd_date'=>$this->input->post('rfd_date'),
            'due_date'=>$this->input->post('due_date'),
            'saved'=>1
        );
        
        if($this->super_model->update_where("rfd", $data, "po_id", $po_id)){
            redirect(base_url().'po/rfd_calapan/'.$po_id, 'refresh');
        }
    }

    public function rfd_calapan_r(){ 
        $this->load->view('template/header');         
        $this->load->view('po/rfd_calapan_r');
        $this->load->view('template/footer');
    }

    public function quantity_of_pr(){
       $pr_details_id = $_POST['id'];
       $quantity = $this->super_model->select_column_where("pr_details", "quantity", "pr_details_id", $pr_details_id);
       echo $quantity;
    }
    
}
?>