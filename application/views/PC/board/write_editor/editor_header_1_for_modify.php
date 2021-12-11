<!--======================================
========================================== -->


<div class="body">
	<!-- 에디터 시작 -->
	<!--
		@decsription
		등록하기 위한 Form으로 상황에 맞게 수정하여 사용한다. Form 이름은 에디터를 생성할 때 설정값으로 설정한다.
	-->
<?php
	//print_r($function_name);
	// <!--게시판 주소 바뀌면 수정해야함 + onsubmit 넣어줬음-->
	if($function_name != 'csc' && !preg_match( '/minfo/',$function_name) ){
		echo "<form name='tx_editor_form' id='tx_editor_form' action='/board/{$function_name}/modifywrite' method='post' accept-charset='utf-8'>";
	} else { 
		echo "<form name='tx_editor_form' id='tx_editor_form' action='/{$function_name}/modifywrite' method='post' accept-charset='utf-8'>";
	}

?>

<?php
		
		if($function_name == 'csc') {
	            	echo "<br>";
	               echo " 질문유형: <select name = 'write_type' id = 'brdWrt_Dstl_SltTp'>
	               <option value = '0'> 선택하세요 </option>
	            	<option value = 'proposal'> 건의 </option>
	            	<option value = 'abug'> 버그발견 </option>
	            	<option value = 'amember'>회원 문의 </option>
	            	<option value = 'etcetera'>기타 문의 </option>
	            	
		            </select>
		            <br>
	            ";
		} else if(preg_match( '/minfo/',$function_name) ) {
	            	echo "<br>";
	               echo " 글 유형: <select name = 'write_type' id = 'brdWrt_Dstl_SltTp'>
	               <option value = '0'> 선택하세요 </option>
	            	<option value = 'symptom'> 증상 정보 </option>
	            	<option value = 'medical'> 의학 정보 </option>
	            	<option value = 'hospital'>병원 정보 </option>
	            	<option value = 'medicine'> 약 정보 </option>
	            	
		            </select>
		            <br>
	            ";
		}
?>

<script>

<?php 
	if(isset($data_w[0]['divide_type']))
		echo "$('select[name=write_type]').val('{$data_w[0]['divide_type']}');";
?>

</script>

		<!-- post 추가하려면 여기에다가 써야됨 form 옮기면 에러남 -->
		<style>
			.for_pwd {
				display:none;
			}
		</style>
	<?PHP
		//print_r($data_w);
		echo "<input name = 'id_check' type = 'password' class = 'for_pwd' value = '{$data_w[0]['id_check']}'></input>";
		echo "<input name = 'read_id' type = 'password' class = 'for_pwd'  value = '{$data_w[0]['read_id']}'></input>";

		//익명일 경우 패스워드 값 전송을 위함
		$check_loggin = $this->session->userdata('logged_in');
		if(!$check_loggin) {  
			echo "<input name = 'anony_passwd' type = 'password' class = 'for_pwd'  value = '{$data_w[0]['passwd']}'></input>";
			echo "<input name = 'anony_nickname' type = 'password' class = 'for_pwd'  value = '{$data_w[0]['writed_member']}'></input>";
		}

		$data_w_read[0]['token_name'] = $this->security->get_csrf_token_name();
		$data_w_read[0]['hash'] = $this->security->get_csrf_hash();
		echo 	"<input type='hidden' name='".$data_w_read[0]['token_name']."' value='".$data_w_read[0]['hash']."' />";
	

	?>

		<div>
			<?php echo "<input name = 'title' id = 'brdWrt_Dplec_WrtTtl' placeholder = '제목을 입력하세요' value = '{$data_w[0]['title']}'> </input>"; ?>
		</div>
	
		<!-- 에디터 컨테이너 시작 -->
		<div id="tx_trex_container" class="tx-editor-container">
			<!-- 사이드바 -->
			<div id="tx_sidebar" class="tx-sidebar">
				<div class="tx-sidebar-boundary">
					<!-- 사이드바 / 첨부 -->
					<ul class="tx-bar tx-bar-left tx-nav-attach">
						<!-- 이미지 첨부 버튼 시작 -->
						<!--
							@decsription
							<li></li> 단위로 위치를 이동할 수 있다.
						-->
						<li class="tx-list">
							<div unselectable="on" id="tx_image" class="tx-image tx-btn-trans">
								<a href="javascript:;" title="사진" class="tx-text">사진</a>
							</div>
						</li>
						<!-- 이미지 첨부 버튼 끝 -->
						<li class="tx-list">
							<div unselectable="on" id="tx_file" class="tx-file tx-btn-trans">
								<a href="javascript:;" title="파일" class="tx-text">파일</a>
							</div>
						</li>
						<li class="tx-list">
							<div unselectable="on" id="tx_media" class="tx-media tx-btn-trans">
								<a href="javascript:;" title="외부컨텐츠" class="tx-text">외부컨텐츠</a>
							</div>
						</li>
						<li class="tx-list tx-list-extra">
							<div unselectable="on" class="tx-btn-nlrbg tx-extra">
								<a href="javascript:;" class="tx-icon" title="버튼 더보기">버튼 더보기</a>
							</div>
							<ul class="tx-extra-menu tx-menu" style="left:-48px;" unselectable="on">
								<!--
									@decsription
									일부 버튼들을 빼서 레이어로 숨기는 기능을 원할 경우 이 곳으로 이동시킬 수 있다.
								-->
							</ul>
						</li>
					</ul>
					<!-- 사이드바 / 우측영역 -->
					<ul class="tx-bar tx-bar-right">
						<li class="tx-list">
							<div unselectable="on" class="tx-btn-lrbg tx-fullscreen" id="tx_fullscreen">
								<a href="javascript:;" class="tx-icon" title="넓게쓰기 (Ctrl+M)">넓게쓰기</a>
							</div>
						</li>
					</ul>
					<ul class="tx-bar tx-bar-right tx-nav-opt">
						<li class="tx-list">
							<div unselectable="on" class="tx-switchtoggle" id="tx_switchertoggle">
								<a href="javascript:;" title="에디터 타입">에디터</a>
							</div>
						</li>
					</ul>
				</div>
			</div>

			