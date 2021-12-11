<?php defined('BASEPATH') OR exit('No direct script access allowed');


function sendback_site ($motion, $middle_url, $last_nrl = '', $num = 0, $first_url = 'board') {
	//$motion = reject, none,
	//$middle_url = $function_name
	if($motion == 'none')
		echo "<script>alert('존재하지 않는 게시글입니다.');</script>";
	else if ($motion == 'exceed_report') 
		echo "<script>alert('신고 누적글은 수정 불가합니다.');</script>";
	else if ($motion == 'reject_access') 
		echo "<script>alert('잘못된 접근입니다');</script>";
	else if ($motion == 'reject_modify_1')
		echo "<script>alert('댓글이 존재하면 수정 불가합니다.');</script>";
	else if ($motion == 'reject_delete_1')
		echo "<script>alert('댓글이 존재하면 삭제 불가합니다.');</script>";
	else if ($motion == 'reject_solved')
		echo "<script>alert('채택된 글은 수정 불가합니다.');</script>";
	else if ($motion == 'reject_solved_1')
		echo "<script>alert('채택된 글은 삭제 불가합니다.');</script>";
	else if ($motion == 'wrong_passwd')
		echo "<script>alert('비밀번호가 틀립니다');</script>";
	else if ($motion == 'limit_title')
		echo "<script>alert('제목은 2글자 이상입니다.');</script>";
	else if ($motion == 'need_image') 
		echo "<script>alert('본문에 이미지를 업로드 해주세요.');</script>";
	else if ($motion == 'wrong_image') 
		echo "<script>alert('삭제가 거부되었습니다. 관리자에게 문의해주세요.');</script>";
	else if ($motion == 'reject_choose') 
		echo "<script>alert('이미 채택되었습니다.');</script>";
	else if ($motion == 'reject_choose_anony') 
		echo "<script>alert('익명 작성자는 채택불가 합니다.');</script>";
	else if ($motion == 'reject_choose_myself') 
		echo "<script>alert('본인 댓글은 채택 불가 합니다.');</script>";

	if($motion == 'result_modify') 
		echo "<script>alert('수정 완료되었습니다.');</script>";
	else if ($motion == 'result_delete') 
		echo "<script>alert('삭제 완료되었습니다.');</script>";
	else if ($motion == 'result_choose') 
		echo "<script>alert('채택완료!');</script>";
	else if ($motion == 'result_progress')
		echo "<script>alert('진행이 완료되었습니다.');</script>";
	else if ($motion == 'result_regist')
		echo "<script>alert('등록 완료되었습니다.');</script>";
	else if ($motion == 'result_defer')
		echo "<script>alert('보류 완료되었습니다.');</script>";
	else if ($motion == 'result_pass')
		echo "<script>alert('해당 답변은 넘어갑니다.');</script>";

	if($motion == 'wrong_email')
		echo '<script>alert("잘못된 이메일 입니다.");</script>';
	else if($motion == 'wrong_nickname')
		echo '<script>alert("잘못된 이메일 입니다.");</script>';
	else if($motion == 'wrong_passwd_2')
		echo '<script>alert("잘못된 비밀번호 입니다.");</script>';

	if($num) { 
		$num  = str_replace('\'','',$num);
		$num = abs($num);
		echo "<script>window.location = '/{$first_url}/{$middle_url}/{$last_nrl}/{$num}';</script>";
	} else {
		$middle_url = str_replace('\'','',$middle_url);
		echo "<script>window.location = '/{$first_url}/{$middle_url}/{$last_nrl}';</script>";
	} 


	return 0;
}

//sendback_site('', $function_name);
//sendback_site('', $function_name, '');
//sendback_site('', $function_name, '', $num);
//sendback_site('', $function_name, '', $_POST['read_id']);
/////////
//주소형식이 다를때
//sendback_site('reject_access', 'write', '', 0, 'csc');
//sendback_site('reject_access', 'read', $function_name, $_POST['read_id'], 'csc');




//sendback_site('reject_access', $middle_url, $last_nrl, $num , $first_url);
//<script>window.location = '/{$first_url}/{$middle_url}/{$last_nrl}/$num';</script>
