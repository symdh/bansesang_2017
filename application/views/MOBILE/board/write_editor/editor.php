
<div id = 'brdWrt_main'> 



<?php

	//로그인 체크함수 (저장할때 form 검사하므로 필요)
	$check_loggin = $this->session->userdata('logged_in');
	echo "<script>";
	if($check_loggin ) {
		echo "check_loggin = ".$check_loggin.";";
	} else {
		echo "check_loggin = 0 ;";
	}
	echo "</script>";

	if($function_name != 'csc' && !preg_match( '/minfo/',$function_name) ){
		echo "<form name='tx_editor_form' id='tx_editor_form' action='/board/{$function_name}/uploadwrite' method='post' accept-charset='utf-8' onsubmit='return check_mobile_input();'>";
	} else { 
		echo "<form name='tx_editor_form' id='tx_editor_form' action='/{$function_name}/uploadwrite' method='post' accept-charset='utf-8' onsubmit='return check_mobile_input();'>";
	}


?>

<script>
function check_mobile_input() {

	$(".C_btn-H40").attr("type", "button");
	$(".C_btn-H40").text("등 록 중");

	if(!watch_comment_before_send()) {
		$(".C_btn-H40").attr("type", "submit");
		$(".C_btn-H40").text("등록하기");
		return false;
	}
	return true;

}

</script>



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
			if( isset($_GET['proposal']) && $_GET['proposal'] == 1) {
				echo "<script>$('#brdWrt_Dstl_SltTp').val('proposal');  </script>";
			}

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


<?php


		echo "

				<input type = 'text' id = 'brdWrt_Dplec_WrtTtl' name ='title'   placeholder ='제목을 입력하세요'>
					<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
					
			";

?>


<?php

	//검사는 editor_5에  validForm에서 진행
	//read_page 댓글 부분이랑 동일함
		$check_loggin = $this->session->userdata('logged_in');
		if(!$check_loggin) {
			echo " <div id = 'brdRd_Dstl_CmInfo'> 
			 <input type = 'text' name = 'anony_nickname' style = 'width:48%; min-height:25px; max-height:35px; height:5vh;' class = 'C_style_input_nickname' placeholder = '닉네임: 한글기준 6자이하 '>
			 <input type = 'password' name = 'anony_passwd' style = 'width:48%; min-height:25px; max-height:35px; height:5vh;' class = 'C_style_input_passwd_show' placeholder = '비밀번호: 띄어쓰기 금지'> 
			</div>
			";
			echo '<br>';
		}


		echo "<textarea name = 'content' id ='ckeditor' class = 'ckeditor'></textarea>";
?>




<?php
	echo " 

					<div>
						<button type = 'submit' class = 'C_btn-H40' style = 'float:right; margin-top:10px; width:100px; height: 40px; font-size:15px;'>
							등록하기
						</button>
					</div>

				</form>

				
				</div>
		";

?>


<script>

<?php
 	if(isset($_GET['type'])) 
 		echo "var write_type='{$_GET['type']}' ";	
 	else 
 		echo "var write_type =0";
?>

$('#brdWrt_Dstl_SltTp').val(write_type);
add_option();

 $('#brdWrt_Dstl_SltTp').change(function(){
      	add_option();
 });

function add_option()  {
	var event_element = $('#brdWrt_Dstl_SltTp');
	 $('#brdWrt_Dstl_SltTp-2').children().remove();

      	if( $('#brdWrt_Dstl_SltTp').val() == 'notice') {
      		$('#brdWrt_Dstl_SltTp-2').append("<option value = 'notice'> 알림 </option>");
      		$('#brdWrt_Dstl_SltTp-2').append("<option value = 'patch'> 패치내역 </option>");
      	 } else if (  $('#brdWrt_Dstl_SltTp').val() == 'faq' ) {
      	 	$('#brdWrt_Dstl_SltTp-2').append("<option value = '0'> 아직없음 </option>");
      		$('#brdWrt_Dstl_SltTp-2').append("<option value = '0'> 아직없음 </option>");
 	}
}




</script>

	<!-- 사용시에만 넣음 -->
	<script src = '/static/lib/ckeditor/ckeditor.js'></script>

<script>

<?php
    echo "CKEDITOR.replace( 'ckeditor', {
        filebrowserUploadUrl: '/wide/uploadimage/{$class_name}'
    });";

?>
</script>


