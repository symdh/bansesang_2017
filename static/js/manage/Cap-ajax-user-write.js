	

//페이지가 1일때 아래문이 실행됨 
//댓글쓴후에 최신 글을 검사
function load_user_write (formData) {
	//console.log(formData);

	var Data = $(formData).serialize(); //post 데이터
	Data[$('#ci_t').attr('name')] = $('#ci_t').val();
	var ajax_url = formData.action;

	$.ajax({
		url: ajax_url,
		dataType: "json",
		processData: true,
		contentType: 'application/x-www-form-urlencoded',
		data: Data,
		type: 'POST',
		success: function(result){	
			//console.log(result[0]['answer_content']);

			//하나라도 존재할때 실행
			if (result.length != 0) {

				//이미 열려있으면 삭제
				if($('#csc_CA_shape_for_user_content').length>0) {
					$('#csc_CA_shape_for_user_content').remove();
				}
				if($('#csc_CA_shape_for_answer').length>0) {
					$('#csc_CA_shape_for_answer').remove();
				}

				//추가시켜줌
				$('#csc_CA_shape_for_list').before(""+
					"<div id = 'csc_CA_shape_for_user_content'>"+
						"<div id = 'csc_CA_appoint_title'>"+
							"제목: "+result[0]['title']+ 
						"</div>"+
						"<div id = 'csc_CA_appoint_nickname'>"+
							"닉네임: "+result[0]['writed_member']+
						"</div>"+
						"<div id = 'csc_CA_appoint_date'>"+
							result[0]['date']+
						"</div>"+
						"<div id = 'csc_CA_appoint_content'>"+
							result[0]['content']+
						"</div>"+
					"</div>"
				);

				if(result[0]['answer_content'] != "") {

					$('#csc_CA_shape_for_user_content').after(""+
						"<div id = 'csc_CA_shape_for_answer'>"+
							"<div id ='csc_CA_shape_for_show_answered'>"+
								"<div id = 'csc_CA_appoint_date_answered'>"+
									"답변자 : "+result[0]['answer_nickname']+"<br>"+  
									"답변일시 : "+result[0]['answer_date']+

								"</div>"+
								"<div id = 'csc_RdT1_appoint_content_answered'>"+
									result[0]['answer_content']+
								"</div>"+
							"</div>"+
						"</div>"
					);
				}
			}
		}
	});
}
