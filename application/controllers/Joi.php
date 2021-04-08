<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Joi extends CI_Controller {

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

    public function joi_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar'); 
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        foreach($this->super_model->select_all_order_by("joi_head","joi_date","ASC") AS $head){
            $data['head'][]=array(
                "joi_id"=>$head->joi_id,
                "date_prepared"=>$head->date_prepared,
                "date_needed"=>$head->date_needed,
                "cenpri_jo_no"=>$head->cenpri_jo_no,
                "joi_no"=>$head->joi_no,
                "joi_type"=>$head->joi_type,
                "project_title"=>$head->project_title,
                "vendor"=>$this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$head->vendor_id),
                'revised'=>$head->revised,
                'draft'=>$head->draft,
                'saved'=>$head->saved,
                'revision_no'=>$head->revision_no,
            );
        }
        $this->load->view('joi/joi_list',$data);
        $this->load->view('template/footer');
    }

    public function getsupplierJOI(){
        $supplier = $this->input->post('supplier');
        echo '<option value="">-Select JO No-</option>';
        foreach($this->super_model->custom_query("SELECT ah.jor_id, ah.jor_aoq_id, ah.aoq_date FROM jor_aoq_head ah INNER JOIN jor_aoq_offers ao ON ah.jor_aoq_id = ao.jor_aoq_id WHERE vendor_id = '$supplier' AND recommended = '1' and cancelled='0' GROUP BY ah.jor_aoq_id") AS $row){
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $row->jor_id);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $row->jor_id);
            }
            echo '<option value="'. $row->jor_id."_".$row->jor_aoq_id .'">'. $jo_no .' ('.$row->aoq_date." - ".$row->jor_aoq_id.')</option>';
        }
    }

    public function getJOinformation(){

        $jor_id = $this->input->post('jor_id');
        $joexp = explode('_',$jor_id);
        $jorid=$joexp[0];
        $jor_aoq_id=$joexp[1];
        $project_title= $this->super_model->select_column_where('jor_head', 'purpose', 'jor_id', $jorid);
        $date_prepared= $this->super_model->select_column_where('jor_head', 'date_prepared', 'jor_id', $jorid);
        $completion_date= $this->super_model->select_column_where('jor_head', 'completion_date', 'jor_id', $jorid);

        
        $return = array('purpose' => $project_title, 'date_prepared' => $date_prepared, 'completion_date' => $completion_date, 'jor_aoq_id'=>$jor_aoq_id);
        echo json_encode($return);
    
    }

    public function getsupplier(){
        $supplier = $this->input->post('supplier');
        $address= $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $supplier);
        $phone= $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $supplier);
        $return = array('address' => $address, 'phone' => $phone);
        echo json_encode($return);
    }

    public function create_joi(){
        $rows_head = $this->super_model->count_rows("joi_head");
        if($rows_head==0){
            $joi_id=1;
        } else {
            $max = $this->super_model->get_max("joi_head", "joi_id");
            $joi_id = $max+1;
        }

        $rows_series = $this->super_model->count_rows("joi_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("joi_series", "series");
            $series = $max+1;
        }

        if(empty($this->input->post('dp'))){
            $jo= $this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id',$this->input->post('jo_no'));
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id',$this->input->post('jo_no'));
            }
            //$po_no = "P".$pr_no."-".$series;
            $joi_no = "P".$jo_no."-".$series;
            $jor_id = $this->input->post('jo_no');
            $data_details = array(
                'joi_id'=>$joi_id,
                'jor_id'=>$jor_id,
                'jor_aoq_id'=>$this->input->post('jor_aoq_id'),
                'purpose'=>$this->super_model->select_column_where('jor_head', 'purpose', 'jor_id', $jor_id),
                'requestor'=>$this->super_model->select_column_where('jor_head', 'requested_by', 'jor_id', $jor_id),

            );
            $this->super_model->insert_into("joi_jor", $data_details);
            $data= array(
                'joi_id'=>$joi_id,
                'joi_date'=>$this->input->post('joi_date'),
                'joi_no'=>$joi_no,
                'vendor_id'=>$this->input->post('vendor'),
                'notes'=>$this->input->post('notes'),
                'cenpri_jo_no'=>$this->input->post('cenjo_no'),
                'date_needed'=>$this->input->post('date_needed'),
                'date_prepared'=>$this->input->post('date_prepared'),
                'start_of_work'=>$this->input->post('work_start'),
                'completion_date'=>$this->input->post('work_completion'),
                'project_title'=>$this->input->post('project_title'),
                'joi_type'=>0,
                'user_id'=>$_SESSION['user_id'],
                'prepared_date'=>date("Y-m-d H:i:s"),
            );  

            $data_series = array(
                'series'=>$series
            );
            $this->super_model->insert_into("joi_series", $data_series);

            if($this->super_model->insert_into("joi_head", $data)){
                 redirect(base_url().'joi/jo_issuance/'.$joi_id);
            }
        }else {
            $joi_no = "JOD-".$series;
            $data= array(
                'joi_id'=>$joi_id,
                'joi_date'=>$this->input->post('joi_date'),
                'joi_no'=>$joi_no,
                'vendor_id'=>$this->input->post('vendor'),
                'notes'=>$this->input->post('notes'),
                'cenpri_jo_no'=>$this->input->post('cenjo_no'),
                'joi_type'=>1,
                'user_id'=>$_SESSION['user_id'],
                'prepared_date'=>date("Y-m-d H:i:s"),
            );  

            $data_series = array(
                'series'=>$series
            );
            $this->super_model->insert_into("joi_series", $data_series);

          
            if($this->super_model->insert_into("po_head", $data)){
                 redirect(base_url().'joi/joi_direct/'.$joi_id);
            }
        }
    }

    public function cancelled_joi(){
        $this->load->view('template/header');
        $this->load->view('template/navbar'); 
        $this->load->view('joi/cancelled_joi');
        $this->load->view('template/footer');
    }

    public function item_checker($jor_items_id, $vendor_id){
        $jor_qty = $this->super_model->select_column_where('jor_items', 'quantity', 'jor_items_id', $jor_items_id);

        $delivered_qty = $this->super_model->select_sum_join("quantity","joi_head","joi_items", "jor_items_id = '$jor_items_id' AND cancelled = '0' ","joi_id");

       // if($delivered_qty!=0){
            if($delivered_qty==$jor_qty){
                $qty = 0;
            } else {
                $qty = $jor_qty-$delivered_qty;
            }
      /*  } else {
            $qty = $this->super_model->select_column_join_where("balance", "aoq_head","aoq_offers", "vendor_id = '$vendor_id' AND recommended = '1'","aoq_id");
        }*/

        return $qty;
    }

    public function jo_issuance(){
        $this->load->view('template/header');
        $joi_id = $this->uri->segment(3);
        $revised = $this->uri->segment(4);
        $data['revised'] = $revised;

        $data['joi_id'] = $joi_id;
        $vendor_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        $data['vendor_id']=$vendor_id;
        foreach($this->super_model->select_row_where('joi_head', 'joi_id', $joi_id) AS $h){
            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'project_title'=>$h->project_title,
                'completion_date'=>$h->completion_date,
                'date_prepared'=>$h->date_prepared,
                'start_of_work'=>$h->start_of_work,
                'date_needed'=>$h->date_needed,
                'cenpri_jo_no'=>$h->cenpri_jo_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['saved']=$h->saved;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
        }

        foreach($this->super_model->custom_query("SELECT ao.jor_aoq_id, ah.jor_id, ao.currency FROM jor_aoq_offers ao INNER JOIN jor_aoq_head ah ON ao.jor_aoq_id = ah.jor_aoq_id WHERE ao.vendor_id = '$vendor_id' AND recommended = '1' GROUP BY jor_id") AS $off){
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $off->jor_id);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $off->jor_id);
            }
            $data['pr'][]=array(
                'jor_id'=>$off->jor_id,
                'jo_no'=>$jo_no,
            );
            //$data['currency'] = $off->currency;
        }

        if(empty($revised)){
            foreach($this->super_model->select_row_where("joi_jor", "joi_id" , $joi_id) AS $popr){
             
                foreach($this->super_model->select_custom_where("jor_aoq_offers", "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id' AND recommended='1' ORDER BY jor_items_id ASC") AS $off){
                    $data['currency'] = $off->currency;
                    $balance = $this->item_checker($off->jor_items_id, $vendor_id);
                    $total = $off->unit_price*$balance;
                      
                    //echo $balance ."<br>";
                    $data['items'][] =  array(
                        'jor_aoq_id'=>$off->jor_aoq_id,
                        'jor_aoq_offer_id'=>$off->jor_aoq_offer_id,
                        'jor_aoq_items_id'=>$off->jor_aoq_items_id,
                        'jor_items_id'=>$off->jor_items_id,
                        'item_name'=>$this->super_model->select_column_where('jor_aoq_items', 'scope_of_work', 'jor_aoq_items_id', $off->jor_aoq_items_id),
                        'offer'=>$off->offer,
                        'currency'=>$off->currency,
                        'price'=>$off->unit_price,
                        'balance'=>$balance,
                        'amount'=>$off->amount,
                        'uom'=>$off->uom,
                        'total'=>$total
                    );
                    
                    $data['vendor_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'vendor_id', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'jor_aoq_vendors_id', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                }
             } 
        } else {
             foreach($this->super_model->select_row_where("jor_items", "joi_id" , $joi_id) AS $off){
                    $data['currency'] = $off->currency;
                  $total = $off->unit_price*$off->quantity;
                    $data['items'][] =  array(
                        'jor_aoq_id'=>$this->super_model->select_column_where('joi_jor', 'jor_aoq_id', 'joi_id', $joi_id),
                        'jor_aoq_offer_id'=>$off->jor_aoq_offer_id,
                        'jor_aoq_items_id'=>$off->jor_aoq_items_id,
                        'jor_items_id'=>$off->jor_items_id,
                        'item_name'=>$this->super_model->select_column_where('jor_aoq_items', 'scope_of_work', 'jor_aoq_items_id', $off->jor_aoq_items_id),
                        'offer'=>$off->offer,
                        'price'=>$off->unit_price,
                        'balance'=>$off->quantity,
                        'amount'=>$off->amount,
                        'uom'=>$off->uom,
                        'total'=>$total
                    );
                

                    $data['vendor_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'vendor_id', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'jor_aoq_vendors_id', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
                    $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$popr->jor_aoq_id' AND vendor_id='$vendor_id'");
             }
        }

        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }
            $data['allpr'][]= array(
                'jo_no'=>$jo_no,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );

            $data['notes'] = $this->super_model->select_row_where("jor_notes", "jor_id", $ppr->jor_id);
        }
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);

        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('joi/jo_issuance',$data);
        $this->load->view('template/footer');
    }

    public function save_joi(){
        $submit = $this->input->post('submit');
        $joi_id = $this->input->post('joi_id');
        $count_item = $this->input->post('count_item');
        $count_notes = $this->input->post('count_notes');
        $a=1;
        $rows_dr = $this->super_model->count_rows("joi_dr");
        if($rows_dr==0){
            $joi_dr_no=1000;
            $joi_dr_id = 1;
        } else {
            $max = $this->super_model->get_max("joi_dr", "joi_dr_no");
            $maxid = $this->super_model->get_max("joi_dr", "joi_dr_id");
            $joi_dr_no = $max+1;
            $joi_dr_id = $maxid+1;
        }

        $dr = array(
            'joi_dr_id'=>$joi_dr_id,
            'joi_id'=>$joi_id,
            'joi_dr_no'=>$joi_dr_no,
            'joi_dr_date'=>$this->super_model->select_column_where('joi_head', 'joi_date', 'joi_id', $joi_id),
        );

        $this->super_model->insert_into("joi_dr", $dr);

        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $rows_items = $this->super_model->count_rows("joi_items");
            if($rows_items==0){
                $joi_items_id = 1;
            } else {
                $maxid = $this->super_model->get_max("joi_items", "joi_items_id");
                $joi_items_id = $maxid+1;
            }
            $saved = $this->super_model->select_column_where("joi_head",'saved','joi_id',$joi_id);
            if($qty!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $data=array(
                    'joi_items_id'=>$joi_items_id,
                    'jor_id'=>$this->super_model->select_column_where('jor_aoq_head', 'jor_id', 'jor_aoq_id', $this->input->post('jor_aoq_id'.$x)),
                    'joi_id'=>$joi_id,
                    'jor_aoq_offer_id'=>$this->input->post('jor_aoq_offer_id'.$x),
                    'jor_aoq_items_id'=>$this->input->post('jor_aoq_items_id'.$x),
                    'jor_items_id'=>$this->input->post('jor_items_id'.$x),
                    'offer'=>$this->input->post('offer'.$x),
                    'delivered_quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );

                $data_dr=array(
                    'joi_items_id'=>$joi_items_id,  
                    'jor_id'=>$this->super_model->select_column_where('jor_aoq_head', 'jor_id', 'jor_aoq_id', $this->input->post('jor_aoq_id'.$x)),
                    'joi_dr_id'=>$joi_dr_id,
                    'joi_id'=>$joi_id,
                    'joi_aoq_offer_id'=>$this->input->post('jor_aoq_offer_id'.$x),
                    'joi_aoq_items_id'=>$this->input->post('jor_aoq_items_id'.$x),
                    'jor_items_id'=>$this->input->post('jor_items_id'.$x),
                    'offer'=>$this->input->post('offer'.$x),
                    'delivered_quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );
                $this->super_model->insert_into("joi_items", $data);
                $this->super_model->insert_into("joi_dr_items", $data_dr);
                $a++;
            }   
        }

        for($y=1; $y<$count_notes;$y++){
            $data_notes=array(
                "joi_id"=>$joi_id,
                "notes"=>$this->input->post('jor_notes'.$y),
            );
            $this->super_model->insert_into("joi_tc", $data_notes);
        }

        if($submit=='Save'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('less_amount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat_amount'),
                'conforme'=>$this->input->post('conforme'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked_by'),
                'recommended_by'=>$this->input->post('recommended_by'),
                'approved_by'=>$this->input->post('approved_by'),
                'verified_by'=>$this->input->post('verified_by'),
                'saved'=>1,
                'revised'=>0
            ); 
             if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'joi/jo_issuance_saved/'.$joi_id);
             }

        } else if($submit=='Save as Draft'){
             $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('less_amount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat_amount'),
                'conforme'=>$this->input->post('conforme'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked_by'),
                'recommended_by'=>$this->input->post('recommended_by'),
                'approved_by'=>$this->input->post('approved_by'),
                'verified_by'=>$this->input->post('verified_by'),
                'saved'=>0,
                'draft'=>1,
                'revised'=>0
            ); 
            if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'joi/jo_issuance_draft/'.$joi_id);
            }
        }      
    }

    public function create_jo_terms(){
        $joi_id = $this->input->post('joi_id');
        $draft = $this->super_model->select_column_where("joi_head", "draft", "joi_id", $joi_id);
        $data = array(
            'joi_id'=>$this->input->post('joi_id'),
            'tc_desc'=>$this->input->post('terms'),
        );


        if($this->super_model->insert_into("joi_tc", $data)){
            if($draft==0){
                redirect(base_url().'joi/jo_issuance/'.$joi_id, 'refresh');
            } else {
                redirect(base_url().'joi/jo_issuance_draft/'.$joi_id, 'refresh');
            }
        }
    }

    public function update_terms(){
        $joi_id = $this->input->post('joi_id');
        $aoq_vendors_id = $this->input->post('aoq_vendors_id');
        $update = array(
            'payment_terms'=>$this->input->post('payments'),
            'delivery_date'=>$this->input->post('del_itm'),
            'item_warranty'=>$this->input->post('item_war'),
            'freight'=>$this->input->post('freigh'),
        ); 
        if($this->super_model->update_where("jor_aoq_vendors", $update, "jor_aoq_vendors_id",$aoq_vendors_id)){

            redirect(base_url().'joi/jo_issuance/'.$joi_id);
        }
    }

    public function update_condition(){
        $joi_id = $this->input->post('joi_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$tc_id)){
            redirect(base_url().'joi/jo_issuance/'.$joi_id);
        }
    }

    public function create_jo_terms_saved(){
        $joi_id = $this->input->post('joi_id');
        $draft = $this->super_model->select_column_where("joi_head", "draft", "joi_id", $joi_id);
        $data = array(
            'joi_id'=>$this->input->post('joi_id'),
            'tc_desc'=>$this->input->post('terms'),
        );


        if($this->super_model->insert_into("joi_tc", $data)){
            if($draft==0){
                redirect(base_url().'joi/jo_issuance_saved/'.$joi_id, 'refresh');
            } else {
                redirect(base_url().'joi/jo_issuance_draft/'.$joi_id, 'refresh');
            }
        }
    }

    public function update_terms_saved(){
        $joi_id = $this->input->post('joi_id');
        $aoq_vendors_id = $this->input->post('aoq_vendors_id');
        $update = array(
            'payment_terms'=>$this->input->post('payments'),
            'delivery_date'=>$this->input->post('del_itm'),
            'item_warranty'=>$this->input->post('item_war'),
            'freight'=>$this->input->post('freigh'),
        ); 
        if($this->super_model->update_where("jor_aoq_vendors", $update, "jor_aoq_vendors_id",$aoq_vendors_id)){

            redirect(base_url().'joi/jo_issuance_saved/'.$joi_id);
        }
    }

    public function update_condition_saved(){
        $joi_id = $this->input->post('joi_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$tc_id)){
            redirect(base_url().'joi/jo_issuance_saved/'.$joi_id);
        }
    }

    public function jo_issuance_saved(){
        $this->load->view('template/header');
        $joi_id = $this->uri->segment(3);
        $data['joi_id'] = $joi_id;
        $vendor_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        foreach($this->super_model->select_row_where('joi_head', 'joi_id', $joi_id) AS $h){

            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'project_title'=>$h->project_title,
                'completion_date'=>$h->completion_date,
                'date_prepared'=>$h->date_prepared,
                'start_of_work'=>$h->start_of_work,
                'date_needed'=>$h->date_needed,
                'cenpri_jo_no'=>$h->cenpri_jo_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['conforme']= $h->conforme;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['joi_no']=$h->joi_no;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
            $data['verified_by']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->verified_by);
        }

        $data['items'] = $this->super_model->select_row_where('joi_items', 'joi_id', $joi_id);
        $data['currency'] = $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }
            $data['allpr'][]= array(
                'jo_no'=>$jo_no,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['vendor_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'vendor_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'jor_aoq_vendors_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
        }
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['dr'] = $this->super_model->select_row_where("joi_dr", "joi_id", $joi_id);
        $this->load->view('joi/jo_issuance_saved',$data);
        $this->load->view('template/footer');
    }

    public function jo_issuance_draft(){
        $this->load->view('template/header');
        $joi_id = $this->uri->segment(3);
        $data['joi_id'] = $joi_id;
        $vendor_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        foreach($this->super_model->select_row_where('joi_head', 'joi_id', $joi_id) AS $h){

            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'project_title'=>$h->project_title,
                'completion_date'=>$h->completion_date,
                'date_prepared'=>$h->date_prepared,
                'start_of_work'=>$h->start_of_work,
                'date_needed'=>$h->date_needed,
                'cenpri_jo_no'=>$h->cenpri_jo_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['conforme']= $h->conforme;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['joi_no']=$h->joi_no;
            $data['notes']=$h->notes;
            $data['approved_id']=$h->approved_by;
            $data['checked_id']=$h->checked_by;
            $data['recommended_id']=$h->recommended_by;
            $data['verified_id']=$h->recommended_by;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
            $data['verified_by']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->verified_by);
        }

        $data['items'] = $this->super_model->select_row_where('joi_items', 'joi_id', $joi_id);
        $data['currency'] = $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }
            $data['allpr'][]= array(
                'jo_no'=>$jo_no,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['vendor_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'vendor_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'jor_aoq_vendors_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['notes'] = $this->super_model->select_row_where("jor_notes", "jor_id", $ppr->jor_id);
        }
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['dr'] = $this->super_model->select_row_where("joi_dr", "joi_id", $joi_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('joi/jo_issuance_draft',$data);
        $this->load->view('template/footer');
    }

    public function save_joi_draft(){
        $submit = $this->input->post('submit');
        $joi_id = $this->input->post('joi_id');
        $count_item = $this->input->post('count_item');
        $count_notes = $this->input->post('count_notes');

        $a=1;
        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $uom=$this->input->post('uom'.$x);
            $joi_items_id = $this->input->post('joi_items_id'.$x);        
            if($qty!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $offer = $this->input->post('offer'.$x);
                $data=array(
                 
                    'delivered_quantity'=>$qty,
                    'offer'=>$offer,
                    'uom'=>$uom,
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );
                $data_dr=array(
                    'delivered_quantity'=>$qty,
                    'uom'=>$uom,
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a
                );

                    $this->super_model->update_where("joi_items", $data, "joi_items_id", $joi_items_id);
                    $this->super_model->update_where("joi_dr_items", $data_dr, "joi_items_id", $joi_items_id);
             $a++;
            } else {
                
                $this->super_model->delete_where("joi_items", "joi_items_id", $joi_items_id);
                $this->super_model->delete_where("joi_dr_items", "joi_items_id", $joi_items_id);
            }
            
        }

        for($y=1; $y<$count_notes;$y++){
            $joi_tc_id = $this->input->post('joi_tc_id'.$y);
            $data_notes=array(
                "notes"=>$this->input->post('joi_notes'.$y),
            );
            $this->super_model->update_where("joi_tc", $data_notes, "joi_tc_id", $joi_tc_id);
        }

        if($submit=='Save'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('less_amount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat_amount'),
                'conforme'=>$this->input->post('conforme'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked_by'),
                'recommended_by'=>$this->input->post('recommended_by'),
                'approved_by'=>$this->input->post('approved_by'),
                'verified_by'=>$this->input->post('verified_by'),
                'saved'=>1,
                'draft'=>0,
                'revised'=>0
            ); 
             if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'joi/jo_issuance_saved/'.$joi_id);
             }

        } else if($submit=='Save as Draft'){
             $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('less_amount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat_amount'),
                'conforme'=>$this->input->post('conforme'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked_by'),
                'recommended_by'=>$this->input->post('recommended_by'),
                'approved_by'=>$this->input->post('approved_by'),
                'verified_by'=>$this->input->post('verified_by'),
                'saved'=>0,
                'draft'=>1,
                'revised'=>0
            ); 
             if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'joi/jo_issuance_draft/'.$joi_id);
             }
        }
    }

    public function jo_issuance_rev(){

        $joi_id=$this->uri->segment(3); 
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        foreach($this->super_model->select_row_where("joi_head", "joi_id",$joi_id) AS $head){
            $data['approved_by']=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);
            $data['prepared_by']=$this->super_model->select_column_where('users','fullname','user_id', $head->user_id);
            $data['revised']=$head->revised;
        }
        
        $data['joi_id'] = $joi_id;
        $vendor_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        foreach($this->super_model->select_row_where('joi_head', 'joi_id', $joi_id) AS $h){
            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'vendor_id'=>$h->vendor_id,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact_person'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['cenjo_no']= $h->cenpri_jo_no;
            $data['joi_no']= $h->joi_no;
            $data['project_title']= $h->project_title;
            $data['date_prepared']= $h->date_prepared;
            $data['date_needed']= $h->date_needed;
            $data['start_of_work']= $h->start_of_work;
            $data['work_completion']= $h->completion_date;
            $data['discount']= $h->discount;
            $data['vat_percent']= $h->vat_percent;
            $data['vat_amount']= $h->vat;
            $data['conforme']= $h->conforme;
            $data['verified_by']= $h->verified_by;
            $data['cancelled']= $h->cancelled;
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['approved_id'] = $h->approved_by;
            $data['recommended_id'] = $h->recommended_by;
             $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
            $data['checked_id'] = $h->checked_by;
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['verified_id'] = $h->verified_by;
            $data['verified_by'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->verified_by);
        }

        $data['items'] = $this->super_model->select_row_where('joi_items', 'joi_id', $joi_id);
        $data['currency']= $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
        $data['items_temp'] = $this->super_model->select_row_where('joi_items_temp', 'joi_id', $joi_id);
        $data['currency_temp']= $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
      
        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }
            $data['allpr'][]= array(
                'jo_no'=>$jo_no,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['vendor_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'vendor_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
            $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'jor_aoq_vendors_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
            $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
        }
        $data['tc_notes'] = $this->super_model->select_column_where("joi_tc_temp",'notes',"joi_id",$joi_id);
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['tc_temp'] = $this->super_model->select_row_where("joi_tc_temp", "joi_id", $joi_id);
        $data['date_needed_temp'] = $this->super_model->select_column_where('joi_head_temp', 'date_needed', 'joi_id', $joi_id);
        $data['start_of_work_temp'] = $this->super_model->select_column_where('joi_head_temp', 'start_of_work', 'joi_id', $joi_id);
        $data['completion_date_temp'] = $this->super_model->select_column_where('joi_head_temp', 'completion_date', 'joi_id', $joi_id);
        $data['cenjo_no_temp'] = $this->super_model->select_column_where('joi_head_temp', 'cenpri_jo_no', 'joi_id', $joi_id);
        $data['project_title_temp'] = $this->super_model->select_column_where('joi_head_temp', 'project_title', 'joi_id', $joi_id);

        $data['shipping_temp'] = $this->super_model->select_column_where('joi_head_temp', 'shipping', 'joi_id', $joi_id);
        $data['discount_temp'] = $this->super_model->select_column_where('joi_head_temp', 'discount', 'joi_id', $joi_id);
        $data['packing_temp'] = $this->super_model->select_column_where('joi_head_temp', 'packing_fee', 'joi_id', $joi_id);
        $data['vat_temp'] = $this->super_model->select_column_where('joi_head_temp', 'vat', 'joi_id', $joi_id);
        $data['conforme_temp'] = $this->super_model->select_column_where('joi_head_temp', 'conforme', 'joi_id', $joi_id);
        $data['vat_percent_temp'] = $this->super_model->select_column_where('joi_head_temp', 'vat_percent', 'joi_id', $joi_id);

        /*$datarfd = array(
            'saved'=>0
        );
        $this->super_model-> update_where("rfd", $datarfd, "joi_id", $joi_id);*/

        $this->load->view('template/header');        
        $this->load->view('joi/jo_issuance_rev', $data);
        $this->load->view('template/footer');
    }

    /*public function jo_issuance_rev_backup(){
        $joi_id = $this->uri->segment(3);
        $data['joi_id'] = $joi_id;
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('template/header');
        $data['cancelled']='';
        foreach($this->super_model->select_custom_where("joi_head", "joi_id='$joi_id'") AS $head){
            $data['vendor_id'] = $head->vendor_id;
            $data['revised'] = $head->revised;
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $head->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $head->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['joi_no']= $head->joi_no;
            $data['project_title']= $head->project_title;
            $data['date_prepared']= $head->date_prepared;
            $data['date_needed']= $head->date_needed;
            $data['start_of_work']= $head->start_of_work;
            $data['work_completion']= $head->completion_date;
            $data['discount']= $head->discount;
            $data['vat_percent']= $head->vat_percent;
            $data['vat_amount']= $head->vat;
            $data['conforme']= $head->conforme;
            $data['verified_by']= $head->verified_by;
            $data['cancelled']= $head->cancelled;
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->approved_by);
            $data['approved_id'] = $head->approved_by;
            $data['recommended_id'] = $head->recommended_by;
             $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->recommended_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->checked_by);
            $data['checked_id'] = $head->checked_by;
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->user_id);
            $data['verified_id'] = $head->verified_by;
            $data['verified_by'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->verified_by);
            foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
                $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
                if($jo!=''){
                    $jo_no=$jo;
                }else{
                    $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
                }
                $data['allpr'][]= array(
                    'jo_no'=>$jo_no,
                    'purpose'=>$ppr->purpose,
                    'requestor'=>$ppr->requestor
                );
                $data['vendor_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'vendor_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
                $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'jor_aoq_vendors_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
                $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
                $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
                $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
                $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
                $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$head->vendor_id'");
            }
        }

        foreach($this->super_model->select_custom_where("joi_head_temp", "joi_id='$joi_id'") AS $headtemp){
            $data['vendor_id'] = $headtemp->vendor_id;
            $data['revised'] = $headtemp->revised;
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $headtemp->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $headtemp->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $headtemp->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $headtemp->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $headtemp->vendor_id);
            $data['cenjo_no']= $headtemp->cenpri_jo_no;
            $data['joi_no']= $headtemp->joi_no;
            $data['project_title']= $headtemp->project_title;
            $data['date_prepared']= $headtemp->date_prepared;
            $data['date_needed']= $headtemp->date_needed;
            $data['start_of_work']= $headtemp->start_of_work;
            $data['work_completion']= $headtemp->completion_date;
            $data['discount']= $headtemp->discount;
            $data['vat_percent']= $headtemp->vat_percent;
            $data['vat_amount']= $headtemp->vat;
            $data['conforme']= $headtemp->conforme;
            $data['cancelled']= $headtemp->cancelled;
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $headtemp->approved_by);
            $data['approved_id'] = $headtemp->approved_by;
            $data['recommended_id'] = $headtemp->recommended_by;
             $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $headtemp->recommended_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $headtemp->checked_by);
            $data['checked_id'] = $headtemp->checked_by;
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $headtemp->user_id);
            $data['verified_id'] = $headtemp->verified_by;
            $data['verified_by'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $headtemp->verified_by);
            foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
                $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
                if($jo!=''){
                    $jo_no=$jo;
                }else{
                    $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
                }
                $data['allpr'][]= array(
                    'jo_no'=>$jo_no,
                    'purpose'=>$ppr->purpose,
                    'requestor'=>$ppr->requestor
                );
                $data['vendor_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'vendor_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$headtemp->vendor_id'");
                $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'jor_aoq_vendors_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$headtemp->vendor_id'");
                $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$headtemp->vendor_id'");
                $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$headtemp->vendor_id'");
                $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$headtemp->vendor_id'");
                $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$headtemp->vendor_id'");
                $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$headtemp->vendor_id'");
            }
        }   
        $jor_id = $this->super_model->select_column_where("joi_items","jor_id","joi_id",$joi_id);
        $data['details'] = $this->super_model->select_row_where("joi_items", "joi_id", $joi_id);
        $data['details_temp'] = $this->super_model->select_row_where("joi_items_temp", "joi_id", $joi_id);
        $data['terms'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['terms_temp'] = $this->super_model->select_row_where("joi_tc_temp", "joi_id", $joi_id);
        $this->load->view('joi/jo_issuance_rev',$data);
        $this->load->view('template/footer');
    }*/

    public function save_change_order(){
        $joi_id = $this->input->post('joi_id');
        $timestamp = date('Y-m-d');
        foreach($this->super_model->select_row_where("joi_head","joi_id",$joi_id) AS $johead){
            $data_details = array(
                "joi_id"=>$johead->joi_id,
                'joi_date'=>$timestamp,
                "vendor_id"=>$this->input->post('vendor'),
                "date_needed"=>$this->input->post('date_needed'),
                "completion_date"=>$this->input->post('work_completion'),
                "date_prepared"=>$this->input->post('date_prepared'),
                "cenpri_jo_no"=>$this->input->post('cenjo_no'),
                "start_of_work"=>$this->input->post('start_of_work'),
                "project_title"=>$this->input->post('project_title'),
                "conforme"=>$this->input->post('conforme'),
                "saved"=>$johead->saved,
                "checked_by"=>$this->input->post('checked_by'),
                "verified_by"=>$this->input->post('verified_by'),
                "approved_by"=>$this->input->post('approved_by'),
                "recommended_by"=>$this->input->post('recommended_by'),
                "user_id"=>$johead->user_id,
                "vat_percent"=>$this->input->post('vat_percent'),
                "vat"=>$this->input->post('vat_amount'),
                "discount"=>$this->input->post('less_amount'),
                "cancelled"=>$johead->cancelled,
                "cancelled_by"=>$johead->cancelled_by,
                "cancel_reason"=>$johead->cancel_reason,
                "cancelled_date"=>$johead->cancelled_date,
            );
            $this->super_model->insert_into("joi_head_temp", $data_details);
        }

        $x=1;
        foreach($this->super_model->select_row_where("joi_items","joi_id",$joi_id) AS $jodets){
            if($this->input->post('quantity'.$x)!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $data_details = array(
                    "joi_items_id"=>$jodets->joi_items_id,
                    "jor_id"=>$jodets->jor_id,
                    "joi_id"=>$jodets->joi_id,
                    "jor_aoq_offer_id"=>$jodets->jor_aoq_offer_id,
                    "jor_aoq_items_id"=>$jodets->jor_aoq_items_id,
                    "jor_items_id"=>$jodets->jor_items_id,
                    "item_no"=>$jodets->item_no,
                    "delivered_quantity"=>$this->input->post('quantity'.$x),
                    "quantity"=>$jodets->delivered_quantity,
                    "unit_price"=>$price,
                    "uom"=>$this->input->post('uom'.$x),
                    "amount"=>$amount,
                    "offer"=>$this->input->post('scope_of_work'.$x),
                );
                $this->super_model->insert_into("joi_items_temp", $data_details);
            }
                
            $x++;
        }

        $y=1;
        foreach($this->super_model->select_row_where("joi_tc","joi_id",$joi_id) AS $jotc){
            $data_tci = array(
                "joi_tc_id"=>$jotc->joi_tc_id,
                "joi_id"=>$joi_id,
                "tc_desc"=>$jotc->tc_desc,
            );
            $this->super_model->insert_into("joi_tc_temp", $data_tci);
            $y++;
        }

        $count_notes=$this->input->post('count_notes');
        for($z=1; $z<$count_notes;$z++){
            $joi_tc_id = $this->input->post('joi_tc_id'.$z);
            $data_notes=array(
                "notes"=>$this->input->post('joi_notes'.$z),
            );
            $this->super_model->update_where("joi_tc_temp", $data_notes, "joi_tc_id", $joi_tc_id);
        }

        $data_head = array(
            'revised'=>1
        );

        if($this->super_model->update_where("joi_head", $data_head, "joi_id", $joi_id)){
            redirect(base_url().'joi/jo_issuance_rev/'.$joi_id, 'refresh');
        }
    }

    public function approve_revision(){
        $joi_id = $this->input->post('joi_id');
        $max_revision = $this->super_model->get_max_where("joi_head", "revision_no","joi_id = '$joi_id'");
        $revision_no = $max_revision+1;

       $rows_dr = $this->super_model->count_rows("joi_dr");
        if($rows_dr==0){
            $dr_no=1000;
        } else {
            $max = $this->super_model->get_max("joi_dr", "joi_dr_no");
            $dr_no = $max+1;
        }

        $rows_series = $this->super_model->count_rows("joi_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("joi_series", "series");
            $series = $max+1;
        }

        $data_series = array(
            'series'=>$series
        );
        $this->super_model->insert_into("joi_series", $data_series);

        foreach($this->super_model->select_row_where("joi_dr","joi_id",$joi_id) AS $drs){
            $data_dr=array(
                'joi_dr_id'=>$drs->joi_dr_id,
                'joi_id'=>$drs->joi_id,
                'joi_dr_no'=>$drs->joi_dr_no,
                'joi_dr_date'=>$drs->joi_dr_date,
                'joi_dr_type'=>$drs->joi_dr_type,
                'saved'=>$drs->saved,
                'revision_no'=>$drs->revision_no,
            );
            if($this->super_model->insert_into("joi_dr_revised", $data_dr)){
                $dr = array(
                    'joi_dr_no'=>$dr_no,
                    'received'=>0,
                    'date_received'=>NULL
                );
                $this->super_model->update_where("joi_dr", $dr, "joi_dr_id", $drs->joi_dr_id);
            }
        }

        foreach($this->super_model->select_row_where("joi_dr_items","joi_id",$joi_id) AS $dritems){
            $data_dritems=array(
                'joi_dr_items_id'=>$dritems->joi_dr_items_id,
                'joi_items_id'=>$dritems->joi_items_id,
                'joi_dr_id'=>$dritems->joi_dr_id,
                'jor_id'=>$dritems->jor_id,
                'joi_id'=>$dritems->joi_id,
                'joi_aoq_offer_id'=>$dritems->joi_aoq_offer_id,
                'joi_aoq_items_id'=>$dritems->joi_aoq_items_id,
                'joi_items_id'=>$dritems->joi_items_id,
                'offer'=>$dritems->offer,
                'item_id'=>$dritems->item_id,
                'delivered_quantity'=>$dritems->delivered_quantity,
                'quantity'=>$dritems->quantity,
                'unit_price'=>$dritems->unit_price,
                'uom'=>$dritems->uom,
                'amount'=>$dritems->amount,
                'item_no'=>$dritems->item_no,
                'revision_no'=>$dritems->revision_no
            );
            $this->super_model->insert_into("joi_dr_items_revised", $data_dritems);
        }
        
        $data_drs =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("joi_dr", $data_drs, "joi_id", $joi_id);

        $jor_id = $this->super_model->select_column_where("joi_items","jor_id","joi_id",$joi_id);
        $jo = $this->super_model->select_column_where("jor_head","jo_no","jor_id",$jor_id);
        if($jo!=''){
            $jo_no=$jo;
        }else{
            $jo_no=$this->super_model->select_column_where("jor_head","user_jo_no","jor_id",$jor_id);
        }
        $joi_no = "P".$jo_no."-".$series;
        foreach($this->super_model->select_row_where("joi_head","joi_id",$joi_id) AS $head){
            $data_head = array(
                "joi_id"=>$head->joi_id,
                "joi_date"=>$head->joi_date,
                "joi_no"=>$head->joi_no,
                "dr_no"=>$head->dr_no,
                "vendor_id"=>$head->vendor_id,
                "notes"=>$head->notes,
                "joi_type"=>$head->joi_type,
                "user_id"=>$head->user_id,
                "shipping"=>$head->shipping,
                "discount"=>$head->discount,
                "packing_fee"=>$head->packing_fee,
                "date_needed"=>$head->date_needed,
                "completion_date"=>$head->completion_date,
                "date_prepared"=>$head->date_prepared,
                "cenpri_jo_no"=>$head->cenpri_jo_no,
                "start_of_work"=>$head->start_of_work,
                "project_title"=>$head->project_title,
                "conforme"=>$head->conforme,
                "recommended_by"=>$head->recommended_by,
                "user_id"=>$head->user_id,
                "cancelled"=>$head->cancelled,
                "cancelled_by"=>$head->cancelled_by,
                "cancel_reason"=>$head->cancel_reason,
                "cancelled_date"=>$head->cancelled_date,
                "vat"=>$head->vat,
                "vat_percent"=>$head->vat_percent,
                "approved_by"=>$head->approved_by,
                "checked_by"=>$head->checked_by,
                "verified_by"=>$head->verified_by,
                "saved"=>$head->saved,
                "done_joi"=>$head->done_joi,
                "date_revised"=>$this->input->post('approve_date'),
                "revision_no"=>$head->revision_no,
                "revise_attachment"=>$head->revise_attachment,
            );
            if($this->super_model->insert_into("joi_head_revised", $data_head)){
                foreach($this->super_model->select_row_where("joi_head_temp","joi_id",$joi_id) AS $headt){
                    $data_po=array(
                        "joi_date"=>$headt->joi_date,
                        "shipping"=>$headt->shipping,
                        "packing_fee"=>$headt->packing_fee,
                        "vat"=>$headt->vat,
                        "vat_percent"=>$headt->vat_percent,
                        "discount"=>$headt->discount,
                        "date_needed"=>$headt->date_needed,
                        "completion_date"=>$headt->completion_date,
                        "cenpri_jo_no"=>$headt->cenpri_jo_no,
                        "start_of_work"=>$headt->start_of_work,
                        "project_title"=>$headt->project_title,
                        "conforme"=>$headt->conforme,
                        "recommended_by"=>$headt->recommended_by,
                        "approved_by"=>$headt->approved_by,
                        "checked_by"=>$headt->checked_by,
                        "verified_by"=>$headt->verified_by,
                    );
                }
                $this->super_model->update_where("joi_head", $data_po, "joi_id", $joi_id);
            }
        }

        foreach($this->super_model->select_row_where("joi_jor","joi_id",$joi_id) AS $popr){
            $data_popr = array(
                "joi_jor_id"=>$popr->joi_jor_id,
                "joi_id"=>$popr->joi_id,
                "jor_aoq_id"=>$popr->jor_aoq_id,
                "purpose"=>$popr->purpose,
                "requestor"=>$popr->requestor,
                "notes"=>$popr->notes,
                "revision_no"=>$popr->revision_no,
            );
            $this->super_model->insert_into("joi_jor_revised", $data_popr);
        }

        foreach($this->super_model->select_row_where("joi_items","joi_id",$joi_id) AS $poitems){
            $data_items = array(
                "joi_items_id"=>$poitems->joi_items_id,
                "jor_id"=>$poitems->jor_id,
                "joi_id"=>$poitems->joi_id,
                "jor_aoq_offer_id"=>$poitems->jor_aoq_offer_id,
                "jor_aoq_items_id"=>$poitems->jor_aoq_items_id,
                "joi_items_id"=>$poitems->joi_items_id,
                "offer"=>$poitems->offer,
                "item_id"=>$poitems->item_id,
                "delivered_quantity"=>$poitems->delivered_quantity,
                "unit_price"=>$poitems->unit_price,
                "uom"=>$poitems->uom,
                "amount"=>$poitems->amount,
                "item_no"=>$poitems->item_no,
                "revision_no"=>$poitems->revision_no,
            );
            $this->super_model->insert_into("joi_items_revised", $data_items);
        }


        foreach($this->super_model->select_row_where("joi_tc","joi_id",$joi_id) AS $potc){
            $data_potc = array(
                "joi_tc_id"=>$potc->joi_tc_id,
                "joi_id"=>$popr->joi_id,
                "tc_desc"=>$potc->tc_desc,
                "notes"=>$potc->notes,
                "revision_no"=>$potc->revision_no,
            );
            if($this->super_model->insert_into("joi_tc_revised", $data_potc)){
                $data_tcn =array(
                    'revision_no'=>$revision_no
                );

                $this->super_model->update_where("joi_tc", $data_tcn, "joi_id", $joi_id);
            }
        }

        foreach($this->super_model->select_row_where("joi_tc_temp","joi_id",$joi_id) AS $potcr){
            $data_rev = array(
                "joi_tc_id"=>$potcr->joi_tc_id,
                "joi_id"=>$popr->joi_id,
                "tc_desc"=>$potcr->tc_desc,
                "notes"=>$potcr->notes,
            );
            $this->super_model->update_where("joi_tc", $data_rev, "joi_tc_id", $potcr->joi_tc_id);
        }

        foreach($this->super_model->custom_query("SELECT joi_items_id FROM joi_items WHERE joi_items_id NOT IN (SELECT joi_items_id FROM joi_items_temp WHERE joi_id='$joi_id')  AND joi_id = '$joi_id'") AS $omit){
           
            $delete_item = $this->super_model->delete_custom_where("joi_items", "joi_items_id='$omit->joi_items_id'");

            $delete_dr = $this->super_model->delete_custom_where("joi_dr_items", "joi_items_id='$omit->joi_items_id'");
        }


        foreach($this->super_model->select_row_where("joi_items_temp","joi_id",$joi_id) AS $poitems){
            $oldqty = $this->super_model->select_column_where('joi_items', 'quantity', 'joi_items_id',  $poitems->joi_items_id);
            if($oldqty==0){
                $nqty=0;
            } else {
                $nqty = $oldqty-$poitems->quantity;
            }
            $data_items = array(
                "jor_id"=>$poitems->jor_id,
                "joi_id"=>$poitems->joi_id,
                "jor_aoq_offer_id"=>$poitems->jor_aoq_offer_id,
                "jor_aoq_items_id"=>$poitems->jor_aoq_items_id,
                "jor_items_id"=>$poitems->jor_items_id,
                "offer"=>$poitems->offer,
                "item_id"=>$poitems->item_id,
                "delivered_quantity"=>$poitems->delivered_quantity,
                "quantity"=>$nqty,
                "unit_price"=>$poitems->unit_price,
                "uom"=>$poitems->uom,
                "amount"=>$poitems->amount,
                "item_no"=>$poitems->item_no,
                "revision_no"=>$revision_no
            );
             $this->super_model->update_where("joi_items", $data_items, "joi_items_id", $poitems->joi_items_id);

            $data_dr_items = array(
                'delivered_quantity'=>$poitems->delivered_quantity,
                'quantity'=>0,
                "uom"=>$poitems->uom,
                'unit_price'=>$poitems->unit_price,
                'amount'=>$poitems->amount,
                'offer'=>$poitems->offer
            );

            $this->super_model->update_where("joi_dr_items", $data_dr_items, "joi_items_id", $poitems->joi_items_id);

            $old_qty = $this->super_model->select_column_where('joi_items', 'delivered_quantity', 'jor_aoq_offer_id',  $poitems->jor_aoq_offer_id);
            if($old_qty!=$poitems->delivered_quantity){
           
                $difference = $old_qty - $poitems->quantity;

                $old_balance = $this->super_model->select_column_where('jor_aoq_offers', 'balance', 'jor_aoq_offer_id',  $poitems->jor_aoq_offer_id);

                $balance = $old_balance+$difference;

                $data_aoq = array(
                    'balance'=>$balance
                );
                $this->super_model->update_where("jor_aoq_offers", $data_aoq, "jor_aoq_offer_id", $poitems->jor_aoq_offer_id);
            } else {
             
                $balance = $this->super_model->select_column_where('jor_aoq_offers', 'balance', 'jor_aoq_offer_id',  $poitems->jor_aoq_offer_id);
                $data_aoq = array(
                    'balance'=>$balance
                );
                $this->super_model->update_where("jor_aoq_offers", $data_aoq, "jor_aoq_offer_id", $poitems->jor_aoq_offer_id);
            }
         
            
        }
        $this->super_model->delete_where("joi_head_temp", "joi_id", $joi_id);
        $this->super_model->delete_where("joi_tc_temp", "joi_id", $joi_id);
        $this->super_model->delete_where("joi_items_temp", "joi_id", $joi_id);    
        $data_pr =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("joi_jor", $data_pr, "joi_id", $joi_id);

        $data =array(
            'served'=>0,
            'date_served'=>NULL,
            'served_by'=>0,
            'approve_rev_by'=>$this->input->post('approve_rev'),
            'approve_rev_date'=>$this->input->post('approve_date'),
            'revised'=>0,
            'revision_no'=>$revision_no
        );
        if($this->super_model->update_where("joi_head", $data, "joi_id", $joi_id)){
            redirect(base_url().'joi/joi_list/', 'refresh');
        }
    }

    public function add_tc_temp(){
        $joi_id = $this->input->post('joi_id');

        $rows_head = $this->super_model->count_rows("joi_tc");
        if($rows_head==0){
            $joi_tc_id=1;
        } else {
            $max = $this->super_model->get_max("joi_tc", "joi_tc_id");
            $joi_tc_id = $max+1;
        }
        $data = array(
            'joi_tc_id'=>$joi_tc_id,
            'joi_id'=>$this->input->post('joi_id'),
            'tc_desc'=>$this->input->post('terms'),
        );
        if($this->super_model->insert_into("joi_tc", $data)){
            redirect(base_url().'joi/jo_issuance_rev/'.$joi_id, 'refresh');
        }
    }

    public function view_history(){
        $this->load->view('template/header');
        $joi_id=$this->uri->segment(3);
        $cenjo_no=str_replace("%20", " ", $this->uri->segment(4));
        $joi_no=str_replace("%20", " ", $this->uri->segment(5));
        $data['joi_id']=$joi_id;
        $data['cenjo_no']=$cenjo_no;
        $data['joi_no']=$joi_no;

        $row = $this->super_model->count_rows_where("joi_head_revised", "joi_id",$joi_id);
        if($row!=0){
            foreach($this->super_model->select_custom_where("joi_head_revised", "joi_id = '$joi_id'") AS $rev){
                $data['revise'][]=array(
                    'joi_id'=>$joi_id,
                    'cenjo_no'=>$rev->cenpri_jo_no,
                    'joi_no'=>$rev->joi_no,
                    'date_revised'=>$rev->date_revised,
                    'revision_no'=>$rev->revision_no,
                );
            }
        }else {
            $data['revise']=array();
        }    
        $this->load->view('joi/view_history',$data);
        $this->load->view('template/footer');
    }

    public function jo_issuance_saved_r(){
        $this->load->view('template/header'); 
        $joi_id=$this->uri->segment(3);
        $revise_no=$this->uri->segment(4);
        $data['joi_id']=$joi_id;
        $data['revise_no']=$revise_no;
        $vendor_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        foreach($this->super_model->select_custom_where('joi_head_revised', "joi_id = '$joi_id' AND revision_no = '$revise_no'") AS $h){
            $data['head'][] = array(
                'joi_date'=>$h->joi_date,
                'joi_no'=>$h->joi_no,
                'project_title'=>$h->project_title,
                'completion_date'=>$h->completion_date,
                'date_prepared'=>$h->date_prepared,
                'start_of_work'=>$h->start_of_work,
                'date_needed'=>$h->date_needed,
                'cenpri_jo_no'=>$h->cenpri_jo_no,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['conforme']= $h->conforme;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['joi_no']=$h->joi_no;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
            $data['verified_by']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->verified_by);
        }
        $data['items'] = $this->super_model->select_custom_where('joi_items_revised', "joi_id = '$joi_id' AND revision_no = '$revise_no'");
        foreach($this->super_model->select_custom_where("joi_jor_revised", "joi_id = '$joi_id' AND revision_no = '$revise_no'") AS $ppr){
            $jo=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }
            $data['allpr'][]= array(
                'jo_no'=>$jo_no,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['vendor_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'vendor_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['aoq_vendors_id'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'jor_aoq_vendors_id', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['price_validity'] = $this->super_model->select_column_custom_where('jor_aoq_vendors', 'price_validity', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['payment_terms']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'payment_terms', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['item_warranty']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'item_warranty', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['freight']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'freight', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
            $data['delivery_time']= $this->super_model->select_column_custom_where('jor_aoq_vendors', 'delivery_date', "jor_aoq_id = '$ppr->jor_aoq_id' AND vendor_id='$vendor_id'");
        }       
        $data['tc'] = $this->super_model->select_custom_where("joi_tc_revised", "joi_id='$joi_id' AND revision_no = '$revise_no'");
        $this->load->view('joi/jo_issuance_saved_r',$data);
        $this->load->view('template/footer');
    }

    public function joi_rfd(){
        $this->load->view('template/header');
        $this->load->view('joi/joi_rfd');
        $this->load->view('template/footer');
    }

    public function joi_dr(){
        $this->load->view('template/header');
        $this->load->view('joi/joi_dr');
        $this->load->view('template/footer');
    }

    public function joi_ac(){
        $this->load->view('template/header');
        $this->load->view('joi/joi_ac');
        $this->load->view('template/footer');
    }

    public function joi_coc(){
        $this->load->view('template/header');
        $this->load->view('joi/joi_coc');
        $this->load->view('template/footer');
    }
}

?>