<script>
  //csrf보안
  $(document).ready(function(){  
   CKEDITOR.on('dialogDefinition', function(ev) {
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;
 
    if(dialogName == 'image') {
     var uploadTab = dialogDefinition.getContents('Upload');
 
     for (var i =0; i < uploadTab.elements.length; i++)
     {
      var el = uploadTab.elements[i];
      if(el.type != 'fileButton') {
       continue;
      }
 
      var onClick = el.onClick;
 
      el.onClick = function(evt){
       var dialog = this.getDialog();
       var fb = dialog.getContentElement(this['for'][0], this['for'][1]);
       var action = fb.getAction();
       var editor = dialog.getParentEditor();
       editor._.filebrowserSe = this;
 
       $(fb.getInputElement().getParent().$).append('<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />');
 
       if(onClick && onClick.call(evt.sender, evt) === false) {
        return false;
       }
       return true;
      };
     }
    }
   });
  });




 // 이미지 창 커스텀
    CKEDITOR.on('dialogDefinition', function (ev) {
        var dialogName = ev.data.name;
        var dialog = ev.data.definition.dialog;
        var dialogDefinition = ev.data.definition;

        if (dialogName == 'image') {
            dialog.on('show', function (obj) {
                this.selectPage('Upload'); //업로드텝으로 바로 이동
            });
        }

        dialogDefinition.removeContents( 'advanced' ); // 자세히 탭 삭제 
        dialogDefinition.removeContents( 'Link' ); // 링크 탭 삭제 

       // ckeditor 설치 폴더에서 plugins/image/dialogs/image.js 이곳에서 해당 앨리먼트 확인
        var infoTab = dialogDefinition.getContents( 'info' );  //info탭을 제거하면 이미지 업로드가 안된다.
        infoTab.remove( 'txtHSpace');
        infoTab.remove( 'txtVSpace');
        infoTab.remove( 'txtBorder');
        infoTab.remove( 'txtWidth');
        infoTab.remove( 'txtHeight');
        infoTab.remove( 'ratioLock');
        infoTab.remove( 'cmbAlign');
    }); 

// image의 width와 height 속성을 없앰
CKEDITOR.on('instanceReady', function (ev) {
// Ends self closing tags the HTML4 way, like <br>.
ev.editor.dataProcessor.htmlFilter.addRules(
    {
        elements:
        {
            $: function (element) {
                // Output dimensions of images as width and height
                if (element.name == 'img') {
                    var style = element.attributes.style;

                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                            width = match && match[1];

                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                        var height = match && match[1];

                        if (width) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                            element.attributes.width = width;
                        }

                        if (height) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                            element.attributes.height = height;
                        }
                    }
                }



                if (!element.attributes.style)
                    delete element.attributes.style;

                return element;
            }
        }
    });
});




</script>



<?php

	if(isset($data_w[0])) {
		echo "
			<script>
				$('#ckeditor').val('{$data_w[0]['content']}');
				$('input[name=title]').val('{$data_w[0]['title']}');

				$('#brdWrt_main').children('form').append(\"<input type='hidden' name='read_id' value='{$data_w[0]['read_id']}'' />\");
				$('#brdWrt_main').children('form').append(\"<input type='hidden' name='id_check' value='{$data_w[0]['id_check']}' />\");
				
				 $('#brdWrt_Dstl_SltTp').hide();
				$('#brdWrt_Dstl_SltTp-2').hide();
			
		";

		$check_loggin = $this->session->userdata('logged_in');
		if(!$check_loggin) {
			echo "$('input[name=anony_nickname]').val('{$data_w[0]['writed_member']}'); ";
			echo "$('input[name=anony_nickname]').hide(); ";
			echo "$('input[name=anony_passwd]').val('{$data_w[0]['passwd']}'); ";
			echo "$('input[name=anony_passwd]').hide(); ";
			echo "$('.C_btn-H40').text('수정하기'); ";
		}

		if($function_name != 'csc' && !preg_match( '/minfo/',$function_name) ){

			echo "$('#brdWrt_main').children('form').attr('action','/{$class_name}/{$function_name}/modifywrite');";
		} else {
			echo "$('select[name=write_type]').val('{$data_w[0]['divide_type']}'); ";
			echo "$('#brdWrt_Dstl_SltTp').show();" ;
			echo "$('#brdWrt_main').children('form').attr('action','/{$function_name}/modifywrite');";
		}

		echo "</script>";




	}



?>