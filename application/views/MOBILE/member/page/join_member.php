
<input type='hidden' id = 'ci_t' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />


	<div id="member_J_main"> 
		<div id="member_J_wrapper">
			
			<p style = 'font-size:25px;'>회원가입</p>

			<div id="member_J_shape">

				<form name="join" method="post" onSubmit="return join_form_check()" action='/member/insertmember' enctype="multipart/form-data">
					<div style = 'height:80px; width: 300px; margin:auto;' class="g-recaptcha" data-sitekey="6Ld2WjwUAAAAAPI80Uex9jPH6K5JfimETBrgEm0X"></div>

					<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />

					<table cellpadding='4px'>

						<tr>
							<td>
								아이디 (id)
								<p></p>
							
							</td>
							<td>
								<input type="text" size="40" name="user_email_id" placeholder="아이디 (id)" onkeydown = 'reset_email();'  onblur = "watch_id(this,1, 'span[name=alert_email]', 10);"> 	
								
								<br><span name="alert_email">
									영문/숫자만 입력 가능
								</span>
<script>
	function reset_email(){
		$('span[name=alert_email]').css('color','black');
		$('span[name=alert_email]').text("영문/숫자만 입력 가능");
	}
</script>

							</td>
						</tr>

						<tr>
							<td>비밀번호<p></p></td>
							<td>
								<input type="password" size="40" name="user_passwd" placeholder="비밀번호 (6자이상)" onkeydown = 'reset_passwd();' ">
								<br><span class = "C_style_input_1">영문자, 숫자 혼용을 권장합니다. (특수문자 가능)</span>
									
							</td>
							
						</tr>
					
						<tr>
							<td>비밀번호 확인<p></p></td>
							<td>
								<input type="password" size="40" name="user_passwd2" placeholder="비밀번호 확인" onkeydown = 'reset_passwd();' onblur = "watch_passwd('user_passwd2', 1, 'span[name=alert_pwd]',10,1, 6);">
								<br>
								<span name = 'alert_pwd' >비밀번호를 한번 더 입력해주세요.</span>
							</td>
						</tr>
<script>
	function reset_passwd ( ) {
		$('span[name=alert_pwd]').css('color','black');
		$('span[name=alert_pwd]').text("비밀번호를 한번 더 입력해주세요.");
	}
</script>

						<tr>
							<td>가장 친한 친구 이름</td>
							<td><input type="text" size="40" maxlength="13" name="user_name" placeholder="가장 친한 친구 이름을 적어주세요 (한글 2글자~4글자)">
								<br>
								<span>
									*해당 정보는 아이디/비밀번호 찾기에 사용됩니다. 
								</span>

							</td>
						</tr>

						<tr>
							<td>닉네임(한글6자, 영문12자)</td>
							<td>
								<input type="text" size="40" maxlength="13" name="user_nickname" placeholder="닉네임 (한글6자, 영문12자)" onkeydown = 'reset_nickname();' onblur = "watch_nickname(this,1, 'span[name=alert_nickname]', 10);">
								<br>
								<span name = "alert_nickname">*공백없이 한글,영문,숫자만 입력 가능  </span>
						
<script>
	function reset_nickname(){
		$('span[name=alert_nickname]').css('color','black');
		$('span[name=alert_nickname]').text("*공백없이 한글,영문,숫자만 입력 가능");
	}
</script>

							</td>
						</tr>

						<tr>
							<td>회원사진 
								<br>
							      (선택사항) 
							</td>
							
							<td>
								<input type="file" name="user_image" >

								<br>
								<span>
									<span style ='font-size:12px; color:gray;'>
									*4x3 규격으로 업로드 권장합니다. 
									

									</span>

								</span>
								


								<div style = 'padding-top:10px; height:160px; text-align: center; '>
									<span style ='font-size:12px; '>
									*사진 미리보기		
									</span>
									<div  style = 'width:180px; height: :135px; margin:auto;'> 
										<img id ='member_J_preview' src = '/static/img/no-image.jpg'>

									</div>
								</div>

							</td>
						</tr>

						<tr>
							<td>본인소개 
								<br>
								(선택사항)
							</td>
							<td>
								<div style = 'width:300px; height:80px;'>
								<textarea class='C_txtr-W280' size="20" maxlength="500" name="user_introduce" value"소개를 간략히 적어주세요."> 소개를 간략히 적어주세요.</textarea>
								<br>
								</div>
								<div>
									
									<span style ='font-size:12px; color:gray;'>
										*글 작성시 노출되는 본인소개 내용입니다.
									</span>
								</div>
							</td>
						</tr>


					</table>

					<br>

					<br>
					<div class="C_for_center" id="member_J_type_submit_1" >			
						<input id='member_J_style_submit_blue' type=submit value="회원가입">
						<input id='member_J_style_submit_red' type=reset value="가입취소">
					</div>
					

				</form>
			</div>
		</div>
	</div>

<script src='https://www.google.com/recaptcha/api.js'></script>

<script>

function join_form_check(){
	//이메일 체크
	if(!watch_id ('user_email_id',1, '',  6, 0 )) {
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

	//이름를 체크
	if(!watch_name ('user_name', '',  6, 0)) {
		$('input[name=user_name').focus();
		return false;
	}	

	if (typeof(grecaptcha) != 'undefined') {
      	if (grecaptcha.getResponse() == "") {
         	alert("스팸방지 창에 체크 해주세요.");
      	  	return false;
      	}
  	} else {
     	return false;
  	}


	//가입 중복클릭 방지
	$('#member_J_style_submit_blue').attr('type','reset');
	$('#member_J_style_submit_blue').val('가입중');
}


</script>