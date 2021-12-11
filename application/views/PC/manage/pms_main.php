
<div id = 'mngPms_main'>
	<div id = 'mngPms_wrapper'>
		<table>	
			<tr>
				<th> 회원등급 </th>
				<th> 최종 변경날짜</th>
				<th> 최근접속일</th>
				<th> 가입날짜 </th>
				<th> 회원번호 </th>
				<th> 성별 </th>
				<th> 이름</th>
				<th> 닉네임</th>
				<th> 전화번호</th>
			</tr>

<?php 

	if(isset($user_info[0][0])) //데이터 없으면 걍 끝냄
 		return 0;

	foreach ($user_info as $entry){ 
					
	echo "
			<tr>
				<td> 
					{$entry['super_user']} 
				</td>

				<td>
					
				</td>

				<td>
					
				</td>

				<td>
					
				</td>

				<td>
					
				</td>

				<td>
					
				</td>
			</tr>
		";
	} 
?>


		</table>
	</div>
</div>

