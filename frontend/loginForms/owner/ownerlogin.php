<?php
// Login page + handler: renders the login form on GET and authenticates on POST.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';

	if ($email === '' || $password === '') {
		http_response_code(400);
		echo "Missing email or password.";
		exit;
	}

	// Use centralized PDO connection
	require_once __DIR__ . '/../dbcon.php';

	try {
		$stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = :email AND role = 'owner' LIMIT 1");
		$stmt->execute([':email' => $email]);
		$row = $stmt->fetch();

		if (!$row) {
			header('Location: ownerlogin.php?error=1');
			exit;
		}

		$hashed = $row['password'];
		if (password_verify($password, $hashed)) {
			session_start();
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['user_name'] = $row['name'];
			$_SESSION['role'] = $row['role'];
			header('Location: ../homepage.php');
			exit;
		} else {
			header('Location: ownerlogin.php?error=1');
			exit;
		}
	} catch (PDOException $e) {
		http_response_code(500);
		echo 'DB error: ' . htmlspecialchars($e->getMessage());
		exit;
	}

	$stmt->close();
	$conn->close();
	exit;
}

// If not POST, render the login HTML page below
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>UEP DORMDASH - Login</title>
	<link rel="stylesheet" href="../css/login.css" />
</head>
<body>

<!-- Top navigation -->
<nav class="navbar">
	<div class="nav-brand"><a href="../admin_login.php"><img src="../res/logo1.png" alt="UEP logo" class="nav-logo"/><span>UEP DORMDASH</span></a></div>
	<div class="nav-links">
		<a href="../homepage.php">Home</a>
		<a href="../renter/login.php">Renter Login</a>
		<a href="ownerlogin.php">Owner Login</a>
	</div>
</nav>

<div class="container">
	<img src="../res/logo1.png" alt="ueplogo" class="logo">
	<h1>UEP DORMDASH</h1>

	<h2> Owner Login</h2>

	<form action="ownerlogin.php" method="POST">

		<label>Email</label>
		<input type="email" name="email" placeholder="username@gmail.com" required />

		<label>Password</label>
		<input type="password" name="password" placeholder="Password" required />
		<a class="forgot" href="#">Forgot Password?</a>

		<button type="submit" class="btn-login">Sign in</button>
	</form>

	<div class="social-container">
		<div class="social-btn">G</div>
		<div class="social-btn">M</div>
		<div class="social-btn">f</div>
	</div>

	<div class="links">
		Don't have an account? <a href="ownercreate.php">Register for free</a>
	</div>
</div>

<!-- Toast notification container -->
<div id="toast">Account created successfully</div>

<script>
// Show toast if URL contains created=1 or error=1
document.addEventListener('DOMContentLoaded', function () {
	const params = new URLSearchParams(window.location.search);
	const toast = document.getElementById('toast');
	if (!toast) return;

	function showToast(text, isError = false) {
		toast.textContent = text;
		if (isError) toast.classList.add('error');
		toast.classList.add('show');
		setTimeout(() => {
			toast.classList.remove('show');
			if (isError) toast.classList.remove('error');
			// remove the query param cleanly
			const url = new URL(window.location);
			url.searchParams.delete('created');
			url.searchParams.delete('error');
			window.history.replaceState({}, '', url);
		}, 3000);
	}

	if (params.get('created') === '1') {
		showToast('Account created successfully', false);
	} else if (params.get('error') === '1') {
		showToast('Invalid email or password', true);
	}
});
</script>

</body>
</html>
