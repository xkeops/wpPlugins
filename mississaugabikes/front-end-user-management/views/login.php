<div class="bike-challenge-enable-bootstrap">
<div class="container-fluid">
	<div class="row" style="position:relative;height:98vh;">
		<div class="img_left_registration">
			<img src="<?=plugins_url( '../img/img-login.jpg' , __FILE__ )?>">
		</div>
		<div class="offset-md-6 col-md-6 registration_login_col_container">
			<form class="form-right-login" method="POST">
				<!-- <div class="row"> -->
					<!-- <div class="col-md-6"> -->
					<p class="login_sign_title"><a class="link-active" href="#">Log in</a><span>or</span><a class="link" href="registration">Sign up</a></p>
					<!-- <div class="col-md-6">
						<div class="row">
							<div class="col-md-4 text-center">
								<a class="link-active" href="#">Log in</a>
							</div>
							<div class="col-md-2 text-center">
								<div style="color:#D2D2D2;font-size:18px">or</div>
							</div>
							<div class="col-md-4 text-center">
								<a class="link" href="registration">Sign up</a>
							</div>
							<div class="col-md-2">
							</div>
						</div>
							
					</div> -->
				<!-- 	<div class="col-md-6">
					</div>
						 -->
					<!-- </div>
				</div> -->
				<div class="row">
					<div class="col-md-10">
						<?php simplistics_show_error_messages();
						if(isset($_GET['msg']) && $_GET['msg'] === 'passwordupdate') : ?>
							<p style='color:red;'>Your password has been updated. Please login below.</p>
						<?php endif; ?>
						<div class="signup_form form-login" >
						
							
							<div class="form-group">
							    <label for="email">Email</label>
							    <input type="email" class="form-control" id="email" name="login" value="<?=(!empty($_POST["login"]) ? $_POST["login"] : ""); ?>">
		 				 	</div>
							<div class="form-group">
					    		<label for="password">Password</label>
					    		<input type="password" name="password" class="form-control" id="Password"/>
					    	</div>
							<input type="hidden" name="simplistics_login_nonce" value="<?php echo wp_create_nonce('simplistics-login-nonce'); ?>"/>
							
						</div>
					</div>
					<div class="col-md-2">
					</div>		
				</div>
				<div style="margin-top:25px;" class="row">
					<div class="col-md-4 text-left">
						<button type="submit" id="login_submit_button" class="btn login_btn btn-primary" value="Login">Login</button>
					</div>
					<div class="col-md-6">
						<a style="color:#ABABAB;font-size: 14px !important;font-weight: bold;" href="reset-password">Forgot Password?</a>
					</div>
					<div class="col-md-2">
					</div>
				</div>
			</form>
		</div>
		
	</div>
</div>
</div>