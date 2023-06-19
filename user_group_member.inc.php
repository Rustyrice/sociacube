<div id="friends" style="display: inline-block;vertical-align: top; width: 200px;margin: 5px;">
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
	</a>
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
		<span style="color: grey;font-size: 11px;font-weight: normal;"><?php echo $online ?></span>
 		 <br>
		<span style="display: inline-block;padding:-5px;"><?=$member['role']?></span>

		<?php if(group_access($_SESSION['mybook_userid'],$group_data,'admin')):?>
			<br style="clear: both;">
			<a href="<?=ROOT?>group/<?=$group_data['userid']?>/members?remove=<?=$FRIEND_ROW['userid']?>">
				<input id="post_button" type="button" value="Remove" style="float:right;background-color:#916e1b;width:auto;font-size:12px;">
			</a>
			<a href="<?=ROOT?>group/<?=$group_data['userid']?>/members?edit_access=<?=$FRIEND_ROW['userid']?>">
				<input id="post_button" type="button" value="Edit Access" style="float:left;margin-right:10px;background-color:#1b9186;width:auto;font-size:12px;">
			</a>
	 	<?php endif;?>
</div>
