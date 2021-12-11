<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Wide extends CI_Controller {
	
	private $is_mobile;
	
	function __construct() {       
      parent::__construct();
      date_default_timezone_set("Asia/Seoul");
      $this->load->library('session');
      $this->load->model('Log_m');
      $this->load->model('Log_a');
		$this->Log_a->exe_log('typical','wide');

      $this->load->library('user_agent');
      	if ($this->agent->is_mobile()) 
		   $this->is_mobile=1;
		else 
			$this->is_mobile=0;
    }

   private function is_mobile () {
    	return $this->is_mobile;
   }


	public function pAttachPhoto() {
		$this->config->set_item('title','이미지 업로드');
		$this->load->view('PC/wide/pAttachPhoto');
	}

	public function uploadimage($control_name) {

		if($control_name == 'board') {
			$url = '/static/upload/img/';
			$do_thumbnail = 1;
		} else if ($control_name == 'csc') {
			$url = '/static/upload/img-csc/';
			$do_thumbnail = 0;
		} else if ($control_name == 'minfo') {
			$url = '/static/upload/img-minfo/';
			$do_thumbnail = 0;
		} else {
			return 0;
		}
		
		$this->load->model('Img_sys');
		$this->Img_sys->upload_img($url, $do_thumbnail);
	}

}