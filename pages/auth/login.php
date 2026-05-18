<?php session_start(); ?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>HIKI - Login</title>
		<link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/pages/auth.css" />
	</head>
	<body>
		<canvas id="starCanvas" style="position:fixed;inset:0;z-index:0;pointer-events:none;"></canvas>
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
      Don't have an account? <a href="/pages/auth/signup.php">Sign up</a>
	  <?php if (isset($_GET['error'])): ?>
		  <p style="color:red;">Invalid username or password.</p>
	  <?php endif; ?>
    		</div>
		</div>
		<script src="../../js/auth.js"></script>
		<script src="../../js/main.js"></script>
	</body>
</html>
