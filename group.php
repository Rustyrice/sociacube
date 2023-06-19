<?php

include("classes/autoload.php");

$login = new Login();
$_SESSION['mybook_userid'] = isset($_SESSION['mybook_userid']) ? $_SESSION['mybook_userid'] : 0;

$USER = $login->check_login($_SESSION['mybook_userid'], false);

$group_data = array();

if (isset($URL[1])) {

	$group = new Group();
	$g_data = $group->get_group($URL[1]);

	if (is_array($g_data)) {
		$group_data = $g_data[0];
	}
}

//posting starts here
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	include("change_image.php");

	if (isset($_POST['first_name'])) {

		if (group_access($_SESSION['mybook_userid'], $group_data, 'admin')) {
			$settings_class = new Settings();
			$settings_class->save_settings($_POST, $group_data['userid']);
		}

		header("Location: " . ROOT . "group/" . $group_data['userid'] . "/settings");
		die;
	} elseif (isset($_POST['post'])) {

		$post = new Post();
		$id = $_SESSION['mybook_userid'];
		$owner = $group_data['userid'];
		$result = $post->create_post($id, $_POST, $_FILES, $owner);

		if ($result == "") {

			header("Location: " . ROOT . "group/" . $group_data['userid']);
			die;
		} else {

			echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
			echo "<br>The following errors occured:<br><br>";
			echo $result;
			echo "</div>";
		}
	}
}

if (count($group_data) > 0) {

	//collect posts
	$post = new Post();
	$id = $group_data['userid'];

	$posts = $post->get_posts($id, 'group');

	//collect friends
	$user = new User();

	$friends = $user->get_following($group_data['userid'], "user");

	$image_class = new Image();

	//check if this is from a notification
	if (isset($URL[3])) {
		notification_seen($URL[3]);
	}
}

$pageTitle = "Group | Sociacube";

?>

<?php include("header.php"); ?>

<style>
	#search_box {
		width: 400px;
		height: 23px;
		border-radius: 20px;
		border: none;
		padding: 4px;
		font-size: 14px;
		background-image: url(../images/search.png);
		background-size: 20px;
		background-repeat: no-repeat;
		background-position: 370px;
		outline: none;
	}
</style>

<?php if (count($group_data) > 0) : ?>

	<!--change cover image area-->
	<div id="change_cover_image" style="display:none;position:absolute;width: 100%;height: 100%;background-color: #000000aa;z-index:1;">
		<div style="max-width:600px;margin:auto;min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

			<form method="post" action="<?= ROOT ?>group/<?= $group_data['userid'] ?>/cover" enctype="multipart/form-data">
				<div style="border:solid thin #aaa; padding: 10px;background-color: white;">

					<input type="file" name="file">
					<input id="post_button" type="submit" style="width:120px;" value="Change">
					<br>
					<p style="width:100px;float:left;text-decoration:none;color:gray;font-size:10px;padding:10px;">Press Esc to go back</p>
					<div style="text-align: center;">
						<br><br>
						<?php
						if ($group_data['cover_image']) {
							echo "<img src='" . ROOT . "$group_data[cover_image]' style='max-width:500px;' >";
						}
						?>
					</div>
				</div>
			</form>

		</div>
	</div>

	<!--cover area-->
	<div style="width: 800px;margin:auto;min-height: 400px;">

		<div style="background-color: white;text-align: center;color: #405d9b;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

			<?php

			$image = "images/image_cover.jpg";
			if (file_exists($group_data['cover_image'])) {
				$image = $image_class->get_thumb_cover($group_data['cover_image']);
			}
			?>

			<img src="<?php echo ROOT . $image ?>" style="width:100%;">


			<span style="font-size: 12px;">

				<?php if (group_access($_SESSION['mybook_userid'], $group_data, 'admin')) : ?>

					<a onclick="show_change_cover_image(event)" class="change_image" href="<?= ROOT ?>change_profile_image/cover">Change Cover Image</a>

				<?php endif; ?>

			</span>
			<br>
			<div style="font-size: 20px;color: black;">
				<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>" class="username" style="color:black;font-weight:bold;">
					<?php echo $group_data['first_name'] ?><br>
				</a>
				<span style="font-size: 12px;">[<?php echo $group_data['group_type'] ?> group]</span>


				<br>
				<?php if (!group_access($_SESSION['mybook_userid'], $group_data, 'member')) : ?>
					<?php if (!group_access($_SESSION['mybook_userid'], $group_data, 'request')) : ?>

						<a href="<?= ROOT ?>join/<?= $group_data['userid'] ?>">
							<input id="post_button" type="button" value="Join Group" style="margin-right:10px;background-color:#D16F14;width:auto;">
						</a>
					<?php else : ?>

						<input id="post_button" type="button" value="Request sent" style="margin-right:10px;color:black;background-color:white;border:solid thin;width:auto;cursor:auto;">
					<?php endif; ?>
				<?php endif; ?>
				<?php if (group_access($_SESSION['mybook_userid'], $group_data, 'member')) : ?>
					<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/invite">
						<input id="post_button" type="button" value="Invite" style="margin-right:10px;background-color: #1b9186;width:auto;">
					</a>
				<?php endif; ?>


			</div>
			<br>
			<br>

			<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>" id="menu_buttons">
				<div>Discussion</div>
			</a>
			<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/about" id="menu_buttons">
				<div>About</div>
			</a>
			<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/members" id="menu_buttons">
				<div>Members</div>
			</a>
			<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/photos" id="menu_buttons">
				<div>Photos</div>
			</a>

			<?php if (group_access($_SESSION['mybook_userid'], $group_data, 'admin')) : ?>
				<?php
				$requests_count = $group->get_requests($group_data['userid']);
				$requests_str = "";
				if (is_array($requests_count)) {
					$requests_str = "(" . count($requests_count) . ")";
				}
				?>

				<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/requests" id="menu_buttons">
					<div>Requests <?= $requests_str ?></div>
				</a>
			<?php endif; ?>

			<?php
			if (group_access($_SESSION['mybook_userid'], $group_data, 'admin')) {

				echo '<a href="' . ROOT . 'group/' . $group_data['userid'] . '/settings" id="menu_buttons"><div>Settings</div></a>';
			}
			?>
		</div>

		<!--below cover area-->

		<?php

		$section = "default";
		if (isset($URL[2])) {

			$section = $URL[2];
		}

		if ($section == "default" || $section == "cover") {

			include("group_content_default.php");
		} elseif ($section == "requests") {

			include("group_content_requests.php");
		} elseif ($section == "invite") {

			include("group_content_invite.php");
		} elseif ($section == "invited") {

			include("group_content_invited.php");
		} elseif ($section == "members") {

			include("group_content_members.php");
		} elseif ($section == "about") {

			include("group_content_about.php");
		} elseif ($section == "settings") {

			include("group_content_settings.php");
		} elseif ($section == "photos") {

			include("group_content_photos.php");
		} elseif ($section == "groups") {

			include("group_content_groups.php");
		} elseif ($section == "denied") {

			include("group_content_denied.php");
		}



		?>

	</div>
<?php else : ?>
	<br><br>
	<div style="color:gray;font-size:14px;text-align:center;">Hmm...this group doesn’t exist. Try searching for something else.</div>
	<br><br>
	<a href="<?= ROOT ?>search">
		<input id="post_button" type="button" value="Search" style="margin-right:600px;background-color: #405d9b;width:auto;">
	</a>

<?php endif; ?>

</body>

</html>

<script type="text/javascript">
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

			hide_change_cover_image();
		}
	}
</script>