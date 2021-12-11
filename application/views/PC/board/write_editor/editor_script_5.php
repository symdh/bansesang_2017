				
		
<script type="text/javascript">
	var config = {
		txHost: '', /* 런타임 시 리소스들을 로딩할 때 필요한 부분으로, 경로가 변경되면 이 부분 수정이 필요. ex) http://xxx.xxx.com */
		txPath: '/static/lib/daumeditor/', /* 런타임 시 리소스들을 로딩할 때 필요한 부분으로, 경로가 변경되면 이 부분 수정이 필요. ex) /xxx/xxx/ */
		txService: 'sample', /* 수정필요없음. */
		txProject: 'sample', /* 수정필요없음. 프로젝트가 여러개일 경우만 수정한다. */
		initializedId: "", /* 대부분의 경우에 빈문자열 */
		wrapper: "tx_trex_container", /* 에디터를 둘러싸고 있는 레이어 이름(에디터 컨테이너) */
		form: 'tx_editor_form'+"", /* 등록하기 위한 Form 이름 */
		txIconPath: "/static/lib/daumeditor/images/icon/editor/", /*에디터에 사용되는 이미지 디렉터리, 필요에 따라 수정한다. */
		txDecoPath: "/static/lib/daumeditor/images/deco/contents/", /*본문에 사용되는 이미지 디렉터리, 서비스에서 사용할 때는 완성된 컨텐츠로 배포되기 위해 절대경로로 수정한다. */
		canvas: {
            exitEditor:{
                /*
                desc:'빠져 나오시려면 shift+b를 누르세요.',
                hotKey: {
                    shiftKey:true,
                    keyCode:66
                },
                nextElement: document.getElementsByTagName('button')[0]
                */
            }, 
<?php 
	if($function_name == 'csc') {  
		echo "initHeight: 200, // 본문높이지정" ; 
        }  
?>
            
			styles: {
				color: "#123456", /* 기본 글자색 */
				fontFamily: "굴림", /* 기본 글자체 */
				fontSize: "10pt", /* 기본 글자크기 */
				backgroundColor: "#fff", /*기본 배경색 */
				lineHeight: "1.5", /*기본 줄간격 */
				padding: "8px" /* 위지윅 영역의 여백 */
			},
			showGuideArea: false
		},
		events: {
			preventUnload: false
		},
		sidebar: {
			attachbox: {
				show: true,
				confirmForDeleteAll: true
			},
			attacher: {
			    image: {

			           features: { left:250, top:65, width:600, height:350, scrollbars:0},
			           popPageUrl: "/wide/pAttachPhoto?name=<?=$class_name?>"
			    }
			},
			  capacity: {
			         maximum: 8388608
			  }
		},
		size: {
			contentWidth: 730 /* 지정된 본문영역의 넓이가 있을 경우에 설정 */
		}
	};
	
	EditorJSLoader.ready(function(Editor) {

		//이미지 업로드시 alt 속성 부여 (overwrite 시켜줌)
		Trex.Class.overwrite(Trex.Attachment.Image, {
		           getObjectAttr: function(data) {
		            var _objattr = Object.extend({}, this.actor.config.objattr);
		            if(data.width) {
		               if(!_objattr.width || _objattr.width > data.width) {
		                  _objattr.width = data.width;
		                }
		            } else {
		                delete _objattr.width;
		            }
		            if(data.height) {
		               if(!_objattr.height || _objattr.height > data.height) {
		                   _objattr.height = data.height;
		               }
		            } else {
		               delete _objattr.height;
		            }
		            _objattr['alt'] = data.alt;
		            return _objattr;
		         }
	    	  });

      		var editor = new Editor(config);
	});
	

</script>

