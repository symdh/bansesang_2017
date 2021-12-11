<div id = 'mInfo_main'>
	
	<?php include "./static/include/view/board/MOBILE_sub_list.php"; ?>

	<div id = 'mInfo_wapper'>
	<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />


                <?php  
                //개발페이지 6-2 참조

function pasing_sys ($data_w_info, $write_type) {

	echo "<form style = 'height:0px;' style='width:0px; height:0px;' name = 'get_list' onsubmit = 'return get_list_ajax(this);' action ='/minfo/medical' method = 'post'>
				
				<input type='hidden' name='page' value='' />
				<input type='hidden' name='write_type' value='{$write_type}' />
			";


	//data_w_info : 바로 사용가능하도록 선언할것. ex) $data_w_info[0]
	
	//주소 호출시 무조건 첫페이지 출력
	$_POST['page'] = 1;

        $recent_group = ceil($_POST['page']/$data_w_info['setUp_groupNum']);
        //시작 page 구함
        $start_pageNum = ($recent_group-1)*$data_w_info['setUp_groupNum'] + 1;

        //현재그룹이 마지막 그룹이면
        if($recent_group == $data_w_info['count_group']) {
            //마지막 그룹의 페이지수를 구함
            $end_group_count_page = $data_w_info['setUp_groupNum']+ ($data_w_info['count_page']- $data_w_info['count_group']*$data_w_info['setUp_groupNum']);
            //반복계수 z를 초기화
            $z = $end_group_count_page;
        } else {
            //아니면 그룹의 내부수로 초기화
            $z = $data_w_info['setUp_groupNum'];
        }

        //처음, 이전페이지 출력
        if ($recent_group > 1) {
            //처음
        	//page = 1 처음 페이지
            echo "<a href = '#' onclick = \"$('input[name=page]').val('1'); $(this).parent('form').submit();\">
            		<div id ='brdLst_SPstl_PgLnk-Fst'>
            			처음
            		</div>
            		</a>";
            //이전
            $before_group_page = $start_pageNum - 1; //이전 페이지
            echo "<a href = '#' onclick = \"$('input[name=page]').val({$before_group_page}); $(this).parent('form').submit();\">
            		<div id ='brdLst_SPstl_PgLnk-Bfr'>
            			이전
            			
            		</div>
	</a>";
        }

        //숫자 출력
        for ($i=0; $i < $z; $i++) {
            
            $page = $start_pageNum + $i;
            //요청한 페이지에 도달하면 다른것 출력
            if($page == $_POST['page']) {
                echo "<div id ='brdLst_SPstl_Slt-on'>".$page."</div>";
                continue;
            }

           echo "<a href = '#' onclick = \"$('input[name=page]').val({$page}); $(this).parent('form').submit();\">
           		
           		<div class ='brdLst-SPstl-Slt-off'>
           		".$page."
           		</div>
           	</a>";
        }

        //다음페이지, 마지막 출력
        if($recent_group < $data_w_info['count_group']) {
            //다음페이지 구현
            $next_group_page = $recent_group*$data_w_info['setUp_groupNum'] +1; 
            echo "<a href = '#' onclick = \"$('input[name=page]').val({$next_group_page}); $(this).parent('form').submit();\">
            		<div id ='brdLst_SPstl_PgLnk-Nxt'>
            			다음

            		</div>
            		</a>";
            //마지막 페이지 링크
	//   echo "<a href = '#' onclick = \"$('input[name=page]').val({$data_w_info['count_page'] }); $(this).parent('form').submit();\">
	//             		<div id ='brdLst_SPstl_PgLnk-Lst'>
	//             			마지막
	//             		</div>
	// </a>";
        }



        echo "</form>";
        return 1;
}

 ?>


		<div id = 'mInfo_Dstl_SplSrch'>
			<div id = 'mInfo_Dstl_SplAd'>
				blank<!-- 병원 홍보  -->
			</div>

			<div id = 'mInfo_Dstl_Srch'>
				1. 검색
			</div>

		</div>

		<div id = 'mInfo_SaH1-up'>
			<div id ='mInfo_DH1up-lft'>
				<div class = 'mInfo-Dstl-SbTtl'> 
					증상으로 알아보는 대처법
					<!-- symptom -->
				</div>

				<ul>
