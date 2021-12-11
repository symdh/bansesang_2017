<?php  defined('BASEPATH') OR exit('No direct script access allowed');
// CSC customer service center

class Csc_model extends CI_model { 
	function __construct(){ 
		parent::__construct(); 
		$this->db_csc = $this->load->database('csc', TRUE);
		date_default_timezone_set("Asia/Seoul");
	}

	public function set_function_name ($function_name) {
		$this->function_name = $function_name;
		
		//외부값 들어올수 있는 가능성이 확인됨
		$this->function_name= $this->db->escape($this->function_name);
    	mb_internal_encoding('UTF-8'); 
      $this->function_name = mb_substr($this->function_name,1);
      $this->function_name = mb_substr($this->function_name,0,-1); 
	}

	public function call_function_name () {
		return $this->function_name;
	}

	//page의 리스트 출력
	public function gets($setUp_pageNum) {
		//$setUp_page = page list 에 몇개의 글을 출력할지
      
		//페이지 값이 없으면 설정해줌
		if(!is_numeric($setUp_pageNum) ) return 0;
		if(!isset($_GET['page']) || !is_numeric($_GET['page']) ) $_GET['page'] = 1;
		if(!isset($_GET['listvary'])) $_GET['listvary'] = 'need';
		if( $_GET['listvary'] == 'need') {
			//여기 수정시 아래 else 부분도 수정할것
					//외부값: 없음
			$result = $this->db_csc->query("SELECT * FROM {$this->function_name}_board WHERE check_answer = 0")->result_array();
		} else if ( $_GET['listvary'] == 'all') {

			$start_index = ($_GET['page']-1)*$setUp_pageNum;
			$start_index = (int)$start_index;
					//외부값: start_index
			$result = $this->db_csc->query("SELECT * FROM {$this->function_name}_board LIMIT {$start_index}, {$setUp_pageNum}" )->result_array();
			
		} else if ( $_GET['listvary'] == 'defer' ) {
					//외부값: 없음
			$result = $this->db_csc->query("SELECT * FROM {$this->function_name}_board WHERE check_answer = -1")->result_array();
		
		} else {
			//이상한거 썻을경우 need 처럼 행동
					//외부값: 없음
			$result = $this->db_csc->query("SELECT * FROM {$this->function_name}_board WHERE check_answer = 0")->result_array();
		}


		$this->load->model('Norm_sys');
			//필요없는 정보 빼기 (사실 노출이 안되서 필요없긴한데 그냥 해주자..)
		foreach ($result as $key => $entry) {
			//익명글인지 검사함 
			if ($result[$key]['user_id'] == -1)
				$result[$key]['is_anony'] = 1;
			else  
				$result[$key]['is_anony'] = 0;

			$result[$key]['date'] = $this->Norm_sys->norm_time(strtotime($result[$key]['date']));

			unset($result[$key]['ip_address']);
			unset($result[$key]['modify_info'] );
			unset($result[$key]['passwd'] );
			unset($result[$key]['user_id'] );
			unset($result[$key]['content'] );
		}

		return $result;
	}
	
	//글 갯수 정보 가져옴 (writed_info) - 페이징 시스템
	public function gets_info ($setUp_pageNum, $setUp_groupNum, $for_manage = 0) {
		//$setUp_pageNum = 위 gets 와 동일하게
		//$setUp_groupNum = 한 group에 몇개를 나타낼지

		if($for_manage == '1') {
			//관리 메뉴(필요 답변갯수)를 위한 설정
					//내부값: 없음   //외부값: 없음
			$result = $this->db_csc->query("SELECT * FROM writed_info")->result_array();

		} else {
					//외부값: 없음
			$result = $this->db_csc->query("SELECT * FROM writed_info WHERE function_name = '{$this->function_name}' ")->result_array();
			
			if($result[0]['load_count']> 5) { //csc는 5로고정 (이용률이 낮음)
						//외부값: 없음
				$this->db_csc->query("UPDATE writed_info set load_count = 1 WHERE function_name = '{$this->function_name}' ");
				$writed_info = $this->db_csc->query("SELECT count(*) as writed_count FROM {$this->function_name}_board")->result_array();
				$count_page  = ceil($writed_info[0]['writed_count']/$setUp_pageNum); //페이지의 총 갯수를 구함
				$count_group  = ceil($count_page/$setUp_groupNum); //그룹의 총 갯수를 구함
				$this->db_csc->query("UPDATE writed_info set writed_count = ".$writed_info[0]['writed_count'].", count_page =".$count_page." , count_group = ".$count_group." WHERE function_name = '{$this->function_name}'");
				$result = $this->db_csc->query("SELECT * FROM writed_info WHERE function_name = '{$this->function_name}' ")->result_array(); //갱신된 정보를 다시 불러옴
			} else {
						//외부값: 없음
				$this->db_csc->query("UPDATE writed_info set load_count = load_count + 1 WHERE function_name = '{$this->function_name}'"); 
			}
		}

		//변수 설정부 데이터까지 같이 보냄
		foreach ($result as $key => $entry) {
			$result[$key]['setUp_pageNum'] = $setUp_pageNum;	
			$result[$key]['setUp_groupNum'] = $setUp_groupNum;
			unset($result[$key]['load_count']);
			unset($result[$key]['writed_count']);
		}

		return $result;
	}

