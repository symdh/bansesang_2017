////////////////////////////////////
//해당 삭제 or 추가시 서버에서도 수정해야됨
////////////////////////////////////
function return_detox (num) { //리턴값 해독
	switch (num) {
		case '-1':
			return "서버 연결 에러. 다시 시도 해주세요.";
		break;
		case '0':
			return '1';
		break;
		case '1':
			return "잘못된 접근입니다.";
		break;
		case '2':
			return "잘못된 형식입니다.";
		break;
		case '11':
			return "이미 사용중입니다.";
		break;
		case '12':
			return "인증 대기중입니다.";
		break;
		default:
			return "에러. 관리자에게 문의하세요.";
	}
}	
	   	
function watch_id (em, used_chk, result_location, result_method, alert_true_msg) { //임시용 //기본값(null,0,null,1,1) 
	//input name 필수 (em = value || this) //예시는 위에 pwd에서
	//used_chk - 서버단 중복체크 

	if (used_chk == undefined) used_chk = 0;
	if (result_method == undefined) result_method = 1;
	if (alert_true_msg == undefined) alert_true_msg = 1;

	if(em.name == undefined) 
		var name = em;
	else 
		var name = em.name;

	var email = $('input[name='+name+']');

	//내용이 비었을때
	if(email.val()==""){
		watch_alert(result_location, result_method, "아이디를 입력해주세요","red");
		return false;
	}

	var check_space = email.val().match(' ');
	if (check_space != null){
		watch_alert(result_location, result_method, "아이디에 빈칸(스페이스)는 사용할 수 없습니다.","red");
		return false;
	}
	if( email.val().length < 6 ) {
		watch_alert(result_location, result_method, "아이디는 6자 이상입니다.","red");
		return false;
	} else if( email.val().length > 20 ) {
		watch_alert(result_location, result_method, "아이디는 20자 이하만 써주세요","red");
		return false;
	} 


	//특수문자를 사용했을때 && 한글을 사용했을때
	var special = ' ~!#$%^&*()-_=+|₩₩{}[];:"₩"<>,?₩/@.'; 
	for(var i = 0 ; i < email.val().length; i++){ //한글과 특수문자를 쓰지 못하게 하는 구문
		var email_check = email.val().charAt(i); //이메일 입력폼에 값을 하나씩 가져온다.
		
		if(email_check >= "ㅏ" && email_check <= "히" || email_check >="ㄱ" && email_check <="ㅎ"){//모든 한글을 확인하는 구문
			watch_alert(result_location, result_method, "아이디에는 한글이 안됩니다.","red");
			return false;
		}
		
		for(var j=0; j<special.length;j++) { //특수문자를 확인하는 구문.미리 등록된 특수문자를 하나씩 가져온다.
			if(email_check == special.charAt(j)) { //이메일 입력폼의 문자를 하나씩 가져와서 미리 등록된 특수문자를 비교하여 같다면 특수문자임.
				watch_alert(result_location, result_method, "아이디에는 특수 문자가 안됩니다.","red");
				return false;
			}
		}
	}

	if(used_chk) { //사용중 체크
		var result = used_chk_email (email.val());
		if(result != '1'){
			watch_alert(result_location, result_method, result,"red");
			return false;
		}
	}

	if(alert_true_msg)
		watch_alert(result_location, result_method, "좋습니다.","blue");
	return true;
}


function watch_alert (alert_location, method , alert_str, str_color) { //기본값 (null, 1, '', black)
	//alert_location - selecter[name=value] 형태로
	//method = 1 (본인 focus), 10 (다른곳에 문자 보여주는 방식) 
	//                 5 (alert 방식) 6(alert+focus)
	//alert_str - method 2일때 alert_str 보여줄 문자
	//watch_alert("span[name=alert_pwd]", 10, "비밀번호가 일치하지 않습니다.","red");
	if (method == undefined) method = 1;
	if (alert_str== undefined) alert_str = '';
	if (str_color== undefined) str_color = 'black';


	var location =	$(alert_location);
	switch (method) {
		case 1:
			location.focus();
			return true;
		break;
		case 5:
			alert(alert_str);
			return true;
		break;
		case 6:
			alert(alert_str);
			location.focus();
			return true;
		break
		case 10:
			location.css('color',str_color);
			location.text(alert_str);
			return true;
		break;
		default:
			return false;
	}
}

