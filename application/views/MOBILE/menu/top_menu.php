
    <!--    .menu-wrap is a wrapper for all the menu structure for the easier traversing-->
    <div id='topMnu_main'>
    	<!--jquery 연관-->
       <div id='topMnu_wrapper' >
        	<div id = 'topMnu_DRsv_1'>
        		<!--jquery 연관-->
	        	<div id = 'topMnu_Dappt_1'>
	        		메뉴
	        	</div>

	        	<!--jquery 연관-->
	        	<div id = 'topMnu_Dstl_Mnu'>
		            <ul>
		            		<li>
		            			<a href="/main">
		            				<div class = 'free_C_div_for_purpose'>
		            					홈으로
		            					<ul> </ul>
		            				</div>
		            			</a>
		            		</li>

		            		 <li>
		                		<div class = 'free_C_div_for_purpose'>
		                			고객센터
		                		</div>
			                	<ul>
			                		<li><a href="/member/userpage">내 정보</a></li>
									<li><a href="#" onclick="return window_open('/csc/notice');">공지사항</a></li>
									<li><a href="#" onclick="return window_open('/csc/faq');">FAQ</a></li>
									<li><a href="#" onclick="return window_open('/csc/write');">1:1질문</a></li>
									<li><a href="#" onclick="return window_open('/csc/confirmanswer');">문의확인</a></li>

			                	</ul>
		                </li>

		                <li>
		                		<div class = 'free_C_div_for_purpose'>
		                			의학정보
		                		</div>
			                	<ul>
			                		<li><a href="/minfo/medic">의학정보</a></li>

			                	</ul>
		                </li>

		                <li>
		                		<div class = 'free_C_div_for_purpose'>
		                		 	커뮤니티
		                		</div>
			                	<ul>
			                		<li><a href="/board/free">자유게시판</a></li>
			                		<li><a href="/board/photo">사진게시판</a></li>
			                		<li><a href="/board/qaa">질답게시판</a></li>
			                	</ul>
		                </li>

		              <!--   <li>
		                		<div class = 'free_C_div_for_purpose'>
		                			동물소식
		                		</div>
			                	<ul>
			                		<li><a href="#">준비중입니다</a></li>
			                	</ul>
		                </li> -->

		            </ul>
		          </div>
		       </div>

		   <div id = "topMnu_Dstl_UsrMnu">    
<?php
			$check_loggin = $this->session->userdata('logged_in');
			if($check_loggin) {
				$nickname = $this->session->userdata('nickname');
				//echo $nickname.'님 환영합니다.';
				//알림 내글 포인트
				echo "  
				  
				  <a style = 'color:#fff' href = '/member/trylogout'> 로그아웃 </a>";
				  // <a style = 'color:#fff; margin-left:10px;' href='#'' onclick=\"return window_open('/csc/write?proposal=1');\">건의</a>
			}else {
				echo "
				<a href = '/member/login'>
					<div class = 'free_C_div_style_1'>
						로그인
					</div>
				</a>

				<a href = '/member/join'>
					<div class = 'free_C_div_style_1'>
						회원가입
						
					</div>
				</a>
				";
			}

?>
			</div>

<!-- 			<div id = "topMnu_Dstl_Evt-1">
				E V E N T
			</div> -->

			<div id = "topMnu_Dstl_Srch">
				<img src ='/static/img/search-Ms.jpg'>
			</div>
       </div>
   </div>

<script>
   //크기 조종 
	// (function() { 
	// 	$('#topMnu_Dstl_Srch').css('width',screen.width-300+'px');
	// }) ()

	// //리사이즈 이벤트
	// $(document).ready(function(){
	// 	var check_width = $(window).width();
	// 	$(window).resize(function() {
	// 		//width가 다를때만 실행 (주소창 땜에 버그생김)
	// 		if (check_width != $(window).width()) {
				
	// 			$('#topMnu_Dstl_Srch').css('width',screen.width-300+'px');
	// 			check_width = $(window).width();
	// 		}
	// 	});
	// });	
</script>



<script> 
	function window_open(url) {
		//open(url);
		open(url,'건의', 'status=yes, width=1000, height=600, scrollbars=1');
		return false;
	}
</script>

