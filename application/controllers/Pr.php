<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pr extends CI_Controller {

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


    public function pr_list(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/pr_list');
        $this->load->view('template/footer');
    }

    public function upload_excel_pr(){
         $dest= realpath(APPPATH . '../uploads/excel/');
         $error_ext=0;
        if(!empty($_FILES['excelfile_pr']['name'])){
             $exc= basename($_FILES['excelfile_pr']['name']);
             $exc=explode('.',$exc);
             $ext1=$exc[1];
            if($ext1=='php' || $ext1!='xlsx'){
                $error_ext++;
            } 
            else {
                 $filename1='Purchase Request.'.$ext1;
                if(move_uploaded_file($_FILES["excelfile_pr"]['tmp_name'], $dest.'/'.$filename1)){
                    $this->readExcel_pr();
                }   
            }
        }
    }

    public function readExcel_pr(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $inputFileName =realpath(APPPATH.'../uploads/excel/Purchase Request.xlsx');
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file"'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        $head_rows = $this->super_model->count_rows("pr_head");
        if($head_rows==0){
            $pr_id=1;
        } else {
            $maxid=$this->super_model->get_max("pr_head", "pr_id");
            $pr_id=$maxid+1;
        }

        $pr = trim($objPHPExcel->getActiveSheet()->getCell('C7')->getValue());
        $date_prepared = trim($objPHPExcel->getActiveSheet()->getCell('C8')->getValue());
        /*$date_issued = trim($objPHPExcel->getActiveSheet()->getCell('C9')->getValue());*/
        $pr_no = trim($objPHPExcel->getActiveSheet()->getCell('C10')->getValue());
        $purpose = trim($objPHPExcel->getActiveSheet()->getCell('C11')->getValue());
        $enduse = trim($objPHPExcel->getActiveSheet()->getCell('C12')->getValue());
        $department = trim($objPHPExcel->getActiveSheet()->getCell('I7')->getValue());
        $requestor = trim($objPHPExcel->getActiveSheet()->getCell('I8')->getValue());
        $urgency_no = trim($objPHPExcel->getActiveSheet()->getCell('I9')->getValue());

        $data_head = array(
            'user_pr_no'=>$pr_no,
            'purchase_request'=>$pr,
            'date_prepared'=>$date_prepared,
            'enduse'=>$enduse,
            'purpose'=>$purpose,
            'department'=>$department,
            'requestor'=>$requestor,
            'urgency'=>$urgency_no,
            'date_imported'=>date('Y-m-d H:i:s'),
            'imported_by'=>$_SESSION['user_id'],
        );

        if($this->super_model->insert_into("pr_head", $data_head)){
            $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
            for($x=14;$x<=$highestRow;$x++){
                $qty = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getValue());
                $uom = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getValue());
                $part_no = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getValue());
                $description = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getValue());
                $date_needed = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getValue());
                $data_items = array(
                    'pr_id'=>$pr_id,
                    'item_description'=>$description,
                    'uom'=>$uom,
                    'quantity'=>$qty,
                    'part_no'=>$part_no,
                    'date_needed'=>$date_needed,
                );
                $this->super_model->insert_into("pr_details", $data_items);
            }
        }
        echo "<script>alert('Successfully Uploaded!'); window.location = 'purchase_request/$pr_id';</script>";
    }

    public function purchase_request(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/purchase_request');
        $this->load->view('template/footer');
    }

    public function pr_group(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');        
        $this->load->view('pr/pr_group');
        $this->load->view('template/footer');
    }
    
    public function choose_vendor(){
        $this->load->view('template/header');
        $this->load->view('pr/choose_vendor');
        $this->load->view('template/footer');
    }
}

?>