<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

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

     public function generate_pr_summary(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        redirect(base_url().'reports/pr_report/'.$year.'/'.$month);
    }

    public function generate_po_summary(){
        $year = $this->input->post('year');
        $month = $this->input->post('month');
        redirect(base_url().'reports/po_report/'.$year.'/'.$month);
    }

	public function pr_report(){
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $data['year']=$year;
        $data['month']=$month;
        if(empty($month)){
            $date = $year;
        } else {
         $date = $year."-".$month;
        }
        $data['date']=date('F Y', strtotime($date));

        foreach($this->super_model->custom_query("SELECT pd.*, ph.* FROM pr_details pd INNER JOIN pr_head ph ON pd.pr_id = ph.pr_id WHERE ph.date_prepared LIKE '$date%'") AS $pr){
            $po_id = $this->super_model->select_column_where('po_items', 'po_id', 'pr_details_id', $pr->pr_details_id);
            $sum_po_qty = $this->super_model->custom_query_single("total","SELECT sum(quantity) AS total FROM po_items WHERE pr_details_id = '$pr->pr_details_id'");

            if($sum_po_qty!=0){
                if($sum_po_qty < $pr->quantity){
                    $status = 'Partially Served';
                    $status_remarks = '';
                } else {
                    $dr_no = $this->super_model->select_column_where('po_dr', 'dr_no', 'po_id', $po_id);
                    $dr_date = $this->super_model->select_column_where('po_dr', 'dr_date', 'po_id', $po_id);

                    $status = 'Fully Served';
                    $status_remarks = date('m.d.y', strtotime($dr_date)) . " - Served DR# ".$dr_no;
                }
            } else {
                $cancelled_items = $this->super_model->select_column_where('pr_details', 'cancelled', 'pr_details_id', $pr->pr_details_id);
                if($cancelled_items==1){
                    $cancel_reason = $this->super_model->select_column_where('pr_details', 'cancelled_reason', 'pr_details_id', $pr->pr_details_id);
                    $cancel_date = $this->super_model->select_column_where('pr_details', 'cancelled_date', 'pr_details_id', $pr->pr_details_id);
                    $status = 'Cancelled';
                    $status_remarks = $cancel_reason ." " . date('m.d.y', strtotime($cancel_date));
                } else {

                    $count_rfq = $this->super_model->count_custom_where("rfq_details","pr_details_id = '$pr->pr_details_id'");
                    $count_aoq = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '0'");
                    $count_aoq_awarded = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '1'");
                    if($count_rfq!=0 && $count_aoq==0 && $count_aoq_awarded==0){
                        $status = 'Pending';
                        $status_remarks = 'For canvassing';
                    } else if($count_rfq!=0 && $count_aoq!=0 && $count_aoq_awarded==0){ 

                         $aoq_date = $this->super_model->custom_query_single("SELECT aoq_date FROM aoq_head ah INNER JOIN aoq_items ai ON ah.aoq_id = ai.aoq_id WHERE ai.pr_details_id= '$pr->pr_details_id' AND saved='1' AND awarded = '1'","aoq_date");
                         $status = 'Pending';
                         $status_remarks = 'AOQ Done - For TE ' .date('m.d.y', strtotime($aoq_date)) ;
                    }
                }
            }
            $data['pr'][] = array(
                'date_prepared'=>$pr->date_prepared,
                'pr_no'=>$pr->pr_no,
                'purpose'=>$pr->purpose,
                'enduse'=>$pr->enduse,
                'department'=>$pr->department,
                'requestor'=>$pr->requestor,
                'item_description'=>$pr->item_description,
                'qty'=>$pr->quantity,
                'uom'=>$pr->uom,
                'status'=>$status,
                'status_remarks'=>$status_remarks

            );
        }

        $this->load->view('template/header');        
        $this->load->view('reports/pr_report',$data);
        $this->load->view('template/footer');
    }

    public function po_report(){
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $data['year']=$year;
        $data['month']=$month;
        $date = $year."-".$month;
        $data['date']=date('F Y', strtotime($date));
        $po_date = date('Y-m', strtotime($date));
        $data['pr_no1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$po_date%'") AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);

            foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
                foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                    foreach($this->super_model->select_row_where('item','item_id',$i->aoq_items_id) AS $it){
                        //$uom=$this->super_model->select_column_where("unit",'unit_name','unit_id',$it->unit_id);
                        $item=$it->item_name." - ".$it->item_specs;
                    }
                    $partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$pr->pr_id' AND ai.aoq_items_id = '$i->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
                    $data['po'][]=array(
                        'po_id'=>$i->po_id,
                        'pr_no'=>$pr_no,
                        'enduse'=>$pr->enduse,
                        'purpose'=>$pr->purpose,
                        'requested_by'=>$pr->requestor,
                        'qty'=>$i->quantity,
                        'uom'=>$i->uom,
                        'item'=>$item,
                        'unit_price'=>$i->unit_price,
                        'notes'=>$pr->notes,
                        'po_id'=>$p->po_id,
                        'po_date'=>$p->po_date,
                        'po_no'=>$p->po_no,
                        'saved'=>$p->saved,
                        'cancelled'=>$p->cancelled,
                        'partial'=>$partial,
                        'supplier'=>$supplier,
                        'terms'=>$terms,
                    );
                }
            }
        }
        $this->load->view('template/header');        
        $this->load->view('reports/po_report',$data);
        $this->load->view('template/footer');
    }

    public function search_po(){
        if(!empty($this->input->post('year'))){
            $data['year'] = $this->input->post('year');
        } else {
            $data['year']= "null";
        }

        if(!empty($this->input->post('month'))){
            $data['month'] = $this->input->post('month');
        } else {
            $data['month']= "null";
        }

        if(!empty($this->input->post('pr_no'))){
            $data['pr_no'] = $this->input->post('pr_no');
        } else {
            $data['pr_no']= "null";
        }

        if(!empty($this->input->post('date_po'))){
            $data['date_po'] = $this->input->post('date_po');
        } else {
            $data['date_po']= "null";
        }

        if(!empty($this->input->post('po_no'))){
            $data['po_no'] = $this->input->post('po_no');
        } else {
            $data['po_no'] = "null";
        }

        if(!empty($this->input->post('purpose'))){
            $data['purpose'] = $this->input->post('purpose');
        } else {
            $data['purpose'] = "null";
        }

        if(!empty($this->input->post('enduse'))){
            $data['enduse'] = $this->input->post('enduse');
        } else {
            $data['enduse'] = "null";
        }

        if(!empty($this->input->post('requestor'))){
            $data['requestor'] = $this->input->post('requestor');
        } else {
            $data['requestor'] = "null";
        } 

        if(!empty($this->input->post('description'))){
            $data['description'] = $this->input->post('description');
        } else {
            $data['description'] = "null";
        } 

        if(!empty($this->input->post('supplier'))){
            $data['supplier'] = $this->input->post('supplier');
        } else {
            $data['supplier'] = "null";
        }

        $sql="";
        $filter = " ";

        if(!empty($this->input->post('pr_no'))){
            $pr_no = $this->input->post('pr_no');
            $sql.=" pp.pr_id = '$pr_no' AND";
            $filter .= "Pr No. - ".$this->super_model->select_column_where('pr_head', 'pr_no', 
                        'pr_id', $pr_no).", ";
        }

        if(!empty($this->input->post('date_po'))){
            $date_po = $this->input->post('date_po');
            $sql.=" ph.po_date LIKE '%$date_po%' AND";
            $filter .= "PO Date. - ".$date_po.", ";
        }

        if(!empty($this->input->post('po_no'))){
            $po_no = $this->input->post('po_no');
            $sql.=" ph.po_no LIKE '%$po_no%' AND";
            $filter .= "PO No. - ".$po_no.", ";
        }

        if(!empty($this->input->post('purpose'))){
            $purpose = $this->input->post('purpose');
            $sql.=" pp.purpose LIKE '%$purpose%' AND";
            $filter .= "Purpose - ".$purpose.", ";
        }

        if(!empty($this->input->post('enduse'))){
            $enduse = $this->input->post('enduse');
            $sql.=" pp.enduse LIKE '%$enduse%' AND";
            $filter .= "Enduse - ".$enduse.", ";
        }

        if(!empty($this->input->post('requestor'))){
            $requestor = $this->input->post('requestor');
            $sql.=" pp.requestor = '$requestor' AND";
            $filter .= "Requestor - ".$requestor.", ";
        }

        if(!empty($this->input->post('description'))){
            $description = $this->input->post('description');
            $sql.=" pi.aoq_items_id = '$description' AND";
            $filter .= "Item Description - ".$this->super_model->select_column_where('item', 'item_name', 
                        'item_id', $description).", ";
        }

        if(!empty($this->input->post('supplier'))){
            $supplier = $this->input->post('supplier');
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= "Supplier - ".$this->super_model->select_column_where('vendor_head', 'vendor_name', 
                        'vendor_id', $supplier).", ";
        }

        $query=substr($sql, 0, -3);
        $data['filt']=substr($filter, 0, -2);
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $data['year']=$year;
        $data['month']=$month;
        $date = $year."-".$month;
        $data['date']=date('F Y', strtotime($date));
        $po_date = date('Y-m', strtotime($date));
        $data['pr_no1']=$this->super_model->select_custom_where('pr_head',"cancelled='0'");
        $data['employees']=$this->super_model->select_all_order_by('employees',"employee_name",'ASC');
        $data['vendors']=$this->super_model->select_all_order_by('vendor_head',"vendor_name",'ASC');
        $data['items']=$this->super_model->select_all_order_by('item',"item_name",'ASC');
        foreach($this->super_model->custom_query("SELECT * FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id INNER JOIN po_pr pp ON pi.po_id = pp.po_id  WHERE ".$query) AS $p){
            $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
            $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
            $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id);
            foreach($this->super_model->select_row_where('item','item_id',$p->aoq_items_id) AS $it){
                $item=$it->item_name." - ".$it->item_specs;
            }
            $partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$p->pr_id' AND ai.aoq_items_id = '$p->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
            $data['po'][]=array(
                'po_id'=>$p->po_id,
                'pr_no'=>$pr_no,
                'enduse'=>$p->enduse,
                'purpose'=>$p->purpose,
                'requested_by'=>$p->requestor,
                'qty'=>$p->quantity,
                'uom'=>$p->uom,
                'item'=>$item,
                'unit_price'=>$p->unit_price,
                'notes'=>$p->notes,
                'po_id'=>$p->po_id,
                'po_date'=>$p->po_date,
                'po_no'=>$p->po_no,
                'saved'=>$p->saved,
                'cancelled'=>$p->cancelled,
                'partial'=>$partial,
                'supplier'=>$supplier,
                'terms'=>$terms,
            );
        }
        $this->load->view('template/header');        
        $this->load->view('reports/po_report',$data);
        $this->load->view('template/footer');
    }

    public function export_po(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="PO Summary.xlsx";
        $year=$this->uri->segment(3);
        $month=$this->uri->segment(4);
        $pr_no1=$this->uri->segment(5);
        $date_po=$this->uri->segment(6);
        $po_no=$this->uri->segment(7);
        $purpose1=$this->uri->segment(8);
        $enduse1=$this->uri->segment(9);
        $requestor=$this->uri->segment(10);
        $description=$this->uri->segment(11);
        $supplier=$this->uri->segment(12);

        $sql="";
        $filter = " ";

        if($pr_no1!='null'){
            $sql.=" pp.pr_id = '$pr_no1' AND";
            $filter .= $this->super_model->select_column_where('pr_head', 'pr_no', 'pr_id', $pr_no1);
        }

        if($date_po!='null'){
            $sql.=" ph.po_date LIKE '%$date_po%' AND";
            $filter .= $date_po;
        }

        if($po_no!='null'){
            $sql.=" ph.po_no LIKE '%$po_no%' AND";
            $filter .= $po_no;
        }

        if($purpose1!='null'){
            $sql.=" pp.purpose LIKE '%$purpose1%' AND";
            $filter .= $purpose1;
        }

        if($enduse1!='null'){
            $sql.=" pp.enduse LIKE '%$enduse1%' AND";
            $filter .= $enduse1;
        }

        if($requestor!='null'){
            $sql.=" pp.requestor LIKE '%$requestor%' AND";
            $filter .= $requestor;
        }

        if($description!='null'){
            $sql.=" pi.aoq_items_id = '$description' AND";
            $filter .= $this->super_model->select_column_where('item', 'item_name', 'item_id', $description);
        }

        if($supplier!='null'){
            $sql.=" ph.vendor_id = '$supplier' AND";
            $filter .= $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $supplier);
        }

        $query=substr($sql, 0, -3);
        $filt=substr($filter, 0, -2);

        $data['year']=$year;
        $data['month']=$month;
        $date = $year."-".$month;
        $monthyear=date('F Y', strtotime($date));
        $po_date = date('Y-m', strtotime($date));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "PO Summary $monthyear");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "PURCHASE ORDER");
        $styleArray1 = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "PR No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', "Purpose");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Enduse");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', "Date of PO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "PO No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F4', "Requested By");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G4', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "UOM");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I4', "Item Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J4', "Status");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K4', "Supplier");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L4', "Payment Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M4', "Unit Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N4', "Total Price");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O4', "Remarks");
        foreach(range('A','O') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:O4')->applyFromArray($styleArray1);
        if($filt!=''){
            $num = 5;
            foreach($this->super_model->custom_query("SELECT * FROM po_head ph INNER JOIN po_items pi ON ph.po_id = pi.po_id INNER JOIN po_pr pp ON pi.po_id = pp.po_id  WHERE ".$query) AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$p->pr_id);
                foreach($this->super_model->select_row_where('item','item_id',$p->aoq_items_id) AS $it){
                    $item=$it->item_name." - ".$it->item_specs;
                }
                $total=$p->quantity*$p->unit_price;
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                $partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$p->pr_id' AND ai.aoq_items_id = '$p->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$p->purpose");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$p->enduse");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->po_date");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->po_no");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$p->requestor");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$p->quantity");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$p->uom");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$item");
                if($partial==1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Partially Served");
                }else if($p->saved==1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Fully Served");
                }else if($p->cancelled==1){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Cancelled");
                }
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$supplier");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$terms");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$p->unit_price");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$total");
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$p->notes");
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":O".$num)->applyFromArray($styleArray);
                $num++;
            }
        }else {
            $num = 5;
            foreach($this->super_model->select_custom_where("po_head","po_date LIKE '%$po_date%'") AS $p){
                $terms =  $this->super_model->select_column_where('vendor_head','terms','vendor_id',$p->vendor_id);
                $supplier = $this->super_model->select_column_where('vendor_head','vendor_name','vendor_id',$p->vendor_id);
                foreach($this->super_model->select_row_where('po_pr','po_id',$p->po_id) AS $pr){
                    $pr_no = $this->super_model->select_column_where('pr_head','pr_no','pr_id',$pr->pr_id);
                    foreach($this->super_model->select_row_where('po_items','po_id',$p->po_id) AS $i){
                        foreach($this->super_model->select_row_where('item','item_id',$i->aoq_items_id) AS $it){
                            $item=$it->item_name." - ".$it->item_specs;
                        }
                        $total=$i->quantity*$i->unit_price;
                        $styleArray = array(
                            'borders' => array(
                                'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        );
                        $partial = $this->super_model->count_custom_query("SELECT ah.aoq_id FROM aoq_head ah INNER JOIN aoq_offers ai ON ah.aoq_id = ai.aoq_id WHERE ah.pr_id = '$pr->pr_id' AND ai.aoq_items_id = '$i->aoq_items_id' AND ai.balance != '0' AND ai.balance != ai.quantity GROUP BY ai.aoq_items_id");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, "$pr_no");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, "$pr->purpose");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, "$pr->enduse");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, "$p->po_date");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, "$p->po_no");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, "$pr->requestor");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, "$i->quantity");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, "$i->uom");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, "$item");
                        if($partial==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Partially Served");
                        }else if($p->saved==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Fully Served");
                        }else if($p->cancelled==1){
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$num, "Cancelled");
                        }
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$num, "$supplier");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$num, "$terms");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$num, "$i->unit_price");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$num, "$total");
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$num, "$pr->notes");
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$num.":O".$num)->applyFromArray($styleArray);
                        $num++;
                    }
                }
            }
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="PO Summary.xlsx"');
        readfile($exportfilename);
    }

}
?>