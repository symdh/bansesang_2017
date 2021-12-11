<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {
	
	private $is_mobile;

	function __construct() {       
      parent::__construct();
      date_default_timezone_set("Asia/Seoul");

      $this->load->library('user_agent');
      	if ($this->agent->is_mobile()) 
		   $this->is_mobile=1;
		else 
			$this->is_mobile=0;

		// 운영자 아니면 BACK
		$this->load->model('Vui_sys');
		if(!$this->Vui_sys->check_login() || $this->Vui_sys->call_super_user() == 0 ) {
			sendback_site('reject_access', '', '', 0,'main');
			return 0;
		} 
   }

   private function is_mobile () {
    	return $this->is_mobile;
   }


   //index.php/manage/csc/$purpose/$function_name/$num
   	public function cap($purpose = 'main', $function_name = 0, $num = 0) {

		$this->config->set_item('title','운영 - 답변 페이지');

		include "./static/include/controllers/manage/cap.php"; 
		
	}


	   //index.php/manage/csc/$purpose/$function_name/$num
   	public function pms($purpose = 'main', $function_name = 0, $num = 0) {

		$this->config->set_item('title','운영 - 권한 관리 페이지');

		include "./static/include/controllers/manage/pms.php"; 
		
	}



}

require_once('./static/include/model/sendback_site.php');
