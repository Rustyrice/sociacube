	<div id="post_bar_home">
		<div>

			<?php

			$image = "images/user_male.jpg";
			if ($ROW_USER['gender'] == "Female") {
				$image = "images/user_female.jpg";
			} else
				if ($ROW_USER['type'] == "group") {
				$image = $image_class->get_thumb_profile("images/image_cover.jpg");
			}

			if (file_exists($ROW_USER['profile_image'])) {
				$image = $image_class->get_thumb_profile($ROW_USER['profile_image']);
			} else
				if ($ROW_USER['type'] == "group" && file_exists($ROW_USER['cover_image'])) {
				$image = $image_class->get_thumb_profile($ROW_USER['cover_image']);
			}

			?>

			<img src="<?php echo ROOT . $image ?>" style="width: 75px;margin-right: 4px;border-radius: 50%;">
		</div>
		<div style="width: 100%;">
			<div style="font-weight: bold;color: #405d9b;width: 100%;">
				<?php
				echo "<a href='" . ROOT . "profile/$ROW_USER[tag_name]' class='username' style='font-size:14px;'>";
				echo htmlspecialchars($ROW_USER['first_name']) . " " . htmlspecialchars($ROW_USER['last_name']);
				echo "</a>";

				if ($ROW['is_profile_image']) {
					$pronoun = "his";
					if ($ROW_USER['gender'] == "Female") {
						$pronoun = "her";
					}
					echo "<span style='font-weight:normal;color:#aaa;'> updated $pronoun profile image</span>";
				}

				if ($ROW['is_cover_image']) {
					$pronoun = "his";
					if ($ROW_USER['gender'] == "Female") {
						$pronoun = "her";
					} else
						if ($ROW_USER['type'] == "group") {
						$pronoun = "their";
					}

					echo "<span style='font-weight:normal;color:#aaa;'> updated $pronoun cover image</span>";
				}


				?>

			</div>

			<?php echo check_tags($ROW['post']) ?>

			<br><br>

			<?php

			if (file_exists($ROW['image'])) {

				$ext = pathinfo($ROW['image'], PATHINFO_EXTENSION);
				$ext = strtolower($ext);

				if ($ext == "jpeg" || $ext == "jpg") {

					$post_image = $image_class->get_thumb_post($ROW['image']);

					echo '<a href="' . ROOT . 'image_view/' . $ROW['postid'] . '">';
					echo "<img src='" . ROOT . "$post_image' style='width:80%;border-radius:10px;' />";
					echo "</a>";
				} elseif ($ext == "mp4") {

					echo "<video controls loop autoplay muted style='width:100%;border-radius:10px;max-height:500px;outline:none;' >
							<source src='" . ROOT . "$ROW[image]' type='video/mp4' >
						</video>";
				}
			}

			?>

			<br><br>
			<?php
			$like_color = "#626a70cf";
			$i_liked = false;

			if (isset($_SESSION['mybook_userid'])) {

				$DB = new Database();

				$sql = "select likes from likes where type='post' && contentid = '$ROW[postid]' limit 1";
				$result = $DB->read($sql);
				if (is_array($result)) {

					$likes = json_decode($result[0]['likes'], true);

					$user_ids = array_column($likes, "userid");

					if (in_array($_SESSION['mybook_userid'], $user_ids)) {
						$i_liked = true;
					}
				}
			}

			if ($i_liked) {
				$like_color = "#1877f2";
			}
			?>
			<!--like button-->
			<a onclick="like_post(event)" href="<?= ROOT ?>like/post/<?php echo $ROW['postid'] ?>" style="text-decoration:none;float:left;position:relative;top:2px;">
				<svg id="icon_like" fill="<?= $Like_color ?>" width="22" height="22" viewBox="0 0 24 24">
					<path d="M21.216 8h-2.216v-1.75l1-3.095v-3.155h-5.246c-2.158 6.369-4.252 9.992-6.754 10v-1h-8v13h8v-1h2l2.507 2h8.461l3.032-2.926v-10.261l-2.784-1.813zm.784 11.225l-1.839 1.775h-6.954l-2.507-2h-2.7v-7c3.781 0 6.727-5.674 8.189-10h1.811v.791l-1 3.095v4.114h3.623l1.377.897v8.328z" />
				</svg>
			</a>

			<!--comment button-->
			<a href="<?= ROOT ?>single_post/<?php echo $ROW['postid'] ?>" style="text-decoration:none;float:left;margin-left:15px;">
				<svg id="icon_comment" width="25" height="25" fill-rule="evenodd" clip-rule="evenodd">
					<path d="M12 1c-6.338 0-12 4.226-12 10.007 0 2.05.739 4.063 2.047 5.625l-1.993 6.368 6.946-3c1.705.439 3.334.641 4.864.641 7.174 0 12.136-4.439 12.136-9.634 0-5.812-5.701-10.007-12-10.007zm0 1c6.065 0 11 4.041 11 9.007 0 4.922-4.787 8.634-11.136 8.634-1.881 0-3.401-.299-4.946-.695l-5.258 2.271 1.505-4.808c-1.308-1.564-2.165-3.128-2.165-5.402 0-4.966 4.935-9.007 11-9.007zm-5 7.5c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5zm5 0c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5zm5 0c.828 0 1.5.672 1.5 1.5s-.672 1.5-1.5 1.5-1.5-.672-1.5-1.5.672-1.5 1.5-1.5z" />
				</svg>
			</a>
			<br><br>
			<span style="color: #999;font-size:11px;">

				<?php echo Time::get_time($ROW['date']); ?>

			</span>

			<span style="color: #999;float:right;margin-top:-25px;padding:5px;">

				<?php

				$post = new Post();

				if (i_own_content($ROW)) {
					// edit and delete button
					echo "
					<a href='" . ROOT . "edit/$ROW[postid]' style='text-decoration:none;margin:10px;'>
						<svg id='icon_edit' width='21' height='21' viewBox='0 0 24 24'><path d='M18.363 8.464l1.433 1.431-12.67 12.669-7.125 1.436 1.439-7.127 12.665-12.668 1.431 1.431-12.255 12.224-.726 3.584 3.584-.723 12.224-12.257zm-.056-8.464l-2.815 2.817 5.691 5.692 2.817-2.821-5.693-5.688zm-12.318 18.718l11.313-11.316-.705-.707-11.313 11.314.705.709z'/></svg>
					</a>

					<a href='" . ROOT . "delete/$ROW[postid]' style='text-decoration:none;'>
						<svg id='icon_delete' width='21' height='21' viewBox='0 0 24 24'><path d='M9 19c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5-17v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712zm-3 4v16h-14v-16h-2v18h18v-18h-2z'/></svg>
					</a>";
				}

				?>

			</span>

			<?php

			$i_liked = false;

			if (isset($_SESSION['mybook_userid'])) {

				$DB = new Database();

				$sql = "select likes from likes where type='post' && contentid = '$ROW[postid]' limit 1";
				$result = $DB->read($sql);
				if (is_array($result)) {

					$likes = json_decode($result[0]['likes'], true);

					$user_ids = array_column($likes, "userid");

					if (in_array($_SESSION['mybook_userid'], $user_ids)) {
						$i_liked = true;
					}
				}
			}

			echo "<a id='info_$ROW[postid]' href='" . ROOT . "likes/post/$ROW[postid]' class='username' style='text-align:left;color:gray;font-size:12px;'>";

			if ($ROW['likes'] > 0) {

				echo "<br/>";

				if ($ROW['likes'] == 1) {

					if ($i_liked) {
						echo "<div>You liked this post </div>";
					} else {
						echo "<div> 1 person liked this post </div>";
					}
				} else {

					if ($i_liked) {

						$text = "others";
						if ($ROW['likes'] - 1 == 1) {
							$text = "other";
						}
						echo "<div> You and " . ($ROW['likes'] - 1) . " $text liked this post </div>";
					} else {
						echo "<div>" . $ROW['likes'] . " others liked this post </div>";
					}
				}
			}

			echo "</a>";

			?>
		</div>
	</div>


	<script type="text/javascript">
		function ajax_send(data, element) {

			var ajax = new XMLHttpRequest();

			ajax.addEventListener('readystatechange', function() {

				if (ajax.readyState == 4 && ajax.status == 200) {

					response(ajax.responseText, element);
				}

			});

			data = JSON.stringify(data);

			ajax.open("post", "<?= ROOT ?>ajax.php", true);
			ajax.send(data);

		}

		function response(result, element) {

			if (result != "") {

				var obj = JSON.parse(result);
				if (typeof obj.action != 'undefined') {

					if (obj.action == 'like_post') {

						var likes = "";

						if (typeof obj.likes != 'undefined') {
							likes =
								(parseInt(obj.likes) > 0) ?
								'<svg fill="#1877f2" width="22" height="22" viewBox="0 0 24 24"><path d="M21.216 8h-2.216v-1.75l1-3.095v-3.155h-5.246c-2.158 6.369-4.252 9.992-6.754 10v-1h-8v13h8v-1h2l2.507 2h8.461l3.032-2.926v-10.261l-2.784-1.813zm.784 11.225l-1.839 1.775h-6.954l-2.507-2h-2.7v-7c3.781 0 6.727-5.674 8.189-10h1.811v.791l-1 3.095v4.114h3.623l1.377.897v8.328z"/></svg>' :
								'<svg fill="#626a70cf" width="22" height="22" viewBox="0 0 24 24"><path d="M21.216 8h-2.216v-1.75l1-3.095v-3.155h-5.246c-2.158 6.369-4.252 9.992-6.754 10v-1h-8v13h8v-1h2l2.507 2h8.461l3.032-2.926v-10.261l-2.784-1.813zm.784 11.225l-1.839 1.775h-6.954l-2.507-2h-2.7v-7c3.781 0 6.727-5.674 8.189-10h1.811v.791l-1 3.095v4.114h3.623l1.377.897v8.328z"/></svg>';
							element.innerHTML = likes;
						}

						if (typeof obj.info != 'undefined') {
							var info_element = document.getElementById(obj.id);
							if (info_element) {
								info_element.innerHTML = obj.info
							}
						}
					}
				}
			}
		}

		function like_post(e) {

			e.preventDefault();
			var link = e.currentTarget.href;

			var data = {};
			data.link = link;
			data.action = "like_post";
			ajax_send(data, e.currentTarget);
		}
	</script>