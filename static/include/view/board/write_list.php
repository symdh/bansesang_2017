
	<div id = "brdLst_Dplec_MnLst">
		<table id = 'brdLst_TBstl'>
			<thead>
<?php
	if(isset($data_w[0]['solve_q'])) {
		echo "<th class = 'C_th_H30'>답변</th>";
	} else {
		echo "<th class = 'C_th_H30'>분류</th>";
	}
?>
				<th class = 'C_th_H30'>제목</th>
				<th class = 'C_th_H30'>글쓴이</th>
				<th class = 'C_th_H30'>날짜</th>
				<th class = 'C_th_H30'>조회수</th>

			</thead>

			<tbody>

<?php
   foreach ($data_w as $entry){   //read page를 위한 설정 
      	if(isset($data_w_read[0]) && $data_w_read[0]['read_id'] == $entry['read_id']) {
      		echo "<tr class = 'C_tr_H30' style = 'background-color:#FFDFFF;' id = 'brdLst_TRstl_SltTtl'>";
      	} else {
      		echo "<tr class = 'C_tr_H30' onMouseOver = \"$(this).css('background-color','#FFF1FF'); \" onMouseout=\"$(this).css('background-color','#fff'); \" >";
      	}

                
    	if(isset($data_w[0]['solve_q'])) {
	      	if($entry['solve_q'] == 1) {
	         echo  "<td class = 'brdLst-TDstl-answer'>완료</td>";
	     	} else if ( $entry['solve_q'] == 0 && $entry['count_comment'] > 0 ) {
	         echo  "<td class = 'brdLst-TDstl-answer'>진행중</td>";
	      	} else if ( $entry['solve_q'] == 0 && $entry['count_comment'] == 0 ) {
	         echo  "<td class = 'brdLst-TDstl-answer'>미답변</td>";
	      	}           
      	} else {
         	echo  "<td class = 'brdLst-TDplec-RdNum'>{$entry['read_id']}</td>";
      	}
            
      	echo "<td class = 'brdLst-TDstl-RdTtl'> ";
	   	echo "<a href = '/board/{$function_name}/read/{$entry['read_id']}'>";

      	//내용에 이미지가 있을경우 (이미지 게시판에는 필요가 없음)
      if(strlen($entry['attach_image']) > 5) {
       	echo "<img class = 'C_img_Ttl_notice' src = '/static/img/appear-picture.jpg' />";
      	} else {
      	   echo "<img class = 'brdLst-IMGstl-Lnk-off' class = 'C_img_Ttl_notice' src = '/static/img/appear-nopicture.jpg' />";
      	}

      	echo "{$entry['title']}";

      	if(!$entry['count_comment']) {
      	   echo "";   
      	} else {
      	   echo "<span class = 'brdLst-SPstl-CmNum'>[{$entry['count_comment']}]</span>";
      	}
      	
      	echo "</a>";

      	echo "</td> 
      	         		<td class = 'brdLst-TDstl-RdWriter'>{$entry['writed_member']} </td>
      	         		<td class = 'brdLst-TDstl-RdDt'> {$entry['date']} </td>
      	         		<td class = 'brdLst-TDstl-ClkNum'> {$entry['click_num']} </td>
      	     	</tr>";
        
  	}
?>
	   		</tbody>
	   </table>


	   <div id = "brdLst_Dstl_WrtBtn" >
	         <!--게시판 주소 바뀌면 수정해야함-->
<?php
   echo "<form action = '/board/{$function_name}/write' method = 'post'>";
   $data_w_read[0]['token_name'] = $this->security->get_csrf_token_name();
   $data_w_read[0]['hash'] = $this->security->get_csrf_hash();
   echo "<input type='hidden' name='".$data_w_read[0]['token_name']."' value='".$data_w_read[0]['hash']."' />";
?>
      
          	<button class = 'C_btn-W100'> 글쓰기 </button>
       	</form>
      	</div>

<?php
   	// 페이징 시스템
 	include "./static/include/view/board/paging_sys.php";
            
?>


   </div>

