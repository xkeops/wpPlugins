jQuery(document).ready(function($){

	
	
	var cropper="";
	$("#simplistics_user_profile_picture").change(function() {
		
		if(this.files[0].name.match(/.(jpg|jpeg|png|gif)$/i)){
			var reader = new FileReader();
        reader.onload = function (e) {
            $('#my-image').attr('src', e.target.result);
            var image = document.getElementById('my-image');
			cropper = new Cropper(image, {
			  aspectRatio: 100 / 100,
			  crop: function(e) {
			   
			  }
			});
        }
        reader.readAsDataURL(this.files[0]);
		}else{
			alert("The file is not a picture");
		}
		
		

	});
	
	$('.rules').bind('scroll', function (e) {
		var elem = $(e.currentTarget);
		if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()) {
			$('.submit-registration').prop("disabled",false);
		}
	});

	


	/*$('#registration-sign-up-button').click(function(){

	});*/

	function validRegistrationFields(elements){
		let arrErrors = [];
		$.each(elements,function(index,value){
			arrErrors[$(value).attr('id')] = [];
			if($(value).val()==""){
				arrErrors[$(value).attr('id')].push("You have to fill this field");
			}
		});


	}

	$('.submit-registration').click(function(){

		var elements = $(`
			#first_name,
			#last_name,
			#email,
			#password,
			#gender,
			#age,
			#phone,
			#address,
			#city,
			#province,
			#postal_code,
			#terms,
			#initials,
			#simplistics_register_nonce,
			.question
		`);

		validRegistrationFields(elements);

		var inputs = "";
		$.each(elements,function(index,value){
			$(value).attr('value',$(value).val());
			
			if($(value).context.checked){
				$(value).attr('checked', 'checked');
			}
			inputs+=$(value).context.outerHTML;
		});
		inputs+='<input id="g-recaptcha-response" name="g-recaptcha-response" value="'+$('#g-recaptcha-response').val()+'">';



		let frm = $('<form method="POST" style="display:none">').append($(inputs)).appendTo($(document.body));

		frm.submit();

		return false;
	});

	$("#cropped").click(function(){
			
			if(cropper){
				var cropped = cropper.getCroppedCanvas({
				  width: 100,
				  height: 100
				});

				var data = {
					action: 'updatePicture',
					security:  user_ajax.ajax_nonce,
		            post_var: 'this will be echoed back',
		            file: cropped.toDataURL(),
		            simplistics_user_firstname:$('#simplistics_user_firstname').val(),
		            simplistics_user_lastname:$('#simplistics_user_lastname').val()
				};
			}else{
				var data = {
					action: 'updatePicture',
					security:  user_ajax.ajax_nonce,
		            post_var: 'this will be echoed back',
		            file: 'false',
		            simplistics_user_firstname:$('#simplistics_user_firstname').val(),
		            simplistics_user_lastname:$('#simplistics_user_lastname').val()
				};
			}

			
			$.post(user_ajax.ajaxurl, data, function(response) {
			 

			 	if(cropper){
			 		cropper.destroy();
					$('#my-image').attr('src',cropped.toDataURL());
				}
				location.reload();				
		 	});

			

			return false;
			
		});

	$("#registration-next-button").click(function(){
		
		$("#form-part-1").fadeOut();

		$("#form-part-2").fadeIn();
		$("#registration-sign-up-button").show();
		$("#registration-next-button").hide();
		$("#registration-previous-button").show();

		return false;
	});

	$("#registration-previous-button").click(function(){
		
		$("#form-part-2").fadeOut();
		$("#form-part-1").fadeIn();

		
		$("#registration-sign-up-button").hide();
		$("#registration-next-button").show();
		$("#registration-previous-button").hide();

		return false;
	});

	/*$(".form-right-login input").change(function(){
		var valid = true;
		
		$(".form-right-login input").each(function(){
			
			if($(this).val() ==""){
				valid = false;
			}
		});
		if(valid){
			$("#registration-sign-up-button").show();
			$("#registration-next-button").hide();
		}else{
			$("#registration-sign-up-button").hide();
			$("#registration-next-button").show();
		}
	});*/

	$('.rules').scrollspy();

});