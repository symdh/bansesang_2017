<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Log_m extends CI_model { //Log_member

	private $access_ip;  //ci 에서 유효성 검사를 해주네.
	private $current_time;
   private $access_os; 
	private $access_brs; //access browser
	private $wrt_content; //들어갈 내용.
	function __construct(){ 
		parent::__construct(); 
		// $this->db_minfo = $this->load->database('minfo', TRUE);
		date_default_timezone_set("Asia/Seoul");
		
		$this->load->model('Vui_sys');
		$this->db_member = $this->load->database('default', TRUE);

		$this->access_ip = $this->input->ip_address();
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

///////////////////////////////////시간 기록으로써, 생성	자에 넣는것이 바람직.
		if( $this->Vui_sys->call_user_id() == -1 ) //회원일 경우에만 진행
			return 1;

		//방문날짜 집계 (90일치), 유저 편의용
		$result = $this->db_member->query("SELECT `visit_lastday` FROM log_user_info where identification_id = {$this->Vui_sys->call_user_id()} ")->result_array();

		$query = $this->current_time;
		if(!isset($result[0]['visit_lastday']) || $result[0]['visit_lastday'] == '' ) {
			$query = $this->db->escape(strtotime(str_replace('.', '-', $query))); // 검색좋게 변환후 집어넣음
					//내부값: call_user_id, query    //외부값: 
			$this->db_member->query("INSERT INTO `log_user_info`  (`identification_id`, `visit_lastday`) VALUES ({$this->Vui_sys->call_user_id()}, {$query}) ON DUPLICATE KEY UPDATE visit_lastday = {$query} ");
		} else if(  $result[0]['visit_lastday'] < strtotime(date("Y-m-d",time())) ) { //이미 존재한다면 day가 오늘인지 비교
 			//오늘이 아니면 최신화
			$query = $this->db->escape(strtotime(str_replace('.', '-', $query))); // 검색좋게 변환후 집어넣음
			$query_1 = $this->db->escape(date("Y.m.d H:i:s",$result[0]['visit_lastday']).'/'); // 정규식 사용좋게 변환.
					//내부값: 전체    //외부값: 
			$this->db_member->query("UPDATE log_user_info set visit_day = concat ({$query_1}, visit_day ), visit_lastday = {$query} ");

		} else { //단순 오늘 방문자가 또 방문한것이라면
			$query = $this->db->escape(strtotime(str_replace('.', '-', $query ))); // 변환후 집어넣음
			$this->db_member->query("UPDATE log_user_info set visit_lastday = {$query} ");
		}
	}

	public function log_try_login ($identification_id) { 

		$identification_id = $this->db->escape($identification_id);
		$this->wrt_content = "로그인 시도";

		$query = $this->current_time."-".$this->access_ip."-".$this->access_os."-".$this->access_brs."-".$this->wrt_content."/";
		$query = $this->db->escape($query);		
			//내부값: call_user_id, query    //외부값: 
		$this->db_member->query("INSERT INTO `log_user_info`  (`identification_id`, `try_login`) VALUES ({$identification_id}, {$query}) ON DUPLICATE KEY UPDATE try_login = concat ({$query}, try_login ) ");
	} 

	//나중에 구축 (실시간 로그인 제어)
	public function add_login () {
		// $query = $this->current_time."-".$this->access_ip."-".$this->access_os."-".$this->access_brs."/";
		// $query = $this->db->escape($query);
		// 	//내부값: call_user_id, query    //외부값:
		// $this->db_member->query("INSERT INTO `log_user_info`  (`identification_id`, `license_login`) VALUES ({$this->Vui_sys->call_user_id()}, {$query}) ON DUPLICATE KEY UPDATE license_login = concat ({$query}, license_login ) ");
	}

	//나중에 구축 (실시간 로그인 제어)
	public function delete_login() {

	}

	public function log_user_info($wrt_content) { // $wrt_content 들어갈 내용
		$query = $this->current_time."-".$this->access_ip."-".$wrt_content."/";
		$query = $this->db->escape($query);		
			//내부값: call_user_id, query    //외부값: 
		$this->db_member->query("INSERT INTO `log_user_info`  (`identification_id`, `modify_user_info`) VALUES ({$this->Vui_sys->call_user_id()}, {$query}) ON DUPLICATE KEY UPDATE modify_user_info = concat ({$query}, modify_user_info ) ");
	}


}