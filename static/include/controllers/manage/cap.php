<?php defined('BASEPATH') OR exit('No direct script access allowed');

switch ($purpose) {
	case 'main':
		$this->config->set_item('title','반세상 - 관리페이지');

   		$this->load->model('Csc_model');
		$data_w_info = $this->Csc_model->gets_info(25, 10, 1);

		$this->load->view('PC/start_page');
		$this->load->view('PC/manage/header_manage_page');
			//여기는 function_name 필요없음
			$this->load->view('PC/manage/Cap_menu', array("data_w_info"=> $data_w_info, "function_name" => "main"));
			$this->load->view('PC/manage/Cap_main');
		$this->load->view('PC/manage/footer_manage_page');
		$this->load->view('PC/end_page');
	break;

	case 'uploadanswer':
		$this->load->model('Manage_model');
		$this->Manage_model->upload_answer();
	break;

	case 'giveupanswer' : 
		$this->load->model('Manage_model');
			//게시판 주소 바뀌면 수정해야함
		$this->Manage_model->giveup_answer();
	break;

	case 'reportanswer' : 
		$this->load->model('Manage_model');
		$this->Manage_model->report_answer();
	break;

	case 'list' :
		////////////////////
		// 질문 형식 추가시
		// 1. csc의 access_function && read부분  , mysql writed_info 내용 추가, 
		// Cap_menu 부분, 
		// user_confirm (유저 메뉴 부분)
		// write부분 editor_header_1 부분(검사는 editor_5에 validForm에서 진행)
		////////////////////
		$this->load->model('Csc_model');
		if($function_name !== 'proposal' && $function_name !== 'abug' && $function_name !== 'report' && $function_name !== 'amember' && $function_name !== 'etcetera') {
			echo "<script>alert('잘못된 접근입니다.');</script>";
			echo "<script>window.location = '/main';</script>";
			return 0;
		}  else {
			$this->Csc_model->set_function_name($function_name);
		}
		$data_w_info = $this->Csc_model->gets_info(25, 10, 1 );
		$data_w = $this->Csc_model->gets(25);
		
		$this->load->view('PC/start_page');
		$this->load->view('PC/manage/header_manage_page');	
			$this->load->view('PC/manage/Cap_menu', array("data_w_info"=> $data_w_info, "function_name" => $function_name ));
			$this->load->view('PC/manage/Cap_list_page', array("data_w"=> $data_w, "data_w_info"=> $data_w_info, "function_name" => $function_name ));
		$this->load->view('PC/manage/footer_manage_page');
		$this->load->view('PC/end_page');
	break;

	case 'read' :
		$this->load->model('Csc_model');
		if($function_name !== 'proposal' && $function_name !== 'abug' && $function_name !== 'report' && $function_name !== 'amember' && $function_name !== 'etcetera') {
				echo "<script>alert('잘못된 접근입니다.');</script>";
				echo "<script>window.location = '/main';</script>";
				return 0;
			}  else {
				$this->Csc_model->set_function_name($function_name);
			}
		$data_w_info = $this->Csc_model->gets_info(25, 10, 1);
		$data_w = $this->Csc_model->gets(25);
		
	 	$this->load->model('Manage_model');
	 	$data_w_read = $this->Manage_model->download_write($num, $function_name);

		$this->load->view('PC/start_page');
		$this->load->view('PC/manage/header_manage_page');
			$this->load->view('PC/manage/Cap_menu', array("data_w_info"=> $data_w_info, "function_name" => $function_name ));
			if($function_name == 'proposal' || $function_name == 'abug' || $function_name == 'amember' || $function_name == 'etcetera') {
				$this->load->view('PC/manage/Cap_read_basic', array("data_w_read"=>$data_w_read, "function_name" => $function_name ));
			} else if ($function_name == 'report') {
				$this->load->view('PC/manage/Cap_read_report', array("data_w_read"=>$data_w_read, "function_name" => $function_name ));
			}
		$this->load->view('PC/manage/footer_manage_page');
		$this->load->view('PC/end_page');
	break;

	case 'managewrite' :
		$modify = $function_name ; //수정되면서 이렇게 됬는데 어쩔수 없다. (파라매타값이 필요함) 
		if($modify == 1) {
			$this->load->model('Csc_model');
			$function_name = $_POST['function_name'];
			if($function_name !== 'proposal' && $function_name !== 'abug' && $function_name !== 'report' && $function_name !== 'amember' && $function_name !== 'etcetera' && $function_name !== 'notice') {
				echo "<script>alert('잘못된 접근입니다.');</script>";
				echo "<script>window.location = '/main';</script>";
				return 0;
			}  else {
				$this->Csc_model->set_function_name($function_name);
			}
			$divide_type = $_POST['divide_type'];
			$read_id = $_POST['read_id'];
			$divide_type = $_POST['divide_type'];
			$data_w = $this->Csc_model->download_notice($divide_type, 1,$read_id);
		}

		$this->load->view('PC/start_page');
		$this->load->view('PC/manage/header_manage_page');
		if($modify != 1) 
			$this->load->view('PC/manage/Cap_write_page');
		else 
			$this->load->view('PC/manage/Cap_write_page', array('data_w'=>$data_w) );
		$this->load->view('PC/manage/footer_manage_page');
		$this->load->view('PC/end_page');
	break;

	case 'uploadsblst' :
		$this->load->model('Collection_function_model');
		$this->Collection_function_model->upload_notice();
	break;

	case 'deletesblst' :
		$this->load->model('Collection_function_model');
		$this->Collection_function_model->delete_notice();
	break;

	case 'managemodifywrtie' :
		$this->load->model('Manage_model');
		$this->Manage_model->Manage_modify_write();
	break;

	case 'manageuploadwrtie' : 
		$this->load->model('Manage_model');
		$this->Manage_model->manage_upload_write();
	break;

	case 'deletewrite' :  
		$this->load->model('Manage_model');
   		$this->Manage_model->Manage_delete_write();
   	break;

   	default:
   		return 0;
   	break;
}

