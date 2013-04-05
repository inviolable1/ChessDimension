<script type="text/ng-template" id="login_index.html">
	<form name="loginForm" ng-submit="submitLogin()">
		USERNAME: <input type="text" name="username" ng-model="loginForm.username"/>
		PASSWORD: <input type="password" name="password" ng-model="loginForm.password"/>
		REMEMBER ME: <input type="checkbox" name="rememberMe" ng-model="loginForm.rememberMe"/>
		<button type="submit name="submit" value="submit>Submit the Form</button>
	</form>
</script>