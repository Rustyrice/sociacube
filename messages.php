<?php

include("classes/autoload.php");
$image_class = new Image();

$ERROR = "";

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

$msg_class = new Messages();

//new message//check if thread already exists
if (isset($URL[1]) && $URL[1] == "new") {

	$old_thread = $msg_class->read($URL[2]);
	if (is_array($old_thread)) {

		//redirect the user
		header("Location: " . ROOT . "messages/read/" . $URL[2]);
		die;
	}
}

//if a message was posted
if ($ERROR == "" && $_SERVER['REQUEST_METHOD'] == "POST") {

	$user_class = new User();
	if (is_array($user_class->get_user($URL[2]))) {

		$ERROR = $msg_class->send($_POST, $_FILES, $URL[2]);

		header("Location: " . ROOT . "messages/read/" . $URL[2]);
		die;
	} else {
		$ERROR = "The requested user could not be found!";
	}
}

$pageTitle = "Messages | Sociacube";
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
		background-image: url(../../images/search.png);
		background-size: 20px;
		background-repeat: no-repeat;
		background-position: 370px;
		outline: none;
	}
</style>
<!--cover area-->
<div style="width: 800px;margin:0 auto;min-height: 400px;position:relative;right: 10px;">

	<!--below cover area-->
	<div style="display: flex;">

		<!--posts area-->
		<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

			<div style="border:solid thin #aaa; padding: 10px;background-color: white;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

				<form method="post" enctype="multipart/form-data">

					<?php

					if ($ERROR != "") {

						echo $ERROR;
					} else {

						if (isset($URL[1]) && $URL[1] == "read") {

							echo "<div style='height:85px;border-bottom:solid thin #ddd;'>";
							if (isset($URL[2]) && is_numeric($URL[2])) {

								$data = $msg_class->read($URL[2]);

								$user = new User();
								$FRIEND_ROW = $user->get_user($URL[2]);

								include("user.php");

								echo "<a href='" . ROOT . "messages'>";
								echo '<input id="post_button" type="button" style="width:auto;cursor:pointer;margin:4px;font-size:12px;" value="All Messages">';
								echo "</a>";

								if (is_array($data)) {
									echo "<a href='" . ROOT . "delete/thread/" . $data[0]['msgid'] . "'>";
									echo '<input id="post_button" type="button" style="width:auto;cursor:pointer;background-color:#b76d40;margin:4px;font-size:12px;" value="Delete Thread">';
									echo "</a>";
								}

								echo "</div>";

								echo '
 		 										<div id="messages_holder" style="height:280px;overflow-y:scroll;">';
								$user = new User();

								if (is_array($data)) {
									foreach ($data as $MESSAGE) {
										# code...
										//show($MESSAGE);
										$ROW_USER = $user->get_user($MESSAGE['sender']);

										if (i_own_content($MESSAGE)) {

											include("message_right.php");
										} else {

											include("message_left.php");
										}
									}
								}

								echo '
		 										</div>';

								echo '
												<br>
												<div style="border:solid thin #aaa;background-color: white;border-radius:40px;height:40px;width:100%;">
													<form onsubmit="myFunction(event)">
 								 						<input id="message_text" name="message" placeholder="Type a message..." style="position:relative;bottom:5px;">
														<label for="inpFile"><img id="image_icon" src="../../images/image_icon.png" style="left:30px;top:2px;"></label>
														<input id="inpFile" type="file" name="file" style="display:none;" accept="image/*">
 								 						<input id="post_button" type="submit" value="Send" style="margin:5px;display:none;">
													</form>
							 					</div>

												<div class="image-preview" id="imagePreview">
													<img src="" class="image-preview__image">
												</div>

							 					';
							} else {

								echo "That user could not be found<br><br>";
							}
						} else
								 		if (isset($URL[1]) && $URL[1] == "new") {

							echo "<div>";
							if (isset($URL[2]) && is_numeric($URL[2])) {

								$user = new User();
								$FRIEND_ROW = $user->get_user($URL[2]);

								include("user.php");

								echo '
								<br><br>
								<div style="border:solid thin #aaa;background-color: white;border-radius:40px;height:40px;width:100%;">
									<form onsubmit="myFunction(event)">
										<input id="message_text" name="message" placeholder="Make your first move..." style="position:relative;bottom:5px;">
										<label for="inpFile"><img id="image_icon" src="../../images/image_icon.png" style="left:30px;top:2px;"></label>
										<input id="inpFile" type="file" name="file" style="display:none;" accept="image/*">
										<input id="post_button" type="submit" value="Send" style="margin:5px;display:none;">
									</form>
								</div>

								<div class="image-preview" id="imagePreview">
									<img src="" class="image-preview__image">
								</div>

								';
							} else {

								echo "That user could not be found<br><br>";
							}
						} else {

							$data = $msg_class->read_threads();
							$user = new User();
							$me = esc($_SESSION['mybook_userid']);

							if (is_array($data)) {
								foreach ($data as $MESSAGE) {
									# code...
									$myid = ($MESSAGE['sender'] == $me) ? $MESSAGE['receiver'] : $MESSAGE['sender'];

									$ROW_USER = $user->get_user($myid);

									include("thread.php");
								}
							} else {
								echo "<br>";
								echo "<div style='text-align:center;font-size:17px;'>Nothing to see here — yet</div><br>";
								echo "<div style='text-align:center;font-size:12px;width:300px;margin:auto;color:gray;'>When someone messages you, you’ll find it here.</div><br>";
							}
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
	function myFunction(e) {

		e.preventDefault();
		var message_text = ("message_text");
		if (e.keyCode == 13) {
			send_message(e);
		}
	}

	setTimeout(function() {
		messages_holder.scrollTo(0, messages_holder.scrollHeight);
		message_text.focus();
	});

	//preview image in textarea
	const inpFile = document.getElementById("inpFile");
	const previewContainer = document.getElementById("imagePreview");
	const previewImage = previewContainer.querySelector(".image-preview__image");

	inpFile.addEventListener("change", function() {
		const file = this.files[0];

		if (file) {
			const reader = new FileReader();

			previewContainer.style.display = "block";
			previewImage.style.display = "block";

			reader.addEventListener("load", function() {
				previewImage.setAttribute("src", this.result);
			});

			reader.readAsDataURL(file);
		} else {
			previewImage.style.display = null;
			previewImage.setAttribute("src", "");
		}
	});
</script>