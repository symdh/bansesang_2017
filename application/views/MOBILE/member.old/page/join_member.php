
<input type='hidden' id = 'ci_t' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />


	<div id="member_J_main"> 
		<div id="member_J_wrapper">
			
			<p style = 'font-size:25px;'>회원가입</p>

			<div id="member_J_shape">
				<form name="join" method="post" onSubmit="return join_form_check()" action='/member/insertmember' enctype="multipart/form-data">
					<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />

					<table cellpadding='4px'>

						<tr>
							<td>
								이메일
								<p></p>
							
							</td>
							<td>
								<input type="text" size="40" name="user_email_id" placeholder="이메일을 입력해주세요" onkeydown = 'reset_checked_email();' onblur = "check_Email_JS();"> 
								
								<!-- 이메일 검사후 이메일 변경을 방지 -->
								<script>
									function reset_checked_email(){
										 $("span[name=show_checked]").html("<span name='show_checked'><span style ='font-size:12px; '>*인증메일이 발송되니 정확한 이메일을 입력 바랍니다.</span></span>");
									}
								</script>
								<br><span name="show_checked">
									<span style ='font-size:12px; '>
										*가입후 인증메일이 발송됩니다.
									</span>
								</span>
								
							</td>
						</tr>

						<tr>
							<td>닉네임</td>
							<td>
								<input type="text" size="40" maxlength="13" name="user_nickname" placeholder="공백없이 한글,영문,숫자만 입력 가능 " onkeydown = 'reset_nickname();' onblur = "watch_nickname(this, 0, 'span[name=explain_nickname]',10,1);">
								<span name = "explain_nickname" class = "C_style_input_1"><br>*한글6자, 영문12자</span>
								<br><span name="show_checked_nickname"></span>



								<!-- 닉네임 검사후 이메일 변경을 방지 -->
								<script>
									function reset_nickname(){
										 $("span[name=explain_nickname]").show();
										  $("span[name=explain_nickname]").html("<br>*공백없이 한글,영문,숫자만 입력 가능 (한글6자, 영문12자)");
										 $("span[name=show_checked_nickname]").html("");
									}
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
								<input type="password" size="40" name="user_passwd2" placeholder="비밀번호 확인" onkeydown = 'reset_passwd();' onblur = "watch_passwd('user_passwd2', 1, 'span[name=explain_passwd]',10,1, 6);">
								<span name = "explain_passwd" class = "C_style_input_1"><br>비밀번호를 한번 더 입력해주세요. </span>
								<span id = 'css_passwd_cf_true'><br>비밀번호가 일치합니다.</span>
								<span id = 'css_passwd_cf_false'><br></span>
							</td>
						</tr>
		<script>
			$('#css_passwd_cf_true').hide();
			$('#css_passwd_cf_false').hide();
		</script>
						<tr>
							<td>성별</td>
							<td> 
								<div class = 'C_float_left' style = 'width:40px;'>
									<input class = "C_style_checkbox_1" type="checkbox" name="user_gender" value="male" onclick="check_only(this)"> 
								</div>
								<div class = 'free_C_div_for_gender' style = 'width:50px;'>
									남자
								</div>
								<div class = 'C_float_left' style = 'width:40px;'>
  								   <input class = "C_style_checkbox_1" type="checkbox" name="user_gender" value="female" onclick="check_only(this)">
  								</div>
								<div class = 'free_C_div_for_gender' style = 'width:50px;'>
									여자
								</div>

  								</td>
						</tr>
						
						<tr>
							<td>이름</td>
							<td><input type="text" size="40" maxlength="13" name="user_name" placeholder="이름 (실명을 적어주세요)">
								<br><span>
									<span style ='font-size:12px; color:gray;'>
										*실명은 한글로 2~4자만 가능합니다. 
									</span>
									<br>
									<span style ='font-size:12px; color:gray;'>
										*실명은 아이디/비밀번호 찾기에 사용됩니다. 
									</span>
								</span>

							</td>
						</tr>
						<tr>
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
							      (선택사항) 
							</td>
							
							<td>
								<input type="file" name="user_image" >

								<br>
								<span>
									<span style ='font-size:12px; color:gray;'>
									*4x3 규격으로 업로드 권장합니다. <br>
									(4x3 규격으로 변환되어 업로드 됩니다.)

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

					<div style ='font-size:12px; margin-left:5px'>
						* 메일인증의 유효기간은 1일입니다.  <br>
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

