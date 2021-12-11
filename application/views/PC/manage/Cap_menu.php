
<?php //메뉴 추가시 아래와 같은 형식을 유지하면됨 (row로 몇번째인지 판단할것) 
	  //메뉴 이름은 메뉴 표시랑 연관(아래 script)되므로 형식 유지할것
	  //get 변수도 동일하게 진행해야됨
?>
<!-- 고정메뉴 -->
<div id="mngtopMnu_main">
	<div id = "mngtopMnu_wrapper">
		<div id = 'mngtopMnu_div_style_1'>
			<div name = "mngtopMnu_div">
				<div id = 'mngtopMnu_Dstl_Mnu'>
					<ul name = "mngtopMnu_ul">
						
						<li>
							<a name = 'main' href="/manage/cap/main">
								<span>
									메인
								</span>
								
							</a>
						</li>
						<li>
							<a name='report' href="/manage/cap/list/report">
								<span>
								<?php 
									if($data_w_info[2]['function_name'] =='report')
										echo '신고('.$data_w_info[2]['need_answer'].')';
									else
										echo "에러"; 
								?>
								</span>	
							</a>
							
						</li>
						<li>
							<a name='proposal' href="/manage/cap/list/proposal">
								<span>
								<?php 
									if($data_w_info[0]['function_name'] =='proposal')
										echo '건의('.$data_w_info[0]['need_answer'].')';
									else
										echo "에러"; 
								?>
								</span>	
							</a>
							
						</li>
						<li>
							<a name='abug' href="/manage/cap/list/abug">
								<span>
								<?php 
									if($data_w_info[1]['function_name'] =='abug')
										echo '버그('.$data_w_info[1]['need_answer'].')';
									else
										echo "에러"; 
								?>
								</span>	
							</a>
							
						</li>
						<li>
							<a name='amember' href="/manage/cap/list/amember">
								<span>
								<?php 
									if($data_w_info[3]['function_name'] =='amember')
										echo '회원('.$data_w_info[3]['need_answer'].')';
									else
										echo "에러"; 
								?>
								</span>	
							</a>

						
							
						</li>
						<li>
							<a name='etcetera' href="/manage/cap/list/etcetera">
								<span>
								<?php 
									if($data_w_info[4]['function_name'] =='etcetera')
										echo '기타('.$data_w_info[4]['need_answer'].')';
									else
										echo "에러"; 
								?>
								</span>	
							</a>
							
						</li>
					</ul>
				</div>
			</div>
		</div>	
	</div>
</div>

<script>

<?php 
	//function_name이 존재하면 진행
	if(isset($function_name)) {
		echo "function_name='{$function_name}';";
	}
?>
	//alert(function_name);
	if( typeof function_name != 'undefined') {
		$('a[name='+function_name+']').addClass('mngtopMnu_Aappt_SltMnu');
	} 

</script>