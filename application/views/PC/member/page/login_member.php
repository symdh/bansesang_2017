
	<div id = "member_L_main">
		<div id = "border_L_wrapper" >
			<div class ='C_for_vertical_center' id='member_L_for_vertical_center'></div>
			<span style = "font-size:20px; margin-left:10%; font-weight: bold;">
					로그인
			</span>
			<hr style = "width:80%; margin:auto;">
			<br>

			<div id = "member_L_wrapper" >
				<form name="login" method="post" action="/member/trylogin " onsubmit = 'return check_login_info();'>

				<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
					<table>
						
						<tr>
							<td colspan="2">
								<span style = "float:right;" class = 'C_height_full'>
									아이디 : <input type='text' name='user_email_id'  placeholder="아이디를 입력하세요" size='25' onkeydown = "if(window.event.keyCode == 9){ $('input[name=user_passwd]').focus(); return false;}" >
								</span>
							</td>


							<td rowspan="2">
								<input type='submit' value="로그인" id = "member_L_type_submit_1">
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<span style = "float:right;" class = 'C_height_full'>
									패스워드 : <input type='password' name='user_passwd' placeholder="패스워드를 입력하세요" size='25'>
								</span>
							</td>
						</tr>

						<tr>
							<td class="C_style_input_1"><input type="checkbox" value="" id = 'member_L_idSaveCheck' checked> 아이디저장 </td>
							<td class="C_style_input_1"><input type="checkbox" value="false" name = "autologin" id = 'member_L_autologin' > 자동로그인</td>

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
							</td>
						</tr>

					</table>

					<br>
					<div style = 'height:80px; width: 300px; margin:auto;' class="g-recaptcha" data-sitekey="6Ld2WjwUAAAAAPI80Uex9jPH6K5JfimETBrgEm0X"></div>

				</form>	

			</div>

		</div>
	</div>

<script src='https://www.google.com/recaptcha/api.js'></script>


<script>
$(document).ready(function(){
	 // 저장된 쿠키값을 가져와서 ID 칸에 넣어준다. 없으면 공백으로 들어감.
    var userInputId = getCookie("userInputId");
    $("input[name='user_email_id']").val(userInputId); 

    // if(userInputId.length){ //공백이 아니면 ID 저장하기 true 상태로 두기
    //     $("#member_L_idSaveCheck").attr("checked", true); 
    // }

    //자동 로그인 관련.
    $("#member_L_autologin").change(function(){ // 체크박스에 변화가 있다면,
        if($("#member_L_autologin").is(":checked")){ // 체크했을 때,
            $("#member_L_autologin").val('true');
            
        }else{ //체크 해제 시,
             $("#member_L_autologin").val('false');
        }
    });
});

	function check_login_info() {
		//아이디 체크
		if(!watch_id ('user_email_id', 0, '', 6, 0) ) {
			$('input[name=user_email_id]').focus();
			return false;
		}

		//비밀번호 체크
		if(!watch_passwd ('user_passwd', 0, '',  6, 0)) {
			$('input[name=user_passwd').focus();
			return false;
		}

		if($("#member_L_idSaveCheck").is(":checked")){ // ID 저장하기 체크했을 때,
           var userInputId = $("input[name='user_email_id']").val();
           setCookie("userInputId", userInputId, 7,'/member/login'); // 7일 동안 쿠키 보관
       } else{ // ID 저장하기 체크 해제 시,
           deleteCookie("userInputId",'/member/login');
       }

      	if (typeof(grecaptcha) != 'undefined') {
	      	if (grecaptcha.getResponse() == "") {
	         	alert("스팸방지 창에 체크 해주세요.");
	      	  	return false;
	      	}
	  	} else {
	     	 return false;
	   	}


		return true;	
	}

</script>