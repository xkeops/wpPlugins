<?php session_start();?>
<div class="bike-challenge-enable-bootstrap">
<div class="container-fluid">
	<div class="row" style="position:relative;height:98vh;">
		<div class="img_left_registration">
			<img src="<?=plugins_url( '../img/img-login.jpg' , __FILE__ )?>">
		</div>
		<div class="offset-md-6 col-md-6 registration_login_col_container">
			<form class="form-right-login" action="" class="account_forms" id="register_form">
				<!-- <div class="row">
					<div class="col-md-6"> -->
							<p class="login_sign_title"><a class="link" href="login">Log in</a><span>or</span><a class="link-active" href="#">Sign up</a></p>
<!-- 						<div class="row">
							<div class="col-md-4 text-center">
								<a class="link" href="#">Log in</a>
							</div>
							<div class="col-md-2 text-center">
								<div style="color:#D2D2D2;font-size:18px">or</div>
							</div>
							<div class="col-md-4 text-center">
								<a class="link-active" href="registration">Sign up</a>
							</div>
							<div class="col-md-2">
							</div>
						</div>
							 -->
					<!-- </div> -->
				<!-- 	<div class="col-md-6">
					</div> -->
				<!-- </div> -->
				<div class="row">
					<div class="col-md-12" style="color:red;">
						<?
						echo $_SESSION['errors_registration'];
						?>
					</div>
					<div id="signup_form" class=" signup_form col-md-10 d-flex align-items-center">
					<?php wp_nonce_field( 'simplistics-register-nonce', 'simplistics_register_nonce' ); ?>
						
						<section id="form-part-1">
							<div class="row">
								<div class="form-group col-md-12">
									<label id="first_name_label" for="first_name">First Name</label>
									<input type="text" class="form-control" id="first_name" name="first_name" value="<?=$_SESSION["value_first_name"]?>" placeholder="Your first name"/>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label id="last_name_label" for="last_name">Last Name</label>
									<input type="text" class="form-control" id="last_name" name="last_name" value="<?=$_SESSION["value_last_name"]?>" placeholder="Your last name"/>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label id="email_label" for="email">Email</label>
									<input type="email" class="form-control" id="email" value="<?=$_SESSION["value_email"]?>" placeholder="Your email" name="simplistics_register_email"/>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label id="password_label" for="password">Password <small>(It must contain at least one number and one upper case letter)</small></label>
									<input type="password" class="form-control" id="password" name="password" placeholder="Your password"/>
								</div>
							</div>
						</section>

						
						<section id="form-part-2" style="display:none">
							<div class="row">	
								<div class="form-group col-md-6">
									
										<label id="gender_label" for="gender">Gender</label>
										<select type="gender" class="form-control" id="gender" name="gender">
											<option disabled selected value>Select an option</option>
											<option value="Male">Male</option>
											<option value="Female">Female</option>
											<option value="Other">Other</option>
										</select>
									
								</div>
								<div class="form-group col-md-6">
									
										<label id="age_label" for="age">Age</label>
										<input type="age" class="form-control" value="<?=$_SESSION["value_age"]?>" id="age" name="age"/>
									
								</div>
								<!--<div class="form-group col-md-6">
									
										<label id="phone_label" for="phone">Phone</label>
										<input type="phone" class="form-control" id="phone" value="<?=$_SESSION["value_phone"]?>" name="phone"/>
									
								</div>-->

								<!-- <div class="form-group col-md-12">
									<label id="address_label" for="address">Address</label>
									<input type="address" class="form-control" id="address" name="address" value="<?=$_SESSION["value_address"]?>" placeholder="House number and street name"/>
								</div> -->
							</div>
							<div class="row">
								<!-- <div class="form-group col-md-4">
									
										<label id="address2_label" for="city">City</label>
										<input type="city" class="form-control" id="city" name="city" value="<?=$_SESSION["value_city"]?>" placeholder="City"/>
									
								</div>
								<div class="form-group col-md-4">
									<label id="province_label" for="province">Province</label>
									<select class="form-control" id="province" name="province"> 
										<option value="AB">Alberta</option>
										<option value="BC">British Columbia</option>
										<option value="MB">Manitoba</option>
										<option value="NB">New Brunswick</option>
										<option value="NL">Newfoundland and Labrador</option>
										<option value="NS">Nova Scotia</option>
										<option value="ON" selected>Ontario</option>
										<option value="PE">Prince Edward Island</option>
										<option value="QC">Quebec</option>
										<option value="SK">Saskatchewan</option>
										<option value="NT">Northwest Territories</option>
										<option value="NU">Nunavut</option>
										<option value="YT">Yukon</option>
									</select>
								</div> -->
								<div class="form-group col-md-12">
									
										<label id="postal_code_label" for="postal_code">Postal Code</label>
										<input type="postal_code" class="form-control" id="postal_code" value="<?=$_SESSION["value_postal"]?>" name="postal_code" placeholder="Postal code"/>
									
								</div>
							</div>
							<div class="row">
							<?php 
								$questions = Question::getListPublishedQuestions();
								foreach($questions as $question){
							?>
								<div class="form-group col-md-12">
									<label for="question-<?=$question->id?>"><?=$question->question?></label>
									<? if($question->dropdown==0){?>
									<input type="text" class="form-control question" name="question-<?=$question->id?>" placeholder="Answer"/>
									<? }else{?>
									<select class="form-control question" name="question-<?=$question->id?>">
										<? foreach(Question::getListChoices($question->id) as $choice){?>
											<option value="<?=$choice->answer?>"><?=$choice->answer?></option>
										<?}?>
									</select>
									<? } ?>
								</div>
							<?php 
								}
							?>
							</div>
						</section>
							
					</div>
					<div class="col-md-2">
					</div>
					
				</div>
				
				<div style="margin-top:30px;margin-bottom:50px" class="row">
					<div class="col-md-12">
						<button type="submit" id="registration-previous-button" style="display:none;font-size: 16px;border-radius: 0;padding: 7px 40px;" class="btn login_btn btn-primary" value="Previous">Previous</button>
						<button type="submit" id="registration-next-button" class="btn login_btn btn-primary" value="Next">Next</button>
						<button type="button" data-target=".registration-modal" data-toggle="modal" style="display:none;font-size: 16px;border-radius: 0;padding: 7px 40px;" id="registration-sign-up-button" class="btn login_btn btn-primary" value="Sign up">
							Sign up
						</button>
					</div>
				</div>

				

			</form>
		</div>
	</div>
		
