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

    public function redirect_pod(){
        $rows_head = $this->super_model->count_rows("po_head");
        if($rows_head==0){
            $po_id=1;
        } else {
            $max = $this->super_model->get_max("po_head", "po_id");
            $po_id = $max+1;
        }

        $rows_series = $this->super_model->count_rows("po_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("po_series", "series");
            $series = $max+1;
        }

        $pr_id = $this->input->post('pr_ids');
        $group_id = $this->input->post('group_id');
         $pr_no = $this->super_model->select_column_where("pr_head","pr_no","pr_id",$pr_id);
        $po_no = "P".$pr_no."-".$series;
       // $po_no = "POD-".$series;
        $data= array(
            'po_id'=>$po_id,
            'po_date'=>$this->input->post('po_date'),
            'po_no'=>$po_no,
            'vendor_id'=>$this->input->post('vendor'),
            'po_type'=>1,
            'user_id'=>$_SESSION['user_id'],
            'prepared_date'=>date("Y-m-d H:i:s"),
        );  

        $data_series = array(
            'series'=>$series
        );
        $this->super_model->insert_into("po_series", $data_series);

      
        if($this->super_model->insert_into("po_head", $data)){
            foreach($this->super_model->select_row_where("pr_head","pr_id",$pr_id) AS $po_pr){
                $data_pr = array(
                    'po_id'=>$po_id,
                    'pr_id'=>$pr_id,
                    'enduse'=>$po_pr->enduse,
                    'purpose'=>$po_pr->purpose,
                    'requestor'=>$po_pr->requestor,
                );
                $this->super_model->insert_into("po_pr", $data_pr);
            }
            redirect(base_url().'pod/po_direct/'.$po_id.'/'.$pr_id.'/'.$group_id);
        }
    }

    public function pending_forrfq(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        //$gr="SELECT pr_id, grouping_id FROM pr_details WHERE ";
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head","vendor_name","ASC");
        foreach($this->super_model->custom_query("SELECT pr_details_id, pr_id, grouping_id, quantity FROM pr_details WHERE cancelled = '0' GROUP BY pr_id, grouping_id") AS $det){
            $pr_qty = $this->super_model->select_sum_where("pr_details", "quantity", "cancelled = '0' AND pr_details_id = '$det->pr_details_id' GROUP BY pr_id, grouping_id");
            $count = $this->super_model->count_custom_query("SELECT pr_id, grouping_id FROM rfq_head WHERE cancelled = '0' AND pr_id = '$det->pr_id' AND grouping_id = '$det->grouping_id' GROUP BY pr_id, grouping_id");
            //echo "**".$det->pr_id . " - ".$det->pr_details_id ."=".$count."<br><br>";
           //echo "SELECT pr_id, grouping_id FROM rfq_head WHERE cancelled = '0' AND pr_id = '$det->pr_id' AND grouping_id = '$det->grouping_id' GROUP BY pr_id, grouping_id";

            $count_po = $this->super_model->count_custom_query("SELECT po_items_id FROM po_items pi LEFT JOIN po_head ph ON pi.po_id = ph.po_id WHERE pi.pr_details_id = '$det->pr_details_id' AND ph.cancelled = '0'");
            $po_qty = $this->super_model->select_sum_join("quantity","po_items","po_head", "pr_details_id = '$det->pr_details_id' AND cancelled = '0'","po_id");
            //echo "pr_details_id = '$det->pr_details_id' AND cancelled = '0'";
     
            //echo $det->pr_details_id . ", ". $count. ", ". $count_po.", ". $pr_qty  ."=". $po_qty . "<br>";
            if($count==0 || $count_po==0 || ($pr_qty > $po_qty)){
                //foreach($this->super_model->custom_query("SELECT pr_id, grouping_id FROM rfq_head WHERE cancelled = '0' AND pr_id != '$det->pr_id' AND grouping_id != '$det->grouping_id' GROUP BY pr_id, grouping_id") AS $rfq){
                    //echo "**".$it->pr_details_id . "<br>";
                    $norfq[] = array(
                        'pr_id'=>$det->pr_id,
                       
                        'grouping_id'=>$det->grouping_id
                    );
                //}
            }
        }
        //print_r($norfq);
        if(!empty($norfq)){
            foreach($norfq AS $key){
                $it='';
                $ven='';

                foreach($this->super_model->select_custom_where("pr_details", "pr_id = '$key[pr_id]' AND grouping_id = '$key[grouping_id]' AND cancelled = '0'") AS $items){
                     $pr_qty = $this->super_model->select_column_custom_where("pr_details", "quantity", "pr_id = '$key[pr_id]' AND grouping_id = '$key[grouping_id]' AND cancelled = '0' AND pr_details_id = '$items->pr_details_id'");
                   
                    $count_rfq = $this->super_model->count_custom_query("SELECT pr_id, grouping_id FROM rfq_head INNER JOIN rfq_details ON rfq_head.rfq_id = rfq_details.rfq_id WHERE cancelled = '0' AND pr_id = '$det->pr_id' AND grouping_id = '$det->grouping_id' AND pr_details_id = '$items->pr_details_id'");
                    //echo "SELECT pr_id, grouping_id FROM rfq_head INNER JOIN rfq_details ON rfq_head.rfq_id = rfq_details.rfq_id WHERE cancelled = '0' AND pr_id = '$det->pr_id' AND grouping_id = '$det->grouping_id' AND pr_details_id = '$items->pr_details_id'<br>";
                    $count_po1 = $this->super_model->count_custom_query("SELECT * FROM po_items pi LEFT JOIN po_head ph ON pi.po_id = ph.po_id WHERE pi.pr_details_id = '$items->pr_details_id' AND ph.cancelled = '0'");
                     $po_qty = $this->super_model->select_sum_join("quantity","po_items","po_head", "pr_details_id = '$items->pr_details_id' AND cancelled = '0'","po_id");

                    //echo $items->pr_details_id. "=". $count_rfq ." = ". $count_po1 . ", ".$pr_qty . " " .$po_qty ."<br>";
                    if($count_rfq==0  || ($pr_qty > $po_qty)){
                     $it .= ' - ' . $items->item_description . "<br>";
                    }
                   
                }

                foreach($this->super_model->select_custom_where("pr_vendors", "pr_id = '$key[pr_id]' AND grouping_id = '$key[grouping_id]'") AS $vendors){
                    $ven .= ' - ' . $this->super_model->select_column_where('vendor_head','vendor_name', 'vendor_id', $vendors->vendor_id) . "<br>";
                }

                $data['head'][] = array(
                    'pr_id'=>$key['pr_id'],
                    'pr_no'=>$this->super_model->select_column_custom_where("pr_head", "pr_no", "pr_id = '$key[pr_id]' AND cancelled = '0'"),
                    'group'=>$key['grouping_id'],
                    'item'=>$it,
                    'vendor'=>$ven
                );
            }
        }else {
            $data['head']=array();
        }


       /* if(!empty($vendors)) $vendors = $vendors;
        else $vendors=array();

        if(!empty($details)) $details = $details;
        else $details=array();*/

        //$result = array_diff($vendors, $details);

     /*   print_r($result);*/
        $this->load->view('pr/pending_forrfq',$data);
        $this->load->view('template/footer');
    }
    
    public function create_reorderpo(){
        $pr_id = $this->input->post('pr_idro');
        $group_id = $this->input->post('group_idro');
        $rows_head = $this->super_model->count_rows("po_head");
        if($rows_head==0){
            $po_id=1;
        } else {
            $max = $this->super_model->get_max("po_head", "po_id");
            $po_id = $max+1;
        }


        $rows_series = $this->super_model->count_rows("po_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("po_series", "series");
            $series = $max+1;
        }
        $pr_no = $this->super_model->select_column_where("pr_head","pr_no","pr_id",$pr_id);
        $po_no = 'P'.$pr_no.'-'.$series;
        $data_series = array(
            'series'=>$series
        );
        $this->super_model->insert_into("po_series", $data_series);
        $data = array(
            'po_id'=>$po_id,
            'po_date'=>$this->input->post('po_date'),
            'po_no'=>$po_no,
            'grouping_id'=>$group_id,
            'notes'=>$this->input->post('notes'),
            'vendor_id'=>$this->input->post('supplier'),
            'user_id'=>$_SESSION['user_id'],
            'po_type'=>2,
            'prepared_date'=>date("Y-m-d H:i:s"),
        );

        $data_popr = array(
            'po_id'=>$po_id,
            'pr_id'=>$pr_id,
            'enduse'=>$this->super_model->select_column_where('pr_head', 'enduse', 'pr_id', $pr_id),
            'purpose'=>$this->super_model->select_column_where('pr_head', 'purpose', 'pr_id', $pr_id),
            'requestor'=>$this->super_model->select_column_where('pr_head', 'requestor', 'pr_id', $pr_id),
        );

        $this->super_model->insert_into("po_pr", $data_popr);

        if($this->super_model->insert_into("po_head", $data)){
            redirect(base_url().'po/reporder_prnt/'.$po_id."/".$pr_id."/".$group_id);
        }
    }

    public function check_diff_multi($arraya, $arrayb){

        if(!empty($arraya)) $arraya = $arraya;
        else $arraya=array();

        if(!empty($arrayb)) $arrayb = $arrayb;
        else $arrayb=array();

        foreach ($arraya as $keya => $valuea) {
            if (in_array($valuea, $arrayb)) {
                unset($arraya[$keya]);
            }
        }
        return $arraya;
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
                 $filename1='PurchaseRequest.'.$ext1;
                if(move_uploaded_file($_FILES["excelfile_pr"]['tmp_name'], $dest.'/'.$filename1)){
                    $this->readExcel_pr();
                }   
            }
        }
    }

    public function createColumnsArray($end_column, $first_letters = '')
        {
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

    public function readExcel_pr(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $inputFileName =realpath(APPPATH.'../uploads/excel/PurchaseRequest.xlsx');
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
        $purpose = trim($objPHPExcel->getActiveSheet()->getCell('C11')->getValue());
        $enduse = trim($objPHPExcel->getActiveSheet()->getCell('C12')->getValue());
        $department = trim($objPHPExcel->getActiveSheet()->getCell('I7')->getValue());
        $dept_code = trim($objPHPExcel->getActiveSheet()->getCell('I8')->getValue());
        $requestor = trim($objPHPExcel->getActiveSheet()->getCell('I9')->getValue());
        $urgency_no = trim($objPHPExcel->getActiveSheet()->getCell('I10')->getValue());

        $series_rows = $this->super_model->count_rows("pr_series");
        if($series_rows==0){
            $pr_series=1000;
        } else {
            $max_series=$this->super_model->get_max("pr_series", "series_no");
            $pr_series=$max_series+1;
        }


        $pr_no = $dept_code.date('y')."-".$pr_series;

        $data_head = array(
            'pr_id'=>$pr_id,
            'pr_no'=>$pr_no,
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
        
        $data_series = array(
            'series_no'=>$pr_series
        );
        $this->super_model->insert_into("pr_series", $data_series);

        if($this->super_model->insert_into("pr_head", $data_head)){
            $highestRow = $objPHPExcel->getActiveSheet()->getHighestRow(); 
            $z=1;
            for($x=14;$x<=$highestRow;$x++){
                $qty = trim($objPHPExcel->getActiveSheet()->getCell('B'.$x)->getValue());
                $uom = trim($objPHPExcel->getActiveSheet()->getCell('C'.$x)->getValue());
                $part_no = trim($objPHPExcel->getActiveSheet()->getCell('D'.$x)->getValue());
                $description = trim($objPHPExcel->getActiveSheet()->getCell('E'.$x)->getValue());
                $date_needed = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('J'.$x)->getValue()));
                $wh_stock = trim($objPHPExcel->getActiveSheet()->getCell('I'.$x)->getValue());
                if(!empty($qty)){
                    $data_items = array(
                        'pr_id'=>$pr_id,
                        'item_description'=>$description,
                        'item_no'=>$z,
                        'uom'=>$uom,
                        'quantity'=>$qty,
                        'part_no'=>$part_no,
                        'date_needed'=>$date_needed,
                        'wh_stocks'=>$wh_stock,
                    );

                    $this->super_model->insert_into("pr_details", $data_items);
                }
                $z++;
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
            echo "<script>alert('Successfully Cancelled!'); window.location ='".base_url()."pr/pr_list';</script>";
        }
    }

    public function purchase_request(){  
        $prid = $this->uri->segment(3);
        $data['pr_id']=$prid;
        $data['head']=$this->super_model->select_row_where("pr_head", "pr_id", $prid);
        $data['saved']=$this->super_model->select_column_where("pr_head",'saved','pr_id',$prid);
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        /*$data['details']=*/
        $data['cancelled']='';
        foreach($this->super_model->select_custom_where("pr_details", "pr_id='$prid'") AS $det){
            $vendor='';
            foreach($this->super_model->select_custom_where("rfq_head", "pr_id='$prid' AND grouping_id = '$det->grouping_id' AND cancelled = '0' GROUP BY vendor_id") AS $ven){
                $vendor.="-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$ven->vendor_id) . "<br>";
            }
            $data['cancelled']=$det->cancelled;
            $data['details'][]=array(
                'pr_details_id'=>$det->pr_details_id,
                'quantity'=>$det->quantity,
                'wh_stocks'=>$det->wh_stocks,
                'uom'=>$det->uom,
                'pn_no'=>$det->part_no,
                'item_description'=>$det->item_description,
                'date_needed'=>$det->date_needed,
                'grouping_id'=>$det->grouping_id,
                'cancelled_by'=>$this->super_model->select_column_where("users",'fullname','user_id',$det->cancelled_by),
                'cancelled_reason'=>$det->cancelled_reason,
                'cancelled_date'=>$det->cancelled_date,
                'cancelled'=>$det->cancelled,
                'vendor'=>$vendor
            ); 
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/purchase_request',$data);
        $this->load->view('template/footer');
    }
    
    public function regroup_item(){
        $pr_det_id=$this->input->post('pr_det_id');
        $pr_id=$this->input->post('pr');
        $data=array(
            'grouping_id'=>$this->input->post('grouping')
        );

        if($this->super_model->update_where("pr_details", $data, "pr_details_id", $pr_det_id)){
            redirect(base_url().'pr/purchase_request/'.$pr_id);
        }
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
            'saved'=>1,
            'processing_code'=>$this->input->post('process'),
        );
        $this->super_model->update_where("pr_head", $data_head, "pr_id", $prid);

        redirect(base_url().'pr/pr_group/'.$prid);
    }

    public function pr_group(){  
        $prid = $this->uri->segment(3);
        $data['pr_id']=$prid;
        $data['pr_no']=$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $prid);

        foreach($this->super_model->custom_query("SELECT DISTINCT grouping_id FROM pr_details WHERE pr_id = '$prid' AND cancelled='0'") AS $groups){
            $data['group'][] = array(
                'group'=>$groups->grouping_id,
            );

        }

       foreach($this->super_model->custom_query("SELECT item_description, grouping_id, cancelled FROM pr_details WHERE pr_id = '$prid' AND cancelled='0'") AS $items){
            $data['items'][] = array(
                'group_id'=>$items->grouping_id,
                'item_desc'=>$items->item_description,
            );
        }


        foreach($this->super_model->custom_query("SELECT vendor_id,grouping_id,noted_by,approved_by,due_date,pr_vendors_id FROM pr_vendors WHERE pr_id = '$prid'") AS $vendor){
            $data['vendor'][] = array(
                'pr_vendors_id'=>$vendor->pr_vendors_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor->vendor_id),
                'group_id'=>$vendor->grouping_id,
                'noted_by'=>$this->super_model->select_column_where("employees", "employee_name", "employee_id", $vendor->noted_by),
                'approved_by'=>$this->super_model->select_column_where("employees", "employee_name", "employee_id", $vendor->approved_by),
                'due_date'=>$vendor->due_date,
            );
        }

        foreach($this->super_model->custom_query("SELECT grouping_id,noted_by,approved_by,due_date FROM pr_vendors WHERE pr_id = '$prid' GROUP BY noted_by,grouping_id") AS $ven){
            $data['vendor_app'][] = array(
                'group_id'=>$ven->grouping_id,
                'noted_by'=>$this->super_model->select_column_where("employees", "employee_name", "employee_id", $ven->noted_by),
                'approved_by'=>$this->super_model->select_column_where("employees", "employee_name", "employee_id", $ven->approved_by),
                'due_date'=>$ven->due_date,
            );
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');        
        $this->load->view('pr/pr_group',$data);
        $this->load->view('template/footer');
    }

    public function delete_vendor(){
        $pr_id=$this->uri->segment(3);
        $pr_vendors_id=$this->uri->segment(4);
        if($this->super_model->delete_where('pr_vendors', 'pr_vendors_id', $pr_vendors_id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."pr/pr_group/$pr_id'; </script>";
        }
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
        $data['employees'] = $this->super_model->select_all_order_by("employees","employee_name",'ASC');
        $data['prid'] = $prid;
        $data['group'] = $group;
        $data['category']=$category;
        $data['vendor'] = $this->super_model->custom_query("SELECT vendor_id, vendor_name, product_services FROM vendor_head WHERE product_services LIKE '%$category%' ORDER BY vendor_name ASC");
        $data['due_date']=$this->super_model->select_column_custom_where("pr_vendors",'due_date',"pr_id = '$prid' AND grouping_id='$group'");
        $data['noted_by']=$this->super_model->select_column_custom_where("pr_vendors",'noted_by',"pr_id = '$prid' AND grouping_id='$group'");
        $data['approved_by']=$this->super_model->select_column_custom_where("pr_vendors",'approved_by',"pr_id = '$prid' AND grouping_id='$group'");
        $this->load->view('template/header');
        $this->load->view('pr/choose_vendor', $data);
        $this->load->view('template/footer');
    }

    public function insert_vendor(){
        $pr_id = $this->input->post('pr_id');
        $group = $this->input->post('group');
        $due_date = $this->input->post('due_date');
        $noted_by = $this->input->post('noted_by');
        $approved_by = $this->input->post('approved_by');
        $vendor = $this->input->post('vendor_id');
        $count = $this->super_model->count_custom_where('pr_vendors',"pr_id='$pr_id' AND grouping_id='$group'");
        if($count==0){
            foreach($vendor as $ven){
                $data = array(
                    'pr_id'=>$pr_id,
                    'vendor_id'=>$ven,
                    'due_date'=>$due_date,
                    'noted_by'=>$noted_by,
                    'approved_by'=>$approved_by,
                    'grouping_id'=>$group
                );
                $this->super_model->insert_into('pr_vendors', $data);
            } 
        } else {
            foreach($vendor as $ven){
                $count_exist= $this->super_model->count_custom_where("pr_vendors","pr_id = '$pr_id' AND vendor_id = '$ven' AND grouping_id = '$group'");
                if($count_exist==0){
                    $data2 = array(
                            'pr_id'=>$pr_id,
                            'vendor_id'=>$ven,
                            'due_date'=>$due_date,
                            'noted_by'=>$noted_by,
                            'approved_by'=>$approved_by,
                            'grouping_id'=>$group
                    );

                 $this->super_model->insert_into('pr_vendors', $data2);
                }
            }
        } 
        $data = array(
            'due_date'=>$due_date,
            'noted_by'=>$noted_by,
            'approved_by'=>$approved_by
        );
        $this->super_model->update_custom_where('pr_vendors', $data,"pr_id='$pr_id' AND grouping_id='$group'");
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
        $prid=$this->input->post('pr_id');
        $timestamp = date("Y-m-d H:i:s");
        $rfq_format = date("Ym");
        $rfqdet=date('Y-m');
        $code = $this->super_model->select_column_where('pr_head','processing_code','pr_id',$prid);
        $rfq = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$prid);
        $rfqpr=explode("-", $rfq);
        $rfqs=$rfqpr[0];
        $rfqss=$rfqs;
        $rows=$this->super_model->count_custom_where("rfq_head","create_date LIKE '$rfqdet%'");
        /*if($rows==0){
            $rfq_no= $rfq_format."-1001";
        } else {
            $series = $this->super_model->get_max("rfq_series", "series","year_month LIKE '$rfqdet%'");
            $next=$series+1;
            $rfq_no = $rfq_format."-".$next;
        }*/
        if($rows==0){
            $rfq_no= $rfqss."-1001";
        } else {
            $series = $this->super_model->get_max("rfq_series", "series","year_month LIKE '$rfqdet%'");
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
        $this->super_model->insert_into("rfq_series", $rfq_data);

        foreach($this->super_model->select_row_where("pr_vendors", "pr_id", $prid) AS $vendors){
            $rows_head = $this->super_model->count_rows("rfq_head");
            if($rows_head==0){
                $rfq_id=1;
            } else {
                $max = $this->super_model->get_max("rfq_head", "rfq_id");
                $rfq_id = $max+1;
            }
            $new_rfq = $rfq_no."-".$vendors->grouping_id;
            $data_head = array(
                'rfq_id'=>$rfq_id,
                'rfq_no'=>$new_rfq,
                'vendor_id'=>$vendors->vendor_id,
                'pr_id'=>$prid,
                'grouping_id'=>$vendors->grouping_id,
                'noted_by'=>$vendors->noted_by,
                'approved_by'=>$vendors->approved_by,
                'quotation_date'=>$vendors->due_date,
                'rfq_date'=>$timestamp,
                'processing_code'=>$code,
                'prepared_by'=>$_SESSION['user_id'],
                'create_date'=>$timestamp,
                'saved'=>1
            );
            $this->super_model->insert_into("rfq_head", $data_head);
            foreach($this->super_model->select_custom_where("pr_details", "pr_id='$prid' AND grouping_id = '$vendors->grouping_id'") AS $details){
                $data_details = array(
                    'rfq_id'=>$rfq_id,
                    'pr_details_id'=>$details->pr_details_id,
                    'pn_no'=>$details->part_no,
                    'item_desc'=>$details->item_description,
                    'quantity'=>$details->quantity,
                    'uom'=>$details->uom,

                );
                $this->super_model->insert_into("rfq_details", $data_details);
            }
        }
        redirect(base_url().'rfq/rfq_list/');
    }

    public function create_rfq_group(){
        $prid=$this->input->post('pr_id');
        $group=$this->input->post('group');
        $timestamp = date("Y-m-d H:i:s");
        $rfq_format = date("Ym");
        $rfqdet=date('Y-m');
        $code = $this->super_model->select_column_where('pr_head','processing_code','pr_id',$prid);
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
        $rfq_prefix2=$rfqdetails[1];
        $rfq_prefix=$rfq_prefix1;
        $series=$rfq_prefix2;
        $rfq_data= array(
            'year_month'=>$rfq_prefix,
            'series'=>$series
        );
        $this->super_model->insert_into("rfq_series", $rfq_data);

        foreach($this->super_model->select_custom_where("pr_vendors", "pr_id='$prid' AND grouping_id = '$group'") AS $vendors){
            $rows_head = $this->super_model->count_rows("rfq_head");
            if($rows_head==0){
                $rfq_id=1;
            } else {
                $max = $this->super_model->get_max("rfq_head", "rfq_id");
                $rfq_id = $max+1;
            }
            $new_rfq = $rfq_no."-".$vendors->grouping_id;
            $data_head = array(
                'rfq_id'=>$rfq_id,
                'rfq_no'=>$new_rfq,
                'vendor_id'=>$vendors->vendor_id,
                'pr_id'=>$prid,
                'grouping_id'=>$vendors->grouping_id,
                'noted_by'=>$vendors->noted_by,
                'approved_by'=>$vendors->approved_by,
                'quotation_date'=>$vendors->due_date,
                'rfq_date'=>$timestamp,
                'processing_code'=>$code,
                'prepared_by'=>$_SESSION['user_id'],
                'create_date'=>$timestamp,
                'saved'=>1
            );
            $this->super_model->insert_into("rfq_head", $data_head);
            foreach($this->super_model->select_custom_where("pr_details", "pr_id='$prid' AND grouping_id = '$vendors->grouping_id'") AS $details){
                $data_details = array(
                    'rfq_id'=>$rfq_id,
                    'pr_details_id'=>$details->pr_details_id,
                    'pn_no'=>$details->part_no,
                    'item_desc'=>$details->item_description,
                    'quantity'=>$details->quantity,
                    'uom'=>$details->uom,

                );
                $this->super_model->insert_into("rfq_details", $data_details);
            }
        }
        redirect(base_url().'rfq/rfq_list/');
    }


    public function cancelled_pr(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $count = $this->super_model->count_custom_query("SELECT * FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE pd.cancelled = '1'");
        if($count!=0){
            foreach($this->super_model->custom_query("SELECT * FROM pr_head WHERE cancelled = '1'") AS $heads){
                
            $items = '';
                
                foreach($this->super_model->select_row_where("pr_details", "pr_id", $heads->pr_id) AS $det){
                    $items .= "-".$det->item_description."<br>"; 
                }

                $data['pr_head'][] = array(
                    'pr_id'=>$heads->pr_id,
                    'pr_no'=>$heads->pr_no,
                    'pr_date'=>$heads->date_prepared,
                    'urgency_num'=>$heads->urgency,
                    'cancelled_by'=> $this->super_model->select_column_where('users','fullname','user_id',$heads->cancelled_by),
                    'cancel_date'=> $heads->cancelled_date,
                    'cancel_reason'=> $heads->cancelled_reason,
                    'items'=>$items
                );
              
            }
        }else {
            $data['pr_head']=array();
        }

        $this->load->view('pr/cancelled_pr',$data);
        $this->load->view('template/footer');
    }

     public function add_vendor_rfq(){
        $prid = $this->input->post('pr_id');
        $group = $this->input->post('group');
        $vendor = $this->input->post('vendor');
        $pr_details_id = $this->input->post('pr_details_id');


        $count_exist = $this->super_model->count_custom_where("rfq_head","pr_id = '$prid' AND vendor_id = '$vendor' AND grouping_id = '$group'");
        if($count_exist!=0){
           ?>
           <script>
            alert('Vendor already existing in this PR Group.'); 
            window.location="<?php echo base_url(); ?>pr/purchase_request/<?php echo $prid; ?>";
            </script>    
            <?php
        } else {
              
                $prven = array(
                    'pr_id'=>$prid,
                    'vendor_id'=>$vendor,
                    'grouping_id'=>$group,
                    'due_date'=>$this->input->post('due_date'),
                    'noted_by'=>$this->input->post('noted'),
                    'approved_by'=>$this->input->post('approved')
                );

                $this->super_model->insert_into("pr_vendors", $prven);


                  $timestamp = date("Y-m-d H:i:s");
                $rfq_format = date("Ym");
                $rfqdet=date('Y-m');
                $code = $this->super_model->select_column_where('pr_head','processing_code','pr_id',$prid);
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
                $rfq_prefix2=$rfqdetails[1];
                $rfq_prefix=$rfq_prefix1;
                $series=$rfq_prefix2;
                $rfq_data= array(
                    'year_month'=>$rfq_prefix,
                    'series'=>$series
                );
                $this->super_model->insert_into("rfq_series", $rfq_data);

                $rows_head = $this->super_model->count_rows("rfq_head");
                if($rows_head==0){
                    $rfq_id=1;
                } else {
                    $max = $this->super_model->get_max("rfq_head", "rfq_id");
                    $rfq_id = $max+1;
                }
                $new_rfq = $rfq_no."-".$group;

                 $data_head = array(
                    'rfq_id'=>$rfq_id,
                    'rfq_no'=>$new_rfq,
                    'vendor_id'=>$vendor,
                    'pr_id'=>$prid,
                    'grouping_id'=>$group,
                    'rfq_date'=>$timestamp,
                    'processing_code'=>$code,
                    'noted_by'=>$this->input->post('noted'),
                    'approved_by'=>$this->input->post('approved'),
                    'prepared_by'=>$_SESSION['user_id'],
                    'create_date'=>$timestamp,
                    'saved'=>1,
                );
                $this->super_model->insert_into("rfq_head", $data_head);

                foreach($this->super_model->select_custom_where("pr_details", "pr_id='$prid' AND grouping_id = '$group'") AS $details){
                    $data_details = array(
                        'rfq_id'=>$rfq_id,
                        'pr_details_id'=>$details->pr_details_id,
                        'pn_no'=>$details->part_no,
                        'item_desc'=>$details->item_description,
                        'quantity'=>$details->quantity,
                        'uom'=>$details->uom,

                    );
                    $this->super_model->insert_into("rfq_details", $data_details);
                }

            redirect(base_url().'rfq/rfq_list/');      
        }
        
    }

}

?>