

function find_form_check(){

	//이메일 검사
	// if( $('input[name=user_email_id]').length > 0 ) {
	// 	if(!watch_email ('user_email_id', 0, '', 5,  0)) {
	// 		return false;
	// 	}
	// }

	//id검사
	if( $('input[name=user_email_id]').length > 0 ) {
		if(!watch_id ('user_email_id', 0, '', 5,  0)) {
			return false;
		}
	}


	//닉네임 체크
	if( $('input[name=user_nickname]').length > 0 ) {
		if(!watch_nickname ('user_nickname',0, '', 5, 0)) {
			return false;
		}	
	}

	//이름검사
	if(!watch_name ('user_name', '', 5, 0) ) {
		return false;
	}

	//구글 스팸 감지기
	if (typeof(grecaptcha) != 'undefined') {
      	if (grecaptcha.getResponse() == "") {
         	alert("스팸방지 창에 체크 해주세요.");
      	  	return false;
      	}
  	} else {
     	return false;
   	}


	//전화번호 검사
	// if(!watch_phone ('user_phone', '', 5,  0) ) {
	// 	return false;
	// }

	//중복 클릭 방지
	$('input[name=submit_input]').attr('type','button');
	$('input[name=submit_input]').attr('value','제출중');

	if( $('input[name=user_email_id]').length > 0 ) {
		//pwd찾기 
		var formData = $('form[name=findpwd]').serialize();

	   $.ajax({
	      url: '/member/tryfindpwd',
	      dataType: "json",
	      processData: true,
	      contentType: 'application/x-www-form-urlencoded',
	      data: formData,
	      type: 'POST',
	      success: function(result){
	          console.log(result);
	      		$('td[name=ajax_result]').html(" ");
	          $('td[name=ajax_result]').append("<br> <span style = 'color:red; font-size:15px;'>"+ result+"</span>");
	          $('td[name=ajax_result]').append("<br> <br> ");

	          $('input[name=submit_input]').attr('type','submit');
				$('input[name=submit_input]').attr('value','제출');

	      },
	      error:function(){
	     			alert('결과가 없습니다.');
	     			$('input[name=submit_input]').attr('type','submit');
					$('input[name=submit_input]').attr('value','제출');
	   		}
	   });


	} else {
		//id 찾기
		var formData = $('form[name=findid]').serialize();

	   $.ajax({
	      url: '/member/tryfindid',
	      dataType: "json",
	      processData: true,
	      contentType: 'application/x-www-form-urlencoded',
	      data: formData,
	      type: 'POST',
	      success: function(result){
	          
	      		$('td[name=ajax_result]').html(" ");

	          for(var i = 0; i < result.length ; i++) {
	          	if(i==0)
	          		$('td[name=ajax_result]').append("<br> <span style = 'font-size:17px;'> 제출 결과입니다. </span>");

	          	$('td[name=ajax_result]').append("<br> "+result[i]['email']+" ");
	          } 

	          $('td[name=ajax_result]').append("<br> <br> ");

	          $('input[name=submit_input]').attr('type','submit');
				$('input[name=submit_input]').attr('value','제출');
	      },
	      error:function(){
	     			alert('결과가 없습니다.');
	     			$('input[name=submit_input]').attr('type','submit');
					$('input[name=submit_input]').attr('value','제출');
	   		}
	   });
	}

}