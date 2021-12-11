
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
									이메일 : <input style = 'max-width:200px;' type='text' name='user_email_id'  placeholder="이메일를 입력하세요" size='25'  >
								</span>
							</td>

							<td rowspan="2">
								<input type='submit' value="로그인" id = "member_L_type_submit_1">
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<span style = "float:right;" class = 'C_height_full'>
									패스워드 : <input style = 'max-width:200px;' type='password' name='user_passwd' placeholder="패스워드를 입력하세요" size='25'>
								</span>
							</td>
						</tr>

						<tr>
							<td class="C_style_input_1"><input type="checkbox" value="" id = 'member_L_idSaveCheck' checked> 이메일저장 </td>
							<td class="C_style_input_1"><input type="checkbox" value="false" name = "autologin" id = 'member_L_autologin' > 자동로그인</td>

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


<script>
	function check_login_info() {
	
		if(check_Email_JS() == false) {
			alert("이메일 형식을 확인해주세요.");
			$('input[name=user_email_id]').focus();
			return false;
		}

		/**********************************************************************************
		*비밀번호 체크하는 자바스크립트
		***********************************************************************************/
		var passwd = document.getElementsByName("user_passwd")[0];

		//띄어쓰기 검사
		var check_space = passwd.value.match(' ');
		if (check_space != null){
			alert("비밀번호에 빈칸(스페이스)는 사용할 수 없습니다.");
			passwd.focus();
			return false;
		}
		//그외
		if(passwd.value==""){
			alert("비밀번호를 입력하세요");
			passwd.focus();
			return false;
		} else if( passwd.value.length < 6 ) {
			alert("비밀번호는 6자 이상입니다.");	
			passwd.focus();
			return false;
		} else if( passwd.value.length > 20 ) {
			alert("비밀번호는 20자 이하만 써주세요~");	
			passwd.focus();
			return false;
		} else if(passwd2.value==""){
			alert("비밀번호를 입력하세요");
			passwd2.focus();
			return false;
		} 
	}
</script>



<script>	
//이메일 저장 쿠키로 구현
$(document).ready(function(){

    // 저장된 쿠키값을 가져와서 ID 칸에 넣어준다. 없으면 공백으로 들어감.
    var userInputId = getCookie("userInputId");
    $("input[name='user_email_id']").val(userInputId); 
     
    if($("input[name='user_email_id']").val() != ""){ // 그 전에 ID를 저장해서 처음 페이지 로딩 시, 입력 칸에 저장된 ID가 표시된 상태라면,
        $("#member_L_idSaveCheck").attr("checked", true); // ID 저장하기를 체크 상태로 두기.
    }
     
    $("#member_L_idSaveCheck").change(function(){ // 체크박스에 변화가 있다면,
        if($("#member_L_idSaveCheck").is(":checked")){ // ID 저장하기 체크했을 때,
            var userInputId = $("input[name='user_email_id']").val();
            setCookie("userInputId", userInputId, 7); // 7일 동안 쿠키 보관
        }else{ // ID 저장하기 체크 해제 시,
            deleteCookie("userInputId");
        }
    });

    //tab키 눌를때 이동
     $("input[name='user_email_id']").keydown(function(){ // ID 입력 칸에 ID를 입력할 때,
        if(window.event.keyCode == 9){ //탭키의 키코드 값은 9 입니다.
           	$('#member_L_type_submit_1').focus();
          }
    });	


    // ID 저장하기를 체크한 상태에서 ID를 입력하는 경우, 이럴 때도 쿠키 저장.
    $("input[name='user_email_id']").keyup(function(){ // ID 입력 칸에 ID를 입력할 때,
        if($("#member_L_idSaveCheck").is(":checked")){ // ID 저장하기를 체크한 상태라면,
            var userInputId = $("input[name='user_email_id']").val();
            setCookie("userInputId", userInputId, 7); // 7일 동안 쿠키 보관
        }
    });

    // ID 저장하기를 체크한 상태에서 로그인한경우 (복붙 대비..)
    $("#member_L_type_submit_1").click(function(){ // ID 입력 칸에 ID를 입력할 때,
        if($("#member_L_idSaveCheck").is(":checked")){ // ID 저장하기를 체크한 상태라면,
            var userInputId = $("input[name='user_email_id']").val();
            setCookie("userInputId", userInputId, 7); // 7일 동안 쿠키 보관
        }
    });
});


</script>