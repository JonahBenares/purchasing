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

public function pending_forrfq(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/pending_forrfq');
        $this->load->view('template/footer');
    }
    

    public function pr_list(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['pr_head']=$this->super_model->select_custom_where("pr_head","cancelled='0' ORDER BY date_prepared ASC");
        $this->load->view('pr/pr_list',$data);
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
        $date_prepared = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('C8')->getValue()));
        /*$date_issued = trim($objPHPExcel->getActiveSheet()->getCell('C9')->getValue());*/
        $pr_no = trim($objPHPExcel->getActiveSheet()->getCell('C10')->getValue());
        $purpose = trim($objPHPExcel->getActiveSheet()->getCell('C11')->getValue());
        $enduse = trim($objPHPExcel->getActiveSheet()->getCell('C12')->getValue());
        $department = trim($objPHPExcel->getActiveSheet()->getCell('I7')->getValue());
        $requestor = trim($objPHPExcel->getActiveSheet()->getCell('I8')->getValue());
        $urgency_no = trim($objPHPExcel->getActiveSheet()->getCell('I9')->getValue());

        $data_head = array(
            'pr_id'=>$pr_id,
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
                $date_needed = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getValue()));
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

    public function cancel_pr(){
        $pr_id=$this->input->post('pr_id');
        $date=date('Y-m-d H:i:s');
        $data=array(
            'cancelled'=>1,
            'cancelled_by'=>$_SESSION['user_id'],
            'cancelled_reason'=>$this->input->post('reason'),
            'cancelled_date'=>$date,
        );
        
        if($this->super_model->update_where('pr_head', $data, 'pr_id', $pr_id)){
            foreach($this->super_model->select_custom_where('pr_details',"pr_id='$pr_id'") AS $up){
                $data_det=array(
                    'cancelled'=>1,
                );
                $this->super_model->update_where('pr_details', $data, 'pr_details_id', $up->pr_details_id);
            }
            echo "<script>alert('Successfully Cancelled!'); window.location ='".base_url()."index.php/pr/pr_list';</script>";
        }
    }

    public function purchase_request(){  
        $prid = $this->uri->segment(3);
        $data['pr_id']=$prid;
        $data['head']=$this->super_model->select_row_where("pr_head", "pr_id", $prid);
        $data['details']=$this->super_model->select_custom_where("pr_details", "pr_id='$prid' AND cancelled='0'");
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/purchase_request',$data);
        $this->load->view('template/footer');
    }

    public function cancel_item(){
        $details_id=$this->input->post('details_id');
        $pr_id=$this->input->post('pr');
        $date=date('Y-m-d H:i:s');
        $data=array(
            'cancelled'=>1,
            'cancelled_reason'=>$this->input->post('reason'),
            'cancelled_by'=>$_SESSION['user_id'],
            'cancelled_date'=>$date
        );

        if($this->super_model->update_where("pr_details", $data, "pr_details_id", $details_id)){
            redirect(base_url().'pr/purchase_request/'.$pr_id);
        }
    }

    public function save_groupings(){
        $prid = $this->input->post('pr_id');
        $count_item = $this->input->post('count_item');

        for($x=1;$x<$count_item;$x++){
            $pr_details_id =  $this->input->post('pr_details_id'.$x);
            $group =  $this->input->post('group'.$x);

            $data = array(
                'grouping_id'=>$group
            );

            $this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_details_id);
        }

        $data_head = array(
            'pr_no'=>$this->input->post('new_pr'),
        );
        $this->super_model->update_where("pr_head", $data_head, "pr_id", $prid);

        redirect(base_url().'pr/pr_group/'.$prid);
    }

    public function pr_group(){  
        $prid = $this->uri->segment(3);
        $data['pr_id']=$prid;
        $data['pr_no']=$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $prid);

        foreach($this->super_model->custom_query("SELECT DISTINCT grouping_id FROM pr_details WHERE pr_id = '$prid'") AS $groups){
            $data['group'][] = array(
                'group'=>$groups->grouping_id
            );

        }

       foreach($this->super_model->custom_query("SELECT item_description, grouping_id FROM pr_details WHERE pr_id = '$prid'") AS $items){
            $data['items'][] = array(
                'group_id'=>$items->grouping_id,
                'item_desc'=>$items->item_description
            );
        }


       foreach($this->super_model->custom_query("SELECT vendor_id,grouping_id FROM pr_vendors WHERE pr_id = '$prid'") AS $vendor){
            $data['vendor'][] = array(
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor->vendor_id),
                'group_id'=>$vendor->grouping_id,
            );
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');        
        $this->load->view('pr/pr_group',$data);
        $this->load->view('template/footer');
    }

    public function search_vendor(){
        $category = $this->input->post('category');
        $pr_id = $this->input->post('pr_id');
        $group = $this->input->post('group');

        redirect(base_url().'pr/choose_vendor/'.$pr_id.'/'.$group.'/'.$category);
        
    }
    
    public function choose_vendor(){
        $prid = $this->uri->segment(3);
        $group = $this->uri->segment(4);
        $category = $this->uri->segment(5);
        $data['prid'] = $prid;
        $data['group'] = $group;
        $data['category']=$category;
        $data['vendor'] = $this->super_model->custom_query("SELECT vendor_id, vendor_name, product_services FROM vendor_head WHERE product_services LIKE '%$category%'");
        $this->load->view('template/header');
        $this->load->view('pr/choose_vendor', $data);
        $this->load->view('template/footer');
    }

    public function insert_vendor(){
        $pr_id = $this->input->post('pr_id');
        $group = $this->input->post('group');
        $vendor = $this->input->post('vendor_id');

        foreach($vendor as $ven){
            $data = array(
                'pr_id'=>$pr_id,
                'vendor_id'=>$ven,
                'grouping_id'=>$group
            );

            $this->super_model->insert_into('pr_vendors', $data);
        } ?>

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
        $prid=$this->input->post('pr_id');
        $timestamp = date("Y-m-d H:i:s");


        foreach($this->super_model->select_row_where("pr_vendors", "pr_id", $prid) AS $vendors){
                $rows_head = $this->super_model->count_rows("rfq_head");
                if($rows_head==0){
                    $rfq_id=1;
                } else {
                    $max = $this->super_model->get_max("rfq_head", "rfq_id");
                    $rfq_id = $max+1;
                }

                $rfq_format = date("Ym");
                $rfqdet=date('Y-m');
                $rows=$this->super_model->count_custom_where("rfq_head","create_date LIKE '$rfqdet%'");
                if($rows==0){
                    $rfq_no= $rfq_format."-1001";
                } else {
                    $series = $this->super_model->get_max("rfq_series", "series","year_month LIKE '$rfqdet%'");
                    $next=$series+1;
                    $rfq_no = $rfq_format."-".$next;
                }

                $rfqdetails=explode("-", $rfq_no);
                $rfq_prefix1=$rfqdetails[0];
                $rfq_prefix=$rfq_prefix1;
                $series = $rfqdetails[1];

                $rfq_data= array(
                    'year_month'=>$rfq_prefix,
                    'series'=>$series
                );
                $this->super_model->insert_into("rfq_series", $rfq_data);


                $data_head = array(
                    'rfq_id'=>$rfq_id,
                    'rfq_no'=>$rfq_no,
                    'vendor_id'=>$vendors->vendor_id,
                    'pr_id'=>$prid,
                    'rfq_date'=>$timestamp,
                    'prepared_by'=>$_SESSION['user_id'],
                    'create_date'=>$timestamp
                );
                $this->super_model->insert_into("rfq_head", $data_head);

                foreach($this->super_model->select_custom_where("pr_details", "pr_id='$prid' AND grouping_id = '$vendors->grouping_id'") AS $details){
                    $data_details = array(
                        'rfq_id'=>$rfq_id,
                        'pr_details_id'=>$details->pr_details_id,
                        'item_desc'=>$details->item_description,
                        'uom'=>$details->uom,

                    );
                    $this->super_model->insert_into("rfq_details", $data_details);
                }

        }

        redirect(base_url().'rfq/rfq_list/');
     }

}

?>