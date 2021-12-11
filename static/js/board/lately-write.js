
//페이지가 1일때 아래문이 실행됨 
//댓글쓴후에 최신 글을 검사
function apply_lately_write () {

	var Data = {'lately_read_id':lately_read_id};
	Data[$('#ci_t').attr('name')] = $('#ci_t').val();
	//'last_comment_id':last_comment_id, 
	var ajax_url = '/board/'+function_name+'/latelywrite';

	$.ajax({
		url: ajax_url,
		dataType: "json",
		processData: true,
		contentType: 'application/x-www-form-urlencoded',
		data: Data,
		type: 'POST',
		success: function(result){	

			//하나라도 존재할때 실행
			if (result.length != 0) {
				var lately_num =  result.length;
				//가장 최신 read_id 로 교체 (0이 최신임)
				lately_read_id = result[0]['read_id'];
				//순서대로 가져오므로 밑으로 추가시키기 위한 변수
				var index_td = 0;

				for(var i=0; i < result.length; i++) {
					index_td = i+1;

					//포토 게시판인지 아닌지를 판별
					if( typeof result[0]['photo'] == 'undefined') {

						if( typeof result[0]['solve_q'] != 'undefined') {
							if(result[0]['solve_q'] == 1) {
								var first_td_content = "<td class = 'brdLst-TDstl-answer'>완료</td>";
							} else if ( result[0]['solve_q'] == 0 && result[0]['count_comment'] > 0 ) {
								var first_td_content = "<td class = 'brdLst-TDstl-answer'>진행중</td>";
							} else if (result[0]['solve_q'] == 0 && result[0]['count_comment'] == 0 ) {
								var first_td_content = "<td class = 'brdLst-TDstl-answer'>미답변</td>";
							}
						} else {
							var first_td_content = "<td class = 'brdLst-TDplec-RdNum'>"+result[i]['read_id']+"</td>";
						}

						$('#brdLst_Dplec_MnLst tr:eq('+index_td+')').before(
							"<tr class = 'C_tr_H30'>"+
							first_td_content+
							"<td class = 'brdLst-TDstl-RdTtl'><a href = '/board/"+function_name+"/read/"+result[i]['read_id']+"'>"+result[i]['title']+"</a></td>"+
							"<td class = 'brdLst-TDstl-RdWriter'>"+result[i]['writed_member']+"</td>"+
							"<td class = 'brdLst-TDstl-RdDt'>"+result[i]['date']+"</td>"+
							"<td class = 'brdLst-TDstl-ClkNum'>"+result[i]['click_num']+"</td>"+
							"</tr>"
							);
						$('#brdLst_Dplec_MnLst tr').last().remove();
					} else { //사진게시판일 경우

						if( typeof result[i]['photo_src_2'] == 'undefined') {
							var twice_photo_src = ''; 
						} else {
							var twice_photo_src = result[i]['photo_src_2'];
						}

						//위에랑 아래랑 기본 위치가 다름 (tr은 위에 하나더 있음)
						index_td = index_td -1;
						$('#brdLst_ULplec_GlrMnLst li:eq('+index_td+')').before(
							"<li class = 'brdLst-LIstl-GlrSlt-off'>" +
							"<div class = 'brdLst-Dstl-GlrRdTtl'> <a href = '/board/"+function_name+"/read/"+result[i]['read_id']+"'>"+result[i]['title']+"</a>"+
							"</div>"+
							"<div class = 'brdLst-Dstl-GlrPt'>"+
							"<img class = 'C_img_GlrThn-lft' src='"+result[i]['photo_src_1']+"' />"+
							"<img class = 'C_img_GlrThn-rt' src='"+twice_photo_src+"' />"+
							"</div> </a>"+
							"<div class = 'brdLst-Dstl-GlrCvc'>"+
							"<div class = 'brdLst-TDstl-GlrRdWriter'>"+result[i]['writed_member']+"</div>"+
							"<div class = 'brdLst-TDstl-GlrRdDt'>"+result[i]['date']+"</div>"+
							"<div class = 'brdLst-TDstl-GlrClkNum'> 조회수 :"+result[i]['click_num']+"</div>"+
							"</div>"+
							"</li>"
							);
						$('#brdLst_ULplec_GlrMnLst li').last().remove();
					}
				}
			}
		}
	});
}