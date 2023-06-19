<?php

include("classes/autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid']);

$USER = $user_data;

if (isset($URL[2])) {

	$profile = new Profile();
	$profile_data = $profile->get_profile($URL[2]);

	if (is_array($profile_data)) {
		$user_data = $profile_data[0];
	}
}


$Post = new Post();
$likes = false;

$ERROR = "";
if (isset($URL[2]) && isset($URL[1])) {

	$likes = $Post->get_likes($URL[2], $URL[1]);
} else {

	$ERROR = "No information post was found!";
}

$pageTitle = "People Who Liked | Sociacube";
?>

<style media="screen">
	<?php include 'css/main.css'; ?><?php include 'css/responsive.css'; ?>#search_box {

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

<?php include("header.php"); ?>

<!--cover area-->
<div style="width: 800px;margin:auto;min-height: 400px;">

	<!--below cover area-->
	<div style="display: flex;">

		<!--posts area-->
		<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

			<div style="border:solid thin #aaa; padding: 10px;background-color: white;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-radius: 10px;">

				<?php

				$User = new User();
				$image_class = new Image();

				if (is_array($likes)) {

					foreach ($likes as $row) {
						# code...
						$FRIEND_ROW = $User->get_user($row['userid']);
						include("user.php");
					}
				}

				?>

				<br style="clear: both;">
			</div>


		</div>
	</div>

</div>

</body>

</html>