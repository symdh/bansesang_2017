<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Norm_sys extends CI_model { //norm system 형식 표준 시스템

	public $current_time;
	public $current_day;
		
	function __construct(){ 
		parent::__construct(); 
		// $this->db_minfo = $this->load->database('minfo', TRUE);
		date_default_timezone_set("Asia/Seoul");
		
		$this->current_time = strtotime("now",time());
		$this->current_day = strtotime(date("Y-m-d",time()));
	}

	public function norm_time ($time) { //형식은 strtotime
		if($this->current_day < $time)
			return '오늘 '.date("H:i",$time);
		else 
			return date("y-m-d",$time);
	}

}

// $this->load->model('Norm_sys');
// $this->Norm_sys->norm_time(strtotime($result[$key]['date']));