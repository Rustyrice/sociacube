<?php

include("classes/autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid']);

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

	$post = new Post();
	$id = $_SESSION['mybook_userid'];
	$result = $post->create_post($id, $_POST, $_FILES);

	if ($result == "") {
		header("Location: " . ROOT . "single_post/$URL[1]");
		die;
	} else {

		echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
		echo "<br>The following errors occured:<br><br>";
		echo $result;
		echo "</div>";
	}
}

$Post = new Post();
$ROW = false;

$is_group_post = false;

$ERROR = "";
if (isset($URL[1])) {

	$ROW = $Post->get_one_post($URL[1]);
	if ($ROW['owner'] > 0) {

		$user_class = new User();
		$group_data = $user_class->get_user($ROW['owner']);
		if ($group_data['type'] == "group") {
			$is_group_post = true;
		}
	}
} else {

	$ERROR = "No post was found!";
}

$pageTitle = "Single Post | Sociacube";

?>

<?php include("header.php"); ?>

<style media="screen">
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
<!--post area-->
<?php if (!($is_group_post && $group_data['group_type'] == 'private' && !group_access($_SESSION['mybook_userid'], $group_data, 'member'))) : ?>

	<div style="width: 800px;margin:auto;min-height: 400px;">

		<!--below cover area-->
		<div style="display: flex;">

			<!--posts area-->
			<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

				<?php
				//check if this is from a notification
				if (isset($URL[2])) {
					notification_seen($URL[2]);
				}

				$user = new User();
				$image_class = new Image();

				if (is_array($ROW)) {

					$ROW_USER = $user->get_user($ROW['userid']);

					if ($ROW['parent'] == 0) {
						include("post.php");
					} else {
						$COMMENT = $ROW;
						include("comment.php");
					}
				}

				?>

				<?php if ($ROW['parent'] == 0) : ?>

					<br style="clear: both;">
					<div style="border:solid thin #aaa; padding: 10px;background-color: white;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

						<?php if (!($is_group_post && $group_data['group_type'] == 'public' && !group_access($_SESSION['mybook_userid'], $group_data, 'member'))) : ?>

							<div style="border:solid thin #aaa; padding: 8px;background-color: white;border-radius:10px;">

								<form method="post" enctype="multipart/form-data">

									<input id="autoresizing" onkeyup="enter_pressed(event)" name="post" placeholder="Write a comment..."></textarea><br>
									<input type="hidden" name="parent" value="<?php echo $ROW['postid'] ?>">
									<input id="post_button" type="submit" value="Post" onclick="send_message(event)">
									<br>
								</form>
							</div>
						<?php endif; ?>

					<?php else : ?>
						<a href="<?= ROOT ?>notifications">
							<input id="post_button" style="width:200px;float:left;cursor:pointer;position:relative;top:35px;" type="button" value="Back to notifications" />
						</a>
						<a href="<?= ROOT ?>single_post/<?php echo $ROW['parent'] ?>">
							<input id="post_button" style="width:200px;float:right;cursor:pointer;position:relative;top:35px;" type="button" value="Go to main post" />
						</a>
					<?php endif; ?>
					<br>
					<?php

					if ($ROW['comments'] > 0) {

						$comments = $ROW['comments'];
					}

					if ($ROW['comments'] > 1) {

						echo "<div style='font-size:13px;color:gray;'>$comments Comments</div>";
					} elseif ($ROW['comments'] == 1) {

						echo "<div style='font-size:13px;color:gray;'>$comments Comment</div>";
					} else {

						$comments = "";
					}
					?>
					<br>
					<?php

					$comments = $Post->get_comments($ROW['postid']);

					if (is_array($comments)) {

						foreach ($comments as $COMMENT) {
							# code...
							$ROW_USER = $user->get_user($COMMENT['userid']);

							include("comment.php");
						}
					}

					//get current url
					$pg = pagination_link();

					$ROW_USER = $user->get_user($ROW['userid']);

					?>

					<?php if (($ROW['parent'] == 0)) : ?>
						<?php if (!$is_group_post) : ?>
							<a href="<?= ROOT ?>home">
								<input id="post_button" type="button" value="Back to Home" style="width:200px;float:left;" />
							</a>
						<?php else : ?>
							<a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>">
								<input id="post_button" type="button" value="Back to Discussion" style="width:auto;float:left;" />
							</a>
						<?php endif; ?>
						<a href="<?php echo $pg['next_page'] ?>">
							<input id="post_button" type="button" value="Next Page" style="float:right;width:100px;">
						</a>
						<a href="<?php echo $pg['prev_page'] ?>">
							<input id="post_button" type="button" value="Prev Page" style="float:right;width:100px;position:relative;right:30px;">
						</a>

					<?php endif; ?>
					</div>


			</div>
		</div>

	</div>
	<!-- end post -->
<?php else : ?>
	<br><br>
	<div style="color:gray;font-size:14px;text-align:center;">Sorry, you do not have access to this content.</div>
	<br>
	<a href="<?= ROOT ?>home">
		<input id="post_button" type="button" value="Home" style="margin-right:600px;background-color: #405d9b;width:auto;">
	</a>
<?php endif; ?>
</body>

</html>

<script type="text/javascript">
	function enter_pressed(e) {
		if (e.keyCode == 13) {
			send_message(e);
		}
	}

	function send_message(e) {
		var message_text = _("autoresizing");
	}
</script>