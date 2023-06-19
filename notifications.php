<?php

include("classes/autoload.php");

$login = new Login();
$_SESSION['mybook_userid'] = isset($_SESSION['mybook_userid']) ? $_SESSION['mybook_userid'] : 0;

$user_data = $login->check_login($_SESSION['mybook_userid'], false);

$USER = $user_data;

if (isset($URL[1])) {

	$profile = new Profile();
	$profile_data = $profile->get_profile($URL[1]);

	if (is_array($profile_data)) {
		$user_data = $profile_data[0];
	}
}

$Post = new Post();
$User = new User();
$image_class = new Image();

$pageTitle = "Notifications | Sociacube";

?>
<?php include("header.php"); ?>

<!--cover area-->
<div style="width: 800px;margin:auto;min-height: 400px;position:relative;right: 10px;">

	<!--below cover area-->
	<div style="display: flex;">

		<!--posts area-->
		<div style="min-height: 400px;flex:2.5;padding: 20px;padding-right: 0px;">

			<div style="border:solid thin #aaa; padding: 10px;background-color: white;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">

				<?php

				$DB = new Database();
				$id = esc($_SESSION['mybook_userid']);
				$follow = array();

				//check content i follow
				$sql = "select * from content_i_follow where disabled = 0 && userid = '$id' limit 100";
				$i_follow = $DB->read($sql);
				if (is_array($i_follow)) {
					$follow = array_column($i_follow, "contentid");
				}

				if (count($follow) > 0) {

					//$str = "'" . implode("','",$follow) . "'";
					$query = "select * from notifications where (userid != '$id' && content_owner = '$id') order by id desc limit 30";
					//$query = "select * from notifications where (userid != '$id' && content_owner = '$id') || (userid != '$id') order by id desc limit 30";
					//$query = "select * from notifications where (userid != '$id' && content_owner = '$id') || (contentid in ($str) && userid != '$id') order by id desc limit 30";
				} else {

					$query = "select * from notifications where userid != '$id' && content_owner = '$id' order by id desc limit 30";
				}

				$data = $DB->read($query);

				?>

				<a href="<?= ROOT ?>notifications?mark_all=true">
					<?php
					if (is_array($data)) {
						echo "<input id='post_button' type='button' value='Mark all as read' style='margin-right:10px;background-color:#405d9b;width:auto;'>";
					}
					?>
				</a>

				<br style="clear:both;">

				<?php if (is_array($data)) : ?>

					<?php foreach ($data as $notif_row) :

						if (isset($_GET['mark_all'])) {
							notification_seen($notif_row['id']);
						}

						include("single_notification.php");
					endforeach; ?>
				<?php else : ?>
					<div style="text-align:center;font-size:17px;">Nothing to see here — yet</div><br>
					<div style="text-align:center;font-size:12px;width:300px;margin:auto;color:gray;">When someone mentions you, you’ll be notified here.</div><br>
				<?php endif; ?>

			</div>


		</div>
	</div>

</div>

</body>

</html>