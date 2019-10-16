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
        );

        return $currency;
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
        $where="SELECT rd.* FROM rfq_details rd INNER JOIN pr_details pd ON rd.pr_details_id = pd.pr_details_id WHERE (";
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
        $sql .= ") AND pd.cancelled = 0 GROUP BY item_desc, pn_no";
        echo $sql;
       foreach($this->super_model->custom_query($sql) AS $items){
          $items = array(
            'aoq_id'=>$aoq_id,
            'item_description'=>$items->item_desc,
            'quantity'=>$items->quantity,
            'uom'=>$items->uom,
            'pr_details_id'=>$items->pr_details_id
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

    public function open_aoq_before(){
        $aoq_id = $this->uri->segment(3);

        $count_vendors = $this->super_model->count_rows_where("aoq_vendors", "aoq_id", $aoq_id);
        $data = array(
            'open'=>1,

        );

        if($this->super_model->update_where("aoq_head", $data, "aoq_id", $aoq_id)){
            if($count_vendors<=3){
                 redirect(base_url().'aoq/aoq_prnt/'.$aoq_id);
            }  else if($count_vendors==4){
                 redirect(base_url().'aoq/aoq_prnt_four/'.$aoq_id);
            }  else if($count_vendors==5){
                 redirect(base_url().'aoq/aoq_prnt_five/'.$aoq_id);
            }
        }
    }
	public function aoq_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $count = $this->super_model->count_custom_where("aoq_head","saved='1' AND served='0' AND cancelled='0'");

        if($count!=0){
            foreach($this->super_model->select_custom_where("aoq_head", "saved='1' and served = '0' AND cancelled='0'") AS $list){
                $rows = $this->super_model->count_rows_where("aoq_vendors","aoq_id",$list->aoq_id);
                $supplier='';
                $not_recom='';
                foreach($this->super_model->select_custom_where("aoq_vendors", "aoq_id='$list->aoq_id'") AS $ven){
                    foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$list->aoq_id' AND recommended='1' GROUP BY vendor_id") AS $offer){
                        if($offer->vendor_id==$ven->vendor_id){
                            $supplier.="<span style='background-color:#b5e61d;'>-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $offer->vendor_id). "</span><br> ";

                        $not_recom .= "vendor_id != '$offer->vendor_id' AND ";
                        }
                       
                    }
                     
                }
               // / $not_recom=substr($not_recom, 0, -4);
                  // echo $not_recom;
                 $not_recom .= " aoq_id='$list->aoq_id' ";
                  foreach($this->super_model->select_custom_where("aoq_vendors", $not_recom) AS $offer1){
                        
                    $supplier.="-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $offer1->vendor_id). "<br> ";
                
                 }

                // echo $supplier;
               // $sup = substr($supplier, 0, -2);

                $data['heads'][]=array(
                    'aoq_id'=>$list->aoq_id,
                    'date'=>$list->aoq_date,
                    'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$list->pr_id),
                    'supplier'=>$supplier,
                    'department'=>$list->department,
                    'enduse'=>$list->enduse,
                    'requestor'=>$list->requestor,
                    'saved'=>$list->saved,
                    'rows'=>$rows,
                    'awarded'=>$list->awarded,
                    'refer_mnl'=>$list->refer_mnl,
                );
            }
        } else {
            $data['heads']=array();
        }
        $this->load->view('aoq/aoq_list',$data);
        $this->load->view('template/footer');
    }  

    public function cancelled_aoq(){
        $this->load->view('template/header');        
        $this->load->view('template/navbar');
        $count = $this->super_model->count_custom_where("aoq_head","saved='1' AND served='0' AND cancelled = '1'");
        if($count!=0){
            foreach($this->super_model->select_custom_where("aoq_head", "saved='1' and served = '0' AND cancelled = '1'") AS $list){
                $rows = $this->super_model->count_rows_where("aoq_vendors","aoq_id",$list->aoq_id);
                $supplier='';
                $not_recom='';
                foreach($this->super_model->select_custom_where("aoq_vendors", "aoq_id='$list->aoq_id'") AS $ven){
                    foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$list->aoq_id' AND recommended='1' GROUP BY vendor_id") AS $offer){
                        if($offer->vendor_id==$ven->vendor_id){
                            $supplier.="<span style='background-color:#b5e61d;'>-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $offer->vendor_id). "</span><br> ";
                            $not_recom .= "vendor_id != '$offer->vendor_id' AND ";
                        }
                       
                    }
                }

                $not_recom .= " aoq_id='$list->aoq_id' ";
                foreach($this->super_model->select_custom_where("aoq_vendors", $not_recom) AS $offer1){   
                    $supplier.="-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $offer1->vendor_id). "<br> ";
                }
                $data['heads'][]=array(
                    'aoq_id'=>$list->aoq_id,
                    'date'=>$list->aoq_date,
                    'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$list->pr_id),
                    'supplier'=>$supplier,
                    'department'=>$list->department,
                    'enduse'=>$list->enduse,
                    'requestor'=>$list->requestor,
                    'saved'=>$list->saved,
                    'rows'=>$rows,
                    'awarded'=>$list->awarded,
                    'refer_mnl'=>$list->refer_mnl,
                    'cancel_date'=>$list->cancel_date,
                    'cancelled_reason'=>$list->cancelled_reason,
                );
            }
        } else {
            $data['heads']=array();
        }
        $this->load->view('aoq/cancelled_aoq',$data);
        $this->load->view('template/footer');
    }

    public function cancel_aoq(){
        $aoq_id=$this->input->post('aoq_id');
        $reason=$this->input->post('reason');
        $create = date('Y-m-d H:i:s');
        $data = array(
            'cancelled'=>1,
            'cancelled_by'=>$_SESSION['user_id'],
            'cancelled_reason'=>$reason,
            'cancel_date'=>$create
        );

        if($this->super_model->update_where("aoq_head", $data, "aoq_id", $aoq_id)){
            redirect(base_url().'aoq/aoq_list', 'refresh');
        }
    }

    public function update_served(){
        $aoq_id=$this->uri->segment(3);
        $data = array(
            'date_served'=>date('Y-m-d H:i:s'),
            'served'=>1
        );

        if($this->super_model->update_where("aoq_head", $data, "aoq_id", $aoq_id)){
            redirect(base_url().'aoq/aoq_list', 'refresh');
        }
    }

    public function served_aoq(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $count = $this->super_model->count_rows_where("aoq_head","saved",'1');
        if($count!=0){
            foreach($this->super_model->select_custom_where("aoq_head", "saved='1' and served = '1'") AS $list){
                $rows = $this->super_model->count_rows_where("aoq_vendors","aoq_id",$list->aoq_id);
                $supplier='';
                $not_recom='';
                foreach($this->super_model->select_custom_where("aoq_vendors", "aoq_id='$list->aoq_id'") AS $ven){
                    foreach($this->super_model->select_custom_where("aoq_offers", "aoq_id = '$list->aoq_id' AND recommended='1' GROUP BY vendor_id") AS $offer){
                        if($offer->vendor_id==$ven->vendor_id){
                            $supplier.="<span style='background-color:#b5e61d;'>-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $offer->vendor_id). "</span><br> ";
                            $not_recom .= "vendor_id != '$offer->vendor_id' AND ";
                        }   
                    } 
                }
                $not_recom=substr($not_recom, 0, -4);
                  // echo $not_recom;
                $not_recom .= " AND aoq_id='$list->aoq_id'";
                foreach($this->super_model->select_custom_where("aoq_vendors", $not_recom) AS $offer1){        
                    $supplier.="-".$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $offer1->vendor_id). "<br> ";
                 }
                // echo $supplier;
               // $sup = substr($supplier, 0, -2);
                $data['heads'][]=array(
                    'aoq_id'=>$list->aoq_id,
                    'date'=>$list->aoq_date,
                    'pr_no'=>$this->super_model->select_column_where("pr_head","pr_no","pr_id",$list->pr_id),
                    'supplier'=>$supplier,
                    'department'=>$list->department,
                    'enduse'=>$list->enduse,
                    'requestor'=>$list->requestor,
                    'saved'=>$list->saved,
                    'rows'=>$rows,
                    'awarded'=>$list->awarded,
                    'refer_mnl'=>$list->refer_mnl
                );
            }
        }else {
            $data['heads']=array();
        }
        $this->load->view('aoq/served_aoq',$data);
        $this->load->view('template/footer');
    }

    public function refer_mnl(){
        $aoq_id=$this->uri->segment(3);
        $data = array(
            'refer_date'=>date('Y-m-d H:i:s'),
            'refer_mnl'=>1
        );
        if($this->super_model->update_where("aoq_head", $data, "aoq_id", $aoq_id)){
            $to='stephineseverino.cenpri@gmail.com';
            $subject="REFERED TO MANILA";
            $message='';
            $message.="REFERED TO MANILA: <br><br>";
            foreach($this->super_model->select_custom_where("aoq_head", "aoq_id = '$aoq_id' AND saved='1' AND refer_mnl = '1'") AS $list){
                $pr_no = $this->super_model->select_column_where("pr_head","pr_no","pr_id",$list->pr_id);
                $pr_no = $this->super_model->select_column_where("pr_head","pr_no","pr_id",$list->pr_id);
                $message.="<div style='border:2px solid; font-size:12px'>";
                $message.="<table style='width:100%; border-collapse:collapse; font-size:12'>";
                $message.="<td style='width:10%;'>PR No.: </td><td style='width:40%;'>".$pr_no."</td>";
                $message.="<td style='width:10%;'>Purpose: </td><td style='width:40%;'>".$list->purpose."</td>";
                $message.="</tr><tr>";
                $message.="<td  style='width:10%;'>Department: </td><td style='width:40%;'>".$list->department."</td>";
                $message.="<td  style='width:10%;'>End-Use: </td><td style='width:40%;'>".$list->enduse."</td>";
                $message.="</tr><tr>";
                $message.="<td  style='width:10%;'>Requestor: </td><td style='width:40%;'>".$list->requestor."</td>";
                $message.="</tr>";
                $message.="</table>";

                $message.="<table style='width:100%; border-collapse:collapse; font-size:12;border:1px solid #000'>";
                $message.="<thead>";
                $message.="<tr>";
                $message.="<th width='2%' align='left' style='border:1px solid #000'>".'#'."</th>";
                $message.="<th width='60%' align='left' style='border:1px solid #000'>".'Item Name'."</th>";
                $message.="<th width='19%' align='left' style='border:1px solid #000'>".'Quantity'."</th>";
                $message.="<th width='19%' align='left' style='border:1px solid #000'>".'UOM'."</th>";
                $message.="</tr>";
                $message.="</thead>";
                $message.="<tbody>";
                $x=1;
                foreach($this->super_model->select_custom_where("aoq_items", "aoq_id = '$aoq_id'") AS $item){
                    $message.="<tr>";
                    $message.="<td style='border:1px solid #000'>".$x."</td>";
                    $message.="<td style='border:1px solid #000'>".$item->item_description."</td>";
                    $message.="<td style='border:1px solid #000'>".$item->quantity."</td>";
                    $message.="<td style='border:1px solid #000'>".$item->uom."</td>";
                    $message.="</tr>";
                    $x++;
                }
                $message.="</tbody>";
                $message.="</table>";
                $message.="</div><br>";
            }
            //echo $message;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <jonah.narazo@gmail.com>' . "\r\n";
            var_dump(mail($to,$subject,$message,$headers));

            redirect(base_url().'aoq/aoq_list', 'refresh');
        }
    }

    public function aoq_prnt(){
        $aoq_id= $this->uri->segment(3);
        $data['aoq_id']=$aoq_id;
        $data['currency'] = $this->currency_list();
        $data['saved']=$this->super_model->select_column_where("aoq_head", "saved", "aoq_id", $aoq_id);
         $data['open']=$this->super_model->select_column_where("aoq_head", "open", "aoq_id", $aoq_id);
        $data['served']=$this->super_model->select_column_where("aoq_head", "served", "aoq_id", $aoq_id);
        $data['awarded']=$this->super_model->select_column_where("aoq_head", "awarded", "aoq_id", $aoq_id);

        $noted_by=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_by=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);

        /*$data['noted']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $noted_id);
        $data['approved']=$this->super_model->select_column_where("employees", "employee_name", "employee_id", $approved_id);*/
        $data['noted']=$noted_by;
        $data['approved']=$approved_by;

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
                'id'=>$ven->aoq_vendors_id,
                'vendor_id'=>$ven->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $ven->vendor_id),
                'phone'=>$this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $ven->vendor_id),
                'contact'=>$this->super_model->select_column_where("vendor_head", "contact_person", "vendor_id", $ven->vendor_id),
                'validity'=>$ven->price_validity,
                'terms'=>$ven->payment_terms,
                'delivery_date'=>$ven->delivery_date,
                'freight'=>$ven->freight,
                'warranty'=>$ven->item_warranty,

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
                'quantity'=>$off->quantity,
                 'pr_details_id'=>$off->pr_details_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $off->vendor_id),
                'item_id'=>$off->aoq_items_id,
                'currency'=>$off->currency,
                'offer'=>$off->offer,
                'price'=>$off->unit_price,
                'amount'=>$off->amount,
                'min'=>$min,
                'recommended'=>$off->recommended,
                'comments'=>$off->comments,

            );
              $x++;
        }

        $data['employee']=$this->super_model->select_all_order_by("employees", "employee_name", "ASC");

        $this->load->view('template/header');
        $this->load->view('aoq/aoq_prnt',$data);
        $this->load->view('template/footer');
        
    } 

    public function update_aoq(){
        $count=$this->input->post('count_offer');
        for($x=1;$x<=$count;$x++){
            $price = str_replace(",", "", $this->input->post('price_'.$x));
            $amount = str_replace(",", "", $this->input->post('amount_'.$x));
            $data = array(
                'currency'=>$this->input->post('currency_'.$x),
                'offer'=>$this->input->post('offer_'.$x),
                'unit_price'=>$price,
                'quantity'=>$this->input->post('quantity_'.$x),
                'amount'=>$amount
            );
            $this->super_model->update_where("aoq_offers", $data, "aoq_offer_id", $this->input->post('offerid_'.$x));
        }

        for($v=1;$v<=3;$v++){
            $datavendors = array(
                'price_validity'=>$this->input->post('price_validity'.$v),
                'payment_terms'=>$this->input->post('payment_terms'.$v),
                'delivery_date'=>$this->input->post('delivery_date'.$v),
                'item_warranty'=>$this->input->post('item_warranty'.$v),
                'freight'=>$this->input->post('freight'.$v)
            );
             $this->super_model->update_where("aoq_vendors", $datavendors, "aoq_vendors_id", $this->input->post('vendor_id'.$v));
        }
        $datahead = array(
            'open'=>0
        );
         $this->super_model->update_where("aoq_head", $datahead, "aoq_id", $this->input->post('aoq_id'));

        $count_vendors = $this->super_model->count_rows_where("aoq_vendors", "aoq_id", $this->input->post('aoq_id'));

         if($count_vendors<=3){
             redirect(base_url().'aoq/aoq_prnt/'.$this->input->post('aoq_id'));
         } else if($count_vendors==4){
             redirect(base_url().'aoq/aoq_prnt_four/'.$this->input->post('aoq_id'));
         } else if($count_vendors==5){
             redirect(base_url().'aoq/aoq_prnt_five/'.$this->input->post('aoq_id'));
         }
    }
 

    public function export_aoq_prnt(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="AOQ.xlsx";
        $aoq_id=$this->uri->segment(3);
        /*$noted_id=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_id=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);*/

        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $noted=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->noted_by);
            $approved=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);
            $pr_no=$this->super_model->select_column_where('pr_head','pr_no','pr_id', $head->pr_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', "ABSTRACT OF QUOTATION");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "Department: $head->department");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', "Purpose: $head->purpose");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "Enduse: $head->enduse");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "Requested By: $head->requestor");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', "Date: ".date('F j, Y',strtotime($head->aoq_date)));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', "PR#: $pr_no");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Date Needed: ");
            $objPHPExcel->getActiveSheet()->getStyle('F1:G1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('F1:G1');
        }

        $num1 = 8;
        $num2 = 9;
        foreach(range('A','B') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A8', "#");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B8', "DESCRIPTION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C8', "QTY");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D8', "OUM");
        $x=0;
        $y=1;
        foreach($this->super_model->select_row_where("aoq_items","aoq_id",$aoq_id) AS $items){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num2, "$y");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num2, "$items->item_description");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num2, "$items->quantity");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num2, "$items->uom");
            $col='E';
            $num=7;
            $one=7;
            $two=8;
            foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $rfq){
                $supplier_id=$this->super_model->select_column_where('rfq_head','vendor_id','rfq_id', $rfq->rfq_id);
                $supplier=$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $supplier_id);
                $contact=$this->super_model->select_column_where('vendor_head','contact_person','vendor_id', $supplier_id);
                $phone=$this->super_model->select_column_where('vendor_head','phone_number','vendor_id', $supplier_id);
                $validity=$this->super_model->select_column_where('rfq_head','price_validity','rfq_id', $rfq->rfq_id);
                $terms=$this->super_model->select_column_where('rfq_head','payment_terms','rfq_id', $rfq->rfq_id);
                $delivery=$this->super_model->select_column_where('rfq_head','delivery_date','rfq_id', $rfq->rfq_id);
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                foreach(range('E','P') as $columnID){
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                $header = array(
                    array(
                        'OFFER',
                        'P/U',
                        'AMOUNT',
                        'COMMENTS',
                    )
                );

                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->fromArray($header, null, $col.$two);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num2.":P".$num2)->applyFromArray($styleArray);

                foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $ven){
                    foreach($this->super_model->select_row_where("aoq_items", "pr_details_id",  $ven->pr_details_id) AS $rf){
                        $allprice[] = array(
                            'item_desc'=>$rf->item_description,
                            'price'=>$ven->unit_price
                        );
                    }
                }

                $q = $num2;
                foreach ($this->super_model->select_custom_where("aoq_offers","aoq_id='$aoq_id' AND vendor_id = '$rfq->vendor_id' AND pr_details_id = '$items->pr_details_id'") AS $allrfq) {
                    $amount = $items->quantity*$allrfq->unit_price;

                    if(!empty($allprice)){
                        foreach($allprice AS $var=>$key){
                            foreach($key AS $v=>$k){
                               
                                if($key['item_desc']==$items->item_description){
                                    $minprice[$x][] = $key['price'];
                                }
                            }               
                        }
                        $min=min($minprice[$x]);
                    } else {
                        $min=0;
                    }

                    $sheet = array(
                        array(
                            $allrfq->offer,
                            $allrfq->unit_price,
                            $amount,
                            $allrfq->comments,
                        )
                    );

                    $phpColor = new PHPExcel_Style_Color();
                    $phpColor->setRGB('FF0000'); 
                    $objPHPExcel->getActiveSheet()->getStyle($col.$q)->getFont()->setColor($phpColor);

                    if($allrfq->unit_price==$min){
                        $col2 = chr(ord($col) + 1);
                        $objPHPExcel->getActiveSheet()->getStyle($col2.$q)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f4e542');
                    }

                    if($allrfq->vendor_id==$supplier_id && $allrfq->recommended==1){
                        $col2 = chr(ord($col) + 2);
                        $objPHPExcel->getActiveSheet()->getStyle($col2.$q)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('92D050');
                    }

                    $objPHPExcel->getActiveSheet()->fromArray($sheet, null, $col.$q);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$q.":G".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$q.":G".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$q.":K".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$q.":K".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$q.":O".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$q.":O".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('R'.$q.":S".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('R'.$q.":S".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('V'.$q.":W".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('V'.$q.":W".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$q.":P".$q)->applyFromArray($styleArray);
                    $q++;
                }

                $objPHPExcel->getActiveSheet()->getStyle($col.$one)->getFont()->setBold(true);
                for($i=0;$i<3; $i++) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$one, "$supplier\n$contact\n$phone");
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$one.':P'.$one)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle($col.$one)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(50);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$one.':P'.$one)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$one.':H'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('I'.$one.':L'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('M'.$one.':P'.$one);
                    $col++;
                }
                $q++;
                $num++;
                $col++;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A'.$num1.":P".$num1)->applyFromArray($styleArray);
            $x++;
            $y++;
            $num1++;
            $num2 = $q;
            $num2++;
        }

        /*$a = $num2+2;
        $b = $num2+4;
        $c = $num2+6;
        $d = $num2+8;
        $e = $num2+10;
        $f = $num2+12;
        $g = $num2+14;*/
        $a = $num2+1;
        $b = $num2+2;
        $c = $num2+3;
        $d = $num2+4;
        $e = $num2+5;
        $f = $num2+6;
        $g = $num2+7;
        $h = $num2+8;
        $cols = 'E';
        foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $rfq){
            $validity=$rfq->price_validity;
            $terms=$rfq->payment_terms;
            $delivery=$rfq->delivery_date;
            $warranty=$rfq->item_warranty;
            $freight=$rfq->freight;
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$a, "a. Price Validity");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$b, "b. Payment Terms");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c, "c. Date of Delivery");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$d, "d. Items Warranty");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$e, "e. In-land Freight");

            $objPHPExcel->getActiveSheet()->mergeCells('E'.$a.':H'.$a);
            $objPHPExcel->getActiveSheet()->mergeCells('I'.$b.':L'.$b);
            $objPHPExcel->getActiveSheet()->mergeCells('M'.$c.':P'.$c);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$a, $validity);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$b, $terms);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$c, $delivery);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$d, $warranty);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$e, $freight);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$c)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$d)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$e)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            for($y=0;$y<3;$y++){
                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$a)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$b)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$c)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $objPHPExcel->getActiveSheet()->getStyle($cols.$d)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $objPHPExcel->getActiveSheet()->getStyle($cols.$e)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $cols++;
            }
            $cols++;
        }

        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $requested=$head->requestor;
            /*$noted=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->noted_by);
            $approved=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);*/
            $noted=$head->noted_by;
            $approved=$head->approved_by;

            $objPHPExcel->getActiveSheet()->setCellValue('E'.$h, $_SESSION['fullname']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$h, $requested);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$h, $approved);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$h, $noted);

            $objPHPExcel->getActiveSheet()->setCellValue('E'.$f, "Prepared by: ");
            $objPHPExcel->getActiveSheet()->getStyle('E'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('G'.$f, "Award Recommended by: ");
            $objPHPExcel->getActiveSheet()->getStyle('G'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('I'.$f, "Noted by: ");
            $objPHPExcel->getActiveSheet()->getStyle('I'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('K'.$f, "Approved by: ");
            $objPHPExcel->getActiveSheet()->getStyle('K'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A8:P8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A8:P8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="AOQ.xlsx"');
        readfile($exportfilename);

    }

    public function aoq_prnt_four(){
        $this->load->view('template/header');
        $aoq_id= $this->uri->segment(3);
        $data['currency'] = $this->currency_list();
        $data['aoq_id']=$aoq_id;
        $data['saved']=$this->super_model->select_column_where("aoq_head", "saved", "aoq_id", $aoq_id);
         $data['open']=$this->super_model->select_column_where("aoq_head", "open", "aoq_id", $aoq_id);
        $data['served']=$this->super_model->select_column_where("aoq_head", "served", "aoq_id", $aoq_id);
        $data['awarded']=$this->super_model->select_column_where("aoq_head", "awarded", "aoq_id", $aoq_id);

        $noted_by=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_by=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);

        $data['noted']=$noted_by;
        $data['approved']=$approved_by;
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
                'id'=>$ven->aoq_vendors_id,
                'vendor_id'=>$ven->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $ven->vendor_id),
                'phone'=>$this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $ven->vendor_id),
                'contact'=>$this->super_model->select_column_where("vendor_head", "contact_person", "vendor_id", $ven->vendor_id),
                'validity'=>$ven->price_validity,
                'terms'=>$ven->payment_terms,
                'delivery_date'=>$ven->delivery_date,
                'freight'=>$ven->freight,
                'warranty'=>$ven->item_warranty,
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
                'quantity'=>$off->quantity,
                'currency'=>$off->currency,
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

    public function export_aoq_prnt_four(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="AOQ.xlsx";
        $aoq_id=$this->uri->segment(3);
        /*$noted_id=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_id=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);*/

        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $noted=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->noted_by);
            $approved=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);
            $pr_no=$this->super_model->select_column_where('pr_head','pr_no','pr_id', $head->pr_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', "ABSTRACT OF QUOTATION");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "Department: $head->department");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', "Purpose: $head->purpose");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "Enduse: $head->enduse");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "Requested By: $head->requestor");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', "Date: ".date('F j, Y',strtotime($head->aoq_date)));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', "PR#: $pr_no");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Date Needed: ");
            $objPHPExcel->getActiveSheet()->getStyle('F1:G1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('F1:G1');
        }

        $num1 = 8;
        $num2 = 9;
        foreach(range('A','B') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A8', "#");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B8', "DESCRIPTION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C8', "QTY");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D8', "OUM");
        $x=0;
        $y=1;
        foreach($this->super_model->select_row_where("aoq_items","aoq_id",$aoq_id) AS $items){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num2, "$y");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num2, "$items->item_description");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num2, "$items->quantity");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num2, "$items->uom");
            $col='E';
            $num=7;
            $one=7;
            $two=8;
            foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $rfq){
                $supplier_id=$this->super_model->select_column_where('rfq_head','vendor_id','rfq_id', $rfq->rfq_id);
                $supplier=$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $supplier_id);
                $contact=$this->super_model->select_column_where('vendor_head','contact_person','vendor_id', $supplier_id);
                $phone=$this->super_model->select_column_where('vendor_head','phone_number','vendor_id', $supplier_id);
                $validity=$this->super_model->select_column_where('rfq_head','price_validity','rfq_id', $rfq->rfq_id);
                $terms=$this->super_model->select_column_where('rfq_head','payment_terms','rfq_id', $rfq->rfq_id);
                $delivery=$this->super_model->select_column_where('rfq_head','delivery_date','rfq_id', $rfq->rfq_id);
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                foreach(range('E','T') as $columnID){
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                $header = array(
                    array(
                        'OFFER',
                        'P/U',
                        'AMOUNT',
                        'COMMENTS',
                    )
                );

                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->fromArray($header, null, $col.$two);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num2.":T".$num2)->applyFromArray($styleArray);

                foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $ven){
                    foreach($this->super_model->select_row_where("aoq_items", "pr_details_id",  $ven->pr_details_id) AS $rf){
                        $allprice[] = array(
                            'item_desc'=>$rf->item_description,
                            'price'=>$ven->unit_price
                        );
                    }
                }

                $q = $num2;
                foreach ($this->super_model->select_custom_where("aoq_offers","aoq_id='$aoq_id' AND vendor_id = '$rfq->vendor_id' AND pr_details_id = '$items->pr_details_id'") AS $allrfq) {
                    $amount = $items->quantity*$allrfq->unit_price;

                    if(!empty($allprice)){
                        foreach($allprice AS $var=>$key){
                            foreach($key AS $v=>$k){
                               
                                if($key['item_desc']==$items->item_description){
                                    $minprice[$x][] = $key['price'];
                                }
                            }               
                        }
                        $min=min($minprice[$x]);
                    } else {
                        $min=0;
                    }

                    $sheet = array(
                        array(
                            $allrfq->offer,
                            $allrfq->unit_price,
                            $amount,
                            $allrfq->comments,
                        )
                    );

                    $phpColor = new PHPExcel_Style_Color();
                    $phpColor->setRGB('FF0000'); 
                    $objPHPExcel->getActiveSheet()->getStyle($col.$q)->getFont()->setColor($phpColor);

                    if($min==$allrfq->unit_price){
                        $col2 = chr(ord($col) + 1);
                        $objPHPExcel->getActiveSheet()->getStyle($col2.$q)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f4e542');
                    }

                    if($allrfq->vendor_id==$supplier_id && $allrfq->recommended==1){
                        $col2 = chr(ord($col) + 2);
                        $objPHPExcel->getActiveSheet()->getStyle($col2.$q)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('92D050');
                    }

                    $objPHPExcel->getActiveSheet()->fromArray($sheet, null, $col.$q);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$q.":G".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$q.":G".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$q.":K".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$q.":K".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$q.":O".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$q.":O".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('R'.$q.":S".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('R'.$q.":S".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('V'.$q.":W".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('V'.$q.":W".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$q.":T".$q)->applyFromArray($styleArray);
                    $q++;
                }

                $objPHPExcel->getActiveSheet()->getStyle($col.$one)->getFont()->setBold(true);
                for($i=0;$i<3; $i++) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$one, "$supplier\n$contact\n$phone");
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$one.':T'.$one)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle($col.$one)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(50);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$one.':V'.$one)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$one.':H'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('I'.$one.':L'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('M'.$one.':P'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('Q'.$one.':T'.$one);
                    $col++;
                }
                $q++;
                $num++;
                $col++;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A'.$num1.":T".$num1)->applyFromArray($styleArray);
            $x++;
            $y++;
            $num1++;
            $num2 = $q;
            $num2++;
        }

        /*$a = $num2+2;
        $b = $num2+4;
        $c = $num2+6;
        $d = $num2+8;
        $e = $num2+10;
        $f = $num2+12;
        $g = $num2+14;*/

        $a = $num2+1;
        $b = $num2+2;
        $c = $num2+3;
        $d = $num2+4;
        $e = $num2+5;
        $f = $num2+6;
        $g = $num2+7;
        $h = $num2+8;
        $cols = 'E';
        foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $rfq){
            $validity=$rfq->price_validity;
            $terms=$rfq->payment_terms;
            $delivery=$rfq->delivery_date;
            $warranty=$rfq->item_warranty;
            $freight=$rfq->freight;
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$a, "a. Price Validity");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$b, "b. Payment Terms");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c, "c. Date of Delivery");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$d, "d. Items Warranty");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$e, "e. In-land Freight");

            $objPHPExcel->getActiveSheet()->mergeCells('E'.$a.':H'.$a);
            $objPHPExcel->getActiveSheet()->mergeCells('I'.$b.':L'.$b);
            $objPHPExcel->getActiveSheet()->mergeCells('M'.$c.':P'.$c);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$a, $validity);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$b, $terms);
            /*if(empty($delivery)){
                $date = '';
            }else {
                $date = date('F j, Y',strtotime($delivery));
            }*/
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$c, $delivery);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$d, $warranty);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$e, $freight);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$c)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$d)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$e)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            for($y=0;$y<3;$y++){
                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$a)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$b)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$c)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $objPHPExcel->getActiveSheet()->getStyle($cols.$d)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $objPHPExcel->getActiveSheet()->getStyle($cols.$e)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $cols++;
            }
            $cols++;
        }

        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $requested=$head->requestor;
            /*$noted=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->noted_by);
            $approved=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);*/
            $noted=$head->noted_by;
            $approved=$head->approved_by;

            $objPHPExcel->getActiveSheet()->setCellValue('E'.$h, $_SESSION['fullname']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$h, $requested);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$h, $approved);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$h, $noted);

            $objPHPExcel->getActiveSheet()->setCellValue('E'.$f, "Prepared by: ");
            $objPHPExcel->getActiveSheet()->getStyle('E'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('G'.$f, "Award Recommended by: ");
            $objPHPExcel->getActiveSheet()->getStyle('G'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('I'.$f, "Noted by: ");
            $objPHPExcel->getActiveSheet()->getStyle('I'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('K'.$f, "Approved by: ");
            $objPHPExcel->getActiveSheet()->getStyle('K'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A8:T8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A8:T8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="AOQ.xlsx"');
        readfile($exportfilename);

    }

    public function aoq_prnt_five(){
        $this->load->view('template/header');
        $aoq_id= $this->uri->segment(3);
         $data['currency'] = $this->currency_list();
        $data['aoq_id']=$aoq_id;
        $data['saved']=$this->super_model->select_column_where("aoq_head", "saved", "aoq_id", $aoq_id);
         $data['open']=$this->super_model->select_column_where("aoq_head", "open", "aoq_id", $aoq_id);
        $data['served']=$this->super_model->select_column_where("aoq_head", "served", "aoq_id", $aoq_id);
        $data['awarded']=$this->super_model->select_column_where("aoq_head", "awarded", "aoq_id", $aoq_id);

        $noted_by=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_by=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);

        $data['noted']=$noted_by;
        $data['approved']=$approved_by;
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
                'id'=>$ven->aoq_vendors_id,
                'vendor_id'=>$ven->vendor_id,
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $ven->vendor_id),
                'phone'=>$this->super_model->select_column_where("vendor_head", "phone_number", "vendor_id", $ven->vendor_id),
                'contact'=>$this->super_model->select_column_where("vendor_head", "contact_person", "vendor_id", $ven->vendor_id),
                'validity'=>$ven->price_validity,
                'terms'=>$ven->payment_terms,
                'delivery_date'=>$ven->delivery_date,
                'freight'=>$ven->freight,
                'warranty'=>$ven->item_warranty,
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
                'currency'=>$off->currency,
                'quantity'=>$off->quantity,
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

    public function export_aoq_prnt_five(){
        require_once(APPPATH.'../assets/js/phpexcel/Classes/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $exportfilename="AOQ.xlsx";
        $aoq_id=$this->uri->segment(3);
        /*$noted_id=$this->super_model->select_column_where("aoq_head", "noted_by", "aoq_id", $aoq_id);
        $approved_id=$this->super_model->select_column_where("aoq_head", "approved_by", "aoq_id", $aoq_id);*/

        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $noted=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->noted_by);
            $approved=$this->super_model->select_column_where('employees','employee_name','employee_id', $head->approved_by);
            $pr_no=$this->super_model->select_column_where('pr_head','pr_no','pr_id', $head->pr_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', "ABSTRACT OF QUOTATION");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', "Department: $head->department");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', "Purpose: $head->purpose");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', "Enduse: $head->enduse");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "Requested By: $head->requestor");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', "Date: ".date('F j, Y',strtotime($head->aoq_date)));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', "PR#: $pr_no");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H4', "Date Needed: ");
            $objPHPExcel->getActiveSheet()->getStyle('F1:G1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('F1:G1');
        }

        $num1 = 8;
        $num2 = 9;
        foreach(range('A','B') as $columnID){
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A8', "#");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B8', "DESCRIPTION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C8', "QTY");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D8', "OUM");
        $x=0;
        $y=1;
        foreach($this->super_model->select_row_where("aoq_items","aoq_id",$aoq_id) AS $items){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$num2, "$y");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$num2, "$items->item_description");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$num2, "$items->quantity");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$num2, "$items->uom");
            $col='E';
            $num=7;
            $one=7;
            $two=8;
            foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $rfq){
                $supplier_id=$this->super_model->select_column_where('rfq_head','vendor_id','rfq_id', $rfq->rfq_id);
                $supplier=$this->super_model->select_column_where('vendor_head','vendor_name','vendor_id', $supplier_id);
                $contact=$this->super_model->select_column_where('vendor_head','contact_person','vendor_id', $supplier_id);
                $phone=$this->super_model->select_column_where('vendor_head','phone_number','vendor_id', $supplier_id);
                $validity=$this->super_model->select_column_where('rfq_head','price_validity','rfq_id', $rfq->rfq_id);
                $terms=$this->super_model->select_column_where('rfq_head','payment_terms','rfq_id', $rfq->rfq_id);
                $delivery=$this->super_model->select_column_where('rfq_head','delivery_date','rfq_id', $rfq->rfq_id);
                $styleArray = array(
                    'borders' => array(
                        'allborders' => array(
                          'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );
                foreach(range('E','X') as $columnID){
                    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                $header = array(
                    array(
                        'OFFER',
                        'P/U',
                        'AMOUNT',
                        'COMMENTS',
                    )
                );

                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->fromArray($header, null, $col.$two);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$num2.":X".$num2)->applyFromArray($styleArray);

                foreach($this->super_model->select_row_where("aoq_offers","aoq_id",$aoq_id) AS $ven){
                    foreach($this->super_model->select_row_where("aoq_items", "pr_details_id",  $ven->pr_details_id) AS $rf){
                        $allprice[] = array(
                            'item_desc'=>$rf->item_description,
                            'price'=>$ven->unit_price
                        );
                    }
                }

                $q = $num2;
                //$q = 9;
                foreach ($this->super_model->select_custom_where("aoq_offers","aoq_id='$aoq_id' AND vendor_id = '$rfq->vendor_id' AND pr_details_id = '$items->pr_details_id'") AS $allrfq) {
                    $amount = $items->quantity*$allrfq->unit_price;

                    if(!empty($allprice)){
                        foreach($allprice AS $var=>$key){
                            foreach($key AS $v=>$k){
                               
                                if($key['item_desc']==$items->item_description){
                                    $minprice[$x][] = $key['price'];
                                }
                            }               
                        }
                        $min=min($minprice[$x]);
                    } else {
                        $min=0;
                    }

                    $sheet = array(
                        array(
                            $allrfq->offer,
                            $allrfq->unit_price,
                            $amount,
                            $allrfq->comments,
                        )
                    );

                    $phpColor = new PHPExcel_Style_Color();
                    $phpColor->setRGB('FF0000'); 
                    $objPHPExcel->getActiveSheet()->getStyle($col.$q)->getFont()->setColor($phpColor);

                    if($min==$allrfq->unit_price){
                        $col2 = chr(ord($col) + 1);
                        $objPHPExcel->getActiveSheet()->getStyle($col2.$q)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('f4e542');
                    }

                    if($allrfq->vendor_id==$supplier_id && $allrfq->recommended==1){
                        $col2 = chr(ord($col) + 2);
                        $objPHPExcel->getActiveSheet()->getStyle($col2.$q)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('92D050');
                    }

                    $objPHPExcel->getActiveSheet()->fromArray($sheet, null, $col.$q);
                    $objPHPExcel->getActiveSheet()->getStyle('C'.$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$q.":G".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$q.":G".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$q.":K".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$q.":K".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$q.":O".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('N'.$q.":O".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('R'.$q.":S".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('R'.$q.":S".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('V'.$q.":W".$q)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('V'.$q.":W".$q)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$q.":X".$q)->applyFromArray($styleArray);
                    $q++;
                }

                $objPHPExcel->getActiveSheet()->getStyle($col.$one)->getFont()->setBold(true);
                for($i=0;$i<3; $i++) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$one, "$supplier\n$contact\n$phone");
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$one.':X'.$one)->applyFromArray($styleArray);
                    $objPHPExcel->getActiveSheet()->getStyle($col.$one)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(50);
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$one.':V'.$one)->getAlignment()->setWrapText(true);
                    $objPHPExcel->getActiveSheet()->mergeCells('E'.$one.':H'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('I'.$one.':L'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('M'.$one.':P'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('Q'.$one.':T'.$one);
                    $objPHPExcel->getActiveSheet()->mergeCells('U'.$one.':X'.$one);
                    $col++;
                }
                $q++;
                $num++;
                $col++;
            }
            $objPHPExcel->getActiveSheet()->getStyle('A'.$num1.":X".$num1)->applyFromArray($styleArray);
            $x++;
            $y++;
            $num1++;
            $num2 = $q;
            $num2++;
        }

        /*$a = $num2+2;
        $b = $num2+4;
        $c = $num2+6;
        $d = $num2+8;
        $e = $num2+10;
        $f = $num2+12;
        $g = $num2+14;*/
        $a = $num2+1;
        $b = $num2+2;
        $c = $num2+3;
        $d = $num2+4;
        $e = $num2+5;
        $f = $num2+6;
        $g = $num2+7;
        $h = $num2+8;
        $cols = 'E';
        foreach($this->super_model->select_row_where("aoq_vendors","aoq_id",$aoq_id) AS $rfq){
            $validity=$rfq->price_validity;
            $terms=$rfq->payment_terms;
            $delivery=$rfq->delivery_date;
            $warranty=$rfq->item_warranty;
            $freight=$rfq->freight;
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$a, "a. Price Validity");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$b, "b. Payment Terms");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c, "c. Date of Delivery");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$d, "d. Items Warranty");
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$e, "e. In-land Freight");

            $objPHPExcel->getActiveSheet()->mergeCells('E'.$a.':H'.$a);
            $objPHPExcel->getActiveSheet()->mergeCells('I'.$b.':L'.$b);
            $objPHPExcel->getActiveSheet()->mergeCells('M'.$c.':P'.$c);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$a, $validity);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$b, $terms);
            /*if(empty($delivery)){
                $date = '';
            }else {
                $date = date('F j, Y',strtotime($delivery));
            }*/
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$c, $delivery);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$d, $warranty);
            $objPHPExcel->getActiveSheet()->setCellValue($cols.$e, $freight);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$c)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$d)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($cols.$e)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            for($y=0;$y<3;$y++){
                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$a)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$b)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                
                $objPHPExcel->getActiveSheet()->getStyle($cols.$c)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $objPHPExcel->getActiveSheet()->getStyle($cols.$d)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $objPHPExcel->getActiveSheet()->getStyle($cols.$e)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $cols++;
            }
            $cols++;
        }

        foreach($this->super_model->select_row_where("aoq_head", "aoq_id", $aoq_id) AS $head){
            $requested=$head->requestor;
            $noted=$head->noted_by;
            $approved=$head->approved_by;

            $objPHPExcel->getActiveSheet()->setCellValue('E'.$h, $_SESSION['fullname']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$h, $requested);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$h, $approved);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$h, $noted);

            $objPHPExcel->getActiveSheet()->setCellValue('E'.$f, "Prepared by: ");
            $objPHPExcel->getActiveSheet()->getStyle('E'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('G'.$f, "Award Recommended by: ");
            $objPHPExcel->getActiveSheet()->getStyle('G'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('I'.$f, "Noted by: ");
            $objPHPExcel->getActiveSheet()->getStyle('I'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            $objPHPExcel->getActiveSheet()->setCellValue('K'.$f, "Approved by: ");
            $objPHPExcel->getActiveSheet()->getStyle('K'.$g)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$f)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$g)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$h)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A8:X8')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A8:X8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if (file_exists($exportfilename))
                unlink($exportfilename);
        $objWriter->save($exportfilename);
        unset($objPHPExcel);
        unset($objWriter);   
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="AOQ.xlsx"');
        readfile($exportfilename);

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
                    $currency = $this->input->post('currency_'.$x.'_'.$v.'_'.$a);
                    $up = $this->input->post('price_'.$x.'_'.$v.'_'.$a);
                    $amount = $this->input->post('amount_'.$x.'_'.$v.'_'.$a);
                    $vendor = $this->input->post('vendor_'.$x.'_'.$v);
                    $item = $this->input->post('item_'.$x.'_'.$v);
                    $quantity = $this->input->post('quantity_'.$x.'_'.$v);
                    $uom = $this->input->post('uom_'.$x.'_'.$v);
                    $pr_details_id = $this->input->post('pr_details_id_'.$x.'_'.$v);
                    //echo $offer. " = " . 'offer_'.$x.'_'.$v.'_'.$a . '<br><br>';
                    //echo $up. " = " . 'price_'.$x.'_'.$v.'_'.$a . '<br><br>';
                    if(!empty($offer)){
                        $offers = array(
                            'aoq_id'=>$aoq_id,
                            'vendor_id'=>$vendor,
                            'aoq_items_id'=>$item,
                            'pr_details_id'=>$pr_details_id,
                            'currency'=>$currency,
                            'offer'=>$offer,
                            'unit_price'=>$up,
                            'quantity'=>$quantity,
                            'balance'=>$quantity,
                            'amount'=>$amount,
                            'uom'=>$uom
                        );
                        //print_r($offers);
                        $this->super_model->insert_into("aoq_offers", $offers);
                    }
                }
            }
        }

        for($b=1;$b<$vendor_count;$b++){
            $date=$this->input->post('delivery_date'.$b);
            $data_vendor = array(
                'price_validity'=>$this->input->post('price_validity'.$b),
                'payment_terms'=>$this->input->post('payment_terms'.$b),
                'delivery_date'=>$date,
                'item_warranty'=>$this->input->post('item_warranty'.$b),
                'freight'=>$this->input->post('freight'.$b),
                'aoq_vendors_id'=>$this->input->post('id'.$b)
            );


            $this->super_model->update_where("aoq_vendors", $data_vendor, "aoq_vendors_id", $this->input->post('id'.$b));
        }

       // print_r($data_vendor);

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