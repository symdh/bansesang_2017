<?php


/////////////////////클라이언트 기본 변수 설정///////////////////////
   echo "<script>";
   echo "store_comment_id = new Array();";
   //comment_id의 가장 마지막 값을 구하면서 배열로 따로 저장
	$max_id = 0;
	$i=0;

	//댓글이 존재해야 진행
	if($data_c[0]['check_exist']) {
		foreach ($data_c as $entry){ 
			if($entry['comment_id'] > $max_id) {
				$max_id = $entry['comment_id'];
			}
			echo "store_comment_id[{$i}]=new Array();";
			echo "store_comment_id[{$i}][0]={$entry['comment_id']};";
			echo "store_comment_id[{$i}][1]={$entry['depth']};";
			echo "store_comment_id[{$i}][2]={$entry['group_1_id']};";
			$i++;
		}
		//이건일단 뒤에서 사용안됨 (현재 max comment id 를 나타냄)
		echo "last_comment_id = {$max_id};";
	} 

	//read_id, comment_id를 전역번수로 선언
		//현재 읽기 read_id (불변)
	echo "global_read_id = {$data_w_read[0]['read_id']};"; 
		//글목록 용 read_id (가변) (가장최신꺼기준)
	echo "lately_read_id = {$data_w[0]['read_id']};"; 
		//글쓴이
	echo "read_writed_member = '{$data_w[0]['writed_member']}';";
		//현재 페이지 확인
	echo "check_page = ".$_GET['page'].";";
  		// 현재 페이지 정보
   echo "function_name = '{$function_name}';";
   echo "class_name = '{$this->class_name}';";


   //질답 게시판 여부 
   if(isset($data_w_read[0]['solve_q'])) {
   	echo "check_qaa_board = 1;";
   	$check_qaa_board = 1;

   	if($data_w_read[0]['solve_q']==1) {
   		echo "check_qaa_solve = 1;";
   		$check_qaa_solve = 1;
   	} else {
   		echo "check_qaa_solve = 0;";
   		$check_qaa_solve = 0;
   	}

   } else {
   	echo "check_qaa_board = 0;";
   	$check_qaa_board = 0;
   }


	//로그인 체크함수
	$check_loggin = $this->session->userdata('logged_in');
	if($check_loggin ) {
		echo "check_loggin = ".$check_loggin.";";
	} else {
		echo "check_loggin = 0 ;";
	}

	echo "</script>";
   /* 배열 초기화 테스트 및 배열 복사 테스트
   echo "console.log(store_comment_id);"; echo "store_comment_id = new Array();"; echo "console.log(store_comment_id);"; echo "var check_store =new Array();"; echo "check_store[0] = store_comment_id[1];"; echo "check_store[1] = store_comment_id[9];"; echo "console.log(check_store);";*/
   
//////////////////////////csrf 방지///////////////////////////////

echo "<div>";

$data_w_read[0]['token_name'] = $this->security->get_csrf_token_name();
$data_w_read[0]['hash'] = $this->security->get_csrf_hash();
echo 	"<input type='hidden' id='ci_t' name='".$data_w_read[0]['token_name']."' value='".$data_w_read[0]['hash']."' />";


	//다른 글에 댓글 등록 방지 및 신고 방지
	if(!$this->session->userdata('hash_salt1')) {
		$result_i = '';
		for ($i = 0; $i < 4; $i++) {
		   $result_j = mt_rand(1, 9);
		   $result_i = $result_i.$result_j;
		}
		//echo $result_i;
		$this->session->set_userdata('hash_salt1', $result_i);
	}

$md5_salt1 = $this->session->userdata('hash_salt1');
$md5_salt2 = '';
require_once('./static/include/view/md5_encryption.php');

echo 	"<input type='hidden' id='ci_r' name='ci_r' value='".md5_encryption($data_w_read[0]['read_id'], $md5_salt1, $md5_salt2)."' />";
//댓글이 존재해야 진행
if($data_c[0]['check_exist']) {
	foreach ($data_c as $entry){ 
		echo 	"<input type='hidden' id='ci_c_{$entry['comment_id']}' name='ci_c_{$entry['comment_id']}' value='".md5_encryption($entry['comment_id'], $md5_salt1, $md5_salt2)."' />";
	}
}

echo "</div>";

?>


<div id = "brdRd_main">
	
