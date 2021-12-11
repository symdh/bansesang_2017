// 각종 글 form, function 적용 함수들
// 패스워드 입력부 구성
// 신고처리시스템
//////////////////////////////////////

//익명시 패스워드 입력부를 불러옴
function insert_anony_passwd(e) {
	//개발노트 8-1 확인

	//이미 열려있으면 닫아줌
	if($('#brdRd_Dplec_RdDlt').length > 0 ) {
		cancel_check_anony_passwd();
	}
	//console.log(e);

	//신고부분 열려있으면 닫아줌
	if($('#brdRd_Dplec_RpRs').length > 0 ) {
		$('#brdRd_Dplec_RpRs').remove();
	}
	if($('form[name=report_form]').length > 0 ) {
		$('form[name=report_form]').attr('name','0');
	}
	if($('#brdRd_Dplec_Rp').length > 0 ) {
		cancel_report();
	}

	//insert 한 부분의 상위 객체를 구분하여 button인지 a인지 확인
	if($(e).parent('#brdRd_plecRdInfo-rt-dn').length > 0) {
		//글 수정인지 삭제인지 검사
		var button_text = $(e).children('button').text();	
		if (~button_text.indexOf('수정')) {
	  		$(e).children('button').html('수정 취소');
	  		var text_complete = '수정 완료'; //아래 넣을거
		} else if (~button_text.indexOf('삭제')) {
			$(e).children('button').html('삭제 취소');
			var text_complete = '삭제 완료';	//아래 넣을거
		} 

		$(e).children('button').attr('onclick','return cancel_check_anony_passwd(this);');

		//삭제일때만 확인메세지 출력
		if (~button_text.indexOf('삭제')) { 
			//무조건 상위 submit 기준이라 form 이벤트 속성 바꿔줌
			$(e).attr('onsubmit','return confirm_delete();');
		} else {
			$(e).attr('onsubmit','return return_true();');
		}

		$(e).append("<div id = 'brdRd_Dplec_RdDlt'>비밀번호 입력 <br>"+
	 	"<input type = 'password' name = 'anony_passwd' class = 'C_style_input_passwd_show' placeholder = '띄어쓰기 금지' id = 'brdRd_Iappt_Dlt'/> "+
	 	"<br><button style='width:100px; height:30px;' >"+text_complete+"</button>" +
	 	 "</div>");

	} else {
		var button_text = $(e).children('a').text();
		if (~button_text.indexOf('삭제')) {
			$(e).children('a').html('삭제 취소');	
			var text_complete = '삭제 완료';
		} else if (~button_text.indexOf('채택')) {
			$(e).children('a').html('채택 취소');	
			var text_complete = '채택 완료';
		}		

		//무조건 상위 submit 기준이라 form 이벤트 속성 바꿔줌
		$(e).attr('onclick','return cancel_check_anony_passwd(this);');

		//삭제일때만 확인메세지 출력
		if (~button_text.indexOf('삭제')) { 
			//무조건 상위 submit 기준이라 form 이벤트 속성 바꿔줌
			$(e).attr('onsubmit','return confirm_delete();');
			var check_progress_confirm = 1;
		} else {
			$(e).attr('onsubmit','return return_true();');
			var check_progress_confirm = 0;
		}

		function progress_confirm_func(check_progress_confirm) {
			if(check_progress_confirm) {
				return "<br><button style='width:100px; height:30px;' onclick = \"submit_check_anony_passwd(this);\" >";
			} else {
				return "<br><button style='width:100px; height:30px;' onclick = \"submit();\" >";
			}
		}

		//onclick 빡치게 안들어 가져서 따로 해서 넣어줬음
		$(e).append("<div id = 'brdRd_Dplec_RdDlt' onclick ='return false;'>비밀번호 입력 <br>"+
	 	"<input type = 'password' name = 'anony_passwd' class = 'C_style_input_passwd_show' placeholder = '띄어쓰기 금지' id = 'brdRd_Iappt_Dlt'/> "+
	 	 progress_confirm_func(check_progress_confirm)+text_complete+"</button>" +
	 	
	 	 "</div>");
	}

	//왜이지 모르게 이게 잘 적용이 안되서 글삭제일 경우에만 top 설정
	var e_height = $(e).height();
	$('#brdRd_plecRdInfo-rt-dn #brdRd_Dplec_RdDlt').css('margin-top', e_height+'px');

	var e_width = $(e).width();
	$('#brdRd_Dplec_RdDlt').css('margin-left', e_width-110+'px');



	$('#brdRd_Iappt_Dlt').focus();
	return false;

}

