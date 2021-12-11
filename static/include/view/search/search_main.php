<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<form name='search_main' method='get' action='/search' onsubmit = 'return Verifiction_search();'>
  <select name="type" class  = 'pgMn-Sltstl-Srch' value="all">       
    <option value="all" class  = 'pgMn-Sltstl-Srch' >전체 검색</option>
    <!-- <option value="commu" class  = 'pgMn-Sltstl-Srch'>커뮤니티</option> -->
    <!-- <option value="minfo" class  = 'pgMn-Sltstl-Srch'>의학정보</option> -->
  </select>
  <input name = 'query' id = 'pgMn_Istl_Srch' type='search'>
  <button id = 'pgMn_Bstl_Srch' > 검색 </button>
</from>



<script>

function Verifiction_search() {

	//정규식 특수문자 제거하기
	var str = $('input[name=query]').val();
	var pattern = /[^(가-힣ㄱ-ㅎㅏ-ㅣa-zA-Z0-9 | /\s/g)]/gi;   // 특수문자 제거 (띄어쓰기 제외)
 	str = str.replace(pattern,"");
	$('input[name=query]').val(str);

	if( $('input[name=query]').val() == ''  || $('input[name=query]').val().length < 2   ) {
		alert('검색어를 2글자이상 입력해주세요.');
		return false;
	}

	if( $('select[name=type]').val() == '' ) {
		$('select[name=type]').val('all'); 
	}
  
	return true;
}



var search_query = getParameters('query');
if( search_query != '0' ) {
	$('input[name=query]').val(search_query);
}

var search_type = getParameters('type');
if( search_type != '0' ) {
	$('select[name=type]').val(search_type);
}


</script>