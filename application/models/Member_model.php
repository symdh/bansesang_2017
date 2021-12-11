<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Member_model extends CI_model { 
	function __construct(){ 
		parent::__construct(); 
		$this->db_member = $this->load->database('default', TRUE);
		date_default_timezone_set("Asia/Seoul");

	}

	public function try_login() { //변형완료
		$this->load->model('Spam_sys'); //recaptcha 스팸 감지
		if( !$this->Spam_sys->watch_recaptcha() )  { sendback_site('reject_access', 'login', '', 0, 'member'); return 0; }

		if(!isset($_POST['user_email_id']) || !isset($_POST['user_passwd']))  {
			sendback_site('reject_access', 'login', '', 0, 'member');
	   		return 0;
	 	} else if (verify_id($_POST['user_email_id']) == 0 || check_passwd($_POST['user_passwd'], 6) == 0) { 
	 		echo "<script>alert('아이디 또는 비밀번호 형식이 틀립니다.')</script>";
	 		sendback_site('', 'login', '', 0, 'member');
	 		return 0;
	 	}  

		$email = $this->db_member->escape($_POST['user_email_id']);
		$user_passwd = $_POST['user_passwd']; //필터 불필요
				//내부값: 없음    //외부값: email
		$result = $this->db_member->query("SELECT * FROM user_info where email = {$email}")->result_array();

		if(!isset($result[0]['email'])) {
			
			echo "<script>alert('존재하지 않는 아이디 입니다.')</script>";
			sendback_site('', 'login', '', 0, 'member');
			return;
		}

		//암호화된 패스워드 비교 (로그인 되어있을경우 덮어씌움)
      if(password_verify($user_passwd, $result[0]['passwd'])){
      		
	      	if(isset($_POST['autologin']) && $_POST['autologin'] == true) {
	      		//해당내용 개발노트 확인 할것
	    			setcookie('ci_sessions', $_COOKIE['ci_sessions'], strtotime('now')-50000,'/' , '.bansesang.com');
					unset($_COOKIE['ci_sessions']);
					setcookie('autologin', 1, strtotime('now')+31536000,'/' , '.bansesang.com');
					$_COOKIE['autologin'] = 1;
					//즉각 적용을 위한 슈퍼 변수 (개발자노트 확인)
					$_COOKIE['varonce_autologin'] = 1; 
	      		$this->load->library('session');

	      	} else if (isset($_COOKIE['autologin']) && $_COOKIE['autologin'] ) {
	      		setcookie('autologin', 1, time()-3600, '/' , '.bansesang.com');
	      		echo "<script>alert('쿠키 에러. 다시 로그인 해주세요.');</script>";
	      		sendback_site('', 'login', '', 0, 'member');
	      		return 0;
	      	} else 
	      		$this->load->library('session');

	      	$img_url = $this->db_member->query("SELECT user_img FROM user_sub_info where identification_id = {$result[0]['identification_id']} ")->result_array(); //본인 이미지 주소, 세션저장

	      	//기존 세션 정보 파괴
	      	$this->session->unset_userdata('img_url');
	      	$this->session->unset_userdata('hash_salt1');
	      	$this->session->unset_userdata('b_report_user');
	      	$this->session->unset_userdata('b_report_time');
	      	$this->session->unset_userdata('b_spam_user');
	      	$this->session->unset_userdata('b_spam_time');
	      	$this->session->unset_userdata('w_report_n');
	      	$this->session->unset_userdata('w_report');
	      	$this->session->unset_userdata('w_spam_comment_n');
	      	$this->session->unset_userdata('w_spam_comment');
	      	$this->session->unset_userdata('w_spam_write_n');
	      	$this->session->unset_userdata('w_spam_write');
	      	
	      	//맞다면 로그인 정보 넣어줌
	      $newdata  = array(
				'email' => $result[0]['email'],
				'nickname' => $result[0]['nickname'],
				'user_id' => $result[0]['identification_id'],
				'logged_in' => TRUE,
				'guitar_id' => $result[0]['super_user'],
				'img_url' => $img_url[0]['user_img']
			);
			$this->session->set_userdata($newdata);
			
			//로그 위치 변경
			$this->load->model('Log_a');
			$this->Log_a->success_login();

			// print_r($result); // return;
			echo "<meta http-equiv='refresh' content='0; url=/main'>";
			return;
		} else{  //비밀번호 틀릴시 로그 저장
			$this->load->model('Log_m');
			$this->Log_m->log_try_login($result[0]['identification_id']);

			sendback_site('wrong_passwd', 'login', '', 0, 'member');
			return;
		}
	}

	public function try_logout() { //건들필요x
		
		if($this->session->userdata('logged_in')) { 
			setcookie('autologin', 1, time()-3600, '/' , '.bansesang.com');
	 		$this->session->sess_destroy();
	 		sendback_site('', '', '', 0, 'main');
	 		return;
	 	} else {
	 		//echo "<script>alert('이미 로그아웃 상태입니다.')</script>";
	 		sendback_site('', 'login', '', 0, 'member');
	 		return;
	 	}
		
	}

	//아이디 찾기
	public function try_findid () { //변형완료
		$this->load->model('Spam_sys'); //recaptcha 스팸 감지
		if( !$this->Spam_sys->watch_recaptcha() )  { echo '<script>alert(\"스팸방지 창에 체크 해주세요.\");</script>'; return 0; }

		$user_name = $_POST['user_name'];
		$user_nickname = $_POST['user_nickname'];

		//이름, 전화번호 유효성 검사
	   if( !check_user_name($user_name) || !check_nickname($user_nickname) ) {
	   		// sendback_site('', 'findid', '', 0, 'member');
	   		return 0;
	   }

	   	$user_name = $this->db->escape($_POST['user_name']);
		$user_nickname = $this->db->escape($_POST['user_nickname']);
				//내부값: 없음    //외부값: 모두
		$result = $this->db_member->query("SELECT `email` FROM user_info where name = {$user_name} AND nickname = binary({$user_nickname}) ")->result_array();

		if(!isset($result[0]['email'])) { 
			echo '<script>alert("일치하는 정보가 없습니다.");</script>';
			// sendback_site('', 'findid', '', 0, 'member');
	   		return 0;
		} else {

			//이메일 일부를 가리기
			foreach ($result as $key => $value) {
				$string =explode('@' , $value['email']);
				$blind_str = substr($string[0], -(int)strlen($string[0])/2); // 역으루 가릴부분 추출		
				$blind_pattern = "/{$blind_str}$/";
				$show_str = '*';
				for($i = 1; $i < (int)strlen($string[0])/2; $i++ )
					$show_str .=  '*';

	 			$string[0] = preg_replace($blind_pattern ,$show_str ,$string[0]); 
	 			$result[$key]['email'] = $string[0];
			}

			$result = json_encode($result, JSON_PRETTY_PRINT);
 			print_r($result);
		}

	}

	//비밀번호 찾기 (인증 방식)
	public function try_findpwd () { //변형완료
		$this->load->model('Spam_sys'); //recaptcha 스팸 감지
		if( !$this->Spam_sys->watch_recaptcha() )  { echo "<span style= 'color:red; font-size:12px;' >스팸방지 창에 체크 해주세요.</span>"; return 0; }

		$user_email_id = $_POST['user_email_id'];
		$user_name = $_POST['user_name'];

 	 	if (verify_id($user_email_id) == 0) { 
 			echo "<span style= 'color:red; font-size:12px;' >아이디 형식 확인 바람</span>";
 			return 0;
 		}  

 		//이름, 전화번호 유효성 검사
	   if(!check_user_name($user_name)  ) {
	 	  	echo "<span style= 'color:red; font-size:12px;' >이름 형식 확인 바람</span>";
	   		// sendback_site('', 'findpwd', '', 0, 'member');
	   		return 0;
	   }

		$check_email = $this->db->escape($_POST['user_email_id']);
		$user_name = $this->db->escape($_POST['user_name']);
				//내부값: 없음    //외부값: 모두
		$result = $this->db_member->query("SELECT `identification_id`, `email` FROM user_info where name = {$user_name} AND email ={$check_email} ")->result_array();

		if(!isset($result[0]['email'])) { 
			$result_return['0'] = "일치하는 정보가 없습니다.";
			$result_return = json_encode($result_return, JSON_PRETTY_PRINT);
 			print_r($result_return);

	   		return 0;
		} else {

			//인증을 위한 암호화코드 생성 
			$server_date = strtotime('now');
			$hash = password_hash($server_date, PASSWORD_DEFAULT); 
			$watch_log = md5($server_date).substr($hash, -5);
			$watch_log = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $watch_log); //특수문자 제거
			$watch_log = $this->db->escape($watch_log);
			$watch_log = str_replace('\'', '',$watch_log);
			$user_email_id = $this->db->escape($result[0]['email']);


			$check_send = $this->db_member->query("SELECT * FROM manage_user_info where user_id = {$result[0]['identification_id']} AND code = 1 ")->result_array();
			if(isset($check_send[0]['num'])) { 
						//내부값: 모두    //외부값: 없음
				$this->db_member->query("UPDATE manage_user_info set send_num = send_num + 1, sendtime = {$server_date}, auth = '{$watch_log}' where user_id = {$result[0]['identification_id']} AND code = 1 ");

			} else {
				
					// 내부값: identification_id, watch_log, server_date    //외부값: user_email_id
				$this->db_member->query("INSERT INTO `manage_user_info` (`code`,`user_id`, `email`, `auth`, `sendtime`) VALUES (1, {$result[0]['identification_id']}, {$user_email_id}, '{$watch_log}','{$server_date}' ) ");

			}
			
			$result_return['0'] = "<p><a href = 'http://www.bansesang.com/member/trychangepwd/{$watch_log}' style = 'font-size:20px;'>비밀번호 변경하기 (클릭)</a></p>";

			$result_return = json_encode($result_return, JSON_PRETTY_PRINT);
 			print_r($result_return);
		}
	}

	//이메일 중복확인
	public function check_email($email = 'none', $echo_err_code = '1') { //변형완료
		if($email == 'none') {
			if(!isset($_POST['email']) )  { //변수 없음
				if($echo_err_code) echo '1';
				return 0;
	 	 	}

	 	 	if (verify_id($_POST['email']) == 0) {  //형식 체크 
	 			if($echo_err_code) echo '2';
				return 0;
	 		}  

			$check_email = $this->db->escape($_POST['email']);
		} else {
			if (verify_id($email) == 0) {  //형식 체크
	 			if($echo_err_code) echo '2';
				return 0;
	 		} 

			$check_email = $this->db->escape($email);
		}

				//내부값: 없음    //외부값: check_email
		$result = $this->db_member->query("SELECT `email` FROM user_info where email = {$check_email}")->result_array();
		$result_1 = $this->db_member->query("SELECT `email` FROM stand_by_user where email = {$check_email}")->result_array(); //인증 대기중인거 확인
		
		if(isset($result[0]['email'])) { // 중복
			if($echo_err_code) echo '11';
			return 0;
		} else if (isset($result_1[0]['email'])){ // 중복
			if($echo_err_code) echo '12';
			return 0;
		} else { //정상 처리
			if($echo_err_code) echo '0';
			return 1;
		} 
	}

	//닉네임 중복확인
	public function check_nickname($nickname = 'none', $echo_err_code = '1') { //건들필요x
		if($nickname == 'none') {
			if(!isset($_POST['nickname']) )  { //변수 없음
	   			if($echo_err_code) echo '1';
				return 0;
	 	 	}
	 	 	if (check_nickname($_POST['nickname']) == 0) { //형식 체크 
	 			if($echo_err_code) echo '2';
				return 0;
	 		} 

			$check_nickname = $this->db->escape($_POST['nickname']);
		} else {
			if (check_nickname($nickname) == 0) { //형식 체크
	 			if($echo_err_code) echo '2';
				return 0;
	 		} 

			$check_nickname = $this->db->escape($nickname);
		}

				//내부값: 없음    //외부값: check_nickname
		$result = $this->db_member->query("SELECT `nickname` FROM check_nickname where nickname = binary({$check_nickname})")->result_array();
		$result_1 = $this->db_member->query("SELECT `nickname` FROM stand_by_user where nickname = binary({$check_nickname})")->result_array();
		
		if(isset($result[0]['nickname'])) { // 중복
			if($echo_err_code) echo '11';
			return 0;
		} else if(isset($result_1[0]['nickname'])) { // 중복
			if($echo_err_code) echo '11';
			return 0;
		} else { //정상 처리
			if($echo_err_code) echo '0';
			return 1;
		}
	}

	//업로드 이미지 처리
	public function upload_user_image ($Domain = 'http://img.bansesang.com') { //건들필요x
		
		if(isset($_FILES['user_image']['tmp_name']) && !empty($_FILES['user_image']['tmp_name'])) {
		   	
		   	$file = $_FILES['user_image']['tmp_name'];
		   	$file_type = $_FILES['user_image']['type'];

			$set_width = 180;
			$set_height = 135;
			$rate =  $set_width/$set_height;  //가로:세로, '가로 = 세로 *$rate' 를 말함

			//이미 업로드된 파일(검사된 파일)을 사용하기 때문에 정규식을 사용하여 확장자를 검사하여도 문제 없음
			$pattern[0] ='/JPG$/i';
			$pattern[1] ='/JPE$/i';
			$pattern[2] ='/JPEG$/i';
			$pattern[3] ='/PNG$/i';
			$pattern[4] ='/BMP$/i';

			for($i=0; $i < count($pattern)+1; $i++) {

				preg_match_all($pattern[$i] ,$file_type ,$matches); 
				
				if(isset($matches[0][0]) ) {
					
					//파일로부터 이미지를 읽어옵니다
					if($i == 0) {
						$take_img = ImageCreateFromJPEG($file);
						break;
					} else if ( $i == 1) {
						$take_img = ImageCreateFromJPEG($file);
						break;
					} else if ( $i == 2) {
						$take_img = ImageCreateFromJPEG($file);
						break;
					} else if ($i == 3) {
						$take_img = ImageCreateFromPNG($file);
						break;
					} else if ($i == 4) {
						$take_img = ImageCreateFromBMP($file);
						break;
					} 
				} 
			}

			$img_info = getImageSize($file);//원본이미지의 정보를 얻어옵니다
			$take_width = $img_info[0];
			$take_height = $img_info[1];

			//설정된 값이 리사이즈 시킬 값임
			$resize_width = $set_width;
			$resize_height = $set_height;

			if( $take_width >= $set_width && $take_height >= $set_height) {    
				//작은쪽 기준으로 가져와야됨
				if( $take_width > $take_height*$rate ) {  //검증
					  //아래꺼랑 반대 개념
					  $take_width = $take_height*$rate;

				} else if ( $take_width < $take_height*$rate) {   //검증
					  //가져올 그림의 세로값이 더 클경우 가로값기준으로 세로값을 짤라서 가져옴
					  $take_height = $take_width/$rate;

				} else if ( $take_width == $take_height*$rate) {  
					  //비율이 같을 경우 그대로 진행함
				} else {
					
			     		return 0;
				}
			} //여기서 부터는 짜르거나 그냥 가져옴 
			else if( $take_width <= $set_width && $take_height <= $set_height) {   
				//모두 작을 때는 그냥 그대로 가도됨
				$resize_width = $take_width;
				$resize_height = $take_height;

			} else if( $take_width < $set_width && $take_height >= $set_height) {  
				//세로만 클때는 세로만 짤라서 가져옴 
				$take_height = $set_height;

				$resize_width = $take_width; //가로 크기는 작으므로 resize 값은 그림값
				$resize_height = $set_height;
			} else if( $take_width >= $set_width && $take_height < $set_height) {    
				//가로만 클때는 가로만 짤라서 가져옴 
				$take_width = $set_width;

				$resize_width = $set_width; 
				$resize_height = $take_height; //세로 크기는 작으므로 resize 값은 그림값
			} else {
				
			     	return 0;
			}

			
			$resize_img = imagecreatetruecolor($resize_width, $resize_height); //타겟이미지를 생성합니다

			//x,y 축 설정
			$std_x_take_img = 0;
			$std_y_take_img = 0;
			$std_x_resize_img = 0;
			$std_y_resize_img = 0;

			//타겟이미지에 원하는 사이즈의 이미지를 저장합니다
			imagecopyresampled($resize_img, $take_img, $std_x_take_img, $std_y_take_img, $std_x_resize_img, $std_y_resize_img, $resize_width, $resize_height, $take_width, $take_height); 
			                                 //저장할 크기(size를 조정) //가져올 크기 (짤라서 가져옴) 
			ImageInterlace($resize_img);

			 //실제로 이미지파일을 생성합니다
			 if ( $i < 3) {
			 	 ImageJPEG($resize_img, $file,100); 
			} else if ($i == 3) {
				  ImagePNG($resize_img, $file,9); 
			} else if ($i == 4) {
				  ImageBMP($resize_img, $file,100); 
			}

			ImageDestroy($resize_img);
			ImageDestroy($take_img);
		
			$upload_path = '/static/upload/img-user/';
			$config['upload_path'] = '.'.$upload_path; //상대주소 처리
			$config['allowed_types'] = 'png|jpeg|bmp|jpg|jpe';
			$config['encrypt_name'] = TRUE;
			//max_size, max_width, max_height 등 설정가능
			$filename = $this->security->sanitize_filename($_FILES['user_image']['name']);
			//echo $filename;
			
			$config['file_name'] = $filename;


			$this->load->library('upload', $config);
			if ( !$this->upload->do_upload('user_image')) {
			   return 0;
			 } else {
			   $data = array('upload_img' => $this->upload->data());
			   //var_dump($data);
			} 
		   
		   $user_img = $Domain.$upload_path.$data["upload_img"]["file_name"];
          return $user_img;
		
			} else {
				$user_img = '/static/img/no-image.jpg';
			    return $user_img;
			} 
	}

	//추가한거 있음
	public function modify_user_image ($alert = 1, $Domain = 'http://img.bansesang.com') { 

		$user_id = $this->db->escape($this->session->userdata('user_id'));
				//내부값: 없음    //외부값: user_id
		$user_info = $this->db_member->query("SELECT user_img from user_sub_info WHERE identification_id = {$user_id}  ")->result_array();

		$user_info[0]['user_img'] = str_replace($Domain,'',$user_info[0]['user_img']);
		if($user_info[0]['user_img'] !='/static/img/no-image.jpg' &&  file_exists('.'.$user_info[0]['user_img']) ){
			unlink('.'.$user_info[0]['user_img']);
		}
		
		//이미지 업로드
		   if( ($user_img =$this->upload_user_image())=== 0) {
		   	 
		   	  if($alert) {
		   		 echo '<script>alert("파일업로드 실패. 문의바람");</script>';
			 	 sendback_site('', 'userpage', '', 0, 'member');
				}
			    return 0;
		   } 

		   	//세션에 바로 업데이트
		   	$this->session->unset_userdata('img_url');
			$newdata  = array(
				'img_url' => $user_img
			);
			$this->session->set_userdata($newdata);


		   $user_img = $this->db->escape($user_img);
		   			//내부값: 없음    //외부값: user_img, user_id
		   $this->db_member->query("UPDATE user_sub_info set user_img = {$user_img} where identification_id = {$user_id} ");

		   //로그 기록
		   	$this->load->model('Log_m');
			$this->Log_m->log_user_info("사진 변경");

		   if($alert) {
		   	 	echo '<script>alert("사진 변경 완료.");</script>';
		    	sendback_site('', 'userpage', '', 0, 'member');
		   	}



		    return 1;
	}

	public function modify_user_intro () { //건들필요x
		$user_intro = htmlspecialchars($_POST['user_introduce'], ENT_QUOTES);
		$user_id = $this->db->escape($this->session->userdata('user_id'));
		$user_intro = $this->db->escape($user_intro);
				//내부값: 없음    //외부값: user_intro, user_id
		$this->db_member->query("UPDATE user_sub_info set user_intro = {$user_intro} where identification_id = {$user_id} ");

		//로그 기록
		$this->load->model('Log_m');
		$this->Log_m->log_user_info("소개글 변경");

		echo '<script>alert("본인소개 변경 완료.");</script>';
		sendback_site('', 'userpage', '', 0, 'member');
	}

	public function modify_user_passwd() { //건들필요x

		$user_recent_passwd = $_POST['user_recent_passwd'];
		$user_passwd = $_POST['user_passwd'];
		$user_passwd2 = $_POST['user_passwd2'];

		//비밀번호 유효성 검사
	   if ($user_passwd != $user_passwd2 || check_passwd($user_passwd, 6) == false || check_passwd($user_recent_passwd, 6) == false  ) {     
	      sendback_site('wrong_passwd_2', 'userpage', '', 0, 'member');
	      return;
	   } 

		$user_id = $this->db->escape($this->session->userdata('user_id'));
				//내부값: 없음    //외부값: user_id
		$result = $this->db_member->query("SELECT passwd from user_info where identification_id = {$user_id} ")->result_array();
		
		//암호화된 패스워드 비교
   		if(password_verify($user_recent_passwd, $result[0]['passwd'])) {
     		 	
     		//비밀번호 암호화 (추가 암호화 필요하면 check_user 컬럼 생성해서 쓸것)
	  		$hash = password_hash($user_passwd, PASSWORD_DEFAULT); 
	  		$hash = $this->db->escape($hash);
	  				//내부값: hash    //외부값: user_id 
	  		$this->db_member->query("UPDATE user_info set passwd = {$hash} where identification_id = {$user_id} ");
	  		echo '<script>alert("비밀번호 변경완료. 다시 로그인해주세요.");</script>';

	 		//로그 기록
			$this->load->model('Log_m');
			$this->Log_m->log_user_info("패스워드 변경");

	  		$this->session->sess_destroy();
	  		sendback_site('', 'login', '', 0, 'member');
     		return;
			   
     	} else {
     		sendback_site('wrong_passwd', 'userpage', '', 0, 'member');
		   return;
       }

	}

	public function insert_member() { //변형완료
		$this->load->model('Spam_sys'); //recaptcha 스팸 감지
		if( !$this->Spam_sys->watch_recaptcha() )  { sendback_site('reject_access', 'join', '', 0, 'member'); return 0; }


		//왜 안접히냐 ㅡㅡ
	   if( !isset($_POST['user_email_id']) || !isset($_POST['user_nickname']) || !isset($_POST['user_passwd']) || !isset($_POST['user_passwd2']) || !isset($_POST['user_name']) || !isset($_POST['user_introduce'])) {
	   		sendback_site('reject_access', 'join', '', 0, 'member');
	   		return 0;
	   } 

		$user_email_id = $_POST['user_email_id'];
		$user_nickname = $_POST['user_nickname'];
		$user_passwd = $_POST['user_passwd'];
		$user_passwd2 = $_POST['user_passwd2'];
		$user_name = $_POST['user_name'];
		$user_intro = htmlspecialchars($_POST['user_introduce'], ENT_QUOTES);
 
	   if(verify_id($user_email_id) == 0) { 
	   	sendback_site('', 'join', '', 0, 'member');
	   	return 0;
	   }
	   	//이메일 중복검사
		if($this->check_email($user_email_id, 0) == 0) {
			echo '<script>alert("중복된 이메일 입니다.");</script>';
	      sendback_site('', 'join', '', 0, 'member');
	      return;
		} 

		//닉네임 검사
		if(check_nickname($user_nickname) == false) {
			sendback_site('wrong_nickname', 'join', '', 0, 'member');
			return;
		} 
	   	//닉네임 중복검사
		if($this->check_nickname($user_nickname, 0) == 0) {
			echo '<script>alert("중복된 닉네임 입니다.");</script>';
	      sendback_site('', 'join', '', 0, 'member');
	      return;
		} 

		//비밀번호 유효성 검사
	   if ($user_passwd != $user_passwd2 || check_passwd($user_passwd, 6) == false) {     
	      sendback_site('wrong_passwd_2', 'join', '', 0, 'member');
	      return;
	   } 

		//이름 유효성 검사
	   if(check_user_name($user_name) == 0) {
	   		sendback_site('', 'join', '', 0, 'member');
	   		return 0;
	   }

		//이미지 업로드
	   if( ($user_img =$this->upload_user_image())=== 0) {
	   	
	   	 echo '<script>alert("파일업로드 실패. 문의바람");</script>';
		  sendback_site('', 'join', '', 0, 'member');
		    return 0;
	   } 

	   //날짜 넣기
	   $server_date = strtotime('now');

	   //비밀번호 암호화 (추가 암호화 필요하면 check_user 컬럼 생성해서 쓸것)
	   $hash = password_hash($user_passwd, PASSWORD_DEFAULT); 

	   	$user_email_id = $this->db->escape($_POST['user_email_id']);
		$user_nickname = $this->db->escape($_POST['user_nickname']);
		$hash = $this->db->escape($hash);
		$user_name = $this->db->escape($_POST['user_name']);
		$ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);
		$user_intro = $this->db->escape($user_intro);
		$user_img = $this->db->escape($user_img);

	  	//이미 인증대기중이면 거부
				//내부값: 없음    //외부값: ip_address
		$result = $this->db_member->query("SELECT * from stand_by_user where ip = INET_ATON({$ip_address}) ")->result_array();
		if(isset($result[0])) {
			echo "<script>alert('해당 ip로 이미 가입 인증 대기 중인 이메일이 있습니다. \\n메일인증 진행, 문의 또는 1일 후 다시 시도 바랍니다.');</script>";
			sendback_site('', 'login', '', 0, 'member');
			return;
		}

		//외부값: 다수
		$this->db_member->query("INSERT INTO `user_info` (`join_date`,`email`,`nickname`,`passwd`,`gender`,`name`,`phone_number`) VALUES ('{$server_date}', {$user_email_id}, {$user_nickname}, {$hash}, '남자', {$user_name}, 0) ");
		$result = $this->db_member->query("SELECT identification_id from user_info where nickname = binary({$user_nickname}) AND email = {$user_email_id}")->result_array();
		$user_id = $result[0]['identification_id'];
		$user_id = $this->db_member->escape($user_id);
		$this->db_member->query("INSERT INTO `user_sub_info` (`identification_id`,`user_img`,`user_intro`) VALUES ({$user_id}, {$user_img}, {$user_intro})   ");
	   	$this->db_member->query("INSERT INTO `check_nickname` (`user_id`, `nickname`) VALUES ({$user_id}, {$user_nickname})");

		echo "<script>alert('가입이 되었습니다. \\n로그인페이지로 이동합니다.');</script>";
		sendback_site('', 'login', '', 0, 'member');
	   return;
	}

	public function download_userinfo() { //건들필요x

		//컨트롤에서 로그인 안하면 거부
		$user_id = $this->db->escape($this->session->userdata('user_id'));
				//내부값: 없음    //외부값: user_id
		$user_info = $this->db_member->query("SELECT * from user_info JOIN user_sub_info ON user_info.identification_id = {$user_id}  AND user_info.identification_id  = user_sub_info.identification_id   ")->result_array();
		
		if(!isset($user_info[0])) {
			echo "<script>alert('잘못된 접근 입니다.');</script>";
			$this->session->sess_destroy();
			return 0;
		}	

		unset($user_info[0]['identification_id']);
		unset($user_info[0]['passwd']);
		unset($user_info[0]['name']);
		unset($user_info[0]['gender']);
		unset($user_info[0]['phone_number']);

		return $user_info[0];

	}

	public function break_away_user() { //변형완료
		$user_id = $this->db->escape($this->session->userdata('user_id'));
				//내부값: 없음    //외부값: user_id
		$user_info = $this->db_member->query("SELECT * from user_info where identification_id = {$user_id} ")->result_array();

		if(!isset($user_info[0])) {
			echo "<script>alert('잘못된 접근 입니다.');</script>";
			$this->session->sess_destroy();
			return 0;
		}	
 
		if( password_verify($_POST['passwd'], $user_info[0]['passwd']) ) {

			if($this->modify_user_image(0) === 0) {
				 echo '<script>alert("탈퇴 실패. 즉각 문의 바람");</script>';
				sendback_site('', 'userpage', 'userout', 0, 'member');
				return 0;
			}

					//외부값: user_id
			$this->db_member->query("DELETE from user_sub_info where identification_id = {$user_id} ");
					//외부값: user_id
			$this->db_member->query("DELETE from log_user_info where identification_id = {$user_id} ");
					//외부값: user_id
			$this->db_member->query("DELETE from user_info where identification_id = {$user_id} ");

			setcookie('userInputId','x',strtotime('now')-3600,'/member/login/','.bansesang.com');
			$this->session->sess_destroy();
			echo "<script>alert('탈퇴 완료되었습니다.');</script>";
			sendback_site('', '', '', 0, 'main');
			return 1;
		} else {
			sendback_site('wrong_passwd', 'userpage', 'userout', 0, 'member');
			return 0;
		}
	}

	//메일 비밀번호 찾기 인증시 작동
	public function certification_pwd($auth, $change_pwd = 0) {  //건들필요x
		$auth = $this->db_member->escape($auth);
				//내부값: 없음    //외부값: auth
		$result = $this->db_member->query("SELECT *  from manage_user_info where auth = {$auth} ")->result_array();

		if(isset($result[0]['num'])) {

			if(!$change_pwd) {
				unset($result[0]['num']);
				unset($result[0]['code']);
				unset($result[0]['user_id']);
				return $result;
			} else if (!isset($_POST['user_passwd']) || !isset($_POST['user_passwd']) ){
				
				return 0;
			} else {
				$user_passwd = $_POST['user_passwd'];
				$user_passwd2 = $_POST['user_passwd2'];				
				if ($user_passwd != $user_passwd2 || check_passwd($user_passwd, 6) == false) {     
	      			sendback_site('wrong_passwd_2', '', '', 0, 'main');
	      			return 0;
	  			} 

	  			//비밀번호 암호화 (추가 암호화 필요하면 check_user 컬럼 생성해서 쓸것)
	   			$hash = password_hash($user_passwd, PASSWORD_DEFAULT); 
	   			$hash = $this->db->escape($hash);

	   					//내부값: hash, result[0]['user_id']    //외부값: auth
				$this->db_member->query("UPDATE user_info set passwd = {$hash} where identification_id = {$result[0]['user_id']} ");
				$this->db_member->query("DELETE from manage_user_info where  auth = {$auth} ");
				$result_2 = $this->db_member->query("SELECT * from user_info where identification_id = '{$result[0]['user_id']}' ")->result_array();
				if(isset($result_2[0]['identification_id'])) { //다시 불러온 정보가 정상적으로 존재하면 통과
					echo "<script> alert('정상적으로 변경 되었습니다.'); </script>";
					sendback_site('', 'login', '', 0, 'member');
				} else  {
					echo "<script> alert('회원정보가 없습니다. 문의해주세요.'); </script>";
					sendback_site('', 'findpwd', '', 0, 'member');
				}
			}

		} else {
			echo "<script> alert('유효하지 않은 인증입니다.'); </script>";
			sendback_site('', 'findpwd', '', 0, 'member');
		}
	} 
}

