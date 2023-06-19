<?php

include("classes/autoload.php");

$first_name = "";
$last_name = "";
$gender = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $signup = new Signup();
  $result = $signup->evaluate($_POST);

  if ($result != "") {

    echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
    echo "The following errors occured:<br><br>";
    echo $result;
    echo "</div>";
  } else {

    header("Location:" . ROOT . "login");
    die;
  }


  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $gender = $_POST['gender'];
  $email = $_POST['email'];
}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Sign Up | Sociacube</title>
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

    Sign up to Sociacube<br><br>

    <form method="post">

      <input value="<?php echo $first_name ?>" name="first_name" type="text" id="text" placeholder="First name" required><br><br>
      <input value="<?php echo $last_name ?>" name="last_name" type="text" id="text" placeholder="Last name" required><br><br>

      <span style="font-weight: normal;">Gender:</span><br>
      <select id="text" name="gender">

        <option><?php echo $gender ?></option>
        <option>Male</option>
        <option>Female</option>

      </select>
      <br><br>
      <input value="<?php echo $email ?>" name="email" type="email" id="text" placeholder="Email" required><br><br>

      <input name="password" type="password" id="password" placeholder="Password" required><br><br>
      <input name="password2" type="password" id="password2" placeholder="Retype Password" required><br><br>

      <div class="s-password">
        <input type="checkbox" class="form-checkbox" id="s-password" onclick="showPassword()">
        <label for="s-password">Show Password</label>
      </div>

      <input type="submit" id="button" value="Sign up"><br><br><br>

    </form>

    <a href="<?= ROOT ?>login" id="login_link">
      Already have an Account? Login here.
    </a><br><br>

</body>

</html>

<script type="text/javascript">
  //show password
  function showPassword() {
    let password = document.getElementById("password");
    let password2 = document.getElementById("password2");

    if (password.type === "password" || password2.type === "password") {
      password.type = "text";
      password2.type = "text";
    } else {
      password.type = "password";
      password2.type = "password";
    }
  }
</script>