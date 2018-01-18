$(document).ready(function($) {
	$("#config-menu li").click(function() {
		$("#config-menu li").each(function() {
			if($(this).hasClass('active')) {
				$(this).removeClass('active');
              	$('div#part-' + $(this).attr('menu-part')).removeClass('show-part');
			}
		});
		$(this).addClass('active');
      	$('div#part-' + $(this).attr('menu-part')).addClass('show-part');
	});
  
  	upload_image('#memory_useravatar');
    upload_image('#memory_header_picture');
    upload_image('#memory_background'); 
    upload_image('#memory_cardbck');   	
    upload_image('#memory_mobilebck');  
    upload_image('#memory_zhifubao_donate'); 
    upload_image('#memory_weixin_donate');  
    upload_image('#memory_comment_default');
  
	$( "input#memory_foot_color").wpColorPicker();
});

function upload_image(tmp){
 	 $(tmp+'_upload').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $(tmp).val(image_url);
        });
    });
}