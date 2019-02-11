<?php $user = wp_get_current_user()->data;
		$name = explode(" ",$user->display_name);?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<span>EDIT PROFILE</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card" style="max-width:292px;">
				<div style="height: 280px;" class="card-header">
		    		<img id="my-image" src="<?php if($picture) echo $picture;?>" alt="No picture" />
		  		</div>
				
				
			  <div class="card-block" style="height: 60px">
			    <input name="simplistics_user_profile_picture" id="simplistics_user_profile_picture" type="file" />
			   
			  </div>
			</div>
			<?php
			$picture = DIR_URL."pictures/".$user->ID.".png"; 
			?>
			
			<input type="button" id="cropped" value="Save your profile picture">
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
			    <label for="simplistics_user_firstname">First Name</label>
			    <input type="text" class="form-control" id="simplistics_user_firstname" name="simplistics_user_firstname">
	   		</div>
	   		<div class="form-group">
			    <label for="simplistics_user_lastname">First Name</label>
			    <input type="text" class="form-control" id="simplistics_user_lastname" name="simplistics_user_lastname">
	   		</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="half">	
		<h1><strong>Account</strong> Details</h1>
		<?php 
			simplistics_show_error_messages();
			if (isset($_GET["msg"])) {
				if($_GET["msg"]=="success") {
					echo '<p class="msg success">Your profile was successfully modified.</p>';
				}
			}
		?>
		
		<form id="simplistics_account_update_form" class="simplistics_form" action="" method="POST" enctype="multipart/form-data">
			<div class="input-group fixed-width">
				<span class="input-group-addon">First Name</span>
			    <input name="simplistics_user_firstname" id="simplistics_user_firstname" class="required" type="text" value="<?=(!empty($name[0]) ? $name[0] : ""); ?>" />
			</div>

			<div class="input-group fixed-width">
				<span class="input-group-addon">Last Name</span>
			    <input name="simplistics_user_lastname" id="simplistics_user_lastname" class="required" type="text" value="<?=(!empty($name[1]) ? $name[1] : ""); ?>" />
			</div>

			<div class="input-group fixed-width">
				<span class="input-group-addon">Username</span>
			    <input name="simplistics_user_login" id="simplistics_user_login" class="required" type="text" value="<?=(!empty($user->user_login) ? $user->user_login : ""); ?>" />
			</div>

			<div class="input-group fixed-width">
				<span class="input-group-addon">Email</span>
				<input name="simplistics_user_email" id="simplistics_user_email" class="required" type="email" value="<?=(!empty($user->user_email) ? $user->user_email : ""); ?>"/>
			</div>
			
			<input type="hidden" name="simplistics_profile_nonce" value="<?php echo wp_create_nonce('simplistics-profile-nonce'); ?>"/>
			
			<div class="row" style="overflow:auto;padding-top:15px">
				<div class="input-group submit block" style="clear:both">
					<!--<a href="change-password" class="form-control button btn">Change Password</a>-->
				    <input type="submit" id="profile" value="Update" class="form-control button btn" />
				    <input type="submit" value="Change Password" class="form-control button btn" id='change-password-button-link' />
				</div>
			</div>
		</form>
		
	</div>	
</div>