function watch_passwd (pwd, overlap_chk , result_location, result_method , alert_true_msg, min_length) { //기본값(null,0,null,1,1) //주의!!!!! overlap_chk = 1일경우 value2값 필요
	//input name               // overlap_chk = 1일시  name = value2 필요
	//(pwd = value || this)                    //스크립트 또한 2에 설정 해야됨								
	//alert_true_msg - 마지막 true 메세지 관련
	//watch_passwd('user_passwd2',1, 'span[name=alert_pwd]', 10)
	//watch_passwd(this,1, 'span[name=alert_pwd]', 5)

	if (overlap_chk== undefined) overlap_chk = 0;
	if (result_method== undefined) result_method = 1;
	if (alert_true_msg== undefined) alert_true_msg = 1;
	if (min_length== undefined) min_length = 6;


	if(pwd.name == undefined) 
		var name = pwd;
	else 
		var name = pwd.name;

	var passwd_2 = $('input[name='+name+']');
	if(overlap_chk) {
		var name = name.slice(0,-1);
		var passwd_1 = $('input[name='+name+']');
	} else {
		var passwd_1 = passwd_2;
	}	//passwd_1이 처음, passwd_2는 확인용으로 결과 나옴
	
	//비밀번호 빈칸(띄어쓰기), 길이, 일치여부
	//숫자, 영문자를 혼용하기 위한 정규식 표현
	//var check_passwd = /[a-zA-Z][1-9]|[1-9][a-zA-Z]/;
		//띄어쓰기 검사
	var check_space = passwd_1.val().match(' ');
	if (check_space != null){
		watch_alert(result_location, result_method, "비밀번호에 빈칸(스페이스)는 사용할 수 없습니다.","red");
		return false;
	}
		//입력 된것 검사
	if(passwd_1.val()==""){ 
		watch_alert(result_location, result_method, "비밀번호를 입력하세요","red");
		return false;
	} else if( passwd_1.val().length < min_length ) {
		watch_alert(result_location, result_method, "비밀번호는 "+min_length+"자 이상입니다.","red");
		return false;
	} else if( passwd_1.val().length > 20 ) {
		watch_alert(result_location, result_method, "비밀번호는 20자 이하만 써주세요","red");
		return false;
	} 
		//일치여부 검사
	if(overlap_chk) {
		if(passwd_2.val()==""){
			watch_alert(result_location, result_method, "비밀번호를 입력하세요","red");
			return false;
		} else if(passwd_1.val() != passwd_2.val()) { 
			watch_alert(result_location, result_method, "비밀번호가 일치하지 않습니다.","red");
			return false;
		}
	}
	//영문, 숫자 규칙검사 
	/*
	if (!result_passwd){
		alert("최소 한자이상의 영문자와 숫자를 입력해주세요.");
		passwd.focus();
		return false;
	} 
	*/


	if(alert_true_msg)
		watch_alert(result_location, result_method, "비밀번호가 일치합니다.","blue");
	return true;
}

