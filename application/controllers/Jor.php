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

    public function jor_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['jo_head']=$this->super_model->select_all_order_by("jor_head","date_prepared","ASC");
        $this->load->view('jor/jor_list',$data);
        $this->load->view('template/footer');
    }

    public function jo_pending_forrfq(){
        $this->load->view('template/header');
        $this->load->view('template/navbar'); 
        $this->load->view('jor/jo_pending_forrfq');
        $this->load->view('template/footer');
    }

    public function cancelled_jor(){
        $this->load->view('template/header');
        $this->load->view('template/navbar'); 
        $this->load->view('jor/cancelled_jor');
        $this->load->view('template/footer');
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
        $data['jo_notes']=$this->super_model->select_row_where("jor_notes","jor_id",$jor_id);
            foreach($this->super_model->select_row_where("jor_items","jor_id",$jor_id) AS $ji){
        //foreach($this->super_model->custom_query("SELECT ji.*, jn.* FROM jor_items ji INNER JOIN jor_notes jn ON ji.jor_id = jn.jor_id WHERE jn.jor_id = '$jor_id'") AS $ji){
        //$notes = $this->super_model->select_column_where('jor_notes', 'notes', 'jor_id', $ji->jor_id);
            $data['jo_items'][]=array(
                'jor_items_id'=>$ji->jor_items_id,
                'jor_id'=>$ji->jor_id,
                'scope_of_work'=>$ji->scope_of_work,
                'quantity'=>$ji->quantity,
                'uom'=>$ji->uom,
                'unit_cost'=>$ji->unit_cost,
                'total_cost'=>$ji->total_cost,
                'grouping_id'=>$ji->grouping_id,
                //'notes'=>$notes,
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

    public function jor_group(){  
        $jor_id = $this->uri->segment(3);
        $data['jor_id']=$jor_id;
        $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor_id);
        if($jo_no!=''){
            $data['jo_no']=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor_id);
        }else{
            $data['jo_no']=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $jor_id);
        }

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
        $jo_rfq_id=$this->uri->segment(4);
        if($this->super_model->delete_where('jo_rfq_head', 'jo_rfq_id', $jo_rfq_id)){
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
        $data['noted_by']=$this->super_model->select_column_custom_where("jo_rfq_head",'noted_by',"jor_id = '$jor_id' AND grouping_id='$group'");
        $data['approved_by']=$this->super_model->select_column_custom_where("jo_rfq_head",'approved_by',"jor_id = '$jor_id' AND grouping_id='$group'");
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
        $jo_no=$this->super_model->select_column_where('jor_head','jo_no','jor_id',$jor_id);
        if($jo_no!=''){
            $rfq = $jo_no;
        }else{
            $rfq=$this->super_model->select_column_where('jor_head','user_jo_no','jor_id',$jor_id);
        }
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
        $jo_no = trim($objPHPExcel->getActiveSheet()->getCell('C10')->getValue());
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
            $jor_no='JOR '.$year."-01";
        } else {
            $max = $this->super_model->get_max_where("jor_series", "series","year='$year'");
            $next= $max+1;
            $jor_no = 'JOR '.$year."-".$next;
        }

        if($jo_no!=''){
            $jor_nos='';
            $jors_nos=$jo_no;
        }else{
            $jor_nos=$jor_no;
            $jors_nos='';
        }

        $data_jor = array(
            'jor_id'=>$jor_id,
            'jo_request'=>$jo_request,
            'date_prepared'=>$date_prepared,
            'department'=>$department,
            'jo_no'=>$jor_nos,
            'user_jo_no'=>$jors_nos,
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
            for($x=14;$x<=$highestRow;$x++){
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
                    );
                    $this->super_model->insert_into("jor_items", $data_ji);
                }

                $num1=$x+3;
                $notes = trim($objPHPExcel->getActiveSheet()->getCell('B'.$num1)->getValue());
                if($notes!=''){
                    $data_notes = array(
                        'jor_id'=>$jor_id,
                        'notes'=>$notes,
                    );
                    $this->super_model->insert_into("jor_notes", $data_notes);
                }
                $num1++;
            }
        }
        echo "<script>alert('Successfully Uploaded!'); window.location = 'jor_request/$jor_id';</script>";
    }

}

?>