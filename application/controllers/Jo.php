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
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jo/jo_list', $data);
        $this->load->view('template/footer');
    }

    public function getJoNo(){
        $year = $this->input->post('year');
        $rows_jo = $this->super_model->count_rows_where("jo_series", "year", $year);
        if($rows_jo==0){
            $jo_no='JO '.$year."-1";
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
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['jo_no']= $head->jo_no;
            $data['project_title']= $head->project_title;
            $data['date_prepared']= $head->date_prepared;
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
            $jo_no='JO '.$year."-1";
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
        $data = array(
            'jo_id'=>$jo_id,
            'scope_of_work'=>$this->input->post('scope'),
            'quantity'=>$this->input->post('quantity'),
            'uom'=>$this->input->post('uom'),
            'unit_cost'=>$this->input->post('unit_cost'),
            'total_cost'=>$total_cost
        );
        if($this->super_model->insert_into("jo_details", $data)){
            redirect(base_url().'jo/job_order/'.$jo_id, 'refresh');
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
            'discount_percent'=>$this->input->post('less_percent'),
            'discount_amount'=>$this->input->post('less_amount'),
            'grand_total'=>$this->input->post('net'),
            'conforme'=>$this->input->post('conforme'),
            'approved_by'=>$this->input->post('approved_by'),
            'checked_by'=>$this->input->post('checked_by'),
            'saved'=>1
        );

        $date_format = date("Y");
        $rows_dr = $this->super_model->count_rows("jo_dr");
        if($rows_dr==0){
            $dr_no= "DR ".$date_format."-01";
        } else {
            $max = $this->super_model->get_max("jo_dr", "series");
            $dr_no = "DR ".$date_format."-".$max+1;
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
            $ar_no = "AR ".$date_format."-".$max+1;
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
        $phone= $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $vendor);

        
        $return = array('address' => $address, 'phone' => $phone);
        echo json_encode($return);
    
    }
    public function job_order_saved(){  
        $jo_id = $this->uri->segment(3);
        $data['jo_id'] = $jo_id;
        $this->load->view('template/header');
        foreach($this->super_model->select_row_where("jo_head", "jo_id", $jo_id) AS $head){
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $head->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['jo_no']= $head->jo_no;
            $data['project_title']= $head->project_title;
            $data['date_prepared']= $head->date_prepared;
            $data['start_of_work']= $head->start_of_work;
            $data['work_completion']= $head->work_completion;
            $data['discount_percent']= $head->discount_percent;
            $data['discount_amount']= $head->discount_amount;
            $data['total_cost']= $head->total_cost;
            $data['grand_total']= $head->grand_total;
            $data['conforme']= $head->conforme;
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->approved_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->checked_by);
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->prepared_by);
        }   

        $data['details'] = $this->super_model->select_row_where("jo_details", "jo_id", $jo_id);
        $data['terms'] = $this->super_model->select_row_where("jo_terms", "jo_id", $jo_id);
        $this->load->view('jo/job_order_saved',$data);
        $this->load->view('template/footer');
    }

    public function jo_rfd(){  
        $jo_id = $this->uri->segment(3);
        $data['jo_id'] = $jo_id;
        $data['rows_rfd'] = $this->super_model->select_count("jo_rfd","jo_id",$jo_id);
        $vendor_id= $this->super_model->select_column_where("jo_head", "vendor_id", "jo_id", $jo_id);
        $data['jo_no']= $this->super_model->select_column_where("jo_head", "jo_no", "jo_id", $jo_id);
        $data['cenjo_no']= $this->super_model->select_column_where("jo_head", "cenpri_jo_no", "jo_id", $jo_id);
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);
        $data['disc_percent']= $this->super_model->select_column_where("jo_head", "discount_percent", "jo_id", $jo_id);
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