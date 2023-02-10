<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jor extends CI_Controller {

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

    public function redirect_jod(){
                $rows_head = $this->super_model->count_rows("joi_head");
        if($rows_head==0){
            $joi_id=1;
        } else {
            $max = $this->super_model->get_max("joi_head", "joi_id");
            $joi_id = $max+1;
        }

/*        $rows_series = $this->super_model->count_rows("joi_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("joi_series", "series");
            $series = $max+1;
        }*/
        $year = date("Y",strtotime($this->input->post('joi_date')));
        $series_rows = $this->super_model->count_rows_where("joi_series","year",$year);
        if($series_rows==0){
            $series=1000;
        } else {
            $max_series=$this->super_model->get_max_where("joi_series", "series","year = '$year'");
            $series = $max_series+1;
        }


        // $year=date('Y');
        // $series_rows = $this->super_model->count_custom_where("joi_head","joi_date LIKE '%$year%'");
        // if($series_rows==0){
        //     $series=1000;
        // }else{
        //     $joi_max = $this->super_model->get_max_where("joi_head", "joi_no","joi_date LIKE '%$year%'");
        //     $joi_exp=explode("-", $joi_max);
        //     $series = $joi_exp[1]+1;
        // }

            $jo= $this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id',$this->input->post('jor_ids'));
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id',$this->input->post('jor_ids'));
            }
            $group_id = $this->input->post('group_id');
            $joi_no = "P".$jo_no."-".$series;
            $jor_id = $this->input->post('jor_ids');
            $data_details = array(
                'joi_id'=>$joi_id,
                'jor_id'=>$jor_id,
                'purpose'=>$this->super_model->select_column_where('jor_head', 'purpose', 'jor_id', $jor_id),
                'requestor'=>$this->super_model->select_column_where('jor_head', 'requested_by', 'jor_id', $jor_id),
            );
            $this->super_model->insert_into("joi_jor", $data_details);
            
            $data= array(
                'joi_id'=>$joi_id,
                'joi_date'=>$this->input->post('joi_date'),
                'joi_no'=>$joi_no,
                'vendor_id'=>$this->input->post('vendor'),
                'notes'=>$this->input->post('notes'),
                'cenpri_jo_no'=>$this->input->post('cenjo_no'),
                'date_needed'=>$this->input->post('date_needed'),
                'date_prepared'=>$this->input->post('date_prepared'),
                'start_of_work'=>$this->input->post('work_start'),
                'completion_date'=>$this->input->post('work_completion'),
                'project_title'=>$this->input->post('purpose'),
                'general_desc'=>$this->input->post('general_desc'),
                'joi_type'=>1,
                'user_id'=>$_SESSION['user_id'],
                'prepared_date'=>date("Y-m-d H:i:s"),
            );  

            $data_series = array(
                'series'=>$series,
                'year'=>$year
            );
            $this->super_model->insert_into("joi_series", $data_series);

          
            if($this->super_model->insert_into("joi_head", $data)){
                 redirect(base_url().'jod/jo_direct/'.$joi_id.'/'.$jor_id.'/'.$group_id);
            }
        }
        /*$rows_head = $this->super_model->count_rows("joi_head");
        if($rows_head==0){
            $joi_id=1;
        } else {
            $max = $this->super_model->get_max("joi_head", "joi_id");
            $joi_id = $max+1;
        }

        $rows_series = $this->super_model->count_rows("joi_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("joi_series", "series");
            $series = $max+1;
        }

        $jor_id = $this->input->post('jor_ids');
        $group_id = $this->input->post('group_id');
         $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $items->jor_id);
                    if($jo_no!=''){
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "jo_no", "jor_id = '$key[jor_id]' AND cancelled = '0'");
                    }else{
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "user_jo_no", "jor_id = '$key[jor_id]' AND cancelled = '0'");
                    }
        $joi_no = "P".$jor_no."-".$series;
       // $po_no = "POD-".$series;
        $data= array(
            'joi_id'=>$joi_id,
            'joi_date'=>$this->input->post('joi_date'),
            'joi_no'=>$joi_no,
            'vendor_id'=>$this->input->post('vendor'),
            'joi_type'=>1,
            'user_id'=>$_SESSION['user_id'],
            'prepared_date'=>date("Y-m-d H:i:s"),
        );  

        $data_series = array(
            'series'=>$series
        );
        $this->super_model->insert_into("joi_series", $data_series);

      
        if($this->super_model->insert_into("joi_head", $data)){
            foreach($this->super_model->select_row_where("jor_head","jor_id",$jor_id) AS $joi_jor){
                $data_jor = array(
                    'joi_id'=>$joi_id,
                    'jor_id'=>$jor_id,
                    'enduse'=>$joi_jor->enduse,
                    'purpose'=>$joi_jor->purpose,
                    'requestor'=>$joi_jor->requestor,
                );
                $this->super_model->insert_into("joi_jor", $data_jor);
            }
            redirect(base_url().'jod/jo_direct/'.$joi_id.'/'.$jor_id.'/'.$group_id);
        }
    }   */ 

    public function jor_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['jo_head']=$this->super_model->select_custom_where("jor_head","cancelled='0' AND completed='0' ORDER BY date_prepared ASC");
        $this->load->view('jor/jor_list',$data);
        $this->load->view('template/footer');
    }

    public function completed_jor_list(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $count = $this->super_model->count_custom_where("jor_head","completed='1'");
        if($count!=0){
            foreach($this->super_model->select_custom_where("jor_head","completed='1' ORDER BY date_completed ASC") AS $heads){
                $data['jor_completed'][] = array(
                    'jor_id'=>$heads->jor_id,
                    'jo_no'=>$heads->jo_no,
                    'user_jo_no'=>$heads->user_jo_no,
                    'date_prepared'=>$heads->date_prepared,
                    'jo_request'=>$heads->jo_request,
                    'urgency'=>$heads->urgency,
                    'date_imported'=>$heads->date_imported,
                    'department'=>$heads->department,
                    'completed_by'=> $this->super_model->select_column_where('users','fullname','user_id',$heads->completed_by),
                    'date_completed'=> $heads->date_completed,
                );
            }
        }else {
            $data['jor_completed']=array();
        }

        $this->load->view('jor/completed_jor_list',$data);
        $this->load->view('template/footer');
    }

    public function completed_jor(){
        $jor_id=$this->input->post('jor_id');
        $date=date('Y-m-d H:i:s');
        $data=array(
            'completed'=>1,
            'completed_by'=>$_SESSION['user_id'],
            'date_completed'=>$date,
        );
        
        if($this->super_model->update_where('jor_head', $data, 'jor_id', $jor_id)){
            foreach($this->super_model->select_custom_where('jor_items',"jor_id='$jor_id'") AS $jo){
                $data_jor=array(
                    'completed'=>1,
                );
                $this->super_model->update_where('jor_items', $data_jor, 'jor_items_id', $jo->jor_items_id);
            }
        }
    }

    public function createColumnsArray($end_column, $first_letters = ''){
        $columns = array();
        $length = strlen($end_column);
        $letters = range('A', 'Z');
        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;
            // Add the column to the final array.
            $columns[] = $column;
            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
            return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
                $new_columns = $this->createColumnsArray($end_column, $column);
                // Merge the new columns which were created with the final columns array.
                $columns = array_merge($columns, $new_columns);
            }
        }
        return $columns;
    }

    public function jor_request(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $jor_id=$this->uri->segment(3);
        $data['jor_id']=$this->uri->segment(3);
        $data['jo_head']=$this->super_model->select_row_where("jor_head","jor_id",$jor_id);
        $data['saved']=$this->super_model->select_column_where('jor_head','saved','jor_id',$jor_id);
        $data['jo_notes']=$this->super_model->select_row_where("jor_notes","jor_id",$jor_id);
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $data['cancelled']='';
            foreach($this->super_model->select_custom_where("jor_items", "jor_id='$jor_id'") AS $ji){
                $vendor='';
                $vendor_id='';
                foreach($this->super_model->select_custom_where("jo_rfq_head", "jor_id='$jor_id' AND grouping_id = '$ji->grouping_id' AND cancelled = '0' GROUP BY vendor_id") AS $ven){
                    $vendor.="-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$ven->vendor_id) . "<br>";
                    $vendor_id.=$this->super_model->select_column_where('vendor_head','vendor_id','vendor_id',$ven->vendor_id);
                }
                $data['cancelled']=$ji->cancelled;
                $data['completed']=$ji->completed;
                if($ji->total_cost==0){
                    $total=$ji->quantity*$ji->unit_cost;
                }else{
                    $total=$ji->total_cost;
                }
                $data['general_desc']=$ji->general_desc;
                $data['jo_items'][]=array(
                    'jor_items_id'=>$ji->jor_items_id,
                    'jor_id'=>$ji->jor_id,
                    'jor_id'=>$ji->jor_id,
                    'vendor_id'=>$vendor_id,
                    'scope_of_work'=>$ji->scope_of_work,
                    'quantity'=>$ji->quantity,
                    'uom'=>$ji->uom,
                    'unit_cost'=>$ji->unit_cost,
                    'total_cost'=>$total,
                    'grouping_id'=>$ji->grouping_id,
                    'cancelled_by'=>$this->super_model->select_column_where("users",'fullname','user_id',$ji->cancelled_by),
                    'cancelled_reason'=>$ji->cancelled_reason,
                    'cancelled_date'=>$ji->cancelled_date,
                    'cancelled'=>$ji->cancelled,
                    'vendor'=>$vendor
                );
        }
        $this->load->view('jor/jor_request',$data);
        $this->load->view('template/footer');
    
}

    public function save_groupings(){
        $jor_id = $this->input->post('jor_id');
        $count_item = $this->input->post('count_item');

        for($x=1;$x<$count_item;$x++){
            $jor_items_id =  $this->input->post('jor_items_id'.$x);
            $group =  $this->input->post('group'.$x);

            $data = array(
                'grouping_id'=>$group
            );

            $this->super_model->update_where("jor_items", $data, "jor_items_id", $jor_items_id);
        }

        $data_head = array(
            'saved'=>1,
        );
        $this->super_model->update_where("jor_head", $data_head, "jor_id", $jor_id);
        echo "<script>alert('Successfully Saved!'); window.location = 'jor_group/$jor_id';</script>";
        //redirect(base_url().'jor/jor_request/'.$jor_id);
    }

    public function create_rfq_group(){
        $jorid=$this->input->post('jor_id');
        $group=$this->input->post('group');
        $timestamp = date("Y-m-d H:i:s");
        $rfq_format = date("Ym");
        $rfqdet=date('Y-m');
        //$code = $this->super_model->select_column_where('jor_head','processing_code','jor_id',$jorid);
        $rows=$this->super_model->count_custom_where("jo_rfq_head","create_date LIKE '$rfqdet%'");
        if($rows==0){
            $rfq_no= $rfq_format."-1001";
        } else {
            $series = $this->super_model->get_max("jor_rfq_series", "series","year_month LIKE '$rfqdet%'");
            $next=$series+1;
            $rfq_no = $rfq_format."-".$next;
        }
        $rfqdetails=explode("-", $rfq_no);
        $rfq_prefix1=$rfqdetails[0];
        $rfq_prefix2=$rfqdetails[1];
        $rfq_prefix=$rfq_prefix1;
        $series=$rfq_prefix2;
        $rfq_data= array(
            'year_month'=>$rfq_prefix,
            'series'=>$series
        );
        $this->super_model->insert_into("jor_rfq_series", $rfq_data);

        foreach($this->super_model->select_custom_where("jor_vendors", "jor_id='$jorid' AND grouping_id = '$group'") AS $vendors){
            $rows_head = $this->super_model->count_rows("jo_rfq_head");
            if($rows_head==0){
                $jo_rfq_id=1;
            } else {
                $max = $this->super_model->get_max("jo_rfq_head", "jo_rfq_id");
                $jo_rfq_id = $max+1;
            }
            $new_rfq = $rfq_no."-".$vendors->grouping_id;
            $data_head = array(
                'jo_rfq_id'=>$jo_rfq_id,
                'jo_rfq_no'=>$new_rfq,
                'vendor_id'=>$vendors->vendor_id,
                'jor_id'=>$jorid,
                'grouping_id'=>$vendors->grouping_id,
                'noted_by'=>$vendors->noted_by,
                'approved_by'=>$vendors->approved_by,
                'quotation_date'=>$vendors->due_date,
                'rfq_date'=>$timestamp,
                //'processing_code'=>$code,
                'prepared_by'=>$_SESSION['user_id'],
                'create_date'=>$timestamp,
                'saved'=>1
            );
            $this->super_model->insert_into("jo_rfq_head", $data_head);
            foreach($this->super_model->select_custom_where("jor_items", "jor_id='$jorid' AND grouping_id = '$vendors->grouping_id'") AS $details){
                $data_details = array(
                    'jo_rfq_id'=>$jo_rfq_id,
                    'jor_items_id'=>$details->jor_items_id,
                    //'pn_no'=>$details->part_no,
                    'scope_of_work'=>$details->scope_of_work,
                    'quantity'=>$details->quantity,
                    'uom'=>$details->uom,

                );
                $this->super_model->insert_into("jo_rfq_details", $data_details);
            }
        }
        redirect(base_url().'jorfq/jorfq_list/');
    }

    public function jor_group(){  
        $jor_id = $this->uri->segment(3);
        $data['jor_id']=$jor_id;
        $data['jo_no']=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor_id);
        /*$jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor_id);
        if($jo_no!=''){
            $data['jo_no']=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor_id);
        }else{
            $data['jo_no']=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $jor_id);
        }*/

        foreach($this->super_model->custom_query("SELECT DISTINCT grouping_id FROM jor_items WHERE jor_id = '$jor_id'") AS $groups){
            $data['group'][] = array(
                'group'=>$groups->grouping_id,
            );

        }

       foreach($this->super_model->custom_query("SELECT jor_id,scope_of_work, grouping_id FROM jor_items WHERE jor_id = '$jor_id'") AS $items){
            $jor_vendor_id=$this->super_model->select_column_where("jor_vendors", "jor_vendor_id", "jor_id", $items->jor_id);
            $vendor_id=$this->super_model->select_column_where("jor_vendors", "vendor_id", "jor_id", $items->jor_id);
            $noted_by=$this->super_model->select_column_where("jor_vendors", "noted_by", "jor_id", $items->jor_id);
            $approved_by=$this->super_model->select_column_where("jor_vendors", "approved_by", "jor_id", $items->jor_id);
            $data['items'][] = array(
                'jor_vendor_id'=>$jor_vendor_id,
                'group_id'=>$items->grouping_id,
                'scope_of_work'=>$items->scope_of_work,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                'noted_by'=>$this->super_model->select_column_where("employees", "employee_name", "employee_id", $noted_by),
                'approved_by'=>$this->super_model->select_column_where("employees", "employee_name", "employee_id", $approved_by),
            );
        }

        foreach($this->super_model->custom_query("SELECT jor_id ,vendor_id,jor_vendor_id,grouping_id FROM jor_vendors WHERE jor_id = '$jor_id'") AS $ven){
            $data['vendor'][] = array(
                'jor_vendor_id'=>$ven->jor_vendor_id,
                'group_id'=>$ven->grouping_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $ven->vendor_id),
            );
        }

        foreach($this->super_model->custom_query("SELECT jor_id ,vendor_id, noted_by,approved_by,jor_vendor_id,grouping_id FROM jor_vendors WHERE jor_id = '$jor_id' GROUP BY grouping_id") AS $ven){
            $data['vendor_app'][] = array(
                'jor_vendor_id'=>$ven->jor_vendor_id,
                'group_id'=>$ven->grouping_id,
                'noted_by'=>$this->super_model->select_column_where("employees", "employee_name", "employee_id", $ven->noted_by),
                'approved_by'=>$this->super_model->select_column_where("employees", "employee_name", "employee_id", $ven->approved_by),
            );
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');        
        $this->load->view('jor/jor_group',$data);
        $this->load->view('template/footer');
    }

    public function delete_vendor(){
        $jor_id=$this->uri->segment(3);
        $jor_vendor_id=$this->uri->segment(4);
        if($this->super_model->delete_where('jor_vendors', 'jor_vendor_id', $jor_vendor_id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."jor/jor_group/$jor_id'; </script>";
        }
    }

    public function search_vendor(){
        $category = $this->input->post('category');
        $jor_id = $this->input->post('jor_id');
        $group = $this->input->post('group');

        redirect(base_url().'jor/choose_vendor/'.$jor_id.'/'.$group.'/'.$category);
        
    }
    
    public function choose_vendor(){
        $jor_id = $this->uri->segment(3);
        $group = $this->uri->segment(4);
        $category = $this->uri->segment(5);
        $data['employees'] = $this->super_model->select_all_order_by("employees","employee_name",'ASC');
        $data['jor_id'] = $jor_id;
        $data['group'] = $group;
        $data['category']=$category;
        $data['vendor'] = $this->super_model->custom_query("SELECT vendor_id, vendor_name, product_services FROM vendor_head WHERE product_services LIKE '%$category%' ORDER BY vendor_name ASC");
        $data['noted_by']=$this->super_model->select_column_custom_where("jor_vendors",'noted_by',"jor_id = '$jor_id' AND grouping_id='$group'");
        $data['approved_by']=$this->super_model->select_column_custom_where("jor_vendors",'approved_by',"jor_id = '$jor_id' AND grouping_id='$group'");
        $this->load->view('template/header');
        $this->load->view('jor/choose_vendor', $data);
        $this->load->view('template/footer');
    }

    public function insert_vendor(){
        $jor_id = $this->input->post('jor_id');
        $group = $this->input->post('group');
        $noted_by = $this->input->post('noted_by');
        $approved_by = $this->input->post('approved_by');
        $vendor = $this->input->post('vendor_id');
        $count = $this->super_model->count_custom_where('jor_vendors',"jor_id='$jor_id' AND grouping_id='$group'");
        if($count==0){
            foreach($vendor as $ven){
                $data = array(
                    'jor_id'=>$jor_id,
                    'vendor_id'=>$ven,
                    'noted_by'=>$noted_by,
                    'approved_by'=>$approved_by,
                    'grouping_id'=>$group
                );
                $this->super_model->insert_into('jor_vendors', $data);
            } 
        } else {
            foreach($vendor as $ven){
                $count_exist= $this->super_model->count_custom_where("jor_vendors","jor_id = '$jor_id' AND vendor_id = '$ven' AND grouping_id = '$group'");
                if($count_exist==0){
                    $data2 = array(
                            'jor_id'=>$jor_id,
                            'vendor_id'=>$ven,
                            'noted_by'=>$noted_by,
                            'approved_by'=>$approved_by,
                            'grouping_id'=>$group
                    );

                 $this->super_model->insert_into('jor_vendors', $data2);
                }
            }
        } 
        $data = array(
            'noted_by'=>$noted_by,
            'approved_by'=>$approved_by
        );
        $this->super_model->update_custom_where('jor_vendors', $data,"jor_id='$jor_id' AND grouping_id='$group'");
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

     public function create_rfq(){
        $jor_id=$this->input->post('jor_id');
        $timestamp = date("Y-m-d H:i:s");
        $rfq_format = date("Ym");
        $rfqdet=date('Y-m');
        $rfq=$this->super_model->select_column_where('jor_head','jo_no','jor_id',$jor_id);
        /*if($jo_no!=''){
            $rfq = $jo_no;
        }else{
            $rfq=$this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$jor_id);
        }*/
        $rfqjor=explode("-", $rfq);
        $rfqs=$rfqjor[0];
        $rfqss=$rfqs;
        $rows=$this->super_model->count_custom_where("jo_rfq_head","create_date LIKE '$rfqdet%'");
        if($rows==0){
            $rfq_no= $rfqss."-1001";
        } else {
            $series = $this->super_model->get_max("jor_rfq_series", "series","year_month LIKE '$rfqdet%'");
            $next=$series+1;
            $rfq_no = $rfqss."-".$next;
        }
        $rfqdetails=explode("-", $rfq_no);
        $rfq_prefix1=$rfqdetails[0];
        $rfq_prefix2=$rfqdetails[1];
        $rfq_prefix=$rfq_prefix1;
        $series=$rfq_prefix2;
        $rfq_data= array(
            'year_month'=>$rfq_prefix,
            'series'=>$series
        );
        $this->super_model->insert_into("jor_rfq_series", $rfq_data);

        foreach($this->super_model->select_row_where("jor_vendors", "jor_id", $jor_id) AS $vendors){
            $rows_head = $this->super_model->count_rows("jo_rfq_head");
            if($rows_head==0){
                $jo_rfq_id=1;
            } else {
                $max = $this->super_model->get_max("jo_rfq_head", "jo_rfq_id");
                $jo_rfq_id = $max+1;
            }
            $new_rfq = $rfq_no."-".$vendors->grouping_id;
            $data_head = array(
                'jo_rfq_id'=>$jo_rfq_id,
                'jo_rfq_no'=>$new_rfq,
                'vendor_id'=>$vendors->vendor_id,
                'jor_id'=>$jor_id,
                'grouping_id'=>$vendors->grouping_id,
                'noted_by'=>$vendors->noted_by,
                'approved_by'=>$vendors->approved_by,
                'rfq_date'=>$timestamp,
                'prepared_by'=>$_SESSION['user_id'],
                'create_date'=>$timestamp,
                'saved'=>1
            );
            $this->super_model->insert_into("jo_rfq_head", $data_head);
            foreach($this->super_model->select_custom_where("jor_items", "jor_id='$jor_id' AND grouping_id = '$vendors->grouping_id'") AS $details){
                $data_details = array(
                    'jo_rfq_id'=>$jo_rfq_id,
                    'jor_items_id'=>$details->jor_items_id,
                    'scope_of_work'=>$details->scope_of_work,
                    'quantity'=>$details->quantity,
                    'uom'=>$details->uom,

                );
                $this->super_model->insert_into("jo_rfq_details", $data_details);
            }
        }
        redirect(base_url().'jorfq/jorfq_list/');
    }

    public function upload_excel_jor(){
         $dest= realpath(APPPATH . '../uploads/excel/');
         $error_ext=0;
        if(!empty($_FILES['excelfile_jor']['name'])){
             $exc= basename($_FILES['excelfile_jor']['name']);
             $exc=explode('.',$exc);
             $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            } 
            else {
                 $filename1='JORequestForm.'.$ext1;
                if(move_uploaded_file($_FILES["excelfile_jor"]['tmp_name'], $dest.'/'.$filename1)){
                    $this->readExcel_jor();
                }   
            }
        }
    }

    public function readExcel_jor(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $inputFileName =realpath(APPPATH.'../uploads/excel/JORequestForm.xlsx');
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $head_rows = $this->super_model->count_rows("jor_head");
        if($head_rows==0){
            $jor_id=1;
        } else {
            $maxid=$this->super_model->get_max("jor_head", "jor_id");
            $jor_id=$maxid+1;
        }

        $jo_request = trim($objPHPExcel->getActiveSheet()->getCell('C7')->getValue());
        $date_prepared = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('C8')->getValue()));
        $department = trim($objPHPExcel->getActiveSheet()->getCell('C9')->getValue());
        $user_jo_no = trim($objPHPExcel->getActiveSheet()->getCell('C10')->getValue());
        $requested_by = trim($objPHPExcel->getActiveSheet()->getCell('C11')->getValue());
        $purpose = trim($objPHPExcel->getActiveSheet()->getCell('C12')->getValue());
        $duration = trim($objPHPExcel->getActiveSheet()->getCell('I7')->getValue());
        $completion_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('I8')->getValue()));
        $delivery_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('I9')->getValue()));
        $urgency_no = trim($objPHPExcel->getActiveSheet()->getCell('I10')->getValue());

        $exp = explode("-",$date_prepared);
        $year = $exp[0];

        $rows_jor = $this->super_model->count_rows_where("jor_series", "year", $year);
        if($rows_jor==0){
            $jor_no='JOR '.$year."-1";
        } else {
            $max = $this->super_model->get_max_where("jor_series", "series","year='$year'");
            $next= $max+1;
            $jor_no = 'JOR '.$year."-".$next;
        }

        $check_exist = $this->super_model->count_custom_where("jor_head", "user_jo_no='$user_jo_no' AND user_jo_no!='' AND cancelled='0'");
        if($user_jo_no!=''){
            $jor_nos='';
            $jors_nos=$user_jo_no;
        }else{
            $jor_nos=$user_jo_no;
            $jors_nos='';
        }
        if($check_exist==0){
            $data_jor = array(
                'jor_id'=>$jor_id,
                'jo_request'=>$jo_request,
                'date_prepared'=>$date_prepared,
                'department'=>$department,
                'jo_no'=>$jor_no,
                'user_jo_no'=>$user_jo_no,
                'purpose'=>$purpose,
                'requested_by'=>$requested_by,
                'duration'=>$duration,
                'completion_date'=>$completion_date,
                'delivery_date'=>$delivery_date,
                'urgency'=>$urgency_no,
                'date_imported'=>date("Y-m-d H:i:s"),
                'imported_by'=>$_SESSION['user_id'],
            );
            if($this->super_model->insert_into("jor_head", $data_jor)){
                $strings = str_replace(' ', '-', $jor_no);
                $jor = explode('-',$strings);
                $years=$jor[1];
                $series=$jor[2];
                $data_series = array(
                    'year'=>$years,
                    'series'=>$series,
                );
                $this->super_model->insert_into("jor_series", $data_series);

                
                $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
                $general_desc = trim($objPHPExcel->getActiveSheet()->getCell('A14')->getValue());
                for($x=15;$x<=$highestRow;$x++){   
                    $item_no = trim($objPHPExcel->getActiveSheet()->getCell('A'.$x)->getValue());
                    $scope_work = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getValue());
                    $qty = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getValue());
                    $uom = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getValue());
                    $unit_cost = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getValue());
                    $total_cost = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getValue());
                    $scope=str_replace("~", "\n ", $scope_work);
                    if($item_no!='' && $item_no!='Notes:'){
                        $data_ji = array(
                            'jor_id'=>$jor_id,
                            'scope_of_work'=>$scope,
                            'quantity'=>$qty,
                            'uom'=>$uom,
                            'unit_cost'=>$unit_cost,
                            'total_cost'=>$total_cost,
                            'item_no'=>$item_no,
                            'general_desc'=>$general_desc,
                        );
                        $this->super_model->insert_into("jor_items", $data_ji);
                    }


                    /*$num1=$x+3;
                    $num2=$num1+2;*/
                    $notes = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getValue());
                    if(($item_no=='' || $item_no=='Notes:') && $notes!=''){
                        $data_notes = array(
                            'jor_id'=>$jor_id,
                            'notes'=>$notes,
                        );
                        $this->super_model->insert_into("jor_notes", $data_notes);
                    }
                    /*if($item_no!='Notes:' && $notes!=''){
                        $data_notes = array(
                            'jor_id'=>$jor_id,
                            'notes'=>$notes,
                        );
                        $this->super_model->insert_into("jor_notes", $data_notes);
                    }*/
                    
                    //$num1++;
                    //$num2++;
                }
            }
            echo "<script>alert('Successfully Uploaded!'); window.location = 'jor_request/$jor_id';</script>";
        } else{
            echo "<script>alert('$jo_no already exist! Please try again.'); window.location = 'jor_list';</script>";
        } 
    }

    public function cancel_jor(){
        $jor_id=$this->input->post('jor_id');
        $date=date('Y-m-d H:i:s');
        $data=array(
            'cancelled'=>1,
            'cancelled_by'=>$_SESSION['user_id'],
            'cancelled_reason'=>$this->input->post('reason'),
            'cancelled_date'=>$date,
        );
        
        if($this->super_model->update_where('jor_head', $data, 'jor_id', $jor_id)){
            foreach($this->super_model->select_custom_where('jor_items',"jor_id='$jor_id'") AS $jor){
                $data_det=array(
                    'cancelled'=>1,
                );
                $this->super_model->update_where('jor_items', $data, 'jor_items_id', $jor->jor_items_id);
            }
            echo "<script>alert('Successfully Cancelled!'); window.location ='".base_url()."jor/jor_list';</script>";
        }
    }

    public function cancelled_jor(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $count = $this->super_model->count_custom_query("SELECT * FROM jor_items ji INNER JOIN jor_head jh ON ji.jor_id = jh.jor_id WHERE ji.cancelled = '1'");
        if($count!=0){
            foreach($this->super_model->custom_query("SELECT * FROM jor_head WHERE cancelled = '1'") AS $heads){
                
            $items = '';
                
                foreach($this->super_model->select_row_where("jor_items", "jor_id", $heads->jor_id) AS $det){
                    $items .= "-".$det->scope_of_work."<br>"; 
                }

                $data['jor_head'][] = array(
                    'jor_id'=>$heads->jor_id,
                    'jor_no'=>$heads->jo_no,
                    'user_jor_no'=>$heads->user_jo_no,
                    'jor_date'=>$heads->date_prepared,
                    'urgency_num'=>$heads->urgency,
                    'cancelled_by'=> $this->super_model->select_column_where('users','fullname','user_id',$heads->cancelled_by),
                    'cancel_date'=> $heads->cancelled_date,
                    'cancel_reason'=> $heads->cancelled_reason,
                    'items'=>$items
                );
              
            }
        }else {
            $data['jor_head']=array();
        }

        $this->load->view('jor/cancelled_jor',$data);
        $this->load->view('template/footer');
    }

    public function regroup_item(){
        $jor_items_id=$this->input->post('jor_items_id');
        $jor_id=$this->input->post('jor');
        $data=array(
            'grouping_id'=>$this->input->post('grouping')
        );

        if($this->super_model->update_where("jor_items", $data, "jor_items_id", $jor_items_id)){
            redirect(base_url().'jor/jor_request/'.$jor_id);
        }
    }

    public function add_vendor_jorfq(){
        $jorid = $this->input->post('jor_id');
        $group = $this->input->post('group');
        $vendor = $this->input->post('vendor');
        $jor_items_id = $this->input->post('jor_items_id');


        $count_exist = $this->super_model->count_custom_where("jo_rfq_head","jor_id = '$jorid' AND vendor_id = '$vendor' AND grouping_id = '$group'");
        if($count_exist!=0){
           ?>
           <script>
            alert('Vendor already existing in this PR Group.'); 
            window.location="<?php echo base_url(); ?>jor/jor_request/<?php echo $jorid; ?>";
            </script>    
            <?php
        } else {
              
                $jorven = array(
                    'jor_id'=>$jorid,
                    'vendor_id'=>$vendor,
                    'grouping_id'=>$group,
                    'due_date'=>$this->input->post('due_date'),
                    'noted_by'=>$this->input->post('noted'),
                    'approved_by'=>$this->input->post('approved')
                );

                $this->super_model->insert_into("jor_vendors", $jorven);


                $timestamp = date("Y-m-d H:i:s");
                $rfq_format = date("Ym");
                $rfqdet=date('Y-m');
                //$code = $this->super_model->select_column_where('pr_head','processing_code','pr_id',$prid);
                $rows=$this->super_model->count_custom_where("jo_rfq_head","create_date LIKE '$rfqdet%'");
                if($rows==0){
                    $rfq_no= $rfq_format."-1001";
                } else {
                    $series = $this->super_model->get_max("jor_rfq_series", "series","year_month LIKE '$rfqdet%'");
                    $next=$series+1;
                    $rfq_no = $rfq_format."-".$next;
                }
                $rfqdetails=explode("-", $rfq_no);
                $rfq_prefix1=$rfqdetails[0];
                $rfq_prefix2=$rfqdetails[1];
                $rfq_prefix=$rfq_prefix1;
                $series=$rfq_prefix2;
                $rfq_data= array(
                    'year_month'=>$rfq_prefix,
                    'series'=>$series
                );
                $this->super_model->insert_into("jor_rfq_series", $rfq_data);

                $rows_head = $this->super_model->count_rows("jo_rfq_head");
                if($rows_head==0){
                    $jo_rfq_id=1;
                } else {
                    $max = $this->super_model->get_max("jo_rfq_head", "jo_rfq_id");
                    $jo_rfq_id = $max+1;
                }
                $new_rfq = $rfq_no."-".$group;

                 $data_head = array(
                    'jo_rfq_id'=>$jo_rfq_id,
                    'jo_rfq_no'=>$new_rfq,
                    'vendor_id'=>$vendor,
                    'jor_id'=>$jorid,
                    'grouping_id'=>$group,
                    'rfq_date'=>$timestamp,
                    //'processing_code'=>$code,
                    'noted_by'=>$this->input->post('noted'),
                    'approved_by'=>$this->input->post('approved'),
                    'prepared_by'=>$_SESSION['user_id'],
                    'create_date'=>$timestamp,
                    'saved'=>1,
                );
                $this->super_model->insert_into("jo_rfq_head", $data_head);

                foreach($this->super_model->select_custom_where("jor_items", "jor_id='$jorid' AND grouping_id = '$group'") AS $details){
                    $data_details = array(
                        'jo_rfq_id'=>$jo_rfq_id,
                        'jor_items_id'=>$details->jor_items_id,
                        //'pn_no'=>$details->part_no,
                        'scope_of_work'=>$details->scope_of_work,
                        'quantity'=>$details->quantity,
                        'uom'=>$details->uom,

                    );
                    $this->super_model->insert_into("jo_rfq_details", $data_details);
                }

            redirect(base_url().'jorfq/jorfq_list/');      
        }
        
    }
        public function cancel_item(){
        $items_id=$this->input->post('items_id');
        $jor_id=$this->input->post('jor');
        $date=date('Y-m-d H:i:s');
        $data=array(
            'cancelled'=>1,
            'cancelled_reason'=>$this->input->post('reason'),
            'cancelled_by'=>$_SESSION['user_id'],
            'cancelled_date'=>$date
        );

        if($this->super_model->update_where("jor_items", $data, "jor_items_id", $items_id)){
            redirect(base_url().'jor/jor_request/'.$jor_id);
        }
    }

        public function jo_pending_forrfq(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $jor_id = $this->uri->segment(3);
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        foreach($this->super_model->custom_query("SELECT jor_items_id, jor_id, grouping_id, quantity FROM jor_items WHERE cancelled = '0' GROUP BY jor_id, grouping_id") AS $det){
            $jor_qty = $this->super_model->select_sum_where("jor_items", "quantity", "cancelled = '0' AND jor_items_id = '$det->jor_items_id' GROUP BY jor_id, grouping_id");
            $count = $this->super_model->count_custom_query("SELECT jor_id, grouping_id FROM jo_rfq_head WHERE cancelled = '0' AND jor_id = '$det->jor_id' AND grouping_id = '$det->grouping_id' GROUP BY jor_id, grouping_id");
            $count_po = $this->super_model->count_custom_query("SELECT joi_items_id FROM joi_items pi LEFT JOIN joi_head ph ON pi.joi_id = ph.joi_id WHERE pi.jor_items_id = '$det->jor_items_id' AND ph.cancelled = '0'");
            $joi_qty = $this->super_model->select_sum_join("quantity","joi_items","joi_head", "jor_items_id = '$det->jor_items_id' AND cancelled = '0'","joi_id");
            if($count==0 || $count_po==0 || ($jor_qty > $joi_qty)){
                    $norfq[] = array(
                        'jor_id'=>$det->jor_id,
                        'grouping_id'=>$det->grouping_id
                    );
            }
        }
        if(!empty($norfq)){
            foreach($norfq AS $key){
                $it='';
                $ven='';
                $jor_no=$this->super_model->select_column_custom_where("jor_head", "jo_no", "jor_id = '$key[jor_id]' AND cancelled = '0'");
                $date_prepared=$this->super_model->select_column_where("jor_head", "date_prepared", "jor_id", $key['jor_id']);
                //$completion_date=$this->super_model->select_column_where("jor_head", "completion_date", "jor_id", $key[jor_id]);
                $user_jo_no=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $key['jor_id']);
                $general_desc=$this->super_model->select_column_where("jor_items", "general_desc", "jor_id", $key['jor_id']);
                $purpose=$this->super_model->select_column_where("jor_head", "purpose", "jor_id", $key['jor_id']);
                foreach($this->super_model->select_custom_where("jor_items", "jor_id = '$key[jor_id]' AND grouping_id = '$key[grouping_id]' AND cancelled = '0'") AS $items){
                     $jor_qty = $this->super_model->select_column_custom_where("jor_items", "quantity", "jor_id = '$key[jor_id]' AND grouping_id = '$key[grouping_id]' AND cancelled = '0' AND jor_items_id = '$items->jor_items_id'");
                   
                    $count_jorfq = $this->super_model->count_custom_query("SELECT jor_id, grouping_id FROM jo_rfq_head INNER JOIN jo_rfq_details ON jo_rfq_head.jo_rfq_id = jo_rfq_details.jo_rfq_id WHERE cancelled = '0' AND jor_id = '$det->jor_id' AND grouping_id = '$det->grouping_id' AND jor_items_id = '$items->jor_items_id'");

                    /*$jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $items->jor_id);
                    if($jo_no!=''){
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "jo_no", "jor_id = '$key[jor_id]' AND cancelled = '0'");
                    }else{
                        $jor_no=$this->super_model->select_column_custom_where("jor_head", "user_jo_no", "jor_id = '$key[jor_id]' AND cancelled = '0'");
                    }*/
                    $joi_qty = $this->super_model->select_sum_join("quantity","joi_items","joi_head", "jor_items_id = '$items->jor_items_id' AND cancelled = '0'","joi_id");
                    
                    if($count_jorfq==0 || ($jor_qty > $joi_qty)){
                     $it .= ' - ' . $items->scope_of_work . "<br>";
                    }
                   
                }

                foreach($this->super_model->select_custom_where("jor_vendors", "jor_id = '$key[jor_id]' AND grouping_id = '$key[grouping_id]'") AS $vendors){
                    $ven .= ' - ' . $this->super_model->select_column_where('vendor_head','vendor_name', 'vendor_id', $vendors->vendor_id) . "<br>";
                }

                $data['head'][] = array(
                    'jor_id'=>$key['jor_id'],
                    'jor_no'=>$jor_no,
                    'group'=>$key['grouping_id'],
                    'item'=>$it,
                    'date_prepared'=>$date_prepared,
                    //'completion_date'=>$completion_date,
                    'user_jo_no'=>$user_jo_no,
                    'general_desc'=>$general_desc,
                    'purpose'=>$purpose,
                    'item'=>$it,
                    'vendor'=>$ven
                );
            
        }
        }else {
            $data['head']=array();
        }

        $this->load->view('jor/jo_pending_forrfq',$data);
        $this->load->view('template/footer');
    }
}

?>