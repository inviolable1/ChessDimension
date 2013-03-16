<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>ChessDimension</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<!--add favicon, apple icon here -->
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="css/main.css">
	<script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>

<body>
	<!--[if lt IE 7]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->
	<header class="navbar navbar-fixed-top">
		<div class="container">
			<div class="navbar-inner">
				<div class="logo">
					<div class="logopic">
						<a class="logo_pic" href="index.html"><img src="img/logo.jpg" /></a>
					</div>
					<div class="logoname">
					<a class="logo_name" href ="index.html">ChessDimension</a>
					</div>
				</div>
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li><a href="index.html"><strong>HOME</strong></a></li>
						<li><a href="#"><strong>MEMBERSHIP</strong></a></li>
						<li><a href="#"><strong>HELP</strong></a></li>
					</ul>
				</div>
				<div class="play-register-icons">
					<a href="#playModal" role="button" class="btn" data-toggle="modal" data-backdrop="static">
						<img src="img/play.png" id="playicon" />
						<span><strong>PLAY</strong></span>
					</a>
					<a href="#registerModal" role="button" class="btn" data-toggle="modal" data-backdrop="static">
						<img src="img/register.png" id="registericon" />
						<span><strong>REGISTER</strong></span>
					</a>
				</div>
			</div>
		</div>
	</header>
	
	<div id="playModal" class="modal hide fade">
		<div class="modal-header">  
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<span class="login"><strong>Login</strong></span>
		</div>  
		<div class="modal-body">  
            <?= form_open($form_destination_login, array('class' => 'form-horizontal','accept-charset'=> 'utf-8')) ?>
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="play_form_username">Username</label>
						<div class="controls">	
							<input type="text" id="play_form_username" name="username" placeholder="Enter your username" required />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="play_form_password">Password</label>
						<div class="controls">	
							<input type="password" id="play_form_password" name="password" placeholder="Enter your password" required />
						</div>
					</div>					
				</fieldset>	
				<span><a href="#">Forgot your password?</a></span>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary">Play!</button>
				</div>
			</form>
		</div>  
		<div class="modal-footer">  
			<span>Not a member yet? <a href="#registerModal" role="button" data-toggle="modal" data-backdrop="static">Join Now!</a></span>
			<!--need to find a way to get this modal box to disappear if registerModal is called-->
		</div>  
	</div>  
	
	<div id="registerModal" class="modal hide fade">
		<div class="modal-header">  
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<span class="login">Register</span>
		</div>  
		<div class="modal-body">  
            <?= form_open($form_destination_register, array('class' => 'form-horizontal','accept-charset'=> 'utf-8')) ?>
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="register_form_username">Username</label>
						<div class="controls">	
							<input type="text" id="register_form_username" name="username" placeholder="Desired Username" required />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="register_form_full_name">Name</label>
						<!-- Use the controls-row class to get these two inline with each other-->
						<div class="controls controls-row">
							<input type="text" id="register_form_first_name" name="name[first]" placeholder="First Name" required/>
							<input type="text" id="register_form_last_name" name="name[last]" placeholder="Last Name" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="register_form_email">Email</label>
						<div class="controls">	
							<input type="email" id="register_form_email" name="Email" placeholder="Your Email" required />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="register_form_password">Password</label>
						<div class="controls">	
							<input type="password" id="register_form_password" name="password" placeholder="New Password" required />
						</div>
					</div>					
				</fieldset>	
				<span>By clicking this button, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</span>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary">Play!</button>
				</div>
			</form>
		</div>  
		<div class="modal-footer">  
			<span>Welcome to ChessDimension!</span>
		</div>  
	</div>  