<div style="display:flex;">
  <?php if ($group_data['group_type'] == 'public' || group_access($_SESSION['mybook_userid'], $group_data, 'member')) : ?>

    <div style="min-height: 400px;flex: 1;">

      <!--about area-->
      <div id="about_bar">
        <?php
        if (group_access($_SESSION['mybook_userid'], $group_data, 'admin')) {

          echo "About <a href='" . ROOT . "group/$group_data[userid]/settings' class='text-hover-underline'>Edit</a><br>";
        } else {

          echo "About <a href='" . ROOT . "group/$group_data[userid]/about' class='text-hover-underline'>See More</a><br>";
        }

        $settings_class = new Settings();

        $settings = $settings_class->get_settings($group_data['userid']);

        if (is_array($settings)) {

          echo "<br>
              <div style='border:none;width:auto;font-size:11px;color:black;' name='about'>" . htmlspecialchars($settings['about']) . "</div>
            ";
        }

        ?>
      </div>

      <!--members area-->
      <div id="friends_bar" style="max-height:550px;">

        Members <a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/members" class="text-hover-underline">See All</a><br>
        <?php

        $image_class = new Image();
        $user_class = new User();

        $members = $group->get_members($group_data['userid'], 10);

        if (is_array($members)) {

          foreach ($members as $member) {
            # code...
            $FRIEND_ROW = $user_class->get_user($member['userid']);
            include("user_group_member.inc.php");
          }
        }

        ?>
      </div>

      <!--photos area-->
      <div id="photos_bar">

        Recent Media <a href="<?= ROOT ?>group/<?php echo $group_data['userid'] ?>/photos" class="text-hover-underline">See All</a><br>
        <?php

        $DB = new Database();
        $sql = "select image,postid from posts where has_image = 1 && owner = $group_data[userid] order by id desc limit 9";
        $images = $DB->read($sql);

        $image_class = new Image();

        if (is_array($images)) {

          foreach ($images as $image_row) {
            # code...
            echo "<a href='" . ROOT . "single_post/$image_row[postid]' class='img-hover-darken'>";
            echo "<img src='" . ROOT . $image_class->get_thumb_post($image_row['image']) . "' style='flex:1;width:80px;margin:3px;' />";
            echo "</a>";
          }
        }

        ?>
      </div>
    </div>
  <?php endif; ?>

  <!--posts area-->
  <div style="flex: 1.8;padding: 20px;padding-right: 0px;">

    <?php if ($group_data['group_type'] == 'public' || group_access($_SESSION['mybook_userid'], $group_data, 'member')) : ?>
      <div style="padding: 10px;background-color: white;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-radius: 10px;">

        <form id="textbox_area" method="post" enctype="multipart/form-data">
          <?php $user_data = $login->check_login($_SESSION['mybook_userid']); ?>
          <textarea id="autoresizing" name="post" placeholder="What's on your mind, <?= $user_data['first_name'] ?>?"></textarea>

          <div class="image-preview" id="imagePreview">
            <img src="" class="image-preview__image">
          </div>

          <div class="video-preview" id="videoPreview">
            <video controls loop autoplay muted class="video-preview__video" id="video" src=""></video>
          </div>

          <fieldset name="image">
            <label for="inpFile"><img id="image_icon" src="images/image_icon.png" onerror='this.onerror = null; this.src="../images/image_icon.png"'></label>
            <input id="inpFile" type="file" name="file" style="display:none;" accept="image/*">
          </fieldset>
          <fieldset name="video">
            <label for="inpFile2"><img id="video_icon" src="images/video_icon.png" onerror='this.onerror = null; this.src="../images/video_icon.png"'></label>
            <input id="inpFile2" type="file" name="file" style="display:none;" accept="video/*">
          </fieldset>

          <input id="post_button" type="submit" value="Post" style="position:relative;top:20px;margin:5px;">
          <br>
        </form>
      </div>

    <?php else : ?>
      <div style="padding:10px;background-color:white;width:100%;position:relative;right:20px;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        <svg fill="gray" style="display:block;margin-left:auto;margin-right:auto;width:50%;" width="70" height="70" viewBox="0 0 24 24">
          <path d="M18 10v-4c0-3.313-2.687-6-6-6s-6 2.687-6 6v4h-3v14h18v-14h-3zm-5 7.723v2.277h-2v-2.277c-.595-.347-1-.984-1-1.723 0-1.104.896-2 2-2s2 .896 2 2c0 .738-.404 1.376-1 1.723zm-5-7.723v-4c0-2.206 1.794-4 4-4 2.205 0 4 1.794 4 4v4h-8z" />
        </svg>
        <h2 style="text-align:center;color:gray;">This group is private.</h2>
        <p style="text-align:center;color:gray;">Join this group to view or participate in discussions.</p>
      </div>
    <?php endif; ?>

    <?php if ($group_data['group_type'] == 'public' || group_access($_SESSION['mybook_userid'], $group_data, 'member')) : ?>
      <!--posts-->
      <div id="post_bar">

        <?php

        if (!$posts) {
          echo "<br><br><br>";
          echo "<div style='font-size:18px;color:gray;text-align:center;'>No posts available</div>";
          echo "<br><br><br>";
        } else {

          foreach ($posts as $ROW) {

            $user = new User();
            $ROW_USER = $user->get_user($ROW['userid']);

            include("post.php");
          }
        }

        //get current url
        $pg = pagination_link();
        ?>

      </div>
      <a href="<?php echo $pg['next_page'] ?>">
        <input id="post_button" type="button" value="Next Page" style="float:right;width:150px;position:relative;bottom:10px;">
      </a>
      <a href="<?php echo $pg['prev_page'] ?>">
        <input id="post_button" type="button" value="Prev Page" style="float:left;width:150px;position:relative;bottom:10px;">
      </a>
      <br>
    <?php endif; ?>

  </div>
