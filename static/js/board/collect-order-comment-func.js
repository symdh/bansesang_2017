//댓글 관련 함수들임
//ajax적용을 위해 댓글 넘어오는 정보를 실질적으로 구성시켜줌
//arr이 넘어오는 정보임

function add_comment_in_g1 (arr) {
	//그룹은 이미 존재하므로 답글만 추가시킴
	//add_comment_in_g1(result[j]);
		
	//개발노트 규칙에 따라 나눔
	if (check_loggin) {

		if( arr['check_owner'] == 1 ) {
			//본인일 경우
			var result_func_select = "";
			var result_func_1 = c_delete_form (arr);
			var result_func_2 = "" ;
			var result_func_3 = "" ;
		} else if (arr['check_owner'] == 0) {
			//본인 외 회원일 경우 
			var result_func_select = select_a_form(arr);
			var result_func_1 = c_answer_form (arr);
			var result_func_2 = c_reported_form (arr, function_name, class_name);
			var result_func_3 = "" ;
		} else if (arr['check_owner'] == -1) {
			var result_func_select = "";
			var result_func_1 = c_answer_form (arr);
			var result_func_2 = c_reported_form (arr, function_name, class_name);
			var result_func_3 = "" ;
		}


	} else {
		if(arr['check_owner'] == -1) {
			//익명일 경우
			var result_func_select = "";
			var result_func_1 = c_answer_form (arr);
			var result_func_2 = c_delete_form (arr);
			var result_func_3 = c_reported_form (arr, function_name, class_name);
		} else if (arr['check_owner'] == 2) {
			//그외
			var result_func_select = "";
			var result_func_1 = c_answer_form (arr);
			var result_func_2 = c_reported_form (arr, function_name, class_name);
			var result_func_3 = "" ;
		}
	}	

	//수정 가능여부
	if(arr['super_user'] == 1) {
		var result_func_modify = c_modify_form (arr);
	} else {
		var result_func_modify = "";
	}

	if(arr['user_img']==0 || arr['user_img']==null) arr['user_img'] = '/static/img/anonymity-ACm.jpg';
	var result_str = "" + 
		//답글(추가부분 없음)
		"<div class = 'brdRd-Dstl-ACm' name = 'comment_id_"+arr['comment_id']+"'>"+
			"<div class = 'brdRd-Dstl-ACmPt'>"+
				"<img class = 'C_img_Cm_user' src = '"+arr['user_img']+"' >"+
			"</div>"+

			"<div class = 'brdRd-Dstl-ACmInfo'>"+
				"<div id ='user_id'>"+arr['writed_member']+"</div>"+ 
				"<div id = 'brdRd_stlCm_UsrDt'>"+arr['date']+"</div>"+
				result_func_select+
				"<div class = 'brdRd-Dstl-CmCvc'>"+
					
				result_func_1+result_func_2+result_func_3+ result_func_modify+
					
				"</div>"+
			"</div>"+

			"<div class = 'brdRd-Dstl-ACmQaa'>"+arr['content']+"</div>"+
		"</div>"	;

	$('div[name=comment_group_1_'+arr['group_1_id']+']').append(result_str);
}

function add_show_delete_info (arr) {
//삭제문을 넣지 않으면 아래 문이 제대로 실행되지 않음
//add_show_delete_info(store_comment_id[i]);

//삭제된 댓글 사진없음.
	if (arr[1] == 0) { 
		$('div[name=comment_group_1_'+arr[2]+']').prepend(
			"<div class = 'brdRd-Dstl-Cm' id = 'dont_exist_reader'>"+
				"<div class = 'brdRd-Dstl-CmPt'>"+
					
				"</div>"+

				"<div class = 'brdRd-Dstl-CmInfo'>"+
				"</div>"+

				"<div class = 'brdRd-Dstl-CmQaa'>삭제된 댓글입니다.</form></div>"+
			"</div>"
		);
	}
}