<div class="modal fade registration-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Complete Sign up</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div style="margin-top:20px" class="row">
					<div class="col-md-12">
						<div class="rules" style="height:120px;overflow-y: scroll">
							<?
							global $setting;
							echo $setting->getWaivers();?>
						</div>
					</div>
				</div>

				<div style="margin-top:35px" class="row d-flex align-items-center">
					<div class="form-group col-md-12 text-center">
						<input style="width:140px" type="text" maxlength="2" name="initials" id="initials" placeholder="Enter your initials">
					</div>
					<div class="form-group col-md-2 text-center">
						<input type="checkbox" name="terms" id="terms">
					</div>
					<div class="col-md-8" style="text-align: justify;font-weight:bold;font-size:12px">
						I AM AWARE OF THE NATURE AND EFFECT OF THIS ASSUMPTION OF RISK, WAIVER, INDEMNITY AND FULLY UNDERSTAND ITS TERMS, UNDERSTAND THAT I HAVE GIVEN UP SUBSTANTIAL RIGHTS BY SIGNING IT, AND SIGN IT FREELY AND VOLUNTARILY WITHOUT ANY INDUCEMENT.
					</div>
					
				</div>

				<div style="margin-top:35px" class="row d-flex align-items-center">
					<div class="form-group col-md-8">
						<div class="g-recaptcha text-center" data-sitekey="6LfwkBQUAAAAAAb0LfOIUL9fNu13GPDVc2KGC_w8"></div>
					</div>
					<div class="form-group col-md-4">
						<button disabled style="font-size: 16px;border-radius: 0;padding: 7px 40px;" type="submit" class="btn btn-primary submit-registration" id="submit_button" value="SUBMIT" name="Continue"/>SUBMIT</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	
</div>
</div>