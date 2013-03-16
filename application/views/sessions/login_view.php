<div>
	<?= form_open($form_destination) ?>
		<?= ($login_messages) ? $login_messages : false ?>
		<span>Enter Login Details</span>
		<br/>
		
		<span>Username:</span>
		<input name="username" type="text" />
		<br/>
		
		<span>Password:</span>
		<input name="password" type="password" />
		<br/>
		
		<button name="submit" type="submit">Login!</button>
	</form>
</div>