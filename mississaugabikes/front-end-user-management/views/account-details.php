<?php $user = wp_get_current_user()->data;
		$name = explode(" ",$user->display_name);
		//$picture = DIR_URL."pictures/".$user->ID.".png";

		if(file_exists(URI_PATH."pictures/".$current_user->ID.".png")){
			$picture = DIR_URL."pictures/".$current_user->ID.".png"; 
		}else{
			$picture = DIR_URL.'img/default_profile_picture.png';
		}
		?>

	<div class="row">
		<div class="col-md-12">
			<div class="card edit_profile_modal">
				<div style="height: 100px;background-color:#E9E9E9;" class="card-header text-center">
		    		<img height="100" width="100" id="my-image" src="<?php if($picture) echo $picture; else echo DIR_URL."img/default_profile_picture.png"?>" alt="No picture" />
		  		</div>
				
				
			  <div class="card-block" style="background-color:#0077C8;color:white;">
			    
			   	<label class="custom-file">
				  <input type="file" id="simplistics_user_profile_picture" name="simplistics_user_profile_picture" class="custom-file-input">
				  <span class="custom-file-control"></span>
				</label>
			  </div>
			</div>
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group" style="margin-top:20px;">
			    <label for="simplistics_user_firstname" style="font-size:14px" >First Name</label>
			    <input type="text" class="form-control" id="simplistics_user_firstname" name="simplistics_user_firstname" value="<?=(!empty($name[0]) ? $name[0] : ""); ?>">
	   		</div>
	   		<div class="form-group" style="margin-top:20px;">
			    <label for="simplistics_user_lastname" style="font-size:14px">Last Name</label>
			    <input type="text" class="form-control" id="simplistics_user_lastname" name="simplistics_user_lastname" value="<?=(!empty($name[1]) ? $name[1] : ""); ?>">
	   		</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 button_blue_container">
			<input type="hidden" name="simplistics_profile_nonce" value="<?php echo wp_create_nonce('simplistics-profile-nonce'); ?>"/>
			<a href="#" class="btn_blue" id="cropped">SAVE</a>
		</div>
	</div>
