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

$pageTitle = "Page not found | Sociacube";
?>

<?php include("header.php"); ?>
<br><br>
<div style="color:gray;font-size:14px;text-align:center;">Hmm...this page doesn’t exist. Try searching for something else.</div>
<br><br>
<a href="<?= ROOT ?>home">
	<input id="post_button" type="button" value="Search" style="margin-right:600px;background-color: #405d9b;width:auto;">
</a>