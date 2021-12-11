
<?php
	echo "
			<div id = 'mngCapRd_shape_for_answer'>
				<form name='mngCapRd_form_1' id='' action='/manage/cap/manageuploadwrtie' method='post' accept-charset='utf-8' onsubmit='return confirm_submit(this);'>
			";
		
		echo "

				제목: <input type = 'text' style = 'width:700px;' name ='title'   placeholder ='제목을 입력하세요'>
					<input type='hidden' name='".$this->security->get_csrf_token_name()."' value='".$this->security->get_csrf_hash()."' />
					<textarea name = 'content' id ='ckeditor' class = 'ckeditor'></textarea>

					올릴곳 
					<select name = 'write_type'  id = 'brdWrt_Dstl_SltTp' >
				             	 <option value = '0'> 선택하세요 </option>
				            	<option value = 'notice'> 공지 </option>
				            	<option value = 'faq'> FAQ </option>
				            					            	
				            </select>

				            세부지정
				            <select name = 'divide_type'  id = 'brdWrt_Dstl_SltTp-2'>
				            	<option value = '0'> </option>			           	
				            </select>

					<div id = 'mngCapRd_shape_for_submit'>
						<button>
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



<?php

	if(isset($data_w[0])) {
		echo "
			<script>
				$('#ckeditor').val('{$data_w[0]['content']}');
				$('input[name=title]').val('{$data_w[0]['title']}');

				$('#mngCapRd_shape_for_answer').children('form').append(\"<input type='hidden' name='read_id' value='{$data_w[0]['read_id']}'' />\");
				$('#mngCapRd_shape_for_answer').children('form').append(\"<input type='hidden' name='id_check' value='{$data_w[0]['id_check']}' />\");
				$('#mngCapRd_shape_for_answer').children('form').attr('action','/manage/cap/managemodifywrtie');
				$('#brdWrt_Dstl_SltTp').hide();
				$('#brdWrt_Dstl_SltTp-2').hide();
			</script>
		";


	}

?>