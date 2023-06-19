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

<div style="min-height:400px;width:100%;background-color:white;text-align:center;position: relative;top:20px;border-radius:10px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
  <div style="padding:20px;max-width:450px;display:inline-block;">
    <form method="post" enctype="multipart/form-data">

      <?php

          $settings_class = new Settings();

          $settings = $settings_class->get_settings($group_data['userid']);

          if(is_array($settings)){

            echo "<br><div style='font-size:24px;'>About:</div><br>
              <div id='textbox' style='height:200px;border:none;width:auto;' name='about'>".htmlspecialchars($settings['about'])."</div>";

        }

      ?>
    </form>
  </div>
</div>
<br><br>
