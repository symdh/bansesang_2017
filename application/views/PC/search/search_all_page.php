

  <div id = 'pgMn_main'> 
    <div id = 'pgMn_wapper'>
      
        
<!--         <div id = 'pgMn_Dstl_Tn'>
          <div id = 'pgMn_DRsv_Tn'>
            <div id = 'pgMn_DRsv_BstTn'>
<?php 
// echo "imformation ";
?>
            </div>
            <div id = 'pgMn_DRsv_IssTn'>
<?php 
// echo "imformation ";
?>
            </div>
          </div>
        </div> -->

<!--         <div id = 'pgMn_Dstl_Ad-1'>
          <div id = 'pgMn_DRsv_Ad-1'>
           
<?php 
// echo "ad ";
?>
            
          </div>
        </div> -->

<!--         <div id = 'pgMn_Dstl_RdInfo-1'>
          <div id = 'pgMn_DRsv_RdInfo-1'>
            <div id = 'pgMn_Dstl_BstRd'>
-->

        <div id = 'pgMn_Dstl_Srch'> 
          <div id = 'pgMn_DRsv_Srch'> 
             <div id = 'pgMn_DRsv_SplSrch'> 
      

               <div class = 'pgMn-SbTtl-style'> 
                  검색
                  	<span style = 'font-weight: normal;'>
<?php 
	if(isset($main_info['count']['total']))
		echo "{$main_info['count']['total']}개의 검색 결과";
	else 
		echo "0개의 검색 결과";

?>
              		</span>
              </div>

             <div id = 'pgMn_SpSrch'>
<?php require_once('./static/include/view/search/search_main.php'); ?>
              </div>


            </div>


            <div id = 'pgMn_Dstl_freeRd'>
               <table>
                <thead>
                  <th style = 'text-align: left;' class = 'pgMn-SbTtl-style'> 
                  의학 게시물

                   		<span style = 'font-weight: normal;'>
<?php 
	if(isset($main_info['count']['medic']))
		echo "{$main_info['count']['medic']}개의 검색 결과";
	else 
		echo "0개의 검색 결과";
?>
                     	</span>
                  </th>
                  <th style = 'background-color: #f3f3f3;' > </th>
                </thead>
                <tbody>
<?php 
  //print_r($main_info);  
  $check_num = 0;
   foreach ($main_info as $key => $entry){

    //모바일은 hide로 숨긴다는 가정을 하면 딱 맞을듯 ㅇㅇ   
    if($entry['pointer'] == 'medic'){
      
      $check_num++;
      if($check_num == 11 ) {
        break;
      }  


      echo "
          <tr>
              <td class = 'phMn-TDstl-freeTtl'> <div>
              <a href = '/minfo/{$entry['pointer']}/read/{$entry['read_id']}'>
                {$entry['title']} ";
                 //댓글갯수가 0개일때는 실행하지 않음
                if(!$entry['count_comment']) {
                } else {
                    echo "[{$entry['count_comment']}]";
                }

                
      echo "   </a></td> 
              <td class = 'phMn-TDstl-freeNm'> {$entry['writed_member']}</td> ";
              // <td class = 'brdLst-TDstl-RdDt'> <div>{$entry['date']}</div> </td>
      echo "</tr>"; 

      unset($main_info[$key]);
    }
  }
  //print_r($main_info);
?>
                </tbody>
              </table>


            </div>
          </div>
        </div>

        <div id = 'pgMn_Dstl_RdInfo-2'>
          <div id = 'pgMn_DRsv_RdInfo-2'>
            <div id = 'pgMn_Dstl_PtRd'>

              <div class = 'pgMn-SbTtl-style'> 
                사진 게시판 

                   <span style = 'font-weight: normal;'>
<?php 
	if(isset($main_info['count']['photo']))
		echo "{$main_info['count']['photo']}개의 검색 결과";
	else 
		echo "0개의 검색 결과";
?>
                    </span>
              </div>

            	<ul id ='pgMn_ULstl_PtRd'>
<?php 
	//print_r($main_info);
	
	

	//위에꺼에서 코드 수정됨
	$check_num = 0;
   foreach ($main_info as $key => $entry){

   	if($entry['pointer'] == 'photo'){
	      $check_num++;
	      if($check_num == 5 ) {
	        break;
	      }  


	      //이미지 파일중에 최상단 하나만 나타낼꺼임
	    	$pattern ='/\/static[^%]+/i';
			preg_match_all($pattern,$entry['attach_image'],$thumbnail_filename); 
			$thumbnail_filename[0][0] = str_replace("/img/", "/img/thumbnail/", $thumbnail_filename[0][0]) ;
			$thumbnail_filename[0][0] = str_replace(".", "_min.", $thumbnail_filename[0][0]) ;
				//print_r($thumbnail_filename[0][0]);

			echo "<li class = 'pgMn-LIstl-PtRd'>
					<a href = '/board/{$entry['pointer']}/read/{$entry['read_id']}'> "; //바로 아래까지 연결됨

            echo "
              <div class = 'pgMn-Dstl-PtRdTtl'> {$entry['title']} </div>
              "; 

			echo "<img class ='C_img_Mn_GlrRd' src='{$thumbnail_filename[0][0]}' />";
      echo "</a>";
	     
	    

	      echo "</li>";


      	unset($main_info[$key]);
      }
   }

