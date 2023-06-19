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
		header("Location:" . ROOT . "home");
		die;
	} else {

		echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
		echo "<br>The following errors occured:<br><br>";
		echo $result;
		echo "</div>";
	}
}

?>

<?php include("header.php"); ?>

<style>
	html,
	body {
		height: 100%;
		overflow: hidden;
		margin: 0;
	}

	/*responsive for home page only*/
	@media (max-width: 426px) {
		#blue_bar {
			height: 50px;
			width: 800px;
		}

		#search_box {
			display: none;
		}

		#post_bar {
			margin: 0 auto;
			margin-top: 20px;
			background-color: white;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 10px;
			max-height: 890px;
			overflow-y: scroll;
			width: 100%;
		}

		#post_bar_home {
			display: flex;
			margin: 0 auto;
			margin-top: 20px;
			background-color: white;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			border-radius: 10px;
			padding: 20px;
			font-size: 13px;
			width: 100%;
		}

		#post_bar::-webkit-scrollbar {
			display: none;
		}

		#notification_icon {
			position: relative;
			right: -100px;
		}

		#messages_icon {
			position: relative;
			right: -100px;
		}

		#icon_edit {
			bottom: 10px;
		}

		#icon_delete {
			bottom: 10px;
		}

		.flex-item-left {
			padding: 10px;
			flex: 5%;
			float: left;
			width: 50%;
			height: 80%;
			overflow: auto;
			text-indent: 100%;
			white-space: nowrap;
		}

		.flex-item-center {
			padding: 10px;
			flex: 80%;
			float: left;
			width: 100%;
			height: 100%;
			overflow: auto;
		}

		.flex-item-center::-webkit-scrollbar {
			display: none;
		}

		#notif_alert {
			right: -105px;
		}

		#message_alert {
			right: -105px;
		}

		#search_button {
			background-color: white;
			border-radius: 50%;
			padding: 5px;
			transition: 0.3s;
			display: block;
		}

		#search_button:hover {
			opacity: 0.8;
			transition: 0.3s;
		}

		#textarea_home {
			width: 100%;
			position: relative;
			right: 12px;
		}

		#posts_home {
			width: 100%;
			position: relative;
			right: 20px;
		}

		a.link {
			margin: 15px;
		}
	}

	@media (max-width: 376px) {
		#blue_bar {
			height: 50px;
			width: 800px;
		}

		#search_box {
			display: none;
		}

		#notification_icon {
			right: -50px;
		}

		#messages_icon {
			right: -50px;
		}

		#icon_edit {
			position: relative;
			bottom: 10px;
		}

		#icon_delete {
			position: relative;
			bottom: 10px;
		}

		#notif_alert {
			right: -55px;
		}

		#message_alert {
			right: -55px;
		}

		#search_button {
			background-color: white;
			border-radius: 50%;
			padding: 5px;
			transition: 0.3s;
			display: block;
		}

		#search_button:hover {
			opacity: 0.8;
			transition: 0.3s;
		}

		#textarea_home {
			width: 100%;
			position: relative;
			right: 12px;
		}

		#posts_home {
			width: 100%;
			position: relative;
			right: 20px;
		}
	}

	@media (max-width: 321px) {

		#icon_edit {
			bottom: 25px;
		}

		#icon_delete {
			bottom: 25px;
		}

		#notification_icon {
			right: -5px;
		}

		#messages_icon {
			right: -5px;
		}

		#notif_alert {
			right: -10px;
		}

		#message_alert {
			right: -10px;
		}

	}
</style>