<!-- Sample: Saving Contents -->
<script type="text/javascript">
	/* 예제용 함수 */
	//에디터가 저장됨
	function saveContent() {
		//중복 등록 방지
		

			//이 첨부기능은 보안상 문제가능성이 높음
		var allAttachmentList = Editor.getAttachBox().datalist; // 첨부기능으로 추가된 모든 첨부파일들 
		var attachements = Editor.getAttachments();  //내용에 있는 첨부파일의 파일이름을 저장함

		var uploaded_file_name = new Object();
		var deleting_file_name = new Object();
		var index_uploaded = 0; //업로드 된 인덱스
		var index_deleting = 0; //삭제할 인덱스

////////////////////
//이미지 게시판 최소 이미지 1개 이상 
<?php echo "function_name = '{$function_name}';"; ?>
if(function_name == 'photo' && attachements.length == 0) {
	alert("본문에 1개이상의 사진이 필요합니다.");
	return 0;
}

////////////////////

		for(var i = 0; i < allAttachmentList.length; i++ ) {
			//console.log(i);
			//console.log(allAttachmentList[i]['data']['filename']);
			for(var j = 0 ; j < attachements.length; j++ ) {
				if (allAttachmentList[i]['data']['filename'] == attachements[j]['data']['filename']){
					uploaded_file_name[index_uploaded] = allAttachmentList[i]['data']['filename'];
					//console.log(allAttachmentList[i]['data']['filename']);
					index_uploaded++;
					break;
				} 
				//console.log(j);
				//console.log(attachements.length);
				if (j == attachements.length - 1) {
					//console.log("된다.");
					deleting_file_name[index_deleting] = allAttachmentList[i]['data']['filename'];
					index_deleting++;
				}
			}
		}
		//console.log(uploaded_file_name);
		/* //보안상 문제때문에 닫음
		console.log(deleting_file_name);
		
        $.ajax({
        	type: 'POST',
            url: '/board/delete_image',
            dataType: "html",
            processData: true,
            contentType: 'application/x-www-form-urlencoded',
            data:deleting_file_name,
            success: function(result){
            	//console.log('동작함');
            }
        });
		*/

		Editor.save(); // 이 함수를 호출하여 글을 등록하면 된다.
	}



	/**
	 * Editor.save()를 호출한 경우 데이터가 유효한지 검사하기 위해 부르는 콜백함수로
	 * 상황에 맞게 수정하여 사용한다.
	 * 모든 데이터가 유효할 경우에 true를 리턴한다.
	 * @function
	 * @param {Object} editor - 에디터에서 넘겨주는 editor 객체
	 * @returns {Boolean} 모든 데이터가 유효할 경우에 true
	 */
	function validForm(editor) {
	// Place your validation logic here
		//관리 페이지 일때, 질문 유형 검사
		if(function_name == 'csc' || function_name == 'minfo' ) {
			if($('select[name=write_type]').val() == 0) {
				alert("질문 유형을 선택해주세요.");
				$('select[name=write_type]').focus();		
				return false;
			}

		}

		//익명일때 검사됨 (+내용 규칙 포함)
		if(watch_comment_before_send()) {

		} else {
			return false;
		}


	// sample : validate that content exists
		var validator = new Trex.Validator();
		var content = editor.getContent();
		if (!validator.exists(content)) {
			alert('내용을 입력하세요');
			return false;
		}

		//글 중복 등록 방지
		$('#regist').hide();
		$('#registering').show();

		return true;
	}

	/**
	 * Editor.save()를 호출한 경우 validForm callback 이 수행된 이후
	 * 실제 form submit을 위해 form 필드를 생성, 변경하기 위해 부르는 콜백함수로
	 * 각자 상황에 맞게 적절히 응용하여 사용한다.
	 * @function
	 * @param {Object} editor - 에디터에서 넘겨주는 editor 객체
	 * @returns {Boolean} 정상적인 경우에 true
	 */
	function setForm(editor) {
        var i, input;
        var form = editor.getForm();
        var content = editor.getContent();

        // 본문 내용을 필드를 생성하여 값을 할당하는 부분
        var textarea = document.createElement('textarea');
        textarea.name = 'content';
        textarea.value = content;
        form.createField(textarea);

        //저장시에 아래쪽에 보이는 textarea를 none 함
        textarea.style.display = 'none';

        /* 아래의 코드는 첨부된 데이터를 필드를 생성하여 값을 할당하는 부분으로 상황에 맞게 수정하여 사용한다.
         첨부된 데이터 중에 주어진 종류(image,file..)에 해당하는 것만 배열로 넘겨준다. */
        var images = editor.getAttachments('image');
        for (i = 0; i < images.length; i++) {
            // existStage는 현재 본문에 존재하는지 여부
            if (images[i].existStage) {
                // data는 팝업에서 execAttach 등을 통해 넘긴 데이터
                //저장되는 이미지 정보가 뜸
                //alert('attachment information - image[' + i + '] \r\n' + JSON.stringify(images[i].data));
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'attach_image['+i+']';
                input.value = images[i].data.imageurl;  // 예에서는 이미지경로만 받아서 사용
                form.createField(input);
            }
        }

        var files = editor.getAttachments('file');
        for (i = 0; i < files.length; i++) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'attach_file';
            input.value = files[i].data.attachurl;
            form.createField(input);
        }

        return true;
	}
