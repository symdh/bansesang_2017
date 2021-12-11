
//댓글 익명 쓸때 닉네임 및 패스워드 감시 (아래것들 모아둔)
//익명일떄를 아래서 검사함
function watch_comment_before_send () {

		//내용이 비었는지 확인 (tab, 띄어쓰기 포함) 
	if($('.brdRd-Dplec-Cm #for_check_text').length > 0)
		var var_for_check = $('.brdRd-Dplec-Cm #for_check_text').val();
	//글인지 댓글인지 확인
	else if ($('#brdRd_Dplec_Cm #for_check_text').length > 0) {
		var var_for_check = $('#brdRd_Dplec_Cm #for_check_text').val();
	} else if ($('#brdWrt_Dplec_WrtTtl').length > 0) {
		var var_for_check = $('#brdWrt_Dplec_WrtTtl').val();
	}
	var_for_check = var_for_check.replace(/\s/gi, '');
	if (var_for_check.length < 2) {
		if ($('#brdWrt_Dplec_WrtTtl').length > 0) {
			alert('제목을 2자이상 입력해 주세요.');
		} else {
			alert('2자이상 입력해 주세요.');
		}
		return false;
	}

	//익명이 존재하면 계속 진행
	if(check_loggin) { //아니면 그냥 넘어감
		return true;
	} 

	if(!watch_nickname('anony_nickname', 0, '',5,0))
		return false;

	if(!watch_passwd('anony_passwd', 0, '',5,0, 3))
		return false;

	return true;
}