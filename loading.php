
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UEP DORM DASH</title>

</head>
<body>
  <div class="bg"></div>
  <div class="text">
    <h2>WELCOME TO</h2>
    <h1>UEP DORM DASH</h1>

    <!-- spinner: placed directly under the H1 -->
    <div class="spinner" role="status" aria-live="polite" aria-label="Loading"> 
      <div class="ring"></div>
    </div>
  </div>
  <img src="res/logo1.png" alt="UEP Logo" class="logo">
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

    .bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      /* use local res/kalabaw3.jpeg and avoid absolute /res path and spaces */
      background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('res/kalabaw3.jpeg');
      background-repeat: no-repeat, no-repeat;
      background-position: center center, center center;
      background-size: cover, cover;
      filter: blur(1px);
      z-index: 0;
    }

    .text {
      position: relative;
      z-index: 1;
      text-align: center;
    }

    h1 {
      font-size: 7em;
      margin: 0;
      color: #059BE5;
      font-weight: bold;
      font-family: 'Paytone One', sans-serif;
      
    }

    h2 {
      font-size: 4em;
      margin: 0;
      font-weight: bold;
      font-family: 'Paytone One', sans-serif;
      color: white;
      letter-spacing: 5px;
    }
    .logo {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 96px;
      height: 96px;
      z-index: 2;
    }

    /* Loading spinner */
    .spinner {
      position: relative;
      z-index: 2;
      margin-top: 20px; /* small gap under the h1 */
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .spinner .ring {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      border: 8px solid rgba(255,255,255,0.15);
      border-top-color: #059BE5;
      animation: spin 1s linear infinite;
      box-shadow: 0 6px 18px rgba(0,0,0,0.25);
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

  </style>
  <!-- load shared scripts (includes redirect when on loading.php) -->
  <script src="css/script.js"></script>
</body>
</html>




 