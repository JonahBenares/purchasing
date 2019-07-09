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

    public function purchase_order_saved(){
        $this->load->view('template/header');        
        $this->load->view('po/purchase_order_saved');
        $this->load->view('template/footer');
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

            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
        }

        foreach($this->super_model->custom_query("SELECT ao.aoq_id, ah.pr_id FROM aoq_offers ao INNER JOIN aoq_head ah ON ao.aoq_id = ah.aoq_id WHERE ao.vendor_id = '$vendor_id' AND recommended = '1'") AS $off){
            $data['pr'][]=array(
                'pr_id'=>$off->pr_id,
                'pr_no'=>$this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $off->pr_id),
            );
        }

        foreach($this->super_model->select_row_where("po_pr", "po_id = '$po_id'") AS $popr){
            foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$popr->aoq_id' AND vendor_id='$vendor_id' AND recommended='1'") AS $offer){
                $data['items'][] =  array(
                    'item_name'=>$this->super_model->select_column_where('aoq_items', 'item_description', 'aoq_items_id', $offer->aoq_items_id),
                    'offer'=>$off->offer,
                    'price'=>$off->unit_price,
                    'amount'=>$off->amount
                );
            }
        }
        $this->load->view('template/header');        
        $this->load->view('po/purchase_order', $data);
        $this->load->view('template/footer');
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

    public function view_history(){
        $this->load->view('template/header');      
        $this->load->view('po/view_history');
        $this->load->view('template/footer');
    }

    public function delivery_receipt(){
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