<?php include "./static/include/view/board/MOBILE_sub_list.php"; ?>


	<div id = "brdRd_wrapper">
		<div id = "brdRd_SpH1-up">

			<div id = "brdRd_Dstl_RdInfo">

				<div id = "brdRd_ShpRdInfo-rt">
					<div id = "brdRd_ShpRdInfo-rt-up">
						<!-- 설게상 바뀜 -->
						<div id = "brdRd_ShpRdInfo-lft">
							<div id = "brdRd_Dstl_RdPt">
<?php
	if(empty($data_w_read[0]['user_img']) ){
		echo "<img class = 'C_img_Rd_user' src = '/static/img/anonymity-Rd.jpg'>";
	} else {
		echo "<img class = 'C_img_Rd_user' src = '{$data_w_read[0]['user_img']}'>";
	}
?> 								
							</div>
						</div>


						글귀가 들어갈곳임
					</div>

					<div id = "brdRd_plecRdInfo-rt-dn">
					<div id = "brdRd_Dstl_RdNm">
						닉네임은여섯글
					</div>
<?php		
//////////////////////////글 버튼 설정//////////////////////////////		
	//익명 글일떄는 나타나지 않는 버튼
	if($data_w_read[0]['check_owner'] != -1) {		
		// echo 	"<button class = 'C_btn-H40'>kakao 상담하기 </button>";
	}


	//개발노트 규칙에 따라 나눔
	if ($check_loggin) {
		if($data_w_read[0]['check_owner'] == 1 ) {
			//본인일 경우
			w_modify_form ($data_w_read,$check_loggin, $function_name);
			w_delete_form ($data_w_read,$check_loggin, $function_name);
		} else if ( $data_w_read[0]['check_owner'] == -1 || $data_w_read[0]['check_owner'] == 0 ) { //그외
			w_reported_form ($data_w_read, $function_name,$this->class_name);
		}

	} else {
		if($data_w_read[0]['check_owner'] == -1) {
			//익명일 경우
			w_modify_form ($data_w_read,$check_loggin, $function_name);
			w_delete_form ($data_w_read,$check_loggin, $function_name);
			w_reported_form ($data_w_read, $function_name,$this->class_name);
		} else if ($data_w_read[0]['check_owner'] == 2) {
			//그외
			w_reported_form ($data_w_read, $function_name,$this->class_name);
		}

	}
			

//글 수정 정보를 위한 form 전송
function w_modify_form ($data_w_read, $check_loggin, $function_name) {

	if($check_loggin ) {
		//로그인 되어 있으면 패스워드 검사 필요없음
		echo "<form action = '/board/{$function_name}/modify/{$data_w_read[0]['read_id']}' method='post' class = 'C_float_left'>";
	} else {
		//로그인 안되어 있으면 검사 진행
		echo "<form action = '/board/{$function_name}/modify/{$data_w_read[0]['read_id']}' method='post' onsubmit = 'return insert_anony_passwd(this);' class = 'C_float_left'>";
	}
	echo 	"<input type='hidden' name='".$data_w_read[0]['token_name']."' value='".$data_w_read[0]['hash']."' />";
	echo "<input name = 'id_check' type = 'password' value = '{$data_w_read[0]['id_check']}' class = 'C_style_input_passwd_hide'></input>";
	echo "<input name = 'read_id' type = 'password' value = '{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>";
	echo "

	<button class = 'C_btn-H40'>글 수정</button>
	</form>";
}

//글 삭제를 위한 form 전송
function w_delete_form ($data_w_read, $check_loggin, $function_name) {


	if($check_loggin ) {
		//로그인 되어 있으면 패스워드 검사 필요없음
		echo "<form action = '/board/{$function_name}/deletewrite/{$data_w_read[0]['read_id']}' method='post' onsubmit='return confirm_delete();' class = 'C_float_left'>";
	} else {
		echo "<form action = '/board/{$function_name}/deletewrite/{$data_w_read[0]['read_id']}' method='post' onsubmit='return insert_anony_passwd(this);' class = 'C_float_left'>";
	}
	echo 	"<input type='hidden' name='".$data_w_read[0]['token_name']."' value='".$data_w_read[0]['hash']."' />";
	echo "<input name = 'id_check' type = 'password' value = '{$data_w_read[0]['id_check']}' class = 'C_style_input_passwd_hide'></input>";
	echo "<input name = 'read_id' type = 'password' value = '{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>";
	echo "<button class = 'C_btn-H40'>글 삭제</button>
		 </form>";
}

