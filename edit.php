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


$Post = new Post();

$ERROR = "";
if (isset($URL[1])) {

	$ROW = $Post->get_one_post($URL[1]);

	if (!$ROW) {

		$ERROR = "No such post was found!";
	} else {

		if ($ROW['userid'] != $_SESSION['mybook_userid']) {

			$ERROR = "Accesss denied! you can't edit this file!";
		}
	}
} else {

	$ERROR = "No such post was found!";
}

if (isset($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "/edit/")) {

	$_SESSION['return_to'] = $_SERVER['HTTP_REFERER'];
}

//if something was posted
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	//if($_FILES['file']['type'] == "image/jpeg"){
	$id = $_SESSION['mybook_userid'];
	$Post->edit_post($id, $_POST, $_FILES);
	//}


	header("Location: " . $_SESSION['return_to']);
	die;
}

$pageTitle = "Edit | Sociacube";
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
<!--cover area-->
<div style="width: 800px;margin:auto;min-height: 400px;">

	<!--below cover area-->
	<div style="display: flex;">

		<!--posts area-->
		<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

			<div style="border:solid thin #aaa; padding: 10px;background-color: white;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-radius: 10px;">

				<form method="post" enctype="multipart/form-data">

					<?php

					if ($ERROR != "") {

						echo $ERROR;
					} else {
						$user_data = $login->check_login($_SESSION['mybook_userid']);

						echo "<div style='text-align:center;font-size:17px;'>Edit</div><br>";

						echo '
							<div id="textarea" style="border:solid thin #aaa; padding: 10px;background-color: white;border-radius:10px;min-height:100px;">
								<textarea id="autoresizing" name="post" placeholder="What\'s on your mind, ' . $user_data['first_name'] . '?">' . $ROW['post'] . '</textarea><br>
							';

						if (!$ROW['parent']) {
							echo '<input type="file" name="file">';
						} else {
							echo "";
						}

						echo "<input type='hidden' name='postid' value='$ROW[postid]'>";
						echo "<input id='post_button' type='submit' value='Save' style='margin:5px;'>";
						echo "</div>";

						if (file_exists($ROW['image'])) {

							$image_class = new Image();

							$ext = pathinfo($ROW['image'], PATHINFO_EXTENSION);
							$ext = strtolower($ext);

							if ($ext == "jpeg" || $ext == "jpg") {

								$post_image = $image_class->get_thumb_post($ROW['image']);

								echo "
									<br><br>
									<div style='text-align:center;'>
										<img src='" . ROOT . "$post_image' style='width:80%;border-radius:10px;' />
									</div>
									";
							} elseif ($ext == "mp4") {

								echo "
									<br><br>
									<div style='text-align:center;'>
										<video id='video' controls loop autoplay muted style='width:100%;border-radius:10px;max-height:500px;'>
											<source src='" . ROOT . "$ROW[image]' type='video/mp4'>
										</video>
									</div>
									";
							} else {
								echo "";
							}
						}

						if (!$ROW['parent']) {

							echo "
								<a href='" . ROOT . "single_post/$ROW[postid]'>
									<input id='post_button' style='width:200px;float:left;cursor:pointer;position:relative;top:20px;' type='button' value='Back to main post' />
								</a>
								";
						} else {
							echo "
								<a href='" . ROOT . "single_post/$ROW[parent]'>
									<input id='post_button' style='width:200px;float:left;cursor:pointer;position:relative;top:20px;' type='button' value='Back to main post' />
								</a>
								";
						}
					}
					?>


					<br>
				</form>
			</div>


		</div>
	</div>

</div>

</body>

</html>

<script type="text/javascript">
	//resize textarea
	textarea = document.querySelector("#autoresizing");
	textarea.addEventListener('input', autoResize, false);

	function autoResize() {
		this.style.height = 'auto';
		this.style.height = this.scrollHeight + 'px';
	}
</script>