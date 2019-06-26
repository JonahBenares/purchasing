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


    public function pr_list(){  
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/pr_list');
        $this->load->view('template/footer');
    }

    public function purchase_request(){  
        $prid = $this->uri->segment(3);
        $data['pr_id']=$prid;
        $data['head']=$this->super_model->select_row_where("pr_head", "pr_id", $prid);
        $data['details']=$this->super_model->select_row_where("pr_details", "pr_id", $prid);
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('pr/purchase_request',$data);
        $this->load->view('template/footer');
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
    
    public function choose_vendor(){
        $this->load->view('template/header');
        $this->load->view('pr/choose_vendor');
        $this->load->view('template/footer');
    }
}

?>