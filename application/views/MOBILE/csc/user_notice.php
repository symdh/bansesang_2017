

<div id = 'mngNtc_main'>
	<div id ='mngNtc_wapper'>
		<span style = 'font-size: 20px;'> 공지사항 </span>
<?php
	$check_login = $this->session->userdata('logged_in');
	$super_user = $this->session->userdata('guitar_id');
	$super_user = isset($check_login) && $check_login == 1 && $super_user > 1 ;
	if($super_user) {
		echo "
			<a href = '/manage/cap/managewrite?type=notice'>
				<button class = 'C_btn-W100'> 글쓰기 </button>
			</a>

		";
	} 
?>

		<div id ='mngFaq_Dstl_SbLst'>

		</div>


		<div id ='mngFaq_Dstl_MnLst'>
	
<?php
	$i=0;
	foreach ($data_w_ntc as $entry){
		
		echo " <div style = 'padding:5px 0px 0px 10px; '>
			<a href = '/csc/notice/notice/{$entry['read_id']}' > {$entry['title']} </a> <br>
			</div>
			";

		if($super_user) {
	
			echo "<form style = 'width:0px;' action = '/manage/cap/uploadsblst' method='post'>
					<input type='hidden' name='{$this->security->get_csrf_token_name()}' value='{$this->security->get_csrf_hash()}' />
					<input type='hidden' name='pointer' value='notice' />
					<input type='hidden' name='read_id' value='{$entry['read_id']}' />
					<input type='hidden' name='title' value='{$entry['title']}' />
					<button type ='submit' class = 'C_btn-W100'>알림등록</button>
				</form>";
	
		}

	}
?>
				
		</div>

		<br>
		<span style = 'font-size: 15px;'>  웹 패치내역 </span>
		<div id = 'mngFaq_Dstl_PtcLst' > 
			<table>
				<tbody>
<?php
	$i=0;
	foreach ($data_w_ptc as $entry){
		$i++;

		$entry['date'] = date('y/m/d', strtotime($entry['date']));

		echo "
			<tr onclick = 'showcontents(this)'>
				<td class = 'mngFaq-THstl-RdNum'>
					{$entry['date']}
				</td>
				<td class = 'mngNtc-THstl-RdTtl'>
					<span style = 'float:left;'> {$entry['title']} </span> 
			";

		if($super_user) {
	
			echo "<form style = 'width:0px height:0px;' action = '/manage/cap/uploadsblst' method='post'>
					<input type='hidden' name='{$this->security->get_csrf_token_name()}' value='{$this->security->get_csrf_hash()}' />
					<input type='hidden' name='pointer' value='notice' />
					<input type='hidden' name='read_id' value='0' />
					<input type='hidden' name='title' value='{$entry['title']}' />
					<button type ='submit' class = 'C_btn-W100'>알림등록</button>
				</form>";
	
		}

		echo "	</td>
			</tr>
		";
	
		echo "

			<tr style = 'display:none;'>
				<td  style = 'background : #f5f5f5;' class = 'mngFaq-THstl-RdNum'>
					<img style = 'width:10px;' src = '/static/img/answer-guide-2.jpg'>
						내용
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

<?php
	if($super_user) {
		echo "<br><span style = 'font-size: 15px;'> 공지 알림 제어 </span><br>";		
		
		foreach ($sblst_info as $entry) { 
			echo  "<span style = 'float:left;'> {$entry['title']} </span>";	
			


			echo "<form style ='width:0px;' action = '/manage/cap/deletesblst' method = 'post'>
					<input type='hidden' name='{$this->security->get_csrf_token_name()}' value='{$this->security->get_csrf_hash()}' />
					<input type='hidden' name='check_num' value='{$entry['check_num']}' />
					<button type ='submit' class = 'C_btn-W100'>알림삭제</button>

				</form>";

			

		}
	}
?>


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