//닉네임 감시
function check_nickname ($user_nickname) {
	//닉네임 유효성 검사
   	//한글, 영어 따로 측정이됨 (영어 1, 한글 2바이트)
   	//UTF-8로 할경우 3byte로 측정이됨
   $length_user_nickname = strlen(iconv('UTF-8','CP949',$user_nickname));
   if ($length_user_nickname < 3 ) {
      return 0;
   } else if ($length_user_nickname > 12) {
      return 0;
   } else {
   }
   	//닉네임 특수문자 제한 
   $special_char = ' ~!#$%^&*()-_=+|₩₩{}[];:"₩"<>,?₩/@.'; 
   $length_special_char = mb_strlen($special_char, 'utf-8');
   $length_user_nickname = mb_strlen($user_nickname, 'utf-8');

   for($i = 0 ; $i < $length_user_nickname ; $i++){
      for($j=0; $j < $length_special_char ;$j++) { 
      	
         if($special_char{$j} == $user_nickname{$i}) {
            return 0;
         }
      }
   }

   return true;
}

//패스워드 감시
function check_passwd($user_passwd, $min_num = 1) {
	
	//길이제한
   $length_user_passwd = mb_strlen($user_passwd, 'utf-8');
   if ($length_user_passwd < $min_num || $length_user_passwd > 20) {
      return 0;
   }
  	//비밀번호 띄어쓰기 및 텝 사용여부
   $check_user_passwd_1 = explode(" ", $user_passwd); 
   $check_user_passwd_2 = explode("\t", $user_passwd); 
   if (count($check_user_passwd_1) > 1 || count($check_user_passwd_2) > 1) {
      return 0;
   }

   return true;
}

