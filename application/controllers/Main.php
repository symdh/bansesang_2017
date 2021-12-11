<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	
	private $is_mobile;

	function __construct() {       
      parent::__construct();
      date_default_timezone_set("Asia/Seoul");
      $this->load->library('session');
    	$this->load->model('Log_m');
    	$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','main');

      $this->load->library('user_agent');
		if ($this->agent->is_mobile()) 
		   $this->is_mobile=1;
		else 
			$this->is_mobile=0;
    }

   private function is_mobile () {
    	return $this->is_mobile;
   }

    //index.php/main/index 
	public function index() {
		$this->config->set_item('title','반세상 - 반려동물세상에 오신걸 환영합니다.');

		$this->load->model('Collection_function_model');
		$main_info = $this->Collection_function_model->get_main_info();

		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/header_main_page');
			$this->load->view('MOBILE/menu/top_menu');
				$this->load->view('MOBILE/main_page', array("main_info"=> $main_info) );
			$this->load->view('MOBILE/footer_main_page');
			$this->load->view('MOBILE/end_page');

			return 0;
		}

		$this->load->view('PC/start_page');
		$this->load->view('PC/header_main_page');
		$this->load->view('PC/menu/top_menu');
			$this->load->view('PC/main_page', array("main_info"=> $main_info) );
		$this->load->view('PC/footer_main_page');
		$this->load->view('PC/end_page');
	}

	//이벤트 테스트
	//index.php/main/test
	public function test() {
		// 업로드 테스트 ㄱㄱ
		// echo "됩니다";
		// $this->load->model('Collection_function_model');
		// $this->Collection_function_model->update_main_info();

		// $this->load->model('Log_m');
		// $this->Log_m->log_try_login();
		// $this->Log_m->log_user_info(2);
		// $this->Log_m->log_visit_day();
		// (2017\.10\.27[^\/]+)

		// $var[0] = "abcd";
		// $var[0][1] = "abcd";
		// echo $var[0][1];
		// print_r($var);

		// $this->load->model('Spam_sys');

		// 	print_r($this->session->all_userdata()); //유저 데이터 전체 출력
	 		

	}

	public function test2(){
		// $this->load->model('Vui_sys');
		// if( 1 == 0) {
		// 	echo "됨";
		// } else {
		// 	echo "안됨";
		// }
		// $test_time = strtotime (date("Y-m-d",time() ) );
		// $test_time = $test_time + 86400;
		// echo $test_time;
		// $test_time = date("Y-m-d",strtotime ("+1 days"));
		// echo $test_time;
		// $test_time = strtotime($test_time);
		// echo $test_time;
			// $query =strtotime(date("Y-m-d H:i:s",time())); //시간 gap이 있지만 1~2초라 노상관
			// echo $query;
			// $query_1 = date("Y.m.d H:i:s",$query);
			// echo $query_1;

		// $this->load->model('Log_a');
		// $this->Log_a->exe_log('typical','board/goo123123d');
		// $var = 'aa';
		// if(isset($var[1])) {
		// 	echo "씨발";
		// } else {
		// 	echo "됨";
		// }


		// $this->session->set_userdata('b_report_time', 100); 
		// $this->session->set_userdata('b_spam_time', 100); 
	}

	 //////////////본인 정보 열람 test용.
	public function test3() {

		// $this->load->library('session');
	 	// print_r($_COOKIE['ci_sessions']);
	 	// print_r($this->session->all_userdata()); //유저 데이터 전체 출력
	 	// 		$this->session->unset_userdata('w_report'); 
			// 	$this->session->unset_userdata('w_report_n'); 
				// $this->session->unset_userdata('w_spam_comment_n'); 
				// $this->session->unset_userdata('w_spam_comment'); 
	// 	// unset($_COOKIE['ci_sessions']);
	// 	// setcookie('ci_sessions', 1, strtotime('now')-3600,'/' , 'url');
	// 	// $this->config->set_item('sess_cookie_name','ci_session_2');
	// 	// $this->load->library('session');
	// 	// print_r($this->session->all_userdata());
	// 	// print_r($_COOKIE['ci_sessions']);
	// 	//print_r($this->config->item('sess_expiration')); //전역변수 출력
	// 	//session_destroy();
	// 	//unset($this->session); // 불가
	// 		// $this->session = null; // 불가
	// 	// $this->session = new CI_Sessions;  //불가
		 // if($this->session->has_userdata('user_id')) {
		 // 	echo "됨";
		 // 	echo $this->session->has_userdata('user_id');
		 // } else {
		 // 	echo "안됨";
		 // 	echo $this->session->has_userdata('user_id');
		 // 	echo "ㅅㅂㅂ?";
		 // }

	 }


}

