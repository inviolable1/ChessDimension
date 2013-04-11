<script type="text/ng-template" id="login_index.html">
	<form name="loginForm" ng-submit="submitLogin()">
		<label for="username">USERNAME: </label>
		<input id="username" type="text" ng-model="loginForm.username"/>	
		<span> {{validationErrors}} </span>
		<label for="password">PASSWORD: </label>
		<input id="password" type="password" ng-model="loginForm.password"/>
		<label for="rememberMe">REMEMBER ME: </label>
		<input id="rememberMe" type="checkbox" ng-model="loginForm.rememberMe"/>
		<button type="submit name="submit" value="submit>Submit the Form</button>
	</form>
</script>