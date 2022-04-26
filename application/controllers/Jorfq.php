<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jorfq extends CI_Controller {

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

	public function jorfq_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $head_count = $this->super_model->count_custom_query("SELECT rh.* FROM jo_rfq_head rh INNER JOIN jor_head jh ON rh.jor_id = jh.jor_id WHERE jh.cancelled='0' AND rh.served='0' AND rh.cancelled = '0'");
        if($head_count!=0){
            foreach($this->super_model->custom_query("SELECT rh.* FROM jo_rfq_head rh INNER JOIN jor_head jh ON rh.jor_id = jh.jor_id WHERE jh.cancelled='0' AND rh.served='0' AND rh.cancelled = '0'") AS $jorfq){
                $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jorfq->jor_id);
                /*if($jo!=''){
                    $jo_no=$jo;
                }else{
                    $jo_no=$this->super_model->select_column_where("jor_head","user_jo_no","jor_id",$jorfq->jor_id);
                }*/
                $data['head'][]= array(
                    'jo_rfq_id'=>$jorfq->jo_rfq_id,
                    'jo_rfq_no'=>$jorfq->jo_rfq_no,
                    'jor_id'=>$jorfq->jor_id,
                    'rfq_date'=>$jorfq->rfq_date,
                    'jo_no'=>$jo_no,
                    'notes'=>$jorfq->notes,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $jorfq->vendor_id),
                    'completed'=>$jorfq->completed
                    
                );
            }

            foreach($this->super_model->custom_query("SELECT rd.* FROM jo_rfq_details rd INNER JOIN jor_items ji ON ji.jor_items_id = rd.jor_items_id WHERE ji.cancelled='0'") AS $it){
                $data['items'][] = array(
                    'jo_rfq_id'=>$it->jo_rfq_id,
                    'item'=>$it->scope_of_work,
                );
            }
        }else{
            $data['head']=array();
            $data['items']=array();
        }   
        $this->load->view('jorfq/jorfq_list',$data);
        $this->load->view('template/footer');
    }  

    public function complete_rfq(){
         $jo_rfq_id=$this->uri->segment(3);
          $data = array(
            'completed'=>1,
            'completed_date'=>date("Y-m-d H:i:s"),
          );
        if($this->super_model->update_where("jo_rfq_head", $data, "jo_rfq_id", $jo_rfq_id)){
             redirect(base_url().'jorfq/jorfq_list/', 'refresh');
        }
    }   

     public function cancel_jorfq(){
         $jorfq_id=$this->input->post('jorfq_id');
         //echo "***".$rfq_id;
         $date=date('Y-m-d H:i:s');
          $data = array(
            'cancelled'=>1,
            'cancelled_reason'=>$this->input->post('reason'),
            'cancelled_date'=>$date,
            'cancelled_by'=>$_SESSION['user_id']

          );
        if($this->super_model->update_where("jo_rfq_head", $data, "jo_rfq_id", $jorfq_id)){
             redirect(base_url().'jorfq/jorfq_list/', 'refresh');
        }
    }  

    public function jorfq_cancelled(){
        $jor_id = $this->uri->segment(3);
        $data['jor_id']=$jor_id;
        $head_count = $this->super_model->count_custom_where("jo_rfq_head","cancelled='1' ORDER BY rfq_date DESC");
        if($head_count!=0){
            foreach($this->super_model->select_custom_where("jo_rfq_head", "cancelled='1' ORDER BY rfq_date DESC") AS $head){
                $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
                if($jo_no!=''){
                    $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
                }else{
                    $jor_no=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $head->jor_id);
                }
                $data['head'][]= array(
                    'jo_rfq_id'=>$head->jo_rfq_id,
                    'jo_rfq_no'=>$head->jo_rfq_no,
                    'jor_id'=>$head->jor_id,
                    'jor_no'=>$jor_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id),
                    'rfq_date'=>$head->rfq_date,
                    //'notes'=>$head->notes,
                    'completed'=>$head->completed
                    
                );
            }

            foreach($this->super_model->custom_query("SELECT rd.* FROM jo_rfq_details rd INNER JOIN jor_items ji ON ji.jor_items_id = rd.jor_items_id WHERE ji.cancelled='1'") AS $it){
                $data['items'][] = array(
                    'jo_rfq_id'=>$it->jo_rfq_id,
                    'item'=>$it->scope_of_work,
                );
            }
        } else {
            $data['head']= array();
            $data['items']= array();
        }

        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jorfq/jorfq_cancelled',$data);
        $this->load->view('template/footer');
    }

    public function served_jorfq(){
         $jo_rfq_id=$this->uri->segment(3);
          $data = array(
            'served'=>1
          );
        if($this->super_model->update_where("jo_rfq_head", $data, "jo_rfq_id", $jo_rfq_id)){
             redirect(base_url().'jorfq/jorfq_list/', 'refresh');
        }
    }

    public function jorfq_served(){
        $jor_id = $this->uri->segment(3);
        $data['jor_id']=$jor_id;
        $head_count = $this->super_model->count_custom_where("jo_rfq_head","served='1' ORDER BY rfq_date DESC");
        if($head_count!=0){
        //foreach($this->super_model->select_all_order_by("rfq_head", "rfq_date", "DESC") AS $head){

        foreach($this->super_model->select_custom_where("jo_rfq_head", "served='1' ORDER BY rfq_date DESC") AS $head){
         $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
                /*if($jo_no!=''){
                    $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
                }else{
                    $jor_no=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $head->jor_id);
                }*/
            $data['head'][]= array(
                'jo_rfq_id'=>$head->jo_rfq_id,
                'jo_rfq_no'=>$head->jo_rfq_no,
                'jor_id'=>$head->jor_id,
                'jor_no'=>$jor_no,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id),
                'rfq_date'=>$head->rfq_date,
                //'notes'=>$head->notes,
                'completed'=>$head->completed
                
            );
        }


         foreach($this->super_model->custom_query("SELECT rd.* FROM jo_rfq_details rd INNER JOIN jor_items ji ON rd.jor_items_id = ji.jor_items_id") AS $it){
                $data['items'][] = array(
                    'jo_rfq_id'=>$it->jo_rfq_id,
                    'item'=>$it->scope_of_work,
                );
            }
        } else {
            $data['head']= array();
            $data['items']= array();
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jorfq/jorfq_served',$data);
        $this->load->view('template/footer');
    } 

    public function jorfq_outgoing(){
        $jo_rfq_id=$this->uri->segment(3);
        $data['jo_rfq_id']=$jo_rfq_id;
        foreach($this->super_model->select_row_where('jo_rfq_head', 'jo_rfq_id', $jo_rfq_id) AS $head){
            $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
            /*if($jo_no!=''){
                $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
            }else{
                $jor_no=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $head->jor_id);
            }*/
            $data['rfq_date']= $head->rfq_date;
            //$data['scope_of_work']= $this->super_model->select_column_where("jor_items", "scope_of_work", "jor_items_id", $head->jor_items_id);
            //$data['notes']= $this->super_model->select_column_where("jor_notes", "notes", "jor_notes_id", $head->jor_notes_id);
            $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id);
            $data['phone']= $this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $head->vendor_id);
            $data['jo_rfq_no']= $head->jo_rfq_no;
            $data['jo_no']= $this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
            /*$jo=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
            if($jo!=''){
                $data['jo_no']= $this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $head->jor_id);
            }else{
                $data['jo_no']= $this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $head->jor_id);
            }*/
            $data['requested_by']= $this->super_model->select_column_where("jor_head", "requested_by", "jor_id", $head->jor_id);
            $data['purpose']= $this->super_model->select_column_where("jor_head", "purpose", "jor_id", $head->jor_id);
            $data['duration']= $this->super_model->select_column_where("jor_head", "duration", "jor_id", $head->jor_id);
            $data['notes']= $head->notes;
            //$data['code']= $head->processing_code;
            $data['noted']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->noted_by);
            $data['approved']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->approved_by);
            $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $head->prepared_by);
            // /$data['due']= $head->quotation_date;
            $data['saved']= $head->saved;
            $data['cancelled']= $head->cancelled;
            $data['served']= $head->served;
            $data['completed']= $head->completed;
            /*$data['items'] = $this->super_model->select_row_where_order_by('jo_rfq_details', 'jo_rfq_id', $jo_rfq_id, 'jor_items_id', 'ASC');*/
            $data['rfq_notes'] = $this->super_model->select_row_where_order_by('jor_notes', 'jor_id', $head->jor_id, 'jor_notes_id', 'ASC');
            $data['general_desc']= $this->super_model->select_column_where("jor_items", "general_desc", "jor_id", $head->jor_id);

            foreach($this->super_model->select_row_where_order_by('jo_rfq_details', 'jo_rfq_id', $jo_rfq_id, 'jor_items_id', 'ASC') AS $as){
                $item_no=$this->super_model->select_column_where("jor_items", "item_no", "jor_items_id", $as->jor_items_id);
                $data['items'][]=array(
                    "item_no"=>$item_no,
                    "scope_of_work"=>$as->scope_of_work,
                );
            }
        }

        
     
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('template/header');
        $this->load->view('jorfq/jorfq_outgoing',$data);
        $this->load->view('template/footer');
    }

    public function add_jorfq_notes(){
        $jo_rfq_id = $this->input->post('jo_rfq_id');
        $notes = $this->input->post('notes');
        $data = array(
            'notes'=>$notes,
        );
        if($this->super_model->update_where("jo_rfq_head", $data, "jo_rfq_id", $jo_rfq_id)){
             redirect(base_url().'jorfq/jorfq_list');
        }

    }

    public function save_jorfq(){
        $jorfq_id = $this->input->post('jorfq_id');
        /*$notes = $this->input->post('notes');
        $due = $this->input->post('due');
        $noted = $this->input->post('noted');
        $approved = $this->input->post('approved');*/
        $data = array(
            /*'notes'=>$notes,
            'quotation_date'=>$due,
            'noted_by'=>$noted,
            'approved_by'=>$approved,*/
            'saved'=>1,
            //'prepared_by'=>$_SESSION['user_id']
        );
        if($this->super_model->update_where("jo_rfq_head", $data, "jo_rfq_id", $jo_rfq_id)){
             redirect(base_url().'jorfq/jorfq_outgoing/'.$jo_rfq_id);
        }

    }

    public function complete_jorfq(){
         $jo_rfq_id=$this->uri->segment(3);
          $data = array(
            'completed'=>1
          );
        if($this->super_model->update_where("jo_rfq_head", $data, "jo_rfq_id", $jo_rfq_id)){
             redirect(base_url().'jorfq/jorfq_list/', 'refresh');
        }
    }
    
}

?>