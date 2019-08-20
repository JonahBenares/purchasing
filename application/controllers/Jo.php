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

        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jo/jo_list');
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
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
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
            'date_prepared'=>$date_prepared,
            'start_of_work'=>$work_start,
            'work_completion'=>$work_completion,
            'scope_of_work'=>$this->input->post('scope_of_work'),
            'quantity'=>$this->input->post('quantity'),
            'uom'=>$this->input->post('uom'),
            'unit_cost'=>$this->input->post('unit_cost'),
            'total_cost'=>$this->input->post('total_cost'),
            'discount_percent'=>$this->input->post('less_percent'),
            'discount_amount'=>$this->input->post('less_amount'),
            'grand_total'=>$this->input->post('net'),
            'jo_terms'=>$this->input->post('jo_terms'),
            'conforme'=>$this->input->post('conforme'),
            'prepared_by'=>$_SESSION['user_id'],
            'approved_by'=>$this->input->post('approved_by'),
            'project_title'=>$this->input->post('project_title'),
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
            redirect(base_url().'jo/job_order_saved/'.$jo_id, 'refresh');
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
        foreach($this->super_model->select_row_where("jo_head","jo_id",$jo_id) AS $s){
            $data['vendor']= $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$s->vendor_id);
            $data['address']= $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $s->vendor_id);
            $data['phone']= $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $s->vendor_id);
            $data['jo_head'][]=array( 
                'cenpri_jo_no'=>$s->cenpri_jo_no,
                'jo_no'=>$s->jo_no,
                'date_prepared'=>$s->date_prepared,
                'start_of_work'=>$s->start_of_work,
                'work_completion'=>$s->work_completion,
                'scope_of_work'=>$s->scope_of_work,
                'quantity'=>$s->quantity,
                'uom'=>$s->uom,
                'unit_cost'=>$s->unit_cost,
                'total_cost'=>$s->total_cost,
                'discount_percent'=>$s->discount_percent,
                'discount_amount'=>$s->discount_amount,
                'grand_total'=>$s->grand_total,
                'jo_terms'=>$s->jo_terms,
                'conforme'=>$s->conforme,
                'approved_by'=>$this->super_model->select_column_where("employees","employee_name","employee_id",$s->approved_by),
                'project_title'=>$s->project_title,
            );
        }
        $this->load->view('jo/job_order_saved',$data);
        $this->load->view('template/footer');
    }

    public function jo_rfd(){  
        $this->load->view('template/header');
        $this->load->view('jo/jo_rfd');
        $this->load->view('template/footer');
    }

    public function jo_ac(){  
        $this->load->view('template/header');
        $this->load->view('jo/jo_ac');
        $this->load->view('template/footer');
    }
    public function jo_dr(){  
        $this->load->view('template/header');
        $this->load->view('jo/jo_dr');
        $this->load->view('template/footer');
    }

   
}

?>