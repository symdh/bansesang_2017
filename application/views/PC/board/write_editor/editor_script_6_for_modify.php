
<script>
/* 파일 첨부쪽이 넘어갈 방법을 모름
	document.getElementsByTagName('body')[0].onload = db_content;
	


	function db_content() {

		var content = 
		<?php  
			//echo "'".addslashes($data_w[0]['content'])."'"; 
		?>;
		
		document.getElementById('tx_canvas_wysiwyg').contentWindow.document.getElementsByClassName('tx-content-container')[0].innerHTML = content ;
		 
		if (self.name != 'reload') {
	        self.name = 'reload';
	        self.location.reload(true);
     	} else 
     		self.name = ''; 
	}	 
*/
/* 파일 첨부시 해당 규칙 따를것 */
		var attachments = {};
		attachments['image'] = [];

<?php  



$saved_image = explode("%///%", $data_w[0]['attach_image']);

// print_r($saved_image);
// return 0;
//마지막 처리 해줘야됨
for($i = 0; $i < count($saved_image) - 1; $i++) {
	$saved_image[$i] = str_replace($this->img_domain,'',$saved_image[$i]);
	if(file_exists('.'.$saved_image[$i])){
		$file_size = filesize('.'.$saved_image[$i]);
		$saved_image[$i] = $this->img_domain.$saved_image[$i];
		
		echo "
			attachments['image'].push({
				'attacher': 'image',
				'data': {
				'imageurl': '{$saved_image[$i]}',
				'filename': '{$saved_image[$i+1]}',
				'filesize': '{$file_size}',
				'originalurl': '{$saved_image[$i]}',
				'thumburl': '{$saved_image[$i]}'
				}
			});
		";
	} else {
		echo "에러. 파일이 존재하지 않습니다. 관리자에게 문의하세요";
	}
	$i++; //결국 2번뛰기
}

// //이미지 뽑아내는 정규식 (일단 아까우니 냄겨두자)
// $pattern ='/\/static[^%]+/i';
// preg_match_all($pattern,$data_w[0]['attach_image'],$matches); 
// foreach ($matches as $key => $entry) {
// 	foreach($entry as $keys => $entry_2){

// 		$pattern = '/\/static\/upload\/img\/(.+)/i';
// 		preg_match_all($pattern,$entry[$keys],$file_info);
		
// 		if(file_exists('.'.$file_info[0][0])){
// 			$file_size = filesize('.'.$file_info[0][0]);
// 			echo "
// 				attachments['image'].push({
// 				'attacher': 'image',
// 				'data': {
// 					'imageurl': '{$file_info[0][0]}',
// 					'filename': '{$file_info[1][0]}',
// 					'filesize': '{$file_size}',
// 					'originalurl': '{$file_info[0][0]}',
// 					'thumburl': '{$file_info[0][0]}'
// 				}
// 				});
// 			";
// 		} else {
// 			echo "에러. 파일이 존재하지 않습니다. 관리자에게 문의하세요";
// 		}
// 	}
// } 

/*
				foreach ($matches as $key => $entry) {
					foreach($entry as $keys => $entry_2){
						//print_r($entry[$keys]);
							//상대경로를 위해 .붙여줌
						$entry[$keys] = '.'.$entry[$keys];
						//print_r($entry[$keys]);

						//$file_size = filesize('.'.$file_info[0][0]);
						
						if(file_exists($entry[$keys]) ){
							unlink($entry[$keys]);
							$this->db_board->query("DELETE from garbage_image WHERE image_route = '{$entry[$keys]}' ");
						} else {
							echo "에러코드 1101. 관리자에게 문의하세요";
						}
						//imageurl: $file_info[0][0]
						//filename: $file_info[1][0]
						
					}
				}
*/
?>

		
/* 이미지 첨부시 사용
		attachments['image'].push({
			'attacher': 'image',
			'data': {
				'imageurl': 'http://cfile273.uf.daum.net/image/2064CD374EE1ACCB0F15C8',
				'filename': 'github.gif',
				'filesize': 59501,
				'originalurl': 'http://cfile273.uf.daum.net/original/2064CD374EE1ACCB0F15C8',
				'thumburl': 'http://cfile273.uf.daum.net/P150x100/2064CD374EE1ACCB0F15C8'
			}
		});
*/
/* 파일 첨부시 사용
		attachments['file'] = [];
		attachments['file'].push({
			'attacher': 'file',
			'data': {
				'attachurl': 'http://cfile297.uf.daum.net/attach/207C8C1B4AA4F5DC01A644',
				'filemime': 'image/gif',
				'filename': 'editor_bi.gif',
				'filesize': 640
			}
		});
*/
		/* 저장된 컨텐츠를 불러오기 위한 함수 호출 */
		Editor.modify({
			"attachments": function () { // 저장된 첨부가 있을 경우 배열로 넘김, 위의 부분을 수정하고 아래 부분은 수정없이 사용 
				var allattachments = [];
				for (var i in attachments) {
					allattachments = allattachments.concat(attachments[i]);
				}
				return allattachments;
			}(),
			// 내용 문자열, 주어진 필드(textarea) 엘리먼트 
			"content": <?php  echo "'".addslashes($data_w[0]['content'])."'"; ?>

		});

</script>


