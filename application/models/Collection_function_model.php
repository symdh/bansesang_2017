<?php  defined('BASEPATH') OR exit('No direct script access allowed');


class Collection_function_model extends CI_model { 
	function __construct(){ 
		parent::__construct(); 
		$this->db_collect_func = $this->load->database('collect_func', TRUE);
		date_default_timezone_set("Asia/Seoul");
	}

	//검색
	public function search_main ($show_no = 10) { //show_no 몇개를 보여줄지 체크
		if(isset($_GET['query']) ) {
			$_GET['query'] = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $_GET['query']);
		} else {
			$result[0]['pointer'] = 'none';
			return $result;
		}
		$search_query = $_GET['query'];
		if(mb_strlen($search_query, 'UTF-8') < 2 ) { // 1글자 이하면 검색결과 없음.
			$result[0]['pointer'] = 'none';
			return $result;
		}


		//기본 연결
		require_once('./static/include/view/search/sphinxapi.php');
		$s = new SphinxClient;
		$s->setServer("127.0.0.1", 9312); // NOT "localhost" under Windows 7!
		// $s->setMatchMode(SPH_MATCH_EXTENDED2);
		$s->SetLimits(0, 200);
		
		

		//검색 결과 갯수 파악
		$result['count']['pointer'] = '0'; //result 초기화
		$s->SetSortMode(SPH_SORT_ATTR_DESC,'group_id');
		$s->SetGroupBy('group_id',SPH_GROUPBY_ATTR, '@group desc'); //갯수를 새주는 
		$result_num = $s->Query($s->EscapeString($search_query));	 //특수문자 검색 에러생길시 가장 먼저 수정해볼것
		if ($result_num['total'] > 0) {
			foreach ($result_num['matches'] as $id => $otherStuff) {
				// echo $result_num['total']; //총 그룹 갯수
				// echo $result_num['matches'][$id]['attrs']['group_id']; //현재 그룹 번호
				// echo $result_num['matches'][$id]['attrs']['@count'];	// 각 그룹의 검색 갯수
				$sum_arr[$result_num['matches'][$id]['attrs']['group_id']] = $result_num['matches'][$id]['attrs']['@count'];
			
				switch($result_num['matches'][$id]['attrs']['group_id']) {
					case 1:
						$result['count']['free'] =  $result_num['matches'][$id]['attrs']['@count'];
					break;
					case 2:
						$result['count']['photo'] =  $result_num['matches'][$id]['attrs']['@count'];
					break;
					case 3:
						$result['count']['qaa'] =  $result_num['matches'][$id]['attrs']['@count'];
					break;
					case 4:
						$result['count']['medic'] =  $result_num['matches'][$id]['attrs']['@count'];
					break;
				}
			}
			// $sum_arr['total'] = $result_num['total']; //foreach사용하면 필요 없어짐
			unset($result_num);	//다시 사용하기위한 리셋
		} else {
			//결과값 없으면 그냥 리턴
			unset($result_num);
			$result_num[0]['pointer'] = 'none'; //'No results found'
			return $result_num;
		}



		//검색 결과를 가공 (결과 없을 경우는 위에서 처리됨)
		$s->SetGroupBy('',SPH_GROUPBY_ATTR, '@group desc'); //초기화
		$result_search = $s->Query($s->EscapeString($search_query));	 //특수문자 검색 에러생길시 가장 먼저 수정해볼것
		$result['count']['total'] = $result_search['total'];
		//그룹은 에러가 없는이상 동일해야 정상
		// echo $result_search['matches'][$id]['attrs']['group_id']; //group_id 출력
		foreach ($sum_arr as $key_group_id => $value_count ) {
			$recent_no = 0;	//진행 갯수 파악

			//show_no 검증 (count와 비교)
			if($value_count <= $show_no)
				$limit_no = $value_count;
			else 
				$limit_no = $show_no;

			foreach($result_search['matches'] as $key_result => $value_result) {
				// group id 검증
				if($key_group_id != $value_result['attrs']['group_id'] )
					continue;
				if($recent_no == $limit_no) { //원하는 갯수만큼 나왔으면 종료
					$result_search['matches']  = array_slice($result_search['matches'] , $value_count + 1);
					break;
				}
				//쿼리 결과에 넣음 (그냥넣으면 배열로 들어가서 foreach 사용)
						//내부값: $key_result		//외부값 없음
				$key_result = $this->db->escape($key_result);
				$result_query =  $this->db_collect_func->query("SELECT * from search_alldb  where id = $key_result ")->result_array();
				foreach ($result_query as $key_query => $value_query)
					array_push ($result,  $value_query); 
				
				// echo $key_group_id;
				$recent_no++;
			}
		}
		// print_r($result_search);
		// print_r($result);

		//최신순으로 결과값 줌
		$result = array_reverse($result);

