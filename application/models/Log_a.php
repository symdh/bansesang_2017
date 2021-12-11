<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Log_a extends CI_model { //Log_All (멤버 제외)

	private $table_lnfo; //저장할 위치 table_info (생성자)
	private $access_ip;  //ci 에서 유효성 검사를 해주네.
	private $current_time;
   private $access_os; 
	private $access_brs; //access browser
	private $wrt_content; //들어갈 내용.

	function __construct($type_log = 0){ 
		parent::__construct(); 
		// $this->db_minfo = $this->load->database('minfo', TRUE);
		date_default_timezone_set("Asia/Seoul");
		
		$this->load->model('Vui_sys');
		$this->db_log = $this->load->database('site_log', TRUE);

		$this->access_ip = ip2long($this->input->ip_address());
		if($this->access_ip == 0 ) { //form 검사용으로 하나 만들것
			//시스템 로그에 넣어야함 
		}

		$this->current_time = date("Y.m.d H:i:s",time()); 

		$this->load->library('user_agent');
		$this->access_brs = $this->agent->browser();
		// echo "<br>버전:".$this->agent->version();
		// echo "<br>로봇:".$this->agent->robot();

		if($this->agent->is_mobile()) {
			$this->access_os =  $this->agent->mobile();
		} else {
			$this->access_os = $this->agent->platform();
		}

//시간 변화에 따른 검사. (나중에 할것)
///////////////////////////////////시간 기록으로써, 생성	자에 넣는것이 바람직.
	// 	$awd =strtotime(date("Y-m-d H:i:s",time())); 
	// 	$ip = ip2long($this->access_ip);
	// ////	// $awd = $this->db->escape(strtotime(str_replace('.', '-', $this->current_time))); // 검색좋게 변환후 집어넣음
	// 	$this->db_member->query("INSERT INTO site_log.`anony_log`  (`ip`,`time`) VALUES ('{$ip}' , '{$awd}') ON DUPLICATE KEY UPDATE time = {$awd} ");
		
	}

	public function exe_log($type_log, $page_info = '') {  // page_info 형식 : board/free (숫자 포함x)
		switch ($type_log) {
			case 'typical':
				if( $this->Vui_sys->call_user_id() == -1 ) { //비회원일 경우
					$query = $this->db->escape($this->access_ip);
							//내부값:		//외부값: $query
					$result = $this->db_log->query("SELECT * FROM frd_log where iporuser = {$query} LIMIT 0,1")->result_array();

					if( isset($result[0]['num']) ) {
						$this->table_info = 'frd_log';
					} else { //새로운 사용자
						$this->table_info = 'anony_log';
					}

				} else { //회원일 경우
					$this->table_info = 'mmb_log';
				}

				$this->typical_log($page_info);
			break;

			case 'update_static':
				$this->update_typical_static();
			break;

		}
	}

	//page 정보 넣어야함
	private function typical_log($page_info) {  
		if( $this->Vui_sys->call_user_id() == -1 ) { //비회원일 경우
			$iporuser =$this->db->escape($this->access_ip);
		} else {
			$iporuser =$this->db->escape($this->Vui_sys->call_user_id());
		}
		
		$standard_time = $this->db->escape(strtotime(date("Y-m-d",time())));
			//내부값: $this->table_info	, $standard_time, $iporuser		//외부값: $iporuser
		$result = $this->db_log->query("SELECT * FROM {$this->table_info} where iporuser = {$iporuser} AND time > {$standard_time} ")->result_array();
	
		$current_time = $this->db->escape(strtotime(date("Y-m-d H:i:s",time())));

		//page 비교, os 비교, browser 비교
		if(isset($result[0]['num'])) {
			//os
			if( preg_match("/{$this->access_os}/", $result[0]['os']) ) {
				$os = $this->db->escape($result[0]['os']);
			} else {
				$os = $this->db->escape($result[0]['os'].'/'.$this->access_os);
			}

			//browser
			if( preg_match("/{$this->access_brs}/", $result[0]['browser']) ) {
				$browser = $this->db->escape($result[0]['browser']);
			} else {
				$browser = $this->db->escape($result[0]['browser'].'/'.$this->access_brs);
			}

			//page
			if( $result[0]['last_page'] == $page_info ) { //처음저장시 first_page, last_page 저장 시키므로
				//view 증가 후 끝
				$is_newpage = 0;
				$num_currentview = $result[0]['num_currentview'] + 1;

			}  else { 
				$is_newpage = 1;
				$connected_page = $this->db->escape($result[0]['connected_page'].$result[0]['last_page'].$result[0]['num_currentview'].'-');
				$last_page = $this->db->escape($page_info);
				$num_allview = $this->db->escape($result[0]['num_allview']+$result[0]['num_currentview']); 
				$num_currentview = $this->db->escape(1);
			}

		}	else {
			$os = $this->db->escape($this->access_os);
			$browser = $this->db->escape($this->access_brs);
			$first_page = $this->db->escape($page_info);
			$last_page = $this->db->escape($page_info);
		}	

		if(isset($result[0]['num'])) { //최초 접속 시간만 일단 넣자.
			if($is_newpage) {
						//내부값:num_allview,num_currentview 		//외부값: last_page, connected_page, 
				$this->db_log->query("UPDATE {$this->table_info} set os = {$os}, browser = {$browser}, last_page = {$last_page}, connected_page = {$connected_page}, num_allview = {$num_allview}, num_currentview = {$num_currentview} where iporuser = {$iporuser} AND time > {$standard_time} ");
			} else {
						//내부값:num_currentview 		//외부값: 
				$this->db_log->query("UPDATE {$this->table_info} set os = {$os}, browser = {$browser}, num_currentview = {$num_currentview} where iporuser = {$iporuser} AND time > {$standard_time} ");
			}
		} else { //처음 insert
					//내부값:current_time 		//외부값: iporuser, os, browser, first_page, last_page
			$this->db_log->query("INSERT INTO {$this->table_info} (`iporuser`, `time`, `os`, `browser`, `first_page`, `last_page`) VALUES ({$iporuser}, {$current_time}, {$os}, {$browser}, {$first_page}, {$last_page} ) ");
		}
	}

	public function update_typical_static () { //typical 동기화도 진행.

		$result[0]['day'] = 0; //테스트
		$standard_time = 100000000000; //테스트

		//일단 typical update. 
				//내부값: 전체
		$this->db_log->query("UPDATE anony_log set connected_page = concat(connected_page, last_page, num_currentview,'-'), num_allview = num_allview + num_currentview, num_currentview = 0 where time > {$result[0]['day']} AND time < {$standard_time} AND  num_currentview != 0");

		return 0;

		//중간에 비어있는 날짜 검사
		$result = $this->db_log->query("SELECT max(day) as day FROM static_log  ")->result_array();
		// print_r($result);
		$standard_time = strtotime(date("Y-m-d",time())); //다음날 실행할테니 그전날 자정 전까지 통계구함
		$from_num = $standard_time - $result[0]['day'] + 86400; // 사이값을 구하기위해 1일 더함
		$from_num = $static_day/86400 ;
		for ($i = 0 ; $i < $from_num; $i++) { 
			$result[0]['day'] = $result[0]['day'] + 86400;
			$standard_time = $result[0]['day'] + 86400;

			//통계 sql 넣으면 됨.
		}

	} 

	public function success_login () {
		$query = $this->db->escape($this->access_ip);
		$this->db_log->query("INSERT INTO frd_log SELECT * FROM anony_log where iporuser = {$query} ");
		$this->db_log->query("DELETE FROM anony_log WHERE  iporuser = {$query}");

	}

	public function log_user_info() { 

	}


}