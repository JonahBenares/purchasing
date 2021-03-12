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
            'user_id'=>$_SESSION['user_id'],
            'prepared_date'=>date("Y-m-d H:i:s"),
        );  

        if($this->super_model->insert_into("po_head", $data)){
             redirect(base_url().'pod/po_direct/'.$po_id);
        }
    }

    public function get_pn($pr_details_id){
        $name = $this->super_model->select_column_where("pr_details", "part_no", "pr_details_id", $pr_details_id);
        return $name;
    }

    public function po_direct(){
        $this->load->view('template/header');
        $po_id=$this->uri->segment(3);  
        $pr_id=$this->uri->segment(4);  
        $group_id=$this->uri->segment(5);  
        $data['po_id']=$po_id;  
        $data['pr_id']=$pr_id;
        $data['group_id']=$group_id;
        $data['currency'] = $this->currency_list();
        $supplier_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        $data['supplier_id']=$supplier_id;
        $data['currency1']='';

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
/*
         $data['items'] = $this->super_model->select_row_where('po_items', 'po_id', $po_id);
        $data['currency'] = $this->super_model->select_column_where('po_items', 'currency', 'po_id', $po_id);*/

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
                $total = $items->delivered_quantity*$items->unit_price;
                $data['currency1']=$items->currency;
                $data['items'][]= array(
                    'pr_details_id'=>$items->pr_details_id,
                    'po_items_id'=>$items->po_items_id,
                    'item'=>$items->offer,
                    'uom'=>$items->uom,
                    'quantity'=>$items->delivered_quantity,
                    'price'=>$items->unit_price,
                    'currency'=>$items->currency,
                    'total'=>$total,
                );
            }
        }

        foreach($this->super_model->select_row_where("po_pr", "po_id", $po_id) AS $purpose){
            $data['popurp'][]= array(
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $purpose->pr_id),
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

    public function po_direct_draft(){
        $this->load->view('template/header');
        $po_id=$this->uri->segment(3);  
        $pr_id=$this->uri->segment(4);  
        $group_id=$this->uri->segment(5);  
        $data['po_id']=$po_id;  
        $data['pr_id']=$pr_id;
        $data['group_id']=$group_id;
        $data['currency'] = $this->currency_list();
        $supplier_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        $data['supplier_id']=$supplier_id;
        $data['currency1']='';

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

        $draft=$this->super_model->select_column_where('po_head', 'draft', 'po_id', $po_id);
        if($draft==0){
            foreach($this->super_model->select_custom_where("pr_details", "pr_id = '$pr_id' AND grouping_id = '$group_id'") AS $items){
                //$total = $items->quantity*$items->unit_price;
                $data['items'][]= array(
                    'pr_details_id'=>$items->pr_details_id,
                    'po_items_id'=>$items->po_items_id,
                    'item'=>$items->item_description,
                    'uom'=>$items->uom,
                    'quantity'=>$items->quantity,
                    'price'=>'',
                    'total'=>'',
                );
            }
        }else {
            foreach($this->super_model->select_row_where("po_items", "po_id", $po_id) AS $items){
                $total = $items->delivered_quantity*$items->unit_price;
                $data['items'][]= array(
                    'pr_details_id'=>$items->pr_details_id,
                    'po_items_id'=>$items->po_items_id,
                    'item'=>$items->offer,
                    'uom'=>$items->uom,
                    'quantity'=>$items->delivered_quantity,
                    'price'=>$items->unit_price,
                    'currency'=>$items->currency,
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
        $this->load->view('pod/po_direct_draft',$data);
        $this->load->view('template/footer');
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
                redirect(base_url().'pod/po_direct/'.$po_id, 'refresh');
            } else if($saved!=0){
                redirect(base_url().'pod/po_direct_saved/'.$po_id, 'refresh');
            }else if($draft==1){
                redirect(base_url().'pod/po_direct_draft/'.$po_id, 'refresh');
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
                redirect(base_url().'pod/po_direct/'.$po_id, 'refresh');
            }else if($saved!=0){
                echo "<script>alert('Succesfully Deleted');</script>";
                redirect(base_url().'pod/po_direct_saved/'.$po_id, 'refresh');
            }else if($draft==1){
                echo "<script>alert('Succesfully Deleted');</script>";
                redirect(base_url().'pod/po_direct_draft/'.$po_id, 'refresh');
            }

        }
    }

    public function add_notes(){
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
        $draft = $this->super_model->select_column_where("po_head", "draft", "po_id", $po_id);
        $saved = $this->super_model->select_column_where("po_head", "saved", "po_id", $po_id);
        $data = array(
            'po_id'=>$this->input->post('po_id'),
            'notes'=>$this->input->post('notes'),
        );
        if($this->super_model->insert_into("po_tc", $data)){
            if($saved==0 && $draft==0){
                redirect(base_url().'pod/po_direct/'.$po_id.'/'.$pr_id.'/'.$group_id, 'refresh');
            } else if($saved!=0){
                redirect(base_url().'pod/po_direct_saved/'.$po_id.'/'.$pr_id.'/'.$group_id, 'refresh');
            }else if($draft==1){
                 redirect(base_url().'pod/po_direct_draft/'.$po_id.'/'.$pr_id.'/'.$group_id, 'refresh');
            }
        }
    }

    public function po_direct_saved(){
        $this->load->view('template/header');
        $po_id=$this->uri->segment(3);  
        $pr_id=$this->uri->segment(4);  
        $group_id=$this->uri->segment(5);  
        $data['po_id']=$po_id;  
        $data['pr_id']=$pr_id;
        $data['group_id']=$group_id;
        $data['currency'] = $this->currency_list();
        $supplier_id = $this->super_model->select_column_where('po_head', 'vendor_id', 'po_id', $po_id);
        $data['supplier_id']=$supplier_id;
        $data['currency1']='';

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

        $draft=$this->super_model->select_column_where('po_head', 'draft', 'po_id', $po_id);
        if($draft==0){
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
                $currency = $this->input->post('currency'.$x);
                $total = $items->delivered_quantity*$items->unit_price;
                $data['items'][]= array(
                    'pr_details_id'=>$items->pr_details_id,
                    'po_items_id'=>$items->po_items_id,
                    'item'=>$items->offer,
                    'uom'=>$items->uom,
                    'quantity'=>$items->delivered_quantity,
                    'price'=>$items->unit_price,
                    'currency'=>$items->currency,
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
        $this->load->view('pod/po_direct_saved',$data);
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
        $submit = $this->input->post('submit');
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
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
            'dr_no'=>$dr_no,
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
                    'currency'=>$this->input->post('currency'.$x),
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
                    'currency'=>$this->input->post('currency'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                    'item_no'=>$a
                );
                $this->super_model->insert_into("po_items", $data);
                $this->super_model->insert_into("po_dr_items", $data_dr);
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

            if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'pod/po_direct/'.$po_id);
            }
        } else if($submit=='Save as Draft'){
            $head = array(
                 'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('discount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked'),
                'approved_by'=>$this->input->post('approved'),
                'checked_by'=>$this->input->post('checked'),
                'saved'=>0,
                'draft'=>1
            );
            if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
                redirect(base_url().'pod/po_direct_draft/'.$po_id.'/'.$pr_id.'/'.$group_id);
            }
        }
    }

    public function save_po_draft(){
        $submit = $this->input->post('submit');
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
        $count_item = $this->input->post('count_item');
        $data['currency1']='';

        $a=1;
        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $po_items_id = $this->input->post('po_items_id'.$x);
            $uom=$this->input->post('uom'.$x);
            if($qty!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $offer = $this->input->post('item'.$x);
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
                redirect(base_url().'pod/po_direct/'.$po_id);
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
                redirect(base_url().'pod/po_direct_draft/'.$po_id.'/'.$pr_id.'/'.$group_id);
            }
        }
    }

    public function add_tc(){
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
        $data = array(
            'po_id'=>$this->input->post('po_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );


        if($this->super_model->insert_into("po_tc", $data)){
            redirect(base_url().'pod/po_direct/'.$po_id.'/'.$pr_id.'/'.$group_id, 'refresh');
        }
    }

    public function add_tc_draft(){
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
        $data = array(
            'po_id'=>$this->input->post('po_id'),
            'tc_desc'=>$this->input->post('tc_desc'),
        );

        
        if($this->super_model->insert_into("po_tc", $data)){
            redirect(base_url().'pod/po_direct_draft/'.$po_id.'/'.$pr_id.'/'.$group_id, 'refresh');
        }
    }

    public function update_condition(){
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("po_tc", $update, "po_tc_id",$tc_id)){
            redirect(base_url().'pod/po_direct/'.$po_id.'/'.$pr_id.'/'.$group_id);
        }
    }

    public function update_condition_draft(){
        $po_id = $this->input->post('po_id');
        $pr_id = $this->input->post('pr_id');
        $group_id = $this->input->post('group_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("po_tc", $update, "po_tc_id",$tc_id)){
            redirect(base_url().'pod/po_direct_draft/'.$po_id.'/'.$pr_id.'/'.$group_id);
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
        $data['revised']=$this->super_model->select_column_where('po_head', 'revised', 'po_id', $po_id);
        $data['revision_no']=$this->super_model->select_column_where('po_head', 'revision_no', 'po_id', $po_id);
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
            $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->approved_by);
            $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->noted_by);
            $data['received']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->received_by);
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
            'noted_by'=>$this->input->post('noted'),
            'received_by'=>$this->input->post('received'),
            'rfd_type'=>$this->input->post('po_type'),
            'notes'=>$this->input->post('notes'),
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


      public function purchase_order_rev(){

        $po_id=$this->uri->segment(3); 
        $data['currency1']='';
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
        $data['currency_list'] = $this->currency_list();
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
        $this->load->view('pod/purchase_order_rev', $data);
        $this->load->view('template/footer');
    }


    public function save_change_order(){
        $po_id = $this->input->post('po_id');
        $x=1;
     /*   $max_revision = $this->super_model->get_max_where("po_head", "revision_no","po_id = '$po_id'");
        $revision_no = $max_revision+1;*/
         $timestamp = date('Y-m-d');
         $data['currency1']='';
        $data_head = array(
            'po_id'=>$po_id,
             'po_date'=>$timestamp,
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
                $currency = $this->input->post('currency'.$x);
                
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
                        "currency"=>$currency,
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
                    "notes"=>$this->input->post('notes'),
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
                'currency'=>$dritems->currency,
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
                $this->super_model->update_where("po_head", $data_po, "po_id", $head->po_id);
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
                "currency"=>$poitems->currency,
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
                    "currency"=>$poitems->currency,
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
                    'currency'=>$poitems->currency,
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

    public function rfd_calapan(){ 
        $this->load->view('template/header');  
        $this->load->view('template/header'); 
         $po_id = $this->uri->segment(3);   
        $data['rows_dr'] = $this->super_model->select_count("rfd","po_id",$po_id);
        $data['saved']= $this->super_model->select_column_where("rfd", "saved", "po_id", $po_id);
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
            $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->approved_by);
            $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->noted_by);
            $data['received']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->received_by);
        }
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");       
        $this->load->view('pod/rfd_calapan',$data);
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
            redirect(base_url().'pod/rfd_calapan/'.$po_id, 'refresh');
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
            redirect(base_url().'pod/rfd_calapan/'.$po_id, 'refresh');
        }
    }

}

?>