<?php
				// print_r($data_w['symptom']);
				foreach ($data_w['symptom'] as $entry) {
					echo "
						<li>
							<div class = 'mInfo-Dstl-RdTtl'>
								<a href = '/minfo/medic/read/{$entry['read_id']}'> {$entry['title']} </a>
							</div>

							<div class = 'mInfo-Dstl-RdWriter'>
								{$entry['writed_member']}
							</div>
						</li>


					";
				}

?>
				</ul>



				<div class = 'MInfo-Dstl-PgLnk'> 

<?php 
		if(!pasing_sys($data_w_info['symptom'][0], 'symptom') ) {
		 echo "<script>alert('존재하지 않는 페이지입니다..');</script>";
		} 
?>

				</div>
			
			</div>

			<div id ='mInfo_DH1up-rt'>
				<div class = 'mInfo-Dstl-SbTtl'> 
					의학 정보 
					<!-- medical -->
				</div> 


				<ul>
<?php
				// print_r($data_w['medical']);
				foreach ($data_w['medical'] as $entry) {
					$entry['read_id'] = -$entry['read_id'];
					echo "
						<li>
							<div class = 'mInfo-Dstl-RdTtl'>
								<a href = '/minfo/medic/read/{$entry['read_id']}'> {$entry['title']} </a>
							</div>

							<div class = 'mInfo-Dstl-RdWriter'>
								{$entry['writed_member']}
							</div>
						</li>


					";
				}

?>
				</ul>




				<div class = 'MInfo-Dstl-PgLnk'> 
<?php 

		if(!pasing_sys($data_w_info['medical'][0] ,'medical') ) {
		 echo "<script>alert('존재하지 않는 페이지입니다..');</script>";
		} 
?>
				</div>
	
			</div>
		</div>

		<div id = 'mInfo_SaH1-dn'>
			<div id ='mInfo_DH1dn-lft'>
				<div class = 'mInfo-Dstl-SbTtl'> 
					병원정보 바로가기 (병원 홍보)
					<!-- hospital -->
				</div>

				<ul>
<?php
				// print_r($data_w['hospital']);
				foreach ($data_w['hospital'] as $entry) {
					$entry['read_id'] = -$entry['read_id'];
					echo "
						<li>
							<div class = 'mInfo-Dstl-RdTtl'>
								<a href = '/minfo/medic/read/{$entry['read_id']}'> {$entry['title']} </a>
							</div>

							<div class = 'mInfo-Dstl-RdWriter'>
								{$entry['writed_member']}
							</div>
						</li>


					";
				}

?>
				</ul>





				<div class = 'MInfo-Dstl-PgLnk'> 

<?php 
		if(!pasing_sys($data_w_info['hospital'][0], 'hospital') ) {
		 echo "<script>alert('존재하지 않는 페이지입니다..');</script>";
		} 
?>

				</div>
			</div>

			<div id ='mInfo_DH1dn-rt'>
				<div class = 'mInfo-Dstl-SbTtl'> 
					약 정보
					<!-- medicine -->
				</div>


				<ul>
<?php
				// print_r($data_w['medicine']);
				foreach ($data_w['medicine'] as $entry) {
					$entry['read_id'] = -$entry['read_id'];
					echo "
						<li>
							<div class = 'mInfo-Dstl-RdTtl'>
								<a href = '/minfo/medic/read/{$entry['read_id']}'> {$entry['title']} </a>
							</div>

							<div class = 'mInfo-Dstl-RdWriter'>
								{$entry['writed_member']}
							</div>
						</li>


					";
				}

?>
				</ul>






				<div class = 'MInfo-Dstl-PgLnk'> 
<?php 
		if(!pasing_sys($data_w_info['medicine'][0], 'medicine') ){
		 echo "<script>alert('존재하지 않는 페이지입니다..');</script>";
		} 
?>
				</div>

			</div>
		</div>

		<div id="brdLst_Dstl_WrtBtn">

<?php
	$logged_in = $this->session->userdata('logged_in');

	if(isset($logged_in) && $logged_in ) {

		echo "
	        	<form action='/minfo/medic/write' method='post'>
	        		<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
	               	<button class='C_btn-W100'> 글쓰기 </button>
	            	</form>";

	} else 
		echo "<span style = 'color:red;'>회원 글쓰기 가능</span>"

?>

	        </div>


	</div>
</div>



<script>
$('form[name=get_list]').submit(function(e) {
	e.preventDefault();
	return 0;
});
</script>