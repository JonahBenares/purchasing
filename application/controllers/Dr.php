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

    public function add_dr(){   

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
                'dr_no'=>$dr_no,
                'direct_purchase'=>2,
                'create_date'=>$this->input->post('dr_date')
            );
            if($this->super_model->insert_into("dr_head", $drhead)){
                 redirect(base_url().'dr/dr_prnt/'.$dr_id);
            }

    }

    public function add_purpose(){
        $dr_id= $this->input->post('dr_id');
        $data=array(
            'dr_id'=>$this->input->post('dr_id'),
            'requestor'=>$this->input->post('requested_by'),
            'purpose_id'=>$this->input->post('requested_by'),
            'enduse_id'=>$this->input->post('enduse'),
            'notes'=>$this->input->post('notes')
        );
        if($this->super_model->insert_into("dr_details", $data)){
             redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');
        }
    }


    public function dr_list(){   
        $data['head'] = array();
        foreach($this->super_model->select_all('dr_head') AS $head){
            $cancelled = $this->super_model->select_column_where("po_head", "cancelled", "po_id",$head->po_id);
            if($cancelled ==0 ){
                $data['head'][]=array(
                    'create_date'=>$head->create_date,
                    'direct_purchase'=>$head->direct_purchase,
                    'dr_no'=>$head->dr_no,
                    'po_id'=>$head->po_id,
                    'rfd_id'=>$head->rfd_id,
                    'dr_id'=>$head->dr_id
                );
            } 
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('dr/dr_list',$data);
        $this->load->view('template/footer');
    }

    public function dr_prnt(){   
        $dr_id=$this->uri->segment(3);
      
        $data['dr_id']=$dr_id;
        $data['saved']=$this->super_model->select_column_where('dr_head','saved','dr_id',$dr_id);
        $data['head'] =  $this->super_model->select_row_where('dr_head', 'dr_id', $dr_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $data['enduse']=$this->super_model->select_all_order_by("enduse", "enduse_name", "ASC");
        $data['purpose']=$this->super_model->select_all_order_by("purpose", "purpose_name", "ASC");

        foreach($this->super_model->select_row_where("dr_details", "dr_id", $dr_id) AS $drdet){
          
            $data['drpurp'][]= array(
                'id'=>$drdet->dr_details_id,
                'notes'=>$drdet->notes,
                'purpose'=>$this->super_model->select_column_where('purpose','purpose_name','purpose_id', $drdet->purpose_id),
                'enduse'=>$this->super_model->select_column_where('enduse','enduse_name','enduse_id', $drdet->enduse_id),
                'requestor'=>$this->super_model->select_column_where('employees','employee_name','employee_id', $drdet->requestor)
            );
        }

        foreach($this->super_model->select_row_where("dr_items", "dr_id", $dr_id) AS $dritems){
            $unit_id = $this->super_model->select_column_where('item','unit_id','item_id', $dritems->item_id);
            $data['items'][]= array(
                'id'=>$dritems->dr_items_id,
                'supplier'=>$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $dritems->vendor_id),
                'item'=>$this->super_model->select_column_where('item','item_name','item_id', $dritems->item_id),
                'specs'=>$this->super_model->select_column_where('item','item_specs','item_id', $dritems->item_id),
                'unit'=>$this->super_model->select_column_where('unit','unit_name','unit_id', $unit_id),
                'delivered'=>$dritems->delivered,
                'remarks'=>$dritems->remarks
            );
        }



        $this->load->view('template/header');
        $this->load->view('dr/dr_prnt',$data);
        $this->load->view('template/footer');
    }

    public function additemdr(){   
        $dr_id=$this->uri->segment(3);
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['dr_id']=$dr_id;
        $this->load->view('template/header');
        $this->load->view('dr/additemdr',$data);
        $this->load->view('template/footer');
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

    public function add_dr_item(){
        $dr_id=$this->input->post('dr_id');
        $data = array(
            'dr_id'=>$this->input->post('dr_id'),
            'item_id'=>$this->input->post('items'),
            'vendor_id'=>$this->input->post('supplier'),
            'delivered'=>$this->input->post('delivered'),
            'remarks'=>$this->input->post('remarks'),
        );

         if($this->super_model->insert_into("dr_items", $data)){
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

     public function delete_purpose(){
        $id=$this->uri->segment(3);
        $dr_id=$this->uri->segment(4);
        if($this->super_model->delete_where("dr_details", "dr_details_id", $id)){
            redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');

        }
    }


     public function delete_dritem(){
        $id=$this->uri->segment(3);
        $dr_id=$this->uri->segment(4);
        if($this->super_model->delete_where("dr_items", "dr_items_id", $id)){
            redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');

        }
    }

    public function save_dr(){
         $dr_id=$this->input->post('dr_id');
         $data = array(
            'saved'=>1
         );

         if($this->super_model->update_where("dr_head", $data, "dr_id", $dr_id)){
              redirect(base_url().'dr/dr_prnt/'.$dr_id, 'refresh');
         }
    }
}

?>