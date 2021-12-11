<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Pchk_sys extends CI_model { //post로 넘어오는 값 체크

	private $read_id;
	private $comment_id;
	private $title;
	private $content;
	private $group_1_id ; //댓글 관련
	private $send_member; //보내는 맴버

	private $ci_r ;  //암호화 넘어온값
	private $md5_result;  //암호화 결과
	private $md5_salt1; //암호화 salt1
	private $md5_salt2 ; //암호화 salt2

	function __construct(){ 
		parent::__construct(); 
		date_default_timezone_set("Asia/Seoul");

		if(!isset($_POST['title']) ) {
			$this->title = 0;
		} else {
			$this->title = $_POST['title'];
		}

		if(!isset($_POST['content'])) {
			$this->content = 0;
		} else {
			$this->content = $_POST['content'];
		}

		if(!isset($_POST['read_id'])) {
			$this->read_id = 0;
		} else {
			$this->read_id = (int)$_POST['read_id'];
		}

		if(!isset($_POST['comment_id'])) {
			$this->comment_id = 0;
		} else {
			$this->comment_id = (int)$_POST['comment_id'];
		}

		if(!isset($_POST['group_1_id'])) {
			$this->group_1_id = 0;
		} else {
			$this->group_1_id = (int)$_POST['group_1_id'];
		}

		if(!isset($_POST['send_member'])) {
			$this->send_member = 0;
		} else {
			$this->send_member = $_POST['send_member'];
		}

		if(!isset($_POST['ci_r'])) {
			$this->ci_r = 0;
		} else {
			$this->ci_r = $_POST['ci_r'];
		}

		if($this->session->has_userdata('hash_salt1')) {
			$this->md5_salt1 = $this->session->userdata('hash_salt1');
			$this->md5_salt2 = '';
			$this->md5_result = 0;
		} else {
			$this->md5_salt1 = 0;
			$this->md5_salt2 = 0;
			$this->md5_result = 0;
		}

	}
	public function call_title ($min_len = 2) {

		if($this->title === 0) {
			return 0;
		}

		$check_title = preg_replace('/\s/', '', $this->title);
		$title_length = mb_strlen($this->title, "utf-8");
		if ($title_length < $min_len) {
			return 0;
		}

		$this->title = htmlspecialchars($this->title, ENT_QUOTES);
		return $this->title;
	}

	public function call_content () {
		if($this->content === 0) {
			return 0;
		}

		//따옴표와 테그 처리때문에 사용
		require_once('./static/include/model/set_purifier.php');
		$this->content = str_replace("\r\n", "",$this->content);
		$this->content= $purifier->purify($this->content);
		return $this->content;
	}

	public function call_read_id () {
		if($this->read_id === 0) {
			return 0;
		}

		return $this->read_id;
	}

	public function call_comment_id () {
		if($this->comment_id === 0) {
			return 0;
		}

		return $this->comment_id;
	}

	public function call_group_1_id () {
		if($this->group_1_id === 0) {
			return 0;
		}

		return $this->group_1_id;
	}

	public function call_send_member () {
		if($this->send_member === 0) {
			return 0;
		}

		return $this->send_member;
	}

	//암호화
	public function md5_encrypt ($data) {

		if($this->md5_salt1 === 0 || $this->md5_salt2 === 0)
			return 0;

		//$md5_salt2는 임시. 필요시 세션에 추가해서 아래 규칙에 추가할것
		$md5_passwd_code = "4!".$data."@3";
		
		//echo $md5_salt;
		$md5_passwd_code = $this->md5_salt1.$md5_passwd_code;

		$this->md5_result = md5($md5_passwd_code);
		return $this->md5_result;
	}

	public function md5_check () {
		if($this->ci_r === 0 || $this->md5_result === 0 ) {
			return 0;
		}

		if($this->ci_r  == $this->md5_result )
			return 1;
		else 
			return 0;
	
	}


}