	//num글이 몇번째 페이지인지 알아냄
	public function get_page($num) {
		if(!is_numeric($num)) return 0;
		$num = (int)$num;

				//외부값:, num
		$result = $this->db_csc->query("SELECT count(*) as count FROM {$this->function_name}_board WHERE read_id > {$num}")->result_array();
		return $result;
	}

	//집어넣은 read_id 값 반환
	private function get_insert_id () {
		$result =  $this->db_csc->query("SELECT LAST_INSERT_ID()")->result_array();
		return $result[0]['LAST_INSERT_ID()'];
	}

	public function download_notice($divide_type, $limit_num, $read_id = 0) {
		// limit_num 은 몇개 뽑아올 것이지 (나중에 페이징 시스템으로 만들어야됨)

		$divide_type = $this->db->escape($divide_type);
		$read_id = (int)$read_id;

		//글 다운과 목록 다운을 구분
		if(!$read_id) {
					//외부값: 다수
			$data_w =  $this->db_csc->query("SELECT * FROM user{$this->function_name} WHERE divide_type= {$divide_type} LIMIT 0, {$limit_num} ")->result_array();
		
		} else {
					 //외부값: 다수
			$data_w =  $this->db_csc->query("SELECT * FROM user{$this->function_name} WHERE divide_type= {$divide_type} AND read_id = '{$read_id}' LIMIT 0, {$limit_num} ")->result_array();
		}

		//결과 값이 없으면 거부
		if(!isset($data_w[0]['read_id'])) {
			sendback_site('none', 'notice', '', 0, 'csc');
			return 0;
		}

		$this->load->model('Norm_sys');
		foreach ($data_w as $key => $entry) {

			$data_w[$key]['date'] = $this->Norm_sys->norm_time(strtotime($data_w[$key]['date']));

			//필요없는 정보 빼기
			unset($data_w[$key]['ip_address']);
			unset($data_w[$key]['modify_info'] );
			unset($data_w[$key]['passwd'] );
			unset($data_w[$key]['user_id'] );
			unset($data_w[$key]['attach_image'] );

			//패치내역은 글 다운할때 내용도 같이감
			if($divide_type != '\'patch\'' && $read_id == '0') 
				unset($data_w[$key]['content']);

		}

		return $data_w;
	}

	public function upload_write () {

		$this->load->model('Img_sys');
		$this->load->model('Pchk_sys');
		$this->load->model('Vui_sys');

		$title = $this->db->escape($this->Pchk_sys->call_title() );
		$content =  $this->db->escape($this->Pchk_sys->call_content() );
		if($title === 0) {sendback_site('reject_access', 'write', '', 0, 'csc'); return 0;}
		if($content === 0) {sendback_site('reject_access', 'write', '', 0, 'csc'); return 0;}

		if(!$this->Vui_sys->trans_pwd() || !$this->Vui_sys->trans_nick() ||!$this->Vui_sys->has_anony_val('nick') || !$this->Vui_sys->has_anony_val('pwd')) { sendback_site('reject_access', 'write', '', 0, 'csc'); return 0; } //유저 검증
		if(!isset($_POST['attach_image'])) $_POST['attach_image'] = $this->Img_sys->extract_img($_POST['content']); //사진없으면 content에서 추출
		$attach_image = $this->db->escape('');
		if(isset($_POST["attach_image"][0]))  //파일 첨부가 있으면 처리
			$attach_image = $this->db->escape($this->Img_sys->save_img($_POST["attach_image"]));
		if($attach_image === 0)  {
			echo "<script>alert('잘못된 접근입니다.');</script>";
			echo "<script>window.location = '/csc/write';</script>";
			return 0;
		}

		$id_check = mt_rand();
		$user_id =$this->db->escape($this->Vui_sys->call_user_id() );
		$writed_member = $this->db->escape($this->Vui_sys->call_trans_nick() );
		$passwd = $this->db->escape($this->Vui_sys->call_trans_pwd() );
		$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);

