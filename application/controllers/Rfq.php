<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rfq extends CI_Controller {

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


	public function rfq_list(){

        //$head_count = $this->super_model->count_custom_where("rfq_head","served='0' AND cancelled = '0' ORDER BY rfq_date DESC");
        $head_count = $this->super_model->count_custom_query("SELECT rh.* FROM rfq_head rh INNER JOIN pr_head pd ON rh.pr_id = pd.pr_id WHERE pd.cancelled='0' AND rh.served='0' AND rh.cancelled = '0'");
        if($head_count!=0){
            //foreach($this->super_model->select_custom_where("rfq_head", "served='0' AND cancelled = '0' ORDER BY rfq_date DESC") AS $head){
            foreach($this->super_model->custom_query("SELECT rh.* FROM rfq_head rh INNER JOIN pr_head pd ON rh.pr_id = pd.pr_id WHERE pd.cancelled='0' AND rh.served='0' AND rh.cancelled = '0'") AS $head){
                $data['head'][]= array(
                    'rfq_id'=>$head->rfq_id,
                    'rfq_no'=>$head->rfq_no,
                    'pr_id'=>$head->pr_id,
                    'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id),
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id),
                    'rfq_date'=>$head->rfq_date,
                    'notes'=>$head->notes,
                    'completed'=>$head->completed
                    
                );
            }

            foreach($this->super_model->custom_query("SELECT rd.* FROM rfq_details rd INNER JOIN pr_details pd ON rd.pr_details_id = pd.pr_details_id WHERE pd.cancelled=0") AS $it){
                $data['items'][] = array(
                    'rfq_id'=>$it->rfq_id,
                    'item'=>$it->item_desc,
                );
            }
        } else {
            $data= array();
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfq/rfq_list',$data);
        $this->load->view('template/footer');
    }  

    public function add_notes(){
        $rfq_id = $this->input->post('rfq_id');
        $notes = $this->input->post('notes');
        $data = array(
            'notes'=>$notes,
        );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_list');
        }

    }

    public function rfq_outgoing(){
        $rfq_id=$this->uri->segment(3);
        $data['rfq_id']=$rfq_id;
        foreach($this->super_model->select_row_where('rfq_head', 'rfq_id', $rfq_id) AS $head){
            $data['rfq_date']= $head->rfq_date;
            $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id);
            $data['phone']= $this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $head->vendor_id);
            $data['rfq_no']= $head->rfq_no;
            $data['pr_no']= $this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id);
            $data['notes']= $head->notes;
            $data['code']= $head->processing_code;
            $data['noted']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->noted_by);
            $data['approved']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->approved_by);
            $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $head->prepared_by);
            $data['due']= $head->quotation_date;
            $data['saved']= $head->saved;
            $data['cancelled']= $head->cancelled;
            $data['served']= $head->served;
            $data['completed']= $head->completed;
        }

       // $data['items'] = $this->super_model->select_row_where('rfq_details', 'rfq_id', $rfq_id);
        $data['items'] = $this->super_model->select_row_where_order_by('rfq_details', 'rfq_id', $rfq_id, 'pr_details_id', 'ASC');
     
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('template/header');
        $this->load->view('rfq/rfq_outgoing',$data);
        $this->load->view('template/footer');
    }

    public function export_rfq(){
        $rfq_id=$this->uri->segment(3);
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="RFQ.xlsx";
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('Logo');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath(LOGO);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setOffsetY(-30);
        $objDrawing->setOffsetX(10);
        $objDrawing->setHeight(100);
        $objDrawing->setWidth(100);
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        /*$gdImage = imagecreatefrompng(LOGO);
        $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
        $objDrawing->setName('Sample image');
        $objDrawing->setDescription('Sample image');
        $objDrawing->setImageResource($gdImage);
        $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
        $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
        $objDrawing->setHeight(40);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', COMPANY_NAME);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', TIN);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', ADDRESS);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', TEL_NO);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', TELFAX);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C6', ADDRESS_2);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C7', "REQUEST FOR QUOTATION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B9', "Date:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B10', "Supplier:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F9', "RFQ No.:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I9', "Urg:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F10', "Tel. No.:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F11', "PR No.:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A13', "No.");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B13', "Qty");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C13', "Unit");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D13', "Item Description");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H13', "Brand/Offer");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J13', "Unit Price");
        foreach($this->super_model->select_row_where('rfq_head', 'rfq_id', $rfq_id) AS $head){
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $vendor= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id);
            $phone= $this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $head->vendor_id);
            $pr_no= $this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id);
            $noted= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->noted_by);
            $approved= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->approved_by);
            $prepared= $this->super_model->select_column_where("users", "fullname", "user_id", $head->prepared_by);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C9', date('F j, Y',strtotime($head->rfq_date)));
            $objPHPExcel->setActiveSheetIndex()->setCellValue('C10', $vendor);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G9', $head->rfq_no);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('J9', $head->processing_code);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G10', $phone);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G11', $pr_no);
        

            $num=14;
            $x=1;
            foreach($this->super_model->select_row_where_order_by('rfq_details', 'rfq_id', $rfq_id, 'pr_details_id', 'ASC') AS $item){
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$num, $x);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$num, $item->quantity);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$num, $item->uom);
                $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$num, $item->item_desc.", ".$this->get_pn($item->pr_details_id));
                $objPHPExcel->getActiveSheet()->mergeCells('D'.$num.':G'.$num);
                $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.':I'.$num);
                $objPHPExcel->getActiveSheet()->mergeCells('J'.$num.':K'.$num);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$num)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num.':K'.$num)->applyFromArray($styleArray);
                $num++;
                $x++;
            }

            $a = $num+1;
            $b = $num+2;
            $c = $num+3;
            $d = $num+4;
            $e = $num+5;
            $f = $num+6;
            $g = $num+7;
            $h = $num+9;
            $bh = $num+10;
            $dh = $num+11;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$c)->getAlignment()->setIndent(10);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$d)->getAlignment()->setIndent(10);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$e)->getAlignment()->setIndent(10);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setIndent(10);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$g)->getAlignment()->setIndent(10);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$h)->getAlignment()->setIndent(7);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$dh)->getAlignment()->setIndent(9);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$a, '1. Quotation must be submitted on or before '.$head->quotation_date);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$b, 'Please Fill - Up :');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$c, 'a. Price Validity');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$d, 'b. Payment Terms');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$e, 'c. Delivery Time');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$f, "d. Item's Warranty");
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$g, 'e. In-land Freight');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$c, '30 Days');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$d, 'n30');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$g, 'Included');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$g, 'Not Included');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$h, 'Prepared By:');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$h, 'Noted By:');
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$h, 'Approved By:');
            if($head->saved==0){
                $fullname = $_SESSION['fullname'];
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$dh, $fullname);
            }else {
                $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$dh, $prepared);
            }
            $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$dh, $noted);
            $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$dh, $approved);

            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$c.':G'.$c)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$bh.':C'.$bh)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$bh.':F'.$bh)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$bh.':I'.$bh)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$c.':G'.$c)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$d.':G'.$d)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$e.':G'.$e)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$f.':G'.$f)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$g.':G'.$g)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        }
        $objPHPExcel->getActiveSheet()->mergeCells('C1:H1');
        $objPHPExcel->getActiveSheet()->mergeCells('C2:H2');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:H3');
        $objPHPExcel->getActiveSheet()->mergeCells('C4:H4');
        $objPHPExcel->getActiveSheet()->mergeCells('C5:H5');
        $objPHPExcel->getActiveSheet()->mergeCells('C6:H6');
        $objPHPExcel->getActiveSheet()->mergeCells('C7:H7');
        $objPHPExcel->getActiveSheet()->mergeCells('C9:D9');
        $objPHPExcel->getActiveSheet()->mergeCells('C10:D10');
        $objPHPExcel->getActiveSheet()->mergeCells('G9:H9');
        $objPHPExcel->getActiveSheet()->mergeCells('G10:H10');
        $objPHPExcel->getActiveSheet()->mergeCells('G11:H11');
        $objPHPExcel->getActiveSheet()->mergeCells('D13:G13');
        $objPHPExcel->getActiveSheet()->mergeCells('H13:I13');
        $objPHPExcel->getActiveSheet()->mergeCells('J13:K13');
        $objPHPExcel->getActiveSheet()->getStyle('A13:K13')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A13:K13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true)->setName('Arial Black');
        $objPHPExcel->getActiveSheet()->getStyle('A13:K13')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C9:D9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C10:D10')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G9:H9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G10:H10')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('G11:H11')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('J9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
        unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="RFQ.xlsx"');
        readfile($exportfilename);
    } 


    // public function export_rfq(){
    //     $rfq_id=$this->uri->segment(3);
    //     require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
    //     $objPHPExcel = new PHPExcel();
    //     $exportfilename="RFQ.xlsx";
    //     $gdImage = imagecreatefrompng('assets/img/logo_cenpri.png');
    //     $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
    //     $objDrawing->setName('Sample image');
    //     $objDrawing->setDescription('Sample image');
    //     $objDrawing->setImageResource($gdImage);
    //     $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
    //     $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    //     $objDrawing->setHeight(35);
    //     $objDrawing->setCoordinates('A1');
    //     $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
    //     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //     $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', "CENTRAL NEGROS POWER RELIABILITY, INC.");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Office: 88 Corner Rizal-Mabini Sts., Bacolod City");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', "Tel. No.: (034) 435-1932/476-7382");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Telefax: (034) 435-1932");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "Plant Site: Purok San Jose, Barangay Calumangan, Bago City");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C7', "REQUEST FOR QUOTATION");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B9', "Date:");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B10', "Supplier:");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F9', "RFQ No.:");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I9', "Urg:");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F10', "Tel. No.:");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F11', "PR No.:");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A13', "No.");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B13', "Unit");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C13', "Item Description");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H13', "Brand/Offer");
    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J13', "Unit Price");
    //     foreach($this->super_model->select_row_where('rfq_head', 'rfq_id', $rfq_id) AS $head){
    //         $styleArray = array(
    //             'borders' => array(
    //                 'allborders' => array(
    //                   'style' => PHPExcel_Style_Border::BORDER_THIN
    //                 )
    //             )
    //         );
    //         $vendor= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id);
    //         $phone= $this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $head->vendor_id);
    //         $pr_no= $this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id);
    //         $noted= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->noted_by);
    //         $approved= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->approved_by);
    //         $prepared= $this->super_model->select_column_where("users", "fullname", "user_id", $head->prepared_by);
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('C9', date('F j, Y',strtotime($head->rfq_date)));
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('C10', $vendor);
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('G9', $head->rfq_no);
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('J9', $head->processing_code);
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('G10', $phone);
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('G11', $pr_no);
        

    //         $num=14;
    //         $x=1;
    //         foreach($this->super_model->select_row_where_order_by('rfq_details', 'rfq_id', $rfq_id, 'item_desc', 'ASC') AS $item){
    //             $styleArray = array(
    //                 'borders' => array(
    //                     'allborders' => array(
    //                       'style' => PHPExcel_Style_Border::BORDER_THIN
    //                     )
    //                 )
    //             );
    //             $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$num, $x);
    //             $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$num, $item->uom);
    //             $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$num, $item->item_desc);
    //             $objPHPExcel->getActiveSheet()->mergeCells('C'.$num.':G'.$num);
    //             $objPHPExcel->getActiveSheet()->mergeCells('H'.$num.':I'.$num);
    //             $objPHPExcel->getActiveSheet()->mergeCells('J'.$num.':K'.$num);
    //             $objPHPExcel->getActiveSheet()->getStyle('A'.$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //             $objPHPExcel->getActiveSheet()->getStyle('A'.$num.':K'.$num)->applyFromArray($styleArray);
    //             $num++;
    //             $x++;
    //         }

    //         $a = $num+1;
    //         $b = $num+2;
    //         $c = $num+3;
    //         $d = $num+4;
    //         $e = $num+5;
    //         $f = $num+6;
    //         $g = $num+7;
    //         $objPHPExcel->getActiveSheet()->getStyle('A'.$c)->getAlignment()->setIndent(10);
    //         $objPHPExcel->getActiveSheet()->getStyle('A'.$d)->getAlignment()->setIndent(10);
    //         $objPHPExcel->getActiveSheet()->getStyle('A'.$e)->getAlignment()->setIndent(10);
    //         $objPHPExcel->getActiveSheet()->getStyle('A'.$f)->getAlignment()->setIndent(10);
    //         $objPHPExcel->getActiveSheet()->getStyle('A'.$g)->getAlignment()->setIndent(10);
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$a, '1. Quotation must be submitted on or before '.$head->quotation_date);
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$b, 'Please Fill - Up :');
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$c, 'a. Price Validity');
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$d, 'b. Payment Terms');
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$e, 'c. Delivery Time');
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$f, "d. Item's Warranty");
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$g, 'e. In-land Freight');
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$c, '30 Days');
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$d, 'n30');
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$g, 'Included');
    //         $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$g, 'Not Included');

    //         $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    //         $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);

    //         $objPHPExcel->getActiveSheet()->getStyle('E'.$c.':G'.$c)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //         $objPHPExcel->getActiveSheet()->getStyle('E'.$c.':G'.$c)->applyFromArray($styleArray);
    //         $objPHPExcel->getActiveSheet()->getStyle('E'.$d.':G'.$d)->applyFromArray($styleArray);
    //         $objPHPExcel->getActiveSheet()->getStyle('E'.$e.':G'.$e)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //         $objPHPExcel->getActiveSheet()->getStyle('E'.$f.':G'.$f)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //         $objPHPExcel->getActiveSheet()->getStyle('E'.$g.':G'.$g)->applyFromArray($styleArray);
    //         $objPHPExcel->getActiveSheet()->getStyle('G'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


    //     }
    //     $objPHPExcel->getActiveSheet()->mergeCells('C1:H1');
    //     $objPHPExcel->getActiveSheet()->mergeCells('C2:H2');
    //     $objPHPExcel->getActiveSheet()->mergeCells('C3:H3');
    //     $objPHPExcel->getActiveSheet()->mergeCells('C4:H4');
    //     $objPHPExcel->getActiveSheet()->mergeCells('C5:H5');
    //     $objPHPExcel->getActiveSheet()->mergeCells('C7:H7');
    //     $objPHPExcel->getActiveSheet()->mergeCells('C9:D9');
    //     $objPHPExcel->getActiveSheet()->mergeCells('C10:D10');
    //     $objPHPExcel->getActiveSheet()->mergeCells('G9:H9');
    //     $objPHPExcel->getActiveSheet()->mergeCells('G10:H10');
    //     $objPHPExcel->getActiveSheet()->mergeCells('G11:H11');
    //     $objPHPExcel->getActiveSheet()->mergeCells('C13:G13');
    //     $objPHPExcel->getActiveSheet()->mergeCells('H13:I13');
    //     $objPHPExcel->getActiveSheet()->mergeCells('J13:K13');
    //     $objPHPExcel->getActiveSheet()->getStyle('A13:K13')->getFont()->setBold(true);
    //     $objPHPExcel->getActiveSheet()->getStyle('A13:K13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //     $objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true)->setName('Arial Black');
    //     $objPHPExcel->getActiveSheet()->getStyle('A13:K13')->applyFromArray($styleArray);
    //     $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //     $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //     $objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //     $objPHPExcel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //     $objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //     $objPHPExcel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    //     $objPHPExcel->getActiveSheet()->getStyle('C9:D9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //     $objPHPExcel->getActiveSheet()->getStyle('C10:D10')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //     $objPHPExcel->getActiveSheet()->getStyle('G9:H9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //     $objPHPExcel->getActiveSheet()->getStyle('G10:H10')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //     $objPHPExcel->getActiveSheet()->getStyle('G11:H11')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //     $objPHPExcel->getActiveSheet()->getStyle('J9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    //     $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //     if (file_exists($exportfilename))
    //     unlink($exportfilename);
    //     $objWriter->save($exportfilename);
    //     unset($objPHPExcel);
    //     unset($objWriter);   
    //     ob_end_clean();
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="RFQ.xlsx"');
    //     readfile($exportfilename);
    // }


    public function save_rfq(){
        $rfq_id = $this->input->post('rfq_id');
        /*$notes = $this->input->post('notes');
        $due = $this->input->post('due');
        $noted = $this->input->post('noted');
        $approved = $this->input->post('approved');*/
        $data = array(
            /*'notes'=>$notes,
            'quotation_date'=>$due,
            'noted_by'=>$noted,
            'approved_by'=>$approved,*/
            'saved'=>1,
            //'prepared_by'=>$_SESSION['user_id']
        );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_outgoing/'.$rfq_id);
        }

    }

    public function complete_rfq(){
         $rfq_id=$this->uri->segment(3);
          $data = array(
            'completed'=>1
          );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_list/', 'refresh');
        }
    }

     public function serve_rfq(){
         $rfq_id=$this->uri->segment(3);
          $data = array(
            'served'=>1
          );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_list/', 'refresh');
        }
    }

     public function cancel_rfq(){
         $rfq_id=$this->input->post('rfq_id');
         //echo "***".$rfq_id;
         $date=date('Y-m-d H:i:s');
          $data = array(
            'cancelled'=>1,
            'cancel_reason'=>$this->input->post('reason'),
            'cancelled_date'=>$date,
            'cancelled_by'=>$_SESSION['user_id']

          );
        if($this->super_model->update_where("rfq_head", $data, "rfq_id", $rfq_id)){
             redirect(base_url().'rfq/rfq_list/', 'refresh');
        }
    }

    public function rfq_incoming(){
        $rfq_id=$this->uri->segment(3);
        $data['rfq_id']=$rfq_id;
        foreach($this->super_model->select_row_where('rfq_head', 'rfq_id', $rfq_id) AS $head){
            $data['rfq_date']= $head->rfq_date;
            $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id);
            $data['phone']= $this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $head->vendor_id);
            $data['rfq_no']= $head->rfq_no;
            $data['pr_no']= $this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id);
            $data['notes']= $head->notes;
            $data['noted']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->noted_by);
            $data['approved']= $this->super_model->select_column_where("employees", "employee_name", "employee_id", $head->approved_by);
            $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $head->prepared_by);
            $data['due']= $head->quotation_date;
            $data['saved']= $head->saved;
        }

         $data['items'] = $this->super_model->select_row_where('rfq_details', 'rfq_id', $rfq_id);
        
        $this->load->view('template/header');
        $this->load->view('rfq/rfq_incoming',$data);
        $this->load->view('template/footer');
    } 
    public function served_rfq(){

        $head_count = $this->super_model->count_custom_where("rfq_head","served='1' ORDER BY rfq_date DESC");
        if($head_count!=0){
        //foreach($this->super_model->select_all_order_by("rfq_head", "rfq_date", "DESC") AS $head){

        foreach($this->super_model->select_custom_where("rfq_head", "served='1' ORDER BY rfq_date DESC") AS $head){
            $data['head'][]= array(
                'rfq_id'=>$head->rfq_id,
                'rfq_no'=>$head->rfq_no,
                'pr_id'=>$head->pr_id,
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id),
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id),
                'rfq_date'=>$head->rfq_date,
                'notes'=>$head->notes,
                'completed'=>$head->completed
                
            );
        }


         foreach($this->super_model->custom_query("SELECT rd.* FROM rfq_details rd INNER JOIN pr_details pd ON rd.pr_details_id = pd.pr_details_id WHERE pd.cancelled=0") AS $it){
                $data['items'][] = array(
                    'rfq_id'=>$it->rfq_id,
                    'item'=>$it->item_desc,
                );
            }
        } else {
            $data= array();
        }
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfq/served_rfq',$data);
        $this->load->view('template/footer');
    } 
    public function cancelled_rfq(){
          $head_count = $this->super_model->count_custom_where("rfq_head","cancelled='1' ORDER BY rfq_date DESC");
        if($head_count!=0){
        //foreach($this->super_model->select_all_order_by("rfq_head", "rfq_date", "DESC") AS $head){

        foreach($this->super_model->select_custom_where("rfq_head", "cancelled='1' ORDER BY rfq_date DESC") AS $head){
            $data['head'][]= array(
                'rfq_id'=>$head->rfq_id,
                'rfq_no'=>$head->rfq_no,
                'pr_id'=>$head->pr_id,
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id),
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $head->vendor_id),
                'rfq_date'=>$head->rfq_date,
                'notes'=>$head->notes,
                'completed'=>$head->completed
                
            );
        }


         foreach($this->super_model->custom_query("SELECT rd.* FROM rfq_details rd INNER JOIN pr_details pd ON rd.pr_details_id = pd.pr_details_id WHERE pd.cancelled=0") AS $it){
                $data['items'][] = array(
                    'rfq_id'=>$it->rfq_id,
                    'item'=>$it->item_desc,
                );
            }
        } else {
            $data= array();
        }

        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfq/cancelled_rfq',$data);
        $this->load->view('template/footer');
    } 

    public function get_pn($pr_details_id){
        $name = $this->super_model->select_column_where("pr_details", "part_no", "pr_details_id", $pr_details_id);
        return $name;
    }

}

?>