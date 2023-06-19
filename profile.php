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

//posting starts here
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  include("change_image.php");

  if (isset($_POST['first_name'])) {

    $settings_class = new Settings();
    $settings_class->save_settings($_POST, $_SESSION['mybook_userid']);
  } elseif (isset($_POST['post'])) {

    $post = new Post();
    $id = $_SESSION['mybook_userid'];
    $result = $post->create_post($id, $_POST, $_FILES);

    if ($result == "") {
      header("Location: " . ROOT . "profile");
      die;
    } else {

      echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
      echo "<br>The following errors occured:<br><br>";
      echo $result;
      echo "</div>";
    }
  }
}

//error handling for settings page

$first_name = "";
$last_name = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $settings_class = new Settings();
  $signup = new Signup();
  $settings = $settings_class->get_settings($_SESSION['mybook_userid']);
  $result = $settings_class->evaluate($_POST);

  if ($result != "") {

    echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
    echo "The following errors occured:<br><br>";
    echo $result;
    echo "</div>";
  }

  $first_name = $_POST['first_name'];

  $last_name = $_POST['last_name'];
}

//collect posts
$post = new Post();
$id = $user_data['userid'];

$posts = $post->get_posts($id);

//collect friends
$user = new User();

$friends = $user->get_following($user_data['userid'], "user");

$image_class = new Image();

//check if this is from a notification
if (isset($URL[2])) {
  notification_seen($URL[2]);
}

$pageTitle = "Profile | Sociacube";

?>

<?php include("header.php"); ?>

<!-- change profile image area-->
<div id="change_profile_image">
  <div style="max-width:600px;margin:auto;min-height: 400px;flex: 2.5;padding: 20px;padding-right: 0px;">

    <form method="post" action="<?= ROOT ?>profile/profile" enctype="multipart/form-data">
      <div style="border:solid thin #aaa; padding: 10px;background-color:white;">

        <input type="file" name="file" accept="image/*">
        <input id="post_button" type="submit" style="width:120px;" value="Change">
        <br>
        <p style="width:100px;float:left;text-decoration:none;color:gray;font-size:10px;padding:10px;">Press Esc to go back</p>
        <br>
        <div style="text-align: center;">
          <br><br>
          <?php
          if ($user_data['profile_image']) {
            echo "<img src='" . ROOT . "$user_data[profile_image]' style='max-width:300px;' >";
          }
          ?>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- change cover image area-->
<div id="change_cover_image">
  <div style="max-width:600px;margin:auto;min-height: 400px;flex: 2.5;padding: 20px;padding-right: 0px;">

    <form method="post" action="<?= ROOT ?>profile/cover" enctype="multipart/form-data">
      <div style="border:solid thin #aaa; padding: 10px;background-color: white;">

        <input type="file" name="file" accept="image/*">
        <input id="post_button" type="submit" style="width:120px;" value="Change">
        <br>
        <p style="width:100px;float:left;text-decoration:none;color:gray;font-size:10px;padding:10px;">Press Esc to go back</p>
        <br>
        <div style="text-align: center;">
          <br><br>
          <?php
          if ($user_data['cover_image']) {
            echo "<img src='" . ROOT . "$user_data[cover_image]' style='max-width:300px;' >";
          }
          ?>
        </div>
      </div>
    </form>
  </div>
</div>

