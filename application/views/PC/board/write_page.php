
<!-- 에디터 관련 -->
<link rel="stylesheet" type="text/css" href="/static/lib/daumeditor/css/editor.css?vss=170323" charset="utf-8">

<div id = "brdWrt_main">
<div id = "brdWrt_wapper">
	<div id = "brdWrt_Dplec_1">

	
<?php 
	//로그인 체크함수
	echo "<script>";
	$check_loggin = $this->session->userdata('logged_in');
	if($check_loggin ) {
		echo "check_loggin = ".$check_loggin.";";
	} else {
		echo "check_loggin = 0 ;";
	}

	echo "</script>";
?>



<!-- 에디터 관련-->
   <script src="/static/lib/daumeditor/js/editor_loader.js?vss=170323&environment=production" type="text/javascript" charset="utf-8"></script>

