
	<!-- 아래부터 include/view/board/write_list.php 복사함 -->
    
	<div id = "brdLst_Dplec_MnLst">
		<table id = 'brdLst_TBstl'>
			<thead>
				<th class = 'C_th_H30'>글목록</th>
			</thead>
			
			<tbody>

<?php
   foreach ($data_w as $entry){
   	   //read page를 위한 설정 
   	   if(isset($data_w_read[0]) && $data_w_read[0]['read_id'] == $entry['read_id'] ) {
			echo "<tr class = 'C_tr-H25' style = 'background-color:#FFDFFF;' id = 'brdLst_TRstl_SltTtl' onclick = 'location.href=\"/board/{$function_name}/read/{$entry['read_id']}\"'> ";
		} else {
			echo "
            <tr class = 'C_tr-H25' onMouseOver = \"$(this).css('background-color','#FFF1FF'); \" onMouseout=\"$(this).css('background-color','#fff'); \" onclick = 'location.href=\"/board/{$function_name}/read/{$entry['read_id']}\" ' >";
		}

      	echo " <td class = 'brdLst-TDstl-RdTtl'> ";

     	//내용에 이미지가 있을경우 (이미지 게시판에는 필요가 없음)
      	if(strlen($entry['attach_image']) > 5) {
       	echo "<img class = 'C_img_Ttl_notice' src = '/static/img/appear-picture.jpg' />";
      	} else {
        	echo "<img class = 'brdLst-IMGstl-Lnk-off' class = 'C_img_Ttl_notice' src = '/static/img/appear-nopicture.jpg' />";
      	}

   	   if(isset($data_w[0]['solve_q'])) {
    	   	if($entry['solve_q'] == 1) {
             // echo  "<td class = 'brdLst-TDstl-answer'>완료</td>";
             echo  " [완료] ";
         	} else if ( $entry['solve_q'] == 0 && $entry['count_comment'] > 0 ) {
             // echo  "<td class = 'brdLst-TDstl-answer'>진행중</td>";
              echo  "[진행중]";
         	} else if ( $entry['solve_q'] == 0 && $entry['count_comment'] == 0 ) {
             // echo  "<td class = 'brdLst-TDstl-answer'>미답변</td>";
             echo  "[미답변]";
         	}
     	} else {
     		//글번호 : {$entry['read_id']}
     	}
                
      	echo "{$entry['title']}";

      	//댓글갯수가 0개일때는 실행하지 않음
      	if(!$entry['count_comment']) {
      	   echo "";   
      	} else {
      	   echo "<span class = 'brdLst-SPstl-CmNum'>[{$entry['count_comment']}]</span>";
      	}

      	echo "  <br>    <div style = 'width:70px; float:right; text-align:center; color:gray; font-size:11px;'> 조회수: {$entry['click_num']} </div>
                   	<div style = 'width:70px; float:right; text-align:center; color:gray; font-size:11px;'> {$entry['date']} </div> 
                   	<div style = 'width:80px; float:right; text-align:center; color:gray; font-size:12px;'> {$entry['writed_member']} </div>
                ";
      	echo "</td>
      	      </tr>";
   	} 
?>
        </tbody>
      </table>

	   	<div id = "brdLst_Dstl_WrtBtn" >
           
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