?>
					</ul>
            </div>
            <div id = 'pgMn_Dstl_QaaRd'>

                 <table>
                <thead>
                  <th style = 'text-align: left;' class = 'pgMn-SbTtl-style'>
                    자유 게시판


                       <span style = 'font-weight: normal;'>
<?php 
	if(isset($main_info['count']['free']))
		echo "{$main_info['count']['free']}개의 검색 결과";
	else 
		echo "0개의 검색 결과";
?>
                   		 </span>
                    </th>
                  <th style = 'background-color: #f3f3f3;' > </th>
                </thead>
                <tbody>
<?php 
  //print_r($main_info);  
  $check_num = 0;
   foreach ($main_info as $key => $entry){

    //모바일은 hide로 숨긴다는 가정을 하면 딱 맞을듯 ㅇㅇ   
    if($entry['pointer'] == 'free'){
      
      $check_num++;
      if($check_num == 8 ) {
        break;
      }  

      echo "
          <tr>
              <td class = 'phMn-TDstl-freeTtl'> <div>
              <a href = '/board/{$entry['pointer']}/read/{$entry['read_id']}'>
                {$entry['title']} ";
                 //댓글갯수가 0개일때는 실행하지 않음
                if(!$entry['count_comment']) {
                } else {
                    echo "[{$entry['count_comment']}]";
                }

                
      echo "   </a></td> 
              <td class = 'phMn-TDstl-freeNm'> {$entry['writed_member']}</td> ";
              // <td class = 'brdLst-TDstl-RdDt'> <div>{$entry['date']}</div> </td>
      echo "</tr>"; 

      unset($main_info[$key]);
    }
  }
  //print_r($main_info);
?>
                </tbody>
              </table>


              <div  style = 'margin-top:20px;'> </div>
              <table>
                <thead>
                  <div class = 'pgMn-SbTtl-style'> 
                      질답 게시판 

                          <span style = 'font-weight: normal;'>
<?php 
	if(isset($main_info['count']['qaa']))
		echo "{$main_info['count']['qaa']}개의 검색 결과";
	else 
		echo "0개의 검색 결과";
?>
                   		 	</span>
                  </div>
                </thead>
                <tbody>
<?php 
  //print_r($main_info);  
  $check_num = 0;
   foreach ($main_info as $key => $entry){

    //모바일은 hide로 숨긴다는 가정을 하면 딱 맞을듯 ㅇㅇ   
    if($entry['pointer'] == 'qaa'){
      
      $check_num++;
      if($check_num == 8 ) {
        break;
      }  

      echo "<tr>";

          if($entry['solve_q'] == 1) {
            echo  "<td class = 'pgMn-TDstl-answer'>완료</td>";
          } else if ( $entry['solve_q'] == 0 && $entry['count_comment'] > 0 ) {
            echo  "<td class = 'pgMn-TDstl-answer'>진행중</td>";
          } else if ( $entry['solve_q'] == 0 && $entry['count_comment'] == 0 ) {
            echo  "<td class = 'pgMn-TDstl-answer'>미답변</td>";
          }

      echo " <td class = 'pgMn-TDstl-QaaRdTtl'> 
              <a href = '/board/{$entry['pointer']}/read/{$entry['read_id']}'>
                {$entry['title']} </a>
                </td>  ";
      echo "</tr>"; 

      unset($main_info[$key]);
    }
  }
  //print_r($main_info);
?>
                </tbody>
              </table>


            </div>
          </div>
        </div>


<!--         <div id = 'pgMn_Dstl_Srch'>
          <div id = 'pgMn_DRsv_Srch'>
            <div id = 'pgMn_DRsv_SplSrch'>
<?php 
echo "research";
?>
            </div>
            <div id = 'pgMn_Dstl_Ad-2'>
<?php 
echo "ad";
?>
            </div>
          </div>
        </div>

         <div id = 'pgMn_Dstl_MdcInfo'>
          <div id = 'pgMn_DRsv_MdcInfo'>
            <div class = 'pgMn-Dstl-MdcInfo'>
<?php 
echo "imformation";
?>
            </div>
            <div class = 'pgMn-Dstl-MdcInfo'>
<?php 
echo "imformation";
?>
            </div>
          </div>
        </div> -->

     
    <div>
  </div>
