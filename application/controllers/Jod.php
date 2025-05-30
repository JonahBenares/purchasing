<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jod extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->model('super_model');
        date_default_timezone_set("Asia/Manila");
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


    public function currency_list(){
        $currency = array(
            'AUD',
            'BDT',
            'CAD',
            'EUR',
            'HKD',
            'IDR',
            'INR',
            'IQD',
            'JPY',
            'KPW',
            'LBP',
            'MXN',
            'MMK',
            'NZD',
            'OMR',
            'PHP',
            'PKR',
            'QAR',
            'THB',
            'USD',
            'GBP',
        );

        return $currency;
    }  

    public function jod_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar'); 
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");      
        foreach($this->super_model->select_custom_where("joi_head", "served='0' AND cancelled='0' ORDER BY joi_date DESC") AS $head){
            $joi_dr_id = $this->super_model->select_column_custom_where("joi_dr", "joi_dr_id", "joi_id = '$head->joi_id' AND received='0'");
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
                'served'=>$head->served,
                'revision_no'=>$head->revision_no,
                'joi_dr_id'=>$joi_dr_id
            );
        }
        $this->load->view('jod/jod_list',$data);
        $this->load->view('template/footer');
    }

    public function deliver_jod(){
        $joi_id = $this->uri->segment(3);
        $joi_dr_id = $this->uri->segment(4);
        $data['joi_id']=$joi_id;
        $data['joi_dr_id']=$joi_dr_id;
        $data['items'] = $this->super_model->select_custom_where("joi_dr_items","joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'");
        $this->load->view('template/header');        
        $this->load->view('jod/deliver_jod',$data);
        $this->load->view('template/footer');
    }

    public function save_delivery(){
        $count = $this->input->post('count');
        $joi_id = $this->input->post('joi_id');
        $joi_dr_id = $this->input->post('joi_dr_id');

        $data_dr = array(
            'received'=>1,
            'date_received'=>$this->input->post('date_delivered'),
        );

         $this->super_model->update_where("joi_dr", $data_dr, "joi_dr_id", $joi_dr_id);

        $data_head = array(
            'served'=>1,
            'served_by'=>$_SESSION['user_id']
        );

        $this->super_model->update_where("joi_head", $data_head, "joi_id", $joi_id);
        for($x=1;$x<$count;$x++){
            $joi_items_id = $this->input->post('joi_items_id'.$x);
            $curr_rec_balance = $this->super_model->select_column_where('joi_items', 'quantity', 'joi_items_id', $joi_items_id);
            $new_qty = $curr_rec_balance + $this->input->post('received_qty'.$x);
            $data_poitems=array(
                'quantity'=>$new_qty,
            );

            $data=array(
                'quantity'=>$this->input->post('received_qty'.$x),
            );

            $this->super_model->update_where("joi_items", $data_poitems, "joi_items_id", $joi_items_id);
            $this->super_model->update_custom_where("joi_dr_items", $data, "joi_items_id='$joi_items_id' AND joi_dr_id='$joi_dr_id'");

            $curr_balance = $this->super_model->select_column_where('jor_aoq_offers', 'balance', 'jor_aoq_offer_id', $this->input->post('jor_aoq_offer_id'.$x));
            $new_balance = $curr_balance-$this->input->post('received_qty'.$x);

            $data_aoq = array(
                'balance'=>$new_balance,
            );
            $this->super_model->update_where("jor_aoq_offers", $data_aoq, "jor_aoq_offer_id", $this->input->post('jor_aoq_offer_id'.$x));
        }

        $curr_matrec_balance = $this->super_model->select_column_where('joi_items', 'materials_received', 'joi_items_id', $joi_items_id);
        $new_mat_qty = $curr_matrec_balance + $this->input->post('materials_received'); 
        $data_poitems=array(
            'materials_received'=>$new_mat_qty
        ); 

        $data=array(
            'materials_received'=>$this->input->post('materials_received'),
        );
        $this->super_model->update_where("joi_items", $data_poitems, "joi_items_id", $joi_items_id);
        $this->super_model->update_custom_where("joi_dr_items", $data, "joi_items_id='$joi_items_id' AND joi_dr_id='$joi_dr_id'");
        $curr_materials_balance = $this->super_model->select_column_where('jor_aoq_offers', 'materials_balance', 'jor_aoq_offer_id', $this->input->post('jor_aoq_offer_id'));
        $new_materials_balance = $curr_materials_balance-$this->input->post('materials_received');
        $data_aoq = array(
            'materials_balance'=>$new_materials_balance
        );
        $this->super_model->update_where("jor_aoq_offers", $data_aoq, "jor_aoq_offer_id", $this->input->post('jor_aoq_offer_id'));
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

    public function incom_jodel(){
        $data=array();
        foreach($this->super_model->custom_query("SELECT jh.* FROM joi_head jh INNER JOIN joi_items ji ON jh.joi_id = ji.joi_id WHERE saved='1' AND cancelled = '0' AND served = '1' AND ji.delivered_quantity > ji.quantity GROUP BY joi_id ORDER BY jh.joi_id DESC") AS $head){
             $rfd=$this->super_model->count_rows_where("joi_rfd","joi_id",$head->joi_id);
             $jo='';
            foreach($this->super_model->select_row_where("joi_jor", "joi_id", $head->joi_id) AS $prd){
                $jo=$this->super_model->select_column_where('jor_head','jo_no','jor_id', $prd->jor_id);
                /*if($jo_no!=''){
                    $jo .= $jo_no."<br>";
                }else{
                    $jo .= $this->super_model->select_column_where('jor_head','user_jo_no','jor_id', $prd->jor_id)."<br>";
                }*/
            }
            $joi_dr_id = $this->super_model->select_column_custom_where("joi_dr", "joi_dr_id", "joi_id = '$head->joi_id' AND received='0'");
            $unreceived_dr = $this->super_model->count_custom_where("joi_dr","joi_id = '$head->joi_id' AND received ='0'");
            $data['header'][]=array(
                'joi_id'=>$head->joi_id,
                'joi_date'=>$head->joi_date,
                'joi_no'=>$head->joi_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                'joi_type'=>$head->joi_type,
                'jo'=>$jo,
                'rfd'=>$rfd,
                'joi_type'=>$head->joi_type,
                'revised'=>$head->revised,
                'revision_no'=>$head->revision_no,
                'served'=>$head->served,
                'unreceived_dr'=>$unreceived_dr,
                'joi_dr_id'=>$joi_dr_id
            );
        }        
        $this->load->view('template/header');        
        $this->load->view('template/navbar');        
        $this->load->view('jod/incom_jodel',$data);
        $this->load->view('template/footer');
    }

    public function create_dr(){
        $joi_id = $this->uri->segment(3);
/*        $rows_dr = $this->super_model->count_rows("joi_dr");
        if($rows_dr==0){
            $joi_dr_no=1000;
            $joi_dr_id = 1;
        } else {
            $max = $this->super_model->get_max("joi_dr", "joi_dr_no");
            $maxid = $this->super_model->get_max("joi_dr", "joi_dr_id");
            $joi_dr_no = $max+1;
            $joi_dr_id = $maxid+1;
        }*/

        $rows_dr = $this->super_model->count_rows("joi_dr");
        if($rows_dr==0){
            $joi_dr_id=1;
        } else {
            $maxid = $this->super_model->get_max("joi_dr", "joi_dr_id");
            $joi_dr_id = $maxid+1;
        }


        // $year=date('Y');
        $year = date("Y",strtotime($this->super_model->select_column_where('joi_head', 'joi_date', 'joi_id', $joi_id)));
        $dr_count = $this->super_model->count_custom_where("joi_dr","joi_dr_date LIKE '%$year%'");
        if($dr_count==0){
            $joi_dr_no = 1000;
        }else{
            $maxno = $this->super_model->get_max_where("joi_dr", "joi_dr_no","joi_dr_date LIKE '%$year%'");
            $joi_dr_no = $maxno + 1;
        }

         $dr = array(
            'joi_dr_id'=>$joi_dr_id,
            'joi_id'=>$joi_id,
            'joi_dr_no'=>$joi_dr_no,
            'joi_dr_date'=>$this->super_model->select_column_where('joi_head', 'joi_date', 'joi_id', $joi_id),
        );
        $this->super_model->insert_into("joi_dr", $dr);

        foreach($this->super_model->custom_query("SELECT * FROM joi_items WHERE (delivered_quantity > quantity || materials_qty > materials_received) AND joi_id = '$joi_id'") AS $items){
            $new_qty = $items->delivered_quantity-$items->quantity;
            $new_materials_qty = $items->materials_qty-$items->materials_received;
            $data = array(
                'joi_items_id'=>$items->joi_items_id,
                'joi_dr_id'=>$joi_dr_id,
                'jor_id'=>$items->jor_id,
                'joi_id'=>$items->joi_id,
                'joi_aoq_offer_id'=>$items->jor_aoq_offer_id,
                'joi_aoq_items_id'=>$items->jor_aoq_items_id,
                'jor_items_id'=>$items->jor_items_id,
                'offer'=>$items->offer,
                'item_id'=>$items->item_id,
                'delivered_quantity'=>$new_qty,
                'unit_price'=>$items->unit_price,
                'uom'=>$items->uom,
                'amount'=>$items->amount,
                'item_no'=>$items->item_no,
                'materials_offer'=>$items->materials_offer,
                'materials_qty'=>$new_materials_qty,
                'materials_unitprice'=>$items->materials_unitprice,
                'materials_amount'=>$items->materials_amount,
                'materials_unit'=>$items->materials_unit,
                'materials_currency'=>$items->materials_currency,
            );
            $this->super_model->insert_into("joi_dr_items", $data);
        }

          redirect(base_url().'jod/delivery_receipt/'.$joi_id.'/'.$joi_dr_id, 'refresh');
    }

    public function delivery_receipt(){
        $joi_id = $this->uri->segment(3); 
        $joi_dr_id = $this->uri->segment(4);
        $data['joi_id']=$joi_id;
        $data['joi_dr_id']=$joi_dr_id; 
        $data['head']= $this->super_model->select_row_where('joi_head', 'joi_id', $joi_id);
        $data['revision_no']= $this->super_model->select_column_where("joi_dr", "revision_no", "joi_id", $joi_id);
        $data['saved'] = $this->super_model->select_column_where("joi_dr", "saved", "joi_id", $joi_id);
        $user_id= $this->super_model->select_column_where("joi_head", "user_id", "joi_id", $joi_id);
        $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $user_id);
        $data['cancelled']=$this->super_model->select_column_where("joi_head", "cancelled", "joi_id", $joi_id);
        $data['delivered_to'] = $this->super_model->select_column_where("joi_dr", "delivered_to", "joi_id", $joi_id);
        $data['address'] = $this->super_model->select_column_where("joi_dr", "address", "joi_id", $joi_id);
        $jor_id= $this->super_model->select_column_where("joi_items", "jor_id", "joi_id", $joi_id);
        $data['requested_by'] = $this->super_model->select_column_where("jor_head", "requested_by", "jor_id", $jor_id);
        $data['jor_no'] = $this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor_id);
        $data['user_jo_no'] = $this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $jor_id);
        foreach($this->super_model->select_row_where('joi_jor', 'joi_id', $joi_id) AS $pr){
             $itemno='';
            foreach($this->super_model->select_custom_where("joi_dr_items", "jor_id= '$pr->jor_id' AND joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $pr->jor_id);
            /*if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $pr->jor_id);
            }*/
            $data['pr'][]=array(
                'jo_no'=>$jo_no,
                'enduse'=>$pr->enduse,
                'purpose'=>$pr->purpose,
                'requestor'=>$pr->requestor,
                'item_no'=>$item_no
            );
        }

        if(empty($joi_dr_id)){
            $data['dr_no']= $this->super_model->select_column_where("joi_dr", "joi_dr_no", "joi_id", $joi_id);
            foreach($this->super_model->select_custom_where("joi_dr_items", "joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'") AS $items){
               $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
               $data['materials_offer']= $items->materials_offer;
                $data['materials_qty']= $items->materials_qty;
                $data['items'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("jor_aoq_items", "scope_of_work", "jor_aoq_items_id", $items->joi_aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'materials_offer'=>$items->materials_offer,
                    'materials_qty'=>$items->materials_qty,
                    'materials_unitprice'=>$items->materials_unitprice,
                    'materials_amount'=>$items->materials_amount,
                    'materials_received'=>$items->materials_received,
                    'materials_unit'=>$items->materials_unit,
                    'uom'=>$items->uom,
                );
            }
            //MATERIALS
            foreach($this->super_model->select_custom_where("joi_dr_items", "joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id' GROUP BY materials_offer") AS $items){
               $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
               $data['materials_offer']= $items->materials_offer;
                $data['materials_qty']= $items->materials_qty;
                $data['items_materials'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("jor_aoq_items", "scope_of_work", "jor_aoq_items_id", $items->joi_aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'materials_offer'=>$items->materials_offer,
                    'materials_qty'=>$items->materials_qty,
                    'materials_unitprice'=>$items->materials_unitprice,
                    'materials_amount'=>$items->materials_amount,
                    'materials_received'=>$items->materials_received,
                    'materials_unit'=>$items->materials_unit,
                    'uom'=>$items->uom,
                );
            }
        } else {
            $data['dr_no']= $this->super_model->select_column_custom_where("joi_dr", "joi_dr_no", "joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'");
            foreach($this->super_model->select_custom_where('joi_dr_items', "joi_id= '$joi_id' AND joi_dr_id = '$joi_dr_id'") AS $items){
               $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
               $data['materials_offer']= $items->materials_offer;
            $data['materials_qty']= $items->materials_qty;
                $data['items'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("jor_aoq_items", "scope_of_work", "jor_aoq_items_id", $items->joi_aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'materials_offer'=>$items->materials_offer,
                    'materials_qty'=>$items->materials_qty,
                    'materials_unitprice'=>$items->materials_unitprice,
                    'materials_amount'=>$items->materials_amount,
                    'materials_received'=>$items->materials_received,
                    'materials_unit'=>$items->materials_unit,
                    'uom'=>$items->uom,
                );
            }
            //MATERIALS
            foreach($this->super_model->select_custom_where('joi_dr_items', "joi_id= '$joi_id' AND joi_dr_id = '$joi_dr_id' GROUP BY materials_offer") AS $items){
               $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
               $data['materials_offer']= $items->materials_offer;
            $data['materials_qty']= $items->materials_qty;
                $data['items_materials'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("jor_aoq_items", "scope_of_work", "jor_aoq_items_id", $items->joi_aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'materials_offer'=>$items->materials_offer,
                    'materials_qty'=>$items->materials_qty,
                    'materials_unitprice'=>$items->materials_unitprice,
                    'materials_amount'=>$items->materials_amount,
                    'materials_received'=>$items->materials_received,
                    'materials_unit'=>$items->materials_unit,
                    'uom'=>$items->uom,
                );
            }
        }
        $this->load->view('template/header');        
        $this->load->view('jod/delivery_receipt',$data);
        $this->load->view('template/footer');
    }

        public function save_dr(){
        $joi_id = $this->input->post('joi_id');
        $joi_dr_id = $this->input->post('joi_dr_id');
        $data = array(
            'delivered_to'=>$this->input->post('delivered_to'),
            'address'=>$this->input->post('address'),
            'requested_by'=>$this->input->post('requested_by'),
            'saved'=>1
        );
        if($this->super_model->update_where("joi_dr", $data, "joi_dr_id", $joi_dr_id)){
            echo "<script>window.location ='".base_url()."jod/delivery_receipt/$joi_id/$joi_dr_id';</script>";
        }
    }

    public function served_jo(){
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $this->load->view('template/header');        
        $this->load->view('template/navbar'); 
        foreach($this->super_model->select_custom_where("joi_head", "saved='1' AND served='1' ORDER BY joi_id DESC") AS $head){
             $rfd=$this->super_model->count_rows_where("joi_dr","joi_id",$head->joi_id);
             $jo='';
            foreach($this->super_model->select_row_where("joi_jor", "joi_id", $head->joi_id) AS $prd){
                $jo=$this->super_model->select_column_where('jor_head','jo_no','jor_id', $prd->jor_id);
                /*if($jo_no!=''){
                    $jo .= $jo_no."<br>";
                }else{
                    $jo .= $this->super_model->select_column_where('jor_head','user_jo_no','jor_id', $prd->jor_id)."<br>";
                }*/
               
            }
            $data['header'][]=array(
                'joi_id'=>$head->joi_id,
                'joi_date'=>$head->joi_date,
                'joi_no'=>$head->joi_no,
                'supplier'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id),
                'supplier_id'=>$head->vendor_id,
                'saved'=>$head->saved,
                'jo'=>$jo,
                'rfd'=>$rfd,
                'joi_type'=>$head->joi_type
            );
        }  
        $this->load->view('jod/served_jo',$data);
        $this->load->view('template/footer');
    }

    public function cancelled_joi(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar'); 
        $data['vendor']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        foreach($this->super_model->select_custom_where("joi_head", "cancelled='1' ORDER BY joi_date DESC") AS $head){
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
                'cancelled_date'=>$head->cancelled_date,
                'cancel_reason'=>$head->cancel_reason,
            );
        }
        $this->load->view('jod/cancelled_jod',$data);
        $this->load->view('template/footer');
    }

    public function getsupplierJOD(){
        $supplier = $this->input->post('supplier');
        echo '<option value="">-Select JO No-</option>';
        foreach($this->super_model->custom_query("SELECT ah.jor_id, ah.jor_aoq_id, ah.aoq_date FROM jor_aoq_head ah INNER JOIN jor_aoq_offers ao ON ah.jor_aoq_id = ao.jor_aoq_id WHERE vendor_id = '$supplier' AND recommended = '1' and cancelled='0' GROUP BY ah.jor_aoq_id") AS $row){
            $jo_no=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $row->jor_id);
            /*if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $row->jor_id);
            }*/
            echo '<option value="'. $row->jor_id."_".$row->jor_aoq_id .'">'. $jo_no .' ('.$row->aoq_date." - ".$row->jor_aoq_id.')</option>';
        }
    }

    public function getJODinformation(){

        $jor_id = $this->input->post('jor_id');
        $joexp = explode('_',$jor_id);
        $jorid=$joexp[0];
        $jor_aoq_id=$joexp[1];
        $project_title= $this->super_model->select_column_where('jor_head', 'purpose', 'jor_id', $jorid);
        $date_prepared= $this->super_model->select_column_where('jor_head', 'date_prepared', 'jor_id', $jorid);
        $user_jo_no= $this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $jorid);
        $general_desc= $this->super_model->select_column_where('jor_items', 'general_desc', 'jor_id', $jorid);
        //$completion_date= $this->super_model->select_column_where('jor_head', 'completion_date', 'jor_id', $jorid);

        
        $return = array('purpose' => $project_title, 'date_prepared' => $date_prepared, 'user_jo_no' => $user_jo_no, 'general_desc' => $general_desc, 'jor_aoq_id'=>$jor_aoq_id);
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
/*        $rows_head = $this->super_model->count_rows("joi_head");
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
        }*/

        $rows_head = $this->super_model->count_rows("joi_head");
        if($rows_head==0){
            $joi_id=1;
        } else {
            $max = $this->super_model->get_max("joi_head", "joi_id");
            $joi_id = $max+1;
        }


        $year=date('Y');
        $rows_series = $this->super_model->count_custom_where("joi_head","joi_date LIKE '%$year%'");
        if($rows_series==0){
            $series=1000;
        }else{
            $joi_max = $this->super_model->get_max_where("joi_head", "joi_no","joi_date LIKE '%$year%'");
            
            $joi_exp=explode("-", $joi_max);
            $series = $joi_exp[1]+1;
        }

        if(empty($this->input->post('dp'))){
            $jo_no= $this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id',$this->input->post('jo_no'));
            /*if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id',$this->input->post('jo_no'));
            }*/
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
                 redirect(base_url().'jod/jo_direct/'.$joi_id);
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

          
            if($this->super_model->insert_into("joi_head", $data)){
                 redirect(base_url().'jod/jo_direct/'.$joi_id);
            }
        }
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

    public function jo_direct(){
        $this->load->view('template/header');
        $joi_id = $this->uri->segment(3);
        $jor_id = $this->uri->segment(4);
        $group_id = $this->uri->segment(5);
        $joi_tc_id = $this->uri->segment(6);
        $data['currency'] = $this->currency_list();
        $data['jor_id'] = $jor_id;
        $data['joi_id'] = $joi_id;
        $data['group_id'] = $group_id;
        $data['currency1']='';
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
                'general_desc'=>$h->general_desc,
                'vendor'=>$this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $h->vendor_id),
                'address'=>$this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $h->vendor_id),
                'phone'=>$this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id',$h->vendor_id),
                'fax'=>$this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id',$h->vendor_id),
                'contact'=>$this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $h->vendor_id),
            );
            $data['saved']=$h->saved;
            $data['notes']=$h->notes;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['shipping']=$h->shipping;
            $data['discount']=$h->discount;
            $data['packing']=$h->packing_fee;
            $data['vat']=$h->vat;
            $data['vat_percent']=$h->vat_percent;
            $data['vat_percent']=$h->vat_percent;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['vat_in_ex']=$h->vat_in_ex;
            $data['revision_no']=$h->revision_no;
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
        }

        $saved=$this->super_model->select_column_where('joi_head', 'saved', 'joi_id', $joi_id);
        if($saved==0){
            foreach($this->super_model->select_custom_where("jor_items", "jor_id = '$jor_id' AND grouping_id = '$group_id'") AS $items){
                $data['currency1']='';
                $data['items'][]= array(
                    'jor_items_id'=>$items->jor_items_id,
                    'offer'=>$items->scope_of_work,
                    'uom'=>$items->uom,
                    'currency'=>'',
                    'quantity'=>$items->quantity,
                );
            }
        }else {
            foreach($this->super_model->select_row_where("joi_items", "joi_id", $joi_id) AS $items){
                $total = $items->delivered_quantity*$items->unit_price;
                $total_amount = $items->materials_qty*$items->materials_unitprice;
                $data['currency1']=$items->currency;
                $data['items'][]= array(
                    'jor_items_id'=>$items->jor_items_id,
                    'joi_items_id'=>$items->joi_items_id,
                    'offer'=>$items->offer,
                    'uom'=>$items->uom,
                    'quantity'=>$items->delivered_quantity,
                    'price'=>$items->unit_price,
                    'currency'=>$items->currency,
                    'total'=>$total,
                    'materials_offer'=>$items->materials_offer,
                    'materials_qty'=>$items->materials_qty,
                    'materials_unitprice'=>$items->materials_unitprice,
                    'materials_amount'=>$total_amount,
                );
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
        $this->load->view('jod/jo_direct',$data);
        $this->load->view('template/footer');
    }

    public function save_jod(){
        $submit = $this->input->post('submit');
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $count_item = $this->input->post('count_item');
        $count_notes = $this->input->post('count_notes');
        $a=1;
/*        $rows_dr = $this->super_model->count_rows("joi_dr");
        if($rows_dr==0){
            $joi_dr_no=1000;
            $joi_dr_id = 1;
        } else {
            $max = $this->super_model->get_max("joi_dr", "joi_dr_no");
            $maxid = $this->super_model->get_max("joi_dr", "joi_dr_id");
            $joi_dr_no = $max+1;
            $joi_dr_id = $maxid+1;
        }*/

        $rows_dr = $this->super_model->count_rows("joi_dr");
        if($rows_dr==0){
            $joi_dr_id=1;
        } else {
            $maxid = $this->super_model->get_max("joi_dr", "joi_dr_id");
            $joi_dr_id = $maxid+1;
        }


        $year=date('Y');
        $dr_count = $this->super_model->count_custom_where("joi_dr","joi_dr_date LIKE '%$year%'");
        if($dr_count==0){
            $joi_dr_no = 1000;
        }else{
            $maxno = $this->super_model->get_max_where("joi_dr", "joi_dr_no","joi_dr_date LIKE '%$year%'");
            $joi_dr_no = $maxno + 1;
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
                $materials_price = str_replace(",", "", $this->input->post('materials_price'));
                $materials_tprice = str_replace(",", "", $this->input->post('materials_tprice'));
                $data=array(
                    'joi_items_id'=>$joi_items_id,
                    'jor_id'=>$jor_id,
                    'joi_id'=>$joi_id,
                    //'jor_aoq_offer_id'=>$this->input->post('jor_aoq_offer_id'.$x),
                    //'jor_aoq_items_id'=>$this->input->post('jor_aoq_items_id'.$x),
                    'jor_items_id'=>$this->input->post('jor_items_id'.$x),
                    'offer'=>$this->input->post('offer'.$x),
                    'delivered_quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a,
                    'materials_unitprice'=>$materials_price,
                    'materials_amount'=>$materials_tprice,
                    'materials_offer'=>$this->input->post('materials_offer'),
                    'materials_qty'=>$this->input->post('materials_qty'),
                    'materials_unit'=>$this->input->post('materials_uom'),
                    'materials_currency'=>$this->input->post('materials_currency'),
                );



                $data_dr=array(
                    'joi_items_id'=>$joi_items_id,  
                    'jor_id'=>$jor_id,
                    'joi_dr_id'=>$joi_dr_id,
                    'joi_id'=>$joi_id,
                    //'joi_aoq_offer_id'=>$this->input->post('jor_aoq_offer_id'.$x),
                    //'joi_aoq_items_id'=>$this->input->post('jor_aoq_items_id'.$x),
                    'jor_items_id'=>$this->input->post('jor_items_id'.$x),
                    'offer'=>$this->input->post('offer'.$x),
                    'delivered_quantity'=>$qty,
                    'uom'=>$this->input->post('uom'.$x),
                    'currency'=>$this->input->post('currency'.$x),
                    'unit_price'=>$price,
                    'amount'=>$amount,
                    'item_no'=>$a,
                );
                $this->super_model->insert_into("joi_items", $data);
                $this->super_model->insert_into("joi_dr_items", $data_dr);
                $a++;
            }   
        }
        /*$data_item=array(
            'materials_unitprice'=>$materials_price,
            'materials_amount'=>$materials_tprice,
            'materials_offer'=>$this->input->post('materials_offer'),
            'materials_qty'=>$this->input->post('materials_qty'),
            'materials_unit'=>$this->input->post('materials_uom'),
            'materials_currency'=>$this->input->post('materials_currency'),
        );

        $this->super_model->update_where("joi_items", $data_item, "joi_items_id", $joi_items_id);
        $this->super_model->update_where("joi_dr_items", $data_item, "joi_items_id", $joi_items_id);*/

        for($y=1; $y<$count_notes;$y++){
            $data_notes=array(
                "joi_id"=>$joi_id,
                "notes"=>$this->input->post('jor_notes'.$y),
            );
            $this->super_model->insert_into("joi_tc", $data_notes);
        }

        $date_format = date("Y");
        $rows_ar = $this->super_model->count_custom_where("joi_ar","ar_date LIKE '%$date_format%'");
        if($rows_ar==0){
            $ar_no= "AR ".$date_format."-01";
        } else {
            $max = $this->super_model->get_max_where("joi_ar", "series","ar_date LIKE '%$date_format%'");
            $nexts = $max+1;
            $nxts = str_pad($nexts, 2, "0", STR_PAD_LEFT);
            $ar_no = "AR ".$date_format."-".$nxts;
        }

        $ar_det=explode("-", $ar_no);
        $ar_prefix=$ar_det[0];
        $series = $ar_det[1];
        $ar = array(
            'joi_id'=>$joi_id,
            'year'=>$ar_prefix,
            'series'=>$series,
            'ar_date'=>$this->super_model->select_column_where('joi_head', 'date_prepared', 'joi_id', $joi_id),
        );

        $this->super_model->insert_into("joi_ar", $ar);

        $date_coc = date("Y");
        $rows_coc = $this->super_model->count_custom_where("joi_coc","year LIKE '%$date_coc%'");
        if($rows_coc==0){
            $coc_no= "COC-".$date_coc."_01";
        } else {
            $max = $this->super_model->get_max_where("joi_coc", "series","year LIKE '%$date_coc%'");
            $nexts = $max+1;
            $nxts = str_pad($nexts, 2, "0", STR_PAD_LEFT);
            $coc_no = "COC-".$date_coc."_".$nxts;
        }

        $coc_det=explode("_", $coc_no);
        $coc_prefix=$coc_det[0];
        $series = $coc_det[1];
        $coc = array(
            'joi_id'=>$joi_id,
            'year'=>$coc_prefix,
            'series'=>$series,
        );

        $this->super_model->insert_into("joi_coc", $coc);

        if($submit=='Save'){
            $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('less_amount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat_amount'),
                'discount_labor'=>$this->input->post('discount_lab'),
                'discount_material'=>$this->input->post('discount_mat'),
                'grand_total'=>$this->input->post('net'),
                'conforme'=>$this->input->post('conforme'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked_by'),
                'recommended_by'=>$this->input->post('recommended_by'),
                'approved_by'=>$this->input->post('approved_by'),
                'verified_by'=>$this->input->post('verified_by'),
                'vat_in_ex'=>$this->input->post('vat_in_ex'),   
                'saved'=>1,
                'revised'=>0
            ); 
             if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'jod/jo_direct_saved/'.$joi_id);
             }

        } else if($submit=='Save as Draft'){
             $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('less_amount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat_amount'),
                'discount_labor'=>$this->input->post('discount_lab'),
                'discount_material'=>$this->input->post('discount_mat'),
                'grand_total'=>$this->input->post('net'),
                'conforme'=>$this->input->post('conforme'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked_by'),
                'recommended_by'=>$this->input->post('recommended_by'),
                'approved_by'=>$this->input->post('approved_by'),
                'verified_by'=>$this->input->post('verified_by'),
                'vat_in_ex'=>$this->input->post('vat_in_ex'),
                'saved'=>0,
                'draft'=>1,
                'revised'=>0
            ); 
            if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'jod/jo_direct_draft/'.$joi_id);
            }
        }      
    }

    public function create_jod_terms(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $draft = $this->super_model->select_column_where("joi_head", "draft", "joi_id", $joi_id);
        $data = array(
            'joi_id'=>$this->input->post('joi_id'),
            'tc_desc'=>$this->input->post('terms'),
        );


        if($this->super_model->insert_into("joi_tc", $data)){
            if($draft==0){
                redirect(base_url().'jod/jo_direct/'.$joi_id."/".$jor_id."/".$group_id, 'refresh');
            } else {
                redirect(base_url().'jod/jo_direct_draft/'.$joi_id."/".$jor_id."/".$group_id, 'refresh');
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

            redirect(base_url().'jod/jo_direct/'.$joi_id);
        }
    }

    public function update_condition(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$tc_id)){
            redirect(base_url().'jod/jo_direct/'.$joi_id.'/'.$jor_id.'/'.$group_id);
        }
    }

    public function update_condition_revise(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$tc_id)){
            redirect(base_url().'jod/jo_direct_rev/'.$joi_id);
        }
    }

    public function update_condition_revise_temp(){
        $joi_id = $this->input->post('joi_id');
        $jor_id = $this->input->post('jor_id');
        $group_id = $this->input->post('group_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc_temp", $update, "joi_tc_id",$tc_id)){
            redirect(base_url().'jod/jo_direct_rev/'.$joi_id);
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
                redirect(base_url().'jod/jo_direct_saved/'.$joi_id, 'refresh');
            } else {
                redirect(base_url().'jod/jo_direct_draft/'.$joi_id, 'refresh');
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

            redirect(base_url().'jod/jo_direct_saved/'.$joi_id);
        }
    }

    public function update_condition_saved(){
        $joi_id = $this->input->post('joi_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$tc_id)){
            redirect(base_url().'jod/jo_direct_saved/'.$joi_id);
        }
    }

    public function update_terms_draft(){
        $joi_id = $this->input->post('joi_id');
        $aoq_vendors_id = $this->input->post('aoq_vendors_id');
        $update = array(
            'payment_terms'=>$this->input->post('payments'),
            'delivery_date'=>$this->input->post('del_itm'),
            'item_warranty'=>$this->input->post('item_war'),
            'freight'=>$this->input->post('freigh'),
        ); 
        if($this->super_model->update_where("aoq_vendors", $update, "aoq_vendors_id",$aoq_vendors_id)){
            
            redirect(base_url().'jod/jo_direct_draft/'.$joi_id);
        }
    }

    public function update_condition_draft(){
        $joi_id = $this->input->post('joi_id');
        $tc_id = $this->input->post('tc_id');
        $update = array(
            'tc_desc'=>$this->input->post('condition'),
        ); 
        if($this->super_model->update_where("joi_tc", $update, "joi_tc_id",$tc_id)){
            redirect(base_url().'jod/jo_direct_draft/'.$joi_id);
        }
    }

    public function jo_direct_saved(){
        $this->load->view('template/header');
        $joi_id = $this->uri->segment(3);
         $jor_id = $this->uri->segment(4);
        $data['joi_id'] = $joi_id;
        $data['cancelled']='';
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
                'general_desc'=>$h->general_desc,
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
            $data['discount_lab']=$h->discount_labor;
            $data['discount_mat']=$h->discount_material;
            $data['grand_total']=$h->grand_total;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['conforme']= $h->conforme;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['joi_no']=$h->joi_no;
            $data['notes']=$h->notes;
            $data['vat_in_ex']=$h->vat_in_ex;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
            $data['verified_by']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->verified_by);
            $data['cancelled']=$h->cancelled;
        }

        $data['items'] = $this->super_model->select_row_where('joi_items', 'joi_id', $joi_id);
        $data['items_materials'] = $this->super_model->select_custom_where('joi_items', "joi_id='$joi_id' GROUP BY materials_offer");
        $data['materials_offer']= $this->super_model->select_column_custom_where('joi_items', 'materials_offer', "joi_id='$joi_id' ORDER BY materials_offer DESC LIMIT 1");
        $data['materials_qty']= $this->super_model->select_column_custom_where('joi_items', 'materials_qty', "joi_id='$joi_id' ORDER BY materials_offer DESC LIMIT 1");
        $data['currency'] = $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
            $jo_no=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            /*if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }*/
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
            $data['tc'] = $this->super_model->select_row_where("jor_notes", "jor_id", $ppr->jor_id);
        }
        $data['joi_tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['dr'] = $this->super_model->select_row_where("joi_dr", "joi_id", $joi_id);
        $data['rfd'] = $this->super_model->select_custom_where("joi_rfd", "joi_id='$joi_id'");
        $this->load->view('jod/jo_direct_saved',$data);
        $this->load->view('template/footer');
    }

    public function jo_direct_draft(){
        $this->load->view('template/header');
        $joi_id = $this->uri->segment(3);
        $jor_id = $this->uri->segment(4);
        $data['joi_id'] = $joi_id;
        $data['currency2'] = $this->currency_list();
        $vendor_id = $this->super_model->select_column_where('joi_head', 'vendor_id', 'joi_id', $joi_id);
        $data['currency1']='';
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
                'general_desc'=>$h->general_desc,
                'vendor_id'=>$h->vendor_id,
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
            $data['discount_lab']=$h->discount_labor;
            $data['discount_mat']=$h->discount_material;
            $data['grand_total']=$h->grand_total;
            $data['vat_percent']=$h->vat_percent;
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['conforme']= $h->conforme;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['joi_no']=$h->joi_no;
            $data['notes']=$h->notes;
            $data['vat_in_ex']=$h->vat_in_ex;
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
        $data['items_materials'] = $this->super_model->select_custom_where('joi_items', "joi_id='$joi_id' GROUP BY materials_offer");
        $data['materials_offer']= $this->super_model->select_column_custom_where('joi_items', 'materials_offer', "joi_id='$joi_id' ORDER BY materials_offer DESC LIMIT 1");
        $data['materials_qty']= $this->super_model->select_column_custom_where('joi_items', 'materials_qty', "joi_id='$joi_id' ORDER BY materials_offer DESC LIMIT 1");
        $data['currency'] = $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
            $jo_no=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            /*if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }*/
            $data['allpr'][]= array(
                'jo_no'=>$jo_no,
                'purpose'=>$ppr->purpose,
                'requestor'=>$ppr->requestor
            );
            $data['notes'] = $this->super_model->select_row_where("jor_notes", "jor_id", $ppr->jor_id);
        }
        $data['tc'] = $this->super_model->select_row_where("joi_tc", "joi_id", $joi_id);
        $data['dr'] = $this->super_model->select_row_where("joi_dr", "joi_id", $joi_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('jod/jo_direct_draft',$data);
        $this->load->view('template/footer');
    }

    public function save_jod_draft(){
        $submit = $this->input->post('submit');
        $joi_id = $this->input->post('joi_id');
        $count_item = $this->input->post('count_item');
        $count_notes = $this->input->post('count_notes');
        $data['currency1']='';

        $a=1;
        for($x=1; $x<$count_item;$x++){
            $qty=$this->input->post('quantity'.$x);
            $uom=$this->input->post('uom'.$x);
            $joi_items_id = $this->input->post('joi_items_id'.$x);        
            if($qty!=0){
                $price = str_replace(",", "", $this->input->post('price'.$x));
                $amount = str_replace(",", "", $this->input->post('tprice'.$x));
                $materials_price = str_replace(",", "", $this->input->post('materials_price'));
                $materials_tprice = str_replace(",", "", $this->input->post('materials_tprice'));
                $offer = $this->input->post('offer'.$x);
                $currency = $this->input->post('currency'.$x);
                $data=array(
                 
                    'delivered_quantity'=>$qty,
                    'offer'=>$offer,
                    'uom'=>$uom,
                    'unit_price'=>$price,
                    'currency'=>$currency,
                    'amount'=>$amount,
                    'item_no'=>$a,
                    'materials_unitprice'=>$materials_price,
                    'materials_amount'=>$materials_tprice,
                    'materials_offer'=>$this->input->post('materials_offer'),
                    'materials_qty'=>$this->input->post('materials_qty'),
                    'materials_unit'=>$this->input->post('materials_uom'),
                    'materials_currency'=>$this->input->post('materials_currency'),
                );
                $data_dr=array(
                    'delivered_quantity'=>$qty,
                    'uom'=>$uom,
                    'unit_price'=>$price,
                    'currency'=>$currency,
                    'amount'=>$amount,
                    'item_no'=>$a,
                    'materials_unitprice'=>$materials_price,
                    'materials_amount'=>$materials_tprice,
                    'materials_offer'=>$this->input->post('materials_offer'),
                    'materials_qty'=>$this->input->post('materials_qty'),
                    'materials_unit'=>$this->input->post('materials_uom'),
                    'materials_currency'=>$this->input->post('materials_currency'),
                );

                    $this->super_model->update_where("joi_items", $data, "joi_items_id", $joi_items_id);
                    $this->super_model->update_where("joi_dr_items", $data_dr, "joi_items_id", $joi_items_id);
             $a++;
            } else {
                
                $this->super_model->delete_where("joi_items", "joi_items_id", $joi_items_id);
                $this->super_model->delete_where("joi_dr_items", "joi_items_id", $joi_items_id);
            }
            
        }
        /*$materials_price = str_replace(",", "", $this->input->post('materials_price'));
        $materials_tprice = str_replace(",", "", $this->input->post('materials_tprice'));
        $materials_joitemid = $this->input->post('joi_items_id');
        $data_item=array(
            'materials_unitprice'=>$materials_price,
            'materials_amount'=>$materials_tprice,
            'materials_offer'=>$this->input->post('materials_offer'),
            'materials_qty'=>$this->input->post('materials_qty'),
            'materials_unit'=>$this->input->post('materials_uom'),
            'materials_currency'=>$this->input->post('materials_currency'),
        );

        $this->super_model->update_where("joi_items", $data_item, "joi_items_id", $materials_joitemid);
        $this->super_model->update_where("joi_dr_items", $data_item, "joi_items_id", $materials_joitemid);*/

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
                'discount_labor'=>$this->input->post('discount_lab'),
                'discount_material'=>$this->input->post('discount_mat'),
                'grand_total'=>$this->input->post('net'),
                'conforme'=>$this->input->post('conforme'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked_by'),
                'recommended_by'=>$this->input->post('recommended_by'),
                'approved_by'=>$this->input->post('approved_by'),
                'verified_by'=>$this->input->post('verified_by'),
                'vat_in_ex'=>$this->input->post('vat_in_ex'),
                'saved'=>1,
                'draft'=>0,
                'revised'=>0
            ); 
             if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'jod/jo_direct_saved/'.$joi_id);
             }

        } else if($submit=='Save as Draft'){
             $head = array(
                'shipping'=>$this->input->post('shipping'),
                'discount'=>$this->input->post('less_amount'),
                'packing_fee'=>$this->input->post('packing'),
                'vat'=>$this->input->post('vat_amount'),
                'discount_labor'=>$this->input->post('discount_lab'),
                'discount_material'=>$this->input->post('discount_mat'),
                'grand_total'=>$this->input->post('net'),
                'conforme'=>$this->input->post('conforme'),
                'vat_percent'=>$this->input->post('vat_percent'),
                'checked_by'=>$this->input->post('checked_by'),
                'recommended_by'=>$this->input->post('recommended_by'),
                'approved_by'=>$this->input->post('approved_by'),
                'verified_by'=>$this->input->post('verified_by'),
                'vat_in_ex'=>$this->input->post('vat_in_ex'),
                'saved'=>0,
                'draft'=>1,
                'revised'=>0
            ); 
             if($this->super_model->update_where("joi_head", $head, "joi_id", $joi_id)){
                redirect(base_url().'jod/jo_direct_draft/'.$joi_id);
             }
        }
    }

    public function jo_direct_rev(){

        $data['url']=$this->uri->segment(2); 
        $joi_id=$this->uri->segment(3); 
        $data['currency1']='';
        $data['supplier']=$this->super_model->select_all_order_by("vendor_head", "vendor_name", "ASC");
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        foreach($this->super_model->select_row_where("joi_head", "joi_id",$joi_id) AS $head){
            $data['approved_by']=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);
            $data['prepared_by']=$this->super_model->select_column_where('users','fullname','user_id', $head->user_id);
            $data['revised']=$head->revised;
        }

        foreach($this->super_model->select_row_where("joi_head_temp", "joi_id",$joi_id) AS $head_temp){
            $data['prepared_by_temp']=$this->super_model->select_column_where('users','fullname','user_id', $head_temp->user_id);
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
            $data['joi_type']=$h->joi_type;
            $data['cenjo_no']= $h->cenpri_jo_no;
            $data['joi_no']= $h->joi_no;
            $data['project_title']= $h->project_title;
            $data['date_prepared']= $h->date_prepared;
            $data['date_needed']= $h->date_needed;
            $data['start_of_work']= $h->start_of_work;
            $data['work_completion']= $h->completion_date;
            $data['general_desc']= $h->general_desc;
            $data['discount']= $h->discount;
            $data['vat_percent']= $h->vat_percent;
            $data['vat_amount']= $h->vat;
            $data['discount_lab']= $h->discount_labor;
            $data['discount_mat']= $h->discount_material;
            $data['grand_total']= $h->grand_total;
            $data['conforme']= $h->conforme;
            $data['verified_by']= $h->verified_by;
            $data['cancelled']= $h->cancelled;
            $data['vat_in_ex']=$h->vat_in_ex;
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['approved_id'] = $h->approved_by;

            $approved_id_temp = $this->super_model->select_column_where('joi_head_temp', 'approved_by', 'joi_id', $h->joi_id);
            $data['approved_temp'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $approved_id_temp);

            $recommended_id_temp = $this->super_model->select_column_where('joi_head_temp', 'recommended_by', 'joi_id', $h->joi_id);
            $data['recommended_temp'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $recommended_id_temp);

            $checked_id_temp = $this->super_model->select_column_where('joi_head_temp', 'checked_by', 'joi_id', $h->joi_id);
            $data['checked_temp'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $checked_id_temp);

            $verified_id_temp = $this->super_model->select_column_where('joi_head_temp', 'verified_by', 'joi_id', $h->joi_id);
            $data['verified_by_temp'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $verified_id_temp);

            $data['recommended_id'] = $h->recommended_by;
            $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
            $data['checked_id'] = $h->checked_by;
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['verified_id'] = $h->verified_by;
            $data['verified_by'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->verified_by);
        }

        $data['items'] = $this->super_model->select_row_where('joi_items', 'joi_id', $joi_id);
        $data['items_materials'] = $this->super_model->select_custom_where('joi_items', "joi_id='$joi_id' GROUP BY materials_offer");
        $data['materials_offer']= $this->super_model->select_column_custom_where('joi_items', 'materials_offer', "joi_id='$joi_id' ORDER BY materials_offer DESC LIMIT 1");
        $data['materials_qty']= $this->super_model->select_column_custom_where('joi_items', 'materials_qty', "joi_id='$joi_id' ORDER BY materials_offer DESC LIMIT 1");
        $data['currency2'] = $this->currency_list();
        $data['currency']= $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
        $data['items_temp'] = $this->super_model->select_row_where('joi_items_temp', 'joi_id', $joi_id);
        $data['items_materials_temp'] = $this->super_model->select_custom_where('joi_items_temp', "joi_id='$joi_id' GROUP BY materials_offer");
        $data['materials_offer_temp']= $this->super_model->select_column_custom_where('joi_items_temp', 'materials_offer', "joi_id='$joi_id' ORDER BY materials_offer DESC LIMIT 1");
        $data['materials_qty_temp']= $this->super_model->select_column_custom_where('joi_items_temp', 'materials_qty', "joi_id='$joi_id' ORDER BY materials_offer DESC LIMIT 1");
      
        foreach($this->super_model->select_row_where("joi_jor", "joi_id", $joi_id) AS $ppr){
            $jo_no=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            /*if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }*/
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
        $data['general_desc_temp'] = $this->super_model->select_column_where('joi_head_temp', 'general_desc', 'joi_id', $joi_id);
        $data['cenjo_no_temp'] = $this->super_model->select_column_where('joi_head_temp', 'cenpri_jo_no', 'joi_id', $joi_id);
        $data['project_title_temp'] = $this->super_model->select_column_where('joi_head_temp', 'project_title', 'joi_id', $joi_id);
        $data['shipping_temp'] = $this->super_model->select_column_where('joi_head_temp', 'shipping', 'joi_id', $joi_id);
        $data['discount_temp'] = $this->super_model->select_column_where('joi_head_temp', 'discount', 'joi_id', $joi_id);
        $data['packing_temp'] = $this->super_model->select_column_where('joi_head_temp', 'packing_fee', 'joi_id', $joi_id);
        $data['vat_temp'] = $this->super_model->select_column_where('joi_head_temp', 'vat', 'joi_id', $joi_id);
        $data['vat_in_ex_temp'] = $this->super_model->select_column_where('joi_head_temp', 'vat_in_ex', 'joi_id', $joi_id);
        $data['conforme_temp'] = $this->super_model->select_column_where('joi_head_temp', 'conforme', 'joi_id', $joi_id);
        $data['vat_percent_temp'] = $this->super_model->select_column_where('joi_head_temp', 'vat_percent', 'joi_id', $joi_id);
        $data['discount_lab_temp']= $this->super_model->select_column_where('joi_head_temp', 'discount_labor', 'joi_id', $joi_id);
        $data['discount_mat_temp']= $this->super_model->select_column_where('joi_head_temp', 'discount_material', 'joi_id', $joi_id);
        $data['grand_total_temp']= $this->super_model->select_column_where('joi_head_temp', 'grand_total', 'joi_id', $joi_id);

        /*$datarfd = array(
            'saved'=>0
        );
        $this->super_model-> update_where("rfd", $datarfd, "joi_id", $joi_id);*/

        $this->load->view('template/header');        
        $this->load->view('jod/jo_direct_rev', $data);
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
        $data['currency1']='';
        foreach($this->super_model->select_row_where("joi_head","joi_id",$joi_id) AS $johead){
            $data_details = array(
                "joi_id"=>$johead->joi_id,
                'joi_date'=>$timestamp,
                "vendor_id"=>$this->input->post('vendor'),
                "date_needed"=>$this->input->post('date_needed'),
                "completion_date"=>$this->input->post('work_completion'),
                "general_desc"=>$this->input->post('general_desc'),
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
                //"user_id"=>$johead->user_id,
                "user_id"=>$_SESSION['user_id'],
                "vat_percent"=>$this->input->post('vat_percent'),
                "vat"=>$this->input->post('vat_amount'),
                "discount_labor"=>$this->input->post('discount_lab'),
                "discount_material"=>$this->input->post('discount_mat'),
                "grand_total"=>$this->input->post('net'),
                // "discount"=>$this->input->post('less_amount'),
                'vat_in_ex'=>$this->input->post('vat_in_ex'),
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
                $materials_price = str_replace(",", "", $this->input->post('materials_price'));
                $materials_tprice = str_replace(",", "", $this->input->post('materials_tprice'));
                $currency = $this->input->post('currency'.$x);
                $joi_items_id = $jodets->joi_items_id;
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
                    "currency"=>$currency,
                    "amount"=>$amount,
                    "offer"=>$this->input->post('scope_of_work'.$x),
                    "materials_offer"=>$this->input->post('materials_offer'),
                    "materials_qty"=>$this->input->post('materials_qty'),
                    "materials_unitprice"=>$materials_price,
                    "materials_amount"=>$materials_tprice,
                    "materials_unit"=>$this->input->post('materials_uom'),
                    "materials_currency"=>$this->input->post('materials_currency'),
                );
                $this->super_model->insert_into("joi_items_temp", $data_details);
            }
                
            $x++;
        }
        /*$materials_price = str_replace(",", "", $this->input->post('materials_price'));
        $materials_tprice = str_replace(",", "", $this->input->post('materials_tprice'));
        $data_items=array(
            "materials_offer"=>$this->input->post('materials_offer'),
            "materials_qty"=>$this->input->post('materials_qty'),
            "materials_unitprice"=>$materials_price,
            "materials_amount"=>$materials_tprice,
            "materials_unit"=>$this->input->post('materials_uom'),
            "materials_currency"=>$this->input->post('materials_currency'),
        );
        $this->super_model->update_where("joi_items_temp", $data_items, "joi_items_id", $joi_items_id);*/

        // $y=1;
        // foreach($this->super_model->select_row_where("joi_tc","joi_id",$joi_id) AS $jotc){
        //     $data_tci = array(
        //         "joi_tc_id"=>$jotc->joi_tc_id,
        //         "joi_id"=>$joi_id,
        //         "tc_desc"=>$jotc->tc_desc,
        //     );
        //     $this->super_model->insert_into("joi_tc_temp", $data_tci);
        //     $y++;
        // }

        // $count_notes=$this->input->post('count_notes');
        // for($z=1; $z<$count_notes;$z++){
        //     $joi_tc_id = $this->input->post('joi_tc_id'.$z);
        //     $data_notes=array(
        //         "notes"=>$this->input->post('joi_notes'.$z),
        //     );
        //     $this->super_model->update_where("joi_tc_temp", $data_notes, "joi_tc_id", $joi_tc_id);
        // }

        $data_head = array(
            'revised'=>1
        );

        if($this->super_model->update_where("joi_head", $data_head, "joi_id", $joi_id)){
            redirect(base_url().'jod/jo_direct_rev/'.$joi_id, 'refresh');
        }
    }

    public function approve_revision(){
        $joi_id = $this->input->post('joi_id');
        $max_revision = $this->super_model->get_max_where("joi_head", "revision_no","joi_id = '$joi_id'");
        $revision_no = $max_revision+1;

        $year=date('Y');
       $rows_dr = $this->super_model->count_custom_where("joi_dr","joi_dr_date LIKE '%$year%'");
        if($rows_dr==0){
            $dr_no=1000;
            $series=1000;
        } else {
            $max = $this->super_model->get_max_where("joi_dr", "joi_dr_no","joi_dr_date LIKE '%$year%'");
            $dr_no = $max+1;
            $series = $max+1;
        }

/*        $rows_series = $this->super_model->count_custom_where("joi_series");
        if($rows_series==0){
            $series=1000;
        } else {
            $max = $this->super_model->get_max("joi_series", "series");
            $series = $max+1;
        }*/

        // $data_series = array(
        //     'series'=>$series
        // );
        // $this->super_model->insert_into("joi_series", $data_series);

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
                'revision_no'=>$dritems->revision_no,
                'materials_offer'=>$dritems->materials_offer,
                'materials_qty'=>$dritems->materials_qty,
                'materials_unitprice'=>$dritems->materials_unitprice,
                'materials_amount'=>$dritems->materials_amount,
                'materials_unit'=>$dritems->materials_unit,
                'materials_currency'=>$dritems->materials_currency,
                'materials_received'=>$dritems->materials_received,
            );
            $this->super_model->insert_into("joi_dr_items_revised", $data_dritems);
        }
        
        $data_drs =array(
            'revision_no'=>$revision_no
        );
        $this->super_model->update_where("joi_dr", $data_drs, "joi_id", $joi_id);

        $jor_id = $this->super_model->select_column_where("joi_items","jor_id","joi_id",$joi_id);
        $jo_no = $this->super_model->select_column_where("jor_head","jo_no","jor_id",$jor_id);
        /*if($jo!=''){
            $jo_no=$jo;
        }else{
            $jo_no=$this->super_model->select_column_where("jor_head","user_jo_no","jor_id",$jor_id);
        }*/
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
                "general_desc"=>$head->general_desc,
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
                "discount_labor"=>$head->discount_labor,
                "discount_material"=>$head->discount_material,
                "grand_total"=>$head->grand_total,
                "vat_percent"=>$head->vat_percent,
                "vat_in_ex"=>$head->vat_in_ex,
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
                        "discount_labor"=>$headt->discount_labor,
                        "discount_material"=>$headt->discount_material,
                        "grand_total"=>$headt->grand_total,
                        "vat_percent"=>$headt->vat_percent,
                        "vat_in_ex"=>$headt->vat_in_ex,
                        "discount"=>$headt->discount,
                        "user_id"=>$headt->user_id,
                        "date_needed"=>$headt->date_needed,
                        "completion_date"=>$headt->completion_date,
                        "general_desc"=>$headt->general_desc,
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
                "currency"=>$poitems->currency,
                "item_no"=>$poitems->item_no,
                "revision_no"=>$poitems->revision_no,
                "materials_offer"=>$poitems->materials_offer,
                "materials_qty"=>$poitems->materials_qty,
                "materials_unitprice"=>$poitems->materials_unitprice,
                "materials_amount"=>$poitems->materials_amount,
                'materials_unit'=>$poitems->materials_unit,
                'materials_currency'=>$poitems->materials_currency,
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
            $this->super_model->insert_into("joi_tc_revised", $data_potc);
            // if($this->super_model->insert_into("joi_tc_revised", $data_potc)){
            //     $data_tcn =array(
            //         'revision_no'=>$revision_no
            //     );

            //     $this->super_model->update_where("joi_tc", $data_tcn, "joi_id", $joi_id);
            // }
        }

        foreach($this->super_model->select_row_where("joi_tc_temp","joi_id",$joi_id) AS $potcr){
            $data_rev = array(
                // "joi_tc_id"=>$potcr->joi_tc_id,
                "joi_id"=>$popr->joi_id,
                "tc_desc"=>$potcr->tc_desc,
                "notes"=>$potcr->notes,
                "revision_no"=>$potcr->revision_no,
            );
            $this->super_model->insert_into("joi_tc", $data_rev);
            // $this->super_model->update_where("joi_tc", $data_rev, "joi_tc_id", $potcr->joi_tc_id);
        }

        foreach($this->super_model->custom_query("SELECT joi_items_id FROM joi_items WHERE joi_items_id NOT IN (SELECT joi_items_id FROM joi_items_temp WHERE joi_id='$joi_id')  AND joi_id = '$joi_id'") AS $omit){
           
            $delete_item = $this->super_model->delete_custom_where("joi_items", "joi_items_id='$omit->joi_items_id'");

            $delete_dr = $this->super_model->delete_custom_where("joi_dr_items", "joi_items_id='$omit->joi_items_id'");
        }


        foreach($this->super_model->select_row_where("joi_items_temp","joi_id",$joi_id) AS $poitems){
            $oldqty = $this->super_model->select_column_where('joi_items', 'quantity', 'joi_items_id',  $poitems->joi_items_id);
            $oldmaterialsqty = $this->super_model->select_column_where('joi_items', 'materials_received', 'joi_items_id',  $poitems->joi_items_id);
            if($oldqty==0){
                $nqty=0;
            } else {
                $nqty = $oldqty-$poitems->quantity;
            }

            if($oldmaterialsqty==0){
                $nmatqty=0;
            } else {
                $nmatqty = $oldmaterialsqty-$poitems->materials_received;
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
                "currency"=>$poitems->currency,
                "item_no"=>$poitems->item_no,
                "revision_no"=>$revision_no,
                "materials_offer"=>$poitems->materials_offer,
                "materials_qty"=>$poitems->materials_qty,
                "materials_received"=>$poitems->materials_received,
                "materials_unitprice"=>$poitems->materials_unitprice,
                "materials_amount"=>$poitems->materials_amount,
                'materials_unit'=>$poitems->materials_unit,
                'materials_currency'=>$poitems->materials_currency,
                'materials_received'=>$nmatqty,
            );
             $this->super_model->update_where("joi_items", $data_items, "joi_items_id", $poitems->joi_items_id);

            $data_dr_items = array(
                'delivered_quantity'=>$poitems->delivered_quantity,
                'quantity'=>0,
                'materials_received'=>0,
                "uom"=>$poitems->uom,
                'unit_price'=>$poitems->unit_price,
                'amount'=>$poitems->amount,
                "currency"=>$poitems->currency,
                'offer'=>$poitems->offer,
                'materials_offer'=>$poitems->materials_offer,
                'materials_qty'=>$poitems->materials_qty,
                'materials_unitprice'=>$poitems->materials_unitprice,
                'materials_amount'=>$poitems->materials_amount,
                'materials_unit'=>$poitems->materials_unit,
                'materials_currency'=>$poitems->materials_currency,
            );

            $this->super_model->update_where("joi_dr_items", $data_dr_items, "joi_items_id", $poitems->joi_items_id);

            $old_qty = $this->super_model->select_column_where('joi_items', 'delivered_quantity', 'jor_aoq_offer_id',  $poitems->jor_aoq_offer_id);
            $old_mat_qty = $this->super_model->select_column_where('joi_items', 'materials_qty', 'jor_aoq_offer_id',  $poitems->jor_aoq_offer_id);

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

            if($old_mat_qty!=$poitems->materials_qty){
           
                $difference_mat = $old_mat_qty - $poitems->materials_received;

                $old_mat_balance = $this->super_model->select_column_where('jor_aoq_offers', 'materials_balance', 'jor_aoq_offer_id',  $poitems->jor_aoq_offer_id);

                $balance_mat = $old_mat_balance+$difference_mat;

                $data_mat_aoq = array(
                    'materials_balance'=>$balance_mat
                );
                $this->super_model->update_where("jor_aoq_offers", $data_mat_aoq, "jor_aoq_offer_id", $poitems->jor_aoq_offer_id);
            } else {
             
                $balance_mat = $this->super_model->select_column_where('jor_aoq_offers', 'materials_balance', 'jor_aoq_offer_id',  $poitems->jor_aoq_offer_id);
                $data_mat_aoq = array(
                    'materials_balance'=>$balance_mat
                );
                $this->super_model->update_where("jor_aoq_offers", $data_mat_aoq, "jor_aoq_offer_id", $poitems->jor_aoq_offer_id);
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
            redirect(base_url().'jod/jo_direct_saved/'.$joi_id, 'refresh');
        }
    }

    public function add_tc_temp(){
        $joi_id = $this->input->post('joi_id');

        $rows_head = $this->super_model->count_rows("joi_tc_temp");
        if($rows_head==0){
            $joi_tc_id=1;
        } else {
            $max = $this->super_model->get_max("joi_tc_temp", "joi_tc_id");
            $joi_tc_id = $max+1;
        }
        $data = array(
            'joi_tc_id'=>$joi_tc_id,
            'joi_id'=>$this->input->post('joi_id'),
            'tc_desc'=>$this->input->post('terms'),
        );
        if($this->super_model->insert_into("joi_tc_temp", $data)){
            redirect(base_url().'jod/jo_direct_rev/'.$joi_id, 'refresh');
        }
    }

    public function cancel_joi(){
        $joi_id=$this->input->post('joi_id');
        $reason=$this->input->post('reason');
        $create = date('Y-m-d H:i:s');

        $data = array(
            'cancelled'=>1,
            'cancelled_by'=>$_SESSION['user_id'],
            'cancel_reason'=>$reason,
            'cancelled_date'=>$create
        );

        if($this->super_model->update_where("joi_head", $data, "joi_id", $joi_id)){
            redirect(base_url().'joi/joi_list', 'refresh');
        }
    }

        public function jod_rfd(){   
        $joi_id = $this->uri->segment(3);
        $data['joi_id'] = $joi_id;   
        $data['joi_date']=$this->super_model->select_column_where('joi_head', 'joi_date', 'joi_id', $joi_id);
        $data['revised']=$this->super_model->select_column_where('joi_head', 'revised', 'joi_id', $joi_id);
        $data['revision_no']=$this->super_model->select_column_where('joi_head', 'revision_no', 'joi_id', $joi_id);
        $data['saved']= $this->super_model->select_column_where("joi_rfd", "saved", "joi_rfd_id", $joi_id);
        $data['rows_rfd'] = $this->super_model->select_count("joi_rfd","joi_id",$joi_id);
        $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
        $data['joi_no']= $this->super_model->select_column_where("joi_head", "joi_no", "joi_id", $joi_id);
        $data['cenpri_jo_no']= $this->super_model->select_column_where("joi_head", "cenpri_jo_no", "joi_id", $joi_id);
        $data['general_desc']= $this->super_model->select_column_where("joi_head", "general_desc", "joi_id", $joi_id);
        $data['joi_type']= $this->super_model->select_column_where("joi_head", "joi_type", "joi_id", $joi_id);
        $data['shipping']= $this->super_model->select_column_where("joi_head", "shipping", "joi_id", $joi_id);
        $data['discount']= $this->super_model->select_column_where("joi_head", "discount", "joi_id", $joi_id);
        $data['packing']= $this->super_model->select_column_where("joi_head", "packing_fee", "joi_id", $joi_id);
        $data['vatt']= $this->super_model->select_column_where("joi_head", "vat", "joi_id", $joi_id);
        $data['sum_amount']= $this->super_model->select_sum("joi_rfd", "payment_amount", "joi_id", $joi_id);
        $data['vat_percent']= $this->super_model->select_column_where("joi_head", "vat_percent", "joi_id", $joi_id);
        $data['total_cost']= $this->super_model->select_column_where("joi_head", "total_cost", "joi_id", $joi_id);
        $data['grand_total']= $this->super_model->select_column_where("joi_head", "grand_total", "joi_id", $joi_id);
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['tin']= $this->super_model->select_column_where("vendor_head", "tin", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);
        $data['dr_no']= $this->super_model->select_column_where("joi_dr", "joi_dr_no", "joi_id", $joi_id);
        $data['cancelled']=$this->super_model->select_column_where("joi_head", "cancelled", "joi_id", $joi_id);
        foreach($this->super_model->select_row_where('joi_items', 'joi_id', $joi_id) AS $items){
            $total = $items->unit_price*$items->delivered_quantity;
            $materials_total = $items->materials_unitprice*$items->materials_qty;
           if(!empty($items->offer)){
                $offer = $items->offer;
            } else {
                $offer = $this->super_model->select_column_where("joi_items", "offer", "joi_items_id", $items->joi_items_id);
            }
            $data['materials_offer']= $items->materials_offer;
            $data['materials_qty']= $items->materials_qty;
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'offer'=>$offer,
                'quantity'=>$items->delivered_quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
                'uom'=>$items->uom,
                'materials_offer'=>$items->materials_offer,
                'materials_qty'=>$items->materials_qty,
                'materials_unitprice'=>$items->materials_unitprice,
                'materials_unit'=>$items->materials_unit,
                'materials_amount'=>$materials_total,
            );

            $data['currency'] = $items->currency;
        }

        //MATERIALS
        foreach($this->super_model->select_custom_where('joi_items', "joi_id='$joi_id' GROUP BY materials_offer") AS $items){
            $total = $items->unit_price*$items->delivered_quantity;
            $materials_total = $items->materials_unitprice*$items->materials_qty;
           if(!empty($items->offer)){
                $offer = $items->offer;
            } else {
                $offer = $this->super_model->select_column_where("joi_items", "offer", "joi_items_id", $items->joi_items_id);
            }
            $data['materials_offer']= $items->materials_offer;
            $data['materials_qty']= $items->materials_qty;
            $data['items_materials'][]= array(
                'item_no'=>$items->item_no,
                'offer'=>$offer,
                'quantity'=>$items->delivered_quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
                'uom'=>$items->uom,
                'materials_offer'=>$items->materials_offer,
                'materials_qty'=>$items->materials_qty,
                'materials_unitprice'=>$items->materials_unitprice,
                'materials_unit'=>$items->materials_unit,
                'materials_amount'=>$materials_total,
            );

            $data['currency'] = $items->currency;
        }

          foreach($this->super_model->select_row_where('joi_jor', 'joi_jor_id', $joi_id) AS $jor){
             $itemno='';
            foreach($this->super_model->select_custom_where('joi_items', "jor_id='$jor->jor_id' AND joi_id = '$joi_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
                $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id);
                /*if($jo_no!=''){
                    $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id);
                }else{
                    $jor_no=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $jor->jor_id);
                }*/

            $item_no = substr($itemno, 0, -2);
            $data['jor_joi'][]=array(
                'joi_jor_id'=>$jor->joi_jor_id,
                'jor_no'=>$jor_no,
                'enduse'=>$jor->enduse,
                'purpose'=>$jor->purpose,
                'requestor'=>$jor->requestor,
                'item_no'=>$item_no
            );
        }

        $data['rfd_max'] = $this->super_model->get_max_where("joi_rfd_payment", "rfd_no","joi_id = '$joi_id'");
        $rfd_max = $this->super_model->get_max_where("joi_rfd_payment", "rfd_no","joi_id = '$joi_id'");
        $grand_total = $this->super_model->select_column_where("joi_head", "grand_total", "joi_id", $joi_id);
        // if($rfd_max != 0){
        //     $data['rfd_payment'] = $this->super_model->custom_query("SELECT * FROM joi_rfd_payment WHERE joi_id = '$joi_id' ORDER BY rfd_date ASC");
        //     $data['sum_rfd_payment'] = $this->super_model->select_sum_where("joi_rfd_payment", "payment_amount", "rfd_no = '$rfd_max' AND joi_id = '$joi_id'");
        //     $sum_rfd_payment = $this->super_model->select_sum_where("joi_rfd_payment", "payment_amount", "rfd_no = '$rfd_max' AND joi_id = '$joi_id'");
        //     $data['remaining_balance'] = $grand_total - $sum_rfd_payment;
        // }else{
        //     $data['rfd_payment'] = '';
        //     $data['sum_rfd_payment'] = '0';
        //     $data['remaining_balance'] = $grand_total;
        // }

        $sum_labor = $this->super_model->select_sum_where("joi_items", "amount", "joi_id = '$joi_id'");
        $sum_materials = $this->super_model->select_sum_where("joi_items", "materials_amount", "joi_id = '$joi_id'");
        $data['net_total'] = $sum_labor +  $sum_materials;
        $grand_total = $this->super_model->select_column_where("joi_head", "grand_total", "joi_id", $joi_id);
        $sum_amount= $this->super_model->select_sum("joi_rfd", "payment_amount", "joi_id", $joi_id);
        // $data['old_remaining_balance'] = ($sum_labor +  $sum_materials) - $sum_amount;

        $data['rfd_payment'] = $this->super_model->custom_query("SELECT * FROM joi_rfd_payment WHERE joi_id = '$joi_id' ORDER BY rfd_date ASC");
        $data['sum_rfd_payment'] = $this->super_model->select_sum_where("joi_rfd_payment", "payment_amount", "joi_id = '$joi_id'");
        $sum_rfd_payment = $this->super_model->select_sum_where("joi_rfd_payment", "payment_amount", "joi_id = '$joi_id'");
        $data['new_remaining_balance'] = $grand_total - $sum_rfd_payment;


        // $data['count_old_payment'] = $this->super_model->count_custom_where("joi_rfd","joi_id = '$joi_id' AND payment_amount !='0.00'");
        $data['payment'] = $this->super_model->custom_query("SELECT * FROM joi_rfd WHERE joi_id = '$joi_id' AND payment_amount !='0.00'");
        
        foreach($this->super_model->select_row_where('joi_rfd', 'joi_id', $joi_id) AS $r){

            // $data['all_payment'] = $this->super_model->select_sum_where("joi_rfd", "payment_amount", "joi_id = '$joi_id' AND rfd_date <= '$r->rfd_date'");
            // $all_payment = $this->super_model->select_sum_where("joi_rfd", "payment_amount", "joi_id = '$joi_id' AND rfd_date <= '$r->rfd_date'");

            
            $data['company']=$r->company;
            $data['pay_to']=$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $r->pay_to);
            $data['check_name']=$r->check_name;
            $data['payment_amount']=$r->payment_amount;
            $data['apv_no']=$r->apv_no;
            $data['rfd_date']=$r->rfd_date;
            $data['due_date']=$r->due_date;
            $data['check_due']=$r->check_due;
            $data['cash_check']=$r->cash_check;
            $data['bank_no']=$r->bank_no;
            $data['notes']=$r->notes;
            
            $data['checked']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->checked_by);
            $data['endorsed']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->endorsed_by);
            $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->noted_by);
            $data['received']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->received_by);
            $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->approved_by);
        }
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");

        $this->load->view('template/header');        
        $this->load->view('jod/jod_rfd',$data);
    }

     public function jod_rfd_saved(){   
        $joi_id = $this->uri->segment(3);
        $joi_rfd_id = $this->uri->segment(4);
        $rfd_date = str_replace('%20', ' ', $this->uri->segment(5));
        $data['joi_id'] = $joi_id;
        $data['joi_date']=$this->super_model->select_column_where('joi_head', 'joi_date', 'joi_id', $joi_id);
        $data['revised']=$this->super_model->select_column_where('joi_head', 'revised', 'joi_id', $joi_id);
        $data['revision_no']=$this->super_model->select_column_where('joi_head', 'revision_no', 'joi_id', $joi_id);
        $data['saved']= $this->super_model->select_column_where("joi_rfd", "saved", "joi_id", $joi_id);
        $data['rows_rfd'] = $this->super_model->select_count("joi_rfd","joi_id",$joi_id);
        $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
        $data['joi_no']= $this->super_model->select_column_where("joi_head", "joi_no", "joi_id", $joi_id);
        $data['cenpri_jo_no']= $this->super_model->select_column_where("joi_head", "cenpri_jo_no", "joi_id", $joi_id);
        $data['general_desc']= $this->super_model->select_column_where("joi_head", "general_desc", "joi_id", $joi_id);
        $data['joi_type']= $this->super_model->select_column_where("joi_head", "joi_type", "joi_id", $joi_id);
        $data['shipping']= $this->super_model->select_column_where("joi_head", "shipping", "joi_id", $joi_id);
        $data['discount']= $this->super_model->select_column_where("joi_head", "discount", "joi_id", $joi_id);
        $data['packing']= $this->super_model->select_column_where("joi_head", "packing_fee", "joi_id", $joi_id);
        $data['vatt']= $this->super_model->select_column_where("joi_head", "vat", "joi_id", $joi_id);
        // $data['sum_amount']= $this->super_model->select_sum("joi_rfd", "payment_amount", "joi_id", $joi_id);
        $data['sum_amount'] = $this->super_model->select_sum_where("joi_rfd", "payment_amount", "joi_id = '$joi_id' AND rfd_date <= '$rfd_date' AND payment_amount !='0.00'");
        $data['vat_percent']= $this->super_model->select_column_where("joi_head", "vat_percent", "joi_id", $joi_id);
        $data['total_cost']= $this->super_model->select_column_where("joi_head", "total_cost", "joi_id", $joi_id);
        $data['grand_total']= $this->super_model->select_column_where("joi_head", "grand_total", "joi_id", $joi_id);
        $data['vendor_id']= $vendor_id;
        $data['vendor']= $this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id);
        $data['tin']= $this->super_model->select_column_where("vendor_head", "tin", "vendor_id", $vendor_id);
        $data['ewt']= $this->super_model->select_column_where("vendor_head", "ewt", "vendor_id", $vendor_id);
        $data['vat']= $this->super_model->select_column_where("vendor_head", "vat", "vendor_id", $vendor_id);
        $data['dr_no']= $this->super_model->select_column_where("joi_dr", "joi_dr_no", "joi_id", $joi_id);
        $data['cancelled']=$this->super_model->select_column_where("joi_head", "cancelled", "joi_id", $joi_id);
        foreach($this->super_model->select_row_where('joi_items', 'joi_id', $joi_id) AS $items){
            $total = $items->unit_price*$items->delivered_quantity;
            $materials_total = $items->materials_unitprice*$items->materials_qty;
           if(!empty($items->offer)){
                $offer = $items->offer;
            } else {
                $offer = $this->super_model->select_column_where("joi_items", "offer", "joi_items_id", $items->joi_items_id);
            }
            $payment_amount = $this->super_model->select_sum("joi_rfd", "payment_amount", "joi_id", $items->joi_id);
            $payment_desc = $this->super_model->select_column_where("joi_rfd", "payment_desc", "joi_id", $items->joi_id);
            $data['materials_offer']= $items->materials_offer;
            $data['materials_qty']= $items->materials_qty;
            $data['items'][]= array(
                'item_no'=>$items->item_no,
                'offer'=>$offer,
                'quantity'=>$items->delivered_quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
                'uom'=>$items->uom,
                'payment_amount'=>$payment_amount,
                'payment_desc'=>$payment_desc,
                'materials_offer'=>$items->materials_offer,
                'materials_qty'=>$items->materials_qty,
                'materials_unitprice'=>$items->materials_unitprice,
                'materials_unit'=>$items->materials_unit,
                'materials_amount'=>$materials_total,
            );

            $data['currency'] = $items->currency;
        }
        //MATERIALS
        foreach($this->super_model->select_custom_where('joi_items', "joi_id='$joi_id' GROUP BY materials_offer") AS $items){
            $total = $items->unit_price*$items->delivered_quantity;
            $materials_total = $items->materials_unitprice*$items->materials_qty;
           if(!empty($items->offer)){
                $offer = $items->offer;
            } else {
                $offer = $this->super_model->select_column_where("joi_items", "offer", "joi_items_id", $items->joi_items_id);
            }
            $payment_amount = $this->super_model->select_sum("joi_rfd", "payment_amount", "joi_id", $items->joi_id);
            $payment_desc = $this->super_model->select_column_where("joi_rfd", "payment_desc", "joi_id", $items->joi_id);
            $data['materials_offer']= $items->materials_offer;
            $data['materials_qty']= $items->materials_qty;
            $data['items_materials'][]= array(
                'item_no'=>$items->item_no,
                'offer'=>$offer,
                'quantity'=>$items->delivered_quantity,
                'price'=>$items->unit_price,
                'total'=>$total,
                'uom'=>$items->uom,
                'payment_amount'=>$payment_amount,
                'payment_desc'=>$payment_desc,
                'materials_offer'=>$items->materials_offer,
                'materials_qty'=>$items->materials_qty,
                'materials_unitprice'=>$items->materials_unitprice,
                'materials_unit'=>$items->materials_unit,
                'materials_amount'=>$materials_total,
            );

            $data['currency'] = $items->currency;
        }



          foreach($this->super_model->select_row_where('joi_jor', 'joi_jor_id', $joi_id) AS $jor){
             $itemno='';
            foreach($this->super_model->select_custom_where('joi_items', "jor_id='$jor->jor_id' AND joi_id = '$joi_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
                $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id);
                /*if($jo_no!=''){
                    $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id);
                }else{
                    $jor_no=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $jor->jor_id);
                }*/

            $item_no = substr($itemno, 0, -2);
            $data['jor_joi'][]=array(
                'joi_jor_id'=>$jor->joi_jor_id,
                'jor_no'=>$jor_no,
                'enduse'=>$jor->enduse,
                'purpose'=>$jor->purpose,
                'requestor'=>$jor->requestor,
                'item_no'=>$item_no
            );
        }

        $sum_labor = $this->super_model->select_sum_where("joi_items", "amount", "joi_id = '$joi_id'");
        $sum_materials = $this->super_model->select_sum_where("joi_items", "materials_amount", "joi_id = '$joi_id'");
        $data['net_total'] = $sum_labor +  $sum_materials;
        // $data['all_payment'] = $this->super_model->select_sum_where("joi_rfd", "payment_amount", "joi_id = '$joi_id' AND rfd_date <= '$rfd_date''");
        // $all_payment = $this->super_model->select_sum_where("joi_rfd", "payment_amount", "joi_id = '$joi_id' AND rfd_date <= '$rfd_date''");
        // $data['old_remaining_balance'] = ($sum_labor +  $sum_materials) - $all_payment;

        $data['sub_total'] = $this->super_model->select_column_where("joi_rfd_payment", "sub_total", "joi_rfd_id", $joi_rfd_id);
        $data['payment_a'] = $this->super_model->select_column_where("joi_rfd_payment", "payment_amount", "joi_rfd_id", $joi_rfd_id);
        $data['ewt_amount'] = $this->super_model->select_column_where("joi_rfd_payment", "ewt_amount", "joi_rfd_id", $joi_rfd_id);
        $data['retention_amount'] = $this->super_model->select_column_where("joi_rfd_payment", "retention_amount", "joi_rfd_id", $joi_rfd_id);
        $data['rfd_payment'] = $this->super_model->custom_query("SELECT * FROM joi_rfd_payment WHERE rfd_date <= '$rfd_date' AND joi_id = '$joi_id' ORDER BY rfd_date ASC");
        $data['sum_rfd_payment'] = $this->super_model->select_sum_where("joi_rfd_payment", "payment_amount", "rfd_date <= '$rfd_date' AND joi_id = '$joi_id'");
        $sum_rfd_payment = $this->super_model->select_sum_where("joi_rfd_payment", "payment_amount", "joi_rfd_id = '$joi_rfd_id' AND joi_id = '$joi_id'");
        $grand_total = $this->super_model->select_column_where("joi_head", "grand_total", "joi_id", $joi_id);
        $data['new_remaining_balance'] = $grand_total - $sum_rfd_payment;

        // $data['count_old_payment'] = $this->super_model->count_custom_where("joi_rfd","joi_id = '$joi_id' AND payment_amount !='0.00'");
        $data['payment'] = $this->super_model->custom_query("SELECT * FROM joi_rfd WHERE joi_id = '$joi_id' AND rfd_date <= '$rfd_date' AND payment_amount !='0.00'");
        
        foreach($this->super_model->select_custom_where('joi_rfd', "joi_id='$joi_id' AND joi_rfd_id = '$joi_rfd_id'") AS $r){
/*            $data['payment'][]=array(
                'pdesc'=>$r->payment_desc,
                'pamount'=>$r->payment_amount,
            );*/
            $data['joi_rfd_id']=$r->joi_rfd_id;
            $data['company']=$r->company;
            $data['payment_desc']=$r->payment_desc;
            $data['payment_amount']=$r->payment_amount;
            $data['pay_to']=$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $r->pay_to);
            $data['check_name']=$r->check_name;
            $data['apv_no']=$r->apv_no;
            $data['rfd_date']=$r->rfd_date;
            $data['due_date']=$r->due_date;
            $data['check_due']=$r->check_due;
            $data['cash_check']=$r->cash_check;
            $data['bank_no']=$r->bank_no;
            $data['notes']=$r->notes;
            $data['checked']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->checked_by);
            $data['endorsed']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->endorsed_by);
            $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->noted_by);
            $data['received']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->received_by);
            $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $r->approved_by);
        }
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");

        $this->load->view('template/header');        
        $this->load->view('jod/jod_rfd_saved',$data);
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
        $this->load->view('jod/view_history',$data);
        $this->load->view('template/footer');
    }

    public function jo_direct_saved_r(){
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
                'general_desc'=>$h->general_desc,
                'date_prepared'=>$h->date_prepared,
                'start_of_work'=>$h->start_of_work,
                'date_needed'=>$h->date_needed,
                'cenpri_jo_no'=>$h->cenpri_jo_no,
                'general_desc'=>$h->general_desc,
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
            $data['grand_total']=$h->grand_total;
            $data['discount_lab']=$h->discount_labor;
            $data['discount_mat']=$h->discount_material;
            $data['saved']=$h->saved;
            $data['draft']=$h->draft;
            $data['conforme']= $h->conforme;
            $data['cancelled']=$h->cancelled;
            $data['revised']=$h->revised;
            $data['revision_no']=$h->revision_no;
            $data['joi_no']=$h->joi_no;
            $data['notes']=$h->notes;
            $data['vat_in_ex']=$h->vat_in_ex;
            $data['prepared']=$this->super_model->select_column_where('users', 'fullname', 'user_id', $h->user_id);
            $data['approved']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->approved_by);
            $data['recommended']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->recommended_by);
            $data['checked']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->checked_by);
            $data['verified_by']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $h->verified_by);
        }
        $data['items'] = $this->super_model->select_custom_where('joi_items_revised', "joi_id = '$joi_id' AND revision_no = '$revise_no'");
        $data['currency'] = $this->super_model->select_column_where('joi_items', 'currency', 'joi_id', $joi_id);
        foreach($this->super_model->select_custom_where("joi_jor_revised", "joi_id = '$joi_id' AND revision_no = '$revise_no'") AS $ppr){
            $jo_no=$this->super_model->select_column_where('jor_head', 'jo_no', 'jor_id', $ppr->jor_id);
            /*if($jo!=''){
                $jo_no=$jo;
            }else{
                $jo_no=$this->super_model->select_column_where('jor_head', 'user_jo_no', 'jor_id', $ppr->jor_id);
            }*/
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
        $this->load->view('jod/jo_direct_saved_r',$data);
        $this->load->view('template/footer');
    }


    public function save_jod_rfd(){
        $joi_id= $this->input->post('joi_id');
        //$joi_rfd_id= $this->input->post('joi_rfd_id');
        // $rows_rfd = $this->super_model->count_rows("joi_rfd");
        // if($rows_rfd==0){
        //     $joi_rfd_id = 1;
        // } else {
        //     $maxid = $this->super_model->get_max("joi_rfd", "joi_rfd_id");
        //     $joi_rfd_id = $maxid+1;
        // }
        $rfd_date=$this->input->post('rfd_date')." ".date("H:i:s");;
      /*  $dr_data = array(
            'dr_date'=>$this->input->post('rfd_date')
        );
        $this->super_model->update_where("po_dr", $dr_data, "joi_id", $joi_id);*/
        $data = array(
            'joi_id'=>$joi_id,
            'apv_no'=>$this->input->post('apv_no'),
            'rfd_date'=>$rfd_date,
            'due_date'=>$this->input->post('due_date'),
            'check_due'=>$this->input->post('check_due'),
            'company'=>$this->input->post('company'),
            'pay_to'=>$this->input->post('pay_to'),
            // 'payment_amount'=>$this->input->post('payment_amount'),
            // 'payment_desc'=>$this->input->post('payment_desc'),
            'check_name'=>$this->input->post('check_name'),
            'cash_check'=>$this->input->post('cash'),
            'bank_no'=>$this->input->post('bank_no'),
            'total_amount'=>$this->input->post('total_amount'),
            'checked_by'=>$this->input->post('checked'),
            'endorsed_by'=>$this->input->post('endorsed'),
            'approved_by'=>$this->input->post('approved'),
            'noted_by'=>$this->input->post('noted'),
            'received_by'=>$this->input->post('received'),
            //'rfd_type'=>$this->input->post('rfd_type'),
            'notes'=>$this->input->post('notes'),
            'user_id'=>$_SESSION['user_id'],
            'saved'=>1
        );

            $joi_rfd_id= $this->super_model->insert_return_id("joi_rfd", $data);


       // $count_rfd=$this->super_model->count_rows_where("joi_rfd_payment","joi_id",$joi_id);
       // if($count_rfd==0){
       //      $rfd_no = 1;
       //      $prev_rfd_no = 0;
       //  } else {
       //      $rfd_max = $this->super_model->get_max_where("joi_rfd_payment", "rfd_no","joi_id = '$joi_id'");
       //      $rfd_no = $rfd_max+1;
       //      if($rfd_no == 1){
       //          $prev_rfd_no = $rfd_no;
       //      }else if($rfd_no > 1){
       //          $prev_rfd_no = $rfd_no-1;
       //      }else{
       //          $prev_rfd_no = 0;
       //      }
            
       //  }

       //  if($prev_rfd_no != 0){
       //      foreach($this->super_model->select_custom_where("joi_rfd_payment", "rfd_no = '$prev_rfd_no' AND joi_id = '$joi_id'") AS $jrp){
       //          $data_pay_prev = array(
       //              'joi_rfd_id'=>$joi_rfd_id,
       //              'joi_id'=>$jrp->joi_id,
       //              'rfd_no'=>$rfd_no,
       //              'rfd_date'=>$jrp->rfd_date,
       //              'payment_description'=>$jrp->payment_description,
       //              'payment_amount'=>$jrp->payment_amount,
       //              'user_id'=>$_SESSION['user_id'],
       //              'date_created'=>date("Y-m-d H:i:s"),
       //              );
       //              $this->super_model->insert_into("joi_rfd_payment", $data_pay_prev);
       //      }
       //  }
        

        // if($this->input->post('payment_desc')!=''){
        //     $count_payment = count($this->input->post('payment_desc'));
        // }else{
        //     $count_payment = 0;
        // }

        // for($x=0; $x<$count_payment;$x++){
        //     $payment_description = $this->input->post('payment_desc['.$x.']');
        //     $payment_amount = $this->input->post('payment_amount['.$x.']');

        if(!empty($this->input->post('sub_total_amount'))){
            $sub_total = $this->input->post('sub_total_amount');
        }else{
            $sub_total = 0;
        }

            // if($payment_description != ''){
                $data_pay = array(
                'joi_rfd_id'=>$joi_rfd_id,
                'joi_id'=>$joi_id,
                // 'rfd_no'=>$rfd_no,
                'rfd_date'=>$rfd_date,
                'payment_description'=>$this->input->post('payment_desc'),
                'payment_amount'=>$this->input->post('payment_amount'),
                'sub_total'=>$sub_total,
                'ewt_vat'=>$this->input->post('ewt_vat'),
                'ewt_percent'=>$this->input->post('ewt_percent'),
                'ewt_amount'=>$this->input->post('ewt_amount'),
                'retention_percent'=>$this->input->post('retention_percent'),
                'retention_amount'=>$this->input->post('retention_amount'),
                'user_id'=>$_SESSION['user_id'],
                'date_created'=>date("Y-m-d H:i:s"),
                );
                $this->super_model->insert_into("joi_rfd_payment", $data_pay);
            // }
            
        // }

        if($this->input->post('payment_details')!=''){
            $count_payment_dets = count($this->input->post('payment_details'));
        }else{
            $count_payment_dets = 0;
        }

        for($x=0; $x<$count_payment_dets;$x++){
            $payment_details = $this->input->post('payment_details['.$x.']');
            $rfd_payment_id = $this->input->post('rfd_payment_id['.$x.']');

        $payment_details = array(
            'rfd_notes'=>$payment_details
        );
        $this->super_model->update_where("joi_rfd_payment", $payment_details, "joi_rfd_payment_id",$rfd_payment_id);
        }


         // if($this->super_model->insert_into("joi_rfd", $data)){
        if($joi_rfd_id != 0 || $joi_rfd_id != ''){
            redirect(base_url().'jod/jod_rfd_saved/'.$joi_id.'/'.$joi_rfd_id.'/'.$rfd_date, 'refresh');
        }
    }

    public function update_rfd(){
           $joi_id= $this->input->post('joi_id');

   
        $data = array(
            'rfd_date'=>$this->input->post('rfd_date'),
            'due_date'=>$this->input->post('due_date'),
            'check_due'=>$this->input->post('check_due'),
            'saved'=>1
        );

         if($this->super_model->update_where("joi_rfd", $data, "joi_id", $joi_id)){
            redirect(base_url().'jod/jod_rfd/'.$joi_id, 'refresh');
        }
    }

    /*public function jod_dr(){
        $joi_id = $this->uri->segment(3); 
        $joi_dr_id = $this->uri->segment(4); 
        $data['joi_id']=$joi_id;
        $data['joi_dr_id']=$joi_dr_id;
        $data['saved'] = $this->super_model->select_column_where("joi_dr", "saved", "joi_id", $joi_id);
        $data['head']= $this->super_model->select_row_where('joi_head', 'joi_id', $joi_id);
        $data['revision_no']= $this->super_model->select_column_where("joi_dr", "revision_no", "joi_id", $joi_id);
        
        $user_id= $this->super_model->select_column_where("joi_head", "user_id", "joi_id", $joi_id);
        $data['prepared']= $this->super_model->select_column_where("users", "fullname", "user_id", $user_id);
        $data['cancelled']=$this->super_model->select_column_where("joi_head", "cancelled", "joi_id", $joi_id);
        $data['delivered_to'] = $this->super_model->select_column_where("joi_dr", "delivered_to", "joi_id", $joi_id);
        $data['address'] = $this->super_model->select_column_where("joi_dr", "address", "joi_id", $joi_id);
        $data['requested_by'] = $this->super_model->select_column_where("joi_dr", "requested_by", "joi_id", $joi_id);
        foreach($this->super_model->select_row_where('joi_jor', 'joi_jor_id', $joi_id) AS $jor){
             $itemno='';
            foreach($this->super_model->select_custom_where("joi_dr_items", "jor_id= '$jor->jor_id' AND joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'") AS $it){
                $itemno .= $it->item_no . ", ";
            }
            $item_no = substr($itemno, 0, -2);
            $jo_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id);
                if($jo_no!=''){
                    $jor_no=$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor->jor_id);
                }else{
                    $jor_no=$this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $jor->jor_id);
            }
            $data['jor'][]=array(
                'jor_no'=>$jor_no,
                'enduse'=>$jor->enduse,
                'purpose'=>$jor->purpose,
                'requestor'=>$jor->requestor,
                'item_no'=>$item_no
            );
        }

        if(empty($joi_dr_id)){
            $data['dr_no']= $this->super_model->select_column_where("joi_dr", "joi_dr_no", "joi_id", $joi_id);
            foreach($this->super_model->select_custom_where("joi_dr_items", "joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'") AS $items){
               $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
                $data['items'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("jor_aoq_items", "scope_of_work", "joi_aoq_items_id", $items->joi_aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'uom'=>$items->uom,
                );
            }
        } else {
            $data['dr_no']= $this->super_model->select_column_custom_where("joi_dr", "joi_dr_no", "joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'");
            foreach($this->super_model->select_custom_where('joi_dr_items', "joi_id= '$joi_id' AND joi_dr_id = '$joi_dr_id'") AS $items){
               $vendor_id= $this->super_model->select_column_where("joi_head", "vendor_id", "joi_id", $joi_id);
                $data['items'][]= array(
                    'item_no'=>$items->item_no,
                    'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $vendor_id),
                    'item'=>$this->super_model->select_column_where("jor_aoq_items", "scope_of_work", "jor_aoq_items_id", $items->joi_aoq_items_id),
                    'offer'=>$items->offer,
                    'delivered_quantity'=>$items->delivered_quantity,
                    'received_quantity'=>$items->quantity,
                    'uom'=>$items->uom,
                );
            }
        }
        $this->load->view('template/header');        
        $this->load->view('jod/jod_dr',$data);
        $this->load->view('template/footer');
    }*/

    /*public function deliver_jod(){
        $joi_id = $this->uri->segment(3);
        $joi_dr_id = $this->uri->segment(4);
        $data['joi_id']=$joi_id;
        $data['joi_dr_id']=$joi_dr_id;
        $data['items'] = $this->super_model->select_custom_where("joi_dr_items","joi_id='$joi_id' AND joi_dr_id = '$joi_dr_id'");
        $this->load->view('template/header');        
        $this->load->view('jod/deliver_jod',$data);
        $this->load->view('template/footer');
    }*/

    public function jod_ac(){  
        $joi_id = $this->uri->segment(3);
        $data['joi_id'] = $joi_id;
        $this->load->view('template/header');
        $data['saved'] = $this->super_model->select_column_where("joi_ar", "saved", "joi_id", $joi_id);
        $data['cancelled'] = $this->super_model->select_column_where("joi_head", "cancelled", "joi_id", $joi_id);
        $data['revision_no'] = $this->super_model->select_column_where("joi_head", "revision_no", "joi_id", $joi_id);
        $data['delivered_to'] = $this->super_model->select_column_where("joi_ar", "delivered_to", "joi_id", $joi_id);
        $data['address'] = $this->super_model->select_column_where("joi_ar", "address", "joi_id", $joi_id);
        /*$data['requested_by'] = $this->super_model->select_column_where("joi_ar", "requested_by", "joi_id", $joi_id);*/
        $data['gatepass_no'] = $this->super_model->select_column_where("joi_ar", "gatepass_no", "joi_id", $joi_id);
        $year = $this->super_model->select_column_where("joi_ar", "year", "joi_id", $joi_id);
        $series = $this->super_model->select_column_where("joi_ar", "series", "joi_id", $joi_id);
        $data['ar_no']= $year."-".$series;
        $data['jo_head']=$this->super_model->select_row_where('joi_head', 'joi_id', $joi_id);
        $jor_id= $this->super_model->select_column_where("joi_items", "jor_id", "joi_id", $joi_id);
        $data['requested_by'] = $this->super_model->select_column_where("jor_head", "requested_by", "jor_id", $jor_id);
        $data['jor_no'] = $this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jor_id);
        $data['user_jo_no'] = $this->super_model->select_column_where("jor_head", "user_jo_no", "jor_id", $jor_id);
        foreach($this->super_model->select_row_where("joi_items","joi_id",$joi_id) AS $jd){
            $vendor_id = $this->super_model->select_column_where("joi_head","vendor_id","joi_id",$joi_id);
            $vendor = $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$vendor_id);
            $data['materials_offer']= $jd->materials_offer;
            $data['materials_qty']= $jd->materials_qty;
            $data['jo_det'][]=array(
                'supplier'=>$vendor,
                'scope_of_work'=>$jd->offer,
                'received_quantity'=>$jd->quantity,
                'delivered_quantity'=>$jd->delivered_quantity,
                'materials_offer'=>$jd->materials_offer,
                'materials_qty'=>$jd->materials_qty,
                'materials_unitprice'=>$jd->materials_unitprice,
                'materials_amount'=>$jd->materials_amount,
                'materials_received'=>$jd->materials_received,
                'materials_unit'=>$jd->materials_unit,
                'uom'=>$jd->uom,
            );
        }

        //MATERIALS
        foreach($this->super_model->select_custom_where("joi_items","joi_id='$joi_id' GROUP BY materials_offer") AS $jd){
            $vendor_id = $this->super_model->select_column_where("joi_head","vendor_id","joi_id",$joi_id);
            $vendor = $this->super_model->select_column_where("vendor_head","vendor_name","vendor_id",$vendor_id);
            $data['materials_offer']= $jd->materials_offer;
            $data['materials_qty']= $jd->materials_qty;
            $data['jo_det_materials'][]=array(
                'supplier'=>$vendor,
                'scope_of_work'=>$jd->offer,
                'received_quantity'=>$jd->quantity,
                'delivered_quantity'=>$jd->delivered_quantity,
                'materials_offer'=>$jd->materials_offer,
                'materials_qty'=>$jd->materials_qty,
                'materials_unitprice'=>$jd->materials_unitprice,
                'materials_amount'=>$jd->materials_amount,
                'materials_received'=>$jd->materials_received,
                'materials_unit'=>$jd->materials_unit,
                'uom'=>$jd->uom,
            );
        }
        $this->load->view('jod/jod_ac',$data);
        $this->load->view('template/footer');
    }

    public function save_ar(){
        $joi_id = $this->input->post('joi_id');
        $data = array(
            'delivered_to'=>$this->input->post('delivered_to'),
            'address'=>$this->input->post('address'),
            'requested_by'=>$this->input->post('requested_by'),
            'gatepass_no'=>$this->input->post('gatepass'),
            'saved'=>1
        );
        if($this->super_model->update_where("joi_ar", $data, "joi_id", $joi_id)){
            echo "<script>window.location ='".base_url()."jod/jod_ac/$joi_id';</script>";
        }
    }

    public function jod_coc(){
        $joi_id = $this->uri->segment(3);
        $data['joi_id'] = $joi_id;
        $this->load->view('template/header');
        foreach($this->super_model->select_row_where("joi_head", "joi_id", $joi_id) AS $head){
            $subtotal = ($head->total_cost + $head->vat);
            $data['vendor'] = $this->super_model->select_column_where('vendor_head', 'vendor_name', 'vendor_id', $head->vendor_id);
            $data['address'] = $this->super_model->select_column_where('vendor_head', 'address', 'vendor_id', $head->vendor_id);
            $data['contact_person'] = $this->super_model->select_column_where('vendor_head', 'contact_person', 'vendor_id', $head->vendor_id);
            $data['phone'] = $this->super_model->select_column_where('vendor_head', 'phone_number', 'vendor_id', $head->vendor_id);
            $data['fax'] = $this->super_model->select_column_where('vendor_head', 'fax_number', 'vendor_id', $head->vendor_id);
            $data['cenjo_no']= $head->cenpri_jo_no;
            $data['jo_no']= $head->joi_no;
            $data['project_title']= $head->project_title;
            //$data['date_prepared']= $head->date_prepared;
            $data['date_needed']= $head->date_needed;
            $data['general_desc']= $head->general_desc;
            $data['start_of_work']= $head->start_of_work;
            $data['completion_date']= $head->completion_date;
            $data['revision_no']= $head->revision_no;
            // /$data['discount_percent']= $head->discount_percent;
            $data['discount_amount']= $head->discount;
            $data['vat_percent']= $head->vat_percent;
            $data['subtotal']= $subtotal;
            $data['vat_amount']= $head->vat;
            $data['total_cost']= $head->total_cost;
            $data['grand_total']= $head->grand_total;
            $data['conforme']= $head->conforme;

            $data['checked_by']= $this->super_model->select_column_where('joi_coc', 'checked_by', 'joi_id', $head->joi_id);
            $data['approved_by']= $this->super_model->select_column_where('joi_coc', 'approved_by', 'joi_id', $head->joi_id);
            $checked_by= $this->super_model->select_column_where('joi_coc', 'checked_by', 'joi_id', $head->joi_id);
            $approved_by= $this->super_model->select_column_where('joi_coc', 'approved_by', 'joi_id', $head->joi_id);
            $data['coc_saved']=  $this->super_model->select_column_where('joi_coc', 'saved', 'joi_id', $head->joi_id);
            $data['warranty']=  $this->super_model->select_column_where('joi_coc', 'warranty', 'joi_id', $head->joi_id);
            $data['date_prepared_coc']=  $this->super_model->select_column_where('joi_coc', 'date_prepared', 'joi_id', $head->joi_id);
            $data['date_created']=  $this->super_model->select_column_where('joi_coc', 'date_created', 'joi_id', $head->joi_id);

            $data['verified_by']=$this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->verified_by);
            $data['checked'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $checked_by);
            $data['pos_checked'] = $this->super_model->select_column_where('employees', 'position', 'employee_id', $checked_by);
            $data['approved'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $approved_by);
            $data['pos_approved'] = $this->super_model->select_column_where('employees', 'position', 'employee_id', $approved_by);
            $data['recommended'] = $this->super_model->select_column_where('employees', 'employee_name', 'employee_id', $head->recommended_by);
            $data['prepared'] = $this->super_model->select_column_where('users', 'fullname', 'user_id', $head->user_id);
            $data['ref_year'] = $this->super_model->select_column_where('joi_coc', 'year', 'joi_id', $head->joi_id);
            $data['ref_series'] = $this->super_model->select_column_where('joi_coc', 'series', 'joi_id', $head->joi_id);
            $data['cancelled']=$head->cancelled;
        }   

        $data['dr'] = $this->super_model->select_row_where("joi_dr", "joi_id", $joi_id);
        $data['details'] = $this->super_model->select_row_where("joi_items", "joi_id", $joi_id);
        $data['details_materials'] = $this->super_model->select_custom_where("joi_items", "joi_id='$joi_id' GROUP BY materials_offer");
        $data['materials_offer']= $this->super_model->select_column_where('joi_items', 'materials_offer', 'joi_id', $joi_id);
        $data['materials_qty']= $this->super_model->select_column_where('joi_items', 'materials_qty', 'joi_id', $joi_id);
        $data['terms'] = $this->super_model->select_row_where("joi_terms", "joi_terms_id", $joi_id);
        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('jod/jod_coc',$data);
        $this->load->view('template/footer');
    }

    public function save_coc(){
        $joi_id = $this->input->post('joi_id');
        $data = array(
            'approved_by'=>$this->input->post('approved_by'),
            'checked_by'=>$this->input->post('checked_by'),
            'warranty'=>$this->input->post('coc_warranty'),
            'date_prepared'=>$this->input->post('date_prepared'),
            'date_created'=>date('Y-m-d H:i:s'),
            'saved'=>1
        );
        if($this->super_model->update_where("joi_coc", $data, "joi_id", $joi_id)){
            echo "<script>window.location ='".base_url()."jod/jod_coc/$joi_id';</script>";
        }
    }
}

?>