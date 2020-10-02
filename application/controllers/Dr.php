<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dr extends CI_Controller {

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

    public function dr_list(){   
        //$data['head']= $this->super_model->select_all_order_by("po_dr", "dr_date", "DESC");
        $count = $this->super_model->count_rows("po_dr");
        if($count!=0){
            foreach($this->super_model->select_all("po_dr", "dr_date", "DESC") AS $d){
                foreach($this->super_model->select_custom_where("po_dr_items", "dr_id = '$d->dr_id' GROUP BY dr_id") AS $da){
                    $data['head'][]=array(
                        'po_id'=>$d->po_id,
                        'rfd_id'=>$d->rfd_id,
                        'dr_id'=>$d->dr_id,
                        'dr_no'=>$d->dr_no,
                        'dr_type'=>$d->dr_type,
                        'dr_date'=>$d->dr_date,
                    );
                }
            }
        }else{
            $data['head']=array();
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('dr/dr_list',$data);
        $this->load->view('template/footer');
    }

    public function add_dr(){
        $dr_count = $this->super_model->count_rows("po_dr");
        if($dr_count==0){
            $dr_id=1;
            $dr_no = 1000;
        } else {
            $maxid=$this->super_model->get_max("po_dr", "dr_id");
            $maxno=$this->super_model->get_max("po_dr", "dr_no");
            $dr_id=$maxid+1;
            $dr_no = $maxno + 1;
        }

        $data= array(
            'dr_id'=>$dr_id,
            'dr_no'=>$dr_no,
            'dr_type'=>2,
            'dr_date'=>$this->input->post('dr_date'),
        );
        if($this->super_model->insert_into("po_dr", $data)){
            redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');
        }
    }

    public function dr_prnt(){   
        $data['details'] = array();
        $this->load->view('template/header');
        $dr_id=$this->uri->segment(3);
        $data['dr_id']=$dr_id;
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $data['saved']=$this->super_model->select_column_where('po_dr','saved','dr_id',$dr_id);
        foreach($this->super_model->select_row_where("po_dr", "dr_id", $dr_id) AS $head){
            $data['dr_no']=$head->dr_no;
            $data['date']=$head->dr_date;
        }

        foreach($this->super_model->select_row_where("po_dr_details", "dr_id", $dr_id) AS $det){
            $data['details'][] = array(
                'dr_details_id'=>$det->dr_details_id,
                'notes'=>$det->notes,
                'purpose'=>$det->purpose,
                'requestor'=>$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $det->requestor),
                'enduse'=>$det->enduse,
            ); 
        }

        foreach($this->super_model->select_row_where("po_dr_items", "dr_id", $dr_id) AS $items){
            $unit_id = $this->super_model->select_column_where('item', 'unit_id', 'item_id', $items->item_id);
            $data['items'][] = array(
                'id'=>$items->dr_items_id,
                'item'=>$this->super_model->select_column_where('item', 'item_name', 'item_id', $items->item_id),
                'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $items->vendor_id),
                'specs'=>$this->super_model->select_column_where('item', 'item_specs', 'item_id', $items->item_id),
                'quantity'=>$items->quantity,
                'remarks'=>$items->remarks,
                'unit'=>$this->super_model->select_column_where('unit', 'unit_name', 'unit_id', $unit_id),
            );
        }

        $this->load->view('dr/dr_prnt', $data);
        $this->load->view('template/footer');
    }

    public function add_dr_purpose(){
        $dr_id = $this->input->post('dr_id');
        $data= array(
            'dr_id'=>$dr_id,
            'purpose'=>$this->input->post('purpose'),
            'requestor'=>$this->input->post('requested_by'),
            'enduse'=>$this->input->post('enduse'),
            'notes'=>$this->input->post('notes')
        );

        if($this->super_model->insert_into("po_dr_details", $data)){
            redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');
        }

    }

    public function delete_purpose(){
        $id=$this->uri->segment(3);
        $dr_id=$this->uri->segment(4);
        if($this->super_model->delete_where("po_dr_details", "dr_details_id", $id)){
            redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');

        }
    }

    public function additemdr(){   
        $dr_id=$this->uri->segment(3);
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['dr_id']=$dr_id;
        $this->load->view('template/header');
        $this->load->view('dr/additemdr',$data);
        $this->load->view('template/footer');
    }

    public function add_dr_item(){
        $dr_id=$this->input->post('dr_id');
        $data = array(
            'dr_id'=>$this->input->post('dr_id'),
            'item_id'=>$this->input->post('items'),
            'vendor_id'=>$this->input->post('supplier'),
            'quantity'=>$this->input->post('delivered'),
            'remarks'=>$this->input->post('remarks'),
        );

         if($this->super_model->insert_into("po_dr_items", $data)){
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
    }

    public function delete_dritem(){
        $id=$this->uri->segment(3);
        $dr_id=$this->uri->segment(4);
        if($this->super_model->delete_where("po_dr_items", "dr_items_id", $id)){
            redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');

        }
    }

    public function save_dr(){
         $dr_id=$this->input->post('dr_id');
         $data = array(
            'saved'=>1
         );

         if($this->super_model->update_where("po_dr", $data, "dr_id", $dr_id)){
              redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');
         }
    }

    public function getSupplierItems(){
        $supplier = $this->input->post('supplier');

        echo '<option value="">-Select Supplier Items-</option>';
        foreach($this->super_model->select_row_where('vendor_details', 'vendor_id', $supplier) AS $row){
            $item_name = $this->super_model->select_column_where('item','item_name','item_id', $row->item_id);
            $item_specs = $this->super_model->select_column_where('item','item_specs','item_id', $row->item_id);
            echo '<option value="'. $row->item_id .'">'. $item_name .', '.$item_specs.'</option>';
        }
    }
}

?>