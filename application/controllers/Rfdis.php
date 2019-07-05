<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfdis extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->model('super_model');
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

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

    public function create_rfd(){
         $rfd_count = $this->super_model->count_rows("rfd");
            if($rfd_count==0){
            $rfd_id=1;
         } else {
            $maxrfdid=$this->super_model->get_max("rfd", "rfd_id");
            $rfd_id=$maxrfdid+1;
         }

         if($this->input->post('cash')==1){
            $cash=1;
            $check=0;
         } else {
            $cash=0;
            $check=1;
         }

         $create = date("Y-m-d H:i:s");
         $data =array(
            'rfd_id'=>$rfd_id,
            'rfd_date'=>$this->input->post('rfd_date'),
            'apv_no'=>$this->input->post('apv_no'),
            'company'=>$this->input->post('company'),
            'pay_to'=>$this->input->post('pay_to'),
            'check_name'=>$this->input->post('check_name'),
            'cash'=>$cash,
            'check'=>$check,
            'bank_no'=>$this->input->post('bank_no'),
            'check_date'=>$this->input->post('check_due'),
            'due_date'=>$this->input->post('due_date'),
            'prepared_by'=>$_SESSION['user_id'],
            'create_date'=>$create,
            'direct_purchase'=>1
         );

         if($this->super_model->insert_into("rfd", $data)){
            redirect(base_url().'rfdis/rfdis_prnt/'.$rfd_id);
         }

    }

    public function getname($column, $table, $col_id, $val_id){
        $name = $this->super_model->select_column_where($table, $column, $col_id, $val_id);
        return $name;
    }

	public function rfdis_list(){	
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");

        foreach($this->super_model->select_all_order_by('rfd', 'rfd_date', 'DESC') AS $rfd){
            if($rfd->direct_purchase == 1){
                $type='Direct Purchase';
            } else {
                $type='Purchase Order';
            }

            $cancelled = $this->super_model->select_column_where("po_head", "cancelled", "po_id",$rfd->po_id);
            if($cancelled ==0 ){
                $data['rfd'][] =  array(
                    'rfd_id'=>$rfd->rfd_id,
                    'rfd_date'=>$rfd->rfd_date,
                    'apv_no'=>$rfd->apv_no,
                    'po_id'=>$rfd->po_id,
                    'company'=>$rfd->company,
                    'pay_to'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $rfd->pay_to),
                    'net_amount'=>$rfd->net_amount,
                    'type'=>$type
                );
            }
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfdis/rfdis_list',$data);
        $this->load->view('template/footer');
    }

    public function rfdis_prnt(){	
        $rfd_id=$this->uri->segment(3);
        $data['rfd'] = $this->super_model->select_row_where("rfd", "rfd_id", $rfd_id);
        $supplier_id = $this->super_model->select_column_where('rfd', 'pay_to', 'rfd_id', $rfd_id);
        $data['saved'] = $this->super_model->select_column_where('rfd', 'saved', 'rfd_id', $rfd_id);
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
        $data['enduse']=$this->super_model->select_all_order_by("enduse", "enduse_name", "ASC");
        $data['purpose']=$this->super_model->select_all_order_by("purpose", "purpose_name", "ASC");
        foreach($this->super_model->select_row_where("rfd_items", "rfd_id", $rfd_id) AS $items){
            $unit_id = $this->super_model->select_column_where('item', 'unit_id', 'item_id', $items->item_id);
            $data['items'][]= array(
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
                'purpose'=>$this->super_model->select_column_where('purpose','purpose_name','purpose_id', $purpose->purpose_id),
                'enduse'=>$this->super_model->select_column_where('enduse','enduse_name','enduse_id', $purpose->enduse_id),
                'requestor'=>$this->super_model->select_column_where('employees','employee_name','employee_id', $purpose->requestor)
            );
        }
        $this->load->view('template/header');
        $this->load->view('rfdis/rfdis_prnt',$data);
        $this->load->view('template/footer');
    }

    public function save_rfdis(){
            $rfd_id = $this->input->post('rfd_id');
          //  echo $rfd_id;
            $create = date("Y-m-d H:i:s");
            $data = array(
                'gross_amount'=>$this->input->post('gross'),
                'less_amount'=>$this->input->post('less_amount'),
                'net_amount'=>$this->input->post('net'),
                'checked_by'=>$this->input->post('checked'),
                'endorsed_by'=>$this->input->post('endorsed'),
                'approved_by'=>$this->input->post('approved'),
                'saved'=>1
            );
            if($this->super_model->update_where("rfd", $data, "rfd_id", $rfd_id)){
                $head_rows = $this->super_model->count_rows("dr_head");
                        if($head_rows==0){
                            $dr_id=1;
                            $dr_no = 1000;
                        } else {
                            $maxid=$this->super_model->get_max("dr_head", "dr_id");
                            $maxno=$this->super_model->get_max("dr_head", "dr_no");
                            $dr_id=$maxid+1;
                            $dr_no = $maxno + 1;
                        }

                      $drhead = array(
                            'dr_id'=>$dr_id,
                            'rfd_id'=>$rfd_id,
                            'dr_no'=>$dr_no,
                            'direct_purchase'=>1,
                            'create_date'=>$create
                        );
                         $this->super_model->insert_into("dr_head", $drhead);

                    foreach($this->super_model->select_row_where("rfd_purpose", "rfd_id", $rfd_id) AS $purp){

                        $drdetails = array(
                            'dr_id'=>$dr_id,
                            'requestor'=>$purp->requestor,
                            'purpose_id'=>$purp->purpose_id,
                            'enduse_id'=>$purp->enduse_id,
                            'notes'=>$purp->notes,
                        );
                        $this->super_model->insert_into("dr_details", $drdetails);
                    }

                    foreach($this->super_model->select_row_where("rfd_items", "rfd_id", $rfd_id) AS $items){

                        $dritems = array(
                            'dr_id'=>$dr_id,
                            'rfd_items_id'=>$items->rfd_items_id
                        );

                        $this->super_model->insert_into("dr_items", $dritems);
                    }
            }

             redirect(base_url().'rfdis/rfdis_prnt/'.$rfd_id);

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

    public function add_rfd_purpose(){
        $rfd_id = $this->input->post('rfd_id');
        $data= array(
            'rfd_id'=>$rfd_id,
            'purpose_id'=>$this->input->post('purpose'),
            'requestor'=>$this->input->post('requested_by'),
            'enduse_id'=>$this->input->post('enduse'),
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
    public function rfdis_dr(){   
        $rfd_id=$this->uri->segment(3);
        $dr_id =$this->super_model->select_column_where('dr_head', 'dr_id', 'rfd_id', $rfd_id);
        $supplier_id =$this->super_model->select_column_where('rfd', 'pay_to', 'rfd_id', $rfd_id);
        $data['vendor']= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier_id);
        foreach($this->super_model->select_row_where("dr_head", "rfd_id", $rfd_id) AS $head){
            $data['dr_no']=$head->dr_no;
            $data['date']=$head->create_date;

        }

        foreach($this->super_model->select_row_where("dr_details", "dr_id", $dr_id) AS $details){
            $data['details'][] = array(
                'notes'=>$details->notes,
                'purpose'=>$this->super_model->select_column_where('purpose', 'purpose_name', 'purpose_id', $details->purpose_id),
                'requestor'=>$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $details->requestor),
                'enduse'=>$this->super_model->select_column_where('enduse', 'enduse_name', 'enduse_id', $details->enduse_id),
            );
        }


        foreach($this->super_model->select_row_where("dr_items", "dr_id", $dr_id) AS $items){
            $item_id = $this->super_model->select_column_where('rfd_items', 'item_id', 'rfd_items_id', $items->rfd_items_id);
            $quantity = $this->super_model->select_column_where('rfd_items', 'quantity', 'rfd_items_id', $items->rfd_items_id);
            $price = $this->super_model->select_column_where('rfd_items', 'unit_price', 'rfd_items_id', $items->rfd_items_id);
            $unit_id = $this->super_model->select_column_where('item', 'unit_id', 'item_id', $item_id);
            $data['items'][] = array(
                'item'=>$this->super_model->select_column_where('item', 'item_name', 'item_id', $item_id),
                'specs'=>$this->super_model->select_column_where('item', 'item_specs', 'item_id', $item_id),
                'quantity'=>$quantity,
                'price'=>$price,
                'unit'=>$this->super_model->select_column_where('unit', 'unit_name', 'unit_id', $unit_id),
            );
        }
        $this->load->view('template/header');
        $this->load->view('rfdis/rfdis_dr',$data);
        $this->load->view('template/footer');
    }

}

?>