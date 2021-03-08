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

/*	public function index(){
        if(empty($_SESSION['user_id'])){
            $this->load->view('masterfile/login');
        }else {
            $this->load->view('template/header');
            $this->load->view('template/navbar');
            $this->load->view('masterfile/dashboard');
            $this->load->view('template/footer');
        }
    }*/
    public function index(){
        
            $this->load->view('masterfile/login');    
        
    }

    public function loginprocess(){
            $username=$this->input->post('username');
            $password=$this->input->post('password');
            $count=$this->super_model->login_user($username,$password);
            if($count>0){   
                $password1 =md5($this->input->post('password'));
                $fetch=$this->super_model->select_custom_where("users", "username = '$username' AND (password = '$password' OR password = '$password1')");
                foreach($fetch AS $d){
               /*     $userid = $d->user_id;
                    $username = $d->username;
                    $fullname = $d->fullname;*/

                  $newdata = array(
                   'user_id'=> $d->user_id,
                   'username'=> $d->username,
                   'fullname'=> $d->fullname,
                   'logged_in'=> TRUE,
                   
                      );
                }
             
                $this->session->set_userdata($newdata);
                redirect('masterfile/dashboard');
            }else{
                $this->session->set_flashdata('error_msg', 'Username And Password Do not Exist!');
                //$this->load->view('masterfile/index');     
                redirect('masterfile/index'); 
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
                    'type'=>'manual',
                    'source'=>''
                );
            }
        } else {
            $data=array();
        }

        $count = $this->super_model->count_rows("to_do_today");
        if($count!=0){
            foreach($this->super_model->select_all_order_by("to_do_today","due_date","ASC") AS $todo){
                $data['todo'][] = array(
                    'todo_id'=>$todo->todo_id,
                    'notes'=>$todo->notes,
                    'due_date'=>$todo->due_date,
                    'done'=>$todo->done,
                    'remind'=>$this->super_model->select_column_where("users","fullname","user_id",$todo->user_id),
                    'type'=>'manual',
                    'source'=>''
                );
            }
        } else {
            $data=array();
        }

        foreach($this->super_model->select_row_where("po_head", "revised", "1") AS $td){
            $today = date('Y-m-d');
            $data['todo'][] =  array(
                'todo_id'=>$td->po_id,
                'notes'=>'Follow up revision approval of PO '.$td->po_no."-".COMPANY,
                'due_date'=>$today,
                'done'=>'',
                'remind'=>'',
                'type'=>'auto',
                'source'=>'po'
            );
        }

      
        
/*        $delivered = array();
        foreach($this->super_model->custom_query("SELECT pi.pr_details_id FROM po_dr_items pi INNER JOIN po_dr pd ON pi.dr_id = pd.dr_id WHERE pd.received='1'") AS $dr){
            $delivered[] = $dr->pr_details_id;
        }
         $calendar = array();
        foreach($this->super_model->custom_query("SELECT pr_details_id FROM pr_calendar") AS $cal){
            $calendar[] = $cal->pr_details_id;
        }

        $pending=array();
        foreach($calendar AS $cl){
            foreach($delivered AS $dl){
                if($cl != $dl){
                    $pending[]= $cl;
                }
            }
        }

       $result= array_unique($pending);
       foreach($result AS $res){
        $pr_id= $this->super_model->select_column_where("pr_details","pr_id","pr_details_id",$res);
            $data['dash_calendar'][] = array(
                'purpose'=>$this->super_model->select_column_where("pr_head","purpose","pr_id",$pr_id),
                'enduse'=>$this->super_model->select_column_where("pr_head","enduse","pr_id",$pr_id),
                'site_pr'=>$this->super_model->select_column_where("pr_details","add_remarks","pr_details_id",$res),
                'requestor'=>$this->super_model->select_column_where("pr_head","requestor","pr_id",$pr_id),
                'qty'=>$this->super_model->select_column_where("pr_details","quantity","pr_details_id",$res),
                'uom'=>$this->super_model->select_column_where("pr_details","uom","pr_details_id",$res),
                'description'=>$this->super_model->select_column_where("pr_details","item_description","pr_details_id",$res),
                'status'=>'',
            );
       }*/