function watch_email (em, used_chk, result_location, result_method, alert_true_msg) { //기본값(null,0,null,1,1) 
	//input name 필수 (em = value || this) //예시는 위에 pwd에서
	//used_chk - 서버단 중복체크 

	if (used_chk == undefined) used_chk = 0;
	if (result_method == undefined) result_method = 1;
	if (alert_true_msg == undefined) alert_true_msg = 1;

	if(em.name == undefined) 
		var name = em;
	else 
		var name = em.name;

	var email = $('input[name='+name+']');

	//내용이 비었을때
	if(email.val()==""){
		watch_alert(result_location, result_method, "이메일을 입력하세요.","red");
		return false;
	}

	//@ 또는 . 을 여러번 썼거나 사용하지 않았을때, 또는 중간에 내용이 비었을때
	var result = email.val().split("@"); 
	if( result.length !=2 ) {
		watch_alert(result_location, result_method, "올바른 이메일을 입력하세요.","red");
		return false;
	} else if( result[0].length < 4  ) {
		watch_alert(result_location, result_method, "올바른 이메일을 입력하세요.","red");
		return false;
	} else if ( result[1].length < 3  ){
		watch_alert(result_location, result_method, "올바른 이메일을 입력하세요.","red");
		return false;
	} 
	var result_2 = result[1].split("."); 
	if ( result_2.length != 2 ){
		watch_alert(result_location, result_method, "올바른 이메일을 입력하세요.","red");
		return false;
	} else if ( result_2[1].length < 2 ){
		watch_alert(result_location, result_method, "올바른 이메일을 입력하세요.","red");
		return false;
	}

	//'@' 와 '.'  외에 특수문자를 사용했을때 && 한글을 사용했을때
	var special = ' ~!#$%^&*()-_=+|₩₩{}[];:"₩"<>,?₩/'; 
	for(var i = 0 ; i < email.val().length; i++){ //한글과 특수문자를 쓰지 못하게 하는 구문
		var email_check = email.val().charAt(i); //이메일 입력폼에 값을 하나씩 가져온다.
		
		if(email_check >= "ㅏ" && email_check <= "히" || email_check >="ㄱ" && email_check <="ㅎ"){//모든 한글을 확인하는 구문
			watch_alert(result_location, result_method, "이메일에는 한글이 안됩니다.","red");
			return false;
		}
		
		for(var j=0; j<special.length;j++) { //특수문자를 확인하는 구문.미리 등록된 특수문자를 하나씩 가져온다.
			if(email_check == special.charAt(j)) { //이메일 입력폼의 문자를 하나씩 가져와서 미리 등록된 특수문자를 비교하여 같다면 특수문자임.
				watch_alert(result_location, result_method, "이메일에는 특수 문자가 안됩니다.","red");
				return false;
			}
		}
	}

	if(used_chk) { //사용중 체크
		var result = used_chk_email (email.val());
		if(result != '1'){
			watch_alert(result_location, result_method, result,"red");
			return false;
		}
	}

	if(alert_true_msg)
		watch_alert(result_location, result_method, "좋습니다.","blue");
	return true;
}

function watch_name (nm, result_location, result_method, alert_true_msg) { //기본값 (null, null, 1, 1)
	//input name 필수 (nm = value || this) //예시는 위에 pwd에서
	//used_chk - 서버단 중복체크 

	if (result_method== undefined) result_method = 1;
	if (alert_true_msg== undefined) alert_true_msg = 1;


	if(nm.name == undefined) 
		var name = nm;
	else 
		var name = nm.name;
	var name = $('input[name='+name+']');

	var check_name_1 = /^[가-힣]{2,4}/;
	var check_name_2 = /[^가-힣]/;

	//내용이 비었을때
	if(name.val()==""){
		watch_alert(result_location, result_method, "이름을 입력하세요.","red");
		return false;
	}

	//이름의 길이를 제한
	var length_name = name.val().length;
	if (name.val().length < 2) {
		watch_alert(result_location, result_method, "이름은 2자이상 입니다.","red");
		return false;
	} else if (name.val().length > 4) {
		watch_alert(result_location, result_method, "이름은 4자이하 입니다.","red");
		return false;
	}
	
	//한글로만 작성 검사. 정규식으로 함
	if(!check_name_1.test(name.val()) ){
		watch_alert(result_location, result_method, "이름은 한글로 정확히 써주세요.","red");
		return false;
	} else if (check_name_2.test(name.val()) ) {
		watch_alert(result_location, result_method, "이름은 한글로 정확히 써주세요.","red");
		return false;
	} 

	if(alert_true_msg)
		watch_alert(result_location, result_method, "이름 규칙이 맞습니다!","blue");
	return true;
}

function watch_phone (phn, result_location, result_method, alert_true_msg) { //기본값 (null, null, 1, 1)
	//input name 필수 (phn = value || this) //예시는 위에 pwd에서
	
	if (result_method== undefined) result_method = 1;
	if (alert_true_msg== undefined) alert_true_msg = 1;


	if(phn.name == undefined) 
		var name = phn;
	else 
		var name = phn.name;
	var phone= $('input[name='+name+']');

	if(phone.val()==""){
		watch_alert(result_location, result_method, "핸드폰 번호를 입력하세요.","red");
		return false;
	}

	//특수문자 - 제거
	var value = phone.val();
	value = value.replace(/-/gi, "");
	phone.val(value);


	//특수문자 - 검사
	// var check_phone_sp = phone.val().match('-');
	// if (check_phone_sp != null){
	// 	watch_alert(result_location, result_method, "핸드폰번호는 - 없이 입력해 주세요.","red");
	// 	return false;
	// }

	//전화번호가 11자리가 아니면 경고
	if(phone.val().length != 11 ){ 
		watch_alert(result_location, result_method, "핸드폰번호를 정확히 입력해주세요.","red");
		return false;
	}
	//전화번호가 숫자가 아니면 경고
	var result_phone = phone.val().match(/^[0-9]{11}/);
	if (phone.val() != result_phone){
		watch_alert(result_location, result_method, "핸드폰번호는 숫자만 입력해주세요.","red");
		return false;
	}

	if(alert_true_msg)
		watch_alert(result_location, result_method, "정확한 핸드폰번호 입니다.","blue");
	return true;
}

