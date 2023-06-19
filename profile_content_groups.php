<style media="screen">

#search_box{

	width: 400px;
	height: 20px;
	border-radius: 20px;
	border: none;
	padding: 4px;
	font-size: 14px;
	background-image: url(../../images/search.png);
	background-size: 20px;
	background-repeat: no-repeat;
	background-position: 375px;
	outline: none;
}

</style>

<div style="min-height: 400px;width:100%;background-color: white;text-align: center;position: relative;top:20px;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

	<br>
	<a href="<?=ROOT?>create_group">
		<input id="post_button" type="button" value="Create Group" style="margin-right:10px;background-color:#405d9b;width:auto;float:none;">
	</a>

	<div style="padding: 20px;">
	<?php

		$image_class = new Image();
		$group_class = new Group();
		$user_class = new User();

		$groups = $group_class->get_my_groups($user_data['userid']);

		if(is_array($groups)){

			foreach ($groups as $group) {
				# code...
				$FRIEND_ROW = $user_class->get_user($group['userid']);
				include("group.inc.php");
			}

		}else{

			echo "<div style='font-size:14px;color:gray;'>Seems like you are currently not in a group. Create a group and invite your friends!</div>";
		}


	?>

	</div>
</div>
<br><br>
