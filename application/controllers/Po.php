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
        $this->load->view('template/header');        
        $this->load->view('template/navbar');
        $this->load->view('po/cancelled_po');
        $this->load->view('template/footer');
    }
    
    public function po_list(){
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');   
        $this->load->view('template/navbar'); 
        foreach($this->super_model->select_custom_where("po_head", "saved='1' ORDER BY po_id DESC") AS $head){
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
        $this->load->view('po/po_list',$data);
        $this->load->view('template/footer');
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
            'user_id'=>$_SESSION['user_id']
        );  

        if($this->super_model->insert_into("po_head", $data)){
             redirect(base_url().'po/purchase_order/'.$po_id);
        }
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
            // /echo "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1'";
            foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1'") AS $off){

                $total = $off->unit_price*$off->quantity;
                $data['items'][] =  array(
                    'aoq_id'=>$off->aoq_id,
                    'aoq_offer_id'=>$off->aoq_offer_id,
                    'aoq_items_id'=>$off->aoq_items_id,
                    'item_name'=>$this->super_model->select_column_where('aoq_items', 'item_description', 'aoq_items_id', $off->aoq_items_id),
                    'offer'=>$off->offer,
                    'price'=>$off->unit_price,
                    'quantity'=>$off->quantity,
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

        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            if($qty!=0){
                $data=array(
                    'pr_id'=>$this->super_model->select_column_where('aoq_head', 'pr_id', 'aoq_id', $this->input->post('aoq_id'.$x)),
                    'po_id'=>$po_id,
                    'aoq_offer_id'=>$this->input->post('aoq_offer_id'.$x),
                    'aoq_items_id'=>$this->input->post('aoq_items_id'.$x),
                    'offer'=>$this->input->post('offer'.$x),
                    'quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'unit_price'=>$this->input->post('price'.$x),
                    'amount'=>$this->input->post('tprice'.$x),
                );

                $this->super_model->insert_into("po_items", $data);
            }
        }

        $head = array(
            'approved_by'=>$this->input->post('approved'),
            'saved'=>1
        );

        if($this->super_model->update_where("po_head", $head, "po_id", $po_id)){
            redirect(base_url().'po/purchase_order_saved/'.$po_id);
        }
    }

    public function add_pr(){
        $po_id=$this->input->post('po_id');
        $data = array(
            'po_id'=>$po_id,
            'pr_id'=>$this->input->post('pr'),
            'aoq_id'=>$this->super_model->select_column_where('aoq_head', 'aoq_id', 'pr_id', $this->input->post('pr')),
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

    public function view_history(){
        $this->load->view('template/header');      
        $this->load->view('po/view_history');
        $this->load->view('template/footer');
    }

    public function delivery_receipt(){
        $po_id = $this->uri->segment(3); 
        $this->load->view('template/header');        
        $this->load->view('po/delivery_receipt');
        $this->load->view('template/footer');
    }

    public function rfd_prnt(){     
        $this->load->view('template/header');        
        $this->load->view('po/rfd_prnt');
        $this->load->view('template/footer');
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
        $this->load->view('template/header');        
        $this->load->view('template/navbar'); 
        $this->load->view('po/done_po');
        $this->load->view('template/footer');
    }

}

?>