//글 신고용 form
function w_reported_form ($data_w_read, $function_name, $class_name) {
	echo "
		<form style='display:inline-block; ' action = '/csc/userreport' method='post' onclick = 'return confirm_report(this);'>
			
			<input type = 'password' name = 'class_name' value ='{$class_name}' class = 'C_style_input_passwd_hide'></input>
			<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
			<input type = 'password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>
			<button class = 'C_btn-H40'>글 신고</button>
		</form>";
}

?>
				
					</div>
				</div>
			</div>


			<div id = "brdRd_Dstl_RdTtl">
				<div id = "brdRd_SpRdTtl-lft">
<?PHP
						echo "제 목 : {$data_w_read[0]['title']}"; 
?>
				</div>
				<div id = "brdRd_SpRdTtl-rt">
<?PHP

						$date = explode(' ',$data_w_read[0]['date']);
						//print_r($date); //날짜 테스트용
						echo "{$date[0]}";
						echo "조회수: {$data_w_read[0]['click_num']} ";
?>
						&nbsp
				</div>
			</div>

		</div>

		<div id = "brdRd_SpH1-dn">
			<div id = "brdRd_Dstl_RdCt">
<?PHP echo "<p>{$data_w_read[0]['content']}</p>" ?>
			</div>
		</div>

		<div id = "brdRd_DRsv_Cm">


<?php