</script>



<script>


/*
//파일 삭제시 나타나는것 (이미지 삭제시) (전체 삭제시 for로 실행되는 방식이다.)
Trex.module("notify removed attachments", function (editor, toolbar, sidebar, canvas, config) {
	editor.getAttachBox().observeJob(Trex.Ev.__ENTRYBOX_ENTRY_REMOVED, function (entry) {

 
		
	});
});
EditorJSLoader.ready(function(Editor) {
	var editor = new Editor(config);
}); //여기까지임

		//내용에 있는 첨부파일의 파일이름을 저장함
		var attachements = Editor.getAttachments(); 
		for(var i = 0; i < attachements.length; i++ ) {
			console.log(attachements[i]['data']['filename']);
		}
		//console.log(attachements);

		console.log("다시시작");
		// 첨부기능으로 추가된 모든 첨부파일들 
		var allAttachmentList = Editor.getAttachBox().datalist;
		for(var i = 0; i < allAttachmentList.length; i++ ) {
			console.log(allAttachmentList[i]['data']['filename']);
		}
		//console.log(allAttachmentList);
		var _attachBox = editor.getAttachBox();
		//alert(entry.data.filename);  첨부파일의 파일이름 출력됩니다.



var attachements = Editor.getAttachments(); // 글 아래 첨부 정보 얻어오기 ( 본문에서 지워진 파일을 제외하고 return 하고요. )

//첨부파일에서 삭제실행한 파일 이름을 가져옴
	var file_name = entry.data.filename;
//alert(entry.data.filename);  첨부파일의 파일이름 출력됩니다.

본문 내용과 상관없이 첨부된 파일 리스트에서 지운 것만 체크하실려면 다음과 같이 deletedMark 를 체크하시면 됩니다.
var files = Editor.getSidebar().getAttachments('file');
for (var i = 0, len = files.length; i < len; i++) {
    alert(files[i].deletedMark);
}

// 위지윅 에디터에 실제로 사용되어진 첨부파일 목록
var attachmentList = Editor.getAttachments();
for( var i=0, n=attachmentList.length; i<n; i++ ){
    var entry = attachmentList[i];
    alert( entry.data.imageurl );
}

// 첨부기능으로 추가된 모든 첨부파일들 ( 삭제버튼으로 제거했다고 하더라도 undo 기능을 위해 남아 있습니다 )
var allAttachmentList = Editor.getAttachBox().datalist;
for( var i=0, n=allAttachmentList.length; i<n; i++ ){
    var entry = allAttachmentList[i];
    if( entry.deletedMark == true ){
        alert("첨부상자에서 삭제된 파일 : " + entry.data.imageurl);
    } else {
        alert("첨부상자에 존재하는 파일 : " + entry.data.imageurl);
    }    
}
*/

</script>

<div><button style='display:none; float:right;' id = 'registering'>등 록 중</button></div>
<div><button onclick='saveContent(this)' style = 'float:right;' id = 'regist'>저장하기</button></div>
<!-- End: Saving Contents -->

<!-- 세로 화면 늘림을 위한 여유길이 부여-->
<div id = 'brdWrt_Dstl_Blk'>
</div>


<!--=======================================
=========================================== -->
	</div>
</div>
</div>



