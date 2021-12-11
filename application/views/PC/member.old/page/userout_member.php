

<div id = 'pgBA_main'>
	<div id = 'pgBA_wapper'>
		
		<?php
		    //왼쪽 리스트 부분
		    include "./static/include/view/member/sub_list.php";
		?>
		    

		<div id = 'pgBA_Dstl_body'>
			<div id ='pgBA_Dstl_SbTtl' >
				회원탈퇴 안내 
				<span style = 'font-size:11px;'>
					 <br> 회원탈퇴를 신청하기 전에 안내 사항을 꼭 확인해주세요.
				</span>
			</div>

			<div class = 'pgBA-Dstl-content-1'>
				<span style = 'font-weight: bold;'> 
					1. 사용하고 계신 닉네임(<?=$user_info['nickname'];?>)은 탈퇴할 경우 재사용 및 복구가 불가능합니다.
				</span>

				<br> 

				<span style = 'color:red; margin-left: 20px; font-size:11px'>
					탈퇴한 닉네임은 본인과 타인 모두 재사용 및 복구가 불가
				</span>
				<span style = 'font-size:11px'>
					하오니 신중하게 선택하시기 바랍니다.
				</span>
			</div>

			<div class = 'pgBA-Dstl-content-1'>
				<span style = 'font-weight: bold;'> 
					2. 탈퇴 후 회원정보 및 개인형 서비스 이용기록은 모두 삭제됩니다.
				</span>

				<br>

				<span style = 'font-size:11px; margin-left: 20px; '>
					탈퇴 신청시 즉시 정보가 삭제처리되며 복구 불가능 합니다. 
				</span>

			</div>

			<div class = 'pgBA-Dstl-content-1'>
				<span style = 'font-weight: bold;'> 
					3. 탈퇴 후에도 게시판형 서비스에 등록한 게시물은 그대로 남아 있습니다.
				</span>

				<br>

				<span style = 'font-size:11px; margin-left: 20px; '>
					삭제를 원하는 게시글과 댓글이 있다면 반드시 탈퇴 전 삭제하시기 바랍니다. 
				</span>

				<br>

				<span style = 'font-size:11px; margin-left: 20px; '>
					탈퇴 후에는 회원정보가 삭제되어 본인 여부를 확인할 수 있는 방법이 없어, 임의로 삭제해드릴 수 없습니다.
				</span>
			</div>

			<div class = 'pgBA-Dstl-content-2'>
				<span style = 'font-weight: bold;'> 
					4. 재가입시  해당 이메일(<?=$user_info['email'];?>)로 재가입이 가능합니다.
				</span>

				<br>

				<span style = 'font-size:11px; margin-left: 20px; '>
					단, 탈퇴 이전 정보의 기록은 열람 또는 수정, 삭제 불가합니다.
				</span>

				
			</div>


			<form style = 'height:0px;' action = '/member/userpage/useroutagree' onsubmit = 'return check_submit_data();' method = 'post'>

			<div class = 'pgBA-Dstl-content-3'>
				<input id ='agree' type = 'checkbox'>
				안내 사항을 모두 확인하였으며, 이에 동의합니다.

				<br>
				<br>

				<span>
					탈퇴할 이메일 : 
				</span>


				<span style = 'color:red;''>
					<?=$user_info['email'];?>
				</span>
				
				<br>

				<span>
					패스워드 입력
				</span>

				<input name = 'passwd' type = 'password'>

			</div>


			<div id = 'pgBA_btn'> 
				<button type = 'submit' class = 'C_btn-H40'>
					탈퇴
				</button>
			</div>

			<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
			</form>

		</div>
	</div>
</div>


<script>
function  check_submit_data() {
	if( $("#agree").is(":checked") == false) {
		alert('탈퇴 안내를 확인하고 동의해 주세요.');
		$("#agree").focus();
		return false;
	}

	if( $("input[name='passwd']").val() == '') {
		alert('비밀번호를 입력해주세요.');
		$("input[name='passwd']").focus();
		return false;
	}
	return true;
}
</script>
