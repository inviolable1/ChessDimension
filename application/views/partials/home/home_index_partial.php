<script type="text/ng-template" id="home_index.html">
	<div class = "main">
		<div class="main-article">
			<div class="container">	
				<div id="myCarousel" class="carousel slide">
					<div class="carousel-inner">
						<div id="slider1" class="active item">
							<div class="slider1">
								<div class="slider1text">
									<span id="slider1text1"><strong>Welcome to ChessDimension </br> Proof that controller is working: <span style="color:white !important">{{data}}</span></strong></span>
									<span id="slider1text2"><strong><em>A browser-based live chess app by chess lovers, for chess lovers.</em></strong></span>
									<p>At ChessDimension, we believe Chess is for <strong>everyone and anyone</strong>. 
										  Whether you're a social player or competitive player, grandmaster or patzer, we 
										  have a place for you.
									</p>
									<a href="#registerModal" role="button" data-toggle="modal" data-backdrop="static">
										<span class="joinbutton"><strong>JOIN FREE!</strong></span> 
									</a>
								</div>
								<div class="slider1img">
									<img src="img/slider1temppic.jpg" />
								</div>
								<div class="breaker"></div>
							</div>
						</div>
						<div id="slider2" class="item">
							<div class="slider2">
								<div class="slider2text">
									<span id="slider2text1"><strong>The future of Online Chess</strong></span>
									<span id="slider2text2"><strong><em>Simple, comprehensive and elegant - just the way you like it.</em></strong></span>
									<p>We've done away with all the downloads and plugins. Everything is kept simple on ChessDimension,
										so you can simply focus on playing the game you love.
									</p>
									<a href="#registerModal" role="button" data-toggle="modal">
										<span class="joinbutton"><strong>JOIN FREE!</strong></span> 
									</a>
								</div>
								<div class="slider2img">
									<img src="img/slider3temppic.jpg" />
								</div>
								<div class="breaker"></div>
							</div>
						</div>
						<div id="slider3" class="item">
							<div class="slider3">
								<div class="slider3text">
									<span id="slider3text1"><strong>One App fits All</strong></span>
									<span id="slider3text2"><strong><em>Social chess, chess training or chess career opportunities - everything you need.</em></strong></span>
									<p>We understand everyone has different needs and wants, so we've decided to offer you the lot. 
										Choose and pick what you want.
									</p>
									<a href="#registerModal" role="button" data-toggle="modal">
										<span class="joinbutton"><strong>JOIN FREE!</strong></span> 
									</a>
								</div>
								<div class="slider3img">
									<img src="img/slider3temppic.jpg" />
								</div>
								<div class="breaker"></div>
							</div>
						</div>
					</div>
					<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
					<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
					<div class="carousel-indicators-div">
						<ol class="carousel-indicators">
							<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
							<li data-target="#myCarousel" data-slide-to="1"></li>
							<li data-target="#myCarousel" data-slide-to="2"></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="second-article">
			<div class="container">
				<div class="explain-panels">
					<div class="play-panel">
						<a href="#"><h2>PLAY</h2></a>
						<p><strong>Play traditional chess or variants (e.g. Bughouse, 960) in real time</strong></p>
						<p><strong>Select your preferred time control and desired opponent strength</strong></p>
						<p><strong>Opt for rated games OR join a variety of tournaments 24/7</strong></p>
					</div>
					<div class="training-panel">
						<a href="#"><h2>TRAINING</h2></a>
						<p><strong>Find a coach to help you improve using our <em>Coach Match function</em></strong></p>
						<p><strong>Take online lessons right here with the integrated <em>video chat</em></strong></p>
						<p><strong>Analyse positions and games using the <em>Engine Analysis</em> feature</strong></p>
				   </div>
					<div class="business-panel">
						<a href="#"><h2>BUSINESS</h2></a>
						<p><strong>Take home real prize money from winning Cash King tournaments</strong></p>
						<p><strong>Unlock cash bonuses by accruing points in our Grand Prix series</strong></p>
						<p><strong>Startup your own online coaching career (subject to accreditation)</strong></p>
					</div>
					<div class="breaker"></div>
				</div>
				<div class="socialmedia-panel">
					<span><strong>Find us on</strong></span>
					<a href="www.facebook.com"><img src="img/facebook.jpg" /> </a>
				</div>
			</div>		
		</div>
	</div>
</script>