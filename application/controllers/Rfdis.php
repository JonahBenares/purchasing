<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfdis extends CI_Controller {

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

	public function rfdis_list(){	
        $data['head']=$this->super_model->select_all_order_by("rfd","rfd_date","DESC");
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        $this->load->view('rfdis/rfdis_list',$data);
        $this->load->view('template/footer');
    }

    public function create_rfd(){
        $rfd_date = trim($this->input->post('rfd_date')," ");
        $company = trim($this->input->post('company')," ");
        $pay_to = trim($this->input->post('pay_to')," ");
        $apv_no = trim($this->input->post('apv_no')," ");
        $cash = trim($this->input->post('cash')," ");
        $check_name = trim($this->input->post('check_name')," ");
        $bank_no = trim($this->input->post('bank_no')," ");
        $due_date = trim($this->input->post('due_date')," ");
        $check_due = trim($this->input->post('check_due')," ");
        $user_id = $_SESSION['user_id'];

        $rfd_count = $this->super_model->count_rows("rfd");
        if($rfd_count==0){
            $rfd_id=1;
        } else {
            $maxrfdid=$this->super_model->get_max("rfd", "rfd_id");
            $rfd_id=$maxrfdid+1;
        }

        $data = array(
            'rfd_id'=>$rfd_id,
            'rfd_date'=>$rfd_date,
            'company'=>$company,
            'pay_to'=>$pay_to,
            'apv_no'=>$apv_no,
            'cash_check'=>$cash,
            'check_name'=>$check_name,
            'bank_no'=>$bank_no,
            'due_date'=>$due_date,
            'check_due'=>$check_due,
            'user_id'=>$user_id,
            'rfd_type'=>1
        );
        if($this->super_model->insert_into("rfd", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."rfdis/rfdis_prnt/$rfd_id'; </script>";
        }
    }

    public function getname($column, $table, $col_id, $val_id){
        $name = $this->super_model->select_column_where($table, $column, $col_id, $val_id);
        return $name;
    }

    public function rfdis_prnt(){
        $this->load->view('template/header');
        $rfd_id=$this->uri->segment(3);
        $data['rfd'] = $this->super_model->select_row_where("rfd", "rfd_id", $rfd_id);
        $data['saved'] = $this->super_model->select_column_where('rfd', 'saved', 'rfd_id', $rfd_id);
        $supplier_id = $this->super_model->select_column_where('rfd', 'pay_to', 'rfd_id', $rfd_id);
        $checked_id= $this->super_model->select_column_where('rfd', 'checked_by', 'rfd_id', $rfd_id);
        $endorse_id= $this->super_model->select_column_where('rfd', 'endorsed_by', 'rfd_id', $rfd_id);
        $approve_id= $this->super_model->select_column_where('rfd', 'approved_by', 'rfd_id', $rfd_id);
        $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $checked_id);
        $data['endorsed'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $endorse_id);
        $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $approve_id);
        $data['supplier_id']=$supplier_id;
        $data['rfd_id']=$rfd_id;
        $data['vat'] = $this->super_model->select_column_where('vendor_head', 'vat', 'vendor_id', $supplier_id);
        $data['ewt'] = $this->super_model->select_column_where('vendor_head', 'ewt', 'vendor_id', $supplier_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");

        foreach($this->super_model->select_row_where("rfd_items", "rfd_id", $rfd_id) AS $items){
            $unit_id = $this->super_model->select_column_where('item', 'unit_id', 'item_id', $items->item_id);
            $data['items'][]= array(
                'rfd_items_id'=>$items->rfd_items_id,
                'item'=>$this->super_model->select_column_where('item', 'item_name', 'item_id', $items->item_id),
                'specs'=>$this->super_model->select_column_where('item', 'item_specs', 'item_id', $items->item_id),
                'unit'=>$this->super_model->select_column_where('unit', 'unit_name', 'unit_id', $unit_id),
                'quantity'=>$items->quantity,
                'price'=>$items->unit_price
            );
        }

        foreach($this->super_model->select_row_where("rfd_purpose", "rfd_id", $rfd_id) AS $purpose){
            $data['rfdpurp'][]= array(
                'rfd_purpose_id'=>$purpose->rfd_purpose_id,
                'notes'=>$purpose->notes,
                'purpose'=>$purpose->purpose,
                'enduse'=>$purpose->enduse,
                'requestor'=>$this->super_model->select_column_where('employees','employee_name','employee_id', $purpose->requestor)
            );
        }
        $this->load->view('rfdis/rfdis_prnt',$data);
        $this->load->view('template/footer');
    }   

    public function additemrfd(){   
        $rfd_id=$this->uri->segment(3);
        $supplier_id=$this->uri->segment(4);
        $data['rfd_id']=$rfd_id;
        $data['items'] = $this->super_model->select_row_where("vendor_details", "vendor_id", $supplier_id);
        $this->load->view('template/header');
        $this->load->view('rfdis/additemrfd',$data);
        $this->load->view('template/footer');
    }

    public function add_rfd_item(){
        $count_item = $this->input->post('count_item');
        $rfd_id = $this->input->post('rfd_id');
        for($x=1;$x<$count_item;$x++){
            $item_id = $this->input->post('item_id'.$x);
            $quantity = $this->input->post('quantity'.$x);
            $price = $this->input->post('price'.$x);
            
            if(!empty($quantity) || $quantity!=0){
                $data = array(
                    'rfd_id'=>$rfd_id,
                    'item_id'=>$item_id,
                    'quantity'=>$quantity,
                    'unit_price'=>$price 
                );
                $this->super_model->insert_into("rfd_items", $data);
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

    public function delete_item(){
        $id=$this->uri->segment(3);
        $rfd_id=$this->uri->segment(4);
        if($this->super_model->delete_where("rfd_items", "rfd_items_id", $id)){
            redirect(base_url().'rfdis/rfdis_prnt/'.$rfd_id, 'refresh');

        }
    }

    public function add_rfd_purpose(){
        $rfd_id = $this->input->post('rfd_id');
        $data= array(
            'rfd_id'=>$rfd_id,
            'purpose'=>$this->input->post('purpose'),
            'requestor'=>$this->input->post('requested_by'),
            'enduse'=>$this->input->post('enduse'),
            'notes'=>$this->input->post('notes')
        );
        if($this->super_model->insert_into("rfd_purpose", $data)){
            redirect(base_url().'rfdis/rfdis_prnt/'.$rfd_id, 'refresh');
        }

    }

    public function delete_purpose(){
        $id=$this->uri->segment(3);
        $rfd_id=$this->uri->segment(4);
        if($this->super_model->delete_where("rfd_purpose", "rfd_purpose_id", $id)){
            redirect(base_url().'rfdis/rfdis_prnt/'.$rfd_id, 'refresh');

        }
    }

    public function save_rfdis(){
        $rfd_id=$this->input->post('rfd_id');
        $date = $this->super_model->select_column_where('rfd', 'rfd_date', 'rfd_id', $rfd_id);
        $data = array(
            'total_amount'=>$this->input->post('net'),
            'checked_by'=>$this->input->post('checked'),
            'endorsed_by'=>$this->input->post('endorsed'),
            'approved_by'=>$this->input->post('approved'),
            'saved'=>1
        );
        if($this->super_model->update_where("rfd", $data, "rfd_id", $rfd_id)){

            $head_rows = $this->super_model->count_rows("po_dr");
            if($head_rows==0){
                $dr_id=1;
                $dr_no = 1000;
            } else {
                $maxid=$this->super_model->get_max("po_dr", "dr_id");
                $maxno=$this->super_model->get_max("po_dr", "dr_no");
                $dr_id=$maxid+1;
                $dr_no = $maxno + 1;
            }

            $po_dr=array(
                'dr_id'=>$dr_id,
                'rfd_id'=>$rfd_id,
                'dr_no'=>$dr_no,
                'dr_date'=>$date,
                'dr_type'=>1
            );
            $this->super_model->insert_into("po_dr", $po_dr);
            redirect(base_url().'rfdis/rfdis_prnt/'.$rfd_id, 'refresh');
        }
    }

    public function rfdis_dr(){           
        $this->load->view('template/header');
        $rfd_id=$this->uri->segment(3);
        $dr_id =$this->super_model->select_column_where('po_dr', 'dr_id', 'rfd_id', $rfd_id);
        $supplier_id =$this->super_model->select_column_where('rfd', 'pay_to', 'rfd_id', $rfd_id);
        $data['vendor']= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier_id);
        foreach($this->super_model->select_row_where("po_dr", "rfd_id", $rfd_id) AS $head){
            $data['dr_no']=$head->dr_no;
            $data['date']=$this->super_model->select_column_where("rfd","rfd_date","rfd_id",$rfd_id);
        }

        foreach($this->super_model->select_row_where("rfd_purpose", "rfd_id", $rfd_id) AS $details){
            $data['details'][] = array(
                'notes'=>$details->notes,
                'purpose'=>$details->purpose,
                'requestor'=>$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $details->requestor),
                'enduse'=>$details->enduse,
            );
        }


        foreach($this->super_model->select_row_where("rfd_items", "rfd_id", $rfd_id) AS $items){
            $unit_id = $this->super_model->select_column_where('item', 'unit_id', 'item_id', $items->item_id);
            $data['items'][] = array(
                'item'=>$this->super_model->select_column_where('item', 'item_name', 'item_id', $items->item_id),
                'specs'=>$this->super_model->select_column_where('item', 'item_specs', 'item_id', $items->item_id),
                'quantity'=>$items->quantity,
                'price'=>$items->unit_price,
                'unit'=>$this->super_model->select_column_where('unit', 'unit_name', 'unit_id', $unit_id),
            );
        }

        $this->load->view('rfdis/rfdis_dr',$data);
        $this->load->view('template/footer');
    }
    public function rfdis_calapan(){ 
        $this->load->view('template/header');         
        $this->load->view('rfdis/rfdis_calapan');
        $this->load->view('template/footer');
    }

}

?>