function add_comment (arr, show_delete_info, check_c_or_ac) {
//댓글이 추가되는 상황이므로 삭제문을 보여줄 필요가 없음
//add_comment(result[j],false, c)
//답글인데 그룹이 존재하지 않으므로 이미 삭제된 댓글임 (삭제문 출력후 추가시킴)
//add_comment(result[j],true, ac)

//show_delete_c 는 삭제문을 보여줄지 여부
//check_c_or_ac 는 답글인지 댓글인지 여부 
	
	if(show_delete_info) {
		var check_delete_str = "<div class = 'brdRd-Dplec-Cm'  name = 'comment_group_1_"+arr['group_1_id']+"'>"+
											"<div class = 'brdRd-Dstl-Cm' id = 'dont_exist_reader'>"+
												"<div class = 'brdRd-Dstl-CmPt'>"+
													
												"</div>"+

												"<div class = 'brdRd-Dstl-CmInfo'>"+
												"</div>"+

												"<div class = 'brdRd-Dstl-CmQaa'>삭제된 댓글입니다.</form></div>"+
											"</div>";
	} else {
		var check_delete_str = "<div class = 'brdRd-Dplec-Cm'  name = 'comment_group_1_"+arr['group_1_id']+"'>";
	}

	if(check_c_or_ac == 'c') {

		//개발노트 규칙에 따라 나눔
		if (check_loggin) {
			if( arr['check_owner'] == 1 ) {
				//본인일 경우
				var result_func_select = "";
				var result_func_1 = c_delete_form (arr);
				var result_func_2 = "" ;
				var result_func_3 = "" ;
			} else if (arr['check_owner'] == 0) {
				//본인 외 회원일 경우 
				var result_func_select = select_a_form(arr);
				var result_func_1 = c_answer_form (arr);
				var result_func_2 = c_reported_form (arr, function_name, class_name);
				var result_func_3 = "" ;
			} else if (arr['check_owner'] == -1) {
				var result_func_select = "";
				var result_func_1 = c_answer_form (arr);
				var result_func_2 = c_reported_form (arr, function_name, class_name);
				var result_func_3 = "" ;
			}
		} else {
			if(arr['check_owner'] == -1) {
				//익명일 경우
				var result_func_select = "";
				var result_func_1 = c_answer_form (arr);
				var result_func_2 = c_delete_form (arr);
				var result_func_3 = c_reported_form (arr, function_name, class_name);
			} else if (arr['check_owner'] == 2) {
				//그외
				var result_func_select = "";
				var result_func_1 = c_answer_form (arr);
				var result_func_2 = c_reported_form (arr, function_name, class_name);
				var result_func_3 = "" ;
			}
		}	

		//수정 가능여부
		if(arr['super_user'] == 1) {
			var result_func_modify = c_modify_form (arr);
		} else {
			var result_func_modify = "";
		}

		//댓글일때
		if(arr['user_img']==0 || arr['user_img']==null) arr['user_img'] = '/static/img/anonymity-Cm.jpg';
		var result_str = "<div class = 'brdRd-Dstl-Cm' name = 'comment_id_"+arr['comment_id']+"'>"+
									"<div class = 'brdRd-Dstl-CmPt'>"+
										"<img class = 'C_img_Cm_user' src = '"+arr['user_img']+"' >"+
									"</div>"+

									"<div class = 'brdRd-Dstl-CmInfo'>"+
										"<div id ='user_id'>"+arr['writed_member']+"</div>"+ 
										"<div id = 'brdRd_stlCm_UsrDt'>"+arr['date']+"</div>"+
										
										result_func_select+
										"<div class = 'brdRd-Dstl-CmCvc'>"+

											result_func_1+result_func_2+result_func_3+result_func_modify+

										"</div>"+
									"</div>"+

									
									"<div class = 'brdRd-Dstl-CmQaa'>"+arr['content']+"</form></div>"+
								"</div>"+
							"</div>";
	} else {
		//개발노트 규칙에 따라 나눔
		if (check_loggin) {
			if( arr['check_owner'] == 1 ) {
				//본인일 경우
				var result_func_select = "";
				var result_func_1 = c_delete_form (arr);
				var result_func_2 = "" ;
				var result_func_3 = "" ;
			} else if (arr['check_owner'] == 0) {
				//본인 외 회원일 경우 
				var result_func_select = select_a_form(arr);
				var result_func_1 = c_answer_form (arr);
				var result_func_2 = c_reported_form (arr, function_name, class_name);
				var result_func_3 = "" ;
			} else if (arr['check_owner'] == -1) {
				var result_func_select = "";
				var result_func_1 = c_answer_form (arr);
				var result_func_2 = c_reported_form (arr, function_name, class_name);
				var result_func_3 = "" ;
			}
		} else {
			if(arr['check_owner'] == -1) {
				//익명일 경우
				var result_func_select = "";
				var result_func_1 = c_answer_form (arr);
				var result_func_2 = c_delete_form (arr);
				var result_func_3 = c_reported_form (arr, function_name, class_name);
			} else if (arr['check_owner'] == 2) {
				//그외
				var result_func_select = "";
				var result_func_1 = c_answer_form (arr);
				var result_func_2 = c_reported_form (arr, function_name, class_name);
				var result_func_3 = "" ;
			}
		}	

		//수정 가능여부
		if(arr['super_user'] == 1) {
			var result_func_modify = c_modify_form (arr);
		} else {
			var result_func_modify = "";
		}

		//답글일때
		if(arr['user_img']==0 || arr['user_img']==null) arr['user_img'] = '/static/img/anonymity-ACm.jpg';
		var result_str = "<div class = 'brdRd-Dstl-ACm' name = 'comment_id_"+arr['comment_id']+"'>"+
									"<div class = 'brdRd-Dstl-ACmPt'>"+
										"<img class = 'C_img_Cm_user' src = '"+arr['user_img']+"' >"+
									"</div>"+

									"<div class = 'brdRd-Dstl-ACmInfo'>"+
										"<div id ='user_id'>"+arr['writed_member']+"</div>"+ 
										"<div id = 'brdRd_stlCm_UsrDt'>"+arr['date']+"</div>"+
										result_func_select+
										"<div class = 'brdRd-Dstl-CmCvc'>"+
											
											result_func_1+result_func_2+result_func_3+result_func_modify+

											

											
										"</div>"+
									"</div>"+

									"<div class = 'brdRd-Dstl-ACmQaa'>"+arr['content']+"</div>"+
								"</div>"+
							"</div>";

	}

	var result = check_delete_str + result_str;

	return result;

}




