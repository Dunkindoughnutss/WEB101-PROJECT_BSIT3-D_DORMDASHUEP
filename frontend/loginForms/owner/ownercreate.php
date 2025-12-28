<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UEP DORMDASH - Owner Create Account</title>
    <link rel="stylesheet" href="../css/create2.css" />
</head>
<body>


<nav class="navbar">
    <div class="nav-brand">
        <a href="../homepage.php">
            <img src="../../res/logo1.png" alt="UEP logo" class="nav-logo"/>
            <span>UEP DORMDASH</span>
        </a>
    </div>
    <div class="nav-links">
        <a href="../renter/login.php">Renter Login</a>
    </div>
</nav>

<div class="container">
    <img src="../../res/logo1.png" alt="ueplogo" class="logo">
    <h1>UEP DORMDASH</h1>
    <h2>Create Owner Account</h2>

    <form action="owner_reg.php" method="post">
        <input type="hidden" name="role" value="owner">

        <label>Name</label>
        <input type="text" name="name" placeholder="Full Name" required />

        <label>Email Address</label>
        <input type="email" name="email" placeholder="example@example.com" required />

        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required />

        <label>Confirm Password</label>
        <input type="password" name="confirm" placeholder="Confirm Password" required />

        <button type="submit" name="register_owner" class="btn-login">Create Owner Account</button>
    </form>

    <div class="links">
        Already have an account? <a href="ownerlogin.php">Sign in</a>
    </div>
</div>

</body>
</html>