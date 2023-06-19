<?php

session_start();

include("classes/connect.php");
include("classes/login.php");

$email = "";
$password = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $login = new Login();
  $result = $login->evaluate($_POST);

  if ($result != "") {

    echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
    echo "The following errors occured:<br><br>";
    echo $result;
    echo "</div>";
  } else {

    header("Location:" . ROOT . "home");
    die;
  }


  $email = $_POST['email'];
  $password = $_POST['password'];
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Log In | Sociacube</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../../favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../../favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../favicon/favicon-16x16.png">
  <link rel="manifest" href="favicon/site.webmanifest">
  <link href="https://fonts.googleapis.com/css2?family=Leckerli+One&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<style media="screen">
  <?php include 'css/main.css'; ?><?php include 'css/responsive.css'; ?>
</style>

<body style="font-family: tahoma;background-color: #e9ebee;margin:0;padding:0;">

  <div id="bar" style="height:60px;">

    <div id="title" style="font-size:40px;text-align:center;cursor:default;">Sociacube</div>

  </div>

  <div id="bar2">

    <form method="post">
      Log in to Sociacube<br><br>

      <input name="email" value="<?php echo $email ?>" type="email" id="text" placeholder="Email"><br><br>
      <input name="password" value="<?php echo $password ?>" type="password" id="text" placeholder="Password"><br><br>

      <input type="submit" id="button" value="Log In"><br><br><br>

    </form>

    <a href="<?= ROOT ?>signup" id="signup_link">
      Don't have an Account? Signup here.
    </a><br><br>
</body>

</html>