//order_comment의 삭제 결과 부분 + 답글 + 댓글 부분에 들어가 있음
//취소 누르면 닫아줌
function cancel_check_anony_passwd() {

	//열려있는게 button인지 a인지 상위 객체를 통해 파악
	if($('#brdRd_Dplec_RdDlt').parent().parent('#brdRd_plecRdInfo-rt-dn').length > 0) {
		var stand_event = $('#brdRd_Dplec_RdDlt').parent().children('button');

		//글 수정인지 삭제인지 검사
		var button_text = stand_event.text();
		if (~button_text.indexOf('수정')) {
	  		var text_complete = '글 수정';
		} else {
			var text_complete = '글 삭제';
		}

		stand_event.text(text_complete);
		stand_event.attr('onclick', 'return true;');

		//글 삭제의 경우 무조건 상위 submit 기준이라 바꿔줌
		stand_event.parent().attr('onsubmit','return insert_anony_passwd(this);');
	} else {
		var stand_event = $('#brdRd_Dplec_RdDlt').parent().children('a');
		
		var button_text = stand_event.text();
		if (~button_text.indexOf('삭제')) {
	  		var text_complete = '삭제';
		} else if (~button_text.indexOf('채택')) {
			var text_complete = '답변채택';
		}

		stand_event.text(text_complete);

		stand_event.attr('onclick', 'return true;');
		stand_event.parent().attr('onclick','return insert_anony_passwd(this);');
	}

	$('#brdRd_Dplec_RdDlt').remove();

	return false;
}

//제출 버튼 클릭시 실행됨
function submit_check_anony_passwd(e) {
	$(e).parent().parent().attr('onclick', 'delete_comment_JS(this)');
}

//order_comment의 삭제 확인메세지 부분에 들어가 있음
function cancel_delete_confirm(e) {
	$(e).attr('onclick', 'return cancel_check_anony_passwd(this);');
}

//글 or 댓글 삭제 확인 한번 더 나오게함
function confirm_delete() {
      msg = "삭제시 복구할 수 없습니다. 정말로 삭제 하시겠습니까?";
      if (confirm(msg)!=0) {

      		if ($('#myDiv').length > 0 ) { 
				var passwd_1 = $('#brdRd_Iappt_Dlt');
				
				var check_space = passwd_1.val().match(' ');
				if (check_space != null){
					alert("비밀번호에 빈칸(스페이스)는 사용할 수 없습니다.");
					return false;
				}
					//입력 된것 검사
				if(passwd_1.val()==""){ 
					alert("비밀번호를 입력하세요");
					return false;
				} else if( passwd_1.val().length < 3 ) {
					alert("비밀번호는 3자 이상입니다.");
					return false;
				} else if( passwd_1.val().length > 20 ) {
					alert("비밀번호는 20자 이하만 써주세요");
					return false;
				} 
			}

			return true;
      } else {
            return false;
		}
}

//빡치게 onsubmit 이상해서 함수로 호출함
function return_true() {

	var passwd_1 = $('#brdRd_Iappt_Dlt');
	
	var check_space = passwd_1.val().match(' ');
	if (check_space != null){
		alert("비밀번호에 빈칸(스페이스)는 사용할 수 없습니다.");
		return false;
	}
		//입력 된것 검사
	if(passwd_1.val()==""){ 
		alert("비밀번호를 입력하세요");
		return false;
	} else if( passwd_1.val().length < 3 ) {
		alert("비밀번호는 3자 이상입니다.");
		return false;
	} else if( passwd_1.val().length > 20 ) {
		alert("비밀번호는 20자 이하만 써주세요");
		return false;
	} 

	return true;
}

//회원만 가능한 기능임을 알림
function alert_lemit() {
    alert('회원만 가능한 기능입니다.');
}


//신고 처리 시스템
function submit_report(e) {
	
	var formData = $('form[name=report_form]').serialize();
   formData = formData + "&"+$('#ci_t').attr('name')+"="+$('#ci_t').val();
   var reg = /comment_id/;
   if(reg.test(formData)) {
      var reg_2 = /ci_c/;
      if(!reg_2.test(formData)) {
         var temper_comment_id = $('form[name=report_form]').children("input[name=comment_id]").val();
         //console.log(temper_comment_id);
         formData = formData + "&"+$('#ci_c_'+temper_comment_id).attr('name')+"="+$('#ci_c_'+temper_comment_id).val();
      }
   } else 
      formData = formData + "&"+$('#ci_r').attr('name')+"="+$('#ci_r').val();
   //console.log(formData);


	var ajax_url = '/csc/userreport';
	$.ajax({
      url: ajax_url,
      dataType: "html",
      processData: true,
      contentType: 'application/x-www-form-urlencoded',
      data: formData,
      type: 'POST',
      success: function(result){
         //console.log(result);
      	$('form[name=report_form]').append("<div id = 'brdRd_Dplec_RpRs'>"+result+
	 	   "</div>");

      	if($('form[name=report_form]').parent('#brdRd_plecRdInfo-rt-dn').length > 0) {
			var e_height = $('form[name=report_form]').height();
			$('#brdRd_Dplec_RpRs').css('margin-top', e_height+'px');

		} else {
			var e_width = $(e).width();
			$('#brdRd_Dplec_RpRs').css('margin-left', e_width-125+'px');
		}
     
      },
      error:function(){
      	
      	$('form[name=report_form]').append("<div id = 'brdRd_Dplec_RpRs'>에러발생! 문의바람."+
	 	 "</div>");

		if($('form[name=report_form]').parent('#brdRd_plecRdInfo-rt-dn').length > 0) {
			var e_height = $('form[name=report_form]').height();
			$('#brdRd_Dplec_RpRs').css('margin-top', e_height/2+'px');

		} else {
			var e_width = $(e).width();
			$('#brdRd_Dplec_RpRs').css('margin-left', e_width-125+'px');
		}
      }
   });

      

}