/*
comment_id = 삭제주소 (id 구지 필요없음)
//print_r($data_c);
/echo "<script>console.log(".$data_c[0]['group_1_id'].");</script>";
*/			
//////////////////////////초기 댓글 구성//////////////////////////////		
			//댓글이 없을때와 있을때를 구분
			if($data_c[0]['check_exist'] != 0) {
				$check_change_g1 = 0; //g1 이 바뀌는 것을 체크함
				$check_exist_g2 = 0; //g1 이 없을경우 답글이 있는지를 체크함
				$check_start_div = 0; //1일경우 댓글은 없는데 답글은 있는 경우임
				//echo "<script>console.log(".$data_c[0]['group_1_id'].");</script>";
				foreach ($data_c as $entry){

					if($entry['group_1_id'] > $check_change_g1 ){
						//echo "<script>console.log(".$entry['group_1_id'].");</script>";
						$check_change_g1 = $entry['group_1_id']; //이로써 한번만 실행이 됨
						if($entry['group_2_id'] != 0) {
							//그룹2의 처음이 0이 아닐경우 (즉, 0이 없을경우) start 지점을 재지정
							$check_start_div = 1; //즉, 이것은 답글
														 
						} else {
							$check_start_div = 0; //이것은 댓글
						}

						//div 가 이상하게 닫아져서 여기다가 넣음
						if( $check_change_g1 > 1) {
							echo "</div>";
						}
					} 

					if($check_start_div == 1) {

						//사진없음 (삭제된 댓글)
						echo "
						<div class = 'brdRd-Dplec-Cm'  name = 'comment_group_1_{$entry['group_1_id']}'>
							<div class = 'brdRd-Dstl-Cm' id = 'dont_exist_reader'>
								<div class = 'brdRd-Dstl-CmPt'>
						
								</div>

								<div class = 'brdRd-Dstl-CmInfo'>
								</div>

								<div class = 'brdRd-Dstl-CmQaa'>삭제된 댓글입니다.</form></div>
							</div>";
						$check_start_div = 0;
					}

					//'댓글일때 / 답글일때'를 나타낸것
					if(!$entry['group_2_id'] && !$entry['depth']) {
						
						$entry['date'] = date('y-m-d H:i', strtotime($entry['date']));
						//댓글일때
						echo " 
						<div class = 'brdRd-Dplec-Cm' name = 'comment_group_1_{$entry['group_1_id']}'>
							<div class = 'brdRd-Dstl-Cm' name = 'comment_id_{$entry['comment_id']}'>
								<div class = 'brdRd-Dstl-CmPt'> ";

						if(empty($entry['user_img']) ){
							echo "<img class = 'C_img_Cm_user' src = '/static/img/anonymity-Cm.jpg'>";
						} else {
							echo "<img class = 'C_img_Cm_user' src = '{$entry['user_img']}'>";
						}
 
							echo "	
						      		</div>

								<div class = 'brdRd-Dstl-CmInfo'>
									<div id ='user_id'>{$entry['writed_member']}</div> 
									<div id = 'brdRd_stlCm_UsrDt'> {$entry['date']} </div>



									";

									//개발노트 12-3 확인 
									if($check_qaa_board && $check_qaa_solve == 1 && $entry['selected_a'] == 1) {
									   echo " <span style ='color:red'> 채택완료 </span>" ;			   
									} else if  ($check_qaa_board && $check_qaa_solve == 0 && ($data_w_read[0]['check_owner'] == -1 || $data_w_read[0]['check_owner'] == 1) ) {

										if($entry['check_owner'] == 0 && $data_w_read[0]['check_owner'] == 1) {
											//본인(회원)글 외의 회원일 경우
											echo " <form style='display:inline-block;' action = '/board/{$function_name}/selectanswer' onclick = 'return true;' method='post' >  
											<input type='hidden' name='".$data_w_read[0]['token_name']."' value='".$data_w_read[0]['hash']."' />
											<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
											<input type = 'password' name = 'comment_id' value ='{$entry['comment_id']}' class = 'C_style_input_passwd_hide'></input>
											
											<a href='javascript:;' onclick='submit_form(this);'> 답변채택 </a> </form> ";
										} else { /* 익명의 답변 채택 형식임 (스크립트 구성이 다되어 있음 ㅜㅜ)
											echo " <form style='display:inline-block;' class = 'board_Rd_appoint_select_form' action = '/board/{$function_name}/selectanswer' method='post' onclick = 'return insert_anony_passwd(this);' onsubmit = 'return false;'> 
											<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
											<input type = 'password' name = 'comment_id' value ='{$entry['comment_id']}' class = 'C_style_input_passwd_hide'></input>
											
											<a href = 'javascript:;'> 답변채택 </a> </form> ";
											*/
										}

									} else {

									}

						//////////////////////////댓글 버튼 구성/////////////////////////		
						echo "		<div class = 'brdRd-Dstl-CmCvc'>";

						//개발노트 규칙에 따라 나눔
						if ($check_loggin) {
							if($entry['check_owner'] == 1 ) {
								//본인일 경우
								c_delete_form ($data_w_read, $entry, $check_loggin, $function_name);
							} else if ( $entry['check_owner'] == -1 || $entry['check_owner'] == 0 ) { //그외
								c_answer_form ($data_w_read, $entry);
								c_reported_form ($data_w_read, $entry, $function_name, $this->class_name);
							}

						} else {
							if($entry['check_owner'] == -1) {
								//익명일 경우
								c_answer_form ($data_w_read, $entry);
								c_delete_form ($data_w_read, $entry, $check_loggin, $function_name);
								c_reported_form ($data_w_read, $entry, $function_name, $this->class_name);
							} else if ($entry['check_owner'] == 2) {
								//그외
								c_answer_form ($data_w_read, $entry);
								c_reported_form ($data_w_read, $entry, $function_name, $this->class_name);
							}

						}				

						//수정 가능 여부
						if($entry['super_user'] == 1) {
								
							echo "
								<span style='float:left; display:inline-block; cursor:pointer;' onclick = 'add_tool_for_modify_comment(this)'> 
									
									<input type = 'password' name = 'id_check' value ='{$entry['id_check']}' class = 'C_style_input_passwd_hide'></input>
									<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
									<input type = 'password' name = 'comment_id' value ='{$entry['comment_id']}' class = 'C_style_input_passwd_hide'></input>
									수정 |
								</span>

								";
						}

						echo "				

									</div>
								</div>

								<!-- 해당값 띄울경우에 수정할때 값 가져오면 띄어서 가져오는 에러 발생 -- -->
								<div class = 'brdRd-Dstl-CmQaa'>{$entry['content']}</form></div>
							</div>";
					} else if ( ($entry['group_2_id'] > 0) && $entry['depth']) {
						//답글일때 (댓글일때랑 구체적 차이가 별로 없다..)
						$entry['date'] = date('y-m-d H:i', strtotime($entry['date']));
						echo "
							<div class = 'brdRd-Dstl-ACm' name = 'comment_id_{$entry['comment_id']}'>
								<div class = 'brdRd-Dstl-ACmPt'> ";

						if(empty($entry['user_img']) ){
							echo "<img class = 'C_img_Cm_user' src = '/static/img/anonymity-Cm.jpg'>";
						} else {
							echo "<img class = 'C_img_Cm_user' src = '{$entry['user_img']}'>";
						}
 
							echo " </div>

								<div class = 'brdRd-Dstl-ACmInfo'>
									<div id ='user_id'>{$entry['writed_member']}</div> 
									<div id = 'brdRd_stlCm_UsrDt'> {$entry['date']} </div>
									";

									//위에랑 똑같음
									if($check_qaa_board && $check_qaa_solve == 1 && $entry['selected_a'] == 1) {
									   echo " <span style ='color:red'> 채택완료 </span>" ;			   
									} else if  ($check_qaa_board && $check_qaa_solve == 0 && ($data_w_read[0]['check_owner'] == -1 || $data_w_read[0]['check_owner'] == 1) ) {

										if($entry['check_owner'] == 0 && $data_w_read[0]['check_owner'] == 1) {
											//본인(회원)글 외의 회원일 경우
											echo " <form style='display:inline-block;' action = '/board/{$function_name}/selectanswer' onclick = 'return true;' method='post' >  
											<input type='hidden' name='".$data_w_read[0]['token_name']."' value='".$data_w_read[0]['hash']."' />
											<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
											<input type = 'password' name = 'comment_id' value ='{$entry['comment_id']}' class = 'C_style_input_passwd_hide'></input>
											
											<a href='javascript:;' onclick='submit_form(this);'> 답변채택 </a> </form> ";
										} else { /* 익명의 답변 채택 형식임 (스크립트 구성이 다되어 있음 ㅜㅜ)
											echo " <form style='display:inline-block;' class = 'board_Rd_appoint_select_form' action = '/board/{$function_name}/selectanswer' method='post' onclick = 'return insert_anony_passwd(this);' onsubmit = 'return false;'> 
											<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
											<input type = 'password' name = 'comment_id' value ='{$entry['comment_id']}' class = 'C_style_input_passwd_hide'></input>
											
											<a href = 'javascript:;'> 답변채택 </a> </form> ";
											*/
										}

									} else {

									}

						//////////////////////////댓글 버튼 구성/////////////////////////		
						echo "	<div class = 'brdRd-Dstl-CmCvc'>";

						//개발노트 규칙에 따라 나눔
						if ($check_loggin) {
							if($entry['check_owner'] == 1 ) {
								//본인일 경우
								c_delete_form ($data_w_read, $entry, $check_loggin, $function_name);
							} else if ( $entry['check_owner'] == -1 || $entry['check_owner'] == 0 ) { //그외
								c_answer_form ($data_w_read, $entry);
								c_reported_form ($data_w_read, $entry, $function_name, $this->class_name);
							}

						} else {
							if($entry['check_owner'] == -1) {
								//익명일 경우
								c_answer_form ($data_w_read, $entry);
								c_delete_form ($data_w_read, $entry, $check_loggin, $function_name);
								c_reported_form ($data_w_read, $entry, $function_name, $this->class_name);
							} else if ($entry['check_owner'] == 2) {
								//그외
								c_answer_form ($data_w_read, $entry);
								c_reported_form ($data_w_read, $entry, $function_name, $this->class_name);
							}

						}	

						//수정 가능 여부
						if($entry['super_user'] == 1) {
							echo "
								<span style='float:left; display:inline-block; cursor:pointer;' onclick = 'add_tool_for_modify_comment(this)'> 
									
									<input type = 'password' name = 'id_check' value ='{$entry['id_check']}' class = 'C_style_input_passwd_hide'></input>
									<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
									<input type = 'password' name = 'comment_id' value ='{$entry['comment_id']}' class = 'C_style_input_passwd_hide'></input>
									수정 |
								</span>

								";
						}

						echo "
									</div>
								</div>

								<div class = 'brdRd-Dstl-ACmQaa'>{$entry['content']}</div>
							</div>";
					} else {
						echo "<script>alert('예기치 않은 에러, 관리자에게 문의주세요.');</script> ";
					}
				}
			} else {
				//echo '<script>alert("ads");</script>';
				$data_c[0]['comment_id'] = 0;
			}

			//맨위에서 마지막에는 실행이 안되서 (닫아주질 못함) 에러때문에 넣음
			if($data_c[0]['comment_id'] != 0) {
				echo "</div>";
			}



		function c_answer_form ($data_w_read, $entry) {
		//댓글 답글을 위한 form 전송 
			echo "
				<span style='float:left; display:inline-block; cursor:pointer;' onclick = 'add_tool_for_answer_comment(this)'> 
					<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
					<input type = 'password' name = 'group_1_id' value ='{$entry['group_1_id']}' class = 'C_style_input_passwd_hide'></input>
					답글 | 
				</span>";
		}

		function c_delete_form ($data_w_read, $entry, $check_loggin, $function_name) {
		//댓글 삭제를 위한 form 전송
			if($check_loggin ) {
				//로그인 되어 있으면 패스워드 검사 필요없음
				echo "<form style='display:inline-block; ' action = '/board/{$function_name}/deletecomment/".$entry['comment_id']."' method='post' onclick = 'delete_comment_JS(this)' onsubmit = 'return false;'>";
			} else {
				//로그인 안되어 있으면 검사 진행
				echo "<form style='display:inline-block; ' action = '/board/{$function_name}/deletecomment/".$entry['comment_id']."' method='post'  onclick = 'return insert_anony_passwd(this);'  onsubmit = 'return false;' >";
			}
		
			echo "
					<a href = 'javascript:;'>삭제 </a>  
					<input name = 'id_check' type = 'password' value = '{$entry['id_check']}' class = 'C_style_input_passwd_hide'></input>
				</form> ";
		}

		function c_reported_form ($data_w_read, $entry, $function_name, $class_name) {

			echo "
				<form style='display:inline-block; ' action = '/csc/userreport' method='post' onclick = 'return confirm_report(this);'>

					<input type = 'password' name = 'class_name' value ='{$class_name}' class = 'C_style_input_passwd_hide'></input>
					<input type = 'password' name = 'read_id' value ='{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>
					<input type = 'password' name = 'function_name' value ='{$function_name}' class = 'C_style_input_passwd_hide'></input>
					<input type = 'password' name = 'comment_id' value ='{$entry['comment_id']}' class = 'C_style_input_passwd_hide'></input>

					<a href = 'javascript:;'>신고 | </a> 
				</form>

				";
		}

