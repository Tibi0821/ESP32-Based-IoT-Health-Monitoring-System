<?php
session_start();
$parola_corecta = "admin123";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['parola']) && $_POST['parola'] === $parola_corecta) {
        $_SESSION['autentificat'] = true;
        header("Location: index.html");
        exit;
    } else {
        $eroare = "Parolă greșită!";
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Autentificare</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f0f0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-box {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      text-align: center;
    }
    input, button {
      padding: 10px;
      margin: 10px 0;
      width: 100%;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
    .error { color: red; font-size: 0.9em; }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Autentificare</h2>
    <form method="POST">
      <input type="password" name="parola" placeholder="Parola..." required>
      <button type="submit">Intră în sistem</button>
      <?php if (isset($eroare)) echo "<p class='error'>$eroare</p>"; ?>
    </form>
  </div>
</body>
</html>
