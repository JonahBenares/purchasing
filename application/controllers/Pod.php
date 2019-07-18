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
        foreach($this->super_model->select_custom_where("po_head", "saved='1' AND done_po = '0' AND po_type = '1' ORDER BY po_id DESC") AS $head){
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
        $data['po_id']=$po_id;  
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

        foreach($this->super_model->select_row_where("po_items", "po_id", $po_id) AS $items){
            $unit_id = $this->super_model->select_column_where('item', 'unit_id', 'item_id', $items->item_id);
            $total = $items->quantity*$items->unit_price;
            $data['items'][]= array(
                'po_items_id'=>$items->po_items_id,
                'item'=>$this->super_model->select_column_where('item', 'item_name', 'item_id', $items->item_id),
                'specs'=>$this->super_model->select_column_where('item', 'item_specs', 'item_id', $items->item_id),
                'uom'=>$this->super_model->select_column_where('unit', 'unit_name', 'unit_id', $unit_id),
                'quantity'=>$items->quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
            );
        }
        $data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('pod/po_direct',$data);
        $this->load->view('template/footer');
    }

    public function save_po(){
        $po_id = $this->input->post('po_id');
        $count_item = $this->input->post('count_item');
        $a=1;
        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $po_items_id = $this->input->post('po_items_id'.$x);
            if($qty!=0){
                $data=array(
                    'quantity'=>$qty,
                    'unit_price'=>$this->input->post('price'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                    'item_no'=>$a
                );
                $this->super_model->update_where("po_items", $data, "po_items_id", $po_items_id);
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
        $data['head']= $this->super_model->select_row_where('po_head', 'po_id', $po_id);
        $data['dr_no']= $this->super_model->select_column_where("po_dr", "dr_no", "po_id", $po_id);
        $user_id= $this->super_model->select_column_where("po_head", "user_id", "po_id", $po_id);
        $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $user_id);
        $itemno='';
        foreach($this->super_model->select_custom_where("po_items", "po_id='$po_id'") AS $it){
            $vendor_id= $this->super_model->select_column_where("po_head", "vendor_id", "po_id", $po_id);
            $data['items'][]=array(
                'item_no'=>$it->item_no,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                'item'=>$this->super_model->select_column_where("item", "item_name", "item_id", $it->item_id),
                'quantity'=>$it->quantity,
                'uom'=>$it->uom,
            );
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
        $data['po_id']= $po_id;
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);

         foreach($this->super_model->select_row_where('po_items', 'po_id', $po_id) AS $items){
            $total = $items->unit_price*$items->quantity;
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'item'=>$this->super_model->select_column_where("item", "item_name", "item_id", $items->item_id),
                'quantity'=>$items->quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
                'uom'=>$items->uom,
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
        $this->load->view('template/header');        
        $this->load->view('template/navbar');
        $this->load->view('pod/cancelled_pod');
        $this->load->view('template/footer');
    }
}

?>