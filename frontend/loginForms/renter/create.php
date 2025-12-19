<?php
// create.php - renders the Create Account page (form posts to register.php)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>UEP DORMDASH - Create Account</title>
<link rel="stylesheet" href="../css/create.css" />
</head>
<body>

<!-- Top navigation -->
<nav class="navbar">
    <div class="nav-brand"><a href="../homepage.php"><img src="../res/logo1.png" alt="UEP logo" class="nav-logo"/><span>UEP DORMDASH</span></a></div>
    <div class="nav-links">
        <a href="../homepage.php">Home</a>
        <a href="login.php">Renter Login</a>
        <a href="../owner/ownerlogin.php">Owner Login</a>
    </div>
</nav>

<div class="container">
    <h1>UEP DORMDASH</h1>
    <h2>Create Account</h2>

    <form action="../register.php" method="post">
        <label>Name</label>
        <input type="text" name="name" placeholder="name" required />

        <label>Email Address</label>
        <input type="email" name="email" placeholder="example@example.com" required />

        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required />

        <label>Confirm Password</label>
        <input type="password" name="confirm" placeholder="Password" required />


        <!-- Submit the registration form -->
        <button type="submit" class="btn-login">Create account</button>
    </form>

    <div class="social-container">
        <div class="social-btn">G</div>
        <div class="social-btn">M</div>
        <div class="social-btn">f</div>
    </div>

    <div class="links">
        Already have an account? <a href="login.php">sign in</a>
    </div>
</div>

</body>
</html>
