<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterfile extends CI_Controller {

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

	public function index(){
        if(empty($_SESSION['user_id'])){
            $this->load->view('masterfile/login');
        }else {
            $this->load->view('template/header');
            $this->load->view('template/navbar');
            $this->load->view('masterfile/dashboard',$data);
            $this->load->view('template/footer');
        }
    }

    public function dashboard(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $count = $this->super_model->count_rows("reminder");
        if($count!=0){
            foreach($this->super_model->select_all_order_by("reminder","due_date","ASC") AS $rem){
                $data['reminder'][] = array(
                    'reminder_id'=>$rem->reminder_id,
                    'notes'=>$rem->notes,
                    'due_date'=>$rem->due_date,
                    'done'=>$rem->done,
                    'remind'=>$this->super_model->select_column_where("users","fullname","user_id",$rem->user_id),
                );
            }
        } else {
            $data=array();
        }


        foreach($this->super_model->custom_query("SELECT ph.date_prepared, ph.pr_id, ph.pr_no, pd.item_description, pd.pr_details_id FROM pr_head ph INNER JOIN pr_details pd ON ph.pr_id = pd.pr_id WHERE saved='1' AND ph.cancelled = '0'") AS $pr){

            $rfq_outgoing = $this->super_model->count_join_where("rfq_head","rfq_details", "rfq_head.pr_id = '$pr->pr_id' AND rfq_details.pr_details_id = '$pr->pr_details_id'","rfq_id");

            $rfq_incoming = $this->super_model->count_join_where("rfq_head","rfq_details", "rfq_head.pr_id = '$pr->pr_id' AND rfq_details.pr_details_id = '$pr->pr_details_id' AND rfq_head.completed='1'","rfq_id");

            $for_te = $this->super_model->count_join_where("aoq_head","aoq_offers", "aoq_head.pr_id = '$pr->pr_id' AND aoq_offers.pr_details_id = '$pr->pr_details_id' AND aoq_offers.recommended='0'","aoq_id");

            $te_done = $this->super_model->count_join_where("aoq_head","aoq_offers", "aoq_head.pr_id = '$pr->pr_id' AND aoq_offers.pr_details_id = '$pr->pr_details_id' AND aoq_offers.recommended='1'","aoq_id");


            $po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND pi.pr_details_id = '$pr->pr_details_id'");


            $data['pendingpr'][]= array(
                'pr_date'=>$pr->date_prepared,
                'pr_no'=>$pr->pr_no,
                'item'=>$pr->item_description,
                'rfq_outgoing'=>$rfq_outgoing,
                'rfq_incoming'=>$rfq_incoming,
                'for_te'=>$for_te,
                'te_done'=>$te_done,
                'po'=>$po
            );
        }


        foreach($this->super_model->custom_query("SELECT ph.pr_id, ph.pr_no, pd.item_description, pd.pr_details_id, pd.date_needed FROM pr_head ph INNER JOIN pr_details pd ON ph.pr_id = pd.pr_id WHERE saved='1' AND ph.cancelled = '0'") AS $pr){

            $current_date= date('Y-m-d');
            $diff= $this-> dateDifference($current_date , $pr->date_needed , $differenceFormat = '%a' );

            $po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND pi.pr_details_id = '$pr->pr_details_id'");



            if($po==0 && $diff<=7){
                $reminder = 'PR No.: '. $pr->pr_no. " - " . $pr->item_description;
                $due = date('M j, Y', strtotime($pr->date_needed));
                $data['reminder'][]=array(
                    'reminder_id'=>'',
                    'notes'=>$reminder,
                    'due_date'=>$due,
                    'done'=>'',
                    'remind'=>'',
                );
            }
        
        }

            foreach($this->super_model->custom_query("SELECT quotation_date, vendor_id, rfq_no FROM rfq_head WHERE saved='1' AND cancelled = '0' AND completed = '0' GROUP BY rfq_id") AS $rfq){

                $rfq_diff= $this-> dateDifference($current_date , $rfq->quotation_date , $differenceFormat = '%a' );

                if($po==0 && $rfq_diff<=3){
                    $reminder = 'RFQ No.: '. $rfq->rfq_no. " - " . $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$rfq->vendor_id);
                    $due = date('M j, Y', strtotime($rfq->quotation_date));
                    $data['reminder'][]=array(
                        'reminder_id'=>'',
                        'notes'=>$reminder,
                        'due_date'=>$due,
                        'done'=>'',
                        'remind'=>'',
                    );
                }
            }
        $this->load->view('masterfile/dashboard',$data);
        $this->load->view('template/footer');
    }

    public function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
       
        $interval = date_diff($datetime1, $datetime2);
       
        return $interval->format($differenceFormat);
       
    }

    public function insert_reminder(){
        $reminder = trim($this->input->post('reminder')," ");
        $due_date = trim($this->input->post('due_date')," ");
        $create_date = date("Y-m-d H:i:s");
        $user_id = $_SESSION['user_id'];
        $data = array(
            'notes'=>$reminder,
            'due_date'=>$due_date,
            'create_date'=>$create_date,
            'user_id'=>$user_id,
        );
        if($this->super_model->insert_into("reminder", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."masterfile/dashboard'; </script>";
        }
    }

    public function reminder_done(){
        $reminder_id=$this->uri->segment(3);
        $data=array(
            'done'=>1,
        );
        
        if($this->super_model->update_where('reminder', $data, 'reminder_id', $reminder_id)){
            echo "<script>alert('Successfully Done!'); window.location ='".base_url()."masterfile/dashboard';</script>";
        }
    }

    public function login(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $count=$this->super_model->login_user($username,$password);
        if($count>0){   
            $password1 =md5($this->input->post('password'));
            $fetch=$this->super_model->select_custom_where("users", "username = '$username' AND (password = '$password' OR password = '$password1')");
            foreach($fetch AS $d){
                $userid = $d->user_id;
                $username = $d->username;
                $fullname = $d->fullname;
            }
            $newdata = array(
               'user_id'=> $userid,
               'username'=> $username,
               'fullname'=> $fullname,
               'logged_in'=> TRUE
            );
            $this->session->set_userdata($newdata);
            redirect(base_url().'masterfile/dashboard/');
        }
        else{
            $this->session->set_flashdata('error_msg', 'Username And Password Do not Exist!');
            $this->load->view('masterfile/login');      
        }
    }

    public function user_logout(){
        $this->session->sess_destroy();
        $this->load->view('template/header');
        $this->load->view('masterfile/login');
        $this->load->view('template/footer');
        echo "<script>alert('You have successfully logged out.'); 
        window.location ='".base_url()."masterfile/index'; </script>";
    }

    public function employee_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['department']=$this->super_model->select_all_order_by('department', 'department_name', 'ASC');
        foreach($this->super_model->select_all_order_by('employees', 'employee_name', 'ASC') AS $emp){
            $data['employees'][]=array(
                'emp_id'=>$emp->employee_id,
                'employee'=>$emp->employee_name,
                'position'=>$emp->position,
                'department'=>$this->super_model->select_column_where('department','department_name','department_id',$emp->department_id)
            );
        }
        $this->load->view('masterfile/employee_list',$data);
        $this->load->view('template/footer');
    }

    public function insert_emp(){
        $emp_name = trim($this->input->post('emp_name')," ");
        $dept = trim($this->input->post('dept')," ");
        $position = trim($this->input->post('position')," ");
        $data = array(
            'employee_name'=>$emp_name,
            'department_id'=>$dept,
            'position'=>$position,
        );
        if($this->super_model->insert_into("employees", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."masterfile/employee_list'; </script>";
        }
    }

    public function update_employee(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['department']=$this->super_model->select_all_order_by('department', 'department_name', 'ASC');
        $data['emp'] = $this->super_model->select_row_where('employees', 'employee_id', $id);
        $this->load->view('masterfile/update_employee',$data);
        $this->load->view('template/footer');
    }

    public function edit_employee(){
        $data = array(
            'employee_name'=>$this->input->post('emp_name'),
            'department_id'=>$this->input->post('dept'),
            'position'=>$this->input->post('position'),
        );
        $emp_id = $this->input->post('emp_id');
            if($this->super_model->update_where('employees', $data, 'employee_id', $emp_id)){
            echo "<script>alert('Successfully Updated!'); window.opener.location.reload(); window.close();</script>";
        }
    }

    public function delete_employee(){
        $id=$this->uri->segment(3);
        if($this->super_model->delete_where('employees', 'employee_id', $id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."masterfile/employee_list'; </script>";
        }
    }
   
    public function unit_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['unit']=$this->super_model->select_all_order_by('unit', 'unit_name', 'ASC');
        $this->load->view('masterfile/unit_list',$data);
        $this->load->view('template/footer');
    }

    public function insert_unit(){
        $unit = trim($this->input->post('unit')," ");
        $data = array(
            'unit_name'=>$unit
        );
        if($this->super_model->insert_into("unit", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."masterfile/unit_list'; </script>";
        }
    }

    public function update_unit(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['unit'] = $this->super_model->select_row_where('unit', 'unit_id', $id);
        $this->load->view('masterfile/update_unit',$data);
        $this->load->view('template/footer');
    }

    public function edit_unit(){
        $data = array(
            'unit_name'=>$this->input->post('unit'),
        );
        $unit_id = $this->input->post('unit_id');
            if($this->super_model->update_where('unit', $data, 'unit_id', $unit_id)){
            echo "<script>alert('Successfully Updated!'); window.opener.location.reload(); window.close();</script>";
        }
    }

    public function delete_unit(){
        $id=$this->uri->segment(3);
        if($this->super_model->delete_where('unit', 'unit_id', $id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."masterfile/unit_list'; </script>";
        }
    }
    

}

?>