	<div id = "member_L_main">
		<div id = "border_L_wrapper" >
			<div class ='C_for_vertical_center' id='member_L_for_vertical_center'></div>
			
			<span style = "font-size:20px; margin-left:10%; font-weight: bold;">
					비밀번호 변경
			</span>
			<hr style = "width:80%; margin:auto;">
			<br>
			
			<div id = "member_L_wrapper" >

				<form name="changepwd" method="post" action="/member/changepwd " onsubmit = 'return change_form_check();'>
				<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
				<input type='hidden' name='log_c' value='<?=$data[0]['auth']?>' />
					<table>
						
						<tr>
							<td colspan="2">
									<span style = "float:left;" class = 'C_height_full'>
										이메일 : <?php echo $data[0]['email']; ?>
									</span>
								
							</td>

							<td rowspan="3">
								<input name =  'submit_input'  type='submit' value="제출" id = "member_L_type_submit_1">
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<span style = "float:right;" class = 'C_height_full' >
									비밀번호 입력 : <input style = 'max-width:200px;' type="password" size="20" name="user_passwd" placeholder="비밀번호 (6자이상)" onkeydown = 'reset_passwd();'>
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<span style = "float:right;" class = 'C_height_full' >
									비밀번호 재입력 : <input style = 'max-width:200px;' type="password" size="20" name="user_passwd2" placeholder="비밀번호 확인" onkeydown = 'reset_passwd();' onblur = "check_passwd_JS();">
								</span>
							</td>
						</tr>

						<tr>
							<td colspan="3" name = 'ajax_result'></td>
						</tr>

						<tr>
							<td colspan="3" style= "width:30%;">
								<div style = "width:33%; float:left;">
									<a href="/member/join" class="C_style_input_1"><div class = 'free_C_div_style_1' id="member_L_watch_join">회원가입</div></a>
								</div>
								<div  style = "width:33%; float:left;">
									<a href="/member/findid" class="C_style_input_1"><div class = 'free_C_div_style_1'>이메일 찾기</div></a>
								</div>
								<div  style = "width:33%; float:left;">
									<a href="/member/findpwd" class="C_style_input_1"><div class = 'free_C_div_style_1'>비밀번호 찾기</div></a>
								</div>	
						</tr>
					</table>

				</form>	
			</div>

		</div>
	</div>