</div>

<script type="text/javascript">
  //resize textarea
  textarea = document.querySelector("#autoresizing");
  textarea.addEventListener('input', autoResize, false);

  function autoResize() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
  }

  //preview image in textarea
  const inpFile = document.getElementById("inpFile");
  const previewContainer = document.getElementById("imagePreview");
  const previewImage = previewContainer.querySelector(".image-preview__image");

  inpFile.addEventListener("change", e => {
    const file = e.target.files[0];

    if (file) {

      e.target.form.video.disabled = true;

      const reader = new FileReader();

      previewContainer.style.display = "block";
      previewImage.style.display = "block";

      reader.addEventListener("load", e => {
        previewImage.setAttribute("src", e.target.result);
      });

      reader.readAsDataURL(file);
    } else {
      previewImage.style.display = null;
      previewImage.setAttribute("src", "");
    }
  });

  // preview video in textarea
  const inpFile2 = document.getElementById('inpFile2');
  const video = document.getElementById('video');
  const previewVideoContainer = document.getElementById("videoPreview");
  const previewVideo = previewVideoContainer.querySelector(".video-preview__video");
  const videoSource = document.createElement('source');

  inpFile2.addEventListener('change', e => {
    const file = e.target.files[0];

    if (file) {

      e.target.form.image.disabled = true;

      let reader = new FileReader();

      previewVideoContainer.style.display = "block";

      reader.addEventListener("load", e => {
        let buffer = e.target.result;

        let videoBlob = new Blob([new Uint8Array(buffer)], {
          type: e.target.filetype
        });

        let url = window.URL.createObjectURL(videoBlob);

        video.src = url;
        video.play();
      });

      reader.addEventListener("progress", e => {
        console.log('progress: ', Math.round((e.loaded * 100) / e.total));
      });

      reader.filetype = file.type;
      reader.readAsArrayBuffer(file);
    } else {
      previewVideoContainer.style.display = null;
      previewVideoContainer.src = "";
    }
  });
</script>