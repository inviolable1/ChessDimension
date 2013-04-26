		<div class="push"></div>
	</div>
	<footer ng-controller="FooterCtrl">
		<div class="container">
			<div class="footer-links">
				<ul>
					<li><a href="#">About Us</a></li>
					<li><a href="#">Terms and Conditions</a></li>
					<li><a href="#">Privacy Policy</a></li>
					<li><a href="#">Contact Us</a></li>
				</ul>
			</div>
			<div class="copyright">
				<span>Copyright &copy; 2013 ChessDimension. All rights reserved.</span>
			</div>
		</div>
	</footer>

	<!-- Client Side Templates -->
	<!--embedding the client side templates into the html. note: first parameter is the path to the partials, second is the extension of the files that we want, and the third parameter is the negations-->
	<? Template::asset('application/views/partials', 'php', array('footer_partial.php', 'header_partial.php')) ?>
	
	<!-- Pass in PHP variables to Javascript -->
	<script>
		var serverVars = {
			baseUrl: '<?= base_url() ?>',
			csrfCookieName: '<?= $this->config->item('cookie_prefix') . $this->config->item('csrf_cookie_name') ?>'
		};
	</script>

	<!-- Vendor Javascripts -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.min.js"></script>
	<script>window.angular || document.write('<script src="js/vendor/angular.min.js"><\/script>')</script>	<!--fallback if the google API did not work -->
	<script src="js/vendor/angular-resource.min.js"></script>
	<script src="js/vendor/angular-cookies.min.js"></script>
	<script src="js/vendor/angular-ui.min.js"></script>
	<script src="js/vendor/ui-bootstrap-tpls-0.2.0.min.js"></script>
	
	<!-- KineticJS -->
	<script src="js/vendor/kineticjs.min.js"></script>
	
	<!-- Shims and Shivs and Other Useful Things -->
	<!--[if lt IE 9]><script src="js/vendor/es5-shim.min.js"></script><![endif]-->
	<script src="js/vendor/es6-shim.min.js"></script>
	<!--[if lt IE 9]><script src="js/vendor/json3.min.js"></script><![endif]-->
	
	<? if(ENVIRONMENT == 'development'){ ?>
		<?
			Template::asset('js', 'js', array(
				'js/main.min.js',
				'js/vendor',
			));
		?>
	<? }elseif(ENVIRONMENT == 'production'){ ?>
		<script src="js/main.min.js"></script>
		<script>
			var _gaq=[['_setAccount','<?= $google_analytics_key ?>'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
		</script>
	<? } ?>
	
</body>
</html>