<script>
	var search_query = getParameters('listvary');
	if( search_query != '0' ) {
		listvary = new Array();
		listvary[1] = 'need';
	}
</script>


<div id = "mngCapLst_main">
	<div id = "mngCapLst_wrapper">
		<div id = "mngCapLst_Dstl_SbMnu">
	<?php
		echo "
			<a href = '/manage/cap/list/{$function_name}?listvary=all'>
				<div id = 'mngCapLst_appoint_all_writed'>
					<span>[전체 목록 보기]</span>
				</div>
			</a>
			<a href = '/manage/cap/list/{$function_name}?listvary=need'>
				<div id = 'mngCapLst_appoint_all_need_answer_writed'>
					<span>[진행 목록 보기]</span>
				</div>
			</a>
			<a href = '/manage/cap/list/{$function_name}?listvary=defer'>
				<div id = 'mngCapLst_appoint_all_defer_answer_writed'>
					<span>[보류 목록 보기]</span>
				</div>
			</a>
			";
	?>
		</div>

	<?php 
		//echo  $_GET['listvary'] ; 
		//해당 목록만 시작버튼 보임
		// print_r($data_w);
		if( $_GET['listvary'] == 'need' && isset($data_w[0]['read_id'])) {
			//echo "{$last_read_id}"; 
			echo "
				<div id = 'mngCapLst_appoint_start_answer'>
					<a href = '/manage/cap/read/{$function_name}/{$data_w[0]['read_id']}'>
						<button>
							답변 시작 ㄱㄱ
						</button>
					</a>
				</div>
				";
		}

	?>
		<table>
        	<tbody>
        		<?php 
        			//print_r($data_w);
        			//print_r($function_name);

        		//데이터는 gets에서 처리해서 가져옴
        			$i = 0;
        			
        			//all일경우 역순
        			if( $_GET['listvary'] == 'all' ) {
        				$data_w = array_reverse($data_w);
        			}

        			foreach ($data_w as $entry){ 

        				//30개만 출력
        				$i++;
        				if($i == 31) {
        					break;
        				}
        // 				if($function_name != 'report') {
        // 					$entry['date'] = date("d일 H:i",strtotime($entry['date']));
        // 				} else {
        // 					//신고일경우 최초 신고시간을 표시
  						//  	$pattern_date = "/~([^\/]+)/i";
						 	// preg_match_all($pattern_date ,$entry['reporter_info'] ,$matches_1); 
						 	// //print_r($matches_1);
        // 					$entry['date'] = $matches_1[1][0];
        // 					$entry['date'] = date("d일 H:i",strtotime($entry['date']));
        // 				}
        				
					   //****************html시작부 

					   echo "<tr>";

					     if($entry['check_answer'] == 0) {
					     	$entry['check_answer'] = '진행';
					     	echo "<td class ='mngCapLst_appoint_need_answer' >{$entry['check_answer']}</td>";
					     } else if ($entry['check_answer'] == -1) {
					     	$entry['check_answer'] = '보류';
					     	echo "<td class ='mngCapLst_appoint_defer_answer' >{$entry['check_answer']}</td>";
					     } else  {  
					     	$entry['check_answer'] = '완료';
					     	echo "<td class ='mngCapLst_appoint_complete_answer' >{$entry['check_answer']}</td>";
					     }

					     	if($function_name != 'report' || !empty($entry['title'])) {
					     		//제목이 존재하면
					     		echo "
					     			
					     			<td class = 'mngCapLst_appoint_title'>
					     				<a href = '/manage/cap/read/{$function_name}/{$entry['read_id']}'> 
					     					{$entry['title']}
					     				</a>
					     			</td> ";
					     	} else {
					     		//제목이 존재하지 않을때
					     		echo "
					     			
					     			<td class = 'mngCapLst_appoint_title'>
					     				<a href = '/manage/cap/read/{$function_name}/{$entry['read_id']}'> 
					     					(댓글 신고)
					     				</a>
					     			</td> ";
					     	}

					     //익명일때와 아닐때를 구분
					     if ($entry['is_anony']) {
					     	echo "<td class = 'mngCapLst_appoint_anony_nickname'>{$entry['writed_member']}</td>";
					     } else {
					     	echo "<td class = 'mngCapLst_appoint_member_nickname'>{$entry['writed_member']}</td>";
					     }

					     //모바일시 제목이 사라지는것을 대비하여 날짜에도 넣음
					     echo "		<td class = 'mngCapLst_appoint_date'>
					     				<a href = '/manage/cap/read/{$function_name}/{$entry['read_id']}'>{$entry['date']}</a>
					     			</td>
					     	   </tr>

					     ";
        			}
        		?>
        		
        	</tbody>
        </table>


         <div id = "mngCapLst_div_style_for_1_pageNum">
            <div id = "mngCapLst_div_style_for_1_1_pageNum">
                
                <?php 
                	//전체 목록일때만 페이징 시스템 실행
                	if( $_GET['listvary'] == 'all' )  {
	                //개발페이지 6-2 참조 (페이징 시스템)
	                    /*넘어오는 변수
	                    $data_w_info[0]['setUp_groupNum'];
	                    $data_w_info[0]['setUp_pageNum'];
	                    $data_w_info[0]['count_page'];
	                    $data_w_info[0]['count_group'];
	                    $_GET['page'];
	                    */

	                    //초과된 페이지 요청시 거부하고 돌려보냄
	                    if($_GET['page'] > $data_w_info[0]['count_page']) {
	                        echo "<script>alert('존재하지 않는 페이지입니다..');</script>";
	                        echo "<script>window.location = '/manage/cap/list/{$function_name}?listvary=all';</script>";
	                        return 0;
	                    }

	                    $recent_group = ceil($_GET['page']/$data_w_info[0]['setUp_groupNum']);
	                    //시작 page 구함
	                    $start_pageNum = ($recent_group-1)*$data_w_info[0]['setUp_groupNum'] + 1;

	                    //현재그룹이 마지막 그룹이면
	                    if($recent_group == $data_w_info[0]['count_group']) {
	                        //마지막 그룹의 페이지수를 구함
	                        $end_group_count_page = $data_w_info[0]['setUp_groupNum']+ ($data_w_info[0]['count_page']- $data_w_info[0]['count_group']*$data_w_info[0]['setUp_groupNum']);
	                        //반복계수 z를 초기화
	                        $z = $end_group_count_page;
	                    } else {
	                        //아니면 그룹의 내부수로 초기화
	                        $z = $data_w_info[0]['setUp_groupNum'];
	                    }

	                    //처음, 이전페이지 출력
	                    if ($recent_group > 1) {
	                        //처음
	                        echo "<a href='/manage/cap/list/{$function_name}?listvary=all&page=1'><span id ='mngCapLst_appoint_link_first_page'>처음</span></a>";
	                        //이전
	                        $before_group_page = $start_pageNum - 1;
	                        echo "<a href='/manage/cap/list/{$function_name}?listvary=all&page=".$before_group_page."'><span id ='mngCapLst_appoint_link_before_page'>이전</span></a>";
	                    }
	   
	                    //숫자 출력
	                    for ($i=0; $i < $z; $i++) {
	                        
	                        $page = $start_pageNum + $i;
	                        //요청한 페이지에 도달하면 다른것 출력
	                        if($page == $_GET['page']) {
	                            echo "<a href='/manage/cap/list/{$function_name}?listvary=all&page=".$page."'><span id ='mngCapLst_appoint_seleted_page'>".$page."</span></a>";
	                            continue;
	                        }

	                        echo "<a href='/manage/cap/list/{$function_name}?listvary=all&page=".$page."'><span class ='mngCapLst_appoint_not_seleted_page'>".$page."</span></a>";
	                    }

	                    //다음페이지, 마지막 출력
	                    if($recent_group < $data_w_info[0]['count_group']) {
	                        //다음페이지 구현
	                        $next_group_page = $recent_group*$data_w_info[0]['setUp_groupNum'] +1; 
	                        echo "<a href='/manage/cap/list/{$function_name}?listvary=all&page=".$next_group_page."'><span id ='mngCapLst_appoint_link_next_page'>다음</span></a>";
	                        //마지막 페이지 링크
	                        echo "<a href='/manage/cap/list/{$function_name}?listvary=all&page=".$data_w_info[0]['count_page']."'><span id ='mngCapLst_appoint_link_last_page'>마지막</span></a>";
	                    }
                	}
                ?>
            </div>
        </div>

	
	</div>
</div> 