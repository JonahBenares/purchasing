<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pod extends CI_Controller {

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

    public function getname($column, $table, $col_id, $val_id){
        $name = $this->super_model->select_column_where($table, $column, $col_id, $val_id);
        return $name;
    }

    public function additempod(){
        $this->load->view('template/header');  
        $po_id=$this->uri->segment(3);
        $supplier_id=$this->uri->segment(4);
        $data['po_id']=$po_id;
        $data['items'] = $this->super_model->select_row_where("vendor_details", "vendor_id", $supplier_id); 
        $this->load->view('pod/additempod',$data);
        $this->load->view('template/footer');
    }

    public function add_po_item(){
        $count_item = $this->input->post('count_item');
        $po_id = $this->input->post('po_id');
        for($x=1;$x<$count_item;$x++){
            $item_id = $this->input->post('item_id'.$x);
            $quantity = $this->input->post('quantity'.$x);
            $price = $this->input->post('price'.$x);
            
            if(!empty($quantity) || $quantity!=0){
                $data = array(
                    'po_id'=>$po_id,
                    'item_id'=>$item_id,
                    'quantity'=>$quantity,
                    'unit_price'=>$price 
                );
                $this->super_model->insert_into("po_items", $data);
            }
        } ?>
        <script>
            window.onunload = refreshParent;
            function refreshParent() {
                window.opener.location.reload();
            }
            window.close();
            
        </script>
        <?php
    }
    
    public function pod_list(){
        $data['header']=array();
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');   
        $this->load->view('template/navbar');
        foreach($this->super_model->select_custom_where("po_head", "saved='1' AND done_po = '0' AND po_type = '1' AND cancelled = '0' ORDER BY po_id DESC") AS $head){
            /*$rfd=$this->super_model->count_rows_where("po_dr","po_id",$head->po_id);*/
            $data['header'][]=array(
                'po_id'=>$head->po_id,
                'po_date'=>$head->po_date,
                'po_no'=>$head->po_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                //'rfd'=>$rfd,
            );
        }        
        $this->load->view('pod/pod_list',$data);
        $this->load->view('template/footer');
    }

    public function update_done(){
        $poid=$this->uri->segment(3);
        $data = array(
            'done_po'=>1
        );
        if($this->super_model->update_where("po_head", $data, "po_id", $poid)){
            redirect(base_url().'pod/pod_list/', 'refresh');
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

    public function po_direct(){
        $this->load->view('template/header');
        $po_id=$this->uri->segment(3);  
        $pr_id=$this->uri->segment(4);  
        $group_id=$this->uri->segment(5);  
        $data['po_id']=$po_id;  
        $data['pr_id']=$pr_id;  
        $supplier_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        $data['supplier_id']=$supplier_id;

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
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
        }

        $saved=$this->super_model->select_column_where('po_head', 'saved', 'po_id', $po_id);
        if($saved==0){
            foreach($this->super_model->select_custom_where("pr_details", "pr_id = '$pr_id' AND grouping_id = '$group_id'") AS $items){
                //$total = $items->quantity*$items->unit_price;
                $data['items'][]= array(
                    'pr_details_id'=>$items->pr_details_id,
                    'item'=>$items->item_description,
                    'uom'=>$items->uom,
                    'quantity'=>$items->quantity,
                    //'total'=>$total,
                );
            }
        }else {
            foreach($this->super_model->select_row_where("po_items", "po_id", $po_id) AS $items){
                $total = $items->quantity*$items->unit_price;
                $data['items'][]= array(
                    'pr_details_id'=>$items->pr_details_id,
                    'po_items_id'=>$items->po_items_id,
                    'item'=>$items->offer,
                    'uom'=>$items->uom,
                    'quantity'=>$items->quantity,
                    'price'=>$items->unit_price,
                    'total'=>$total,
                );
            }
        }

        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $purpose){
            $data['popurp'][]= array(
                'po_pr_id'=>$purpose->po_pr_id,
                'notes'=>$purpose->notes,
                'purpose'=>$purpose->purpose,
                'enduse'=>$purpose->enduse,
                'requestor'=>$purpose->requestor
                //'requestor'=>$this->super_model->select_column_where('employees','employee_name','employee_id', $purpose->requestor)
            );
        }
        $data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('pod/po_direct',$data);
        $this->load->view('template/footer');
    }

    public function add_po_purpose(){
        $po_id = $this->input->post('po_id');
        $data= array(
            'po_id'=>$po_id,
            'purpose'=>$this->input->post('purpose'),
            'requestor'=>$this->input->post('requested_by'),
            'enduse'=>$this->input->post('enduse'),
            'notes'=>$this->input->post('notes')
        );
        if($this->super_model->insert_into("po_pr", $data)){
            redirect(base_url().'pod/po_direct/'.$po_id, 'refresh');
        }
    }

    public function save_po(){
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $count_item = $this->input->post('count_item');
        $a=1;

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
            'dr_type'=>1,
            'po_id'=>$po_id,
            'dr_no'=>$dr_no
        );
        $this->super_model->insert_into("po_dr", $dr);

        for($x=1; $x<$count_item;$x++){

                $rows_items = $this->super_model->count_rows("po_items");
                if($rows_items==0){
                    $po_items_id = 1;
                } else {
                    $maxid = $this->super_model->get_max("po_items", "po_items_id");
                    $po_items_id = $maxid+1;
                }

            $qty=$this->input->post('quantity'.$x);
            $item=$this->input->post('item'.$x);
            
            if($qty!=0){
                $data=array(
                    'po_items_id'=>$po_items_id,
                    'pr_details_id'=>$this->input->post('pr_details_id'.$x),
                    'delivered_quantity'=>$qty,
                    'po_id'=>$po_id,
                    'pr_id'=>$pr_id,
                    'offer'=>$item,
                    'pr_details_id'=>$this->input->post('pr_details_id'.$x),
                    'unit_price'=>$this->input->post('price'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                    'uom'=>$this->input->post('uom'.$x),
                    'item_no'=>$a
                );

                $data_dr=array(
                    'po_items_id'=>$po_items_id,  
                    'pr_id'=>$pr_id,
                    'dr_id'=>$dr_id,
                    'po_id'=>$po_id,
                    'pr_details_id'=>$this->input->post('pr_details_id'.$x),
                    'offer'=>$item,
                    'delivered_quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'unit_price'=>$this->input->post('price'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                    'item_no'=>$a
                );
                $this->super_model->insert_into("po_items", $data);
                $this->super_model->insert_into("po_dr_items", $data_dr);
                //$this->super_model->update_where("po_items", $data, "po_items_id", $po_items_id);
            $a++;
            }
        }

   
        $head = array(
            'approved_by'=>$this->input->post('approved'),
            'saved'=>1
        );

        if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
            redirect(base_url().'pod/po_direct/'.$po_id);
        }
    }

    public function add_tc(){
        $po_id = $this->input->post('po_id');
        $data = array(
            'po_id'=>$this->input->post('po_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );
        if($this->super_model->insert_into("po_tc", $data)){
            redirect(base_url().'pod/po_direct/'.$po_id, 'refresh');
        }
    }

    public function delivery_receipt(){
        $this->load->view('template/header');  
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
            foreach($this->super_model->select_row_where('po_dr_items', 'po_id', $po_id) AS $items){
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
        $this->load->view('pod/delivery_receipt',$data);
        $this->load->view('template/footer');
    }

    public function rfd_prnt(){
        $this->load->view('template/header'); 
         $po_id = $this->uri->segment(3);   
        $data['rows_dr'] = $this->super_model->select_count("rfd","po_id",$po_id);
        $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
        $data['po_no']= $this->super_model->select_column_where("po_head", "po_no", "po_id", $po_id);
        $data['po_type']= $this->super_model->select_column_where("po_head", "po_type", "po_id", $po_id);
        $data['po_id']= $po_id;
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
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
            $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->approved_by);
        }
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('pod/rfd_prnt',$data);
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
            redirect(base_url().'pod/rfd_prnt/'.$po_id, 'refresh');
        }
    }

    public function done_pod(){
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
        $this->load->view('pod/done_pod',$data);
        $this->load->view('template/footer');
    }

    public function cancelled_pod(){
        $data['header']=array();
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('template/navbar');
        foreach($this->super_model->select_custom_where("po_head", "saved='1' AND done_po = '0' AND po_type = '1' AND cancelled = '1' ORDER BY po_id DESC") AS $head){
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
        $this->load->view('pod/cancelled_pod', $data);
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
            redirect(base_url().'pod/pod_list', 'refresh');
        }
    }
}

?>