
function fn_previewImg(input,preImg) {
	// param : input - 파일업로드 input 객체 change 이벤트에서 this 로 받아온다
	//             preImg - 미리보기 이미지를 보여줄 img 태그  ID 
	if ($(input).val()!="") {
		//확장자 확인
		var ext = $(input).val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg','jpe']) == -1) {
			alert('gif,png,jpg,jpeg,jpe 파일만 업로드 할수 있습니다.');
			return;
		}

		if ( window.FileReader ) {
			/*IE 9 이상에서는 FileReader  이용*/

			// 읽기
			var reader = new FileReader();
			reader.readAsDataURL(input.files[0]);

			//로드 한 후
			reader.onload = function  () {
				//로컬 이미지를 보여주기
				// document.querySelector('#member_J_preview').src = reader.result;

				//썸네일 이미지 생성
				var tempImage = new Image(); //drawImage 메서드에 넣기 위해 이미지 객체화
				tempImage.src = reader.result; //data-uri를 이미지 객체에 주입
				tempImage.onload = function () {
					//리사이즈를 위해 캔버스 객체 생성
					var canvas = document.createElement('canvas');
					var canvasContext = canvas.getContext("2d");

					//캔버스 크기 설정
					canvas.width = 180; 
					canvas.height = 135; 

					//이미지 비율 축소
					var preview_w = this.width;
					var preview_h = this.height;
					var rate =  canvas.width/canvas.height;

					//원본 size를 썸네일화 하기 위해 비율을 맞춤
					//작은쪽 기준으로 가져와야됨
					if( preview_w > preview_h*rate ) {  //검증
						//아래꺼랑 반대 개념
						preview_w = preview_h*rate;

					} else if ( preview_w < preview_h*rate) {   //검증
						//가져올 그림의 세로값이 더 클경우 가로값기준으로 세로값을 짤라서 가져옴
						preview_h = preview_w/rate;

					} else if ( preview_w == preview_h*rate) {  
						//비율이 같을 경우 그대로 진행함
					} else {
						alert('이미지 미리보기 에러발생. 문의바람');
						return 0;
					}        


					if( preview_w >=  canvas.width && preview_h >= canvas.height) {     //할거 없음.

					} //여기서 부터는 짜르거나 그냥 가져옴 
					else if( preview_w <=  canvas.width && preview_h <= canvas.height) {   
						//모두 작을 때는 그냥 그대로 가도됨
						resize_width = preview_w;
						resize_height = preview_h;

					} else if( preview_w <  canvas.width && preview_h >= canvas.height) {  
						//세로만 클때는 세로만 짤라서 가져옴 
						preview_h = canvas.height;

						resize_width = preview_w; //가로 크기는 작으므로 resize 값은 그림값
						resize_height = canvas.height;
					} else if( preview_w >=  canvas.width && preview_h < canvas.height) {    
						//가로만 클때는 가로만 짤라서 가져옴 
						preview_w =  canvas.width;

						resize_width =  canvas.width; 
						resize_height = preview_h; //세로 크기는 작으므로 resize 값은 그림값
					} else {
						alert('이미지 미리보기 에러발생. 문의바람');
						return 0;
					}

					//이미지를 캔버스에 그리기
					// drawImage(대상,기준,기준,캔버스에들어갈 이미지크기W, H,기준,기준,캔버스크기,캔버스크기)
					canvasContext.drawImage(this, 0, 0, preview_w, preview_h,0,0,canvas.width , canvas.height);

					//캔버스에 그린 이미지를 다시 data-uri 형태로 변환
					var dataURI = canvas.toDataURL("image/jpeg");

					//썸네일 이미지 보여주기
					document.querySelector('#member_J_preview').src = dataURI;
				};
			};
		} else {
			/* IE8 전용 이미지 미리보기 */ 
			$('#'+preImg).attr('src', '/static/img/reject-browser-msg.jpg');  

		}
	}
}


//file 양식으로 이미지를 선택(값이 변경) 되었을때 처리하는 코드
$("input[name=user_image]").change(function(){
	//alert(this.value); //선택한 이미지 경로 표시
	fn_previewImg(this, 'member_J_preview');
});

