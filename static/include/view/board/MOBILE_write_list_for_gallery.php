<? //여기부분만 조절하면 될 것으로 보입니다. ㄱㄱ ?>


   <div id = "brdLst_Dplec_GlrMnLst">
    <ul id = "brdLst_ULplec_GlrMnLst">

<?php
    $i = 0;
    foreach ($data_w as $entry){
        //이미지 파일중에 최상단 2개만 나타낼꺼임 (이미지가 없는 경우는 생각하지 않음)
        $pattern ='/\/static[^%]+/i';
        preg_match_all($pattern,$entry['attach_image'],$thumbnail_filename); 
        //print_r($thumbnail_filename);
        
        $thumbnail_filename[0][0] = str_replace("/img/", "/img/thumbnail/", $thumbnail_filename[0][0]) ;
        $thumbnail_filename[0][0] = str_replace(".", "_min.", $thumbnail_filename[0][0]) ;
        
        if(isset($thumbnail_filename[0][1])) {
            $thumbnail_filename[0][1] = str_replace("/img/", "/img/thumbnail/", $thumbnail_filename[0][1]) ;
            $thumbnail_filename[0][1] = str_replace(".", "_min.", $thumbnail_filename[0][1]) ;
        } else {
            //두번째 이미지가 없는 경우를 생각함
            $thumbnail_filename[0][1] = '/static/img/no-image.jpg';
        }

        //읽고 있는 페이지 표시를 위한 설정
        if (isset($data_w_read[0]) &&$data_w_read[0]['read_id'] == $entry['read_id']) {
             //div는 모바일을 위한 설정. 바꾸면 에러생김
            echo "
            <li class = 'C_style_for_color_1' id = 'brdLst_LIstl_GlrSlt-on'>
            <a href = '/board/{$function_name}/read/{$entry['read_id']}'>

            <div class = 'brdLst-Dstl-GlrRdTtl'>{$entry['title']} ";

            if(!$entry['count_comment']) {
                    
            } else {
                    echo "<span class = 'brdLst-SPstl-CmNum'>[{$entry['count_comment']}]</span>";
            }


            echo "
                </div>
                <div class = 'brdLst-Dstl-GlrPt'> 
                    <img class = 'brdLs-IMGstl' src='{$thumbnail_filename[0][0]}' /> 
                    <img class = 'brdLs-IMGstl-off' src='{$thumbnail_filename[0][1]}' />
                    ";

             echo "      </div>
                <div class = 'brdLst-Dstl-GlrCvc'>
                    <div class = 'brdLst-TDstl-GlrRdWriter'> {$entry['writed_member']}</div>
                    <div class = 'brdLst-TDstl-GlrRdDt'> {$entry['date']}</div> 
                    <div class = 'brdLst-TDstl-GlrClkNum'> 조회수 :{$entry['click_num']}</div>";
             
             echo "   </div>
                </a> 
            </li>";
             
        } else  {
            //div는 모바일을 위한 설정. 바꾸면 에러생김
            echo "
                <li class = 'brdLst-LIstl-GlrSlt-off' onMouseOver = \"$(this).css('background-color','#FFF1FF'); \" onMouseout=\"$(this).css('background-color','#fff');\">

                <a href = '/board/{$function_name}/read/{$entry['read_id']}'>
                <div class = 'brdLst-Dstl-GlrRdTtl'> {$entry['title']}  ";
             if(!$entry['count_comment']) {
                        
             } else {
                 echo "<span class = 'brdLst-SPstl-CmNum'>[{$entry['count_comment']}]</span>";
             }

             echo "
                </div>
                <div class = 'brdLst-Dstl-GlrPt'>
                    <img class = 'brdLs-IMGstl' src='{$thumbnail_filename[0][0]}' />
                    <img class = 'brdLs-IMGstl-off' src='{$thumbnail_filename[0][1]}' />
                    ";

             echo "   </div> 
                <div class = 'brdLst-Dstl-GlrCvc'>
                    <div class = 'brdLst-TDstl-GlrRdWriter'> {$entry['writed_member']}</div>
                    <div class = 'brdLst-TDstl-GlrRdDt'> {$entry['date']} </div> 
                    <div class = 'brdLst-TDstl-GlrClkNum'> 조회수 :{$entry['click_num']}</div>";
                    
             echo "   </div>
                </a> 
            </li>";
        }

        $i++;
        if($i%2 == 1)
            echo "<div class = 'brdLst-Dstl-GlrBlk'> </div>";

    }
?>
      </ul>

      <div class ='C_clear_both' > </div>
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
    <!-- 여기까지 -->