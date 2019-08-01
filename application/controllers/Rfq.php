<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfq extends CI_Controller {

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


	public function rfq_list(){

        $head_count = $this->super_model->count_rows("rfq_head");
        if($head_count!=0){
        //foreach($this->super_model->select_all_order_by("rfq_head", "rfq_date", "DESC") AS $head){

        foreach($this->super_model->select_custom_where("rfq_head", "served='0' AND cancelled = '0' ORDER BY rfq_date DESC") AS $head){
            $data['head'][]= array(
                'rfq_id'=>$head->rfq_id,
                'rfq_no'=>$head->rfq_no,
                'pr_id'=>$head->pr_id,
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id),
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id),
                'rfq_date'=>$head->rfq_date,
                'notes'=>$head->notes,
                'completed'=>$head->completed
                
            );
        }


         foreach($this->super_model->custom_query("SELECT rd.* FROM rfq_details rd INNER JOIN pr_details pd ON rd.pr_details_id = pd.pr_details_id WHERE pd.cancelled=0") AS $it){
                $data['items'][] = array(
                    'rfq_id'=>$it->rfq_id,
                    'item'=>$it->item_desc,
                );
            }
        } else {
            $data= array();
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfq/rfq_list',$data);
        $this->load->view('template/footer');
    }  

    public function add_notes(){
        $rfq_id = $this->input->post('rfq_id');
        $notes = $this->input->post('notes');
        $data = array(
            'notes'=>$notes,
        );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_list');
        }

    }

    public function rfq_outgoing(){
        $rfq_id=$this->uri->segment(3);
        $data['rfq_id']=$rfq_id;
        foreach($this->super_model->select_row_where('rfq_head', 'rfq_id', $rfq_id) AS $head){
            $data['rfq_date']= $head->rfq_date;
            $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id);
            $data['phone']= $this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $head->vendor_id);
            $data['rfq_no']= $head->rfq_no;
            $data['pr_no']= $this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id);
            $data['notes']= $head->notes;
            $data['code']= $head->processing_code;
            $data['noted']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->noted_by);
            $data['approved']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->approved_by);
            $data['prepared']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->prepared_by);
            $data['due']= $head->quotation_date;
            $data['saved']= $head->saved;
        }

        $data['items'] = $this->super_model->select_row_where('rfq_details', 'rfq_id', $rfq_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('template/header');
        $this->load->view('rfq/rfq_outgoing',$data);
        $this->load->view('template/footer');
    } 

    public function save_rfq(){
        $rfq_id = $this->input->post('rfq_id');
        $notes = $this->input->post('notes');
        $due = $this->input->post('due');
        $noted = $this->input->post('noted');
        $approved = $this->input->post('approved');

        $data = array(
            'notes'=>$notes,
            'quotation_date'=>$due,
            'noted_by'=>$noted,
            'approved_by'=>$approved,
            'saved'=>1,
            'prepared_by'=>$_SESSION['user_id']
        );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_outgoing/'.$rfq_id);
        }

    }

    public function complete_rfq(){
         $rfq_id=$this->uri->segment(3);
          $data = array(
            'completed'=>1
          );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_list/', 'refresh');
        }
    }

     public function serve_rfq(){
         $rfq_id=$this->uri->segment(3);
          $data = array(
            'served'=>1
          );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_list/', 'refresh');
        }
    }

     public function cancel_rfq(){
         $rfq_id=$this->uri->segment(3);
          $data = array(
            'cancelled'=>1
          );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_list/', 'refresh');
        }
    }

    public function rfq_incoming(){
        $rfq_id=$this->uri->segment(3);
        $data['rfq_id']=$rfq_id;
        foreach($this->super_model->select_row_where('rfq_head', 'rfq_id', $rfq_id) AS $head){
            $data['rfq_date']= $head->rfq_date;
            $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id);
            $data['phone']= $this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $head->vendor_id);
            $data['rfq_no']= $head->rfq_no;
            $data['pr_no']= $this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id);
            $data['notes']= $head->notes;
            $data['noted']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->noted_by);
            $data['approved']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->approved_by);
            $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $head->prepared_by);
            $data['due']= $head->quotation_date;
            $data['saved']= $head->saved;
        }

         $data['items'] = $this->super_model->select_row_where('rfq_details', 'rfq_id', $rfq_id);
        
        $this->load->view('template/header');
        $this->load->view('rfq/rfq_incoming',$data);
        $this->load->view('template/footer');
    } 
    public function served_rfq(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfq/served_rfq');
        $this->load->view('template/footer');
    } 
    public function cancelled_rfq(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfq/cancelled_rfq');
        $this->load->view('template/footer');
    } 

}

?>