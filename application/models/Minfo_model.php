<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Minfo_model extends CI_model { 
	private $function_name; 

	function __construct(){ 
		parent::__construct(); 
		$this->db_minfo = $this->load->database('minfo', TRUE);
		date_default_timezone_set("Asia/Seoul");
	}

	public function set_function_name ($function_name) {
		$this->function_name = $function_name;
	}

	public function call_function_name () {
		return $this->function_name;
	}

	//page의 리스트 출력
	public function gets($divide_type, $setUp_pageNum) {
		//$setUp_pageNum = page list에 몇개의 글을 출력할지
		
	 	// 들어오는 값 제어 ($_GET은 현재 list 페이지 넘버 )
		if(!is_numeric($setUp_pageNum) ) return 0;
		if(!isset($_GET['page']) || !is_numeric($_GET['page']) ) $_GET['page'] = 1;
		$start_index = ($_GET['page']-1)*$setUp_pageNum;
		$start_index = (int)$start_index;
		$divide_type = $this->db->escape($divide_type);
				//외부값: start_index, divide_type
		$result = $this->db_minfo->query("SELECT * FROM {$this->function_name}_board  WHERE divide_type ={$divide_type} LIMIT {$start_index}, {$setUp_pageNum} ")->result_array();
		
		$this->load->model('Norm_sys');
		foreach ($result as $key => $entry) {
			if($result[$key]['report_result'] == 2) {
				$result[$key]['title'] = "[신고누적되어 Hide처리된 글입니다.]";
			}
			$result[$key]['date'] = $this->Norm_sys->norm_time(strtotime($result[$key]['date']));

			unset($result[$key]['ip_address']);
			unset($result[$key]['modify_info'] );
			unset($result[$key]['passwd'] );
			unset($result[$key]['user_id'] );
			unset($result[$key]['content'] );
			unset($result[$key]['garbage_check'] );
			unset($result[$key]['report_result'] );
		}

		return $result;
	}
			
	//글 갯수 정보 가져옴 (writed_info) - 페이징 시스템
	public function gets_info ($divide_type, $setUp_pageNum, $setUp_groupNum) {
		//divide_type : 게시물 구분
		//$setUp_pageNum = 위 gets 와 동일하게
		//$setUp_groupNum = 한 group에 몇개를 나타낼지

		$divide_type = $this->db->escape($divide_type);
				//외부값: divide_type
		$result = $this->db_minfo->query("SELECT * FROM board_info WHERE function_name = '{$this->function_name}' AND divide_type = {$divide_type} ")->result_array();

		//일정수 이상 넘어가면 글 정보 갱신
		if($result[0]['load_count']> 100) { //최적화시 유동적으로 변경
					//외부값: divide_type
			$this->db_minfo->query("UPDATE board_info set load_count = 1 WHERE  function_name = '{$this->function_name}'  AND divide_type = {$divide_type} ");
			$board_info = $this->db_minfo->query("SELECT count(*) as writed_count FROM {$this->function_name}_board WHERE divide_type = {$divide_type} ")->result_array();
			$count_page  = ceil($board_info[0]['writed_count']/$setUp_pageNum); //페이지의 총 갯수를 구함
			$count_group  = ceil($count_page/$setUp_groupNum); //그룹의 총 갯수를 구함
			$this->db_minfo->query("UPDATE board_info set writed_count = ".$board_info[0]['writed_count'].", count_page =".$count_page." , count_group = ".$count_group." WHERE  function_name = '{$this->function_name}'  AND divide_type = {$divide_type}");
			$result = $this->db_minfo->query("SELECT * FROM board_info WHERE  function_name = '{$this->function_name}'  AND divide_type = {$divide_type} ")->result_array(); //갱신된 정보를 다시 불러옴
		} else {
					//외부값: divide_type
			$this->db_minfo->query("UPDATE board_info set load_count = load_count + 1 WHERE  function_name = '{$this->function_name}' AND divide_type = {$divide_type}"); 
		}

		//변수 설정부 데이터까지 같이 보냄
		$result[0]['setUp_pageNum'] = $setUp_pageNum;	
		$result[0]['setUp_groupNum'] = $setUp_groupNum;
		unset($result[0]['load_count']);
		unset($result[0]['writed_count']);

		return $result;
	}

	//몇번째 페이지인지 정보를 알아내기위한 db작업
	public function get_page($num) {
		if(!is_numeric($num)) return 0;
		$num = (int)$num;

				//외부값: num
		$result = $this->db_minfo->query("SELECT count(*) as count FROM {$this->function_name}_board WHERE read_id > {$num}")->result_array();

		return $result;
	}

	//집어넣은 read_id 값 반환
	private function get_insert_id () {
		$result =  $this->db_minfo->query("SELECT LAST_INSERT_ID()")->result_array();
		return $result[0]['LAST_INSERT_ID()'];
	}

	//글쓴거 업로드
	public function upload_write() {
		$this->load->model('Spam_sys');
		$this->load->model('Img_sys');
		$this->load->model('Pchk_sys');
		$this->load->model('Vui_sys');
		if(!$this->Spam_sys->watch_spam('write')) return 0; //스팸 감지

		if(!isset($_POST['write_type']) ) {
			sendback_site('reject_access', 'write','',0,'minfo');
			return 0;
		} else if ($_POST['write_type'] != 'symptom' && $_POST['write_type'] != 'medical'  && $_POST['write_type'] != 'hospital'  && $_POST['write_type'] != 'medicine' )  {
			sendback_site('reject_access', 'write','',0,'minfo');
			return 0;
		} 

		$title = $this->db->escape($this->Pchk_sys->call_title() );
		$content =  $this->db->escape($this->Pchk_sys->call_content() );
		if($title === 0) {sendback_site('reject_access', 'write','',0,'minfo'); return 0;}
		if($content === 0) {sendback_site('reject_access', 'write','',0,'minfo'); return 0;}
		if(!$this->Vui_sys->check_login() ) { sendback_site('reject_access', 'write','',0,'minfo'); return 0; }  //익명일때 거부
		if(!isset($_POST['attach_image'])) $_POST['attach_image'] = $this->Img_sys->extract_img($_POST['content']); //사진없으면 content에서 추출
		$attach_image = $this->db->escape('');
		if(isset($_POST["attach_image"][0]))  //파일 첨부가 있으면 처리
			$attach_image = $this->db->escape($this->Img_sys->save_img($_POST["attach_image"]));
		if($attach_image === 0) { sendback_site('reject_access', 'write','',0,'minfo'); return 0;}

		$id_check = mt_rand();
		$user_id =$this->db->escape($this->Vui_sys->call_user_id());
		$writed_member = $this->db->escape($this->Vui_sys->call_trans_nick());
		$passwd = $this->db->escape($this->Vui_sys->call_trans_pwd());
		$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);
		$_POST['write_type'] = $this->db->escape($_POST['write_type']);

				//외부값: 다수
		$this->db_minfo->query("INSERT into {$this->function_name}_board (`ip_address`,`id_check`,`passwd`,`writed_member`,`user_id`,`title`,`content`,`attach_image`, `divide_type`) values( {$ip_address},'".$id_check ."',{$passwd} ,{$writed_member} ,{$user_id} ,{$title} ,{$content} ,{$attach_image} ,{$_POST['write_type']})");

		redirect('/minfo/'.$this->function_name);
	}

	//글내용 불러오기 (+글내용 수정할꺼 불러오기)
	public function download_write($num, $check_modify) {
		if(!is_numeric($num)) return 0;
		$num = (int)$num;

				//외부값: num
		$data_w =  $this->db_minfo->query("SELECT * FROM {$this->function_name}_board AS A LEFT JOIN member.user_sub_info AS B ON A.user_id = B.identification_id WHERE A.read_id = '{$num}' ")->result_array();
		if(!isset($data_w[0]['read_id'])) { sendback_site('none', $this->function_name, 'list',0,'minfo'); return 0; } //결과 값이 없으면 거부

		//수정의 경우 데이터 검정을 함
		if ($check_modify == 1) {	
			//신고 누적상태시 거부
			if ($data_w[0]['report_result'] == 2) {
				sendback_site('exceed_report', $this->function_name, 'read',$num,'minfo');
				return 0;
			}

			$user_id = $this->session->userdata('user_id');
			if($user_id != $data_w[0]['user_id']) { sendback_site('reject_access', $this->function_name, 'read', $num,'minfo'); return 0; } //유저 확인
		}

		//단순 글 불러오기일 경우
		if ($check_modify == 0) {
			//조회수 늘리기
					//외부값: num
			$this->db_minfo->query("UPDATE {$this->function_name}_board set click_num = click_num + 1 where read_id = '{$num}' ");
			//불러온다음에 update 하므로 게시글에서 제대로 보여주기 위해 ++ 시켜줌
			$data_w[0]['click_num']++;
		}

		$check_login = $this->session->userdata('logged_in');
		$user_id = $this->session->userdata('user_id');

		$this->load->model('Norm_sys');
		foreach ($data_w as $key => $entry) {

			if($data_w[$key]['report_result'] == 2) {
				//신고 누적 됬을경우 내용 가림
				$data_w[$key]['content'] = "[신고누적되어 Hide처리된 글입니다.]";
				$data_w[$key]['title'] = "[신고누적되어 Hide처리된 글입니다.]";
			}

			//로그인 안되어 있으면 본인꺼인지 검사안함
			//개발노트 7-2
			if($check_login) {
				if($data_w[$key]['user_id'] == $user_id) {
					$data_w[$key]['check_owner'] = 1;
				} else {
					if($data_w[$key]['user_id'] == -1 ) {
						$data_w[$key]['check_owner'] = -1;

					} else {
						$data_w[$key]['check_owner'] = 0;
					}
				}
			} else {
				//로그인이 안되어 있으면 익명인지만 확인
				if($data_w[$key]['user_id'] == -1) {
					$data_w[$key]['check_owner'] = -1;
				} else {
					//익명외에는 모두 회원
					$data_w[$key]['check_owner'] = 2;
				}
			}

			$data_w[$key]['date'] = $this->Norm_sys->norm_time(strtotime($data_w[$key]['date']));
				

			//필요없는 정보 빼기
			unset($data_w[$key]['ip_address']);
			unset($data_w[$key]['modify_info'] );
			unset($data_w[$key]['identification_id'] );

			if ($check_modify != 1 ) { 
				//수정페이지는 필요함
				unset($data_w[$key]['passwd'] );
			}
			unset($data_w[$key]['user_id'] );
			if($check_modify == 0) {
				unset($data_w[$key]['attach_image'] );
			}
		}
		//print_r($data_w); //return 0;
		
		return $data_w;
	}

	//수정한 글 저장하기
	public function modify_write() {
		$this->load->model('Img_sys');
		$this->load->model('Pchk_sys');
		$this->load->model('Vui_sys');

		if(!isset($_POST['write_type']) ) {
			sendback_site('reject_access', 'write','',0,'minfo');
			return 0;
		} else if ($_POST['write_type'] != 'symptom' && $_POST['write_type'] != 'medical'  && $_POST['write_type'] != 'hospital'  && $_POST['write_type'] != 'medicine' )  {
			sendback_site('reject_access', 'write','',0,'minfo');
			return 0;
		} 

		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		$title = $this->db->escape($this->Pchk_sys->call_title() );
		$content =  $this->db->escape($this->Pchk_sys->call_content() );
		if($read_id === 0)  { sendback_site('reject_access', '', '', 0,'main'); return 0; } 
		if($title === 0) {sendback_site('reject_access', 'write','',0,'minfo'); return 0;}
		if($content === 0) {sendback_site('reject_access', 'write','',0,'minfo'); return 0;}
		if(!isset($_POST['attach_image'])) $_POST['attach_image'] = $this->Img_sys->extract_img($_POST['content']); //사진없으면 content에서 추출

				//외부값: read_id
		$data_w = $this->db_minfo->query("SELECT * FROM {$this->function_name}_board WHERE read_id = {$read_id} ")->result_array();
		if(!isset($data_w[0]['read_id'])) { sendback_site('none' ,$this->function_name, 'list',0,'minfo'); return 0; } //결과 값이 없으면 거부

		//id 조작 여부를 확인
		if ($_POST['id_check'] !=$data_w[0]['id_check']){ 
			sendback_site('reject_access' ,$this->function_name, 'read', $read_id,'minfo');
			return 0;
		}

		//신고 누적상태시 거부
		if ($data_w[0]['report_result'] == 2) {
			sendback_site('exceed_report' ,$this->function_name, 'read', $read_id,'minfo');
			return 0;
		}

		$this->Vui_sys->saved_id = $data_w[0]['user_id'];
		if(!$this->Vui_sys->check_login() || !$this->Vui_sys->verify_board_user() ) { sendback_site('reject_access', $this->function_name, 'read', $read_id,'minfo'); return 0; } //유저 검증

		//기존에 저장된 이미지
		$saved_image = explode("%///%", $data_w[0]['attach_image']);
		if(isset($_POST["attach_image"][0])) { //파일 첨부를 처리해서 저장함
			$attach_image = $this->db->escape($this->Img_sys->modify_img($_POST["attach_image"], $saved_image));		
		} else {
			$attach_image = $this->db->escape($this->Img_sys->delete_img($saved_image));
		}
		if($attach_image === 0) {
			sendback_site('reject_access', $this->function_name, 'list',0,'minfo');
			return 0;
		}

		//수정하는 날짜와 ip주소를 기록
		$server_date = date("Y-m-d H:i:s",time());
		$ip_address = $_SERVER['REMOTE_ADDR']; //query에 직접 접근 안함
		$modify_info = $ip_address.'~'.$server_date.'/';
		$modify_info = $this->db->escape($modify_info);

				//외부값: modify_info외 다수
		$this->db_minfo->query("UPDATE {$this->function_name}_board set modify_info = concat(ifnull(modify_info,''), {$modify_info}), title= {$title}, content = {$content}, attach_image = {$attach_image} where read_id= {$read_id}");

		//수정완료
		sendback_site('result_modify', $this->function_name, 'read', $read_id,'minfo');
	}

	public function delete_write() {
		$this->load->model('Vui_sys');
		$this->load->model('Pchk_sys');
		$this->load->model('Img_sys');
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0)  { sendback_site('reject_access', '', '', 0,'main'); return 0; } 
	
				//외부값: $read_id
		$data_w = $this->db_minfo->query("SELECT * FROM {$this->function_name}_board WHERE read_id = {$read_id}")->result_array();
		if(!isset($data_w[0])) { sendback_site('none', $this->function_name, 'list',0,'minfo'); return 0; } //이미 삭제됬을 경우

		//id 조작 여부를 확인
		if ($_POST['id_check']==$data_w[0]['id_check']){

			$this->Vui_sys->saved_id = $data_w[0]['user_id'];
			if(!$this->Vui_sys->check_login() || !$this->Vui_sys->verify_board_user() ) { sendback_site('reject_access', $this->function_name, 'read', $read_id,'minfo'); return 0; } //유저 검증
			$user_id =$this->db->escape($this->Vui_sys->call_user_id());

			//사진이 있으면 서버에서 확인후 삭제시킴 (패턴으로 찾음) 
			$saved_image = explode("%///%", $data_w[0]['attach_image']);
			$result = $this->Img_sys->delete_img($saved_image);
			if($result === 0) { sendback_site('reject_access', $this->function_name, 'list',0,'minfo'); return 0; }

					//외부값: read_id
			$this->db_minfo->query("UPDATE {$this->function_name}_comment set garbage_check ='1' WHERE read_id = {$read_id}");
			$this->db_minfo->query("DELETE from {$this->function_name}_board WHERE read_id = {$read_id}"); //글 삭제

			if($this->Img_sys->clean_upload_img() === 0) {
				echo "에러코드 1101. 관리자에게 문의하세요";
				return 0;
			}

			sendback_site('result_delete', $this->function_name, 'list',0,'minfo');
			return 0;
		} else {

			//삭제 불가
			sendback_site('reject_access', $this->function_name, 'read', $read_id,'minfo');
			return 0;
		}
	}

	// //최신화된 글 정보를 보냄
	// public function lately_write () {
	// 	//print_r($_POST);

	// 	if(!is_numeric($_POST['lately_read_id'])) return 0;
	// 	$_POST['lately_read_id'] = (int)$_POST['lately_read_id'];

	// 	$data_w =  $this->db_minfo->query("SELECT * FROM {$this->function_name}_board WHERE read_id > '{$_POST['lately_read_id']}' ")->result_array();

	// 	if(count($data_w) > 30) {
	// 		echo "잘못된 접근 입니다.";
	// 		return 0;
	// 	}


	// 	foreach ($data_w as $key => $entry) {
	// 		//포토 게시판일 경우
	// 		if( $this->function_name == 'photo') {
	// 			$data_w[$key]['photo'] = 1 ;

	// 			//이미지를 구한다음에 넘김 (이미지가 없는 경우는 생각하지 않음)
	// 				$saved_image = explode("%///%", $entry['attach_image']);		        
	// 	         $saved_image[0] = str_ireplace("/img/", "/img/thumbnail/", $saved_image[0]) ;
	// 	         $saved_image[0] = str_ireplace(".", "_min.", $saved_image[0]) ;
	// 	         $data_w[$key]['photo_src_1'] = $saved_image[0];


	// 	         if(isset($saved_image[3])) { //1개만 존재해도 2번은 존재함
	// 	            $saved_image[2] = str_ireplace("/img/", "/img/thumbnail/", $saved_image[2]) ;
	// 	            $saved_image[2] = str_ireplace(".", "_min.",$saved_image[2]) ;

	// 	             $data_w[$key]['photo_src_2'] = $saved_image[2];
	// 	         } else {
	// 	            //두번째 이미지가 없는 경우를 생각함
	// 	            $saved_image[2] = '';
	// 	         }

     
	// 		}
	// 		if($data_w[$key]['report_result'] != 2) {
	// 			//타이틀 해독 안함
				
	// 		} else {
	// 			//신고 누적 됬을경우 내용 가림
	// 			$data_w[$key]['title'] = "[신고누적되어 Hide처리된 글입니다.]";
	// 		}
	// 		//필요없는 정보 빼기
	// 		unset($data_w[$key]['ip_address']);
	// 		unset($data_w[$key]['modify_info']);
	// 		unset($data_w[$key]['passwd'] );
	// 		unset($data_w[$key]['user_id'] );
	// 		unset($data_w[$key]['attach_image'] );
	// 		unset($data_w[$key]['content']);
			// unset($data_w[$key]['garbage_check'] );
			// unset($data_w[$key]['report_result'] );
	// 	}
	// 	$data_w = json_encode($data_w, JSON_PRETTY_PRINT);
	// 	print_r($data_w);
	// }

