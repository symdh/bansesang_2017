<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Spam_sys extends CI_model { //spam 관리

	private $server_date;

	//데이터 추가시 처리 부분 (언급되지 않은 private 부분, 언급되지 않은 public)
	//추가로 변수 immoral_code 전체 찾아서 고쳐줘야됨

	private $immoral_code; 
	private $b_spam_en;
	private $b_report_en; 

	private $spam_type; //스팸 유형 (글, 댓글 등)
	private $b_spam_user; //이미 스팸 유저인지 확인
	private $w_spam_write_n; //글 입력 갯수
	private $w_spam_write; //글 배열 
	private $w_spam_comment_n; //댓글 입력 갯수
	private $w_spam_comment; //댓글 배열
	private $watch_num = 5; //글+댓글 watch_num마다 스팸내역 존재 하는지 검사.

	private $limit_time;
	private $is_passTime; //제한 시간 넘겼는지 확인 (넘겼으면 1)

	//member 부분 스팸 감지
	private $recaptcha;

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

		$this->server_date = strtotime('now');
		if($this->session->has_userdata('b_spam_time')) {
			$this->limit_time =  $this->session->userdata('b_spam_time');
			if( $this->limit_time - $this->server_date < 0)
				$this->is_passTime = 1;
			else 
				$this->is_passTime = 0;
		} else {
			$this->limit_time = 0;
		 	$this->is_passTime = 0;
		}

		if($this->session->has_userdata('w_spam_write_n')) 
			$this->w_spam_write_n = $this->session->userdata('w_spam_write_n');
		else 
			$this->w_spam_write_n = 0;

		if($this->session->has_userdata('w_spam_comment_n')) 
			$this->w_spam_comment_n = $this->session->userdata('w_spam_comment_n');
		else 
			$this->w_spam_comment_n = 0;

		if($this->session->has_userdata('w_spam_write')) 
			$this->w_spam_write = $this->session->userdata('w_spam_write');
		else 
			$this->w_spam_write = array();

		if($this->session->has_userdata('w_spam_comment')) 
			$this->w_spam_comment = $this->session->userdata('w_spam_comment');
		else 
			$this->w_spam_comment = array();
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

	private function unset_spam () { //세션에서 스팸 해제
		$this->session->unset_userdata('b_spam_user'); 
		$this->session->unset_userdata('b_spam_time'); 
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

	//스팸 감지
	public function watch_spam ($type) {
		//////**반드시 수정시 manage report검사 부분도 생각할것**/////
		$this->spam_type = $type;

		//b_spam_user = 1로 통일 			//b_spam_time = n로 통일
		//먼저 차단됫는지 확인
		if($this->b_spam_user) {
			if($this->limit_time == 1) {
				echo "알림. 스팸의심으로 쓰기 영구 차단된 ip 또는 유저. 해제는 문의바람";
				return 0;
			} else if ($this->is_passTime) { //시간 지났으면 세션내용 삭제.
				$this->limit_time == 0;
				$this->unset_spam(); //세션 처리 
				$result = $this->call_DBdata(); //정보 불러옴

				//갱신시 limit_time이 갱신됬을경우 시간 계산하고 -일때만 update 
				//&& 영구제한 상태도 아니여야함, 아니면 시간 갱신후 제한
				if( ($result[0]['limit_time'] != $this->limit_time || $result[0]['limit_time'] - $this->server_date < 0) && $result[0]['limit_time'] != 1){
					$this->limit_time = 0; //차단 해제
					$this->update_DBdata();
				} else {
					$this->session->set_userdata('b_spam_user', '1');
					$this->session->set_userdata('b_spam_time', $result[0]['limit_time']);
					echo "알림. 스팸의심으로 차단내역이 있습니다. 해제는 문의바람니다.";
					return 0;
				}
			} else { 
				echo "알림. 스팸의심으로 ".date('d일 H시 i분 s초',$this->limit_time)."까지 쓰기 제한된 ip 또는 유저. \n해제는 문의바람";
				return 0;
			}
		} else {	//차단 안됬을경우 일정수마다 확인 (0번째도 확인됨)

			if( ($this->w_spam_write_n + $this->w_spam_comment_n ) % $this->watch_num == 0)  {
				
				$result = $this->call_DBdata(); //정보 불러옴

				//차단시간 존재하면 차단
				if($result[0]['limit_time'] != 0) {
					$this->session->set_userdata('b_spam_user', '1');
					$this->session->set_userdata('b_spam_time', $result[0]['limit_time']);
					if($result[0]['limit_time'] == 1) {
						echo "알림. 스팸의심으로 쓰기 영구 차단된 ip 또는 유저. 해제는 문의바람";
						return 0;
					} else {
						echo "알림. 스팸의심으로 ".date('d일 H시 i분 s초',$result[0]['limit_time'])."까지 쓰기 제한된 ip 또는 유저. \n해제는 문의바람";
						return 0;
					}
				}
			}	
		}	

		//스팸인지 검사
		if ($this->spam_type == 'write') { 

			//데이터 세션 저장
			$this->w_spam_write[$this->w_spam_write_n] = $this->server_date; //0부터 저장
			$this->w_spam_write_n++; 
			$this->session->set_userdata('w_spam_write_n', $this->w_spam_write_n);
			$this->session->set_userdata('w_spam_write', $this->w_spam_write);

			//회원 및 비회원 규칙 통일
				// 1초안에 글 2개  // 10초안에 글 3개 	// 40초안에 글 5개 // 5분안에 10개 이상
			if($this->w_spam_write_n == 2) 
				return $this->check_spam(2, 1);
			else if($this->w_spam_write_n > 2 && $this->w_spam_write_n <= 4)
				return $this->check_spam(3, 10);
			else if($this->w_spam_write_n > 4 && $this->w_spam_write_n <= 9)
				return $this->check_spam(5, 40);
			else if($this->w_spam_write_n > 10 && $this->w_spam_write_n <= 19 )
				return $this->check_spam(11, 600);
			else if($this->w_spam_write_n == 20) {
				//20개되면 검사안하고 초기화
				$this->session->unset_userdata('w_spam_write_n'); 
				$this->session->unset_userdata('w_spam_write'); 
				return true;
			} else 
				return true;

		} else if ($this->spam_type == 'comment') {
			
			//저장뒤 검사
			$this->w_spam_comment[$this->w_spam_comment_n] = $this->server_date; //0부터 저장
			$this->w_spam_comment_n++;
			$this->session->set_userdata('w_spam_comment_n', $this->w_spam_comment_n);
			$this->session->set_userdata('w_spam_comment', $this->w_spam_comment);

			//회원 및 비회원 규칙 통일
				// 1초안에 댓글 3개 // 10초안에 댓글 5개 // 40초안에 댓글 10개 // 3분안에 댓글 20개 
			if($this->w_spam_comment_n > 2 && $this->w_spam_comment_n <= 4) 
				return $this->check_spam(2, 1);
			else if($this->w_spam_comment_n > 5 && $this->w_spam_comment_n <= 9)
				return $this->check_spam(6, 10);
			else if($this->w_spam_comment_n > 9 && $this->w_spam_comment_n <= 19)
				return $this->check_spam(10, 40);
			else if($this->w_spam_comment_n > 19 && $this->w_spam_comment_n <= 29)
				return $this->check_spam(20, 180);
			else if($this->w_spam_comment_n == 30) {
				//30개되면 검사안하고 초기화
				$this->session->unset_userdata('w_spam_comment_n'); 
				$this->session->unset_userdata('w_spam_comment'); 
				return true;
			} else 
				return true;			
		} 

	} 

	//스팸감지 -> 규칙위반했을때 정책
	private function check_spam($upload_num, $watch_time) {
		//$upload_num = 갯수 규칙, $watch_time = 제한 타임
		//ex. 2초안에 글 2개, $upload_num = 2, $watch_time = 2

		if ($this->spam_type == 'write') { 
			$data_num = $this->w_spam_write_n;
			$data = $this->w_spam_write;
		} else if ($this->spam_type == 'comment') {
			$data_num = $this->w_spam_comment_n;
			$data = $this->w_spam_comment;
		}

		$result = ($data[$data_num-1] - $data[$data_num-$upload_num]);
		if($result <= $watch_time) {
		
			// 1시간/1일/1주일/영구 			
			$result = $this->call_DBdata();

			if(isset($result[0]['watch_log']))
				$watch_log = $result[0]['watch_log'].'//'.date('y년 m월 d일 H시 i분 s초',$this->server_date).'에 자동탐지에의해 스팸차단 되었습니다.';
			else 
				$watch_log = date('y년 m월 d일 H시 i분 s초',$this->server_date).'에 자동탐지에의해 스팸차단 되었습니다.';

			$watch_log = $this->db->escape($watch_log);
	
			if($this->b_spam_en == 4) {
				//영구차단일 경우 그냥 넘어감
				$limit_time = 1;
			} else {
				//영구차단 아닐경우 업데이트 하고 넘어감
				switch($this->b_spam_en) {
					case 0:
						$limit_time = $this->server_date + 60*60;
						$this->b_spam_en = 1;
					break; 

					case 1:
						$limit_time = $this->server_date + 24*60*60;
						$this->b_spam_en = 2;
					break;

					case 2:
						$limit_time = $this->server_date + 7*24*60*60;
						$this->b_spam_en = 3;
					break;

					case 3:
						$limit_time = 1;
						$this->b_spam_en = 4;
					break;

					default:
						echo "알림. 에러발생. 문의바람";
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

			$this->session->set_userdata('b_spam_user', '1');
			$this->session->set_userdata('b_spam_time',$limit_time);
			echo "알림. 자동탐지기에의해 스팸으로 차단당함. 해제는 문의바람";
			return false;
		} else 
			return true;
	}

	//member 부분 스팸 감지
	public function watch_recaptcha() {
		if(isset($_POST['g-recaptcha-response'])) { 
			$this->recaptcha = $_POST['g-recaptcha-response']; 
		} else {
			return 0;
		}

		// curl 리소스를 초기화
		$data = array(
		   'secret' => "6Ld2WjwUAAAAAMnFdoQp486XHNvcMfpmcblvdv0Q",
		   'response' => $this->recaptcha,
		   'remoteip' => $_SERVER['REMOTE_ADDR']
		);

		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($verify);
		
		if (preg_match("/\"success\"\: true/i", $response)) {
		   return 1;
		} else {
		   return 0;
		}

   		return 0;
	}


}

