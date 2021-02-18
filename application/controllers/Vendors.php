<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors extends CI_Controller {

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
	public function view_vendors_per_item(){
        $this->load->view('template/header');
        $id=$this->uri->segment(3);
        foreach($this->super_model->select_row_where('vendor_head','vendor_id',$id) AS $vd){
            $data['vendors'][]=array(
                'vendor_id'=>$vd->vendor_id,
                'vendor'=>$vd->vendor_name,
                'phn_no'=>$vd->phone_number,
                'fax'=>$vd->fax_number,
                'email'=>$vd->email,
                'address'=>$vd->address,
                'contact_person'=>$vd->contact_person,
                'type'=>$vd->type,
                'terms'=>$vd->terms,
                'notes'=>$vd->notes,
            );
        }
        $this->load->view('vendors/view_vendors_per_item',$data);
        $this->load->view('template/footer');
    }

    public function vendor_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        foreach($this->super_model->select_all_order_by('vendor_head', 'vendor_name', 'ASC') AS $et){
            $data['vendors'][] = array(
                'vendor_id'=>$et->vendor_id,
                'vendor'=>$et->vendor_name,
                'product'=>$et->product_services,
                'address'=>$et->address,
                'phone'=>$et->phone_number,
                'fax'=>$et->fax_number,
                'terms'=>$et->terms,
                'type'=>$et->type,
                'contact'=>$et->contact_person,
                'notes'=>$et->notes,
                'ewt'=>$et->ewt,
                'vat'=>$et->vat,
                'status'=>$et->status
            );
        }
        $this->load->view('vendors/vendor_list',$data);
        $this->load->view('template/footer');
    }

    public function insert_vendor(){
        $vendor = trim($this->input->post('vendor')," ");
        $product = trim($this->input->post('product')," ");
        $address = trim($this->input->post('address')," ");
        $phone_num = trim($this->input->post('phone_num')," ");
        $fax_num = trim($this->input->post('fax_num')," ");
        $terms = trim($this->input->post('terms')," ");
        $type = trim($this->input->post('type')," ");
        $contact = trim($this->input->post('contact')," ");
        $note = trim($this->input->post('note')," ");
        $tin = trim($this->input->post('tin')," ");
        $ewt = trim($this->input->post('ewt')," ");
        $vat = trim($this->input->post('vat')," ");
        $status = trim($this->input->post('status')," ");
        $data = array(
            'vendor_name'=>$vendor,
            'product_services'=>$product,
            'address'=>$address,
            'phone_number'=>$phone_num,
            'fax_number'=>$fax_num,
            'terms'=>$terms,
            'type'=>$type,
            'contact_person'=>$contact,
            'notes'=>$note,
            'tin'=>$tin,
            'ewt'=>$ewt,
            'vat'=>$vat,
            'status'=>$status,
        );
        if($this->super_model->insert_into("vendor_head", $data)){
            echo "<script>alert('Successfully Added!'); window.location ='".base_url()."index.php/vendors/vendor_list'; </script>";
        }
    }

    public function update_vendor(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['vendor'] = $this->super_model->select_row_where('vendor_head', 'vendor_id', $id);
        $this->load->view('vendors/update_vendor',$data);
        $this->load->view('template/footer');
    }

    public function edit_vendor(){
        $data = array(
            'vendor_name'=>$this->input->post('vendor'),
            'product_services'=>$this->input->post('product'),
            'address'=>$this->input->post('address'),
            'phone_number'=>$this->input->post('phone'),
            'fax_number'=>$this->input->post('fax'),
            'terms'=>$this->input->post('terms'),
            'type'=>$this->input->post('type'),
            'contact_person'=>$this->input->post('contact'),
            'notes'=>$this->input->post('notes'),
            'tin'=>$this->input->post('tin'),
            'ewt'=>$this->input->post('ewt'),
            'vat'=>$this->input->post('vat'),
            'status'=>$this->input->post('status'),
        );
        $vendor_id = $this->input->post('vendor_id');
        if($this->super_model->update_where('vendor_head', $data, 'vendor_id', $vendor_id)){
            echo "<script>alert('Successfully Updated!'); window.opener.location.reload(); window.close();</script>";
        }
    }

    public function delete_vendor(){
        $id=$this->uri->segment(3);
        if($this->super_model->delete_where('vendor_head', 'vendor_id', $id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."index.php/vendors/vendor_list'; </script>";
        }
    }

    public function vendor_details(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['vendor_id']=$id;
        $data['vendor'] = $this->super_model->select_row_where('vendor_head', 'vendor_id', $id);
        $row = $this->super_model->count_rows_where("vendor_details",'vendor_id',$id);
        if($row!=0){
            foreach($this->super_model->select_row_where('vendor_details','vendor_id',$id) AS $v){
                foreach($this->super_model->select_row_where('item','item_id',$v->item_id) AS $vd){
                    $data['vendors'][]=array(
                        'vendordet_id'=>$v->vendordet_id,
                        'item_id'=>$v->item_id,
                        'brand'=>$this->super_model->select_column_where('item','brand_name','item_id',$v->item_id),
                        'item'=>$this->super_model->select_column_where('item','item_name','item_id',$v->item_id),
                        'specs'=>$this->super_model->select_column_where('item','item_specs','item_id',$v->item_id),
                        'price'=>$this->super_model->select_column_where('item','unit_price','item_id',$v->item_id),
                        'offer_date'=>$this->super_model->select_column_where('item','offer_date','item_id',$v->item_id),
                    );
                }
            }
        }else {
            $data['vendors']=array();
        }
        $this->load->view('vendors/vendor_details',$data);
        $this->load->view('template/footer');
    }

    public function add_vendoritem(){
        $this->load->view('template/header');
        $data['id']=$this->uri->segment(3);
        $id=$this->uri->segment(3);
        $data['vendor']=$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $id);
        $data['item']=$this->super_model->select_all_order_by('item', 'item_name', 'ASC');
        $this->load->view('vendors/add_vendoritem',$data);
        $this->load->view('template/footer');
    }

    public function insert_itemvendor(){
        for($x=1;$x<=10;$x++){
            $item= $this->input->post('item'.$x);
            $id= $this->input->post('id');
            if(!empty($item)) {
                $rows = $this->super_model->count_custom_where("vendor_details","vendor_id='$id' AND item_id='$item'");
                $itemname = $this->super_model->select_column_where('item', 'item_name', 'item_id', $item);
                if($rows==0){
                    $data = array(
                        'vendor_id'=>$id,
                        'item_id'=>$item,
                    );
                    if($this->super_model->insert_into("vendor_details", $data)){
                        echo "<script>alert('Successfully Added!'); window.opener.location.reload(); window.close();</script>";
                    }
                } else {
                    echo "<script>alert('$itemname is already linked to this vendor. Item duplication prevented.'); window.location ='".base_url()."index.php/vendors/add_vendoritem';</script>";
                     //window.opener.location.reload();
                }
            } 
        }
    }

    public function delete_item(){
        $venid=$this->uri->segment(3);
        $id=$this->uri->segment(4);
        if($this->super_model->delete_where('vendor_details', 'vendordet_id', $id)){
            echo "<script>alert('Succesfully Deleted'); 
                window.location ='".base_url()."index.php/vendors/vendor_details/$venid'; </script>";
        }
    }

    public function search_vendor(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');

        if(!empty($this->input->post('vendor'))){
            $data['vendor'] = $this->input->post('vendor');
        } else {
            $data['vendor']= "null";
        }

        if(!empty($this->input->post('product'))){
            $data['product'] = $this->input->post('product');
        } else {
            $data['product']= "null";
        }

        if(!empty($this->input->post('address'))){
            $data['address'] = $this->input->post('address');
        } else {
            $data['address'] = "null";
        }

        if(!empty($this->input->post('phone'))){
            $data['phone'] = $this->input->post('phone');
        } else {
            $data['phone'] = "null";
        }

        if(!empty($this->input->post('terms'))){
            $data['terms'] = $this->input->post('terms');
        } else {
            $data['terms'] = "null";
        }

        if(!empty($this->input->post('type'))){
            $data['type'] = $this->input->post('type');
        } else {
            $data['type'] = "null";
        } 

        if(!empty($this->input->post('contact'))){
            $data['contact'] = $this->input->post('contact');
        } else {
            $data['contact'] = "null";
        } 

        if(!empty($this->input->post('notes'))){
            $data['notes'] = $this->input->post('notes');
        } else {
            $data['notes'] = "null";
        }

        if(!empty($this->input->post('ewt'))){
            $data['ewt'] = $this->input->post('ewt');
        } else {
            $data['ewt'] = "null";
        } 

        if(!empty($this->input->post('vat'))){
            $data['vat'] = $this->input->post('vat');
        } else {
            $data['vat'] = "null";
        } 

        if(!empty($this->input->post('status'))){
            $data['status'] = $this->input->post('status');
        } else {
            $data['status'] = "null";
        } 

        $sql="";
        $filter = " ";

        if(!empty($this->input->post('vendor'))){
            $vendor = $this->input->post('vendor');
            $sql.=" vendor_head.vendor_name LIKE '%$vendor%' AND";
            $filter .= "Vendor - ".$vendor.", ";
        }

        if(!empty($this->input->post('product'))){
            $product = $this->input->post('product');
            $sql.=" vendor_head.product_services LIKE '%$product%' AND";
            $filter .= "Product - ".$product.", ";
        }

        if(!empty($this->input->post('address'))){
            $address = $this->input->post('address');
            $sql.=" vendor_head.address LIKE '%$address%' AND";
            $filter .= "Address - ".$address.", ";
        }

        if(!empty($this->input->post('phone'))){
            $phone = $this->input->post('phone');
            $sql.=" vendor_head.phone_number LIKE '%$phone%' AND";
            $filter .= "Phone Number - ".$phone.", ";
        }

        if(!empty($this->input->post('fax'))){
            $fax = $this->input->post('fax');
            $sql.=" vendor_head.fax_number LIKE '%$fax%' AND";
            $filter .= "Fax Number - ".$fax.", ";
        }

        if(!empty($this->input->post('terms'))){
            $terms = $this->input->post('terms');
            $sql.=" vendor_head.terms LIKE '%$terms%' AND";
            $filter .= "Terms - ".$terms.", ";
        }

        if(!empty($this->input->post('type'))){
            $type = $this->input->post('type');
            $sql.=" vendor_head.type LIKE '%$type%' AND";
            $filter .= "Type - ".$type.", ";
        }

        if(!empty($this->input->post('contact'))){
            $contact = $this->input->post('contact');
            $sql.=" vendor_head.contact_person LIKE '%$contact%' AND";
            $filter .= "Contact Person - ".$contact.", ";
        }

        if(!empty($this->input->post('notes'))){
            $notes = $this->input->post('notes');
            $sql.=" vendor_head.notes LIKE '%$notes%' AND";
            $filter .= "Notes - ".$notes.", ";
        }

        if(!empty($this->input->post('ewt'))){
            $ewt = $this->input->post('ewt');
            $sql.=" vendor_head.ewt LIKE '%$ewt%' AND";
            $filter .= "EWT(%) - ".$ewt.", ";
        }

        if(!empty($this->input->post('vat'))){
            $vat = $this->input->post('vat');
            $sql.=" vendor_head.vat = '$vat' AND";
            $filter .= "Vat - ".$vat.", ";
        }

        if(!empty($this->input->post('status'))){
            $status = $this->input->post('status');
            $sql.=" vendor_head.status = '$status' AND";
            $filter .= "Status - ".$status.", ";
        }

        $query=substr($sql, 0, -3);
        $data['filt']=substr($filter, 0, -2);
        foreach ($this->super_model->select_custom_where('vendor_head', $query) AS $et){
            $data['vendors'][] = array(
                'vendor_id'=>$et->vendor_id,
                'vendor'=>$et->vendor_name,
                'product'=>$et->product_services,
                'address'=>$et->address,
                'phone'=>$et->phone_number,
                'fax'=>$et->fax_number,
                'terms'=>$et->terms,
                'type'=>$et->type,
                'contact'=>$et->contact_person,
                'notes'=>$et->notes,
                'ewt'=>$et->ewt,
                'vat'=>$et->vat,
                'status'=>$et->status
            );
        }
        $this->load->view('vendors/vendor_list',$data);
        $this->load->view('template/footer');
    }

    public function export_vendor(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $id=$this->uri->segment(3);
        foreach($this->super_model->select_row_where('vendor_head', 'vendor_id', $id) AS $info){
            $vendor = $info->vendor_name;
            $address = $info->address;
            $phone_number = $info->phone_number;
            $fax_number = $info->fax_number;
            $email = $info->email;
            $contact_person = $info->contact_person;
            $terms = $info->terms;
            $type = $info->type;
            $notes = $info->notes;
            $ewt = $info->ewt;
        }
        $objPHPExcel = new PHPExcel();
        $exportfilename="Vendor Profile.xlsx";
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "VENDOR PROFILE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "Vendor Name:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', $vendor);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', "Address:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', $address);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "Phone Number:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', $phone_number);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', "Fax Number:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', $fax_number);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "Email:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', $email);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "Contact Person:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', $contact_person);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', "Terms:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B6', $terms);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C6', "Type:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6', $type);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7', "EWT(%):");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B7', $ewt);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C7', "Notes:");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D7', $notes);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A9', "ITEM LIST");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A10', "Item Name");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C10', "Brand");
        $col='A';
        $col2='C';
        $num=10;
        foreach($this->super_model->select_row_where('vendor_details', 'vendor_id', $id) AS $fetch){
            $num++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$num, $this->super_model->select_column_where('item', 'item_name', 'item_id', $fetch->item_id));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col2.$num, $this->super_model->select_column_where('item', 'brand_name', 'item_id', $fetch->item_id));
            $objPHPExcel->getActiveSheet()->mergeCells($col.$num .':B'.$num);
            $objPHPExcel->getActiveSheet()->mergeCells($col2.$num .':D'.$num);
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
        $objPHPExcel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D6')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('D7')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A9')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A10')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('C10')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->getStyle('A9:D9')->getFont()->setSize(14);

        $objPHPExcel->getActiveSheet()->mergeCells('A8:D8');
        $objPHPExcel->getActiveSheet()->mergeCells('A9:D9');
        $objPHPExcel->getActiveSheet()->mergeCells('A10:B10');
        $objPHPExcel->getActiveSheet()->mergeCells('C10:D10');
       //$objPHPExcel->getActiveSheet()->mergeCells('C7:D7');

        $objPHPExcel->getActiveSheet()->getStyle('A8:B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('A1:D7')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A9:D'.$num)->applyFromArray($styleArray);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Vendor Profile.xlsx"');
        readfile($exportfilename);

    }
}
?>