		return $result;
	}

	public function get_main_info () {
				//내부값: 없음    //외부값: 없음
		$result = $this->db_collect_func->query("SELECT * from data_for_main")->result_array();
		return $result;
	}

	public function get_sblst_info($position = 0) {
		if(!$position)
					//내부값: 없음    //외부값: 없음
			$result = $this->db_collect_func->query("SELECT * from data_sblst")->result_array();
		else  {
			$position = $this->db->escape($position);
					//내부값: 없음    //외부값: 다수
			$result = $this->db_collect_func->query("SELECT * from data_sblst WHERE position = {$position}")->result_array();
		}

		return $result;
	}

	public function upload_notice() {
		$this->load->model('Vui_sys');
		$read_id = $this->db->escape($this->Pchk_sys->call_read_id());
		if($read_id === 0) { return 0; }
		$title = $this->db->escape($this->Pchk_sys->call_title());
		if($title ===0) { echo "내용은 2글자 이상입니다."; return 0;}
				//내부값: 없음    //외부값: 다수
		$this->db_collect_func->query("INSERT INTO data_sblst (`position`,`pointer`, `read_id`, `title`) values('csc','notice', {$read_id}, {$title} ) ");

		redirect('/csc/notice');
	}

	public function delete_notice() {
	
		$check_num = $this->db->escape($_POST['check_num']);
				//내부값: 없음    //외부값: 없음
		$this->db_collect_func->query("DELETE from data_sblst where check_num = {$check_num} AND position = 'csc' ");

		redirect('/csc/notice');
	}

	public function update_main_info () { //테스트용

		//수정안임
		// $this->db_collect_func->query("DELETE from data_for_main where pointer = 'free' LIMIT 7"); 
		// $this->db_collect_func->query("DELETE from data_for_main where pointer = 'qaa' LIMIT 7");  
		// $this->db_collect_func->query("DELETE from data_for_main where pointer = 'photo' LIMIT 7");  
		// $this->db_collect_func->query("DELETE from data_for_main where pointer = 'medic' LIMIT 10");  
		// $this->db_collect_func->query("INSERT into data_for_main (`pointer`, `date`, `read_id`, `writed_member`, `title`, `count_comment`) SELECT 'free',`date`, `read_id`, `writed_member`, `title`, `count_comment` from board.free_writed ORDER BY read_id DESC LIMIT 0,7"); 
		// $this->db_collect_func->query("INSERT into data_for_main (`pointer`, `date`, `read_id`, `title`, `count_comment`, `solve_q`) SELECT 'qaa',`date`, `read_id`, `title`, `count_comment`, `solve_q` from board.qaa_writed ORDER BY read_id DESC LIMIT 0,7");  
		// $this->db_collect_func->query("INSERT into data_for_main (`pointer`, `date`, `read_id`, `writed_member`, `title`, `attach_image`) SELECT 'photo',`date`, `read_id`, `writed_member`, `title`, `attach_image` from board.photo_writed ORDER BY read_id DESC LIMIT 0,7"); 
		// $this->db_collect_func->query("INSERT into data_for_main (`pointer`, `date`, `read_id`, `writed_member`, `title`, `count_comment`) SELECT 'medic',`date`, `read_id`, `writed_member`, `title`, `count_comment` from minfo.medic_board ORDER BY read_id DESC LIMIT 0,10"); 


		//pointer의 n개의 내용이 미리 들어가 있어야됨
		//테스트후 mysql event에 저장 시킬것
	
		// $this->db_collect_func->query("INSERT into data_sblst (`position`,`pointer`, `date`, `read_id`, `writed_member`, `title`, `count_comment`) SELECT 'board','free',`date`, `read_id`, `writed_member`, `title`, `count_comment` from board.free_writed LIMIT 0,5");
		// $this->db_collect_func->query("DELETE from data_sblst where pointer = 'free' LIMIT 5 ");

		//게시판 업로드 부분
			//일반 게시판
		// $this->db_collect_func->query("INSERT into data_for_main (`pointer`, `date`, `read_id`, `writed_member`, `title`, `count_comment`) SELECT 'free',`date`, `read_id`, `writed_member`, `title`, `count_comment` from board.free_writed LIMIT 0,5");
		// 		//질답 게시판
		// $this->db_collect_func->query("INSERT into data_for_main (`pointer`, `date`, `read_id`, `title`, `count_comment`, `solve_q`) SELECT 'qaa',`date`, `read_id`, `title`, `count_comment`, `solve_q` from board.qaa_writed LIMIT 0,5");
		// 	//갤러리
		// $this->db_collect_func->query("INSERT into data_for_main (`pointer`, `date`, `read_id`, `writed_member`, `title`, `attach_image`) SELECT 'photo',`date`, `read_id`, `writed_member`, `title`, `attach_image` from board.photo_writed LIMIT 0,5");

		// //그냥 삭제 시켜도 순차적으로 삭제됨
		// $this->db_collect_func->query("DELETE from data_for_main where pointer = 'free' LIMIT 5 ");
		// $this->db_collect_func->query("DELETE from data_for_main where pointer = 'qaa' LIMIT 5 ");
		// $this->db_collect_func->query("DELETE from data_for_main where pointer = 'photo' LIMIT 5 ");
		ECHO "완료"; 
	}
}