<?php
// ownercreate.php - Owner registration form (posts to register.php with role=owner)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>UEP DORMDASH - Owner Create Account</title>
<link rel="stylesheet" href="../css/create.css" />
</head>
<body>

<!-- Top navigation -->
<nav class="navbar">
    <div class="nav-brand"><a href="../homepage.php"><img src="../res/logo1.png" alt="UEP logo" class="nav-logo"/><span>UEP DORMDASH</span></a></div>
    <div class="nav-links">
        <a href="../homepage.php">Home</a>
        <a href="../renter/login.php">Renter Login</a>
        <a href="ownerlogin.php">Owner Login</a>
    </div>
</nav>

<div class="container">
    <h1>UEP DORMDASH</h1>
    <h2>Create Owner Account</h2>

    <form action="../register.php" method="post">
        <input type="hidden" name="role" value="owner">

        <label>Name</label>
        <input type="text" name="name" placeholder="Owner name" required />

        <label>Email Address</label>
        <input type="email" name="email" placeholder="example@example.com" required />

        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required />

        <label>Confirm Password</label>
        <input type="password" name="confirm" placeholder="Password" required />

        <button type="submit" class="btn-login">Create owner account</button>
    </form>

    <div class="links" style="margin-top:12px">
        Already have an account? <a href="ownerlogin.php">sign in</a>
    </div>
</div>

</body>
</html>
