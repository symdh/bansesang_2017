<div id="member_J_main"> 
	<div id="member_J_wrapper">
		
		<p style = 'font-size:25px;'>회원가입</p>

		<div id="member_J_shape">
			<form name="join" method="post" onSubmit="return join_form_check();" action='/member/insertmember' enctype="multipart/form-data">
				<input type='hidden' id = 'ci_t' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />

				<table cellpadding='4px'>
					<tr>
						<td>
							이메일
							<!--for  script --> <p></p>
						</td>
						<td>
							<input type="text" size="40" name="user_email_id" placeholder="이메일을 입력해주세요" onkeydown = 'reset_email();' onblur = "watch_email(this,1, 'span[name=alert_email]', 10);"> 		
							<br>
							<span name = 'alert_email'>*인증메일이 발송되니 정확한 이메일을 입력 바랍니다.</span>
<script>
	//이메일 키 이벤트 (검사후 이메일 변경을 방지)
	function reset_email(){
		$('span[name=alert_email]').css('color','black');
		$('span[name=alert_email]').text("*인증메일이 발송되니 정확한 이메일을 입력 바랍니다.");
	}
//ie9 이하 에러때문.
$(document).ready(function () {
  	reset_email();
});
</script>
						</td>
					</tr>

					<tr>
						<td>닉네임</td>
						<td>
							<input type="text" size="40" maxlength="13" name="user_nickname" placeholder="한글/영어 (3~8자)" onkeydown = 'reset_nickname();' onblur = "watch_nickname(this,1, 'span[name=alert_nickname]', 10);">
							<br>
							<span name = 'alert_nickname'>*공백없이 한글,영문,숫자만 입력 가능 (한글6자, 영문12자)</span>
<script>
	// 닉네임 키 이벤트 (검사후 이메일 변경을 방지)
	function reset_nickname(){
		$('span[name=alert_nickname]').css('color','black');
		$('span[name=alert_nickname]').text("*공백없이 한글,영문,숫자만 입력 가능 (한글6자, 영문12자)");
	}

//ie9 이하 에러때문.
$(document).ready(function () {
  	reset_nickname();
});
</script>
						</td>
					</tr>

					<tr>
						<td>비밀번호<p></p></td>
						<td>
							<input type="password" size="40" name="user_passwd" placeholder="비밀번호 (6자이상)" onkeydown = 'reset_passwd();'>
							<br><span class = "C_style_input_1">영문자, 숫자 혼용을 권장합니다. (특수문자 가능)</span>
						</td>
						
					</tr>
				
					<tr>
						<td>비밀번호 확인<p></p></td>
						<td>
							<input type="password" size="40" name="user_passwd2" placeholder="비밀번호 확인" onkeydown = 'reset_passwd();' onblur = "watch_passwd(this,1, 'span[name=alert_pwd]', 10);">
							<br>
							<span name = 'alert_pwd' >비밀번호를 한번 더 입력해주세요.</span>
<script>
	// 패스워드 키 이벤트
	function reset_passwd(){
		$('span[name=alert_pwd]').css('color','black');
		$('span[name=alert_pwd]').text("비밀번호를 한번 더 입력해주세요.");
	}
//ie9 이하 에러때문.
$(document).ready(function () {
  	reset_passwd();
});

