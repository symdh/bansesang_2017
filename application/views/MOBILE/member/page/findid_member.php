	<div id = "member_L_main">
		<div id = "border_L_wrapper" >
			<div class ='C_for_vertical_center' id='member_L_for_vertical_center'></div>
			
			<span style = "font-size:20px; margin-left:10%; font-weight: bold;">
					아이디 찾기
			</span>
			<hr style = "width:80%; margin:auto;">
			<br>
			
			<div id = "member_L_wrapper" >

				<form name="findid" method="post" action="/member/tryfindid" onsubmit = ' find_form_check(); return false; '>
				<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
					<table>
						
						<tr>
							<td colspan="2">
								<span style = "float:right;" class = 'C_height_full' >
									닉네임 : <input type="text"  size='25'  name="user_nickname" placeholder="한글/영어 (3~12자)" >
								</span>
							</td>

							<td rowspan="2">
								<input name =  'submit_input' type='submit' value="제출" id = "member_L_type_submit_1">
							</td>
						</tr>

						<tr>

							<td colspan="2">
									<span style = "float:right;" class = 'C_height_full'>
										 가입시 입력한 이름 : <input  type='text' name='user_name'  placeholder="가입시 입력했던  가장 친한 친구이름" size='25'  ">
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
									<a href="/member/findid" class="C_style_input_1"><div class = 'free_C_div_style_1'>아이디 찾기</div></a>
								</div>
								<div  style = "width:33%; float:left;">
									<a href="/member/findpwd" class="C_style_input_1"><div class = 'free_C_div_style_1'>비밀번호 찾기</div></a>
								</div>	
						</tr>
					</table>


					<br>
					<div style = 'height:80px; width: 300px; margin:auto;' class="g-recaptcha" data-sitekey="6Ld2WjwUAAAAAPI80Uex9jPH6K5JfimETBrgEm0X"></div>

				</form>	
				
			</div>

		</div>
	</div>

<script src='https://www.google.com/recaptcha/api.js'></script>
