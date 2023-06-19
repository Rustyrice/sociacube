<div id="friends" style="display: inline-block;vertical-align: top; width: 200px;background-color: #eee;">
	<?php

		$image = "images/user_male.jpg";
		if($FRIEND_ROW['gender'] == "Female")
		{
			$image = "images/user_female.jpg";
		}

		if(file_exists($FRIEND_ROW['profile_image']))
		{
			$image = $image_class->get_thumb_profile($FRIEND_ROW['profile_image']);
		}


	?>

	<a href="<?=ROOT?>profile/<?php echo $FRIEND_ROW['userid']; ?>" class="username">
 		<img id="friends_img" src="<?php echo ROOT . $image ?>">
		<br>
		<?php echo $FRIEND_ROW['first_name'] . " " . $FRIEND_ROW['last_name'] ?>
		<br>

		<?php

			$online = "Last seen: <br> Unknown";
			if($FRIEND_ROW['online'] > 0){
				$online = $FRIEND_ROW['online'];

				$current_time = time();
				$threshold = 60 * 2;//2 minutes

				if(($current_time - $online) < $threshold){
					$online = "<span style='color:green;'>Online</span>";
				}else{
					$online = "Last seen: <br>" . Time::get_time(date("Y-m-d H:i:s",$online));
				}
			}
		?>
	 <br>
 	</a>
 	<?php if(!is_invited($group_data['userid'],$FRIEND_ROW['userid'])):?>
 	<a href="<?=ROOT?>invite/<?=$group_data['userid']?>/<?=$FRIEND_ROW['userid']?>">
			<input id="post_button" type="button" value="Invite" style="font-size:11px;margin-right:30px;background-color: #1b9186;width:auto;">
		</a>
		<?php else: ?>
			<input id="post_button" type="button" value="Invited" style="font-size:11px;margin-right:30px;background-color: #D16F14;width:auto;cursor:initial;">
	<?php endif;?>
</div>
