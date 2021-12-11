<?php
	//신고 전용 read type (그냥 type_1 복사해놓은거)
	//나중에 세부내용 처리할때 최적화 필요
?>

<div id="mngCapRd_main">
	<div id = 'mngCapRd_wrapper'>
		<div id = "mngCapRd_shape_for_content">
			<div id = "mngCapRd_appoint_info_content">
				<div id = 'mngCapRd_appoint_title'>
					제목:
					<?php 
						if(empty($data_w_read[0]['title'])) {
							echo "<span style = 'color:red;'>(댓글 신고)</span>";
						} else {
							echo $data_w_read[0]['title'];
						}
					?>
				</div>

				<div id = 'mngCapRd_appoint_nickname'>
					 
				<?php
					if($data_w_read[0]['check_anony'] == 1) {
						echo "
							닉네임:
							<span class = 'C_style_for_color_2'>
								 {$data_w_read[0]['writed_member']} 
							</span>
							<span class ='C_style_for_color_1'>(익명)</span>
							";
					} else {
						echo " 
							닉네임: {$data_w_read[0]['writed_member']}
							";

					}
				?>

				</div>

				<div id = 'mngCapRd_appoint_date'>
					<?php
						echo $data_w_read[0]['date'];
					?>
				</div>
			</div>

			<div id = "mngCapRd_appoint_content">

				<?php 

						
						echo $data_w_read[0]['content'];

						//수정된 제목, 글 표시
						if(!empty($data_w_read[0]['modify_content'])) {
							echo "<br>";
							echo "<span style = 'color:red'>수정된 글의 신고내용도 존재합니다.</span>";
							echo "<br>";
							echo "제목: ".$data_w_read[0]['modify_title'];
							echo "내용 <br>";
							echo $data_w_read[0]['modify_content']."<br>";
						} else {
							echo "<br>";
						}

						//링크위해 양수처리
						$pointer_read_id = -$data_w_read[0]['pointer_read_id'];
						echo "<br>";
						echo "<a href = '/board/{$data_w_read[0]['pointer']}/read/$pointer_read_id' style='color:blue; text-decoration:none;'> [원본글 확인하기] </a>";
						echo "<br>";
						echo (int)($data_w_read[0]['reporter_num']/100)."명의 회원신고가 있었습니다.";
						echo "<br>";
						echo (int)($data_w_read[0]['reporter_num']%100)."명의 익명신고가 있었습니다.";		
				?>

			</div>
		</div>



		
<?php 
		
	if($data_w_read[0]['check_answer'] == 1) {
		echo "
			<div id ='mngCapRd_shape_for_show_answered'>
				<div id = 'mngCapRd_appoint_date_answered'>
			
					{$data_w_read[0]['answer_date']}
				에 작성된 답변입니다.
				</div>

				<div id = 'mngCapRd_appoint_content_answered'>
			
					
					{$data_w_read[0]['answer_content']}
			
				</div>
			</div> 

		";

	} else {

		echo "
			<div id = 'mngCapRd_shape_for_answer'>
				<form name='mngCapRd_form_1' id='' action='/manage/cap/reportanswer' method='post' accept-charset='utf-8' onsubmit='return confirm_submit(this);'>
			";
		
			echo "<input type='password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input> ";
			echo "<input type='password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>";
			echo "<input type='password' name = 'type' value ='delete' class = 'C_style_input_passwd_hide'></input>";
		
		echo "
					<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
					<div id = 'mngCapRd_shape_for_submit'>
						<button>
							삭제처리
						</button>
					</div>

				</form>

				<form name='mngCapRd_form_1' id='' action='/manage/cap/giveupanswer' method='post' accept-charset='utf-8' onsubmit='return confirm_pass(this);'>
					<div id = 'mngCapRd_shape_for_pass'>
						<input type='password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
						<input type='password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>
						<input type='password' name = 'pass' value ='1' class = 'C_style_input_passwd_hide'></input>
						<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
						<button>
							넘어가기
						</button>
					</div>
				</form>

				<form name='mngCapRd_form_1' id='' action='/manage/cap/giveupanswer' method='post' accept-charset='utf-8' onsubmit='return confirm_defer(this);'>
					<div id = 'mngCapRd_shape_for_defer'>

						<input type='password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
						<input type='password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>
						<input type='password' name = 'defer' value ='1' class = 'C_style_input_passwd_hide'></input>
						<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />

						<button>
							보류
						</button>
					</div>
				</form>

				</div>
		";


		echo "
			<div id = 'manage_RdT2_shape_for_answer'>
				<form name='mngCapRd_form_1' id='' action='/manage/cap/reportanswer' method='post' accept-charset='utf-8' onsubmit='return cancel_submit(this);'>
			";
		
			echo "<input type='password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input> ";
			echo "<input type='password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>";
			echo "<input type='password' name = 'type' value ='cancel' class = 'C_style_input_passwd_hide'></input>";
		
		echo "
					<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
					<div class = 'manage_RdT2_shape_for_submit'>
						<button>
							이상없음
						</button>
					</div>

				</form>";


		echo "
				<form name='mngCapRd_form_1' id='' action='/manage/cap/reportanswer' method='post' accept-charset='utf-8' onsubmit='return spam_submit(this);'>
					
					<input type='password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
					<input type='password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>
					<input type='password' name = 'type' value ='spam' class = 'C_style_input_passwd_hide'></input>
					<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />

					<div class = 'manage_RdT2_shape_for_submit'>
						<button>
							스팸처리
						</button>
					</div>
				</form>

		";
				
					// <div id = 'manage_RdT2_shape_for_pass'>
					// 	<button>
					// 		임시버튼
					// 	</button>
					// </div>
				

					// <div id = 'manage_RdT2_shape_for_defer'>

					// 	<button>
					// 		임시버튼
					// 	</button>
					// </div>
		echo "
				</div>
		";


	}
?>
		</div>	
	</div>
</div>

<script>
function spam_submit(arr) {

	msg = '스팸 내용이 확실 합니까?';
    if (confirm(msg)!=0) {
     	return true;
    } else {
    	return false;
	}
}
function confirm_submit(arr) {

	msg = '정말 삭제처리 하시겠습니까?';
    if (confirm(msg)!=0) {
     	return true;
    } else {
    	return false;
	}
}
function cancel_submit(arr) {
	msg = '신고내용에 이상이 없습니다. 맞습니까?';
    if (confirm(msg)!=0) {
     	return true;
    } else {
    	return false;
	}
}
function confirm_pass(arr) {

	msg = "해결할 수 없는 문제면 보류해주세요. 정말 넘어가시겠습니까?";

    if (confirm(msg)!=0) {
     	return true;
    } else {
    	return false;
	}
}
function confirm_defer(arr) {

	msg = "해결할 수 없는 문제가 맞습니까?";

    if (confirm(msg)!=0) {
     	return true;
    } else {
    	return false;
	}
}
</script>

