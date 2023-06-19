<div id="message_left" style="background-color: #fff;">
	<div>

		<?php

			$image = "images/user_male.jpg";
			if($ROW_USER['gender'] == "Female")
			{
				$image = "images/user_female.jpg";
			}

			if(file_exists($ROW_USER['profile_image']))
			{
				$image = $image_class->get_thumb_profile($ROW_USER['profile_image']);
			}

		?>

		<img src="<?php echo ROOT . $image ?>" style="width: 50px;margin-left: 10px;margin-top: 5px;border-radius: 50%;">
	</div>
	<div style="width: 100%;">
		<div style="font-weight: bold;color: #405d9b;width: 100%;position:relative;left:20px;">
			<?php
				echo "<a href='".ROOT."profile/$ROW_USER[tag_name]' class='username'>";
				echo htmlspecialchars($ROW_USER['first_name']) . " " . htmlspecialchars($ROW_USER['last_name']);
				echo "</a>";


			?>
		</div>

		<?php

		echo "<div style='position:relative;left:20px;height:0px;'>";
		echo check_tags($MESSAGE['message']);
		echo "</div>";

		?>
		<br><br>

		<?php

			if(file_exists($MESSAGE['file']))
			{

				$post_image = ROOT . $image_class->get_thumb_post($MESSAGE['file']);

				echo "<a href='".ROOT."image_view/msg/$MESSAGE[id]'><img src='$post_image' style='width:40%;border-radius:20px;position:relative;left:20px;' /></a>";
				echo "<br>";
			}

		?>

	<span style="color: #999;font-size:10px;position:relative;left:20px;">

		<?php echo Time::get_time($MESSAGE['date']) ?>

	</span>

	<span style="color: #999;float:right">

		<?php

			$post = new Post();

				echo "<a href='".ROOT."delete/msg/$MESSAGE[id]' >";
	 			echo '<svg id="icon_delete" style="position:relative;right:10px;" width="15" height="15" viewBox="0 0 24 24"><path d="M9 19c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5-17v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712zm-3 4v16h-14v-16h-2v18h18v-18h-2z"/></svg>';
				echo "</a>";

		?>

	</span>

	</div>
</div>
