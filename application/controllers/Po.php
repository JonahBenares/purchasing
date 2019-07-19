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

	}

    public function cancelled_po(){
        $data['header']=array();
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('template/navbar');
        foreach($this->super_model->select_custom_where("po_head", "saved='1' AND done_po = '0' AND cancelled = '1' ORDER BY po_id DESC") AS $head){
            $data['header'][]=array(
                'po_id'=>$head->po_id,
                'po_date'=>$head->po_date,
                'po_no'=>$head->po_no,
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
        foreach($this->super_model->select_custom_where("po_head", "saved='1' AND done_po = '0' AND cancelled = '0' ORDER BY po_id DESC") AS $head){
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
                'po_type'=>$head->po_type,
                'pr'=>$pr,
                'rfd'=>$rfd,
            );
        }        
        $this->load->view('po/po_list',$data);
        $this->load->view('template/footer');
    }

    public function update_done(){
        $poid=$this->uri->segment(3);
        $data = array(
            'done_po'=>1
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

        if(empty($this->input->post('dp'))){
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
                'po_no'=>$this->input->post('po_no'),
                'vendor_id'=>$this->input->post('vendor'),
                'notes'=>$this->input->post('notes'),
                'po_type'=>0,
                'user_id'=>$_SESSION['user_id']
            );  

            if($this->super_model->insert_into("po_head", $data)){
                 redirect(base_url().'po/purchase_order/'.$po_id);
            }
        }else {
            $data= array(
                'po_id'=>$po_id,
                'po_date'=>$this->input->post('po_date'),
                'po_no'=>$this->input->post('po_no'),
                'vendor_id'=>$this->input->post('vendor'),
                'notes'=>$this->input->post('notes'),
                'po_type'=>1,
                'user_id'=>$_SESSION['user_id']
            );  

            if($this->super_model->insert_into("po_head", $data)){
                 redirect(base_url().'pod/po_direct/'.$po_id);
            }
        }
    }


    public function getsupplierPR(){
        $supplier = $this->input->post('supplier');
        echo '<option value="">-Select PR No-</option>';
        foreach($this->super_model->custom_query("SELECT ah.pr_id, ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ao ON ah.aoq_id = ao.aoq_id WHERE vendor_id = '$supplier' AND recommended = '1' GROUP BY ah.pr_id") AS $row){

            echo '<option value="'. $row->pr_id."_".$row->aoq_id .'">'. $this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $row->pr_id) .'</option>';
      
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

    public function purchase_order(){
        $po_id = $this->uri->segment(3);
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
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['saved']=$h->saved;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
        }

        foreach($this->super_model->custom_query("SELECT ao.aoq_id, ah.pr_id FROM aoq_offers ao INNER JOIN aoq_head ah ON ao.aoq_id = ah.aoq_id WHERE ao.vendor_id = '$vendor_id' AND recommended = '1' GROUP BY pr_id") AS $off){
            $data['pr'][]=array(
                'pr_id'=>$off->pr_id,
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $off->pr_id),
            );
        }

        foreach($this->super_model->select_row_where("po_pr", "po_id" , $po_id) AS $popr){
            //echo "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1'<br>";
            // /echo "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1'";
            foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1'") AS $off){

                $total = $off->unit_price*$off->quantity;
                $data['items'][] =  array(
                    'aoq_id'=>$off->aoq_id,
                    'aoq_offer_id'=>$off->aoq_offer_id,
                    'aoq_items_id'=>$off->aoq_items_id,
                    'pr_details_id'=>$off->pr_details_id,
                    'item_name'=>$this->super_model->select_column_where('aoq_items', 'item_description', 'aoq_items_id', $off->aoq_items_id),
                    'offer'=>$off->offer,
                    'price'=>$off->unit_price,
                    'balance'=>$off->balance,
                    'amount'=>$off->amount,
                    'uom'=>$off->uom,
                    'total'=>$total
                );
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

    public function save_po(){
        $po_id = $this->input->post('po_id');
        $count_item = $this->input->post('count_item');
        $a=1;
        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $revise = $this->super_model->select_column_where("po_head",'revised','po_id',$po_id);
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
                    'unit_price'=>$this->input->post('price'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                    'item_no'=>$a
                );

                if($revise==0){
                    $this->super_model->insert_into("po_items", $data);
                }else{
                    $this->super_model->update_where("po_items", $data, "aoq_offer_id", $this->input->post('aoq_offer_id'.$x));
                }

                $curr_balance = $this->super_model->select_column_where('aoq_offers', 'balance', 'aoq_offer_id', $this->input->post('aoq_offer_id'.$x));
                $new_balance = $curr_balance-$qty;

                $data_aoq = array(
                    'balance'=>$new_balance
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

        $dr = array(
            'po_id'=>$po_id,
            'dr_no'=>$dr_no
        );
        $this->super_model->insert_into("po_dr", $dr);

        $head = array(
        
            'approved_by'=>$this->input->post('approved'),
            'saved'=>1,
            'revised'=>0
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
        $data = array(
            'po_id'=>$this->input->post('po_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );
        if($this->super_model->insert_into("po_tc", $data)){
            redirect(base_url().'po/purchase_order/'.$po_id, 'refresh');
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


    public function purchase_order_saved(){
        $po_id = $this->uri->segment(3);
        $data['po_id'] = $po_id;
         foreach($this->super_model->select_row_where('po_head', 'po_id', $po_id) AS $h){
            $data['head'][] = array(
                'po_date'=>$h->po_date,
                'po_no'=>$h->po_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['saved']=$h->saved;
            $data['revised']=$h->revised;
            $data['po_no']=$h->po_no;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
        }

        $data['items'] = $this->super_model->select_row_where('po_items', 'po_id', $po_id);
      
        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $ppr){
            $data['allpr'][]= array(
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $ppr->pr_id),
                'enduse'=>$ppr->enduse,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
        }


        $this->load->view('template/header');        
        $this->load->view('po/purchase_order_saved',$data);
        $this->load->view('template/footer');
    }

    public function revise_po(){
        $po_id=$this->uri->segment(3);
        $data = array(
            'saved'=>0,
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
        foreach($this->super_model->select_row_where("po_pr", "po_id" , $po_id) AS $popr){
            foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1'") AS $off){
                for($x=1; $x<$count_item;$x++){
                    $qty = $this->input->post('qty'.$x);
                    $data_aoq =  array(
                        'balance'=>$qty,
                    );
                    $this->super_model->update_where("aoq_offers", $data_aoq, "aoq_offer_id", $this->input->post('aoq_offer_id'.$x));
                }
            }
        }

        $data=array(
            'revise_attachment'=>$filename,
        );
        if($this->super_model->update_where("po_head", $data, "po_id", $po_id)){
            redirect(base_url().'po/purchase_order/'.$po_id);
        }

    }

    public function get_name($column, $table, $where){
        $col = $this->super_model->select_column_custom_where($table, $column, $where);
        return $col;
    }

    public function view_history(){
        $this->load->view('template/header');      
        $this->load->view('po/view_history');
        $this->load->view('template/footer');
    }

    public function delivery_receipt(){
        $po_id = $this->uri->segment(3); 
        $data['head']= $this->super_model->select_row_where('po_head', 'po_id', $po_id);
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
                'requestor'=>$pr->requestor,
                'item_no'=>$item_no
            );
        }

        foreach($this->super_model->select_row_where('po_items', 'po_id', $po_id) AS $items){
           $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                'item'=>$this->super_model->select_column_where("aoq_items", "item_description", "aoq_items_id", $items->aoq_items_id),
                'offer'=>$items->offer,
                'quantity'=>$items->quantity,
                'uom'=>$items->uom,
            );
        }
        $this->load->view('template/header');        
        $this->load->view('po/delivery_receipt',$data);
        $this->load->view('template/footer');
    }

    public function rfd_prnt(){   
        $po_id = $this->uri->segment(3);   
        $data['rows_dr'] = $this->super_model->select_count("rfd","po_id",$po_id);
        $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
        $data['po_no']= $this->super_model->select_column_where("po_head", "po_no", "po_id", $po_id);
        $data['po_id']= $po_id;
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);

         foreach($this->super_model->select_row_where('po_items', 'po_id', $po_id) AS $items){
            $total = $items->unit_price*$items->quantity;
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'item'=>$this->super_model->select_column_where("aoq_items", "item_description", "aoq_items_id", $items->aoq_items_id),
                'offer'=>$items->offer,
                'quantity'=>$items->quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
                'uom'=>$items->uom,
            );
        }

          foreach($this->super_model->select_row_where('po_pr', 'po_id', $po_id) AS $pr){
             $itemno='';
            foreach($this->super_model->select_row_where('po_items', 'pr_id', $pr->pr_id) AS $it){
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
            
            $data['checked']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->checked_by);
            $data['endorsed']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->endorsed_by);
            $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->approved_by);
        }
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");

        $this->load->view('template/header');        
        $this->load->view('po/rfd_prnt',$data);
        $this->load->view('template/footer');
    }

    public function save_rfd(){
        $po_id= $this->input->post('po_id');

        $dr_data = array(
            'dr_date'=>$this->input->post('rfd_date')
        );
        $this->super_model->update_where("po_dr", $dr_data, "po_id", $po_id);
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
            'user_id'=>$_SESSION['user_id'],
            'saved'=>1
        );

         if($this->super_model->insert_into("rfd", $data)){
            redirect(base_url().'po/rfd_prnt/'.$po_id, 'refresh');
        }
    }

    public function reporder_prnt(){
        $this->load->view('template/header');        
        $this->load->view('po/reporder_prnt');
        $this->load->view('template/footer');
    }

    public function delivery_receipt_r(){
        $this->load->view('template/header');        
        $this->load->view('po/delivery_receipt_r');
        $this->load->view('template/footer');
    }


    public function rfd_prnt_r(){        
        $this->load->view('template/header');        
        $this->load->view('po/rfd_prnt_r');
        $this->load->view('template/footer');
    }


    public function done_po(){
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('template/navbar'); 
        foreach($this->super_model->select_custom_where("po_head", "saved='1' AND done_po='1' ORDER BY po_id DESC") AS $head){
             $rfd=$this->super_model->count_rows_where("po_dr","po_id",$head->po_id);
             $pr='';
            foreach($this->super_model->select_row_where("po_pr", "po_id", $head->po_id) AS $prd){
                $pr_no=$this->super_model->select_column_where('pr_head','pr_no','pr_id', $prd->pr_id);
                $pr .= "-".$pr_no."<br>";
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
            );
        }  
        $this->load->view('po/done_po',$data);
        $this->load->view('template/footer');
    }

}

?>