<div style="display:flex;">

  <div id="navWrap" style="min-height: 400px;flex: 1;">

    <!--intro area-->
    <div id="intro_bar">

      Intro<br>

      <?php

      $post_class = new Post();
      $user_class = new User();

      $followers_count = $post_class->get_likes($user_data['userid'], "user");
      $followers_str = "";
      if (is_array($followers_count)) {
        $followers_str = count($followers_count);
      }

      $following_count = $user_class->get_following($user_data['userid'], "user");
      $following_str = "";
      if (is_array($following_count)) {
        $following_str = count($following_count);
      }

      ?>

      <?php if ($followers_count) : ?>
        <br>
        <div>
          <svg fill="gray" width="18" height="18" viewBox="0 0 24 24">
            <path d="M8.213 16.984c.97-1.028 2.308-1.664 3.787-1.664s2.817.636 3.787 1.664l-3.787 4.016-3.787-4.016zm-1.747-1.854c1.417-1.502 3.373-2.431 5.534-2.431s4.118.929 5.534 2.431l2.33-2.472c-2.012-2.134-4.793-3.454-7.864-3.454s-5.852 1.32-7.864 3.455l2.33 2.471zm-4.078-4.325c2.46-2.609 5.859-4.222 9.612-4.222s7.152 1.613 9.612 4.222l2.388-2.533c-3.071-3.257-7.313-5.272-12-5.272s-8.929 2.015-12 5.272l2.388 2.533z" />
          </svg>&nbsp
          <a href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>/followers" class="username" style="font-size:12px;color:#405d9b;position:relative;bottom:5px;">Followed by <?= $followers_str ?> people</a>
        </div>
      <?php endif; ?>
      <?php if ($friends) : ?>
        <br>
        <div>
          <svg fill="gray" width="18" height="18" viewBox="0 0 24 24">
            <path d="M19.5 15c-2.483 0-4.5 2.015-4.5 4.5s2.017 4.5 4.5 4.5 4.5-2.015 4.5-4.5-2.017-4.5-4.5-4.5zm2.5 5h-2v2h-1v-2h-2v-1h2v-2h1v2h2v1zm-7.18 4h-14.815l-.005-1.241c0-2.52.199-3.975 3.178-4.663 3.365-.777 6.688-1.473 5.09-4.418-4.733-8.729-1.35-13.678 3.732-13.678 6.751 0 7.506 7.595 3.64 13.679-1.292 2.031-2.64 3.63-2.64 5.821 0 1.747.696 3.331 1.82 4.5z" />
          </svg>&nbsp
          <a href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>/following" class="username" style="font-size:12px;color:#405d9b;position:relative;bottom:5px;">Follows <?= $following_str ?> people</a>
        </div>
      <?php endif; ?>
    </div>

    <!--about area-->
    <div id="about_bar">

      <?php
      if (i_own_content($user_data)) {

        echo "About <a href='" . ROOT . "profile/$user_data[tag_name]/settings' class='text-hover-underline'>Edit</a><br>";
      } else {

        echo "About <a href='" . ROOT . "profile/$user_data[tag_name]/about' class='text-hover-underline'>See More</a><br>";
      }

      $settings_class = new Settings();

      $settings = $settings_class->get_settings($user_data['userid']);

      if (is_array($settings)) {

        echo "<br>
              <div style='border:none;width:auto;font-size:12px;color:black;' name='about'>" . htmlspecialchars($settings['about']) . "</div>
            ";
      }

      ?>

    </div>

    <!--friends area-->
    <div id="friends_bar">

      Following <a href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>/following" class="text-hover-underline">See All</a><br>

      <?php

      if ($friends) {

        foreach ($friends as $friend) {

          $FRIEND_ROW = $user_class->get_user($friend['userid']);
          include("user.php");
        }
      } else
        if (i_own_content($user_data)) {

        echo "<br>";
        echo "<div style='font-size:11px;color:gray;text-align:center;'>You are currently not following anyone. You can search for friends using the <a href='" . ROOT . "search?find=' class='text-hover-underline' style='font-size:11px;float:none;color:none;'>search bar</a>.</div>";
      }

      ?>

    </div>

    <!--photos area-->
    <div id="photos_bar">

      Photos <a href="<?= ROOT ?>profile/<?php echo $user_data['tag_name'] ?>/photos" class="text-hover-underline">See All Photos</a><br>

      <?php

      $DB = new Database();
      $sql = "select image,postid from posts where has_image = 1 && userid = $user_data[userid] && owner = 0 order by id desc limit 9";
      $images = $DB->read($sql);

      $image_class = new Image();

      if (is_array($images)) {

        foreach ($images as $image_row) {

          echo "<a href='" . ROOT . "single_post/$image_row[postid]' class='img-hover-darken'>";
          echo "<img src='" . ROOT . $image_class->get_thumb_post($image_row['image']) . "' style='flex:1;width:80px;margin:3px;' />";
          echo "</a>";
        }
      } else
        if (i_own_content($user_data)) {

        echo "<br>";
        echo "<div style='font-size:11px;color:gray;text-align:center;'>No photos to show. You can share images with your friends using the textbox.</div>";
      }
      ?>
    </div>
    <br>

  </div>

  <!--posts area-->
  <div class="textbox_area">

    <?php if ($user_data['userid'] == $_SESSION['mybook_userid']) : ?>
      <div id="textarea" style="padding: 10px;background-color: white;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);border-radius: 10px;">

        <form method="post" enctype="multipart/form-data" name="form01">
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

    <?php endif; ?>

    <!--posts-->
    <?php if (!($user_data['userid'] == $_SESSION['mybook_userid'])) : ?>
      <div id="post_bar" style="margin-top:0px;">

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
    <?php else : ?>
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