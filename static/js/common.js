   
// placeholder 실행    
(function ($) {
	// window.stopPropagation();
	$(document).ready(function () {
		$("input").placeholder();
	});
})(jQuery);

////////////////////////////////////////////////////////////////////////////////
///////////////////   control-cookie    ///////////////////////////////
////////////////////////////////////////////////////////////////////////////////
function setCookie(cookieName, value, exdays, path) {
	//setCookie("userInputId", 'userid', 7,'/member/login');
	//이름/값/몇일/경로

	var exdate = new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var cookieValue = escape(value) + ((exdays==null) ? "" : "; expires=" + exdate.toGMTString()) + "; path="+path+" ;" ;
	document.cookie = cookieName + "=" + cookieValue;
}

function deleteCookie(cookieName, path){    
	//deleteCookie("userInputId",'/member/login');
	//이름/경로

	var expireDate = new Date();
	expireDate.setDate(expireDate.getDate() - 1);
	document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString() + "; path="+path+" ;" ;
}

function getCookie(cookieName) {
	cookieName = cookieName + '=';
	var cookieData = document.cookie;
	var start = cookieData.indexOf(cookieName);
	var cookieValue = '';
	if(start != -1){
		start += cookieName.length;
		var end = cookieData.indexOf(';', start);
		if(end == -1)end = cookieData.length;
		cookieValue = cookieData.substring(start, end);
	}
	return unescape(cookieValue);
}



var getParameters = function (paramName) { //get 변수 가져오기.
	var returnValue; // 리턴값을 위한 변수 선언
	var url = location.href; // 현재 URL 가져오기

		// get 파라미터 값을 가져올 수 있는 ? 를 기점으로 slice 한 후 split 으로 나눔
	var parameters = (url.slice(url.indexOf('?') + 1, url.length)).split('&');
		// 나누어진 값의 비교를 통해 paramName 으로 요청된 데이터의 값만 return
	for (var i = 0; i < parameters.length; i++) {
		var varName = parameters[i].split('=')[0];
		if (varName.toUpperCase() == paramName.toUpperCase()) {
			returnValue = parameters[i].split('=')[1];
			return decodeURIComponent(returnValue);
		}
	}

	//값이 없으면 리턴0
	returnValue = '0'
	return 0;	

	//get 없으면 input에 넣음.. 
	// var search_query = getParameters('query');
	// if( search_query != '0' ) {
	// 	$('input[name=query]').val(search_query);
	// }
};
