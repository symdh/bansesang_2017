<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Manage_model extends CI_model { 
	function __construct(){ 
		parent::__construct(); 
		$this->db_manage = $this->load->database('manage', TRUE);
		date_default_timezone_set("Asia/Seoul");
	}

	public function user_permission_load () {
		if(!isset($_GET['pmt1'])) {
			$result[0][0] = 0;
			return $result;
		} else if (!is_numeric($_GET['pmt2'])) {
			$result[0][0] = 0;
			return $result;
		} 

		// $result =  $this->db_manage->query("SELECT * FROM member.user_info WHERE super_user BETWEEN -1 AND 1")->result_array();
		
		return $result;
	}
 
	//cap 관련
	public function manage_upload_write() {
		$this->load->model('Pchk_sys');
		$this->load->model('Vui_sys');

		if(!isset($_POST['write_type']) ) {
			sendback_site('reject_access','','',0,'main');
			return 0;
		} else if($_POST['write_type'] != 'notice' && $_POST['write_type'] != 'faq') {
			sendback_site('reject_access','','',0,'main');
			return 0;
		} else if(!isset($_POST['divide_type']) ) {
			sendback_site('reject_access','','',0,'main');
			return 0;
		} 
		$function_name =$this->db->escape($_POST['write_type']);
		$function_name = preg_replace('/\'/', '', $function_name);
		$title = $this->db->escape($this->Pchk_sys->call_title() );
		$content =  $this->db->escape($this->Pchk_sys->call_content() );
		if($title === 0) {sendback_site('reject_access','','',0,'main'); return 0;}
		if($content === 0) {sendback_site('reject_access','','',0,'main'); return 0;}
		if(!$this->Vui_sys->check_login()) { sendback_site('reject_access',$function_name , '',0, 'csc'); return 0; }
			
		$user_id =$this->db->escape($this->Vui_sys->call_user_id() );
		$writed_member = $this->db->escape($this->Vui_sys->call_trans_nick() );
		$passwd = $this->db->escape($this->Vui_sys->call_trans_pwd() );
		$id_check = mt_rand();
		$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']); 
		$_POST['divide_type'] = $this->db->escape($_POST['divide_type']);
		
				//외부값: 다수
		$this->db_manage->query("INSERT into csc.user{$function_name} (`ip_address`,`id_check`,`passwd`,`writed_member`,`user_id`,`title`,`content`, `divide_type`) values({$ip_address}, '".$id_check ."',{$passwd} ,{$writed_member} ,{$user_id} ,{$title} ,{$content} ,{$_POST['divide_type']})");
		
		redirect('/csc/'.$function_name);
	}

	//cap 관련
	public function manage_delete_write() {
		//db 위치(function_name), divide_type는 필요없고, read_id 있으면 됨
		//추가적으로 id_check확인 (로그인 검사는 컨트롤러에서 함)

		if(!isset($_POST['function_name'])) { sendback_site('reject_access', '', '', 0,'main'); return 0; }
		$function_name= $this->db->escape($_POST['function_name']);
		mb_internal_encoding('UTF-8'); 
		$function_name = mb_substr($function_name,1);
		$function_name = mb_substr($function_name,0,-1); 

		$this->load->model('Pchk_sys');
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0)  { sendback_site('reject_access',$function_name , '',0, 'csc'); return 0; } 

				//내부값: 없음    //외부값: 다수
		$data_w = $this->db_manage->query("SELECT * FROM csc.user{$function_name} WHERE read_id = {$read_id}")->result_array();

		//이미 삭제됬을 경우
		if(!isset($data_w[0])) { sendback_site('reject_access',$function_name , '',0, 'csc'); return 0; }

		//id 조작 여부를 확인
		if ($_POST['id_check']==$data_w[0]['id_check']){
					//내부값: 없음    //외부값: 다수
			$this->db_manage->query("DELETE from csc.user{$function_name} WHERE read_id = {$read_id}");

			//삭제 완료
			sendback_site('result_delete',$function_name , '',0, 'csc');
			return 0;
		} else {
			//삭제 불가
			sendback_site('reject_access',$function_name , '',0, 'csc');
			return 0;
		}
	}

	//cap 관련
	public function manage_modify_write() {
		$this->load->model('Pchk_sys');
		$this->load->model('Vui_sys');

		if(!isset($_POST['write_type']) ) {
			sendback_site('reject_access','','',0,'main');
			return 0;
		} else if($_POST['write_type'] != 'notice' && $_POST['write_type'] != 'faq') {
			sendback_site('reject_access','','',0,'main');
			return 0;
		} else if(!isset($_POST['divide_type']) ) {
			sendback_site('reject_access','','',0,'main');
			return 0;
		} 
		$function_name =$this->db->escape($_POST['write_type']);
		$function_name = preg_replace('/\'/', '', $function_name);
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id() );
		$title = $this->db->escape($this->Pchk_sys->call_title() );
		$content =  $this->db->escape($this->Pchk_sys->call_content() );
		if($read_id === 0) {sendback_site('reject_access','','',0,'main'); return 0;}
		if($title === 0) {sendback_site('reject_access','','',0,'main'); return 0;}
		if($content === 0) {sendback_site('reject_access','','',0,'main'); return 0;}
		if(!$this->Vui_sys->check_login()) { sendback_site('reject_access',$function_name , '',0, 'csc'); return 0; }

				//외부값: read_id, function_name
		$data_w = $this->db_manage->query("SELECT * FROM csc.user{$function_name} WHERE read_id = {$read_id}")->result_array();
		if(!isset($data_w[0]['read_id'])) { sendback_site('reject_access',$function_name , '',0, 'csc'); return 0; } //결과 값이 없으면 거부

		//id 조작 여부를 확인
		if ($_POST['id_check'] !=$data_w[0]['id_check']){ 
			sendback_site('reject_access',$function_name , '',0, 'csc');
			return 0;
		}

		//수정하는 날짜와 ip주소를 기록
		$server_date = date("Y-m-d H:i:s",time());
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$modify_info = $ip_address.'~'.$server_date.'/';
		$modify_info = $this->db->escape($modify_info);

				//내부값: 없음    //외부값: 다수
		$this->db_manage->query("UPDATE csc.user{$function_name} set modify_info = concat(ifnull(modify_info,''), {$modify_info}), title= {$title}, content = {$content} where read_id= {$read_id}");

		//수정완료
		sendback_site('result_modify',$function_name , '',0, 'csc');
	}

	//cap 관련
	public function upload_answer () {
		if(!isset($_POST['function_name'])) { sendback_site('reject_access', 'main', '', 0, 'manage/cap'); return 0; }
		$function_name= $this->db->escape($_POST['function_name']);
		mb_internal_encoding('UTF-8'); 
		$function_name = mb_substr($function_name,1);
		$function_name = mb_substr($function_name,0,-1); 
		
		$_POST['content'] = $_POST['answer_content'];
		$this->load->model('Pchk_sys');
		$content =  $this->db->escape($this->Pchk_sys->call_content() );
		if($content === 0) { sendback_site('reject_access', 'main', '', 0, 'manage/cap'); return 0; }
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0)  { sendback_site('reject_access', 'main', '', 0, 'manage/cap'); return 0; }

				//외부값: read_id, function_name
		$data_w = $this->db_manage->query("SELECT * FROM csc.{$function_name}_board WHERE read_id = {$read_id}")->result_array();

		//이미 답변 되어있으면 에러
		if ($data_w[0]['check_answer'] == 1){ 
			sendback_site('reject_access', 'read', $function_name, $read_id, 'manage/cap');
			return 0;
		}
		
		//id 조작 여부를 확인
		if ($_POST['id_check'] !=$data_w[0]['id_check']){ 
			sendback_site('reject_access', 'read', $function_name, $read_id, 'manage/cap');
			return 0;
		}
		
		//답변자과 답변날짜와 내용을 넣기위해 설정
		$user_nickname =  $this->session->userdata('nickname');
		$user_nickname = $this->db->escape($user_nickname);
		$server_date = date("Y-m-d H:i:s",time());
		$check_answer = 1; //답변완료로 변경

				//외부값: 다수
		$this->db_manage->query("UPDATE csc.{$function_name}_board set answer_nickname = {$user_nickname}, answer_content = {$content}, answer_date = '{$server_date}', check_answer = '{$check_answer}' where read_id= {$read_id}");

		//답변완료됬으므로 필요 답변수 하나 빼줌 (보류 답변일땐 제외)
		if($data_w[0]['check_answer'] != -1) {
					//외부값: function_name
			$this->db_manage->query("UPDATE csc.writed_info set need_answer = need_answer-1 where function_name='{$function_name}' ");
		}
		
		//다음 답변 이동을 위해 마지막 요소를 가져옴 
				//외부값: 다수
		$result = $this->db_manage->query("SELECT read_id FROM csc.{$function_name}_board WHERE read_id < {$read_id} AND check_answer = 0 ORDER BY read_id DESC LIMIT 1  ")->result_array();
		
			//답변할게 없을땐 list로 보냄
		if(!isset($result[0]['read_id'])) {
			sendback_site('result_progress', 'list', $function_name, 0, 'manage/cap');
			return 0;
		} else {
			//등록 완료
			sendback_site('result_regist', 'read', $function_name, $result[0]['read_id'], 'manage/cap');
			return 0;
		}

		return 0;
	}

	//cap 관련
	public function giveup_answer () {

		//넘어오는 내용을 검사함
		if (!isset($_POST['function_name']) || !isset($_POST['read_id'])) {
			sendback_site('reject_access', 'main', '', 0, 'manage/cap');
			return 0;
		} else if (!isset($_POST['defer']) && !isset($_POST['pass'])){
			//둘다 넘어오지 않으면 에러
			sendback_site('reject_access', 'read', $_POST['function_name'], $_POST['read_id'], 'manage/cap');
			return 0;
		}
		$function_name= $this->db->escape($_POST['function_name']);
		mb_internal_encoding('UTF-8'); 
		$function_name = mb_substr($function_name,1);
		$function_name = mb_substr($function_name,0,-1); 

		$this->load->model('Pchk_sys');
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0)  { return 0; } 

		if (isset($_POST['defer'])) {
			if($_POST['defer'] == 1) {
				//보류자 닉네임 입력
				$user_nickname =  $this->session->userdata('nickname');
				$user_nickname = $this->db->escape($user_nickname);
			
						//내부값: 없음    //외부값: 다수
				$this->db_manage->query("UPDATE csc.{$function_name}_board set defer_nickname = {$user_nickname}, check_answer = -1 where read_id= {$read_id}");

				//보류하므로 need_answer 하나를 빼줌
						//내부값: 없음    //외부값: 다수
				$this->db_manage->query("UPDATE csc.writed_info set need_answer = need_answer-1 where function_name='{$function_name}' ");
				
				//다음 답변 이동을 위해 마지막 요소를 가져옴 
						//내부값: 없음    //외부값: 다수
				$result = $this->db_manage->query("SELECT read_id FROM csc.{$function_name}_board WHERE read_id < {$read_id} AND check_answer = 0 ORDER BY read_id DESC LIMIT 1  ")->result_array();
		
				//답변할게 없을땐 list로 보냄
				if(!isset($result[0]['read_id'])) {
					sendback_site('result_progress', 'list', $function_name, 0, 'manage/cap');
					return 0;
				} else {
					//아니면 보류
					sendback_site('result_defer', 'read', $function_name, $result[0]['read_id'], 'manage/cap');
					return 0;
				}
			}
		} else if (isset($_POST['pass'])){
			if($_POST['pass'] == 1) { 
				//다음 답변 이동을 위해 마지막 요소를 가져옴 
						//내부값: 없음    //외부값: 다수
				$result = $this->db_manage->query("SELECT read_id FROM csc.{$function_name}_board WHERE read_id < {$read_id} AND check_answer = 0 ORDER BY read_id DESC LIMIT 1  ")->result_array();
		
					//답변할게 없을땐 list로 보냄
				if(!isset($result[0]['read_id'])) {
					sendback_site('result_progress', 'list', $function_name, 0, 'manage/cap');
					return 0;
				} else {
					//아니면 패스
					sendback_site('result_pass', 'read', $function_name, $result[0]['read_id'], 'manage/cap');
					return 0;
				}
			}
		}

		//값이 전달 안되면 값 변조한거임
		sendback_site('reject_access', 'read', $function_name, $read_id, 'manage/cap');
		return 0;
	}		

	//cap 관련
	public function report_answer () {

		//넘어오는 내용을 검사함
		if(!isset($_POST['type'])) {
			sendback_site('reject_access', 'main', '', 0, 'manage/cap');
			return 0;
		} else if (!isset($_POST['function_name']) ) {
			sendback_site('reject_access', 'main', '', 0, 'manage/cap');
			return 0;
		}

		$function_name= $this->db->escape($_POST['function_name']);
		mb_internal_encoding('UTF-8'); 
		$function_name = mb_substr($function_name,1);
		$function_name = mb_substr($function_name,0,-1); 

		//등록 answer 설정
		if($_POST['type'] == 'delete') {
			$_POST['answer_content'] = "삭제처리 되었습니다.";
			$do_delete = 1;
			$do_spam = 0;
		} else if ($_POST['type'] == 'cancel') {
			$_POST['answer_content'] = "신고취소 되었습니다.";
			$do_delete = 0;
			$do_spam = 0;
		} else if ($_POST['type'] == 'spam') {
			$_POST['answer_content'] = "스팸처리 되었습니다.";
			$do_delete = 0;
			$do_spam = 1;
		}

		$_POST['content'] = $_POST['answer_content'];
		$this->load->model('Pchk_sys');
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0)  { sendback_site('reject_access', 'main', '', 0, 'manage/cap'); return 0; } 
		$content =  $this->db->escape($this->Pchk_sys->call_content() );
		if($content === 0) { sendback_site('reject_access', 'main', '', 0, 'manage/cap'); return 0; }
	
				//외부값: read_id, function_name
		$data_w = $this->db_manage->query("SELECT * FROM csc.{$function_name}_board WHERE read_id = {$read_id}")->result_array();

		//이미 답변 되어있으면 에러
		if ($data_w[0]['check_answer'] == 1){ 
			sendback_site('reject_access', 'read', $function_name, $read_id, 'manage/cap');
			return 0;
		}

		if($data_w[0]['location'] == 'board' ) {
			$db_name = $data_w[0]['location'].'.'.$data_w[0]['pointer'].'_writed';
		} else if ($data_w[0]['location'] == 'minfo') {
			$db_name = $data_w[0]['location'].'.'.$data_w[0]['pointer'].'_board';
		} else {
			echo "에러 문의바람";
			return;
		}

		$db_name = $this->db->escape($db_name);
		$db_name = mb_substr($db_name,1);
		$db_name = mb_substr($db_name,0,-1); 
		
		//답변자과 답변날짜와 내용을 넣기위해 설정
		$user_nickname =  $this->session->userdata('nickname');
		$user_nickname = $this->db->escape($user_nickname);
		$server_date = date("Y-m-d H:i:s",time());
		$check_answer = 1; //답변완료로 변경

		if($do_delete) {
			//신고로 삭제 판단시

			//삭제시 3으로 업데이트
					//내부값: server_date, check_answer    //외부값: 다수
			$this->db_manage->query("UPDATE csc.{$function_name}_board set answer_nickname = {$user_nickname}, answer_content = {$content}, answer_date = '{$server_date}', check_answer = '{$check_answer}', answer_result = 3 where read_id = {$read_id}");
			//옮긴후 삭제
			$this->db_manage->query("INSERT INTO csc.report_result SELECT * FROM report_board where read_id = {$read_id}");
			$this->db_manage->query("DELETE from csc.report_board where read_id = {$read_id}");

			//원본글 삭제
			if(empty($data_w[0]['comment_id'])) { 
						//내부값: db_name   //외부값: 다수
				$this->db_manage->query("DELETE from {$db_name} where read_id = '{$data_w[0]['pointer_read_id']}' ");
			} else {
						//내부값: 다수   //외부값: 없음
				$this->db_manage->query("DELETE from {$data_w[0]['location']}.{$data_w[0]['pointer']}_comment where comment_id = '{$data_w[0]['comment_id']}' AND read_id = '{$data_w[0]['pointer_read_id']}' ");
			}

		} else if($do_spam) {
			//스팸처리시
			$server_date = date('y년 m월 d일 H시 i분 s초', strtotime('now'));
			
			if($data_w[0]['user_id'] == -1) { //(익명)
				$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);
						//내부값: 없음    //외부값: ip_address
				$result = $this->db_manage->query("SELECT * from collection_function.watch_limit_ip where ip = INET_ATON({$ip_address}) ")->result_array();

				$watch_log = '//'.'스팸신고처리로 '.$server_date.'에 영구차단 되었습니다.';
		
				if(empty($data_w[0]['title'])) 
					$data_w[0]['title'] = '(댓글신고)';

				if(isset($result[0])) {
					//이미 존재할때
					$watch_content = $result[0]['watch_content'].'%///% title:'.$data_w[0]['title'].'  content: '.$data_w[0]['content'];
					$watch_log = $result[0]['watch_log'].$watch_log;
					$watch_content = $this->db->escape($watch_content);
					$watch_log = $this->db->escape($watch_log);
							//내부값: 없음    //외부값: 다수
					$this->db_manage->query("UPDATE collection_function.watch_limit_ip set limit_time = 1, immoral_code = 40, watch_content = {$watch_content}, watch_log ={$watch_log} where ip = INET_ATON({$ip_address})");

				} else  {
					//처음 처리할때
				
					$watch_content = '%///% title:'.$data_w[0]['title'].'  content: '.$data_w[0]['content'];
					$watch_content = $this->db->escape($watch_content);
					$watch_log = $this->db->escape($watch_log);
							//내부값: 없음    //외부값: 다수
					$this->db_manage->query("INSERT INTO collection_function.watch_limit_ip (`ip`, `limit_time`, `immoral_code`, `watch_content`, `watch_log`) values( INET_ATON({$ip_address}), '1', '40', {$watch_content}, {$watch_log}) ");
				}

				
			} else { //(회원)
				//뎦어씌우기
				$watch_log = '//'.'스팸신고처리로 '.$server_date.'에 영구차단 되었습니다.';
				$watch_content = '%///% title:'.$data_w[0]['title'].'  content: '.$data_w[0]['content'];
				$watch_content = $this->db->escape($watch_content);
				$watch_log = $this->db->escape($watch_log);
				$data_w[0]['user_id'] = $this->db->escape($data_w[0]['user_id']);
						//내부값: 없음    //외부값: 다수
				$this->db_manage->query("UPDATE member.user_info set limit_time = '1', immoral_code = '40', watch_content = CONCAT(watch_content ,{$watch_content}), watch_log = CONCAT(watch_log ,{$watch_log}) where identification_id = {$data_w[0]['user_id']} "); 

			}

			//옮긴후 삭제
					//내부값: 없음    //외부값: 다수
			$this->db_manage->query("INSERT INTO csc.report_result SELECT * FROM report_board where read_id = {$read_id}");
			$this->db_manage->query("DELETE from csc.report_board where read_id = {$read_id}");

			//원본글 삭제
			if(empty($data_w[0]['comment_id'])) { 
						//내부값: 다수   //외부값: 없음
				$this->db_manage->query("DELETE from {$db_name} where read_id = '{$data_w[0]['pointer_read_id']}' ");
			} else {
						//내부값: 다수   //외부값: 없음
				$this->db_manage->query("DELETE from {$data_w[0]['location']}.{$data_w[0]['pointer']}_comment where comment_id = '{$data_w[0]['comment_id']}' AND read_id = '{$data_w[0]['pointer_read_id']}' ");
			}
		} else {
			//미삭제시 -1으로 업데이트
					//내부값: 다수   //외부값: 다수
			$this->db_manage->query("UPDATE csc.{$function_name}_board set answer_nickname = {$user_nickname}, answer_content = {$content}, answer_date = '{$server_date}', check_answer = '{$check_answer}', answer_result = -1 where read_id = {$read_id}");
			//옮긴후 삭제
			$this->db_manage->query("INSERT INTO csc.report_result SELECT * FROM report_board where read_id = {$read_id}");
			$this->db_manage->query("DELETE from csc.report_board where read_id = {$read_id}");

			//원본글 업데이트
			if(empty($data_w[0]['comment_id'])) {
						//내부값: 다수   //외부값: 다수
				$this->db_manage->query("UPDATE {$db_name} set report_result = {$data_w[0]['recent_state']} -1 where read_id = '{$data_w[0]['pointer_read_id']}' ");
			} else {
						//내부값: 다수   //외부값: 다수
				$this->db_manage->query("UPDATE {$data_w[0]['location']}.{$data_w[0]['pointer']}_comment set report_result = {$data_w[0]['recent_state']} -1 where comment_id = '{$data_w[0]['comment_id']}' AND read_id = '{$data_w[0]['pointer_read_id']}' ");
			}
		}

		//답변완료됬으므로 필요 답변수 하나 빼줌 (보류 답변일땐 제외)
		if($data_w[0]['check_answer'] != -1) {
					//내부값: 없음   //외부값: function_name
			$this->db_manage->query("UPDATE csc.writed_info set need_answer = need_answer-1 where function_name='{$function_name}' ");
		}
		
		//다음 답변 이동을 위해 마지막 요소를 가져옴 
				//내부값: 없음   //외부값: 다수
		$result = $this->db_manage->query("SELECT read_id FROM csc.{$function_name}_board WHERE read_id < {$read_id} AND check_answer = 0 ORDER BY read_id DESC LIMIT 1  ")->result_array();
		
			//답변할게 없을땐 list로 보냄
		if(!isset($result[0]['read_id'])) {
			sendback_site('result_progress', 'list', $function_name, 0, 'manage/cap');
			return 0;
		} else {
			sendback_site('result_regist', 'read', $function_name, $result[0]['read_id'], 'manage/cap');
			return 0;
		}

		return 0;
	}

	//cap 관련
	public function download_write($num, $function_name) {
		//수정도 여기서 불러오므로 채크
		$function_name= $this->db->escape($function_name);
		mb_internal_encoding('UTF-8'); 
		$function_name = mb_substr($function_name,1);
		$function_name = mb_substr($function_name,0,-1); 

		$_POST['read_id'] = $num;
		$this->load->model('Pchk_sys');
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id() );
		if($read_id ===0) { sendback_site('none', 'list', $function_name, 0, 'manage/cap'); return 0; }

				//외부값: function_name, read_id
		$data_w =  $this->db_manage->query("SELECT * FROM csc.{$function_name}_board WHERE read_id = {$read_id}")->result_array();
		if(!isset($data_w[0]['read_id'])) { sendback_site('none', 'list', $function_name, 0, 'manage/cap'); return 0; }

		$this->load->model('Norm_sys');
		foreach ($data_w as $key => $entry) {
			//신고일경우
			if(isset($data_w[$key]['modify_title'])) { //이거 왜해놨을꼬??
				//$data_w[$key]['modify_content'] = html_entity_decode($entry['modify_content'], ENT_QUOTES, 'UTF-8');
			}

			//익명인지 확인
			if($data_w[$key]['user_id'] == -1)
				$data_w[$key]['check_anony'] = 1;
			else 
				$data_w[$key]['check_anony'] = 0;
			
			$data_w[$key]['date'] = $this->Norm_sys->norm_time(strtotime($data_w[$key]['date']));
			$data_w[$key]['answer_date'] = $this->Norm_sys->norm_time(strtotime($data_w[$key]['answer_date']));

			//필요없는 정보 빼기
			unset($data_w[$key]['ip_address']);
			unset($data_w[$key]['modify_info'] );
			unset($data_w[$key]['passwd'] );
			unset($data_w[$key]['user_id'] );
			unset($data_w[$key]['attach_image'] );
		}


		return $data_w;
	}

}


require_once('./static/include/model/sendback_site.php');