</script>
						</td>
					</tr>

					<tr>
						<td>성별</td>
						<td> 
							<div class = 'C_float_left' style = 'width:40px;'>
								<input class = "C_style_checkbox_1" type="checkbox" name="user_gender" value="male" onclick="checkbox_only(this);"> 
							</div>
							<div class = 'free_C_div_for_gender' style = 'width:50px;'>
								남자
							</div>
							<div class = 'C_float_left' style = 'width:40px;'>
 								   <input class = "C_style_checkbox_1" type="checkbox" name="user_gender" value="female" onclick="checkbox_only(this);">
 								</div>
							<div class = 'free_C_div_for_gender' style = 'width:50px;'>
								여자
							</div>
 						</td>
					</tr>
					
					<tr>
						<td>이름</td>
						<td><input type="text" size="40" maxlength="13" name="user_name" placeholder="이름 (실명을 적어주세요)">
							<br>
							<span name = 'alert_name' style ='font-size:12px; color:gray;' >
								*실명은 한글로 2~4자만 가능합니다. <br> 
								*실명은 아이디/비밀번호 찾기에 사용됩니다. 
							</span>

						</td>
					</tr>
					<tr style = 'border-bottom: 1px dashed black;'>
						<td>핸드폰번호</td>
						<td>
							<input type="text" size="40" maxlength="13" name="user_phone" placeholder="- 없이 입력해주세요.">
							<br><span>
								
								<span style ='font-size:12px; color:gray;'>
									*핸드폰번호는 아이디/비밀번호 찾기에 사용됩니다. 
								</span>
							</span>
						</td>
					</tr>

					<tr>
						<td>회원사진 
							<br>
						      <span style = 'color:blue; font-weight: bold;'>(선택사항) </span>
						</td>
						
						<td>
							<input type="file" name="user_image" >

							<br>
							<span>
								<span style ='font-size:12px; color:gray;'>
								*4x3 규격으로 변환되어 업로드 됩니다.

								</span>

							</span>
							
							<div style = 'padding-top:10px; height:160px; text-align: center; '>
								<span style ='font-size:15px; '>
								*사진 미리보기		
								</span>
								<div  style = 'width:180px; height: :135px; margin:auto;'> 
									<img id ='member_J_preview' src = '/static/img/no-image.jpg'>

								</div>
							</div>

						</td>
					</tr>

					<tr style = 'border-bottom: 2px dashed black;'>
						<td>본인소개 
							<br>
							<span style = 'color:blue; font-weight: bold;'> (선택사항) </span>
						</td>
						<td>
							<div style = 'width:350px; height:80px;'>
							<textarea class='C_txtr-W500' style = 'width:350px; height:80px;' size="40" maxlength="500" name="user_introduce" value"소개를 적어주세요."> 소개를 적어주세요.</textarea>
							<br>
							</div>
							<div>
								
								<span style ='font-size:12px; color:gray;'>
									*글 작성시 노출되는 본인소개 내용입니다. (간락히 적어주세요.)
								</span>
							</div>
						</td>
					</tr>
				</table>

				<br>
				<div style ='font-size:12px; margin-left:30px'>
					* 메일인증을 1일이내 완료하지 않으면 재가입해야 합니다. <br>
					* 메일인증 기간동안 재가입은 불가합니다.
				</div>
				<br>
				<div class="C_for_center" id="member_J_type_submit_1" >			
					<input id='member_J_style_submit_blue' type=submit value="회원가입">
					<input id='member_J_style_submit_red' type=reset value="가입취소">
				</div>

			</form>
		</div>
	</div>
</div>

<script>

function join_form_check(){
	//이메일 체크
	if(!watch_email ('user_email_id',1, '',  6, 0 )) {
		$('input[name=user_email_id').focus();
		return false;
	}

	//닉네임 체크
	if(!watch_nickname ('user_nickname',1, '',  6, 0)) {
		$('input[name=user_nickname').focus();
		return false;
	}	

	//비밀번호 체크
	if(!watch_passwd ('user_passwd2', 1, '',  6, 0)) {
		$('input[name=user_passwd2').focus();
		return false;
	}	

	//성별 체크 
	if(!watch_checkbox ('user_gender', '',  6, 0)) {
		return false;
	}	

	//이름를 체크
	if(!watch_name ('user_name', '',  6, 0)) {
		$('input[name=user_name').focus();
		return false;
	}	

	//전화번호 체크
	if(!watch_phone ('user_phone', '',  6, 0)) {
		$('input[name=user_phone').focus();
		return false;
	}	
	
	//가입 중복클릭 방지
	$('#member_J_style_submit_blue').attr('type','reset');
	$('#member_J_style_submit_blue').val('가입중');
}


</script>