function watch_nickname (nm, used_chk, result_location, result_method, alert_true_msg) { //기본값(null,0,null,1,1) 
	//input name 필수 (nm = value || this) //예시는 위에 pwd에서
	//used_chk 0/1 일때, alert_true_msg 가 다름

	if (used_chk== undefined) used_chk = 0;
	if (result_method== undefined) result_method = 1;
	if (alert_true_msg== undefined) alert_true_msg = 1;


	if(nm.name == undefined) 
		var name = nm;
	else 
		var name = nm.name;
	var nickname = $('input[name='+name+']');

	if (nickname.val().length < 3) {
		watch_alert(result_location, result_method, "닉네임은 3자 이상입니다.","red");
		return false;
	} else if (nickname.val().length > 12 ) {
		watch_alert(result_location, result_method, "닉네임은 12자 이하입니다.","red");
		return false;
	}
	//닉네임의 길이를 (한글, 영어 따로) 측정한다.
	var totalByte = 0;
	for (var i = 0; i < nickname.val().length; i++) {
		oneChar = nickname.val().charAt(i);
		if (escape(oneChar).length > 4) {
			totalByte += 2;
		} else {
			totalByte++;
		}
	}
	if (totalByte > 12) {
		watch_alert(result_location, result_method, "닉네임이 너무 깁니다.","red");
		return false;
	}
	//특수문자 제외
	var special = ' ~!#$%^&*()-_=+|₩₩{}[];:"₩"<>,?₩/@.'; 
	for(var i = 0 ; i < nickname.val().length; i++){//한글과 특수문자를 쓰지 못하게 하는 구문
		var check_nickname = nickname.val().charAt(i); //이메일 입력폼에 값을 하나씩 가져온다.
		
		for(var j=0; j<special.length;j++) { //특수문자를 확인하는 구문.미리 등록된 특수문자를 하나씩 가져온다.
			if(check_nickname == special.charAt(j)) { //이메일 입력폼의 문자를 하나씩 가져와서 미리 등록된 특수문자를 비교하여 같다면 특수문자임.
				watch_alert(result_location, result_method, "닉네임에는 특수 문자가 안됩니다.","red");
				return false;
			}
		}
	}

	if(used_chk) { //사용중 체크
		var result = used_chk_nickname (nickname.val());
		if(result != '1'){
			watch_alert(result_location, result_method, result,"red");
			return false;
		}
	}

	if(alert_true_msg)
		watch_alert(result_location, result_method, "정확한 닉네임 입니다.","blue");
	return true;
}

function watch_checkbox (gd, result_location, result_method, alert_true_msg) {  //기본값 (null, null, 1, 1)
	//input name 필수 (gd = value || this) //예시는 위에 pwd에서

	if (result_method== undefined) result_method = 1;
	if (alert_true_msg== undefined) alert_true_msg = 1;



	if(gd.name == undefined) 
		var name = gd;
	else 
		var name = gd.name;

	var check_checkbox = $('input[name='+name+']');
	for (var i = 0; i < check_checkbox.length; i++ ) {
		if ( check_checkbox[i].checked )
			break;
		else if (i == check_checkbox.length -1 ) {
			watch_alert(result_location, result_method, "체크박스를 체크해주세요.","red");
			return false;
		}
	}

	if(alert_true_msg)
		watch_alert(result_location, result_method, "정확합니다.","blue");
	return true;
}

function checkbox_only(chk) { //체크박스 중복체크 방지
	//input 태그, name  존재
   if(chk.checked == true) { //클릭했을때 기준이 checked임
      	$("input[name="+chk.name+"]").attr('checked', false);
      	chk.checked = true;
   } else {
   		chk.checked = false;
   }
}