<div class="flex-container">
	<div class="flex-item-left">
		<?php include("nav_header.php"); ?>
	</div>

	<!--below cover area-->
	<div class="flex-item-center">

		<!--posts area-->
		<div style="padding: 20px;">

			<div id="textarea_home">

				<form method="post" enctype="multipart/form-data" style="margin:0;">
					<textarea id="autoresizing" name="post" placeholder="What's on your mind, <?= $user_data['first_name'] ?>?"></textarea>

					<div class="image-preview" id="imagePreview">
						<img src="" class="image-preview__image">
					</div>

					<div class="video-preview" id="videoPreview">
						<video controls loop autoplay muted class="video-preview__video" id="video" src=""></video>
					</div>

					<fieldset name="image">
						<label for="inpFile"><img id="image_icon" src="images/image_icon.png"></label>
						<input id="inpFile" type="file" name="file" style="display:none;" accept="image/*">
					</fieldset>
					<fieldset name="video">
						<label for="inpFile2"><img id="video_icon" src="images/video_icon.png"></label>
						<input id="inpFile2" type="file" name="file" style="display:none;" accept="video/*">
					</fieldset>

					<input id="post_button" type="submit" value="Post" style="margin:25px;">
				</form>
			</div>

			<!--posts-->
			<div id="posts_home">

				<?php

				$page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;
				$page_number = ($page_number < 1) ? 1 : $page_number;


				$limit = 10;
				$offset = ($page_number - 1) * $limit;

				$DB = new Database();
				$user_class = new User();
				$image_class = new Image();

				$followers = $user_class->get_following($_SESSION['mybook_userid'], "user");

				$follower_ids = false;
				if (is_array($followers)) {

					$follower_ids = array_column($followers, "userid");
					$follower_ids = implode("','", $follower_ids);
				}

				if ($follower_ids) {
					$myuserid = $_SESSION['mybook_userid'];
					$sql = "select * from posts where parent = 0 and owner = 0 and (userid = '$myuserid' || userid in('" . $follower_ids . "')) order by id desc"; //limit $limit offset $offset
					$posts = $DB->read($sql);
				}

				if (isset($posts) && $posts) {

					foreach ($posts as $ROW) {

						$user = new User();
						$ROW_USER = $user->get_user($ROW['userid']);

						include("post.php");
					}
					echo "
					<div id='post_bar' style='margin-top:30px;padding:20px;'>
						<div>
							<h3 style='font-size:21px;color:gray;text-align:center;'>
								No more posts
							</h3>
						</div>
						<div style='font-size:15px;color:gray;text-align:center;'>
							Add more friends to see more posts in your News Feed.
						</div>
						<br>
						<div style='text-align:center;'>
							<a href='" . ROOT . "search?find=' style='text-decoration:none;'>
								<input type='button' value='Find Friends' style='background-color:#1877F2;width:auto;font-size:14px;border-radius:5px;margin:auto;border:none;color:white;padding:4px;cursor:pointer;'>
							</a>
						</div>
					</div>
					<br><br><br>
					";
				} else {
					echo "
					<div id='post_bar' style='margin-top:30px;padding:20px;'>
						<br>
						<div style='font-size:18px;color:gray;text-align:center;'>
							No posts to show
						</div>
						<br>
						<div style='font-size:11px;color:gray;text-align:center;'>
							Posts from people you follow will be displayed here
						</div>
						<br>
						<div style='text-align:center;'>
							<a href='" . ROOT . "search?find=' style='text-decoration:none;'>
								<input type='button' value='Find Friends' style='background-color:#1877F2;width:auto;font-size:14px;border-radius:5px;margin:auto;border:none;color:white;padding:4px;cursor:pointer;'>
							</a>
						</div>
					</div>
					";
				}

				//get current url
				// $pg = pagination_link();

				?>

				<!-- <a href="<?= $pg['next_page'] ?>">
					<input id="post_button" type="button" value="Next Page" style="float: right;width:150px;position:relative;bottom:10px;">
				</a>
				<a href="<?= $pg['prev_page'] ?>">
					<input id="post_button" type="button" value="Prev Page" style="float: left;width:150px;position:relative;bottom:10px;">
				</a> -->
				<br>
			</div>
		</div>
	</div>

	<div class="flex-item-right">
		<div id="post_bar">
			<h3 style="display:inline-block;position:relative;left:10px;">Who to follow</h3>
			<a href="<?= ROOT ?>search?find=" class="text-hover-underline" style="position:relative;top:23px;right:10px;">Show more</a>
			<?php

			$user = new User;
			$friends = $user->get_friends($user_data['userid'], "user");

			if ($friends) {

				foreach ($friends as $friend) {

					$FRIEND_ROW = $user_class->get_user($friend['userid']);
					include("user.php");
				}
			}
			?>
		</div>
		<div id="post_bar">
			<h3 style="display:inline-block;position:relative;left:10px;">Trends for you</h3>
			<?php

			$user = new User;
			$friends = $user->get_friends($user_data['userid'], "user");

			if ($friends) {

				foreach ($friends as $friend) {

					$FRIEND_ROW = $user_class->get_user($friend['userid']);
					include("tags.php");
				}
			}
			?>
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

	//preview image in textarea
	const inpFile = document.getElementById("inpFile");
	const previewContainer = document.getElementById("imagePreview");
	const previewImage = previewContainer.querySelector(".image-preview__image");

	inpFile.addEventListener("change", e => {
		const file = e.target.files[0];

		if (file) {

			e.target.form.video.disabled = true;

			const reader = new FileReader();

			previewContainer.style.display = "block";
			previewImage.style.display = "block";

			reader.addEventListener("load", e => {
				previewImage.setAttribute("src", e.target.result);
			});

			reader.readAsDataURL(file);
		} else {
			previewImage.style.display = null;
			previewImage.setAttribute("src", "");
		}
	});

	// preview video in textarea
	const inpFile2 = document.getElementById('inpFile2');
	const video = document.getElementById('video');
	const previewVideoContainer = document.getElementById("videoPreview");
	const previewVideo = previewVideoContainer.querySelector(".video-preview__video");
	const videoSource = document.createElement('source');

	inpFile2.addEventListener('change', e => {
		const file = e.target.files[0];

		if (file) {

			e.target.form.image.disabled = true;

			let reader = new FileReader();

			previewVideoContainer.style.display = "block";

			reader.addEventListener("load", e => {
				let buffer = e.target.result;

				let videoBlob = new Blob([new Uint8Array(buffer)], {
					type: e.target.filetype
				});

				let url = window.URL.createObjectURL(videoBlob);

				video.src = url;
				video.play();
			});

			reader.addEventListener("progress", e => {
				console.log('progress: ', Math.round((e.loaded * 100) / e.total));
			});

			reader.filetype = file.type;
			reader.readAsArrayBuffer(file);
		} else {
			previewVideoContainer.style.display = null;
			previewVideoContainer.src = "";
		}
	});
</script>