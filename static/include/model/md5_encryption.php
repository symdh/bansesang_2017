<?php defined('BASEPATH') OR exit('No direct script access allowed');

//암호화
function md5_encryption ($data, $md5_salt1, $md5_salt2) {
	//$md5_salt2는 임시. 필요시 세션에 추가해서 아래 규칙에 추가할것
	$md5_passwd_code = "4!".$data."@3";
	
	//echo $md5_salt;
	$md5_passwd_code = $md5_salt1.$md5_passwd_code;

	$result = md5($md5_passwd_code);
	return $result;
}



