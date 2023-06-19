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
	<div style="padding:20px;max-width:450px;display:inline-block;">

		<form method="post" enctype="multipart/form-data">

			<?php

			if (is_array($settings)) {

				// echo "<p style='font-size:11px;color:grey;float:left;margin:-20px;padding:30px;'>Username</p>";
				// echo "<input type='text' id='textbox' name='tag_name' value='" . htmlspecialchars($settings['tag_name']) . "' placeholder='Username'/>";

				echo "<p style='font-size:11px;color:grey;float:left;margin:-20px;padding:30px;'>First Name</p>";
				echo "<input maxlength='15' type='text' id='textbox' name='first_name' value='" . test_input($settings['first_name']) . "' required/>";

				echo "<p style='font-size:11px;color:grey;float:left;margin:-20px;padding:30px;'>Last Name</p>";
				echo "<input maxlength='15' type='text' id='textbox' name='last_name' value='" . test_input($settings['last_name']) . "' required/>";

				echo "<p style='font-size:11px;color:grey;float:left;margin:-20px;padding:30px;'>New Password</p>";
				// echo "<input type='hidden' id='textbox' name='password' value='" . htmlspecialchars($settings['password']) . "'/>";
				echo "<input type='password' id='textbox' name='password' value='" . htmlspecialchars($settings['password']) . "'/>";

				echo "<p style='font-size:11px;color:grey;float:left;margin:-20px;padding:30px;'>Retype New Password</p>";
				// echo "<input type='hidden' id='textbox' name='password2' value='" . htmlspecialchars($settings['password']) . "'/>";
				echo "<input type='password' id='textbox' name='password2' value='" . htmlspecialchars($settings['password']) . "'/>";

				echo "<br>";

				echo "<br>About Me:<br>
				<textarea maxlength='50' id='textbox' style='height:200px;' name='about' placeholder='Tell us a bit about yourself...'>" . htmlspecialchars($settings['about']) . "</textarea>
				";

				echo '<input style="position:relative;left:20px;" id="post_button" type="submit" value="Save">';
			}

			?>
		</form>
	</div>
</div>
<br><br>

<script>
	function maxLength(el) {
		if (!('maxLength' in el)) {
			var max = el.attributes.maxLength.value;
			el.onkeypress = function() {
				if (this.value.length >= max) return false;
			};
		}
	}

	maxLength(document.getElementById("textbox"));
</script>