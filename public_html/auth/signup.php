<?php session_start();
$pageTitle = 'HIKI - Sign Up';
$pageActive = '';
$extraStyles = ['css/pages/auth.css'];
$hideNavbar = true;
include __DIR__ . '/../../src/Includes/header.php';
?>
		<div class="container">
			<p class="login-title">Welcome</p>
			<p class="login-sub">Sign up to your account</p>
			<form action="processSignUp.php" method="POST">
				<div class="input-box">
					<input type="text" name="email" required>
					<label>Email</label>
				</div>
				<div class="input-box">
					<input type="text" name="username" required>
					<label>Username</label>
				</div>
				<div class="input-box">
					<input type="text" name="phone" required>
					<label>Phone Number</label>
				</div>
				<div class="input-box">
					<input type="password" name="password" required>
					<label>Password</label>
				</div>
				<div class="input-box">
					<input type="password" name="password2" required>
					<label>Confirm Password</label>
				</div>
				<button class="btn-login">Create an account</button>
			</form>
			<div class="signup-row">
	  <?php if (isset($_GET['error'])): ?>
		  <p class="form-error"><?php echo htmlspecialchars($_GET['error']) ?></p>
	  <?php endif; ?>
	    	</div>
		</div>
		<script src="/projet-web-gl21-chabiba/js/auth.js"></script>
		<script src="/projet-web-gl21-chabiba/js/main.js"></script>
	</div>
</body>
</html>
