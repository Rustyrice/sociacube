<div id="friends" style="display:inline-block;width:240px;vertical-align:top;">
	<?php

	$image = "images/user_male.jpg";
	if ($FRIEND_ROW['gender'] == "Female") {
		$image = "images/user_female.jpg";
	}

	if (file_exists($FRIEND_ROW['profile_image'])) {
		$image = $image_class->get_thumb_profile($FRIEND_ROW['profile_image']);
	}


	?>

	<a href="<?= ROOT ?><?php echo $FRIEND_ROW['type']; ?>/<?php echo $FRIEND_ROW['tag_name']; ?>" class="username">
		<img id="friends_img" src="<?php echo ROOT . $image ?>">
		<br>
		<?php echo $FRIEND_ROW['first_name'] . " " . $FRIEND_ROW['last_name'] ?>
	</a>
	<br>
	<span style="color:black;font-size:11px;font-weight:normal;">@<?php echo $FRIEND_ROW['tag_name']; ?></span>

	<?php

	$online = "Last seen: <br> Unknown";
	if ($FRIEND_ROW['online'] > 0) {

		$online = $FRIEND_ROW['online'];
		$current_time = time();
		$threshold = 60 * 2;

		if (($current_time - $online) < $threshold) {
			$online = "<span style='color:green;'>Online</span>";
		} else {
			$online = "Last seen: <br>" . Time::get_time(date("Y-m-d H:i:s", $online));
		}
	}
	?>
	<br>
	<span style="color:grey;font-size:11px;font-weight:normal;"><?php echo $online ?></span>

</div>