//////////////////////////////////////////////////////////////////////////////////////////////////////
////////이 아래부터는 댓글 시스템//////
//////////////////////////////////////////////////////////////////////////////////////////////////////
	private function chg_content() { //표준화작업 댓글의 content를 title로 교체
		$_POST['title'] = $_POST['content']; 
		unset($_POST['content']);
		return 1;
	}

	public function upload_comment() {
		$this->load->model('Spam_sys');
		$this->chg_content();
		$this->load->model('Vui_sys');
		$this->load->model('Pchk_sys');
		
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0) { echo "잘못된 접근"; return 0; }
		$this->Pchk_sys->md5_encrypt($read_id);
		if(!$this->Pchk_sys->md5_check()) { echo "잘못된 접근"; return 0; }
		$title = $this->db->escape($this->Pchk_sys->call_title());
		if($title ===0) { echo "내용은 2글자 이상입니다."; return 0;}
		if(!$this->Spam_sys->watch_spam('comment')) return 0; //스팸감지
		if(!$this->Vui_sys->trans_pwd() || !$this->Vui_sys->trans_nick() ||!$this->Vui_sys->has_anony_val('nick') || !$this->Vui_sys->has_anony_val('pwd')) { echo "닉네임, 패스워드를 확인해 주세요."; return 0; } //유저 검증
		// g1을 불러옴
				//외부값: _POST['read_id']
		$this->db_minfo->query("UPDATE {$this->function_name}_board set max_group_1_id = @var1 := max_group_1_id +1 WHERE read_id = {$read_id} ");
		$result = $this->db_minfo->query("SELECT @var1 ")->result_array();
		if(is_null($result[0]['@var1'])) { echo "알림. 이미 삭제된 글 입니다."; return 0; } 

		$user_id =$this->db->escape($this->Vui_sys->call_user_id() );
		$writed_member = $this->db->escape($this->Vui_sys->call_trans_nick() );
		$passwd = $this->db->escape($this->Vui_sys->call_trans_pwd() );
		$super_user = $this->db->escape($this->Vui_sys->call_super_user() );
		$result[0]['max_group_1_id'] = $result[0]['@var1'];
		$depth_comment = 0;
		$id_check = mt_rand();
		$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);

				//외부값: 다수
		$this->db_minfo->query("INSERT into {$this->function_name}_comment (`ip_address`,`read_id`,`group_1_id`,`id_check`,`passwd`,`writed_member`,`user_id`,`content`,`super_user`) values({$ip_address}, {$read_id}, '".$result[0]['max_group_1_id']."', '".$id_check ."', {$passwd}, {$writed_member}, {$user_id}, {$title}, {$super_user})");
		

		//글의 댓글 갯수 정보를 업데이트함
				//외부값: read_id
		$this->db_minfo->query("UPDATE {$this->function_name}_board set count_comment = count_comment + 1 where read_id = {$read_id}  ");

		echo "성공!";
	}

	public function upload_answer_comment () {
		$this->load->model('Spam_sys');
		$this->chg_content();
		$this->load->model('Vui_sys');
		$this->load->model('Pchk_sys');

		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0) { echo "잘못된 접근"; return 0; }
		$this->Pchk_sys->md5_encrypt($read_id);
		if(!$this->Pchk_sys->md5_check()) { echo "잘못된 접근"; return 0; }
		$title = $this->db->escape($this->Pchk_sys->call_title());
		if($title ===0) { echo "내용은 2글자 이상입니다."; return 0;}
		if(!$this->Spam_sys->watch_spam('comment')) return 0; //스팸감지
		if(!$this->Vui_sys->trans_pwd() || !$this->Vui_sys->trans_nick() ||!$this->Vui_sys->has_anony_val('nick') || !$this->Vui_sys->has_anony_val('pwd')) { echo "닉네임, 패스워드를 확인해 주세요."; return 0; } //유저 검증
		//g1 으로 글 삭제 됬는지 체크
				//외부값: read_id
		$check_delete = $this->db_minfo->query("SELECT * FROM {$this->function_name}_board WHERE read_id = {$read_id} ")->result_array();
		if(is_null($check_delete[0]['max_group_1_id']) ) { echo "알림. 이미 삭제된 글 입니다."; return 0; }

		$group_1_id = $this->db->escape($this->Pchk_sys->call_group_1_id());
		if($group_1_id ===0) {echo "잘못된 접근"; return 0;}
		//g2의 마지막 그룹 확인 (현재 댓글 또는 답글이 존재하는지도 파악됨)
				//외부값: read_id, group_1_id
		$result = $this->db_minfo->query("SELECT max(DISTINCT group_2_id) as max_g2 FROM {$this->function_name}_comment WHERE read_id = {$read_id} AND group_1_id = {$group_1_id} ")->result_array();
		if(!isset($result[0]['max_g2']))  { echo "알림. 이미 삭제된 댓글 입니다"; return 0; } 

		$send_member = $this->db->escape($this->Pchk_sys->call_send_member());
		if($send_member === 0) {echo "잘못된 접근"; return 0;}

		$result[0]['max_g2']++;
		$depth_comment = 1;
		$user_id =$this->db->escape($this->Vui_sys->call_user_id() );
		$writed_member = $this->db->escape($this->Vui_sys->call_trans_nick() );
		$passwd = $this->db->escape($this->Vui_sys->call_trans_pwd() );
		$super_user = $this->db->escape($this->Vui_sys->call_super_user() );
		$id_check = mt_rand();
		$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);

				//외부값: 다수
		$this->db_minfo->query("INSERT into {$this->function_name}_comment (`ip_address`,`read_id`,`depth`,`group_1_id`,`group_2_id`,`id_check`,`passwd`,`writed_member`,`user_id`,`send_member`,`content`,`super_user`) values( {$ip_address}, {$read_id},'".$depth_comment."', {$group_1_id}, '".$result[0]['max_g2']."', '".$id_check ."', {$passwd}, {$writed_member}, {$user_id}, {$send_member}, {$title}, {$super_user})");
	

		//글의 댓글 갯수 정보를 업데이트함
				//외부값: _POST['read_id']
		$this->db_minfo->query("UPDATE {$this->function_name}_board set count_comment = count_comment + 1 where read_id = {$read_id}  ");

		echo "답글 등록 성공";
	}

	//최초 다운로드
	public function download_comment($num) {
		
		//내림차순 정렬시 view 에서 재정렬 할 필요가 없어짐
		if(!is_numeric($num)) return 0;
		$num = (int)$num;
				//외부값: num
		$data_c =  $this->db_minfo->query("SELECT * FROM {$this->function_name}_comment AS A LEFT JOIN member.user_sub_info AS B ON A.user_id = B.identification_id WHERE  A.read_id = '{$num}' ORDER BY group_1_id ASC, comment_id ASC ")->result_array();

		$check_login = $this->session->userdata('logged_in');
		$user_id = $this->session->userdata('user_id');
		$super_user = $this->session->userdata('guitar_id');

		foreach ($data_c as $key => $entry) {

			if($data_c[$key]['report_result'] != 2) {
				// 저장할때 하면 br태그가 뭍힘
				$data_c[$key]['content'] = str_replace("\r\n", "<br>",$data_c[$key]['content']);
			} else {

				//신고 누적 됬을경우 내용 가림
				$data_c[$key]['content'] = "[신고누적되어 Hide처리된 댓글입니다.]";
			}

			//로그인 안되어 있으면 본인꺼인지 검사안함
			//개발노트 7-2
			if($check_login) {
				if($data_c[$key]['user_id'] == $user_id) {
					$data_c[$key]['check_owner'] = 1;
				} else {
					if($data_c[$key]['user_id'] == -1 ) {
						$data_c[$key]['check_owner'] = -1;

					} else {
						$data_c[$key]['check_owner'] = 0;
					}
				}
			} else {
				//로그인이 안되어 있으면 익명인지만 확인
				if($data_c[$key]['user_id'] == -1) {
					$data_c[$key]['check_owner'] = -1;
				} else {
					//익명외에는 모두 회원
					$data_c[$key]['check_owner'] = 2;
				}
			}

			//운영자 수준의 comment인지 확인
			if($check_login) {
				if ($data_c[$key]['super_user'] != 0 ) {
					if ( $data_c[$key]['user_id'] == $user_id ) {
						$data_c[$key]['super_user'] = 1;
					} else {
						$data_c[$key]['super_user'] = 0;
					}
				} else {
					$data_c[$key]['super_user'] = 0;
				}
			} else {
				$data_c[$key]['super_user'] = 0;
			}

			//필요없는 정보 빼기
			unset($data_c[$key]['ip_address']);
			unset($data_c[$key]['passwd'] );
			unset($data_c[$key]['user_id'] );
			unset($data_c[$key]['garbage_check']);
			unset($data_c[$key]['identification_id']);
			unset($data_c[$key]['user_intro']);
		}
		//print_r($data_c);

		return $data_c;
	}

	// //댓글 수정시 작동
	// public function modify_comment() {
		
	// 	//로그인 여부 확인
	// 	$check_login = $this->session->userdata('logged_in');
	// 	if(!$check_login) {
	// 		echo "잘못된 접근입니다";
	// 		return 0;
	// 	}
	// 	//print_r($_POST);
	// 	if(!is_numeric($_POST['comment_id'])) return 0;
	// 	$_POST['comment_id'] = (int)$_POST['comment_id'];
	// 			//외부값: _POST['comment_id']
	// 	$result = $this->db_minfo->query("SELECT * FROM {$this->function_name}_comment WHERE comment_id = '{$_POST['comment_id']}' ")->result_array();
		
	// 	//채택된 댓글은 수정불가
	// 	if(isset($result[0]['selected_a'])) {
	// 		//채택이 안됬고 댓글이 존재할때 
	// 		if( $result[0]['selected_a'] == 1 ) {
	// 			//댓글이 있을경우 수정 전혀 불가
	// 			echo "<script>alert('채택된 댓글은 수정 불가합니다.');</script>";
	// 			echo "<script>window.location = '/board/{$this->function_name}/read/".$_POST['read_id']."';</script>";
	// 			return 0;
	// 		}
	// 	} 

	// 	//id 조작 여부를 확인 && 운영자 아니면 리턴
	// 	if ($_POST['id_check']!=$result[0]['id_check'] || $result[0]['super_user']==0) {
	// 		echo "잘못된 접근입니다";
	// 		return 0;
	// 	}

	// 	//유저 id비교하고
	// 	$user_id = $this->session->userdata('user_id');
	// 	if($user_id != $result[0]['user_id']) {
	// 		echo "잘못된 접근입니다";
	// 		return 0;
	// 	} 


	// 	//수정하는 날짜와 ip주소를 기록 (실제로 기록은 추가시켜야됨)
	// 	// $server_date = date("Y-m-d H:i:s",time());
	// 	// $ip_address = $_SERVER['REMOTE_ADDR'];
	// 	// $modify_info = $ip_address.'~'.$server_date.'/';

	// 	//댓글 수정
	// 	$_POST['content'] = htmlspecialchars($_POST['content'], ENT_QUOTES);
	// 	$_POST['content'] = (int)$_POST['content'];
	// 			//외부값:_POST['content'],  _POST['comment_id']
	// 	$this->db_minfo->query("UPDATE {$this->function_name}_comment set content ='{$_POST['content']}' WHERE comment_id = '{$_POST['comment_id']}' ");
		
	// 	//위 download_comment의 규칙을 따름
	// 	$result = $this->db_minfo->query("SELECT * FROM {$this->function_name}_comment WHERE comment_id = '{$_POST['comment_id']}' ")->result_array();
	// 	//필요없는 정보 빼기
	// 	unset($result[0]['ip_address']);
	// 	unset($result[0]['passwd'] );
	// 	unset($result[0]['user_id'] );
	// 	unset($result[0]['garbage_check']);

	// 	//댓글 일단 해독 안함
	// 	//$result[0]['content'] = html_entity_decode($result[0]['content'], ENT_QUOTES, 'UTF-8');
	// 	$result[0]['content'] = str_replace("\r\n", "<br>",$result[0]['content']);
	// 	$result = json_encode($result, JSON_PRETTY_PRINT);
	// 	print_r($result);
	// }

	public function delete_comment ($num) {
		$_POST['comment_id'] = $num;
		$this->load->model('Vui_sys');
		$this->load->model('Pchk_sys');

		$comment_id = $this->db->escape($this->Pchk_sys->call_comment_id() );
		if($comment_id ===0) { echo "잘못된 접근입니다."; return 0; }

				//외부값: comment_id
		$data_c =  $this->db_minfo->query("SELECT * FROM {$this->function_name}_comment WHERE comment_id = {$comment_id} ")->result_array();
		
		//이미 삭제됬을 경우 실패 //1차원의 0이 존재하지 않을경우
		if(!array_key_exists('0', $data_c)) {
			echo "잘못된 접근입니다.";
			return 0;
			//에러시 0을 리턴하게 되고 (삭제처리 안됨)
			//그대로 진행되므로 에러 없음
		} 

		//id 조작 여부를 확인
		if ($_POST['id_check'] !=$data_c[0]['id_check']){
			echo "잘못된 접근입니다.";
			return 0;
		}

		//채택된 댓글은 삭제불가
		if(isset($data_c[0]['selected_a'])) {
			//채택이 안됬고 댓글이 존재할때 
			if( $data_c[0]['selected_a'] == 1 ) {
				//댓글이 있을경우 수정 전혀 불가
				echo "채택된 댓글은 삭제 불가합니다.";
				return 0;
			}
		} 


		$this->Vui_sys->saved_id = $data_c[0]['user_id'];
		$this->Vui_sys->saved_pwd = $data_c[0]['passwd'];	
		if(!$this->Vui_sys->trans_pwd()  || !$this->Vui_sys->verify_board_user()) { echo "패스워드가 틀립니다."; return 0; } //유저 검증

				//외부값: comment_id		
		$this->db_minfo->query("DELETE from {$this->function_name}_comment WHERE comment_id = {$comment_id} ");
		//글의 댓글 갯수 정보를 업데이트함
				//외부값: 없음
		$this->db_minfo->query("UPDATE {$this->function_name}_board set count_comment = count_comment - 1 where read_id = '{$data_c['0']['read_id']}'  ");
	}  


	//해당 답/댓글 기준으로 최신화된 정보를 보냄
	public function lately_comment () {
		//print_r($_POST);

		if(!is_numeric($_POST['global_read_id'])) return 0;
		$_POST['global_read_id'] = (int)$_POST['global_read_id'];
				//외부값: _POST['global_read_id'] 
		$data_c =  $this->db_minfo->query("SELECT * FROM {$this->function_name}_comment AS A LEFT JOIN member.user_sub_info AS B ON  A.user_id = B.identification_id WHERE A.read_id = '{$_POST['global_read_id']}' ORDER BY group_1_id ASC, comment_id ASC ")->result_array();
		
		$check_login = $this->session->userdata('logged_in');
		$user_id = $this->session->userdata('user_id');
		$super_user = $this->session->userdata('guitar_id');

		$this->load->model('Pchk_sys'); 
		foreach ($data_c as $key => $entry) {
			if($data_c[$key]['report_result'] != 2) {
				//해독시 변환해줘야지 테그 적용됨
				$data_c[$key]['content'] = str_replace("\r\n", "<br>",$data_c[$key]['content']);
			} else {
				//신고 누적 됬을경우 내용 가림
				$data_c[$key]['content'] = "[신고누적되어 Hide처리된 댓글입니다.]";
			}
			
			//로그인 안되어 있으면 본인꺼인지 검사안함
			//개발노트 7-2
			if($check_login) {
				if($data_c[$key]['user_id'] == $user_id) {
					$data_c[$key]['check_owner'] = 1;
				} else {
					if($data_c[$key]['user_id'] == -1 ) {
						$data_c[$key]['check_owner'] = -1;

					} else {
						$data_c[$key]['check_owner'] = 0;
					}
				}
			} else {
				//로그인이 안되어 있으면 익명인지만 확인
				if($data_c[$key]['user_id'] == -1) {
					$data_c[$key]['check_owner'] = -1;
				} else {
					//익명외에는 모두 회원
					$data_c[$key]['check_owner'] = 2;
				}
			}

			//운영자 수준의 comment인지 확인
			if($check_login) {
				if ($data_c[$key]['super_user'] != 0 ) {
					if ( $data_c[$key]['user_id'] == $user_id ) {
						$data_c[$key]['super_user'] = 1;
					} else {
						$data_c[$key]['super_user'] = 0;
					}
				} else {
					$data_c[$key]['super_user'] = 0;
				}
			} else {
				$data_c[$key]['super_user'] = 0;
			}

			$data_c[$key]['encryption_report'] = $this->Pchk_sys->md5_encrypt($data_c[$key]['comment_id']);
				

			//필요없는 정보 빼기
			unset($data_c[$key]['ip_address']);
			unset($data_c[$key]['passwd'] );
			unset($data_c[$key]['user_id'] );
			unset($data_c[$key]['garbage_check']);
			unset($data_c[$key]['identification_id']);
			unset($data_c[$key]['user_intro']);
		}
		$data_c = json_encode($data_c, JSON_PRETTY_PRINT);
		print_r($data_c);
	}


}


require_once('./static/include/model/sendback_site.php');
