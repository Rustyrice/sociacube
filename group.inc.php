<div id="friends" style="display:inline-block;width:240px;vertical-align:top;">
	<?php

	$image = $image_class->get_thumb_profile("images/image_cover.jpg");

	if (file_exists($FRIEND_ROW['cover_image'])) {
		$image = $image_class->get_thumb_profile($FRIEND_ROW['cover_image']);
	}


	?>

	<a href="<?= ROOT ?>group/<?php echo $FRIEND_ROW['userid']; ?>" class="username">
		<img id="friends_img" src="<?php echo ROOT . $image ?>">
		<br>
		<?php echo $FRIEND_ROW['first_name'] ?>
	</a>
	<br>
	<span style="font-size:10px;color:gray;"><?php echo $FRIEND_ROW['group_type'] ?> group</span>

</div>