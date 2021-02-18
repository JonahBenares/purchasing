<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

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

	public function item_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $data['item'] = $this->super_model->select_all_order_by('item', 'item_name', 'ASC');
        $data['unit'] = $this->super_model->select_all_order_by('unit', 'unit_name', 'ASC');
        $this->load->view('items/item_list',$data);
        $this->load->view('template/footer');
    }

    public function insert_item(){
        $item = trim($this->input->post('item')," ");
        $spec = trim($this->input->post('spec')," ");
        $brand = trim($this->input->post('brand')," ");
        //$unit = trim($this->input->post('unit')," ");
        $pn = trim($this->input->post('pn')," ");
        $data = array(
            'item_name'=>$item,
            'item_specs'=>$spec,
            'brand_name'=>$brand,
            //'unit_id'=>$unit,
            'part_no'=>$pn,
        );
        if($this->super_model->insert_into("item", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."index.php/items/item_list'; </script>";
        }
    }

    public function update_item(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['item'] = $this->super_model->select_row_where('item', 'item_id', $id);
        /*$data['unit'] = $this->super_model->select_all_order_by('unit', 'unit_name', 'ASC');*/
        $this->load->view('items/update_item',$data);
        $this->load->view('template/footer');
    }

    public function edit_item(){
        $data = array(
            'item_name'=>$this->input->post('item'),
            'item_specs'=>$this->input->post('spec'),
            'brand_name'=>$this->input->post('brand'),
            //'unit_id'=>$this->input->post('unit'),
            'part_no'=>$this->input->post('pn'),
        );
        $item_id = $this->input->post('item_id');
            if($this->super_model->update_where('item', $data, 'item_id', $item_id)){
            echo "<script>alert('Successfully Updated!'); window.opener.location.reload(); window.close();</script>";
        }
    }

    public function delete_item(){
        $id=$this->uri->segment(3);
        if($this->super_model->delete_where('item', 'item_id', $id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."index.php/items/item_list'; </script>";
        }
    }

    public function item_details(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        foreach($this->super_model->select_row_where('item', 'item_id', $id) AS $i){
            $data['item'][]=array(
                'item_id'=>$i->item_id,
                'item_name'=>$i->item_name,
                //'unit'=>$this->super_model->select_column_where("unit",'unit_name','unit_id',$i->unit_id),
                'item_spec'=>$i->item_specs,
                'brand_name'=>$i->brand_name,
                'pn_no'=>$i->part_no,
                'unit_price'=>$i->unit_price,
                'offer_date'=>$i->offer_date
            );
        }
        $row = $this->super_model->count_rows_where("vendor_details",'item_id',$id);
        if($row!=0){
            foreach($this->super_model->select_row_where('vendor_details','item_id',$id) AS $v){
                foreach($this->super_model->select_row_where('vendor_head','vendor_id',$v->vendor_id) AS $vd){
                    $data['vendors'][]=array(
                        'vendor_id'=>$vd->vendor_id,
                        'vendor'=>$vd->vendor_name,
                        'phn_no'=>$vd->phone_number,
                        'address'=>$vd->address,
                        'terms'=>$vd->terms,
                        'notes'=>$vd->notes,
                    );
                }
            }
        }else {
            $data['vendors']=array();
        }
        $this->load->view('items/item_details',$data);
        $this->load->view('template/footer');
    }

    public function export_items(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $id=$this->uri->segment(3);
        foreach($this->super_model->select_row_where('item', 'item_id', $id) AS $info){
            $item = $info->item_name;
            $brand = $info->brand_name;
            $item_specs = $info->item_specs;
            $part_no = $info->part_no;
        }
        $exportfilename="item_details.xlsx";
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "ITEM DETAILS");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "Item Name:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', $item);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', "Brand:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', $brand);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "Specifications:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', $item_specs);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Part Number:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', $part_no);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', "VENDOR LIST");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7', "Vendor Name");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B7', "Address");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C7', "Phone Number");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D7', "Fax Number");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E7', "Email");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F7', "Terms");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G7', "Type");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H7', "Contact Person");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I7', "Notes");

        $num=7;
        foreach($this->super_model->select_join_where('vendor_head','vendor_details',"item_id = '$id'",'vendor_id') AS $fetch){
            $num++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num, $fetch->vendor_name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num, $fetch->address);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num, $fetch->phone_number);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num, $fetch->fax_number);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$num, $fetch->email);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$num, $fetch->terms);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$num, $fetch->type);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$num, $fetch->contact_person);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$num, $fetch->notes);
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A7:I7')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('A6:I6')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A6:I6');
        $objPHPExcel->getActiveSheet()->getStyle('A6:I6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D4')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A6:I'.$num)->applyFromArray($styleArray);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
        unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="item_details.xlsx"');
        readfile($exportfilename);
    }
}
?>