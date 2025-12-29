<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UEP DormDash</title>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Paytone+One&display=swap');

    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
      font-family: Arial, sans-serif;
    }

    /* Background */
    .bg {
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
        url("res/kalabaw1.jpeg");
      background-size: cover;
      background-position: center;
      filter: blur(1px);
      z-index: 0;
    }

    .logo {
      position: absolute;
      top: 10px;
      left: 1400px;
      width: 100px;   /* adjust size here */
      height: auto;
      z-index: 2;
    }

    /* MAIN TITLE TEXT */
    .text {
      text-align: center;
      position: relative;
      z-index: 2;
      margin-bottom: 15rem;
    
    }

    .text h2 {
      font-size: 1.8rem;
      font-family: 'Paytone One', sans-serif;
      letter-spacing: 4px;
      color: white;
      margin: 0;
    }

    .text h1 {
      font-size: 4.5rem;
      font-family: 'Paytone One', sans-serif;
      color: #059BE5;
      margin: 10px 0 0 0;
    }

    /* BUTTON CONTAINER */
    .select-btn {
      position: absolute;
      bottom: 80px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 20px;
      z-index: 2;
      flex-wrap: wrap;
      justify-content: center;
    }

    /* BUTTON STYLE */
    .owner,
    .renter {
      appearance: none;
      background-color: #2f80ed;
      border: none;
      border-radius: 10px;
      color: white;
      font-size: 20px;
      font-weight: 600;
      font-family: Arial, sans-serif;
      padding: 12px 40px;
      text-decoration: none;
      cursor: pointer;
      transition: 0.3s;
      white-space: nowrap;
      min-width: 200px;
      text-align: center;
      display: inline-block;
    }

    .owner:hover,
    .renter:hover {
      background-color: #1366d6;
      box-shadow: rgba(0, 0, 0, 0.1) 0 4px 20px;
    }

    .owner:active,
    .renter:active {
      transform: translateY(2px);
    }
  </style>

</head>

<body>

  <div class="bg"></div>

  <!-- Centered Logo -->
  <img src="res/logo1.png" alt="UEP Logo" class="logo">

  <!-- Text -->
  <div class="text">
    <h2>Welcome to</h2>
    <h1>UEP DormDash</h1>
  </div>

  <!-- Buttons -->
  <div class="select-btn">
    <a href="renter/login.php" class="renter">Are you a renter?</a>
    <a href="owner/ownerlogin.php" class="owner">Are you an owner?</a>
  </div>

</body>

</html>