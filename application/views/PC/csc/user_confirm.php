<?php //$_GET으로 게시판 주소 설정함 
		//read 부분 ajax로 통신함

echo "<input type='hidden' id = 'ci_t' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />";
?>


<div id = 'csc_CA_main'>
	<div id ='csc_CA_wrapper'>

		<div id = 'csc_CA_shape_for_sub_menu_info'>	
			<br>
			<ul>
				<li>
					비회원일경우 신고내역을 볼 수 없습니다.
				</li>

				<li>
					비회원의 재로그인은 새로고침
				</li>

				<li>
					익명 제출로 회원 문의내역을 볼 수 없습니다. 
				</li>
			
				
<?php 
	//$_POST['check_login']은 model에서 설정해준값 
	if(!isset($_POST['check_login']) && (!isset($_POST['anony_nickname']) || !isset($_POST['anony_passwd'])) ) {
		
		echo "
			 </ul>
		<br>
		</div>
				<form name='login' method='post' action='/csc/confirmanswer' onsubmit='return check_form_data();'>
					<div id ='csc_CA_shape_for_anony_login'>
						<span class = 'C_height_full' id = 'csc_CA_type_span_1'>
							익명닉네임:<input type='text' name='anony_nickname'  placeholder='닉네임 입력'' size='12'  >
						</span>

                  <input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
        
						
				
						<span class = 'C_height_full'>
							비밀번호: <input type='password' name='anony_passwd' placeholder='패스워드 입력' size='13'>
						</span>
					</div>
					<input type='submit' value='제출(익명)' id = 'csc_CA_type_submit_1'>
				</form>
			";

		echo "<a href = '/member/login'> <button id = 'csc_CA_type_submit_2'>회원 로그인 바로가기</button> </a>";

		//해당 값은 board의 whatch_input.js에서 가져온것
		echo "
				<script>
				function check_form_data () {

					if(!watch_nickname('anony_nickname', 0, '',5,0))
						return false;
						
					if(!watch_passwd('anony_passwd', 0, '',5,0, 3))
						return false;
				}
				</script>
			";


		return; 
	}	

?>
				<li>
					문의 내역입니다. 	
					메뉴를 선택해주세요.
				</li>
			</ul>
		</div>


		<div id = 'csc_CA_shape_for_sub_menu'>
		<?php //메뉴 하나 추가시 css ul width 늘려줘야함 ?>

	<?php 
		if(isset($_POST['check_login'])) {
			//로그인 상태
			echo "
				<ul>
					<a href='/csc/confirmanswer?listvary=report'>
						<li name='report'>
							신고 내역
						</li>
					</a>

					<a href='/csc/confirmanswer?listvary=proposal'>
						<li name='proposal'>
							건의 내역
						</li>
					</a>

					<a href='/csc/confirmanswer?listvary=abug'>
						<li name='abug'>
							버그 문의
						</li>
					</a>

					<a href='/csc/confirmanswer?listvary=amember'>
						<li name='amember'>
							회원 문의
						</li>
					</a>

					<a href='/csc/confirmanswer?listvary=etcetera'>
						<li name='etcetera'>
							 기타 문의
						</li>
					</a>
				</ul>
				";
		} else {

			//익명 상태
			echo "
				<ul>
					<form name='login' method='post' action='/csc/confirmanswer?listvary=report' onclick='this.submit();'>

						<input name = 'anony_nickname' type = 'password' value = '{$_POST['anony_nickname']}' class = 'C_style_input_passwd_hide'></input>
						<input name = 'anony_passwd' type = 'password' value = '{$_POST['anony_passwd']}' class = 'C_style_input_passwd_hide'></input>
						<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
						<li name = 'report'>
							신고 내역
						</li>
						
					</form>

					<form name='login' method='post' action='/csc/confirmanswer?listvary=proposal' onclick='this.submit();'>
						
						<input name = 'anony_nickname' type = 'password' value = '{$_POST['anony_nickname']}' class = 'C_style_input_passwd_hide'></input>
						<input name = 'anony_passwd' type = 'password' value = '{$_POST['anony_passwd']}' class = 'C_style_input_passwd_hide'></input>
						<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
						<li name='proposal'>
							건의 내역
						</li>
						
					</form>

					<form name='login' method='post' action='/csc/confirmanswer?listvary=abug' onclick='this.submit();'>
						
						<input name = 'anony_nickname' type = 'password' value = '{$_POST['anony_nickname']}' class = 'C_style_input_passwd_hide'></input>
						<input name = 'anony_passwd' type = 'password' value = '{$_POST['anony_passwd']}' class = 'C_style_input_passwd_hide'></input>
						<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
						<li name='abug'>
							버그 신고
						</li>
						
					</form>

					<form name='login' method='post' action='/csc/confirmanswer?listvary=amember'  onclick='this.submit();'>

						<input name = 'anony_nickname' type = 'password' value = '{$_POST['anony_nickname']}' class = 'C_style_input_passwd_hide'></input>
						<input name = 'anony_passwd' type = 'password' value = '{$_POST['anony_passwd']}' class = 'C_style_input_passwd_hide'></input>
						<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
						<li name='amember'>
							회원 문의
						</li>
					
					</form>

					<form name='login' method='post' action='/csc/confirmanswer?listvary=etcetera'  onclick='this.submit();'>

						<input name = 'anony_nickname' type = 'password' value = '{$_POST['anony_nickname']}' class = 'C_style_input_passwd_hide'></input>
						<input name = 'anony_passwd' type = 'password' value = '{$_POST['anony_passwd']}' class = 'C_style_input_passwd_hide'></input>
						<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
						<li name='etcetera'>
							 기타 문의
						</li>
						
					</form>
				</ul>
				";
		}
	?>

		</div>

		<br>
		<div id = 'csc_CA_shape_for_list'>
			<table>
				<tr>
					<th id = 'csc_CA_appoint_user_condition'>
						상태
					</th>