<!--cover area-->
<div style="width: 800px;margin: auto;min-height: 400px;">

  <div style="background-color: white;text-align: center;color: #405d9b;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

    <?php

    $image = "images/image_cover.jpg";
    if (file_exists($user_data['cover_image'])) {
      $image = $image_class->get_thumb_cover($user_data['cover_image']);
    }
    ?>

    <img src="<?php echo ROOT . $image ?>" style="width:100%;">


    <span style="font-size: 12px;">
      <?php

      $image = "images/user_male.jpg";
      if ($user_data['gender'] == "Female") {
        $image = "images/user_female.jpg";
      }
      if (file_exists($user_data['profile_image'])) {
        $image = $image_class->get_thumb_profile($user_data['profile_image']);
      }
      ?>

      <img id="profile_pic" src="<?php echo ROOT . $image ?>"><br />

      <?php if (i_own_content($user_data)) : ?>

        <a class="change_image" onclick="show_change_profile_image(event)" href="<?= ROOT ?>change_profile_image/profile">Change Profile Image</a> |
        <a class="change_image" onclick="show_change_cover_image(event)" href="<?= ROOT ?>change_profile_image/cover">Change Cover Image</a>

      <?php endif; ?>

    </span>
    <br>
    <div style="font-size: 20px;color: black;">
      <a href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>" class="username" style="color:black;font-weight:bold;">
        <?php echo $user_data['first_name'] . " " . $user_data['last_name'] ?>
      </a>
      <br>
      <span id="tag_name">@<?php echo $user_data['tag_name'] ?></span>

      <?php if (!i_own_content($user_data)) : ?>
        <br>
        <span id="about"><?php echo $user_data['about'] ?></span>
      <?php endif; ?>

      <?php
      if (i_follow($user_data['userid'])) {
        $i_follow = "Following";
        $bg_color = "white";
        $text_color = "black";
        $border = "solid thin";
      } else {
        $i_follow = "Follow";
        $bg_color = "#405d9b";
        $text_color = "white";
        $border = "none";
      }
      ?>

      <br>
      <a href="<?= ROOT ?>like/user/<?php echo $user_data['userid'] ?>">
        <?php if ($user_data['userid'] != $_SESSION['mybook_userid']) {
          echo "<input id='post_button' type='button' value='" . $i_follow . "' style='margin-right:10px;color:" . $text_color . ";background-color:" . $bg_color . ";border:" . $border . ";border-color:black;width:auto;'>";
        }
        ?>
      </a>

      <?php if (!($user_data['userid'] == $_SESSION['mybook_userid'])) : ?>
        <a href="<?= ROOT ?>messages/new/<?= $user_data['userid'] ?>">
          <input id="post_button" type="button" value="Message" style="margin-right:10px;background-color: #1b9186;width:auto;">
        </a>
      <?php endif; ?>

    </div>
    <br>
    <br>
    <?php
    if ($user_data['userid'] == $_SESSION['mybook_userid']) {
      echo '<a id="menu_buttons" href="' . ROOT . 'home"><div>Home</div></a>';
    }
    ?>

    <a id="menu_buttons" href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>/about">
      <div>About</div>
    </a>
    <a id="menu_buttons" href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>/followers">
      <div>Followers</div>
    </a>
    <a id="menu_buttons" href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>/following">
      <div>Following</div>
    </a>
    <a id="menu_buttons" href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>/photos">
      <div>Photos</div>
    </a>

    <?php
    if ($user_data['userid'] == $_SESSION['mybook_userid']) {
      echo '<a id="menu_buttons" href="' . ROOT . 'profile/' . $user_data['tag_name'] . '/groups"><div>Groups</div></a>';
      echo '<a id="menu_buttons" href="' . ROOT . 'profile/' . $user_data['tag_name'] . '/settings"><div>Settings</div></a>';
    }
    ?>
  </div>



  <!--below cover area-->
  <?php

  $section = "default";
  if (isset($URL[2]) && !is_numeric($URL[2])) {

    $section = $URL[2];
  }

  if ($section == "default") {

    include("profile_content_default.php");
  } elseif ($section == "following") {

    include("profile_content_following.php");
  } elseif ($section == "followers") {

    include("profile_content_followers.php");
  } elseif ($section == "about") {

    include("profile_content_about.php");
  } elseif ($section == "settings") {

    include("profile_content_settings.php");
  } elseif ($section == "photos") {

    include("profile_content_photos.php");
  } elseif ($section == "groups") {

    include("profile_content_groups.php");
  }


  ?>

</div>

</body>

</html>

<script type="text/javascript">
  function show_change_profile_image(event) {

    event.preventDefault();
    var profile_image = document.getElementById("change_profile_image");
    profile_image.style.display = "block";
  }

  function hide_change_profile_image(event) {

    var profile_image = document.getElementById("change_profile_image");
    profile_image.style.display = "none";
  }

  function show_change_cover_image(event) {

    event.preventDefault();
    var cover_image = document.getElementById("change_cover_image");
    cover_image.style.display = "block";
  }

  function hide_change_cover_image(event) {

    var cover_image = document.getElementById("change_cover_image");
    cover_image.style.display = "none";
  }

  window.onkeydown = function(key) {

    if (key.keyCode == 27) {

      hide_change_profile_image();
      hide_change_cover_image();
    }
  }
</script>