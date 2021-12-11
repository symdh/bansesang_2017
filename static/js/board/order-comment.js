// 각종 댓글 form, function 
// 댓글 ajax 적용 logic 부분



//댓글 수정 클릭시 작동 스크립트
function add_tool_for_modify_comment(e) {

   //만약 수정 형식이 이미 열려있다면 닫아줌
   if($("form[name=form_for_modify_comment]").length > 0) {
      cancel_added_tool('span[name=button_cancel_modify]');
   }

   //답글이미 열려있다면 닫고 실행
   cancel_tool_for_answer_comment();

   //삭제부분 열려있다면 닫아줌
   cancel_check_anony_passwd();

   //신고부분 열려있으면 닫아줌
   if($('#brdRd_Dplec_Rp').length > 0 ) {
      cancel_report();
   }
   

   //$(e).parent().parent().next().css('background-color', 'red');
   //alert($(e).parent().parent().next().html());
   //console.log($(e).children().clone());
   
   //넣어줄 div 선택
   var set_position_div = $(e).parent().parent().next();
   //기존의 comment 저장
   var store_comment = set_position_div.html();
   //input을 복사해서 저장함 
   var clone_input = $(e).children().clone();

   //모두 저장한 뒤에 처리 
   //수정처리를 위해 name을 입력함 (수정취소시 name 없앰)
   $(e).attr('onclick','cancel_added_tool(this)');
   $(e).attr('name','button_cancel_modify');

   //이상하게 아래껀 되는데 이건왜 안되냐 씨발
   //clone 복사가 안되서 이렇게 넣어줬음
   var recent_expression = $(e).html();
   recent_expression = recent_expression.replace('수정 |','수정 취소 |');
   $(e).html(recent_expression);

   set_position_div.html("");
   //alert(set_position_div.html());
   set_position_div.append("<form method = 'post' action='#' name = 'form_for_modify_comment'></form>");
   var set_postion_form = $('form[name=form_for_modify_comment]');
   set_postion_form.append(clone_input);
   set_postion_form.append("<textarea class = 'C_txtr-W500' name = 'content'>");
   $('.C_txtr-W500:first').val(store_comment);
   set_postion_form.append("</textarea>");
   set_postion_form.append("<button class = 'C_btn-H80' type='submit' id='modify_button'>수정</button>");
   
   $('#modify_button').click(function(e){
      e.preventDefault();
      //alert("동작 확인용");

      var formData = $('form[name=form_for_modify_comment]').serialize();
      //console.log(formData);
      //console.log($('form[name=form_for_modify_comment]').children());
      
      var ajax_url = '/'+class_name+'/'+function_name+'/modifycomment';
      $.ajax({
         url: ajax_url,
         dataType: "json",
         processData: true,
         contentType: 'application/x-www-form-urlencoded',
         data: formData,
         type: 'POST',
         success: function(result){
            //alert("수정 완료!");
            //console.log(result);
            $('form[name=form_for_modify_comment]').remove();
            set_position_div.html(result[0]['content']);

            cancel_added_tool('span[name=button_cancel_modify]');
         },
         error:function(){
            alert('잘못된 접근입니다.');
         }
            
      });
   });
}

//댓글 수정 취소
function cancel_added_tool(e) {
   var clone_input = $(e).children().clone();
   $(e).attr('onclick','add_tool_for_modify_comment(this)');
   $(e).text('수정 |');

   //text로 사려져서 붙여넣기 한번 해야됨
   $(e).append(clone_input);

   //$(e).parent().parent().next().css('background-color', 'red');
   //넣어줄 div 선택
   var set_position_div = $(e).parent().parent().next();
   //textarea 중복, 위치변경, 상위위치 중복 등 처리시 에러남 
   var store_comment = $('.C_txtr-W500:first').val();
   //input 사라지면 안되서 append 사용
   set_position_div.append(store_comment);
   $('form[name=form_for_modify_comment]').remove();
   //console.log(set_position_div);
   //console.log(store_comment);

   //중간에 없애면 에러뜸
   $(e).attr('name','');
}  

   //댓글 reload 시스템
      $("#brdRd_Dplec_reloadCm-off").click(function(e){
         $('#brdRd_Dplec_reloadCm-off').css('color','black');  
         $(this).text("댓글 새로고침 중");
         $(this).attr('id','brdRd_Dplec_reloadCm-on');	
         
         apply_lately_comment();

         setTimeout(function(){ 
            $("#brdRd_Dplec_reloadCm-on").text("댓글 새로고침");
            $("#brdRd_Dplec_reloadCm-on").attr('id','brdRd_Dplec_reloadCm-off');
         }, 3000);

      });