//정말 신고할지 물어봄
function confirm_report(e) {
	//insert_anony_passwd 에서 수정한 내용
	//삭제 부분 열려있으면 닫아줌
	if($('#brdRd_Dplec_RdDlt').length > 0 ) {
		cancel_check_anony_passwd();
	}

	//신고 완료 문구 열려있으면 닫아줌
	if($('#brdRd_Dplec_RpRs').length > 0 ) {
		$('#brdRd_Dplec_RpRs').remove();
	}

	//개발노트: 이벤트 전파 참고
	//이름이 이미 존재하면 변경해줌
	if($('form[name=report_form]').length > 0 ) {
		$('form[name=report_form]').attr('name','0');
	}

	//이미 열려있으면 닫아줌
	if($('#brdRd_Dplec_Rp').length > 0 ) {
		cancel_report();
	}


	//ajax전송을 위해 이름을 넣어줌
	$(e).attr('name','report_form');

	//insert 한 부분의 상위 객체를 구분하여 button인지 a인지 확인
	if($(e).parent('#brdRd_plecRdInfo-rt-dn').length > 0) {
		//글 수정인지 삭제인지 검사
		var button_text = $(e).children('button').text();	
		if (~button_text.indexOf('신고')) {
	  		$(e).children('button').html('신고 취소');
	  		var text_complete_1 = '예';
			var text_complete_2 = '아니요';
		} 

		$(e).attr('onclick','return cancel_report(this);');


		//onclick 빡치게 안들어 가져서 따로 해서 넣어줬음
		$(e).append("<div id = 'brdRd_Dplec_Rp'>정말 신고하시겠습니까?"+
	 	"<br><button class = 'C_btn-H30' type = 'button' onclick ='return submit_report(this);'>"+text_complete_1+"</button>" +
	 	"<button class = 'C_btn-H30'>"+text_complete_2+"</button>" +
	 	 "</div>");

		var e_height = $(e).height();
		$('#brdRd_plecRdInfo-rt-dn #brdRd_Dplec_Rp').css('margin-top', e_height+'px');

		//var e_width = $(e).width();
		//$('#brdRd_Dplec_Rp').css('margin-left', e_width-165+'px');

	} else {

		//내용 파악하여 적절하게 바꿔줌
		var button_text = $(e).children('a').text();
		if (~button_text.indexOf('신고')) {
			$(e).children('a').html('신고 취소');	
			var text_complete_1 = '예';
			var text_complete_2 = '아니요';
		} 

		//이벤트 바꿔줌
		$(e).attr('onclick','return cancel_report(this);');

		//onclick 빡치게 안들어 가져서 따로 해서 넣어줬음
		$(e).append("<div id = 'brdRd_Dplec_Rp'>정말 신고하시겠습니까?"+
	 	"<br><button class = 'C_btn-H30' type = 'button' onclick ='return submit_report(this);'>"+text_complete_1+"</button>" +
	 	"<button class = 'C_btn-H30'>"+text_complete_2+"</button>" +
	 	 "</div>");

		var e_height = $(e).height();
		$('#brdRd_plecRdInfo-rt-dn #brdRd_Dplec_Rp').css('margin-top', e_height+'px');

		var e_width = $(e).width();
		$('#brdRd_Dplec_Rp').css('margin-left', e_width-165+'px');

	}

	

	return false;
}

//신고 취소
function cancel_report() {
	//cancel_check_anony_passwd 에서 수정
	//열려있는게 button인지 a인지 상위 객체를 통해 파악
	if($('#brdRd_Dplec_Rp').parent().parent('#brdRd_plecRdInfo-rt-dn').length > 0) {
		var stand_event = $('#brdRd_Dplec_Rp').parent().children('button');

		//글 수정인지 삭제인지 검사
		var button_text = stand_event.text();
		if (~button_text.indexOf('신고')) {
	  		var text_complete = '글 신고';
		} 

		stand_event.text(text_complete);
		stand_event.attr('onclick', 'return true;');
		stand_event.parent().attr('onclick','return confirm_report(this);');

		$('#brdRd_Dplec_Rp').remove();
	} else {


		var stand_event = $('#brdRd_Dplec_Rp').parent().children('a');
		
		var button_text = stand_event.text();
		if (~button_text.indexOf('신고')) {
	  		var text_complete = '신고 |';
		} 

		stand_event.text(text_complete);
		stand_event.attr('onclick', 'return true;');
		stand_event.parent().attr('onclick','return confirm_report(this);');
		
		$('#brdRd_Dplec_Rp').remove();
	}
	return false;
}

//a용 form 전송 (ex.회원의 경우 답변채택)
function submit_form(e) {
	//console.log(e);
	if (confirm("채택 하시겠습니까? ")){
	  $(e).parent().submit();
	}else{
	  return false;
	}
}
