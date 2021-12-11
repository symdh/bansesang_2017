

<div id = 'mngFaq_main'>
	<div id ='mngFaq_wapper'>
		<span style = 'font-size: 20px;'> FAQ [자주묻는질문] </span>
<?php
	$check_login = $this->session->userdata('logged_in');
	$super_user = $this->session->userdata('guitar_id');
		
	if(isset($check_login) && $check_login == 1 && $super_user > 1 ) {
		echo "
			<a href = '/csc/cscwrite?type=faq'>
				<button class = 'C_btn-W100'> 글쓰기 </button>
			</a>

		";
	} 
?>

		<div id ='mngFaq_Dstl_SbLst'>

		</div>




		<div id ='mngFaq_Dstl_MnLst'>
			<table>
				<thead>
					<th>
						번호
					</th>
					<th>
						내용
					</th>
				</thead>

				<tbody>
<?php
	$i=0;
	foreach ($data_w as $entry){
		$i++;
		echo "
			<tr onclick = 'showcontents(this)'>

				<td class = 'mngFaq-THstl-RdNum'>
					{$i}
				</td>

				<td>
					{$entry['title']}
				</td>
			</tr>
		";
	
		echo "

			<tr style = 'display:none;'>
				<td  style = 'background : #f5f5f5;' class = 'mngFaq-THstl-RdNum'>
					<img style = 'width:10px;' src = '/static/img/answer-guide-2.jpg'>
						답변내용
				</td>

				<td style = 'background : #f5f5f5; '>
					{$entry['content']}
				</td>
			</tr>


		";

	}


?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
function showcontents(e) {
	$(e).next().css('display','table-row');
	$(e).attr('onclick','hidecontents(this)');
}

function hidecontents(e) {
	$(e).next().css('display','none');
	$(e).attr('onclick','showcontents(this)');
}
</script>



