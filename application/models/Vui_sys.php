<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Vui_sys extends CI_model { //verify_user_infomation
	//기본적으로 session 값과 비교하는것을 원칙으로함

	private $user_id;  //유저id
	private $super_user; // 댓글의 경우 : 수정권한을 가진 유저

	private $trans_pwd; //받은 패스워드 or 회원의 경우엔 0
	private $trans_nick; //받은 닉네임  or 회원닉

	public $saved_id;  //db에 저장된 id
	public $saved_pwd; //db에 저장된 pwd
	
	function __construct(){ 
		parent::__construct(); 
		// $this->db_minfo = $this->load->database('minfo', TRUE);
		date_default_timezone_set("Asia/Seoul");
		
		$this->load->library('session');
		if($this->session->has_userdata('user_id')) {
			$this->user_id = $this->session->userdata('user_id');
			$this->super_user = $this->session->userdata('guitar_id');
			$this->trans_nick = $this->session->userdata('nickname');
			$this->trans_pwd = 0;
		} else {
			$this->user_id = -1;
			$this->super_user = 0;
		}
	}

	public function has_anony_val ($str_switch) { // 익명의 변수들 데이터 있는지 검증
		//1. 익명일때 pwd가 0이 아닌것으로 존재해야됨.
		//2. 익명일때 nick이 존재해야됨
		switch ($str_switch) {
			case 'pwd':
				if($this->call_user_id() == -1 && ($this->call_trans_pwd() == null ||  $this->call_trans_pwd() == '0') )   return 0;
			break;

			case 'nick':
				if($this->call_user_id() == -1 && $this->call_trans_nick() == null )  return 0;
			break;

			default:
				return 0;
		}
		return 1;
	}

	public function call_user_id () { //user_id 리턴
		return $this->user_id;
	}

	public function call_super_user () { //super_user 리턴
		return $this->super_user;
	}

	public function call_trans_pwd () { //trans_pwd 리턴
		return $this->trans_pwd;
	}

	public function call_trans_nick () { //trans_nick 리턴
		return $this->trans_nick;
	}


	private function compare_user_id () { //user_id, saved_id 일치여부
		if(!isset($this->saved_id)) return 0;

		if($this->call_user_id() == $this->saved_id) {
			return 1;
		} else {
			return 0;
		}
	}

	private function compare_user_pwd () { //trans_pwd, saved_pwd 일치여부
		if(!isset($this->saved_pwd)) return 0;
		if(!$this->has_anony_val('pwd')) return 0;

		if($this->call_trans_pwd() == $this->saved_pwd) {
			return 1;
		} else {
			return 0;
		}
	}

	//닉네임 검증후 저장
	public function trans_nick() {
		if(!isset($_POST['anony_nickname']) || $this->user_id != -1) {
			return 1; //익명 아니면 정상 진행
		}
		$trans_nick = $_POST['anony_nickname'];
	   //UTF-8로 할경우 3byte로 측정이됨 //한글, 영어 따로 측정이됨 (영어 1, 한글 2바이트) 
	   $check_len = strlen(iconv('UTF-8','CP949',$trans_nick)); 
	   if ($check_len < 3 || $check_len > 12) {
	      return 0;
	   } 
	   	//닉네임 특수문자 제한 
	   $special_char = ' ~!#$%^&*()-_=+|₩₩{}[];:"₩"<>,?₩/@.'; 
	   $len_special_char = mb_strlen($special_char, 'utf-8');
	   $len_trans_nick = mb_strlen($trans_nick, 'utf-8');
	   for($i = 0 ; $i < $len_trans_nick ; $i++){
	      for($j=0; $j < $len_special_char ;$j++) { 
	         if($special_char{$j} == $trans_nick{$i}) {
	            return 0;
	         }
	      }
	   }

	   $this->trans_nick = $trans_nick;
   		return 1;
	}

	//패스워드 검증후 저장
	public function trans_pwd($min_num = 3) { //저장할 pwd, 최소 길이	
		if(!isset($_POST['anony_passwd']) || $this->user_id != -1 ) {
			return 1; //익명 아니면 정상 진행
		}
		$trans_pwd = $_POST['anony_passwd'];
		if($min_num < 3 || $min_num > 19) 
			return 0; //파라매터 검사

		//길이제한 체크
	   $check_len = mb_strlen($trans_pwd, 'utf-8');
	   if ($check_len < $min_num || $check_len > 20) {
	      return 0;
	   }
	  	//비밀번호 띄어쓰기 및 텝 사용여부
	   $check__1 = explode(" ", $trans_pwd); 
	   $check__2 = explode("\t", $trans_pwd); 
	   if (count($check__1) > 1 || count($check__2) > 1) {
	      return 0;
	   }

	   $this->trans_pwd = $trans_pwd;
	   return 1;
	}

	//유저 동일성 검증 (회원: id /// 비회원: id, pwd)
	public function verify_board_user () {
		if(!$this->compare_user_id()) return 0;
		if($this->call_user_id() == -1) //비회원 일경우 패스워드도 확인
			if(!$this->compare_user_pwd()) return 0;
		return 1;
	}

	public function check_login () { //로그인 유무 확인 
		if($this->call_user_id() == -1) 
			return 0;
		else 
			return 1;
	}
	
}


//*****ex******//

/*
익명 불가 && 회원id 검증
		$this->Vui_sys->saved_id = $data_w[0]['user_id'];
		if(!$this->Vui_sys->check_login() || !$this->Vui_sys->verify_board_user() ) { sendback_site('reject_access', $this->function_name, 'read', $read); return 0; } //유저 검증
		$user_id =$this->db->escape($this->Vui_sys->call_user_id());

익명 가능 && 검증 (닉네임, 패스워드 검증)
	$this->load->model('Vui_sys');
	if(!$this->Vui_sys->trans_pwd() || !$this->Vui_sys->trans_nick() ||!$this->Vui_sys->has_anony_val('nick') || !$this->Vui_sys->has_anony_val('pwd')) { sendback_site('reject_access', $this->function_name, 'write'); return 0; } //유저 검증
	$user_id =$this->db->escape($this->Vui_sys->call_user_id() );
	$writed_member = $this->db->escape($this->Vui_sys->call_trans_nick() );
	$passwd = $this->db->escape($this->Vui_sys->call_trans_pwd() );

익명 가능 && 검증 (id, 패스워드 일치여부)
	$this->load->model('Vui_sys');
	$this->Vui_sys->saved_id = $data_w[0]['user_id'];
	$this->Vui_sys->saved_pwd = $data_w[0]['passwd'];	
	if(!$this->Vui_sys->trans_pwd()  || !$this->Vui_sys->verify_board_user()) { sendback_site('wrong_passwd', $this->function_name, 'read', $num); return 0; } //유저 검증

*/
