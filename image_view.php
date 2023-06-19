<?php

include("classes/autoload.php");

$login = new Login();
$user_data = $login->check_login($_SESSION['mybook_userid'], false);

$USER = $user_data;

if (isset($URL[1])) {

	$profile = new Profile();
	$profile_data = $profile->get_profile($URL[1]);

	if (is_array($profile_data)) {
		$user_data = $profile_data[0];
	}
}

$Message = new Messages();
$Post = new Post();
$ROW = false;

$ERROR = "";
if (isset($URL[1]) && $URL[1] == "msg") {

	$ROW = $Message->read_one($URL[2]);
	if (is_array($ROW)) {
		$ROW['image'] = $ROW['file'];
	}
} else
	if (isset($URL[1])) {

	$ROW = $Post->get_one_post($URL[1]);
} else {

	$ERROR = "No image was found!";
}

$pageTitle = "View Full Image | Sociacube";
?>

<?php include("header.php"); ?>

<style media="screen">
	<?php include 'css/main.css'; ?><?php include 'css/responsive.css'; ?>#search_box {

		width: 400px;
		height: 20px;
		border-radius: 20px;
		border: none;
		padding: 4px;
		font-size: 14px;
		background-image: url(../images/search.png);
		background-size: 20px;
		background-repeat: no-repeat;
		background-position: 375px;
		outline: none;
	}
</style>

<!--cover area-->
<div style="width: 800px;margin:auto;min-height: 400px;">

	<!--below cover area-->
	<div style="display: flex;">

		<!--posts area-->
		<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

			<div style="border:solid thin #aaa; padding: 10px;background-color: white;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

				<?php

				$user = new User();
				$image_class = new Image();

				if (is_array($ROW)) {

					echo "<img src='" . ROOT . "$ROW[image]' style='width:100%;' />";
					echo "
						<a href='" . ROOT . "single_post/$ROW[postid]'>
							<input id='post_button' style='width:200px;float:left;cursor:pointer;position:relative;top:5px;' type='button' value='Back to main post' />
						</a>
						";
				}

				?>
				<br style="clear: both;">
			</div>


		</div>
	</div>

</div>

</body>

</html>