function delete_if_dont_exist_div_g1 (i, count_store, arr1, arr2) {
//arr1, arr2 는 구릅을 비교하기 위한것 arr1 < arr2 순으로 입력
//답글 댓글이 모두 삭제되면 그룹 삭제
//delete_if_dont_exist_div_g1(i, count_store, store_comment_id[i-1], store_comment_id[i]);

  //댓글이 이미 삭제되었거나 삭제될때, 답글도 다 없어지면 그 div를 처리하는 문 
	if(i == count_store || (arr1[2] < arr2[2])) {
		//console.log($('div[name=comment_group_1_'+arr1[2]+']').children('#dont_exist_reader').next());
		//console.log($('div[name=comment_group_1_'+arr1[2]+']').children('#dont_exist_reader').next().length);
		if($('div[name=comment_group_1_'+arr1[2]+']').children('#dont_exist_reader').length > 0) {

			if ($('div[name=comment_group_1_'+arr1[2]+']').children('#dont_exist_reader').next().length > 0){
				
			} else {
				//alert("삭제된다");
				$('div[name=comment_group_1_'+arr1[2]+']').remove();
				
			}
		}

	} else {
		
	}

}

function select_a_form (arr) {

	//qaa 게시판인지 여부
	if(check_qaa_board && read_writed_member != arr['writed_member']) {
		//이미 채택되었는지 여부
		if(check_qaa_solve) {
			var result_str = ""
		} else {
			var result_str = "" +
			  " <form style='display:inline-block;' action = '"+class_name+'/'+function_name+"/selectanswer' onclick = 'return true;' method='post' >"+  

				"<input type = 'password' name = 'read_id' value ='"+arr['read_id']+"' class = 'C_style_input_passwd_hide'></input>"+
				"<input type = 'password' name = 'comment_id' value ='"+arr['comment_id']+"' class = 'C_style_input_passwd_hide'></input>"+
				
				"<a href='javascript:;' onclick='submit_form(this);'> 답변채택 </a> </form> ";
		}
	} else {
		var result_str = ""
	}

	
	return result_str;
}



