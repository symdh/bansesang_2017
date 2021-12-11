<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Img_sys extends CI_model { //이미지 관리 시스템

	function __construct(){ 
		parent::__construct(); 
		$this->db_collect_func = $this->load->database('collect_func', TRUE);
		date_default_timezone_set("Asia/Seoul");
		
		
	}

	public function extract_img ($content) {
		//내용을 가지고 attach_image를 만들어 보내는 대신에 받은다음에 정규식으로 추출!!
		preg_match_all("/http\:\/\/img\.bansesang\.com\/static\/upload\/img[^\"]+/",$content,  $preg_result);
		foreach ($preg_result as $key => $value) {
			$result = $preg_result[$key];
		}

		return $result;
	}

	//필요시 컨트롤러에서 처리
	public function upload_img ($url, $do_thumbnail = 0, $Domain = 'http://img.bansesang.com') {
		//print_r($_FILES);

		//ckeditor 같은경우 upload로 넘어옴
		if (isset($_FILES['upload'])) { 
			$_FILES['upload_file'] = $_FILES['upload'];
		}

		// 다음웹 에디터는 upload_file로 해놓고 이걸로 설계함
		if(!isset($_FILES['upload_file']['name'])) {
			echo "파일업로드 실패";
	      return 0;
	   } 

       if($_FILES['upload_file']['error']==1 ) {
       	echo "{\"error\": \"업로드실패. 확장자 및 용량을 확인해주세요.\"}";

       	return 0;
       }
	          
		$upload_path = $url;

		$config['upload_path'] = '.'.$upload_path; //상대주소 처리
		$config['allowed_types'] = 'png|jpeg|gif|bmp|jpg|jpe';
		$config['encrypt_name'] = TRUE;
		//max_size, max_width, max_height 등 설정가능
		$filename = $this->security->sanitize_filename($_FILES['upload_file']['name']);
		//echo $filename;
		
		$config['file_name'] = $filename;
		

		$this->load->library('upload', $config);
		if ( !$this->upload->do_upload('upload_file')) {
		        // $error = array('error' => $this->upload->display_errors());
		        // echo "파일업로드 실패:".var_dump($error);
		       echo "실패. 확장자 및 용량을 확인해주세요.";
		        return 0;
		 } else {
		        $data = array('upload_img' => $this->upload->data());
		        //var_dump($data);
		} 

		//썸네일 업로드를 위한 설정
		$image_url = $upload_path.$data["upload_img"]["file_name"];
		//리턴을 위한 설정
		$upload_path = $Domain.$upload_path;

      $result["upload_img"]["image_url"] = $upload_path.$data["upload_img"]["file_name"];
      $result["upload_img"]["size"] = $data["upload_img"]["file_size"];
      $result['upload_img']['name'] = $data["upload_img"]["orig_name"];

        
      $origin_name = $data["upload_img"]["orig_name"];

	
      if($do_thumbnail)  {
	      //비율을 '2:1 = 가로:세로'로 확정, 150 - 110 까지
			//비율 수정(4:3)됬음 개발노트확인 
			$this->thumbnail_img('.'.$image_url, 300, 225); //상대주소 처리
		} 

		//db등록을 위한 주소처리
		$image_url = $Domain.$image_url ;

		//이미지 정보를 따로 저장
		$check_login = $this->session->userdata('logged_in');
		$image_url = $this->db->escape($image_url);
		$origin_name = $this->db->escape($origin_name);
		if($check_login) {
			$writed_member = $this->session->userdata('nickname');
			$writed_member = $this->db->escape($writed_member);
					//내부값: 없음    //외부값: 다수
			$this->db_collect_func->query("INSERT into garbage_image (`image_route`,`writed_member`, `origin_name`) values ({$image_url} ,{$writed_member}, {$origin_name} ) ");
		} else {
					//내부값: 없음    //외부값: 다수
			$this->db_collect_func->query("INSERT into garbage_image (`image_route`,`writed_member`, `origin_name`) values ({$image_url} ,'0', {$origin_name} ) ");
		}
	
		//print_r($_FILES);

		// ckedior 일경우 원본주소 출력
		if (isset($_FILES['upload'])) { 
			$CKEditorFuncNum = $this->input->get("CKEditorFuncNum");
			echo "

			  <!--jquery 링크-->
  			<script src=\"https://code.jquery.com/jquery-1.12.4.min.js\"></script>

			<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('{$CKEditorFuncNum}', '{$result['upload_img']['image_url']}', function() {
                // Get the reference to a dialog window.
                var dialog = this.getDialog();
                // Check if this is the Image Properties dialog window.
                if ( dialog.getName() == 'image' ) {
                    // Get the reference to a text field that stores the alt attribute.
                    var element = dialog.getContentElement( 'info', 'txtAlt' );
                    // Assign the new value.
                    if ( element )
                        element.setValue( '{$result['upload_img']['name']}' );
                }
                // Return false to stop further execution. In such case CKEditor will ignore the second argument (fileUrl)
                // and the onSelect function assigned to the button that called the file manager (if defined).
                // return false;
            	
            } ); 
            // 바로 업로드
            window.parent.$(\".cke_dialog_ui_button_ok span\").click();

            </script>";
			return 0;
		}

		// //자바스크립트에서 바로 객체로 사용할수 있게 변환
		$json_string = json_encode($result, JSON_PRETTY_PRINT);
		echo $json_string; //이게 ajax 통신의 출력 결과
	}

	//썸네일 이미지 생성 ($file/thumbnail 폴더)
	private function thumbnail_img($file, $set_width, $set_height ) {
		//rate는 가로:세로, '가로 = 세로 *$rate' 를 말함
		$rate =  $set_width/$set_height;

		//이미 업로드된 파일(검사된 파일)을 사용하기 때문에 정규식을 사용하여 확장자를 검사하여도 문제 없음
		$pattern[0] ='/\.GIF$/i';
		$pattern[1] ='/\.JPG$/i';
		$pattern[2] ='/\.JPE$/i';
		$pattern[3] ='/\.JPEG$/i';
		$pattern[4] ='/\.PNG$/i';
		$pattern[5] ='/\.BMP$/i';

		for($i=0; $i < count($pattern); $i++) {
			preg_match_all($pattern[$i] ,$file ,$matches); 
			if(isset($matches[0][0]) ) {
				//파일로부터 이미지를 읽어옵니다
				if($i == 0) {
					$take_img = ImageCreateFromGIF($file);
					$save_filename = str_ireplace(".gif", "_min.gif", $file) ;
					break;
				} else if ( $i == 1) {

					$take_img = ImageCreateFromJPEG($file);
					$save_filename = str_ireplace(".jpg", "_min.jpg", $file) ;
					break;
				} else if ( $i == 2) {
					$take_img = ImageCreateFromJPEG($file);
					$save_filename = str_ireplace(".jpe", "_min.jpe", $file) ;
					break;
				} else if ( $i == 3) {
					$take_img = ImageCreateFromJPEG($file);
					$save_filename = str_ireplace(".jpge", "_min.jpge", $file) ;
					break;
				} else if ($i == 4) {
					$take_img = ImageCreateFromPNG($file);
					$save_filename = str_ireplace(".png", "_min.png", $file) ;
					break;
				} else if ($i == 5) {
					$take_img = ImageCreateFromBMP($file);
					$save_filename = str_ireplace(".bmp", "_min.bmp", $file) ;
					break;
				} else {
					echo "에러발생";
					return 0;
				} 
			} 
		}

		$save_filename = str_ireplace("/img/", "/img/thumbnail/", $save_filename) ;
		//print_r($save_filename);

		$img_info = getImageSize($file);//원본이미지의 정보를 얻어옵니다
		$take_width = $img_info[0];
		$take_height = $img_info[1];

		//설정된 값이 리사이즈 시킬 값임
		$resize_width = $set_width;
		$resize_height = $set_height;

		//원본 size를 썸네일화 하기 위해 비율을 맞춤
			//작은쪽 기준으로 가져와야됨
		if( $take_width > $take_height*$rate ) {  //검증
			  //아래꺼랑 반대 개념
			  $take_width = $take_height*$rate;

		} else if ( $take_width < $take_height*$rate) {   //검증
			  //가져올 그림의 세로값이 더 클경우 가로값기준으로 세로값을 짤라서 가져옴
			  $take_height = $take_width/$rate;

		} else if ( $take_width == $take_height*$rate) {  
			  //비율이 같을 경우 그대로 진행함
		} else {
			  echo "있을 수 없는 경우 ";
			  return 0;
		}

		if( $take_width >= $set_width && $take_height >= $set_height) {    //할거 없음.

		} //여기서 부터는 짜르거나 그냥 가져옴 
		else if( $take_width <= $set_width && $take_height <= $set_height) {   
			//모두 작을 때는 그냥 그대로 가도됨
			$resize_width = $take_width;
			$resize_height = $take_height;

		} else if( $take_width < $set_width && $take_height >= $set_height) {  
			//세로만 클때는 세로만 짤라서 가져옴 
			$take_height = $set_height;

			$resize_width = $take_width; //가로 크기는 작으므로 resize 값은 그림값
			$resize_height = $set_height;
		} else if( $take_width >= $set_width && $take_height < $set_height) {    
			//가로만 클때는 가로만 짤라서 가져옴 
			$take_width = $set_width;

			$resize_width = $set_width; 
			$resize_height = $take_height; //세로 크기는 작으므로 resize 값은 그림값
		} else {
			echo "있을 수 없는 경우 ";
			return 0;
		}

		$resize_img = imagecreatetruecolor($resize_width, $resize_height); //타겟이미지를 생성합니다

		//x,y 축 설정
		$std_x_take_img = 0;
		$std_y_take_img = 0;
		$std_x_resize_img = 0;
		$std_y_resize_img = 0;

		//타겟이미지에 원하는 사이즈의 이미지를 저장합니다
		imagecopyresampled($resize_img, $take_img, $std_x_take_img, $std_y_take_img, $std_x_resize_img, $std_y_resize_img, $resize_width, $resize_height, $take_width, $take_height); 
		                                 //저장할 크기(size를 조정) //가져올 크기 (짤라서 가져옴) 
		ImageInterlace($resize_img);

		 //실제로 이미지파일을 생성합니다
		if($i == 0) {
		 	ImageGIF($resize_img, $save_filename,40); 
		} else if ( $i < 4) {
		 	 ImageJPEG($resize_img, $save_filename,40); 
		} else if ($i == 4) {
			  ImagePNG($resize_img, $save_filename,4); 
		} else if ($i == 5) {
			  ImageBMP($resize_img, $save_filename,40); 
		}

		//print_r($save_filename);
		//print_r($resize_img);

		ImageDestroy($resize_img);
		ImageDestroy($take_img);
	}

	//이미지가 실제로 저장되는거면 garbage 삭제후 넘겨줌 
	public function save_img ($arr_img_info) { //배열로 넘어와야됨
		$attach_image = '';
		if( $this->session->userdata('logged_in') ) {
			$writed_member = $this->session->userdata('nickname');
			$writed_member = $this->db->escape($writed_member);

			for ($i = 0; $i < count($arr_img_info); $i++){
				$check_exist_image = $arr_img_info[$i];
				$check_exist_image = $this->db->escape($check_exist_image);
						//외부값: 다수
				$db_image_info = $this->db_collect_func->query("SELECT * from garbage_image WHERE writed_member = binary({$writed_member}) AND image_route = {$check_exist_image} ")->result_array();
				
				if(!isset($db_image_info[0]['image_route'])) {
					return 0;
				} else {
					$attach_image = $attach_image.$arr_img_info[$i].'%///%'.$db_image_info[0]['origin_name'].'%///%';
							//내부값: 없음    //외부값: 다수
					$this->db_collect_func->query("DELETE from garbage_image WHERE writed_member = binary({$writed_member}) AND image_route = {$check_exist_image} ");
				}
			}


		} else {
			//로그인 안되어 있을때 파일검사
			for ($i = 0; $i < count($arr_img_info); $i++){
				$check_exist_image = $arr_img_info[$i];

				//print_r($check_exist_image);
				$check_exist_image = $this->db->escape($check_exist_image);
						//외부값: 다수
				$db_image_info = $this->db_collect_func->query("SELECT * from garbage_image WHERE writed_member = '0' AND image_route = {$check_exist_image} ")->result_array();
				
				if(!isset($db_image_info[0]['image_route'])) {
					return 0;
				} else {
					$attach_image = $attach_image.$arr_img_info[$i].'%///%'.$db_image_info[0]['origin_name'].'%///%';
							//외부값: 다수
					$this->db_collect_func->query("DELETE from garbage_image WHERE writed_member = '0' AND image_route = {$check_exist_image} ");
				}
			}
		}
		return $attach_image;
	}

	public function modify_img ($arr_img_info, $saved_img_info) { //이미지 정보 변경함

		//수정전 이미지 비교를위해 따로저장
		$i = -1;
		$j = count($saved_img_info) -1;
		$index_i = 0;

		foreach ($saved_img_info as $entry)  {
			$i++;
			if($i%2 == 0 && $i != $j) {
				$previous_store_image[$index_i] = $entry; //파일 경로 저장 (마지막요소 저장x)
			}

			if($i%2 == 1) {
				$store_image_name[$index_i] = $entry; //실제 파일 이름 저장
				$index_i++;
			}
		}

		$attach_image = '';
				
		//기존의 이미지가 있었을때
		if(isset($previous_store_image)) { 
			//기존의 이미지와 넘어온 이미지를 비교
			$count_img_info = count($arr_img_info);
			for ($i = 0; $i < count($previous_store_image); $i++){
				$do_unlink = 1;
				for ($j = 0; $j < $count_img_info; $j++){
					if(!isset($arr_img_info[$j])) continue;
					if($previous_store_image[$i] == $arr_img_info[$j]) {
						 //새로운 이미지 검사하기 위해서 기존이미지 발견되면 삭제
						unset($arr_img_info[$j]);
						//db 이미지 정보에 추가할 내용
						$attach_image = $attach_image.$previous_store_image[$i].'%///%'.$store_image_name[$i].'%///%'; 
						$do_unlink = 0;
						break;
					}
				}

				//기존 이미지가 삭제됬다고 판단했을때 삭제위해 그거 따로 저장
				if($do_unlink) {
					if(!isset($ii)) $ii =0;
					$unlink_img_info[$ii] = 	$previous_store_image[$i];
					$ii++;
				}
			}

			//삭제할 이미지가 있으면 삭제시킴
			if(isset($unlink_img_info)) {
				$result = $this->delete_img(0,1,$unlink_img_info);
				if($result === 0) { return 0; }
			}
			
			// //unset 하면 정렬은 안됨 ㅡㅡ
			// sort($arr_img_info);
		
			//수정시 새로운 이미지 검사
			for ($j = 0; $j < $count_img_info; $j++){
				if(!isset($arr_img_info[$j])) continue;
				$array_img_info[0]= $arr_img_info[$j];
				$result = $this->save_img($array_img_info );
				if($result === 0) { 
					return 0; 
				} else {
					$attach_image = $attach_image.$result;
				}
			}
		} else {
			//기존의 이미지가 없을경우 그대로 이미지 파일 검사 진행하면됨
			$attach_image = $this->save_img($arr_img_info);
			if($attach_image === 0) { return 0; }
		}

		return $attach_image;
	}

	public function delete_img ($saved_img_info, $is_url = 0, $url_info = '', $Domain = 'http://img.bansesang.com') {
		//saved_img_info : db에 저장된 방식 (배열로 넘어와야됨)
		//is_url : 1인경우 단순 주소 형식
		//url_info : 주소가 배열로 저장되어 있을때 (배열로 넘어와야됨)
		if($is_url) {
			foreach ($url_info as $entry) {
				$entry = str_replace($Domain,'',$entry);
				$entry = '.'.$entry;
				if(file_exists($entry) ){
					unlink($entry);

					//썹네일 이미지또한 삭제
					$entry =str_ireplace("/img/","/img/thumbnail/",$entry);
					$pattern ='/\.GIF$|\.JPG$|\.JPE$|\.JPGE$|\.PNG$|\.BMP$/i';
					preg_match_all($pattern,$entry,$matches); 
					$entry =str_ireplace($matches[0][0],"_min".$matches[0][0],$entry);
					if(file_exists($entry) )
						unlink($entry);
				} else {
					//삭제할 파일이 없을경우 동작정지
					return 0;
				}
			} 
			return 1;
		}


		//수정전 이미지 비교를위해 따로저장
		$i = -1;
		$j = count($saved_img_info) -1;
		$index_i = 0;

		foreach ($saved_img_info as $entry)  {
			$i++;
			if($i%2 == 0 && $i != $j) {
				$entry = str_replace($Domain,'',$entry);
				$previous_store_image[$index_i] = $entry; //파일 경로 저장 (마지막요소 저장x)
			}

			if($i%2 == 1) {
				$store_image_name[$index_i] = $entry; //파일 실제 이름 저장
				$index_i++;
			}
		}

		//글 수정에서 이미지를 모두 삭제 했을 경우임
		if(isset($previous_store_image)) {
			for ($i = 0; $i < count($previous_store_image); $i++){
				$previous_store_image[$i] = '.'.$previous_store_image[$i];
				if(file_exists($previous_store_image[$i]) ){
					unlink($previous_store_image[$i]);

					//썹네일 이미지또한 삭제
					$previous_store_image[$i] =str_ireplace("/img/","/img/thumbnail/",$previous_store_image[$i]);
					$pattern ='/\.GIF$|\.JPG$|\.JPE$|\.JPGE$|\.PNG$|\.BMP$/i';
					preg_match_all($pattern,$previous_store_image[$i],$matches); 
					$previous_store_image[$i] =str_ireplace($matches[0][0],"_min".$matches[0][0],$previous_store_image[$i]);
					if(file_exists($previous_store_image[$i]) )
						unlink($previous_store_image[$i]);

				} else {
					//삭제할 파일이 없을경우 오류
					return 0;
				}
			}
		}

		$attach_image ='';
		return $attach_image;
	}

	//쓰레기 값 삭제
	public function clean_upload_img ($Domain = 'http://img.bansesang.com') {

		//글 삭제시 쓰레기 이미지 제거 코드 입력
		
		//회원일경우 24시간 
				//내부값: 없음    //외부값: 없음
		$check_delete_image = $this->db_collect_func->query("SELECT image_route from garbage_image WHERE check_hour > '23' ")->result_array();
		if(isset($check_delete_image[0])) {
			for($i = 0; $i < count($check_delete_image); $i++ ) {
				//배열로 넘겨줌
				$array_img_info[0] = $check_delete_image[$i]['image_route'];
				$result = $this->delete_img(0,1,$array_img_info);

				if($result === 1) {
					$check_delete_image[$i]['image_route'] = $this->db->escape($check_delete_image[$i]['image_route']);
							//내부값: 없음    //외부값: 다수
					$this->db_collect_func->query("DELETE from garbage_image WHERE image_route = {$check_delete_image[$i]['image_route']} ");
				} else {
					
					return 0;
				}
			}
			unset($check_delete_image);
		}

		//익명일경우 6시간 
				//내부값: 없음    //외부값: 없음
		$check_delete_image = $this->db_collect_func->query("SELECT image_route from garbage_image WHERE writed_member = '0' AND check_hour > '5' ")->result_array();
		if(isset($check_delete_image[0])) {
			for($i = 0; $i < count($check_delete_image); $i++ ) {
				//배열로 넘겨줌
				$array_img_info[0] = $check_delete_image[$i]['image_route'];
				$result = $this->delete_img(0,1,$array_img_info);

				if($result === 1) {
					$check_delete_image[$i]['image_route'] = $this->db->escape($check_delete_image[$i]['image_route']);
							//내부값: 없음    //외부값: 다수
					$this->db_collect_func->query("DELETE from garbage_image WHERE image_route = {$check_delete_image[$i]['image_route']} ");
				} else {
					
					return 0;
				}
			}
		}
		return 1;
	}

}