/*
        $count_calendar = $this->super_model->count_rows("pr_calendar");
        if($count_calendar!=0){
        foreach($this->super_model->select_custom_where("pr_calendar","ver_date_needed!='' AND estimated_price!='0' ORDER BY ver_date_needed DESC") AS $ca){
            $quantity=$this->super_model->select_column_where("pr_details","quantity","pr_details_id",$ca->pr_details_id);
            $total_ep = $quantity * $ca->estimated_price;
            $total_array[] = $total_ep;
            $total_disp = array_sum($total_array);
            $data['total_disp']=$total_disp;
            $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $ca->pr_details_id, "po_id", "DESC", "1");
            $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
            $data['dash_calendar'][] =  array(
                'ver_date_needed'=>$ca->ver_date_needed,
                'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$ca->pr_id),
                'description'=>$this->super_model->select_column_where("pr_details","item_description","pr_details_id",$ca->pr_details_id),
                'quantity'=>$quantity,
                'estimated_price'=>$ca->estimated_price,
                'total_ep'=>$total_ep,
                'served'=>$served

            );
            }
        }else{
            $data['dash_calendar']=array();
            $data['total_disp']=0.00;
        }*/

/*
        foreach($this->super_model->custom_query("SELECT ph.date_prepared, ph.pr_id, ph.pr_no, pd.item_description, pd.pr_details_id, pd.quantity FROM pr_head ph INNER JOIN pr_details pd ON ph.pr_id = pd.pr_id WHERE saved='1' AND pd.cancelled = '0' AND ph.cancelled='0'") AS $pr){

            $rfq_outgoing = $this->super_model->count_join_where("rfq_head","rfq_details", "rfq_head.pr_id = '$pr->pr_id' AND rfq_details.pr_details_id = '$pr->pr_details_id'","rfq_id");

            $for_te = $this->super_model->count_join_where("aoq_head","aoq_offers", "aoq_head.pr_id = '$pr->pr_id' AND aoq_offers.pr_details_id = '$pr->pr_details_id' AND aoq_offers.recommended='0' AND aoq_head.cancelled='0'","aoq_id");

            $te_done = $this->super_model->count_join_where("aoq_head","aoq_offers", "aoq_head.pr_id = '$pr->pr_id' AND aoq_offers.pr_details_id = '$pr->pr_details_id' AND aoq_offers.recommended='1' AND aoq_head.cancelled='0'","aoq_id");


            $po_issued = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND pi.pr_details_id = '$pr->pr_details_id'");

            $po_delivered = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND served = '1' AND pi.pr_details_id = '$pr->pr_details_id'");

            $po_qty = $this->super_model->select_sum_join("quantity","po_head","po_items", "po_head.cancelled='0' AND po_items.pr_id = '$pr->pr_id' AND po_items.pr_details_id = '$pr->pr_details_id'","po_id");

            $balance = $pr->quantity - $po_qty;

            $data['pendingpr'][]= array(
                'pr_date'=>$pr->date_prepared,
                'pr_no'=>$pr->pr_no,
                'item'=>$pr->item_description,
                'rfq_outgoing'=>$rfq_outgoing,
                'for_te'=>$for_te,
                'te_done'=>$te_done,
                'po_issued'=>$po_issued,
                'po_delivered'=>$po_delivered,
                'balance'=>$balance
            );
        }
*/

        foreach($this->super_model->custom_query("SELECT ph.pr_id, ph.pr_no, pd.item_description, pd.pr_details_id, pd.date_needed, pd.quantity FROM pr_head ph INNER JOIN pr_details pd ON ph.pr_id = pd.pr_id WHERE saved='1' AND pd.cancelled = '0' AND ph.cancelled = '0'") AS $pr){

            $current_date= date('Y-m-d');
            $diff= $this-> dateDifference($current_date , $pr->date_needed , $differenceFormat = '%a' );

            $po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND pi.pr_details_id = '$pr->pr_details_id'");

            $po_qty = $this->super_model->select_sum_join("quantity","po_head","po_items", "po_head.cancelled='0' AND po_items.pr_id = '$pr->pr_id' AND po_items.pr_details_id = '$pr->pr_details_id'","po_id");


            if(($po==0 && ($diff>=1 && $diff<=7)) || ($po_qty !=$pr->quantity && ($diff>=1 && $diff<=7)) ){
                if($po_qty !=$pr->quantity){
                    $bal = $pr->quantity-$po_qty;
                    $rem = 'Unserved: '.$bal;
                }
                $reminder = 'Process PO for PR No.: '. $pr->pr_no. " - " . $pr->item_description . ", ".$rem;
                $due = date('M j, Y', strtotime($pr->date_needed));
                $data['reminder'][]=array(
                    'reminder_id'=>$pr->pr_id,
                    'notes'=>$reminder,
                    'due_date'=>$due,
                    'done'=>'',
                    'remind'=>'',
                    'type'=>'auto',
                    'source'=>'pr'
                );
            }


            if(($po==0 && $diff<=0) || ($po_qty !=$pr->quantity && $diff<=0) ){
                if($po_qty !=$pr->quantity){
                    $bal = $pr->quantity-$po_qty;
                    $rem = 'Unserved: '.$bal;
                }
                $reminder = 'Process PO for PR No.: '. $pr->pr_no. " - " . $pr->item_description . ", ".$rem;
                $due = date('M j, Y', strtotime($pr->date_needed));
                $data['todo'][]=array(
                    'todo_id'=>$pr->pr_id,
                    'notes'=>$reminder,
                    'due_date'=>$due,
                    'done'=>'',
                    'remind'=>'',
                    'type'=>'auto',
                    'source'=>'pr'
                );
            }
        
        }

            foreach($this->super_model->custom_query("SELECT quotation_date, vendor_id, rfq_no, rfq_id FROM rfq_head WHERE saved='1' AND cancelled = '0' AND completed = '0' GROUP BY rfq_id") AS $rfq){

                $rfq_diff= $this-> dateDifference($current_date , $rfq->quotation_date , $differenceFormat = '%a' );

                if($po==0 && ($rfq_diff>=1 && $rfq_diff<=7)){
                    $reminder = 'Follow up RFQ No. '. $rfq->rfq_no. " with " . $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$rfq->vendor_id);
                    $due = date('M j, Y', strtotime($rfq->quotation_date));
                    $data['reminder'][]=array(
                        'reminder_id'=>$rfq->rfq_id,
                        'notes'=>$reminder,
                        'due_date'=>$due,
                        'done'=>'',
                        'remind'=>'',
                        'type'=>'auto',
                        'source'=>'rfq'
                    );
                }

                 if($po==0 && $rfq_diff<=0){
                    $reminder = 'Follow up RFQ No. '. $rfq->rfq_no. " with " . $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$rfq->vendor_id);
                    $due = date('M j, Y', strtotime($rfq->quotation_date));
                    $data['todo'][]=array(
                        'todo_id'=>$rfq->rfq_id,
                        'notes'=>$reminder,
                        'due_date'=>$due,
                        'done'=>'',
                        'remind'=>'',
                        'type'=>'auto',
                        'source'=>'rfq'
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

    public function insert_todo(){
        $todo = trim($this->input->post('todo')," ");
        $due_date = trim($this->input->post('due_date')," ");
        $create_date = date("Y-m-d H:i:s");
        $user_id = $_SESSION['user_id'];
        $data = array(
            'notes'=>$todo,
            'due_date'=>$due_date,
            'create_date'=>$create_date,
            'user_id'=>$user_id,
        );
        if($this->super_model->insert_into("to_do_today", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."masterfile/dashboard'; </script>";
        }
    }

    public function todo_done(){
        $todo_id=$this->uri->segment(3);
        $data=array(
            'done'=>1,
        );
        
        if($this->super_model->update_where('to_do_today', $data, 'todo_id', $todo_id)){
            echo "<script>alert('Successfully Done!'); window.location ='".base_url()."masterfile/dashboard';</script>";
        }
    }

  

    public function user_logout(){
        $this->session->sess_destroy();
        /*$this->load->view('template/header');*/
        /*$this->load->view('masterfile/login');*/
        /*$this->load->view('template/footer');*/
        echo "<script>alert('You have successfully logged out.'); 
        window.location ='".base_url()."'; </script>";
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

    public function department_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['department']=$this->super_model->select_all_order_by('department', 'department_name', 'ASC');
        $this->load->view('masterfile/department_list',$data);
        $this->load->view('template/footer');
    }

    public function insert_dept(){
        $dept = trim($this->input->post('dept')," ");
        $data = array(
            'department_name'=>$dept
        );
        if($this->super_model->insert_into("department", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."masterfile/department_list'; </script>";
        }
    }

    public function update_department(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['department'] = $this->super_model->select_row_where('department', 'department_id', $id);
        $this->load->view('masterfile/update_department',$data);
        $this->load->view('template/footer');
    }

    public function edit_dept(){
        $data = array(
            'department_name'=>$this->input->post('dept'),
        );
        $department_id = $this->input->post('dept_id');
            if($this->super_model->update_where('department', $data, 'department_id', $department_id)){
            echo "<script>alert('Successfully Updated!'); window.opener.location.reload(); window.close();</script>";
        }
    }

    public function delete_dept(){
        $id=$this->uri->segment(3);
        if($this->super_model->delete_where('department', 'department_id', $id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."masterfile/department_list'; </script>";
        }
    }

    public function company_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['company']=$this->super_model->select_all_order_by('company', 'company_name', 'ASC');
        $this->load->view('masterfile/company_list',$data);
        $this->load->view('template/footer');
    }

    public function update_company(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['company'] = $this->super_model->select_row_where('company', 'company_id', $id);
        $this->load->view('masterfile/update_company',$data);
        $this->load->view('template/footer');
    }

    public function edit_company(){
        $data = array(
            'company_name'=>$this->input->post('company'),
        );
        $department_id = $this->input->post('company_id');
            if($this->super_model->update_where('company', $data, 'company_id', $department_id)){
            echo "<script>alert('Successfully Updated!'); window.opener.location.reload(); window.close();</script>";
        }
    }

    public function insert_company(){
        $dept = trim($this->input->post('company')," ");
        $data = array(
            'company_name'=>$dept
        );
        if($this->super_model->insert_into("company", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."masterfile/company_list'; </script>";
        }
    }

    public function delete_company(){
        $id=$this->uri->segment(3);
        if($this->super_model->delete_where('company', 'company_id', $id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."masterfile/company_list'; </script>";
        }
    }
    public function proj_activity_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['proj_act']=$this->super_model->select_all_order_by('project_activity', 'proj_activity', 'ASC');
        $this->load->view('masterfile/proj_activity_list',$data);
        $this->load->view('template/footer');
    }

    public function insert_proj_activity(){
        $proj_activity = trim($this->input->post('proj_activity')," ");
        $c_remarks = trim($this->input->post('c_remarks')," ");
        $duration = trim($this->input->post('duration')," ");
        $target_start_date = trim($this->input->post('target_start_date')," ");
        $target_completion = trim($this->input->post('target_completion')," ");
        $actual_start = trim($this->input->post('actual_start')," ");
        $actual_completion = trim($this->input->post('actual_completion')," ");
        $est_total_materials = trim($this->input->post('est_total_materials')," ");
        $status = trim($this->input->post('status')," ");
        $data = array(
            'proj_activity'=>$proj_activity,
            'c_remarks'=>$c_remarks,
            'duration'=>$duration,
            'target_start_date'=>$target_start_date,
            'target_completion'=>$target_completion,
            'actual_start'=>$actual_start,
            'actual_completion'=>$actual_completion,
            'est_total_materials'=>$est_total_materials,
            'status'=>$status,
        );
        if($this->super_model->insert_into("project_activity", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."masterfile/proj_activity_list'; </script>";
        }
    }

    public function update_proj_activity(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['proj_act'] = $this->super_model->select_row_where('project_activity', 'proj_act_id', $id);
        $this->load->view('masterfile/update_proj_activity',$data);
        $this->load->view('template/footer');
    }

    public function edit_proj_act(){
        $data = array(
            'proj_activity'=>$this->input->post('proj_activity'),
            'c_remarks'=>$this->input->post('c_remarks'),
            'duration'=>$this->input->post('duration'),
            'target_start_date'=>$this->input->post('target_start_date'),
            'target_completion'=>$this->input->post('target_completion'),
            'actual_start'=>$this->input->post('actual_start'),
            'actual_completion'=>$this->input->post('actual_completion'),
            'est_total_materials'=>$this->input->post('est_total_materials'),
            'status'=>$this->input->post('status'),
        );
        $proj_act_id = $this->input->post('proj_act_id');
        if($this->super_model->update_where('project_activity', $data, 'proj_act_id', $proj_act_id)){
            echo "<script>alert('Successfully Updated!'); window.opener.location.reload(); window.close();</script>";
        }
    }

    public function filter_pending(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $filter_date_from = trim($this->input->post('filter_date_from')," ");
        $filter_date_to = trim($this->input->post('filter_date_to')," ");
        $estimated_price =$this->input->post('estimated_price');
        $quantity =$this->input->post('quantity');
        $count = $this->super_model->count_rows("reminder");
        if($count!=0){
            foreach($this->super_model->select_all_order_by("reminder","due_date","ASC") AS $rem){
                $data['reminder'][] = array(
                    'reminder_id'=>$rem->reminder_id,
                    'notes'=>$rem->notes,
                    'due_date'=>$rem->due_date,
                    'done'=>$rem->done,
                    'remind'=>$this->super_model->select_column_where("users","fullname","user_id",$rem->user_id),
                    'type'=>'manual',
                    'source'=>''
                );
            }
        } else {
            $data=array();
        }

        $count = $this->super_model->count_rows("to_do_today");
        if($count!=0){
            foreach($this->super_model->select_all_order_by("to_do_today","due_date","ASC") AS $todo){
                $data['todo'][] = array(
                    'todo_id'=>$todo->todo_id,
                    'notes'=>$todo->notes,
                    'due_date'=>$todo->due_date,
                    'done'=>$todo->done,
                    'remind'=>$this->super_model->select_column_where("users","fullname","user_id",$todo->user_id),
                    'type'=>'manual',
                    'source'=>''
                );
            }
        } else {
            $data=array();
        }

        foreach($this->super_model->select_row_where("po_head", "revised", "1") AS $td){
            $today = date('Y-m-d');
            $data['todo'][] =  array(
                'todo_id'=>$td->po_id,
                'notes'=>'Follow up revision approval of PO '.$td->po_no."-".COMPANY,
                'due_date'=>$today,
                'done'=>'',
                'remind'=>'',
                'type'=>'auto',
                'source'=>'po'
            );
        }

        $count_calendar = $this->super_model->count_custom_where("pr_calendar","ver_date_needed BETWEEN '$filter_date_from' AND '$filter_date_to' AND estimated_price!='0' ORDER BY ver_date_needed DESC");
        if($count_calendar!=0){
            foreach($this->super_model->select_custom_where("pr_calendar","ver_date_needed BETWEEN '$filter_date_from' AND '$filter_date_to' AND estimated_price!='0' ORDER BY ver_date_needed DESC") AS $ca){
                //$estimated_price = $this->super_model->select_column_custom_where('pr_details','estimated_price',"pr_details_id='$ca->pr_details_id'");
                $quantity=$this->super_model->select_column_where("pr_details","quantity","pr_details_id",$ca->pr_details_id);
                $total_ep = $quantity * $ca->estimated_price;
                $total_array[] = $total_ep;
                $total_disp = array_sum($total_array);
                $data['total_disp']=$total_disp;
                $data['filt']=$filter_date_from." - ".$filter_date_to;
                $data['filter_date_from']=$filter_date_from;
                $data['filter_date_to']=$filter_date_to;
                $po_id = $this->super_model->select_column_row_order_limit2("po_id","po_items","pr_details_id", $ca->pr_details_id, "po_id", "DESC", "1");
                $served=  $this->super_model->select_column_where('po_head', 'served', 'po_id', $po_id);
                $data['dash_calendar'][] =  array(
                    'ver_date_needed'=>$ca->ver_date_needed,
                    'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$ca->pr_id),
                    'description'=>$this->super_model->select_column_where("pr_details","item_description","pr_details_id",$ca->pr_details_id),
                    'quantity'=>$quantity,
                    'estimated_price'=>$ca->estimated_price,
                    'total_ep'=>$total_ep,
                    'served'=>$served

                );
            }
        }else{
            $data['filt']=$filter_date_from." - ".$filter_date_to;
            $data['filter_date_from']=$filter_date_from;
            $data['filter_date_to']=$filter_date_to;
            $data['dash_calendar']=array();
            $data['total_disp']=0.00;
        }


        foreach($this->super_model->custom_query("SELECT ph.pr_id, ph.pr_no, pd.item_description, pd.pr_details_id, pd.date_needed, pd.quantity FROM pr_head ph INNER JOIN pr_details pd ON ph.pr_id = pd.pr_id WHERE saved='1' AND pd.cancelled = '0' AND ph.cancelled = '0'") AS $pr){

            $current_date= date('Y-m-d');
            $diff= $this-> dateDifference($current_date , $pr->date_needed , $differenceFormat = '%a' );

            $po = $this->super_model->count_custom_query("SELECT ph.po_id FROM po_head ph INNER JOIN po_pr pr ON ph.po_id = pr.po_id INNER JOIN po_items pi ON ph.po_id=pi.po_id WHERE ph.cancelled='0' AND pr.pr_id = '$pr->pr_id' AND pi.pr_details_id = '$pr->pr_details_id'");

            $po_qty = $this->super_model->select_sum_join("quantity","po_head","po_items", "po_head.cancelled='0' AND po_items.pr_id = '$pr->pr_id' AND po_items.pr_details_id = '$pr->pr_details_id'","po_id");


            if(($po==0 && ($diff>=1 && $diff<=7)) || ($po_qty !=$pr->quantity && ($diff>=1 && $diff<=7)) ){
                if($po_qty !=$pr->quantity){
                    $bal = $pr->quantity-$po_qty;
                    $rem = 'Unserved: '.$bal;
                }
                $reminder = 'Process PO for PR No.: '. $pr->pr_no. " - " . $pr->item_description . ", ".$rem;
                $due = date('M j, Y', strtotime($pr->date_needed));
                $data['reminder'][]=array(
                    'reminder_id'=>$pr->pr_id,
                    'notes'=>$reminder,
                    'due_date'=>$due,
                    'done'=>'',
                    'remind'=>'',
                    'type'=>'auto',
                    'source'=>'pr'
                );
            }


            if(($po==0 && $diff<=0) || ($po_qty !=$pr->quantity && $diff<=0) ){
                if($po_qty !=$pr->quantity){
                    $bal = $pr->quantity-$po_qty;
                    $rem = 'Unserved: '.$bal;
                }
                $reminder = 'Process PO for PR No.: '. $pr->pr_no. " - " . $pr->item_description . ", ".$rem;
                $due = date('M j, Y', strtotime($pr->date_needed));
                $data['todo'][]=array(
                    'todo_id'=>$pr->pr_id,
                    'notes'=>$reminder,
                    'due_date'=>$due,
                    'done'=>'',
                    'remind'=>'',
                    'type'=>'auto',
                    'source'=>'pr'
                );
            }
        
        }

        foreach($this->super_model->custom_query("SELECT quotation_date, vendor_id, rfq_no, rfq_id FROM rfq_head WHERE saved='1' AND cancelled = '0' AND completed = '0' GROUP BY rfq_id") AS $rfq){

            $rfq_diff= $this-> dateDifference($current_date , $rfq->quotation_date , $differenceFormat = '%a' );

            if($po==0 && ($rfq_diff>=1 && $rfq_diff<=7)){
                $reminder = 'Follow up RFQ No. '. $rfq->rfq_no. " with " . $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$rfq->vendor_id);
                $due = date('M j, Y', strtotime($rfq->quotation_date));
                $data['reminder'][]=array(
                    'reminder_id'=>$rfq->rfq_id,
                    'notes'=>$reminder,
                    'due_date'=>$due,
                    'done'=>'',
                    'remind'=>'',
                    'type'=>'auto',
                    'source'=>'rfq'
                );
            }

             if($po==0 && $rfq_diff<=0){
                $reminder = 'Follow up RFQ No. '. $rfq->rfq_no. " with " . $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$rfq->vendor_id);
                $due = date('M j, Y', strtotime($rfq->quotation_date));
                $data['todo'][]=array(
                    'todo_id'=>$rfq->rfq_id,
                    'notes'=>$reminder,
                    'due_date'=>$due,
                    'done'=>'',
                    'remind'=>'',
                    'type'=>'auto',
                    'source'=>'rfq'
                );
            }
        }      
        $this->load->view('masterfile/dashboard',$data);
        $this->load->view('template/footer');
    }
}

?>