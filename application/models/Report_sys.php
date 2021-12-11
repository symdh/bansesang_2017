<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Report_sys extends CI_model { //spam 관리

	private $server_date;

	//데이터 추가시 처리 부분 (언급되지 않은 private 부분, 언급되지 않은 public)
	private $immoral_code; 
	private $b_spam_en;
	private $b_report_en; 


	//b_report_user = 1로 통일 			//b_report_time = n로 통일
	private $b_spam_user;  //스팸차단일시 신고 차단
	private $b_report_user; //이미 신고차단 유저인지 확인
	private $w_report_n; //신고 갯수
	private $w_report; //신고 배열 
	private $watch_num = 3; //몇개의 w_report_n마다 스팸내역 존재 하는지 검사.

	private $limit_time;
	private $is_passTime; //제한 시간 넘겼는지 확인 (넘겼으면 1)
									     		
	

	function __construct(){ 
		parent::__construct(); 
		$this->db_collect_func = $this->load->database('collect_func', TRUE);
		date_default_timezone_set("Asia/Seoul");

		$this->load->model('Vui_sys');
		if($this->session->has_userdata('b_spam_user')) {
			$this->b_spam_user = $this->session->userdata('b_spam_user');
		} else {
			$this->b_spam_user = 0;
		}

		if($this->session->has_userdata('b_report_user')) {
			$this->b_report_user = $this->session->userdata('b_report_user');
		} else {
			$this->b_report_user = 0;
		}

		$this->server_date = strtotime('now');
		if($this->session->has_userdata('b_report_time')) {
			$this->limit_time =  $this->session->userdata('b_report_time');
			if( $this->limit_time - $this->server_date < 0)
				$this->is_passTime = 1;
			else 
				$this->is_passTime = 0;
		} else { //스팸 유저면 어차피 신고도 불가.

			$this->limit_time = 0;
		 	$this->is_passTime = 0;
		}

		if($this->session->has_userdata('w_report_n')) 
			$this->w_report_n = $this->session->userdata('w_report_n');
		else 
			$this->w_report_n = 0;

		if($this->session->has_userdata('w_report')) 
			$this->w_report = $this->session->userdata('w_report');
		else 
			$this->w_report = array();
	}


	private function interpret_code($code = 0) {
		//$this->interpret_code($result[0]['immoral_code']);

		if($code == 0) {
			$this->b_spam_en = 0;
			$this->b_report_en = 0;
		} else {
			$this->b_spam_en = $code[0];
			if(isset($code[1]))
				$this->b_report_en = $code[1];
			else 
				$this->b_report_en = 0;
		}
	}


	private function set_immoral_code () {
		$this->immoral_code = $this->b_spam_en.$this->b_report_en;
	}


	private function unset_session () { //세션에서 스팸 해제
		$this->session->unset_userdata('b_report_user'); 
		$this->session->unset_userdata('b_report_time'); 
	}


	private function call_DBdata () { //db에서 정보 불러옴
		if($this->Vui_sys->check_login()) { //회원시
			$user_id = $this->db->escape($this->Vui_sys->call_user_id());
					//외부값: 없음
			$result = $this->db_collect_func->query("SELECT * from member.user_info where identification_id = {$user_id} ")->result_array();
		} else { // 익명시 처리
			$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);					
					//외부값: ip_address
			$result = $this->db_collect_func->query("SELECT * from collection_function.watch_limit_ip where ip = INET_ATON({$ip_address}) ")->result_array();
		}

		if(isset($result[0]['immoral_code'])) 
			$this->interpret_code($result[0]['immoral_code']);
		else 
			$this->interpret_code();

		if(!isset($result[0]['limit_time'])) 
			$result[0]['limit_time'] = 0;

		return $result;
	}


	private function update_DBdata () { //시간 update
		if($this->Vui_sys->check_login()) {
			$user_id = $this->db->escape($this->Vui_sys->call_user_id());
					//외부값: 없음
			$this->db_collect_func->query("UPDATE member.user_info set limit_time = '{$this->limit_time}' where identification_id = {$user_id} ");
		}
		else {
			$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);					
					//외부값: ip_address
			$this->db_collect_func->query("UPDATE collection_function.watch_limit_ip set limit_time = '{$this->limit_time}' where ip = INET_ATON({$ip_address})  ");
		}
	}


		//신고 처리
	public function user_report() {
		$this->load->model('Pchk_sys');
		$this->load->model('Vui_sys');
		if(!$this->watch_report()) return 0; 


		if(!isset($_POST['function_name']) || !isset($_POST['class_name']) ) {
			echo '잘못된 접근';
			return 0;
		}	
		$comment_id = $this->db->escape($this->Pchk_sys->call_comment_id());
		$function_name = $_POST['function_name'];
		$class_name = $_POST['class_name'];
		if($class_name == 'board' && $comment_id === 0 ) {
			$db_name = $class_name.'.'.$function_name.'_writed';
		} else if ($class_name == 'minfo' && $comment_id === 0 ) {
			$db_name = $class_name.'.'.$function_name.'_board';
		} else if ($comment_id !== 0 )  {
			$db_name = $class_name.'.'.$function_name.'_comment';
		} else {
			echo "에러 문의바람";
			return;
		}

		$function_name = $this->db->escape($function_name);
		mb_internal_encoding('UTF-8'); 
		$function_name = mb_substr($function_name,1);
		$function_name = mb_substr($function_name,0,-1); 

		$class_name = $this->db->escape($class_name);
		mb_internal_encoding('UTF-8'); 
		$class_name = mb_substr($class_name,1);
		$class_name = mb_substr($class_name,0,-1); 

		$db_name = $this->db->escape($db_name);
		mb_internal_encoding('UTF-8'); 
		$db_name = mb_substr($db_name,1);
		$db_name = mb_substr($db_name,0,-1); 


		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0)  { echo "잘못된 접근"; return 0; } 
		if($comment_id === 0) { //검증
			$this->Pchk_sys->md5_encrypt($read_id);
			if(!$this->Pchk_sys->md5_check()) { echo "잘못된 접근"; return 0; }
			$where = "read_id = {$read_id}";
		} else {
			$result = $this->Pchk_sys->md5_encrypt($comment_id);
			if(!isset($_POST["ci_c_{$comment_id}"]) || $result != $_POST["ci_c_{$comment_id}"]) {
				echo "잘못된 접근";
				return 0;
			}	
			unset($result); 
			$where = "comment_id = {$comment_id}";
		}

				//외부값: 다수
		$result = $this->db_collect_func->query("SELECT * from {$db_name} where {$where} ")->result_array();
		if(!isset($result[0]['report_result'])) { echo "알림. 이미 삭제됬습니다."; return; }	
		if($result[0]['report_result'] != 2 && $result[0]['report_result'] != 3) {
					//외부값: 다수
			$this->db_collect_func->query("UPDATE {$db_name} set report_result = 1 where {$where} ");
		} else if ($result[0]['report_result'] == 2) { //이미신고 누적되어 있으면 거부
			echo "신고 처리 진행중입니다.";
			return 0;
		}
		if(!isset($result[0]['comment_id'])) $result[0]['comment_id']= 0; //글의 경우 null일수있음

		//reporter_info 정의
		$user_id =$this->db->escape($this->Vui_sys->call_user_id() );
		$reporter_ip_address = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d H:i:s");
		$reporter_info = $reporter_ip_address.'~'.$date.'/'.$user_id.'*';
		$reporter_info = $this->db->escape($reporter_info);

		if($user_id == -1 ) {
			$reporter_num = 1;
			$reporter_weight = 1;
		} else {
			$reporter_num = 100;
			$reporter_weight = 100;
		}

		$event_num = 0;
		//본인글 본인이 신고했는지 검증
		if($reporter_ip_address == $result[0]['ip_address']) {
			echo "본인 글 신고불가";
			return 0;
		} else if ($user_id != -1 && $user_id == $result[0]['user_id']) {
			echo "본인 글 신고불가";
			return 0;
		}
				//외부값: 없음
		$savad_report = $this->db_collect_func->query("SELECT * from csc.report_board where pointer_read_id = '{$result[0]['read_id']}' AND comment_id = '{$result[0]['comment_id']}' AND pointer = '{$function_name}' ")->result_array();

		if(isset($savad_report[0]['read_id'])) {
			// 이미 신고 저장되어 있는 경우

					//이미 신고했는지 검증 (정규식)
			if($user_id == -1 ) {
				$pattern_ip = "/{$reporter_ip_address}~/i";
				preg_match_all($pattern_ip ,$savad_report[0]['reporter_info'] ,$matches_1); 
			} else {
				$pattern_ip = "/{$reporter_ip_address}~/i";
				$pattern_id = "/\/{$user_id}\*/i";
				preg_match_all($pattern_ip ,$savad_report[0]['reporter_info'] ,$matches_1); 
				preg_match_all($pattern_id ,$savad_report[0]['reporter_info'] ,$matches_2); 
			}
			if(isset($matches_1[0][0]) || isset($matches_2[0][0]) ) {
				echo "이미 신고하셨습니다.";
				return 0;
			}

					//weight 검증 (회원, 비회원 나눔)
			if($savad_report[0]['reporter_num']%100 > 99 && $reporter_num == 1) {
				$reporter_num = 0;
			} else if((int)$savad_report[0]['reporter_num']/100 > 99 && $reporter_num == 100) {
				$reporter_num = 0;
			}
			if($savad_report[0]['reporter_weight']%100 > 99 && $reporter_weight == 1) {
				$reporter_weight = 0;
			} else if((int)$savad_report[0]['reporter_weight']/100 > 99 && $reporter_weight == 100) {
				$reporter_weight = 0;
			}

					//reporter_info 갱신
			$reporter_info = $savad_report[0]['reporter_info'].$reporter_info;
			$reporter_info = $this->db->escape($reporter_info);
				//수정했는지 검증
			if(isset($result[0]['modify_info']) && $savad_report[0]['modify_info'] != $result[0]['modify_info']) {
				$is_modify = 1;
			} else {
				$is_modify = 0;
			}


				//weight 검증, 수정 글일시 행동 (개발 노트) 
			if($savad_report[0]['recent_state'] != 2) {
				$member_weight = 2;
				$anony_weight = 1;
				$result_weight = ($savad_report[0]['reporter_num']%100)*$anony_weight+($savad_report[0]['reporter_num']/100)*$member_weight;
			
				if((int)$result_weight > 6) { //8을 원하면 6으로, 10을 원하면 8로 설정해야됨
					//원본글에도 업데이트 필요
					//댓글,글 구분 필요함
					if($comment_id === 0) {  
						//글일경우 수정한경우와 안했을경우를 나눔
						if($is_modify) {
							$result[0]['title'] = $this->db->escape($result[0]['title']);
							$result[0]['content'] = $this->db->escape($result[0]['content']);
							$result[0]['attach_image'] = $this->db->escape($result[0]['attach_image']);
									//내부값: reporter_num, savad_report[0]['read_id'] 외 다수   //외부값: reporter_info 외 다수
							$this->db_collect_func->query("UPDATE csc.report_board set recent_state = 2, reporter_num = reporter_num+{$reporter_num}, reporter_info = {$reporter_info}, modify_title = {$result[0]['title']}, modify_content = {$result[0]['content']}, modify_attach_image = {$result[0]['attach_image']} where read_id = '{$savad_report[0]['read_id']}'");
						} else {
									//내부값: reporter_num, savad_report[0]['read_id']   //외부값: reporter_info 
							$this->db_collect_func->query("UPDATE csc.report_board set recent_state = 2, reporter_num = reporter_num+{$reporter_num}, reporter_info = {$reporter_info} where read_id = '{$savad_report[0]['read_id']}'");
						}
								//내부값: db_name   //외부값: _POST['read_id'] 
						$this->db_collect_func->query("UPDATE {$db_name} set report_result = 2 where read_id = {$read_id} ");
	
					} else { //댓글일 경우
								//외부값: reporter_info 외 다수
						$this->db_collect_func->query("UPDATE csc.report_board set recent_state = 2, reporter_num = reporter_num+{$reporter_num}, reporter_info = {$reporter_info} where read_id = '{$savad_report[0]['read_id']}'");
						$this->db_collect_func->query("UPDATE {$db_name} set report_result = 2 where comment_id = {$comment_id} AND read_id = {$read_id}");
					}

				} else {
					//이 경우 댓글,글 구분 필요없음
					if($is_modify && $comment_id === 0) {  
						$result[0]['title'] = $this->db->escape($result[0]['title']);
						$result[0]['content'] = $this->db->escape($result[0]['content']);
						$result[0]['attach_image'] = $this->db->escape($result[0]['attach_image']);
								//외부값: 다수
						$this->db_collect_func->query("UPDATE csc.report_board set reporter_num = reporter_num+{$reporter_num}, reporter_weight = reporter_weight+{$reporter_weight}, reporter_info = {$reporter_info}, modify_title = {$result[0]['title']}, modify_content = {$result[0]['content']}, modify_attach_image = {$result[0]['attach_image']} where read_id = '{$savad_report[0]['read_id']}'");
					} else {
								 //외부값: 다수
						$this->db_collect_func->query("UPDATE csc.report_board set reporter_num = reporter_num+{$reporter_num}, reporter_weight = reporter_weight+{$reporter_weight}, reporter_info = {$reporter_info} where read_id = '{$savad_report[0]['read_id']}'");
					}
				}
			} else {
				//신고누적 후에는 검증할 필요없음
				//댓글,글 구분 필요없음
				if($is_modify &&  $comment_id === 0) {  
					$result[0]['title'] = $this->db->escape($result[0]['title']);
					$result[0]['content'] = $this->db->escape($result[0]['content']);
					$result[0]['attach_image'] = $this->db->escape($result[0]['attach_image']);
							//내부값: reporter_num, savad_report[0]['read_id'] 외 다수   //외부값: reporter_info 외 다수
					$this->db_collect_func->query("UPDATE csc.report_board set reporter_num = reporter_num+{$reporter_num}, reporter_info = {$reporter_info}, modify_title = {$result[0]['title']}, modify_content = {$result[0]['content']}, modify_attach_image = {$result[0]['attach_image']} where read_id = '{$savad_report[0]['read_id']}'");
				} else {
							//내부값: reporter_num, savad_report[0]['read_id']   //외부값: reporter_info
					$this->db_collect_func->query("UPDATE csc.report_board set reporter_num = reporter_num+{$reporter_num}, reporter_info = {$reporter_info} where read_id = '{$savad_report[0]['read_id']}'");
				}
			}	
		} else {
			//처음 신고한 경우
			$reporter_info = $this->db->escape($reporter_info);
			$id_check = mt_rand();

			if(isset($_POST['comment_id'])) { 
				$result[0]['ip_address'] = $this->db->escape($result[0]['ip_address']);
				$result[0]['writed_member'] = $this->db->escape($result[0]['writed_member']);
				$result[0]['content'] = $this->db->escape($result[0]['content']);

				$set_var = "(`location`,`pointer`,`pointer_read_id`,`comment_id`,`reporter_info`,`reporter_num`,`reporter_weight`,`event_num`,`ip_address`,`date`,`writed_member`,`user_id`,`content`,`recent_state`)";
				$set_value = "values('{$class_name}','{$function_name}','{$result[0]['read_id']}','{$result[0]['comment_id']}',{$reporter_info},'{$reporter_num}','{$reporter_weight}','{$event_num}',{$result[0]['ip_address']},'{$result[0]['date']}',{$result[0]['writed_member']},'{$result[0]['user_id']}',{$result[0]['content']},'{$result[0]['report_result']}' )";
						//내부값: 다수    //외부값: 다수
				$this->db_collect_func->query("INSERT into csc.report_board {$set_var} {$set_value}");
			} else {

				$result[0]['ip_address'] = $this->db->escape($result[0]['ip_address']);
				$result[0]['modify_info'] = $this->db->escape($result[0]['modify_info']);
				$result[0]['writed_member'] = $this->db->escape($result[0]['writed_member']);
				$result[0]['title'] = $this->db->escape($result[0]['title']);
				$result[0]['content'] = $this->db->escape($result[0]['content']);
				$result[0]['attach_image'] = $this->db->escape($result[0]['attach_image']);

				$set_var = "(`location`,`pointer`,`pointer_read_id`,`reporter_info`,`reporter_num`,`reporter_weight`,`event_num`,`ip_address`,`date`,`modify_info`,`writed_member`,`title`,`user_id`,`content`,`attach_image`,`recent_state`)";
				$set_value = "values('{$class_name}','{$function_name}','{$result[0]['read_id']}',{$reporter_info},'{$reporter_num}','{$reporter_weight}','{$event_num}',{$result[0]['ip_address']},'{$result[0]['date']}',{$result[0]['modify_info']},{$result[0]['writed_member']},{$result[0]['title']},'{$result[0]['user_id']}',{$result[0]['content']},{$result[0]['attach_image']},'{$result[0]['report_result']}')";
						//내부값: 다수    //외부값: 다수
				$this->db_collect_func->query("INSERT into csc.report_board {$set_var} {$set_value}");

			}

			//처음 신고한 경우에만 늘림
					//내부값: 없음    //외부값: 없음
			$this->db_collect_func->query("UPDATE csc.writed_info set need_answer = need_answer + 1 WHERE function_name = 'report' ");
		}

		echo "신고 완료되었습니다.";
		return 0;
	}

	//신고 남발 감지
	private function watch_report () {
		//////**반드시 수정시 manage report검사 부분도 생각할것**/////

		if($this->b_spam_user) { //초기 세션 데이터 값임
			echo "신고 제한 상태입니다";
			return 0;
		}

		//먼저 차단됫는지 확인
		if($this->b_report_user) {
			if($this->limit_time == 1) {
				echo "신고 제한 상태입니다";
				return 0;
			} else if ($this->is_passTime) { //시간 지났으면 세션내용 삭제.
				$this->limit_time == 0;
				$this->unset_session(); //세션 처리 
				$result = $this->call_DBdata(); //정보 불러옴

				//갱신시 limit_time이 갱신됬을경우 시간 계산하고 -일때만 update 
				//&& 영구제한 상태도 아니여야함, 아니면 시간 갱신후 제한
				if( ($result[0]['limit_time'] != $this->limit_time || $result[0]['limit_time'] - $this->server_date < 0) && $result[0]['limit_time'] != 1){
					$this->limit_time = 0; //차단 해제
					$this->update_DBdata();
				} else {
					$this->session->set_userdata('b_report_user', '1');
					$this->session->set_userdata('b_report_time', $result[0]['limit_time']);
					echo "신고 제한 상태입니다";
					return 0;
				}
			} else { 
				echo "신고 제한 상태입니다";
				return 0;
			}
		} else {	//차단 안됬을경우 일정수마다 확인 (0번째도 확인됨)

			//--------------------일정수 넘어가면 더 길게하도록 생각 ----------------------
			if( $this->w_report_n % $this->watch_num == 0)  {
				
				$result = $this->call_DBdata(); //정보 불러옴

				//차단시간 존재하면 차단
				if($result[0]['limit_time'] != 0) {
					$this->session->set_userdata('b_report_user', '1');
					$this->session->set_userdata('b_report_time', $result[0]['limit_time']);
					echo "신고 제한 상태입니다";
					return 0;
					
				}
			}	
		}	

		//신고 남발인지 검사
		$this->w_report[$this->w_report_n] = $this->server_date; //0부터 저장
		$this->w_report_n++; 
		$this->session->set_userdata('w_report_n', $this->w_report_n);
		$this->session->set_userdata('w_report', $this->w_report);

		//회원 및 비회원 규칙 통일
			// 1초안에 글 2개  // 10초안에 글 3개 	// 40초안에 글 5개 // 5분안에 10개 이상
	   if($this->w_report_n > 9 && $this->w_report_n <= 19)
			return $this->check_report(10, 60);
		else if($this->w_report_n > 19 && $this->w_report_n <= 29)
			return $this->check_report(20, 180);
		else if($this->w_report_n > 29 && $this->w_report_n <= 39 )
			return $this->check_report(30, 300);
		else if($this->w_report_n == 40) {
			//20개되면 검사안하고 초기화
			$this->session->unset_userdata('w_report_n'); 
			$this->session->unset_userdata('w_report'); 
			return true;
		} else 
			return true;

	} 

			//신고 남발 감지 -> 규칙위반했을때 정책
	private function check_report($upload_num, $watch_time) {
		//$upload_num = 갯수 규칙, $watch_time = 제한 타임
		//ex. 2초안에 글 2개, $upload_num = 2, $watch_time = 2
		$data_num = $this->w_report_n;
		$data = $this->w_report;
		
		$result = ($data[$data_num-1] - $data[$data_num-$upload_num]);
		if($result <= $watch_time) {
		
			// 1시간/1일/1주일/영구 			
			$result = $this->call_DBdata();

			if(isset($result[0]['watch_log']))
				$watch_log = $result[0]['watch_log'].'//'.date('y년 m월 d일 H시 i분 s초',$this->server_date).'에 자동탐지에의해 신고차단 되었습니다.';
			else 
				$watch_log = date('y년 m월 d일 H시 i분 s초',$this->server_date).'에 자동탐지에의해 신고차단 되었습니다.';

			$watch_log = $this->db->escape($watch_log);
	
			if($this->b_report_en == 4) {
				//영구차단일 경우 그냥 넘어감
				$limit_time = 1;
			} else {
				//영구차단 아닐경우 업데이트 하고 넘어감
				switch($this->b_report_en) {
					case 0:
						$limit_time = $this->server_date + 60*60;
						$this->b_report_en = 1;
					break; 

					case 1:
						$limit_time = $this->server_date + 24*60*60;
						$this->b_report_en = 2;
					break;

					case 2:
						$limit_time = $this->server_date + 7*24*60*60;
						$this->b_report_en = 3;
					break;

					case 3:
						$limit_time = 1;
						$this->b_report_en = 4;
					break;

					default:
						echo "신고에러. 문의바람";
						return false;
					break;
				}
			}	

			$this->set_immoral_code();
			if($this->Vui_sys->check_login())  { //회원의 경우
				$user_id = $this->db->escape($this->Vui_sys->call_user_id());
						 //외부값: watch_log, user_id
				$this->db_collect_func->query("UPDATE member.user_info set limit_time = '{$limit_time}', immoral_code = '{$this->immoral_code}', watch_log = {$watch_log} where identification_id = {$user_id} ");
			} else { //익명의 경우
				$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);
						//외부값: ip_address, watch_log
				$this->db_collect_func->query("INSERT into collection_function.watch_limit_ip (`ip`, `limit_time`,`immoral_code`, `watch_content`, `watch_log` ) values(INET_ATON({$ip_address}),'{$limit_time}','{$this->immoral_code}','',{$watch_log})  ON DUPLICATE KEY UPDATE limit_time = '{$limit_time}', immoral_code = '{$this->immoral_code}', watch_log = {$watch_log}  ");
			}

			$this->session->set_userdata('b_report_user', '1');
			$this->session->set_userdata('b_report_time',$limit_time);
			echo "신고 제한 상태입니다";
			return false;
		} else 
			return true;
	}





}

