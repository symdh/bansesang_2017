<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Minfo extends CI_Controller {
	
	private $is_mobile;
	public $class_name = 'minfo';
   public $img_domain = 'http://img.bansesang.com'; //글 수정시 이미지 처리에 들어감

	function __construct() {       
	      parent::__construct();
	      date_default_timezone_set("Asia/Seoul");
	      $this->load->library('session');
	      $this->load->model('Log_m');
	      $this->load->model('Log_a');
			$this->Log_a->exe_log('typical','minfo');
	 		$this->load->library('user_agent');

	 		if ($this->agent->is_mobile()) 
		   		$this->is_mobile=1;
			else 
				$this->is_mobile=0;

	      $this->config->set_item('title','의학정보');
	      $this->load->model('Minfo_model');
	}

	private function is_mobile () {
    	return $this->is_mobile;
   }

	//로그인 되어있는지 검사
	private function access_user() {
		$check_login = $this->session->userdata('logged_in');
		
		if(isset($check_login) && $check_login == 1 ) {
			return 1;
		} else {
			return 0;
		}
	}



    //index.php/minfo/medic 
	public function medic($purpose = 'list', $num = 0 ) {
		$this->Minfo_model->set_function_name('medic');

		if($purpose=='list') {
			$this->load->model('Collection_function_model');
			$sblst_info = $this->Collection_function_model->get_sblst_info();

			$this->load->model('Minfo_model');
		
			//정보저장되어 있는거 불러옴
			$info_medical = $this->Minfo_model->gets_info('medical',10,5);
			$info_symptom = $this->Minfo_model->gets_info('symptom',10,5);
			$info_hospital = $this->Minfo_model->gets_info('hospital',10,5);
			$info_medicine = $this->Minfo_model->gets_info('medicine',10,5);
			$data_w_info = array('medical'=>$info_medical, 'symptom'=>$info_symptom, 'hospital'=>$info_hospital, 'medicine'=>$info_medicine);
			//글 정보를 불러옴
			$data_w_medical = $this->Minfo_model->gets('medical',10);
			$data_w_symptom = $this->Minfo_model->gets('symptom',10);
			$data_w_hospital = $this->Minfo_model->gets('hospital',10);
			$data_w_medicine = $this->Minfo_model->gets('medicine',10);
			$data_w = array('medical'=>$data_w_medical, 'symptom'=>$data_w_symptom, 'hospital'=>$data_w_hospital, 'medicine'=>$data_w_medicine);


			if($this->is_mobile()) {
				$this->load->view('MOBILE/start_page');
				$this->load->view('MOBILE/minfo/header_minfo');
				$this->load->view('MOBILE/menu/top_menu');
					$this->load->view('MOBILE/minfo/medic_main', array("sblst_info"=> $sblst_info,'data_w_info'=>$data_w_info, 'data_w'=>$data_w ) );
				$this->load->view('MOBILE/minfo/footer_minfo');
				$this->load->view('MOBILE/end_page');
				return 0;
			}


			$this->load->view('PC/start_page');
			$this->load->view('PC/minfo/header_minfo');
			$this->load->view('PC/menu/top_menu');
				$this->load->view('PC/minfo/medic_main', array("sblst_info"=> $sblst_info,'data_w_info'=>$data_w_info, 'data_w'=>$data_w ) );
			$this->load->view('PC/minfo/footer_minfo');
			$this->load->view('PC/end_page');
		} else if ($purpose == 'read') {

			$this->load->model('Minfo_model');
			$data_w_read = $this->Minfo_model->download_write($num, false);

			//정보저장되어 있는거 불러옴
			$info_medical = $this->Minfo_model->gets_info('medical',10,5);
			$info_symptom = $this->Minfo_model->gets_info('symptom',10,5);
			$info_hospital = $this->Minfo_model->gets_info('hospital',10,5);
			$info_medicine = $this->Minfo_model->gets_info('medicine',10,5);
			$data_w_info = array('medical'=>$info_medical, 'symptom'=>$info_symptom, 'hospital'=>$info_hospital, 'medicine'=>$info_medicine);
			
			//페이지 정보를 불러오기위한 코드 (개발노트 6-3)
			//print_r($this->Minfo_model->get_page($num);
			$result = $this->Minfo_model->get_page($num);
			$_GET['page']= ceil( ($result[0]['count'] + 1)/$data_w_info["{$data_w_read[0]['divide_type']}"][0]['setUp_pageNum'] );
			//print_r($_GET['page']);
			
			//글 정보를 불러옴
			$data_w_medical = $this->Minfo_model->gets('medical',10);
			$data_w_symptom = $this->Minfo_model->gets('symptom',10);
			$data_w_hospital = $this->Minfo_model->gets('hospital',10);
			$data_w_medicine = $this->Minfo_model->gets('medicine',10);
			$data_w = array('medical'=>$data_w_medical, 'symptom'=>$data_w_symptom, 'hospital'=>$data_w_hospital, 'medicine'=>$data_w_medicine);
			

			//댓글 보안을 위해 한번더 설정함
			if(!isset($data_w_read[0]['read_id'])) {
				echo "<script>alert('존재하지 않는 게시글입니다..');</script>";
				echo "<script>window.location = '/board/{$this->Minfo_model->call_function_name()}/list';</script>";
				return 0;
			} else {
				//댓글도 불러옴
				$data_c = $this->Minfo_model->download_comment($num);
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
				$this->load->view('MOBILE/minfo/header_minfo');
				$this->load->view('MOBILE/menu/top_menu');
					$this->load->view('MOBILE/minfo/medic_read_page', array("data_w_read"=>$data_w_read, "data_c"=>$data_c, "data_w"=>$data_w, "data_w_info"=> $data_w_info, "function_name" => $this->Minfo_model->call_function_name(), "check_gallery" => 0, "sblst_info"=> $sblst_info));
				$this->load->view('MOBILE/minfo/footer_minfo');
				$this->load->view('MOBILE/end_page');

				return 0;
			}


			$this->load->view('PC/start_page');
			$this->load->view('PC/minfo/header_minfo');
			$this->load->view('PC/menu/top_menu');
				$this->load->view('PC/minfo/medic_read_page', array("data_w_read"=>$data_w_read, "data_c"=>$data_c, "data_w"=>$data_w, "data_w_info"=> $data_w_info, "function_name" => $this->Minfo_model->call_function_name(), "check_gallery" => 0, "sblst_info"=> $sblst_info));
			$this->load->view('PC/minfo/footer_minfo');
			$this->load->view('PC/end_page');


		} else if ($purpose == 'write') {
			if(!$this->access_user() ) {
				echo "<script>alert('잘못된 접근입니다.');</script>";
				echo "<script>window.location = '/main';</script>";
				return 0;
			}

			if($this->is_mobile()) {
				$this->load->view('MOBILE/start_page');
				$this->load->view('MOBILE/board/header_board_page');
				$this->load->view('MOBILE/menu/top_menu');
					$this->load->view('MOBILE/board/write_editor/editor.php', array("function_name" => 'minfo/'.$this->Minfo_model->call_function_name(),"class_name" => $this->class_name));
				$this->load->view('MOBILE/board/footer_board_page');
				$this->load->view('MOBILE/end_page');

				return 0;
			}

			//쓰기부분
			$this->load->view('PC/start_page');
			$this->load->view('PC/board/header_board_page');
			$this->load->view('PC/menu/top_menu');

			$this->load->view('PC/board/write_page');
			$this->load->view('PC/board/write_editor/editor_header_1', array("function_name" => 'minfo/'.$this->Minfo_model->call_function_name()));
			$this->load->view('PC/board/write_editor/toolber_2');
			$this->load->view('PC/board/write_editor/editor_main_3');
			$this->load->view('PC/board/write_editor/fileupload_4');
			$this->load->view('PC/board/write_editor/editor_script_5', array("class_name" => $this->class_name)); //이미지 업로드를 위함
			$this->load->view('PC/board/footer_board_page');
			$this->load->view('PC/end_page');
		} else if ($purpose == 'uploadwrite')  {
			if(!$this->access_user() ) {
				echo "<script>alert('잘못된 접근입니다.');</script>";
				echo "<script>window.location = '/main';</script>";
				return 0;
			}

			//유저 글 업로드
			$this->load->model('Minfo_model');
			$this->Minfo_model->upload_write($this->Minfo_model->call_function_name());
		} else if ($purpose == 'modify') {
			if(!$this->access_user() ) {
				echo "<script>alert('잘못된 접근입니다.');</script>";
				echo "<script>window.location = '/main';</script>";
				return 0;
			}

			$this->load->model('Minfo_model');
			$data_w = $this->Minfo_model->download_write($num, true);


			if($this->is_mobile()) {
				$this->load->view('MOBILE/start_page');
				$this->load->view('MOBILE/board/header_board_page');
				$this->load->view('MOBILE/menu/top_menu');
					$this->load->view('MOBILE/board/write_editor/editor.php', array("data_w"=> $data_w, "function_name" => 'minfo/'.$this->Minfo_model->call_function_name(),"class_name" => $this->class_name));
				$this->load->view('MOBILE/board/footer_board_page');
				$this->load->view('MOBILE/end_page');
				return 0;
			}

			$this->load->view('PC/start_page');
			$this->load->view('PC/board/header_board_page');	
			$this->load->view('PC/menu/top_menu');
				$this->load->view('PC/board/write_page', array("function_name" => $this->Minfo_model->call_function_name()));
				$this->load->view('PC/board/write_editor/editor_header_1_for_modify', array("data_w"=> $data_w, "function_name" => 'minfo/'.$this->Minfo_model->call_function_name()));
				$this->load->view('PC/board/write_editor/toolber_2');
				$this->load->view('PC/board/write_editor/editor_main_3');
				$this->load->view('PC/board/write_editor/fileupload_4');
				$this->load->view('PC/board/write_editor/editor_script_5', array("class_name" => $this->class_name));
				$this->load->view('PC/board/write_editor/editor_script_6_for_modify', array("data_w"=> $data_w));
			$this->load->view('PC/board/footer_board_page');
			$this->load->view('PC/end_page');
		} else if ($purpose == 'modifywrite') {
			if(!$this->access_user() ) {
				echo "<script>alert('잘못된 접근입니다.');</script>";
				echo "<script>window.location = ''/main'';</script>";
				return 0;
			}

			$this->load->model('Minfo_model');
			$this->Minfo_model->modify_write();
		} else if ($purpose == 'deletewrite') {
			if(!$this->access_user() ) {
				echo "<script>alert('잘못된 접근입니다.');</script>";
				echo "<script>window.location = ''/main';</script>";
				return 0;
			}
			
			$this->load->model('Minfo_model');
			$this->Minfo_model->delete_write();
		} 
		//else if ($purpose == 'latelywrite') {
		//	$this->load->model('Minfo_model');
		//	$this->Minfo_model->lately_write();	
		//}  


		/*이 아래부터는 댓글 관련*/
		  else if ($purpose == 'uploadcomment') {
			$this->load->model('Minfo_model');
			$this->Minfo_model->upload_comment();
		} else if ($purpose == 'uploadanswercomment') {
			$this->load->model('Minfo_model');
			$this->Minfo_model->upload_answer_comment();
		} else if ($purpose == 'modifycomment') {
			$this->load->model('Minfo_model');
			$this->Minfo_model->modify_comment();
		} else if ($purpose == 'deletecomment') {
			$this->load->model('Minfo_model');
			$this->Minfo_model->delete_comment($num);
		} else if ($purpose == 'latelycomment') {
			$this->load->model('Minfo_model');
			$this->Minfo_model->lately_comment();
		} else {
			echo "<script>alert('잘못된 주소입니다.');</script>";
			echo "<script>window.location = '/board/{$function_name}/list';</script>";
		}
	

	}
	
	public function getlist ($function_name) {
		if($function_name !== 'medic') {
			return 0;
		} else if (!isset($_POST['write_type']) || ($_POST['write_type'] !== 'medical' && $_POST['write_type'] !== 'symptom' && $_POST['write_type'] !== 'hospital' && $_POST['write_type'] !== 'medicine' )     ) {
			return 0;
		}

		//글 리스트 불러옴
		$this->load->model('Minfo_model');
		$result = $this->Minfo_model->gets($_POST['write_type'], 10);
		$result = json_encode($result, JSON_PRETTY_PRINT);
		print_r($result);
	}
}