?>

			<!--글 쓰기 부분 -->
			<div id = "brdRd_Dplec_Cm">
				<div id = "brdRd_Dstl_Cm">
					<div id = "brdRd_Dstl_CmPt">
<?php 
	if($this->session->has_userdata('img_url'))
		echo "<img class='C_img_Cm_user' src='{$this->session->userdata('img_url')}'>";
	else 
		echo "<img class='C_img_Cm_user' src='/static/img/anonymity-Cm.jpg'>";
?>
					</div>
<?php
//////////////////////////댓글 작성 정보 구성/////////////////////////					
					$check_loggin = $this->session->userdata('logged_in');
					if($check_loggin) {
						//댓글 작성부분 위쪽에 표시됨
						echo "
						<div id = 'brdRd_Dstl_CmInfo'>
							{$this->session->userdata('nickname')}
						</div>";
					}
?>
					<div id = "brdRd_Dstl_CmQaa" >
<?php
					echo "<form name = 'comment_form' action = '/board/{$function_name}/uploadcomment' method='post'>";

					//write_page 랑 동일하게 설정할것
					if(!$check_loggin) {
						echo " <div id = 'brdRd_Dstl_CmInfo'> 
						<input type = 'text' name = 'anony_nickname' class = 'C_style_input_nickname' placeholder = '닉네임: 한글기준 6자이하 '>
						<input type = 'password' name = 'anony_passwd' class = 'C_style_input_passwd_show' placeholder = '비밀번호: 띄어쓰기 금지'> 
						</div>
						";
					}

					/*글의 id 정보*/
					echo "<input name = 'read_id' type = 'password' value = '{$data_w_read[0]['read_id']}' class = 'C_style_input_passwd_hide'></input>";
						
?>

							<textarea class = 'C_txtr-W500' name = "content" placeholder="내용을 입력해주세요" id = 'for_check_text'></textarea> 
							<button class = 'C_btn-H50' id="brdRd_Bappt_CmUpload">등록
							</button>
						</form>
							<button id ="brdRd_Dplec_reloadCm-off" > 댓글 새로고침
							</button>
					</div>
				</div>
			</div>


		<div>
	</div>


<?php 

		if($check_gallery) {
			include "./static/include/view/board/MOBILE_write_list_for_gallery.php"; 
		} else {
	 		include "./static/include/view/board/MOBILE_write_list.php";
	 	} 
?>

</div>