//댓글입력시 ajax 처리
$("#brdRd_Bappt_CmUpload").click(function(e){
	//만약 수정 형식이 이미 열려있다면 닫아줌 (내용이 사라지는 버그때문)
	if($("form[name=form_for_modify_comment]").length > 0) {
		cancel_added_tool('span[name=button_cancel_modify]');
	}
	//답글 형식이 열려있다면 닫아줌 (그냥)
	cancel_tool_for_answer_comment();

   //삭제 열려있다면 닫아줌
   cancel_check_anony_passwd();

   //신고부분 열려있으면 닫아줌
    if($('#brdRd_Dplec_Rp').length > 0 ) {
		cancel_report();
	}

	//익명시에 검사 (+내용 규칙 포함)
	if(watch_comment_before_send()) {
		//true로 넘어오면 익명이 아니거나 검사규칙을 통과한것
	} else {
		return false;
	}
	

  	e.preventDefault();
  	var formData = $('form[name=comment_form]').serialize();
   formData = formData + "&"+$('#ci_t').attr('name')+"="+$('#ci_t').val();
   formData = formData + "&"+$('#ci_r').attr('name')+"="+$('#ci_r').val();
   //console.log(formData);
   
   //댓글 중복 등록 방지
   $('form[name=comment_form]>#for_check_text').val('');

   var ajax_url = '/'+class_name+'/'+function_name+'/uploadcomment';
    $.ajax({
      url: ajax_url,
      dataType: "html",
      processData: true,
      contentType: 'application/x-www-form-urlencoded',
      data: formData,
      type: 'POST',
      success: function(result){
         
         var reg = /알림/;
         if(reg.test(result)) {
            alert(result);
            window.location = '/'+class_name+'/'+function_name+'/list';
         }

      	//console.log(result);
      	$('textarea[name=content]').val("");

      	apply_lately_comment();

      }
   });
});

//답글 클릭시 작동 스크립트
function add_tool_for_answer_comment(e) {
	//$(e).parent().parent().next().css('background-color', 'red');
	//console.log($(e).children().clone());

	//이미 열려있다면 닫고 실행
	cancel_tool_for_answer_comment();

   //삭제부분 열려있다면 닫아줌
   cancel_check_anony_passwd();

   //신고부분 열려있으면 닫아줌
   if($('#brdRd_Dplec_Rp').length > 0 ) {
		cancel_report();
	}

	//넣어줄 div 생성
	var send_user = $(e).parent().parent().children('#user_id').text();
	$(e).parent().parent().parent().parent().append("<div class = 'brdRd-Dstl-ACm' name='div_for_answer_comment'><div class = 'brdRd-Dstl-ACmPt'>답글 쓰기</div><div class = 'brdRd-Dstl-ACmInfo'>"+send_user+"님께 답글</div><div class = 'brdRd-Dstl-ACmQaa' name = 'div_for_write_answer'></div></div>");
	//console.log(send_user);

	//input을 복사해서 미리 저장함 
	var clone_input = $(e).children().clone();

	//이름을 지정하여 선택 (class라 복잡해짐)
	$('div[name=div_for_write_answer]').append("<form method = 'post' action='#' onsubmit = 'return false' name = 'form_for_answer_comment'></form>");

	var set_postion_form = $('form[name=form_for_answer_comment]');
	set_postion_form.append(clone_input);

	//익명이여야 진행 
	if(!check_loggin) {
		//자꾸만 뒤로가서 div 따로 부여. 개빡치네 이거
		set_postion_form.append("<div class = 'C_float_left'>닉네임 <input style = 'display:inline-block' type = 'text' name = 'anony_nickname'placeholder = '한글기준 6자이하 '> </div>");
		set_postion_form.append("");
     
		set_postion_form.append("<div class = 'C_float_left'>&nbsp &nbsp비밀번호&nbsp "+
         "<input style = 'display:inline-block' type = 'password' name = 'anony_passwd' placeholder = '띄어쓰기 금지'> <br> </div> ");
      set_postion_form.append("<br>");
		
	}

	
	set_postion_form.append("<input type = 'password' name = 'send_member' value ='"+send_user+"' style = 'display : none;'></input>");

	set_postion_form.append("<div style = 'clear:both'><textarea class = 'C_txtr-W500' name = 'content' id = 'for_check_text'>");
	set_postion_form.append("</textarea>");
	set_postion_form.append("<button class = 'C_btn-H80' type='submit' id='answer_button'>등록</button></div>");
	
	set_postion_form.children('textarea').focus();

	$('#answer_button').click(function(e){

      //삭제 열려있다면 닫아줌 (-- 2번 했음)
      cancel_check_anony_passwd();

		//익명시에 검사 (+내용 규칙 포함)
		if(watch_comment_before_send()) {
			//true로 넘어오면 익명이 아니거나 검사규칙을 통과한것
		} else {
			return false;
		}

		e.preventDefault();
		//alert("동작 확인용");

		var formData = $('form[name=form_for_answer_comment]').serialize();
      formData = formData + "&"+$('#ci_t').attr('name')+"="+$('#ci_t').val();
      formData = formData + "&"+$('#ci_r').attr('name')+"="+$('#ci_r').val();
		//console.log(formData);
		//console.log($('form[name=form_for_answer_comment]').children());

      //답글 중복 등록 방지
      $('form[name=form_for_answer_comment]>#for_check_text').val('');

      var ajax_url = '/'+class_name+'/'+function_name+'/uploadanswercomment';
      $.ajax({
         url: ajax_url,
         dataType: "html",
         processData: true,
         contentType: 'application/x-www-form-urlencoded',
         data: formData,
         type: 'POST',
         success: function(result){	
            var reg = /알림/;
            if(reg.test(result)) {
               alert(result);
               var reg_2 = /댓글/;
               if(reg_2.test(result)) 
                  location.reload();
               else 
                  window.location = '/'+class_name+'/'+function_name+'/list';
            }
               
         	$('div[name=div_for_answer_comment]').remove();
         	//alert("수정 완료!");

         	apply_lately_comment();
         },
         error:function(){
     			alert('잘못된 접근입니다.');
   		}
         	
      });
	});
}
//답글 취소 스크립트
function cancel_tool_for_answer_comment() {
	if($("div[name=div_for_answer_comment]").length > 0) {
		$("div[name=div_for_answer_comment]").remove();
	}
}

