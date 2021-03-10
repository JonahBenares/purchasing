<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jo extends CI_Controller {

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

    public function jo_list(){  
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        foreach($this->super_model->select_custom_where("jo_head", "cancelled='0' ORDER BY date_prepared DESC") AS $head){
            $data['head'][]=array(
                'jo_id'=>$head->jo_id,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'date'=>$head->date_prepared,
                'date_needed'=>$head->date_needed,
                'cenjo_no'=>$head->cenpri_jo_no,
                'jo_no'=>$head->jo_no,
                'project_title'=>$head->project_title,
                'revised'=>$head->revised,
                'saved'=>$head->saved,
                'revision_no'=>$head->revision_no,
            );
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jo/jo_list', $data);
        $this->load->view('template/footer');
    }

    public function cancelled_jo(){  
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        foreach($this->super_model->select_custom_where("jo_head", "cancelled='1' ORDER BY date_prepared DESC") AS $head){
            $data['head'][]=array(
                'jo_id'=>$head->jo_id,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'date'=>$head->date_prepared,
                'date_needed'=>$head->date_needed,
                'cenjo_no'=>$head->cenpri_jo_no,
                'jo_no'=>$head->jo_no,
                'project_title'=>$head->project_title,
                'revised'=>$head->revised,
                'revision_no'=>$head->revision_no,
                'cancelled_date'=>$head->cancelled_date,
                'cancelled_reason'=>$head->cancelled_reason,
            );
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jo/cancelled_jo', $data);
        $this->load->view('template/footer');
    }

    public function cancel_jo(){
        $jo_id=$this->input->post('jo_id');
        $reason=$this->input->post('reason');
        $create = date('Y-m-d H:i:s');

        $data = array(
            'cancelled'=>1,
            'cancelled_by'=>$_SESSION['user_id'],
            'cancelled_reason'=>$reason,
            'cancelled_date'=>$create
        );

        if($this->super_model->update_where("jo_head", $data, "jo_id", $jo_id)){
            redirect(base_url().'jo/jo_list', 'refresh');
        }
    }

    public function job_order_saved_r(){  
        $jo_id = $this->uri->segment(3);
        $revised_no = $this->uri->segment(4);
        $data['jo_id'] = $jo_id;
        $this->load->view('template/header');
        $data['cancelled']='';
        foreach($this->super_model->select_custom_where("jo_head_revised", "jo_id='$jo_id' AND revision_no = '$revised_no'") AS $head){
            $subtotal = ($head->total_cost + $head->vat_amount);
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $head->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $head->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['jo_no']= $head->jo_no;
            $data['project_title']= $head->project_title;
            $data['date_prepared']= $head->date_prepared;
            $data['date_needed']= $head->date_needed;
            $data['start_of_work']= $head->start_of_work;
            $data['work_completion']= $head->work_completion;
            $data['discount_percent']= $head->discount_percent;
            $data['discount_amount']= $head->discount_amount;
            $data['vat_percent']= $head->vat_percent;
            $data['subtotal']= $subtotal;
            $data['vat_amount']= $head->vat_amount;
            $data['total_cost']= $head->total_cost;
            $data['grand_total']= $head->grand_total;
            $data['revision_no']=$head->revision_no;
            $data['conforme']= $head->conforme;
            $data['verified_by']= $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->verified_by);
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->approved_by);
            $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->recommended_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->checked_by);
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->prepared_by);
            $data['cancelled']=$head->cancelled;
        }   

        $data['details'] = $this->super_model->select_custom_where("jo_details_revised", "jo_id='$jo_id' AND revision_no = '$revised_no' ORDER BY jo_details_id ASC");
        $data['terms'] = $this->super_model->select_custom_where("jo_terms_revised", "jo_id='$jo_id' AND revision_no = '$revised_no'");
        $this->load->view('jo/job_order_saved_r',$data);
        $this->load->view('template/footer');
    }

    public function view_history(){
        $this->load->view('template/header'); 
        $jo_id=$this->uri->segment(3);
        $cenjo_no=str_replace("%20", " ", $this->uri->segment(4));
        $jo_no=str_replace("%20", " ", $this->uri->segment(5));
        $data['jo_id']=$jo_id;
        $data['cenjo_no']=$cenjo_no;
        $data['jo_no']=$jo_no;

        $row = $this->super_model->count_rows_where("jo_head_revised", "jo_id",$jo_id);
        if($row!=0){
            foreach($this->super_model->select_custom_where("jo_head_revised", "jo_id = '$jo_id'") AS $rev){
                $data['revise'][]=array(
                    'jo_id'=>$jo_id,
                    'cenjo_no'=>$rev->cenpri_jo_no,
                    'jo_no'=>$rev->jo_no,
                    'revised_date'=>$rev->revised_date,
                    'revision_no'=>$rev->revision_no,
                );
            }
        }else {
            $data['revise']=array();
        }     
        $this->load->view('jo/view_history',$data);
        $this->load->view('template/footer');
    }

    public function getJoNo(){
        $year = $this->input->post('year');
        $rows_jo = $this->super_model->count_rows_where("jo_series", "year", $year);
        if($rows_jo==0){
            $jo_no='JO '.$year."-01";
        } else {
            $max = $this->super_model->get_max_where("jo_series", "series","year='$year'");
            $next= $max+1;
            $jo_no = 'JO '.$year."-".$next;
        }
        $return = array('jo_no' => $jo_no);
        echo json_encode($return);

    }

    public function job_order(){  
        $joid = $this->uri->segment(3);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $data['jo_id']=$joid;
        foreach($this->super_model->select_row_where("jo_head", "jo_id", $joid) AS $head){
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $head->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $head->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['jo_no']= $head->jo_no;
            $data['project_title']= $head->project_title;
            $data['date_prepared']= $head->date_prepared;
            $data['date_needed']= $head->date_needed;
            $data['start_of_work']= $head->start_of_work;
            $data['work_completion']= $head->work_completion;
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->prepared_by);

        }   


        $data['details'] = $this->super_model->select_row_where("jo_details", "jo_id", $joid);
        $data['terms'] = $this->super_model->select_row_where("jo_terms", "jo_id", $joid);
        $this->load->view('template/header');
        $this->load->view('jo/job_order',$data);
        $this->load->view('template/footer');
    }

    public function create_jo(){  
        $rows_jo = $this->super_model->count_rows("jo_head");
        if($rows_jo==0){
            $jo_id=1;
        } else {
            $max = $this->super_model->get_max("jo_head", "jo_id");
            $jo_id = $max+1;
        }

        $date_prepared = date('Y-m-d', strtotime($this->input->post('date_prepared')));
        $work_start = date('Y-m-d',strtotime($this->input->post('work_start')));
        $work_completion = date('Y-m-d', strtotime($this->input->post('work_completion')));
        $exp = explode("-",$date_prepared);
        $year = $exp[0];

        $rows_jo = $this->super_model->count_rows_where("jo_series", "year", $year);
        if($rows_jo==0){
            $jo_no='JO '.$year."-01";
        } else {
            $max = $this->super_model->get_max_where("jo_series", "series","year='$year'");
            $next= $max+1;
            $jo_no = 'JO '.$year."-".$next;
        }

        $data_jo = array(
            'jo_id'=>$jo_id,
            'vendor_id'=>$this->input->post('vendor'),
            'cenpri_jo_no'=>$this->input->post('cenjo_no'),
            'jo_no'=>$jo_no,
            'project_title'=>$this->input->post('project_title'),
            'date_needed'=>$this->input->post('date_needed'),
            'date_prepared'=>$date_prepared,
            'start_of_work'=>$work_start,
            'work_completion'=>$work_completion,
            'prepared_by'=>$_SESSION['user_id'],  
        );

        if($this->super_model->insert_into("jo_head", $data_jo)){
            $strings = str_replace(' ', '-', $jo_no);
            $jo = explode('-',$strings);
            $years=$jo[1];
            $series=$jo[2];
            $data_series = array(
                'year'=>$years,
                'series'=>$series,
            );
            $this->super_model->insert_into("jo_series", $data_series);
            redirect(base_url().'jo/job_order/'.$jo_id);
        }
    }

    public function create_jo_details(){
        $jo_id = $this->input->post('jo_id');
        $total_cost = $this->input->post('quantity') * $this->input->post('unit_cost');
            //$cost=number_format($total_cost,2);
         //echo $this->input->post('quantity') ."*". $this->input->post('unit_cost') ." = " .  $total_cost;
        $data = array(
            'jo_id'=>$jo_id,
            'scope_of_work'=>$this->input->post('scope'),
            'quantity'=>$this->input->post('quantity'),
            'uom'=>$this->input->post('uom'),
            'unit_cost'=>$this->input->post('unit_cost'),
            'total_cost'=>$total_cost
        );
        //print_r($data);
        if($this->super_model->insert_into("jo_details", $data)){
            redirect(base_url().'jo/job_order/'.$jo_id, 'refresh');
        }
    }

    public function create_jo_details_temp(){
        $jo_id = $this->input->post('jo_id');
        $total_cost = $this->input->post('quantity') * $this->input->post('unit_cost');
        $data = array(
            'jo_id'=>$jo_id,
            'scope_of_work'=>$this->input->post('scope'),
            'quantity'=>$this->input->post('quantity'),
            'uom'=>$this->input->post('uom'),
            'unit_cost'=>$this->input->post('unit_cost'),
            'total_cost'=>$total_cost
        );
        if($this->super_model->insert_into("jo_details", $data)){
            redirect(base_url().'jo/job_order_rev/'.$jo_id, 'refresh');
        }
    }

    public function create_jo_terms_temp(){
        $jo_id = $this->input->post('jo_id');
       
        $data = array(
            'jo_id'=>$jo_id,
            'terms'=>$this->input->post('terms'),
        );
        if($this->super_model->insert_into("jo_terms", $data)){
            redirect(base_url().'jo/job_order_rev/'.$jo_id, 'refresh');
        }
    }

    public function create_jo_terms(){
        $jo_id = $this->input->post('jo_id');
       
        $data = array(
            'jo_id'=>$jo_id,
            'terms'=>$this->input->post('terms'),
        );
        if($this->super_model->insert_into("jo_terms", $data)){
            redirect(base_url().'jo/job_order/'.$jo_id, 'refresh');
        }
    }

    public function save_jo(){
        $jo_id = $this->input->post('jo_id');
        $data = array(
            'total_cost'=>$this->input->post('sum_cost'),
            'vat_percent'=>$this->input->post('vat_percent'),
            'vat_amount'=>$this->input->post('vat_amount'),
           /* 'discount_percent'=>$this->input->post('less_percent'),*/
            'discount_amount'=>$this->input->post('less_amount'),
            'grand_total'=>$this->input->post('net'),
            'conforme'=>$this->input->post('conforme'),
            'verified_by'=>$this->input->post('verified_by'),
            'approved_by'=>$this->input->post('approved_by'),
            'recommended_by'=>$this->input->post('recommended_by'),
            'checked_by'=>$this->input->post('checked_by'),
            'saved'=>1
        );

        $date_format = date("Y");
        $rows_dr = $this->super_model->count_rows("jo_dr");
        if($rows_dr==0){
            $dr_no= "DR ".$date_format."-01";
        } else {
            $maxs = $this->super_model->get_max("jo_dr", "series");
            $next = $maxs+1;
            $nxt = str_pad($next, 2, "0", STR_PAD_LEFT);
            $dr_no = "DR ".$date_format."-".$nxt;
        }

        $dr_det=explode("-", $dr_no);
        $dr_prefix=$dr_det[0];
        $series = $dr_det[1];
        $dr = array(
            'jo_id'=>$jo_id,
            'year'=>$dr_prefix,
            'series'=>$series,
            'dr_date'=>$this->super_model->select_column_where('jo_head', 'date_prepared', 'jo_id', $jo_id),
        );

        $this->super_model->insert_into("jo_dr", $dr);

        $rows_ar = $this->super_model->count_rows("jo_ar");
        if($rows_ar==0){
            $ar_no= "AR ".$date_format."-01";
        } else {
            $max = $this->super_model->get_max("jo_ar", "series");
            $nexts = $max+1;
            $nxts = str_pad($nexts, 2, "0", STR_PAD_LEFT);
            $ar_no = "AR ".$date_format."-".$nxts;
        }

        $ar_det=explode("-", $ar_no);
        $ar_prefix=$ar_det[0];
        $series = $ar_det[1];
        $ar = array(
            'jo_id'=>$jo_id,
            'year'=>$ar_prefix,
            'series'=>$series,
            'ar_date'=>$this->super_model->select_column_where('jo_head', 'date_prepared', 'jo_id', $jo_id),
        );

        $this->super_model->insert_into("jo_ar", $ar);


        if($this->super_model->update_where("jo_head", $data, "jo_id", $jo_id)){
                redirect(base_url().'jo/job_order_saved/'.$jo_id);
        }
    }

    public function getVendorInformation(){

        $vendor = $this->input->post('vendor');
        $address= $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $vendor);
        $contact_person= $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $vendor);
        $phone= $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $vendor);
        $fax= $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $vendor);

        
        $return = array('address' => $address, 'phone' => $phone, 'contact_person' => $contact_person, 'fax' => $fax);
        echo json_encode($return);
    
    }
    public function job_order_saved(){  
        $jo_id = $this->uri->segment(3);
        $data['jo_id'] = $jo_id;
        $this->load->view('template/header');
        $data['cancelled']='';
        foreach($this->super_model->select_row_where("jo_head", "jo_id", $jo_id) AS $head){
            $subtotal = ($head->total_cost + $head->vat_amount);
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $head->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $head->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['jo_no']= $head->jo_no;
            $data['project_title']= $head->project_title;
            $data['date_prepared']= $head->date_prepared;
            $data['date_needed']= $head->date_needed;
            $data['start_of_work']= $head->start_of_work;
            $data['work_completion']= $head->work_completion;
            $data['discount_percent']= $head->discount_percent;
            $data['discount_amount']= $head->discount_amount;
            $data['vat_percent']= $head->vat_percent;
            $data['subtotal']= $subtotal;
            $data['vat_amount']= $head->vat_amount;
            $data['total_cost']= $head->total_cost;
            $data['grand_total']= $head->grand_total;
            $data['revision_no']=$head->revision_no;
            $data['conforme']= $head->conforme;
            $data['verified_by']= $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->verified_by);
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->approved_by);
            $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->recommended_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->checked_by);
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->prepared_by);
            $data['cancelled']=$head->cancelled;
        }   

        $data['details'] = $this->super_model->select_row_where("jo_details", "jo_id", $jo_id);
        $data['terms'] = $this->super_model->select_row_where("jo_terms", "jo_id", $jo_id);
        $this->load->view('jo/job_order_saved',$data);
        $this->load->view('template/footer');
    }

    public function job_order_rev(){  
        $jo_id = $this->uri->segment(3);
        $data['jo_id'] = $jo_id;
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('template/header');
        $data['cancelled']='';
        foreach($this->super_model->select_custom_where("jo_head", "jo_id='$jo_id' AND revised = '0'") AS $head){
            $data['vendor_id'] = $head->vendor_id;
            $data['revised'] = $head->revised;
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $head->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $head->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['jo_no']= $head->jo_no;
            $data['project_title']= $head->project_title;
            $data['date_prepared']= $head->date_prepared;
            $data['date_needed']= $head->date_needed;
            $data['start_of_work']= $head->start_of_work;
            $data['work_completion']= $head->work_completion;
            $data['discount_percent']= $head->discount_percent;
            $data['discount_amount']= $head->discount_amount;
            $data['vat_percent']= $head->vat_percent;
            $data['vat_amount']= $head->vat_amount;
            $data['total_cost']= $head->total_cost;
            $data['grand_total']= $head->grand_total;
            $data['conforme']= $head->conforme;
            $data['verified_by']= $head->verified_by;
            $data['cancelled']= $head->cancelled;
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->approved_by);
            $data['approved_id'] = $head->approved_by;
            $data['recommended_id'] = $head->recommended_by;
             $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->recommended_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->checked_by);
            $data['checked_id'] = $head->checked_by;
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->prepared_by);
        }

        foreach($this->super_model->select_custom_where("jo_head_temp", "jo_id='$jo_id' AND revised = '1'") AS $headtemp){
            $data['vendor_id'] = $headtemp->vendor_id;
            $data['revised'] = $headtemp->revised;
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $headtemp->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $headtemp->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $headtemp->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $headtemp->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $headtemp->vendor_id);
            $data['cenjo_no']= $headtemp->cenpri_jo_no;
            $data['jo_no']= $headtemp->jo_no;
            $data['project_title']= $headtemp->project_title;
            $data['date_prepared']= $headtemp->date_prepared;
            $data['date_needed']= $headtemp->date_needed;
            $data['start_of_work']= $headtemp->start_of_work;
            $data['work_completion']= $headtemp->work_completion;
            $data['discount_percent']= $headtemp->discount_percent;
            $data['discount_amount']= $headtemp->discount_amount;
            $data['discount_percent']= $headtemp->discount_percent;
            $data['discount_amount']= $headtemp->discount_amount;
             $data['vat_percent']= $headtemp->vat_percent;
            $data['vat_amount']= $headtemp->vat_amount;
            $data['total_cost']= $headtemp->total_cost;
            $data['grand_total']= $headtemp->grand_total;
            $data['conforme']= $headtemp->conforme;
            $data['cancelled']= $headtemp->cancelled;
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $headtemp->approved_by);
            $data['approved_id'] = $headtemp->approved_by;
            $data['recommended_id'] = $headtemp->recommended_by;
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $headtemp->checked_by);
             $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $headtemp->recommended_by);
            $data['checked_id'] = $headtemp->checked_by;
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $headtemp->prepared_by);
        }   

        $data['details'] = $this->super_model->select_row_where("jo_details", "jo_id", $jo_id);
        $data['details_temp'] = $this->super_model->select_row_where("jo_details_temp", "jo_id", $jo_id);
        $data['terms'] = $this->super_model->select_row_where("jo_terms", "jo_id", $jo_id);
        $data['terms_temp'] = $this->super_model->select_row_where("jo_terms_temp", "jo_id", $jo_id);
        $this->load->view('jo/job_order_rev',$data);
        $this->load->view('template/footer');
    }

    public function delete_scope(){
        $jo_details_id = $this->uri->segment(3);
        $jo_id = $this->uri->segment(4);
        foreach($this->super_model->select_row_where("jo_details","jo_details_id",$jo_details_id) AS $jodets){
            $data_details = array(
                "jo_details_id"=>$jodets->jo_details_id,
                "jo_id"=>$jodets->jo_id,
                "quantity"=>$jodets->quantity,
                "unit_cost"=>$jodets->unit_cost,
                "uom"=>$jodets->uom,
                "total_cost"=>$jodets->total_cost,
                "scope_of_work"=>$jodets->scope_of_work,
                "revision_no"=>$jodets->revision_no,
            );
            if($this->super_model->insert_into("jo_details_revised", $data_details)){
                $this->super_model->delete_where('jo_details', 'jo_details_id', $jo_details_id);
                echo "<script>alert('Succesfully Deleted'); 
                    window.location ='".base_url()."jo/job_order_rev/".$jo_id."'; </script>";
            }
        }
    }

    public function job_coc(){  
        $jo_id = $this->uri->segment(3);
        $data['jo_id'] = $jo_id;
        $this->load->view('template/header');
        foreach($this->super_model->select_row_where("jo_head", "jo_id", $jo_id) AS $head){
            $subtotal = ($head->total_cost + $head->vat_amount);
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $head->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $head->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['jo_no']= $head->jo_no;
            $data['project_title']= $head->project_title;
            $data['date_prepared']= $head->date_prepared;
            $data['date_needed']= $head->date_needed;
            $data['start_of_work']= $head->start_of_work;
            $data['work_completion']= $head->work_completion;
             $data['discount_percent']= $head->discount_percent;
            $data['discount_amount']= $head->discount_amount;
            $data['vat_percent']= $head->vat_percent;
            $data['subtotal']= $subtotal;
            $data['vat_amount']= $head->vat_amount;
            $data['total_cost']= $head->total_cost;
            $data['grand_total']= $head->grand_total;
            $data['conforme']= $head->conforme;
            $data['verified_by']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->verified_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->checked_by);
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->approved_by);
            $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->recommended_by);
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->prepared_by);
            $data['cancelled']=$head->cancelled;
        }   

        $data['details'] = $this->super_model->select_row_where("jo_details", "jo_id", $jo_id);
        $data['terms'] = $this->super_model->select_row_where("jo_terms", "jo_id", $jo_id);
        $this->load->view('jo/job_coc',$data);
        $this->load->view('template/footer');
    }

    public function save_change_order(){
        $jo_id = $this->input->post('jo_id');
        foreach($this->super_model->select_row_where("jo_head","jo_id",$jo_id) AS $johead){
            $data_details = array(
                "jo_id"=>$johead->jo_id,
                "vendor_id"=>$this->input->post('vendor'),
                "jo_no"=>$this->input->post('jo_no'),
                "date_needed"=>$this->input->post('date_needed'),
                "work_completion"=>$this->input->post('work_completion'),
                "date_prepared"=>$this->input->post('date_prepared'),
                "cenpri_jo_no"=>$this->input->post('cenjo_no'),
                "start_of_work"=>$this->input->post('start_of_work'),
                "project_title"=>$this->input->post('project_title'),
                "conforme"=>$this->input->post('conforme'),
                "revised"=>1,
                "saved"=>$johead->saved,
                "checked_by"=>$this->input->post('checked_by'),
                "verified_by"=>$this->input->post('verified_by'),
                "approved_by"=>$this->input->post('approved_by'),
                "recommended_by"=>$this->input->post('recommended_by'),
                "prepared_by"=>$johead->prepared_by,
                "total_cost"=>$this->input->post('sum_cost'),
                /*"discount_percent"=>$johead->discount_percent,*/
                "vat_percent"=>$this->input->post('vat_percent'),
                "vat_amount"=>$this->input->post('vat_amount'),
                "discount_amount"=>$this->input->post('less_amount'),
                "grand_total"=>$this->input->post('net'),
                "cancelled"=>$johead->cancelled,
                "cancelled_by"=>$johead->cancelled_by,
                "cancelled_reason"=>$johead->cancelled_reason,
                "cancelled_date"=>$johead->cancelled_date,
            );
            $this->super_model->insert_into("jo_head_temp", $data_details);
        }

        $x=1;
        foreach($this->super_model->select_row_where("jo_details","jo_id",$jo_id) AS $jodets){
            //if($this->input->post('quantity'.$x)!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $data_details = array(
                    "jo_details_id"=>$jodets->jo_details_id,
                    "jo_id"=>$jodets->jo_id,
                    "quantity"=>$this->input->post('quantity'.$x),
                    "unit_cost"=>$price,
                    "uom"=>$this->input->post('uom'.$x),
                    "total_cost"=>$amount,
                    "scope_of_work"=>$this->input->post('scope_of_work'.$x),
                );
                $this->super_model->insert_into("jo_details_temp", $data_details);
            //}
                
            $x++;
        }

        $y=1;
        foreach($this->super_model->select_row_where("jo_terms","jo_id",$jo_id) AS $jotc){
            $data_tci = array(
                "jo_terms_id"=>$jotc->jo_terms_id,
                "jo_id"=>$jo_id,
                "terms"=>$this->input->post('termsc'.$y),
            );
            $this->super_model->insert_into("jo_terms_temp", $data_tci);
            $y++;
        }

        $data_head = array(
            'revised'=>1
        );

        if($this->super_model->update_where("jo_head", $data_head, "jo_id", $jo_id)){
            //redirect(base_url().'jo/job_order_rev/'.$jo_id);
            $max_revision = $this->super_model->get_max_where("jo_head", "revision_no","jo_id = '$jo_id'");
            $revision_no = $max_revision+1;
            $revised_date = date("Y-m-d");
            foreach($this->super_model->select_row_where("jo_head","jo_id",$jo_id) AS $joh){
                $data_joh=array(
                    "jo_id"=>$joh->jo_id,
                    "vendor_id"=>$joh->vendor_id,
                    "jo_no"=>$joh->jo_no,
                    "date_needed"=>$joh->date_needed,
                    "work_completion"=>$joh->work_completion,
                    "date_prepared"=>$joh->date_prepared,
                    "cenpri_jo_no"=>$joh->cenpri_jo_no,
                    "start_of_work"=>$joh->start_of_work,
                    "project_title"=>$joh->project_title,
                    "conforme"=>$joh->conforme,
                    "verified_by"=>$joh->verified_by,
                    "revised"=>$joh->revised,
                    "saved"=>$joh->saved,
                    "checked_by"=>$joh->checked_by,
                    "approved_by"=>$joh->approved_by,
                    "recommended_by"=>$joh->recommended_by,
                    "prepared_by"=>$joh->prepared_by,
                    "total_cost"=>$joh->total_cost,
                    "discount_percent"=>$joh->discount_percent,
                    "discount_amount"=>$joh->discount_amount,
                    "vat_percent"=>$joh->vat_percent,
                    "vat_amount"=>$joh->vat_amount,
                    "grand_total"=>$joh->grand_total,
                    "revision_no"=>$joh->revision_no,
                    "revised_date"=>$joh->revised_date,
                );
                $this->super_model->insert_into("jo_head_revised", $data_joh);
            }

            $data_head =array(
                'revision_no'=>$revision_no
            );
            $this->super_model->update_where("jo_head", $data_head, "jo_id", $jo_id);

            foreach($this->super_model->select_row_where("jo_head_temp","jo_id",$jo_id) AS $joht){
                $data_joht=array(
                    "vendor_id"=>$joht->vendor_id,
                    "jo_no"=>$joht->jo_no,
                    "date_needed"=>$joht->date_needed,
                    "work_completion"=>$joht->work_completion,
                    "date_prepared"=>$joht->date_prepared,
                    "cenpri_jo_no"=>$joht->cenpri_jo_no,
                    "start_of_work"=>$joht->start_of_work,
                    "project_title"=>$joht->project_title,
                    "conforme"=>$joht->conforme,
                    "verified_by"=>$joht->verified_by,
                    "revised"=>$joht->revised,
                    "saved"=>$joht->saved,
                    "checked_by"=>$joht->checked_by,
                    "approved_by"=>$joht->approved_by,
                    "prepared_by"=>$joht->prepared_by,
                    "recommended_by"=>$joht->recommended_by,
                    "total_cost"=>$joht->total_cost,
                    "discount_percent"=>$joht->discount_percent,
                    "discount_amount"=>$joht->discount_amount,
                     "vat_percent"=>$joht->vat_percent,
                    "vat_amount"=>$joht->vat_amount,
                    "grand_total"=>$joht->grand_total,
                    "revision_no"=>$revision_no,
                    "revised_date"=>$revised_date,
                );
                $this->super_model->update_where("jo_head", $data_joht, "jo_id", $joht->jo_id);
            }
            $this->super_model->delete_where("jo_head_temp", "jo_id", $jo_id);


            foreach($this->super_model->select_row_where("jo_details","jo_id",$jo_id) AS $jodets){
                $data_details = array(
                    "jo_details_id"=>$jodets->jo_details_id,
                    "jo_id"=>$jodets->jo_id,
                    "quantity"=>$jodets->quantity,
                    "unit_cost"=>$jodets->unit_cost,
                    "uom"=>$jodets->uom,
                    "total_cost"=>$jodets->total_cost,
                    "scope_of_work"=>$jodets->scope_of_work,
                    "revision_no"=>$jodets->revision_no,
                );
                $this->super_model->insert_into("jo_details_revised", $data_details);
            }

            $datadet =array(
                'revision_no'=>$revision_no
            );
            $this->super_model->update_where("jo_details", $datadet, "jo_id", $jo_id);

            foreach($this->super_model->select_row_where("jo_details_temp","jo_id",$jo_id) AS $jodetst){
                $data_detailst = array(
                    "jo_id"=>$jodetst->jo_id,
                    "quantity"=>$jodetst->quantity,
                    "unit_cost"=>$jodetst->unit_cost,
                    "uom"=>$jodetst->uom,
                    "total_cost"=>$jodetst->total_cost,
                    "scope_of_work"=>$jodetst->scope_of_work,
                    "revision_no"=>$revision_no,
                );
                $this->super_model->update_where("jo_details", $data_detailst, "jo_details_id", $jodetst->jo_details_id);
            }
            $this->super_model->delete_where("jo_details_temp", "jo_id", $jo_id);

            foreach($this->super_model->select_row_where("jo_terms","jo_id",$jo_id) AS $jotc){
                $data_tci = array(
                    "jo_terms_id"=>$jotc->jo_terms_id,
                    "jo_id"=>$jotc->jo_id,
                    "terms"=>$jotc->terms,
                    "revision_no"=>$jotc->revision_no,
                );
                $this->super_model->insert_into("jo_terms_revised", $data_tci);
            }

            foreach($this->super_model->select_row_where("jo_terms_temp","jo_id",$jo_id) AS $jotct){
                $data_tcit = array(
                    "jo_id"=>$jotct->jo_id,
                    "terms"=>$jotct->terms,
                    "revision_no"=>$revision_no,
                );
                $this->super_model->update_where("jo_terms", $data_tcit, "jo_terms_id", $jotct->jo_terms_id);
            }
            $this->super_model->delete_where("jo_terms_temp", "jo_id", $jo_id);

            $data_revs =array(
                'approve_rev_by'=>$this->input->post('approve_rev'),
                'approve_rev_date'=>$this->input->post('approve_date'),
                'revised'=>0,
                'revised_date'=>date("Y-m-d"),
                'revision_no'=>$revision_no
            );

            if($this->super_model->update_where("jo_head", $data_revs, "jo_id", $jo_id)){
                redirect(base_url().'jo/jo_list/', 'refresh');
            }

        }
    }

    public function approve_revision(){
        $jo_id = $this->input->post('jo_id');
        $max_revision = $this->super_model->get_max_where("jo_head", "revision_no","jo_id = '$jo_id'");
        $revision_no = $max_revision+1;
        $revised_date = date("Y-m-d");
        foreach($this->super_model->select_row_where("jo_head","jo_id",$jo_id) AS $joh){
            $data_joh=array(
                "jo_id"=>$joh->jo_id,
                "vendor_id"=>$joh->vendor_id,
                "jo_no"=>$joh->jo_no,
                "date_needed"=>$joh->date_needed,
                "work_completion"=>$joh->work_completion,
                "date_prepared"=>$joh->date_prepared,
                "cenpri_jo_no"=>$joh->cenpri_jo_no,
                "start_of_work"=>$joh->start_of_work,
                "project_title"=>$joh->project_title,
                "conforme"=>$joh->conforme,
                "verified_by"=>$joh->verified_by,
                "revised"=>$joh->revised,
                "saved"=>$joh->saved,
                "checked_by"=>$joh->checked_by,
                "approved_by"=>$joh->approved_by,
                "recommended_by"=>$joh->recommended_by,
                "prepared_by"=>$joh->prepared_by,
                "total_cost"=>$joh->total_cost,
                "discount_percent"=>$joh->discount_percent,
                "discount_amount"=>$joh->discount_amount,
                "vat_percent"=>$joh->vat_percent,
                "vat_amount"=>$joh->vat_amount,
                "grand_total"=>$joh->grand_total,
                "revision_no"=>$joh->revision_no,
                "revised_date"=>$joh->revised_date,
            );
            $this->super_model->insert_into("jo_head_revised", $data_joh);
        }

        $data_head =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("jo_head", $data_head, "jo_id", $jo_id);

        foreach($this->super_model->select_row_where("jo_head_temp","jo_id",$jo_id) AS $joht){
            $data_joht=array(
                "vendor_id"=>$joht->vendor_id,
                "jo_no"=>$joht->jo_no,
                "date_needed"=>$joht->date_needed,
                "work_completion"=>$joht->work_completion,
                "date_prepared"=>$joht->date_prepared,
                "cenpri_jo_no"=>$joht->cenpri_jo_no,
                "start_of_work"=>$joht->start_of_work,
                "project_title"=>$joht->project_title,
                "conforme"=>$joht->conforme,
                "verified_by"=>$joht->verified_by,
                "revised"=>$joht->revised,
                "saved"=>$joht->saved,
                "checked_by"=>$joht->checked_by,
                "approved_by"=>$joht->approved_by,
                "prepared_by"=>$joht->prepared_by,
                "recommended_by"=>$joht->recommended_by,
                "total_cost"=>$joht->total_cost,
                "discount_percent"=>$joht->discount_percent,
                "discount_amount"=>$joht->discount_amount,
                 "vat_percent"=>$joht->vat_percent,
                "vat_amount"=>$joht->vat_amount,
                "grand_total"=>$joht->grand_total,
                "revision_no"=>$revision_no,
                "revised_date"=>$revised_date,
            );
            $this->super_model->update_where("jo_head", $data_joht, "jo_id", $joht->jo_id);
        }
        $this->super_model->delete_where("jo_head_temp", "jo_id", $jo_id);


        foreach($this->super_model->select_row_where("jo_details","jo_id",$jo_id) AS $jodets){
            $data_details = array(
                "jo_details_id"=>$jodets->jo_details_id,
                "jo_id"=>$jodets->jo_id,
                "quantity"=>$jodets->quantity,
                "unit_cost"=>$jodets->unit_cost,
                "uom"=>$jodets->uom,
                "total_cost"=>$jodets->total_cost,
                "scope_of_work"=>$jodets->scope_of_work,
                "revision_no"=>$jodets->revision_no,
            );
            $this->super_model->insert_into("jo_details_revised", $data_details);
        }

        $datadet =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("jo_details", $datadet, "jo_id", $jo_id);

        foreach($this->super_model->select_row_where("jo_details_temp","jo_id",$jo_id) AS $jodetst){
            $data_detailst = array(
                "jo_id"=>$jodetst->jo_id,
                "quantity"=>$jodetst->quantity,
                "unit_cost"=>$jodetst->unit_cost,
                "uom"=>$jodetst->uom,
                "total_cost"=>$jodetst->total_cost,
                "scope_of_work"=>$jodetst->scope_of_work,
                "revision_no"=>$revision_no,
            );
            $this->super_model->update_where("jo_details", $data_detailst, "jo_details_id", $jodetst->jo_details_id);
        }
        $this->super_model->delete_where("jo_details_temp", "jo_id", $jo_id);

        foreach($this->super_model->select_row_where("jo_terms","jo_id",$jo_id) AS $jotc){
            $data_tci = array(
                "jo_terms_id"=>$jotc->jo_terms_id,
                "jo_id"=>$jotc->jo_id,
                "terms"=>$jotc->terms,
                "revision_no"=>$jotc->revision_no,
            );
            $this->super_model->insert_into("jo_terms_revised", $data_tci);
        }

        foreach($this->super_model->select_row_where("jo_terms_temp","jo_id",$jo_id) AS $jotct){
            $data_tcit = array(
                "jo_id"=>$jotct->jo_id,
                "terms"=>$jotct->terms,
                "revision_no"=>$revision_no,
            );
            $this->super_model->update_where("jo_terms", $data_tcit, "jo_terms_id", $jotct->jo_terms_id);
        }
        $this->super_model->delete_where("jo_terms_temp", "jo_id", $jo_id);

        $data_revs =array(
            'approve_rev_by'=>$this->input->post('approve_rev'),
            'approve_rev_date'=>$this->input->post('approve_date'),
            'revised'=>0,
            'revised_date'=>date("Y-m-d"),
            'revision_no'=>$revision_no
        );

        if($this->super_model->update_where("jo_head", $data_revs, "jo_id", $jo_id)){
            redirect(base_url().'jo/jo_list/', 'refresh');
        }
    }

    public function jo_rfd(){  
        $jo_id = $this->uri->segment(3);
        $data['jo_id'] = $jo_id;

        $data['rows_rfd'] = $this->super_model->select_count("jo_rfd","jo_id",$jo_id);
        $vendor_id= $this->super_model->select_column_where("jo_head", "vendor_id", "jo_id", $jo_id);
        $data['jo_no']= $this->super_model->select_column_where("jo_head", "jo_no", "jo_id", $jo_id);
        $data['cancelled']= $this->super_model->select_column_where("jo_head", "cancelled", "jo_id", $jo_id);
        $data['cenjo_no']= $this->super_model->select_column_where("jo_head", "cenpri_jo_no", "jo_id", $jo_id);
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);
        $data['total_cost']= $this->super_model->select_column_where("jo_head", "total_cost", "jo_id", $jo_id);
      /*  $data['disc_percent']= $this->super_model->select_column_where("jo_head", "discount_percent", "jo_id", $jo_id);*/
        $data['vat_amount']= $this->super_model->select_column_where("jo_head", "vat_amount", "jo_id", $jo_id);
        $data['vat_percent']= $this->super_model->select_column_where("jo_head", "vat_percent", "jo_id", $jo_id);
        $data['disc_amount']= $this->super_model->select_column_where("jo_head", "discount_amount", "jo_id", $jo_id);
        $data['grand_total']= $this->super_model->select_column_where("jo_head", "grand_total", "jo_id", $jo_id);
        $data['dr_no']= $this->super_model->select_column_where("jo_dr", "year", "jo_id", $jo_id) ."-".$this->super_model->select_column_where("jo_dr", "series", "jo_id", $jo_id);


         foreach($this->super_model->select_row_where('jo_details', 'jo_id', $jo_id) AS $details){
          
            $data['details'][]= array(
                'scope'=>$details->scope_of_work,
                'quantity'=>$details->quantity,
                'cost'=>$details->unit_cost,
                'total'=>$details->total_cost,
                'uom'=>$details->uom,
            );
        }

        foreach($this->super_model->select_row_where('jo_rfd', 'jo_id', $jo_id) AS $head){
            $data['company'] = $head->company;
            $data['pay_to'] = $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->pay_to);
            $data['check_name'] = $head->check_name;
            $data['cash_check'] = $head->cash_check;
            $data['bank_no'] = $head->bank_no;
            $data['apv_no'] = $head->apv_no;
            $data['rfd_date'] = $head->rfd_date;
            $data['due_date'] = $head->due_date;
            $data['check_due'] = $head->check_due;
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->user_id);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->checked_by);
            $data['endorsed'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->endorsed_by);
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->approved_by);
        }

        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('template/header');
        $this->load->view('jo/jo_rfd',$data);
        $this->load->view('template/footer');
    }


    public function save_jo_rfd(){
        $jo_id= $this->input->post('jo_id');
        $data = array(
            'jo_id'=>$jo_id,
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
            'user_id'=>$_SESSION['user_id'],
            'saved'=>1,
        );

         if($this->super_model->insert_into("jo_rfd", $data)){
            redirect(base_url().'jo/jo_rfd/'.$jo_id, 'refresh');
        }
    }


    public function jo_ac(){  
        $jo_id = $this->uri->segment(3);
        $data['jo_id'] = $jo_id;
        $this->load->view('template/header');
        $data['saved'] = $this->super_model->select_column_where("jo_ar", "saved", "jo_id", $jo_id);
        $data['cancelled'] = $this->super_model->select_column_where("jo_head", "cancelled", "jo_id", $jo_id);
        $data['delivered_to'] = $this->super_model->select_column_where("jo_ar", "delivered_to", "jo_id", $jo_id);
        $data['address'] = $this->super_model->select_column_where("jo_ar", "address", "jo_id", $jo_id);
        $data['requested_by'] = $this->super_model->select_column_where("jo_ar", "requested_by", "jo_id", $jo_id);
        $data['gatepass_no'] = $this->super_model->select_column_where("jo_ar", "gatepass_no", "jo_id", $jo_id);
        $year = $this->super_model->select_column_where("jo_ar", "year", "jo_id", $jo_id);
        $series = $this->super_model->select_column_where("jo_ar", "series", "jo_id", $jo_id);
        $data['ar_no']= $year."-".$series;
        $data['jo_head']=$this->super_model->select_row_where('jo_head', 'jo_id', $jo_id);
        foreach($this->super_model->select_row_where("jo_details","jo_id",$jo_id) AS $jd){
            $vendor_id = $this->super_model->select_column_where("jo_head","vendor_id","jo_id",$jo_id);
            $vendor = $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$vendor_id);
            $data['jo_det'][]=array(
                'supplier'=>$vendor,
                'scope_of_work'=>$jd->scope_of_work,
                'quantity'=>$jd->quantity,
                'uom'=>$jd->uom,
            );
        }
        $this->load->view('jo/jo_ac',$data);
        $this->load->view('template/footer');
    }

    public function save_ar(){
        $jo_id = $this->input->post('jo_id');
        $data = array(
            'delivered_to'=>$this->input->post('delivered_to'),
            'address'=>$this->input->post('address'),
            'requested_by'=>$this->input->post('requested_by'),
            'gatepass_no'=>$this->input->post('gatepass'),
            'saved'=>1
        );
        if($this->super_model->update_where("jo_ar", $data, "jo_id", $jo_id)){
            redirect(base_url().'jo/jo_ac/'.$jo_id);
        }
    }

    public function jo_dr(){  
        $jo_id = $this->uri->segment(3);
        $data['jo_id'] = $jo_id;
        $this->load->view('template/header');
        $data['saved'] = $this->super_model->select_column_where("jo_dr", "saved", "jo_id", $jo_id);
        $data['cancelled']= $this->super_model->select_column_where("jo_head", "cancelled", "jo_id", $jo_id);
        $data['delivered_to'] = $this->super_model->select_column_where("jo_dr", "delivered_to", "jo_id", $jo_id);
        $data['address'] = $this->super_model->select_column_where("jo_dr", "address", "jo_id", $jo_id);
        $data['requested_by'] = $this->super_model->select_column_where("jo_dr", "requested_by", "jo_id", $jo_id);
        $year = $this->super_model->select_column_where("jo_dr", "year", "jo_id", $jo_id);
        $series = $this->super_model->select_column_where("jo_dr", "series", "jo_id", $jo_id);
        $data['dr_no']= $year."-".$series;
        $data['jo_head']=$this->super_model->select_row_where('jo_head', 'jo_id', $jo_id);
        foreach($this->super_model->select_row_where("jo_details","jo_id",$jo_id) AS $jd){
            $vendor_id = $this->super_model->select_column_where("jo_head","vendor_id","jo_id",$jo_id);
            $vendor = $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$vendor_id);
            $data['jo_det'][]=array(
                'supplier'=>$vendor,
                'scope_of_work'=>$jd->scope_of_work,
                'quantity'=>$jd->quantity,
                'uom'=>$jd->uom,
            );
        }
        $this->load->view('jo/jo_dr', $data);
        $this->load->view('template/footer');
    }

    public function save_dr(){
        $jo_id = $this->input->post('jo_id');
        $data = array(
            'delivered_to'=>$this->input->post('delivered_to'),
            'address'=>$this->input->post('address'),
            'requested_by'=>$this->input->post('requested_by'),
            'saved'=>1
        );
        if($this->super_model->update_where("jo_dr", $data, "jo_id", $jo_id)){
                redirect(base_url().'jo/jo_dr/'.$jo_id);
        }
    }  
}

?>