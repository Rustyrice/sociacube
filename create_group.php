<?php

include("classes/autoload.php");

$login = new Login();
$_SESSION['mybook_userid'] = isset($_SESSION['mybook_userid']) ? $_SESSION['mybook_userid'] : 0;

$user_data = $login->check_login($_SESSION['mybook_userid'], false);

$USER = $user_data;

if (isset($URL[1])) {

  $profile = new Profile();
  $profile_data = $profile->get_profile($URL[1]);

  if (is_array($profile_data)) {
    $user_data = $profile_data[0];
  }
}

$group_name = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $group = new Group();
  $result = $group->evaluate($_POST);

  if ($result != "") {

    echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
    echo "The following errors occured:<br><br>";
    echo $result;
    echo "</div>";
  } else {

    header("Location:" . ROOT . "profile/" . $_SESSION['mybook_userid'] . "/groups");
    die;
  }


  $group_name = $_POST['group_name'];
}

$pageTitle = "Create Group | Sociacube";
?>

<?php include("header.php"); ?>

<div id="bar2">

  Create Group<br><br>

  <form method="post" action="">

    <input maxlength="20" value="<?php echo $group_name ?>" name="group_name" type="text" id="text" placeholder="Group Name" autofocus required><br><br>

    <select id="text" name="group_type">
      <option>Public</option>
      <option>Private</option>
    </select><br>
    <br>
    <input type="submit" id="button" value="Create">
    <br><br><br>

  </form>
  </body>

  </html>

  <script>
    function maxLength(el) {
      if (!('maxLength' in el)) {
        var max = el.attributes.maxLength.value;
        el.onkeypress = function() {
          if (this.value.length >= max) return false;
        };
      }
    }

    maxLength(document.getElementById("textbox"));
  </script>