//댓글 삭제 스크립트
function delete_comment_JS(e) {

	//확인메세지 취소시 삭제 취소
	if(!confirm_delete()) {
      cancel_delete_confirm(e);
		return false;
	}

	//답글 형식이 열려있다면 닫아줌 (그냥)
	cancel_tool_for_answer_comment();
	
	var formData = $(e).serialize();
   formData = formData + "&"+$('#ci_t').attr('name')+"="+$('#ci_t').val();
   //console.log(formData);
	$.ajax({
      url: $(e).attr('action'),
      dataType: "html",
      processData: true,
      contentType: 'application/x-www-form-urlencoded',
      data: formData,
      type: 'POST',
      success: function(result){
      	//alert("삭제 완료!");
      	//console.log(result);
      	
         if(result.length > 5) {
            alert(result);
            //삭제 안되도 닫음
            cancel_check_anony_passwd();
         }
      	apply_lately_comment();

      },
      error:function(){
  			alert('잘못된 접근입니다.');
		}
   });
}

//댓글입력할때 답변진행중에서 답변완료 되었는지를 체크
function check_change_selected (Data) {

	//qaa 게시판 아니거나 이미 해결되었으면 돌려보냄
	if(!check_qaa_board || check_qaa_solve) {
		return 0;
	}

	var ajax_url = '/'+class_name+'/'+function_name+'/qaasolveinfo';
   $.ajax({
      url: ajax_url,
      dataType: "json",
      processData: true,
      contentType: 'application/x-www-form-urlencoded',
      data: Data,
      type: 'POST',
      success: function(result){	
      	//alert("수정 완료!");
      	//console.log(result);
      	if(result[0]['solve_q'] == 1) {
      		location.reload();  
      	}
      }
   });
}


