<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
	
	private $is_mobile;

	function __construct() {       
      parent::__construct();
      date_default_timezone_set("Asia/Seoul");
      $this->load->library('session');
      $this->load->model('Log_m');
      $this->load->model('Log_a');
		$this->Log_a->exe_log('typical','search');
      $this->load->library('user_agent');
		if ($this->agent->is_mobile()) 
		   $this->is_mobile=1;
		else 
			$this->is_mobile=0;
    }

   private function is_mobile () {
    	return $this->is_mobile;
   }

    //index.php/Search/index 
	public function index() {
		$this->config->set_item('title','반세상 - 검색');

		$this->load->model('Collection_function_model');
		$search_info = $this->Collection_function_model->search_main();

		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/header_main_page');
			$this->load->view('MOBILE/menu/top_menu');
				$this->load->view('MOBILE/search/search_all_page', array("main_info"=> $search_info) );
			$this->load->view('MOBILE/footer_main_page');
			$this->load->view('MOBILE/end_page');

			return 0;
		}

		// print_r($search_info);
	
		$this->load->view('PC/start_page');
		$this->load->view('PC/header_main_page');
		$this->load->view('PC/menu/top_menu');
			$this->load->view('PC/search/search_all_page', array("main_info"=> $search_info) );
		$this->load->view('PC/footer_main_page');
		$this->load->view('PC/end_page');
	}

}
