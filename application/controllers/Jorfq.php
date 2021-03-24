<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jorfq extends CI_Controller {

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

	public function jorfq_list(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $head_count = $this->super_model->count_custom_query("SELECT rh.* FROM jo_rfq_head rh INNER JOIN jor_head jh ON rh.jor_id = jh.jor_id");
        foreach($this->super_model->custom_query("SELECT rh.* FROM jo_rfq_head rh INNER JOIN jor_head jh ON rh.jor_id = jh.jor_id") AS $jorfq){
            $data['head'][]= array(
                'jo_rfq_id'=>$jorfq->jo_rfq_id,
                'jo_rfq_no'=>$jorfq->jo_rfq_no,
                'jor_id'=>$jorfq->jor_id,
                'jo_no'=>$this->super_model->select_column_where("jor_head", "jo_no", "jor_id", $jorfq->jor_id),
                'vendor'=>$this->super_model->select_column_where("vendor_head", "vendor_name", "vendor_id", $jorfq->vendor_id),
                'rfq_date'=>$jorfq->rfq_date,
                'completed'=>$jorfq->completed
                
            );
        }

        foreach($this->super_model->custom_query("SELECT rd.* FROM jo_rfq_details rd INNER JOIN jor_items ji ON ji.jor_items_id = rd.jor_items_id") AS $it){
            $data['items'][] = array(
                'jo_rfq_id'=>$it->jo_rfq_id,
                'scope_of_work'=>$it->scope_of_work,
            );
        }
        $this->load->view('jorfq/jorfq_list',$data);
        $this->load->view('template/footer');
    }  

    public function complete_rfq(){
         $jo_rfq_id=$this->uri->segment(3);
          $data = array(
            'completed'=>1,
            'completed_date'=>date("Y-m-d H:i:s"),
          );
        if($this->super_model->update_where("jo_rfq_head", $data, "jo_rfq_id", $jo_rfq_id)){
             redirect(base_url().'jorfq/jorfq_list/', 'refresh');
        }
    }

    public function jorfq_outgoing(){
        $this->load->view('template/header');
        $this->load->view('jorfq/jorfq_outgoing');
        $this->load->view('template/footer');
    }  

    public function jorfq_served(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jorfq/jorfq_served');
        $this->load->view('template/footer');
    }  

    public function jorfq_cancelled(){
        $this->load->view('template/header');
        $this->load->view('template/navbar');
        $this->load->view('jorfq/jorfq_cancelled');
        $this->load->view('template/footer');
    }  

    
}

?>