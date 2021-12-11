<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Main_model extends CI_model { 
	function __construct(){ 
		parent::__construct(); 
		$this->db_collect_func = $this->load->database('collect_func', TRUE);
		date_default_timezone_set("Asia/Seoul");
	}

}