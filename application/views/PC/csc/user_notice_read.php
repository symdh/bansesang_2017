
<div id = 'mngNtc_main'>
	<div id ='mngNtc_wapper'>
		<span style = 'font-size: 20px;'> 공지사항 </span>

		<a href = 'javascript:history.back()'>
			<button class = 'C_btn-W100'> 뒤로가기 </button>
		</a>
		<br>

<?php

	$check_login = $this->session->userdata('logged_in');
	$super_user = $this->session->userdata('guitar_id');
	
	if(isset($check_login) && $check_login == 1 && $super_user > 1 ) {
		echo "
			<form action = '/manage/cap/deletewrite' method='post' onsubmit='return confirm_delete();' class = 'C_float_left'>
				<input name = 'function_name' type = 'password' value = 'notice' class = 'C_style_input_passwd_hide'></input>
				<input name = 'read_id' type = 'password' value = '{$data_w[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
				<input name = 'id_check' type = 'password' value = '{$data_w[0]['id_check']}' class = 'C_style_input_passwd_hide'></input>
				<input type='hidden' name='{$this->security->get_csrf_token_name()}' value='{$this->security->get_csrf_hash()}' />
				<button class = 'C_btn-W100'> 삭제하기 </button>
			</form>

			<form action = '/manage/cap/managewrite/1?type=notice' method='post' class = 'C_float_left'>
				<input name = 'function_name' type = 'password' value = 'notice' class = 'C_style_input_passwd_hide'></input>
				<input name = 'divide_type' type = 'password' value = 'notice' class = 'C_style_input_passwd_hide'></input>
				<input name = 'read_id' type = 'password' value = '{$data_w[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
				<input name = 'id_check' type = 'password' value = '{$data_w[0]['id_check']}' class = 'C_style_input_passwd_hide'></input>
				<input type='hidden' name='{$this->security->get_csrf_token_name()}' value='{$this->security->get_csrf_hash()}' />
				<button class = 'C_btn-W100'> 수정하기 </button>
			</form>
			<br>
		";
	} 

//
echo " <script>
  	function confirm_delete() {
	      	msg = '삭제시 복구할 수 없습니다. 정말로 삭제 하시겠습니까?';
	      	if (confirm(msg)!=0) {
	 	           return true;
		} else {
	            	return false;
		}
	}

	</script>";


?>
		

		<div id = 'mngNtc_Dstl_RdTtl' >
<?php
	echo $data_w[0]['title'];
?>
		</div>

		<div id ='mngNtc_Dstl_RdCt'>
<?php
	echo $data_w[0]['content'];
?>
		</div>


	</div>
</div>
