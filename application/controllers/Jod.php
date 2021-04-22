<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jod extends CI_Controller {

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

    public function currency_list(){
        $currency = array(
            'AUD',
            'BDT',
            'CAD',
            'EUR',
            'HKD',
            'IDR',
            'INR',
            'IQD',
            'JPY',
            'KPW',
            'LBP',
            'MXN',
            'MMK',
            'NZD',
            'OMR',
            'PHP',
            'PKR',
            'QAR',
            'THB',
            'USD',
            'GBP',
        );

        return $currency;
    }

    public function getname($column, $table, $col_id, $val_id){
        $name = $this->super_model->select_column_where($table, $column, $col_id, $val_id);
        return $name;
    }

    public function additemjod(){
        $this->load->view('template/header');  
        $joi_id=$this->uri->segment(3);
        $supplier_id=$this->uri->segment(4);
        $data['joi_id']=$joi_id;
        $data['items'] = $this->super_model->select_row_where("vendor_details", "vendor_id", $supplier_id); 
        $this->load->view('jod/additemjod',$data);
        $this->load->view('template/footer');
    }

    public function add_jo_item(){
        $count_item = $this->input->post('count_item');
        $joi_id = $this->input->post('joi_id');
        for($x=1;$x<$count_item;$x++){
            $item_id = $this->input->post('item_id'.$x);
            $quantity = $this->input->post('quantity'.$x);
            $price = $this->input->post('price'.$x);
            
            if(!empty($quantity) || $quantity!=0){
                $data = array(
                    'joi_id'=>$joi_id,
                    'item_id'=>$item_id,
                    'quantity'=>$quantity,
                    'unit_price'=>$price 
                );
                $this->super_model->insert_into("joi_items", $data);
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
    
    public function jod_list(){
        $data['header']=array();
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');   
        $this->load->view('template/navbar');
        foreach($this->super_model->select_custom_where("joi_head", "saved='1' AND done_po = '0' AND joi_type = '1' AND cancelled = '0' ORDER BY joi_id DESC") AS $head){
            /*$rfd=$this->super_model->count_rows_where("po_dr","po_id",$head->po_id);*/
            $data['header'][]=array(
                'joi_id'=>$head->joi_id,
                'joi_date'=>$head->joi_date,
                'joi_no'=>$head->joi_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                //'rfd'=>$rfd,
            );
        }        
        $this->load->view('jod/jod_list',$data);
        $this->load->view('template/footer');
    }

    public function update_done(){
        $joiid=$this->uri->segment(3);
        $data = array(
            'done_joi'=>1
        );
        if($this->super_model->update_where("joi_head", $data, "joi_id", $joiid)){
            redirect(base_url().'jod/jod_list/', 'refresh');
        }
    }

    public function create_jo(){
        $rows_head = $this->super_model->count_rows("joi_head");
        if($rows_head==0){
            $joi_id=1;
        } else {
            $max = $this->super_model->get_max("joi_head", "joi_id");
            $joi_id = $max+1;
        }
        $data= array(
            'joi_id'=>$joi_id,
            'joi_date'=>$this->input->post('joi_date'),
            'joi_no'=>$this->input->post('joi_no'),
            'vendor_id'=>$this->input->post('vendor'),
            'notes'=>$this->input->post('notes'),
            'joi_type'=>1,
            'user_id'=>$_SESSION['user_id'],
            'prepared_date'=>date("Y-m-d H:i:s"),
        );  

        if($this->super_model->insert_into("joi_head", $data)){
             redirect(base_url().'jod/jo_direct/'.$joi_id);
        }
    }

    public function get_pn($jor_items_id){
        $name = $this->super_model->select_column_where("jor_items", "part_no", "jor_items_id", $jor_items_id);
        return $name;
    }

    public function jo_direct(){
        $this->load->view('template/header');
        $joi_id=$this->uri->segment(3);  
        $jor_id=$this->uri->segment(4);  
        $group_id=$this->uri->segment(5);  
        $data['joi_id']=$joi_id;  
        $data['jor_id']=$jor_id;
        $data['group_id']=$group_id;
        $data['currency'] = $this->currency_list();
        $supplier_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        $data['supplier_id']=$supplier_id;
        $data['currency1']='';

        foreach($this->super_model->select_row_where('joi_head', 'joi_id', $joi_id) AS $h){
            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['saved']=$h->saved;
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['cancelled']=$h->cancelled;
            $data['notes']=$h->notes;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }

        $saved=$this->super_model->select_column_where('joi_head', 'saved', 'joi_id', $joi_id);
        if($saved==0){
            foreach($this->super_model->select_custom_where("jor_items", "jor_id = '$jor_id' AND grouping_id = '$group_id'") AS $items){
                //$total = $items->quantity*$items->unit_price;
                $data['items'][]= array(
                    'jor_items_id'=>$items->jor_items_id,
                    'scope_of_work'=>$items->scope_of_work,
                    'uom'=>$items->uom,
                    'quantity'=>$items->quantity,
                    //'total'=>$total,
                );
            }
        }else {
            foreach($this->super_model->select_row_where("joi_items", "joi_id", $joi_id) AS $items){
                $total = $items->delivered_quantity*$items->unit_price;
                $data['currency1']=$items->currency;
                $data['items'][]= array(
                    'jor_items_id'=>$items->jor_items_id,
                    'joi_items_id'=>$items->joi_items_id,
                    'scope_of_work'=>$items->offer,
                    'uom'=>$items->uom,
                    'quantity'=>$items->delivered_quantity,
                    'price'=>$items->unit_price,
                    'currency'=>$items->currency,
                    'total'=>$total,
                );
            }
        }

        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $purpose){
            $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $purpose->jor_id);
                    if($jo_no!=''){
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "jo_no", "jor_id = '$purpose->jor_id' AND cancelled = '0'");
                    }else{
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "user_jo_no", "jor_id = '$purpose->jor_id' AND cancelled = '0'");
                    }
            $data['popurp'][]= array(
                'jor_no'=>$jor_no,
                'joi_jor_id'=>$purpose->joi_jor_id,
                'notes'=>$purpose->notes,
                'purpose'=>$purpose->purpose,
                'enduse'=>$purpose->enduse,
                'requestor'=>$purpose->requestor
                //'requestor'=>$this->super_model->select_column_where('employees','employee_name','employee_id', $purpose->requestor)
            );
        }
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('jod/jo_direct',$data);
        $this->load->view('template/footer');
    }

    public function jo_direct_draft(){
        $this->load->view('template/header');
        $joi_id=$this->uri->segment(3);  
        $jor_id=$this->uri->segment(4);  
        $group_id=$this->uri->segment(5);  
        $data['joi_id']=$joi_id;  
        $data['jor_id']=$jor_id;
        $data['group_id']=$group_id;
        $data['currency'] = $this->currency_list();
        $supplier_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        $data['supplier_id']=$supplier_id;
        $data['currency1']='';

        foreach($this->super_model->select_row_where('joi_head', 'joi_id', $joi_id) AS $h){
            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['cancelled']=$h->cancelled;
            $data['notes']=$h->notes;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved_id']=$h->approved_by;
            $data['recommended_id']=$h->recommended_by;
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['checked_id']=$h->checked_by;
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }
/*
         $data['items'] = $this->super_model->select_row_where('po_items', 'po_id', $po_id);
        $data['currency'] = $this->super_model->select_column_where('po_items', 'currency', 'po_id', $po_id);*/

        $draft=$this->super_model->select_column_where('joi_head', 'draft', 'joi_id', $joi_id);
        if($draft==0){
            foreach($this->super_model->select_custom_where("jor_items", "jor_id = '$jor_id' AND grouping_id = '$group_id'") AS $items){
                //$total = $items->quantity*$items->unit_price;
                $data['items'][]= array(
                    'jor_items_id'=>$items->jor_items_id,
                    'joi_items_id'=>$items->joi_items_id,
                    'scope_of_work'=>$items->scope_of_work,
                    'uom'=>$items->uom,
                    'quantity'=>$items->quantity,
                    'price'=>'',
                    'total'=>'',
                );
            }
        }else {
            foreach($this->super_model->select_row_where("joi_items", "joi_id", $joi_id) AS $items){
                $total = $items->delivered_quantity*$items->unit_price;
                $data['items'][]= array(
                    'jor_items_id'=>$items->jor_items_id,
                    'joi_items_id'=>$items->joi_items_id,
                    'scope_of_work'=>$items->offer,
                    'uom'=>$items->uom,
                    'quantity'=>$items->delivered_quantity,
                    'price'=>$items->unit_price,
                    'currency'=>$items->currency,
                    'total'=>$total,
                );
            }
        }

        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $purpose){
            $data['popurp'][]= array(
                'joi_jor_id'=>$purpose->joi_jor_id,
                'notes'=>$purpose->notes,
                'purpose'=>$purpose->purpose,
                'enduse'=>$purpose->enduse,
                'requestor'=>$purpose->requestor
                //'requestor'=>$this->super_model->select_column_where('employees','employee_name','employee_id', $purpose->requestor)
            );
        }
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('jod/jo_direct_draft',$data);
        $this->load->view('template/footer');
    }

    public function update_notes(){
        $joi_id = $this->input->post('joi_id');
        $tc_id = $this->input->post('tc_id');
        $draft = $this->super_model->select_column_where("joi_head", "draft", "joi_id", $joi_id);
        $saved = $this->super_model->select_column_where("joi_head", "saved", "joi_id", $joi_id);
        $update = array(
            'notes'=>$this->input->post('notes'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$tc_id)){
            if($saved==0 && $draft==0){
                redirect(base_url().'jod/jo_direct/'.$joi_id, 'refresh');
            } else if($saved!=0){
                redirect(base_url().'jod/jo_direct_saved/'.$joi_id, 'refresh');
            }else if($draft==1){
                redirect(base_url().'jod/jo_direct_draft/'.$joi_id, 'refresh');
            }
            //redirect(base_url().'po/purchase_order/'.$po_id);
        }
    }

    public function delete_inst(){
        $id=$this->uri->segment(3);
        $joi_id=$this->uri->segment(4);
        $draft = $this->super_model->select_column_where("joi_head", "draft", "joi_id", $joi_id);
        $saved = $this->super_model->select_column_where("joi_head", "saved", "joi_id", $joi_id);
        if($this->super_model->delete_where('joi_tc', 'joi_tc_id', $id)){
            if($saved==0 && $draft==0){
                echo "<script>alert('Succesfully Deleted');</script>";
                redirect(base_url().'jod/jo_direct/'.$joi_id, 'refresh');
            }else if($saved!=0){
                echo "<script>alert('Succesfully Deleted');</script>";
                redirect(base_url().'jod/jo_direct_saved/'.$joi_id, 'refresh');
            }else if($draft==1){
                echo "<script>alert('Succesfully Deleted');</script>";
                redirect(base_url().'jod/jo_direct_draft/'.$joi_id, 'refresh');
            }

        }
    }

    public function add_notes(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $draft = $this->super_model->select_column_where("joi_head", "draft", "joi_id", $joi_id);
        $saved = $this->super_model->select_column_where("joi_head", "saved", "joi_id", $joi_id);
        $data = array(
            'joi_id'=>$this->input->post('joi_id'),
            'notes'=>$this->input->post('notes'),
        );
        if($this->super_model->insert_into("joi_tc", $data)){
            if($saved==0 && $draft==0){
                redirect(base_url().'jod/jo_direct/'.$joi_id.'/'.$jor_id.'/'.$group_id, 'refresh');
            } else if($saved!=0){
                redirect(base_url().'jod/jo_direct_saved/'.$joi_id.'/'.$jor_id.'/'.$group_id, 'refresh');
            }else if($draft==1){
                 redirect(base_url().'jod/jo_direct_draft/'.$joi_id.'/'.$jor_id.'/'.$group_id, 'refresh');
            }
        }
    }

    public function jo_direct_saved(){
        $this->load->view('template/header');
        $joi_id=$this->uri->segment(3);  
        $jor_id=$this->uri->segment(4);  
        $group_id=$this->uri->segment(5);  
        $data['joi_id']=$joi_id;  
        $data['jor_id']=$jor_id;
        $data['group_id']=$group_id;
        $data['currency'] = $this->currency_list();
        $supplier_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        $data['supplier_id']=$supplier_id;
        $data['currency1']='';

        foreach($this->super_model->select_row_where('joi_head', 'joi_id', $joi_id) AS $h){
            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['cancelled']=$h->cancelled;
            $data['notes']=$h->notes;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }
/*
         $data['items'] = $this->super_model->select_row_where('po_items', 'po_id', $po_id);
        $data['currency'] = $this->super_model->select_column_where('po_items', 'currency', 'po_id', $po_id);*/

        $draft=$this->super_model->select_column_where('joi_head', 'draft', 'joi_id', $joi_id);
        if($draft==0){
            foreach($this->super_model->select_custom_where("jor_items", "jor_id = '$jor_id' AND grouping_id = '$group_id'") AS $items){
                //$total = $items->quantity*$items->unit_price;
                $data['items'][]= array(
                    'jor_items_id'=>$items->jor_items_id,
                    'scope_of_work'=>$items->scope_of_work,
                    'uom'=>$items->uom,
                    'quantity'=>$items->quantity,
                    //'total'=>$total,
                );
            }
        }else {
            foreach($this->super_model->select_row_where("joi_items", "joi_id", $joi_id) AS $items){
                $currency = $this->input->post('currency'.$x);
                $total = $items->delivered_quantity*$items->unit_price;
                $data['items'][]= array(
                    'jor_items_id'=>$items->jor_items_id,
                    'joi_items_id'=>$items->joi_items_id,
                    'scope_of_work'=>$items->offer,
                    'uom'=>$items->uom,
                    'quantity'=>$items->delivered_quantity,
                    'price'=>$items->unit_price,
                    'currency'=>$items->currency,
                    'total'=>$total,
                );
            }
        }

        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $purpose){
            $data['popurp'][]= array(
                'joi_jor_id'=>$purpose->joi_jor_id,
                'notes'=>$purpose->notes,
                'purpose'=>$purpose->purpose,
                'enduse'=>$purpose->enduse,
                'requestor'=>$purpose->requestor
                //'requestor'=>$this->super_model->select_column_where('employees','employee_name','employee_id', $purpose->requestor)
            );
        }
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('jod/jo_direct_saved',$data);
        $this->load->view('template/footer');
    }

    public function add_jo_purpose(){
        $joi_id = $this->input->post('joi_id');
        $data= array(
            'joi_id'=>$joi_id,
            'purpose'=>$this->input->post('purpose'),
            'requestor'=>$this->input->post('requested_by'),
            'enduse'=>$this->input->post('enduse'),
            'notes'=>$this->input->post('notes')
        );
        if($this->super_model->insert_into("joi_jor", $data)){
            redirect(base_url().'jod/jo_direct/'.$joi_id, 'refresh');
        }
    }

    public function save_jo(){
        $submit = $this->input->post('submit');
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $count_item = $this->input->post('count_item');
        $a=1;

        $rows_dr = $this->super_model->count_rows("joi_dr");
        if($rows_dr==0){
            $dr_id = 1;
            $dr_no=1000;
        } else {
            $max = $this->super_model->get_max("joi_dr", "joi_dr_no");
            $maxid = $this->super_model->get_max("joi_dr", "joi_dr_id");
            $joi_dr_no = $max+1;
            $joi_dr_id = $maxid+1;
        }

        $dr = array(
            'joi_dr_id'=>$joi_dr_id,
            'joi_dr_type'=>1,
            'joi_id'=>$joi_id,
            'joi_dr_no'=>$joi_dr_no,
        );
        $this->super_model->insert_into("joi_dr", $dr);

        for($x=1; $x<$count_item;$x++){

                $rows_items = $this->super_model->count_rows("joi_items");
                if($rows_items==0){
                    $joi_items_id = 1;
                } else {
                    $maxid = $this->super_model->get_max("joi_items", "joi_items_id");
                    $joi_items_id = $maxid+1;
                }

            $qty=$this->input->post('quantity'.$x);
            $item=$this->input->post('scope_of_work'.$x);
            
            if($qty!=0){
                $data=array(
                    'joi_items_id'=>$joi_items_id,
                    'jor_items_id'=>$this->input->post('jor_items_id'.$x),
                    'delivered_quantity'=>$qty,
                    'joi_id'=>$joi_id,
                    'jor_id'=>$jor_id,
                    'offer'=>$item,
                    'jor_items_id'=>$this->input->post('jor_items_id'.$x),
                    'unit_price'=>$this->input->post('price'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                    'uom'=>$this->input->post('uom'.$x),
                    'item_no'=>$a
                );

                $data_dr=array(
                    'joi_items_id'=>$joi_items_id,  
                    'jor_id'=>$jor_id,
                    'joi_dr_id'=>$joi_dr_id,
                    'joi_id'=>$joi_id,
                    'jor_items_id'=>$this->input->post('jor_items_id'.$x),
                    'offer'=>$item,
                    'delivered_quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'unit_price'=>$this->input->post('price'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                    'item_no'=>$a
                );
                $this->super_model->insert_into("joi_items", $data);
                $this->super_model->insert_into("joi_dr_items", $data_dr);
                //$this->super_model->update_where("po_items", $data, "po_items_id", $po_items_id);
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
                'checked_by'=>$this->input->post('checked'),
                'saved'=>1
            );

            if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'jod/jo_direct/'.$joi_id);
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
                'checked_by'=>$this->input->post('checked'),
                'saved'=>0,
                'draft'=>1
            );
            if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'jod/jo_direct_draft/'.$joi_id.'/'.$jor_id.'/'.$group_id);
            }
        }
    }

    public function save_jo_draft(){
        $submit = $this->input->post('submit');
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $count_item = $this->input->post('count_item');
        $data['currency1']='';

        $a=1;
        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $joi_items_id = $this->input->post('joi_items_id'.$x);
            $uom=$this->input->post('uom'.$x);
            if($qty!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $offer = $this->input->post('scope_of_work'.$x);
                $currency = $this->input->post('currency'.$x);
                $data=array(
                    'delivered_quantity'=>$qty,
                    'offer'=>$offer,
                    'uom'=>$uom,
                    'currency'=>$currency,
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );
                $data_dr=array(
                    'delivered_quantity'=>$qty,
                    'unit_price'=>$price,
                    'uom'=>$uom,
                    'currency'=>$currency,
                    'amount'=>$amount,
                    'item_no'=>$a
                );

                    $this->super_model->update_where("joi_items", $data, "joi_items_id", $joi_items_id);
                    $this->super_model->update_where("joi_dr_items", $data_dr, "joi_items_id", $joi_items_id);
             $a++;
            } else {
                
                $this->super_model->delete_where("joi_items", "joi_items_id", $joi_items_id);
                $this->super_model->delete_where("joi_dr_items", "joi_items_id", $joi_items_id);
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
             if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'jod/jo_direct/'.$joi_id);
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
            if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'jod/jo_direct_draft/'.$joi_id.'/'.$jor_id.'/'.$group_id);
            }
        }
    }

    public function add_tc(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $data = array(
            'joi_id'=>$this->input->post('joi_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );


        if($this->super_model->insert_into("joi_tc", $data)){
            redirect(base_url().'jod/jo_direct/'.$joi_id.'/'.$jor_id.'/'.$group_id, 'refresh');
        }
    }

    public function add_tc_draft(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $data = array(
            'joi_id'=>$this->input->post('joi_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );

        
        if($this->super_model->insert_into("joi_tc", $data)){
            redirect(base_url().'jod/jo_direct_draft/'.$joi_id.'/'.$jor_id.'/'.$group_id, 'refresh');
        }
    }

    public function update_condition(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$tc_id)){
            redirect(base_url().'jod/jo_direct/'.$joi_id.'/'.$jor_id.'/'.$group_id);
        }
    }

    public function update_condition_draft(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $joi_tc_id = $this->input->post('joi_tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$joi_tc_id)){
            redirect(base_url().'jod/jo_direct_draft/'.$joi_id.'/'.$jor_id.'/'.$group_id);
        }
    }

    public function delivery_receipt(){
        $this->load->view('template/header');  
        $joi_id = $this->uri->segment(3); 
        $joi_dr_id = $this->uri->segment(4); 
        $data['head']= $this->super_model->select_row_where('joi_head', 'joi_id', $joi_id);
        $data['revision_no']= $this->super_model->select_column_where("joi_dr", "revision_no", "joi_id", $joi_id);
        
        $user_id= $this->super_model->select_column_where("joi_head", "user_id", "joi_id", $joi_id);
        $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $user_id);
        $data['cancelled']=$this->super_model->select_column_where("joi_head", "cancelled", "joi_id", $joi_id);
        foreach($this->super_model->select_row_where('joi_jor', 'joi_id', $joi_id) AS $jor){
             $itemno='';
            foreach($this->super_model->select_custom_where("joi_dr_items", "jor_id= '$jor->jor_id' AND joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id);
                    if($jo_no!=''){
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "jo_no", "jor_id = '$jor->jor_id' AND cancelled = '0'");
                    }else{
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "user_jo_no", "jor_id = '$jor->jor_id' AND cancelled = '0'");
                    }
            $data['jor'][]=array(
                'jor_no'=>$jor_no,
                'enduse'=>$jor->enduse,
                'purpose'=>$jor->purpose,
                'requestor'=>$jor->requestor,
                'item_no'=>$item_no
            );
        }

        if(empty($joi_dr_id)){
            $data['dr_no']= $this->super_model->select_column_where("joi_dr", "joi_dr_no", "joi_id", $joi_id);
            foreach($this->super_model->select_row_where('joi_dr_items', 'joi_id', $joi_id) AS $items){
               $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
                $data['items'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("jor_aoq_items", "scope_of_work", "jor_aoq_items_id", $items->joi_aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'uom'=>$items->uom,
                );
            }
        } else {
            $data['dr_no']= $this->super_model->select_column_custom_where("joi_dr", "dr_no", "joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'");
            foreach($this->super_model->select_custom_where('joi_dr_items', "joi_id= '$joi_id' AND joi_dr_id = '$joi_dr_id'") AS $items){
               $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
                $data['items'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("jor_aoq_items", "scope_of_work", "jor_aoq_items_id", $items->joi_aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'uom'=>$items->uom,
                );
            }
        }
        $this->load->view('jod/delivery_receipt',$data);
        $this->load->view('template/footer');
    }

    public function rfd_prnt(){
        $this->load->view('template/header'); 
        $joi_id = $this->uri->segment(3);   
        $data['revised']=$this->super_model->select_column_where('joi_head', 'revised', 'joi_id', $joi_id);
        $data['revision_no']=$this->super_model->select_column_where('joi_head', 'revision_no', 'joi_id', $joi_id);
        $data['rows_dr'] = $this->super_model->select_count("joi_rfd","joi_id",$joi_id);
        $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
        $data['joi_no']= $this->super_model->select_column_where("joi_head", "joi_no", "joi_id", $joi_id);
        $data['joi_type']= $this->super_model->select_column_where("joi_head", "joi_type", "joi_id", $joi_id);
        $data['shipping']= $this->super_model->select_column_where("joi_head", "shipping", "joi_id", $joi_id);
        $data['discount']= $this->super_model->select_column_where("joi_head", "discount", "joi_id", $joi_id);
        $data['packing']= $this->super_model->select_column_where("joi_head", "packing_fee", "joi_id", $joi_id);
        $data['vatt']= $this->super_model->select_column_where("joi_head", "vat", "joi_id", $joi_id);
        $data['vat_percent']= $this->super_model->select_column_where("joi_head", "vat_percent", "joi_id", $joi_id);
        $data['joi_id']= $joi_id;
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['tin']= $this->super_model->select_column_where("vendor_head", "tin", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);
        $data['dr_no']= $this->super_model->select_column_where("joi_dr", "joi_dr_no", "joi_id", $joi_id);
        $data['cancelled']=$this->super_model->select_column_where("joi_head", "cancelled", "joi_id", $joi_id);
        foreach($this->super_model->select_row_where('joi_items', 'joi_id', $joi_id) AS $items){
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

          foreach($this->super_model->select_row_where('joi_jor', 'joi_id', $joi_id) AS $jor){
             $itemno='';
            foreach($this->super_model->select_custom_where('joi_items', "jor_id='$jor->jor_id' AND joi_id = '$joi_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $data['jor'][]=array(
                'jor_id'=>$jor->jor_id,
                'jo_no'=>$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id),
                'enduse'=>$jor->enduse,
                'purpose'=>$jor->purpose,
                'requestor'=>$jor->requestor,
                'item_no'=>$item_no
            );
        }

        foreach($this->super_model->select_row_where('joi_rfd', 'joi_id', $joi_id) AS $r){
            
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
            $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->noted_by);
            $data['received']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->received_by);
        }
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('jod/rfd_prnt',$data);
        $this->load->view('template/footer');
    }

    public function save_rfd(){
        $joi_id= $this->input->post('joi_id');

        $dr_data = array(
            'joi_dr_date'=>$this->input->post('joi_rfd_date')
        );
        $this->super_model->update_where("joi_dr", $dr_data, "joi_id", $joi_id);
        $data = array(
            'joi_id'=>$joi_id,
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
            'rfd_type'=>$this->input->post('joi_type'),
            'notes'=>$this->input->post('notes'),
            'user_id'=>$_SESSION['user_id'],
            'saved'=>1
        );
         if($this->super_model->insert_into("joi_rfd", $data)){
            redirect(base_url().'jod/rfd_prnt/'.$joi_id, 'refresh');
        }
    }

    public function done_jod(){
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('template/navbar'); 
        foreach($this->super_model->select_custom_where("joi_head", "saved='1' AND done_joi='1' ORDER BY joi_id DESC") AS $head){
             $jorfd=$this->super_model->count_rows_where("joi_dr","joi_id",$head->joi_id);
             $jor='';
            foreach($this->super_model->select_row_where("joi_jor", "joi_id", $head->joi_id) AS $jord){
                $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $items->jor_id);
                    if($jo_no!=''){
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "jo_no", "jor_id = '$purpose->jor_id' AND cancelled = '0'");
                    }else{
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "user_jo_no", "jor_id = '$purpose->jor_id' AND cancelled = '0'");
                    }
                $jor .= "-".$jor_no."<br>";
            }
            $data['header'][]=array(
                'joi_id'=>$head->joi_id,
                'joi_date'=>$head->joi_date,
                'joi_no'=>$head->joi_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                'jor'=>$jor,
                'jorfd'=>$jorfd,
            );
        }  
        $this->load->view('jod/done_jod',$data);
        $this->load->view('template/footer');
    }

    public function cancelled_jod(){
        $data['header']=array();
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('template/navbar');
        foreach($this->super_model->select_custom_where("joi_head", "saved='1' AND done_joi = '0' AND joi_type = '1' AND cancelled = '1' ORDER BY joi_id DESC") AS $head){
            $data['header'][]=array(
                'joi_id'=>$head->joi_id,
                'joi_date'=>$head->joi_date,
                'joi_no'=>$head->joi_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                'cancelled'=>$head->cancelled,
                'cancel_reason'=>$head->cancel_reason,
                'cancelled_date'=>$head->cancelled_date,
            );
        }
        $this->load->view('jod/cancelled_jod', $data);
        $this->load->view('template/footer');
    }

    public function cancel_jo(){
        $joi_id=$this->input->post('joi_id');
        $reason=$this->input->post('reason');
        $create = date('Y-m-d H:i:s');
        $data = array(
            'cancelled'=>1,
            'cancelled_by'=>$_SESSION['user_id'],
            'cancel_reason'=>$reason,
            'cancelled_date'=>$create
        );

        if($this->super_model->update_where("joi_head", $data, "joi_id", $joi_id)){
            redirect(base_url().'jod/jod_list', 'refresh');
        }
    }


      public function job_order_rev(){

        $joi_id=$this->uri->segment(3); 
        $data['currency1']='';
        foreach($this->super_model->select_row_where("joi_head", "joi_id",$joi_id) AS $head){
            $data['approved_by']=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);
            $data['prepared_by']=$this->super_model->select_column_where('users','fullname','user_id', $head->user_id);
            $data['revised']=$head->revised;
        }
        
        $data['joi_id'] = $joi_id;
        $vendor_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        foreach($this->super_model->select_row_where('joi_head', 'joi_id', $joi_id) AS $h){
            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['joi_type']=$h->joi_type;
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['joi_no']=$h->joi_no;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }

        $data['items'] = $this->super_model->select_row_where('joi_items', 'joi_id', $joi_id);
        $data['currency']= $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
        $data['currency_list'] = $this->currency_list();
        $data['items_temp'] = $this->super_model->select_row_where('joi_items_temp', 'joi_id', $joi_id);
        $data['currency_temp']= $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
      
        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $jor){
            $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id);
                    if($jo_no!=''){
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "jo_no", "jor_id = '$jor->jor_id' AND cancelled = '0'");
                    }else{
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "user_jo_no", "jor_id = '$jor->jor_id' AND cancelled = '0'");
                    }
            $data['alljor'][]= array(
                'jor_no'=>$jor_no,
                'enduse'=>$jor->enduse,
                'purpose'=>$jor->purpose,
                'requestor'=>$jor->requestor
            );
            $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$jor->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$jor->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$jor->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$jor->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$jor->jor_aoq_id' AND vendor_id='$vendor_id'");
        }
        //$data['tc'] = $this->super_model->select_row_where("po_tc", "po_id", $po_id);
        $data['tc_notes'] = $this->super_model->select_column_where("joi_tc_temp",'notes',"joi_id",$joi_id);
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['tc_temp'] = $this->super_model->select_row_where("joi_tc_temp", "joi_id", $joi_id);
        //$data['tc'] = $this->super_model->select_row_where("po_tc_temp", "po_id", $po_id);
        $data['shipping_temp'] = $this->super_model->select_column_where('joi_head_temp', 'shipping', 'joi_id', $joi_id);
        $data['discount_temp'] = $this->super_model->select_column_where('joi_head_temp', 'discount', 'joi_id', $joi_id);
        $data['packing_temp'] = $this->super_model->select_column_where('joi_head_temp', 'packing_fee', 'joi_id', $joi_id);
        $data['vat_temp'] = $this->super_model->select_column_where('joi_head_temp', 'vat', 'joi_id', $joi_id);
        $data['vat_percent_temp'] = $this->super_model->select_column_where('joi_head_temp', 'vat_percent', 'joi_id', $joi_id);

        $datarfd = array(
            'saved'=>0
        );
        $this->super_model-> update_where("joi_rfd", $datarfd, "joi_id", $joi_id);

        $this->load->view('template/header');        
        $this->load->view('jod/job_order_rev', $data);
        $this->load->view('template/footer');
    }


    public function save_change_order(){
        $joi_id = $this->input->post('joi_id');
        $x=1;
     /*   $max_revision = $this->super_model->get_max_where("po_head", "revision_no","po_id = '$po_id'");
        $revision_no = $max_revision+1;*/
         $timestamp = date('Y-m-d');
         $data['currency1']='';
        $data_head = array(
            'joi_id'=>$joi_id,
            'joi_date'=>$timestamp,
            'shipping'=>$this->input->post('shipping'),
            'packing_fee'=>$this->input->post('packing'),
            'vat'=>$this->input->post('vat'),
            'vat_percent'=>$this->input->post('vat_percent'),
            'discount'=>$this->input->post('discount')
        );
        $this->super_model->insert_into("joi_head_temp", $data_head);
          foreach($this->super_model->select_row_where("joi_items","joi_id",$joi_id) AS $joitems){
            if($this->input->post('quantity'.$x)!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $currency = $this->input->post('currency'.$x);
                
                    $data_items = array(
                        "joi_items_id"=>$joitems->joi_items_id,
                        "jor_id"=>$joitems->jor_id,
                        "joi_id"=>$joitems->joi_id,
                        "jor_aoq_offer_id"=>$joitems->jor_aoq_offer_id,
                        "jor_aoq_items_id"=>$joitems->jor_aoq_items_id,
                        "jor_items_id"=>$joitems->jor_items_id,
                        "offer"=>$this->input->post('offer'.$x),
                        "item_id"=>$joitems->item_id,
                        "delivered_quantity"=>$this->input->post('quantity'.$x),
                        "quantity"=>$joitems->delivered_quantity,
                        "unit_price"=>$price,
                        "uom"=>$this->input->post('uom'.$x),
                        "currency"=>$currency,
                        "amount"=>$amount,
                        "item_no"=>$joitems->item_no,
                       /* "revision_no"=>$revision_no*/
                    );
                    $this->super_model->insert_into("joi_items_temp", $data_items);
        
            }
                $x++;
        }

        $y=1;
        foreach($this->super_model->select_row_where("joi_tc","joi_id",$joi_id) AS $potc){
            $data_tci = array(
                "joi_tc_id"=>$potc->joi_tc_id,
                "joi_id"=>$joi_id,
                "tc_desc"=>$this->input->post('terms'.$y),
                //"notes"=>$this->input->post('notes'),
            );
            if($this->super_model->insert_into("joi_tc_temp", $data_tci)){
                $data_notes = array(
                    "notes"=>$this->input->post('notes'),
                );
                $this->super_model->update_where("joi_tc_temp", $data_notes, "joi_tc_id", $potc->joi_tc_id);
            }


            $y++;
        }

        $data_head = array(
            'revised'=>1
        );

        if($this->super_model->update_where("joi_head", $data_head, "joi_id", $joi_id)){
            redirect(base_url().'jod/job_order_rev/'.$joi_id);
        }
    }

    public function approve_revision(){
        $joi_id = $this->input->post('joi_id');


        $max_revision = $this->super_model->get_max_where("joi_head", "revision_no","joi_id = '$joi_id'");
        $revision_no = $max_revision+1;

        $rows_dr = $this->super_model->count_rows("joi_dr");
        if($rows_dr==0){
            $joi_dr_no=1000;
        } else {
            $max = $this->super_model->get_max("joi_dr", "joi_dr_no");
            $joi_dr_no = $max+1;
        }

        $rows_series = $this->super_model->count_rows("joi_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("joi_series", "series");
            $series = $max+1;
        }

        $data_series = array(
            'series'=>$series
        );
        $this->super_model->insert_into("joi_series", $data_series);

        foreach($this->super_model->select_row_where("joi_dr","joi_id",$joi_id) AS $drs){
            $data_dr=array(
                'joi_dr_id'=>$drs->joi_dr_id,
                'joi_id'=>$drs->joi_id,
                'joi_dr_no'=>$drs->joi_dr_no,
                'joi_dr_date'=>$drs->dr_date,
                'joi_dr_type'=>$drs->joi_dr_type,
                'saved'=>$drs->saved,
                'revision_no'=>$drs->revision_no,
            );
            if($this->super_model->insert_into("joi_dr_revised", $data_dr)){
                $dr = array(
                    'joi_dr_no'=>$joi_dr_no,
                    'received'=>0,
                    'date_received'=>NULL
                );
                $this->super_model->update_where("joi_dr", $dr, "joi_dr_id", $drs->joi_dr_id);
            }
        }

        foreach($this->super_model->select_row_where("joi_dr_items","joi_id",$joi_id) AS $dritems){
            $data_dritems=array(
                'joi_dr_items_id'=>$dritems->joi_dr_items_id,
                'joi_items_id'=>$dritems->joi_items_id,
                'joi_dr_id'=>$dritems->joi_dr_id,
                'jor_id'=>$dritems->jor_id,
                'joi_id'=>$dritems->joi_id,
                'jor_aoq_offer_id'=>$dritems->jor_aoq_offer_id,
                'jor_aoq_items_id'=>$dritems->jor_aoq_items_id,
                'jor_items_id'=>$dritems->jor_items_id,
                'offer'=>$dritems->offer,
                'item_id'=>$dritems->item_id,
                'delivered_quantity'=>$dritems->delivered_quantity,
                'quantity'=>$dritems->quantity,
                'unit_price'=>$dritems->unit_price,
                'currency'=>$dritems->currency,
                'uom'=>$dritems->uom,
                'amount'=>$dritems->amount,
                'item_no'=>$dritems->item_no,
                'revision_no'=>$dritems->revision_no
            );
            $this->super_model->insert_into("joi_dr_items_revised", $data_dritems);
        }
        
        $data_drs =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("joi_dr", $data_drs, "joi_id", $joi_id);

        $jor_id = $this->super_model->select_column_where("joi_items","jor_id","joi_id",$joi_id);
        $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor_id);
                    if($jo_no!=''){
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "jo_no", "jor_id = '$jor_id' AND cancelled = '0'");
                    }else{
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "user_jo_no", "jor_id = '$jor_id' AND cancelled = '0'");
                    }
        $joi_no = "P".$jo_no."-".$series;
        foreach($this->super_model->select_row_where("joi_head","joi_id",$joi_id) AS $head){
            $data_head = array(
                "joi_id"=>$head->joi_id,
                "joi_date"=>$head->joi_date,
                "joi_no"=>$head->joi_no,
                "joi_dr_no"=>$head->joi_dr_no,
                "vendor_id"=>$head->vendor_id,
                "notes"=>$head->notes,
                "joi_type"=>$head->joi_type,
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
            if($this->super_model->insert_into("joi_head_revised", $data_head)){
                foreach($this->super_model->select_row_where("joi_head_temp","joi_id",$joi_id) AS $headt){
                    $data_joi=array(
                        "joi_date"=>$headt->joi_date,
                        "shipping"=>$headt->shipping,
                        "packing_fee"=>$headt->packing_fee,
                        "vat"=>$headt->vat,
                        "vat_percent"=>$headt->vat_percent,
                        "discount"=>$headt->discount,
                       
                    );
                }
                $this->super_model->update_where("joi_head", $data_joi, "joi_id", $head->joi_id);
            }
        }

        foreach($this->super_model->select_row_where("joi_jor","joi_id",$joi_id) AS $joijor){
            $data_joijor = array(
                "joi_jor_id"=>$joijor->joi_jor_id,
                "joi_id"=>$joijor->joi_id,
                "jor_aoq_id"=>$joijor->jor_aoq_id,
                "enduse"=>$joijor->enduse,
                "purpose"=>$joijor->purpose,
                "requestor"=>$joijor->requestor,
                "notes"=>$joijor->notes,
                "revision_no"=>$joijor->revision_no,
            );
            $this->super_model->insert_into("joi_jor_revised", $data_joijor);
        }

        foreach($this->super_model->select_row_where("joi_items","joi_id",$joi_id) AS $joitems){
            $data_items = array(
                "joi_items_id"=>$joitems->joi_items_id,
                "jor_id"=>$joitems->jor_id,
                "joi_id"=>$joitems->joi_id,
                "jor_aoq_offer_id"=>$joitems->jor_aoq_offer_id,
                "jor_aoq_items_id"=>$joitems->jor_aoq_items_id,
                "jor_items"=>$joitems->jor_items,
                "offer"=>$joitems->offer,
                "item_id"=>$joitems->item_id,
                "delivered_quantity"=>$joitems->delivered_quantity,
                "unit_price"=>$joitems->unit_price,
                "currency"=>$joitems->currency,
                "uom"=>$joitems->uom,
                "amount"=>$joitems->amount,
                "item_no"=>$joitems->item_no,
                "revision_no"=>$joitems->revision_no,
            );
            $this->super_model->insert_into("joi_items_revised", $data_items);
        }



        foreach($this->super_model->select_row_where("joi_tc","joi_id",$joi_id) AS $joitc){
            $data_potc = array(
                "joi_tc_id"=>$joitc->joi_tc_id,
                "joi_id"=>$joijor->joi_id,
                "tc_desc"=>$joitc->tc_desc,
                "notes"=>$joitc->notes,
                "revision_no"=>$joitc->revision_no,
            );
            $this->super_model->insert_into("joi_tc_revised", $data_potc);
        }

        $data_tcn =array(
            'revision_no'=>$revision_no
        );

        $this->super_model->update_where("joi_tc", $data_tcn, "joi_id", $joi_id);

        foreach($this->super_model->select_row_where("joi_tc_temp","joi_id",$joi_id) AS $joitcr){
            $data_rev = array(
                "joi_tc_id"=>$joitcr->joi_tc_id,
                "joi_id"=>$joijor->joi_id,
                "tc_desc"=>$joitcr->tc_desc,
                "notes"=>$joitcr->notes,
                "revision_no"=>$joitcr->revision_no,
            );
            $this->super_model->update_where("joi_tc", $data_rev, "joi_tc_id", $joitcr->joi_tc_id);
        }

       
         foreach($this->super_model->custom_query("SELECT joi_items_id FROM joi_items WHERE joi_items_id NOT IN (SELECT joi_items_id FROM joi_items_temp WHERE joi_id='$joi_id')  AND joi_id = '$joi_id'") AS $omit){
           
           $delete_item = $this->super_model->delete_custom_where("joi_items", "joi_items_id= 
                '$omit->joi_items_id'");

            $delete_dr = $this->super_model->delete_custom_where("joi_dr_items", "joi_items_id= 
                '$omit->joi_items_id'");
        }

        foreach($this->super_model->select_row_where("joi_items_temp","joi_id",$joi_id) AS $joitems){
            $oldqty = $this->super_model->select_column_where('joi_items', 'quantity', 'joi_items_id',  $joitems->joi_items_id);

          
                if($oldqty==0){
                    $nqty=0;
                } else {
                    $nqty = $oldqty-$joitems->quantity;
                }


                $data_items = array(
               
                    "jor_id"=>$joitems->jor_id,
                    "joi_id"=>$joitems->joi_id,
                    "aoq_offer_id"=>$joitems->aoq_offer_id,
                    "aoq_items_id"=>$joitems->aoq_items_id,
                    "jor_items_id"=>$joitems->jor_items_id,
                    "offer"=>$joitems->offer,
                    "item_id"=>$joitems->item_id,
                    "delivered_quantity"=>$joitems->delivered_quantity,
                    "quantity"=>$nqty,
                    "unit_price"=>$joitems->unit_price,
                    "currency"=>$joitems->currency,
                    "uom"=>$joitems->uom,
                    "amount"=>$joitems->amount,
                    "item_no"=>$joitems->item_no,
                    "revision_no"=>$revision_no
                );
                 $this->super_model->update_where("joi_items", $data_items, "joi_items_id", $joitems->joi_items_id);

                $data_dr_items = array(
                    'delivered_quantity'=>$joitems->delivered_quantity,
                    'quantity'=>0,
                    "uom"=>$joitems->uom,
                    'unit_price'=>$joitems->unit_price,
                    'currency'=>$joitems->currency,
                    'amount'=>$joitems->amount,
                    'offer'=>$joitems->offer
                );

                $this->super_model->update_where("joi_dr_items", $data_dr_items, "joi_items_id", $joitems->joi_items_id);
         

            $old_qty = $this->super_model->select_column_where('joi_items', 'delivered_quantity', 'jor_aoq_offer_id',  $joitems->jor_aoq_offer_id);
            if($old_qty!=$joitems->delivered_quantity){
             
                $difference = $old_qty - $joitems->quantity;

                $old_balance = $this->super_model->select_column_where('jor_aoq_offers', 'balance', 'jor_aoq_offer_id',  $joitems->jor_aoq_offer_id);

                $balance = $old_balance+$difference;

                $data_aoq = array(
                    'balance'=>$balance
                );
                $this->super_model->update_where("jor_aoq_offers", $data_aoq, "jor_aoq_offer_id", $joitems->jor_aoq_offer_id);
            } else {
              
                $balance = $this->super_model->select_column_where('jor_aoq_offers', 'balance', 'jor_aoq_offer_id',  $joitems->jor_oq_offer_id);
                $data_aoq = array(
                    'balance'=>$balance
                );
                $this->super_model->update_where("jor_aoq_offers", $data_aoq, "jor_aoq_offer_id", $joitems->jor_aoq_offer_id);
            }
        
           
            
        }
        $this->super_model->delete_where("joi_head_temp", "joi_id", $joi_id);
        $this->super_model->delete_where("joi_tc_temp", "joi_id", $joi_id);
        $this->super_model->delete_where("joi_items_temp", "joi_id", $joi_id);    
        $data_jor =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("joi_jor", $data_jor, "joi_id", $joi_id);

        $data =array(
            'served'=>0,
            'date_served'=>NULL,
            'served_by'=>0,
            'approve_rev_by'=>$this->input->post('approve_rev'),
            'approve_rev_date'=>$this->input->post('approve_date'),
            'revised'=>0,
            'revision_no'=>$revision_no
        );
        if($this->super_model->update_where("joi_head", $data, "joi_id", $joi_id)){
            redirect(base_url().'jor/jor_list/', 'refresh');
        }
    }

}

?>