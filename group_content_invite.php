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
	<div style="padding: 20px;">
	<?php if(group_access($_SESSION['mybook_userid'],$group_data,'member')):?>
	<?php

		$image_class = new Image();
		$post_class = new Post();
		$user_class = new User();

		$followers = $group->get_invites($group_data['userid'],$USER['userid'],"user");

		if(is_array($followers)){

			foreach ($followers as $follower) {
				# code...
				$FRIEND_ROW = $user_class->get_user($follower['userid']);
				include("user_group_invite.php");
			}

		}else{

			echo "<div style='font-size:14px;color:gray;'>Hmmm... seems like you have no friends to invite. Go find some before inviting them to your group!</div>";
		}


	?>
	<?php else: ?>
		<div style="font-size:14px;color:gray;">You must be a member to invite others.</div>
	<?php endif; ?>
	</div>
</div>
<br><br>
