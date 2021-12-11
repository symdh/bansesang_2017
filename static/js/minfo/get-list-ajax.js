

function get_list_ajax (e)  {
	var formData = $(e).serialize();
	formData[$('#ci_t').attr('name')] = $('#ci_t').val();
      // alert(formData);
       var ajax_url = '/minfo/getlist/minfo';
       $.ajax({
	         url: ajax_url,
	         dataType: "json",
	         processData: true,
	         contentType: 'application/x-www-form-urlencoded',
	         data: formData,
	         type: 'POST',
	         success: function(result){
		     
	            // $('form[name=form_for_modify_comment]').remove();
	            // set_position_div.html(result[0]['content']);

	            // cancel_added_tool('span[name=button_cancel_modify]');
         		
	         },
	         error:function(){
	          	
	         }	
            
      });


     
}