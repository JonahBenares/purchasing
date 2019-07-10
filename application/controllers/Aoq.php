<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aoq extends CI_Controller {

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

    public function add_aoq(){
        $rfq = $this->input->post('rfq');
        
        $rfq_id1 = $rfq[0];
        //echo $rfq_id1;
        $pr_id = $this->super_model->select_column_where("rfq_head", "pr_id", "rfq_id", $rfq_id1);

       $rows_head = $this->super_model->count_rows("aoq_head");
        if($rows_head==0){
            $aoq_id=1;
        } else {
            $max = $this->super_model->get_max("aoq_head", "aoq_id");
            $aoq_id = $max+1;
        }
        $aoq_date = date('Y-m-d');

        foreach($this->super_model->select_row_where("pr_head", "pr_id", $pr_id) AS $pr){
            $head = array(
                'aoq_id'=>$aoq_id,
                'aoq_date'=>$aoq_date,
                'pr_id'=>$pr_id,
                'department'=>$pr->department,
                'purpose'=>$pr->purpose,
                'enduse'=>$pr->enduse,
                'requestor'=>$pr->requestor
            );

            $this->super_model->insert_into("aoq_head", $head);
        }
        $where="SELECT * FROM rfq_details WHERE";
        foreach($rfq AS $r){
            $vendor_id = $this->super_model->select_column_where("rfq_head", "vendor_id", "rfq_id", $r);
            $vendors = array(
                'aoq_id'=>$aoq_id,
                'vendor_id'=>$vendor_id,
                'rfq_id'=>$r
            );
            $this->super_model->insert_into("aoq_vendors", $vendors);

            $where.=" rfq_id = '$r' OR";
        }

        $sql=substr($where, 0, -3);
        $sql .= " GROUP BY item_desc";
       // echo $sql;
       foreach($this->super_model->custom_query($sql) AS $items){
          $items = array(
            'aoq_id'=>$aoq_id,
            'item_description'=>$items->item_desc,
            'quantity'=>$items->quantity,
            'uom'=>$items->uom
          );
          $this->super_model->insert_into("aoq_items", $items);
       }

        $count = count($rfq);
        if($count<=3){
            redirect(base_url().'aoq/aoq_prnt/'.$aoq_id);
        } else if($count==4){
            redirect(base_url().'aoq/aoq_prnt_four/'.$aoq_id);
        } else if($count==5){
            redirect(base_url().'aoq/aoq_prnt_five/'.$aoq_id);
        }
    }

	public function aoq_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $count = $this->super_model->count_rows_where("aoq_head","saved",'1');
        if($count!=0){
            foreach($this->super_model->select_custom_where("aoq_head", "saved='1'") AS $list){
                $rows = $this->super_model->count_rows_where("aoq_vendors","aoq_id",$list->aoq_id);
                $supplier='';
                foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$list->aoq_id' GROUP BY vendor_id") AS $offer){
                    $supplier.="-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $offer->vendor_id). "<br> ";
                }
                $sup = substr($supplier, 0, -2);

                $data['heads'][]=array(
                    'aoq_id'=>$list->aoq_id,
                    'date'=>$list->aoq_date,
                    'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$list->pr_id),
                    'supplier'=>$sup,
                    'department'=>$list->department,
                    'enduse'=>$list->enduse,
                    'requestor'=>$list->requestor,
                    'saved'=>$list->saved,
                    'rows'=>$rows,
                    'awarded'=>$list->awarded,
                );
            }
        }else {
            $data['heads']=array();
        }
        $this->load->view('aoq/aoq_list',$data);
        $this->load->view('template/footer');
    }  

    public function aoq_prnt(){
        $aoq_id= $this->uri->segment(3);
        $data['aoq_id']=$aoq_id;
        $data['saved']=$this->super_model->select_column_where("aoq_head", "saved", "aoq_id", $aoq_id);
        $data['awarded']=$this->super_model->select_column_where("aoq_head", "awarded", "aoq_id", $aoq_id);

        $noted_id=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_id=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);

        $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $noted_id);
        $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $approved_id);
        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $data['head'][] =  array(
                'aoq_date'=>$head->aoq_date,
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id),
                'department'=>$head->department,
                'purpose'=>$head->purpose,
                'enduse'=>$head->enduse,
                'requestor'=>$head->requestor

            );
        }

        foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $ven){
            $data['vendors'][] = array(
                'vendor_id'=>$ven->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $ven->vendor_id),
                'phone'=>$this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $ven->vendor_id),
                'contact'=>$this->super_model->select_column_where("vendor_head", "contact_person", "vendor_id", $ven->vendor_id),
            );
        }

        $data['items'] = $this->super_model->select_row_where("aoq_items","aoq_id",$aoq_id);

        foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $off){
            $allprice[] = array(
                'item_id'=>$off->aoq_items_id,
                'price'=>$off->unit_price,
            );

          }
         
          $x=0;
        foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $off){  
           if(!empty($allprice)){
                foreach($allprice AS $var=>$key){
                    foreach($key AS $v=>$k){
                       
                        if($key['item_id']==$off->aoq_items_id){
                            $minprice[$x][] = $key['price'];
                        }
                    }               
                }
                $min=min($minprice[$x]);
            } else {
                $min=0;
            }

      
              $data['offers'][] = array(
                'aoq_offer_id'=>$off->aoq_offer_id,
                'vendor_id'=>$off->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $off->vendor_id),
                'item_id'=>$off->aoq_items_id,
                'offer'=>$off->offer,
                'price'=>$off->unit_price,
                'amount'=>$off->amount,
                'min'=>$min,
                'recommended'=>$off->recommended,
                'comments'=>$off->comments
            );
              $x++;
        }

        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");

        $this->load->view('template/header');
        $this->load->view('aoq/aoq_prnt',$data);
        $this->load->view('template/footer');
        
    } 

    public function aoq_prnt_four(){
        $this->load->view('template/header');
        $aoq_id= $this->uri->segment(3);
        $data['aoq_id']=$aoq_id;
        $data['saved']=$this->super_model->select_column_where("aoq_head", "saved", "aoq_id", $aoq_id);
        $data['awarded']=$this->super_model->select_column_where("aoq_head", "awarded", "aoq_id", $aoq_id);

        $noted_id=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_id=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);

        $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $noted_id);
        $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $approved_id);
        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $data['head'][] =  array(
                'aoq_date'=>$head->aoq_date,
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id),
                'department'=>$head->department,
                'purpose'=>$head->purpose,
                'enduse'=>$head->enduse,
                'requestor'=>$head->requestor

            );
        }

        foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $ven){
            $data['vendors'][] = array(
                'vendor_id'=>$ven->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $ven->vendor_id),
                'phone'=>$this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $ven->vendor_id),
                'contact'=>$this->super_model->select_column_where("vendor_head", "contact_person", "vendor_id", $ven->vendor_id),
            );
        }

        $data['items'] = $this->super_model->select_row_where("aoq_items","aoq_id",$aoq_id);

        foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $off){
            $allprice[] = array(
                'item_id'=>$off->aoq_items_id,
                'price'=>$off->unit_price,
            );
        }
         
        $x=0;
        foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $off){  
           if(!empty($allprice)){
                foreach($allprice AS $var=>$key){
                    foreach($key AS $v=>$k){
                       
                        if($key['item_id']==$off->aoq_items_id){
                            $minprice[$x][] = $key['price'];
                        }
                    }               
                }
                $min=min($minprice[$x]);
            } else {
                $min=0;
            }

            $data['offers'][] = array(
                'aoq_offer_id'=>$off->aoq_offer_id,
                'vendor_id'=>$off->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $off->vendor_id),
                'item_id'=>$off->aoq_items_id,
                'offer'=>$off->offer,
                'price'=>$off->unit_price,
                'amount'=>$off->amount,
                'min'=>$min,
                'recommended'=>$off->recommended,
                'comments'=>$off->comments
            );
            $x++;
        }

        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('aoq/aoq_prnt_four',$data);
        $this->load->view('template/footer');
    } 

    public function aoq_prnt_five(){
        $this->load->view('template/header');
        $aoq_id= $this->uri->segment(3);
        $data['aoq_id']=$aoq_id;
        $data['saved']=$this->super_model->select_column_where("aoq_head", "saved", "aoq_id", $aoq_id);
        $data['awarded']=$this->super_model->select_column_where("aoq_head", "awarded", "aoq_id", $aoq_id);

        $noted_id=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_id=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);

        $data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $noted_id);
        $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $approved_id);
        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $data['head'][] =  array(
                'aoq_date'=>$head->aoq_date,
                'pr_no'=>$this->super_model->select_column_where("pr_head", "pr_no", "pr_id", $head->pr_id),
                'department'=>$head->department,
                'purpose'=>$head->purpose,
                'enduse'=>$head->enduse,
                'requestor'=>$head->requestor

            );
        }

        foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $ven){
            $data['vendors'][] = array(
                'vendor_id'=>$ven->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $ven->vendor_id),
                'phone'=>$this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $ven->vendor_id),
                'contact'=>$this->super_model->select_column_where("vendor_head", "contact_person", "vendor_id", $ven->vendor_id),
            );
        }

        $data['items'] = $this->super_model->select_row_where("aoq_items","aoq_id",$aoq_id);

        foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $off){
            $allprice[] = array(
                'item_id'=>$off->aoq_items_id,
                'price'=>$off->unit_price,
            );
        }
         
        $x=0;
        foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $off){  
           if(!empty($allprice)){
                foreach($allprice AS $var=>$key){
                    foreach($key AS $v=>$k){
                       
                        if($key['item_id']==$off->aoq_items_id){
                            $minprice[$x][] = $key['price'];
                        }
                    }               
                }
                $min=min($minprice[$x]);
            } else {
                $min=0;
            }

            $data['offers'][] = array(
                'aoq_offer_id'=>$off->aoq_offer_id,
                'vendor_id'=>$off->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $off->vendor_id),
                'item_id'=>$off->aoq_items_id,
                'offer'=>$off->offer,
                'price'=>$off->unit_price,
                'amount'=>$off->amount,
                'min'=>$min,
                'recommended'=>$off->recommended,
                'comments'=>$off->comments
            );
            $x++;
        }

        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");
        $this->load->view('aoq/aoq_prnt_five',$data);
        $this->load->view('template/footer');
    } 

    public function save_aoq(){
        $aoq_id = $this->input->post('aoq_id');
        $item_count = $this->input->post('item_count');
        $vendor_count = $this->input->post('vendor_count');
        $aoq_date = date('Y-m-d');
        $count=$this->input->post('count');
        for($x=1;$x<$item_count;$x++){
            for($v=1;$v<$vendor_count;$v++){
                for($a=1;$a<=3;$a++){
                    $offer = $this->input->post('offer_'.$x.'_'.$v.'_'.$a);
                    $up = $this->input->post('price_'.$x.'_'.$v.'_'.$a);
                    $amount = $this->input->post('amount_'.$x.'_'.$v.'_'.$a);
                    $vendor = $this->input->post('vendor_'.$x.'_'.$v);
                    $item = $this->input->post('item_'.$x.'_'.$v);
                    $quantity = $this->input->post('quantity_'.$x.'_'.$v);
<<<<<<< HEAD
                    $uom = $this->input->post('uom'.$x.'_'.$v);

=======
>>>>>>> 90d19c165f6b4b854a4ec3748eb4230d2ecc9a47
                    //echo $offer. " = " . 'offer_'.$x.'_'.$v.'_'.$a . '<br><br>';
                    //echo $up. " = " . 'price_'.$x.'_'.$v.'_'.$a . '<br><br>';
                    if(!empty($offer)){
                        $offers = array(
                            'aoq_id'=>$aoq_id,
                            'vendor_id'=>$vendor,
                            'aoq_items_id'=>$item,
                            'offer'=>$offer,
                            'unit_price'=>$up,
                            'quantity'=>$quantity,
                            'amount'=>$amount,
                            'uom'=>$uom
                        );
                        //print_r($offers);
                        $this->super_model->insert_into("aoq_offers", $offers);
                    }
                }
            }
        }
        $head = array(
            'noted_by'=>$this->input->post('noted'),
            'approved_by'=>$this->input->post('approved'),
            'aoq_date'=>$aoq_date,
            'saved'=>1
        );
        if($this->super_model->update_where("aoq_head", $head, "aoq_id", $aoq_id)){
            if($count==3){
                redirect(base_url().'aoq/aoq_prnt/'.$aoq_id, 'refresh');
            }else if($count==4){
                redirect(base_url().'aoq/aoq_prnt_four/'.$aoq_id, 'refresh');
            }else if($count==5){
                redirect(base_url().'aoq/aoq_prnt_five/'.$aoq_id, 'refresh');
            }
        }
    }

    public function award_aoq(){
        $count_offer = $this->input->post('count_offer');
        $aoq_id = $this->input->post('aoq_id');
        $count=$this->input->post('count');
        for($x=1;$x<$count_offer;$x++){
            $data =  array(
                'recommended'=>$this->input->post('award_'.$x),
                'comments'=>$this->input->post('comments_'.$x),
            );

            $this->super_model->update_where('aoq_offers', $data, 'aoq_offer_id', $this->input->post('offerid_'.$x));
        }

        $head=array(
            'awarded'=>1
        );
        $this->super_model->update_where('aoq_head', $head, 'aoq_id', $aoq_id);
        if($count==3){
            redirect(base_url().'aoq/aoq_prnt/'.$aoq_id, 'refresh');
        }else if($count==4){
            redirect(base_url().'aoq/aoq_prnt_four/'.$aoq_id, 'refresh');
        }else if($count==5){
            redirect(base_url().'aoq/aoq_prnt_five/'.$aoq_id, 'refresh');
        }
    }

    public function aoq_prnt2(){
        $this->load->view('template/header_aoq');
        $this->load->view('aoq/aoq_prnt2');
        $this->load->view('template/footer_aoq');
    } 

    public function cancelled_rfq(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('rfq/cancelled_rfq');
        $this->load->view('template/footer');
    } 

}

?>