
<!-- 고정메뉴 -->
<div id="topMnu_main">
	<div id = "topMnu_wrapper">
		<div id = 'topMnu_DRsv_1'>
				<a href="/main">
			<div id = 'topMnu_Dstl_LnkHome'>
			
			</div>
				</a>
			<div id = 'topMnu_Dappt_1' name = "top_Mn_div">
				<div id = 'topMnu_Dstl_Mnu'>
					<ul name = "top_Mn_ul">

						
						<li>
							<a href="#" onclick="return window_open('/csc/redirect');">고객센터</a>
							
							<ul>
								<!-- <li><a href="/member/userpage">마이페이지</a></li> -->
								<li><a href="#" onclick="return window_open('/csc/notice');">공지사항</a></li>
								<li><a href="#" onclick="return window_open('/csc/faq');">FAQ</a></li>
								<li><a href="#" onclick="return window_open('/csc/write');">1:1질문</a></li>
								<li><a href="#" onclick="return window_open('/csc/confirmanswer');">문의확인</a></li>
							</ul>
						</li>


						<li>
							<div>
<?php

			if($this->session->userdata('logged_in')) {
						echo "<a href='/member/userpage'>내 정보</a>";
			} else {
						echo	"<a href='/member/join'>회원가입</a>";
			}
?>
									</div>
							
						</li>


						<li><div><a href="/board/free">이동</a></div>
							<ul>
								<!-- <li><a href="#">뉴스</a></li> -->
								<!-- <li><a href="/board/free">커뮤니티</a></li> -->
								<li><a href="/minfo/medic">의학정보</a></li>
								<!-- <li><a href="/board/qaa">분양</a></li> -->
		                		<li><a href="/board/free">자유게시판</a></li>
		                		<li><a href="/board/photo">사진게시판</a></li>
		                		<li><a href="/board/qaa">질답게시판</a></li>

							</ul>
						</li>

						<!-- <li><div><a href="/board/free">커뮤니티</a></div>
							<ul> -->
								<!-- <li><a href="#">웹툰</a></li> -->
								<!-- <li><a href="/board/free">자유게시판</a></li>
								<li><a href="/board/photo">사진게시판</a></li>
								<li><a href="/board/qaa">질답게시판</a></li>
							</ul>
						</li> -->
						
					</ul>
				</div>
			</div>

			<div id = "topMnu_Dstl_UsrMnu">
<?php

			if($this->session->userdata('logged_in')) {
				$nickname = $this->session->userdata('nickname');
				//echo $nickname.'님 환영합니다.';
				//알림 내글 포인트
				echo "  
				  
				  <a style = 'color:black;' href = '/member/trylogout'> 로그아웃 </a>
				  ";
			}else {
				echo "
				<a href = '/member/login'>
					<div class = 'free_C_div_style_1'>
						로그인
					</div>
				</a>

				";
			}

?>
			</div>

<!-- 			<div id = "topMnu_Dstl_Evt-1">
				E V E N T
			</div>

			<div id = "topMnu_Dstl_Evt-2"> -->
				 <!-- <img src = '/static/img/logo.jpg'> -->
			<!-- </div> -->

		</div>	
	</div>
</div>

<script>
	function window_open(url) {
		//open(url);
		open(url,'건의', 'status=yes, width=1000, height=600, scrollbars=1');
		return false;
	}
</script>