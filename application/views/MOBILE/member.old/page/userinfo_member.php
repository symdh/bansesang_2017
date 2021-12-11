


<div id="pgUi_main">
	<div id = "pgUi_wrapper">
		<?php
		    //왼쪽 리스트 부분
		    include "./static/include/view/member/MOBILE_sub_list.php";
		?>
		    


		<div id ="pgUi_Dstl_body">
		
			<div id = 'pgUi_Dstl_SbTtl'>
				<span style = 'font-size:20px;'> 
					개인정보변경 
				</span>
			</div>

			<table id = "pgUi_TBstl_info">
				<tbody>
					<tr>
						<td>
							가입날짜 
						</td>

						<td> 
							<?=date('y년 m월 d일',$user_info['join_date']);?> 
						</td>
					</tr>
					
					<tr>
						<td> 
							이메일
						</td>
						
						<td> 
							<?=$user_info['email'];?>
							
							<span style = 'font-size : 10px; color : gray; '>
								<br> <br> *이메일은 어떠한 경우에도 변경 불가합니다.
							</span>
						</td>
					</tr>

					<tr>
						<td> 
							닉네임
						</td>
						
						<td> 
							<?=$user_info['nickname'];?>

							<span style = 'font-size : 10px; color : gray; '>
								<br> <br> *닉네임 변경 곧 추가 예정입니다.
							</span>

						</td>
					</tr>

					<tr>
						<td> 
							비밀번호 	
						</td>
							
						<td> 
							<button style = 'font-size:15px; margin-top: 10px;' onclick = "$('#pgUi_Dstl_modifyPasswd').show();">비밀번호 변경</button>
							
							<div style ='margin-top: 10px; display:none;' id = 'pgUi_Dstl_modifyPasswd'>
								<form method="post" onSubmit="return check_passwd_ing();" action='/member/userpage/modifypasswd'>
									<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />



									기존 비밀번호
									<input type="password" size="40" name="user_recent_passwd" placeholder="기존 비밀번호를 입력해주세요">

									<br>

									비밀번호 입력
									<input type="password" size="40" name="user_passwd" placeholder="비밀번호 (6자이상)" onkeydown = 'reset_passwd();'>
									<br><span class = "C_style_input_1" style = 'padding-left: 80px; color:gray;'>*영문자, 숫자 혼용을 권장합니다. (특수문자 가능)</span>

									<br>

									비밀번호 확인
									<input type="password" size="40" name="user_passwd2" placeholder="비밀번호 확인" onkeydown = 'reset_passwd();' onblur = "check_passwd_JS();">
									<span name = "explain_passwd" class = "C_style_input_1" style = 'padding-left: 80px; color:gray;'><br>*비밀번호를 한번 더 입력해주세요. </span>
									<span id = 'css_passwd_cf_true' style = 'padding-left: 80px;'><br>비밀번호가 일치합니다.</span>
									<span id = 'css_passwd_cf_false' style = 'padding-left: 80px;'><br></span>

									<div style = 'margin-left:40px; margin-top: 10px; width:100px;'>
										<button type = 'submit'> 변경완료 </button>
										<button onclick = "$('#pgUi_Dstl_modifyPasswd').hide();" type = 'reset' style = 'margin-left:20px;'> 취소 </button>
									</div>

								</form>
				<script>
					$('#css_passwd_cf_true').hide();
					$('#css_passwd_cf_false').hide();
				</script>				
							</div>



						</td>
					</tr>

					<tr>
						<td> 
							이름
						</td>
						
						<td> 
							<?=$user_info['name'];?>  (<?=$user_info['gender'];?>)

							<span style = 'font-size : 10px; color : gray; '>
								<br> *이름, 성별은 변경 불가합니다.
								<br> *변경 요청은 문의바랍니다.
							</span>
						</td>
					</tr>

					<tr>
						<td> 
							핸드폰 번호
						</td>
						
						<td> 
							<?=$user_info['phone_number'];?> 

							<span style = 'font-size : 10px; color : gray; '>
								<br> <br> *핸드폰번호 변경은 아직 예정이 없습니다.
							</span>
						</td>
					</tr>

					<tr>
						<td> 
							회원사진


						</td>
						
						<td> 
							<button style = 'font-size:15px; margin-top: 10px;' onclick = "$('#pgUi_Dstl_modifyImg').show();">사진변경</button>

							<div style ='margin-top: 10px; display:none;' id = 'pgUi_Dstl_modifyImg'>
								
								<form method="post" action='/member/userpage/modifyimg' enctype="multipart/form-data">
									<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />


									<input type="file" name="user_image" >

									<br>
									<span>
										<span style ='font-size:12px; color:gray;'>
										*4x3 규격으로 변환되어 업로드 됩니다. <br>
										*사진삭제는 파일선택을 하지 않고 제출!

										</span>

									</span>
									<div style = 'margin-left:40px; width:100px;'>
										<button> 변경완료 </button>
										<button onclick = "$('#pgUi_Dstl_modifyImg').hide();" type = 'reset' style = 'margin-left:20px;'> 취소 </button>
									</div>
								</form>
							</div>

							<div style = 'padding-top:10px; height:160px; margin-left: 10px; '>
								<span style ='font-size:15px; '>
								*사진 미리보기		
								</span>
								<div  style = 'width:180px; height: :135px; '> 
									<img id ='member_J_preview' src = '<?=$user_info['user_img']?>'>

								</div>
							</div>
						</td>
					</tr>


					<tr>
						<td> 
							본인소개
						</td>
						
						<td> 
							<button style = 'font-size:15px; margin-top: 10px;' onclick = "$('#pgUi_Dstl_contentIntro').hide(); $('#pgUi_Dstl_modifyIntro').show();">소개변경</button>

							<div style = 'width:220px; margin-top: 10px;' id = 'pgUi_Dstl_contentIntro'>
								<?=$user_info['user_intro']?>
							</div>

							<div style = 'width:220px; height:100px; display:none;' id = 'pgUi_Dstl_modifyIntro'>

								<form method="post" action='/member/userpage/modifyIntro'>

									<input type='hidden' name='<?=$this->security->get_csrf_token_name()?>' value='<?=$this->security->get_csrf_hash()?>' />


									<div style = 'display : block; width:210px; height:80px;'>
										<textarea class='C_txtr-W500' style = 'width:210px; height:80px; ' size="40" maxlength="500" name="user_introduce"><?=$user_info['user_intro']?></textarea>
									
									</div>
									
									<div style = 'margin-left:40px; margin-top: 10px; width:100px; display : block;'>
										<button> 변경완료 </button>
										<button onclick = "$('#pgUi_Dstl_contentIntro').show(); $('#pgUi_Dstl_modifyIntro').hide();" type = 'reset' style = 'margin-left:20px;'> 취소 </button>
									</div>
								</form>
							</div>

							<div style ='margin-top: 10px;'>
								
								<span style ='font-size:12px; color:gray;'>
									*글 작성시 노출되는 본인소개 내용입니다.
								</span>
							</div>
							
						</td>
					</tr>


				</tbody>
			</table>

	
					
			

		</div>

	</div>
</div>



