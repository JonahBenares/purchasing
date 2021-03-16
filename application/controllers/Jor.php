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
        $this->load->view('jor/jor_list');
        $this->load->view('template/footer');
    }

    public function jor_request(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $jor_id=$this->uri->segment(3);
        $data['jo_head']=$this->super_model->select_all_order_by("jor_head","jo_no","ASC");
        foreach($this->super_model->select_row_where("jor_items","jor_id",$jor_id) AS $ji){
            $data['jo_items'][]=array(
                'jor_id'=>$ji->jor_id,
                'scope_of_work'=>$ji->scope_of_work,
                'quantity'=>$ji->quantity,
                'uom'=>$ji->uom,
                'unit_cost'=>$ji->unit_cost,
                'total_cost'=>$ji->total_cost,
            );
        }
        $this->load->view('jor/jor_request',$data);
        $this->load->view('template/footer');
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
        $purpose = trim($objPHPExcel->getActiveSheet()->getCell('C11')->getValue());
        $duration = trim($objPHPExcel->getActiveSheet()->getCell('I7')->getValue());
        $completion_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('I8')->getValue()));
        $delivery_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('I9')->getValue()));
        $urgency_no = trim($objPHPExcel->getActiveSheet()->getCell('I10')->getValue());

        $data_jor = array(
            'jor_id'=>$jor_id,
            'jo_request'=>$jo_request,
            'date_prepared'=>$date_prepared,
            'department'=>$department,
            'jo_no'=>$jo_no,
            'purpose'=>$purpose,
            'duration'=>$duration,
            'completion_date'=>$completion_date,
            'delivery_date'=>$delivery_date,
            'urgency'=>$urgency_no,
            'saved'=>1,
            'date_imported'=>date("Y-m-d H:i:s"),
            'imported_by'=>$_SESSION['user_id'],
        );
        if($this->super_model->insert_into("jor_head", $data_jor)){
            $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
            for($x=14;$x<=$highestRow;$x++){
                $scope_work = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getValue());
                $qty = trim($objPHPExcel->getActiveSheet()->getCell('G'.$x)->getValue());
                $uom = trim($objPHPExcel->getActiveSheet()->getCell('H'.$x)->getValue());
                $unit_cost = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getValue());
                $total_cost = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getValue());
                $scope=str_replace("~", "\n ", $scope_work);
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
        }
        echo "<script>alert('Successfully Uploaded!'); window.location = 'jor_request/$jor_id';</script>";
    }

}

?>