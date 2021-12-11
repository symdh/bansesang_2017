<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Csc extends CI_Controller {
// CSC customer service center

	private $is_mobile;

	function __construct() {       
      parent::__construct();
      date_default_timezone_set("Asia/Seoul");
      $this->load->library('session');
      $this->load->model('Log_m');
      $this->load->model('Log_a');
		$this->Log_a->exe_log('typical','csc');


      $this->load->library('user_agent');
       if ($this->agent->is_mobile()) 
		   $this->is_mobile=1;
		else 
			$this->is_mobile=0;

      $this->config->set_item('title','고객센터');
      $this->load->model('Csc_model');
    } 

   private function is_mobile () {
    	return $this->is_mobile;
   }

   public function redirect ()  {

		//운영자 이상이면 redirect
		$check_login = $this->session->userdata('logged_in');
		$super_user =  $this->session->userdata('guitar_id');
		
		if(isset($check_login) && $check_login == 1 && $super_user >= 2 ) {
			redirect('/manage/cap/main');
		} else 
			redirect('/csc/notice');

	} 

	// //function_name 제한
	private function access_function ($function_name)  {
		//function_name 제한 둘것
		if($function_name !== 'proposal' && $function_name !== 'abug' && $function_name !== 'report' && $function_name !== 'amember' && $function_name !== 'etcetera') {
			echo "<script>alert('잘못된 접근입니다.');</script>";
			echo "<script>window.location = '/main';</script>";
			return 0;
		}  else {
			$this->Csc_model->set_function_name($function_name);
		}
		
		return 1;
	}

   	public function faq() {
   		$this->load->model('Csc_model');
   		$this->Csc_model->set_function_name('faq');
		$data_w = $this->Csc_model->download_notice(0, 10);

		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/csc/header_csc_page');
				$this->load->view('MOBILE/csc/user_menu');
				$this->load->view('MOBILE/csc/user_faq', array("data_w"=>$data_w));
			$this->load->view('MOBILE/csc/footer_csc_page');
			$this->load->view('MOBILE/end_page');
			return 0;
		}
		
   		$this->load->view('PC/start_page');
		$this->load->view('PC/csc/header_csc_page');
			$this->load->view('PC/csc/user_menu');
			$this->load->view('PC/csc/user_faq', array("data_w"=>$data_w));
		$this->load->view('PC/csc/footer_csc_page');
		$this->load->view('PC/end_page');
   	}


   	public function notice($divide_type = 'none', $num=0) {
   		$this->load->model('Csc_model');
   		$this->Csc_model->set_function_name('notice');

   		if(!$num) {
			$data_w_ntc = $this->Csc_model->download_notice('notice', 10);
			$data_w_ptc = $this->Csc_model->download_notice('patch', 10);
		} else {
			//글 다운
			if($divide_type != 'notice' && $divide_type != 'patch') {	
				echo "<script>alert('잘못된 접근입니다.');</script>";
				echo "<script>window.location = '/main';</script>";
				return 0;
			}

			$data_w = $this->Csc_model->download_notice($divide_type, 1,$num);
		}
		
		$check_login = $this->session->userdata('logged_in');
		$super_user =$this->session->userdata('guitar_id');
		//읽기 페이지가 아니고 운영자이면 알림등록된 공지 리스트 가져옴
		if(!$num &&  isset($check_login) && $check_login == 1 && $super_user > 1 )  {
			$this->load->model('Collection_function_model');
			$sblst_info = $this->Collection_function_model->get_sblst_info('csc');
		} else 
			$sblst_info = 0;
		
		if($this->is_mobile()) {

			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/csc/header_csc_page');
				$this->load->view('MOBILE/csc/user_menu');

				if(!$num) {
					$this->load->view('MOBILE/csc/user_notice', array("data_w_ntc"=>$data_w_ntc, "data_w_ptc"=>$data_w_ptc, "sblst_info"=>$sblst_info));
				} else {
					$this->load->view('MOBILE/csc/user_notice_read', array("data_w"=>$data_w) );
				} 
				
			$this->load->view('MOBILE/csc/footer_csc_page');
			$this->load->view('MOBILE/end_page');
			return 0;
		}
		
   		$this->load->view('PC/start_page');
		$this->load->view('PC/csc/header_csc_page');
			$this->load->view('PC/csc/user_menu');

			if(!$num) {
				$this->load->view('PC/csc/user_notice', array("data_w_ntc"=>$data_w_ntc, "data_w_ptc"=>$data_w_ptc, "sblst_info"=>$sblst_info));
			} else {
				$this->load->view('PC/csc/user_notice_read', array("data_w"=>$data_w) );
			} 

		$this->load->view('PC/csc/footer_csc_page');
		$this->load->view('PC/end_page');
   	}

    //index.php/csc/write
	public function write() {

		//게시판 규칙 따라 간거임
		$function_name = 'csc';
		$class_name = 'csc';

		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/csc/header_csc_page');
			$this->load->view('MOBILE/board/header_board_page');
			// $this->load->view('MOBILE/menu/top_menu');
				$this->load->view('MOBILE/csc/user_menu');
				$this->load->view('MOBILE/board/write_editor/editor.php', array("function_name" => $function_name,"class_name" => $class_name));
			$this->load->view('MOBILE/board/footer_board_page');
			$this->load->view('MOBILE/csc/footer_csc_page');
			$this->load->view('MOBILE/end_page');

			return 0;
		}


		//쓰기부분
		$this->load->view('PC/start_page');
		$this->load->view('PC/csc/header_csc_page');
		$this->load->view('PC/board/header_board_page');
		//$this->load->view('PC/menu/top_menu');
			$this->load->view('PC/csc/user_menu');
			$this->load->view('PC/board/write_page', array("function_name" => $function_name));
			$this->load->view('PC/board/write_editor/editor_header_1', array("function_name" => $function_name));
			$this->load->view('PC/board/write_editor/toolber_2');
			$this->load->view('PC/board/write_editor/editor_main_3');
			$this->load->view('PC/board/write_editor/fileupload_4');
			$this->load->view('PC/board/write_editor/editor_script_5', array("class_name" => $class_name));
		$this->load->view('PC/board/footer_board_page');
		$this->load->view('PC/csc/footer_csc_page');
		$this->load->view('PC/end_page');
	}

	//index.php/csc/confirmanswer 
	public function confirmanswer() {
		//기본 페이지 설정
		if(!isset($_GET['listvary'])) $_GET['listvary'] = 'proposal';
		
		if(!$this->access_function($_GET['listvary']))  { return 0; }
		
		$this->load->model('Csc_model');
		$data_w_list = $this->Csc_model->user_list();
		
		//user 확인 하는거임
		if($this->is_mobile()) { 
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/csc/header_csc_page');
				$this->load->view('MOBILE/csc/user_menu');
				$this->load->view('MOBILE/csc/user_confirm', array("data_w_list"=>$data_w_list));
			$this->load->view('MOBILE/csc/footer_csc_page');
			$this->load->view('MOBILE/end_page');
			return 0;
		}

		$this->load->view('PC/start_page');
		$this->load->view('PC/csc/header_csc_page');
			$this->load->view('PC/csc/user_menu');
			$this->load->view('PC/csc/user_confirm', array("data_w_list"=>$data_w_list));
		$this->load->view('PC/csc/footer_csc_page');
		$this->load->view('PC/end_page');
	}

	//index.php/csc/userread/$function_name/$num
	public function userread($function_name,$num) {
		//ajax 처리
		if(!$this->access_function($function_name))  { return 0; }
		
		$this->load->model('Csc_model');
		//양수로 되어있으므로 불로오기위해 음수로 바꿈
	 	$num = -$num;
	 	$this->Csc_model->download_user_write($num);
	}

	//index.php/csc/userreport
	public function userreport() {
		$this->load->model('Report_sys');
		$this->Report_sys->user_report();
	}


	//index.php/csc/uploadwrite
	public function uploadwrite () {
		if(!$this->access_function($_POST['write_type']))  { return 0; }
		
		//유저 글 업로드
		$this->load->model('Csc_model');
		$this->Csc_model->upload_write();
	}

}
