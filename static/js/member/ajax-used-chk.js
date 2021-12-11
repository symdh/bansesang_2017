
function used_chk_email(email) {
	//alert(nickname);
	var result;
	var email = {"email":email};
	email[$('#ci_t').attr('name')] = $('#ci_t').val();

	$.ajax({
		type: 'POST',
		url: '/member/checkemail',
		dataType: "html",
		async : false,
		processData: true,
		contentType: 'application/x-www-form-urlencoded',
		data:email,
		success: function(data){
			result = return_detox(data);
		}, error:function(request,status,error){
			result = return_detox(-1);
		}
	});

	return result;
}


function used_chk_nickname(nickname) {
	//alert(nickname);
	var result;
	var nickname = {"nickname":nickname};
	nickname[$('#ci_t').attr('name')] = $('#ci_t').val();

	$.ajax({
		type: 'POST',
		url: '/member/checknickname',
		dataType: "html",
		async : false,
		processData: true,
		contentType: 'application/x-www-form-urlencoded',
		data:nickname,
		success: function(data){
			result = return_detox(data);
		}, error:function(request,status,error){
			result = return_detox(-1);
		}
	});

	return result;
}