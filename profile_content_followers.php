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

	<?php

		$image_class = new Image();
		$post_class = new Post();
		$user_class = new User();

		$followers = $post_class->get_likes($user_data['userid'],"user");

		$followers_str = "";
		if(is_array($followers)){
			$followers_str = count($followers);
		}

		if($followers){

			echo "<div style='float:left;font-size:11px;color:gray;'>Followed by $followers_str people</div><br><br>";
		}else{
			echo "";
		}

	?>
	<?php

		if(is_array($followers)){

			foreach ($followers as $follower) {
				# code...
				$FRIEND_ROW = $user_class->get_user($follower['userid']);
				include("user.php");
			}

		}else
		if(i_own_content($user_data)){

			echo "<div style='font-size:14px;color:gray;'>You do not have any followers</div>";
		}else{

			echo "<div style='font-size:14px;color:gray;'>$user_data[first_name] does not have any followers</div>";
		}


	?>

	</div>
</div>
<br><br>
