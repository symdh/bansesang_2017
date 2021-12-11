<div id ="brdLst_Dstl_SbLst">
        <div id = "brdLst_DRsv_AidMnu">
            <div class ='brdLst-Dstl-AidMnu' onclick = 'show_SbLst(".brdLst-Dstl-LstIdx")'>
                공지
            </div>

         <!--    <div class ='brdLst-Dstl-AidMnu' onclick = 'show_SbLst("#brdLst_Dstl_freeRd")'>
                실시간 글
            </div>

            <div id ='brdLst_Dstl_AidMnu-Lst'>
                best
            </div> -->
        </div>

<script>
    function show_SbLst(id) {
        $(".brdLst-Dstl-LstIdx").css('display', 'none');
        $("#brdLst_Dstl_freeRd").css('display', 'none');
        
        $(id).css('display','initial');
    }
</script>


        <div class = "brdLst-Dstl-LstIdx">

            <div class = 'brdLst-Dstl-SbTtl' >
                <div class = 'brdLst-DSbTtl-lft'> 공지</div> 
                <div class = 'brdLst-DSbTtl-rt'> <a href='#' onclick ="return window_open('/csc/notice')";> 더보기 </a></div>
            </div>
            
             <span style = 'clear:both;'>
    

<?php
               foreach ($sblst_info as $entry){  
                    if($entry['position'] != 'csc')
                        continue;
                    
                    if($entry['read_id'] == 0) {

                        echo "<a style = 'color:black; font-size:11px;' href='#' onclick=\"return window_open('/csc/notice');\"> {$entry['title']}</a><br>";
                    } else  {
                        echo "<a style = 'color:black; font-size:11px;' href='#' onclick = \"return window_open('/csc/notice/{$entry['pointer']}/{$entry['read_id']}');\"> {$entry['title']}</a><br>";
                    }
               }
?>
             </span>
        </div>
      
<!--         <div id = "brdLst_Dstl_popular">

            <div class = 'brdLst-Dstl-SbTtl' >
                 <div class = 'brdLst-DSbTtl-lft'> 인기 & 급상승</div> 
                 <div class = 'brdLst-DSbTtl-rt'> 더보기</div>
              </div>

            <span style = 'clear:both; font-size:12px'>
            [뉴스] 강아지들 분양소 사실은..
            <br> [사진] 귀여움 주의!!
            <br> [웹툰] 강아지를 떠나보내습니다.
            <br> [자게] 이것이 팩트다!!
            <br> [사진] 귀여움 주의!!
            <br> [웹툰] 강아지를 떠나보내습니다.
            </span>


           
        </div>
 -->



         <!-- <div id = "brdLst_Dstl_PrcRd">
             <div class = 'brdLst-Dstl-SbTtl' >
                <div class = 'brdLst-DSbTtl-lft'> 실시간 무료분양</div> 
                <div class = 'brdLst-DSbTtl-rt'> 더보기</div>
            </div>

            <ul>
             <li style = 'width:110px; float:left; margin-right:15px; margin-bottom: 10px; text-align: center;'> 
                 <img style = 'width:110px;' src = '/static/img/개사진.jpg'> 요크셔테리어
             </li>

             <li style = 'width:110px; float:left; margin-right:15px; margin-bottom: 10px; text-align: center;'> 
                 <img style = 'width:110px;' src = '/static/img/개사진.jpg'> 말티즈
             </li>

             <li style = 'width:110px; float:left; margin-right:15px; margin-bottom: 10px; text-align: center;'> 
                 <img style = 'width:110px;' src = '/static/img/개사진.jpg'> 포메라이언
             </li>

             <li style = 'width:110px; float:left; margin-right:15px; margin-bottom: 10px; text-align: center;'> 
                 <img style = 'width:110px;' src = '/static/img/개사진.jpg'> 건강한아이
             </li>

            </ul>
            
        </div> -->

<!--         <div id = "brdLst_Dstl_newsRd">
             <div class = 'brdLst-Dstl-SbTtl' >
                <div class = 'brdLst-DSbTtl-lft'> 뉴스 & 많이본 의학정보</div> 
                <div class = 'brdLst-DSbTtl-rt'> 더보기</div>
            </div>

            <span style = 'clear:both;'> </span>
            <ul style = 'list-style: square;'>
              <li class = 'brdLst-LIstl-SbLst' >
                    강아지들 분양소 사실은..
                </li>
                <li style = 'margin-left:20px; font-size:11px; '>
                    니들 이걸 뭐라고 생각하냐
                </li>
                <li style = 'margin-left:20px; font-size:11px;'>
                    너는 그럼 뭐라고 생각하냐ㅇㅁㅈㅁㅈ
                </li>
                 <li style = 'margin-left:20px; font-size:11px;'>
                    이것이 팩트다!!
                </li>
                 <li style = 'margin-left:20px; font-size:11px;'>
                    귀여움 주의!!
                </li>
                <li style = 'margin-left:20px; font-size:11px;'>
                    강아지를 떠나보내습니다.
                </li>
            </ul>
        </div>
 -->

        <div id = "brdLst_Dstl_freeRd">

            <div class = 'brdLst-Dstl-SbTtl' >
                <div class = 'brdLst-DSbTtl-lft'> 자유게시판</div> 
                <div class = 'brdLst-DSbTtl-rt'> 
                    <a href ='/board/free/list'>
                        더보기
                    </a>
                </div>
            </div>

            <span style = 'clear:both;'> </span>            
            <ul class = 'brdLst-ULstl-SbLst' >

<?php
    foreach ($sblst_info as $entry){  
        if($entry['pointer'] != 'free')
            continue;

        $entry['read_id'] = -$entry['read_id'];
        echo "
            <li class = 'brdLst-LIstl-SbLst' >
                <a href = '/{$entry['position']}/{$entry['pointer']}/read/{$entry['read_id']}'>
                    {$entry['title']}
                </a>
            </li>

        ";
    }
?>

            </ul>
        </div>

      <!--   <div id = "brdLst_Dplec_SbLstAd">
            광고 
        </div> -->
        
    </div>