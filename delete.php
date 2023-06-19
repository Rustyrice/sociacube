<?php

include("classes/autoload.php");
$image_class = new Image();

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

$Post = new Post();
$msg_class = new Messages();


if (isset($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "/delete/")) {

	$_SESSION['return_to'] = $_SERVER['HTTP_REFERER'];
}

$ERROR = "";
if (isset($URL[1])) {

	if ($URL[1] == "msg") {

		$MESSAGE = $msg_class->read_one($URL[2]);

		if (!$MESSAGE) {

			$ERROR = "Accesss denied! you cant delete this message!";
		}
	} elseif ($URL[1] == "thread") {

		$MESSAGE = false;

		if (isset($URL[2])) {

			$MESSAGE = $msg_class->read_one_thread($URL[2]);
		}
		if (!$MESSAGE) {

			$ERROR = "Accesss denied! you cant delete this thread!";
		}
	} else {

		$ROW = $Post->get_one_post($URL[1]);

		if (!$ROW) {

			$ERROR = "No such post was found!";
		} else {

			if (!i_own_content($ROW)) {

				$ERROR = "Accesss denied! you cant delete this file!";
			}
		}
	}
} else {

	$ERROR = "No such post was found!";
}


//if something was posted
if ($ERROR == "" && $_SERVER['REQUEST_METHOD'] == "POST") {

	if ($URL[1] == "msg") {
		$msg_class->delete_one($_POST['id']);
	} elseif ($URL[1] == "thread") {

		$msg_class->delete_one_thread($_POST['id']);
	} else {

		$Post->delete_post($_POST['postid']);
	}

	header("Location: " . $_SESSION['return_to']);
	die;
}

$pageTitle = "Delete | Sociacube";
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
<!--cover area-->
<div style="width: 800px;margin:auto;min-height: 400px;">

	<!--below cover area-->
	<div style="display: flex;">

		<!--posts area-->
		<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

			<div style="border:solid thin #aaa; padding: 10px;background-color: white;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-radius: 10px;">

				<form method="post">

					<?php

					if ($ERROR != "") {

						echo $ERROR;
					} else {

						if (isset($URL[1]) && $URL[1] == "msg") {

							echo "<div style='text-align:center;font-size:17px;'>Are you sure you want to delete this message?</div><br>";
							echo "<div style='text-align:center;font-size:12px;width:300px;margin:auto;color:gray;'>The receiver will still be able to see your deleted message.</div><br>";

							$user = new User();
							$ROW_USER = $user->get_user($MESSAGE['sender']);

							include("message_left.php");

							echo "<input type='hidden' name='id' value='$MESSAGE[id]'>";
							echo "<input id='post_button' type='submit' value='Delete' style='background-color:#ff1b1bd1'>";
							echo '<a href="' . ROOT . 'messages/read/' . $MESSAGE['receiver'] . '">';
							echo "<input id='post_button' type='button' value='Cancel' style='background-color:#1b9186;position:relative;right:20px;width:auto;'>";
							echo "</a>";
						} elseif (isset($URL[1]) && $URL[1] == "thread") {

							echo "<div style='text-align:center;font-size:17px;'>Are you sure you want to delete this thread?<br><br>";
							echo "<div style='text-align:center;font-size:12px;width:300px;margin:auto;color:gray;'>This action can't be undone.</div><br>";

							$user = new User();
							$ROW_USER = $user->get_user($MESSAGE['sender']);

							echo "<input type='hidden' name='id' value='$MESSAGE[msgid]'>";
							echo "<input id='post_button' type='submit' value='Delete' style='background-color:#fc0000c7'>";
							echo '<a href="' . ROOT . 'messages/read/' . $MESSAGE['receiver'] . '">';
							echo "<input id='post_button' type='button' value='Cancel' style='background-color:#1b9186;float:left;width:auto;'>";
							echo "</a>";
						} else {

							echo "<div style='text-align:center;font-size:17px;'>Are you sure you want to delete this post?</div><br>";
							echo "<div style='text-align:center;font-size:12px;width:300px;margin:auto;color:gray;'>This can’t be undone and it will be removed from your profile, the timeline of any accounts that follow you, and from Mybook search results.</div><br>";

							$user = new User();
							$ROW_USER = $user->get_user($ROW['userid']);

							include("post_delete.php");

							echo "<input type='hidden' name='postid' value='$ROW[postid]'>";
							echo "<input id='post_button' type='submit' value='Delete' style='background-color:#ff1b1bd1'>";
							echo '<a href="' . ROOT . 'single_post/' . $ROW['postid'] . '">';
							echo "<input id='post_button' type='button' value='Cancel' style='background-color:#1b9186;float:left;width:auto;'>";
							echo "</a>";
						}
					}
					?>


					<br style="clear: both;">
				</form>
			</div>


		</div>
	</div>

</div>

</body>

</html>