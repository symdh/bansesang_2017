<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {
	
	private $is_mobile;

	function __construct() {       
      parent::__construct();
      date_default_timezone_set("Asia/Seoul");

      $this->load->library('user_agent');
      	if ($this->agent->is_mobile()) 
		   $this->is_mobile=1;
		else 
			$this->is_mobile=0;
   }

   private function is_mobile () {
    	return $this->is_mobile;
   }

	//index.php/member/join
	public function join() {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','member');

		if($this->session->userdata('logged_in'))  {
			echo "<script>alert('이미 로그인 되어 있습니다.')</script>";
			echo "<meta http-equiv='refresh' content='0; url=/main'>";
			return 0;
		}

		$this->config->set_item('title','회원가입');
		
		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/member/header_member_page');		
				$this->load->view('MOBILE/menu/top_menu');
				$this->load->view('MOBILE/member/page/join_member');
			$this->load->view('MOBILE/member/footer_member_page');
			$this->load->view('MOBILE/end_page');

			return 0;
		}

		$this->load->view('PC/start_page');
		$this->load->view('PC/member/header_member_page');		
			$this->load->view('PC/menu/top_menu');
			$this->load->view('PC/member/page/join_member');
		$this->load->view('PC/member/footer_member_page');
		$this->load->view('PC/end_page');
	}

	//index.php/member/certifyemail/$watch_log
	public function certifyemail($watch_log) {
		$this->load->model('Member_model');
		$this->Member_model->certification_member($watch_log);
	}

	//index.php/member/login
	public function login() {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','member');
		if($this->session->userdata('logged_in') )  {
			echo "<script>alert('이미 로그인 되어 있습니다.')</script>";
			echo "<meta http-equiv='refresh' content='0; url=/main'>";
			return 0;
		}

		$this->config->set_item('title','로그인');
		
		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/member/header_member_page');
				$this->load->view('MOBILE/menu/top_menu');
				$this->load->view('MOBILE/member/page/login_member');	
			$this->load->view('MOBILE/member/footer_member_page');
			$this->load->view('MOBILE/end_page');

			return 0;
		}

		$this->load->view('PC/start_page');
		$this->load->view('PC/member/header_member_page');
			$this->load->view('PC/menu/top_menu');
			$this->load->view('PC/member/page/login_member');	
		$this->load->view('PC/member/footer_member_page');
		$this->load->view('PC/end_page');
	}

	//index.php/member/userpage/$purpose
	public function userpage($purpose = 'userinfo') {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','member');
		if(!$this->session->userdata('logged_in')) {
				echo "<meta http-equiv='refresh' content='0; url=/member/login'>";
				return 0;
		} 

		$this->load->model('Member_model');
		$user_info = $this->Member_model->download_userinfo();

		if(!$user_info) 
			redirect('/main');


		$this->config->set_item('title','마이페이지');

		if($purpose == 'userinfo') {
			if($this->is_mobile()) {
				$this->load->view('MOBILE/start_page');
				$this->load->view('MOBILE/member/header_member_page');
					$this->load->view('MOBILE/menu/top_menu');
					$this->load->view('MOBILE/member/page/userinfo_member',array('user_info'=>$user_info));
				$this->load->view('MOBILE/member/footer_member_page');
				$this->load->view('MOBILE/end_page');
				
				return 0;
			}

			$this->load->view('PC/start_page');
			$this->load->view('PC/member/header_member_page');
				$this->load->view('PC/menu/top_menu');
				$this->load->view('PC/member/page/userinfo_member',array('user_info'=>$user_info));
			$this->load->view('PC/member/footer_member_page');
			$this->load->view('PC/end_page');

		} else if ($purpose == 'modifyuserinfo') {
			$this->Member_model->modify_userinfo();

		} else if ($purpose == 'userout') {

			if($this->is_mobile()) {
				$this->load->view('MOBILE/start_page');
				$this->load->view('MOBILE/member/header_member_page');
					$this->load->view('MOBILE/menu/top_menu');
					$this->load->view('MOBILE/member/page/userout_member', array('user_info'=>$user_info));
				$this->load->view('MOBILE/member/footer_member_page');
				$this->load->view('MOBILE/end_page');
				return 0;
			}

			$this->load->view('PC/start_page');
			$this->load->view('PC/member/header_member_page');
				$this->load->view('PC/menu/top_menu');
				$this->load->view('PC/member/page/userout_member', array('user_info'=>$user_info));
			$this->load->view('PC/member/footer_member_page');
			$this->load->view('PC/end_page');
		} else if ($purpose == 'modifyimg') {
			$this->Member_model->modify_user_image();
		} else if ($purpose == 'modifyIntro') {
			$this->Member_model->modify_user_intro();
		} else if ($purpose == 'modifypasswd') {
			$this->Member_model->modify_user_passwd();
		} else if ($purpose == 'useroutagree') {
			$this->Member_model->break_away_user();
		}
	}

	public function findid () {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','member');
		if($this->session->userdata('logged_in'))  {
			echo "<script>alert('이미 로그인 되어 있습니다.')</script>";
			echo "<meta http-equiv='refresh' content='0; url=/main'>";
			return 0;
		}

		$this->config->set_item('title','이메일 찾기');

		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/member/header_member_page');
				$this->load->view('MOBILE/menu/top_menu');
				$this->load->view('MOBILE/member/page/findid_member');
			$this->load->view('MOBILE/member/footer_member_page');
			$this->load->view('MOBILE/end_page');
			return 0;
		}

		$this->load->view('PC/start_page');
		$this->load->view('PC/member/header_member_page');
			$this->load->view('PC/menu/top_menu');
			$this->load->view('PC/member/page/findid_member');
		$this->load->view('PC/member/footer_member_page');
		$this->load->view('PC/end_page');
	}

	public function findpwd () {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','member');
		if($this->session->userdata('logged_in'))  {
			echo "<script>alert('이미 로그인 되어 있습니다.')</script>";
			echo "<meta http-equiv='refresh' content='0; url=/main'>";
			return 0;
		}

		$this->config->set_item('title','패스워드 찾기');

		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/member/header_member_page');
				$this->load->view('MOBILE/menu/top_menu');
				$this->load->view('MOBILE/member/page/findpwd_member');
			$this->load->view('MOBILE/member/footer_member_page');
			$this->load->view('MOBILE/end_page');
			return 0;
		}

		$this->load->view('PC/start_page');
		$this->load->view('PC/member/header_member_page');
			$this->load->view('PC/menu/top_menu');
			$this->load->view('PC/member/page/findpwd_member');
		$this->load->view('PC/member/footer_member_page');
		$this->load->view('PC/end_page');
	}

	public function trychangepwd($log) { //비밀번호 변경 페이지
		$this->load->library('session');
		$this->load->model('Log_m');
		if($this->session->userdata('logged_in'))  {
			echo "<script>alert('이미 로그인 되어 있습니다.')</script>";
			echo "<meta http-equiv='refresh' content='0; url=/main'>";
			return 0;
		}

		$this->load->model('Member_model');
		$data = $this->Member_model->certification_pwd($log, 0);

		$this->config->set_item('title','비밀번호 변경');

		if($this->is_mobile()) {
			$this->load->view('MOBILE/start_page');
			$this->load->view('MOBILE/member/header_member_page');
				$this->load->view('MOBILE/menu/top_menu');
				$this->load->view('MOBILE/member/page/trychangepwd_member', array('data'=>$data));
			$this->load->view('MOBILE/member/footer_member_page');
			$this->load->view('MOBILE/end_page');
			return 0;
		}

		$this->load->view('PC/start_page');
		$this->load->view('PC/member/header_member_page');
			$this->load->view('PC/menu/top_menu');
			$this->load->view('PC/member/page/trychangepwd_member', array('data'=>$data));
		$this->load->view('PC/member/footer_member_page');
		$this->load->view('PC/end_page');
	}

	public function changepwd() {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Member_model');
		$data = $this->Member_model->certification_pwd($_POST['log_c'], 1);
	}

	//index.php/member/trylogin 
	public function trylogin() {
		/////////////
		//로그인시도 할때 session load는 모델에서 시행
		/////////////
		$this->load->model('Member_model');
		$this->Member_model->try_login();
	}
	
	//index.php/member/trylogout 
	public function trylogout() {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','member');
		$this->load->model('Member_model');
		$this->Member_model->try_logout();
	}
	//index.php/member/checkemail (이메일 중복체크)
	public function checkemail() {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Member_model');
		$this->Member_model->check_email();
	}
	//index.php/member/checknickname (닉네임 중복체크)
	public function checknickname() {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Member_model');
		$this->Member_model->check_nickname();
	}
	//index.php/member/insertmember (회원가입 입력)
	public function insertmember() {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Member_model');
		$this->Member_model->insert_member();
	}
	public function tryfindid () {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','member');
		$this->load->model('Member_model');
		$this->Member_model->try_findid();
	}

	public function tryfindpwd () {
		$this->load->library('session');
		$this->load->model('Log_m');
		$this->load->model('Log_a');
		$this->Log_a->exe_log('typical','member');
		$this->load->model('Member_model');
		$this->Member_model->try_findpwd();
	}

}
