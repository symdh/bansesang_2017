<?php defined('BASEPATH') OR exit('No direct script access allowed');

if($purpose == 'list') {
	//board/$purpose/list  -> 즉 그 게시판 메인
	
	$this->load->model('Board_model');
	$data_w = $this->Board_model->gets(14);
	$data_w_info = $this->Board_model->gets_info(14 ,10);

	$this->load->model('Collection_function_model');
	$sblst_info = $this->Collection_function_model->get_sblst_info();

	if($this->is_mobile()) {
		$this->load->view('MOBILE/start_page');
		$this->load->view('MOBILE/board/header_board_page');
		$this->load->view('MOBILE/menu/top_menu');
			$this->load->view('MOBILE/board/list_page_for_gallery', array("data_w"=> $data_w, "data_w_info"=> $data_w_info, "function_name" => $this->Board_model->call_function_name(), "sblst_info" => $sblst_info ));
		$this->load->view('MOBILE/board/footer_board_page');
		$this->load->view('MOBILE/end_page');
		return 0;
	}

	$this->load->view('PC/start_page');
	$this->load->view('PC/board/header_board_page');
	$this->load->view('PC/menu/top_menu');
		$this->load->view('PC/board/list_page_for_gallery', array("data_w"=> $data_w, "data_w_info"=> $data_w_info, "function_name" => $this->Board_model->call_function_name(), "sblst_info" => $sblst_info ));
	$this->load->view('PC/board/footer_board_page');
	$this->load->view('PC/end_page');
} else if ($purpose == 'write') {

	if($this->is_mobile()) {
		$this->load->view('MOBILE/start_page');
		$this->load->view('MOBILE/board/header_board_page');
		$this->load->view('MOBILE/menu/top_menu');
			$this->load->view('MOBILE/board/write_editor/editor.php', array("function_name" => $this->Board_model->call_function_name(),"class_name" => $this->class_name));
		$this->load->view('MOBILE/board/footer_board_page');
		$this->load->view('MOBILE/end_page');

		return 0;
	}

	//쓰기부분
	$this->load->view('PC/start_page');
	$this->load->view('PC/board/header_board_page');
	$this->load->view('PC/menu/top_menu');

	$this->load->view('PC/board/write_page', array("function_name" => $this->Board_model->call_function_name()));
	$this->load->view('PC/board/write_editor/editor_header_1', array("function_name" => $this->Board_model->call_function_name()));
	$this->load->view('PC/board/write_editor/toolber_2');
	$this->load->view('PC/board/write_editor/editor_main_3');
	$this->load->view('PC/board/write_editor/fileupload_4');
	$this->load->view('PC/board/write_editor/editor_script_5', array("class_name" => $this->class_name));
	$this->load->view('PC/board/footer_board_page');
	$this->load->view('PC/end_page');

} else if ($purpose == 'read') {
	$this->load->model('Board_model');
	$data_w_read = $this->Board_model->download_write($num, false);

	//include 된 정보를 불러오기 위함
	$data_w_info = $this->Board_model->gets_info(14, 10);
	
	//페이지 정보를 불러오기위한 코드 (개발노트 6-3)
	//print_r($this->Board_model->get_page($num));
	$result = $this->Board_model->get_page($num);
	$_GET['page']= ceil( ($result[0]['count'] + 1)/$data_w_info[0]['setUp_pageNum'] );
	//print_r($_GET['page']);
	
	$data_w = $this->Board_model->gets(14);
	

	//댓글 보안을 위해 한번더 설정함
	if(!isset($data_w_read[0]['read_id'])) {
		echo "<script>alert('존재하지 않는 게시글입니다..');</script>";
		echo "<script>window.location = '/board/{$this->Board_model->call_function_name()}/list';</script>";
		return 0;
	} else {
		//댓글도 불러옴
		$data_c = $this->Board_model->download_comment($num);
	}
	//print_r($data_c);

	//댓글이 존재하는지 확인 (존재시 1)
	if(isset($data_c[0]['comment_id'])) {
		$data_c[0]['check_exist'] = 1;
	} else {
		$data_c[0]['check_exist'] = 0;
	}

	$this->load->model('Collection_function_model');
	$sblst_info = $this->Collection_function_model->get_sblst_info();
	

	if($this->is_mobile()) {
		$this->load->view('MOBILE/start_page');
		$this->load->view('MOBILE/board/header_board_page');
		$this->load->view('MOBILE/menu/top_menu');
			$this->load->view('MOBILE/board/read_page', array("data_w_read"=>$data_w_read, "data_c"=>$data_c, "data_w"=>$data_w, "data_w_info"=> $data_w_info, "function_name" => $this->Board_model->call_function_name(), "check_gallery" => 1, "sblst_info"=>$sblst_info ));
		$this->load->view('MOBILE/board/footer_board_page');
		$this->load->view('MOBILE/end_page');

		return 0;
	}

	$this->load->view('PC/start_page');
	$this->load->view('PC/board/header_board_page');
	$this->load->view('PC/menu/top_menu');
		$this->load->view('PC/board/read_page', array("data_w_read"=>$data_w_read, "data_c"=>$data_c, "data_w"=>$data_w, "data_w_info"=> $data_w_info, "function_name" => $this->Board_model->call_function_name(), "check_gallery" => 1, "sblst_info"=>$sblst_info ));
	$this->load->view('PC/board/footer_board_page');
	$this->load->view('PC/end_page');

} else if ($purpose == 'modify') {
	//이미 음수ㅜ이므로 바꿀필요x
	//$num = -$num;
	$this->load->model('Board_model');
	$data_w = $this->Board_model->download_write($num, true);
	
	if($this->is_mobile()) { 
		$this->load->view('MOBILE/start_page');
		$this->load->view('MOBILE/board/header_board_page');
		$this->load->view('MOBILE/menu/top_menu');
			$this->load->view('MOBILE/board/write_editor/editor.php', array("data_w"=> $data_w, "function_name" => $this->Board_model->call_function_name(),"class_name" => $this->class_name));
		$this->load->view('MOBILE/board/footer_board_page');
		$this->load->view('MOBILE/end_page');
		return 0;
	}

	$this->load->view('PC/start_page');
	$this->load->view('PC/board/header_board_page');	
	$this->load->view('PC/menu/top_menu');

	$this->load->view('PC/board/write_page', array("function_name" => $this->Board_model->call_function_name()));
	//form 몇개 추가
	$this->load->view('PC/board/write_editor/editor_header_1_for_modify', array("data_w"=> $data_w, "function_name" => $this->Board_model->call_function_name()));
	$this->load->view('PC/board/write_editor/toolber_2');
	$this->load->view('PC/board/write_editor/editor_main_3');
	$this->load->view('PC/board/write_editor/fileupload_4');
	$this->load->view('PC/board/write_editor/editor_script_5', array("class_name" => $this->class_name));
	
	$this->load->view('PC/board/write_editor/editor_script_6_for_modify', array("data_w"=> $data_w));
	$this->load->view('PC/board/footer_board_page');
	$this->load->view('PC/end_page');
} else if ($purpose == 'uploadwrite') {
	$this->load->model('Board_model');
		//게시판 주소 바뀌면 수정해야함
	$this->Board_model->upload_write();

	/* 데이터를 받아와서 테스트 하는것
	$data = $this->Board_model->upload_write();
	
	foreach ($data as $entry){
		var_dump($entry);
		echo "<br>";
	}
	*/
} else if ($purpose == 'modifywrite') {
	$this->load->model('Board_model');
		//게시판 주소 바뀌면 수정해야함
	$this->Board_model->modify_write();
} else if ($purpose == 'deletewrite') {
	$this->load->model('Board_model');
	$this->Board_model->delete_write();
} else if ($purpose == 'latelywrite') {
	$this->load->model('Board_model');
	$this->Board_model->lately_write();
	
}  /*이 아래부터는 댓글 관련*/
  else if ($purpose == 'uploadcomment') {
	$this->load->model('Board_model');
	$this->Board_model->upload_comment();
} else if ($purpose == 'uploadanswercomment') {
	$this->load->model('Board_model');
	$this->Board_model->upload_answer_comment();
} else if ($purpose == 'modifycomment') {
	$this->load->model('Board_model');
	$this->Board_model->modify_comment();
} else if ($purpose == 'deletecomment') {
	$this->load->model('Board_model');
	$this->Board_model->delete_comment($num);
} else if ($purpose == 'latelycomment') {
	$this->load->model('Board_model');
	$this->Board_model->lately_comment();
} else {
	echo "<script>alert('잘못된 주소입니다.');</script>";
	echo "<script>window.location = '/board/{$this->Board_model->call_function_name()}/list';</script>";
}


?>