//모바일 클릭이벤트 
$(document).ready(function(){

	
	$('#topMnu_Dappt_1').click(function(){
		$('#topMnu_Dstl_Mnu').toggle(0);
	});
	
	var check_open = 0; 	//오픈되어 있으면 1을 저장
	var index_li = -1; //초기값 에러 방지
	//console.log($('ul .free_C_div_for_purpose').length);
	//console.log($('.free_C_div_for_purpose').length);
	//console.log($('.free_C_div_for_purpose').index($(this)));

	$('.free_C_div_for_purpose').click(function(){
		//console.log($('.free_C_div_for_purpose').index($(this)));
		//console.log(index_li);

		//오픈이 되어있으면 기존 ul을 숨김 (변수는 이전꺼 저장되어있음)
		if (check_open == 1 ) {
			$('ul ul:eq('+index_li+')').hide(400);
		}
		
		//만약에 누른 값이 이전꺼랑 같을 경우와 아닐경우를 나눔
		if(index_li ==  $('.free_C_div_for_purpose').index($(this))){
			if (check_open == 0){
				$('ul ul:eq('+index_li+')').show(400);
				check_open = 1;
			} else {
				//위에서 이미 닫았으므로 또 닫을 필요 없음
				check_open = 0;
			}
		} else {
			//이미 닫았으므로 보여주기만 하면됨
			index_li =$('.free_C_div_for_purpose').index($(this));
			$('ul ul:eq('+index_li+')').show(400);
			check_open = 1;

		}
	});

	$(document).on("click",function(e) { 
		if($(e.target).parents("#topMnu_wrapper").size() == 0) { 
			$('ul ul:eq('+index_li+')').hide(800);	
			$('#topMnu_Dstl_Mnu').hide(0);
			check_open = 0;
		}
		
	}); 

	//스크롤시 이벤트
	$(document).on('scroll', function() {
		//스크롤 할때 메뉴판이 열려있으면 닫음
		$('ul ul:eq('+index_li+')').hide(800);	
		$('#topMnu_Dstl_Mnu').hide(800);
		check_open = 0;
	});

});


