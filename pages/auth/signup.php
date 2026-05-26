<?php session_start(); ?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>HIKI - Sign Up</title>
		<link rel="stylesheet" href="../../css/node_modules/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/pages/auth.css" />
	</head>
	<body>
		<canvas id="starCanvas" style="position:fixed;inset:0;z-index:0;pointer-events:none;"></canvas>
            <a href="../../../" class="navbar-brand">HIKI</a>
		<div class="container">
			<p class="login-title">Wecome</p>
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
		  <p style="color:red;"><?php echo $_GET['error'] ?></p>
	  <?php endif; ?>
    		</div>
		</div>
		<script src="../../js/auth.js"></script>
		<script src="../../js/main.js"></script>
	</body>
</html>