//이름 감시
function check_user_name ($user_name) {
	//한글만 입력가능
   $check_user_name = preg_match('/^[가-힣]{2,4}/', $user_name);
   	//한글을 입력 안했을때
   $check_user_name_2 = preg_match('/[^가-힣]/', $user_name);
   if($check_user_name==true && $check_user_name_2 ==false) {
     //echo $check_email;
   } else {
     echo '<script>alert("이름은 한글만 됩니다.");</script>';
     return 0;
   }

   	//이름 갯수 수정할것
   $length_user_name = mb_strlen($user_name, 'utf-8');
   if($length_user_name < 2) {
      echo '<script>alert("이름은 2글자이상 입니다.");</script>';
      return 0;
   } else if ($length_user_name > 4) {
      echo '<script>alert("이름은 4글자이하 입니다.");</script>';
      return 0;
   }

   return 1;
}


function verify_id ($user_id, $min_num = 6) { //이메일 대용 잠깐 쓸꺼

	
	$special_char = '~!#$%^&*()-_=+|₩₩{}[];:"₩"<>,?₩/';  //아이디 특수문제 제한 
	$length_special_char = mb_strlen($special_char, 'utf-8');
	$length_user_email_id = mb_strlen($user_id, 'utf-8');
	for($i = 0 ; $i < $user_id ; $i++){
	   for($j=0; $j < $length_special_char ;$j++) { 
	      if($special_char{$j} == $user_id{$i}) {
	         return 0;
	      }
	   }
	}

	//길이제한
   $length_user_passwd = mb_strlen($user_id, 'utf-8');
   if ($length_user_passwd < $min_num || $length_user_passwd > 20) {
      return 0;
   }
  	//비밀번호 띄어쓰기 및 텝 사용여부
   $check_user_passwd_1 = explode(" ", $user_id); 
   $check_user_passwd_2 = explode("\t", $user_id); 
   if (count($check_user_passwd_1) > 1 || count($check_user_passwd_2) > 1) {
      return 0;
   }

   return true;

}

require_once('./static/include/model/sendback_site.php');
