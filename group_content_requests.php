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

	<?php if(group_access($_SESSION['mybook_userid'],$group_data,'moderator')):?>
	<?php

		$image_class = new Image();
		//$post_class = new Post();
		$user_class = new User();

		$requests = $group->get_requests($group_data['userid']);

		if(is_array($requests)){

			foreach ($requests as $request) {
				# code...
				$FRIEND_ROW = $user_class->get_user($request['userid']);
				include("user_group_request.inc.php");
			}

		}else{

			echo "<div style='font-size:14px;color:gray;'>No pending requests</div>";
		}


	?>
<?php else: ?>
	<div style="font-size:14px;color:gray;">You do not have access to this content. Join the group to access to this content</div>
<?php endif; ?>
	</div>
</div>
<br><br>
