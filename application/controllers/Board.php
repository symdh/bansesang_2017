<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends CI_Controller {
	
	private $is_mobile;
	public $class_name = 'board';
   public $img_domain = 'http://img.bansesang.com'; //글 수정시 이미지 처리에 들어감

	function __construct() {       
      parent::__construct();
      date_default_timezone_set("Asia/Seoul");
      $this->load->library('session');
      $this->load->model('Log_m');
      $this->load->model('Log_a');
	   $this->Log_a->exe_log('typical','board');

    	$this->load->library('user_agent');
    	if ($this->agent->is_mobile()) 
		   $this->is_mobile=1;
		else 
			$this->is_mobile=0;
		$this->load->model('Board_model');
    }

   private function is_mobile () {
    	return $this->is_mobile;
   }

	public function free($purpose = 'list', $num = 0) {
		$this->Board_model->set_function_name('free');
		$this->config->set_item('title','자유게시판');

		include "./static/include/controllers/board/board.php"; 
		
	}

	public function photo($purpose = 'list', $num = 0) {
		$this->Board_model->set_function_name('photo');
		$this->config->set_item('title','사진게시판');

		include "./static/include/controllers/board/gallery.php"; 
		
	}

	public function qaa($purpose= 'list', $num = 0) {
		$this->Board_model->set_function_name('qaa');
		$this->config->set_item('title','질문게시판');

		include "./static/include/controllers/board/board.php"; 

	}
}
