<div class="bike-challenge-enable-bootstrap">
	<div class="container-fluid">
		<div class="row d-flex align-items-center">
			<div class="img_left_registration">
				<img src="<?=plugins_url( '../img/img-login.jpg' , __FILE__ )?>">
			</div>
			<div class="offset-md-6 col-md-6 registration_login_col_container">
				<?php 
				simplistics_show_error_messages(); 
				$activation = false;
				$hidep = false;
				if (isset($_GET["msg"])) {
					if($_GET["msg"]=="emailsent") {
						echo '<p class="msg success" style="font-size:16px">Please check your email to reset your password.</p>';
						$hidep = true;
					} elseif($_GET["msg"]=="fail") {
						echo '<p class="msg fail">An error occured.</p>';
					} else {
						$activation = (int)$_GET["msg"];
					}
				}
				?>
				<?php if($activation==false): ?>
					<?php if(false === $hidep) : ?><p style="font-size:16px">Please enter your email below. A link to reset your password will be emailed to you.</p><?php endif; ?>
					<form method="post" action="" class="account_forms" id="reset_password_form" style="font-size:16px">				
	 				 	<div class="form-group">
						    <label for="email" style="font-size:16px">Email</label>
						    <input type="email" class="form-control" id="email" name="simplistics_user_email">
	 				 	</div>	 				 	
						  <div id="login_buttons" class="col-xs-12">					
							 <input style="font-size:14px" type="submit" id="submit_button" class="btn btn-primary" value="Request Reset Link"/>
							 <input type="hidden" name="simplistics_reset_password_nonce" value="<?php echo wp_create_nonce('simplistics-reset-password-nonce'); ?>"/>
						 </div>
					</form>
				<?php else: ?>
					<form method="post" action="" class="account_forms" id="reset_password_form" style="font-size:16px">
						<div class="form-group">
						    <label for="password">Password</label>
						    <input type="password" class="form-control" id="password" name="simplistics_user_pass">
						    <small id="validation_error">Your password must be 8-16 characters and must contain at least one number, one uppercase and one lowercase letter.</small>
	 				 	</div>
	 				 		<div class="form-group">
						    <label for="confirm_password">Confirm Password</label>
						    <input type="password" class="form-control" id="confirm_password" name="simplistics_user_pass_confirm">
	 				 	</div>
	 				 	<div id="login_buttons" class="col-xs-12">					
							 <input type="submit" id="submit_button" class="btn btn-primary" style="font-size:14px" value="Change Password"/>
							 <input type="hidden" name="simplistics_user_pass_hash" value="<?php echo $activation; ?>"/>
							 <input type="hidden" name="simplistics_reset_password_nonce" value="<?php echo wp_create_nonce('simplistics-reset-password-nonce'); ?>"/>
						 </div>
					 </form>
				<?php endif; ?>
			</div>	
		</div>
	</div>
</div>