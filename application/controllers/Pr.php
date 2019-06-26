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
                 $filename1='Purcahse Request.'.$ext1;
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



        $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
        for($x=14;$x<=$highestRow;$x++){
            $qty = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getValue());
            $uom = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getValue());
            $part_no = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getValue());
            $description = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getValue());
            $date_needed = trim($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getValue());
            $data_items = array(
                'item_id'=>$itemid,
                'catalog_no'=>$catalog,
                'quantity'=>$qty
            );
            $this->super_model->insert_into("supplier_items", $data_items);
        }
        echo "<script>alert('Successfully Uploaded!'); window.location = 'purchase_request';</script>";
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