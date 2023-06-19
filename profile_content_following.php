<style media="screen">
	#search_box {

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

<div style="min-height:400px;width:100%;background-color:white;text-align:center;position: relative;top:20px;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
	<div style="padding:20px;">

		<?php

		$image_class = new Image();
		$post_class = new Post();
		$user_class = new User();

		$following = $user_class->get_following($user_data['userid'], "user");

		$following_str = "";
		if (is_array($following)) {
			$following_str = count($following);
		}

		if ($following) {

			echo "<div style='float:left;font-size:11px;color:gray;'>Following $following_str people</div><br><br>";
		} else {
			echo "";
		}
		?>

		<?php

		if (is_array($following)) {

			foreach ($following as $follower) {
				// code...
				$FRIEND_ROW = $user_class->get_user($follower['userid']);
				include("user.php");
			}
		} else
    if (i_own_content($user_data)) {

			echo "<div style='font-size:14px;color:gray;'>You are currently not following anyone. You can search for friends using the <a href='" . ROOT . "search?find=' class='text-hover-underline' style='font-size:13px;float:none;color:none;'>search bar</a>.</div>";
		} else {

			echo "<div style='font-size:14px;color:gray;'>$user_data[first_name] is currently not following anyone</div>";
		}

		?>

	</div>
</div>
<br><br>