function c_answer_form (arr) {
//댓글 답글을 위한 form 전송 
	var result_str = "" +
		"<span style='float:left; display:inline-block; cursor:pointer;' onclick = 'add_tool_for_answer_comment(this)'> "+
			"<input type = 'password' name = 'read_id' value ='"+arr['read_id']+"' class = 'C_style_input_passwd_hide' 추가></input>"+
			"<input type = 'password' name = 'group_1_id' value ='"+arr['group_1_id']+"' class = 'C_style_input_passwd_hide' 추가></input>"+
			"답글 | "+
		"</span>";
	return result_str;
}

function c_delete_form (arr) {
//댓글 삭제를 위한 form 전송

	if(check_loggin) {
		var check_loggin_for_form = "<form style='display:inline-block; ' action = '"+class_name+'/'+function_name+"/deletecomment/"+arr['comment_id']+"' method='post' onclick = 'delete_comment_JS(this)' onsubmit = 'return false;'>";
	} else {
		var check_loggin_for_form = "<form style='display:inline-block; ' action = '"+class_name+'/'+function_name+"/deletecomment/"+arr['comment_id']+"' method='post'  onclick = 'return insert_anony_passwd(this);'  onsubmit = 'return false;' >";
	}

	var result_str = "" + check_loggin_for_form +
			"<a href = 'javascript:;'>삭제 </a> "+
			"<input name = 'id_check' type = 'password' value = '"+arr['id_check']+"' class = 'C_style_input_passwd_hide' 추가></input>"+
		"</form>";
	return result_str;
}

//회원시 신고란
function c_reported_form (arr, function_name, class_name) {
	var result_str = "" +
		"<form style='display:inline-block; ' action = '/csc/userreport' method='post' onclick = 'return confirm_report(this);'>"+
			"<input type = 'password' name = 'class_name' value ='"+class_name+"' class = 'C_style_input_passwd_hide'></input>"+
			"<input type = 'password' name = 'ci_c_"+arr['comment_id']+"' value ='"+arr['encryption_report']+"' class = 'C_style_input_passwd_hide'></input>"+			
			"<input type = 'password' name = 'read_id' value ='"+arr['read_id']+"' class = 'C_style_input_passwd_hide'></input>"+
			"<input type = 'password' name = 'function_name' value ='"+function_name+"' class = 'C_style_input_passwd_hide'></input>"+
			"<input type = 'password' name = 'comment_id' value ='"+arr['comment_id']+"' class = 'C_style_input_passwd_hide'></input>"+

			"<a href = 'javascript:;'>신고 | </a> "+
		"</form>";

	return result_str;
}

//댓글 수정가능 여부 
function c_modify_form (arr) {
	var result_str = "" +
	"<span style='float:left; display:inline-block; cursor:pointer;' onclick = 'add_tool_for_modify_comment(this)'>"+
												
		"<input type = 'password' name = 'id_check' value ='"+arr['id_check']+"' class = 'C_style_input_passwd_hide' 추가></input>"+
		"<input type = 'password' name = 'read_id' value ='"+arr['read_id']+"' class = 'C_style_input_passwd_hide' 추가></input>"+
		"<input type = 'password' name = 'comment_id' value ='"+arr['comment_id']+"' class = 'C_style_input_passwd_hide' 추가></input>"+
		"수정 |"+
	"</span>";

	return result_str;
}