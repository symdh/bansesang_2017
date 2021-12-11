<?php defined('BASEPATH') OR exit('No direct script access allowed');

switch ($purpose) {
	case 'main':
		$this->config->set_item('title','반세상 - 권한 관리');

		$this->load->model('Manage_model');
		$user_info = $this->Manage_model->user_permission_load();



		$this->load->view('PC/start_page');
		$this->load->view('PC/manage/header_manage_page');
			$this->load->view('PC/manage/pms_main', array("user_info"=> $user_info));
		$this->load->view('PC/manage/footer_manage_page');
		$this->load->view('PC/end_page');
	break;

   	default:
   		return 0;
   	break;
}

