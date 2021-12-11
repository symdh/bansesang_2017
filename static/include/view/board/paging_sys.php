 <div id = "brdLst_DRsv_PgLnk">
            <div id = "brdLst_Dstl_PgLnk">
                
                <?php  
                //개발페이지 6-2 참조
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
                        echo "<script>window.location = '/board/{$function_name}/list';</script>";
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
                        echo "<a href='/board/{$function_name}/list?page=1'>
                                     <div id = 'brdLst_SPstl_PgLnk-Fst'>
                                        
                                        처음
                                        
                                     </div>
                                   </a>";
                        //이전
                        $before_group_page = $start_pageNum - 1;
                        echo "<a href='/board/{$function_name}/list?page=".$before_group_page."'>
                                        <div id ='brdLst_SPstl_PgLnk-Bfr'>            
                                            이전
                                        </div>
                                    </a>";
                    }
   
                    //숫자 출력
                    for ($i=0; $i < $z; $i++) {
                        
                        $page = $start_pageNum + $i;
                        //요청한 페이지에 도달하면 다른것 출력
                        if($page == $_GET['page']) {
                            echo "<a href='/board/{$function_name}/list?page=".$page."'>
                                            <div id ='brdLst_SPstl_Slt-on'>
                                                 ".$page."
                                            </div>
                                        </a>";
                            continue;
                        }

                        echo "<a href='/board/{$function_name}/list?page=".$page."'>
                                        <div class ='brdLst-SPstl-Slt-off'>
                                            ".$page."
                                        </div>
                                    </a>";
                    }

                    //다음페이지, 마지막 출력
                    if($recent_group < $data_w_info[0]['count_group']) {
                        //다음페이지 구현
                        $next_group_page = $recent_group*$data_w_info[0]['setUp_groupNum'] +1; 
                        echo "<a href='/board/{$function_name}/list?page=".$next_group_page."'>
                                        <div id ='brdLst_SPstl_PgLnk-Nxt'>
                                            다음
                                        </div>
                                   </a>";
                        //마지막 페이지 링크 (제거)
                        // echo "<a href='/board/{$function_name}/list?page=".$data_w_info[0]['count_page']."'>
                        //                 <div id ='brdLst_SPstl_PgLnk-Lst'>
                        //                     마지막
                        //                 </div>
                        //            </a>";
                    }
                ?>
            </div>
        </div>