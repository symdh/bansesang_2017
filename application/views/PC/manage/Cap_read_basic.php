<?php //print_r($data_w_read);

		//기본 read type
?>

<div id="mngCapRd_main">
	<div id = 'mngCapRd_wrapper'>
		<div id = "mngCapRd_shape_for_content">
			<div id = "mngCapRd_appoint_info_content">
				<div id = 'mngCapRd_appoint_title'>
				제목:
					<?php 
						echo $data_w_read[0]['title'];

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

				?>

			</div>
		</div>


<?php 
		
	if($data_w_read[0]['check_answer'] == 1) {
		echo "
			<div id ='mngCapRd_shape_for_show_answered'>
				<div id = 'mngCapRd_appoint_date_answered'>
				답변자 : {$data_w_read[0]['answer_nickname']},
				<br> 	
				답변일시 :	{$data_w_read[0]['answer_date']}
				
				</div>

				<div id = 'mngCapRd_appoint_content_answered'>
			
					
					{$data_w_read[0]['answer_content']}
			
				</div>
			</div> 

		";

	} else {

		echo "
			<div id = 'mngCapRd_shape_for_answer'>
				<form name='mngCapRd_form_1' id='' action='/manage/cap/uploadanswer' method='post' accept-charset='utf-8' onsubmit='return confirm_submit(this);'>
			";
		
			echo "<input type='password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input> ";
			echo "<input type='password' name = 'id_check' value ='{$data_w_read[0]['id_check']}' class = 'C_style_input_passwd_hide'></input>";
			echo "<input type='password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>";
		
		echo "

					<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
					<textarea name = 'answer_content' id ='ckeditor' class = 'ckeditor'></textarea>

					<div id = 'mngCapRd_shape_for_submit'>
						<button>
							등록하기
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
					   <input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
						<input type='password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
						<input type='password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>
						<input type='password' name = 'defer' value ='1' class = 'C_style_input_passwd_hide'></input>

						<button>
							보류
						</button>
					</div>
				</form>

				</div>
		";
	}
?>
		</div>	
	</div>
</div>

<script>
function confirm_submit(arr) {

	msg = '현재 등록시 수정이 불가합니다. 정말 등록 하시겠습니까?';
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


	<!-- 사용시에만 넣음 -->
	<script src = '/static/lib/ckeditor/ckeditor.js'></script>