<?php 
		if ($_GET['listvary'] != 'report') {
			echo "<th id = 'csc_CA_appoint_user_title'>
						제목
					</th>

					<th id = 'csc_CA_appoint_user_date'>
						문의날짜
					</th>
				</tr>";
		} else if ($_GET['listvary'] == 'report') {
			echo "<th id = 'csc_CA_appoint_user_title'>
						내용
					</th>

					<th id = 'csc_CA_appoint_user_date'>
						처리날짜
					</th>
				</tr>";
		} else {
			echo "<th id = 'csc_CA_appoint_user_title'>
						에러
					</th>

					<th id = 'csc_CA_appoint_user_date'>
						에러발생
					</th>
				</tr>";
		}
?>
<?php
		//print_r($data_w_list);
		if(!isset($data_w_list[0]) && $_GET['listvary'] == 'report' && !isset($_POST['check_login'])) {
			echo "</table>
					<div id = 'csc_CA_appoint_no_request'>
						비회원은 신고내역을 볼 수 없습니다.
					</div>
				";
		} else if(!isset($data_w_list[0])) {
			echo "</table>
					<div id = 'csc_CA_appoint_no_request'>
						문의 내역이 없습니다.
					</div>
				";
		} else if($_GET['listvary'] != 'report') {
			foreach ($data_w_list as $entry){  
				
				// $entry['date'] = date("y/m/d",strtotime($entry['date']));
				$entry['read_id'] = -$entry['read_id'];
				echo "<tr>";

				if($entry['check_answer'] == 1) {
					echo "
						<td>
							완료
						</td>
						";
				} else if ($entry['check_answer'] == 0) {
					echo "
						<td class ='C_style_for_color_1'>
							진행
						</td>
						";
				} else {
					echo "
						<td class ='C_style_for_color_3'>
							보류
						</td>
						";
				}

				//제목클릭시 로그인 되어있는 경우와 안되어 있는 경우를 나눔
				if(isset($_POST['check_login'])) {
					echo "<td class = 'csc_CA_appoint_user_title'>
							<form name='login' method='post' action='/csc/userread/{$_GET['listvary']}/{$entry['read_id']}' onclick='return load_user_write(this);'>
								<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
								{$entry['title']}
							</form>

						</td>
						";
				} else {
					echo "<td class = 'csc_CA_appoint_user_title'>

						<form name='login' method='post' action='/csc/userread/{$_GET['listvary']}/{$entry['read_id']}' onclick='return load_user_write(this);'>

							<input name = 'anony_nickname' type = 'password' value = '{$_POST['anony_nickname']}' class = 'C_style_input_passwd_hide'></input>
							<input name = 'anony_passwd' type = 'password' value = '{$_POST['anony_passwd']}' class = 'C_style_input_passwd_hide'></input>
							<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
							{$entry['title']}
						</form>


							
						</td>
						";
				}

				echo "<td class = 'csc_CA_appoint_user_date'>
						{$entry['date']}
						</td>
					</tr>
					";


				
			}			
		} else if ($_GET['listvary'] == 'report') {
			//위에꺼에서 수정한거
			foreach ($data_w_list as $entry){  

				//답변시간 체크
				$entry['answer_date'] = $entry['answer_date'];
				if($entry['answer_date'] == 0)
					$entry['answer_date'] = '미처리';
				else 
					$entry['answer_date'] = date("y-m-d",strtotime($entry['answer_date']));
				
				//신고시간 체크
				$entry['user_date'] = $entry['reporter_info'];
				$entry['user_date'] = date("y년 m월 d일",strtotime($entry['user_date']));
				
				//댓글인지 글인지 체크
				if($entry['comment_id'] == 0 ) {
					$report_type = '글';
				} else {
					$report_type = '댓글';
				}

				echo "<tr>";
				if($entry['answer_result'] == 3) {
					echo "
						<td>
							삭제
						</td>
						";
					$entry['title'] = $entry['user_date']." ".$report_type." 신고내역 [삭제처리]되었습니다.";
 				} else if ($entry['answer_result'] < 0) { 
					echo "
						<td class ='C_style_for_color_3'>
							취소
						</td>
						";
					$entry['title'] = $entry['user_date']." ".$report_type." 신고내역 [신고취소]되었습니다.";
				} else if ($entry['check_answer'] == 0) {
					echo "
						<td class ='C_style_for_color_1'>
							진행
						</td>
						";
					$entry['title'] = $entry['user_date']." ".$report_type." 신고내역 처리 [진행중]입니다.";
				} else if ($entry['check_answer'] == -1) {
					echo "
						<td class ='C_style_for_color_1'>
							보류
						</td>
						";
					$entry['title'] = $entry['user_date']." ".$report_type." 신고내역 처리 [진행중]입니다.";
				} else {
					echo "
						<td>
							오류
						</td>
						";
					$entry['title'] ="에러발생 관리자에게 문의바람.";
				}

				//제목클릭시 로그인 되어있는 경우와 안되어 있는 경우를 나눔
				if(isset($_POST['check_login'])) {
					echo "<td class = 'csc_CA_appoint_user_title'>
								{$entry['title']}
							</td>
							";
				} else {
					//비회원 제공 안함
					// echo "<td class = 'csc_CA_appoint_user_title'>

					// 	<form name='login' method='post' action='/csc/userread/{$_GET['listvary']}/{$entry['read_id']}' onclick='return load_user_write(this);'>

					// 		<input name = 'anony_nickname' type = 'password' value = '{$_POST['anony_nickname']}' class = 'C_style_input_passwd_hide'></input>
					// 		<input name = 'anony_passwd' type = 'password' value = '{$_POST['anony_passwd']}' class = 'C_style_input_passwd_hide'></input>
					
					// 		{$entry['title']}
					// 	</form>


							
					// 	</td>
					// 	";
				}

				echo "<td class = 'csc_CA_appoint_user_date'>
						{$entry['answer_date']}
						</td>
					</tr>
					";


				
			}
		}


		echo "</table>";
?>


			
		</div>

	</div>
</div>


<script>
<?php
	if(isset($_GET['listvary'])) {
		echo "
			$('li[name={$_GET['listvary']}]').addClass('csc_CA_appoint_selected_menu');
		
		";
	}
?>
</script>