				//외부값: 다수		
		$this->db_csc->query("INSERT into {$this->function_name}_board (`ip_address`,`id_check`,`passwd`,`writed_member`,`user_id`,`title`,`content`,`attach_image`) values({$ip_address},'".$id_check ."',{$passwd} ,{$writed_member} ,{$user_id} ,{$title} ,{$content} ,{$attach_image} )");
				//외부값: 없음 
		$this->db_csc->query("UPDATE writed_info set need_answer = need_answer + 1 WHERE function_name = '{$this->function_name}' ");

		echo "<script>alert('접수 완료 되었습니다.');</script>";
		echo "<script>window.close(); </script>";
		echo "완료";
	}

	//유저 문의 내역 리스트 받아옴
	public function user_list () {
		$this->load->model('Vui_sys');

		$check_login = $this->session->userdata('logged_in');
		//신고 글일경우 로그인 안하면 거부 
		if($this->function_name == 'report' && (!isset($check_login) || $check_login != 1) ) {
			return 0;
		}

		if(isset($check_login) && $check_login == 1) {
			$_POST['check_login'] = 3242;  //아무변수나 집어 넣어두됨

			//로그인 되어있으면 user_id로 검사
			$user_id =  $this->session->userdata('user_id');
			if($this->function_name != 'report') {
				$user_id = $this->db->escape($user_id);
						//내부값: 없음    //외부값: 다수
				$data_w =  $this->db_csc->query("SELECT * FROM {$this->function_name}_board WHERE user_id = {$user_id}")->result_array();
			} else {
				//신고일경우
				$pattern_id = "[/]{$user_id}[*]"; // mysql 정규식 이상함 ㅡㅡ
				$pattern_id = $this->db->escape($pattern_id);
				$data_w =  $this->db_csc->query("SELECT comment_id, reporter_info, answer_date, answer_result, check_answer FROM {$this->function_name}_board WHERE reporter_info regexp {$pattern_id} ")->result_array();
				$data_w_rs =  $this->db_csc->query("SELECT comment_id, reporter_info, answer_date, answer_result, check_answer  FROM {$this->function_name}_result WHERE reporter_info regexp {$pattern_id} ")->result_array();
				//print_r($data_w_rs);
			} 

		} else { //익명일 경우
			if(!$this->Vui_sys->trans_pwd() || !$this->Vui_sys->trans_nick() ||!$this->Vui_sys->has_anony_val('nick') || !$this->Vui_sys->has_anony_val('pwd')) {
				// echo "<script>alert('잘못된 접근입니다.');</script>";
				// echo "<script>window.close();</script>";
				//정보 없을경우 정보 창으로 넘어감 그래서 close안함
				return 0;
			} //유저 검증
			$writed_member = $this->db->escape($this->Vui_sys->call_trans_nick() );
			$passwd = $this->db->escape($this->Vui_sys->call_trans_pwd() );
					//외부값: 다수
			$data_w =  $this->db_csc->query("SELECT * FROM {$this->function_name}_board WHERE user_id = -1 AND writed_member = binary({$writed_member}) AND binary passwd = binary({$passwd}) ")->result_array();
		}

		$this->load->model('Norm_sys');
		foreach ($data_w as $key => $entry) {
			
			if($this->function_name != 'report') {

				//익명인지 확인
				if($data_w[$key]['user_id'] == -1)
					$data_w[$key]['check_anony'] = 1;
				else 
					$data_w[$key]['check_anony'] = 0;
				
				$data_w[$key]['date'] = $this->Norm_sys->norm_time(strtotime($data_w[$key]['date']));

				//필요없는 정보 빼기 (리스트만 받아감)
				unset($data_w[$key]['content'] );
				unset($data_w[$key]['answer_content'] );
				unset($data_w[$key]['ip_address']);
				unset($data_w[$key]['modify_info'] );
				unset($data_w[$key]['passwd'] );
				unset($data_w[$key]['user_id'] );
				unset($data_w[$key]['attach_image'] );
			} else {
				$pattern_id = "/~([^\/]+)\/{$user_id}\*/i";
				preg_match_all($pattern_id ,$data_w[$key]['reporter_info'] ,$matches_1); 
				if(isset($matches_1[1][0]))
					$data_w[$key]['reporter_info'] = $matches_1[1][0];
				//print_r($matches_1);

				//답변시간 null 일시
				if(is_null($data_w[$key]['answer_date'])) {
					$data_w[$key]['answer_date'] = 0;
				}
			}
			
		}

		if($this->function_name == 'report') {
			foreach ($data_w_rs as $key => $entry) {
				//위와 동일하게 구성
				$pattern_id = "/~([^\/]+)\/{$user_id}\*/i";
				preg_match_all($pattern_id ,$data_w_rs[$key]['reporter_info'] ,$matches_1); 
				//print_r($matches_1);
				if(isset($matches_1[1][0]))
					$data_w_rs[$key]['reporter_info'] = $matches_1[1][0];

			}
			//배열 합침
			$data_w = array_merge($data_w, $data_w_rs);
		}
		return $data_w;
	}

	public function download_user_write($num) {

		//수정도 여기서 불러오므로 채크
		if(!is_numeric($num)) return 0;
		$num = (int)$num;

				//내부값: 없음    //외부값: 다수
		$data_w =  $this->db_csc->query("SELECT * FROM {$this->function_name}_board WHERE read_id = '{$num}' ")->result_array();

		if(!isset($data_w[0]['read_id'])) {
			sendback_site('none', "confirmanswer?listvary={$this->function_name}", '', 0, 'csc');
			return 0;
		}

		//모든 검정이 통과되면 에러없음
		$check_login = $this->session->userdata('logged_in');
		if(isset($check_login) && $check_login == 1) {
			$_POST['check_login'] = 3242;  //아무변수나 집어 넣어두됨

			//로그인 되어있으면 user_id로 검사
			$user_id = $this->session->userdata('user_id');

			if($data_w[0]['user_id'] != $user_id) {
				sendback_site('reject_access', "confirmanswer?listvary={$this->function_name}", '', 0, 'csc');
				return;
			}
		} else { //익명일 경우
			if(!$this->Vui_sys->trans_pwd() || !$this->Vui_sys->trans_nick() ||!$this->Vui_sys->has_anony_val('nick') || !$this->Vui_sys->has_anony_val('pwd')) {
				sendback_site('reject_access', "confirmanswer?listvary={$this->function_name}", '', 0, 'csc');
				return 0;
			} //유저 검증
			$writed_member = $this->db->escape($this->Vui_sys->call_trans_nick() );
			$passwd = $this->db->escape($this->Vui_sys->call_trans_pwd() );
			if($data_w[0]['writed_member'] != $this->Vui_sys->call_trans_nick() || $data_w[0]['passwd'] != $this->Vui_sys->call_trans_pwd()) {
				sendback_site('reject_access', "confirmanswer?listvary={$this->function_name}", '', 0, 'csc');
				return 0;
			} 
		}

		$this->load->model('Norm_sys');

		foreach ($data_w as $key => $entry) {
			//날짜 미리 변경해줌
			$data_w[$key]['date'] = $this->Norm_sys->norm_time(strtotime($data_w[$key]['date']));
			$data_w[$key]['answer_date'] = $this->Norm_sys->norm_time(strtotime($data_w[$key]['answer_date']));

			//익명인지 확인
			if($data_w[$key]['user_id'] == -1)
				$data_w[$key]['check_anony'] = 1;
			else 
				$data_w[$key]['check_anony'] = 0;
			
			//필요없는 정보 빼기
			unset($data_w[$key]['ip_address']);
			unset($data_w[$key]['modify_info'] );
			unset($data_w[$key]['passwd'] );
			unset($data_w[$key]['user_id'] );
			unset($data_w[$key]['attach_image'] );
		}
		//print_r($data_w);
		//return 0;

		$data_w = json_encode($data_w, JSON_PRETTY_PRINT);
		print_r($data_w);
	}
}

require_once('./static/include/model/sendback_site.php');