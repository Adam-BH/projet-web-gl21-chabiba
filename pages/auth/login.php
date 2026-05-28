<?php session_start();
$pageTitle = 'HIKI - Login';
$pageActive = '';
$extraStyles = ['css/pages/auth.css'];
include __DIR__ . '/../includes/header.php';
?>
		<div class="container">
			<p class="login-title">Welcome Back</p>
			<p class="login-sub">Sign in to your account</p>
			<form action="processLogin.php" method="POST">
				<div class="input-box">
					<input type="text" name="email" required>
					<label>Email</label>
				</div>
				<div class="input-box">
					<input type="password" name="password" required>
					<label>Password</label>
				</div>
				<button class="btn-login">Sign in</button>
			</form>
			<div class="signup-row">
      Don't have an account? <a href="/projet-web-gl21-chabiba/pages/auth/signup.php">Sign up</a>
	  <?php if (isset($_GET['error'])): ?>
		  <p class="error">Invalid username or password.</p>
	  <?php endif; ?>
	    	</div>
		</div>
		<script src="/projet-web-gl21-chabiba/js/auth.js"></script>
		<script src="/projet-web-gl21-chabiba/js/main.js"></script>
	</div>
</body>
</html>