//최신 댓글 불러와 비교하는 함수
function apply_lately_comment () {


   //formData = formData + "&"+$('#ci_t').attr('name')+"="+$('#ci_t').val();
	var Data = {'global_read_id':global_read_id};
   Data[$('#ci_t').attr('name')] = $('#ci_t').val();
   //console.log(Data);

	//댓글 불러오기 전에 qaa 게시판일 경우 글 정보를 먼저 검사함
	check_change_selected (Data);


	//'last_comment_id':last_comment_id, 
	var ajax_url = '/'+class_name+'/'+function_name+'/latelycomment';
   $.ajax({
      url: ajax_url,
      dataType: "json",
      processData: true,
      contentType: 'application/x-www-form-urlencoded',
      data: Data,
      type: 'POST',
      success: function(result){	
      	//alert("수정 완료!");
      	//console.log(result);

      	var count_store = store_comment_id.length;
      	var count_result = result.length;

      	var i = 0;
      	var j = 0;
      	
      	//배열 저장 함수
      	var store_tempor = new Array();
      	var z = 0;
      	function store_array(arr) {
      		//객체로 전달 받을경우 (result로 받을 경우)
      		if(arr['comment_id'] > 0) {
      			store_tempor[z] = new Array();
      			store_tempor[z][0] = arr['comment_id'];
      			store_tempor[z][1] = arr['depth'];
      			store_tempor[z][2] = arr['group_1_id'];
      		} else {
      			store_tempor[z] = arr;
      		}
      		z++;
      		//console.log(arr['comment_id']);
      		//console.log(arr);
      	}
      	//store_array(result[j]);
      	//store_array(store_comment_id[i]);

      	//개발노트 5 다 확인할것
      	while(1) {
      		//console.log(i);
      		//console.log(j);
      		//console.log(count_store);
      		//console.log(count_result);

      		
      		//이미 저장된 값의 index가 먼저 초과하여 존재하지 않는경우  (오른쪽 값 무조건 추가)
      		if(count_store == i && count_result != j) {
      			if (result[j]['depth'] == 0) {
      				$('#brdRd_Dplec_Cm').before(
	      				//댓글이 추가되는 상황이므로 삭제문을 보여줄 필요가 없음
	      				add_comment (result[j], false, 'c')
						);
      				

      			} else if (result[j]['depth'] == 1) {
      				if($('div[name=comment_group_1_'+result[j]['group_1_id']+']').length > 0) {
      					//그룹이 이미 존재하므로 답글만 추가시킴
      					add_comment_in_g1(result[j]);

	   				} else {

	   					$('#brdRd_Dplec_Cm').before(
	      					//답글인데 그룹이 존재하지 않으므로 이미 삭제된 댓글임 (삭제문 출력후 추가시킴)
	      					add_comment(result[j],true ,'ac')
							);
	   					
	      			}
	      		}
      			
      			store_array(result[j]);
      			j++;
      			continue;
      		}

      		//----------------------------- 새로 고침때 필요
      		//result의 index가 먼저 초과하여 존재하지 않는경우 (왼쪽 값 모두 삭제)
      		if(count_store != i && count_result == j) {

					//맨아래랑 똑같음........... (결국 원리가 같음)ㅋ`
      			$('div[name=comment_id_'+store_comment_id[i][0]+']').remove();

      			//삭제문을 넣지 않으면 아래 문이 제대로 실행되지 않음
      			add_show_delete_info(store_comment_id[i]);
      			i++;
      			//답글 댓글이 모두 삭제되면 그룹 삭제
      			delete_if_dont_exist_div_g1(i, count_store, store_comment_id[i-1], store_comment_id[i]);

      			continue;
      		}
      		//-----------------------------

      		//맨아래 처리시 undefined 처리 하기 싫어서 위로 옮겼음
      		if(count_store == i && count_result == j){
      			break;
      		}

      		if(store_comment_id[i][0] == result[j]['comment_id']) {

      			store_array(store_comment_id[i]);
      			i++;
      			j++;
      			continue;
      		} else if (store_comment_id[i][0] > result[j]['comment_id'] && store_comment_id[i][1] > result[j]['depth'] ) {
      			
      			$('div[name=comment_id_'+store_comment_id[i][0]+']').remove();

      			//답글만 삭제되므로 삭제문을 넣을 필요없음
      			i++;
      			//답글 댓글이 모두 삭제되면 그룹 삭제
      			delete_if_dont_exist_div_g1(i, count_store, store_comment_id[i-1], store_comment_id[i]);

      			continue;

      		} else if (store_comment_id[i][0] < result[j]['comment_id'] && store_comment_id[i][1] < result[j]['depth']) {
      			//g1이 같은지 확인
      			if(store_comment_id[i][2] != result[j]['group_1_id']) {
      				//해당 답글이 존재하는지 확인 
	      			if ($("div[name=comment_id_"+result[j]['comment_id']+"]").length > 0) {
	      				
	      				//해당 답글이 존재하면 비교한 댓글 삭제하고 넘어감
		      			$('div[name=comment_id_'+store_comment_id[i][0]+']').remove();

		      			//삭제문을 넣지 않으면 아래 문이 제대로 실행되지 않음
		      			add_show_delete_info(store_comment_id[i]);
		      		
		      			i++;
		      			
		      			//답글 댓글이 모두 삭제되면 그룹 삭제
							delete_if_dont_exist_div_g1(i, count_store, store_comment_id[i-1], store_comment_id[i]);

	      				continue;

	      			} else {//해당 답글이 존재하지 않으면 답글추가

	      				//그룹은 이미 존재하므로 답글만 추가시킴
							add_comment_in_g1(result[j]);

	      				store_array(result[j]);
	      				j++;
	      				continue;
	      			}

      			} else {
      			//g1이 같으면 실행
   					//해당 div가 존재하는지 확인 
      				if ($("div[name=comment_id_"+result[j]['comment_id']+"]").length > 0) {
      					$("div[name=comment_id_"+store_comment_id[i][0]+"]").remove();
      					
      					//답글이 있는상태에서 댓글만 삭제하는 상황임
      					add_show_delete_info(store_comment_id[i]);
      					
      					i++;
      					continue;

      				} else {
      					$("div[name=comment_id_"+store_comment_id[i][0]+"]").remove();
      					
      					//답글이 없는 상태에서 댓글을 삭제함
      					add_show_delete_info(store_comment_id[i]);

      					//그룹은 이미 존재하므로 답글만 추가시킴
							add_comment_in_g1(result[j]);
      					
	   					store_array(result[j]);
	   					i++;
	   					j++;
	   					continue;
      				}
      			}
      		} else if (store_comment_id[i][0] < result[j]['comment_id'] && store_comment_id[i][1] == result[j]['depth']) {
      			
      			if (store_comment_id[i][2] >result[j]['group_1_id']) {
      				if($('div[name=comment_group_1_'+result[j]['group_1_id']+']').length > 0) {
      					//그룹이 이미 존재하면 답글만 추가시킴
      					add_comment_in_g1(result[j]);
      					
	   				} else {
	   					//답글인데 그룹이 존재하지 않으므로 이미 삭제된 댓글임
	   					//삭제문 출력후 추가시킴

	   					//왼쪽 그룹 기준으로 before 시킴
	   					$('div[name=comment_group_1_'+store_comment_id[i][2]+']').before(
	   						//답글인데 그룹이 존재하지 않으므로 이미 삭제된 댓글임 (삭제문 출력후 추가시킴)
								add_comment(result[j],true,'ac')
							);
	      			}

	      			store_array(result[j]);
	      			j++;
	      			continue;

      			} else if(store_comment_id[i][2] < result[j]['group_1_id']) { 
      				$('div[name=comment_id_'+store_comment_id[i][0]+']').remove();
      	
      				
      				//삭제문을 넣지 않으면 아래 문이 제대로 실행되지 않음
						add_show_delete_info(store_comment_id[i]);
      				i++;
      				//답글 댓글이 모두 삭제되면 그룹 삭제
						delete_if_dont_exist_div_g1(i, count_store, store_comment_id[i-1], store_comment_id[i]);
	      			continue;
      			} else {
      				$('div[name=comment_id_'+store_comment_id[i][0]+']').remove();

   					i++;
   					continue;
      			}
      		} else if (store_comment_id[i][0] < result[j]['comment_id'] && store_comment_id[i][1] > result[j]['depth']) {
      			
      			$('div[name=comment_id_'+store_comment_id[i][0]+']').remove();
      			
      			//답글만 삭제되므로 삭제문을 넣을 필요없음
      			i++;
      			//답글 댓글이 모두 삭제되면 그룹 삭제
      			delete_if_dont_exist_div_g1(i, count_store, store_comment_id[i-1], store_comment_id[i]);
	      		continue;

      		} else if (store_comment_id[i][0] > result[j]['comment_id'] && store_comment_id[i][1] == result[j]['depth']) {

      			
      			$('div[name=comment_id_'+store_comment_id[i][0]+']').remove();

      			//답글만 삭제되므로 삭제문을 넣을 필요없음
      			i++;
      			//답글 댓글이 모두 삭제되면 그룹 삭제
      			delete_if_dont_exist_div_g1(i, count_store, store_comment_id[i-1], store_comment_id[i]);
	      		continue;

      		}

      	} 

      	//alert("잘빠져나옴 ㅇㅇ");
      	store_comment_id = new Array();
      	store_comment_id = store_tempor;
      	//console.log(store_comment_id);

      	if(check_page == 1) {
	      	//게시글 목록 최신화
	      	apply_lately_write ();
      	}

      },
      error:function(){
  			alert('잘못된 접근입니다.');
		}
   });
}
