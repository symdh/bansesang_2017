<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?=$this->config->item('title'); ?></title>
<script src="/static/lib/daumeditor/js/popup.js?vss=170323" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="/static/lib/daumeditor/css/popup.css?vss=170323" type="text/css"  charset="utf-8"/>
<script type="text/javascript">
// <![CDATA[
	function done(_mockdata) {
		if (typeof(execAttach) == 'undefined') { //Virtual Function
	        return;
	    }
		
		// var _mockdata = _mockdata;
		execAttach(_mockdata);
		closeWindow();
	}

	function initUploader(){
	    var _opener = PopupUtil.getOpener();
	    if (!_opener) {
	        alert('잘못된 경로로 접근하셨습니다.');
	        return;
	    }
	    
	    var _attacher = getAttacher('image', _opener);
	    registerAction(_attacher);

	    // config 내용 불러올수 있다.
	    // console.log(_attacher['editor']['config']['contentWidth']);
	}
// ]]>
</script>
</head>
<body onload="initUploader();">
<div class="wrapper">
	<div class="header">
		<h1>사진 첨부</h1>
	</div>	


<!--여기수정했습니다.-->
		<form class="alert" id="frm" method="post" enctype="multipart/form-data" action="/wide/uploadimage/<?=$_GET['name']?>">
			<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />
			<input type="file" name="upload_file" />
			<button type="submit" id="upload_button">업로드</button>
			<button onclick = "return false;" style = 'display:none;'  id = 'show_uploading'> 저장중 </button>
		</form>
	<dl class="alert">
		<dt> 업로드 가능 확장자 : GIF, JPG, JPE, JPGE, PNG, BMP</dt>
	</dl>

	<dl class="alert">
		<dt> 업로드 가능 크기 : <span style = 'color:red;'>2MB</span> <span style = 'color:gray;'> (한 게시글 제한 크기: 8MB)</span> </dt>
	</dl>

	<div class="body">
		<dl class="alert">
		    <dt>사진 첨부 확인</dt>
		    <dd>
		    	확인을 누르시면 임시 데이터가 사진첨부 됩니다.<br /> 
				인터페이스는 소스를 확인해주세요.
			</dd>
		</dl>


	</div>
<!-- onclick="done() 을 위에 initUploader()랑 합침
	<div class="footer">
		<p><a href="#" onclick="closeWindow();" title="닫기" class="close">닫기</a></p>
		<ul>
			<li class="submit"><a href="#" onclick="done();" title="등록" class="btnlink">등록</a> </li>
			<li class="cancel"><a href="#" onclick="closeWindow();" title="취소" class="btnlink">취소</a></li>
		</ul>
	</div>
-->
</div>

<!--여기서 부터가 본문에 추가되도록 ajax 하는 내용-->
  <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

<script>

	//폼객체를 불러와서
	var form = $('form')[0];
	//FormData parameter에 담아줌
	var formData = new FormData(form);

	$(function(){
      $("#upload_button").click(function(e){
     		e.preventDefault();

         $("#upload_button").hide();
         $("#show_uploading").show();

     		
     		//ajax 전에 이미지 파일의 확장자를 검사
     		var filename = $('input[name=upload_file]').val();
     		
     		//이미지 확장자를 검사함
			//FileName = filename.slice(filename.indexOf(".") + 1).toLowerCase();
			FileName = filename.split('.');
			
			//console.log(FileName);
			if (FileName.length == 0) {
				alert("이미지 파일을 등록해주세요.");
				$('input[name=upload_file]').val("");
				$("#show_uploading").hide();
				$("#upload_button").show();
				return 0;
			} else {
				FileName = FileName[FileName.length -1].toLowerCase();
			}
			if(FileName !="jpg" && FileName !="jpge" && FileName != "jpe" && FileName != "png" &&  FileName != "gif" &&  FileName != "bmp"){
				alert("이미지 파일의 확장자를 확인해주세요.");
				//$('input[name=upload_file]').val("");
				$("#show_uploading").hide();
				$("#upload_button").show();
				return 0;
			} 

         var form = $('form')[0];
         var formData = new FormData(form);

             $.ajax({
                url:"/wide/uploadimage/<?=$_GET['name']?>",
                dataType: "json",
                processData: false,
                //안되면 false로 고치고 연구할것 (multipart/form-data)
                contentType: false,
                data: formData,
                type: 'POST',
                success: function(result){
                    //alert("업로드 성공!!");
                    //console.log(result);

	            if(typeof result['error']  != "undefined")  {
	            	 console.log('실패 검출됨');
	                alert(result['error']);

	                $("#show_uploading").hide();
			       	$("#upload_button").show();
	              return 0;
	            }


                    var _mockdata = {
				//업로드 파일 정보는 여기서 수정
				         'imageurl': "",
			            'filename': "",
			            'filesize':"",
			            'imagealign': 'C',
			            'originalurl':"",
			            'thumburl': "",
	        };

					_mockdata['imageurl'] = result['upload_img']['image_url'];
					_mockdata['filename'] = result['upload_img']['name'];
					_mockdata['filesize'] = result['upload_img']['size']*1024;
					_mockdata['originalurl'] = result['upload_img']['image_url'];
					_mockdata['thumburl'] = result['upload_img']['image_url'];
					_mockdata['alt'] = result['upload_img']['name'];
					//console.log(_mockdata);
					//alert("동작 확인용");
					
					done(_mockdata);



               }
            });
         });
	})

</script>


<!-- 자바스크립트로 ajax 하는방법 (권장하지 않음)
	(멀티플 x, 멀티플시 생각해봐야함)
<script>
var form = document.getElementById('file-form');
var fileSelect = document.getElementById('file-select');
var uploadButton = document.getElementById('upload-button');

form.onsubmit = function(event) {
  	event.preventDefault();

  	// Update button text.
  	uploadButton.innerHTML = '업로드중';

 	// Get the selected files from the input.
	//var files = fileSelect.files;

	// Create a new FormData object.
	var formData = new FormData();
	/*
	// Loop through each of the selected files.
	for (var i = 0; i < files.length; i++) {
		var file = files[i];


		// Check the file type. /*
		if (!file.type.match('image.*')) {
			continue;
		}
		*/
		// Add the file to the request.
		//formData.append('photos', file, file.name);
	//}

	var file =fileSelect.files[0];
	formData.append('upload_file', file.name);

	// Open the connection.
	var xhttp = new XMLHttpRequest();

	//이거 문제였을 가능성 높음
	setRequestHeader("Content-Type", "multipart/form-data");

	xhttp.onreadystatechange = function() {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
                /* 가져온거 넣을 것*/
               uploadButton.innerHTML = '업로드 완료';
        } else {
	    alert('An error occurred!');
	  }
	};	
	alert(formData);
	xhttp.open('POST', '/board/uploadimage');
	xhttp.setRequestHeader("Content-type", "multipart/form-data");
	xhttp.send(formData);

</script>
}-->


</body>
</html>

